<?php
/**
 * On-page SEO: meta titles, meta descriptions, Open Graph, Twitter Card, canonical, sitemap.
 *
 * Uses WordPress document_title and core sitemap. Custom fields: service_seo_title,
 * service_meta_description; optional ccs_seo_title, ccs_meta_description for other post types.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_SEO_Optimizer
 */
class CCS_SEO_Optimizer {

	const TITLE_MAX = 60;
	const DESC_MAX = 160;

	/** Optional meta keys for custom title/description (e.g. pages). */
	const META_TITLE       = 'ccs_seo_title';
	const META_DESCRIPTION = 'ccs_meta_description';

	/** Service-specific meta (from Service Details meta box). */
	const SERVICE_META_TITLE       = 'service_seo_title';
	const SERVICE_META_DESCRIPTION = 'service_meta_description';

	/** Default county for location meta title. */
	const DEFAULT_COUNTY = 'Kent';

	/**
	 * Constructor: hooks for title, meta, OG, Twitter, canonical, sitemap.
	 */
	public function __construct() {
		add_filter( 'document_title_parts', array( $this, 'filter_document_title_parts' ), 10, 1 );
		add_filter( 'document_title', array( $this, 'filter_document_title_truncate' ), 10, 1 );
		add_action( 'wp_head', array( $this, 'output_meta_description' ), 1 );
		add_action( 'wp_head', array( $this, 'output_open_graph' ), 2 );
		add_action( 'wp_head', array( $this, 'output_twitter_card' ), 3 );
		add_action( 'wp_head', array( $this, 'output_canonical' ), 4 );

		add_filter( 'wp_sitemaps_post_types', array( $this, 'filter_sitemap_post_types' ), 10, 1 );
		add_filter( 'wp_sitemaps_posts_entry', array( $this, 'filter_sitemap_posts_entry' ), 10, 3 );
		add_filter( 'wp_sitemaps_exclude_post_ids', array( $this, 'exclude_sitemap_post_ids' ), 10, 2 );
	}

	// -------------------------------------------------------------------------
	// Meta titles
	// -------------------------------------------------------------------------

	/**
	 * Set document title parts (title and site name). WordPress builds "Title | Site".
	 *
	 * @param array $parts Associative array with 'title', 'page', 'tagline', 'site'.
	 * @return array Modified parts.
	 */
	public function filter_document_title_parts( $parts ) {
		if ( is_admin() || is_feed() || is_robots() ) {
			return $parts;
		}

		$title = $this->get_meta_title();
		if ( $title !== '' ) {
			$parts['title'] = $title;
			$parts['page']  = '';
			$parts['tagline'] = '';
		}
		if ( ! isset( $parts['site'] ) || $parts['site'] === '' ) {
			$parts['site'] = get_bloginfo( 'name', 'display' );
		}

		return $parts;
	}

	/**
	 * Truncate final document title to TITLE_MAX characters.
	 *
	 * @param string $title Full title string.
	 * @return string Truncated title.
	 */
	public function filter_document_title_truncate( $title ) {
		if ( is_admin() || is_feed() || is_robots() ) {
			return $title;
		}
		return $this->truncate( $title, self::TITLE_MAX );
	}

	/**
	 * Get the main title part (no site name) for current request.
	 *
	 * @return string Title part; empty to fall back to default.
	 */
	private function get_meta_title() {
		if ( is_singular( 'service' ) ) {
			return $this->get_service_meta_title();
		}
		if ( is_singular( 'location' ) ) {
			return $this->get_location_meta_title();
		}
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$custom = get_post_meta( $post->ID, self::META_TITLE, true );
				if ( is_string( $custom ) && trim( $custom ) !== '' ) {
					return trim( $custom );
				}
				return get_the_title( $post->ID );
			}
		}
		return '';
	}

	/**
	 * Service meta title: custom or "[Service] | [Site Name]" (site name added by WP).
	 * Format "[Service] in [Location] | [Site Name]" if a location meta is added later.
	 *
	 * @return string Title part.
	 */
	private function get_service_meta_title() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return '';
		}
		$custom = get_post_meta( $post->ID, self::SERVICE_META_TITLE, true );
		if ( is_string( $custom ) && trim( $custom ) !== '' ) {
			return trim( $custom );
		}
		return get_the_title( $post->ID );
	}

	/**
	 * Location meta title: "Home Care in [Town], [County]". County from meta or DEFAULT_COUNTY.
	 *
	 * @return string Title part.
	 */
	private function get_location_meta_title() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return '';
		}
		$custom = get_post_meta( $post->ID, self::META_TITLE, true );
		if ( is_string( $custom ) && trim( $custom ) !== '' ) {
			return trim( $custom );
		}
		$town   = get_post_meta( $post->ID, 'location_town', true );
		$county = get_post_meta( $post->ID, 'location_county', true );
		$town   = is_string( $town ) && trim( $town ) !== '' ? trim( $town ) : get_the_title( $post->ID );
		$county = is_string( $county ) && trim( $county ) !== '' ? trim( $county ) : self::DEFAULT_COUNTY;
		return sprintf(
			/* translators: 1: town name, 2: county name */
			__( 'Home Care in %1$s, %2$s', 'ccs-wp-theme' ),
			$town,
			$county
		);
	}

	// -------------------------------------------------------------------------
	// Meta description
	// -------------------------------------------------------------------------

	/**
	 * Output meta description tag in wp_head.
	 */
	public function output_meta_description() {
		if ( is_admin() || is_feed() || is_robots() || ! is_singular() ) {
			return;
		}
		$desc = $this->get_meta_description();
		if ( $desc === '' ) {
			return;
		}
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}

	/**
	 * Get meta description: custom field or auto from excerpt/content. Truncate 160.
	 *
	 * @return string Empty if none.
	 */
	private function get_meta_description() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		if ( $post->post_type === 'service' ) {
			$custom = get_post_meta( $post->ID, self::SERVICE_META_DESCRIPTION, true );
			if ( is_string( $custom ) && trim( $custom ) !== '' ) {
				return $this->truncate( trim( $custom ), self::DESC_MAX );
			}
		} else {
			$custom = get_post_meta( $post->ID, self::META_DESCRIPTION, true );
			if ( is_string( $custom ) && trim( $custom ) !== '' ) {
				return $this->truncate( trim( $custom ), self::DESC_MAX );
			}
		}

		$auto = $this->get_auto_description( $post );
		if ( $auto !== '' ) {
			return $this->truncate( $auto, self::DESC_MAX );
		}

		return '';
	}

	/**
	 * Auto description from short description, excerpt, or content. Include location for location/service.
	 *
	 * @param WP_Post $post Post object.
	 * @return string Plain text, no length limit.
	 */
	private function get_auto_description( WP_Post $post ) {
		$text = '';

		if ( $post->post_type === 'service' ) {
			$short = get_post_meta( $post->ID, 'service_short_description', true );
			if ( is_string( $short ) && trim( $short ) !== '' ) {
				$text = wp_strip_all_tags( trim( $short ) );
			}
		}

		if ( $text === '' && has_excerpt( $post->ID ) ) {
			$text = get_the_excerpt( $post->ID );
			$text = wp_strip_all_tags( trim( $text ) );
		}

		if ( $text === '' ) {
			$content = get_post_field( 'post_content', $post->ID );
			if ( $content !== '' ) {
				$text = wp_trim_words( wp_strip_all_tags( $content ), 25 );
			}
		}

		if ( $text !== '' && $post->post_type === 'location' ) {
			$town = get_post_meta( $post->ID, 'location_town', true );
			$county = get_post_meta( $post->ID, 'location_county', true );
			if ( $town || $county ) {
				$loc = implode( ', ', array_filter( array( trim( (string) $town ), trim( (string) $county ) ) ) );
				$text = $loc . ' – ' . $text;
			}
		}

		return $text;
	}

	// -------------------------------------------------------------------------
	// Open Graph
	// -------------------------------------------------------------------------

	/**
	 * Output Open Graph meta tags.
	 */
	public function output_open_graph() {
		if ( is_admin() || is_feed() || is_robots() ) {
			return;
		}

		$title = $this->get_og_title();
		$desc  = $this->get_og_description();
		$image = $this->get_og_image();
		$url   = $this->get_canonical_url();
		$type  = is_singular() ? 'website' : 'website';
		if ( is_singular( 'service' ) || is_singular( 'location' ) || is_singular( 'page' ) || is_singular( 'post' ) ) {
			$type = 'article';
		}

		if ( $title !== '' ) {
			echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
		}
		if ( $desc !== '' ) {
			echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		if ( $image !== '' ) {
			echo '<meta property="og:image" content="' . esc_attr( $image ) . '">' . "\n";
		}
		echo '<meta property="og:url" content="' . esc_attr( $url ) . '">' . "\n";
		echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '">' . "\n";
	}

	private function get_og_title() {
		$title = wp_get_document_title();
		return $this->truncate( $title, self::TITLE_MAX );
	}

	private function get_og_description() {
		if ( is_singular() ) {
			return $this->get_meta_description();
		}
		$desc = get_bloginfo( 'description', 'display' );
		return $desc !== '' ? $this->truncate( $desc, self::DESC_MAX ) : '';
	}

	/**
	 * og:image: featured image or theme logo.
	 *
	 * @return string Image URL or empty.
	 */
	private function get_og_image() {
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$thumb_id = get_post_thumbnail_id( $post->ID );
				if ( $thumb_id ) {
					$src = wp_get_attachment_image_src( $thumb_id, 'large' );
					if ( is_array( $src ) && ! empty( $src[0] ) ) {
						return $src[0];
					}
				}
			}
		}
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$src = wp_get_attachment_image_src( $logo_id, 'full' );
			if ( is_array( $src ) && ! empty( $src[0] ) ) {
				return $src[0];
			}
		}
		return '';
	}

	// -------------------------------------------------------------------------
	// Twitter Card
	// -------------------------------------------------------------------------

	/**
	 * Output Twitter Card meta tags.
	 */
	public function output_twitter_card() {
		if ( is_admin() || is_feed() || is_robots() ) {
			return;
		}

		$title = $this->get_og_title();
		$desc  = $this->get_og_description();
		$image = $this->get_og_image();

		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		if ( $title !== '' ) {
			echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
		}
		if ( $desc !== '' ) {
			echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		if ( $image !== '' ) {
			echo '<meta name="twitter:image" content="' . esc_attr( $image ) . '">' . "\n";
		}
	}

	// -------------------------------------------------------------------------
	// Canonical
	// -------------------------------------------------------------------------

	/**
	 * Output canonical link. Handles pagination (core get_canonical_url does).
	 */
	public function output_canonical() {
		if ( is_admin() || is_feed() || is_robots() ) {
			return;
		}
		$url = $this->get_canonical_url();
		if ( $url === '' ) {
			return;
		}
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	/**
	 * Current request canonical URL. Uses wp_get_canonical_url for singular; handles paged archives.
	 *
	 * @return string URL.
	 */
	private function get_canonical_url() {
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post && function_exists( 'wp_get_canonical_url' ) ) {
				$url = wp_get_canonical_url( $post );
				if ( $url ) {
					return $url;
				}
			}
			if ( $post instanceof WP_Post ) {
				$url = get_permalink( $post->ID );
				$page = (int) get_query_var( 'page', 0 );
				if ( $page > 1 ) {
					$url = get_pagenum_link( $page );
				}
				return $url;
			}
		}

		if ( is_home() && ! is_front_page() ) {
			return get_permalink( (int) get_option( 'page_for_posts' ) );
		}

		if ( is_front_page() ) {
			return home_url( '/' );
		}

		if ( is_post_type_archive() ) {
			$obj = get_queried_object();
			if ( $obj && isset( $obj->name ) ) {
				return get_post_type_archive_link( $obj->name );
			}
		}

		// Paged archive: canonical is the current page URL (page 2, 3, etc.).
		if ( is_archive() || is_search() ) {
			$paged = (int) get_query_var( 'paged', 0 );
			if ( $paged > 1 ) {
				return get_pagenum_link( $paged );
			}
		}

		return home_url( add_query_arg( array() ) );
	}

	// -------------------------------------------------------------------------
	// XML Sitemap (WordPress core)
	// -------------------------------------------------------------------------

	/**
	 * Limit sitemap to desired post types and expose service/location.
	 *
	 * @param array $post_types Current post types in sitemap.
	 * @return array Filtered post types.
	 */
	public function filter_sitemap_post_types( $post_types ) {
		$want = array( 'post', 'page', 'service', 'location' );
		return array_intersect_key( $post_types, array_flip( $want ) );
	}

	/**
	 * Set priority and changefreq per post type.
	 *
	 * @param array   $entry     Sitemap entry (loc, lastmod, changefreq, etc.).
	 * @param WP_Post $post      Post object.
	 * @param string  $post_type Post type name.
	 * @return array Modified entry.
	 */
	public function filter_sitemap_posts_entry( $entry, $post, $post_type ) {
		$priorities = array(
			'page'     => 0.9,
			'service'  => 0.8,
			'location' => 0.8,
			'post'     => 0.7,
		);
		$entry['priority'] = isset( $priorities[ $post_type ] ) ? (string) $priorities[ $post_type ] : '0.5';
		if ( $post_type === 'page' && (int) $post->ID === (int) get_option( 'page_on_front' ) ) {
			$entry['priority'] = '1.0';
		}
		return $entry;
	}

	/**
	 * Exclude specific post IDs from sitemap (e.g. thank-you, noindex pages).
	 *
	 * @param array  $post_ids  IDs to exclude.
	 * @param string $post_type Post type.
	 * @return array Merged exclude list.
	 */
	public function exclude_sitemap_post_ids( $post_ids, $post_type ) {
		$thank_you = get_theme_mod( 'ccs_analytics_thank_you_page', '' );
		if ( $thank_you === '' || $post_type !== 'page' ) {
			return $post_ids;
		}
		$page = get_page_by_path( trim( $thank_you ), OBJECT, 'page' );
		if ( $page instanceof WP_Post ) {
			$post_ids[] = $page->ID;
		}
		return $post_ids;
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Truncate string to max length at word boundary.
	 *
	 * @param string $text   Input.
	 * @param int    $max    Max length.
	 * @return string Truncated string.
	 */
	private function truncate( $text, $max ) {
		$text = wp_strip_all_tags( $text );
		if ( mb_strlen( $text ) <= $max ) {
			return $text;
		}
		$trim = mb_substr( $text, 0, $max - 1 );
		$last = mb_strrpos( $trim, ' ' );
		if ( $last !== false && $last > (int) ( $max * 0.6 ) ) {
			return mb_substr( $trim, 0, $last );
		}
		return $trim . '…';
	}
}
