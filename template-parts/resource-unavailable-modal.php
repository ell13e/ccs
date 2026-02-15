<?php
/**
 * Modal shown when a care guide (resource) is temporarily unavailable.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="resource-unavailable-modal" class="resource-unavailable-modal" role="dialog" aria-modal="true" aria-labelledby="resource-unavailable-title" aria-hidden="true">
	<div class="resource-unavailable-backdrop" data-resource-unavailable-close aria-hidden="true"></div>
	<div class="resource-unavailable-card" role="document">
		<button type="button" class="resource-unavailable-close" data-resource-unavailable-close aria-label="<?php esc_attr_e( 'Close', 'ccs-wp-theme' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
		</button>
		<div class="resource-unavailable-body">
			<h2 id="resource-unavailable-title" class="resource-unavailable-title"><?php esc_html_e( 'Care guide temporarily unavailable', 'ccs-wp-theme' ); ?></h2>
			<p id="resource-unavailable-subtitle" class="resource-unavailable-text"><?php esc_html_e( 'This care guide is not available for download right now. Please try again later or contact us if you need help.', 'ccs-wp-theme' ); ?></p>
			<?php
			$contact_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
			?>
			<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact us', 'ccs-wp-theme' ); ?></a>
		</div>
	</div>
</div>
