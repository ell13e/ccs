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
		 * Third version (Nov 2026). Panels on plum and loose text on plum were
		 * both judged ugly: four long paragraphs at once is a wall, however it
		 * is boxed. Now one quiet photograph beside the heading, and the four
		 * commitments as an accordion: the titles read at a glance, each
		 * explanation one tap away, the first open. Native <details name>, so
		 * keyboard and screen readers work with no script.
		 */
		$ccs_diff_photo = 'site-photos/WhatsApp-Image-2025-05-23-at-13.39.58-2.webp';
		?>
		<?php if ( file_exists( get_template_directory() . '/assets/images/' . $ccs_diff_photo ) ) : ?>
			<figure class="home-diff__photo" aria-hidden="true">
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_diff_photo ); ?>"
					alt=""
					width="800"
					height="1000"
					<?php echo ccs_theme_image_srcset( $ccs_diff_photo, '(min-width: 960px) 440px, 92vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
					loading="lazy"
					decoding="async"
				>
			</figure>
		<?php endif; ?>

		<div class="home-diff__body">
			<h2 id="home-diff-heading" class="ccs-title">
				<?php
				echo ccs_copy_accent( 'diff_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
				?>
			</h2>

			<div class="diff-list">
				<?php foreach ( $items as $i => $item ) : ?>
					<details class="diff-item" name="differentiators"<?php echo 0 === $i ? ' open' : ''; ?>>
						<summary class="diff-item__summary">
							<span class="diff-item__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( (int) $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span class="diff-item__title"><?php echo esc_html( $item['title'] ); ?></span>
						</summary>
						<p class="diff-item__text"><?php echo esc_html( $item['desc'] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
