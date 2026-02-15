<?php
/**
 * Template Name: CQC and Our Care
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--cqc" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<header class="page-header entry-header">
					<?php the_title( '<h1 class="page-title entry-title">', '</h1>' ); ?>
				</header>
				<?php if ( get_the_content() ) : ?>
					<div class="page-body entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/home/cqc-section' ); ?>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
