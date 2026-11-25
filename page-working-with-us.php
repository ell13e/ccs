<?php
/**
 * Careers > Working with us (page slug: working-with-us).
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
		$ccs_wf_photo   = 'site-photos-extra/team-group-office-window.webp';
		get_template_part( 'template-parts/page-header' );
		?>
		<section class="ccs-section careers-open" aria-labelledby="working-intro-heading">
			<div class="container container--lg">
				<?php
				/*
				 * Words beside the photo, as the other careers pages and the care
				 * pages open (client, Nov 2026: a centred heading over a banner
				 * photo "sticks out like a sore thumb").
				 */
				?>
				<div class="ccs-media-split careers-open__layout">
					<header class="careers-open__text">
						<h2 id="working-intro-heading" class="ccs-title"><?php echo ccs_copy_accent( 'working_intro_title' ); ?></h2>
						<?php if ( '' !== trim( wp_strip_all_tags( $ccs_wf_content['intro'] ) ) ) : ?>
							<div class="ccs-lede careers-open__lede"><?php echo wp_kses_post( $ccs_wf_content['intro'] ); ?></div>
						<?php endif; ?>
					</header>
					<?php
					/*
					 * The site's standard photo frame (client, Nov 2026, on the banner
					 * this replaced: "the styling of this photo is so random"). It had
					 * its own letterbox shape, a rounder corner and a heavier shadow
					 * than every other photo on the site. The file is a wide crop, so
					 * the frame is the widest of the standard shapes and is placed to
					 * keep all six people in it.
					 */
					ccs_section_photo(
						$ccs_wf_photo,
						array(
							'alt'      => __( 'Six members of the Continuity of Care Services team together at the office', 'ccs-wp-theme' ),
							'class'    => 'ccs-photo--wide',
							'position' => '58% 50%',
							'sizes'    => '(min-width: 900px) 620px, 92vw',
						)
					);
					?>
				</div>
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
