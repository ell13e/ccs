<?php
/**
 * Enquiry (CRM) post type meta box: contact, enquiry details, tracking, CRM management.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Enquiry_Meta_Box
 */
class CCS_Enquiry_Meta_Box extends CCS_Meta_Box_Base {

	/**
	 * Section title => field IDs for ordered rendering.
	 *
	 * @var array
	 */
	private static $sections = array(
		'Contact Info'     => array(
			'enquiry_name',
			'enquiry_email',
			'enquiry_phone',
			'enquiry_preferred_contact',
		),
		'Enquiry Details'  => array(
			'enquiry_care_type',
			'enquiry_conditions',
			'enquiry_urgency',
			'enquiry_location',
			'enquiry_message',
		),
		'Tracking'         => array(
			'enquiry_source',
			'enquiry_landing_page',
			'enquiry_referrer',
			'enquiry_utm_source',
			'enquiry_utm_medium',
			'enquiry_utm_campaign',
		),
		'CRM Management'   => array(
			'enquiry_status',
			'enquiry_assigned_to',
			'enquiry_follow_up_date',
			'enquiry_notes',
			'enquiry_communications',
			'enquiry_converted_date',
			'enquiry_contract_value',
		),
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'ccs_enquiry_details',
			__( 'Enquiry Details', 'ccs-wp-theme' ),
			array( 'enquiry' ),
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
			// Contact Info
			array(
				'id'       => 'enquiry_name',
				'type'     => 'text',
				'label'    => __( 'Name', 'ccs-wp-theme' ),
				'required' => true,
			),
			array(
				'id'    => 'enquiry_email',
				'type'  => 'email',
				'label' => __( 'Email', 'ccs-wp-theme' ),
			),
			array(
				'id'       => 'enquiry_phone',
				'type'     => 'text',
				'label'    => __( 'Phone', 'ccs-wp-theme' ),
				'required' => true,
			),
			array(
				'id'       => 'enquiry_preferred_contact',
				'type'     => 'select',
				'label'    => __( 'Preferred contact', 'ccs-wp-theme' ),
				'options'  => array(
					'phone' => __( 'Phone', 'ccs-wp-theme' ),
					'email' => __( 'Email', 'ccs-wp-theme' ),
				),
				'placeholder' => __( '— Select —', 'ccs-wp-theme' ),
			),
			// Enquiry Details
			array(
				'id'          => 'enquiry_care_type',
				'type'        => 'text',
				'label'       => __( 'Care type', 'ccs-wp-theme' ),
				'description' => __( 'e.g. live-in, visiting, respite', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_conditions',
				'type'        => 'textarea',
				'label'       => __( 'Conditions', 'ccs-wp-theme' ),
				'description' => __( 'Relevant medical or care conditions.', 'ccs-wp-theme' ),
				'rows'        => 3,
			),
			array(
				'id'       => 'enquiry_urgency',
				'type'     => 'select',
				'label'    => __( 'Urgency', 'ccs-wp-theme' ),
				'options'  => array(
					'immediate'      => __( 'Immediate', 'ccs-wp-theme' ),
					'this_week'      => __( 'This Week', 'ccs-wp-theme' ),
					'this_month'     => __( 'This Month', 'ccs-wp-theme' ),
					'just_exploring' => __( 'Just Exploring', 'ccs-wp-theme' ),
				),
				'placeholder' => __( '— Select —', 'ccs-wp-theme' ),
			),
			array(
				'id'    => 'enquiry_location',
				'type'  => 'text',
				'label' => __( 'Location', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_message',
				'type'        => 'textarea',
				'label'       => __( 'Message', 'ccs-wp-theme' ),
				'rows'        => 4,
			),
			// Tracking
			array(
				'id'       => 'enquiry_source',
				'type'     => 'select',
				'label'    => __( 'Source', 'ccs-wp-theme' ),
				'options'  => array(
					'website_form'  => __( 'Website Form', 'ccs-wp-theme' ),
					'google_ads'    => __( 'Google Ads', 'ccs-wp-theme' ),
					'facebook_ads'  => __( 'Facebook Ads', 'ccs-wp-theme' ),
					'organic_search' => __( 'Organic Search', 'ccs-wp-theme' ),
					'referral'      => __( 'Referral', 'ccs-wp-theme' ),
					'phone_call'    => __( 'Phone Call', 'ccs-wp-theme' ),
					'email'         => __( 'Email', 'ccs-wp-theme' ),
				),
				'placeholder' => __( '— Select —', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_landing_page',
				'type'        => 'text',
				'label'       => __( 'Landing page', 'ccs-wp-theme' ),
				'description' => __( 'Auto-filled from form submission.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_referrer',
				'type'        => 'text',
				'label'       => __( 'Referrer', 'ccs-wp-theme' ),
				'description' => __( 'Auto-filled from form submission.', 'ccs-wp-theme' ),
			),
			array(
				'id'    => 'enquiry_utm_source',
				'type'  => 'text',
				'label' => __( 'UTM Source', 'ccs-wp-theme' ),
			),
			array(
				'id'    => 'enquiry_utm_medium',
				'type'  => 'text',
				'label' => __( 'UTM Medium', 'ccs-wp-theme' ),
			),
			array(
				'id'    => 'enquiry_utm_campaign',
				'type'  => 'text',
				'label' => __( 'UTM Campaign', 'ccs-wp-theme' ),
			),
			// CRM Management
			array(
				'id'       => 'enquiry_status',
				'type'     => 'select',
				'label'    => __( 'Status', 'ccs-wp-theme' ),
				'options'  => array(
					'new'                 => __( 'New', 'ccs-wp-theme' ),
					'contacted'           => __( 'Contacted', 'ccs-wp-theme' ),
					'assessment_scheduled' => __( 'Assessment Scheduled', 'ccs-wp-theme' ),
					'proposal_sent'       => __( 'Proposal Sent', 'ccs-wp-theme' ),
					'won'                 => __( 'Won', 'ccs-wp-theme' ),
					'lost'                => __( 'Lost', 'ccs-wp-theme' ),
					'not_right_fit'       => __( 'Not Right Fit', 'ccs-wp-theme' ),
				),
				'placeholder' => __( '— Select —', 'ccs-wp-theme' ),
			),
			array(
				'id'     => 'enquiry_assigned_to',
				'type'   => 'user_select',
				'label'  => __( 'Assigned to', 'ccs-wp-theme' ),
				'roles'  => array( 'administrator', 'editor' ),
			),
			array(
				'id'    => 'enquiry_follow_up_date',
				'type'  => 'date',
				'label' => __( 'Follow-up date', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_notes',
				'type'        => 'wysiwyg',
				'label'       => __( 'Notes', 'ccs-wp-theme' ),
				'rows'        => 6,
				'teeny'       => true,
				'media_buttons' => false,
			),
			array(
				'id'          => 'enquiry_communications',
				'type'        => 'repeater',
				'label'       => __( 'Communications', 'ccs-wp-theme' ),
				'add_button'  => __( 'Add communication', 'ccs-wp-theme' ),
				'sub_fields'  => array(
					array(
						'id'    => 'communication_date',
						'type'  => 'date',
						'label' => __( 'Date', 'ccs-wp-theme' ),
					),
					array(
						'id'       => 'communication_type',
						'type'     => 'select',
						'label'    => __( 'Type', 'ccs-wp-theme' ),
						'options'  => array(
							'phone'   => __( 'Phone', 'ccs-wp-theme' ),
							'email'   => __( 'Email', 'ccs-wp-theme' ),
							'meeting' => __( 'Meeting', 'ccs-wp-theme' ),
						),
					),
					array(
						'id'    => 'communication_notes',
						'type'  => 'textarea',
						'label' => __( 'Notes', 'ccs-wp-theme' ),
					),
				),
			),
			array(
				'id'          => 'enquiry_converted_date',
				'type'        => 'date',
				'label'       => __( 'Converted date', 'ccs-wp-theme' ),
				'description' => __( 'Auto-set when status is set to Won.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'enquiry_contract_value',
				'type'        => 'number',
				'label'       => __( 'Contract value (£)', 'ccs-wp-theme' ),
				'min'         => 0,
				'step'        => '0.01',
				'description' => __( 'For Won enquiries.', 'ccs-wp-theme' ),
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
		echo '<div class="ccs-meta-box ccs-meta-box--enquiry">';

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
	 * Save meta box; auto-set converted date when status = Won.
	 *
	 * @param int     $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public function save( $post_id, $post ) {
		parent::save( $post_id, $post );

		if ( ! in_array( $post->post_type, $this->post_types, true ) ) {
			return;
		}

		$status = isset( $_POST['enquiry_status'] ) ? sanitize_text_field( wp_unslash( $_POST['enquiry_status'] ) ) : '';
		if ( $status === 'won' ) {
			$converted = get_post_meta( $post_id, 'enquiry_converted_date', true );
			if ( empty( $converted ) ) {
				update_post_meta( $post_id, 'enquiry_converted_date', gmdate( 'Y-m-d' ) );
			}
		}
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
