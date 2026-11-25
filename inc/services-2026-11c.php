<?php
/**
 * Twelve more service pages (thirteen at first; Emergency care was removed), and the two groups every service sits in
 * (Nov 2026).
 *
 * The client pointed to helpinghandshomecare.co.uk for its single service
 * pages and its "sitemap structure" (services grouped as everyday care and
 * condition-led care), and said: "we do all of the services except live in
 * and after hospital". Our site had eight service pages; these are the rest
 * of that menu, less those two.
 *
 * What is written here is general fact about each kind of care, and the
 * plain statement that it is provided, which the client made. No figures,
 * response times, staff numbers or outcomes are claimed. Each page's deeper
 * explanation (what it is, who it helps, how its options differ) is in
 * inc/service-guides.php.
 *
 * No photographs are set: none in the theme is known to show these, and a
 * client's photo on, say, the cancer care page would be a guess about a real
 * person. The page header shows the infinity mark until the client chooses.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The thirteen services: slug => title, group, icon, excerpt, content.
 *
 * @return array<string, array<string, string>>
 */
function ccs_more_services_2026_11c() {
	$list = static function ( $items ) {
		return "<h2>What’s included</h2>\n<ul>\n<li>" . implode( "</li>\n<li>", $items ) . "</li>\n</ul>";
	};

	return array(
		// Everyday care.
		'personal-care'        => array(
			'title'   => 'Personal Care',
			'group'   => 'everyday',
			'icon'    => 'home',
			'excerpt' => 'Help with washing, dressing and getting ready for the day, given with patience and respect for how you like things done.',
			'content' => "<p>Personal care is the most private kind of help, so it is given by carers you get to know, at your pace and in the way you prefer. We provide personal care at home across Maidstone and Kent.</p>\n" . $list( array( 'Washing, bathing and showering', 'Dressing and getting ready for the day', 'Help with the toilet and continence care', 'Shaving, hair, skin and mouth care', 'Getting in and out of bed safely' ) ),
		),
		'elderly-care'         => array(
			'title'   => 'Elderly Care',
			'group'   => 'everyday',
			'icon'    => 'cup',
			'excerpt' => 'Care at home for older people, from a little help each week to support every day, so home stays home.',
			'content' => "<p>Most older people want to stay in their own home, with their own things and their own routine. Elderly care makes that possible for longer, and gives family the comfort of knowing someone is calling in.</p>\n" . $list( array( 'Help with washing, dressing and meals', 'Medication reminders and support', 'Light housework, laundry and shopping', 'Company, conversation and getting out', 'Keeping family informed' ) ),
		),
		'long-term-care'       => array(
			'title'   => 'Long-term Care',
			'group'   => 'everyday',
			'icon'    => 'clock',
			'excerpt' => 'Ongoing care at home for a condition that is not going away, from carers who stay with you as needs change.',
			'content' => "<p>Some care is for a few weeks. Long-term care is for the years: steady support for an ongoing or progressive condition, reviewed as things change, from a small team who know you well.</p>\n" . $list( array( 'Regular visits planned around your routine', 'Personal care, meals and medication', 'A care plan reviewed as needs change', 'The same small team of carers', 'Working with your GP and other professionals' ) ),
		),
		'younger-adults-care'  => array(
			'title'   => 'Support for Younger Adults',
			'group'   => 'everyday',
			'icon'    => 'people',
			'excerpt' => 'Care and support for adults of working age, built around the life you want to lead, at home and out and about.',
			'content' => "<p>Care is not only for older people. Younger adults living with a disability, an injury or a long-term condition often want support that fits around work, study, friends and family.</p>\n" . $list( array( 'Personal care at times that suit your day', 'Support to get to work, study or activities', 'Help with meals, shopping and managing at home', 'Carers matched to your age and interests where we can', 'Support that changes as your plans do' ) ),
		),
		// Condition-led care.
		'dementia-care'        => array(
			'title'   => 'Dementia Care',
			'group'   => 'condition',
			'icon'    => 'heart',
			'excerpt' => 'Care at home for people living with dementia, from familiar carers who keep to the routines that help.',
			'content' => "<p>For someone living with dementia, familiar surroundings and familiar faces matter a great deal. Care at home keeps both, and gives family time to be family again.</p>\n" . $list( array( 'The same small team of carers', 'Help with washing, dressing and meals', 'Medication support', 'Company, activities and gentle routine', 'Support and breaks for family carers' ) ),
		),
		'stroke-care'          => array(
			'title'   => 'Stroke Care',
			'group'   => 'condition',
			'icon'    => 'pulse',
			'excerpt' => 'Support at home after a stroke, helping with daily life and with the exercises and routines set by your therapists.',
			'content' => "<p>Recovery after a stroke takes time and looks different for everyone. Care at home supports daily life while that recovery happens, and follows the plan set by the hospital and community teams.</p>\n" . $list( array( 'Help with washing, dressing and moving around', 'Support with eating and drinking', 'Medication support', 'Help to keep up exercises set by therapists', 'Company and encouragement' ) ),
		),
		'parkinsons-care'      => array(
			'title'   => 'Parkinson’s Care',
			'group'   => 'condition',
			'icon'    => 'shield',
			'excerpt' => 'Care at home for people living with Parkinson’s, with help to take medication at the times prescribed and support that changes as the condition does.',
			'content' => "<p>Parkinson’s affects people differently, and from one day to the next. Good care is flexible, unhurried, and careful about medication times, which matter a great deal with this condition.</p>\n" . $list( array( 'Medication given on time', 'Help with washing, dressing and moving safely', 'Meals and help with eating', 'Support on good days and difficult ones', 'Company and help to stay active' ) ),
		),
		'cancer-care'          => array(
			'title'   => 'Cancer Care',
			'group'   => 'condition',
			'icon'    => 'heart',
			'excerpt' => 'Care at home during and after cancer treatment, for the practical things and for the days when energy is low.',
			'content' => "<p>Cancer and its treatment can leave little energy for anything else. Care at home takes the practical things off your hands, and is there for the person and the family through treatment and beyond.</p>\n" . $list( array( 'Help with washing, dressing and meals', 'Medication support', 'Help getting to appointments', 'Housework, laundry and shopping', 'Company and emotional support' ) ),
		),
		'nursing-care'         => array(
			'title'   => 'Nursing Care',
			'group'   => 'everyday',
			'icon'    => 'badge',
			'excerpt' => 'Clinical care at home, planned and overseen by nurses, for people whose health needs go beyond everyday support.',
			'content' => "<p>Nursing care is for health needs that call for clinical skill. It is planned and overseen by nurses, given at home, and works alongside your GP, district nurses and hospital teams.</p>\n" . $list( array( 'Clinical tasks set out in the care plan', 'Medication administration and monitoring', 'Wound, catheter and stoma care', 'Observations and reporting changes', 'Personal care with dignity' ) ),
		),
		'cerebral-palsy-care'  => array(
			'title'   => 'Cerebral Palsy Care',
			'group'   => 'condition',
			'icon'    => 'shield',
			'excerpt' => 'Support at home for children and adults with cerebral palsy, shaped around what each person can do and wants to do.',
			'content' => "<p>Cerebral palsy affects movement and coordination, and each person differently. Support is planned around the individual, their family, and the therapists and specialists already involved.</p>\n" . $list( array( 'Personal care and help with moving around', 'Help with eating, including PEG feeding where needed', 'Support with equipment and positioning', 'Getting out to school, work and activities', 'Working with therapists and family' ) ),
		),
		'spinal-injury-care'   => array(
			'title'   => 'Spinal Injury Care',
			'group'   => 'condition',
			'icon'    => 'pulse',
			'excerpt' => 'Care at home after a spinal injury, from carers trained for the clinical and practical help it calls for.',
			'content' => "<p>A spinal injury changes daily life, and coming home from a spinal unit is a big step. Care at home covers the practical and clinical help needed, from a team trained for it.</p>\n" . $list( array( 'Personal care and moving and handling', 'Bowel and bladder care', 'Skin care and repositioning', 'Help with equipment at home', 'Support to get out and live the life you choose' ) ),
		),
		'brain-injury-care'    => array(
			'title'   => 'Brain Injury Care',
			'group'   => 'condition',
			'icon'    => 'pulse',
			'excerpt' => 'Support at home after a brain injury, patient and consistent, working with the rehabilitation team’s plan.',
			'content' => "<p>A brain injury can affect memory, mood, movement and speech, often in ways other people cannot see. Care at home is patient and consistent, and follows the plan set by the rehabilitation team.</p>\n" . $list( array( 'Help with daily routines and personal care', 'Prompts and structure through the day', 'Medication support', 'Support with rehabilitation exercises', 'Help to get back to activities and the community' ) ),
		),
	);
}

/**
 * Which group a service belongs to.
 *
 * @param string $slug Service slug.
 * Nov 2026 (client): the groups are by kind of support and then by
 * condition, so complex care and nursing care sit with the kinds of support.
 * The keys are unchanged (they are the page anchors and menu classes).
 *
 * @return string 'everyday' (types of care) or 'condition' (conditions we support).
 */
function ccs_service_group( $slug ) {
	$more = ccs_more_services_2026_11c();
	if ( isset( $more[ $slug ] ) ) {
		return $more[ $slug ]['group'];
	}
	return 'disability-care' === $slug ? 'condition' : 'everyday';
}

/**
 * The two groups, in menu order: key => name.
 *
 * @return array<string, string>
 */
function ccs_service_groups() {
	return array(
		'everyday'  => __( 'Types of care', 'ccs-wp-theme' ),
		'condition' => __( 'Conditions we support', 'ccs-wp-theme' ),
	);
}

/**
 * Create the thirteen service pages once. A service that already exists (by
 * its address) is left exactly as it is.
 */
function ccs_add_more_services_2026_11c() {
	if ( get_option( 'ccs_more_services_2026_11c' ) || ! post_type_exists( 'service' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_more_services_2026_11c', '1', '', 'no' ) ) {
		return;
	}
	$authors = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
	foreach ( ccs_more_services_2026_11c() as $slug => $data ) {
		if ( ccs_menu_find( $slug, 'service' ) ) {
			continue;
		}
		wp_insert_post(
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
			)
		);
	}
}
add_action( 'init', 'ccs_add_more_services_2026_11c', 47 );

/**
 * Regroup "Our services" in the main menu, once: a heading for each group
 * with its services under it, then the practical pages. Only marked done
 * once the menu and every service exist.
 */
function ccs_group_services_menu_2026_11c() {
	if ( get_option( 'ccs_services_menu_groups_2026_11c' ) || ! function_exists( 'ccs_menu_find' ) || ! function_exists( 'ccs_menu_add_entry' ) ) {
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
	// Every service must exist before the menu is rebuilt.
	$services = array();
	foreach ( ccs_service_order() as $slug ) {
		$post = ccs_menu_find( $slug, 'service' );
		if ( ! $post ) {
			return;
		}
		$services[ $slug ] = $post;
	}
	if ( ! $parent ) {
		return;
	}
	update_option( 'ccs_services_menu_groups_2026_11c', 1, false );

	// What is under "Our services" now: service items by the service they point to, and the rest.
	$existing = array();
	$other    = array();
	$first    = 0;
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent !== $parent ) {
			continue;
		}
		$first = $first ? min( $first, (int) $item->menu_order ) : (int) $item->menu_order;
		if ( 'service' === $item->object ) {
			$existing[ (int) $item->object_id ] = (int) $item->ID;
		} else {
			$other[] = (int) $item->ID;
		}
	}
	$first = $first ? $first : 2;

	// Make room: everything after this dropdown moves down by the number of new rows.
	$new_rows = count( $services ) - count( $existing ) + count( ccs_service_groups() );
	foreach ( $items as $item ) {
		if ( (int) $item->menu_order >= $first && (int) $item->menu_item_parent !== $parent ) {
			wp_update_post( array( 'ID' => (int) $item->ID, 'menu_order' => (int) $item->menu_order + $new_rows ) );
		}
	}

	$position = $first;
	$more     = array_merge( function_exists( 'ccs_more_services_2026_11' ) ? ccs_more_services_2026_11() : array(), ccs_more_services_2026_11c() );
	foreach ( ccs_service_groups() as $group => $label ) {
		// The group's heading: a link to its part of the services page.
		$heading = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $label,
				'menu-item-url'       => get_permalink( $hub ) . '#' . $group,
				'menu-item-type'      => 'custom',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position,
				'menu-item-parent-id' => $parent,
				'menu-item-classes'   => 'nav-heading nav-heading--' . $group,
			)
		);
		++$position;
		$column = 0;
		$in     = array();
		foreach ( $services as $slug => $post ) {
			if ( ccs_service_group( $slug ) === $group ) {
				$in[ $slug ] = $post;
			}
		}
		$half = (int) ceil( count( $in ) / 2 );
		$n    = 0;
		foreach ( $in as $slug => $post ) {
			$column = ( 'everyday' === $group ? 1 : 3 ) + ( $n >= $half ? 1 : 0 );
			$class  = 'nav-col-' . $column;
			if ( isset( $existing[ (int) $post->ID ] ) ) {
				$item_id = $existing[ (int) $post->ID ];
				wp_update_post( array( 'ID' => $item_id, 'menu_order' => $position ) );
				$classes = array_filter( (array) get_post_meta( $item_id, '_menu_item_classes', true ) );
				$classes = array_values( array_filter( $classes, static function ( $c ) { return 0 !== strpos( (string) $c, 'nav-col-' ); } ) );
				$classes[] = $class;
				update_post_meta( $item_id, '_menu_item_classes', $classes );
			} else {
				ccs_menu_add_entry(
					$menu_id,
					array(
						'label'   => function_exists( 'ccs_service_menu_label' ) ? ccs_service_menu_label( get_the_title( $post ) ) : get_the_title( $post ),
						'service' => $slug,
						'icon'    => isset( $more[ $slug ]['icon'] ) ? $more[ $slug ]['icon'] : '',
						'class'   => $class,
					),
					$parent,
					$position
				);
			}
			++$position;
			++$n;
		}
		unset( $heading );
	}
	foreach ( $other as $item_id ) {
		wp_update_post( array( 'ID' => $item_id, 'menu_order' => $position ) );
		++$position;
	}
}
add_action( 'init', 'ccs_group_services_menu_2026_11c', 48 );

/**
 * Bring an existing menu in line with the regrouping (Nov 2026, client: the
 * labels "Everyday care" and "Condition-led care" did not fit what sat under
 * them). Once: the two headings are renamed, and the services are put back
 * under them in order, so complex care and nursing care move to the first
 * group. A heading the client has renamed is left alone. A menu built after
 * this change is already right, and nothing moves.
 */
function ccs_regroup_services_menu_2026_11h() {
	if ( get_option( 'ccs_services_menu_groups_2026_11h' ) || ! get_option( 'ccs_services_menu_groups_2026_11c' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
	if ( ! $menu_id || ! is_array( $items ) ) {
		return;
	}
	update_option( 'ccs_services_menu_groups_2026_11h', 1, false );

	$old      = array( 'everyday' => 'Everyday care', 'condition' => 'Condition-led care' );
	$headings = array();
	foreach ( $items as $item ) {
		foreach ( $old as $group => $was ) {
			if ( in_array( 'nav-heading--' . $group, (array) $item->classes, true ) ) {
				$headings[ $group ] = $item;
			}
		}
	}
	if ( count( $headings ) < 2 || (int) $headings['everyday']->menu_item_parent !== (int) $headings['condition']->menu_item_parent ) {
		return;
	}
	$parent = (int) $headings['everyday']->menu_item_parent;
	$labels = ccs_service_groups();
	foreach ( $headings as $group => $item ) {
		if ( $item->title === $old[ $group ] ) {
			wp_update_post( array( 'ID' => (int) $item->ID, 'post_title' => $labels[ $group ] ) );
		}
	}

	// The dropdown's rows as they stand, and the menu positions they hold.
	$slots    = array();
	$services = array();
	$other    = array();
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent !== $parent ) {
			continue;
		}
		$slots[] = (int) $item->menu_order;
		if ( 'service' === $item->object ) {
			$services[ get_post_field( 'post_name', (int) $item->object_id ) ] = (int) $item->ID;
		} elseif ( (int) $item->ID !== (int) $headings['everyday']->ID && (int) $item->ID !== (int) $headings['condition']->ID ) {
			$other[] = (int) $item->ID;
		}
	}
	sort( $slots );

	// The same rows in the new order: each heading, then its services in two columns.
	$rank  = array_flip( ccs_service_order() );
	$order = array();
	foreach ( array_keys( $labels ) as $group ) {
		$order[] = (int) $headings[ $group ]->ID;
		$in      = array();
		foreach ( $services as $slug => $item_id ) {
			if ( ccs_service_group( $slug ) === $group ) {
				$in[ $slug ] = $item_id;
			}
		}
		uksort(
			$in,
			static function ( $a, $b ) use ( $rank ) {
				return ( $rank[ $a ] ?? PHP_INT_MAX ) <=> ( $rank[ $b ] ?? PHP_INT_MAX );
			}
		);
		$half = (int) ceil( count( $in ) / 2 );
		$n    = 0;
		foreach ( $in as $item_id ) {
			$classes   = array_filter( (array) get_post_meta( $item_id, '_menu_item_classes', true ) );
			$classes   = array_values(
				array_filter(
					$classes,
					static function ( $c ) {
						return 0 !== strpos( (string) $c, 'nav-col-' );
					}
				)
			);
			$classes[] = 'nav-col-' . ( ( 'everyday' === $group ? 1 : 3 ) + ( $n >= $half ? 1 : 0 ) );
			update_post_meta( $item_id, '_menu_item_classes', $classes );
			$order[] = $item_id;
			++$n;
		}
	}
	foreach ( array_merge( $order, $other ) as $i => $item_id ) {
		if ( isset( $slots[ $i ] ) ) {
			wp_update_post( array( 'ID' => $item_id, 'menu_order' => $slots[ $i ] ) );
		}
	}
}
add_action( 'init', 'ccs_regroup_services_menu_2026_11h', 49 );

/**
 * Alphabetical within each group (Nov 2026, client: asked how the lists were
 * ordered, which was the order the services had been added in, and chose
 * "alphabetical within each group"). Once: the services under each heading in
 * the menu are put in A to Z order by the name shown, in two columns. The
 * services page and the FAQ topics sort the same way in their templates.
 */
function ccs_alphabetise_services_menu_2026_11n() {
	if ( get_option( 'ccs_services_menu_alpha_2026_11n' ) || ! get_option( 'ccs_services_menu_groups_2026_11c' ) ) {
		return;
	}
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
	if ( ! $menu_id || ! is_array( $items ) ) {
		return;
	}
	$headings = array();
	foreach ( $items as $item ) {
		foreach ( array_keys( ccs_service_groups() ) as $group ) {
			if ( in_array( 'nav-heading--' . $group, (array) $item->classes, true ) ) {
				$headings[ $group ] = $item;
			}
		}
	}
	if ( count( $headings ) < 2 || (int) $headings['everyday']->menu_item_parent !== (int) $headings['condition']->menu_item_parent ) {
		return;
	}
	update_option( 'ccs_services_menu_alpha_2026_11n', 1, false );
	$parent = (int) $headings['everyday']->menu_item_parent;

	$slots    = array();
	$services = array();
	$other    = array();
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent !== $parent ) {
			continue;
		}
		$slots[] = (int) $item->menu_order;
		if ( 'service' === $item->object ) {
			$services[] = array(
				'id'    => (int) $item->ID,
				'title' => (string) $item->title,
				'group' => ccs_service_group( get_post_field( 'post_name', (int) $item->object_id ) ),
			);
		} elseif ( (int) $item->ID !== (int) $headings['everyday']->ID && (int) $item->ID !== (int) $headings['condition']->ID ) {
			$other[] = (int) $item->ID;
		}
	}
	sort( $slots );
	usort(
		$services,
		static function ( $a, $b ) {
			return strcasecmp( $a['title'], $b['title'] );
		}
	);

	$order = array();
	foreach ( array_keys( ccs_service_groups() ) as $group ) {
		$order[] = (int) $headings[ $group ]->ID;
		$in      = array_values(
			array_filter(
				$services,
				static function ( $service ) use ( $group ) {
					return $service['group'] === $group;
				}
			)
		);
		$half = (int) ceil( count( $in ) / 2 );
		foreach ( $in as $n => $service ) {
			$classes   = array_filter( (array) get_post_meta( $service['id'], '_menu_item_classes', true ) );
			$classes   = array_values(
				array_filter(
					$classes,
					static function ( $c ) {
						return 0 !== strpos( (string) $c, 'nav-col-' );
					}
				)
			);
			$classes[] = 'nav-col-' . ( ( 'everyday' === $group ? 1 : 3 ) + ( $n >= $half ? 1 : 0 ) );
			update_post_meta( $service['id'], '_menu_item_classes', $classes );
			$order[] = $service['id'];
		}
	}
	foreach ( array_merge( $order, $other ) as $i => $item_id ) {
		if ( isset( $slots[ $i ] ) ) {
			wp_update_post( array( 'ID' => $item_id, 'menu_order' => $slots[ $i ] ) );
		}
	}
}
add_action( 'init', 'ccs_alphabetise_services_menu_2026_11n', 50 );

/**
 * Emergency care is not provided (client, Nov 2026: "we dont do emergency
 * care"). It was one of the thirteen pages added from the Helping Hands
 * menu. Once: its page goes to the bin (recoverable there) and its menu
 * entry is removed.
 */
function ccs_remove_emergency_care_2026_11() {
	if ( get_option( 'ccs_emergency_care_removed_2026_11' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	update_option( 'ccs_emergency_care_removed_2026_11', 1, false );
	$service = ccs_menu_find( 'emergency-care', 'service' );
	if ( ! $service ) {
		return;
	}
	$items = get_posts(
		array(
			'post_type'   => 'nav_menu_item',
			'post_status' => 'any',
			'numberposts' => 20,
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- one-time step.
				array( 'key' => '_menu_item_object_id', 'value' => (string) $service->ID ),
				array( 'key' => '_menu_item_object', 'value' => 'service' ),
			),
		)
	);
	foreach ( $items as $item ) {
		wp_delete_post( $item->ID, true );
	}
	wp_trash_post( $service->ID );
}
add_action( 'init', 'ccs_remove_emergency_care_2026_11', 49 );

/**
 * Even columns in the services menu, once (client, Nov 2026: "these could
 * be even now"). With Emergency care removed, Everyday care has ten
 * services: five and five. Each service's menu entry gets the column for
 * its place in its group.
 */
function ccs_even_services_menu_columns_2026_11() {
	if ( get_option( 'ccs_services_menu_even_2026_11' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	update_option( 'ccs_services_menu_even_2026_11', 1, false );
	foreach ( array_keys( ccs_service_groups() ) as $group ) {
		$in = array();
		foreach ( ccs_service_order() as $slug ) {
			$post = ccs_menu_find( $slug, 'service' );
			if ( $post && ccs_service_group( $slug ) === $group ) {
				$in[] = $post;
			}
		}
		$half = (int) ceil( count( $in ) / 2 );
		foreach ( $in as $n => $post ) {
			$column = ( 'everyday' === $group ? 1 : 3 ) + ( $n >= $half ? 1 : 0 );
			$items  = get_posts(
				array(
					'post_type'   => 'nav_menu_item',
					'post_status' => 'any',
					'numberposts' => 5,
					'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- one-time step.
						array( 'key' => '_menu_item_object_id', 'value' => (string) $post->ID ),
						array( 'key' => '_menu_item_object', 'value' => 'service' ),
					),
				)
			);
			foreach ( $items as $item ) {
				$classes   = array_filter( (array) get_post_meta( $item->ID, '_menu_item_classes', true ) );
				$classes   = array_values( array_filter( $classes, static function ( $c ) { return 0 !== strpos( (string) $c, 'nav-col-' ); } ) );
				$classes[] = 'nav-col-' . $column;
				update_post_meta( $item->ID, '_menu_item_classes', $classes );
			}
		}
	}
}
add_action( 'init', 'ccs_even_services_menu_columns_2026_11', 50 );

/**
 * The services dropdown's feature panel and help row (Nov 2026; structure
 * after mega menus on Dribbble the client asked to be looked at: a feature
 * panel on the left, grouped lists, and a row at the foot offering help; the
 * client chose the photo version and asked for it less messy, so the
 * heading icons, row rules and arrows of the first build were dropped).
 * Added to the menu's markup here because they are not links in the menu
 * itself; hidden in the phone menu.
 *
 * @param string   $html Menu markup.
 * @param stdClass $args Menu arguments.
 * @return string
 */
function ccs_services_menu_extras( $html, $args ) {
	if ( false === strpos( $html, 'nav-mega--services' ) || ! function_exists( 'ccs_copy' ) ) {
		return $html;
	}
	$hub     = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
	$contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	$line    = preg_split( '/(?<=\?)\s+/', ccs_copy( 'services_cta_line' ), 2 );
	$feature = '<li class="nav-feature"><a class="nav-feature__link" href="' . esc_url( $hub ) . '"><span class="nav-feature__title">' . esc_html__( 'Your team. Your time.', 'ccs-wp-theme' ) . ' <em>' . esc_html__( 'Your life.', 'ccs-wp-theme' ) . '</em></span><span class="nav-feature__text">' . esc_html__( 'From everyday help at home to complex and specialist care, across Maidstone and Kent.', 'ccs-wp-theme' ) . '</span><span class="nav-feature__cue">' . esc_html__( 'See all our services', 'ccs-wp-theme' ) . ' <span aria-hidden="true">&rarr;</span></span></a></li>';
	// The question alone, as a link (client, Nov 2026: a shorter line). It was the question, a second sentence and a button.
	// It answers the question with the care finder (client, 25 Nov 2026: "should that not link from here?"); contact if that page is missing.
	$finder  = function_exists( 'ccs_care_finder_url' ) ? ccs_care_finder_url() : '';
	$help    = '<li class="nav-help"><a class="nav-help__link" href="' . esc_url( $finder ? $finder : $contact ) . '">' . esc_html( $line[0] ) . '</a></li>';

	$html = preg_replace( '#(nav-mega--services[^>]*>\s*<a[^>]*>.*?</a>.*?<ul class="sub-menu">)#s', '$1' . $feature, $html, 1 );
	$html = preg_replace( '#(<li[^>]*class="[^"]*nav-secondary)#', $help . '$1', $html, 1 );
	return $html;
}
add_filter( 'wp_nav_menu', 'ccs_services_menu_extras', 10, 2 );

/**
 * A shorter menu name, once (client, Nov 2026): "Younger adults" for
 * "Support for younger adults", which wrapped onto two lines in the
 * dropdown. The page keeps its full title.
 */
function ccs_shorten_younger_adults_label_2026_11() {
	if ( get_option( 'ccs_younger_adults_label_2026_11' ) || ! function_exists( 'ccs_menu_find' ) ) {
		return;
	}
	$service = ccs_menu_find( 'younger-adults-care', 'service' );
	if ( ! $service ) {
		return;
	}
	update_option( 'ccs_younger_adults_label_2026_11', 1, false );
	$items = get_posts(
		array(
			'post_type'   => 'nav_menu_item',
			'post_status' => 'any',
			'numberposts' => 5,
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- one-time step.
				array( 'key' => '_menu_item_object_id', 'value' => (string) $service->ID ),
				array( 'key' => '_menu_item_object', 'value' => 'service' ),
			),
		)
	);
	foreach ( $items as $item ) {
		wp_update_post( array( 'ID' => $item->ID, 'post_title' => __( 'Younger adults', 'ccs-wp-theme' ) ) );
	}
}
add_action( 'init', 'ccs_shorten_younger_adults_label_2026_11', 51 );
