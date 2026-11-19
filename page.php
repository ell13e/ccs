<?php
/**
 * Default page template
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--page" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php
			// Two default-template pages have their own body layout (Nov 2026 review).
			$ccs_page_slug = get_post_field( 'post_name', get_the_ID() );
			if ( 'resources' === $ccs_page_slug ) {
				get_template_part( 'template-parts/resources-hub' );
			} elseif ( 'referral-information' === $ccs_page_slug ) {
				get_template_part( 'template-parts/referral-body' );
			} else {
				get_template_part( 'template-parts/article-body' );
			}
			?>
		</article>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
