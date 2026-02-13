<?php
/**
 * Homepage CQC regulatory section (trust & authority).
 * Official CQC widget, fallback link, reserved height for CLS.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cqc_location_id = '1-2624556588';
$cqc_widget_src  = 'https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=' . esc_attr( $cqc_location_id ) . '&data-host=https://www.cqc.org.uk&type=location';
$cqc_fallback_url = 'https://www.cqc.org.uk/location/' . $cqc_location_id;
?>

<section class="cqc-section" aria-labelledby="cqc-section-heading">
	<div class="cqc-section__inner">
		<h2 id="cqc-section-heading" class="cqc-section__heading" data-animate="fade-up" data-delay="0">
			<?php esc_html_e( 'Regulated, rated, and reliable home care across Maidstone & Kent', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="cqc-section__subheading" data-animate="fade-up" data-delay="100">
			<?php esc_html_e( "Proud to be rated 'Good' by the CQC", 'ccs-wp-theme' ); ?>
		</p>

		<div class="cqc-section__widget-card" data-animate="fade-up" data-delay="200" role="complementary" aria-label="<?php esc_attr_e( 'Care Quality Commission rating and registration', 'ccs-wp-theme' ); ?>">
			<div class="cqc-section__widget-wrap" id="cqc-widget-mount">
				<div class="cqc-section__skeleton" aria-hidden="true">
					<span class="cqc-section__skeleton-line cqc-section__skeleton-line--title"></span>
					<span class="cqc-section__skeleton-line cqc-section__skeleton-line--body"></span>
					<span class="cqc-section__skeleton-line cqc-section__skeleton-line--body cqc-section__skeleton-line--short"></span>
				</div>
				<script type="text/javascript" src="<?php echo esc_url( $cqc_widget_src ); ?>" defer></script>
			</div>
		</div>

		<noscript>
			<p class="cqc-section__fallback">
				<a href="<?php echo esc_url( $cqc_fallback_url ); ?>"
				   target="_blank"
				   rel="noopener noreferrer"
				   class="cqc-section__fallback-link"
				   aria-label="<?php esc_attr_e( 'View our CQC report and rating on the Care Quality Commission website (opens in new tab)', 'ccs-wp-theme' ); ?>">
					<?php esc_html_e( 'View our CQC report and rating on the CQC website', 'ccs-wp-theme' ); ?>
				</a>
			</p>
		</noscript>
	</div>
</section>
