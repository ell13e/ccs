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
			// One jotted fact beside the first two steps, each taken from that
			// step's own text ("Expect about twenty minutes", "usually within a
			// few days"). If the client rewrites those steps, change or remove
			// the note for that step number here.
			$ccs_gs_notes = array(
				'1' => __( 'about 20 minutes', 'ccs-wp-theme' ),
				'2' => __( 'within a few days', 'ccs-wp-theme' ),
				'3' => __( 'you keep a copy', 'ccs-wp-theme' ),
				'4' => __( 'you meet them first', 'ccs-wp-theme' ),
				'5' => __( 'a call after week one', 'ccs-wp-theme' ),
				'6' => __( 'every visit is logged', 'ccs-wp-theme' ),
			);
			// The drawing that sits with each note, from the client's packs (client: liked the first two, "there might need to be some on the others too"). Each note repeats a fact from its step's own text.
			$ccs_gs_note_doodles = array(
				'1' => 'alarm-clock',
				'2' => 'calendar',
				'3' => 'clipboard',
				'4' => 'two-mugs',
				'5' => 'speech-bubble',
				'6' => 'journal',
			);
			// Photos used nowhere else on the site (client, Nov 2026: "make sure we arent repeating photos"). Which photo sits with which step is the client's call.
			$ccs_gs_photos = array(
				array( 'site-photos/client-baking-together.webp', '50% 35%' ),
				array( 'site-photos/client-sewing.webp', '50% 30%' ),
				array( 'site-photos/team-member-writing-at-event.jpg', '50% 45%' ),
				array( 'site-photos/carer-with-older-gentleman-selfie.webp', '50% 30%' ),
				array( 'site-photos/client-cooking-prep.webp', '50% 45%' ),
				array( 'site-photos/client-thank-you-flowers.webp', '50% 30%' ),
			);
			?>
			<?php
			/*
			 * No separate introduction (Nov 2026). Two were disliked: the heading
			 * and lead at the left beside a photo, then centred in a band of their
			 * own. The client picked this from three sketches: the heading and her
			 * lead sit at the top of the steps themselves, centred over the line,
			 * and the timeline runs down out of them.
			 */
			?>
			<?php // The steps are on white (client: "maybe not a purple background?"), so "Good to know" below takes the tint and the grounds still alternate. ?>
			<section class="ccs-section gs-steps" aria-labelledby="gs-steps-heading">
				<div class="container container--lg">
					<?php // One wrapper round the heading and the steps: on narrow screens it carries the line, so the line (and its plum progress) runs from the heading down without a join. ?>
					<div class="gs-journey">
					<header class="ccs-head ccs-head--center gs-intro">
						<h2 id="gs-steps-heading" class="ccs-title"><?php echo ccs_accent_text( __( 'From first call to *first visit*', 'ccs-wp-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_accent_text(). ?></h2>
						<?php if ( '' !== trim( wp_strip_all_tags( $ccs_gs['intro'] ) ) ) : ?>
							<div class="ccs-lede gs-intro__lede" data-ccs-exception="rule 10: the client chose the lead centred over the timeline, from three sketches">
								<?php echo wp_kses_post( $ccs_gs['intro'] ); ?>
							</div>
						<?php endif; ?>
					</header>
					<ol class="gs-path" role="list">
						<?php foreach ( $ccs_gs_steps as $ccs_gs_n => $ccs_gs_step ) : ?>
							<?php $ccs_gs_photo = $ccs_gs_photos[ $ccs_gs_n % count( $ccs_gs_photos ) ]; ?>
							<li class="gs-path__step">
								<span class="gs-path__num" aria-hidden="true"><?php echo esc_html( $ccs_gs_step['num'] ); ?></span>
								<div class="gs-path__text">
									<h3 class="gs-path__title"><span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: step number. */ __( 'Step %s:', 'ccs-wp-theme' ), $ccs_gs_step['num'] ) ); ?> </span><?php echo esc_html( $ccs_gs_step['title'] ); ?></h3>
									<div class="gs-path__body entry-content"><?php echo wp_kses_post( $ccs_gs_step['body'] ); ?></div>
									<?php // The jotted note closes the step, under its text (client's pick from four sketched placements, after the note read as "added on" under and then beside the title). A drawing from her packs sits with it, as on the CQC page: a note on its own had nothing to belong to. ?>
									<?php if ( isset( $ccs_gs_notes[ (string) $ccs_gs_step['num'] ] ) ) : ?>
										<p class="gs-path__note"><?php echo ccs_doodle( isset( $ccs_gs_note_doodles[ (string) $ccs_gs_step['num'] ] ) ? $ccs_gs_note_doodles[ (string) $ccs_gs_step['num'] ] : 'heart-doodle', 'gs-path__note-doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?><?php echo ccs_hand_note( $ccs_gs_notes[ (string) $ccs_gs_step['num'] ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_hand_note(). ?></p>
									<?php endif; ?>
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
				</div>
			</section>

			<?php if ( ! empty( $ccs_gs_more ) ) : ?>
				<section class="ccs-section ccs-section--tint gs-more" aria-labelledby="gs-more-heading">
					<div class="container container--lg">
						<div class="ccs-split">
							<header class="ccs-head">
								<h2 id="gs-more-heading" class="ccs-title"><?php echo ccs_accent_text( __( '*Good* to know', 'ccs-wp-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_accent_text(). ?></h2>
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
