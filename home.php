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

	<div class="post-list-section">
		<div class="container container--lg">
			<?php if ( have_posts() ) : ?>
				<ul class="post-list" role="list">
					<?php
					/*
					 * The newest post on page one leads. Only on page one, and
					 * only when there is more than one post: promoting the sole
					 * item on an otherwise empty index, or the fourth-newest
					 * post at the top of page two, would both be lying about
					 * what "lead story" means.
					 */
					$ccs_lead = ! is_paged() && $wp_query->post_count > 1;
					$ccs_i    = 0;

					while ( have_posts() ) :
						the_post();
						get_template_part(
							'template-parts/post-card',
							null,
							array( 'featured' => ( $ccs_lead && 0 === $ccs_i ) )
						);
						++$ccs_i;
					endwhile;
					?>
				</ul>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( '&larr; Newer', 'ccs-wp-theme' ),
						'next_text' => __( 'Older &rarr;', 'ccs-wp-theme' ),
					)
				);
				?>
			<?php else : ?>
				<p class="post-list__empty"><?php esc_html_e( 'Nothing here just yet. Check back soon.', 'ccs-wp-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
