<?php
/**
 * Theme security: headers and optional security event logging.
 *
 * Does not duplicate rate limiting or input validation (handled in form handlers).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Security
 */
class CCS_Security {

	/**
	 * Hook into WordPress.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'send_security_headers' ), 0 );
		add_action( 'ccs_security_event', array( $this, 'log_security_event' ), 10, 2 );
	}

	/**
	 * Send security-related HTTP headers on front-end responses.
	 *
	 * Runs at template_redirect priority 0 so it runs before cache headers (priority 1).
	 * Skips if headers already sent or in admin.
	 */
	public function send_security_headers() {
		if ( is_admin() || headers_sent() ) {
			return;
		}

		$headers = array(
			'X-Frame-Options'             => 'SAMEORIGIN',
			'X-Content-Type-Options'      => 'nosniff',
			'Referrer-Policy'             => 'strict-origin-when-cross-origin',
			'X-XSS-Protection'            => '1; mode=block', // Legacy; CSP is preferred when used.
			'Content-Security-Policy'     => $this->get_content_security_policy(),
			'Permissions-Policy'         => 'geolocation=(), microphone=(), camera=()',
		);

		/**
		 * Filter security headers before sending.
		 *
		 * @param array $headers Associative array of header name => value.
		 */
		$headers = apply_filters( 'ccs_security_headers', $headers );

		foreach ( $headers as $name => $value ) {
			if ( $value !== '' && $value !== null ) {
				header( sprintf( '%s: %s', $name, $value ), true );
			}
		}
	}

	/**
	 * Build Content-Security-Policy header value.
	 *
	 * Allows same-origin, inline scripts/styles (WordPress and theme patterns).
	 * Filter with ccs_security_headers to override or set to empty to disable CSP.
	 *
	 * @return string
	 */
	private function get_content_security_policy() {
		$directives = array(
			"default-src 'self'",
			"script-src 'self' 'unsafe-inline'", // WP localize and inline scripts
			"style-src 'self' 'unsafe-inline'",
			"img-src 'self' data: https:",
			"font-src 'self' data:",
			"connect-src 'self'",
			"frame-ancestors 'self'",
			"base-uri 'self'",
			"form-action 'self'",
		);
		$value = implode( '; ', $directives );
		return (string) apply_filters( 'ccs_content_security_policy', $value );
	}

	/**
	 * Log a security event (e.g. failed nonce, rate limit, honeypot).
	 *
	 * Only logs when enabled via constant CCS_LOG_SECURITY or filter ccs_log_security_events.
	 * Does not log request bodies or tokens.
	 *
	 * @param string $event   Event type (e.g. 'nonce_failed', 'rate_limit_exceeded', 'honeypot').
	 * @param array  $context Optional. Safe context (e.g. ip, action). Do not pass raw input.
	 */
	public function log_security_event( $event, $context = array() ) {
		if ( ! $this->is_security_logging_enabled() ) {
			return;
		}

		$event = is_string( $event ) ? $event : 'unknown';
		$context = is_array( $context ) ? $context : array();

		// Never log raw POST or tokens.
		$allowed_keys = array( 'ip', 'action', 'form', 'user_id' );
		$safe = array();
		foreach ( $allowed_keys as $key ) {
			if ( isset( $context[ $key ] ) && is_string( $context[ $key ] ) ) {
				$safe[ $key ] = sanitize_text_field( $context[ $key ] );
			}
		}

		$ip = isset( $safe['ip'] ) ? $safe['ip'] : $this->get_client_ip();
		$line = sprintf(
			"[CCS Security] %s ip=%s",
			$event,
			$ip
		);
		if ( ! empty( $safe ) ) {
			$line .= ' ' . wp_json_encode( $safe );
		}

		if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( $line );
		}
	}

	/**
	 * Whether security event logging is enabled.
	 *
	 * @return bool
	 */
	private function is_security_logging_enabled() {
		if ( defined( 'CCS_LOG_SECURITY' ) && CCS_LOG_SECURITY ) {
			return true;
		}
		return (bool) apply_filters( 'ccs_log_security_events', false );
	}

	/**
	 * Get client IP for logging (sanitized, no raw headers).
	 *
	 * @return string
	 */
	private function get_client_ip() {
		$ip = '';
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && is_string( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$parts = array_map( 'trim', explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
			$ip = sanitize_text_field( $parts[0] );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) && is_string( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
		}
		return $ip === '' ? '-' : $ip;
	}
}
