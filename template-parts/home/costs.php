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
 * Figures are the 01 Sept 2026 – 31 Aug 2027 schedule (assets/_Care Services
 * Fees - 2026.pdf), published ahead of the change date rather than showing the
 * 2025 rates that expire on 31 August 2026. Rates are reviewed annually, so they
 * are filterable rather than inlined in the markup — update in one place, or
 * override via the `ccs_home_costs` filter without touching the template.
 *
 * Deliberately a teaser, not the full rate table: strategy.md asks for costs to
 * be one tap from anywhere, and warns against walls of choices for this
 * audience. The full schedule lives on the costs page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Read from the Customizer (Appearance → Customise → Care Costs) rather than
 * from literals here. These figures were previously hardcoded, which meant the
 * single most frequently changing number on the site — and its clearest
 * advantage over every competitor benchmarked in the UI audit — could only be
 * updated by editing PHP and deploying. Defaults below match the 1 Sept 2026
 * schedule, so an install that has never touched the Customizer renders exactly
 * as before. The `ccs_home_costs` filter still wins over both, for anyone
 * driving these from a plugin or a rate feed.
 */
$ccs_cost_extras_raw = (string) get_theme_mod(
	'ccs_cost_extras',
	class_exists( 'CCS_Theme_Customizer' ) ? CCS_Theme_Customizer::default_cost_extras() : ''
);
$ccs_cost_extras = array_values( array_filter( array_map(
	'trim',
	preg_split( '/\R/', $ccs_cost_extras_raw ) ?: array()
), static function ( $line ) {
	return $line !== '';
} ) );

$ccs_costs = apply_filters(
	'ccs_home_costs',
	array(
		'from_rate'      => get_theme_mod( 'ccs_cost_from_rate', '£36.38' ),
		'rate_basis'     => get_theme_mod( 'ccs_cost_basis', __( 'per hour for personal care, weekdays 7am–10pm', 'ccs-wp-theme' ) ),
		'effective_from' => get_theme_mod( 'ccs_cost_effective_from', __( 'Rates from 1 September 2026', 'ccs-wp-theme' ) ),
		'extras'         => $ccs_cost_extras,
	)
);

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

<section class="home-costs" aria-labelledby="home-costs-heading">
	<div class="home-costs__inner container container--lg">
		<div class="home-costs__intro">
			<p class="ccs-eyebrow"><?php esc_html_e( 'No hidden fees', 'ccs-wp-theme' ); ?></p>
			<h2 id="home-costs-heading" class="home-costs__heading">
				<?php esc_html_e( 'What care costs', 'ccs-wp-theme' ); ?>
			</h2>
			<p class="home-costs__lede">
				<?php esc_html_e( 'Most providers make you ask. Here are our rates, and every extra that could appear on your invoice — so nothing on it is a surprise.', 'ccs-wp-theme' ); ?>
			</p>
		</div>

		<div class="home-costs__figure">
			<p class="home-costs__from">
				<span class="home-costs__from-label"><?php esc_html_e( 'From', 'ccs-wp-theme' ); ?></span>
				<span class="home-costs__from-rate"><?php echo esc_html( $ccs_costs['from_rate'] ); ?></span>
			</p>
			<p class="home-costs__basis"><?php echo esc_html( $ccs_costs['rate_basis'] ); ?></p>
			<p class="home-costs__effective"><?php echo esc_html( $ccs_costs['effective_from'] ); ?></p>
			<?php
			/*
			 * Explicit, every time a figure is shown. £36.38 is the entry rate for
			 * the lightest, cheapest band on the fee schedule (personal care,
			 * weekday daytime) — the actual charge for any given package depends on
			 * the type of care, the time of day, and whether it falls on a weekend
			 * or bank holiday (see the extras list below). Without this line the
			 * headline figure alone reads as a flat rate, which the fee schedule
			 * itself is not.
			 *
			 * Wording follows how other UK providers phrase the same caveat —
			 * Bupa: "the exact cost will depend on their specific care needs";
			 * Home Instead: "The cost of this service depends on several factors,
			 * including the type of care needed" — adapted to CCS's own actual
			 * variables rather than copied.
			 */
			?>
			<p class="home-costs__caveat">
				<?php esc_html_e( 'Your actual cost depends on the type of care you need and when it happens — this is our lowest rate, not a flat price.', 'ccs-wp-theme' ); ?>
			</p>
		</div>

		<div class="home-costs__extras">
			<h3 class="home-costs__extras-heading">
				<?php esc_html_e( 'What else you might be charged', 'ccs-wp-theme' ); ?>
			</h3>
			<ul class="home-costs__list">
				<?php foreach ( $ccs_costs['extras'] as $ccs_extra ) : ?>
					<li class="home-costs__list-item"><?php echo esc_html( $ccs_extra ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p class="home-costs__funding">
				<?php esc_html_e( 'Care can be funded privately, through local authority support, or through NHS health funding. We’ll talk you through which applies to you.', 'ccs-wp-theme' ); ?>
			</p>
			<?php if ( $ccs_costs_url ) : ?>
				<a href="<?php echo esc_url( $ccs_costs_url ); ?>" class="home-costs__link">
					<?php echo esc_html( $ccs_costs_text ); ?> <span aria-hidden="true">&rarr;</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
