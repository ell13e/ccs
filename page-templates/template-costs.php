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
			<header class="ccs-head ccs-head--center costs-head costs-head--center">
				<h2 id="costs-rates-heading" class="ccs-title"><?php esc_html_e( 'Our rates', 'ccs-wp-theme' ); ?></h2>
				<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
			</header>

			<?php get_template_part( 'template-parts/price-cards', null, array(
					'links'  => $ccs_links,
					'layout' => 'photo',
					'photos' => array(
						'Help at home' => array( 'site-photos/ccs-domiciliary-care.webp', __( 'A carer and the person she supports at home', 'ccs-wp-theme' ) ),
						'Complex care' => array( 'site-photos/ccs-complex-care-maidstone-24-7.webp', __( 'A member of the complex care team with the person they support', 'ccs-wp-theme' ) ),
					),
				) ); ?>

		</div>
	</section>

	<?php
	/*
	 * The detail, one tap away (Nov 2026; the user: "I hate this whole
	 * page"). The page showed everything at once: three price cards, a strip
	 * of extras, a link to a hidden table, a calculator, ten bank-holiday
	 * tiles and four ways to pay. Now: two photo price cards, then
	 * everything else (every rate, the extra charges, bank holidays, ways to
	 * pay) in one closed accordion. The calculator is off the page
	 * (template-parts/cost-estimator.php): with the rates table it read as
	 * "ugly and complex".
	 */
	$ccs_holidays = function_exists( 'ccs_bank_holidays' ) ? ccs_bank_holidays() : array();
	?>
	<section class="ccs-section ccs-section--tint costs-more" aria-labelledby="costs-more-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="costs-more-heading" class="ccs-title"><?php esc_html_e( 'More detail', 'ccs-wp-theme' ); ?></h2>
			</header>
			<div class="ccs-faq costs-more__faq">
				<details class="ccs-faq__item" name="costs-more" id="every-rate">
					<summary class="ccs-faq__q"><?php esc_html_e( 'Every rate, weekdays and weekends', 'ccs-wp-theme' ); ?></summary>
					<div class="ccs-faq__a"><?php get_template_part( 'template-parts/rate-table' ); ?></div>
				</details>
				<?php $ccs_extras = function_exists( 'ccs_fee_extras' ) ? ccs_fee_extras() : array(); ?>
				<?php if ( ! empty( $ccs_extras ) ) : ?>
					<details class="ccs-faq__item" name="costs-more">
						<summary class="ccs-faq__q"><?php esc_html_e( 'Extra charges', 'ccs-wp-theme' ); ?></summary>
						<div class="ccs-faq__a">
							<ul class="holiday-list holiday-list--two" role="list">
								<?php foreach ( $ccs_extras as $ccs_extra ) : ?>
									<li>
										<span class="holiday-list__name"><?php echo esc_html( $ccs_extra['label'] ); ?></span>
										<span class="holiday-list__rate"><?php echo esc_html( $ccs_extra['price'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</details>
				<?php endif; ?>
				<?php if ( ! empty( $ccs_holidays ) ) : ?>
					<details class="ccs-faq__item" name="costs-more">
						<summary class="ccs-faq__q"><?php esc_html_e( 'Bank holidays this year', 'ccs-wp-theme' ); ?></summary>
						<div class="ccs-faq__a">
							<p><?php esc_html_e( 'Double our usual rate, or treble on Christmas Day, Boxing Day, New Year’s Day and New Year’s Eve after 7pm.', 'ccs-wp-theme' ); ?></p>
							<ul class="holiday-list" role="list">
								<?php foreach ( $ccs_holidays as $ccs_day ) : ?>
									<?php $ccs_time = strtotime( $ccs_day['date'] ); ?>
									<li>
										<span class="holiday-list__date"><?php echo esc_html( $ccs_time ? wp_date( 'j M Y', $ccs_time, new DateTimeZone( 'UTC' ) ) : $ccs_day['date'] ); ?></span>
										<span class="holiday-list__name"><?php echo esc_html( $ccs_day['name'] ); ?></span>
										<span class="holiday-list__rate"><?php echo esc_html( $ccs_day['rate'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</details>
				<?php endif; ?>
				<details class="ccs-faq__item" name="costs-more">
					<summary class="ccs-faq__q"><?php esc_html_e( 'Ways to pay', 'ccs-wp-theme' ); ?></summary>
					<div class="ccs-faq__a">
						<p><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
						<dl class="pay-ways">
							<?php foreach ( $ccs_pay_ways as $ccs_way ) : ?>
								<div>
									<dt><?php echo esc_html( $ccs_way['title'] ); ?></dt>
									<dd><?php echo esc_html( $ccs_way['text'] ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
						<p><a class="ccs-text-link" href="<?php echo esc_url( $ccs_faqs ); ?>"><?php esc_html_e( 'Read our funding and payment FAQs', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a></p>
					</div>
				</details>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

</main>

<?php
get_footer();
