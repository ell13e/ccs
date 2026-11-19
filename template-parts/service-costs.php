<?php
/**
 * What a service costs, on its own page (Nov 2026).
 *
 * This was a title-left intro beside the full rate table (weekday and
 * weekend columns, then four extra charges). The client rejected it ("this
 * too", of a section she had already called ugly and complex elsewhere) and
 * rejects title-left intros. Now: a centred heading with the quote note
 * under it, one tint panel of this service's prices, one line each, and a
 * quiet link to the Costs page, which holds every rate and the extra charges.
 *
 * The lines are the same ones the Costs page's cards show, from the same
 * source: ccs_fee_schedule() (Customizer > Care Costs). Nothing is typed here.
 *
 * Args: 'service' (slug).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_sc_slug   = isset( $args['service'] ) ? (string) $args['service'] : '';
$ccs_sc_all    = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
$ccs_sc_want   = function_exists( 'ccs_fee_groups_for_service' ) ? ccs_fee_groups_for_service( $ccs_sc_slug ) : array();
$ccs_sc_groups = $ccs_sc_want ? array_intersect_key( $ccs_sc_all, array_flip( $ccs_sc_want ) ) : array();

if ( empty( $ccs_sc_groups ) ) {
	return;
}

$ccs_sc_lines = array();
foreach ( $ccs_sc_groups as $ccs_sc_rows ) {
	if ( ! $ccs_sc_rows[0]['night'] ) {
		$ccs_sc_lines[] = array( __( 'Weekdays, daytime', 'ccs-wp-theme' ), $ccs_sc_rows[0]['weekday'], __( 'an hour', 'ccs-wp-theme' ) );
		if ( '' !== $ccs_sc_rows[0]['weekend'] ) {
			$ccs_sc_lines[] = array( __( 'Weekends, daytime', 'ccs-wp-theme' ), $ccs_sc_rows[0]['weekend'], __( 'an hour', 'ccs-wp-theme' ) );
		}
		continue;
	}
	foreach ( $ccs_sc_rows as $ccs_sc_row ) {
		// Every figure carries "from" (below), so a night that costs more at the weekend needs no note of its own.
		$ccs_sc_lines[] = array( $ccs_sc_row['label'], $ccs_sc_row['weekday'], __( 'a night', 'ccs-wp-theme' ) );
	}
}

$ccs_sc_costs = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'costs-and-funding' ) : home_url( '/costs-and-funding/' );
?>
<?php if ( ! empty( $args['bare'] ) ) : // The panel alone, under its own small title, for a split layout. ?>
	<div class="service-costs service-costs--bare">
		<h2 class="service-plain__title">
			<?php
			/* translators: %s: service name. */
			echo esc_html( sprintf( __( 'What %s costs', 'ccs-wp-theme' ), mb_strtolower( get_the_title() ) ) );
			?>
		</h2>
		<div class="service-costs__panel<?php echo empty( $args['on_white'] ) ? ' service-costs__panel--white' : ''; ?>" data-ccs-exception="rule 1: this service's prices, one object a family reads and compares">
			<ul class="service-costs__lines" role="list">
				<?php foreach ( $ccs_sc_lines as $ccs_sc_line ) : ?>
					<li>
						<span class="service-costs__what"><?php echo esc_html( $ccs_sc_line[0] ); ?></span>
						<span class="service-costs__amount"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong><?php echo esc_html( $ccs_sc_line[1] ); ?></strong> <?php echo esc_html( $ccs_sc_line[2] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="btn btn-quiet service-costs__link" href="<?php echo esc_url( $ccs_sc_costs ); ?>"><?php esc_html_e( 'See every rate and extra charge', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
		</div>
	</div>
	<?php return; ?>
<?php endif; ?>
<section class="ccs-section service-costs" aria-labelledby="service-costs-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center">
			<h2 id="service-costs-heading" class="ccs-title">
				<?php
				/* translators: %s: service name. */
				echo esc_html( sprintf( __( 'What %s costs', 'ccs-wp-theme' ), mb_strtolower( get_the_title() ) ) );
				?>
			</h2>
			<p class="ccs-lede"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
		</header>

		<div class="service-costs__panel" data-ccs-exception="rule 1: this service's prices, one object a family reads and compares">
			<ul class="service-costs__lines" role="list">
				<?php foreach ( $ccs_sc_lines as $ccs_sc_line ) : ?>
					<li>
						<span class="service-costs__what"><?php echo esc_html( $ccs_sc_line[0] ); ?></span>
						<?php // "from" before every figure (client, Nov 2026: "make sure it says from"). ?>
						<span class="service-costs__amount"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong><?php echo esc_html( $ccs_sc_line[1] ); ?></strong> <?php echo esc_html( $ccs_sc_line[2] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="btn btn-quiet service-costs__link" href="<?php echo esc_url( $ccs_sc_costs ); ?>"><?php esc_html_e( 'See every rate and extra charge', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
		</div>
	</div>
</section>
