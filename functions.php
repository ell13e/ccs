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
