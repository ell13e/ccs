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
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'ccs-wp-theme' ),
		'footer'  => __( 'Footer Navigation', 'ccs-wp-theme' ),
	) );
}
add_action( 'after_setup_theme', 'ccs_theme_setup' );

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
		'ccs-responsive',
		$theme_uri . '/assets/css/responsive.css',
		array( 'ccs-design-system', 'ccs-components', 'ccs-header' ),
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

	wp_enqueue_script(
		'ccs-navigation',
		$theme_uri . '/assets/js/navigation.js',
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
	}
}
add_action( 'wp_enqueue_scripts', 'ccs_theme_scripts' );

/**
 * Defer non-critical scripts for better LCP (they run after HTML parse; no document.write).
 */
function ccs_defer_scripts( $tag, $handle, $src ) {
	$defer_handles = array( 'ccs-navigation', 'ccs-form-handler', 'ccs-consultation-form' );
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
	require_once THEME_DIR . '/inc/blocks/class-cta-block.php';
	new CCS_CTA_Block();
	require_once THEME_DIR . '/inc/blocks/class-faq-block.php';
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
