<?php
/**
 * Homepage hero section: full-bleed background, H1, H2, description, primary/secondary CTAs.
 * Content from CCS-THEME-AND-CONTENT-GUIDE.md Section 2b. Design: MASTER.md.
 * Organization/LocalBusiness schema is output by inc/schema-markup.php (not in this partial).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_image_id = get_theme_mod( 'ccs_hero_image', 0 );
$hero_desktop  = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
$hero_tablet   = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'large' ) : '';
$hero_mobile   = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'medium_large' ) : '';
if ( ! $hero_tablet && $hero_desktop ) {
	$hero_tablet = $hero_desktop;
}
if ( ! $hero_mobile && $hero_tablet ) {
	$hero_mobile = $hero_tablet;
}
if ( ! $hero_desktop && $hero_tablet ) {
	$hero_desktop = $hero_tablet;
}

$services_url = get_theme_mod( 'ccs_services_page', 0 );
$services_url = $services_url ? get_permalink( $services_url ) : home_url( '/services/' );
$careers_url  = get_theme_mod( 'ccs_careers_page', 0 );
$careers_url  = $careers_url ? get_permalink( $careers_url ) : home_url( '/careers/' );

$inline_style = '';
if ( $hero_mobile || $hero_tablet || $hero_desktop ) {
	$inline_style = ' style="';
	if ( $hero_mobile ) {
		$inline_style .= '--hero-bg-mobile: url(' . esc_url( $hero_mobile ) . ');';
	}
	if ( $hero_tablet ) {
		$inline_style .= ' --hero-bg-tablet: url(' . esc_url( $hero_tablet ) . ');';
	}
	if ( $hero_desktop ) {
		$inline_style .= ' --hero-bg-desktop: url(' . esc_url( $hero_desktop ) . ');';
	}
	$inline_style .= '"';
}
?>

<section class="hero-homepage" aria-labelledby="hero-homepage__title"<?php echo $inline_style; ?>>
	<div class="hero-homepage__backdrop" aria-hidden="true"></div>
	<div class="hero-homepage__container">
		<div class="hero-homepage__content">
			<h1 id="hero-homepage__title" class="hero-homepage__title">
				<?php esc_html_e( 'Home Care in Maidstone & Kent — Your Team, Your Time, Your Life', 'ccs-wp-theme' ); ?>
			</h1>
			<h2 class="hero-homepage__subtitle">
				<?php esc_html_e( 'Trusted Home Care Services in Maidstone & Kent', 'ccs-wp-theme' ); ?>
			</h2>
			<p class="hero-homepage__description">
				<?php esc_html_e( 'Compassionately supporting children and adults with domiciliary, disability, respite, complex, and palliative care, day or night, 24/7.', 'ccs-wp-theme' ); ?>
			</p>
			<div class="hero-homepage__ctas">
				<a href="<?php echo esc_url( $services_url ); ?>" class="hero-homepage__cta hero-homepage__cta--primary btn btn-primary">
					<?php esc_html_e( 'Explore Our Services', 'ccs-wp-theme' ); ?>
				</a>
				<a href="<?php echo esc_url( $careers_url ); ?>" class="hero-homepage__cta hero-homepage__cta--secondary btn btn-secondary">
					<?php esc_html_e( 'Explore Career Paths', 'ccs-wp-theme' ); ?>
				</a>
			</div>
			<?php
			$hero_stat = absint( get_theme_mod( 'ccs_hero_stat_value', 15 ) );
			if ( $hero_stat > 0 ) :
				?>
			<p class="hero-homepage__stat" aria-hidden="true">
				<span class="count-up" data-target="<?php echo (int) $hero_stat; ?>" data-duration="2000">0</span>+
				<?php esc_html_e( 'years of care experience', 'ccs-wp-theme' ); ?>
			</p>
			<?php endif; ?>
		</div>
	</div>
</section>
