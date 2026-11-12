<?php
/**
 * Shared form parts.
 *
 * "How did you hear about us?" is asked on every form the theme renders
 * (contact, switching, service call-back, care guide download) at the
 * client's request (Nov 2026), so the office can see which routes bring
 * enquiries in. One list of answers here, so every form and every place the
 * answer is shown use the same wording.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The answers, key => label. Keys are stored for validation only; the label
 * is what gets saved, so the inbox, emails and exports read in plain words.
 *
 * @return array<string,string>
 */
function ccs_heard_about_options() {
	return apply_filters(
		'ccs_heard_about_options',
		array(
			'search'         => __( 'Searching online (Google or similar)', 'ccs-wp-theme' ),
			'recommendation' => __( 'Recommended by family or a friend', 'ccs-wp-theme' ),
			'health'         => __( 'NHS, GP or hospital team', 'ccs-wp-theme' ),
			'council'        => __( 'Social worker or Kent County Council', 'ccs-wp-theme' ),
			'social'         => __( 'Facebook or social media', 'ccs-wp-theme' ),
			'knows-us'       => __( 'Someone we already support or employ', 'ccs-wp-theme' ),
			'other'          => __( 'Somewhere else', 'ccs-wp-theme' ),
		)
	);
}

/**
 * The submitted answer as its label, or '' when missing or not on the list.
 *
 * @param string $field POST field name.
 * @return string
 */
function ccs_heard_about_from_post( $field ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- each handler verifies its own nonce before calling this.
	$key     = isset( $_POST[ $field ] ) ? sanitize_key( wp_unslash( $_POST[ $field ] ) ) : '';
	$options = ccs_heard_about_options();
	return isset( $options[ $key ] ) ? $options[ $key ] : '';
}

/**
 * Print the question as a required dropdown (seven answers: more than the
 * two to four that rule 20 shows as radios).
 *
 * @param string $id    Element id.
 * @param string $name  Field name.
 * @param array  $args  'field_class', 'label_class', 'input_class' to fit a form's own markup.
 */
function ccs_heard_about_field( $id, $name = 'enquiry_heard_about', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'field_class' => 'ccs-field',
			'label_class' => 'ccs-field__label',
			'input_class' => 'ccs-field__input ccs-field__select',
		)
	);
	?>
	<div class="<?php echo esc_attr( $args['field_class'] ); ?>">
		<label for="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $args['label_class'] ); ?>"><?php esc_html_e( 'How did you hear about us?', 'ccs-wp-theme' ); ?></label>
		<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="<?php echo esc_attr( $args['input_class'] ); ?>" required aria-required="true" data-ccs-error="<?php esc_attr_e( 'Please tell us how you heard about us.', 'ccs-wp-theme' ); ?>">
			<option value=""><?php esc_html_e( 'Choose one', 'ccs-wp-theme' ); ?></option>
			<?php foreach ( ccs_heard_about_options() as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
}

/**
 * Which page the person was looking at when they asked us to get in touch
 * (client, Nov 2026: "if they say book free consultation from whatever page
 * it is clear what they've been looking into").
 *
 * The form sends one URL in `enquiry_booked_from` (assets/js/form-handler.js):
 * for the contact and switching forms, the page that sent them there; for a
 * form on the page itself, such as the service call-back, that page. Nothing
 * is stored in the browser to do this; it is the address of the page they
 * clicked from, sent with the form they chose to submit.
 *
 * Only addresses on this site are accepted. The page's own title is looked up
 * here rather than trusting text from the browser.
 *
 * @return array{label:string,url:string} Empty strings when nothing usable was sent.
 */
function ccs_enquiry_booked_from() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- each handler verifies its own nonce before calling this.
	$raw  = isset( $_POST['enquiry_booked_from'] ) ? esc_url_raw( wp_unslash( $_POST['enquiry_booked_from'] ) ) : '';
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$form = isset( $_POST['enquiry_form_page'] ) ? esc_url_raw( wp_unslash( $_POST['enquiry_form_page'] ) ) : '';
	$home = wp_parse_url( home_url( '/' ) );

	$same_site = static function ( $url ) use ( $home ) {
		$parts = $url ? wp_parse_url( $url ) : false;
		return $parts && isset( $parts['host'], $home['host'] ) && strtolower( $parts['host'] ) === strtolower( $home['host'] );
	};

	if ( ! $same_site( $raw ) ) {
		if ( ! $same_site( $form ) ) {
			return array( 'label' => '', 'url' => '' );
		}
		$page = ccs_enquiry_page_label( $form );
		return array(
			/* translators: %s: name of the page the form is on. */
			'label' => sprintf( __( 'Came straight to the form (%s)', 'ccs-wp-theme' ), $page ),
			'url'   => $form,
		);
	}

	return array( 'label' => ccs_enquiry_page_label( $raw ), 'url' => strtok( $raw, '?#' ) );
}

/**
 * A page address as words the office recognises: "Complex Care (service
 * page)", "Home care in Tonbridge (area page)", "Homepage".
 *
 * @param string $url Same-site URL.
 * @return string
 */
function ccs_enquiry_page_label( $url ) {
	$clean = strtok( $url, '?#' );
	if ( untrailingslashit( $clean ) === untrailingslashit( home_url( '/' ) ) ) {
		return __( 'Homepage', 'ccs-wp-theme' );
	}

	$post_id = url_to_postid( $clean );
	if ( ! $post_id ) {
		$path = wp_parse_url( $clean, PHP_URL_PATH );
		return $path ? $path : $clean;
	}

	$types = array(
		'service'  => __( 'service page', 'ccs-wp-theme' ),
		'location' => __( 'area page', 'ccs-wp-theme' ),
		'post'     => __( 'article', 'ccs-wp-theme' ),
	);
	$type  = get_post_type( $post_id );
	$title = wp_strip_all_tags( get_the_title( $post_id ) );

	return isset( $types[ $type ] ) ? sprintf( '%s (%s)', $title, $types[ $type ] ) : $title;
}

/**
 * Save the booked-from page on an enquiry record.
 *
 * @param int $post_id Enquiry post ID.
 */
function ccs_save_enquiry_booked_from( $post_id ) {
	$from = ccs_enquiry_booked_from();
	if ( '' === $from['label'] ) {
		return;
	}
	update_post_meta( $post_id, 'enquiry_booked_from', $from['label'] );
	update_post_meta( $post_id, 'enquiry_booked_from_url', $from['url'] );
}
