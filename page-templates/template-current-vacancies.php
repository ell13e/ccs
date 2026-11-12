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
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<section class="ccs-section vac-intro" aria-labelledby="vac-heading">
			<div class="container container--lg">
				<header class="ccs-head vac-intro__head">
					<h2 id="vac-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'vac_title' ) ); ?></h2>
					<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
						<div class="ccs-lede vac-intro__lede"><?php the_content(); ?></div>
					<?php endif; ?>
					<div class="ccs-actions vac-intro__actions">
						<a class="btn btn-primary" href="#vac-portal-heading" data-ccs-exception="rule 8: the page's primary action, a jump to the job listings"><?php echo esc_html( ccs_copy( 'vac_jump' ) ); ?> <span aria-hidden="true">&darr;</span></a>
						<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_vac_contact ); ?>"><?php echo esc_html( ccs_copy( 'vac_none_link' ) ); ?></a>
					</div>
					<p class="vac-intro__none"><?php echo esc_html( ccs_copy( 'vac_none_text' ) ); ?></p>
				</header>

				<h3 class="vac-steps__title"><?php echo esc_html( ccs_copy( 'vac_steps_title' ) ); ?></h3>
				<ol class="ccs-steps ccs-steps--panel" role="list" data-ccs-exception="rule 1: one soft panel holding the sequence, as on the homepage">
					<?php foreach ( $ccs_vac_steps as $ccs_i => $ccs_step ) : ?>
						<li class="ccs-step">
							<span class="ccs-step__num" aria-hidden="true"><?php echo (int) $ccs_i + 1; ?></span>
							<h4 class="ccs-step__title"><?php echo esc_html( $ccs_step['title'] ); ?></h4>
							<p class="ccs-step__text"><?php echo esc_html( $ccs_step['text'] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<section class="ccs-section ccs-section--tint vac-portal" aria-labelledby="vac-portal-heading">
			<div class="container container--lg">
				<h2 id="vac-portal-heading" class="vac-portal__title"><?php echo esc_html( ccs_copy( 'vac_portal_title' ) ); ?></h2>
				<div class="vac-portal__frame" data-ccs-exception="rule 1: the job portal is a separate system, framed as one object">
					<?php get_template_part( 'template-parts/careers/cv-minder-embed' ); ?>
				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
