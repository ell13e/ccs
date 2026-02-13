<?php
/**
 * Email notifications: new enquiry (admin), confirmation (user), urgent (SMS/Slack/on-call).
 * HTML templates with inline CSS, plain text fallback, configurable recipients and headers.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Email_Notifications
 */
class CCS_Email_Notifications {

	/** Theme mod key: admin recipient(s), comma-separated. */
	const SETTING_RECIPIENTS = 'ccs_email_recipients';

	/** Theme mod key: CC addresses, comma-separated. */
	const SETTING_CC = 'ccs_email_cc';

	/** Theme mod key: BCC addresses, comma-separated. */
	const SETTING_BCC = 'ccs_email_bcc';

	/** Theme mod key: From name. */
	const SETTING_FROM_NAME = 'ccs_email_from_name';

	/** Theme mod key: From email. */
	const SETTING_FROM_EMAIL = 'ccs_email_from_email';

	/** Theme mod key: Reply-To address. */
	const SETTING_REPLY_TO = 'ccs_email_reply_to';

	/** Theme mod key: Slack webhook URL for urgent notifications. */
	const SETTING_SLACK_WEBHOOK = 'ccs_email_slack_webhook';

	/** Theme mod key: On-call team email(s), comma-separated (urgent). */
	const SETTING_ONCALL_EMAIL = 'ccs_email_oncall';

	/** Urgency value that triggers immediate/urgent handling. */
	const URGENCY_IMMEDIATE = 'immediate';

	/**
	 * Constructor: hook into enquiry and callback creation.
	 */
	public function __construct() {
		add_action( 'ccs_enquiry_created', array( $this, 'on_enquiry_created' ), 10, 1 );
		add_action( 'ccs_callback_request_created', array( $this, 'on_callback_request_created' ), 10, 1 );
	}

	/**
	 * Fired when a standard enquiry form is submitted.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function on_enquiry_created( $post_id ) {
		$this->send_new_enquiry_admin_email( $post_id );
		$this->send_confirmation_email( $post_id );
		if ( $this->is_urgent( $post_id ) ) {
			$this->send_urgent_notifications( $post_id );
		}
	}

	/**
	 * Fired when a callback request is submitted (treated as urgent).
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function on_callback_request_created( $post_id ) {
		$this->send_new_enquiry_admin_email( $post_id, true );
		$this->send_confirmation_email( $post_id, true );
		$this->send_urgent_notifications( $post_id );
	}

	/**
	 * Whether the enquiry has immediate urgency.
	 *
	 * @param int $post_id Enquiry post ID.
	 * @return bool
	 */
	public function is_urgent( $post_id ) {
		$urgency = get_post_meta( $post_id, 'enquiry_urgency', true );
		return ( $urgency === self::URGENCY_IMMEDIATE );
	}

	// -------------------------------------------------------------------------
	// 1. NEW ENQUIRY EMAIL (TO ADMIN)
	// -------------------------------------------------------------------------

	/**
	 * Send new enquiry notification to admin(s).
	 *
	 * @param int  $post_id     Enquiry post ID.
	 * @param bool $is_callback True if this is a callback request.
	 */
	public function send_new_enquiry_admin_email( $post_id, $is_callback = false ) {
		$to = $this->get_recipients();
		if ( empty( $to ) ) {
			$to = array( get_option( 'admin_email' ) );
		}

		$urgency_prefix = ( $this->is_urgent( $post_id ) || $is_callback )
			? __( '[URGENT] ', 'ccs-wp-theme' )
			: '';

		$subject = $urgency_prefix . sprintf(
			/* translators: 1: site name, 2: callback or enquiry */
			__( '[%1$s] New %2$s', 'ccs-wp-theme' ),
			get_bloginfo( 'name' ),
			$is_callback ? __( 'callback request', 'ccs-wp-theme' ) : __( 'care enquiry', 'ccs-wp-theme' )
		);

		$details = $this->get_enquiry_details_for_email( $post_id );
		$edit_url = admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' );

		$html_body = $this->render_template( 'admin-enquiry', array(
			'subject_line' => $subject,
			'details'      => $details,
			'edit_url'     => $edit_url,
			'is_urgent'    => $this->is_urgent( $post_id ) || $is_callback,
			'is_callback'  => $is_callback,
		) );

		$plain_body = $this->get_admin_enquiry_plain_text( $details, $edit_url, $is_callback );

		$this->send_mail( $to, $subject, $html_body, $plain_body, array(
			'cc'  => $this->get_setting_emails( self::SETTING_CC ),
			'bcc' => $this->get_setting_emails( self::SETTING_BCC ),
		) );
	}

	/**
	 * Build enquiry details array for templates.
	 *
	 * @param int $post_id Enquiry post ID.
	 * @return array<string, string>
	 */
	private function get_enquiry_details_for_email( $post_id ) {
		$labels = array(
			'name'       => __( 'Name', 'ccs-wp-theme' ),
			'email'      => __( 'Email', 'ccs-wp-theme' ),
			'phone'      => __( 'Phone', 'ccs-wp-theme' ),
			'care_type'  => __( 'Care type', 'ccs-wp-theme' ),
			'location'   => __( 'Location / area', 'ccs-wp-theme' ),
			'urgency'    => __( 'Urgency', 'ccs-wp-theme' ),
			'message'    => __( 'Message', 'ccs-wp-theme' ),
			'preferred_contact' => __( 'Preferred contact', 'ccs-wp-theme' ),
			'preferred_time'    => __( 'Preferred callback time', 'ccs-wp-theme' ),
		);

		$urgency_labels = array(
			'standard'   => __( 'Standard', 'ccs-wp-theme' ),
			'soon'       => __( 'Within a week', 'ccs-wp-theme' ),
			'immediate'  => __( 'Immediate', 'ccs-wp-theme' ),
		);

		$raw = array(
			'name'       => get_post_meta( $post_id, 'enquiry_name', true ),
			'email'      => get_post_meta( $post_id, 'enquiry_email', true ),
			'phone'      => get_post_meta( $post_id, 'enquiry_phone', true ),
			'care_type'  => get_post_meta( $post_id, 'enquiry_care_type', true ),
			'location'   => get_post_meta( $post_id, 'enquiry_location', true ),
			'urgency'    => get_post_meta( $post_id, 'enquiry_urgency', true ),
			'message'    => get_post_meta( $post_id, 'enquiry_message', true ),
			'preferred_contact' => get_post_meta( $post_id, 'enquiry_preferred_contact', true ),
			'preferred_time'    => get_post_meta( $post_id, 'enquiry_preferred_time', true ),
		);

		$rows = array();
		foreach ( $raw as $key => $value ) {
			$value = is_string( $value ) ? trim( $value ) : '';
			if ( $key === 'urgency' && $value !== '' ) {
				$value = isset( $urgency_labels[ $value ] ) ? $urgency_labels[ $value ] : $value;
			}
			if ( $value === '' && ( $key === 'preferred_contact' || $key === 'preferred_time' ) ) {
				continue;
			}
			$label = isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
			$rows[ $label ] = $value !== '' ? $value : '—';
		}

		return $rows;
	}

	/**
	 * Plain text body for admin enquiry email.
	 *
	 * @param array  $details    Key-value enquiry details.
	 * @param string $edit_url   Admin edit URL.
	 * @param bool   $is_callback Whether this is a callback request.
	 * @return string
	 */
	private function get_admin_enquiry_plain_text( array $details, $edit_url, $is_callback ) {
		$intro = $is_callback
			? __( 'New callback request received.', 'ccs-wp-theme' )
			: __( 'New enquiry received.', 'ccs-wp-theme' );
		$out = $intro . "\n\n";
		foreach ( $details as $label => $value ) {
			$out .= $label . ': ' . $value . "\n";
		}
		$out .= "\n" . __( 'View / edit in admin:', 'ccs-wp-theme' ) . "\n" . $edit_url;
		return $out;
	}

	// -------------------------------------------------------------------------
	// 2. CONFIRMATION EMAIL (TO USER)
	// -------------------------------------------------------------------------

	/**
	 * Send confirmation email to the person who submitted the enquiry.
	 *
	 * @param int  $post_id     Enquiry post ID.
	 * @param bool $is_callback True if this is a callback request.
	 */
	public function send_confirmation_email( $post_id, $is_callback = false ) {
		$email = get_post_meta( $post_id, 'enquiry_email', true );
		if ( ! is_email( $email ) ) {
			return;
		}

		$name = get_post_meta( $post_id, 'enquiry_name', true );
		$subject = sprintf(
			/* translators: %s: site name */
			__( 'We’ve received your enquiry – %s', 'ccs-wp-theme' ),
			get_bloginfo( 'name' )
		);

		$contact_phone   = get_theme_mod( CCS_Theme_Customizer::PHONE, '' );
		$contact_email   = get_theme_mod( CCS_Theme_Customizer::CONTACT_EMAIL, get_option( 'admin_email' ) );
		$response_time   = $this->get_expected_response_time();
		$what_happens    = $is_callback
			? __( 'A member of our team will call you back as soon as possible during office hours.', 'ccs-wp-theme' )
			: __( 'A member of our team will review your enquiry and get back to you within the expected time below.', 'ccs-wp-theme' );

		$html_body = $this->render_template( 'confirmation', array(
			'name'          => $name,
			'is_callback'   => $is_callback,
			'what_happens'  => $what_happens,
			'contact_phone' => $contact_phone,
			'contact_email' => $contact_email,
			'response_time' => $response_time,
			'site_name'     => get_bloginfo( 'name' ),
		) );

		$plain_body = $this->get_confirmation_plain_text( $name, $is_callback, $what_happens, $contact_phone, $contact_email, $response_time );

		$this->send_mail( array( $email ), $subject, $html_body, $plain_body );
	}

	/**
	 * Expected response time string (filterable).
	 *
	 * @return string
	 */
	private function get_expected_response_time() {
		$default = __( '1–2 business days', 'ccs-wp-theme' );
		return (string) apply_filters( 'ccs_email_expected_response_time', $default );
	}

	/**
	 * Plain text for confirmation email.
	 *
	 * @param string $name           Recipient name.
	 * @param bool   $is_callback    Callback or enquiry.
	 * @param string $what_happens   What happens next.
	 * @param string $contact_phone  Contact phone.
	 * @param string $contact_email  Contact email.
	 * @param string $response_time  Expected response time.
	 * @return string
	 */
	private function get_confirmation_plain_text( $name, $is_callback, $what_happens, $contact_phone, $contact_email, $response_time ) {
		$greeting = $name !== ''
			? sprintf( __( 'Hi %s,', 'ccs-wp-theme' ), $name )
			: __( 'Hi,', 'ccs-wp-theme' );
		$thank = $is_callback
			? __( 'Thank you for requesting a callback.', 'ccs-wp-theme' )
			: __( 'Thank you for getting in touch. We have received your enquiry.', 'ccs-wp-theme' );

		$out = $greeting . "\n\n" . $thank . "\n\n";
		$out .= __( 'What happens next', 'ccs-wp-theme' ) . "\n" . $what_happens . "\n\n";
		$out .= __( 'Expected response time:', 'ccs-wp-theme' ) . ' ' . $response_time . "\n\n";
		$out .= __( 'If you need to reach us:', 'ccs-wp-theme' ) . "\n";
		if ( $contact_phone !== '' ) {
			$out .= __( 'Phone:', 'ccs-wp-theme' ) . ' ' . $contact_phone . "\n";
		}
		$out .= __( 'Email:', 'ccs-wp-theme' ) . ' ' . $contact_email . "\n";
		return $out;
	}

	// -------------------------------------------------------------------------
	// 3. URGENT NOTIFICATIONS (SMS HOOK, SLACK, ON-CALL EMAIL)
	// -------------------------------------------------------------------------

	/**
	 * Send urgent notifications: action for SMS, Slack webhook, on-call email.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function send_urgent_notifications( $post_id ) {
		$details = $this->get_enquiry_details_for_email( $post_id );
		$edit_url = admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' );

		// SMS / Twilio etc.: plugins or custom code can hook in.
		do_action( 'ccs_urgent_enquiry_sms', $post_id, $details );

		// Slack webhook.
		$this->send_slack_urgent_notification( $post_id, $details, $edit_url );

		// On-call team email.
		$this->send_oncall_urgent_email( $post_id, $details, $edit_url );
	}

	/**
	 * Post to Slack webhook if configured.
	 *
	 * @param int   $post_id   Enquiry post ID.
	 * @param array $details   Enquiry details.
	 * @param string $edit_url Admin edit URL.
	 */
	private function send_slack_urgent_notification( $post_id, array $details, $edit_url ) {
		$webhook = get_theme_mod( self::SETTING_SLACK_WEBHOOK, '' );
		if ( $webhook === '' ) {
			return;
		}

		$lines = array();
		foreach ( $details as $label => $value ) {
			$lines[] = $label . ': ' . $value;
		}
		$text = implode( "\n", $lines ) . "\n\n" . $edit_url;

		$payload = wp_json_encode( array(
			'text' => __( '[URGENT] New enquiry', 'ccs-wp-theme' ) . ' – ' . get_bloginfo( 'name' ) . "\n\n" . $text,
		) );

		wp_remote_post( $webhook, array(
			'body'    => $payload,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'timeout' => 10,
			'blocking' => false,
		) );
	}

	/**
	 * Send urgent email to on-call team.
	 *
	 * @param int   $post_id   Enquiry post ID.
	 * @param array $details   Enquiry details.
	 * @param string $edit_url Admin edit URL.
	 */
	private function send_oncall_urgent_email( $post_id, array $details, $edit_url ) {
		$to = $this->get_setting_emails( self::SETTING_ONCALL_EMAIL );
		if ( empty( $to ) ) {
			return;
		}

		$subject = __( '[URGENT] ', 'ccs-wp-theme' ) . sprintf(
			/* translators: %s: site name */
			__( 'New urgent enquiry – %s', 'ccs-wp-theme' ),
			get_bloginfo( 'name' )
		);

		$html_body = $this->render_template( 'urgent-oncall', array(
			'details'   => $details,
			'edit_url'  => $edit_url,
			'site_name' => get_bloginfo( 'name' ),
		) );

		$plain_body = $this->get_admin_enquiry_plain_text( $details, $edit_url, true );

		$this->send_mail( $to, $subject, $html_body, $plain_body );
	}

	// -------------------------------------------------------------------------
	// 4. EMAIL TEMPLATES (HTML + INLINE CSS, PLAIN TEXT FALLBACK)
	// -------------------------------------------------------------------------

	/**
	 * Render an email template by name.
	 *
	 * @param string $name Template name (e.g. 'admin-enquiry', 'confirmation', 'urgent-oncall').
	 * @param array  $vars Variables for the template.
	 * @return string HTML output.
	 */
	private function render_template( $name, array $vars ) {
		$vars['site_name'] = isset( $vars['site_name'] ) ? $vars['site_name'] : get_bloginfo( 'name' );
		ob_start();
		$this->include_template( $name, $vars );
		return ob_get_clean();
	}

	/**
	 * Include a template file (theme can override from theme/ccs/emails/).
	 *
	 * @param string $name Template name.
	 * @param array  $vars Variables.
	 */
	private function include_template( $name, array $vars ) {
		$vars = array_merge( array( 'subject_line' => '', 'details' => array(), 'edit_url' => '' ), $vars );
		extract( $vars, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$locations = array(
			get_stylesheet_directory() . '/ccs/emails/' . $name . '.php',
			THEME_DIR . '/inc/integrations/email-templates/' . $name . '.php',
		);

		foreach ( $locations as $path ) {
			if ( is_readable( $path ) ) {
				include $path;
				return;
			}
		}

		// Inline fallback: generic HTML wrapper with body content from method.
		echo $this->get_template_html_wrapper( $this->get_inline_template_content( $name, $vars ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get inline template content when no file exists (built-in defaults).
	 *
	 * @param string $name Template name.
	 * @param array  $vars Variables.
	 * @return string HTML fragment (inner content).
	 */
	private function get_inline_template_content( $name, array $vars ) {
		switch ( $name ) {
			case 'admin-enquiry':
				return $this->inline_admin_enquiry_html( $vars );
			case 'confirmation':
				return $this->inline_confirmation_html( $vars );
			case 'urgent-oncall':
				return $this->inline_urgent_oncall_html( $vars );
			default:
				return '<p>' . esc_html__( 'No template content.', 'ccs-wp-theme' ) . '</p>';
		}
	}

	/**
	 * Inline HTML for admin enquiry (no external file).
	 *
	 * @param array $vars Template vars.
	 * @return string
	 */
	private function inline_admin_enquiry_html( array $vars ) {
		$details   = isset( $vars['details'] ) ? $vars['details'] : array();
		$edit_url  = isset( $vars['edit_url'] ) ? $vars['edit_url'] : '';
		$is_urgent = ! empty( $vars['is_urgent'] );
		$is_callback = ! empty( $vars['is_callback'] );

		$out = '';
		if ( $is_urgent ) {
			$out .= '<p style="margin:0 0 16px;padding:10px 14px;background:#fef3cd;border-left:4px solid #856404;color:#856404;"><strong>' . esc_html__( 'Urgent', 'ccs-wp-theme' ) . '</strong></p>';
		}
		$out .= '<p style="margin:0 0 16px;">' . esc_html( $is_callback ? __( 'New callback request received.', 'ccs-wp-theme' ) : __( 'New enquiry received.', 'ccs-wp-theme' ) ) . '</p>';
		$out .= '<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:500px;border-collapse:collapse;margin:0 0 20px;">';
		foreach ( $details as $label => $value ) {
			$out .= '<tr><td style="padding:8px 12px;border:1px solid #dee2e6;background:#f8f9fa;font-weight:600;width:35%;">' . esc_html( $label ) . '</td><td style="padding:8px 12px;border:1px solid #dee2e6;">' . esc_html( $value ) . '</td></tr>';
		}
		$out .= '</table>';
		if ( $edit_url !== '' ) {
			$out .= '<p style="margin:0 0 8px;"><a href="' . esc_url( $edit_url ) . '" style="display:inline-block;padding:10px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:6px;">' . esc_html__( 'Edit enquiry in admin', 'ccs-wp-theme' ) . '</a></p>';
			$out .= '<p style="margin:0;font-size:13px;color:#6c757d;"><a href="' . esc_url( $edit_url ) . '" style="color:#6c757d;">' . esc_html( $edit_url ) . '</a></p>';
		}
		return $out;
	}

	/**
	 * Inline HTML for user confirmation.
	 *
	 * @param array $vars Template vars.
	 * @return string
	 */
	private function inline_confirmation_html( array $vars ) {
		$name          = isset( $vars['name'] ) ? $vars['name'] : '';
		$what_happens  = isset( $vars['what_happens'] ) ? $vars['what_happens'] : '';
		$contact_phone = isset( $vars['contact_phone'] ) ? $vars['contact_phone'] : '';
		$contact_email = isset( $vars['contact_email'] ) ? $vars['contact_email'] : '';
		$response_time = isset( $vars['response_time'] ) ? $vars['response_time'] : '';
		$site_name     = isset( $vars['site_name'] ) ? $vars['site_name'] : get_bloginfo( 'name' );

		$greeting = $name !== ''
			? sprintf( __( 'Hi %s,', 'ccs-wp-theme' ), esc_html( $name ) )
			: __( 'Hi,', 'ccs-wp-theme' );

		$thank_msg = $vars['is_callback']
			? __( 'Thank you for requesting a callback.', 'ccs-wp-theme' )
			: __( 'Thank you for getting in touch. We have received your enquiry.', 'ccs-wp-theme' );
		$out = '<p style="margin:0 0 16px;">' . $greeting . '</p>';
		$out .= '<p style="margin:0 0 16px;">' . esc_html( $thank_msg ) . '</p>';
		$out .= '<h2 style="margin:24px 0 8px;font-size:18px;">' . esc_html__( 'What happens next', 'ccs-wp-theme' ) . '</h2>';
		$out .= '<p style="margin:0 0 16px;">' . esc_html( $what_happens ) . '</p>';
		$out .= '<p style="margin:0 0 24px;"><strong>' . esc_html__( 'Expected response time:', 'ccs-wp-theme' ) . '</strong> ' . esc_html( $response_time ) . '</p>';
		$out .= '<h2 style="margin:24px 0 8px;font-size:18px;">' . esc_html__( 'Contact us', 'ccs-wp-theme' ) . '</h2>';
		$out .= '<p style="margin:0 0 8px;">' . esc_html__( 'If you need to reach us:', 'ccs-wp-theme' ) . '</p>';
		$out .= '<ul style="margin:0 0 24px;padding-left:20px;">';
		if ( $contact_phone !== '' ) {
			$out .= '<li style="margin:0 0 4px;">' . esc_html__( 'Phone:', 'ccs-wp-theme' ) . ' ' . esc_html( $contact_phone ) . '</li>';
		}
		$out .= '<li style="margin:0 0 4px;">' . esc_html__( 'Email:', 'ccs-wp-theme' ) . ' <a href="mailto:' . esc_attr( $contact_email ) . '" style="color:#0d6efd;">' . esc_html( $contact_email ) . '</a></li>';
		$out .= '</ul>';
		$out .= '<p style="margin:0;color:#6c757d;font-size:14px;">' . esc_html__( 'Thank you,', 'ccs-wp-theme' ) . '<br>' . esc_html( $site_name ) . '</p>';
		return $out;
	}

	/**
	 * Inline HTML for on-call urgent email.
	 *
	 * @param array $vars Template vars.
	 * @return string
	 */
	private function inline_urgent_oncall_html( array $vars ) {
		$details   = isset( $vars['details'] ) ? $vars['details'] : array();
		$edit_url  = isset( $vars['edit_url'] ) ? $vars['edit_url'] : '';
		$site_name = isset( $vars['site_name'] ) ? $vars['site_name'] : get_bloginfo( 'name' );

		$out = '<p style="margin:0 0 16px;padding:10px 14px;background:#f8d7da;border-left:4px solid #721c24;color:#721c24;"><strong>' . esc_html__( 'Urgent enquiry – please respond as soon as possible.', 'ccs-wp-theme' ) . '</strong></p>';
		$out .= '<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:500px;border-collapse:collapse;margin:0 0 20px;">';
		foreach ( $details as $label => $value ) {
			$out .= '<tr><td style="padding:8px 12px;border:1px solid #dee2e6;background:#f8f9fa;font-weight:600;width:35%;">' . esc_html( $label ) . '</td><td style="padding:8px 12px;border:1px solid #dee2e6;">' . esc_html( $value ) . '</td></tr>';
		}
		$out .= '</table>';
		if ( $edit_url !== '' ) {
			$out .= '<p style="margin:0;"><a href="' . esc_url( $edit_url ) . '" style="display:inline-block;padding:10px 20px;background:#dc3545;color:#fff;text-decoration:none;border-radius:6px;">' . esc_html__( 'View enquiry', 'ccs-wp-theme' ) . '</a></p>';
		}
		$out .= '<p style="margin:24px 0 0;font-size:13px;color:#6c757d;">' . esc_html( $site_name ) . '</p>';
		return $out;
	}

	/**
	 * Wrap inner HTML in responsive email shell with inline CSS.
	 *
	 * @param string $inner_html Inner body content.
	 * @return string Full HTML document.
	 */
	private function get_template_html_wrapper( $inner_html ) {
		$site_name = get_bloginfo( 'name' );
		return '<!DOCTYPE html>
<html lang="' . esc_attr( get_bloginfo( 'language' ) ) . '" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>' . esc_html( $site_name ) . '</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,sans-serif;font-size:16px;line-height:1.5;color:#212529;background:#f1f3f5;">
	<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f1f3f5;">
		<tr>
			<td align="center" style="padding:24px 16px;">
				<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
					<tr>
						<td style="padding:32px 24px;">
							' . $inner_html . '
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>';
	}

	// -------------------------------------------------------------------------
	// 5. SETTINGS & SENDING
	// -------------------------------------------------------------------------

	/**
	 * Get primary recipient(s) for admin emails.
	 *
	 * @return array List of email addresses.
	 */
	private function get_recipients() {
		return $this->get_setting_emails( self::SETTING_RECIPIENTS );
	}

	/**
	 * Parse a theme mod (comma-separated emails) into an array of valid addresses.
	 *
	 * @param string $setting_key Theme mod key.
	 * @return array
	 */
	private function get_setting_emails( $setting_key ) {
		$default = ( $setting_key === self::SETTING_RECIPIENTS ) ? get_option( 'admin_email' ) : '';
		$value   = get_theme_mod( $setting_key, $default );
		if ( ! is_string( $value ) || $value === '' ) {
			return array();
		}
		$emails = array_map( 'trim', explode( ',', $value ) );
		return array_values( array_filter( $emails, 'is_email' ) );
	}

	/**
	 * Build From header value (name and email from settings).
	 *
	 * @return string e.g. "Site Name <hello@example.com>"
	 */
	private function get_from_header() {
		$name  = get_theme_mod( self::SETTING_FROM_NAME, get_bloginfo( 'name' ) );
		$email = get_theme_mod( self::SETTING_FROM_EMAIL, get_option( 'admin_email' ) );
		if ( ! is_email( $email ) ) {
			$email = get_option( 'admin_email' );
		}
		if ( $name !== '' ) {
			return $name . ' <' . $email . '>';
		}
		return $email;
	}

	/**
	 * Get Reply-To header (optional).
	 *
	 * @return string|null Reply-To address or null.
	 */
	private function get_reply_to() {
		$reply = get_theme_mod( self::SETTING_REPLY_TO, '' );
		return is_email( $reply ) ? $reply : null;
	}

	/**
	 * Send an email with HTML and plain text parts via wp_mail().
	 *
	 * @param array       $to          Recipients.
	 * @param string      $subject     Subject line.
	 * @param string      $html_body   HTML body (full document or fragment).
	 * @param string      $plain_body  Plain text body.
	 * @param array       $extra       Optional 'cc' and 'bcc' arrays.
	 * @return bool Whether wp_mail() returned true.
	 */
	private function send_mail( array $to, $subject, $html_body, $plain_body, array $extra = array() ) {
		if ( empty( $to ) ) {
			return false;
		}

		$boundary = 'ccs-' . wp_generate_password( 16, false );
		$headers  = array(
			'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
			'From: ' . $this->get_from_header(),
			'MIME-Version: 1.0',
		);

		$reply_to = $this->get_reply_to();
		if ( $reply_to !== null ) {
			$headers[] = 'Reply-To: ' . $reply_to;
		}

		$cc = isset( $extra['cc'] ) ? $extra['cc'] : array();
		$bcc = isset( $extra['bcc'] ) ? $extra['bcc'] : array();
		foreach ( $cc as $addr ) {
			if ( is_email( $addr ) ) {
				$headers[] = 'Cc: ' . $addr;
			}
		}
		foreach ( $bcc as $addr ) {
			if ( is_email( $addr ) ) {
				$headers[] = 'Bcc: ' . $addr;
			}
		}

		$plain_part = "Content-Type: text/plain; charset=UTF-8\r\n\r\n" . $plain_body;
		$html_part  = "Content-Type: text/html; charset=UTF-8\r\n\r\n" . $html_body;
		$body       = "--{$boundary}\r\n{$plain_part}\r\n--{$boundary}\r\n{$html_part}\r\n--{$boundary}--";

		return wp_mail( $to, $subject, $body, $headers );
	}
}
