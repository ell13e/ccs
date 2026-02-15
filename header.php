<?php
/**
 * Header template
 *
 * Top bar (CQC, phone, hours), main header (logo, nav, CTA), optional emergency banner.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_phone           = get_theme_mod( 'ccs_phone', '01234 567890' );
$ccs_office_hours    = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$ccs_cqc_url         = get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk' );
$ccs_cqc_img         = get_theme_mod( 'ccs_cqc_badge_url', '' );
$ccs_emergency_on    = (bool) get_theme_mod( 'ccs_emergency_banner_enabled', false );
$ccs_emergency_text  = get_theme_mod( 'ccs_emergency_banner', '' );
$ccs_emergency_link  = get_theme_mod( 'ccs_emergency_banner_link', '' );
$ccs_emergency       = $ccs_emergency_on && ( $ccs_emergency_text || $ccs_phone );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="skip-link"><?php esc_html_e( 'Skip to content', 'ccs-wp-theme' ); ?></a>

<?php if ( $ccs_emergency ) : ?>
	<div class="emergency-banner" role="alert">
		<div class="emergency-banner__inner">
			<?php if ( $ccs_emergency_text ) : ?>
				<p class="emergency-banner__text">
					<?php if ( $ccs_emergency_link ) : ?>
						<a href="<?php echo esc_url( $ccs_emergency_link ); ?>"><?php echo wp_kses_post( $ccs_emergency_text ); ?></a>
					<?php else : ?>
						<?php echo wp_kses_post( $ccs_emergency_text ); ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ( $ccs_phone ) : ?>
				<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $ccs_phone ) ); ?>" class="emergency-banner__phone btn btn-phone btn-sm"><?php esc_html_e( 'Call now', 'ccs-wp-theme' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<header id="masthead" class="site-header" role="banner">
	<!-- Top bar -->
	<div class="site-header__top">
		<div class="site-header__top-inner">
			<div class="site-header__top-left">
				<?php if ( $ccs_cqc_url ) : ?>
					<a href="<?php echo esc_url( $ccs_cqc_url ); ?>" class="site-header__cqc" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Care Quality Commission – view our profile (opens in new window)', 'ccs-wp-theme' ); ?>">
						<?php if ( $ccs_cqc_img ) : ?>
							<img src="<?php echo esc_url( $ccs_cqc_img ); ?>" alt="" width="80" height="32" loading="lazy">
						<?php else : ?>
							<span class="site-header__cqc-text"><?php esc_html_e( 'CQC Regulated', 'ccs-wp-theme' ); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="site-header__top-right">
				<?php if ( $ccs_office_hours ) : ?>
					<span class="site-header__hours" aria-label="<?php esc_attr_e( 'Office hours', 'ccs-wp-theme' ); ?>"><?php echo esc_html( $ccs_office_hours ); ?></span>
				<?php endif; ?>
				<?php if ( $ccs_phone ) : ?>
					<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $ccs_phone ) ); ?>" class="site-header__phone"><?php echo esc_html( $ccs_phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Main header (CTA-style) -->
	<?php
	$ccs_contact = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => $ccs_phone, 'phone_link' => $ccs_phone ? 'tel:' . preg_replace( '/\s+/', '', $ccs_phone ) : '' );
	$ccs_cta_url = get_theme_mod( 'ccs_cta_url', home_url( '/contact/' ) );
	?>
	<div class="header-container">
		<div class="header-inner-wrapper">
			<div class="header-logo">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div>

			<button type="button" id="mobile-menu-button" class="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-navigation" aria-label="<?php esc_attr_e( 'Open menu', 'ccs-wp-theme' ); ?>">
				<span class="mobile-menu-btn__icon" aria-hidden="true"></span>
			</button>

			<nav id="site-navigation" class="nav-desktop" aria-label="<?php esc_attr_e( 'Primary navigation', 'ccs-wp-theme' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'menu_id'         => 'primary-menu',
							'menu_class'      => 'nav-list',
							'container'       => false,
							'fallback_cb'     => false,
							'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
							'link_class'      => 'nav-link',
						)
					);
				} else {
					ccs_primary_menu_fallback();
				}
				?>
			</nav>

			<div class="header-actions">
				<?php if ( ! empty( $ccs_contact['phone'] ) ) : ?>
					<a href="<?php echo esc_url( $ccs_contact['phone_link'] ); ?>" class="header-actions__phone"><?php echo esc_html( $ccs_contact['phone'] ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $ccs_cta_url ); ?>" class="header-actions__cta btn btn-primary"><?php esc_html_e( 'Book a care consultation', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>

		<div id="mobile-navigation" class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ccs-wp-theme' ); ?>" hidden>
			<nav class="mobile-menu-content" aria-label="<?php esc_attr_e( 'Primary navigation', 'ccs-wp-theme' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'menu_id'         => 'mobile-menu-list',
							'menu_class'      => 'mobile-menu-list',
							'container'       => false,
							'fallback_cb'     => false,
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'link_class'      => 'mobile-menu-link',
						)
					);
				} else {
					ccs_primary_menu_fallback_mobile();
				}
				?>
			</nav>
			<?php if ( ! empty( $ccs_contact['phone'] ) ) : ?>
				<a href="<?php echo esc_url( $ccs_contact['phone_link'] ); ?>" class="mobile-menu__phone"><?php echo esc_html( $ccs_contact['phone'] ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $ccs_cta_url ); ?>" class="mobile-menu__cta btn btn-primary"><?php esc_html_e( 'Book a care consultation', 'ccs-wp-theme' ); ?></a>
		</div>
	</div>
</header>

<?php
$schema_logo = '';
if ( has_custom_logo() ) {
	$logo_id = get_theme_mod( 'custom_logo' );
	$logo    = wp_get_attachment_image_src( $logo_id, 'full' );
	if ( $logo ) {
		$schema_logo = $logo[0];
	}
}
?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Organization",
	"name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>",
	"url": "<?php echo esc_url( home_url( '/' ) ); ?>"
	<?php if ( $schema_logo ) : ?>
	,"logo": "<?php echo esc_url( $schema_logo ); ?>"
	<?php endif; ?>
	<?php if ( $ccs_phone ) : ?>
	,"contactPoint": {
		"@type": "ContactPoint",
		"telephone": "<?php echo esc_js( preg_replace( '/\s+/', '', $ccs_phone ) ); ?>",
		"contactType": "customer service",
		"areaServed": "GB",
		"availableLanguage": "English"
	}
	<?php endif; ?>
}
</script>

<div id="content" class="site-content">
