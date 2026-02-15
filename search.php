<?php
/**
 * Search results template
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--search" role="main">
	<div class="container container--lg">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search results for: %s', 'ccs-wp-theme' ),
					'<span>' . get_search_query() . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="search-results-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li <?php post_class( 'search-result' ); ?>>
						<h2 class="search-result__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<?php if ( has_excerpt() ) : ?>
							<div class="search-result__excerpt entry-summary">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
						<p class="search-result__meta">
							<a href="<?php the_permalink(); ?>" class="search-result__link">
								<?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						</p>
					</li>
					<?php
				endwhile;
				?>
			</ul>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( 'Previous', 'ccs-wp-theme' ),
					'next_text' => __( 'Next', 'ccs-wp-theme' ),
				)
			);
			?>
		<?php else : ?>
			<div class="no-results">
				<p><?php esc_html_e( 'Sorry, no results matched your search. Try different keywords.', 'ccs-wp-theme' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
