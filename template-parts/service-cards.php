<?php
/**
 * Service card grid (.svc-grid of .svc-card), shared by the services hub and
 * the location pages so a service looks the same wherever it is listed.
 *
 * Args:
 * - services (WP_Post[]) Services to show, already ordered; defaults to
 *   ccs_ordered_services().
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_cards = isset( $args['services'] ) && is_array( $args['services'] ) ? $args['services'] : ccs_ordered_services();

if ( empty( $ccs_cards ) ) {
	return;
}

$ccs_card_icons = array(
	'domiciliary-care' => 'home',
	'respite-care'     => 'cup',
	'complex-care'     => 'pulse',
);
?>
<ul class="svc-grid" role="list">
	<?php foreach ( $ccs_cards as $ccs_card ) : ?>
		<?php
		$ccs_card_short = get_post_meta( $ccs_card->ID, 'service_short_description', true );
		$ccs_card_text  = has_excerpt( $ccs_card ) ? get_the_excerpt( $ccs_card ) : ( $ccs_card_short ? $ccs_card_short : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $ccs_card ) ), 26 ) );
		?>
		<li class="svc-card">
			<?php if ( has_post_thumbnail( $ccs_card ) ) : ?>
				<div class="svc-card__media">
					<?php
					echo wp_get_attachment_image(
						get_post_thumbnail_id( $ccs_card ),
						'large',
						false,
						array(
							'alt'     => '',
							'loading' => 'lazy',
							'sizes'   => '(min-width: 1280px) 400px, (min-width: 860px) 31vw, 92vw',
						)
					);
					?>
				</div>
			<?php endif; ?>
			<div class="svc-card__body">
				<span class="ccs-badge ccs-badge--ring svc-card__badge"><?php echo ccs_icon( $ccs_card_icons[ $ccs_card->post_name ] ?? 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<h3 class="svc-card__title">
					<a href="<?php echo esc_url( get_permalink( $ccs_card ) ); ?>" class="svc-card__link"><?php echo esc_html( get_the_title( $ccs_card ) ); ?></a>
				</h3>
				<?php if ( $ccs_card_text ) : ?>
					<p class="svc-card__text"><?php echo esc_html( $ccs_card_text ); ?></p>
				<?php endif; ?>
				<span class="svc-card__cue" aria-hidden="true"><?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span class="svc-card__arrow">&rarr;</span></span>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
