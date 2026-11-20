<?php
/**
 * Template Name: Costs and funding
 *
 * Every published rate, what affects the price, and the ways care can be paid
 * for (Nov 2026). The full table moved here from the homepage at the client's
 * request; the homepage keeps a short section that links to this page.
 *
 * Sections (Nov 2026, the client-approved mockup): page hero (with the CQC
 * card); price cards and the extra charges, with every rate behind "See every
 * rate" (white); a weekly cost estimator (tint); this year's bank holidays as
 * calendar tiles (white); ways to pay as one list (tint); the closing card.
 *
 * Rates come from the Customizer (Care Costs), printed by
 * template-parts/rate-table.php. The funding routes are the ones the agency
 * lists on its own homecare.co.uk profile: private funding, direct payments,
 * local authority (contracted provider) and NHS (contracted provider).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
$ccs_faqs    = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'faqs' ) : home_url( '/faqs/' );

$ccs_pay_ways = array(
	array(
		'icon'  => 'home',
		'title' => __( 'Paying for yourself', 'ccs-wp-theme' ),
		'text'  => __( 'You pay us directly for the care you choose, at the rates on this page.', 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'badge',
		'title' => __( 'Direct payments', 'ccs-wp-theme' ),
		'text'  => __( 'If the council or the NHS gives you a direct payment, you can use it to pay for your care with us.', 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'people',
		'title' => __( 'Care arranged by the council', 'ccs-wp-theme' ),
		'text'  => __( 'Where your local council arranges and pays for your care, we can be the provider.', 'ccs-wp-theme' ),
	),
	array(
		'icon'  => 'pulse',
		'title' => __( 'NHS funding', 'ccs-wp-theme' ),
		'text'  => __( 'For people with ongoing, complex health needs, the NHS may pay for care at home, which we can provide.', 'ccs-wp-theme' ),
	),
);
?>

<main id="main" class="site-main site-main--costs" role="main">

	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-header' );
	endwhile;

	/*
	 * Fifth form of this page (Nov 2026). The client rejected, section by
	 * section, the photo price cards (two 550px photos stretched across half
	 * the page each), four ways to pay as columns of text in a box, and two
	 * lists side by side. She was then shown three sketches and chose this
	 * one: a single rate card you use. Pick the kind of care and the days,
	 * and the figures change. The charges on top of the rate run along the
	 * card's foot, the bank holiday dates are one tap away, and the ways to
	 * pay are the homepage's numbered accordion beside one photograph.
	 *
	 * Every figure is read from the Customizer (Care Costs). Without script
	 * the card shows the first kind of care at weekday rates.
	 */
	$ccs_fees   = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
	$ccs_finder = array();
	$ccs_nights = array();
	foreach ( $ccs_fees as $ccs_group => $ccs_rows ) {
		if ( $ccs_rows[0]['night'] ) {
			$ccs_nights = array_merge( $ccs_nights, $ccs_rows );
			continue;
		}
		foreach ( $ccs_rows as $ccs_row ) {
			$ccs_finder[ $ccs_group ][ $ccs_row['label'] ] = array(
				'weekday' => $ccs_row['weekday'],
				'weekend' => '' !== $ccs_row['weekend'] ? $ccs_row['weekend'] : $ccs_row['weekday'],
				'unit'    => __( 'an hour', 'ccs-wp-theme' ),
			);
		}
	}
	// Night rates go to the care they belong to: the complex ones to complex care.
	foreach ( $ccs_nights as $ccs_row ) {
		$ccs_is_complex = (bool) preg_match( '/complex/i', $ccs_row['label'] );
		$ccs_kind       = ucfirst( trim( preg_replace( '/complex\s*/i', '', $ccs_row['label'] ) ) );
		foreach ( array_keys( $ccs_finder ) as $ccs_group ) {
			if ( $ccs_is_complex === (bool) preg_match( '/complex/i', $ccs_group ) ) {
				$ccs_finder[ $ccs_group ][ $ccs_kind ] = array(
					'weekday' => $ccs_row['weekday'],
					'weekend' => '' !== $ccs_row['weekend'] ? $ccs_row['weekend'] : $ccs_row['weekday'],
					'unit'    => __( 'a night', 'ccs-wp-theme' ),
				);
				break;
			}
		}
	}
	$ccs_cares    = array_keys( $ccs_finder );
	$ccs_times    = $ccs_cares ? array_keys( $ccs_finder[ $ccs_cares[0] ] ) : array();
	$ccs_first    = ( $ccs_cares && $ccs_times ) ? $ccs_finder[ $ccs_cares[0] ][ $ccs_times[0] ] : null;
	$ccs_extras   = function_exists( 'ccs_fee_extras' ) ? ccs_fee_extras() : array();
	$ccs_holidays = function_exists( 'ccs_bank_holidays' ) ? ccs_bank_holidays() : array();
	$ccs_days     = array(
		'weekday' => __( 'Weekdays', 'ccs-wp-theme' ),
		'weekend' => __( 'Weekends', 'ccs-wp-theme' ),
	);
	?>

	<?php if ( $ccs_first ) : ?>
		<section class="ccs-section costs-rates" aria-labelledby="costs-rates-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="costs-rates-heading" class="ccs-title"><?php esc_html_e( 'Our rates', 'ccs-wp-theme' ); ?></h2>
				</header>

				<?php
				/*
				 * Simplified after "too messy" (client, same day): the first build
				 * had nine pills in three rows, a note, a strip lapping the card and
				 * two buttons under it. Now one object: two switches (kind of care,
				 * days), the daytime rate large with the other three rates listed
				 * beside it, and the extra charges along the card's foot. Every
				 * rate is reachable from the two switches, so the "See every rate"
				 * list is gone.
				 */
				$ccs_main_time = $ccs_times[0];
				$ccs_sets      = array(
					'care' => array( __( 'Kind of care', 'ccs-wp-theme' ), array_combine( $ccs_cares, $ccs_cares ) ),
					'days' => array( __( 'Days', 'ccs-wp-theme' ), $ccs_days ),
				);
				?>
				<div class="rate-finder" data-ccs-rate-finder data-ccs-exception="rule 1: the rates, one card a family uses to find their own">
					<div class="rate-finder__switches">
						<?php foreach ( $ccs_sets as $ccs_set => $ccs_def ) : ?>
							<fieldset class="rate-finder__set">
								<legend class="screen-reader-text"><?php echo esc_html( $ccs_def[0] ); ?></legend>
								<div class="rate-finder__options">
									<?php $ccs_n = 0; ?>
									<?php foreach ( $ccs_def[1] as $ccs_value => $ccs_label ) : ?>
										<label class="rate-finder__option">
											<input type="radio" name="rate-<?php echo esc_attr( $ccs_set ); ?>" value="<?php echo esc_attr( $ccs_value ); ?>"<?php checked( 0 === $ccs_n++ ); ?>>
											<span><?php echo esc_html( $ccs_label ); ?></span>
										</label>
									<?php endforeach; ?>
								</div>
							</fieldset>
						<?php endforeach; ?>
					</div>

					<div class="rate-finder__main">
						<div class="rate-finder__result" aria-live="polite">
							<?php
							// Two lines, the client's own layout (Nov 2026): "7am to 10pm
							// from", then the figure with "/hour" beside it. The label is set
							// like the row labels beside it and the small words like theirs,
							// so the block and the rows read as one list with its first rate
							// large. Four equal figures across was tried and "looks worse".
							// "from" before every figure (client: "make sure it says from").
							$ccs_main_unit = '/' . preg_replace( '/^an?\s+/i', '', (string) $ccs_first['unit'] );
							?>
							<p class="rate-finder__when"><?php echo esc_html( $ccs_main_time ); ?> <span class="rate-finder__from"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?></span></p>
							<p class="rate-finder__price">
								<span class="rate-finder__figure" data-rate-line="<?php echo esc_attr( $ccs_main_time ); ?>"><?php echo esc_html( $ccs_first['weekday'] ); ?></span>
								<span class="rate-finder__unit"><?php echo esc_html( $ccs_main_unit ); ?></span>
							</p>
						</div>
						<ul class="rate-finder__lines" role="list">
							<?php foreach ( array_slice( $ccs_times, 1 ) as $ccs_time_label ) : ?>
								<?php $ccs_line = $ccs_finder[ $ccs_cares[0] ][ $ccs_time_label ]; ?>
								<li>
									<span><?php echo esc_html( $ccs_time_label ); ?></span>
									<span class="rate-finder__amount"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong data-rate-line="<?php echo esc_attr( $ccs_time_label ); ?>"><?php echo esc_html( $ccs_line['weekday'] ); ?></strong> <?php echo esc_html( $ccs_line['unit'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<?php if ( $ccs_extras ) : ?>
						<ul class="rate-finder__extras" role="list" aria-label="<?php esc_attr_e( 'Also charged', 'ccs-wp-theme' ); ?>">
							<?php foreach ( array_slice( $ccs_extras, 0, 4 ) as $ccs_extra ) : ?>
								<li>
									<span class="rate-finder__extra-price"><?php echo esc_html( $ccs_extra['price'] ); ?></span>
									<span class="rate-finder__extra-what"><?php echo esc_html( $ccs_extra['label'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php // The "not a quote" line is the card's last row (Nov 2026, client: under the card it sat in the card's shadow at an odd width). ?>
					<p class="rate-finder__note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
					<script type="application/json" data-rate-data><?php echo wp_json_encode( $ccs_finder ); ?></script>
				</div>

				<?php if ( $ccs_holidays ) : ?>
					<details class="rate-more">
						<summary class="rate-more__toggle"><span class="ccs-text-link"><?php esc_html_e( 'See this year’s bank holiday dates', 'ccs-wp-theme' ); ?></span></summary>
						<div class="rate-more__body">
							<ul class="rate-dates" role="list">
								<?php foreach ( $ccs_holidays as $ccs_day ) : ?>
									<?php $ccs_time = strtotime( $ccs_day['date'] ); ?>
									<li>
										<span class="rate-dates__date"><?php echo esc_html( $ccs_time ? wp_date( 'j M Y', $ccs_time, new DateTimeZone( 'UTC' ) ) : $ccs_day['date'] ); ?></span>
										<span class="rate-dates__name"><?php echo esc_html( $ccs_day['name'] ); ?></span>
										<span class="rate-dates__rate"><?php echo esc_html( $ccs_day['rate'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</details>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="ccs-section ccs-section--tint costs-pay" aria-labelledby="costs-pay-heading">
		<div class="container container--lg">
			<div class="photo-acc">
				<figure class="photo-acc__photo" data-ccs-exception="rule 15: one photograph in its frame">
					<?php
					// A photo used nowhere else on the site.
					printf(
						'<img src="%1$s" alt="%2$s" width="900" height="1600" loading="lazy" decoding="async" style="object-position:50%% 12%%"%3$s>',
						esc_url( get_template_directory_uri() . '/assets/images/site-photos/WhatsApp-Image-2025-04-30-at-15.12.21.webp' ),
						esc_attr__( 'A young man in a wheelchair with a football club blanket over his knees, smiling', 'ccs-wp-theme' ),
						ccs_theme_image_srcset( 'site-photos/WhatsApp-Image-2025-04-30-at-15.12.21.webp', '(min-width: 960px) 480px, 92vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
					);
					?>
				</figure>
				<div class="photo-acc__body">
					<h2 id="costs-pay-heading" class="ccs-title"><?php esc_html_e( 'Ways to pay', 'ccs-wp-theme' ); ?></h2>
					<p class="photo-acc__lede"><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
					<?php
					$ccs_pay_items = array();
					foreach ( $ccs_pay_ways as $ccs_way ) {
						$ccs_pay_items[] = array( 'heading' => $ccs_way['title'], 'body' => '<p>' . esc_html( $ccs_way['text'] ) . '</p>' );
					}
					get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_pay_items, 'name' => 'ways-to-pay' ) );
					?>
					<p class="photo-acc__foot"><a class="ccs-text-link" href="<?php echo esc_url( $ccs_faqs ); ?>"><?php esc_html_e( 'Read our funding and payment FAQs', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a></p>
				</div>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

</main>

<?php
get_footer();
