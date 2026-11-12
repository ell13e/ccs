<?php
/**
 * Homepage costs teaser.
 *
 * Exists because the homepage promised "Transparent pricing. No hidden fees."
 * while the fee schedule carried four charges that appeared nowhere on the site:
 * a £30 setup fee, 50p/mile escort mileage, bank holidays at double rate and
 * Christmas/Boxing/New Year's Day at treble. UK guidance tells families to ask
 * about exactly those extras, so a family could read "no hidden fees", sign up,
 * and then meet them. Stating them is what makes the claim true — and it is a
 * real differentiator, since neither Bluebird Care nor Home Instead publishes
 * rates at all.
 *
 * Figures are the 1 Sept 2026 to 31 Aug 2027 schedule (assets/_Care Services
 * Fees - 2026.pdf), editable in the Customizer. Since Nov 2026 every published
 * rate is shown, with the note that the real figure is a personalised quote:
 * the client asked for the reasoning behind not quoting a single price to be
 * clear, and a full table answers "what could I be charged?" outright.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Rates and charges come from ccs_fee_schedule() and ccs_fee_extras()
 * (inc/content-sections.php), edited under Customizer > Care Costs, and are
 * printed by template-parts/rate-table.php.
 */

/*
 * Resolve the destination against pages that actually exist. ccs_page_url()
 * falls back to a home_url() guess when nothing matches, which would publish a
 * confident "See full rates" link straight into a 404 — the costs page is still
 * to be built. Check for a real page first, and fall back to FAQs, which
 * already carries a funding answer, until it exists.
 */
$ccs_costs_page = get_page_by_path( 'costs-and-funding' );
$ccs_costs_url  = '';

if ( $ccs_costs_page instanceof WP_Post ) {
	$ccs_costs_url  = get_permalink( $ccs_costs_page );
	$ccs_costs_text = __( 'See full rates and funding options', 'ccs-wp-theme' );
} else {
	$ccs_faqs_page = get_page_by_path( 'faqs' );
	if ( ! $ccs_faqs_page instanceof WP_Post ) {
		$ccs_faqs_page = get_page_by_path( 'resources/faqs' );
	}
	if ( $ccs_faqs_page instanceof WP_Post ) {
		$ccs_costs_url  = get_permalink( $ccs_faqs_page );
		$ccs_costs_text = __( 'Read our funding and payment FAQs', 'ccs-wp-theme' );
	}
}
?>

<?php
/*
 * Seventh version (Nov 2026; client: the rate cards looked "uneven, messy",
 * and did not make clear that a published rate is not a quote). Two columns:
 * on the left, what affects the price and the plain note that the real
 * figure is a personalised quote after a free consultation (the wording of
 * the agency's own homecare.co.uk listing); on the right, every published
 * rate in one even table (template-parts/rate-table.php).
 */
?>
<section class="home-costs" aria-labelledby="home-costs-heading">
	<div class="container container--lg">
		<div class="home-costs__grid">

			<div class="home-costs__intro">
				<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'costs_eyebrow' ) ); ?></p>
				<h2 id="home-costs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'costs_title' ) ); ?></h2>
				<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>

				<h3 class="home-costs__factors-title"><?php echo esc_html( ccs_copy( 'costs_factors_title' ) ); ?></h3>
				<ol class="home-costs__factors">
					<li><?php echo esc_html( ccs_copy( 'costs_factor_1' ) ); ?></li>
					<li><?php echo esc_html( ccs_copy( 'costs_factor_2' ) ); ?></li>
					<li><?php echo esc_html( ccs_copy( 'costs_factor_3' ) ); ?></li>
				</ol>

				<p class="home-costs__funding"><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
				<?php if ( $ccs_costs_url ) : ?>
					<a class="ccs-text-link" href="<?php echo esc_url( $ccs_costs_url ); ?>"><?php echo esc_html( $ccs_costs_text ); ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/rate-table' ); ?>

		</div>
	</div>
</section>
