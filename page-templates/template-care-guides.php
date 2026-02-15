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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<div class="container container--lg">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<header class="page-header entry-header">
					<?php the_title( '<h1 class="page-title entry-title">', '</h1>' ); ?>
				</header>
				<?php if ( get_the_content() ) : ?>
					<div class="page-body entry-content care-guides-intro">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>

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
</main>

<?php
get_footer();
