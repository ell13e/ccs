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

		$ccs_category = function_exists( 'ccs_primary_category' ) ? ccs_primary_category() : null;
		?>
		<header class="page-hero">
			<div class="page-hero__inner container container--lg">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<?php if ( $ccs_category ) : ?>
					<p class="page-hero__eyebrow">
						<a href="<?php echo esc_url( get_category_link( $ccs_category->term_id ) ); ?>"><?php echo esc_html( $ccs_category->name ); ?></a>
					</p>
				<?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<p class="page-hero__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php if ( function_exists( 'ccs_reading_time_label' ) ) : ?>
						<span class="page-hero__meta-item"><?php echo esc_html( ccs_reading_time_label() ); ?></span>
					<?php endif; ?>
					<span class="page-hero__meta-item">
						<?php
						printf(
							/* translators: %s: author display name. */
							esc_html__( 'By %s', 'ccs-wp-theme' ),
							esc_html( get_the_author() )
						);
						?>
					</span>
				</p>
			</div>
		</header>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-post__media">
						<?php the_post_thumbnail( 'large', array( 'class' => 'single-post__img' ) ); ?>
						<?php
						$ccs_caption = get_the_post_thumbnail_caption();
						if ( $ccs_caption ) :
							?>
							<figcaption class="single-post__caption"><?php echo esc_html( $ccs_caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>
				<div class="page-body entry-content">
					<?php the_content(); ?>
				</div>

				<?php
				/*
				 * Updated date, but only when it is meaningfully later than
				 * publication. WordPress bumps post_modified on any save, so a
				 * typo fix an hour after publishing would otherwise display as
				 * "Updated" and quietly devalue the label everywhere it appears.
				 * A day's grace is the smallest gap that means a real revisit.
				 */
				$ccs_published = (int) get_post_time( 'U', true );
				$ccs_modified  = (int) get_post_modified_time( 'U', true );
				if ( $ccs_modified - $ccs_published > DAY_IN_SECONDS ) :
					?>
					<p class="single-post__updated">
						<?php
						printf(
							/* translators: %s: date the article was last updated. */
							esc_html__( 'Last updated %s', 'ccs-wp-theme' ),
							esc_html( get_the_modified_date() )
						);
						?>
					</p>
				<?php endif; ?>
			</div>
		</article>

		<?php
		$ccs_related = function_exists( 'ccs_related_posts' ) ? ccs_related_posts( get_post(), 3 ) : array();
		if ( ! empty( $ccs_related ) ) :
			?>
			<section class="related-posts" aria-labelledby="related-posts-heading">
				<div class="container container--lg">
					<h2 id="related-posts-heading" class="related-posts__heading"><?php esc_html_e( 'More from our news', 'ccs-wp-theme' ); ?></h2>
					<ul class="post-list" role="list">
						<?php
						foreach ( $ccs_related as $ccs_related_post ) :
							setup_postdata( $GLOBALS['post'] = $ccs_related_post ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							get_template_part( 'template-parts/post-card' );
						endforeach;
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</section>
			<?php
		endif;
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
