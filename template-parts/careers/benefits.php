<?php
/**
 * What staff get: a card that moves through the benefits one group at a
 * time, beside a photo with the referral scheme on a tab over its corner.
 *
 * Sixth layout (Nov 2026). The card is the comment rotator from the "What
 * new starters tell us" section above (same arrows, dots and cross-fade),
 * which is the piece the user called "really good"; here each turn shows one
 * group: its icon, its name and three to five short benefits. Sixteen ticks
 * in two columns beside the same photo "sucked": too much at once. Before
 * that: lists on a deep plum band, four icon cards, a long checklist, and a
 * changing headline over chips.
 *
 * The list is the "Advantages of joining our team" list from the company's
 * real job adverts, supplied by the user (Nov 2026), grouped and nothing
 * added. It replaced a list drawn from the induction deck, which had items
 * the adverts do not promise. Role-specific terms (salary, on-call pay) are
 * left to each advert. Customize > Page wording > Careers: one group per
 * line, as Group | benefit; benefit; benefit.
 * Without the script the groups stack, each under its heading.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// One group per line: "Group | benefit; benefit; benefit".
$ccs_ben_groups = array();
foreach ( preg_split( '/\r\n|\r|\n/', ccs_copy( 'careers_benefits' ) ) as $ccs_ben_line ) {
	$ccs_ben_bits = array_map( 'trim', explode( '|', $ccs_ben_line, 2 ) );
	if ( 2 !== count( $ccs_ben_bits ) || '' === $ccs_ben_bits[0] ) {
		continue;
	}
	$ccs_ben_list = array_values( array_filter( array_map( 'trim', explode( ';', $ccs_ben_bits[1] ) ) ) );
	if ( ! empty( $ccs_ben_list ) ) {
		$ccs_ben_groups[] = array( $ccs_ben_bits[0], $ccs_ben_list );
	}
}
if ( empty( $ccs_ben_groups ) ) {
	return;
}
$ccs_ben_referral = trim( ccs_copy( 'careers_benefits_referral' ) );
$ccs_ben_count    = count( $ccs_ben_groups );
?>
<section class="ccs-section ccs-section--flush-bottom careers-benefits" aria-labelledby="careers-benefits-heading">
	<div class="container container--lg">
		<div class="ccs-media-split careers-benefits__layout">
			<div class="careers-benefits__text">
				<h2 id="careers-benefits-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'careers_benefits_title' ) ); ?></h2>

				<div class="quote-rotator benefit-rotator" data-ccs-stepper>
					<div class="quote-rotator__panels">
						<?php foreach ( $ccs_ben_groups as $ccs_ben_i => $ccs_ben_group ) : ?>
							<div class="quote-rotator__panel benefit-rotator__panel" role="tabpanel" id="benefit-panel-<?php echo (int) $ccs_ben_i; ?>" aria-labelledby="benefit-dot-<?php echo (int) $ccs_ben_i; ?>">
								<div class="benefit-rotator__head">
									<h3 class="benefit-rotator__title"><?php echo esc_html( $ccs_ben_group[0] ); ?></h3>
								</div>
								<ul class="benefit-rotator__list" role="list">
									<?php foreach ( $ccs_ben_group[1] as $ccs_ben_item ) : ?>
										<li><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><?php echo esc_html( $ccs_ben_item ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ( $ccs_ben_count > 1 ) : ?>
						<div class="quote-rotator__controls">
							<button type="button" class="quote-rotator__arrow" data-ccs-step-prev aria-label="<?php esc_attr_e( 'Previous group of benefits', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&larr;</span></button>
							<div class="quote-rotator__dots" role="tablist" aria-label="<?php esc_attr_e( 'Groups of benefits', 'ccs-wp-theme' ); ?>">
								<?php foreach ( $ccs_ben_groups as $ccs_ben_i => $ccs_ben_group ) : ?>
									<button type="button" class="quote-rotator__dot" role="tab" id="benefit-dot-<?php echo (int) $ccs_ben_i; ?>" aria-controls="benefit-panel-<?php echo (int) $ccs_ben_i; ?>" aria-selected="<?php echo 0 === $ccs_ben_i ? 'true' : 'false'; ?>">
										<span class="screen-reader-text"><?php echo esc_html( $ccs_ben_group[0] ); ?></span>
									</button>
								<?php endforeach; ?>
							</div>
							<button type="button" class="quote-rotator__arrow" data-ccs-step-next aria-label="<?php esc_attr_e( 'Next group of benefits', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&rarr;</span></button>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="careers-benefits__media">
				<?php
				// A carer with a client, not another shot from the training-room session: the same few faces were on every careers page (user).
				ccs_section_photo(
					'site-photos/467212766_9676172959062903_646005181027754761_n-copy.webp',
					array(
						'alt'   => __( 'A carer and a client smiling together', 'ccs-wp-theme' ),
						'sizes' => '(min-width: 900px) 480px, 92vw',
						'class' => 'careers-benefits__photo',
						// Keeps the carer's face in frame; the centre crop cut it at the edge.
						'position' => '0% 35%',
					)
				);
				?>
				<?php if ( '' !== $ccs_ben_referral ) : ?>
					<?php // The referral bonus on the tab over the photo: an offer with terms, set apart from the standing benefits. ?>
					<p class="careers-benefits__badge" data-ccs-exception="rule 1: the referral offer, set apart on the photo like the rating tab in the section above"><?php $ccs_ben_ref_bits = array_map( 'trim', explode( '|', $ccs_ben_referral, 3 ) ); ?><?php if ( count( $ccs_ben_ref_bits ) > 1 ) : ?><strong><?php echo esc_html( $ccs_ben_ref_bits[0] ); ?></strong><span><?php echo esc_html( $ccs_ben_ref_bits[1] ); ?></span><?php if ( ! empty( $ccs_ben_ref_bits[2] ) ) : ?><small><?php echo esc_html( $ccs_ben_ref_bits[2] ); ?></small><?php endif; ?><?php else : ?><span><?php echo esc_html( $ccs_ben_referral ); ?></span><?php endif; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
