<?php
/**
 * Careers > Working with us (page slug: working-for-us).
 *
 * WordPress picks this file for the page by its slug, so nothing needs
 * setting in the editor. The page was the default long-form layout; the user
 * asked for it to be renamed "Working with us", made more visually
 * interesting, and given a benefits section (Nov 2026).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--careers-page" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		/*
		 * The opening, redesigned to match the homepage (Nov 2026; the client
		 * asked for the first section of this page). It was the short band,
		 * then a heading and paragraph beside a wide photo that the split
		 * cropped. Now the heading centred over the team photo at full width, in
		 * its own shape, with the page's opening paragraph centred beneath.
		 */
		$ccs_wf_content = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
		$ccs_wf_photo   = 'site-photos-extra/WhatsApp-Image-2025-06-10-at-14.34.31.webp';
		get_template_part( 'template-parts/page-header' );
		?>
		<section class="ccs-section careers-open" aria-labelledby="working-intro-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="working-intro-heading" class="ccs-title"><?php echo ccs_copy_accent( 'working_intro_title' ); ?></h2>
				</header>
				<figure class="hero-banner">
					<?php
					printf(
						'<img src="%1$s" alt="%2$s" width="1200" height="500" fetchpriority="high" decoding="async"%3$s>',
						esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_wf_photo ),
						esc_attr__( 'Six members of the Continuity Care Services team together at the office', 'ccs-wp-theme' ),
						ccs_theme_image_srcset( $ccs_wf_photo, '(min-width: 1100px) 1024px, 92vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
					);
					?>
				</figure>
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_wf_content['intro'] ) ) ) : ?>
					<div class="hero-banner__lede"><?php echo wp_kses_post( $ccs_wf_content['intro'] ); ?></div>
				<?php endif; ?>
			</div>
		</section>
		<?php
		get_template_part(
			'template-parts/careers/sub-page',
			null,
			array(
				'id'         => 'working',
				'skip_intro' => true,
				'title'      => ccs_copy( 'working_intro_title' ),
				'tabs_title' => ccs_copy( 'working_tabs_title' ),
			)
		);
		// Benefits only. The new starters' survey section lives on the vacancies
		// page: the two are the same composition with similar photos, and one
		// after the other, and on both pages, read as a repeat (user).
		get_template_part( 'template-parts/careers/benefits' );
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
