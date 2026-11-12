<?php
/**
 * Homepage: the three newest articles (Nov 2026).
 *
 * The client pointed to ultimacare.co.uk, whose homepage ends on its latest
 * news. CCS publishes practical guides for families (paying for care, what
 * an assessment involves, signs someone needs help), so showing the newest
 * three tells a visitor the company is active and knows its subject. Same
 * cards as the blog index (template-parts/post-card.php). Hidden when there
 * are no posts.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_news = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $ccs_news->have_posts() ) {
	return;
}

$ccs_news_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
?>

<section class="home-news" aria-labelledby="home-news-heading">
	<div class="container container--lg">
		<header class="ccs-head home-news__head">
			<h2 id="home-news-heading" class="ccs-title">
				<?php echo ccs_copy_accent( 'news_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent(). ?>
			</h2>
			<a class="ccs-text-link home-news__more" href="<?php echo esc_url( $ccs_news_url ); ?>"><?php echo esc_html( ccs_copy( 'news_button' ) ); ?> <span aria-hidden="true">&rarr;</span></a>
		</header>
		<ul class="post-list ccs-swipe" role="list">
			<?php
			while ( $ccs_news->have_posts() ) :
				$ccs_news->the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
