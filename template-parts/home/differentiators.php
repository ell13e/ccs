<?php
/**
 * Homepage differentiators: a photo beside an accordion of four items (title
 * and description), the first open.
 *
 * Not here on purpose: "CQC regulated" (the rating is shown elsewhere on the
 * page), "Transparent pricing" (a claim that belongs beside actual rates) and
 * the list of towns (they belong with the location pages).
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
		$ccs_diff_photo = 'site-photos/client-wheelchair-seafront-pier.webp';
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
			<?php // Eyebrow added with the client's rewrite (Nov 2026): it names whose voice the heading is in. ?>
			<?php // No label above this heading (client, Nov 2026: "weird eyebrow"). "Our approach" added nothing the heading does not say (rule 12). ?>
			<h2 id="home-diff-heading" class="ccs-title">
				<?php
				echo ccs_copy_accent( 'diff_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
				?>
			</h2>

			<div class="diff-list">
				<?php foreach ( $items as $i => $item ) : ?>
					<details class="diff-item" name="differentiators"<?php echo 0 === $i ? ' open' : ''; ?>>
						<summary class="diff-item__summary">
							<span class="diff-item__title"><?php echo esc_html( $item['title'] ); ?></span>
						</summary>
						<p class="diff-item__text"><?php echo esc_html( $item['desc'] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
