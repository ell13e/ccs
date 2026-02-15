<?php
/**
 * Template Name: Content Page
 *
 * Single layout for policy/legal/utility pages: Privacy, Terms, Accessibility, Cookies.
 * Breadcrumb, title, then post content. Main class uses page slug for per-page styling.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$slug = get_post_field( 'post_name', get_queried_object_id() );
$main_class = 'site-main site-main--content-page site-main--' . sanitize_html_class( $slug );
?>

<main id="main" class="<?php echo esc_attr( $main_class ); ?>" role="main">
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
