<?php
/**
 * Internal links (Nov 2026, after a crawl of every page).
 *
 * Two gaps were found. No service page linked to any town it is provided in,
 * and pages named other pages in plain text ("NHS Continuing Healthcare",
 * "the CQC", "care plan", another service's name) without linking to them.
 *
 * 1. ccs_service_areas(): the towns a service is provided in, for the line
 *    of town links on each service page. It reads the same location_services
 *    setting the area pages use, so no page claims a service a town does not
 *    get.
 * 2. ccs_link_mentions(): links the first plain mention of a page to that
 *    page. It runs over editor content (the_content) and is called on a few
 *    blocks of theme copy. It never links inside a heading or an existing
 *    link, never links a page to itself, never adds a second link to a page
 *    the text already links to, and adds at most four links to one block.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Area posts where a service is provided.
 *
 * Domiciliary, respite and complex care follow each area's location_services
 * setting. The area pages only name those three, so every other service is
 * listed for the areas that get everything (an empty setting) and nowhere
 * else.
 *
 * @param string $slug Service slug.
 * @return WP_Post[]
 */
function ccs_service_areas( $slug ) {
	$areas = get_posts(
		array(
			'post_type'      => 'location',
			'post_status'    => 'publish',
			'posts_per_page' => 40,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	$named = array( 'domiciliary-care', 'respite-care', 'complex-care' );
	$out   = array();
	foreach ( $areas as $area ) {
		$only = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $area->ID, 'location_services', true ) ) ) );
		if ( ! $only || ( in_array( $slug, $named, true ) && in_array( $slug, $only, true ) ) ) {
			$out[] = $area;
		}
	}
	return $out;
}

/**
 * Phrase => URL, longest phrase first.
 *
 * @return array<string, string>
 */
function ccs_link_targets() {
	static $targets = null;
	if ( null !== $targets ) {
		return $targets;
	}
	$targets = array();
	$page    = static function ( $slug ) {
		return function_exists( 'ccs_page_url' ) ? ccs_page_url( $slug ) : home_url( '/' . $slug . '/' );
	};

	foreach ( get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'posts_per_page' => 60 ) ) as $service ) {
		$targets[ strtolower( str_replace( '’', "'", get_the_title( $service ) ) ) ] = get_permalink( $service );
	}
	// Towns named in running text. Maidstone (the office address, on every page) and Medway (also a river and a council) are left out.
	foreach ( get_posts( array( 'post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => 40 ) ) as $area ) {
		if ( ! in_array( $area->post_name, array( 'maidstone', 'medway' ), true ) ) {
			$targets[ str_replace( '-', ' ', $area->post_name ) ] = get_permalink( $area );
		}
	}
	$targets['nhs continuing healthcare'] = $page( 'costs-and-funding' );
	$targets['care quality commission']   = $page( 'cqc-and-our-care' );
	$targets['cqc']                       = $page( 'cqc-and-our-care' );
	$targets['care plan']                 = $page( 'getting-started' );
	$targets['registered manager']        = $page( 'who-youll-meet' );
	$targets['office team']               = $page( 'who-youll-meet' );

	uksort(
		$targets,
		static function ( $a, $b ) {
			return strlen( $b ) <=> strlen( $a );
		}
	);
	return $targets;
}

/**
 * Link the first plain mention of each known page in a block of HTML.
 *
 * @param string $html HTML.
 * @param int    $max  Most links to add.
 * @return string
 */
function ccs_link_mentions( $html, $max = 4 ) {
	$html = (string) $html;
	if ( '' === trim( $html ) || is_admin() || is_feed() ) {
		return $html;
	}
	$here    = untrailingslashit( (string) get_permalink() );
	$targets = ccs_link_targets();

	// Pages this block already links to are left alone.
	$linked = array();
	if ( preg_match_all( '#<a\s[^>]*href="([^"\#?]+)#i', $html, $found ) ) {
		$linked = array_map( 'untrailingslashit', $found[1] );
	}

	// Split into tags and text; skip text inside links, headings, buttons, labels and form controls.
	$parts = preg_split( '#(<[^>]+>)#', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$skip  = 0;
	$added = 0;
	$used  = array();
	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			if ( preg_match( '#^<(a|h[1-6]|button|label|select|option|summary|figcaption|script|style)\b#i', $part ) && '/>' !== substr( $part, -2 ) ) {
				++$skip;
			} elseif ( preg_match( '#^</(a|h[1-6]|button|label|select|option|summary|figcaption|script|style)>#i', $part ) ) {
				$skip = max( 0, $skip - 1 );
			}
			continue;
		}
		if ( $skip || $added >= $max ) {
			continue;
		}
		foreach ( $targets as $phrase => $url ) {
			$bare = untrailingslashit( $url );
			if ( $added >= $max || $bare === $here || in_array( $bare, $linked, true ) || isset( $used[ $bare ] ) ) {
				continue;
			}
			// Curly and straight apostrophes both match; whole words only.
			$pattern = '/(?<![\p{L}\p{N}>])(' . str_replace( "'", "['’]", preg_quote( $phrase, '/' ) ) . ')(?![\p{L}\p{N}])/iu';
			$next    = preg_replace( $pattern, '<a href="' . esc_url( $url ) . '">$1</a>', $parts[ $i ], 1, $count );
			if ( $count ) {
				// The text is now text and a link: stop here so a second phrase cannot land inside the new link.
				$parts[ $i ]   = $next;
				$used[ $bare ] = true;
				++$added;
				break;
			}
		}
	}
	$html = implode( '', $parts );
	// One link is added per run of text at a time (so no phrase lands inside a new link); go round again for the rest, e.g. a sentence naming five towns.
	if ( $added && $added < $max ) {
		return ccs_link_mentions( $html, $max - $added );
	}
	return $html;
}

/**
 * Editor content on pages, posts and services.
 *
 * @param string $content Content.
 * @return string
 */
function ccs_link_mentions_in_content( $content ) {
	if ( ! is_singular() || ! in_the_loop() ) {
		return $content;
	}
	return ccs_link_mentions( $content );
}
add_filter( 'the_content', 'ccs_link_mentions_in_content', 25 );
