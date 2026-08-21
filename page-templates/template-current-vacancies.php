<?php
/**
 * Template Name: Current Vacancies
 *
 * Careers page: intro content + CV Minder job portal embed.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--current-vacancies" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php if ( get_the_content() ) : ?>
					<div class="page-body entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/careers/cv-minder-embed' ); ?>
		</article>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
