<?php
/**
 * The old website's addresses (Nov 2026), for the CCS SEO plugin's redirects.
 *
 * The plugin does the redirecting (ccs-seo/includes/redirects.php); this file
 * only says where each old address goes, through its ccs_seo_redirects and
 * ccs_seo_redirect_target filters (inc/seo-bridge.php).
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
 *   when a page lives there, and answers "gone" (410) when one does not.
 * - 'gone' means the page has no successor: the CCS SEO plugin answers 410.
 *
 * The plugin copies this list into SEO > Redirects once, where it can be
 * seen and edited ("Added by: Old website").
 *
 * Three of the old site's four articles are carried over at their old
 * addresses, so they need no redirect. The fourth goes to News.
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
		// Pages with no successor: "gone" (410), not the homepage. Google treats
		// a redirect to an unrelated page as a soft 404 (Search Central, Nov 2026).
		'/home/under-construction/'             => 'gone',
		'/sitemap/'                             => 'gone',
		// One old article is not carried over (seasonal advice from 2024). The other three live at their old addresses (class-theme-activation.php).
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
 * Where an old /home/ address should go when the list doesn't name it, or ''.
 *
 * @param string $path The path asked for, e.g. "/home/contact-us/".
 * @return string New path, 'gone', or ''.
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
		return 'gone';
	}
	return '';
}
