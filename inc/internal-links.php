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
 * setting. Every other service is offered in every area (client, 23 Nov
 * 2026: "all but domiciliary"). Before that, the other seventeen were listed
 * only for the areas that get everything, so pages such as nursing care and
 * brain injury care named Maidstone and Aylesford alone, which was wrong.
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
		if ( ! $only || ! in_array( $slug, $named, true ) || in_array( $slug, $only, true ) ) {
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

	/*
	 * Shorter names people actually write (23 Nov 2026, after a second crawl:
	 * twelve service pages had three links each, and answers named them
	 * without the word "care"). Only phrases that mean the service or the
	 * page; "dementia" alone is left out, because it is as often a diagnosis
	 * in a sentence of advice as a kind of care.
	 */
	$service = static function ( $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'service' );
		return $post instanceof WP_Post && 'publish' === $post->post_status ? get_permalink( $post ) : '';
	};
	$aliases = array(
		'overnight-care'     => array( 'waking night', 'sleep-in night', 'sleeping night' ),
		'continuous-care'    => array( '24-hour care', 'round-the-clock care' ),
		'palliative-care'    => array( 'end of life care', 'end-of-life care' ),
		'parkinsons-care'    => array( "parkinson's" ),
		'spinal-injury-care' => array( 'spinal cord injury', 'spinal injuries', 'spinal injury' ),
		'brain-injury-care'  => array( 'acquired brain injury', 'brain injuries', 'brain injury' ),
		'cerebral-palsy-care' => array( 'cerebral palsy' ),
	);
	foreach ( $aliases as $slug => $phrases ) {
		$url = $service( $slug );
		foreach ( $url ? $phrases : array() as $phrase ) {
			$targets[ $phrase ] = $url;
		}
	}
	$targets['continuing healthcare'] = $page( 'costs-and-funding' );
	$targets['what care costs']       = $page( 'costs-and-funding' );
	$targets['care certificate']      = $page( 'professional-development' );
	$targets['case managers']         = $page( 'referral-information' );
	$targets['case manager']          = $page( 'referral-information' );
	$targets['care guides']           = $page( 'care-guides' );
	$targets['join our team']         = $page( 'careers' );

	/*
	 * Other organisations' pages (24 Nov 2026, client: "identify external and
	 * internal linking opportunities"). A benefit, a scheme or an assessment
	 * named in passing links to the official page that explains it. Each
	 * address was opened and read before it was added here; a phrase is left
	 * out where no official page could be confirmed (Kent County Council's
	 * adult social care landing page and cqc.org.uk both refused to load).
	 * Phrases that already point to a page of this site (Continuing
	 * Healthcare, end of life care, the Care Certificate) stay as they are.
	 */
	$targets['personal independence payment'] = 'https://www.gov.uk/pip';
	$targets['attendance allowance']          = 'https://www.gov.uk/attendance-allowance';
	$targets["carer's allowance"]             = 'https://www.gov.uk/carers-allowance';
	$targets['direct payments']               = 'https://www.gov.uk/apply-direct-payments';
	$targets['direct payment']                = 'https://www.gov.uk/apply-direct-payments';
	$targets['personal health budget']        = 'https://www.nhs.uk/nhs-services/help-with-health-costs/what-is-a-personal-health-budget/';
	$targets["carer's assessment"]            = 'https://www.nhs.uk/social-care-and-support/support-and-benefits-for-carers/carer-assessments/';
	$targets['mental capacity act']           = 'https://www.nhs.uk/social-care-and-support/making-decisions-for-someone-else/mental-capacity-act/';
	$targets['disability confident']          = 'https://www.gov.uk/government/collections/disability-confident-campaign';
	$targets['dbs check']                     = 'https://www.gov.uk/government/organisations/disclosure-and-barring-service';
	// Kent County Council's own page on asking for one (read 23 Nov 2026).
	$targets['needs assessment']              = 'https://www.kent.gov.uk/social-care-and-health/adult-social-care/care-and-support/how-to-get-adult-social-care-support/care-needs-assessments';

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
 * @param string $html  HTML.
 * @param int    $max   Most links to add.
 * @param string $group Optional. Blocks sharing a group name link each page
 *                      once between them on a page view: a list of forty
 *                      answers links "care plan" in the first that says it,
 *                      not in all of them.
 * @return string
 */
function ccs_link_mentions( $html, $max = 4, $group = '' ) {
	static $seen = array();
	$html = (string) $html;
	if ( '' === trim( $html ) || is_admin() || is_feed() ) {
		return $html;
	}
	// The page being viewed, not whichever post a loop last set up.
	$here    = untrailingslashit( (string) ( is_singular() && get_queried_object_id() ? get_permalink( get_queried_object_id() ) : get_permalink() ) );
	$targets = ccs_link_targets();
	if ( '' !== $group && ! isset( $seen[ $group ] ) ) {
		$seen[ $group ] = array();
	}

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
			if ( $added >= $max || $bare === $here || in_array( $bare, $linked, true ) || isset( $used[ $bare ] ) || ( '' !== $group && isset( $seen[ $group ][ $bare ] ) ) ) {
				continue;
			}
			// Curly and straight apostrophes both match; whole words only.
			$pattern = '/(?<![\p{L}\p{N}>])(' . str_replace( "'", "['’]", preg_quote( $phrase, '/' ) ) . ')(?![\p{L}\p{N}])/iu';
			$next    = preg_replace( $pattern, '<a href="' . esc_url( $url ) . '">$1</a>', $parts[ $i ], 1, $count );
			if ( $count ) {
				// The text is now text and a link: stop here so a second phrase cannot land inside the new link.
				$parts[ $i ]   = $next;
				$used[ $bare ] = true;
				if ( '' !== $group ) {
					$seen[ $group ][ $bare ] = true;
				}
				++$added;
				break;
			}
		}
	}
	$html = implode( '', $parts );
	// One link is added per run of text at a time (so no phrase lands inside a new link); go round again for the rest, e.g. a sentence naming five towns.
	if ( $added && $added < $max ) {
		return ccs_link_mentions( $html, $max - $added, $group );
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
	// Up to six: a sentence naming five towns was left with two of them unlinked.
	return ccs_link_mentions( $content, 6 );
}
add_filter( 'the_content', 'ccs_link_mentions_in_content', 25 );
