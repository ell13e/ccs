<?php
/**
 * Homepage hero section: two-column grid, H1, CTAs, trust line, hero image with stats card.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_phone     = get_theme_mod( 'ccs_phone', '01234 567890' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
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
					<?php esc_html_e( 'Complex and personal care in Kent — from hospital discharge to long-term support', 'ccs-wp-theme' ); ?>
				</h1>
				<p class="home-hero__subtitle">
					<?php esc_html_e( 'We help individuals and families across Kent with tailored care at home. Whether you need support after a hospital stay or ongoing companionship and personal care, we’re here.', 'ccs-wp-theme' ); ?>
				</p>
				<div class="home-hero__ctas">
					<?php if ( $ccs_phone_tel ) : ?>
						<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn btn-phone btn-lg home-hero__cta-primary">
							<span aria-hidden="true"><?php echo esc_html( $ccs_phone ); ?></span>
						</a>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_permalink( get_theme_mod( 'ccs_learn_more_page', 0 ) ) ?: home_url( '/our-care/' ) ); ?>" class="btn btn-secondary btn-lg home-hero__cta-secondary">
						<?php esc_html_e( 'Learn more', 'ccs-wp-theme' ); ?>
					</a>
				</div>
				<div class="home-hero__trust">
					<span class="home-hero__trust-item"><?php esc_html_e( 'CQC regulated', 'ccs-wp-theme' ); ?></span>
					<span class="home-hero__trust-item"><?php esc_html_e( 'Based in Maidstone', 'ccs-wp-theme' ); ?></span>
					<span class="home-hero__trust-item"><?php esc_html_e( 'Covering Kent', 'ccs-wp-theme' ); ?></span>
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
