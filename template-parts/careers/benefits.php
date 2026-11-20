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
				<h2 id="careers-benefits-heading" class="ccs-title"><?php echo ccs_copy_accent( 'careers_benefits_title' ); ?></h2>
				<?php
				/*
				 * The groups of benefits in the site's one accordion (Nov 2026,
				 * careers second pass). They were a carousel: one group showing,
				 * four behind arrows and dots. Now every group's name is on the
				 * page, the first open, each benefit ticked.
				 */
				$ccs_ben_items = array();
				foreach ( $ccs_ben_groups as $ccs_ben_group ) {
					$ccs_ben_body = '<ul class="tick-list" role="list">';
					foreach ( $ccs_ben_group[1] as $ccs_ben_item ) {
						$ccs_ben_body .= '<li>' . ccs_icon( 'check' ) . '<span>' . esc_html( $ccs_ben_item ) . '</span></li>';
					}
					$ccs_ben_items[] = array( 'heading' => $ccs_ben_group[0], 'body' => $ccs_ben_body . '</ul>' );
				}
				get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_ben_items, 'name' => 'benefits' ) );
				?>
			</div>
			<div class="careers-benefits__media">
				<?php
				// A carer with a client, not another shot from the training-room session: the same few faces were on every careers page (user). Used on no other page.
				ccs_section_photo(
					'site-photos/carer-and-client-selfie.webp',
					array(
						'alt'   => __( 'Two women smiling together for a selfie', 'ccs-wp-theme' ),
						'sizes' => '(min-width: 900px) 480px, 92vw',
						'class' => 'careers-benefits__photo',
												'position' => '50% 35%',
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
