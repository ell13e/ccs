<?php
/**
 * Centralized error handling: logging, exceptions, form validation, API errors.
 * Logs to wp-content/debug.log when WP_DEBUG_LOG is enabled; respects WP_DEBUG for verbosity.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Error_Handler
 */
class CCS_Error_Handler {

	const LEVEL_ERROR   = 'ERROR';
	const LEVEL_WARNING = 'WARNING';
	const LEVEL_INFO    = 'INFO';

	/** Transient key for recent errors (admin notification). */
	const TRANSIENT_RECENT_ERRORS = 'ccs_recent_errors';

	/** Max entries to keep for admin notice. */
	const RECENT_ERRORS_MAX = 10;

	/** User-facing generic message when not in debug mode. */
	const USER_MESSAGE_GENERIC = 'Something went wrong. Please try again later.';

	/**
	 * Whether logging is enabled (WP_DEBUG_LOG or custom).
	 *
	 * @var bool
	 */
	private $logging_enabled;

	/**
	 * Constructor: register shutdown and exception handling.
	 */
	public function __construct() {
		$this->logging_enabled = ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG )
			|| ( defined( 'CCS_FORCE_ERROR_LOG' ) && CCS_FORCE_ERROR_LOG );

		add_action( 'wp_ajax_ccs_log_client_error', array( $this, 'ajax_log_client_error' ) );
		add_action( 'wp_ajax_nopriv_ccs_log_client_error', array( $this, 'ajax_log_client_error' ) );
	}

	// -------------------------------------------------------------------------
	// 1. ERROR LOGGING (timestamp, context, levels)
	// -------------------------------------------------------------------------

	/**
	 * Log an error with optional context.
	 *
	 * @param string $message Human-readable message.
	 * @param array  $context Optional. 'file', 'line', 'function', or any key-value data.
	 * @return void
	 */
	public function log_error( $message, array $context = array() ) {
		$this->log( self::LEVEL_ERROR, $message, $context );
		$this->record_recent_error( self::LEVEL_ERROR, $message, $context );
	}

	/**
	 * Log a warning with optional context.
	 *
	 * @param string $message Human-readable message.
	 * @param array  $context Optional. 'file', 'line', 'function', or any key-value data.
	 * @return void
	 */
	public function log_warning( $message, array $context = array() ) {
		$this->log( self::LEVEL_WARNING, $message, $context );
	}

	/**
	 * Log an info message with optional context.
	 *
	 * @param string $message Human-readable message.
	 * @param array  $context Optional. 'file', 'line', 'function', or any key-value data.
	 * @return void
	 */
	public function log_info( $message, array $context = array() ) {
		$this->log( self::LEVEL_INFO, $message, $context );
	}

	/**
	 * Write a single log entry with timestamp and context.
	 *
	 * @param string $level   LEVEL_ERROR, LEVEL_WARNING, or LEVEL_INFO.
	 * @param string $message Log message.
	 * @param array  $context Optional context; if missing, backtrace is used for file/line/function.
	 * @return void
	 */
	private function log( $level, $message, array $context = array() ) {
		if ( ! $this->logging_enabled ) {
			return;
		}

		$context = $this->normalize_context( $context );
		$timestamp = gmdate( 'Y-m-d H:i:s' );
		$prefix = sprintf( '[%s] [%s]', $timestamp, $level );

		$location = '';
		if ( isset( $context['file'], $context['line'] ) ) {
			$location = sprintf( ' %s:%d', $context['file'], $context['line'] );
		}
		if ( ! empty( $context['function'] ) ) {
			$location .= ' in ' . $context['function'] . '()';
		}
		if ( $location !== '' ) {
			$prefix .= $location;
		}

		$line = $prefix . ' ' . $message;

		$extra = array_diff_key( $context, array_flip( array( 'file', 'line', 'function' ) ) );
		if ( ! empty( $extra ) ) {
			$line .= ' | ' . wp_json_encode( $extra );
		}

		error_log( $line );
	}

	/**
	 * Merge caller context with optional file/line/function from backtrace.
	 *
	 * @param array $context Context passed by caller.
	 * @return array
	 */
	private function normalize_context( array $context ) {
		if ( isset( $context['file'], $context['line'] ) ) {
			return $context;
		}
		$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 );
		// Caller of log_* is at index 3 (normalize_context -> log -> log_error -> caller).
		$idx = 3;
		if ( isset( $trace[ $idx ]['file'] ) ) {
			$context['file'] = $trace[ $idx ]['file'];
		}
		if ( isset( $trace[ $idx ]['line'] ) ) {
			$context['line'] = $trace[ $idx ]['line'];
		}
		if ( isset( $trace[ $idx ]['function'] ) ) {
			$context['function'] = $trace[ $idx ]['function'];
		}
		return $context;
	}

	/**
	 * Store recent errors for admin notification (errors only, not warnings/info).
	 *
	 * @param string $level   Level.
	 * @param string $message Message.
	 * @param array  $context Context.
	 * @return void
	 */
	private function record_recent_error( $level, $message, array $context ) {
		$list = get_transient( self::TRANSIENT_RECENT_ERRORS );
		if ( ! is_array( $list ) ) {
			$list = array();
		}
		$list[] = array(
			'time'    => time(),
			'level'   => $level,
			'message' => $message,
			'context' => $context,
		);
		$list = array_slice( $list, -self::RECENT_ERRORS_MAX, self::RECENT_ERRORS_MAX, true );
		set_transient( self::TRANSIENT_RECENT_ERRORS, $list, DAY_IN_SECONDS );
	}

	// -------------------------------------------------------------------------
	// 2. EXCEPTION HANDLING (try/catch, graceful degradation)
	// -------------------------------------------------------------------------

	/**
	 * Handle an exception: log it and return a user-friendly message.
	 *
	 * @param Exception|Throwable $exception The thrown exception.
	 * @param array               $context  Optional extra context for the log.
	 * @return string User-safe message to display (detailed in debug mode).
	 */
	public function handle_exception( $exception, array $context = array() ) {
		$context['file']     = $exception->getFile();
		$context['line']     = $exception->getLine();
		$context['function'] = '';
		$context['trace']    = $exception->getTraceAsString();

		$this->log_error(
			$exception->getMessage(),
			$context
		);

		if ( $this->is_debug() ) {
			return sprintf(
				/* translators: 1: message, 2: file, 3: line */
				__( 'Error: %1$s (in %2$s on line %3$d)', 'ccs-wp-theme' ),
				$exception->getMessage(),
				$exception->getFile(),
				$exception->getLine()
			);
		}

		return self::USER_MESSAGE_GENERIC;
	}

	/**
	 * Run a callable inside try/catch; on exception log and return fallback value.
	 *
	 * @param callable    $fn         Code to run (e.g. function() { return risky_thing(); }).
	 * @param mixed       $fallback   Value to return on exception.
	 * @param string|null $log_message Optional override for log message.
	 * @return mixed Result of $fn() or $fallback on exception.
	 */
	public function run_safely( callable $fn, $fallback = null, $log_message = null ) {
		try {
			return $fn();
		} catch ( Exception $e ) {
			$msg = $log_message !== null ? $log_message : $e->getMessage();
			$this->handle_exception( $e, array( 'message_override' => $msg ) );
			return $fallback;
		} catch ( Throwable $e ) {
			$msg = $log_message !== null ? $log_message : $e->getMessage();
			$this->handle_exception( $e, array( 'message_override' => $msg ) );
			return $fallback;
		}
	}

	/**
	 * Whether debug mode is on (show detailed errors to user).
	 *
	 * @return bool
	 */
	public function is_debug() {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	// -------------------------------------------------------------------------
	// 3. FORM ERROR HANDLING (validation, specific messages, log failures)
	// -------------------------------------------------------------------------

	/**
	 * Validate input against rules and return field-level errors.
	 *
	 * @param array $data   Submitted data (e.g. $_POST) – keys are field names.
	 * @param array $rules  Rules keyed by field: 'required', 'email', 'max_length' => N, 'min_length' => N, 'regex' => pattern.
	 * @return array Empty if valid; otherwise keys are field names, values are translated error messages.
	 */
	public function validate_form( array $data, array $rules ) {
		$errors = array();

		foreach ( $rules as $field => $field_rules ) {
			$value = isset( $data[ $field ] ) ? $data[ $field ] : '';
			if ( is_string( $value ) ) {
				$value = trim( $value );
			}

			if ( ! empty( $field_rules['required'] ) && $value === '' ) {
				$errors[ $field ] = isset( $field_rules['required_message'] )
					? $field_rules['required_message']
					: __( 'This field is required.', 'ccs-wp-theme' );
				continue;
			}

			// If not required and empty, skip further checks unless 'allow_empty' is false.
			if ( $value === '' && empty( $field_rules['required'] ) ) {
				continue;
			}

			if ( ! empty( $field_rules['email'] ) && $value !== '' && ! is_email( $value ) ) {
				$errors[ $field ] = isset( $field_rules['email_message'] )
					? $field_rules['email_message']
					: __( 'Please enter a valid email address.', 'ccs-wp-theme' );
			}

			if ( isset( $field_rules['max_length'] ) && is_string( $value ) && mb_strlen( $value ) > $field_rules['max_length'] ) {
				$errors[ $field ] = isset( $field_rules['max_length_message'] )
					? $field_rules['max_length_message']
					: sprintf(
						/* translators: %d: max length */
						__( 'Must be no more than %d characters.', 'ccs-wp-theme' ),
						$field_rules['max_length']
					);
			}

			if ( isset( $field_rules['min_length'] ) && is_string( $value ) && mb_strlen( $value ) < $field_rules['min_length'] ) {
				$errors[ $field ] = isset( $field_rules['min_length_message'] )
					? $field_rules['min_length_message']
					: sprintf(
						/* translators: %d: min length */
						__( 'Must be at least %d characters.', 'ccs-wp-theme' ),
						$field_rules['min_length']
					);
			}

			if ( ! empty( $field_rules['regex'] ) && is_string( $value ) && $value !== '' ) {
				$pattern = $field_rules['regex'];
				if ( @preg_match( $pattern, $value ) !== 1 ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					$errors[ $field ] = isset( $field_rules['regex_message'] )
						? $field_rules['regex_message']
						: __( 'Invalid format.', 'ccs-wp-theme' );
				}
			}
		}

		return $errors;
	}

	/**
	 * Log a failed form submission (e.g. validation or processing failure).
	 *
	 * @param string $form_id Form identifier (e.g. 'enquiry', 'callback').
	 * @param array  $errors  Field errors or single message.
	 * @param array  $context Optional (e.g. IP, user_id, sanitized post keys).
	 * @return void
	 */
	public function log_form_failure( $form_id, $errors, array $context = array() ) {
		$context['form_id'] = $form_id;
		$context['errors']  = is_array( $errors ) ? $errors : array( $errors );
		if ( empty( $context['ip'] ) && isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$context['ip'] = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		$this->log_warning(
			sprintf( 'Form submission failed: %s', $form_id ),
			$context
		);
	}

	/**
	 * Return a single user-facing message from validation errors (first error or combined).
	 *
	 * @param array $errors Field errors from validate_form().
	 * @return string
	 */
	public function format_form_errors_for_user( array $errors ) {
		if ( empty( $errors ) ) {
			return '';
		}
		$first = reset( $errors );
		if ( count( $errors ) === 1 ) {
			return is_string( $first ) ? $first : __( 'Please correct the error below.', 'ccs-wp-theme' );
		}
		return __( 'Please correct the errors below.', 'ccs-wp-theme' );
	}

	// -------------------------------------------------------------------------
	// 4. API ERROR HANDLING (AJAX, external API, HTTP status)
	// -------------------------------------------------------------------------

	/**
	 * Send a standardized JSON error response for AJAX and optionally set HTTP status.
	 *
	 * @param string $message     User-facing message.
	 * @param int    $http_status HTTP status code (default 400).
	 * @param array  $extra       Optional extra keys to include in JSON (e.g. 'code', 'errors').
	 * @return void Exits after wp_send_json.
	 */
	public function send_ajax_error( $message, $http_status = 400, array $extra = array() ) {
		if ( $http_status >= 400 && ! headers_sent() ) {
			status_header( $http_status );
		}
		$body = array_merge( array( 'success' => false, 'message' => $message ), $extra );
		wp_send_json( $body );
	}

	/**
	 * Send a standardized JSON success response.
	 *
	 * @param array $data Data to send (will be wrapped with success => true).
	 * @param int   $http_status HTTP status (default 200).
	 * @return void Exits after wp_send_json.
	 */
	public function send_ajax_success( array $data = array(), $http_status = 200 ) {
		if ( ! headers_sent() ) {
			status_header( $http_status );
		}
		wp_send_json_success( $data );
	}

	/**
	 * Log an external API failure (timeout, non-2xx, etc.).
	 *
	 * @param string $api_name   Identifier (e.g. 'twilio', 'slack').
	 * @param string $message    Short description.
	 * @param array  $context    Optional: 'url', 'status_code', 'response_body', 'timeout'.
	 * @return void
	 */
	public function log_api_failure( $api_name, $message, array $context = array() ) {
		$context['api'] = $api_name;
		$this->log_error( $message, $context );
	}

	/**
	 * Execute a wp_remote_* call and on failure or non-2xx log and optionally return WP_Error.
	 *
	 * @param string $url     Request URL.
	 * @param array  $options wp_remote_get/post options (timeout, etc.).
	 * @param string $method  'GET' or 'POST'.
	 * @param string $api_name Optional name for log context.
	 * @return array|WP_Error Response array or WP_Error on failure.
	 */
	public function safe_remote_request( $url, array $options = array(), $method = 'GET', $api_name = 'external' ) {
		$options = array_merge( array( 'timeout' => 15 ), $options );
		if ( $method === 'POST' ) {
			$response = wp_remote_post( $url, $options );
		} else {
			$response = wp_remote_get( $url, $options );
		}

		if ( is_wp_error( $response ) ) {
			$this->log_api_failure( $api_name, $response->get_error_message(), array(
				'url'     => $url,
				'code'    => $response->get_error_code(),
			) );
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) {
			$this->log_api_failure( $api_name, 'Non-2xx response', array(
				'url'     => $url,
				'status_code' => $code,
				'body'    => wp_remote_retrieve_body( $response ),
			) );
			return new WP_Error( 'http_error', sprintf( 'HTTP %d', $code ), array( 'status' => $code ) );
		}

		return $response;
	}

	/**
	 * AJAX handler: log client-side errors (e.g. JS exceptions) for debugging.
	 */
	public function ajax_log_client_error() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'ccs_log_client_error' ) ) {
			wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 );
			return;
		}
		$message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';
		$stack   = isset( $_POST['stack'] ) ? sanitize_textarea_field( wp_unslash( $_POST['stack'] ) ) : '';
		if ( $message !== '' ) {
			$this->log_error( 'Client error: ' . $message, array(
				'stack' => $stack,
				'url'   => isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '',
			) );
		}
		wp_send_json_success();
	}

	// -------------------------------------------------------------------------
	// 5. DEBUG MODE & ADMIN NOTIFICATION
	// -------------------------------------------------------------------------

	/**
	 * Get recent errors for admin display (e.g. dashboard or notice).
	 *
	 * @return array List of { time, level, message, context }.
	 */
	public function get_recent_errors() {
		$list = get_transient( self::TRANSIENT_RECENT_ERRORS );
		return is_array( $list ) ? $list : array();
	}

	/**
	 * Clear stored recent errors (e.g. after admin dismissed notice).
	 *
	 * @return void
	 */
	public function clear_recent_errors() {
		delete_transient( self::TRANSIENT_RECENT_ERRORS );
	}

	/**
	 * Get a user-friendly message for production (hide technical details).
	 *
	 * @param string $debug_message Message to show when WP_DEBUG is on.
	 * @return string
	 */
	public function get_user_message( $debug_message ) {
		if ( $this->is_debug() ) {
			return $debug_message;
		}
		return self::USER_MESSAGE_GENERIC;
	}
}
