<?php
/**
 * Single post (news article).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--single" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<header class="page-hero">
			<div class="page-hero__inner container container--lg">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<p class="page-hero__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</p>
			</div>
		</header>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-post__media">
						<?php the_post_thumbnail( 'large', array( 'class' => 'single-post__img' ) ); ?>
					</figure>
				<?php endif; ?>
				<div class="page-body entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
