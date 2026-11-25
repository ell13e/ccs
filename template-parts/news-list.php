<?php
/**
 * The news hub body: topic menu on the left, stories beside it, pagination
 * under the stories. Shared by the news index (home.php) and its topic
 * archives (archive.php), so moving between "All stories" and a topic
 * changes only the list, never the layout.
 *
 * Expects the main query to be the list of posts.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;
?>

<div class="post-list-section news-hub">
	<div class="container container--lg news-hub__inner">
		<?php get_template_part( 'template-parts/news-topics' ); ?>

		<div class="news-hub__main">
			<?php if ( have_posts() ) : ?>
				<ul class="news-list" role="list">
					<?php
					$ccs_i = 0;
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/post-row', null, array( 'first' => 0 === $ccs_i ) );
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
</div>
