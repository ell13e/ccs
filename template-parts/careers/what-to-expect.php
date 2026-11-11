<?php
/**
 * Careers hub: "Here's what you can expect", four points on the tint band,
 * icon and text with no box (.feature-grid--plain): none of them is clickable
 * or a separate object, so a card would only add weight (CCS/CLAUDE.md rule 1).
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
		'icon'  => 'shield',
		'title' => __( 'Real Support', 'ccs-wp-theme' ),
		'body'  => __( "We don't just hand you a uniform and send you on your way. You'll get practical training, a team you can rely on, and a manager who means it when they ask how you're doing.", 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'clock',
		'title' => __( 'Flexible Hours', 'ccs-wp-theme' ),
		'body'  => __( "We understand the importance of balance. Whether you need school hours, overnight shifts or something steady and full-time, we'll build a rota that complements your life.", 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'people',
		'title' => __( 'Communication', 'ccs-wp-theme' ),
		'body'  => __( 'Your ideas, your instincts, and your voice, all matter here. We know that our best care comes from listening to the people who deliver it. If something\'s not right, we want to know.', 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'badge',
		'title' => __( 'Room to Grow', 'ccs-wp-theme' ),
		'body'  => __( "With Continuity, progression opportunities don't stop at completing your Care Certificate. Want to do your NVQ? Try a new area? We're here to support you in taking the next step.", 'ccs-wp-theme' ),
	),
);
?>
<section class="ccs-section ccs-section--tint careers-expect" aria-labelledby="careers-expect-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center">
			<h2 id="careers-expect-heading" class="ccs-title">
				<?php
				/* translators: %s: the emphasised word, drawn with a brush underline. */
				printf( esc_html__( "Here's what you can %s", 'ccs-wp-theme' ), ccs_accent( __( 'expect', 'ccs-wp-theme' ) ) );
				?>
			</h2>
		</header>
		<ul class="feature-grid feature-grid--4" role="list">
			<?php foreach ( $items as $item ) : ?>
				<li class="feature-card" data-ccs-exception="rule 1: cards at the client's request (Nov 2026); as loose icon-and-text points this section looked unfinished">
					<span class="ccs-badge"><?php echo ccs_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<h3 class="feature-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="feature-card__text"><?php echo esc_html( $item['body'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
