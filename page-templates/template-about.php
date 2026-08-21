<?php
/**
 * Template Name: About
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<div class="page-body entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>

		<section class="about-story" aria-labelledby="about-story-heading">
			<div class="container container--md">
				<span class="about-story__eyebrow"><?php esc_html_e( 'What makes us different', 'ccs-wp-theme' ); ?></span>
				<h2 id="about-story-heading" class="about-story__heading"><?php esc_html_e( 'Our Story Starts Here', 'ccs-wp-theme' ); ?></h2>
				<p class="about-story__intro">
					<?php esc_html_e( "In this short video, our company director and registered manager, Victoria Walker, explains how Continuity of Care started right here in Kent and why our service is built on compassion, reliability, and enhancing quality of life. Whether you're looking for domiciliary care, complex support, or simply want to learn what sets us apart, we invite you to hear directly from the heart behind our organisation.", 'ccs-wp-theme' ); ?>
				</p>
				<?php
				get_template_part(
					'template-parts/video-embed',
					null,
					array(
						'video_id' => '7RJ-pdNh65o',
						'title'    => __( 'Our Story Starts Here \u2014 Victoria Walker, Registered Manager & Company Director', 'ccs-wp-theme' ),
					)
				);
				?>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
