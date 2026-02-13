<?php
/**
 * Email notifications: admin enquiry notification, user confirmation.
 * HTML templates with CCS branding, inline CSS only, plain text fallback.
 * Uses wp_mail() – no SMTP plugin required.
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

	/** Default admin recipient. */
	const ADMIN_EMAIL_DEFAULT = 'office@continuitycareservices.co.uk';

	/** Theme mod: admin recipient(s), comma-separated. */
	const SETTING_RECIPIENTS = 'ccs_email_recipients';

	/** Theme mod: From name. */
	const SETTING_FROM_NAME = 'ccs_email_from_name';

	/** Theme mod: From email. */
	const SETTING_FROM_EMAIL = 'ccs_email_from_email';

	/** Theme mod: Reply-To. */
	const SETTING_REPLY_TO = 'ccs_email_reply_to';

	/** Theme mod: logo URL for emails. */
	const SETTING_LOGO_URL = 'ccs_email_logo_url';

	/** Theme mod: contact phone (user confirmation). */
	const SETTING_PHONE = 'ccs_contact_phone';

	/** Theme mod: contact email (user confirmation). */
	const SETTING_CONTACT_EMAIL = 'ccs_contact_email';

	/** Theme mod: social links JSON or comma-separated (e.g. Facebook URL, Twitter URL). */
	const SETTING_SOCIAL_LINKS = 'ccs_social_links';

	/** Brand colours (design-system). */
	const COLOR_PRIMARY   = '#564298';
	const COLOR_SECONDARY = '#a8ddd4';
	const COLOR_TEXT      = '#2e2e2e';
	const COLOR_BG        = '#f6f5ef';

	/**
	 * Constructor: hook into enquiry creation (consultation form + API form).
	 */
	public function __construct() {
		add_action( 'ccs_enquiry_created', array( $this, 'on_enquiry_created' ), 10, 1 );
		add_action( 'ccs_consultation_enquiry_created', array( $this, 'on_consultation_enquiry_created' ), 10, 1 );
	}

	/**
	 * Fired when API enquiry form creates a post.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function on_enquiry_created( $post_id ) {
		$this->send_admin_notification( $post_id );
		$this->send_user_confirmation( $post_id );
	}

	/**
	 * Fired when consultation form creates a ccs_enquiry post.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function on_consultation_enquiry_created( $post_id ) {
		$this->send_admin_notification( $post_id );
		$this->send_user_confirmation( $post_id );
	}

	// -------------------------------------------------------------------------
	// 1. ADMIN NOTIFICATION
	// -------------------------------------------------------------------------

	/**
	 * Send admin notification to office@continuitycareservices.co.uk.
	 * Subject: "New Enquiry from [Name]". HTML with table of fields, timestamp, IP, admin link.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function send_admin_notification( $post_id ) {
		$to = $this->get_admin_recipients();
		if ( empty( $to ) ) {
			$to = array( self::ADMIN_EMAIL_DEFAULT );
		}

		$name    = get_post_meta( $post_id, 'enquiry_name', true );
		$subject = sprintf(
			/* translators: %s: submitter name */
			__( 'New Enquiry from %s', 'ccs-wp-theme' ),
			$name !== '' ? $name : __( 'Website', 'ccs-wp-theme' )
		);

		$details  = $this->get_enquiry_details_for_email( $post_id );
		$edit_url = admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' );

		$html_body  = $this->build_admin_html( $details, $edit_url );
		$plain_body = $this->build_admin_plain( $details, $edit_url );

		$this->send_mail( $to, $subject, $html_body, $plain_body );
	}

	/**
	 * Build enquiry details array from post meta (consultation + API form fields).
	 *
	 * @param int $post_id Enquiry post ID.
	 * @return array<string, string> Label => value.
	 */
	private function get_enquiry_details_for_email( $post_id ) {
		$meta_labels = array(
			'enquiry_name'             => __( 'Name', 'ccs-wp-theme' ),
			'enquiry_phone'            => __( 'Phone', 'ccs-wp-theme' ),
			'enquiry_email'            => __( 'Email', 'ccs-wp-theme' ),
			'enquiry_care_type'        => __( 'Service / Care type', 'ccs-wp-theme' ),
			'enquiry_with_whom'        => __( 'With Whom?', 'ccs-wp-theme' ),
			'enquiry_preferred_date'   => __( 'Preferred Date', 'ccs-wp-theme' ),
			'enquiry_preferred_time'   => __( 'Preferred Time', 'ccs-wp-theme' ),
			'enquiry_message'          => __( 'Additional Info', 'ccs-wp-theme' ),
			'enquiry_newsletter'       => __( 'Newsletter', 'ccs-wp-theme' ),
			'enquiry_location'         => __( 'Location / area', 'ccs-wp-theme' ),
			'enquiry_urgency'          => __( 'Urgency', 'ccs-wp-theme' ),
			'enquiry_preferred_contact'=> __( 'Preferred contact', 'ccs-wp-theme' ),
		);

		$with_whom_labels = array(
			''              => __( 'Anyone', 'ccs-wp-theme' ),
			'keelie_varney' => __( 'Keelie Varney', 'ccs-wp-theme' ),
			'nikki_mackay'  => __( 'Nikki Mackay', 'ccs-wp-theme' ),
		);

		$rows = array();
		foreach ( $meta_labels as $meta_key => $label ) {
			$value = get_post_meta( $post_id, $meta_key, true );
			if ( $meta_key === 'enquiry_newsletter' ) {
				$value = ( $value === '1' || $value === true ) ? __( 'Yes', 'ccs-wp-theme' ) : __( 'No', 'ccs-wp-theme' );
			} elseif ( $meta_key === 'enquiry_with_whom' && $value !== '' ) {
				$value = isset( $with_whom_labels[ $value ] ) ? $with_whom_labels[ $value ] : $value;
			}
			$value = is_string( $value ) ? trim( $value ) : '';
			if ( $value !== '' || in_array( $meta_key, array( 'enquiry_name', 'enquiry_phone', 'enquiry_email', 'enquiry_message', 'enquiry_newsletter' ), true ) ) {
				$rows[ $label ] = $value !== '' ? $value : '—';
			}
		}

		$submitted_at = get_post_meta( $post_id, 'enquiry_submitted_at', true );
		if ( $submitted_at === '' ) {
			$post = get_post( $post_id );
			$submitted_at = $post && $post->post_date ? get_date_from_gmt( $post->post_date, 'j F Y, g:i a' ) : '';
		} else {
			$submitted_at = date_i18n( 'j F Y, g:i a', strtotime( $submitted_at ) );
		}
		$rows[ __( 'Submitted', 'ccs-wp-theme' ) ] = $submitted_at;

		$ip = get_post_meta( $post_id, 'enquiry_submitted_ip', true );
		if ( $ip !== '' ) {
			$rows[ __( 'IP address', 'ccs-wp-theme' ) ] = $ip;
		}

		return $rows;
	}

	/**
	 * Build admin email HTML (inline CSS, max-width 600px, CCS branding).
	 *
	 * @param array  $details  Label => value.
	 * @param string $edit_url Admin edit URL.
	 * @return string Full HTML document.
	 */
	private function build_admin_html( array $details, $edit_url ) {
		$logo_html = $this->get_logo_html();
		$table_rows = '';
		foreach ( $details as $label => $value ) {
			$table_rows .= '<tr><td style="padding:10px 14px;border:1px solid #e0e0e0;background:#f6f5ef;font-weight:600;width:38%;font-size:14px;color:' . self::COLOR_TEXT . ';">' . esc_html( $label ) . '</td><td style="padding:10px 14px;border:1px solid #e0e0e0;font-size:14px;color:' . self::COLOR_TEXT . ';">' . ( $label === __( 'Additional Info', 'ccs-wp-theme' ) ? wp_kses_post( $value ) : esc_html( $value ) ) . '</td></tr>';
		}

		$inner = $logo_html .
			'<h2 style="margin:0 0 16px;font-size:20px;color:' . self::COLOR_PRIMARY . ';">' . esc_html__( 'New consultation enquiry', 'ccs-wp-theme' ) . '</h2>' .
			'<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:100%;border-collapse:collapse;margin:0 0 24px;">' . $table_rows . '</table>' .
			'<p style="margin:0 0 8px;"><a href="' . esc_url( $edit_url ) . '" style="display:inline-block;padding:12px 24px;background:' . self::COLOR_PRIMARY . ';color:#fff;text-decoration:none;border-radius:8px;font-weight:600;">' . esc_html__( 'View enquiry in admin', 'ccs-wp-theme' ) . '</a></p>' .
			'<p style="margin:8px 0 0;font-size:13px;color:#666;"><a href="' . esc_url( $edit_url ) . '" style="color:#666;">' . esc_html( $edit_url ) . '</a></p>';

		return $this->wrap_email_html( $inner );
	}

	/**
	 * Build admin plain text body.
	 *
	 * @param array  $details  Label => value.
	 * @param string $edit_url Admin edit URL.
	 * @return string
	 */
	private function build_admin_plain( array $details, $edit_url ) {
		$out = __( 'New enquiry received.', 'ccs-wp-theme' ) . "\n\n";
		foreach ( $details as $label => $value ) {
			$out .= $label . ': ' . wp_strip_all_tags( $value ) . "\n";
		}
		$out .= "\n" . __( 'View enquiry in admin:', 'ccs-wp-theme' ) . "\n" . $edit_url;
		return $out;
	}

	// -------------------------------------------------------------------------
	// 2. USER CONFIRMATION
	// -------------------------------------------------------------------------

	/**
	 * Send confirmation email to the submitter.
	 * Subject: "Thank you for your enquiry - CCS". Confirms details, 24hr expectation, contact/social.
	 *
	 * @param int $post_id Enquiry post ID.
	 */
	public function send_user_confirmation( $post_id ) {
		$email = get_post_meta( $post_id, 'enquiry_email', true );
		if ( ! is_email( $email ) ) {
			return;
		}

		$name    = get_post_meta( $post_id, 'enquiry_name', true );
		$subject = __( 'Thank you for your enquiry - CCS', 'ccs-wp-theme' );

		$details = $this->get_enquiry_details_for_email( $post_id );
		$html_body  = $this->build_confirmation_html( $name, $details );
		$plain_body = $this->build_confirmation_plain( $name, $details );

		$this->send_mail( array( $email ), $subject, $html_body, $plain_body );
	}

	/**
	 * Build user confirmation HTML (branded, confirm details, expectations, contact, social).
	 *
	 * @param string $name    Submitter name.
	 * @param array  $details Label => value (subset for confirmation).
	 * @return string Full HTML document.
	 */
	private function build_confirmation_html( $name, array $details ) {
		$logo_html = $this->get_logo_html();
		$greeting = $name !== ''
			? sprintf( __( 'Hi %s,', 'ccs-wp-theme' ), esc_html( $name ) )
			: __( 'Hi,', 'ccs-wp-theme' );

		$expectation = __( "We'll contact you within 24 hours.", 'ccs-wp-theme' );
		$expectation = (string) apply_filters( 'ccs_email_confirmation_expectation', $expectation );

		$contact_phone = get_theme_mod( self::SETTING_PHONE, '' );
		$contact_email = get_theme_mod( self::SETTING_CONTACT_EMAIL, get_option( 'admin_email' ) );
		if ( ! is_email( $contact_email ) ) {
			$contact_email = get_option( 'admin_email' );
		}

		$summary_rows = '';
		$show_keys = array( __( 'Name', 'ccs-wp-theme' ), __( 'Phone', 'ccs-wp-theme' ), __( 'Email', 'ccs-wp-theme' ), __( 'Service / Care type', 'ccs-wp-theme' ), __( 'Preferred Date', 'ccs-wp-theme' ), __( 'Preferred Time', 'ccs-wp-theme' ), __( 'Additional Info', 'ccs-wp-theme' ) );
		foreach ( $details as $label => $value ) {
			if ( ! in_array( $label, $show_keys, true ) ) {
				continue;
			}
			$summary_rows .= '<tr><td style="padding:8px 12px;border:1px solid #e0e0e0;background:#f6f5ef;font-weight:600;width:38%;font-size:14px;">' . esc_html( $label ) . '</td><td style="padding:8px 12px;border:1px solid #e0e0e0;font-size:14px;">' . ( $label === __( 'Additional Info', 'ccs-wp-theme' ) ? wp_kses_post( $value ) : esc_html( $value ) ) . '</td></tr>';
		}

		$social_html = $this->get_social_links_html();

		$inner = $logo_html .
			'<p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:' . self::COLOR_TEXT . ';">' . $greeting . '</p>' .
			'<p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:' . self::COLOR_TEXT . ';">' . esc_html__( 'Thank you for getting in touch. We have received your enquiry and will be in touch shortly.', 'ccs-wp-theme' ) . '</p>' .
			'<p style="margin:0 0 20px;padding:12px 16px;background:' . self::COLOR_SECONDARY . ';border-radius:8px;font-size:15px;color:' . self::COLOR_TEXT . ';"><strong>' . esc_html__( 'What happens next', 'ccs-wp-theme' ) . '</strong><br>' . esc_html( $expectation ) . '</p>' .
			'<h3 style="margin:24px 0 8px;font-size:18px;color:' . self::COLOR_PRIMARY . ';">' . esc_html__( 'Your details', 'ccs-wp-theme' ) . '</h3>' .
			'<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;margin:0 0 24px;">' . $summary_rows . '</table>' .
			'<h3 style="margin:24px 0 8px;font-size:18px;color:' . self::COLOR_PRIMARY . ';">' . esc_html__( 'Contact us', 'ccs-wp-theme' ) . '</h3>' .
			'<p style="margin:0 0 8px;font-size:15px;color:' . self::COLOR_TEXT . ';">' . esc_html__( 'If you need to reach us:', 'ccs-wp-theme' ) . '</p>' .
			'<ul style="margin:0 0 16px;padding-left:20px;font-size:15px;color:' . self::COLOR_TEXT . ';">';
		if ( $contact_phone !== '' ) {
			$inner .= '<li style="margin:0 0 4px;">' . esc_html__( 'Phone:', 'ccs-wp-theme' ) . ' <a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ) . '" style="color:' . self::COLOR_PRIMARY . ';">' . esc_html( $contact_phone ) . '</a></li>';
		}
		$inner .= '<li style="margin:0 0 4px;">' . esc_html__( 'Email:', 'ccs-wp-theme' ) . ' <a href="mailto:' . esc_attr( $contact_email ) . '" style="color:' . self::COLOR_PRIMARY . ';">' . esc_html( $contact_email ) . '</a></li></ul>';
		if ( $social_html !== '' ) {
			$inner .= '<p style="margin:16px 0 8px;font-size:15px;color:' . self::COLOR_TEXT . ';">' . esc_html__( 'Follow us:', 'ccs-wp-theme' ) . '</p>' . $social_html;
		}
		$inner .= '<p style="margin:24px 0 0;font-size:14px;color:#666;">' . esc_html__( 'Thank you,', 'ccs-wp-theme' ) . '<br><strong>' . esc_html( get_bloginfo( 'name' ) ) . '</strong></p>';

		return $this->wrap_email_html( $inner );
	}

	/**
	 * Build user confirmation plain text.
	 *
	 * @param string $name    Submitter name.
	 * @param array  $details Label => value.
	 * @return string
	 */
	private function build_confirmation_plain( $name, array $details ) {
		$greeting = $name !== ''
			? sprintf( __( 'Hi %s,', 'ccs-wp-theme' ), $name )
			: __( 'Hi,', 'ccs-wp-theme' );
		$out = $greeting . "\n\n" . __( 'Thank you for getting in touch. We have received your enquiry and will be in touch shortly.', 'ccs-wp-theme' ) . "\n\n";
		$out .= __( "We'll contact you within 24 hours.", 'ccs-wp-theme' ) . "\n\n";
		$out .= __( 'Your details', 'ccs-wp-theme' ) . "\n";
		foreach ( $details as $label => $value ) {
			$out .= $label . ': ' . wp_strip_all_tags( $value ) . "\n";
		}
		$contact_email = get_theme_mod( self::SETTING_CONTACT_EMAIL, get_option( 'admin_email' ) );
		$out .= "\n" . __( 'Contact us:', 'ccs-wp-theme' ) . "\n" . __( 'Email:', 'ccs-wp-theme' ) . ' ' . $contact_email . "\n";
		$out .= "\n" . __( 'Thank you,', 'ccs-wp-theme' ) . "\n" . get_bloginfo( 'name' );
		return $out;
	}

	// -------------------------------------------------------------------------
	// 3. SHARED: WRAPPER, LOGO, SOCIAL, SEND
	// -------------------------------------------------------------------------

	/**
	 * Wrap inner HTML in responsive email shell (inline CSS, max-width 600px).
	 *
	 * @param string $inner_html Body content.
	 * @return string Full HTML document.
	 */
	private function wrap_email_html( $inner_html ) {
		return '<!DOCTYPE html>
<html lang="' . esc_attr( get_bloginfo( 'language' ) ) . '" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>' . esc_html( get_bloginfo( 'name' ) ) . '</title>
</head>
<body style="margin:0;padding:0;font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;font-size:16px;line-height:1.5;color:' . self::COLOR_TEXT . ';background:' . self::COLOR_BG . ';">
	<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:' . self::COLOR_BG . ';">
		<tr>
			<td align="center" style="padding:24px 16px;">
				<!--[if mso]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
				<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:10px;box-shadow:0 2px 8px rgba(46,46,46,0.08);">
					<tr>
						<td style="padding:32px 24px;">
							' . $inner_html . '
						</td>
					</tr>
				</table>
				<!--[if mso]></td></tr></table><![endif]-->
			</td>
		</tr>
	</table>
</body>
</html>';
	}

	/**
	 * Logo HTML: image URL from theme mod/filter, or text "Continuity Care Services".
	 *
	 * @return string
	 */
	private function get_logo_html() {
		$url = get_theme_mod( self::SETTING_LOGO_URL, '' );
		$url = (string) apply_filters( 'ccs_email_logo_url', $url );
		if ( $url !== '' && filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return '<p style="margin:0 0 24px;"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="180" height="48" style="display:block;max-width:180px;height:auto;" /></p>';
		}
		return '<p style="margin:0 0 24px;font-size:22px;font-weight:700;color:' . self::COLOR_PRIMARY . ';">' . esc_html( get_bloginfo( 'name' ) ) . '</p>';
	}

	/**
	 * Social links HTML (if theme mod set). Inline list of links.
	 *
	 * @return string
	 */
	private function get_social_links_html() {
		$links = get_theme_mod( self::SETTING_SOCIAL_LINKS, '' );
		$links = (string) apply_filters( 'ccs_email_social_links', $links );
		if ( $links === '' ) {
			return '';
		}
		$decoded = json_decode( $links, true );
		if ( ! is_array( $decoded ) ) {
			return '';
		}
		$out = '<p style="margin:0;">';
		$first = true;
		foreach ( $decoded as $item ) {
			$label = isset( $item['label'] ) ? $item['label'] : ( isset( $item['name'] ) ? $item['name'] : 'Link' );
			$url   = isset( $item['url'] ) ? $item['url'] : '';
			if ( $url === '' || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
				continue;
			}
			if ( ! $first ) {
				$out .= ' &nbsp;|&nbsp; ';
			}
			$out .= '<a href="' . esc_url( $url ) . '" style="color:' . self::COLOR_PRIMARY . ';text-decoration:none;">' . esc_html( $label ) . '</a>';
			$first = false;
		}
		$out .= '</p>';
		return $first ? '' : $out;
	}

	/**
	 * Get admin recipient(s).
	 *
	 * @return array Email addresses.
	 */
	private function get_admin_recipients() {
		$value = get_theme_mod( self::SETTING_RECIPIENTS, self::ADMIN_EMAIL_DEFAULT );
		if ( ! is_string( $value ) || $value === '' ) {
			return array( self::ADMIN_EMAIL_DEFAULT );
		}
		$emails = array_map( 'trim', explode( ',', $value ) );
		return array_values( array_filter( $emails, 'is_email' ) );
	}

	/**
	 * From header value.
	 *
	 * @return string
	 */
	private function get_from_header() {
		$name  = get_theme_mod( self::SETTING_FROM_NAME, get_bloginfo( 'name' ) );
		$email = get_theme_mod( self::SETTING_FROM_EMAIL, get_option( 'admin_email' ) );
		if ( ! is_email( $email ) ) {
			$email = get_option( 'admin_email' );
		}
		return $name !== '' ? $name . ' <' . $email . '>' : $email;
	}

	/**
	 * Send multipart email (plain + HTML) via wp_mail().
	 *
	 * @param array  $to         Recipients.
	 * @param string $subject    Subject.
	 * @param string $html_body  HTML body.
	 * @param string $plain_body Plain text body.
	 * @return bool
	 */
	private function send_mail( array $to, $subject, $html_body, $plain_body ) {
		if ( empty( $to ) ) {
			return false;
		}

		$boundary = 'ccs-' . wp_generate_password( 16, false );
		$headers  = array(
			'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
			'From: ' . $this->get_from_header(),
			'MIME-Version: 1.0',
		);

		$reply_to = get_theme_mod( self::SETTING_REPLY_TO, '' );
		if ( is_email( $reply_to ) ) {
			$headers[] = 'Reply-To: ' . $reply_to;
		}

		$plain_part = "Content-Type: text/plain; charset=UTF-8\r\n\r\n" . $plain_body;
		$html_part  = "Content-Type: text/html; charset=UTF-8\r\n\r\n" . $html_body;
		$body       = "--{$boundary}\r\n{$plain_part}\r\n--{$boundary}\r\n{$html_part}\r\n--{$boundary}--";

		return wp_mail( $to, $subject, $body, $headers );
	}
}
