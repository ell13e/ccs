<?php
/**
 * The "Costs and funding" page (Nov 2026).
 *
 * The full published rate table moved off the homepage to its own page at
 * the client's request ("maybe we need a page for this and just a section
 * directing to it here instead"). The homepage costs section links here; it
 * already looked for a page with this slug (template-parts/home/costs.php).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCS_COSTS_SLUG', 'costs-and-funding' );

/**
 * Create the costs page once on sites activated before it existed. New
 * installs get it from CCS_Theme_Activation. add_option() is the lock, as for
 * the switching page (inc/switching.php); never re-created if deleted.
 */
function ccs_ensure_costs_page() {
	if ( get_option( 'ccs_costs_page_v1' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_costs_page_v1', '1', '', 'yes' ) ) {
		return;
	}
	if ( get_page_by_path( CCS_COSTS_SLUG ) ) {
		return;
	}

	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __( 'Costs and funding', 'ccs-wp-theme' ),
			'post_name'    => CCS_COSTS_SLUG,
			'post_content' => '',
		),
		true
	);

	if ( ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/template-costs.php' );
		update_post_meta( $page_id, 'ccs_page_heading', __( 'What care costs', 'ccs-wp-theme' ) );
		update_post_meta( $page_id, 'ccs_page_intro', __( 'Our published rates, what affects them, and the ways care can be paid for.', 'ccs-wp-theme' ) );
	}
}
add_action( 'init', 'ccs_ensure_costs_page', 30 );
