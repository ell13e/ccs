<?php
/**
 * The published rates as one table (Nov 2026).
 *
 * Replaces the three rate cards, which the client found uneven and messy:
 * the cards had two, two and four rows, so they never lined up. One panel,
 * one set of columns (weekdays, weekends), and the rates grouped under small
 * headings, with the extra charges as a short list beneath. Used by the
 * homepage costs section (every group) and by each service page (only that
 * service's groups, in a section of its own with the quote note).
 *
 * Figures: ccs_fee_schedule() and ccs_fee_extras() (inc/content-sections.php),
 * edited under Customizer > Care Costs.
 *
 * Args: 'service' (slug) to show only that service's groups as a page section.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_rt_all     = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
$ccs_rt_service = isset( $args['service'] ) ? (string) $args['service'] : '';
$ccs_rt_groups  = $ccs_rt_all;

if ( '' !== $ccs_rt_service ) {
	$ccs_rt_want   = ccs_fee_groups_for_service( $ccs_rt_service );
	$ccs_rt_groups = $ccs_rt_want ? array_intersect_key( $ccs_rt_all, array_flip( $ccs_rt_want ) ) : array();
}

if ( empty( $ccs_rt_groups ) ) {
	return;
}

$ccs_rt_extras = function_exists( 'ccs_fee_extras' ) ? ccs_fee_extras() : array();
$ccs_rt_date   = get_theme_mod( 'ccs_cost_effective_from', __( 'Rates from 1 September 2026', 'ccs-wp-theme' ) );
?>

<?php if ( '' !== $ccs_rt_service ) : ?>
<section class="ccs-section service-costs" aria-labelledby="service-costs-heading">
	<div class="container container--lg">
		<div class="service-costs__grid">
			<header class="ccs-head service-costs__head">
				<h2 id="service-costs-heading" class="ccs-title">
					<?php
					/* translators: %s: service name. */
					echo esc_html( sprintf( __( 'What %s costs', 'ccs-wp-theme' ), mb_strtolower( get_the_title() ) ) );
					?>
				</h2>
				<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
			</header>
<?php endif; ?>

			<div class="rate-panel" data-ccs-exception="rule 1: a rate sheet people compare and screenshot, a distinct object">
				<table class="rate-table">
					<caption class="rate-table__caption"><?php echo esc_html( $ccs_rt_date ); ?></caption>
					<thead>
						<tr>
							<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Care', 'ccs-wp-theme' ); ?></span></th>
							<th scope="col"><?php esc_html_e( 'Mon–Fri', 'ccs-wp-theme' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Sat–Sun', 'ccs-wp-theme' ); ?></th>
						</tr>
					</thead>
					<?php foreach ( $ccs_rt_groups as $ccs_group => $ccs_rows ) : ?>
						<?php $ccs_per = $ccs_rows[0]['night'] ? __( 'a night, fixed', 'ccs-wp-theme' ) : __( 'an hour', 'ccs-wp-theme' ); ?>
						<tbody>
							<tr class="rate-table__group">
								<th scope="colgroup" colspan="3">
									<?php echo esc_html( $ccs_group ); ?>
									<span class="rate-table__per"><?php echo esc_html( $ccs_per ); ?></span>
								</th>
							</tr>
							<?php foreach ( $ccs_rows as $ccs_row ) : ?>
								<tr>
									<th scope="row"><?php echo esc_html( $ccs_row['label'] ); ?></th>
									<td><?php echo esc_html( $ccs_row['weekday'] ); ?></td>
									<td><?php echo esc_html( '' !== $ccs_row['weekend'] ? $ccs_row['weekend'] : '–' ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					<?php endforeach; ?>
				</table>

				<?php if ( ! empty( $ccs_rt_extras ) ) : ?>
					<div class="rate-extras">
						<h3 class="rate-extras__title"><?php echo esc_html( ccs_copy( 'costs_extras_heading' ) ); ?></h3>
						<ul class="rate-extras__list" role="list">
							<?php foreach ( $ccs_rt_extras as $ccs_row ) : ?>
								<li>
									<span><?php echo esc_html( $ccs_row['label'] ); ?></span>
									<?php if ( '' !== $ccs_row['price'] ) : ?>
										<span class="rate-extras__price"><?php echo esc_html( $ccs_row['price'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>

<?php if ( '' !== $ccs_rt_service ) : ?>
		</div>
	</div>
</section>
<?php endif; ?>
