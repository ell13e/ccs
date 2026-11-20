<?php
/**
 * Template Name: Current Vacancies
 *
 * Job: show what is open and make applying feel straightforward. Primary
 * action: choose a role in the job portal.
 *
 * The portal is CV Minder's own page in a frame: its inside cannot be styled
 * from here, and its tall pinned-frame sizing is the vendor's mechanism (see
 * current-vacancies.css), so it is left as it is. Everything a reader needs
 * before the listings sits above it, because the frame is very tall:
 * 1. Head: the page's own intro (editor content) and a route for anyone
 *    who doesn't see a role that fits.
 * 2. What happens after you apply: the shared step panel.
 * 3. The portal, set in one clean frame on the tint band.
 * Wording is editable in Customize > Page wording > Vacancies page.
 * Designed without loading the portal (Nov 2026: the client asked to keep
 * requests to it to a minimum), then checked with a single load.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_vac_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
$ccs_vac_steps   = array(
	array( 'title' => ccs_copy( 'vac_step_1_title' ), 'text' => ccs_copy( 'vac_step_1_text' ) ),
	array( 'title' => ccs_copy( 'vac_step_2_title' ), 'text' => ccs_copy( 'vac_step_2_text' ) ),
	array( 'title' => ccs_copy( 'vac_step_3_title' ), 'text' => ccs_copy( 'vac_step_3_text' ) ),
);
?>

<main id="main" class="site-main site-main--current-vacancies" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php
		// The page's short intro sits in the banner, as on other pages. Under an
		// "Open roles" heading it was tried left-aligned, split and centred, and
		// read as an awkward block each time.
		get_template_part( 'template-parts/page-header', null, array( 'intro' => trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( get_the_content() ) ) ) ) );
		?>

		<?php
		/*
		 * Rebuilt Nov 2026 (user: "I also really hate this whole page"). It
		 * opened with a block of text, two buttons, a small note and a row of
		 * steps before any job was in sight. Now, in the order someone looking
		 * for work needs it:
		 * 1. the open roles themselves, straight under the hero (white: the
		 *    hero is tint);
		 * 2. what happens after you apply, as three steps in one card (tint);
		 * 3. what new starters tell us, from the onboarding survey (white).
		 * A "no role that fits? send your details" strip came next and was
		 * removed: the closing card under it says the same thing (user).
		 * All wording is unchanged and still comes from Customize > Page wording.
		 */
		?>
		<section class="ccs-section ccs-section--sm vac-portal" aria-labelledby="vac-portal-heading">
			<div class="container container--lg">
				<?php // The banner above already says what this is; a lone "Open roles" title over the frame looked silly (user). Kept for screen readers and the section's name. ?>
				<h2 id="vac-portal-heading" class="screen-reader-text"><?php echo esc_html( ccs_copy( 'vac_portal_title' ) ); ?></h2>
				<div class="vac-portal__frame" data-ccs-exception="rule 1: the job portal is a separate system, framed as one object">
					<?php get_template_part( 'template-parts/careers/cv-minder-embed' ); ?>
				</div>
			</div>
		</section>

		<section class="ccs-section ccs-section--tint vac-after" aria-labelledby="vac-after-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="vac-after-heading" class="ccs-title"><?php echo ccs_copy_accent( 'vac_steps_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent(). ?></h2>
				</header>
				<?php
				/*
				 * The same steps pattern as "How care starts" on the service pages
				 * (client, Nov 2026: the careers side to match the care side): a
				 * drawing from the client's packs over each step and a hand-drawn
				 * arrow between them. It replaced three numbered cells in one card.
				 */
				$ccs_vac_art = array( 'letter', 'coffee-cup', 'checklist' );
				?>
				<ol class="flow-steps" role="list">
					<?php foreach ( $ccs_vac_steps as $ccs_vac_i => $ccs_vac_step ) : ?>
						<li class="flow-step">
							<?php if ( $ccs_vac_i > 0 ) : ?>
								<?php echo ccs_doodle( 'arrow-14', 'flow-step__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
							<?php endif; ?>
							<span class="flow-step__art"><?php echo ccs_doodle( $ccs_vac_art[ $ccs_vac_i % count( $ccs_vac_art ) ], 'flow-step__doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?></span>
							<span class="flow-step__num" aria-hidden="true"><?php echo (int) $ccs_vac_i + 1; ?></span>
							<h3 class="flow-step__title"><?php echo esc_html( $ccs_vac_step['title'] ); ?></h3>
							<p class="flow-step__text"><?php echo esc_html( $ccs_vac_step['text'] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<?php // The survey section only; the benefits are on Working with us. ?>
		<?php get_template_part( 'template-parts/careers/onboarding-proof' ); ?>

		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
