<?php
/**
 * Consultation form: native HTML5, AJAX submit, validation, rate limit, honeypot.
 * Shortcode [ccs_consultation_form]. Stores in CPT ccs_enquiry, sends HTML email.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Contact_Form
 */
class CCS_Contact_Form {

	const CPT_ENQUIRY       = 'ccs_enquiry';
	const SHORTCODE         = 'ccs_consultation_form';
	const AJAX_ACTION       = 'submit_ccs_consultation_form';
	const NONCE_ACTION      = 'ccs_consultation_form';
	const HONEYPOT_FIELD    = 'company_website';
	const RATE_LIMIT_MAX    = 5;
	const RATE_LIMIT_WINDOW = 600; // 10 minutes

	/**
	 * Constructor: register CPT, shortcode, AJAX, scripts.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_cpt' ), 6 );
		add_shortcode( self::SHORTCODE, array( $this, 'render_shortcode' ) );
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( $this, 'handle_submit' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( $this, 'handle_submit' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register custom post type ccs_enquiry.
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => _x( 'Consultation Enquiries', 'Post type general name', 'ccs-wp-theme' ),
			'singular_name'      => _x( 'Consultation Enquiry', 'Post type singular name', 'ccs-wp-theme' ),
			'menu_name'          => _x( 'Consultation Enquiries', 'Admin Menu text', 'ccs-wp-theme' ),
			'edit_item'          => __( 'Edit Enquiry', 'ccs-wp-theme' ),
			'view_item'          => __( 'View Enquiry', 'ccs-wp-theme' ),
			'search_items'       => __( 'Search Enquiries', 'ccs-wp-theme' ),
			'not_found'          => __( 'No enquiries found.', 'ccs-wp-theme' ),
			'not_found_in_trash' => __( 'No enquiries found in Trash.', 'ccs-wp-theme' ),
		);

		register_post_type(
			self::CPT_ENQUIRY,
			array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => false,
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'hierarchical'       => false,
				'menu_position'      => 22,
				'menu_icon'          => 'dashicons-email-alt',
				'supports'           => array( 'title' ),
			)
		);
	}

	/**
	 * Enqueue script and pass nonce + AJAX URL for consultation form.
	 */
	public function enqueue_scripts() {
		if ( ! is_singular() ) {
			return;
		}
		global $post;
		if ( ! $post || strpos( $post->post_content, '[' . self::SHORTCODE . ']' ) === false ) {
			return;
		}

		wp_enqueue_script(
			'ccs-consultation-form',
			THEME_URL . '/assets/js/consultation-form.js',
			array(),
			THEME_VERSION,
			true
		);
		wp_enqueue_script(
			'ccs-form-success-confetti',
			THEME_URL . '/assets/js/form-success-confetti.js',
			array( 'ccs-consultation-form' ),
			THEME_VERSION,
			true
		);
		wp_localize_script(
			'ccs-consultation-form',
			'ccsConsultationForm',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
			)
		);
	}

	/**
	 * Shortcode callback: output heading, description, and form.
	 *
	 * @return string
	 */
	public function render_shortcode() {
		ob_start();
		?>
		<div class="ccs-consultation-form-wrapper">
			<h2 class="ccs-consultation-form-title"><?php esc_html_e( 'Book Your Free Care Consultation', 'ccs-wp-theme' ); ?></h2>
			<p class="ccs-consultation-form-description"><?php esc_html_e( 'Please let us know what you’d like to discuss below, whether you’re seeking home care in Maidstone or across Kent, and we’ll get in touch to arrange a call or visit. We can’t promise we’ll be able to make your exact date, but we’ll use this as a rough guideline on when to get in touch!', 'ccs-wp-theme' ); ?></p>
			<?php $this->render_form(); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Output the form HTML (native HTML5, all Section 10 fields).
	 */
	private function render_form() {
		$id_msg = 'ccs-consultation-form-message';
		?>
		<form
			class="ccs-consultation-form"
			id="ccs-consultation-form"
			action=""
			method="post"
			novalidate
			data-ccs-action="<?php echo esc_attr( self::AJAX_ACTION ); ?>"
			aria-describedby="<?php echo esc_attr( $id_msg ); ?>"
		>
			<?php wp_nonce_field( self::NONCE_ACTION, 'ccs_consultation_nonce', false ); ?>

			<div id="<?php echo esc_attr( $id_msg ); ?>" class="ccs-form-message" role="alert" hidden></div>

			<?php $this->render_honeypot(); ?>

			<p class="ccs-form-field">
				<label for="ccs_consultation_name"><?php esc_html_e( 'Your Name', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
				<input type="text" id="ccs_consultation_name" name="consultation_name" required aria-required="true" maxlength="200" autocomplete="name" />
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_phone"><?php esc_html_e( 'Your Phone Number', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
				<input type="tel" id="ccs_consultation_phone" name="consultation_phone" required aria-required="true" maxlength="50" autocomplete="tel" />
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_email"><?php esc_html_e( 'Your Email', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
				<input type="email" id="ccs_consultation_email" name="consultation_email" required aria-required="true" maxlength="254" autocomplete="email" />
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_service"><?php esc_html_e( 'Select Service', 'ccs-wp-theme' ); ?></label>
				<select id="ccs_consultation_service" name="consultation_service[]" multiple size="4">
					<option value="domiciliary"><?php esc_html_e( 'Domiciliary Care', 'ccs-wp-theme' ); ?></option>
					<option value="respite"><?php esc_html_e( 'Respite Care', 'ccs-wp-theme' ); ?></option>
					<option value="complex"><?php esc_html_e( 'Complex Care', 'ccs-wp-theme' ); ?></option>
					<option value="not_sure"><?php esc_html_e( "I'm Not Sure", 'ccs-wp-theme' ); ?></option>
				</select>
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_with_whom"><?php esc_html_e( 'With Whom?', 'ccs-wp-theme' ); ?></label>
				<select id="ccs_consultation_with_whom" name="consultation_with_whom">
					<option value=""><?php esc_html_e( 'Anyone', 'ccs-wp-theme' ); ?></option>
					<option value="keelie_varney"><?php esc_html_e( 'Keelie Varney', 'ccs-wp-theme' ); ?></option>
					<option value="nikki_mackay"><?php esc_html_e( 'Nikki Mackay', 'ccs-wp-theme' ); ?></option>
				</select>
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_date"><?php esc_html_e( 'Preferred Date', 'ccs-wp-theme' ); ?></label>
				<input type="date" id="ccs_consultation_date" name="consultation_preferred_date" />
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_time"><?php esc_html_e( 'Preferred Time', 'ccs-wp-theme' ); ?></label>
				<input type="time" id="ccs_consultation_time" name="consultation_preferred_time" />
			</p>

			<p class="ccs-form-field">
				<label for="ccs_consultation_message"><?php esc_html_e( 'Additional Info', 'ccs-wp-theme' ); ?></label>
				<textarea id="ccs_consultation_message" name="consultation_message" rows="4" maxlength="5000"></textarea>
			</p>

			<p class="ccs-form-field ccs-form-field--checkbox">
				<input type="checkbox" id="ccs_consultation_consent" name="consultation_consent" value="1" required aria-required="true" />
				<label for="ccs_consultation_consent"><?php esc_html_e( 'I consent to Continuity of Care Services storing my details so they can respond to this enquiry.', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
			</p>

			<p class="ccs-form-field ccs-form-field--checkbox">
				<input type="checkbox" id="ccs_consultation_newsletter" name="consultation_newsletter" value="1" checked />
				<label for="ccs_consultation_newsletter"><?php esc_html_e( 'I agree to Continuity of Care Services storing my details so they can send me newsletters and updates.', 'ccs-wp-theme' ); ?></label>
			</p>

			<p class="ccs-form-submit">
				<button type="submit" class="ccs-consultation-form-submit"><?php esc_html_e( 'Send Request', 'ccs-wp-theme' ); ?></button>
			</p>
		</form>
		<?php
	}

	/**
	 * Honeypot field (hidden with CSS; bots tend to fill it).
	 */
	private function render_honeypot() {
		?>
		<div class="ccs-form-honeypot" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
			<label for="ccs_consultation_company_website"><?php esc_html_e( 'Company website', 'ccs-wp-theme' ); ?></label>
			<input type="text" id="ccs_consultation_company_website" name="<?php echo esc_attr( self::HONEYPOT_FIELD ); ?>" tabindex="-1" autocomplete="off" />
		</div>
		<?php
	}

	/**
	 * AJAX handler: validate, rate limit, save to ccs_enquiry, send email, JSON response.
	 */
	public function handle_submit() {
		$honeypot = isset( $_POST[ self::HONEYPOT_FIELD ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::HONEYPOT_FIELD ] ) ) : '';
		if ( $honeypot !== '' ) {
			do_action( 'ccs_security_event', 'honeypot', array( 'form' => 'consultation', 'ip' => $this->get_client_ip() ) );
			wp_send_json_success( array( 'message' => __( 'Thank you. We will be in touch shortly.', 'ccs-wp-theme' ) ) );
			return;
		}

		$nonce = isset( $_POST['ccs_consultation_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ccs_consultation_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			do_action( 'ccs_security_event', 'nonce_failed', array( 'action' => self::NONCE_ACTION, 'form' => 'consultation', 'ip' => $this->get_client_ip() ) );
			wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'ccs-wp-theme' ) ) );
			return;
		}

		if ( ! $this->rate_limit_allow() ) {
			do_action( 'ccs_security_event', 'rate_limit_exceeded', array( 'form' => 'consultation', 'ip' => $this->get_client_ip() ) );
			wp_send_json_error( array( 'message' => __( 'Too many submissions. Please try again later.', 'ccs-wp-theme' ) ) );
			return;
		}

		$data = $this->sanitize_and_validate();
		if ( is_wp_error( $data ) ) {
			wp_send_json_error( array( 'message' => $data->get_error_message() ) );
			return;
		}

		$post_id = $this->save_enquiry( $data );
		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'ccs-wp-theme' ) ) );
			return;
		}

		do_action( 'ccs_consultation_enquiry_created', $post_id );

		wp_send_json_success( array(
			'message' => __( 'Thank you. We have received your enquiry and will be in touch shortly.', 'ccs-wp-theme' ),
		) );
	}

	/**
	 * Rate limit: max 5 per 10 min per IP (transient).
	 *
	 * @return bool True if allowed.
	 */
	private function rate_limit_allow() {
		$ip = $this->get_client_ip();
		$key = 'ccs_consultation_' . md5( $ip );
		$now = time();
		$cut = $now - self::RATE_LIMIT_WINDOW;
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
	 * Get client IP for rate limiting.
	 *
	 * @return string
	 */
	private function get_client_ip() {
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$parts = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			return trim( $parts[0] );
		}
		return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	}

	/**
	 * Sanitize POST and validate required fields.
	 *
	 * @return array|WP_Error Sanitized data array or WP_Error.
	 */
	private function sanitize_and_validate() {
		$name   = isset( $_POST['consultation_name'] ) ? sanitize_text_field( wp_unslash( $_POST['consultation_name'] ) ) : '';
		$phone  = isset( $_POST['consultation_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['consultation_phone'] ) ) : '';
		$email  = isset( $_POST['consultation_email'] ) ? sanitize_email( wp_unslash( $_POST['consultation_email'] ) ) : '';
		$consent = ! empty( $_POST['consultation_consent'] );

		if ( trim( $name ) === '' ) {
			return new WP_Error( 'missing', __( 'Your name is required.', 'ccs-wp-theme' ) );
		}
		if ( trim( $phone ) === '' ) {
			return new WP_Error( 'missing', __( 'Your phone number is required.', 'ccs-wp-theme' ) );
		}
		if ( trim( $email ) === '' ) {
			return new WP_Error( 'missing', __( 'Your email is required.', 'ccs-wp-theme' ) );
		}
		if ( ! is_email( $email ) ) {
			return new WP_Error( 'invalid', __( 'Please enter a valid email address.', 'ccs-wp-theme' ) );
		}
		if ( ! $consent ) {
			return new WP_Error( 'consent', __( 'You must consent to us storing your details to submit this form.', 'ccs-wp-theme' ) );
		}

		$service = array();
		if ( ! empty( $_POST['consultation_service'] ) && is_array( $_POST['consultation_service'] ) ) {
			$allowed = array( 'domiciliary', 'respite', 'complex', 'not_sure' );
			foreach ( $_POST['consultation_service'] as $v ) {
				$v = sanitize_text_field( $v );
				if ( in_array( $v, $allowed, true ) ) {
					$service[] = $v;
				}
			}
		}

		$with_whom = '';
		if ( isset( $_POST['consultation_with_whom'] ) ) {
			$w = sanitize_text_field( wp_unslash( $_POST['consultation_with_whom'] ) );
			if ( in_array( $w, array( '', 'keelie_varney', 'nikki_mackay' ), true ) ) {
				$with_whom = $w;
			}
		}

		$date = isset( $_POST['consultation_preferred_date'] ) ? sanitize_text_field( wp_unslash( $_POST['consultation_preferred_date'] ) ) : '';
		$time = isset( $_POST['consultation_preferred_time'] ) ? sanitize_text_field( wp_unslash( $_POST['consultation_preferred_time'] ) ) : '';
		$message = isset( $_POST['consultation_message'] ) ? wp_kses_post( wp_unslash( $_POST['consultation_message'] ) ) : '';
		$newsletter = ! empty( $_POST['consultation_newsletter'] );

		return array(
			'name'            => $name,
			'phone'           => $phone,
			'email'           => $email,
			'service'         => $service,
			'with_whom'       => $with_whom,
			'preferred_date'  => $date,
			'preferred_time'  => $time,
			'message'         => $message,
			'newsletter'      => $newsletter,
		);
	}

	/**
	 * Save submission as ccs_enquiry post and meta.
	 *
	 * @param array $data Sanitized form data.
	 * @return int|WP_Error Post ID or error.
	 */
	private function save_enquiry( array $data ) {
		$title = sprintf(
			/* translators: 1: name, 2: date */
			__( '%1$s – %2$s', 'ccs-wp-theme' ),
			$data['name'],
			gmdate( 'Y-m-d H:i' )
		);

		$post_id = wp_insert_post( array(
			'post_type'   => self::CPT_ENQUIRY,
			'post_title'  => $title,
			'post_status' => 'private',
			'post_author' => 1,
		), true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, 'enquiry_name', $data['name'] );
		update_post_meta( $post_id, 'enquiry_phone', $data['phone'] );
		update_post_meta( $post_id, 'enquiry_email', $data['email'] );
		update_post_meta( $post_id, 'enquiry_care_type', implode( ', ', $data['service'] ) );
		update_post_meta( $post_id, 'enquiry_with_whom', $data['with_whom'] );
		update_post_meta( $post_id, 'enquiry_preferred_date', $data['preferred_date'] );
		update_post_meta( $post_id, 'enquiry_preferred_time', $data['preferred_time'] );
		update_post_meta( $post_id, 'enquiry_message', $data['message'] );
		update_post_meta( $post_id, 'enquiry_newsletter', $data['newsletter'] ? '1' : '0' );

		update_post_meta( $post_id, 'enquiry_submitted_at', current_time( 'mysql' ) );
		update_post_meta( $post_id, 'enquiry_submitted_ip', $this->get_client_ip() );

		return $post_id;
	}

	/**
	 * Get the Contact page slug used by theme activation.
	 *
	 * @return string
	 */
	public static function get_contact_page_slug() {
		return 'contact-us';
	}
}
