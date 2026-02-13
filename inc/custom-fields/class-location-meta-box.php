<?php
/**
 * Location post type meta box: location details, demographics, healthcare, council, support, team.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Location_Meta_Box
 */
class CCS_Location_Meta_Box extends CCS_Meta_Box_Base {

	/**
	 * Section title => field IDs for ordered rendering.
	 *
	 * @var array
	 */
	private static $sections = array(
		'Location Details'      => array(
			'location_town',
			'location_county',
			'location_postcode_area',
			'location_areas_covered',
			'location_latitude',
			'location_longitude',
		),
		'Demographics'          => array(
			'location_population_65_plus',
			'location_families_supported',
		),
		'Healthcare Partners'   => array(
			'location_local_hospitals',
			'location_local_gp_practices',
		),
		'Council/CHC Contacts'  => array(
			'location_chc_contact',
			'location_council_adult_services',
		),
		'Local Support'         => array(
			'location_local_support_groups',
		),
		'Our Team'              => array(
			'location_team_size',
			'location_coordinator_name',
			'location_coordinator_photo',
		),
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'ccs_location_details',
			__( 'Location Details', 'ccs-wp-theme' ),
			array( 'location' ),
			$this->get_fields(),
			'normal',
			'default'
		);
	}

	/**
	 * Field definitions.
	 *
	 * @return array
	 */
	private function get_fields() {
		return array(
			array(
				'id'    => 'location_town',
				'type'  => 'text',
				'label' => __( 'Town', 'ccs-wp-theme' ),
			),
			array(
				'id'      => 'location_county',
				'type'    => 'text',
				'label'   => __( 'County', 'ccs-wp-theme' ),
				'default' => 'Kent',
			),
			array(
				'id'          => 'location_postcode_area',
				'type'        => 'text',
				'label'       => __( 'Postcode area', 'ccs-wp-theme' ),
				'description' => __( 'e.g. ME, CT, TN', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'location_areas_covered',
				'type'        => 'textarea',
				'label'       => __( 'Areas covered', 'ccs-wp-theme' ),
				'description' => __( 'Districts and villages we cover. One per line.', 'ccs-wp-theme' ),
				'rows'        => 6,
			),
			array(
				'id'    => 'location_latitude',
				'type'  => 'number',
				'label' => __( 'Latitude', 'ccs-wp-theme' ),
				'step'  => 'any',
			),
			array(
				'id'    => 'location_longitude',
				'type'  => 'number',
				'label' => __( 'Longitude', 'ccs-wp-theme' ),
				'step'  => 'any',
			),
			array(
				'id'          => 'location_population_65_plus',
				'type'        => 'number',
				'label'       => __( 'Population 65+', 'ccs-wp-theme' ),
				'min'         => 0,
				'description' => __( 'Local population aged 65 and over (optional).', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'location_families_supported',
				'type'        => 'number',
				'label'       => __( 'Families supported', 'ccs-wp-theme' ),
				'min'         => 0,
				'description' => __( 'Number of families currently supported in this area.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'location_local_hospitals',
				'type'        => 'repeater',
				'label'       => __( 'Local hospitals', 'ccs-wp-theme' ),
				'add_button'  => __( 'Add hospital', 'ccs-wp-theme' ),
				'sub_fields'  => array(
					array(
						'id'    => 'hospital_name',
						'type'  => 'text',
						'label' => __( 'Hospital name', 'ccs-wp-theme' ),
					),
					array(
						'id'    => 'hospital_phone',
						'type'  => 'text',
						'label' => __( 'Phone', 'ccs-wp-theme' ),
					),
					array(
						'id'    => 'hospital_address',
						'type'  => 'textarea',
						'label' => __( 'Address', 'ccs-wp-theme' ),
					),
				),
			),
			array(
				'id'          => 'location_local_gp_practices',
				'type'        => 'textarea',
				'label'       => __( 'Local GP practices', 'ccs-wp-theme' ),
				'description' => __( 'List or describe GP practices in the area.', 'ccs-wp-theme' ),
				'rows'        => 4,
			),
			array(
				'id'          => 'location_chc_contact',
				'type'        => 'textarea',
				'label'       => __( 'CHC contact', 'ccs-wp-theme' ),
				'description' => __( 'Continuing Healthcare / contact phone and email.', 'ccs-wp-theme' ),
				'rows'        => 3,
			),
			array(
				'id'          => 'location_council_adult_services',
				'type'        => 'textarea',
				'label'       => __( 'Council adult services', 'ccs-wp-theme' ),
				'description' => __( 'Local authority adult social care contact details.', 'ccs-wp-theme' ),
				'rows'        => 3,
			),
			array(
				'id'          => 'location_local_support_groups',
				'type'        => 'repeater',
				'label'       => __( 'Local support groups', 'ccs-wp-theme' ),
				'add_button'  => __( 'Add support group', 'ccs-wp-theme' ),
				'sub_fields'  => array(
					array(
						'id'    => 'group_name',
						'type'  => 'text',
						'label' => __( 'Group name', 'ccs-wp-theme' ),
					),
					array(
						'id'    => 'group_contact',
						'type'  => 'textarea',
						'label' => __( 'Contact details', 'ccs-wp-theme' ),
					),
				),
			),
			array(
				'id'          => 'location_team_size',
				'type'        => 'number',
				'label'       => __( 'Team size', 'ccs-wp-theme' ),
				'min'         => 0,
				'description' => __( 'Number of care staff in this location.', 'ccs-wp-theme' ),
			),
			array(
				'id'    => 'location_coordinator_name',
				'type'  => 'text',
				'label' => __( 'Coordinator name', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'location_coordinator_photo',
				'type'        => 'image',
				'label'       => __( 'Coordinator photo', 'ccs-wp-theme' ),
				'description' => __( 'Profile photo for the location coordinator.', 'ccs-wp-theme' ),
			),
		);
	}

	/**
	 * Output meta box with sections.
	 *
	 * @param \WP_Post $post Current post.
	 */
	public function render( $post ) {
		wp_nonce_field( $this->nonce_action, $this->nonce_name );
		echo '<div class="ccs-meta-box">';

		foreach ( self::$sections as $section_title => $field_ids ) {
			echo '<div class="ccs-meta-box__section">';
			echo '<h3 class="ccs-meta-box__heading">' . esc_html( $section_title ) . '</h3>';

			foreach ( $field_ids as $field_id ) {
				$field = $this->get_field_by_id( $field_id );
				if ( ! $field ) {
					continue;
				}
				$value = get_post_meta( $post->ID, $field['id'], true );
				if ( isset( $field['default'] ) && $value === '' && $value !== 0 ) {
					$value = $field['default'];
				}
				$this->render_field( $field, $value, $post->ID );
			}

			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Get field config by ID.
	 *
	 * @param string $id Field id.
	 * @return array|null Field config or null.
	 */
	private function get_field_by_id( $id ) {
		foreach ( $this->fields as $field ) {
			if ( isset( $field['id'] ) && $field['id'] === $id ) {
				return $field;
			}
		}
		return null;
	}
}
