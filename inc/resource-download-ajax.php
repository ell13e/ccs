<?php
/**
 * Resource download: AJAX request handler, email delivery with token link, token-based file serve.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register AJAX actions for resource download request (logged-in and guest).
 */
function ccs_register_resource_download_ajax() {
	add_action( 'wp_ajax_ccs_request_resource_download', 'ccs_handle_request_resource_download' );
	add_action( 'wp_ajax_nopriv_ccs_request_resource_download', 'ccs_handle_request_resource_download' );
}
add_action( 'init', 'ccs_register_resource_download_ajax', 5 );

/**
 * Handle token-based download: serve file when URL has ccs_resource_download=1&token=...
 */
function ccs_handle_resource_download_by_token() {
	if ( ! isset( $_GET['ccs_resource_download'] ) || $_GET['ccs_resource_download'] !== '1' || ! isset( $_GET['token'] ) ) {
		return;
	}
	$token = is_string( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
	if ( $token === '' ) {
		return;
	}
	global $wpdb;
	$table   = $wpdb->prefix . 'ccs_resource_downloads';
	$hash    = hash( 'sha256', $token );
	$now_gmt = gmdate( 'Y-m-d H:i:s' );
	$row     = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, resource_id, token_expires_at FROM $table WHERE token_hash = %s",
			$hash
		),
		ARRAY_A
	);
	if ( ! $row || $row['token_expires_at'] < $now_gmt ) {
		wp_die( esc_html__( 'This download link has expired or is invalid.', 'ccs-wp-theme' ), '', array( 'response' => 404 ) );
	}
	$resource_id = (int) $row['resource_id'];
	$file_id     = (int) get_post_meta( $resource_id, '_ccs_resource_file_id', true );
	if ( ! $file_id ) {
		wp_die( esc_html__( 'This resource is no longer available.', 'ccs-wp-theme' ), '', array( 'response' => 404 ) );
	}
	$file_path = get_attached_file( $file_id );
	if ( ! $file_path || ! is_readable( $file_path ) ) {
		wp_die( esc_html__( 'File not found.', 'ccs-wp-theme' ), '', array( 'response' => 404 ) );
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $table SET download_count = download_count + 1, last_downloaded_at = %s WHERE id = %d",
			$now_gmt,
			(int) $row['id']
		)
	);
	$filename = wp_basename( $file_path );
	header( 'Content-Type: ' . ( wp_check_filetype( $filename )['type'] ?: 'application/octet-stream' ) );
	header( 'Content-Disposition: attachment; filename="' . esc_attr( $filename ) . '"' );
	header( 'Content-Length: ' . (string) filesize( $file_path ) );
	header( 'Cache-Control: private, no-cache' );
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile
	readfile( $file_path );
	exit;
}
add_action( 'template_redirect', 'ccs_handle_resource_download_by_token', 1 );

/**
 * AJAX handler: validate input, insert download request, send email with download link, return JSON.
 */
function ccs_handle_request_resource_download() {
	$nonce_action = 'ccs_resource_download';
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $nonce_action ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'ccs-wp-theme' ) ) );
	}
	$resource_id = isset( $_POST['resource_id'] ) ? absint( $_POST['resource_id'] ) : 0;
	$first_name  = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name   = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$email       = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone       = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$dob         = isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '';
	$consent     = isset( $_POST['consent'] ) && ( $_POST['consent'] === 'true' || $_POST['consent'] === '1' );

	$errors = array();
	if ( ! $resource_id ) {
		$errors['resource'] = __( 'Invalid resource.', 'ccs-wp-theme' );
	}
	if ( trim( $first_name ) === '' ) {
		$errors['first_name'] = __( 'First name is required.', 'ccs-wp-theme' );
	}
	if ( trim( $last_name ) === '' ) {
		$errors['last_name'] = __( 'Last name is required.', 'ccs-wp-theme' );
	}
	if ( $email === '' || ! is_email( $email ) ) {
		$errors['email'] = __( 'Please enter a valid email address.', 'ccs-wp-theme' );
	}
	if ( ! $consent ) {
		$errors['consent'] = __( 'Consent is required.', 'ccs-wp-theme' );
	}
	if ( ! empty( $errors ) ) {
		wp_send_json_error( array( 'errors' => $errors ) );
	}

	$post = get_post( $resource_id );
	if ( ! $post || $post->post_type !== 'ccs_resource' || $post->post_status !== 'publish' ) {
		wp_send_json_error( array( 'code' => 'resource_unavailable', 'message' => __( 'This care guide is not available.', 'ccs-wp-theme' ) ) );
	}
	$file_id = (int) get_post_meta( $resource_id, '_ccs_resource_file_id', true );
	if ( ! $file_id || ! get_attached_file( $file_id ) ) {
		wp_send_json_error( array( 'code' => 'resource_unavailable', 'message' => __( 'This care guide is not available for download.', 'ccs-wp-theme' ) ) );
	}

	$expiry_days = (int) get_post_meta( $resource_id, '_ccs_resource_expiry_days', true );
	if ( $expiry_days < 1 ) {
		$expiry_days = 7;
	}
	$expiry_days = min( 30, $expiry_days );
	$raw_token   = wp_generate_password( 32, false );
	$token_hash  = hash( 'sha256', $raw_token );
	$now_gmt     = gmdate( 'Y-m-d H:i:s' );
	$expires_gmt = gmdate( 'Y-m-d H:i:s', strtotime( "+ {$expiry_days} days" ) );
	$ip          = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$ua          = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

	global $wpdb;
	$table = $wpdb->prefix . 'ccs_resource_downloads';
	$wpdb->insert(
		$table,
		array(
			'resource_id'       => $resource_id,
			'first_name'        => $first_name,
			'last_name'         => $last_name,
			'email'             => $email,
			'phone'             => $phone,
			'date_of_birth'     => $dob !== '' ? $dob : null,
			'consent'           => $consent ? 1 : 0,
			'ip_address'        => $ip,
			'user_agent'        => $ua,
			'downloaded_at'     => $now_gmt,
			'email_sent'        => 0,
			'download_count'    => 0,
			'token_hash'        => $token_hash,
			'token_expires_at'  => $expires_gmt,
		),
		array( '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%s', '%s' )
	);
	$insert_id = $wpdb->insert_id;
	if ( ! $insert_id ) {
		wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'ccs-wp-theme' ) ) );
	}

	$download_url = add_query_arg(
		array(
			'ccs_resource_download' => '1',
			'token'                 => $raw_token,
		),
		home_url( '/' )
	);
	$resource_name = $post->post_title;
	$site_name     = get_bloginfo( 'name', 'display' );
	$subject       = (string) get_post_meta( $resource_id, '_ccs_resource_email_subject', true );
	$body          = (string) get_post_meta( $resource_id, '_ccs_resource_email_body', true );
	if ( $subject === '' ) {
		$subject = __( 'Your {{resource_name}} from {{site_name}}', 'ccs-wp-theme' );
	}
	if ( $body === '' ) {
		$body  = "Hi {{first_name}},\n\n";
		$body .= __( 'Thanks for requesting {{resource_name}}.', 'ccs-wp-theme' ) . "\n\n";
		$body .= __( 'Download:', 'ccs-wp-theme' ) . " {{download_link}}\n\n";
		$body .= __( 'This link expires in {{expiry_days}} days.', 'ccs-wp-theme' );
	}
	$subject = str_replace(
		array( '{{resource_name}}', '{{site_name}}', '{{first_name}}', '{{last_name}}', '{{email}}', '{{download_link}}', '{{expiry_days}}' ),
		array( $resource_name, $site_name, $first_name, $last_name, $email, $download_url, (string) $expiry_days ),
		$subject
	);
	$body = str_replace(
		array( '{{resource_name}}', '{{site_name}}', '{{first_name}}', '{{last_name}}', '{{email}}', '{{download_link}}', '{{expiry_days}}' ),
		array( $resource_name, $site_name, $first_name, $last_name, $email, $download_url, (string) $expiry_days ),
		$body
	);
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	$sent    = wp_mail( $email, $subject, $body, $headers );
	$wpdb->update(
		$table,
		array(
			'email_sent'   => $sent ? 1 : 0,
			'email_sent_at' => $sent ? $now_gmt : null,
		),
		array( 'id' => $insert_id ),
		array( '%d', '%s' ),
		array( '%d' )
	);

	wp_send_json_success( array(
		'message' => __( 'Thanks! Check your email for the download link.', 'ccs-wp-theme' ),
	) );
}
