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
	$ccs_costs_text = __( 'See all our rates and ways to pay', 'ccs-wp-theme' );
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
 * Eighth version (Nov 2026): the full rate table moved to its own Costs and
 * funding page (client: "maybe we need a page for this and just a section
 * directing to it here instead"). Here: the note that published rates are
 * not a quote, the three starting prices in one small panel, and a link to
 * the page with every rate.
 */
$ccs_from = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
?>
<section class="home-costs" aria-labelledby="home-costs-heading">
	<div class="container container--lg">
		<div class="home-costs__grid">

			<div class="home-costs__intro">
				<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'costs_eyebrow' ) ); ?></p>
				<h2 id="home-costs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'costs_title' ) ); ?></h2>
				<p class="rate-note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
				<?php if ( $ccs_costs_url ) : ?>
					<div class="ccs-actions home-costs__actions">
						<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_costs_url ); ?>"><?php echo esc_html( $ccs_costs_text ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $ccs_from ) ) : ?>
				<dl class="rate-panel rate-from" data-ccs-exception="rule 1: the starting prices, one distinct object">
					<?php foreach ( $ccs_from as $ccs_group => $ccs_rows ) : ?>
						<div class="rate-from__row">
							<dt><?php echo esc_html( $ccs_group ); ?></dt>
							<dd>
								<span class="rate-from__word"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?></span>
								<span class="rate-from__price"><?php echo esc_html( $ccs_rows[0]['weekday'] ); ?></span>
								<span class="rate-from__unit"><?php echo esc_html( $ccs_rows[0]['night'] ? __( 'a night', 'ccs-wp-theme' ) : __( 'an hour', 'ccs-wp-theme' ) ); ?></span>
							</dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

		</div>
	</div>
</section>
