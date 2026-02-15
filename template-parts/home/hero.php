<?php
/**
 * Homepage hero section: two-column grid, H1, CTAs, trust line, hero image with stats card.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_image_id = get_theme_mod( 'ccs_hero_image', 0 );
$hero_image    = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'large' ) : '';
$hero_alt      = $hero_image_id ? get_post_meta( $hero_image_id, '_wp_attachment_image_alt', true ) : '';
$hero_alt      = is_string( $hero_alt ) ? trim( $hero_alt ) : '';
?>

<section class="home-hero" aria-labelledby="home-hero-heading">
	<div class="home-hero__inner container container--xl">
		<div class="home-hero__grid">
			<div class="home-hero__content">
				<h1 id="home-hero-heading" class="home-hero__title">
					<?php esc_html_e( 'Home Care in Maidstone & Kent — Your Team, Your Time, Your Life', 'ccs-wp-theme' ); ?>
				</h1>
				<p class="home-hero__subtitle home-hero__subtitle--h2">
					<?php esc_html_e( 'Trusted Home Care Services in Maidstone & Kent', 'ccs-wp-theme' ); ?>
				</p>
				<p class="home-hero__description">
					<?php esc_html_e( 'Compassionately supporting children and adults with domiciliary, disability, respite, complex, and palliative care, day or night, 24/7.', 'ccs-wp-theme' ); ?>
				</p>
				<div class="home-hero__ctas">
					<a href="<?php echo esc_url( home_url( '/home/home-care-services-kent/' ) ); ?>" class="btn btn-primary btn-lg home-hero__cta-primary">
						<?php esc_html_e( 'Explore Our Services', 'ccs-wp-theme' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/home/care-careers-maidstone-kent/' ) ); ?>" class="btn btn-secondary btn-lg home-hero__cta-secondary">
						<?php esc_html_e( 'Explore Career Paths', 'ccs-wp-theme' ); ?>
					</a>
				</div>
				<div class="home-hero__trust">
					<span class="home-hero__trust-item"><?php esc_html_e( 'CQC regulated', 'ccs-wp-theme' ); ?></span>
					<span class="home-hero__trust-item"><?php esc_html_e( 'Based in Maidstone', 'ccs-wp-theme' ); ?></span>
					<span class="home-hero__trust-item"><?php esc_html_e( 'Serving Kent', 'ccs-wp-theme' ); ?></span>
				</div>
			</div>
			<div class="home-hero__media">
				<?php if ( $hero_image ) : ?>
					<div class="home-hero__image-wrap">
						<img
							src="<?php echo esc_url( $hero_image ); ?>"
							alt="<?php echo $hero_alt !== '' ? esc_attr( $hero_alt ) : ''; ?>"
							class="home-hero__image"
							width="800"
							height="600"
							fetchpriority="high"
							loading="eager"
						>
						<div class="home-hero__stats-card" aria-hidden="true">
							<span class="home-hero__stats-value"><?php echo absint( get_theme_mod( 'ccs_hero_stat_value', '15' ) ); ?>+</span>
							<span class="home-hero__stats-label"><?php esc_html_e( 'Years of care experience', 'ccs-wp-theme' ); ?></span>
						</div>
					</div>
				<?php else : ?>
					<div class="home-hero__image-wrap home-hero__image-wrap--placeholder">
						<div class="home-hero__placeholder" role="img" aria-hidden="true"></div>
						<div class="home-hero__stats-card" aria-hidden="true">
							<span class="home-hero__stats-value"><?php echo absint( get_theme_mod( 'ccs_hero_stat_value', '15' ) ); ?>+</span>
							<span class="home-hero__stats-label"><?php esc_html_e( 'Years of care experience', 'ccs-wp-theme' ); ?></span>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
