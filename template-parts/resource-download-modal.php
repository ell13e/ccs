<?php
/**
 * Resource download modal (lead capture) for the downloadable resources page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$privacy_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'privacy' ) : '';
if ( ! $privacy_url ) {
	$privacy_page = get_page_by_path( 'privacy-policy' );
	$privacy_url  = $privacy_page ? get_permalink( $privacy_page ) : home_url( '/privacy-policy/' );
}
?>

<div id="resource-download-modal" class="resource-download-modal" role="dialog" aria-modal="true" aria-labelledby="resource-download-title" aria-hidden="true">
	<div class="resource-download-backdrop" data-resource-modal-close aria-hidden="true"></div>

	<div class="resource-download-card" role="document">
		<button type="button" class="resource-download-close" data-resource-modal-close aria-label="<?php esc_attr_e( 'Close download form', 'ccs-wp-theme' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
		</button>

		<div class="resource-download-header">
			<div class="resource-download-icon" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
			</div>
			<h2 id="resource-download-title" class="resource-download-title"><?php esc_html_e( 'Get this care guide', 'ccs-wp-theme' ); ?></h2>
			<p class="resource-download-subtitle" id="resource-download-subtitle"><?php esc_html_e( "We'll email you a secure download link.", 'ccs-wp-theme' ); ?></p>
		</div>

		<form id="resource-download-form" class="resource-download-form" novalidate>
			<input type="hidden" name="resource_id" id="resource-download-resource-id" value="">

			<div class="resource-download-grid">
				<div class="resource-download-field">
					<label for="resource-first-name" class="resource-download-label"><?php esc_html_e( 'First name', 'ccs-wp-theme' ); ?> <span class="required-indicator" aria-label="<?php esc_attr_e( 'required', 'ccs-wp-theme' ); ?>">*</span></label>
					<input type="text" id="resource-first-name" name="first_name" class="resource-download-input" autocomplete="given-name" required aria-required="true" aria-describedby="resource-first-name-error" aria-invalid="false">
					<p id="resource-first-name-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
				</div>

				<div class="resource-download-field">
					<label for="resource-last-name" class="resource-download-label"><?php esc_html_e( 'Last name', 'ccs-wp-theme' ); ?> <span class="required-indicator" aria-label="<?php esc_attr_e( 'required', 'ccs-wp-theme' ); ?>">*</span></label>
					<input type="text" id="resource-last-name" name="last_name" class="resource-download-input" autocomplete="family-name" required aria-required="true" aria-describedby="resource-last-name-error" aria-invalid="false">
					<p id="resource-last-name-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
				</div>

				<div class="resource-download-field resource-download-field-full">
					<label for="resource-email" class="resource-download-label"><?php esc_html_e( 'Email', 'ccs-wp-theme' ); ?> <span class="required-indicator" aria-label="<?php esc_attr_e( 'required', 'ccs-wp-theme' ); ?>">*</span></label>
					<input type="email" id="resource-email" name="email" class="resource-download-input" autocomplete="email" required aria-required="true" aria-describedby="resource-email-error" aria-invalid="false">
					<p id="resource-email-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
				</div>

				<div class="resource-download-field">
					<label for="resource-phone" class="resource-download-label"><?php esc_html_e( 'Phone number', 'ccs-wp-theme' ); ?> <span class="optional-indicator">(<?php esc_html_e( 'optional', 'ccs-wp-theme' ); ?>)</span></label>
					<input type="tel" id="resource-phone" name="phone" class="resource-download-input" autocomplete="tel" aria-describedby="resource-phone-error" aria-invalid="false">
					<p id="resource-phone-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
				</div>

				<div class="resource-download-field">
					<label for="resource-dob" class="resource-download-label"><?php esc_html_e( 'Date of birth', 'ccs-wp-theme' ); ?> <span class="optional-indicator">(<?php esc_html_e( 'optional', 'ccs-wp-theme' ); ?>)</span></label>
					<input type="date" id="resource-dob" name="date_of_birth" class="resource-download-input" aria-describedby="resource-dob-error" aria-invalid="false" max="<?php echo esc_attr( date( 'Y-m-d', strtotime( '-13 years' ) ) ); ?>">
					<p id="resource-dob-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
				</div>
			</div>

			<div class="resource-download-consent">
				<div class="resource-download-consent-row">
					<input type="checkbox" id="resource-consent" name="consent" class="resource-download-checkbox" required aria-required="true" aria-describedby="resource-consent-error" aria-invalid="false">
					<label for="resource-consent" class="resource-download-consent-label">
						<?php
						printf(
							/* translators: 1: site name, 2: privacy policy URL */
							esc_html__( 'I consent to receiving emails, resources, updates and marketing from %1$s. %2$s', 'ccs-wp-theme' ),
							esc_html( get_bloginfo( 'name' ) ),
							'<a class="resource-download-privacy-link" href="' . esc_url( $privacy_url ) . '">' . esc_html__( 'Privacy Policy', 'ccs-wp-theme' ) . '</a>'
						);
						?>
						<span class="required-indicator" aria-label="<?php esc_attr_e( 'required', 'ccs-wp-theme' ); ?>">*</span>
					</label>
				</div>
				<p id="resource-consent-error" class="resource-download-error" role="alert" aria-live="polite" style="display:none"></p>
			</div>

			<button type="submit" class="btn btn-primary resource-download-submit">
				<?php esc_html_e( 'Email me the care guide', 'ccs-wp-theme' ); ?>
			</button>

			<p class="resource-download-note" id="resource-download-note"><?php esc_html_e( "You'll receive the care guide by email.", 'ccs-wp-theme' ); ?></p>
		</form>
	</div>
</div>
