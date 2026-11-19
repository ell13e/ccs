<?php
/**
 * Every published rate, for the Costs page's "Every rate" item (Nov 2026).
 *
 * Replaces the wide rate table there, which the client called "awful": two
 * number columns pushed far from their labels across a 900px panel, eight
 * rows, then the extra charges repeated (they have their own item below).
 *
 * Four even cards, two rates each, each rate with its weekday and weekend
 * price side by side (the client's choice over a narrow list). The night
 * rates are shared between two cards (sleep-in, waking) so no card is longer
 * than another; three cards with two, two and four rows were rejected
 * earlier as uneven.
 *
 * Figures: ccs_fee_schedule() (Customizer > Care Costs). Nothing typed here.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_rl_all = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
if ( empty( $ccs_rl_all ) ) {
	return;
}

$ccs_rl_date   = get_theme_mod( 'ccs_cost_effective_from', __( 'Rates from 1 September 2026', 'ccs-wp-theme' ) );

// Groups for display: hourly groups as they are; night rows grouped by kind of night.
$ccs_rl_groups = array();
foreach ( $ccs_rl_all as $ccs_rl_name => $ccs_rl_rows ) {
	if ( ! $ccs_rl_rows[0]['night'] ) {
		$ccs_rl_groups[] = array(
			'name' => $ccs_rl_name,
			'per'  => __( 'an hour', 'ccs-wp-theme' ),
			'rows' => $ccs_rl_rows,
		);
		continue;
	}
	$ccs_rl_nights = array();
	foreach ( $ccs_rl_rows as $ccs_rl_row ) {
		$ccs_rl_kind = ucfirst( trim( preg_replace( '/complex\s*/i', '', $ccs_rl_row['label'] ) ) );
		$ccs_rl_nights[ $ccs_rl_kind ][] = $ccs_rl_row;
	}
	foreach ( $ccs_rl_nights as $ccs_rl_kind => $ccs_rl_kind_rows ) {
		$ccs_rl_groups[] = array(
			'name' => $ccs_rl_kind . 's',
			'per'  => __( 'a night', 'ccs-wp-theme' ),
			'rows' => $ccs_rl_kind_rows,
		);
	}
}
?>
<div class="rate-list rate-list--cards">
	<p class="rate-list__date"><?php echo esc_html( $ccs_rl_date ); ?></p>
	<div class="rate-list__groups">
		<?php foreach ( $ccs_rl_groups as $ccs_rl_group ) : ?>
			<section class="rate-list__group" data-ccs-exception="rule 1: one kind of care and its rates, a price card people compare side by side">
				<h3 class="rate-list__name"><?php echo esc_html( $ccs_rl_group['name'] ); ?> <span><?php echo esc_html( $ccs_rl_group['per'] ); ?></span></h3>
				<dl class="rate-list__rows">
					<?php foreach ( $ccs_rl_group['rows'] as $ccs_rl_row ) : ?>
						<?php
						/*
						 * One line a rate: the name, and "from" its weekday price on the
						 * right. The weekend price follows on a small second line only
						 * where it differs. The first version printed "from £x Mon to Fri"
						 * and "from £x Sat and Sun" under every name, even when the two
						 * were the same; the client called it ugly (Nov 2026).
						 */
						$ccs_rl_weekend = '' !== $ccs_rl_row['weekend'] && $ccs_rl_row['weekend'] !== $ccs_rl_row['weekday'];
						?>
						<div class="rate-list__row">
							<dt><?php echo esc_html( $ccs_rl_row['label'] ); ?></dt>
							<dd>
								<span class="rate-list__price"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong><?php echo esc_html( $ccs_rl_row['weekday'] ); ?></strong></span>
								<?php if ( $ccs_rl_weekend ) : ?>
									<span class="rate-list__weekend"><?php echo esc_html( $ccs_rl_row['weekend'] ); ?> <?php esc_html_e( 'Sat and Sun', 'ccs-wp-theme' ); ?></span>
								<?php endif; ?>
							</dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</section>
		<?php endforeach; ?>
	</div>
</div>
