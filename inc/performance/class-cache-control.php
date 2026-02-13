<?php
/**
 * HTTP cache headers: Cache-Control, Expires, ETag, Vary.
 * Per-content durations, no-cache for logged-in/preview/search, cache busting for assets.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Cache_Control
 */
class CCS_Cache_Control {

	/**
	 * Cache duration in seconds: static pages and blog posts.
	 *
	 * @var int
	 */
	const DURATION_PAGE_POST = 3600; // 1 hour.

	/**
	 * Cache duration in seconds: services and locations.
	 *
	 * @var int
	 */
	const DURATION_SERVICE_LOCATION = 21600; // 6 hours.

	/**
	 * Cache duration in seconds: archives and homepage.
	 *
	 * @var int
	 */
	const DURATION_ARCHIVE_HOMEPAGE = 1800; // 30 minutes.

	/**
	 * Hook into WordPress.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'send_cache_headers' ), 1 );
		add_filter( 'style_loader_src', array( $this, 'add_version_to_theme_asset' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'add_version_to_theme_asset' ), 10, 2 );
	}

	/**
	 * Send Cache-Control, Expires, ETag, and Vary headers (or no-cache when appropriate).
	 */
	public function send_cache_headers() {
		if ( $this->should_skip_cache() ) {
			$this->send_no_cache_headers();
			return;
		}

		$duration = $this->get_cache_duration();
		if ( $duration <= 0 ) {
			return;
		}

		$this->send_cache_headers_for_duration( $duration );
		$this->send_etag();
		$this->send_vary_headers();
	}

	/**
	 * Whether the current request should not be cached.
	 *
	 * @return bool
	 */
	private function should_skip_cache() {
		if ( is_user_logged_in() ) {
			return true;
		}
		if ( is_admin() ) {
			return true;
		}
		if ( is_preview() ) {
			return true;
		}
		if ( is_search() ) {
			return true;
		}
		if ( is_feed() || wp_doing_ajax() ) {
			return true;
		}
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}
		return false;
	}

	/**
	 * Get cache duration in seconds for the current request context.
	 *
	 * @return int Seconds; 0 to skip sending cache headers.
	 */
	private function get_cache_duration() {
		if ( is_singular( 'service' ) || is_singular( 'location' ) ) {
			return self::DURATION_SERVICE_LOCATION;
		}
		if ( is_singular() ) {
			// Static pages and blog posts.
			return self::DURATION_PAGE_POST;
		}
		if ( is_home() || is_front_page() ) {
			return self::DURATION_ARCHIVE_HOMEPAGE;
		}
		if ( is_archive() || is_post_type_archive() ) {
			return self::DURATION_ARCHIVE_HOMEPAGE;
		}

		// Default: 1 hour for other front-end (e.g. 404, generic).
		return self::DURATION_PAGE_POST;
	}

	/**
	 * Send no-cache headers (logged-in, admin, preview, search).
	 */
	private function send_no_cache_headers() {
		if ( headers_sent() ) {
			return;
		}
		header( 'Cache-Control: no-cache, no-store, must-revalidate', true );
		header( 'Pragma: no-cache', true );
		header( 'Expires: 0', true );
	}

	/**
	 * Send Cache-Control and Expires for the given duration.
	 *
	 * @param int $duration Seconds.
	 */
	private function send_cache_headers_for_duration( $duration ) {
		if ( headers_sent() ) {
			return;
		}
		header( 'Cache-Control: public, max-age=' . (int) $duration, true );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $duration ) . ' GMT', true );
	}

	/**
	 * Send ETag for revalidation (weak ETag based on resource identity and last modified).
	 */
	private function send_etag() {
		if ( headers_sent() ) {
			return;
		}
		$etag = $this->get_etag();
		if ( $etag !== '' ) {
			header( 'ETag: ' . $etag, true );
		}
	}

	/**
	 * Build a weak ETag for the current request (CDN- and browser-friendly revalidation).
	 *
	 * @return string Empty string if no ETag should be sent.
	 */
	private function get_etag() {
		if ( is_singular() && get_queried_object_id() ) {
			$id = get_queried_object_id();
			$post = get_post( $id );
			if ( $post instanceof WP_Post ) {
				$modified = $post->post_modified_gmt ?? '';
				return 'W/"' . (int) $id . '-' . md5( $modified ) . '"';
			}
		}
		if ( is_front_page() || is_home() ) {
			// Use option that changes when posts/pages change.
			$seed = get_option( 'posts_per_page' ) . get_option( 'page_on_front' );
			return 'W/"home-' . md5( $seed ) . '"';
		}
		if ( is_archive() || is_post_type_archive() ) {
			$seed = get_queried_object_id() . ( get_query_var( 'paged' ) ?: 1 );
			return 'W/"arch-' . md5( (string) $seed ) . '"';
		}
		return '';
	}

	/**
	 * Send Vary headers for CDN and proxy compatibility (e.g. Cloudflare).
	 */
	private function send_vary_headers() {
		if ( headers_sent() ) {
			return;
		}
		// Accept-Encoding: caches may store compressed vs uncompressed variants.
		header( 'Vary: Accept-Encoding', true );
	}

	/**
	 * Add THEME_VERSION as query arg to theme CSS/JS so caches invalidate on deploy.
	 *
	 * @param string $src    Full URL of the asset.
	 * @param string $handle Handle name.
	 * @return string URL with version query string if theme asset.
	 */
	public function add_version_to_theme_asset( $src, $handle ) {
		if ( ! defined( 'THEME_URL' ) || ! defined( 'THEME_VERSION' ) ) {
			return $src;
		}
		$theme_base = preg_quote( THEME_URL, '#' );
		if ( ! preg_match( '#^' . $theme_base . '#i', $src ) ) {
			return $src;
		}
		$version = THEME_VERSION;
		return add_query_arg( 'ver', $version, $src );
	}
}
