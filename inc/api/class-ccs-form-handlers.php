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
		if ( trim( $email ) === '' ) {
			$errors[] = __( 'Email is required.', 'ccs-wp-theme' );
		} elseif ( ! is_email( $email ) ) {
			$errors[] = __( 'Please enter a valid email address.', 'ccs-wp-theme' );
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

		do_action( 'ccs_callback_request_created', $post_id );

		wp_send_json_success( array(
			'message' => __( 'Thank you. We will call you back as soon as possible.', 'ccs-wp-theme' ),
		) );
	}

}
