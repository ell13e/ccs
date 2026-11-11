<?php
/**
 * Template Name: About
 *
 * Photo hero, then the page's own copy in a split intro, the people (the same
 * "Meet the people" band as the homepage, linking on to Who you'll meet), and
 * Victoria's story video beside its section head. Built from the section
 * system; no About-only components.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--about" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<section class="ccs-section about-intro" aria-labelledby="about-intro-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="about-intro-heading" class="ccs-title">
							<?php
							echo ccs_copy_accent( 'about_intro_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
							?>
						</h2>
					</header>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
						<div class="page-body page-body--flush entry-content">
							<?php the_content(); ?>
						</div>
					</article>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/home/team' ); ?>

		<section class="ccs-section about-story" aria-labelledby="about-story-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="about-story-heading" class="ccs-title">
							<?php
							echo esc_html( ccs_copy( 'about_story_title' ) );
							?>
						</h2>
						<p class="ccs-lede">
							<?php echo esc_html( ccs_copy( 'about_story_text' ) ); ?>
						</p>
					</header>
					<div class="about-story__media">
						<?php
						get_template_part(
							'template-parts/video-embed',
							null,
							array(
								'video_id' => '7RJ-pdNh65o',
								/*
								 * Was 'Our Story Starts Here — Victoria Walker, …' in single
								 * quotes, where PHP does not expand \u escapes: the accessible name
								 * of the play button literally read "backslash u 2014".
								 */
								'title'    => __( 'Our story, told by Victoria Walker, Registered Manager and Company Director', 'ccs-wp-theme' ),
							)
						);
						?>
					</div>
				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php
	// Moved from the homepage (calm pass, Nov 2026): "who we work with" belongs with "who we are".
	get_template_part( 'template-parts/home/partnerships' );
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
