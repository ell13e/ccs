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
		<?php
		/*
		 * Redesigned to match the homepage (Nov 2026). The article opened on
		 * the header with a small photo under it. Now: the standard header
		 * (category, title, date and reading time), the photograph large and
		 * lapping over the foot of the band,
		 * and the text in one reading column with a numbered "In this article"
		 * list beside it on wide screens when there are three or more headings.
		 * Structure after the editorial article layouts in the Pinterest
		 * research: centred title over one large image, pull quotes in the
		 * heading face.
		 */
		$ccs_art      = ccs_content_anchors( apply_filters( 'the_content', get_the_content() ) );
		$ccs_art_toc  = count( $ccs_art['toc'] ) >= 3;
		$ccs_art_meta = array( get_the_date() );
		if ( function_exists( 'ccs_reading_time_label' ) ) {
			$ccs_art_meta[] = ccs_reading_time_label();
		}
		/* translators: %s: author display name. */
		$ccs_art_meta[] = sprintf( __( 'By %s', 'ccs-wp-theme' ), get_the_author() );
		?>
		<header class="page-hero page-hero--article">
			<div class="page-hero__inner container container--lg">
				<div class="page-hero__text">
					<?php get_template_part( 'template-parts/breadcrumb' ); ?>
					<?php if ( $ccs_category ) : ?>
						<p class="page-hero__eyebrow">
							<a href="<?php echo esc_url( get_category_link( $ccs_category->term_id ) ); ?>"><?php echo esc_html( $ccs_category->name ); ?></a>
						</p>
					<?php endif; ?>
					<h1 class="page-hero__title"><?php the_title(); ?></h1>
					<p class="page-hero__meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<?php foreach ( array_slice( $ccs_art_meta, 1 ) as $ccs_art_m ) : ?>
							<span class="page-hero__meta-item"><?php echo esc_html( $ccs_art_m ); ?></span>
						<?php endforeach; ?>
					</p>
				</div>
			</div>
		</header>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="hero-lap">
					<div class="container container--lg">
						<figure class="article-figure">
							<?php
							the_post_thumbnail(
								'full',
								array(
									'class'         => 'article-figure__img',
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'sizes'         => '(min-width: 1100px) 1024px, 92vw',
								)
							);
							$ccs_caption = get_the_post_thumbnail_caption();
							?>
							<?php if ( $ccs_caption ) : ?>
								<figcaption><?php echo esc_html( $ccs_caption ); ?></figcaption>
							<?php endif; ?>
						</figure>
					</div>
				</div>
			<?php endif; ?>

			<div class="ccs-section ccs-section--sm">
				<div class="container container--lg">
					<div class="article-read<?php echo $ccs_art_toc ? ' article-read--toc' : ''; ?>">
						<?php if ( $ccs_art_toc ) : ?>
							<nav class="article-rail" aria-labelledby="article-rail-title">
								<p class="article-rail__title" id="article-rail-title"><?php esc_html_e( 'In this article', 'ccs-wp-theme' ); ?></p>
								<ol class="article-rail__list" role="list">
									<?php foreach ( $ccs_art['toc'] as $ccs_art_id => $ccs_art_label ) : ?>
										<li><a href="#<?php echo esc_attr( $ccs_art_id ); ?>"><?php echo esc_html( $ccs_art_label ); ?></a></li>
									<?php endforeach; ?>
								</ol>
							</nav>
						<?php endif; ?>

						<div class="article-read__main">
							<div class="article-body entry-content">
								<?php echo $ccs_art['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output with heading ids added, printed as the_content() would. ?>
							</div>

							<?php
							/*
							 * Updated date, but only when it is meaningfully later than
							 * publication. WordPress bumps post_modified on any save, so a
							 * typo fix an hour after publishing would otherwise display as
							 * "Updated". A day's grace is the smallest gap that means a
							 * real revisit.
							 */
							$ccs_published = (int) get_post_time( 'U', true );
							$ccs_modified  = (int) get_post_modified_time( 'U', true );
							if ( $ccs_modified - $ccs_published > DAY_IN_SECONDS ) :
								?>
								<p class="article-updated">
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
					</div>
				</div>
			</div>
		</article>

		<?php
		$ccs_related = function_exists( 'ccs_related_posts' ) ? ccs_related_posts( get_post(), 3 ) : array();
		if ( ! empty( $ccs_related ) ) :
			?>
			<section class="ccs-section ccs-section--tint article-more" aria-labelledby="related-posts-heading">
				<div class="container container--lg">
					<header class="ccs-head ccs-head--center">
						<h2 id="related-posts-heading" class="ccs-title"><?php esc_html_e( 'More from our news', 'ccs-wp-theme' ); ?></h2>
					</header>
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
