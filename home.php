<?php
/**
 * Blog index (the page assigned as "Posts page" — News & Updates).
 *
 * Without this file WordPress fell back to index.php, which output no page
 * header, no container and one <h1> per post — so /news-and-updates/ was headed
 * "Hello world!" by the default WordPress post.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_posts_page_id = (int) get_option( 'page_for_posts' );
$ccs_heading       = $ccs_posts_page_id ? get_post_meta( $ccs_posts_page_id, 'ccs_page_heading', true ) : '';
$ccs_heading       = ( is_string( $ccs_heading ) && trim( $ccs_heading ) !== '' )
	? trim( $ccs_heading )
	: ( $ccs_posts_page_id ? get_the_title( $ccs_posts_page_id ) : __( 'News & Updates', 'ccs-wp-theme' ) );
$ccs_intro         = $ccs_posts_page_id ? get_post_meta( $ccs_posts_page_id, 'ccs_page_intro', true ) : '';
?>

<main id="main" class="site-main site-main--blog" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<?php get_template_part( 'template-parts/breadcrumb' ); ?>
			<h1 class="page-hero__title"><?php echo esc_html( $ccs_heading ); ?></h1>
			<?php if ( is_string( $ccs_intro ) && trim( $ccs_intro ) !== '' ) : ?>
				<p class="page-hero__intro"><?php echo esc_html( trim( $ccs_intro ) ); ?></p>
			<?php endif; ?>
		</div>
	</header>

	<?php get_template_part( 'template-parts/news-list' ); ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
