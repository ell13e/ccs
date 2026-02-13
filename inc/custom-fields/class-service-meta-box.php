<?php
/**
 * Service post type meta box: basic info, pricing, features, FAQs, SEO.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Service_Meta_Box
 */
class CCS_Service_Meta_Box extends CCS_Meta_Box_Base {

	/**
	 * Section title => field IDs for ordered rendering.
	 *
	 * @var array
	 */
	private static $sections = array(
		'Basic Info'  => array(
			'service_icon',
			'service_short_description',
			'service_setup_time',
			'service_urgent',
		),
		'Pricing'     => array(
			'service_price_from',
			'service_price_to',
			'service_typical_hours',
			'service_funding_options',
		),
		'Features'    => array(
			'service_features',
		),
		'FAQs'        => array(
			'service_faqs',
		),
		'SEO'         => array(
			'service_seo_title',
			'service_meta_description',
		),
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'ccs_service_details',
			__( 'Service Details', 'ccs-wp-theme' ),
			array( 'service' ),
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
				'id'          => 'service_icon',
				'type'        => 'text',
				'label'       => __( 'Icon (Dashicon class)', 'ccs-wp-theme' ),
				'description' => __( 'e.g. dashicons-heart or dashicons-admin-home', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'service_short_description',
				'type'        => 'textarea',
				'label'       => __( 'Short description', 'ccs-wp-theme' ),
				'description' => __( 'Maximum 200 characters.', 'ccs-wp-theme' ),
				'maxlength'   => 200,
				'rows'        => 3,
			),
			array(
				'id'          => 'service_setup_time',
				'type'        => 'text',
				'label'       => __( 'Setup time', 'ccs-wp-theme' ),
				'description' => __( 'e.g. Usually 24–48 hours', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'service_urgent',
				'type'        => 'checkbox',
				'label'       => __( 'Urgent service', 'ccs-wp-theme' ),
				'description' => __( 'If checked, the service page shows a "Call now" CTA instead of "Get in touch". Use for hospital discharge, urgent care, etc.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'service_price_from',
				'type'        => 'number',
				'label'       => __( 'Price from (£)', 'ccs-wp-theme' ),
				'min'         => 0,
				'step'        => '0.01',
			),
			array(
				'id'          => 'service_price_to',
				'type'        => 'number',
				'label'       => __( 'Price to (£)', 'ccs-wp-theme' ),
				'min'         => 0,
				'step'        => '0.01',
			),
			array(
				'id'          => 'service_typical_hours',
				'type'        => 'text',
				'label'       => __( 'Typical hours', 'ccs-wp-theme' ),
				'description' => __( 'e.g. 1–2 hours per visit', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'service_funding_options',
				'type'        => 'textarea',
				'label'       => __( 'Funding options', 'ccs-wp-theme' ),
				'description' => __( 'Describe funding or payment options.', 'ccs-wp-theme' ),
				'rows'        => 4,
			),
			array(
				'id'          => 'service_features',
				'type'        => 'repeater',
				'label'       => __( 'Features', 'ccs-wp-theme' ),
				'add_button'  => __( 'Add feature', 'ccs-wp-theme' ),
				'sub_fields'  => array(
					array(
						'id'    => 'feature_text',
						'type'  => 'text',
						'label' => __( 'Feature text', 'ccs-wp-theme' ),
					),
					array(
						'id'    => 'feature_icon',
						'type'  => 'text',
						'label' => __( 'Icon (Dashicon class)', 'ccs-wp-theme' ),
					),
				),
			),
			array(
				'id'          => 'service_faqs',
				'type'        => 'repeater',
				'label'       => __( 'FAQs', 'ccs-wp-theme' ),
				'add_button'  => __( 'Add FAQ', 'ccs-wp-theme' ),
				'sub_fields'  => array(
					array(
						'id'    => 'question',
						'type'  => 'text',
						'label' => __( 'Question', 'ccs-wp-theme' ),
					),
					array(
						'id'    => 'answer',
						'type'  => 'textarea',
						'label' => __( 'Answer', 'ccs-wp-theme' ),
					),
				),
			),
			array(
				'id'          => 'service_seo_title',
				'type'        => 'text',
				'label'       => __( 'SEO title', 'ccs-wp-theme' ),
				'description' => __( 'Override the page title for search results.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'service_meta_description',
				'type'        => 'textarea',
				'label'       => __( 'Meta description', 'ccs-wp-theme' ),
				'description' => __( 'Maximum 160 characters. Shown in search results.', 'ccs-wp-theme' ),
				'maxlength'   => 160,
				'rows'        => 2,
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
