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
	<?php
	/*
	 * Top utility bar removed 2026-08-19: it added a strip of chrome above the
	 * header that the agreed reference design doesn't have. The phone moved
	 * into the main row as a pill; CQC and office hours are carried by the
	 * hero proof line and the footer instead.
	 */
	?>

	<!-- Main header (CTA-style) -->
	<?php
	$ccs_contact = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => $ccs_phone, 'phone_link' => $ccs_phone ? 'tel:' . preg_replace( '/\s+/', '', $ccs_phone ) : '' );
	// Resolve the real Contact permalink; the previous home_url( '/contact/' )
	// default pointed at a URL that doesn't exist (the page slug is contact-us).
	$ccs_contact_default = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	$ccs_cta_url = get_theme_mod( 'ccs_cta_url', $ccs_contact_default );
	// "Switch to us" — dedicated CTA for families unhappy with their current provider (see BUILD-LOG.md 2026-08-19). Points at Contact until a dedicated page/form exists.
	$ccs_switch_url = get_theme_mod( 'ccs_switch_to_us_url', $ccs_contact_default );

	// On careers pages, show careers menu if assigned; otherwise primary.
	$ccs_careers_page_ids = (array) get_option( 'ccs_careers_page_ids', array() );
	$ccs_is_careers_context = false;
	if ( is_page() ) {
		$ccs_page_id = get_queried_object_id();
		if ( in_array( $ccs_page_id, array_values( $ccs_careers_page_ids ), true ) ) {
			$ccs_is_careers_context = true;
		} else {
			$ccs_ancestors = get_post_ancestors( $ccs_page_id );
			foreach ( $ccs_ancestors as $ccs_aid ) {
				$ccs_p = get_post( $ccs_aid );
				if ( $ccs_p && $ccs_p->post_name === 'careers' ) {
					$ccs_is_careers_context = true;
					break;
				}
			}
		}
	}
	$ccs_nav_location = ( $ccs_is_careers_context && has_nav_menu( 'careers' ) ) ? 'careers' : 'primary';
	?>
	<div class="header-container">
		<div class="header-inner-wrapper">
			<div class="header-logo">
				<?php
				$ccs_logo_rel     = '/assets/images/brand/ccs-logo-long.png';
				$ccs_logo_default = get_template_directory() . $ccs_logo_rel;
				if ( has_custom_logo() ) :
					the_custom_logo();
				elseif ( file_exists( $ccs_logo_default ) ) :
					// Packaged brand lockup, so the header shows the real logo rather than
					// plain text before anyone uploads one in the Customizer.
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link logo-link--img" rel="home">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . $ccs_logo_rel ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="header-logo__img"
							width="1617"
							height="276"
						>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div>

			<button type="button" id="mobile-menu-button" class="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-navigation" aria-label="<?php esc_attr_e( 'Open menu', 'ccs-wp-theme' ); ?>">
				<span class="mobile-menu-btn__icon" aria-hidden="true"></span>
			</button>

			<nav id="site-navigation" class="nav-desktop" aria-label="<?php esc_attr_e( 'Primary navigation', 'ccs-wp-theme' ); ?>">
				<?php
				if ( has_nav_menu( $ccs_nav_location ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => $ccs_nav_location,
							'menu_id'         => 'primary-menu',
							'menu_class'      => 'nav-list',
							'container'       => false,
							'fallback_cb'     => false,
							'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
							'link_class'      => 'nav-link',
						)
					);
				} elseif ( has_nav_menu( 'primary' ) ) {
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
				<?php
				/*
				 * Two actions only. "Book a free consultation" is deliberately absent:
				 * the hero and page-level CTAs carry it, so repeating it here just
				 * competed with itself.
				 */
				?>
				<?php if ( ! empty( $ccs_contact['phone'] ) ) : ?>
					<a href="<?php echo esc_url( $ccs_contact['phone_link'] ); ?>" class="header-actions__phone btn btn-primary">
						<?php echo esc_html( $ccs_contact['phone'] ); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $ccs_switch_url ); ?>" class="header-actions__switch btn btn-accent"><?php esc_html_e( 'Switch to us', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>

		<div id="mobile-navigation" class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ccs-wp-theme' ); ?>" hidden>
			<nav class="mobile-menu-content" aria-label="<?php esc_attr_e( 'Primary navigation', 'ccs-wp-theme' ); ?>">
				<?php
				if ( has_nav_menu( $ccs_nav_location ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => $ccs_nav_location,
							'menu_id'         => 'mobile-menu-list',
							'menu_class'      => 'mobile-menu-list',
							'container'       => false,
							'fallback_cb'     => false,
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'link_class'      => 'mobile-menu-link',
						)
					);
				} elseif ( has_nav_menu( 'primary' ) ) {
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
			<?php
			/*
			 * No phone here: the header bar already shows a persistent Call pill at
			 * this width, so repeating it inside the panel just crowded the actions.
			 */
			?>
			<div class="mobile-menu__actions">
				<a href="<?php echo esc_url( $ccs_cta_url ); ?>" class="mobile-menu__cta btn btn-primary"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( $ccs_switch_url ); ?>" class="mobile-menu__switch btn btn-accent"><?php esc_html_e( 'Switch to us', 'ccs-wp-theme' ); ?></a>
			</div>
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
