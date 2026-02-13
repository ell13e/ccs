<?php
/**
 * Theme Customizer: Contact, Social, Analytics, CQC, Emergency Banner.
 *
 * Uses WordPress Customizer API with sanitization, postMessage transport
 * where possible, and a preview script for live updates.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Theme_Customizer
 */
class CCS_Theme_Customizer {

	/**
	 * Theme mod keys (for use in templates and analytics class).
	 */
	const PHONE                 = 'ccs_phone';
	const CONTACT_EMAIL         = 'ccs_contact_email';
	const OFFICE_ADDRESS        = 'ccs_contact_address';
	const OFFICE_HOURS          = 'ccs_office_hours';
	const FACEBOOK_URL          = 'ccs_facebook_url';
	const LINKEDIN_URL          = 'ccs_linkedin_url';
	const TWITTER_URL           = 'ccs_twitter_url';
	const GA4_ID                = 'ccs_analytics_ga4_id';
	const FB_PIXEL_ID           = 'ccs_analytics_fb_pixel_id';
	const GADS_ID               = 'ccs_analytics_gads_id';
	const GADS_LABEL            = 'ccs_analytics_gads_label';
	const CQC_REG_NUMBER        = 'ccs_cqc_registration_number';
	const CQC_RATING            = 'ccs_cqc_rating';
	const CQC_REPORT_URL        = 'ccs_cqc_report_url';
	const CQC_URL               = 'ccs_cqc_url';
	const CQC_BADGE_URL         = 'ccs_cqc_badge_url';
	const EMERGENCY_ENABLED     = 'ccs_emergency_banner_enabled';
	const EMERGENCY_TEXT        = 'ccs_emergency_banner';
	const EMERGENCY_LINK        = 'ccs_emergency_banner_link';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_script' ) );
	}

	/**
	 * Register all sections and settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public function register( $wp_customize ) {
		$this->register_contact_section( $wp_customize );
		$this->register_social_section( $wp_customize );
		$this->register_analytics_section( $wp_customize );
		$this->register_cqc_section( $wp_customize );
		$this->register_emergency_section( $wp_customize );
	}

	/**
	 * Contact Information section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function register_contact_section( $wp_customize ) {
		$wp_customize->add_section( 'ccs_contact', array(
			'title'    => __( 'Contact Information', 'ccs-wp-theme' ),
			'priority' => 30,
		) );

		$wp_customize->add_setting( self::PHONE, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '01234 567890',
			'sanitize_callback' => array( $this, 'sanitize_phone' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::PHONE, array(
			'label'   => __( 'Phone number', 'ccs-wp-theme' ),
			'section' => 'ccs_contact',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( self::CONTACT_EMAIL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CONTACT_EMAIL, array(
			'label'   => __( 'Email address', 'ccs-wp-theme' ),
			'section' => 'ccs_contact',
			'type'    => 'email',
		) );

		$wp_customize->add_setting( self::OFFICE_ADDRESS, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::OFFICE_ADDRESS, array(
			'label'   => __( 'Office address', 'ccs-wp-theme' ),
			'section' => 'ccs_contact',
			'type'    => 'textarea',
			'rows'    => 3,
		) );

		$wp_customize->add_setting( self::OFFICE_HOURS, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => 'Mon–Fri 9am–5pm',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::OFFICE_HOURS, array(
			'label'   => __( 'Office hours', 'ccs-wp-theme' ),
			'section' => 'ccs_contact',
			'type'    => 'text',
		) );
	}

	/**
	 * Social Media section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function register_social_section( $wp_customize ) {
		$wp_customize->add_section( 'ccs_social', array(
			'title'    => __( 'Social Media', 'ccs-wp-theme' ),
			'priority' => 40,
		) );

		$wp_customize->add_setting( self::FACEBOOK_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::FACEBOOK_URL, array(
			'label'   => __( 'Facebook URL', 'ccs-wp-theme' ),
			'section' => 'ccs_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( self::LINKEDIN_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::LINKEDIN_URL, array(
			'label'   => __( 'LinkedIn URL', 'ccs-wp-theme' ),
			'section' => 'ccs_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( self::TWITTER_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::TWITTER_URL, array(
			'label'   => __( 'Twitter / X URL', 'ccs-wp-theme' ),
			'section' => 'ccs_social',
			'type'    => 'url',
		) );
	}

	/**
	 * Analytics section (same theme mod keys as CCS_Analytics).
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function register_analytics_section( $wp_customize ) {
		$wp_customize->add_section( 'ccs_analytics', array(
			'title'       => __( 'Analytics', 'ccs-wp-theme' ),
			'description' => __( 'Tracking codes run on the front end only. Enable "Require cookie consent" and call ccsAnalytics.enableTracking() from your consent banner when the user accepts.', 'ccs-wp-theme' ),
			'priority'    => 160,
		) );

		$wp_customize->add_setting( self::GA4_ID, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( self::GA4_ID, array(
			'label'       => __( 'GA4 Measurement ID', 'ccs-wp-theme' ),
			'description' => __( 'e.g. G-XXXXXXXXXX', 'ccs-wp-theme' ),
			'section'     => 'ccs_analytics',
			'type'        => 'text',
			'input_attrs' => array( 'placeholder' => 'G-XXXXXXXXXX' ),
		) );

		$wp_customize->add_setting( self::FB_PIXEL_ID, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( self::FB_PIXEL_ID, array(
			'label'       => __( 'Facebook Pixel ID', 'ccs-wp-theme' ),
			'description' => __( 'Numeric ID from Events Manager.', 'ccs-wp-theme' ),
			'section'     => 'ccs_analytics',
			'type'        => 'text',
			'input_attrs' => array( 'placeholder' => '123456789012345' ),
		) );

		$wp_customize->add_setting( self::GADS_ID, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( self::GADS_ID, array(
			'label'       => __( 'Google Ads Conversion ID', 'ccs-wp-theme' ),
			'description' => __( 'e.g. AW-123456789', 'ccs-wp-theme' ),
			'section'     => 'ccs_analytics',
			'type'        => 'text',
			'input_attrs' => array( 'placeholder' => 'AW-123456789' ),
		) );

		$wp_customize->add_setting( self::GADS_LABEL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( self::GADS_LABEL, array(
			'label'   => __( 'Google Ads Conversion Label', 'ccs-wp-theme' ),
			'section' => 'ccs_analytics',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'ccs_analytics_require_consent', array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => false,
			'sanitize_callback' => function( $value ) {
				return ! empty( $value );
			},
		) );
		$wp_customize->add_control( 'ccs_analytics_require_consent', array(
			'label'       => __( 'Require cookie consent before loading tracking', 'ccs-wp-theme' ),
			'section'     => 'ccs_analytics',
			'type'        => 'checkbox',
		) );

		$wp_customize->add_setting( 'ccs_analytics_thank_you_page', array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ccs_analytics_thank_you_page', array(
			'label'       => __( 'Thank you page (conversion)', 'ccs-wp-theme' ),
			'description' => __( 'Page slug, e.g. thank-you.', 'ccs-wp-theme' ),
			'section'     => 'ccs_analytics',
			'type'        => 'text',
			'input_attrs' => array( 'placeholder' => 'thank-you' ),
		) );
	}

	/**
	 * CQC Information section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function register_cqc_section( $wp_customize ) {
		$wp_customize->add_section( 'ccs_cqc', array(
			'title'    => __( 'CQC Information', 'ccs-wp-theme' ),
			'priority' => 50,
		) );

		$wp_customize->add_setting( self::CQC_REG_NUMBER, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CQC_REG_NUMBER, array(
			'label'   => __( 'CQC Registration Number', 'ccs-wp-theme' ),
			'section' => 'ccs_cqc',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( self::CQC_RATING, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => array( $this, 'sanitize_cqc_rating' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CQC_RATING, array(
			'label'   => __( 'CQC Rating', 'ccs-wp-theme' ),
			'section' => 'ccs_cqc',
			'type'    => 'select',
			'choices' => array(
				''                    => __( '— Select —', 'ccs-wp-theme' ),
				'outstanding'         => __( 'Outstanding', 'ccs-wp-theme' ),
				'good'                 => __( 'Good', 'ccs-wp-theme' ),
				'requires-improvement' => __( 'Requires improvement', 'ccs-wp-theme' ),
				'inadequate'           => __( 'Inadequate', 'ccs-wp-theme' ),
			),
		) );

		$wp_customize->add_setting( self::CQC_REPORT_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CQC_REPORT_URL, array(
			'label'       => __( 'CQC Report URL', 'ccs-wp-theme' ),
			'description' => __( 'Link to your CQC profile or latest report.', 'ccs-wp-theme' ),
			'section'     => 'ccs_cqc',
			'type'        => 'url',
		) );

		// Legacy: ccs_cqc_url used in header for CQC link (keep for backward compatibility; can point to same as report).
		$wp_customize->add_setting( self::CQC_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => 'https://www.cqc.org.uk',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CQC_URL, array(
			'label'   => __( 'CQC profile link (header)', 'ccs-wp-theme' ),
			'section' => 'ccs_cqc',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( self::CQC_BADGE_URL, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::CQC_BADGE_URL, array(
			'label'   => __( 'CQC badge image URL', 'ccs-wp-theme' ),
			'section' => 'ccs_cqc',
			'type'    => 'url',
		) );
	}

	/**
	 * Emergency Banner section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function register_emergency_section( $wp_customize ) {
		$wp_customize->add_section( 'ccs_emergency', array(
			'title'    => __( 'Emergency Banner', 'ccs-wp-theme' ),
			'priority' => 60,
		) );

		$wp_customize->add_setting( self::EMERGENCY_ENABLED, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => false,
			'sanitize_callback' => function( $value ) {
				return ! empty( $value );
			},
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::EMERGENCY_ENABLED, array(
			'label'   => __( 'Show emergency banner', 'ccs-wp-theme' ),
			'section' => 'ccs_emergency',
			'type'    => 'checkbox',
		) );

		$wp_customize->add_setting( self::EMERGENCY_TEXT, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::EMERGENCY_TEXT, array(
			'label'   => __( 'Banner text', 'ccs-wp-theme' ),
			'section' => 'ccs_emergency',
			'type'    => 'textarea',
			'rows'    => 2,
		) );

		$wp_customize->add_setting( self::EMERGENCY_LINK, array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( self::EMERGENCY_LINK, array(
			'label'       => __( 'Banner link URL', 'ccs-wp-theme' ),
			'description' => __( 'Optional. Leave empty to use "Call now" with phone number only.', 'ccs-wp-theme' ),
			'section'     => 'ccs_emergency',
			'type'        => 'url',
		) );
	}

	/**
	 * Sanitize phone: allow digits, spaces, plus, hyphens, parentheses.
	 *
	 * @param string $value Raw value.
	 * @return string Sanitized value.
	 */
	public function sanitize_phone( $value ) {
		$value = sanitize_text_field( $value );
		return preg_replace( '/[^\d\s+\-()]/', '', $value ) ?: $value;
	}

	/**
	 * Sanitize CQC rating: must be one of allowed values.
	 *
	 * @param string $value Raw value.
	 * @return string Sanitized value.
	 */
	public function sanitize_cqc_rating( $value ) {
		$allowed = array( '', 'outstanding', 'good', 'requires-improvement', 'inadequate' );
		return in_array( $value, $allowed, true ) ? $value : '';
	}

	/**
	 * Enqueue preview script and pass list of postMessage setting IDs.
	 */
	public function preview_script() {
		wp_enqueue_script(
			'ccs-customizer-preview',
			THEME_URL . '/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			THEME_VERSION,
			true
		);
	}
}
