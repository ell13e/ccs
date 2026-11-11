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
