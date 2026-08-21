<?php
/**
 * Careers hub: "Here's what you can expect" — four-card grid.
 *
 * Copy carried over from the live site's careers page (Ellie's own wording,
 * kept close to verbatim rather than rewritten).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	array(
		'title' => __( 'Real Support', 'ccs-wp-theme' ),
		'body'  => __( "We don't just hand you a uniform and send you on your way. You'll get practical training, a team you can rely on, and a manager who means it when they ask how you're doing.", 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'Flexible Hours', 'ccs-wp-theme' ),
		'body'  => __( "We understand the importance of balance. Whether you need school hours, overnight shifts or something steady and full-time, we'll build a rota that compliments your life.", 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'Communication', 'ccs-wp-theme' ),
		'body'  => __( 'Your ideas, your instincts, and your voice, all matter here. We know that our best care comes from listening to the people who deliver it. If something\'s not right, we want to know.', 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'Room to Grow', 'ccs-wp-theme' ),
		'body'  => __( "With Continuity, progression opportunities don't stop at completing your Care Certificate. Want to do your NVQ? Try a new area? We're here to support you in taking the next step.", 'ccs-wp-theme' ),
	),
);
?>
<section class="careers-expect" aria-labelledby="careers-expect-heading">
	<div class="container container--lg">
		<span class="careers-expect__eyebrow"><?php esc_html_e( 'Working with us', 'ccs-wp-theme' ); ?></span>
		<h2 id="careers-expect-heading" class="careers-expect__heading"><?php esc_html_e( "Here's what you can expect", 'ccs-wp-theme' ); ?></h2>
		<div class="careers-expect__grid">
			<?php foreach ( $items as $item ) : ?>
				<div class="careers-expect__card card">
					<div class="card-body">
						<h3 class="careers-expect__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="careers-expect__body"><?php echo esc_html( $item['body'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
