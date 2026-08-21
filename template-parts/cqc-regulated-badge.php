<?php
/**
 * CQC "Regulated by" compact badge — CQC brand-guideline compliant.
 *
 * Per CQC's own digital-platform rules: JPG format, used as a complete
 * unaltered block (no resizing/distortion beyond consistent scaling), no
 * smaller than 135x65px, and the graphic itself must link to CQC's homepage
 * (www.cqc.org.uk) — not a specific report or location page. That deep link
 * belongs on the separate "Read our full CQC report" links elsewhere on the
 * site (see template-parts/home/cqc-section.php), which intentionally use a
 * different theme_mod (ccs_cqc_report_url) for that reason.
 *
 * Usage: get_template_part( 'template-parts/cqc-regulated-badge', null, array( 'variant' => 'purple' ) );
 * $args['variant'] is 'purple' (default, for light backgrounds) or 'white' (for dark/photo backgrounds).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$variant = ( isset( $args['variant'] ) && $args['variant'] === 'white' ) ? 'white' : 'purple';

// Custom-uploaded badge overrides the packaged default; the packaged files
// are the official CQC artwork so they're the safe default, not a placeholder.
$badge_custom = get_theme_mod( 'ccs_cqc_badge_url', '' );

$badge_files = array(
	'purple' => 'assets/images/regulated_by/1--CQC-Regulated-by-PURPLE-135px.jpg',
	'white'  => 'assets/images/regulated_by/1--CQC-Regulated-by-WHITE-135px.jpg',
);
$badge_rel  = $badge_files[ $variant ];
$badge_path = get_template_directory() . '/' . $badge_rel;
$badge_src  = $badge_custom !== '' ? $badge_custom : get_template_directory_uri() . '/' . $badge_rel;

// CQC's own homepage — the badge must link here, never to a location/report page.
$badge_link = get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk' );

if ( $badge_custom === '' && ! file_exists( $badge_path ) ) {
	return;
}
?>
<a
	href="<?php echo esc_url( $badge_link ); ?>"
	class="cqc-regulated-badge cqc-regulated-badge--<?php echo esc_attr( $variant ); ?>"
	target="_blank"
	rel="noopener noreferrer"
>
	<img
		src="<?php echo esc_url( $badge_src ); ?>"
		alt="<?php esc_attr_e( 'Regulated by CQC — Care Quality Commission', 'ccs-wp-theme' ); ?>"
		width="135"
		height="64"
		loading="lazy"
		decoding="async"
	>
	<span class="screen-reader-text"><?php esc_html_e( '(opens cqc.org.uk in a new window)', 'ccs-wp-theme' ); ?></span>
</a>
