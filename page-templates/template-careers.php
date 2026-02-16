<?php
/**
 * Template Name: Careers Hub
 *
 * Careers section entry: content + links to Professional development, Current vacancies, Working for us.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--careers" role="main">
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
					<?php
					$careers_page_ids = (array) get_option( 'ccs_careers_page_ids', array() );
					$child_slugs     = array( 'professional-development', 'current-vacancies', 'working-for-us' );
					$children        = array();
					foreach ( $child_slugs as $slug ) {
						if ( ! empty( $careers_page_ids[ $slug ] ) ) {
							$children[] = get_post( $careers_page_ids[ $slug ] );
						}
					}
					$children = array_filter( $children );
					if ( ! empty( $children ) ) :
						?>
						<nav class="careers-sub-nav" aria-label="<?php esc_attr_e( 'Careers section links', 'ccs-wp-theme' ); ?>">
							<ul class="careers-sub-nav__list">
								<?php foreach ( $children as $child ) : ?>
									<li><a href="<?php echo esc_url( get_permalink( $child ) ); ?>"><?php echo esc_html( get_the_title( $child ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
