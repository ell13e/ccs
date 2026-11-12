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

/*
 * Each line is "what the charge is | the price" (Nov 2026). A line without
 * the bar still shows, as a full-width row, so older wording never vanishes.
 */
$ccs_cost_rows = array();
foreach ( $ccs_cost_extras as $ccs_line ) {
	$ccs_parts       = array_map( 'trim', explode( '|', $ccs_line, 2 ) );
	$ccs_cost_rows[] = array(
		'label' => $ccs_parts[0],
		'price' => isset( $ccs_parts[1] ) ? $ccs_parts[1] : '',
	);
}

$ccs_costs = apply_filters(
	'ccs_home_costs',
	array(
		'from_rate'      => get_theme_mod( 'ccs_cost_from_rate', '£36.38' ),
		'rate_basis'     => get_theme_mod( 'ccs_cost_basis', __( 'per hour for personal care, weekdays 7am–10pm', 'ccs-wp-theme' ) ),
		'effective_from' => get_theme_mod( 'ccs_cost_effective_from', __( 'Rates from 1 September 2026', 'ccs-wp-theme' ) ),
		'extras'         => $ccs_cost_rows,
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

<?php
/*
 * Fifth version (Nov 2026; client: "i dont like this section at all"). The
 * old layout put a short intro beside a tall card, so the intro floated in
 * empty space and the extras read as a list of sentences. Now: the heading
 * above, then one wide fee sheet. The rate is the focal point on the left; the
 * charges on the right are a ruled table, each charge with its price aligned
 * to the right, as on a printed fee schedule. Everything that can be charged
 * is still on the page, which is this section's promise.
 *
 * The caveat stays with the figure: the headline is the entry rate for the
 * cheapest band, and alone it reads as a flat price.
 */
?>
<section class="home-costs" aria-labelledby="home-costs-heading">
	<div class="container container--lg">

		<header class="home-costs__head">
			<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'costs_eyebrow' ) ); ?></p>
			<h2 id="home-costs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'costs_title' ) ); ?></h2>
			<p class="home-costs__funding"><?php echo esc_html( ccs_copy( 'costs_funding' ) ); ?></p>
		</header>

		<div class="fee-sheet" data-ccs-exception="rule 1: a rate sheet people compare and screenshot, a distinct object">
			<div class="fee-sheet__rate">
				<p class="fee-sheet__label"><?php esc_html_e( 'Hourly rate from', 'ccs-wp-theme' ); ?></p>
				<p class="fee-sheet__figure"><?php echo esc_html( $ccs_costs['from_rate'] ); ?></p>
				<p class="fee-sheet__basis"><?php echo esc_html( $ccs_costs['rate_basis'] ); ?></p>
				<p class="fee-sheet__note"><?php echo esc_html( ccs_copy( 'costs_note' ) ); ?></p>
				<p class="fee-sheet__date"><?php echo esc_html( $ccs_costs['effective_from'] ); ?></p>
			</div>

			<?php if ( ! empty( $ccs_costs['extras'] ) ) : ?>
				<div class="fee-sheet__extras">
					<h3 class="fee-sheet__label"><?php echo esc_html( ccs_copy( 'costs_extras_heading' ) ); ?></h3>
					<dl class="fee-sheet__list">
						<?php foreach ( $ccs_costs['extras'] as $ccs_row ) : ?>
							<?php
							// A filter may still pass plain strings; treat each as a label.
							if ( ! is_array( $ccs_row ) ) {
								$ccs_row = array( 'label' => (string) $ccs_row, 'price' => '' );
							}
							?>
							<div class="fee-sheet__row<?php echo '' === $ccs_row['price'] ? ' fee-sheet__row--full' : ''; ?>">
								<dt><?php echo esc_html( $ccs_row['label'] ); ?></dt>
								<?php if ( '' !== $ccs_row['price'] ) : ?>
									<dd><?php echo esc_html( $ccs_row['price'] ); ?></dd>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</dl>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $ccs_costs_url ) : ?>
			<p class="home-costs__more">
				<a class="ccs-text-link" href="<?php echo esc_url( $ccs_costs_url ); ?>"><?php echo esc_html( $ccs_costs_text ); ?> <span aria-hidden="true">&rarr;</span></a>
			</p>
		<?php endif; ?>

	</div>
</section>
