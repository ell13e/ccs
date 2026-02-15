<?php
/**
 * Template Name: Getting Started
 *
 * Repurposed from CTA "Group Training" slot. Use for care journey / book a consultation
 * messaging. Content from editor; CTA can link to Contact or consultation form.
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
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<header class="page-header entry-header">
					<?php the_title( '<h1 class="page-title entry-title">', '</h1>' ); ?>
				</header>
				<div class="page-body entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
