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
		get_template_part( 'template-parts/page-header' );
		get_template_part(
			'template-parts/careers/sub-page',
			null,
			array(
				'id'             => 'working',
				'photo'          => 'site-photos-extra/WhatsApp-Image-2025-06-10-at-14.34.31.webp',
				'alt'            => __( 'Six members of the Continuity Care Services team together at the office', 'ccs-wp-theme' ),
				'title'          => ccs_copy( 'working_intro_title' ),
				'tabs_title'     => ccs_copy( 'working_tabs_title' ),
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
