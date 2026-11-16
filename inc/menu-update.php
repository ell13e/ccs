<?php
/**
 * The main menu: one definition, used when the theme is installed and by a
 * one-time update on existing sites (Nov 2026).
 *
 * The client found the navigation "not accurate": the three service pages,
 * the area pages and the Costs and funding page were not in it, "Getting
 * started" (how care begins) sat under About us, and "Home" took a place the
 * logo already does. The new menu:
 *
 *   Our services  > Domiciliary care, Respite care, Complex care, Costs and
 *                   funding, Getting started
 *   Areas         > every area page, Maidstone first
 *   About us      > Who you'll meet, CQC and our care
 *   Resources     > Care guides, FAQs, Referral information, News and updates
 *   Careers
 *   Contact us
 *
 * Six top-level items, the same count as before (client: "the navigation
 * menu is too long" when Costs had its own place). Labels are sentence case
 * (CCS/CLAUDE.md rule 17). The update only rebuilds
 * a menu that is still exactly the one the theme first created, so a menu
 * the client has edited in Appearance > Menus is never overwritten.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The menu, top level first. Each entry: label, what it links to (a page or
 * service by slug, or the areas archive), and optional children.
 *
 * @return array<int, array<string, mixed>>
 */
function ccs_primary_menu_spec() {
	return apply_filters(
		'ccs_primary_menu_spec',
		array(
			array(
				'label'    => __( 'Our services', 'ccs-wp-theme' ),
				'page'     => 'home-care-services-kent',
				'class'    => 'nav-mega nav-mega--services',
				'children' => array(
					array( 'label' => __( 'Domiciliary care', 'ccs-wp-theme' ), 'service' => 'domiciliary-care', 'icon' => 'home', 'desc' => __( 'Everyday help at home', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'Respite care', 'ccs-wp-theme' ), 'service' => 'respite-care', 'icon' => 'cup', 'desc' => __( 'A break for family carers', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'Complex care', 'ccs-wp-theme' ), 'service' => 'complex-care', 'icon' => 'pulse', 'desc' => __( 'Clinical care, day and night', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'Costs and funding', 'ccs-wp-theme' ), 'page' => 'costs-and-funding', 'desc' => __( 'Our rates and ways to pay', 'ccs-wp-theme' ), 'class' => 'nav-secondary' ),
					array( 'label' => __( 'Getting started', 'ccs-wp-theme' ), 'page' => 'getting-started', 'desc' => __( 'How care begins', 'ccs-wp-theme' ), 'class' => 'nav-secondary' ),
				),
			),
			array(
				'label'    => __( 'Areas', 'ccs-wp-theme' ),
				'archive'  => 'location',
				'class'    => 'nav-mega nav-mega--areas',
				'children' => 'locations',
			),
			array(
				'label'    => __( 'About us', 'ccs-wp-theme' ),
				'page'     => 'about-home-care-maidstone',
				'children' => array(
					array( 'label' => __( 'Who you’ll meet', 'ccs-wp-theme' ), 'page' => 'who-youll-meet', 'desc' => __( 'The team behind your care', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'CQC and our care', 'ccs-wp-theme' ), 'page' => 'cqc-and-our-care', 'desc' => __( 'Our rating and inspection report', 'ccs-wp-theme' ) ),
				),
			),
			array(
				'label'    => __( 'Resources', 'ccs-wp-theme' ),
				'page'     => 'resources',
				'children' => array(
					array( 'label' => __( 'Care guides', 'ccs-wp-theme' ), 'page' => 'care-guides', 'desc' => __( 'Practical guides for families', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'FAQs', 'ccs-wp-theme' ), 'page' => 'faqs', 'desc' => __( 'Answers to common questions', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'Referral information', 'ccs-wp-theme' ), 'page' => 'referral-information', 'desc' => __( 'For professionals referring to us', 'ccs-wp-theme' ) ),
					array( 'label' => __( 'News and updates', 'ccs-wp-theme' ), 'page' => 'news-and-updates', 'desc' => __( 'Articles and advice', 'ccs-wp-theme' ) ),
				),
			),
			array(
				'label' => __( 'Careers', 'ccs-wp-theme' ),
				'page'  => 'careers',
			),
			array(
				'label' => __( 'Contact us', 'ccs-wp-theme' ),
				'page'  => 'contact-us',
			),
		)
	);
}

/**
 * A published post of a type by slug, searching every level (pages here sit
 * under parents such as Resources).
 *
 * @param string $slug      Post name.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function ccs_menu_find( $slug, $post_type = 'page' ) {
	$found = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		)
	);
	return $found ? $found[0] : null;
}

/**
 * Add one spec entry to a menu. Returns the new item's ID, or 0 when what it
 * links to does not exist on this site (then it is simply left out).
 *
 * @param int   $menu_id  Nav menu term ID.
 * @param array $entry    Spec entry.
 * @param int   $parent   Parent menu item ID.
 * @param int   $position Menu order.
 * @return int
 */
function ccs_menu_add_entry( $menu_id, $entry, $parent, $position ) {
	$classes = isset( $entry['class'] ) ? $entry['class'] : '';
	if ( ! empty( $entry['icon'] ) ) {
		$classes = trim( $classes . ' nav-icon-' . sanitize_html_class( $entry['icon'] ) );
	}
	$item = array(
		'menu-item-title'       => $entry['label'],
		'menu-item-status'      => 'publish',
		'menu-item-position'    => $position,
		'menu-item-parent-id'   => $parent,
		'menu-item-description' => isset( $entry['desc'] ) ? $entry['desc'] : '',
		'menu-item-classes'     => $classes,
	);

	if ( ! empty( $entry['archive'] ) ) {
		if ( ! get_post_type_archive_link( $entry['archive'] ) ) {
			return 0;
		}
		$item['menu-item-type']   = 'post_type_archive';
		$item['menu-item-object'] = $entry['archive'];
	} else {
		$type = ! empty( $entry['service'] ) ? 'service' : ( ! empty( $entry['location'] ) ? 'location' : 'page' );
		$post = ccs_menu_find( $entry[ $type ], $type );
		if ( ! $post ) {
			return 0;
		}
		$item['menu-item-type']      = 'post_type';
		$item['menu-item-object']    = $type;
		$item['menu-item-object-id'] = $post->ID;
	}

	$id = wp_update_nav_menu_item( $menu_id, 0, $item );
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Fill an empty menu from ccs_primary_menu_spec().
 *
 * @param int $menu_id Nav menu term ID.
 */
function ccs_build_primary_menu( $menu_id ) {
	if ( ! $menu_id ) {
		return;
	}
	$position = 0;
	foreach ( ccs_primary_menu_spec() as $entry ) {
		$parent = ccs_menu_add_entry( $menu_id, $entry, 0, ++$position );
		if ( ! $parent || empty( $entry['children'] ) ) {
			continue;
		}
		$children = $entry['children'];
		if ( 'locations' === $children ) {
			$children = array();
			$towns    = get_posts(
				array(
					'post_type'      => 'location',
					'post_status'    => 'publish',
					'posts_per_page' => 50,
				)
			);
			// Maidstone first (the office is there), then by town name.
			usort(
				$towns,
				static function ( $a, $b ) {
					if ( 'maidstone' === $a->post_name ) {
						return -1;
					}
					if ( 'maidstone' === $b->post_name ) {
						return 1;
					}
					$an = get_post_meta( $a->ID, 'location_town', true ) ?: $a->post_title;
					$bn = get_post_meta( $b->ID, 'location_town', true ) ?: $b->post_title;
					return strcasecmp( $an, $bn );
				}
			);
			foreach ( $towns as $town ) {
				$name       = get_post_meta( $town->ID, 'location_town', true );
				$children[] = array(
					'label'    => $name ? $name : get_the_title( $town ),
					'location' => $town->post_name,
					// Town names only: a service line under each of twelve
					// towns made the panel cluttered (client, Nov 2026). The
					// area page and the areas hub say what each area gets.
					'desc'     => '',
				);
			}
		}
		foreach ( $children as $child ) {
			ccs_menu_add_entry( $menu_id, $child, $parent, ++$position );
		}
	}
}

/**
 * One-time update of an existing site's main menu. Rebuilds it only when it
 * still holds exactly the thirteen pages the theme first put in it (Home, Our
 * Services, About Us and its three, Resources and its four, Careers, Contact
 * Us); an edited menu is left for the client to change by hand.
 */
function ccs_update_primary_menu_2026_11() {
	if ( get_option( 'ccs_menu_update_2026_11' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_menu_update_2026_11', '1', '', 'no' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
	if ( ! $menu_id || ! is_array( $items ) ) {
		return;
	}

	$old_slugs = array( 'home', 'home-care-services-kent', 'about-home-care-maidstone', 'who-youll-meet', 'cqc-and-our-care', 'getting-started', 'resources', 'care-guides', 'faqs', 'referral-information', 'news-and-updates', 'careers', 'contact-us' );
	$old_ids   = array();
	foreach ( $old_slugs as $slug ) {
		$page = ccs_menu_find( $slug );
		if ( $page ) {
			$old_ids[] = (int) $page->ID;
		}
	}
	// "Home" may be the front page or a custom link to the site root.
	$current_ids = array();
	foreach ( $items as $item ) {
		if ( 'custom' === $item->type && untrailingslashit( $item->url ) === untrailingslashit( home_url( '/' ) ) ) {
			$front = (int) get_option( 'page_on_front' );
			$current_ids[] = $front ? $front : -1;
			continue;
		}
		$current_ids[] = (int) $item->object_id;
	}
	sort( $old_ids );
	sort( $current_ids );
	if ( $old_ids !== $current_ids ) {
		return;
	}

	foreach ( $items as $item ) {
		wp_delete_post( (int) $item->ID, true );
	}
	ccs_build_primary_menu( $menu_id );
}
add_action( 'init', 'ccs_update_primary_menu_2026_11', 40 );

/**
 * Add newly created area pages under the main menu's Areas item, in the
 * order the menu builder uses (Maidstone first, then alphabetical). Does
 * nothing when the menu has no Areas item (an edited menu is left alone).
 *
 * @param int[] $location_ids New location post IDs.
 */
function ccs_add_new_areas_to_menu( $location_ids ) {
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
	if ( ! $menu_id || ! is_array( $items ) ) {
		return;
	}
	$parent = 0;
	foreach ( $items as $item ) {
		if ( 'post_type_archive' === $item->type && 'location' === $item->object && ! $item->menu_item_parent ) {
			$parent = (int) $item->ID;
		}
	}
	if ( ! $parent ) {
		return;
	}
	// Rebuild just the Areas children, in order, so old and new sit together.
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent === $parent ) {
			wp_delete_post( (int) $item->ID, true );
		}
	}
	$towns = get_posts(
		array(
			'post_type'      => 'location',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	usort(
		$towns,
		static function ( $a, $b ) {
			$an = get_post_meta( $a->ID, 'location_town', true ) ?: $a->post_title;
			$bn = get_post_meta( $b->ID, 'location_town', true ) ?: $b->post_title;
			if ( 'maidstone' === $a->post_name ) {
				return -1;
			}
			if ( 'maidstone' === $b->post_name ) {
				return 1;
			}
			return strcasecmp( $an, $bn );
		}
	);
	$position = 100;
	foreach ( $towns as $town ) {
		$name = get_post_meta( $town->ID, 'location_town', true );
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-description' => '',
				'menu-item-title'     => $name ? $name : get_the_title( $town ),
				'menu-item-object'    => 'location',
				'menu-item-object-id' => $town->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
				'menu-item-position'  => ++$position,
			)
		);
	}
}

/*
 * Dropdown items show their names only. A version with an icon on the three
 * services and a description line under every item was dropped (client,
 * Nov 2026: "why some icon some not, why so much small text"). The items'
 * descriptions are still stored, unused, in case the client wants them back.
 */

