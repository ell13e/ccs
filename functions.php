<?php
/**
 * Kent Care Provider – Theme functions and loader
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme constants.
 */
define( 'THEME_VERSION', '1.0.0' );
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URL', get_template_directory_uri() );

/**
 * Autoloader: load classes from inc/ and inc/* using WordPress naming
 * (Class_Name → class-class-name.php).
 */
require_once THEME_DIR . '/inc/class-autoloader.php';
CCS_Autoloader::register();

/**
 * Custom post types (Services, Locations, Enquiries, Testimonials).
 */
new CCS_Register_Post_Types();

/**
 * Custom taxonomies (Service Category, Condition, Location Areas).
 */
new CCS_Register_Taxonomies();

/**
 * Theme setup (supports, menus, enqueue).
 */
require_once THEME_DIR . '/inc/theme-setup.php';

/**
 * Header and footer helpers (contact info, footer fallback menus).
 */
require_once THEME_DIR . '/inc/header-footer-helpers.php';

/**
 * Block patterns (CCS Patterns category).
 */
require_once THEME_DIR . '/inc/block-patterns.php';

/**
 * Page editor enhancements (classic editor for pages, SEO meta box).
 */
require_once THEME_DIR . '/inc/page-editor-enhancements.php';

/**
 * Resource downloads: CPT ccs_resource, taxonomy, download table, metaboxes (care guides).
 */
require_once THEME_DIR . '/inc/resource-downloads.php';

/**
 * Resource download: AJAX request handler, email delivery, token-based download URL.
 */
require_once THEME_DIR . '/inc/resource-download-ajax.php';

/**
 * Theme activation: demo pages, services, menus, Reading, permalinks. Idempotent; handles Reset Demo Content.
 */
new CCS_Theme_Activation();

/**
 * Appearance → CCS Theme Setup: welcome message, quick start checklist, theme info, Reset Demo Content, server requirements.
 */
new CCS_Welcome_Screen();

/**
 * Consultation form: shortcode [ccs_consultation_form], AJAX handler, CPT ccs_enquiry, emails.
 */
new CCS_Contact_Form();

/**
 * WP-CLI: wp ccs regenerate-critical-css (only when running via WP-CLI).
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once THEME_DIR . '/inc/cli/class-ccs-critical-css-command.php';
}
