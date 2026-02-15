<?php
/**
 * Homepage partnerships section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$partners = array(
	__( 'National Care Association', 'ccs-wp-theme' ),
	__( 'Disability Confident Committed', 'ccs-wp-theme' ),
	__( 'CV Minder', 'ccs-wp-theme' ),
	__( 'MidKent College', 'ccs-wp-theme' ),
	__( 'Kent Integrated Care Alliance (KiCA)', 'ccs-wp-theme' ),
	__( 'Care Quality Commission (CQC)', 'ccs-wp-theme' ),
	__( 'iTrust', 'ccs-wp-theme' ),
	__( 'NHS', 'ccs-wp-theme' ),
	__( 'Homecare Association', 'ccs-wp-theme' ),
	__( 'Brain Injury Group', 'ccs-wp-theme' ),
	__( 'Continuity Training Academy', 'ccs-wp-theme' ),
);
?>

<section class="home-partnerships" aria-labelledby="home-partnerships-heading">
	<div class="home-partnerships__inner container container--lg">
		<h2 id="home-partnerships-heading" class="home-partnerships__heading">
			<?php esc_html_e( 'Our Local & National Partnerships', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-partnerships__subheading">
			<?php esc_html_e( "We're proud to collaborate with local, regional, and national organisations to enhance care and opportunities for our clients and staff.", 'ccs-wp-theme' ); ?>
		</p>
		<ul class="home-partnerships__list">
			<?php foreach ( $partners as $partner ) : ?>
				<li><?php echo esc_html( $partner ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
