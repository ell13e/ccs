<?php
/**
 * Local support on each area page (Nov 2026).
 *
 * Each area page has a "Local support in [town]" section that prints the
 * page's Local support groups (Areas > edit > Local support groups). It was
 * empty everywhere, so it never showed, and the outlying area pages carried
 * about 200 words. This fills it with the five things a family arranging
 * care most often needs locally: the council's adult social care line, the
 * nearest hospital, support for unpaid carers, the hospice and Age UK.
 *
 * Where the facts come from: a research report the client commissioned
 * (Nov 2026), then every name, phone number and opening time checked on the
 * organisation's own website on 23 Nov 2026. Only what those pages state is
 * used. Left out because the organisation's own page did not confirm it:
 * that an A&E is "24-hour" at Maidstone or Tunbridge Wells Hospital, which
 * towns a hospice serves, and Kent County Council's separate out-of-hours
 * number (its page gives one number). Kent County Council's hours are
 * 9am to 5pm as its page says, not the 8:30am in the report.
 *
 * These are other organisations' details and they change: check them once
 * or twice a year. The client edits them in the area's Local support groups;
 * the theme only fills an area whose list is still empty.
 *
 * Sources (all read 23 Nov 2026):
 * kent.gov.uk care needs assessments; medway.gov.uk adult social care;
 * mtw.nhs.uk contact us; medway.nhs.uk emergency department and contact us;
 * ekhuft.nhs.uk urgent care, William Harvey and QEQM pages; imago.community/kent-carers; involvekent.org.uk
 * (East Kent carers contract, 22 Jul 2026); carersfirst.org.uk/medway;
 * hokh.org contact us; hospiceintheweald.org.uk contact us;
 * pilgrimshospices.org advice line; kentcht.nhs.uk Wisdom Hospice;
 * ageuk.org.uk local branch pages.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Local support for each area: slug => list of name and contact lines.
 *
 * @return array<string, array<int, array{group_name:string, group_contact:string}>>
 */
function ccs_area_local_support() {
	$card = static function ( $name, $contact ) {
		return array(
			'group_name'    => $name,
			'group_contact' => $contact,
		);
	};

	// Adult social care.
	$kcc    = $card( 'Kent County Council adult social care', "To ask for a care needs assessment.\n03000 41 61 61, Monday to Friday, 9am to 5pm" );
	$medway = $card( 'Medway Council adult social care', "To ask for a care needs assessment. Medway has its own council, separate from Kent County Council.\n01634 334 466, Monday to Friday, 9am to 5pm" );

	// Hospitals.
	$maidstone_hospital = $card( 'Maidstone Hospital', "Has an emergency department (A&E).\n01622 729000" );
	$pembury_hospital   = $card( 'Tunbridge Wells Hospital, Pembury', "Has an emergency department (A&E).\n01892 823535" );
	$medway_hospital    = $card( 'Medway Maritime Hospital, Gillingham', "Emergency department open 24 hours a day.\n01634 830000" );
	$snodland_hospitals = $card( 'Maidstone Hospital and Medway Maritime Hospital', "Both have an emergency department (A&E). Medway Maritime is in Gillingham.\nMaidstone 01622 729000\nMedway Maritime 01634 830000" );
	$william_harvey     = $card( 'William Harvey Hospital, Ashford', "24-hour A&E, with an urgent treatment centre for minor injuries from 8am to midnight.\n01233 633331" );
	$qeqm               = $card( 'Queen Elizabeth The Queen Mother Hospital, Margate', "24-hour A&E, with an urgent treatment centre for minor injuries from 8am to midnight.\n01843 225544" );
	$estuary_view       = $card( 'Estuary View Urgent Treatment Centre, Whitstable', "For minor injuries and illness, daily 8am to 8pm. The nearest 24-hour A&E departments are in Margate and Ashford.\n01227 284309" );
	$herne_bay_utc      = $card( 'Herne Bay Urgent Treatment Centre', "At Queen Victoria Memorial Hospital. For minor injuries and illness, daily 8am to 8pm. The nearest 24-hour A&E departments are in Margate and Ashford.\n01227 594756" );

	// Support for unpaid carers.
	$imago        = $card( 'Imago Kent Carers Support', "For anyone looking after a relative or friend. Commissioned by Kent County Council and the NHS.\n0300 011 1965, Monday to Friday, 8:30am to 5pm" );
	$involve      = $card( 'Involve Kent carers’ support', "For anyone looking after a relative or friend in east Kent.\n0330 320 4444" );
	$carers_first = $card( 'Carers First Medway', "For unpaid carers supporting someone in Medway.\n0300 303 1555" );

	// Hospices.
	$heart_of_kent = $card( 'Heart of Kent Hospice', "Preston Hall, Aylesford.\n01622 792200" );
	$weald         = $card( 'Hospice in the Weald', "Maidstone Road, Pembury. Advice by phone 24 hours a day on 01892 820515.\n01892 820500" );
	$pilgrims      = $card( 'Pilgrims Hospices', "Hospices in Ashford, Canterbury and Thanet.\n01233 504 133" );
	$wisdom        = $card( 'Wisdom Hospice', "High Bank, Rochester.\n01634 984024, 9am to 5pm" );

	// Age UK.
	$age_mst      = $card( 'Age UK Maidstone, Sevenoaks and Tonbridge', "Information, advice and local services for older people.\n01622 753618" );
	$age_tw       = $card( 'Age UK Tunbridge Wells', "Information, advice and local services for older people.\n01892 522591" );
	$age_ashford  = $card( 'Age UK Hythe, Lyminge and Ashford', "Information, advice and local services for older people.\n01303 269602, Monday to Friday, 9am to 3:30pm" );
	$age_hbw      = $card( 'Age UK Herne Bay and Whitstable', "Information, advice and local services for older people.\n01227 749570" );
	$age_thanet   = $card( 'Age UK Thanet', "Information, advice and local services for older people.\n01843 223881" );
	$age_national = $card( 'Age UK advice line', "Advice for older people, their families and carers.\n0800 169 6565" );

	return array(
		'maidstone'       => array( $kcc, $maidstone_hospital, $imago, $heart_of_kent, $age_mst ),
		'west-malling'    => array( $kcc, $maidstone_hospital, $imago, $heart_of_kent, $age_mst ),
		'aylesford'       => array( $kcc, $maidstone_hospital, $imago, $heart_of_kent, $age_mst ),
		'snodland'        => array( $kcc, $snodland_hospitals, $imago, $heart_of_kent, $age_mst ),
		'tonbridge'       => array( $kcc, $pembury_hospital, $imago, $weald, $age_mst ),
		'tunbridge-wells' => array( $kcc, $pembury_hospital, $imago, $weald, $age_tw ),
		'paddock-wood'    => array( $kcc, $pembury_hospital, $imago, $weald, $age_tw ),
		'ashford'         => array( $kcc, $william_harvey, $involve, $pilgrims, $age_ashford ),
		'whitstable'      => array( $kcc, $estuary_view, $involve, $pilgrims, $age_hbw ),
		'herne-bay'       => array( $kcc, $herne_bay_utc, $involve, $pilgrims, $age_hbw ),
		'thanet'          => array( $kcc, $qeqm, $involve, $pilgrims, $age_thanet ),
		'medway'          => array( $medway, $medway_hospital, $carers_first, $wisdom, $age_national ),
	);
}

/**
 * Fill each area's Local support groups once, and only where the list is
 * still empty, so anything the client has entered is kept. An area that does
 * not exist yet (a new install still building its pages) is tried again on
 * the next request.
 */
function ccs_fill_area_local_support_2026_11() {
	if ( ! post_type_exists( 'location' ) ) {
		return;
	}
	$all  = ccs_area_local_support();
	$done = (array) get_option( 'ccs_area_local_support_2026_11', array() );
	$todo = array_diff( array_keys( $all ), $done );
	if ( ! $todo ) {
		return;
	}
	foreach ( $todo as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'location' );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		$existing = get_post_meta( $post->ID, 'location_local_support_groups', true );
		$has_any  = false;
		foreach ( is_array( $existing ) ? $existing : array() as $row ) {
			if ( '' !== trim( (string) ( isset( $row['group_name'] ) ? $row['group_name'] : '' ) ) || '' !== trim( (string) ( isset( $row['group_contact'] ) ? $row['group_contact'] : '' ) ) ) {
				$has_any = true;
				break;
			}
		}
		if ( ! $has_any ) {
			update_post_meta( $post->ID, 'location_local_support_groups', $all[ $slug ] );
		}
		$done[] = $slug;
	}
	update_option( 'ccs_area_local_support_2026_11', array_values( array_unique( $done ) ), false );
}
add_action( 'init', 'ccs_fill_area_local_support_2026_11', 38 );
