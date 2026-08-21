<?php
/**
 * Template Name: Services Hub
 *
 * Services landing page: page header, page content, then a card grid built from
 * the `service` CPT. Previously this page rendered through the default template,
 * so the services themselves appeared only as a plain bulleted list of links.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_services = get_posts(
	array(
		'post_type'      => 'service',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);
?>

<main id="main" class="site-main site-main--services" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php if ( trim( get_the_content() ) !== '' ) : ?>
				<div class="container container--lg">
					<div class="page-body entry-content">
						<?php the_content(); ?>
					</div>
				</div>
			<?php endif; ?>
		</article>
		<?php
	endwhile;
	?>

	<?php if ( ! empty( $ccs_services ) ) : ?>
		<section class="services-grid-section" aria-labelledby="services-grid-heading">
			<div class="container container--lg">
				<h2 id="services-grid-heading" class="services-grid-section__heading">
					<?php esc_html_e( 'The care we provide', 'ccs-wp-theme' ); ?>
				</h2>
				<ul class="services-grid" role="list">
					<?php foreach ( $ccs_services as $ccs_service ) : ?>
						<li class="service-card">
							<?php if ( has_post_thumbnail( $ccs_service ) ) : ?>
								<a class="service-card__media" href="<?php echo esc_url( get_permalink( $ccs_service ) ); ?>" tabindex="-1" aria-hidden="true">
									<?php echo get_the_post_thumbnail( $ccs_service, 'medium_large', array( 'class' => 'service-card__img', 'loading' => 'lazy' ) ); ?>
								</a>
							<?php endif; ?>
							<div class="service-card__body">
								<h3 class="service-card__title">
									<a href="<?php echo esc_url( get_permalink( $ccs_service ) ); ?>">
										<?php echo esc_html( get_the_title( $ccs_service ) ); ?>
									</a>
								</h3>
								<p class="service-card__excerpt">
									<?php
									$ccs_excerpt = has_excerpt( $ccs_service )
										? get_the_excerpt( $ccs_service )
										: wp_trim_words( get_post_field( 'post_content', $ccs_service ), 28 );
									echo esc_html( $ccs_excerpt );
									?>
								</p>
								<span class="service-card__link" aria-hidden="true">
									<?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?>
									<span>&rarr;</span>
								</span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
