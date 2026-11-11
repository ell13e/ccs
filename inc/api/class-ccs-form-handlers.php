<?php
/**
 * Front-end form handlers: enquiry form, callback request.
 * Nonce, rate limiting, honeypot, sanitization, email, JSON response.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Form_Handlers
 */
class CCS_Form_Handlers {

	/**
	 * Max submissions per IP per hour.
	 */
	const RATE_LIMIT_MAX = 5;

	/**
	 * Rate limit window in seconds.
	 */
	const RATE_LIMIT_WINDOW = 3600;

	/**
	 * Honeypot field name (must be empty).
	 */
	const HONEYPOT_FIELD = '_company';

	/**
	 * Constructor: register AJAX actions.
	 */
	public function __construct() {
		add_action( 'wp_ajax_submit_enquiry', array( $this, 'handle_enquiry' ) );
		add_action( 'wp_ajax_nopriv_submit_enquiry', array( $this, 'handle_enquiry' ) );
		add_action( 'wp_ajax_request_callback', array( $this, 'handle_callback' ) );
		add_action( 'wp_ajax_nopriv_request_callback', array( $this, 'handle_callback' ) );
	}

	/**
	 * Get client IP for rate limiting.
	 *
	 * @return string
	 */
	private function get_client_ip() {
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			return trim( $ips[0] );
		}
		return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	}

	/**
	 * Check rate limit; increment on success. Returns true if allowed.
	 *
	 * @return bool
	 */
	private function rate_limit_check() {
		$ip   = $this->get_client_ip();
		$key  = 'ccs_rate_' . md5( $ip );
		$now  = time();
		$cut  = $now - self::RATE_LIMIT_WINDOW;
		$data = get_transient( $key );
		$data = is_array( $data ) ? $data : array();
		$data = array_values( array_filter( $data, function ( $t ) use ( $cut ) {
			return $t > $cut;
		} ) );
		if ( count( $data ) >= self::RATE_LIMIT_MAX ) {
			return false;
		}
		$data[] = $now;
		set_transient( $key, $data, self::RATE_LIMIT_WINDOW );
		return true;
	}

	/**
	 * Check if honeypot was filled (spam).
	 *
	 * @return bool True if honeypot has value (likely bot).
	 */
	private function is_honeypot_filled() {
		$val = isset( $_POST[ self::HONEYPOT_FIELD ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::HONEYPOT_FIELD ] ) ) : '';
		return $val !== '';
	}

	/**
	 * Verify nonce for action.
	 *
	 * @param string $action Action name.
	 * @return bool
	 */
	private function verify_nonce( $action ) {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		return wp_verify_nonce( $nonce, $action ) !== false;
	}

	/**
	 * Handle enquiry form submission.
	 */
	public function handle_enquiry() {
		if ( $this->is_honeypot_filled() ) {
			wp_send_json_success( array( 'message' => __( 'Thank you. We will be in touch shortly.', 'ccs-wp-theme' ) ) );
			return;
		}
		if ( ! $this->verify_nonce( 'ccs_enquiry_form' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'ccs-wp-theme' ) ) );
			return;
		}
		if ( ! $this->rate_limit_check() ) {
			wp_send_json_error( array( 'message' => __( 'Too many submissions. Please try again later.', 'ccs-wp-theme' ) ) );
			return;
		}

		$name  = isset( $_POST['enquiry_name'] ) ? sanitize_text_field( wp_unslash( $_POST['enquiry_name'] ) ) : '';
		$email = isset( $_POST['enquiry_email'] ) ? sanitize_email( wp_unslash( $_POST['enquiry_email'] ) ) : '';
		$phone = isset( $_POST['enquiry_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['enquiry_phone'] ) ) : '';

		$errors = array();
		if ( trim( $name ) === '' ) {
			$errors[] = __( 'Name is required.', 'ccs-wp-theme' );
		}
		if ( trim( $phone ) === '' ) {
			$errors[] = __( 'Phone is required.', 'ccs-wp-theme' );
		}
		// Email is optional: a name and a phone number are enough to call back.
		if ( trim( $email ) !== '' && ! is_email( $email ) ) {
			$errors[] = __( 'Please enter a valid email address.', 'ccs-wp-theme' );
		}
		$heard_about = function_exists( 'ccs_heard_about_from_post' ) ? ccs_heard_about_from_post( 'enquiry_heard_about' ) : '';
		if ( '' === $heard_about ) {
			$errors[] = __( 'Please tell us how you heard about us.', 'ccs-wp-theme' );
		}

		$is_switching = isset( $_POST['enquiry_source'] ) && 'switching' === sanitize_text_field( wp_unslash( $_POST['enquiry_source'] ) );
		if ( $is_switching ) {
			$timings = function_exists( 'ccs_switch_timing_options' ) ? ccs_switch_timing_options() : array();
			if ( ! isset( $_POST['switch_provider'] ) || '' === trim( sanitize_text_field( wp_unslash( $_POST['switch_provider'] ) ) ) ) {
				$errors[] = __( 'Please tell us who provides your care now.', 'ccs-wp-theme' );
			}
			if ( ! isset( $_POST['switch_timing'] ) || ! isset( $timings[ sanitize_key( wp_unslash( $_POST['switch_timing'] ) ) ] ) ) {
				$errors[] = __( 'Please choose when your current care ends.', 'ccs-wp-theme' );
			}
		}

		if ( ! empty( $errors ) ) {
			wp_send_json_error( array( 'message' => implode( ' ', $errors ) ) );
			return;
		}

		$title = sprintf(
			/* translators: 1: name, 2: date */
			__( '%1$s – %2$s', 'ccs-wp-theme' ),
			$name,
			gmdate( 'Y-m-d H:i' )
		);

		$post_id = wp_insert_post( array(
			'post_type'   => 'enquiry',
			'post_title'  => $title,
			'post_status' => 'private',
			'post_author' => 1,
		), true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'ccs-wp-theme' ) ) );
			return;
		}

		$meta_map = array(
			'enquiry_name'               => 'sanitize_text_field',
			'enquiry_email'              => 'sanitize_email',
			'enquiry_phone'              => 'sanitize_text_field',
			'enquiry_preferred_contact'  => 'sanitize_text_field',
			'enquiry_care_type'          => 'sanitize_text_field',
			'enquiry_conditions'         => 'sanitize_textarea_field',
			'enquiry_urgency'            => 'sanitize_text_field',
			'enquiry_location'           => 'sanitize_text_field',
			'enquiry_message'            => 'sanitize_textarea_field',
			'enquiry_source'             => 'sanitize_text_field',
			'enquiry_landing_page'       => 'sanitize_text_field',
			'enquiry_referrer'           => 'sanitize_text_field',
			'enquiry_utm_source'         => 'sanitize_text_field',
			'enquiry_utm_medium'         => 'sanitize_text_field',
			'enquiry_utm_campaign'       => 'sanitize_text_field',
		);

		foreach ( $meta_map as $key => $callback ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}
			$raw = wp_unslash( $_POST[ $key ] );
			if ( is_string( $raw ) ) {
				$value = call_user_func( $callback, $raw );
			} else {
				$value = sanitize_text_field( $raw );
			}
			update_post_meta( $post_id, $key, $value );
		}

		/*
		 * Switching enquiries (page-templates/template-switch.php) carry two extra
		 * answers. They are written into the message, the field the inbox, the
		 * CSV export and the notification email already show, rather than as new
		 * meta that none of those know about.
		 */
		if ( isset( $_POST['enquiry_source'] ) && 'switching' === sanitize_text_field( wp_unslash( $_POST['enquiry_source'] ) ) ) {
			$switch_lines = array( __( 'Switching from another provider.', 'ccs-wp-theme' ) );
			$provider     = isset( $_POST['switch_provider'] ) ? sanitize_text_field( wp_unslash( $_POST['switch_provider'] ) ) : '';
			$timing       = isset( $_POST['switch_timing'] ) ? sanitize_key( wp_unslash( $_POST['switch_timing'] ) ) : '';
			$timings      = function_exists( 'ccs_switch_timing_options' ) ? ccs_switch_timing_options() : array();

			if ( '' !== $provider ) {
				/* translators: %s: name of the family's current care provider. */
				$switch_lines[] = sprintf( __( 'Current provider: %s', 'ccs-wp-theme' ), $provider );
			}
			if ( isset( $timings[ $timing ] ) ) {
				/* translators: %s: when the family's current care ends. */
				$switch_lines[] = sprintf( __( 'Current care ends: %s', 'ccs-wp-theme' ), $timings[ $timing ] );
			}

			$message = (string) get_post_meta( $post_id, 'enquiry_message', true );
			update_post_meta( $post_id, 'enquiry_message', implode( "\n", $switch_lines ) . ( '' !== $message ? "\n\n" . $message : '' ) );
		}

		update_post_meta( $post_id, 'enquiry_heard_about', $heard_about );
		update_post_meta( $post_id, 'enquiry_status', 'new' );

		do_action( 'ccs_enquiry_created', $post_id );

		wp_send_json_success( array(
			'message' => __( 'Thank you. We have received your enquiry and will be in touch shortly.', 'ccs-wp-theme' ),
		) );
	}

	/**
	 * Handle callback request submission.
	 */
	public function handle_callback() {
		if ( $this->is_honeypot_filled() ) {
			wp_send_json_success( array( 'message' => __( 'Thank you. We will call you shortly.', 'ccs-wp-theme' ) ) );
			return;
		}
		if ( ! $this->verify_nonce( 'ccs_callback_form' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'ccs-wp-theme' ) ) );
			return;
		}
		if ( ! $this->rate_limit_check() ) {
			wp_send_json_error( array( 'message' => __( 'Too many requests. Please try again later.', 'ccs-wp-theme' ) ) );
			return;
		}

		$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$preferred_time = isset( $_POST['preferred_time'] ) ? sanitize_text_field( wp_unslash( $_POST['preferred_time'] ) ) : '';

		$errors = array();
		if ( trim( $name ) === '' ) {
			$errors[] = __( 'Name is required.', 'ccs-wp-theme' );
		}
		if ( trim( $phone ) === '' ) {
			$errors[] = __( 'Phone is required.', 'ccs-wp-theme' );
		}
		$heard_about = function_exists( 'ccs_heard_about_from_post' ) ? ccs_heard_about_from_post( 'enquiry_heard_about' ) : '';
		if ( '' === $heard_about ) {
			$errors[] = __( 'Please tell us how you heard about us.', 'ccs-wp-theme' );
		}
		if ( ! empty( $errors ) ) {
			wp_send_json_error( array( 'message' => implode( ' ', $errors ) ) );
			return;
		}

		$title = sprintf(
			/* translators: 1: name, 2: date */
			__( 'Callback: %1$s – %2$s', 'ccs-wp-theme' ),
			$name,
			gmdate( 'Y-m-d H:i' )
		);

		$post_id = wp_insert_post( array(
			'post_type'   => 'enquiry',
			'post_title'  => $title,
			'post_status' => 'private',
			'post_author' => 1,
		), true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'ccs-wp-theme' ) ) );
			return;
		}

		update_post_meta( $post_id, 'enquiry_name', $name );
		update_post_meta( $post_id, 'enquiry_phone', $phone );
		update_post_meta( $post_id, 'enquiry_preferred_time', $preferred_time );
		update_post_meta( $post_id, 'enquiry_urgency', 'immediate' );
		update_post_meta( $post_id, 'enquiry_status', 'new' );
		update_post_meta( $post_id, 'enquiry_callback_request', '1' );
		update_post_meta( $post_id, 'enquiry_source', 'phone_call' );
		update_post_meta( $post_id, 'enquiry_heard_about', $heard_about );

		do_action( 'ccs_callback_request_created', $post_id );

		wp_send_json_success( array(
			'message' => __( 'Thank you. We will call you back as soon as possible.', 'ccs-wp-theme' ),
		) );
	}

}
