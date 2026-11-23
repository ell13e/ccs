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
	$phone = get_theme_mod( 'ccs_phone', '01622 809 881' );
	$raw   = preg_replace( '/\s+/', '', $phone );
	$link  = $raw !== '' ? 'tel:' . $raw : '';

	return array(
		'phone'      => $phone,
		'phone_link' => $link,
		'email'      => ccs_contact_email(),
	);
}

/**
 * The office email address shown to visitors.
 *
 * The Customizer's contact email, unless it is empty or a placeholder: the
 * setting used to default to the WordPress admin address, which on a fresh
 * or local install is something like admin@localhost.com and was showing on
 * the site (client, Nov 2026). Falls back to the office address.
 *
 * @return string
 */
function ccs_contact_email() {
	$office = 'office@continuitycareservices.co.uk';
	$email  = trim( (string) get_theme_mod( 'ccs_contact_email', '' ) );
	if ( '' === $email || ! is_email( $email ) || preg_match( '/@(localhost|example\.|wordpress\.)|^(admin|wordpress)@/i', $email ) ) {
		return $office;
	}
	return $email;
}

/**
 * Whether the current request is inside the Careers section.
 *
 * The careers side is deliberately segregated from the care side: it has its own
 * nav, its own CTA, and must not show care CTAs like "Switch to us" or
 * "Book a free consultation" — those are aimed at families, not job seekers.
 *
 * @return bool
 */
function ccs_is_careers_context() {
	if ( ! is_page() ) {
		return false;
	}

	$page_id = get_queried_object_id();
	if ( ! $page_id ) {
		return false;
	}

	$careers_ids = (array) get_option( 'ccs_careers_page_ids', array() );
	if ( in_array( $page_id, array_map( 'intval', array_values( $careers_ids ) ), true ) ) {
		return true;
	}

	foreach ( get_post_ancestors( $page_id ) as $ancestor_id ) {
		$ancestor = get_post( $ancestor_id );
		if ( $ancestor && 'careers' === $ancestor->post_name ) {
			return true;
		}
	}

	return false;
}

/**
 * Get URL for a page by slug (for footer fallback links).
 *
 * @param string $slug Page slug or path.
 * @return string URL or home_url('/slug/').
 */
function ccs_page_url( $slug ) {
	/*
	 * get_page_by_path() expects a full hierarchical path, so it only ever
	 * matched top-level pages. Every child page fell through to the
	 * home_url( '/' . $slug . '/' ) guess below, which drops the parent segment
	 * ("/contact-us/" instead of "/home/contact-us/"). Those URLs still resolved,
	 * but only via WordPress's canonical redirect — so every internal CTA on the
	 * site took a needless 301 hop. Resolve by slug across all pages instead.
	 */
	$page = get_page_by_path( $slug );

	/*
	 * get_page_by_path() ignores post status, so it happily returned drafts,
	 * and a draft's permalink (/?page_id=N) is a 404 for every visitor. That is
	 * how the footer's Privacy Policy link pointed at WordPress's unpublished
	 * placeholder. Only a published page is a valid link target; anything else
	 * falls through to the published-only lookup below.
	 */
	if ( $page instanceof WP_Post && 'publish' !== $page->post_status ) {
		$page = null;
	}

	if ( ! $page instanceof WP_Post ) {
		$found = get_posts(
			array(
				'post_type'        => 'page',
				'name'             => $slug,
				'post_status'      => 'publish',
				'numberposts'      => 1,
				'suppress_filters' => false,
			)
		);
		if ( ! empty( $found ) ) {
			$page = $found[0];
		}
	}

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
		array( 'title' => __( 'Careers', 'ccs-wp-theme' ), 'url' => ccs_page_url( 'careers' ) ),
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
		array( 'title' => __( 'About us', 'ccs-wp-theme' ), 'slug' => 'about-home-care-maidstone' ),
		array( 'title' => __( 'Our services', 'ccs-wp-theme' ), 'slug' => 'home-care-services-kent' ),
		array( 'title' => __( 'Careers', 'ccs-wp-theme' ), 'slug' => 'careers' ),
		array( 'title' => __( 'Contact us', 'ccs-wp-theme' ), 'slug' => 'contact-us' ),
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
		array( 'title' => __( 'Costs and funding', 'ccs-wp-theme' ), 'slug' => 'costs-and-funding' ),
		array( 'title' => __( 'FAQs', 'ccs-wp-theme' ), 'slug' => 'faqs' ),
		array( 'title' => __( 'Privacy policy', 'ccs-wp-theme' ), 'slug' => 'privacy-policy' ),
		array( 'title' => __( 'Terms and conditions', 'ccs-wp-theme' ), 'slug' => 'terms-and-conditions' ),
		array( 'title' => __( 'Accessibility statement', 'ccs-wp-theme' ), 'slug' => 'accessibility-statement' ),
	);

	echo '<ul class="footer-modern-links">';
	foreach ( $items as $item ) {
		// Previously swapped in a /accessibility/ fallback whenever ccs_page_url()
		// returned a top-level permalink — which, now that helper resolves
		// correctly, meant it rewrote the right URL into a redirecting one.
		$url = ccs_page_url( $item['slug'] );
		echo '<li><a href="' . esc_url( $url ) . '" class="footer-modern-link">' . esc_html( $item['title'] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Wrap one word (or short phrase) of a section heading in the accent mark
 * (.ccs-accent in components.css).
 *
 * Returns markup, already escaped, so it can go straight to printf() as a %s
 * placeholder inside an esc_html__() heading:
 *
 *     printf( esc_html__( 'How care %s', 'ccs-wp-theme' ), ccs_accent( __( 'starts', 'ccs-wp-theme' ) ) );
 *
 * Keeping the placeholder inside the translatable string lets a translator
 * move the emphasis to wherever the word falls in their language.
 *
 * @param string $word The word or phrase to emphasise (unescaped).
 * @return string Escaped <span> markup.
 */
function ccs_accent( $word ) {
	return '<span class="ccs-accent">' . esc_html( $word ) . '</span>';
}

/**
 * Menu links to the old Care guides page go straight to the guides on the
 * Resources hub (Nov 2026: the guides are read there; the page redirects).
 * Saves every visitor a redirect and keeps the link check clean. The menu
 * item itself is untouched, so the client can still rename or remove it.
 *
 * @param array $items Menu item objects.
 * @return array
 */
function ccs_menu_care_guides_to_hub( $items ) {
	static $from = null;
	static $to   = null;
	if ( null === $from ) {
		$from  = '';
		$guide = get_page_by_path( 'resources/care-guides' );
		$hub   = get_page_by_path( 'resources' );
		if ( $guide instanceof WP_Post && $hub instanceof WP_Post && 'publish' === $hub->post_status ) {
			$from = untrailingslashit( get_permalink( $guide ) );
			$to   = add_query_arg( 'show', 'care-guides', get_permalink( $hub ) );
		}
	}
	if ( '' === $from ) {
		return $items;
	}
	foreach ( $items as $item ) {
		if ( isset( $item->url ) && untrailingslashit( $item->url ) === $from ) {
			$item->url = $to;
		}
	}
	return $items;
}
// Not hooked since the guides got their own page back (Nov 2026, client:
// Resources is a signpost page, not a hub).

/**
 * Link to the office in Google Maps: a pin on the address, with Google's
 * own Directions button. No start point is set, so Google asks each visitor
 * for theirs.
 *
 * The address alone, on purpose. Naming the business as well opens the
 * company's Google profile, which was tried on 23 Nov 2026 and put back the
 * same day at the client's request: the profile shows the Google rating to
 * everyone who follows the link.
 *
 * @param string $address_line The office address on one line.
 * @return string URL, or '' without an address.
 */
function ccs_directions_url( $address_line ) {
	$address_line = trim( (string) $address_line );
	if ( '' === $address_line ) {
		return '';
	}
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address_line );
}

/**
 * The site icon (the favicon in the browser tab, and the icon when the site
 * is saved to a phone's home screen), Nov 2026.
 *
 * The theme had none, so a new site showed WordPress's own "W" in the tab.
 * Until an icon is chosen in Appearance > Customise > Site Identity, the
 * infinity mark from the logo is used: on a clear ground for browser tabs,
 * on white for an iPhone's home screen, which would otherwise put it on
 * black. An icon chosen in the Customiser replaces all of these.
 *
 * The files are assets/images/brand/site-icon-{32,180,192,270,512}.png, made
 * from ccs-infinity-mark.png (resized and centred on a square, nothing else).
 *
 * @param string $url  Site icon URL, empty when none is set.
 * @param int    $size Size asked for, in pixels.
 * @return string
 */
function ccs_default_site_icon( $url, $size ) {
	if ( '' !== (string) $url && false === strpos( (string) $url, '/wp-includes/images/w-logo' ) ) {
		return $url;
	}
	if ( get_option( 'site_icon' ) ) {
		return $url;
	}
	$sizes = array( 32, 180, 192, 270, 512 );
	$pick  = 512;
	foreach ( $sizes as $candidate ) {
		if ( $candidate >= (int) $size ) {
			$pick = $candidate;
			break;
		}
	}
	$file = '/assets/images/brand/site-icon-' . $pick . '.png';
	return file_exists( get_template_directory() . $file ) ? get_template_directory_uri() . $file : $url;
}
add_filter( 'get_site_icon_url', 'ccs_default_site_icon', 10, 2 );
