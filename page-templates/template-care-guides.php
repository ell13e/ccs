<?php
/**
 * Template Name: Care Guides
 *
 * Lists ccs_resource posts as cards; each card has a "Get this care guide" button
 * that opens the resource download modal (lead capture). Modal and JS enqueued in theme.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--care-guides" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php if ( trim( get_the_content() ) !== '' ) : ?>
				<?php
				/*
				 * The guidance as the site's standard accordion (Nov 2026). It was
				 * seven sections of prose down one column with an "On this page"
				 * rail, which a design review rated among the weakest pages: a
				 * family could not see what was covered without scrolling 4,700px.
				 * Each heading is now a row that opens in place, the first one open.
				 * The words are the editor's own, split at the headings; a page
				 * with no headings prints as before.
				 */
				$ccs_cg = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
				?>
				<?php if ( count( $ccs_cg['sections'] ) >= 3 ) : ?>
					<section class="ccs-section ccs-section--flush-bottom care-guide">
						<div class="container container--lg">
							<div class="care-guide__inner">
								<?php if ( '' !== trim( wp_strip_all_tags( $ccs_cg['intro'] ) ) ) : ?>
									<div class="ccs-lede ccs-lede--large care-guide__lede">
										<?php echo wp_kses_post( $ccs_cg['intro'] ); ?>
									</div>
								<?php endif; ?>
								<div class="ccs-faq care-guide__faq">
									<?php foreach ( $ccs_cg['sections'] as $ccs_cg_i => $ccs_cg_section ) : ?>
										<details class="ccs-faq__item" name="care-guide"<?php echo 0 === $ccs_cg_i ? ' open' : ''; ?>>
											<summary class="ccs-faq__q"><?php echo esc_html( $ccs_cg_section['heading'] ); ?></summary>
											<div class="ccs-faq__a entry-content"><?php echo wp_kses_post( $ccs_cg_section['body'] ); ?></div>
										</details>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</section>
				<?php else : ?>
					<?php get_template_part( 'template-parts/article-body' ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			$resources = get_posts(
				array(
					'post_type'      => 'ccs_resource',
					'post_status'    => 'publish',
					'numberposts'    => -1,
					'orderby'        => 'menu_order title',
					'order'          => 'ASC',
				)
			);
			if ( ! empty( $resources ) ) :
				?>
				<div class="care-guides-grid-wrap">
					<div class="container container--lg">
						<ul class="care-guides-grid" aria-label="<?php esc_attr_e( 'Care guides', 'ccs-wp-theme' ); ?>">
							<?php
							foreach ( $resources as $resource ) {
								$id    = $resource->ID;
								$title = get_the_title( $id );
								$excerpt = has_excerpt( $id ) ? get_the_excerpt( $id ) : '';
								?>
								<li class="care-guide-card">
									<?php if ( get_the_post_thumbnail( $id, 'medium' ) ) : ?>
										<div class="care-guide-card__image">
											<?php echo get_the_post_thumbnail( $id, 'medium' ); ?>
										</div>
									<?php endif; ?>
									<div class="care-guide-card__body">
										<h2 class="care-guide-card__title"><?php echo esc_html( $title ); ?></h2>
										<?php if ( $excerpt ) : ?>
											<p class="care-guide-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
										<?php endif; ?>
										<button type="button" class="btn btn-primary resource-download-btn" data-resource-id="<?php echo esc_attr( (string) $id ); ?>" data-resource-name="<?php echo esc_attr( $title ); ?>">
											<?php esc_html_e( 'Get this care guide', 'ccs-wp-theme' ); ?>
										</button>
									</div>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
		</article>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
