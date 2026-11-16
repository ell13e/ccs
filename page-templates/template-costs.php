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
	?>

	<?php
	$ccs_cc_page = get_page_by_path( 'complex-care', OBJECT, 'service' );
	$ccs_links   = array(
		'Help at home' => array( function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/' ), __( 'About care at home', 'ccs-wp-theme' ) ),
		'Complex care' => array( $ccs_cc_page ? get_permalink( $ccs_cc_page ) : home_url( '/' ), __( 'About complex care', 'ccs-wp-theme' ) ),
		'Nights'       => array( '#every-rate', __( 'See every rate', 'ccs-wp-theme' ) ),
	);
	?>
	<section class="ccs-section costs-rates" aria-labelledby="costs-rates-heading">
		<div class="container container--lg">
			<header class="costs-head">
				<h2 id="costs-rates-heading" class="ccs-title"><?php esc_html_e( 'Our published rates', 'ccs-wp-theme' ); ?></h2>
				<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
			</header>

			<?php get_template_part( 'template-parts/price-cards', null, array( 'links' => $ccs_links ) ); ?>

			<details class="costs-detail" id="every-rate">
				<summary><?php esc_html_e( 'See every rate, weekdays and weekends', 'ccs-wp-theme' ); ?></summary>
				<?php get_template_part( 'template-parts/rate-table' ); ?>
			</details>
		</div>
	</section>

	<?php
	/*
	 * Weekly cost estimator. Hidden until assets/js/cost-estimator.js runs,
	 * so a visitor without JavaScript never sees controls that do nothing;
	 * the rate table above still answers the question for them.
	 */
	$ccs_est_rates = function_exists( 'ccs_estimator_rates' ) ? ccs_estimator_rates() : array();
	?>
	<?php if ( ! empty( $ccs_est_rates ) ) : ?>
		<section id="costs-estimate" class="ccs-section ccs-section--tint costs-estimate" aria-labelledby="costs-estimate-heading" data-ccs-estimator data-rates="<?php echo esc_attr( wp_json_encode( $ccs_est_rates ) ); ?>" hidden>
			<div class="container container--lg">
				<header class="ccs-head">
					<h2 id="costs-estimate-heading" class="ccs-title"><?php esc_html_e( 'Estimate your weekly cost', 'ccs-wp-theme' ); ?></h2>
					<p class="ccs-lede"><?php esc_html_e( 'Choose the kind of care and the hours you think you’ll need, at our published daytime rates.', 'ccs-wp-theme' ); ?></p>
				</header>

				<?php
				/*
				 * One calculator card (client, Nov 2026: the two-column estimator
				 * "needs work"): the three controls in a row, then the answer in a
				 * band beneath them, with the note and the next step.
				 */
				?>
				<div class="calc" data-ccs-exception="rule 1: the calculator, one tool">
					<div class="calc__controls">
						<fieldset class="calc__field">
							<legend class="calc__label"><?php esc_html_e( 'Kind of care', 'ccs-wp-theme' ); ?></legend>
							<div class="estimator__kinds">
								<?php foreach ( $ccs_est_rates as $ccs_i => $ccs_rate ) : ?>
									<label class="estimator__kind">
										<input type="radio" name="est_kind" value="<?php echo (int) $ccs_i; ?>"<?php checked( 0, $ccs_i ); ?>>
										<span><?php echo esc_html( $ccs_rate['name'] ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>

						<?php
						$ccs_steppers = array(
							array( 'id' => 'est-weekday', 'name' => 'est_weekday', 'label' => __( 'Weekday hours', 'ccs-wp-theme' ), 'hint' => __( 'Mon–Fri, 7am–10pm, each week', 'ccs-wp-theme' ), 'value' => 5, 'max' => 75 ),
							array( 'id' => 'est-weekend', 'name' => 'est_weekend', 'label' => __( 'Weekend hours', 'ccs-wp-theme' ), 'hint' => __( 'Sat–Sun, 7am–10pm, each week', 'ccs-wp-theme' ), 'value' => 0, 'max' => 30 ),
						);
						?>
						<?php foreach ( $ccs_steppers as $ccs_st ) : ?>
							<div class="calc__field">
								<label class="calc__label" for="<?php echo esc_attr( $ccs_st['id'] ); ?>"><?php echo esc_html( $ccs_st['label'] ); ?></label>
								<div class="estimator__stepper">
									<button type="button" class="estimator__step" data-step="-1" aria-controls="<?php echo esc_attr( $ccs_st['id'] ); ?>">
										<span aria-hidden="true">&minus;</span><span class="screen-reader-text"><?php esc_html_e( 'One hour less', 'ccs-wp-theme' ); ?></span>
									</button>
									<input type="number" id="<?php echo esc_attr( $ccs_st['id'] ); ?>" name="<?php echo esc_attr( $ccs_st['name'] ); ?>" class="estimator__input" value="<?php echo (int) $ccs_st['value']; ?>" min="0" max="<?php echo (int) $ccs_st['max']; ?>" step="1" inputmode="numeric" aria-describedby="<?php echo esc_attr( $ccs_st['id'] ); ?>-hint">
									<button type="button" class="estimator__step" data-step="1" aria-controls="<?php echo esc_attr( $ccs_st['id'] ); ?>">
										<span aria-hidden="true">+</span><span class="screen-reader-text"><?php esc_html_e( 'One hour more', 'ccs-wp-theme' ); ?></span>
									</button>
								</div>
								<p class="calc__hint" id="<?php echo esc_attr( $ccs_st['id'] ); ?>-hint"><?php echo esc_html( $ccs_st['hint'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="calc__result" aria-live="polite">
						<div class="calc__total">
							<p class="calc__total-label"><?php esc_html_e( 'Estimated cost a week', 'ccs-wp-theme' ); ?></p>
							<p class="calc__week" data-est="week">£0.00</p>
							<p class="calc__month">
								<?php
								printf(
									/* translators: %s: monthly amount. */
									esc_html__( 'About %s a month', 'ccs-wp-theme' ),
									'<span data-est="month">£0</span>'
								);
								?>
							</p>
						</div>
						<ul class="calc__lines" role="list">
							<li data-est="weekday-line"></li>
							<li data-est="weekend-line"></li>
						</ul>
						<div class="calc__action">
							<a class="btn btn-primary" href="<?php echo esc_url( $ccs_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
						</div>
					</div>
					<p class="calc__note"><?php esc_html_e( 'An estimate, not a quote. It leaves out nights, bank holidays and the one-off £30 set-up fee.', 'ccs-wp-theme' ); ?></p>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php $ccs_holidays = function_exists( 'ccs_bank_holidays' ) ? ccs_bank_holidays() : array(); ?>
	<?php if ( ! empty( $ccs_holidays ) ) : ?>
		<section class="ccs-section costs-holidays" aria-labelledby="costs-holidays-heading">
			<div class="container container--lg">
				<header class="costs-head">
					<h2 id="costs-holidays-heading" class="ccs-title"><?php esc_html_e( 'Bank holidays this year', 'ccs-wp-theme' ); ?></h2>
					<p class="rate-note"><?php esc_html_e( 'Double our usual rate, or treble on Christmas Day, Boxing Day, New Year’s Day and New Year’s Eve after 7pm.', 'ccs-wp-theme' ); ?></p>
				</header>
				<ul class="holiday-tiles" role="list">
					<?php foreach ( $ccs_holidays as $ccs_day ) : ?>
						<?php
						$ccs_time   = strtotime( $ccs_day['date'] );
						$ccs_treble = __( 'Treble', 'ccs-wp-theme' ) === $ccs_day['rate'];
						?>
						<li class="holiday-tile" data-ccs-exception="rule 1: each tile is one date, read like a calendar">
							<span class="holiday-tile__month"><?php echo esc_html( $ccs_time ? wp_date( 'M Y', $ccs_time, new DateTimeZone( 'UTC' ) ) : '' ); ?></span>
							<span class="holiday-tile__day"><?php echo esc_html( $ccs_time ? wp_date( 'j', $ccs_time, new DateTimeZone( 'UTC' ) ) : $ccs_day['date'] ); ?></span>
							<span class="screen-reader-text"><?php echo esc_html( $ccs_day['day'] ); ?></span>
							<span class="holiday-tile__name"><?php echo esc_html( $ccs_day['name'] ); ?></span>
							<span class="holiday-tile__rate<?php echo $ccs_treble ? ' holiday-tile__rate--treble' : ''; ?>"><?php echo esc_html( $ccs_day['rate'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<section class="ccs-section ccs-section--tint costs-pay" aria-labelledby="costs-pay-heading">
		<div class="container container--lg">
			<div class="costs-split">
				<header class="ccs-head">
					<h2 id="costs-pay-heading" class="ccs-title"><?php esc_html_e( 'Ways to pay for care', 'ccs-wp-theme' ); ?></h2>
					<p class="ccs-lede"><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
					<p class="costs-pay__more">
						<a class="ccs-text-link" href="<?php echo esc_url( $ccs_faqs ); ?>"><?php esc_html_e( 'Read our funding and payment FAQs', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</p>
				</header>
				<ul class="pay-list" role="list">
					<?php foreach ( $ccs_pay_ways as $ccs_way ) : ?>
						<li class="pay-list__item">
							<span class="ccs-badge ccs-badge--sm" aria-hidden="true"><?php echo ccs_icon( $ccs_way['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<div>
								<h3 class="pay-list__title"><?php echo esc_html( $ccs_way['title'] ); ?></h3>
								<p class="pay-list__text"><?php echo esc_html( $ccs_way['text'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

</main>

<?php
get_footer();
