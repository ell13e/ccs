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
		'title' => ccs_copy( 'diff_1_title' ),
		'desc'  => ccs_copy( 'diff_1_text' ),
	),
	array(
		'title' => ccs_copy( 'diff_2_title' ),
		'desc'  => ccs_copy( 'diff_2_text' ),
	),
	array(
		'title' => ccs_copy( 'diff_3_title' ),
		'desc'  => ccs_copy( 'diff_3_text' ),
	),
	array(
		'title' => ccs_copy( 'diff_4_title' ),
		'desc'  => ccs_copy( 'diff_4_text' ),
	),
);
?>

<section class="home-differentiators" aria-labelledby="home-diff-heading">
	<div class="home-diff container container--lg">

		<?php
		/*
		 * Redesigned Nov 2026 after the client called this band "awful": it was a
		 * centred heading over four blocks of loose text on plum, which read as a
		 * wall of copy. Now the argument sits on the left (heading, one line, the
		 * page's main action) and the four commitments are panels on the right,
		 * each led by an icon from the site's set: one tone lighter than the band,
		 * so the depth comes from surface levels (CLAUDE.md rule 6), not effects.
		 */
		$ccs_diff_icons   = array( 'people', 'heart', 'pin', 'phone' );
		$ccs_diff_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
		?>
		<header class="home-diff__head">
			<h2 id="home-diff-heading" class="ccs-title ccs-title--on-dark">
				<?php
				echo ccs_copy_accent( 'diff_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
				?>
			</h2>
			<p class="ccs-lede ccs-lede--on-dark">
				<?php echo esc_html( ccs_copy( 'diff_lede' ) ); ?>
			</p>
			<div class="ccs-actions">
				<a href="<?php echo esc_url( $ccs_diff_contact ); ?>" class="btn btn-accent">
					<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
				</a>
			</div>
		</header>

		<ul class="home-diff-grid" role="list">
			<?php foreach ( $items as $i => $item ) : ?>
				<li class="diff-card" data-ccs-exception="rule 1: four commitments as panels at the client's request (Nov 2026); as loose text the band read as a wall of copy">
					<span class="diff-card__icon" aria-hidden="true"><?php echo ccs_icon( isset( $ccs_diff_icons[ $i ] ) ? $ccs_diff_icons[ $i ] : 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<h3 class="diff-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="diff-card__text"><?php echo esc_html( $item['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
