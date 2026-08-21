<?php
/**
 * Team roster.
 *
 * Single source of truth for the people shown on the "Who You'll Meet" page,
 * so the list isn't buried in page content where it's easy to miss when someone
 * joins or leaves.
 *
 * Roster confirmed with Ellie 2026-08-19. Hani Ahmed (formerly Care Manager)
 * and Zoe Commons (formerly Domiciliary Care Manager) have left CCS and must not
 * appear anywhere on the site. Trish Henley and Jennifer Boorman are still staff
 * but are deliberately not featured on the public team page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Team members shown on the public team page, in display order.
 *
 * `photo` is a filename in assets/images/team/ — square-cropped (512×512)
 * web versions generated from the raw uploads (also in assets/images/team/,
 * underscore-named e.g. `magdalena_zoledz.jpg`). Crop only — no brightness/
 * colour correction, per Ellie's instruction (2026-08-20).
 *
 * 2026-08-20: the raw `magdalena_zoledz.jpg` in the first upload batch was
 * actually a photo of Keelie Varney (mislabelled at source, confirmed by
 * comparison) — corrected once Ellie re-uploaded both under the right names.
 *
 * Members without a photo fall back to an initials tile rather than an empty slot.
 *
 * @return array<int, array{name: string, role: string, photo: string}>
 */
function ccs_team_members() {
	$members = array(
		array(
			'name'  => 'Victoria Walker',
			'role'  => __( 'Registered Manager', 'ccs-wp-theme' ),
			'photo' => 'victoria-walker.jpg',
		),
		array(
			'name'  => 'Amanda Carter',
			'role'  => __( 'General Manager', 'ccs-wp-theme' ),
			'photo' => 'amanda-carter.jpg',
		),
		array(
			'name'  => 'Nikki Mackay',
			'role'  => __( 'Senior Clinical Manager', 'ccs-wp-theme' ),
			'photo' => 'nikki-mackay.jpg',
		),
		array(
			'name'  => 'Keelie Varney',
			'role'  => __( 'Care Manager', 'ccs-wp-theme' ),
			'photo' => 'keelie-varney.jpg',
		),
		array(
			'name'  => 'Shayna-Rae Fuller',
			'role'  => __( 'Field Care Supervisor', 'ccs-wp-theme' ),
			'photo' => 'shayna-rae-fuller.jpg',
		),
		array(
			'name'  => 'Danielle King',
			'role'  => __( 'Field Care Supervisor', 'ccs-wp-theme' ),
			'photo' => 'danielle-king.jpg',
		),
		array(
			'name'  => 'Heidi Griffen',
			'role'  => __( 'Field Care Supervisor', 'ccs-wp-theme' ),
			'photo' => 'heidi-griffen.jpg',
		),
		array(
			'name'  => 'Magdalena Zoledz',
			'role'  => __( 'Recruitment Coordinator', 'ccs-wp-theme' ),
			'photo' => 'magdalena-zoledz.jpg',
		),
	);

	/**
	 * Filter the public team roster.
	 *
	 * @param array $members Team members.
	 */
	return apply_filters( 'ccs_team_members', $members );
}
