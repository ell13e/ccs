<?php
/**
 * Header and footer helpers: contact info, footer fallback menus.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add optional link class to menu anchors (e.g. nav-link, mobile-menu-link).
 * Pass 'link_class' in wp_nav_menu args.
 */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) {
	if ( ! empty( $args->link_class ) ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' ' . esc_attr( $args->link_class ) : esc_attr( $args->link_class );
	}
	return $atts;
}, 10, 4 );

/**
 * Get contact info from theme mods for header/footer.
 *
 * @return array{phone: string, phone_link: string}
 */
function ccs_get_contact_info() {
	$phone = get_theme_mod( 'ccs_phone', '01234 567890' );
	$raw   = preg_replace( '/\s+/', '', $phone );
	$link  = $raw !== '' ? 'tel:' . $raw : '';

	return array(
		'phone'      => $phone,
		'phone_link' => $link,
	);
}

/**
 * Get URL for a page by slug (for footer fallback links).
 *
 * @param string $slug Page slug or path.
 * @return string URL or home_url('/slug/').
 */
function ccs_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Primary (header) menu structure per content guide §7.
 * Resources = dropdown: Care Guides, FAQs, Referral Information.
 *
 * @return array<int, array{title: string, url: string, children?: array<int, array{title: string, url: string}>}>
 */
function ccs_primary_menu_fallback_items() {
	return array(
		array( 'title' => __( 'Home', 'ccs-wp-theme' ), 'url' => home_url( '/' ) ),
		array( 'title' => __( 'About Us', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'about-home-care-maidstone' ) ),
		array( 'title' => __( 'Our Services', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'home-care-services-kent' ) ),
		array( 'title' => __( "Who You'll Meet", 'ccs-wp-theme' ), 'url' => ccs_page_url( 'who-youll-meet' ) ),
		array( 'title' => __( 'Careers', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'care-careers-maidstone-kent' ) ),
		array(
			'title'    => _x( 'Resources', 'Primary nav – Home care guides parent', 'ccs-wp-theme' ),
			'url'      => ccs_page_url( 'resources' ),
			'children' => array(
				array( 'title' => __( 'Care Guides', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'care-guides' ) ),
				array( 'title' => __( 'FAQs', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'faqs' ) ),
				array( 'title' => __( 'Referral Information', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'referral-information' ) ),
			),
		),
		array( 'title' => __( 'News & Updates', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'news-and-updates' ) ),
		array( 'title' => __( 'Contact Us', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'contact-us' ) ),
	);
}

/**
 * Fallback markup for primary navigation when no menu assigned.
 * Outputs desktop nav-list structure with Resources dropdown; use same list for mobile.
 */
function ccs_primary_menu_fallback() {
	$items = ccs_primary_menu_fallback_items();
	$link_class = 'nav-link';
	echo '<ul id="primary-menu" class="nav-list" role="menubar">';
	foreach ( $items as $item ) {
		if ( ! empty( $item['children'] ) ) {
			echo '<li class="menu-item menu-item-has-children">';
			echo '<a href="' . esc_url( $item['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $item['title'] ) . '</a>';
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				echo '<li class="menu-item"><a href="' . esc_url( $child['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $child['title'] ) . '</a></li>';
			}
			echo '</ul></li>';
		} else {
			echo '<li class="menu-item"><a href="' . esc_url( $item['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $item['title'] ) . '</a></li>';
		}
	}
	echo '</ul>';
}

/**
 * Fallback for mobile primary menu (same items, mobile-menu-list class).
 */
function ccs_primary_menu_fallback_mobile() {
	$items = ccs_primary_menu_fallback_items();
	$link_class = 'mobile-menu-link';
	echo '<ul id="mobile-menu-list" class="mobile-menu-list">';
	foreach ( $items as $item ) {
		if ( ! empty( $item['children'] ) ) {
			echo '<li class="menu-item menu-item-has-children">';
			echo '<a href="' . esc_url( $item['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $item['title'] ) . '</a>';
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				echo '<li class="menu-item"><a href="' . esc_url( $child['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $child['title'] ) . '</a></li>';
			}
			echo '</ul></li>';
		} else {
			echo '<li class="menu-item"><a href="' . esc_url( $item['url'] ) . '" class="' . esc_attr( $link_class ) . '">' . esc_html( $item['title'] ) . '</a></li>';
		}
	}
	echo '</ul>';
}

/**
 * Fallback menu for footer Company column when no menu assigned.
 * Per content guide §7: About Us, Services, Careers, Contact Us.
 */
function ccs_footer_company_fallback_menu() {
	$items = array(
		array( 'title' => __( 'About Us', 'ccs-wp-theme' ), 'slug' => 'about-home-care-maidstone' ),
		array( 'title' => __( 'Our Services', 'ccs-wp-theme' ), 'slug' => 'home-care-services-kent' ),
		array( 'title' => __( 'Careers', 'ccs-wp-theme' ), 'slug' => 'care-careers-maidstone-kent' ),
		array( 'title' => __( 'Contact Us', 'ccs-wp-theme' ), 'slug' => 'contact-us' ),
	);

	echo '<ul class="footer-modern-links">';
	foreach ( $items as $item ) {
		$url = ccs_page_url( $item['slug'] );
		echo '<li><a href="' . esc_url( $url ) . '" class="footer-modern-link">' . esc_html( $item['title'] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Fallback menu for footer Help column when no menu assigned.
 * Per content guide §7: FAQs, Privacy Policy, Terms & Conditions, Accessibility Statement.
 */
function ccs_footer_help_fallback_menu() {
	$items = array(
		array( 'title' => __( 'FAQs', 'ccs-wp-theme' ), 'slug' => 'faqs' ),
		array( 'title' => __( 'Privacy Policy', 'ccs-wp-theme' ), 'slug' => 'privacy-policy' ),
		array( 'title' => __( 'Terms & Conditions', 'ccs-wp-theme' ), 'slug' => 'terms-and-conditions' ),
		array( 'title' => __( 'Accessibility Statement', 'ccs-wp-theme' ), 'slug' => 'accessibility-statement', 'fallback' => home_url( '/accessibility/' ) ),
	);

	echo '<ul class="footer-modern-links">';
	foreach ( $items as $item ) {
		$url = ccs_page_url( $item['slug'] );
		if ( ! empty( $item['fallback'] ) && $url === home_url( '/' . $item['slug'] . '/' ) ) {
			$url = $item['fallback'];
		}
		echo '<li><a href="' . esc_url( $url ) . '" class="footer-modern-link">' . esc_html( $item['title'] ) . '</a></li>';
	}
	echo '</ul>';
}
