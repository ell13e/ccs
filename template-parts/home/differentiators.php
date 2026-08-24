<?php
/**
 * Homepage differentiators: four items in a 2-column grid with number badge,
 * title and description.
 *
 * Was six items, each carrying a second `detail` paragraph — twelve blocks of
 * prose in one section. strategy.md asks for "scannable blocks, one obvious
 * next step — never a wall of choices" for this audience, and that is precisely
 * what it had become. Three changes, all subtractive:
 *
 * - "CQC regulated" removed. The homepage already opens with a full CQC section
 *   ("Rated Good by the CQC") directly under the hero, so this repeated the
 *   page's own strongest trust signal a screen and a half later.
 * - "Transparent pricing" removed to template-parts/home/costs.php, where it is
 *   backed by actual rates. Making the claim here with no numbers anywhere on
 *   the site was the thing that made it hollow.
 * - The area list moved out of "Local, and quick to respond"; towns belong with
 *   the location pages, not buried in a supporting paragraph.
 *
 * The `detail` line is gone entirely — it was carrying the text that made this
 * a wall. Anything genuinely load-bearing was promoted into `desc`.
 *
 * Strongest claim first. "A team matched to you" is the only item here no
 * competitor can copy, so it leads rather than sitting second behind a
 * regulation badge every provider has.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	array(
		'title' => __( 'A team matched to you, not whoever is free', 'ccs-wp-theme' ),
		'desc'  => __( 'The same small team visits you, so you build a relationship and never have to keep explaining your needs. If nobody in our current team fits your hours, your home, or simply doesn’t get on with you, we recruit someone specifically for you.', 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'Care plans built around you', 'ccs-wp-theme' ),
		'desc'  => __( 'Your care plan starts with how you actually live, not a one-size-fits-all checklist. We review it regularly and adjust as your situation changes.', 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'Local, and quick to respond', 'ccs-wp-theme' ),
		'desc'  => __( 'We’re based in Maidstone and work across Kent, so our teams already know your area and can get to you quickly.', 'ccs-wp-theme' ),
	),
	array(
		'title' => __( 'You are kept in the loop', 'ccs-wp-theme' ),
		'desc'  => __( 'A named contact who already knows the situation, and updates often enough that you’re never left guessing how a visit went.', 'ccs-wp-theme' ),
	),
);
?>

<section class="home-differentiators" aria-labelledby="home-diff-heading">
	<div class="home-differentiators__inner container container--lg">
		<p class="ccs-eyebrow ccs-eyebrow--on-dark"><?php esc_html_e( 'What sets us apart', 'ccs-wp-theme' ); ?></p>
		<h2 id="home-diff-heading" class="home-differentiators__heading">
			<?php esc_html_e( 'What we do differently', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-differentiators__intro">
			<?php esc_html_e( 'Every provider says they care. These are the four things you can hold us to.', 'ccs-wp-theme' ); ?>
		</p>
		<div class="home-differentiators__grid">
			<?php foreach ( $items as $i => $item ) : ?>
				<article class="home-diff-item">
					<span class="home-diff-item__number" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
					<h3 class="home-diff-item__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="home-diff-item__desc"><?php echo esc_html( $item['desc'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
