<?php
/**
 * Service card grid (.svc-grid of .svc-card), shared by the services hub and
 * the location pages so a service looks the same wherever it is listed.
 *
 * Args:
 * - services (WP_Post[]) Services to show, already ordered; defaults to
 *   ccs_ordered_services().
 * - town (string) On an area page: the card title names the town ("Respite
 *   care in Tonbridge") and the card carries no excerpt, only the price tag
 *   on its photo. The same excerpts on all six area pages made them read as
 *   near-duplicates to search engines (Nov 2026).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_cards = isset( $args['services'] ) && is_array( $args['services'] ) ? $args['services'] : ccs_ordered_services();
$ccs_town  = isset( $args['town'] ) ? trim( (string) $args['town'] ) : '';

if ( empty( $ccs_cards ) ) {
	return;
}

?>
<ul class="svc-grid ccs-swipe" role="list">
	<?php foreach ( $ccs_cards as $ccs_card ) : ?>
		<?php
		$ccs_card_short = get_post_meta( $ccs_card->ID, 'service_short_description', true );
		$ccs_card_text  = has_excerpt( $ccs_card ) ? get_the_excerpt( $ccs_card ) : ( $ccs_card_short ? $ccs_card_short : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $ccs_card ) ), 26 ) );
			$ccs_card_title = get_the_title( $ccs_card );
		if ( '' !== $ccs_town ) {
			/* translators: 1: service name, 2: town. */
			$ccs_card_title = sprintf( __( '%1$s in %2$s', 'ccs-wp-theme' ), $ccs_card_title, $ccs_town );
			$ccs_card_text  = '';
		}
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
			<?php elseif ( function_exists( 'ccs_service_chosen_photo' ) && ccs_service_chosen_photo( $ccs_card->post_name ) ) : ?>
				<div class="svc-card__media">
					<?php echo ccs_service_card_photo( $ccs_card->post_name, '(min-width: 1280px) 400px, (min-width: 860px) 31vw, 92vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_service_card_photo(). ?>
				</div>
			<?php else : ?>
				<?php // No photo yet: the service's icon on the tint, in the photo's place, so every card in a row is the same height. ?>
				<div class="svc-card__media svc-card__media--icon" aria-hidden="true" data-ccs-exception="rule 1: stands in for the card's photo until one is chosen; the whole card is the link"><?php echo ccs_icon( ccs_service_icon( $ccs_card->post_name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></div>
			<?php endif; ?>
			<?php
			// The starting price as a tag on the photo (as on the homepage).
			echo function_exists( 'ccs_service_price_tag' ) ? ccs_service_price_tag( $ccs_card->post_name ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper.
			?>
			<div class="svc-card__body">
				<span class="ccs-badge ccs-badge--ring svc-card__badge"><?php echo ccs_icon( ccs_service_icon( $ccs_card->post_name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<h3 class="svc-card__title">
					<a href="<?php echo esc_url( get_permalink( $ccs_card ) ); ?>" class="svc-card__link"><?php echo esc_html( $ccs_card_title ); ?></a>
				</h3>
				<?php if ( $ccs_card_text ) : ?>
					<p class="svc-card__text"><?php echo esc_html( $ccs_card_text ); ?></p>
				<?php endif; ?>
				<span class="svc-card__cue" aria-hidden="true"><?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span class="svc-card__arrow">&rarr;</span></span>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
