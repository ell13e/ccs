<?php
/**
 * Template Name: Careers
 *
 * Careers page with hero and job listings section. Provides .careers-hero and
 * #cvm_content for sticky Apply button scroll logic (CV Minder or equivalent).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--careers" role="main">

	<section class="careers-hero" aria-labelledby="careers-hero-heading">
		<div class="careers-hero__inner container container--lg">
			<h1 id="careers-hero-heading" class="careers-hero__title"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="careers-hero__intro">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section id="cvm_content" class="careers-listings" aria-labelledby="careers-listings-heading">
		<div class="careers-listings__inner container container--lg">
			<h2 id="careers-listings-heading" class="careers-listings__heading"><?php esc_html_e( 'Current vacancies', 'ccs-wp-theme' ); ?></h2>
			<div class="careers-listings__content">
				<?php
				// Job widget/shortcode/iframe placeholder. Replace with CV Minder shortcode or embed as needed.
				// Sticky Apply button uses #cvm_content for scroll target and visibility logic.
				echo apply_filters( 'the_content', get_post_meta( get_the_ID(), '_careers_listings_content', true ) );
				?>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
