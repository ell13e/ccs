<?php
/**
 * Which kinds of care each area gets (Nov 2026).
 *
 * The client confirmed that domiciliary care is not offered in Herne Bay,
 * Snodland, Tunbridge Wells, Ashford, Medway, Thanet, West Malling, Paddock
 * Wood, Tonbridge or Whitstable: those areas get respite and complex care.
 * (Medway was first described as complex care only; the client corrected
 * that the same day.) Domiciliary care is offered in Maidstone and Aylesford.
 *
 * 23 Nov 2026: the client confirmed that every other service is offered in
 * every area. The setting below still records only these three, which is
 * what decides domiciliary care; the wording and the hub's group headings
 * say "all our services except domiciliary care".
 *
 * An area page stores what it offers in the location_services meta, a
 * comma-separated list of service slugs; empty means all three. The page's
 * service cards, the areas hub and the page's own wording all
 * read it, so no page claims a service the area does not get.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service slugs offered in an area, in the site's order.
 *
 * @param int $post_id Location post ID.
 * @return string[]
 */
function ccs_location_services( $post_id ) {
	$all  = array( 'domiciliary-care', 'respite-care', 'complex-care' );
	$only = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $post_id, 'location_services', true ) ) ) );
	return $only ? array_values( array_intersect( $all, $only ) ) : $all;
}

/**
 * A short line naming the care offered in an area, for the area page and
 * the areas hub: "All our services except domiciliary care", "Complex care
 * only", or everything where the area gets domiciliary care too.
 *
 * @param int $post_id Location post ID.
 * @return string
 */
function ccs_location_services_label( $post_id ) {
	$offered = ccs_location_services( $post_id );
	if ( 3 === count( $offered ) ) {
		return __( 'All our services, including domiciliary care', 'ccs-wp-theme' );
	}
	if ( array( 'complex-care' ) === $offered ) {
		return __( 'Complex care only', 'ccs-wp-theme' );
	}
	if ( array( 'respite-care', 'complex-care' ) === $offered ) {
		// Every service but domiciliary care is offered in every area (client, 23 Nov 2026).
		return __( 'All our services except domiciliary care', 'ccs-wp-theme' );
	}
	$names = array(
		'domiciliary-care' => __( 'domiciliary care', 'ccs-wp-theme' ),
		'respite-care'     => __( 'respite care', 'ccs-wp-theme' ),
		'complex-care'     => __( 'complex care', 'ccs-wp-theme' ),
	);
	$list = array_map(
		static function ( $slug ) use ( $names ) {
			return $names[ $slug ];
		},
		$offered
	);
	return ucfirst( implode( _x( ' and ', 'list separator', 'ccs-wp-theme' ), $list ) );
}

/**
 * New wording for the areas without domiciliary care: the page heading,
 * the excerpt (the hero's intro) and the body. The old wording promised
 * "a short daily visit" or "everyday help at home" in every one of them.
 *
 * @return array<string, array<string, string>>
 */
function ccs_area_copy_2026_11_base() {
	$respite = home_url( '/services/respite-care/' );
	$complex = home_url( '/services/complex-care/' );
	$refer   = home_url( '/resources/referral-information/' );
	$r       = static function ( $text ) use ( $respite ) {
		return '<a href="' . $respite . '">' . $text . '</a>';
	};
	$c       = static function ( $text ) use ( $complex ) {
		return '<a href="' . $complex . '">' . $text . '</a>';
	};

	return array(
		'west-malling'    => array(
			'heading' => 'Respite and complex care in West Malling',
			'excerpt' => 'Respite and complex care at home in West Malling and the villages around it, from a team based just down the road in Maidstone.',
			'content' => '<p>West Malling is a small town with a strong sense of itself, from the high street and the abbey to the villages that ring it. We provide care here the way the town works: unhurried, familiar, nothing rushed.</p>
<p>In West Malling, Kings Hill, Offham, Leybourne and Ditton we provide ' . $r( 'respite care' ) . ', when a family carer needs a break, and ' . $c( 'complex care' ) . ' at home. Carers are matched to you and kept consistent, so the person who knows your routine is the one who keeps coming.</p>',
		),
		'snodland'        => array(
			'heading' => 'Respite and complex care in Snodland',
			'excerpt' => 'Respite and complex care at home in Snodland and the villages along the Medway, from a small, consistent local team.',
			'content' => '<p>Snodland and the villages around it, including Halling, Wouldham, Birling and Ryarsh, sit just north of our Maidstone base, and it\'s an area we visit often. That means shorter travel time for your carer and more of it spent with you.</p>
<p>Here we provide ' . $r( 'respite care' ) . ', so a family carer can take a proper break, and ' . $c( 'complex care' ) . ' at home. We build each plan around your routine and keep the same small team coming back, so nobody\'s a stranger.</p>',
		),
		'tonbridge'       => array(
			'heading' => 'Respite and complex care in Tonbridge',
			'excerpt' => 'Respite and complex care at home in Tonbridge and the surrounding villages, with a consistent, matched team of carers.',
			'content' => '<p>Tonbridge is a market town with the river and the castle at its centre, and villages spreading out from it in every direction. We cover the town and those villages with the same promise: the carer who starts with you is the one who stays.</p>
<p>In Tonbridge, Hildenborough, Leigh, Hadlow and East Peckham we provide ' . $r( 'respite care' ) . ' for family carers and ' . $c( 'complex care' ) . ' at home, regulated by the CQC and planned around what you need.</p>',
		),
		'tunbridge-wells' => array(
			'heading' => 'Respite and complex care in Tunbridge Wells',
			'excerpt' => 'Respite and complex care at home in Royal Tunbridge Wells and the villages around it, from carers you\'ll get to know.',
			'content' => '<p>Royal Tunbridge Wells and the villages around it, including Southborough, Rusthall, Pembury and Speldhurst, are part of our regular patch. However spread out the address, you get the same small team, matched to you.</p>
<p>We provide ' . $r( 'respite care' ) . ', for a few hours, an overnight or longer while a family carer rests, and ' . $c( 'complex care' ) . ' at home. Every plan starts with a free conversation about what would help, and stays flexible as needs change.</p>',
		),
		'ashford'         => array(
			'heading' => 'Respite and complex care in Ashford',
			'excerpt' => 'Respite and complex care at home in Ashford and its neighbourhoods, from a small team matched to you and kept the same.',
			'content' => '<p>Continuity of Care Services provides care at home in Ashford, from Kennington and Willesborough to the newer neighbourhoods at Kingsnorth and Repton Park.</p>
<p>In Ashford we offer ' . $r( 'respite care' ) . ', a few days of cover while a family carer has a break, and ' . $c( 'complex care' ) . ', including care around the clock. It starts with a free consultation and continues with the same small team.</p>',
		),
		'herne-bay'       => array(
			'heading' => 'Respite and complex care in Herne Bay',
			'excerpt' => 'Respite and complex care at home in Herne Bay and along the north Kent coast, with the same carers at every visit.',
			'content' => '<p>We provide care at home in Herne Bay and the places around it on the north Kent coast, including Herne, Beltinge, Greenhill and Studd Hill.</p>
<p>Here we offer ' . $r( 'respite care' ) . ', so a family carer can have a break, and specialist ' . $c( 'complex care' ) . ' at home. You get to know a small team, because it is the same people who come each time.</p>',
		),
		'whitstable'      => array(
			'heading' => 'Respite and complex care in Whitstable',
			'excerpt' => 'Respite and complex care at home in Whitstable, Tankerton and Chestfield, from carers you get to know.',
			'content' => '<p>Continuity of Care Services provides care at home across Whitstable, from the town itself to Tankerton, Chestfield, Swalecliffe and Seasalter.</p>
<p>Here we offer ' . $r( 'respite care' ) . ' and ' . $c( 'complex care' ) . '. Each plan begins with a free conversation about what would help, and the same small team visits from then on.</p>',
		),
		'paddock-wood'    => array(
			'heading' => 'Respite and complex care in Paddock Wood',
			'excerpt' => 'Respite and complex care at home in Paddock Wood and the villages nearby, from a consistent, matched team.',
			'content' => '<p>We provide care at home in Paddock Wood and the villages close by, including Five Oak Green, Brenchley and Matfield.</p>
<p>From ' . $r( 'respite care' ) . ' while a family carer takes a break to ' . $c( 'complex care' ) . ' day and night, your care is built around how you like to live, and delivered by the same small team each time.</p>',
		),
		'thanet'          => array(
			'heading' => 'Respite and complex care in Margate, Ramsgate and Broadstairs',
			'excerpt' => 'Respite and complex care at home across Thanet, in Margate, Ramsgate, Broadstairs and the towns between them.',
			'content' => '<p>Continuity of Care Services provides care at home across Thanet: in Margate, Ramsgate and Broadstairs, and in Cliftonville, Westgate-on-Sea, Birchington and Minster.</p>
<p>We offer ' . $r( 'respite care' ) . ' for family carers and ' . $c( 'complex care' ) . '. Every plan starts with a free consultation, and you are matched with a small team who keep coming back.</p>',
		),
		'medway'          => array(
			'heading' => 'Respite and complex care in Medway',
			'excerpt' => 'Respite and complex care at home across Medway, in Chatham, Gillingham, Rochester, Strood and Rainham.',
			'content' => '<p>We provide care at home across Medway, in Chatham, Gillingham, Rochester, Strood and Rainham.</p>
<p>We offer ' . $r( 'respite care' ) . ', so a family carer can take a proper break, and ' . $c( 'complex care' ) . ' for people with conditions including epilepsy, spinal injuries, acquired brain injury and PEG feeding, working alongside their case manager, healthcare professionals and family. Case managers can see our <a href="' . $refer . '">referral information</a>.</p>',
		),
	);
}

/**
 * The area wording as first changed on 23 Nov 2026, kept only so the update
 * below can recognise a page that still holds it: "Care at home" in the
 * heading and the hero line, and a closing paragraph of links to five other
 * services.
 *
 * @return array<string, array<string, string>>
 */
function ccs_area_copy_2026_11c_was() {
	$towns = array(
		'west-malling'    => 'West Malling',
		'snodland'        => 'Snodland',
		'tonbridge'       => 'Tonbridge',
		'tunbridge-wells' => 'Tunbridge Wells',
		'ashford'         => 'Ashford',
		'herne-bay'       => 'Herne Bay',
		'whitstable'      => 'Whitstable',
		'paddock-wood'    => 'Paddock Wood',
		'thanet'          => 'Thanet',
		'medway'          => 'Medway',
	);
	$hub   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
	$copy  = ccs_area_copy_2026_11_base();
	$svc   = static function ( $slug, $text ) {
		return '<a href="' . home_url( '/services/' . $slug . '/' ) . '">' . $text . '</a>';
	};
	foreach ( $copy as $slug => $row ) {
		$town                     = isset( $towns[ $slug ] ) ? $towns[ $slug ] : '';
		$copy[ $slug ]['heading'] = preg_replace( '/^Respite and complex care in /', 'Care at home in ', $row['heading'] );
		$copy[ $slug ]['excerpt'] = preg_replace( '/^Respite and complex care at home /', 'Care at home ', $row['excerpt'] );
		$copy[ $slug ]['content'] = $row['content'] . "\n" . '<p>Alongside these we provide the rest of <a href="' . $hub . '">our services</a> in ' . $town . ', including ' . $svc( 'overnight-care', 'overnight care' ) . ', ' . $svc( 'palliative-care', 'palliative care' ) . ', ' . $svc( 'dementia-care', 'dementia care' ) . ', ' . $svc( 'stroke-care', 'stroke care' ) . ' and ' . $svc( 'parkinsons-care', 'Parkinson’s care' ) . '.</p>';
	}
	return $copy;
}

/**
 * The area wording as it stands (23 Nov 2026). The client confirmed that
 * every service except domiciliary care is offered in every area, so these
 * pages no longer present respite and complex care as all there is.
 * - The heading is "Care at home in [town]".
 * - The hero line no longer opens with the same words as the heading (a
 *   review found "Care at home in Whitstable" twice in a row); each is made
 *   from phrases the page already used, with nothing new claimed.
 * - The body closes with one sentence saying the rest of the services are
 *   offered here, with one link. A sentence of five links read as a list,
 *   and a block of seventeen link buttons under the cards was worse.
 *
 * - Three areas close with where carers go out (ccs_area_outings_2026_11()).
 *
 * @param bool $with_outings False gives the wording before the outings were added.
 * @return array<string, array<string, string>>
 */
function ccs_area_copy_2026_11( $with_outings = true ) {
	$lines = array(
		'west-malling'    => 'Carers for West Malling and the villages around it, from a team based just down the road in Maidstone.',
		'snodland'        => 'Carers for Snodland and the villages along the Medway, from a small, consistent local team.',
		'tonbridge'       => 'A consistent, matched team of carers for Tonbridge and the surrounding villages.',
		'tunbridge-wells' => 'Carers you’ll get to know, for Royal Tunbridge Wells and the villages around it.',
		'ashford'         => 'A small team matched to you and kept the same, for Ashford and its neighbourhoods.',
		'herne-bay'       => 'The same carers at every visit, in Herne Bay and along the north Kent coast.',
		'whitstable'      => 'Carers you get to know, in Whitstable, Tankerton and Chestfield.',
		'paddock-wood'    => 'A consistent, matched team of carers for Paddock Wood and the villages nearby.',
		'thanet'          => 'Carers across Thanet, in Margate, Ramsgate, Broadstairs and the towns between them.',
		'medway'          => 'Carers across Medway, in Chatham, Gillingham, Rochester, Strood and Rainham.',
	);
	$towns = array(
		'west-malling'    => 'West Malling',
		'snodland'        => 'Snodland',
		'tonbridge'       => 'Tonbridge',
		'tunbridge-wells' => 'Tunbridge Wells',
		'ashford'         => 'Ashford',
		'herne-bay'       => 'Herne Bay',
		'whitstable'      => 'Whitstable',
		'paddock-wood'    => 'Paddock Wood',
		'thanet'          => 'Thanet',
		'medway'          => 'Medway',
	);
	$hub   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
	$copy  = ccs_area_copy_2026_11_base();
	$out   = $with_outings ? ccs_area_outings_2026_11() : array();
	foreach ( $copy as $slug => $row ) {
		$copy[ $slug ]['heading'] = preg_replace( '/^Respite and complex care in /', 'Care at home in ', $row['heading'] );
		if ( isset( $lines[ $slug ] ) ) {
			$copy[ $slug ]['excerpt'] = $lines[ $slug ];
		}
		if ( isset( $towns[ $slug ] ) ) {
			$copy[ $slug ]['content'] = $row['content'] . "\n" . '<p>Alongside these we provide the rest of <a href="' . $hub . '">our services</a> in ' . $towns[ $slug ] . '.</p>';
		}
		if ( isset( $out[ $slug ] ) ) {
			$copy[ $slug ]['content'] .= "\n" . '<p>' . $out[ $slug ] . '</p>';
		}
	}
	return $copy;
}

/**
 * Where carers go with the people they support, by area (Nov 2026, from the
 * client: "we also take them out on walks or to the park no matter their
 * age"; a client supported at Upz and Downz, another at Lainey's Care Farm;
 * trips to Wildwood "every now and then"). The one thing on an area page a
 * national provider could not write. Places checked on their own listings
 * on 23 Nov 2026: Upz and Downz CIC, a day service for adults with learning
 * disabilities, Tonbridge TN10; Laineys Care Farm, Wadd Farm, Frittenden
 * TN17 (nearest area we cover: Paddock Wood); Wildwood Trust, Herne Common
 * CT6. No client is named.
 *
 * @return array<string, string> slug => sentence(s), HTML.
 */
function ccs_area_outings_2026_11() {
	$walks = 'Carers also go out with the people they support, whatever their age, for a walk or to the park.';
	return array(
		'tonbridge'    => $walks . ' In Tonbridge we have supported one of our clients at <a href="https://www.upzanddownz.com/">Upz and Downz</a>, the day service for adults with learning disabilities.',
		'paddock-wood' => $walks . ' Nearby, we have supported one of our clients at Lainey’s Care Farm in Frittenden, a care farm for adults with learning disabilities.',
		'herne-bay'    => $walks . ' Every now and then we go further, to <a href="https://kent.wildwoodtrust.org/">Wildwood</a>, the wildlife park at Herne Common.',
	);
}

/**
 * Existing sites: add the outings sentence to the three area pages, once,
 * only where the page still holds exactly the wording before it.
 */
function ccs_area_copy_2026_11e() {
	if ( get_option( 'ccs_area_copy_2026_11e' ) || ! get_option( 'ccs_area_copy_2026_11d' ) || ! post_type_exists( 'location' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_area_copy_2026_11e', '1', '', 'no' ) ) {
		return;
	}
	ccs_area_copy_apply_2026_11( array( ccs_area_copy_2026_11( false ) ) );
}
add_action( 'init', 'ccs_area_copy_2026_11e', 39 );

/**
 * Every service offered in an area, in the site's order: all of them, less
 * any of domiciliary, respite and complex care the area does not get.
 *
 * @param int $post_id Location post ID.
 * @return WP_Post[]
 */
function ccs_area_offered_services( $post_id ) {
	if ( ! function_exists( 'ccs_ordered_services' ) ) {
		return array();
	}
	$named  = array( 'domiciliary-care', 'respite-care', 'complex-care' );
	$gets   = ccs_location_services( $post_id );
	$offers = array();
	foreach ( ccs_ordered_services() as $service ) {
		if ( in_array( $service->post_name, $named, true ) && ! in_array( $service->post_name, $gets, true ) ) {
			continue;
		}
		$offers[] = $service;
	}
	return $offers;
}

/**
 * Put the new wording and services into a set of area definitions (used by
 * theme activation and inc/locations-2026-11.php when creating pages).
 *
 * @param array<string, array<string, mixed>> $areas slug => post data.
 * @return array<string, array<string, mixed>>
 */
function ccs_apply_area_copy( array $areas ) {
	foreach ( ccs_area_copy_2026_11() as $slug => $copy ) {
		if ( ! isset( $areas[ $slug ] ) ) {
			continue;
		}
		$areas[ $slug ]['content']                   = $copy['content'];
		$areas[ $slug ]['excerpt']                   = $copy['excerpt'];
		$areas[ $slug ]['meta']['location_heading']  = $copy['heading'];
		$areas[ $slug ]['meta']['location_services'] = 'respite-care,complex-care';
	}
	return $areas;
}

/**
 * Fingerprint of a stored text, independent of the site address and spacing,
 * so the migration can tell the theme's own wording from the client's edits.
 *
 * @param string $text Text.
 * @return string
 */
function ccs_area_copy_fingerprint( $text ) {
	return md5( preg_replace( '/\s+/', ' ', trim( str_replace( untrailingslashit( home_url() ), '{home}', (string) $text ) ) ) );
}

/**
 * Apply the services and the new wording once, on existing sites.
 *
 * Wording is replaced only where the page still has the theme's original
 * text (checked by fingerprint); a page the client has edited keeps its
 * text and is listed for review in the admin instead.
 */
function ccs_area_services_2026_11() {
	// "b": the second run, after Medway was corrected to respite and complex care.
	if ( get_option( 'ccs_area_services_2026_11b' ) || ! post_type_exists( 'location' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_area_services_2026_11b', '1', '', 'no' ) ) {
		return;
	}

	// Fingerprints of the original content and excerpt, by area.
	$original = array(
		'west-malling'    => array( '51ea021c9a28e003386242fd811c1800', 'fcfc5be22dae3a10c12d059325fbe0c7' ),
		'snodland'        => array( '2438b1dca9b56f25964bd528779aad5d', 'b82de09cc6364a69365123143f0ec9af' ),
		'tonbridge'       => array( '20838605c11a0436bd340ec4c0811dc7', '6780f09ae207c76e0fb05c8b08d9a463' ),
		'tunbridge-wells' => array( 'e149399220041101dd7b59eb6ae4470d', 'effb052c129dcf21ce2bb58a503e7e6a' ),
		'ashford'         => array( '023792fc579d8f248c092f86daad2f93', 'c0dbed0941ed790024aa98ef07eff902' ),
		'herne-bay'       => array( 'e8ef9441211a1bf9cbd6e6b95a824a41', 'ed71db2e0a695bcf998a7d03062bab27' ),
		'whitstable'      => array( '729a6de6745e78a81303621044ef4c75', 'd045c90410795ff237a5fd8d0b894a56' ),
		'paddock-wood'    => array( '96dcada724f2ca5e68cdceb0d1f14ac1', '79c53ab64aa04180228e9ec24b3f0dc6' ),
		'thanet'          => array( '7d19da1239175e2e0d7ac0aa6d49999b', '1d342ac775e8321614569bad42102518' ),
		'medway'          => array( 'bca1df48da163795453957d6bf19b0c7', '7de7e0f14f8d623ef3401c74fec8857d' ),
	);

	$review = array();
	foreach ( ccs_area_copy_2026_11() as $slug => $copy ) {
		$post = get_page_by_path( $slug, OBJECT, 'location' );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		// The client stated what each of these areas gets, so this is set
		// whatever the page had before.
		update_post_meta( $post->ID, 'location_services', 'respite-care,complex-care' );
		if ( 'professional' === get_post_meta( $post->ID, 'location_audience', true ) ) {
			delete_post_meta( $post->ID, 'location_audience' );
		}

		$update  = array( 'ID' => $post->ID );
		$current = ccs_area_copy_fingerprint( $post->post_content );
		if ( isset( $original[ $slug ] ) && $current === $original[ $slug ][0] ) {
			$update['post_content'] = $copy['content'];
		} elseif ( ccs_area_copy_fingerprint( $copy['content'] ) !== $current ) {
			$review[] = $post->ID;
		}
		if ( isset( $original[ $slug ] ) && ccs_area_copy_fingerprint( $post->post_excerpt ) === $original[ $slug ][1] ) {
			$update['post_excerpt'] = $copy['excerpt'];
		}
		// Medway's title said "Complex care" while it was thought to be
		// complex care only; it now matches the other areas.
		if ( 'medway' === $slug && 'Complex Care in Medway' === $post->post_title ) {
			$update['post_title'] = 'Home Care in Medway';
		}
		if ( count( $update ) > 1 ) {
			wp_update_post( wp_slash( $update ) );
		}
		$heading = (string) get_post_meta( $post->ID, 'location_heading', true );
		if ( '' === $heading || 0 === strpos( $heading, 'Home care in' ) || 'Complex care in Medway' === $heading ) {
			update_post_meta( $post->ID, 'location_heading', $copy['heading'] );
		}
	}
	if ( $review ) {
		update_option( 'ccs_area_copy_review', array_unique( $review ), false );
	}

	ccs_sync_area_menu_descriptions();
}
add_action( 'init', 'ccs_area_services_2026_11', 36 );

/**
 * Existing sites: move the ten area pages from the "respite and complex
 * care" wording to the wording above, once. Each of the heading, the hero
 * line and the body changes only where the page still holds exactly the
 * earlier theme wording, so anything edited since is left alone.
 */
function ccs_area_copy_2026_11c() {
	if ( get_option( 'ccs_area_copy_2026_11c' ) || ! get_option( 'ccs_area_services_2026_11b' ) || ! post_type_exists( 'location' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_area_copy_2026_11c', '1', '', 'no' ) ) {
		return;
	}
	ccs_area_copy_apply_2026_11( array( ccs_area_copy_2026_11_base() ) );
}
add_action( 'init', 'ccs_area_copy_2026_11c', 37 );

/**
 * The same again for the second change that day (the hero line reworded,
 * the paragraph of links removed): a site that had already taken the first
 * change is brought to the current wording, once.
 */
function ccs_area_copy_2026_11d() {
	if ( get_option( 'ccs_area_copy_2026_11d' ) || ! get_option( 'ccs_area_copy_2026_11c' ) || ! post_type_exists( 'location' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_area_copy_2026_11d', '1', '', 'no' ) ) {
		return;
	}
	ccs_area_copy_apply_2026_11( array( ccs_area_copy_2026_11c_was() ) );
}
add_action( 'init', 'ccs_area_copy_2026_11d', 38 );

/**
 * Move the area pages to the current wording wherever a page still holds
 * exactly one of the given earlier wordings.
 *
 * @param array<int, array<string, array<string, string>>> $earlier Earlier wordings, each slug => heading, excerpt, content.
 */
function ccs_area_copy_apply_2026_11( array $earlier ) {
	foreach ( ccs_area_copy_2026_11() as $slug => $copy ) {
		$post = get_page_by_path( $slug, OBJECT, 'location' );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		$update  = array( 'ID' => $post->ID );
		$heading = (string) get_post_meta( $post->ID, 'location_heading', true );
		foreach ( $earlier as $before ) {
			if ( ! isset( $before[ $slug ] ) ) {
				continue;
			}
			if ( ccs_area_copy_fingerprint( $post->post_content ) === ccs_area_copy_fingerprint( $before[ $slug ]['content'] ) ) {
				$update['post_content'] = $copy['content'];
			}
			if ( ccs_area_copy_fingerprint( $post->post_excerpt ) === ccs_area_copy_fingerprint( $before[ $slug ]['excerpt'] ) ) {
				$update['post_excerpt'] = $copy['excerpt'];
			}
			if ( $before[ $slug ]['heading'] === $heading ) {
				update_post_meta( $post->ID, 'location_heading', $copy['heading'] );
			}
		}
		if ( count( $update ) > 1 ) {
			wp_update_post( wp_slash( $update ) );
		}
	}
}

/**
 * Clear the line under each town in the main menu's Areas dropdown: the
 * dropdown lists town names only (client, Nov 2026, on a service line under
 * every town: "messy"). Each area page and the areas hub say what it gets.
 */
function ccs_sync_area_menu_descriptions() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return;
	}
	foreach ( (array) wp_get_nav_menu_items( (int) $locations['primary'] ) as $item ) {
		if ( 'location' === $item->object && '' !== trim( (string) $item->post_content ) ) {
			wp_update_post(
				array(
					'ID'           => $item->ID,
					'post_content' => '',
				)
			);
		}
	}
}

/**
 * Admin notice: area pages whose wording was edited, so it was not changed
 * automatically, and may still mention domiciliary care.
 */
function ccs_area_copy_review_notice() {
	$ids = (array) get_option( 'ccs_area_copy_review', array() );
	if ( ! $ids || ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	$links = array();
	foreach ( $ids as $id ) {
		if ( get_post( $id ) && false !== stripos( get_post_field( 'post_content', $id ), 'domiciliary' ) ) {
			$links[] = '<a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . esc_html( get_the_title( $id ) ) . '</a>';
		}
	}
	if ( ! $links ) {
		delete_option( 'ccs_area_copy_review' );
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'These area pages still mention domiciliary care, which is not offered there. Their wording was edited, so it was not changed automatically:', 'ccs-wp-theme' ) . ' ' . implode( ', ', $links ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}
add_action( 'admin_notices', 'ccs_area_copy_review_notice' );
