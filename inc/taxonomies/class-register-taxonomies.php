<?php
/**
 * Register custom taxonomies: Service Category, Condition, Location Areas.
 *
 * Instantiate early (e.g. in functions.php) so init hook is registered.
 * Run after post types (init priority 5) so use priority 10 or higher.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Register_Taxonomies
 */
class CCS_Register_Taxonomies {

	/**
	 * Hook registration into init.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ), 10 );
	}

	/**
	 * Register all custom taxonomies.
	 */
	public function register_taxonomies() {
		$this->register_service_category();
		$this->register_condition();
		$this->register_location_areas();
	}

	/**
	 * Service Category – hierarchical (like categories) for Services CPT.
	 */
	private function register_service_category() {
		$labels = array(
			'name'                       => _x( 'Service Categories', 'Taxonomy general name', 'ccs-wp-theme' ),
			'singular_name'              => _x( 'Service Category', 'Taxonomy singular name', 'ccs-wp-theme' ),
			'menu_name'                  => _x( 'Service Categories', 'Admin Menu text', 'ccs-wp-theme' ),
			'all_items'                  => __( 'All Service Categories', 'ccs-wp-theme' ),
			'edit_item'                  => __( 'Edit Service Category', 'ccs-wp-theme' ),
			'view_item'                  => __( 'View Service Category', 'ccs-wp-theme' ),
			'update_item'                => __( 'Update Service Category', 'ccs-wp-theme' ),
			'add_new_item'               => __( 'Add New Service Category', 'ccs-wp-theme' ),
			'new_item_name'              => __( 'New Service Category Name', 'ccs-wp-theme' ),
			'parent_item'                => __( 'Parent Service Category', 'ccs-wp-theme' ),
			'parent_item_colon'          => __( 'Parent Service Category:', 'ccs-wp-theme' ),
			'search_items'               => __( 'Search Service Categories', 'ccs-wp-theme' ),
			'popular_items'              => __( 'Popular Service Categories', 'ccs-wp-theme' ),
			'separate_items_with_commas' => __( 'Separate service categories with commas', 'ccs-wp-theme' ),
			'add_or_remove_items'        => __( 'Add or remove service categories', 'ccs-wp-theme' ),
			'choose_from_most_used'       => __( 'Choose from the most used service categories', 'ccs-wp-theme' ),
			'not_found'                  => __( 'No service categories found.', 'ccs-wp-theme' ),
			'no_terms'                   => __( 'No service categories', 'ccs-wp-theme' ),
			'items_list_navigation'      => __( 'Service categories list navigation', 'ccs-wp-theme' ),
			'items_list'                 => __( 'Service categories list', 'ccs-wp-theme' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'publicly_queryable' => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => 'service-category',
				'with_front'   => false,
				'hierarchical' => true,
			),
			'capabilities'      => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
		);

		register_taxonomy( 'service_category', array( 'service' ), $args );
	}

	/**
	 * Condition – non-hierarchical (like tags) for tagging by medical condition.
	 * Attached to Services; add 'resource' to the post types array when that CPT exists.
	 */
	private function register_condition() {
		$labels = array(
			'name'                       => _x( 'Conditions', 'Taxonomy general name', 'ccs-wp-theme' ),
			'singular_name'              => _x( 'Condition', 'Taxonomy singular name', 'ccs-wp-theme' ),
			'menu_name'                  => _x( 'Conditions', 'Admin Menu text', 'ccs-wp-theme' ),
			'all_items'                  => __( 'All Conditions', 'ccs-wp-theme' ),
			'edit_item'                  => __( 'Edit Condition', 'ccs-wp-theme' ),
			'view_item'                  => __( 'View Condition', 'ccs-wp-theme' ),
			'update_item'                => __( 'Update Condition', 'ccs-wp-theme' ),
			'add_new_item'               => __( 'Add New Condition', 'ccs-wp-theme' ),
			'new_item_name'              => __( 'New Condition Name', 'ccs-wp-theme' ),
			'search_items'               => __( 'Search Conditions', 'ccs-wp-theme' ),
			'popular_items'               => __( 'Popular Conditions', 'ccs-wp-theme' ),
			'separate_items_with_commas'  => __( 'Separate conditions with commas', 'ccs-wp-theme' ),
			'add_or_remove_items'         => __( 'Add or remove conditions', 'ccs-wp-theme' ),
			'choose_from_most_used'       => __( 'Choose from the most used conditions', 'ccs-wp-theme' ),
			'not_found'                   => __( 'No conditions found.', 'ccs-wp-theme' ),
			'no_terms'                    => __( 'No conditions', 'ccs-wp-theme' ),
			'items_list_navigation'       => __( 'Conditions list navigation', 'ccs-wp-theme' ),
			'items_list'                  => __( 'Conditions list', 'ccs-wp-theme' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'publicly_queryable' => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'condition',
				'with_front' => false,
			),
			'capabilities'      => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
		);

		register_taxonomy( 'condition', array( 'service' ), $args );
	}

	/**
	 * Location Areas – hierarchical, for grouping locations by region.
	 */
	private function register_location_areas() {
		$labels = array(
			'name'                       => _x( 'Location Areas', 'Taxonomy general name', 'ccs-wp-theme' ),
			'singular_name'              => _x( 'Location Area', 'Taxonomy singular name', 'ccs-wp-theme' ),
			'menu_name'                  => _x( 'Location Areas', 'Admin Menu text', 'ccs-wp-theme' ),
			'all_items'                  => __( 'All Location Areas', 'ccs-wp-theme' ),
			'edit_item'                  => __( 'Edit Location Area', 'ccs-wp-theme' ),
			'view_item'                  => __( 'View Location Area', 'ccs-wp-theme' ),
			'update_item'                => __( 'Update Location Area', 'ccs-wp-theme' ),
			'add_new_item'               => __( 'Add New Location Area', 'ccs-wp-theme' ),
			'new_item_name'              => __( 'New Location Area Name', 'ccs-wp-theme' ),
			'parent_item'                => __( 'Parent Location Area', 'ccs-wp-theme' ),
			'parent_item_colon'          => __( 'Parent Location Area:', 'ccs-wp-theme' ),
			'search_items'               => __( 'Search Location Areas', 'ccs-wp-theme' ),
			'popular_items'               => __( 'Popular Location Areas', 'ccs-wp-theme' ),
			'separate_items_with_commas'  => __( 'Separate location areas with commas', 'ccs-wp-theme' ),
			'add_or_remove_items'         => __( 'Add or remove location areas', 'ccs-wp-theme' ),
			'choose_from_most_used'       => __( 'Choose from the most used location areas', 'ccs-wp-theme' ),
			'not_found'                   => __( 'No location areas found.', 'ccs-wp-theme' ),
			'no_terms'                    => __( 'No location areas', 'ccs-wp-theme' ),
			'items_list_navigation'       => __( 'Location areas list navigation', 'ccs-wp-theme' ),
			'items_list'                  => __( 'Location areas list', 'ccs-wp-theme' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'publicly_queryable' => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => 'location-area',
				'with_front'   => false,
				'hierarchical' => true,
			),
			'capabilities'      => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
		);

		register_taxonomy( 'location_area', array( 'location' ), $args );
	}
}
