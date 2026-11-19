<?php
/**
 * Five more services (Nov 2026).
 *
 * The company's live site lists eight services; this theme shipped three
 * (domiciliary, respite, complex). The user asked for all eight as full
 * pages. The wording here is the company's own, from its live services page
 * (continuitycareservices.co.uk/home/home-care-services-kent/), with only
 * house-style changes: "and" for "&", commas for dashes, and bullets that
 * were printed twice there listed once. Nothing is added.
 *
 * "Companion Care" on the live site is titled "Companionship Care" here:
 * reference/strategy.md notes that "companion care" in UK search belongs to a
 * vet chain.
 *
 * No photographs are set: the theme has no photo that is known to show these
 * services, and putting a client's photo on, say, the palliative page would
 * be a guess about a real person. Cards show the service's icon instead until
 * the client chooses photos (Services > edit > Featured image).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The five services: slug => title, excerpt, content, icon, menu line.
 *
 * @return array<string, array<string, string>>
 */
function ccs_more_services_2026_11() {
	return array(
		'companionship-care' => array(
			'title'   => 'Companionship Care',
			'icon'    => 'people',
			'desc'    => 'Company, outings and a chat',
			'excerpt' => 'Meaningful companionship brightens days and creates genuine connection, with every visit shaped around your interests and personality.',
			'content' => "<p>Meaningful companionship brightens days and creates genuine connection. Whether you’d like to explore the local park, dive into a craft project, or simply share stories over tea, our carers adapt each visit to your interests and personality.</p>
<p>Every visit is shaped around interests and personality. Perhaps you’d like to revisit old photo albums, try a gentle walk in the local park, tackle a puzzle, or even explore a new café in Maidstone. Our carers take time to understand what brings you joy and create experiences that uplift your spirits.</p>
<h2>What’s included</h2>
<ul>
<li>Engaging conversation and emotional support</li>
<li>Flexible visit lengths to suit your routine</li>
<li>Tea-time chats and well-being check-ins</li>
<li>Accompanied trips to shops, cafés or community groups</li>
<li>Shared activities (arts, games, walks or outings)</li>
</ul>",
		),
		'palliative-care'    => array(
			'title'   => 'Palliative Care',
			'icon'    => 'heart',
			'desc'    => 'Comfort and support at home',
			'excerpt' => 'When you or a loved one faces a life-limiting illness, our palliative and end-of-life care focuses on comfort, pain relief and support for the whole family.',
			'content' => "<p>When you or a loved one faces a life-limiting illness, our palliative and end-of-life care focuses on alleviating symptoms, managing pain and providing emotional and physical support. We collaborate closely with your hospice team, GP and family to deliver a bespoke care plan that honours personal wishes and maximises quality of life.</p>
<p>From medication management and symptom relief to heartfelt companionship and family liaison, our compassionate carers and nurses are available around the clock. Whether it’s helping you remain at home or providing respite for family members, we’re here to ensure every moment is as peaceful and meaningful as possible.</p>
<h2>What’s included</h2>
<ul>
<li>Symptom and pain management</li>
<li>Medication administration and monitoring</li>
<li>Personal care with dignity and respect</li>
<li>Emotional wellbeing and bereavement support</li>
<li>24/7 on-call availability and rapid response</li>
</ul>",
		),
		'overnight-care'     => array(
			'title'   => 'Overnight Care',
			'icon'    => 'moon',
			'desc'    => 'Someone there through the night',
			'excerpt' => 'Discreet, compassionate support through the night, so you and your loved ones can rest peacefully.',
			'content' => "<p>Even when the world is quiet, our night care staff remain attentive, providing discreet and compassionate support so you and your loved ones can rest peacefully. Whether it’s a midnight check-in, help with toileting, or administering medication, our night-care team is trained to respond swiftly and respectfully.</p>
<p>Every night visit is tailored to individual needs. Experienced staff can gently reposition you to enhance your comfort, monitor any changes in your health, and communicate with family or healthcare professionals. By providing continuity of care 24/7, you’ll wake in the morning knowing someone reliable was by your side all night.</p>
<h2>What’s included</h2>
<ul>
<li>Overnight wellbeing checks and safety monitoring</li>
<li>Assistance with toileting and continence care</li>
<li>Repositioning to prevent pressure sores</li>
<li>Medication prompts and emergency response</li>
<li>Discreet support to minimise sleep disruption</li>
</ul>",
		),
		'continuous-care'    => array(
			'title'   => 'Continuous Care',
			'icon'    => 'clock',
			'desc'    => 'Care around the clock',
			'excerpt' => 'Reassurance and assistance day and night, from a small, dedicated team working carefully managed shifts.',
			'content' => "<p>When your loved one needs reassurance and assistance day and night, our home care services are available round the clock through carefully managed shift patterns.</p>
<p>You’ll have a small, dedicated team of carers, each enhanced DBS-checked and trained by us, who know your routines and preferences inside out. With weekly rota planning and regular care-plan reviews, you can rest assured that care is never interrupted, and your family always stays in the loop.</p>
<h2>What’s included</h2>
<ul>
<li>Personal care and medication with prompt and discreet assistance</li>
<li>Meal preparation and nutrition checks: breakfast, lunch, dinner and snacks</li>
<li>Companionship and meaningful activities tailored to interests at any hour</li>
<li>Well-being checks and emergency response, providing urgent support if needs change</li>
<li>Seamless handovers with clear communication between shifts to ensure consistency</li>
</ul>",
		),
		'disability-care'    => array(
			'title'   => 'Disability Care',
			'icon'    => 'shield',
			'desc'    => 'Support for children and adults',
			'excerpt' => 'Respectful, client-centred support for children and adults with physical disabilities, learning disabilities or autism spectrum conditions.',
			'content' => "<p>We take pride in offering respectful, client-centred support for children and adults with physical disabilities, learning disabilities, or autism spectrum conditions. Our caring and experienced team works closely with you and your family to help develop essential life skills, encourage independence, and engage in fun social, educational, and recreational activities.</p>
<p>Each care plan is thoughtfully tailored to help you meet your personal goals, whether that’s exploring sensory-friendly hobbies or joining community groups. We also provide any necessary clinical or nursing support, such as catheter care or PEG feeding, ensuring you feel safe and respected every step of the way.</p>
<h2>What’s included</h2>
<ul>
<li>Personal care and toileting support</li>
<li>Life-skills coaching (cooking, budgeting, personal hygiene)</li>
<li>Sensory and therapeutic activities (art, music, gardens)</li>
<li>Community integration (day trips, group sessions, workplace skills)</li>
<li>Specialist clinical support (medication administration, PEG care)</li>
<li>Family liaison and regular review meetings</li>
</ul>",
		),
	);
}

/**
 * Every service's icon, by slug: one map for cards, the menu and the pages.
 *
 * @param string $slug Service slug.
 * @return string Icon name for ccs_icon().
 */
function ccs_service_icon( $slug ) {
	$icons = array(
		'domiciliary-care' => 'home',
		'respite-care'     => 'cup',
		'complex-care'     => 'pulse',
	);
	foreach ( array_merge( ccs_more_services_2026_11(), function_exists( 'ccs_more_services_2026_11c' ) ? ccs_more_services_2026_11c() : array() ) as $more_slug => $more ) {
		$icons[ $more_slug ] = $more['icon'];
	}
	return isset( $icons[ $slug ] ) ? $icons[ $slug ] : 'heart';
}

/**
 * The order services appear in, everywhere.
 *
 * @return string[]
 */
function ccs_service_order() {
	// The first eight, then the thirteen added in inc/services-2026-11c.php (everyday care, then condition-led).
	return array_merge(
		array( 'domiciliary-care', 'respite-care', 'complex-care', 'companionship-care', 'overnight-care', 'continuous-care', 'palliative-care', 'disability-care' ),
		function_exists( 'ccs_more_services_2026_11c' ) ? array_keys( ccs_more_services_2026_11c() ) : array()
	);
}

/**
 * Overnight care is charged from the published night rates. The other four
 * new services are not mapped to a rate card: which rates apply to them is
 * the client's to say, so their pages point to the Costs page instead.
 */
add_filter(
	'ccs_fee_groups_for_service',
	static function ( $map ) {
		$map['overnight-care'] = array( 'Nights' );
		return $map;
	}
);

/**
 * Create the five service pages once, and add them to the main menu under
 * "Our services", before "Costs and funding". A service that already exists
 * (by its address) is left exactly as it is.
 */
function ccs_add_more_services_2026_11() {
	if ( get_option( 'ccs_more_services_2026_11' ) || ! post_type_exists( 'service' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_more_services_2026_11', '1', '', 'no' ) ) {
		return;
	}

	$authors = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
	$created = array();
	foreach ( ccs_more_services_2026_11() as $slug => $data ) {
		if ( ccs_menu_find( $slug, 'service' ) ) {
			continue;
		}
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'service',
					'post_title'   => $data['title'],
					'post_name'    => $slug,
					'post_content' => $data['content'],
					'post_excerpt' => $data['excerpt'],
					'post_status'  => 'publish',
					'post_author'  => $authors ? (int) $authors[0] : 0,
				)
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			$created[ $slug ] = $data;
		}
	}
}
add_action( 'init', 'ccs_add_more_services_2026_11', 45 );

/**
 * Add the five services to the main menu under "Our services", before
 * "Costs and funding". Its own step, and only marked done once the menu
 * exists: on a fresh site the pages are created before the menu is, and the
 * menu part used to be skipped for good, leaving three services in a
 * two-column dropdown.
 */
function ccs_add_more_services_to_menu_2026_11() {
	if ( get_option( 'ccs_more_services_menu_2026_11' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
	$hub       = ccs_menu_find( 'home-care-services-kent' );
	if ( ! $menu_id || ! is_array( $items ) || ! $hub ) {
		return;
	}
	$parent = 0;
	foreach ( $items as $item ) {
		if ( 0 === (int) $item->menu_item_parent && (int) $item->object_id === (int) $hub->ID ) {
			$parent = (int) $item->ID;
		}
	}
	if ( ! $parent ) {
		return;
	}
	update_option( 'ccs_more_services_menu_2026_11', 1, false );

	// Only the services that exist and are not in the menu yet.
	$in_menu = array();
	foreach ( $items as $item ) {
		if ( 'service' === $item->object ) {
			$in_menu[ (int) $item->object_id ] = true;
		}
	}
	$created = array();
	foreach ( ccs_more_services_2026_11() as $slug => $data ) {
		$service = ccs_menu_find( $slug, 'service' );
		if ( $service && empty( $in_menu[ (int) $service->ID ] ) ) {
			$created[ $slug ] = $data;
		}
	}
	if ( empty( $created ) ) {
		return;
	}
	$insert_at = 0;
	$last      = 0;
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent !== $parent ) {
			continue;
		}
		$last = max( $last, (int) $item->menu_order );
		if ( ! $insert_at && 'service' !== $item->object ) {
			$insert_at = (int) $item->menu_order;
		}
	}
	$insert_at = $insert_at ? $insert_at : $last + 1;
	$shift     = count( $created );
	foreach ( $items as $item ) {
		if ( (int) $item->menu_order >= $insert_at ) {
			wp_update_post( array( 'ID' => (int) $item->ID, 'menu_order' => (int) $item->menu_order + $shift ) );
		}
	}
	$position = $insert_at;
	foreach ( ccs_service_order() as $slug ) {
		if ( isset( $created[ $slug ] ) ) {
			ccs_menu_add_entry( $menu_id, array( 'label' => ccs_service_menu_label( $created[ $slug ]['title'] ), 'service' => $slug, 'icon' => $created[ $slug ]['icon'], 'desc' => $created[ $slug ]['desc'] ), $parent, $position );
			++$position;
		}
	}
}
add_action( 'init', 'ccs_add_more_services_to_menu_2026_11', 46 );


/**
 * Menu labels in sentence case ("Companionship care"), like the three
 * services already in the menu.
 *
 * @param string $title Service title.
 * @return string
 */
function ccs_service_menu_label( $title ) {
	return ucfirst( strtolower( $title ) );
}

/**
 * One-time fix for sites where the five were added in title case.
 */
function ccs_fix_service_menu_labels_2026_11() {
	if ( get_option( 'ccs_service_menu_labels_2026_11b' ) ) {
		return;
	}
	update_option( 'ccs_service_menu_labels_2026_11b', 1, false );
	// WordPress stores no label when it equals the page's title, so the items
	// are found by the service they point to, not by their text.
	foreach ( ccs_more_services_2026_11() as $slug => $data ) {
		$service = ccs_menu_find( $slug, 'service' );
		if ( ! $service ) {
			continue;
		}
		$items = get_posts(
			array(
				'post_type'        => 'nav_menu_item',
				'post_status'      => 'any',
				'numberposts'      => 10,
				'meta_key'         => '_menu_item_object_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-time update.
				'meta_value'       => (string) $service->ID, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- one-time update.
				'suppress_filters' => true,
			)
		);
		foreach ( $items as $item ) {
			if ( '' === $item->post_title || $data['title'] === $item->post_title ) {
				wp_update_post( array( 'ID' => $item->ID, 'post_title' => ccs_service_menu_label( $data['title'] ) ) );
			}
		}
	}
}
add_action( 'init', 'ccs_fix_service_menu_labels_2026_11', 46 );
