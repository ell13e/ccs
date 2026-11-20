<?php
/**
 * Template Name: Getting Started
 *
 * What happens between the first call and the first visit. The words are the
 * client's own page content; this template lays them out (Nov 2026 rebuild,
 * after the client said the page needed significant work: it was one 4,000px
 * column of text on a drawn rail).
 *
 * How the editor content is read (ccs_content_sections()):
 * - the paragraph before the first h2 is the introduction;
 * - h2s that start with a number ("1. The first conversation") are the
 *   steps, shown as numbered rows in one white panel: number and title
 *   left, the step's first paragraph right, the rest one tap away;
 * - every other h2 ("Paying for care", "Safeguarding…") is a question in a
 *   "Good to know" accordion, so the long reference sections are one tap away
 *   instead of all on screen.
 * The client edits the page as before; renumbering or adding an h2 just works.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--getting-started" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_gs_html  = apply_filters( 'the_content', get_the_content() );
		$ccs_gs       = function_exists( 'ccs_content_sections' ) ? ccs_content_sections( $ccs_gs_html ) : array( 'intro' => $ccs_gs_html, 'sections' => array() );
		$ccs_gs_steps = array();
		$ccs_gs_more  = array();

		foreach ( $ccs_gs['sections'] as $ccs_gs_section ) {
			if ( preg_match( '/^\s*(\d+)[.)]\s*(.+)$/u', $ccs_gs_section['heading'], $ccs_gs_m ) ) {
				$ccs_gs_steps[] = array(
					'num'   => $ccs_gs_m[1],
					'title' => $ccs_gs_m[2],
					'body'  => $ccs_gs_section['body'],
				);
			} else {
				$ccs_gs_more[] = $ccs_gs_section;
			}
		}
		?>

		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php if ( empty( $ccs_gs_steps ) ) : ?>
			<?php // No numbered steps (the client restructured the page): show it as a plain article. ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'ccs-section page-content' ); ?>>
				<div class="container container--lg">
					<div class="page-body entry-content">
						<?php echo $ccs_gs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?>
					</div>
				</div>
			</article>
		<?php else : ?>

			<?php
			/*
			 * Inner-page pass, Nov 2026. This section was 2,500px of text with no
			 * image: 650 words in hairline rows. Now three things, each one object:
			 * 1. the introduction beside one real photo (the office team, who take
			 *    the first call), with the client's intro as a large lede;
			 * 2. the steps in ONE white panel on the tint (the client called unboxed
			 *    steps "awful", and six separate cards would be cards for their own
			 *    sake: rule 1). Each row shows the step's first paragraph; the rest
			 *    of the client's text opens in place, so no words are cut;
			 * 3. "Good to know" moves to white so the grounds still alternate.
			 */
			?>
			<section class="ccs-section gs-intro-section" aria-labelledby="gs-steps-heading">
				<div class="container container--lg">
					<div class="ccs-media-split">
						<header class="gs-intro">
							<h2 id="gs-steps-heading" class="ccs-title"><?php esc_html_e( 'From first call to first visit', 'ccs-wp-theme' ); ?></h2>
							<?php if ( '' !== trim( wp_strip_all_tags( $ccs_gs['intro'] ) ) ) : ?>
								<div class="ccs-lede ccs-lede--large gs-intro__lede">
									<?php echo wp_kses_post( $ccs_gs['intro'] ); ?>
								</div>
							<?php endif; ?>
						</header>
						<?php
						ccs_section_photo(
							'site-photos/WhatsApp-Image-2021-02-10-at-15.39.32.webp',
							array(
								'alt'      => __( 'Members of the Continuity Care Services team talking through care notes together', 'ccs-wp-theme' ),
								'sizes'    => '(min-width: 900px) 520px, 92vw',
								// Keeps the three faces and crops the right-hand edge, where the
								// photographer's mark sits.
								'position' => '0% 50%',
							)
						);
						?>
					</div>
				</div>
			</section>

			<?php
			/*
			 * The steps as a timeline (Nov 2026, fourth form). Rejected before:
			 * hairline rows (client: "awful"), rows in a panel with the second
			 * paragraph behind "More" ("very blocky text"), and a stepper card
			 * showing one step at a time ("i hate this", twice: an empty corner
			 * under the title and a Next button doing the work of scrolling).
			 * The client then asked for Pinterest and Dribbble research on
			 * process sections and picked the vertical timeline from three
			 * sketches. Built first as one white card with a line joining the
			 * discs; that became the sections below. Every step shows; nothing
			 * opens, closes or moves.
			 */
			?>
			<?php
			/*
			 * The steps as an alternating timeline (same day, fifth form). One
			 * card was "too much text", one band per step "too visually
			 * repetitive", and two shapes of band still lacked "visual
			 * interest". So each step now has a photograph, the steps swap
			 * sides down a centre line that joins their numbered discs, and
			 * the line fills as the page scrolls (CSS only, and only where the
			 * visitor has not asked for reduced motion). The photographs break
			 * up the text; the line makes six steps one journey.
			 *
			 * The photos are real CCS photos already in the theme, a first
			 * choice for the client to change: one per step, in order.
			 */
			$ccs_gs_photos = array(
				array( 'site-photos/Untitled-1.webp', '50% 30%' ),
				array( 'site-photos/client-afternoon-tea.webp', '50% 25%' ),
				array( 'site-photos/IMG-20241017-WA0005-edited-scaled.jpg', '50% 45%' ),
				array( 'site-photos/467212766_9676172959062903_646005181027754761_n-copy.webp', '50% 30%' ),
				array( 'site-photos/Untitled-design-4.webp', '50% 30%' ),
				array( 'site-photos/client-activity-laughing.webp', '50% 30%' ),
			);
			?>
			<section class="ccs-section ccs-section--tint gs-steps" aria-labelledby="gs-stepper-heading">
				<div class="container container--lg">
					<h2 id="gs-stepper-heading" class="screen-reader-text"><?php esc_html_e( 'The steps, in order', 'ccs-wp-theme' ); ?></h2>
					<ol class="gs-path" role="list">
						<?php foreach ( $ccs_gs_steps as $ccs_gs_n => $ccs_gs_step ) : ?>
							<?php $ccs_gs_photo = $ccs_gs_photos[ $ccs_gs_n % count( $ccs_gs_photos ) ]; ?>
							<li class="gs-path__step">
								<span class="gs-path__num" aria-hidden="true"><?php echo esc_html( $ccs_gs_step['num'] ); ?></span>
								<div class="gs-path__text">
									<h3 class="gs-path__title"><span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: step number. */ __( 'Step %s:', 'ccs-wp-theme' ), $ccs_gs_step['num'] ) ); ?> </span><?php echo esc_html( $ccs_gs_step['title'] ); ?></h3>
									<div class="gs-path__body entry-content"><?php echo wp_kses_post( $ccs_gs_step['body'] ); ?></div>
								</div>
								<figure class="gs-path__photo" data-ccs-exception="rule 15: one photograph in its frame">
									<?php
									printf(
										'<img src="%1$s" alt="" loading="lazy" decoding="async" style="object-position:%2$s"%3$s>',
										esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_gs_photo[0] ),
										esc_attr( $ccs_gs_photo[1] ),
										ccs_theme_image_srcset( $ccs_gs_photo[0], '(min-width: 900px) 460px, 80vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
									);
									?>
								</figure>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>
			</section>

			<?php if ( ! empty( $ccs_gs_more ) ) : ?>
				<section class="ccs-section ccs-section--flush-bottom gs-more" aria-labelledby="gs-more-heading">
					<div class="container container--lg">
						<div class="ccs-split">
							<header class="ccs-head">
								<h2 id="gs-more-heading" class="ccs-title"><?php esc_html_e( 'Good to know', 'ccs-wp-theme' ); ?></h2>
							</header>
							<div class="ccs-faq">
								<?php foreach ( $ccs_gs_more as $ccs_gs_i => $ccs_gs_item ) : ?>
									<details class="ccs-faq__item" name="getting-started"<?php echo 0 === $ccs_gs_i ? ' open' : ''; ?>>
										<summary class="ccs-faq__q"><?php echo esc_html( $ccs_gs_item['heading'] ); ?></summary>
										<div class="ccs-faq__a"><?php echo wp_kses_post( $ccs_gs_item['body'] ); ?></div>
									</details>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>

		<?php endif; ?>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
