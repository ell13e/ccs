<?php
/**
 * Register custom post types: Services, Locations, Enquiries, Testimonials.
 *
 * Instantiate early (e.g. in functions.php) so init hook is registered.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Register_Post_Types
 */
class CCS_Register_Post_Types {

	/**
	 * Hook registration into init.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ), 5 );
		add_action( 'admin_menu', array( $this, 'remove_enquiry_add_new' ), 99 );
	}

	/**
	 * Register all custom post types.
	 */
	public function register_post_types() {
		$this->register_services();
		$this->register_locations();
		$this->register_enquiries();
		$this->register_testimonials();
	}

	/**
	 * Services – public, archive, Gutenberg.
	 */
	private function register_services() {
		$labels = array(
			'name'                  => _x( 'Services', 'Post type general name', 'ccs-wp-theme' ),
			'singular_name'         => _x( 'Service', 'Post type singular name', 'ccs-wp-theme' ),
			'menu_name'             => _x( 'Services', 'Admin Menu text', 'ccs-wp-theme' ),
			'add_new'               => __( 'Add New', 'ccs-wp-theme' ),
			'add_new_item'          => __( 'Add New Service', 'ccs-wp-theme' ),
			'edit_item'             => __( 'Edit Service', 'ccs-wp-theme' ),
			'new_item'              => __( 'New Service', 'ccs-wp-theme' ),
			'view_item'             => __( 'View Service', 'ccs-wp-theme' ),
			'view_items'            => __( 'View Services', 'ccs-wp-theme' ),
			'search_items'          => __( 'Search Services', 'ccs-wp-theme' ),
			'not_found'             => __( 'No services found.', 'ccs-wp-theme' ),
			'not_found_in_trash'    => __( 'No services found in Trash.', 'ccs-wp-theme' ),
			'archives'              => __( 'Service archives', 'ccs-wp-theme' ),
			'attributes'            => __( 'Service attributes', 'ccs-wp-theme' ),
			'insert_into_item'      => __( 'Insert into service', 'ccs-wp-theme' ),
			'uploaded_to_this_item' => __( 'Uploaded to this service', 'ccs-wp-theme' ),
			'filter_items_list'     => __( 'Filter services list', 'ccs-wp-theme' ),
			'items_list_navigation' => __( 'Services list navigation', 'ccs-wp-theme' ),
			'items_list'            => __( 'Services list', 'ccs-wp-theme' ),
		);

		register_post_type(
			'service',
			array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable' => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => true,
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => 'services', 'with_front' => false ),
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => false,
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-heart',
				'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			)
		);
	}

	/**
	 * Locations – public, archive at /care-services/.
	 */
	private function register_locations() {
		$labels = array(
			'name'                  => _x( 'Locations', 'Post type general name', 'ccs-wp-theme' ),
			'singular_name'         => _x( 'Location', 'Post type singular name', 'ccs-wp-theme' ),
			'menu_name'             => _x( 'Locations', 'Admin Menu text', 'ccs-wp-theme' ),
			'add_new'               => __( 'Add New', 'ccs-wp-theme' ),
			'add_new_item'          => __( 'Add New Location', 'ccs-wp-theme' ),
			'edit_item'             => __( 'Edit Location', 'ccs-wp-theme' ),
			'new_item'              => __( 'New Location', 'ccs-wp-theme' ),
			'view_item'             => __( 'View Location', 'ccs-wp-theme' ),
			'view_items'            => __( 'View Locations', 'ccs-wp-theme' ),
			'search_items'          => __( 'Search Locations', 'ccs-wp-theme' ),
			'not_found'             => __( 'No locations found.', 'ccs-wp-theme' ),
			'not_found_in_trash'    => __( 'No locations found in Trash.', 'ccs-wp-theme' ),
			'archives'              => __( 'Location archives', 'ccs-wp-theme' ),
			'attributes'            => __( 'Location attributes', 'ccs-wp-theme' ),
			'insert_into_item'      => __( 'Insert into location', 'ccs-wp-theme' ),
			'uploaded_to_this_item' => __( 'Uploaded to this location', 'ccs-wp-theme' ),
			'filter_items_list'     => __( 'Filter locations list', 'ccs-wp-theme' ),
			'items_list_navigation' => __( 'Locations list navigation', 'ccs-wp-theme' ),
			'items_list'            => __( 'Locations list', 'ccs-wp-theme' ),
		);

		register_post_type(
			'location',
			array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable' => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => true,
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => 'care-services', 'with_front' => false ),
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => false,
				'menu_position'       => 21,
				'menu_icon'           => 'dashicons-location',
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
			)
		);
	}

	/**
	 * Enquiries – CRM; UI visible but not public. Title only (use meta for name + date; title can be auto-generated on save).
	 * Add New submenu removed so entries are created only via form/code.
	 */
	private function register_enquiries() {
		$labels = array(
			'name'                  => _x( 'Enquiries', 'Post type general name', 'ccs-wp-theme' ),
			'singular_name'         => _x( 'Enquiry', 'Post type singular name', 'ccs-wp-theme' ),
			'menu_name'             => _x( 'Enquiries', 'Admin Menu text', 'ccs-wp-theme' ),
			'add_new'               => __( 'Add New', 'ccs-wp-theme' ),
			'add_new_item'          => __( 'Add New Enquiry', 'ccs-wp-theme' ),
			'edit_item'             => __( 'Edit Enquiry', 'ccs-wp-theme' ),
			'new_item'              => __( 'New Enquiry', 'ccs-wp-theme' ),
			'view_item'             => __( 'View Enquiry', 'ccs-wp-theme' ),
			'view_items'            => __( 'View Enquiries', 'ccs-wp-theme' ),
			'search_items'          => __( 'Search Enquiries', 'ccs-wp-theme' ),
			'not_found'             => __( 'No enquiries found.', 'ccs-wp-theme' ),
			'not_found_in_trash'    => __( 'No enquiries found in Trash.', 'ccs-wp-theme' ),
			'archives'              => __( 'Enquiry archives', 'ccs-wp-theme' ),
			'attributes'            => __( 'Enquiry attributes', 'ccs-wp-theme' ),
			'filter_items_list'     => __( 'Filter enquiries list', 'ccs-wp-theme' ),
			'items_list_navigation' => __( 'Enquiries list navigation', 'ccs-wp-theme' ),
			'items_list'            => __( 'Enquiries list', 'ccs-wp-theme' ),
		);

		register_post_type(
			'enquiry',
			array(
				'labels'              => $labels,
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => false,
				'menu_position'       => 22,
				'menu_icon'           => 'dashicons-email',
				'supports'            => array( 'title' ),
			)
		);
	}

	/**
	 * Testimonials – backend only; title, editor, thumbnail.
	 */
	private function register_testimonials() {
		$labels = array(
			'name'                  => _x( 'Testimonials', 'Post type general name', 'ccs-wp-theme' ),
			'singular_name'         => _x( 'Testimonial', 'Post type singular name', 'ccs-wp-theme' ),
			'menu_name'             => _x( 'Testimonials', 'Admin Menu text', 'ccs-wp-theme' ),
			'add_new'               => __( 'Add New', 'ccs-wp-theme' ),
			'add_new_item'          => __( 'Add New Testimonial', 'ccs-wp-theme' ),
			'edit_item'             => __( 'Edit Testimonial', 'ccs-wp-theme' ),
			'new_item'              => __( 'New Testimonial', 'ccs-wp-theme' ),
			'view_item'             => __( 'View Testimonial', 'ccs-wp-theme' ),
			'view_items'            => __( 'View Testimonials', 'ccs-wp-theme' ),
			'search_items'          => __( 'Search Testimonials', 'ccs-wp-theme' ),
			'not_found'             => __( 'No testimonials found.', 'ccs-wp-theme' ),
			'not_found_in_trash'    => __( 'No testimonials found in Trash.', 'ccs-wp-theme' ),
			'archives'              => __( 'Testimonial archives', 'ccs-wp-theme' ),
			'attributes'            => __( 'Testimonial attributes', 'ccs-wp-theme' ),
			'insert_into_item'      => __( 'Insert into testimonial', 'ccs-wp-theme' ),
			'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'ccs-wp-theme' ),
			'filter_items_list'     => __( 'Filter testimonials list', 'ccs-wp-theme' ),
			'items_list_navigation' => __( 'Testimonials list navigation', 'ccs-wp-theme' ),
			'items_list'            => __( 'Testimonials list', 'ccs-wp-theme' ),
		);

		register_post_type(
			'testimonial',
			array(
				'labels'              => $labels,
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => true,
				'has_archive'         => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => false,
				'menu_position'       => 23,
				'menu_icon'           => 'dashicons-format-quote',
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
			)
		);
	}

	/**
	 * Remove "Add New" for Enquiries so entries are created only via form/code.
	 */
	public function remove_enquiry_add_new() {
		remove_submenu_page( 'edit.php?post_type=enquiry', 'post-new.php?post_type=enquiry' );
	}
}
