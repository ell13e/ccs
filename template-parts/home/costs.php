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
	<div class="container container--lg">

		<header class="ccs-head ccs-head--center">
			<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'costs_eyebrow' ) ); ?></p>
			<h2 id="home-costs-heading" class="ccs-title">
				<?php echo esc_html( ccs_copy( 'costs_title' ) ); ?>
			</h2>
			<p class="ccs-lede">
				<?php echo esc_html( ccs_copy( 'costs_lede' ) ); ?>
			</p>
		</header>

		<?php
		/*
		 * One wide price panel (redesigned Nov 2026; the client called the old
		 * layout "awful": loose text on the left, a heavy dark box on the right).
		 * The rate sits on ink on the left, everything else that can be charged
		 * sits on white on the right, and the funding note and link close the
		 * same panel, so the whole answer to "what will it cost?" is one object.
		 * Same two-part anatomy as the closing card, so the page has one kind of
		 * feature panel, not two.
		 *
		 * The caveat under the figure is deliberate, every time a rate is shown:
		 * the headline is the entry rate for the cheapest band on the schedule,
		 * and without it the number reads as a flat price.
		 */
		?>
		<div class="price-card" data-ccs-exception="rule 1: a rate card people compare and screenshot, a distinct object">
			<div class="price-card__rate-panel">
				<p class="price-card__from">
					<span class="price-card__from-label"><?php esc_html_e( 'From', 'ccs-wp-theme' ); ?></span>
					<span class="price-card__rate"><?php echo esc_html( $ccs_costs['from_rate'] ); ?></span>
				</p>
				<p class="price-card__basis"><?php echo esc_html( $ccs_costs['rate_basis'] ); ?></p>
				<p class="price-card__effective"><?php echo esc_html( $ccs_costs['effective_from'] ); ?></p>
				<p class="price-card__caveat">
					<?php echo esc_html( ccs_copy( 'costs_note' ) ); ?>
				</p>
			</div>

			<div class="price-card__detail">
				<?php if ( ! empty( $ccs_costs['extras'] ) ) : ?>
					<h3 class="price-card__extras-heading"><?php echo esc_html( ccs_copy( 'costs_extras_heading' ) ); ?></h3>
					<ul class="price-card__list" role="list">
						<?php foreach ( $ccs_costs['extras'] as $ccs_extra ) : ?>
							<li class="price-card__item">
								<span class="price-card__tick"><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<span><?php echo esc_html( $ccs_extra ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="price-card__foot">
					<p class="price-card__funding">
						<?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?>
					</p>
					<?php if ( $ccs_costs_url ) : ?>
						<a href="<?php echo esc_url( $ccs_costs_url ); ?>" class="btn btn-quiet">
							<?php echo esc_html( $ccs_costs_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>
</section>
