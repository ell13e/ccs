<?php
/**
 * New area pages (Nov 2026).
 *
 * The client confirmed coverage of Ashford, Herne Bay, Whitstable, Paddock
 * Wood and Thanet (Margate, Ramsgate, Broadstairs), and of all of Medway for
 * complex care only. Pages follow what people search for: a town page where
 * families search the town by name; one Thanet page titled for its three
 * towns, rather than three near-identical pages; and a Medway page written
 * for case managers, since only complex care is offered there.
 *
 * Wording uses only what the client stated, what the service pages already
 * say, and plain geography. The client should read each page before relying
 * on it (docs/FAQ-DRAFTS-FOR-APPROVAL.md lists the open questions). Areas
 * without domiciliary care (Medway included, corrected by the client) take
 * their final wording from inc/location-services.php.
 *
 * Created once on existing sites; never re-created if deleted or renamed.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The new areas, slug => post data.
 *
 * @return array<string, array<string, mixed>>
 */
function ccs_new_locations_2026_11() {
	$dom     = home_url( '/services/domiciliary-care/' );
	$respite = home_url( '/services/respite-care/' );
	$complex = home_url( '/services/complex-care/' );
	$refer   = home_url( '/resources/referral-information/' );

	// Areas without domiciliary care: wording and services from
	// inc/location-services.php replace the drafts below.
	return ccs_apply_area_copy( array(
		'ashford'      => array(
			'title'   => 'Home Care in Ashford',
			'excerpt' => 'Home care in Ashford and its neighbourhoods, from a small team matched to you and kept the same.',
			'content' => '<p>Continuity of Care Services provides home care in Ashford, from Kennington and Willesborough to the newer neighbourhoods at Kingsnorth and Repton Park.</p>
<p>That might be a short morning <a href="' . $dom . '">domiciliary care</a> visit, a few days of <a href="' . $respite . '">respite care</a> while a family carer has a break, or <a href="' . $complex . '">complex care</a> around the clock. Whatever you need, it starts with a free consultation and continues with the same small team.</p>',
			'meta'    => array(
				'location_town'          => 'Ashford',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'TN',
				'location_areas_covered' => "Kennington\nWillesborough\nKingsnorth\nSingleton\nRepton Park",
				'location_latitude'      => 51.1465,
				'location_longitude'     => 0.8750,
			),
		),
		'herne-bay'    => array(
			'title'   => 'Home Care in Herne Bay',
			'excerpt' => 'Home care in Herne Bay and along the north Kent coast, with the same carers at every visit.',
			'content' => '<p>We provide home care in Herne Bay and the places around it on the north Kent coast, including Herne, Beltinge, Greenhill and Studd Hill.</p>
<p>Care is planned around your routine: help getting up and dressed, meals and medication, a break for a family carer, or specialist <a href="' . $complex . '">complex care</a> at home. You get to know a small team, because it is the same people who come each time.</p>',
			'meta'    => array(
				'location_town'          => 'Herne Bay',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'CT',
				'location_areas_covered' => "Herne\nBeltinge\nGreenhill\nStudd Hill\nHampton",
				'location_latitude'      => 51.3730,
				'location_longitude'     => 1.1280,
			),
		),
		'whitstable'   => array(
			'title'   => 'Home Care in Whitstable',
			'excerpt' => 'Home care in Whitstable, Tankerton and Chestfield, from carers you get to know.',
			'content' => '<p>Continuity of Care Services provides home care across Whitstable, from the town itself to Tankerton, Chestfield, Swalecliffe and Seasalter.</p>
<p>We offer <a href="' . $dom . '">everyday help at home</a>, <a href="' . $respite . '">respite care</a> and <a href="' . $complex . '">complex care</a>. Each plan begins with a free conversation about what would help, and the same small team visits from then on.</p>',
			'meta'    => array(
				'location_town'          => 'Whitstable',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'CT',
				'location_areas_covered' => "Tankerton\nChestfield\nSwalecliffe\nSeasalter",
				'location_latitude'      => 51.3604,
				'location_longitude'     => 1.0250,
			),
		),
		'paddock-wood' => array(
			'title'   => 'Home Care in Paddock Wood',
			'excerpt' => 'Home care in Paddock Wood and the villages nearby, from a consistent, matched team.',
			'content' => '<p>We provide home care in Paddock Wood and the villages close by, including Five Oak Green, Brenchley and Matfield.</p>
<p>From a regular <a href="' . $dom . '">domiciliary care</a> visit to <a href="' . $complex . '">complex care</a> day and night, your care is built around how you like to live, and delivered by the same small team each time.</p>',
			'meta'    => array(
				'location_town'          => 'Paddock Wood',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'TN',
				'location_areas_covered' => "Five Oak Green\nBrenchley\nMatfield",
				'location_latitude'      => 51.1822,
				'location_longitude'     => 0.3889,
			),
		),
		'thanet'       => array(
			'title'   => 'Home Care in Thanet: Margate, Ramsgate and Broadstairs',
			'excerpt' => 'Home care across Thanet, in Margate, Ramsgate, Broadstairs and the towns between them.',
			'content' => '<p>Continuity of Care Services provides home care across Thanet: in Margate, Ramsgate and Broadstairs, and in Cliftonville, Westgate-on-Sea, Birchington and Minster.</p>
<p>We offer <a href="' . $dom . '">everyday help at home</a>, <a href="' . $respite . '">respite care</a> for family carers and <a href="' . $complex . '">complex care</a>. Every plan starts with a free consultation, and you are matched with a small team who keep coming back.</p>',
			'meta'    => array(
				'location_town'          => 'Thanet',
				'location_heading'       => 'Home care in Margate, Ramsgate and Broadstairs',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'CT',
				'location_areas_covered' => "Margate\nRamsgate\nBroadstairs\nCliftonville\nWestgate-on-Sea\nBirchington\nMinster",
				'location_latitude'      => 51.3600,
				'location_longitude'     => 1.3900,
			),
		),
		'medway'       => array(
			'title'   => 'Home Care in Medway',
			'excerpt' => 'Complex care at home across Medway, for case managers, NHS teams and families arranging specialist support.',
			'content' => '<p>We provide <a href="' . $complex . '">complex care</a> at home across Medway, in Chatham, Gillingham, Rochester, Strood and Rainham. In Medway we take on complex care packages only.</p>
<p>Our trained team supports people with conditions including epilepsy, spinal injuries, acquired brain injury and PEG feeding, and works alongside the person\'s case manager, healthcare professionals and family. To discuss a referral, call the office or see our <a href="' . $refer . '">referral information</a>.</p>',
			'meta'    => array(
				'location_town'          => 'Medway',
				'location_county'        => 'Kent',
				'location_postcode_area' => 'ME',
				'location_areas_covered' => "Chatham\nGillingham\nRochester\nStrood\nRainham\nWalderslade",
				'location_latitude'      => 51.3790,
				'location_longitude'     => 0.5280,
			),
		),
	) );
}

/**
 * Create the new area pages once.
 */
function ccs_ensure_locations_2026_11() {
	if ( get_option( 'ccs_locations_2026_11' ) || ! post_type_exists( 'location' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_locations_2026_11', '1', '', 'no' ) ) {
		return;
	}

	$created = array();
	foreach ( ccs_new_locations_2026_11() as $slug => $data ) {
		$exists = get_posts(
			array(
				'post_type'      => 'location',
				'name'           => $slug,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $exists ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'location',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $data['title'],
				'post_excerpt' => $data['excerpt'],
				'post_content' => $data['content'],
				'meta_input'   => $data['meta'],
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			$created[] = (int) $id;
		}
	}

	// Add the new areas to the main menu's Areas dropdown, if that menu is
	// still the theme's own (it is rebuilt from inc/menu-update.php).
	if ( $created && function_exists( 'ccs_add_new_areas_to_menu' ) ) {
		ccs_add_new_areas_to_menu( $created );
	}
}
add_action( 'init', 'ccs_ensure_locations_2026_11', 35 );
