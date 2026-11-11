<?php
/**
 * Areas hub (/areas/): every location as a card.
 *
 * This URL is the "Areas we cover" crumb on every location page, and used to
 * fall through to the blog archive template: "Archives: Locations" over a
 * list of post excerpts. Now it answers the question the crumb asks, one card
 * per town with the villages it covers.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone     = get_theme_mod( 'ccs_phone', '01622 809 881' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';

$ccs_areas = get_posts(
	array(
		'post_type'      => 'location',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
?>

<main id="main" class="site-main site-main--areas" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php echo esc_html( ccs_copy( 'areas_title' ) ); ?></h1>
				<p class="page-hero__intro">
					<?php echo esc_html( ccs_copy( 'areas_lede' ) ); ?>
				</p>
			</div>
		</div>
	</header>

	<?php if ( ! empty( $ccs_areas ) ) : ?>
		<section class="ccs-section ccs-section--flush-bottom areas-hub" aria-labelledby="areas-hub-heading">
			<div class="container container--lg">
				<h2 id="areas-hub-heading" class="screen-reader-text"><?php esc_html_e( 'Towns', 'ccs-wp-theme' ); ?></h2>
				<ul class="area-grid" role="list">
					<?php foreach ( $ccs_areas as $ccs_area ) : ?>
						<?php
						$ccs_area_town     = get_post_meta( $ccs_area->ID, 'location_town', true );
						$ccs_area_town     = $ccs_area_town ? $ccs_area_town : get_the_title( $ccs_area );
						$ccs_area_villages = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $ccs_area->ID, 'location_areas_covered', true ) ) ) );
						$ccs_area_shown    = array_slice( $ccs_area_villages, 0, 5 );
						$ccs_area_more     = count( $ccs_area_villages ) - count( $ccs_area_shown );
						?>
						<li class="area-card">
							<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<h3 class="area-card__title">
								<a class="area-card__link" href="<?php echo esc_url( get_permalink( $ccs_area ) ); ?>"><?php echo esc_html( $ccs_area_town ); ?></a>
							</h3>
							<?php if ( ! empty( $ccs_area_shown ) ) : ?>
								<p class="area-card__villages">
									<?php
									$ccs_area_line = implode( ', ', $ccs_area_shown );
									if ( $ccs_area_more > 0 ) {
										/* translators: 1: comma-separated village names, 2: number of further villages. */
										$ccs_area_line = sprintf( _n( '%1$s and %2$d more', '%1$s and %2$d more', $ccs_area_more, 'ccs-wp-theme' ), $ccs_area_line, $ccs_area_more );
									}
									echo esc_html( $ccs_area_line );
									?>
								</p>
							<?php endif; ?>
							<span class="area-card__cue" aria-hidden="true">
								<?php
								/* translators: %s: town name. */
								echo esc_html( sprintf( __( 'Care in %s', 'ccs-wp-theme' ), $ccs_area_town ) );
								?>
								&rarr;
							</span>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php if ( $ccs_phone_tel ) : ?>
					<div class="ccs-strip areas-hub__strip">
						<span class="ccs-badge"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<div class="ccs-strip__text">
							<h2 class="ccs-strip__title"><?php echo esc_html( ccs_copy( 'areas_strip_title' ) ); ?></h2>
							<p class="ccs-strip__lede"><?php echo esc_html( ccs_copy( 'areas_strip_text' ) ); ?></p>
						</div>
						<a class="btn btn-quiet ccs-strip__action" href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_phone ); ?></span>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
