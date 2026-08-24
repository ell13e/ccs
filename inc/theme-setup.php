<?php
/**
 * Theme setup: supports, menus, scripts, styles.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for WordPress features.
 */
function ccs_theme_setup() {
	load_theme_textdomain( 'ccs-wp-theme', THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );

	add_post_type_support( 'page', 'editor' );

	register_nav_menus( array(
		'primary'        => __( 'Primary Navigation', 'ccs-wp-theme' ),
		'careers'        => __( 'Careers Navigation', 'ccs-wp-theme' ),
		'footer'         => __( 'Footer Navigation', 'ccs-wp-theme' ),
		'footer_company' => __( 'Footer – Company', 'ccs-wp-theme' ),
		'footer_help'    => __( 'Footer – Help', 'ccs-wp-theme' ),
	) );
}
add_action( 'after_setup_theme', 'ccs_theme_setup' );

/**
 * Add design-system root class to body so typography and tokens apply site-wide.
 *
 * @param array $classes Body classes.
 * @return array
 */
function ccs_body_class_ds_root( $classes ) {
	$classes[] = 'ds-root';
	return $classes;
}
add_filter( 'body_class', 'ccs_body_class_ds_root', 10, 1 );

/**
 * Whether a page opens with the full-bleed photo hero.
 *
 * Single source of truth, and it needs to be. Two separate places decide things
 * that must agree: template-parts/page-header.php picks the hero variant, and
 * ccs_body_class_photo_hero() below tells the header to go transparent with a
 * white logo and white nav. If those two ever disagree, the header paints white
 * text onto a light background and the whole thing disappears — which is
 * exactly the bug this theme has already shipped twice. They both call this.
 *
 * Policy pages are excluded: they are plain text documents with no featured
 * image and no business carrying a photo hero.
 *
 * @param int|null $post_id Page ID. Defaults to the current post.
 * @return bool
 */
function ccs_page_uses_photo_hero( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! $post_id ) {
		return false;
	}

	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	/**
	 * Filter the slugs that never receive a photo hero.
	 *
	 * @param array $slugs Excluded page slugs.
	 */
	$excluded = apply_filters(
		'ccs_photo_hero_excluded_slugs',
		array(
			'privacy-policy',
			'terms-and-conditions',
			'accessibility-statement',
			'cookies',
		)
	);

	if ( in_array( $post->post_name, $excluded, true ) ) {
		return false;
	}

	return has_post_thumbnail( $post_id );
}

/**
 * Flag pages that open with a dark full-bleed photo directly under the header
 * (the homepage hero, or an inner page's page-hero--photo — see
 * template-parts/home/hero.php and template-parts/page-header.php) so the header
 * can start transparent over the photo and only pick up its usual blurred
 * background once .is-scrolled is added (assets/js/navigation.js).
 *
 * Uses get_queried_object_id() rather than get_the_ID(): body_class runs outside
 * the loop, where get_the_ID() is not dependable.
 *
 * @param array $classes Body classes.
 * @return array
 */
function ccs_body_class_photo_hero( $classes ) {
	/*
	 * is_singular( 'service' ) added alongside is_page(): single-service.php has
	 * its own hero markup (page-hero classes now, formerly a separate
	 * service-hero implementation — see assets/css/service-page.css), but it is
	 * a CPT singular, not a page, so this check never matched it before. The
	 * header stayed solid and dark-text even when the service hero itself had
	 * gone full-bleed and dark, because nothing told it to.
	 *
	 * Location posts are deliberately NOT included. single-location.php's
	 * featured-image slot is a map image, not a decorative photo — most
	 * location pages have no image at all and show a plain gradient "Map"
	 * placeholder there. Full-bleed-photo-behind-white-text is the wrong
	 * treatment for that content even where a map image exists.
	 */
	if ( is_front_page()
		|| ( is_page() && ccs_page_uses_photo_hero( get_queried_object_id() ) )
		|| ( is_singular( 'service' ) && has_post_thumbnail( get_queried_object_id() ) )
	) {
		$classes[] = 'has-photo-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'ccs_body_class_photo_hero', 10, 1 );

/**
 * Use classic editor for pages (Visual/Code tabs).
 *
 * @param bool   $use_block_editor Whether to use the block editor.
 * @param string $post_type       Post type.
 * @return bool
 */
function ccs_use_classic_editor_for_pages( $use_block_editor, $post_type ) {
	if ( $post_type === 'page' ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'ccs_use_classic_editor_for_pages', 10, 2 );

/**
 * Ensure custom logo has descriptive alt text for accessibility.
 * When the logo image has no alt, use the site name.
 *
 * @param string $html    Logo HTML.
 * @param int    $blog_id Blog ID.
 * @param int    $logo_id Attachment ID.
 * @return string
 */
function ccs_custom_logo_alt( $html, $blog_id, $logo_id ) {
	if ( ! $html || ! $logo_id ) {
		return $html;
	}
	$site_name = get_bloginfo( 'name' );
	if ( $site_name === '' ) {
		return $html;
	}
	$alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
	if ( is_string( $alt ) && trim( $alt ) !== '' ) {
		return $html;
	}
	$safe_name = esc_attr( $site_name );
	if ( preg_match( '/<img\s[^>]*\balt=""[^>]*>/i', $html ) ) {
		return preg_replace( '/(<img\s[^>]*)\balt=""([^>]*>)/i', '$1alt="' . $safe_name . '"$2', $html );
	}
	if ( preg_match( '/<img((?![^>]*\balt=)[^>]*)>/i', $html ) ) {
		return preg_replace( '/<img(\s+)([^>]*)>/i', '<img$1alt="' . $safe_name . '"$2>', $html );
	}
	return $html;
}
add_filter( 'get_custom_logo', 'ccs_custom_logo_alt', 10, 3 );

/**
 * Register meta boxes (Service, Location, Enquiry).
 * Must run on add_meta_boxes: add_meta_box() is only available in admin.
 */
function ccs_register_meta_boxes() {
	$service_meta_box = new CCS_Service_Meta_Box();
	$service_meta_box->register();
	$location_meta_box = new CCS_Location_Meta_Box();
	$location_meta_box->register();
	$enquiry_meta_box = new CCS_Enquiry_Meta_Box();
	$enquiry_meta_box->register();
}
add_action( 'add_meta_boxes', 'ccs_register_meta_boxes' );

/**
 * Register email notifications (admin enquiry, user confirmation, urgent Slack/SMS/on-call).
 */
function ccs_register_email_notifications() {
	new CCS_Email_Notifications();
}
add_action( 'init', 'ccs_register_email_notifications', 4 );

/**
 * Register front-end form handlers (enquiry, callback).
 */
function ccs_register_form_handlers() {
	new CCS_Form_Handlers();
}
add_action( 'init', 'ccs_register_form_handlers', 5 );

/**
 * Enqueue scripts and styles.
 */
function ccs_theme_scripts() {
	$theme_uri = THEME_URL;
	$version   = THEME_VERSION;

	wp_enqueue_style(
		'ccs-design-system',
		$theme_uri . '/assets/css/design-system.css',
		array(),
		$version
	);
	wp_enqueue_style(
		'ccs-components',
		$theme_uri . '/assets/css/components.css',
		array( 'ccs-design-system' ),
		$version
	);
	wp_enqueue_style(
		'ccs-header',
		$theme_uri . '/assets/css/header.css',
		array( 'ccs-design-system', 'ccs-components' ),
		$version
	);
	wp_enqueue_style(
		'ccs-footer',
		$theme_uri . '/assets/css/footer.css',
		array( 'ccs-design-system', 'ccs-components', 'ccs-header' ),
		$version
	);
	wp_enqueue_style(
		'ccs-responsive',
		$theme_uri . '/assets/css/responsive.css',
		array( 'ccs-design-system', 'ccs-components', 'ccs-header', 'ccs-footer' ),
		$version
	);
	wp_enqueue_style(
		'ccs-theme-style',
		get_stylesheet_uri(),
		array( 'ccs-design-system' ),
		$version
	);
	wp_style_add_data( 'ccs-theme-style', 'rtl', 'replace' );

	if ( is_page_template( 'page-templates/template-homepage.php' ) ) {
		wp_enqueue_style(
			'ccs-homepage',
			$theme_uri . '/assets/css/homepage.css',
			array( 'ccs-design-system', 'ccs-components' ),
			$version
		);
	}

	if ( is_singular( 'service' ) ) {
		wp_enqueue_style(
			'ccs-service-page',
			$theme_uri . '/assets/css/service-page.css',
			array( 'ccs-design-system', 'ccs-components' ),
			$version
		);
	}

	if ( is_singular( 'location' ) ) {
		wp_enqueue_style(
			'ccs-location-page',
			$theme_uri . '/assets/css/location-page.css',
			array( 'ccs-design-system', 'ccs-components' ),
			$version
		);
	}

	if ( is_page_template( 'page-templates/template-contact.php' ) ) {
		wp_enqueue_style(
			'ccs-contact-page',
			$theme_uri . '/assets/css/contact-page.css',
			array( 'ccs-design-system', 'ccs-components' ),
			$version
		);
	}

	if ( is_page_template( 'page-templates/template-current-vacancies.php' ) ) {
		wp_enqueue_style(
			'ccs-current-vacancies',
			$theme_uri . '/assets/css/current-vacancies.css',
			array( 'ccs-design-system', 'ccs-components' ),
			$version
		);
	}

	wp_enqueue_script(
		'ccs-navigation',
		$theme_uri . '/assets/js/navigation.js',
		array(),
		$version,
		true
	);
	// Tiny, dependency-free, and a safe no-op on pages with no .video-embed —
	// cheaper to load everywhere than to thread a per-template conditional.
	wp_enqueue_script(
		'ccs-video-embed',
		$theme_uri . '/assets/js/video-embed.js',
		array(),
		$version,
		true
	);
	wp_localize_script(
		'ccs-navigation',
		'ccsNavigation',
		array(
			'openMenu'       => __( 'Open menu', 'ccs-wp-theme' ),
			'closeMenu'      => __( 'Close menu', 'ccs-wp-theme' ),
			'expandSubmenu'  => __( 'Expand submenu', 'ccs-wp-theme' ),
			'collapseSubmenu' => __( 'Collapse submenu', 'ccs-wp-theme' ),
		)
	);

	if ( ! is_admin() ) {
		wp_enqueue_script(
			'ccs-form-handler',
			$theme_uri . '/assets/js/form-handler.js',
			array(),
			$version,
			true
		);
		wp_localize_script(
			'ccs-form-handler',
			'ccsFormHandler',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonceEnquiry'  => wp_create_nonce( 'ccs_enquiry_form' ),
				'nonceCallback' => wp_create_nonce( 'ccs_callback_form' ),
			)
		);

		// Resource-download modal assets: only where a download can actually be triggered.
		if ( ! function_exists( 'ccs_needs_resource_downloads' ) || ccs_needs_resource_downloads() ) {
			wp_enqueue_style(
				'ccs-resource-download-modal',
				$theme_uri . '/assets/css/resource-download-modal.css',
				array( 'ccs-design-system', 'ccs-components' ),
				$version
			);
			wp_enqueue_script(
				'ccs-resource-download',
				$theme_uri . '/assets/js/resource-download.js',
				array(),
				$version,
				true
			);
			wp_localize_script(
				'ccs-resource-download',
				'ccsResourceDownload',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'ccs_resource_download' ),
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ccs_theme_scripts' );

/**
 * Defer non-critical scripts for better LCP (they run after HTML parse; no document.write).
 */
function ccs_defer_scripts( $tag, $handle, $src ) {
	$defer_handles = array( 'ccs-navigation', 'ccs-form-handler', 'ccs-consultation-form', 'ccs-resource-download' );
	if ( in_array( $handle, $defer_handles, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'ccs_defer_scripts', 10, 3 );

/**
 * Register admin dashboard widget (stats, chart, recent enquiries).
 */
function ccs_register_dashboard_widget() {
	if ( ! is_admin() ) {
		return;
	}
	new CCS_Dashboard_Widget();
}
add_action( 'init', 'ccs_register_dashboard_widget', 20 );

/**
 * Register enquiry list table customizations (columns, filters, bulk/row actions, export).
 */
function ccs_register_enquiry_manager() {
	if ( ! is_admin() ) {
		return;
	}
	new CCS_Enquiry_Manager();
}
add_action( 'init', 'ccs_register_enquiry_manager', 20 );

/**
 * Output Schema.org JSON-LD structured data (Organization, WebSite, Service, Location, ContactPage, FAQPage, BreadcrumbList).
 */
function ccs_register_structured_data() {
	if ( ! is_admin() ) {
		new CCS_Structured_Data();
	}
}
add_action( 'init', 'ccs_register_structured_data', 15 );

/**
 * On-page SEO: meta titles, meta descriptions, Open Graph, Twitter Card, canonical, sitemap.
 */
function ccs_register_seo_optimizer() {
	if ( ! is_admin() ) {
		new CCS_SEO_Optimizer();
	}
}
add_action( 'init', 'ccs_register_seo_optimizer', 15 );

/**
 * Native lazy loading for images (content, thumbnails, attachment images) with JS fallback for older browsers.
 */
function ccs_register_lazy_load() {
	if ( ! is_admin() ) {
		new CCS_Lazy_Load();
	}
}
add_action( 'init', 'ccs_register_lazy_load', 15 );

/**
 * Image optimisation: WebP generation on upload, WebP serving, max dimensions, custom sizes, responsive srcset.
 */
function ccs_register_image_optimization() {
	new CCS_Image_Optimization();
}
add_action( 'init', 'ccs_register_image_optimization', 10 );

/**
 * Cache control: Cache-Control, Expires, ETag, Vary; per-content durations; no-cache for logged-in/preview/search.
 */
function ccs_register_cache_control() {
	new CCS_Cache_Control();
}
add_action( 'init', 'ccs_register_cache_control', 10 );

/**
 * Security: HTTP security headers (X-Frame-Options, X-Content-Type-Options, Referrer-Policy) and optional security event logging.
 */
function ccs_register_security() {
	new CCS_Security();
}
add_action( 'init', 'ccs_register_security', 10 );

/**
 * Critical CSS: inline above-the-fold styles, defer rest with media="print" onload.
 */
function ccs_register_critical_css() {
	if ( ! is_admin() ) {
		new CCS_Critical_CSS();
	}
}
add_action( 'init', 'ccs_register_critical_css', 15 );

/**
 * Allow admins to clear stored critical CSS without WP-CLI (theme will fall back to assets/css/critical.css).
 * Visit: add_query_arg( 'ccs_clear_critical_css', '1', home_url( '/' ) ) with _wpnonce=wp_create_nonce( 'ccs_clear_critical_css' ).
 */
function ccs_handle_clear_critical_css() {
	if ( ! isset( $_GET['ccs_clear_critical_css'] ) || $_GET['ccs_clear_critical_css'] !== '1' ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccs_clear_critical_css' ) ) {
		return;
	}
	CCS_Critical_CSS::clear_stored_css();
	wp_safe_redirect( add_query_arg( 'ccs_critical_css_cleared', '1', admin_url() ) );
	exit;
}
add_action( 'init', 'ccs_handle_clear_critical_css', 5 );

/**
 * One-time admin notice after clearing stored critical CSS.
 */
function ccs_notice_critical_css_cleared() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_GET['ccs_critical_css_cleared'] ) ) {
		return;
	}
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Stored critical CSS cleared. Theme is using assets/css/critical.css.', 'ccs-wp-theme' ) . '</p></div>';
}
add_action( 'admin_notices', 'ccs_notice_critical_css_cleared' );

/**
 * Analytics & conversion tracking (GA4, Facebook Pixel, Google Ads). Customizer runs in admin; tracking runs on front.
 */
function ccs_register_analytics() {
	new CCS_Analytics();
}
add_action( 'init', 'ccs_register_analytics', 20 );

/**
 * Theme Customizer: Contact, Social, Analytics, CQC, Emergency Banner.
 */
function ccs_register_theme_customizer() {
	new CCS_Theme_Customizer();
}
add_action( 'init', 'ccs_register_theme_customizer', 20 );

/**
 * Gutenberg blocks (Testimonial, CTA, FAQ).
 */
function ccs_register_blocks() {
	new CCS_Testimonial_Block();
	new CCS_CTA_Block();
	new CCS_FAQ_Block();
}
add_action( 'init', 'ccs_register_blocks', 20 );

/**
 * WCAG 2.1 AA accessibility checker (admin notices and checklist).
 */
function ccs_register_accessibility_checker() {
	if ( is_admin() ) {
		CCS_Accessibility_Checker::register();
	}
}
add_action( 'init', 'ccs_register_accessibility_checker', 25 );
