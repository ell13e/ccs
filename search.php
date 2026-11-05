<?php
/**
 * Search results.
 *
 * Tint page hero with the query and the search form (to refine without going
 * back), then a plain list: type, title, a line of text. No cards, since a
 * results list is reading, and each result is a single link (its title).
 * It used to repeat the link as a separate "Read more" too: same destination
 * twice, the second one meaningless out of context and 18px tall
 * (CCS/CLAUDE.md rules 1, 14 and 19). An empty search offers the three routes
 * most people want instead of a dead end.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;
$ccs_found = (int) $wp_query->found_posts;
$ccs_query = get_search_query();
?>

<main id="main" class="site-main site-main--search" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php esc_html_e( 'Search results', 'ccs-wp-theme' ); ?></h1>
				<p class="page-hero__intro">
					<?php
					if ( $ccs_found ) {
						/* translators: 1: number of results, 2: the search query. */
						echo esc_html( sprintf( _n( '%1$d result for “%2$s”', '%1$d results for “%2$s”', $ccs_found, 'ccs-wp-theme' ), $ccs_found, $ccs_query ) );
					} else {
						/* translators: %s: the search query. */
						echo esc_html( sprintf( __( 'Nothing matched “%s”. Try a different word, or start from one of the pages below.', 'ccs-wp-theme' ), $ccs_query ) );
					}
					?>
				</p>
				<div class="search-hero__form"><?php get_search_form(); ?></div>
			</div>
		</div>
	</header>

	<section class="ccs-section ccs-section--sm ccs-section--flush-bottom search-results" aria-labelledby="search-results-heading">
		<div class="container container--lg">
			<h2 id="search-results-heading" class="screen-reader-text"><?php esc_html_e( 'Results', 'ccs-wp-theme' ); ?></h2>

			<?php if ( have_posts() ) : ?>
				<ol class="search-list" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						$ccs_type_obj = get_post_type_object( get_post_type() );
						$ccs_type     = 'post' === get_post_type() ? __( 'Article', 'ccs-wp-theme' ) : ( $ccs_type_obj ? $ccs_type_obj->labels->singular_name : '' );
						$ccs_summary  = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 28 );
						?>
						<li <?php post_class( 'search-hit' ); ?>>
							<?php if ( $ccs_type ) : ?>
								<p class="search-hit__type"><?php echo esc_html( $ccs_type ); ?></p>
							<?php endif; ?>
							<h3 class="search-hit__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php if ( $ccs_summary ) : ?>
								<p class="search-hit__excerpt"><?php echo esc_html( $ccs_summary ); ?></p>
							<?php endif; ?>
						</li>
						<?php
					endwhile;
					?>
				</ol>
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
				<?php
				$ccs_routes = array(
					array( 'home-care-services-kent', __( 'Our care services', 'ccs-wp-theme' ), __( 'Domiciliary, respite and complex care at home.', 'ccs-wp-theme' ) ),
					array( 'resources/faqs', __( 'Common questions', 'ccs-wp-theme' ), __( 'Costs, timescales, and who your carers would be.', 'ccs-wp-theme' ) ),
					array( 'contact-us', __( 'Talk to someone', 'ccs-wp-theme' ), __( 'Book a free consultation, or ask us anything.', 'ccs-wp-theme' ) ),
				);
				?>
				<ul class="search-list" role="list">
					<?php foreach ( $ccs_routes as $ccs_route ) : ?>
						<li class="search-hit">
							<h3 class="search-hit__title">
								<a href="<?php echo esc_url( function_exists( 'ccs_page_url' ) ? ccs_page_url( $ccs_route[0] ) : home_url( '/' . $ccs_route[0] . '/' ) ); ?>"><?php echo esc_html( $ccs_route[1] ); ?></a>
							</h3>
							<p class="search-hit__excerpt"><?php echo esc_html( $ccs_route[2] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
