<?php
/**
 * Redirects from the old website's addresses (Nov 2026).
 *
 * The site this theme replaces (continuitycareservices.co.uk, Nov 2026) kept
 * most of its pages under /home/ and had four articles at the top level.
 * Search engines, other websites and people's bookmarks hold those
 * addresses, so each one is sent, permanently (301), to the page that now
 * does its job. Without this they would all show "page not found" on the
 * day the new site goes live.
 *
 * The list is the old site's own sitemaps, read on 24 Nov 2026: sixteen
 * pages and four articles. A redirect never fires for an address where a
 * real page lives, so it cannot take over a page that exists.
 *
 * - An address in the list goes where the list says.
 * - Any other address under /home/ goes to the same address without /home/
 *   when a page lives there, and to the homepage when one does not.
 *
 * The four old articles are not carried over. Each goes to the page nearest
 * its subject; change a target here if an article is brought across later.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Old path => new path, both without the domain and with slashes at each end.
 *
 * @return array<string, string>
 */
function ccs_old_site_redirects() {
	$map = array(
		// Pages that kept their name and lost /home/.
		'/home/news-and-updates/'               => '/news-and-updates/',
		'/home/home-care-services-kent/'        => '/home-care-services-kent/',
		'/home/who-youll-meet/'                 => '/who-youll-meet/',
		'/home/about-home-care-maidstone/'      => '/about-home-care-maidstone/',
		'/home/contact-us/'                     => '/contact-us/',
		'/home/accessibility-statement/'        => '/accessibility-statement/',
		'/home/privacy-policy/'                 => '/privacy-policy/',
		'/home/resources/'                      => '/resources/',
		'/home/resources/faqs/'                 => '/resources/faqs/',
		'/home/resources/care-guides/'          => '/resources/care-guides/',
		'/home/resources/referral-information/' => '/resources/referral-information/',
		// Pages whose name changed.
		'/home/cookie-policy/'                  => '/cookies/',
		'/home/care-careers-maidstone-kent/'    => '/careers/',
		// Pages with no successor.
		'/home/under-construction/'             => '/',
		'/sitemap/'                             => '/',
		// The old site's four articles, each to the page nearest its subject.
		'/proud-to-be-a-nurse-maidstone/'       => '/careers/',                      // a nurse on why she nurses; the old page pointed readers to careers.
		'/the-right-person-makes-the-difference/' => '/services/companionship-care/', // on company and loneliness.
		'/end-of-life-care-experience/'         => '/services/palliative-care/',     // a carer's first experience of end of life care.
		'/stay-safe-online-shopping-tips/'      => '/news-and-updates/',             // seasonal advice, no page on the subject now.
		// This site's own earlier address for Working with us.
		'/careers/working-for-us/'              => '/careers/working-with-us/',
	);

	/**
	 * Filters the old-address redirects.
	 *
	 * @param array<string, string> $map Old path => new path.
	 */
	return (array) apply_filters( 'ccs_old_site_redirects', $map );
}

/**
 * Where an address WordPress could not find should go, or '' for nowhere.
 *
 * @param string $path The path asked for, e.g. "/home/contact-us/".
 * @return string New path, or ''.
 */
function ccs_old_site_redirect_target( $path ) {
	$path = '/' . trim( strtolower( (string) $path ), '/' ) . '/';
	$path = (string) preg_replace( '#/+#', '/', $path );
	$map  = ccs_old_site_redirects();
	if ( isset( $map[ $path ] ) ) {
		return $map[ $path ];
	}
	if ( 0 === strpos( $path, '/home/' ) ) {
		$rest = substr( $path, 5 ); // "/contact-us/".
		if ( '/' !== $rest && url_to_postid( home_url( $rest ) ) ) {
			return $rest;
		}
		return '/';
	}
	return '';
}

/**
 * Send an old address to its new page.
 */
function ccs_old_site_redirect() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	// A site in a sub-folder: compare the part after the folder.
	$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( '' !== $base && '/' !== $base && 0 === strpos( $request, $base ) ) {
		$request = '/' . ltrim( substr( $request, strlen( $base ) ), '/' );
	}
	$target = ccs_old_site_redirect_target( $request );
	if ( '' === $target ) {
		return;
	}
	/*
	 * Not only on "page not found": WordPress reads some old addresses
	 * (/home/resources/faqs/) as the front page with extra words after it and
	 * sends them to the homepage itself, so waiting for a 404 lost them. The
	 * one thing that stops a redirect is a real page living at the address.
	 */
	if ( ! is_404() && is_singular() ) {
		$here = (string) wp_parse_url( (string) get_permalink( get_queried_object_id() ), PHP_URL_PATH );
		if ( trailingslashit( strtolower( $here ) ) === trailingslashit( strtolower( $request ) ) ) {
			return;
		}
	}
	wp_safe_redirect( home_url( $target ), 301 );
	exit;
}
// Before WordPress's own guess at a moved page (priority 10), which can pick a different one.
add_action( 'template_redirect', 'ccs_old_site_redirect', 1 );
