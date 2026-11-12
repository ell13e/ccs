<?php
/**
 * Template Name: Costs and funding
 *
 * Every published rate, what affects the price, and the ways care can be paid
 * for (Nov 2026). The full table moved here from the homepage at the client's
 * request; the homepage keeps a short section that links to this page.
 *
 * Sections: page hero (with the CQC card); what affects your price beside
 * the rate table (white); ways to pay (tint); the closing card.
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

	<section class="ccs-section costs-rates" aria-labelledby="costs-rates-heading">
		<div class="container container--lg">
			<div class="costs-rates__grid">
				<div class="costs-explain">
					<h2 id="costs-rates-heading" class="ccs-title"><?php esc_html_e( 'Our published rates', 'ccs-wp-theme' ); ?></h2>
					<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>

					<h3 class="costs-explain__title"><?php echo esc_html( ccs_copy( 'costs_factors_title' ) ); ?></h3>
					<ol class="costs-explain__factors">
						<li><?php echo esc_html( ccs_copy( 'costs_factor_1' ) ); ?></li>
						<li><?php echo esc_html( ccs_copy( 'costs_factor_2' ) ); ?></li>
						<li><?php echo esc_html( ccs_copy( 'costs_factor_3' ) ); ?></li>
					</ol>

					<div class="ccs-actions costs-explain__actions">
						<a class="btn btn-primary" href="<?php echo esc_url( $ccs_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
					</div>
				</div>

				<?php get_template_part( 'template-parts/rate-table' ); ?>
			</div>
		</div>
	</section>

	<section class="ccs-section ccs-section--tint costs-pay" aria-labelledby="costs-pay-heading">
		<div class="container container--lg">
			<header class="ccs-head">
				<h2 id="costs-pay-heading" class="ccs-title"><?php esc_html_e( 'Ways to pay for care', 'ccs-wp-theme' ); ?></h2>
				<p class="ccs-lede"><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
			</header>
			<ul class="feature-grid feature-grid--4" role="list">
				<?php foreach ( $ccs_pay_ways as $ccs_way ) : ?>
					<li class="feature-card" data-ccs-exception="rule 1: each card is a separate way of paying, compared side by side">
						<span class="ccs-badge"><?php echo ccs_icon( $ccs_way['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<h3 class="feature-card__title"><?php echo esc_html( $ccs_way['title'] ); ?></h3>
						<p class="feature-card__text"><?php echo esc_html( $ccs_way['text'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="costs-pay__more">
				<a class="ccs-text-link" href="<?php echo esc_url( $ccs_faqs ); ?>"><?php esc_html_e( 'Read our funding and payment FAQs', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
			</p>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

</main>

<?php
get_footer();
