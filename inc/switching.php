<?php
/**
 * The "Switch to us" route: families moving their home care from another
 * provider.
 *
 * The header button used to drop these visitors on the general contact page,
 * which asks first-time questions (what type of care, how soon) and says
 * nothing about the thing on their mind: how to leave the current provider
 * without a gap in care. The route is now its own page
 * (page-templates/template-switch.php) with a short form that asks the
 * switching questions and is tagged as a switching enquiry in the inbox.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slug of the switching page.
 */
const CCS_SWITCH_SLUG = 'switch-to-us';

/**
 * URL for "Switch to us": a Customizer override if set, else the switching
 * page, else the contact page (so the button never 404s).
 *
 * @return string
 */
function ccs_switch_url() {
	$override = (string) get_theme_mod( 'ccs_switch_to_us_url', '' );
	if ( '' !== $override ) {
		return $override;
	}

	$page = get_page_by_path( CCS_SWITCH_SLUG );
	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}

	return function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
}

/**
 * Create the switching page once on sites that were activated before it
 * existed. New installs get it from CCS_Theme_Activation.
 *
 * add_option() is the lock: it only succeeds for the first request, so two
 * requests arriving together cannot both insert a page (the activation race
 * that once doubled the menus). Never re-created if the client deletes it.
 */
function ccs_ensure_switch_page() {
	if ( get_option( 'ccs_switch_page_v1' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_switch_page_v1', '1', '', 'yes' ) ) {
		return;
	}
	if ( get_page_by_path( CCS_SWITCH_SLUG ) ) {
		return;
	}

	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __( 'Switching your home care', 'ccs-wp-theme' ),
			'post_name'    => CCS_SWITCH_SLUG,
			'post_content' => '',
		),
		true
	);

	if ( ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/template-switch.php' );
	}
}
add_action( 'init', 'ccs_ensure_switch_page', 30 );

/**
 * Labels for the "when does your current care end?" answers. Shared by the
 * form and the handler, so the inbox shows words rather than codes.
 *
 * @return array<string,string>
 */
function ccs_switch_timing_options() {
	return array(
		'no-notice'  => __( 'I have not given notice yet', 'ccs-wp-theme' ),
		'two-weeks'  => __( 'Within the next two weeks', 'ccs-wp-theme' ),
		'later'      => __( 'In more than two weeks', 'ccs-wp-theme' ),
		'not-sure'   => __( 'Not sure', 'ccs-wp-theme' ),
	);
}
