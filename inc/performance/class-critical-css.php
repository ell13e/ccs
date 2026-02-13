<?php
/**
 * Critical CSS and asset optimization.
 *
 * Critical CSS:
 * - Serves critical CSS per template type from wp_options (option key: ccs_critical_css).
 * - Inlines first 14KB in <head> for fast first paint (HTTP/2 recommendation).
 * - Defers non-critical stylesheets with media="print" and onload="this.media='all'".
 * - Preconnects to Google Fonts and preloads the main font stylesheet.
 *
 * Asset optimization:
 * - Non-critical styles are deferred (filter ccs_deferred_style_handles to extend).
 * - Script defer is applied in theme-setup (ccs-navigation, ccs-form-handler, ccs-consultation-form).
 *
 * Regenerate stored CSS via WP-CLI: wp ccs regenerate-critical-css
 * For true above-the-fold extraction use Critical (https://github.com/addyosmani/critical)
 * or Penthouse, then import with: wp ccs regenerate-critical-css --template=page --from-file=path/to/critical.css
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Critical_CSS
 */
class CCS_Critical_CSS {

	/**
	 * Option key for stored critical CSS (array keyed by template type).
	 *
	 * @var string
	 */
	const OPTION_KEY = 'ccs_critical_css';

	/**
	 * Max bytes to inline (14KB – fits in first TCP window).
	 *
	 * @var int
	 */
	const CRITICAL_CSS_MAX_BYTES = 14 * 1024;

	/**
	 * Relative path to fallback critical CSS file (from theme root).
	 *
	 * @var string
	 */
	const CRITICAL_CSS_PATH = 'assets/css/critical.css';

	/**
	 * Style handles to load as deferred (non-blocking). Filter: ccs_deferred_style_handles.
	 *
	 * @var string[]
	 */
	private $deferred_handles;

	/**
	 * Hook into wp_head and style_loader_tag.
	 */
	public function __construct() {
		$this->deferred_handles = apply_filters( 'ccs_deferred_style_handles', array(
			'ccs-design-system',
			'ccs-components',
			'ccs-header',
			'ccs-responsive',
			'ccs-theme-style',
			'ccs-homepage',
			'ccs-service-page',
			'ccs-location-page',
			'ccs-contact-page',
		) );

		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_head', array( $this, 'preload_google_fonts' ), 0 );
		add_action( 'wp_head', array( $this, 'inline_critical_css' ), 1 );
		add_filter( 'style_loader_tag', array( $this, 'defer_stylesheet_tag' ), 10, 4 );
	}

	/**
	 * Output preconnect and preload for Google Fonts.
	 */
	public function preload_google_fonts() {
		$font_url = apply_filters( 'ccs_google_fonts_url', $this->get_default_google_fonts_url() );

		if ( empty( $font_url ) || strpos( $font_url, 'fonts.googleapis.com' ) === false ) {
			return;
		}

		echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
		printf(
			"<link rel=\"preload\" as=\"style\" href=\"%s\">\n",
			esc_url( $font_url )
		);
	}

	/**
	 * Default Google Fonts URL (Poppins + Open Sans per design system).
	 *
	 * @return string
	 */
	private function get_default_google_fonts_url() {
		return 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap';
	}

	/**
	 * Current template type for critical CSS lookup.
	 *
	 * @return string
	 */
	public function get_current_template_type() {
		if ( is_front_page() ) {
			return 'front_page';
		}
		if ( is_home() ) {
			return 'home';
		}
		if ( is_singular( 'service' ) ) {
			return 'singular_service';
		}
		if ( is_singular( 'location' ) ) {
			return 'singular_location';
		}
		if ( is_singular() ) {
			return 'single';
		}
		if ( is_page() ) {
			return 'page';
		}
		if ( is_archive() ) {
			return 'archive';
		}
		if ( is_search() ) {
			return 'search';
		}
		if ( is_404() ) {
			return '404';
		}
		return 'default';
	}

	/**
	 * Get critical CSS for current template: from options or fallback to file, trimmed to 14KB.
	 *
	 * @return string
	 */
	private function get_critical_css() {
		$template_type = $this->get_current_template_type();
		$stored       = get_option( self::OPTION_KEY, array() );

		if ( is_array( $stored ) && ! empty( $stored[ $template_type ] ) ) {
			return $this->trim_to_max_bytes( $stored[ $template_type ] );
		}

		// Fallback: try default key then file.
		if ( is_array( $stored ) && ! empty( $stored['default'] ) ) {
			return $this->trim_to_max_bytes( $stored['default'] );
		}

		$path = THEME_DIR . '/' . self::CRITICAL_CSS_PATH;
		if ( ! is_readable( $path ) ) {
			return '';
		}

		$css = file_get_contents( $path );
		if ( $css === false || $css === '' ) {
			return '';
		}

		return $this->trim_to_max_bytes( trim( $css ) );
	}

	/**
	 * Trim CSS to first CRITICAL_CSS_MAX_BYTES (14KB).
	 *
	 * @param string $css Raw CSS.
	 * @return string
	 */
	private function trim_to_max_bytes( $css ) {
		$css = wp_strip_all_tags( $css );
		$css = str_replace( '</style>', '<\/style>', $css );

		if ( strlen( $css ) <= self::CRITICAL_CSS_MAX_BYTES ) {
			return $css;
		}

		return substr( $css, 0, self::CRITICAL_CSS_MAX_BYTES );
	}

	/**
	 * Output critical CSS inline in <head>.
	 */
	public function inline_critical_css() {
		$css = $this->get_critical_css();

		if ( $css === '' ) {
			return;
		}

		// Prevent breaking out of <style> (stored/file CSS is trusted; escape for safety).
		$css = str_replace( '</style>', '<\/style>', $css );

		printf(
			"<style id=\"ccs-critical-css\">\n%s\n</style>\n",
			$css
		);
	}

	/**
	 * Defer non-critical stylesheets: media="print" + onload, with noscript fallback.
	 *
	 * @param string $html   The link tag for the enqueued style.
	 * @param string $handle The style's registered handle.
	 * @param string $href   The stylesheet's source URL.
	 * @param string $media  The stylesheet's media attribute.
	 * @return string Modified link tag (and optional noscript).
	 */
	public function defer_stylesheet_tag( $html, $handle, $href, $media ) {
		if ( ! in_array( $handle, $this->deferred_handles, true ) ) {
			return $html;
		}

		$deferred = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
		$deferred = str_replace( 'media="all"', 'media="print" onload="this.media=\'all\'"', $deferred );

		$noscript = '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '" media="all" /></noscript>';

		return $deferred . $noscript;
	}

	/**
	 * Get all supported template types (for CLI and storage).
	 *
	 * @return string[]
	 */
	public static function get_template_types() {
		return array(
			'default',
			'front_page',
			'home',
			'single',
			'page',
			'singular_service',
			'singular_location',
			'archive',
			'search',
			'404',
		);
	}

	/**
	 * Get stored critical CSS for a template type (or all).
	 *
	 * @param string|null $template_type Optional. Template key; null = all.
	 * @return array|string
	 */
	public static function get_stored_css( $template_type = null ) {
		$stored = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		if ( $template_type !== null ) {
			return isset( $stored[ $template_type ] ) ? $stored[ $template_type ] : '';
		}

		return $stored;
	}

	/**
	 * Save critical CSS for a template type.
	 *
	 * @param string $template_type Template key.
	 * @param string $css           Raw CSS (will be trimmed to 14KB when served).
	 * @return bool
	 */
	public static function save_css_for_template( $template_type, $css ) {
		$stored = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		$stored[ $template_type ] = $css;

		return update_option( self::OPTION_KEY, $stored );
	}

	/**
	 * Clear all stored critical CSS.
	 *
	 * @return bool
	 */
	public static function clear_stored_css() {
		return update_option( self::OPTION_KEY, array() );
	}
}
