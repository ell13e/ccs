<?php
/**
 * The CQC rating card in inner-page heroes (Nov 2026).
 *
 * The client pointed to ultimacare.co.uk, which shows its CQC rating beside
 * the heading of every page, not only the homepage. This is the regulator's
 * own live widget, the same one the homepage hero uses (template-parts/home/
 * hero.php), so the rating is always CQC's current one and never typed in by
 * us. If the script is blocked or CQC is down, the official "Inspected and
 * rated" artwork shows instead, linking to the same report.
 *
 * Shown on the care side only: not on careers pages (a job seeker is not
 * choosing a provider), not on policy pages, and not on the CQC page, which
 * carries the widget in its own section.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( get_theme_mod( 'ccs_cqc_widget_hide', false )
	|| ( function_exists( 'ccs_is_careers_context' ) && ccs_is_careers_context() )
	|| is_page_template( 'page-templates/template-cqc.php' )
	|| is_page( array( 'privacy-policy', 'terms-and-conditions', 'accessibility-statement', 'cookies' ) )
) {
	return;
}

$ccs_cqc_id    = get_theme_mod( 'ccs_cqc_widget_data_id', '1-2624556588' );
$ccs_cqc_id    = $ccs_cqc_id ? $ccs_cqc_id : '1-2624556588';
$ccs_cqc_url   = defined( 'CCS_CQC_REPORT_URL' ) ? CCS_CQC_REPORT_URL : get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' );
$ccs_cqc_badge = 'cqc-badges/CQC inspected and rated good RGB.jpg';
$ccs_cqc_has   = file_exists( get_template_directory() . '/assets/images/' . $ccs_cqc_badge );
?>
<div class="page-cqc">
	<?php if ( $ccs_cqc_has ) : ?>
		<a id="page-cqc-fallback" class="page-cqc__fallback" href="<?php echo esc_url( $ccs_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . str_replace( ' ', '%20', $ccs_cqc_badge ) ); ?>" alt="<?php esc_attr_e( 'CQC inspected and rated Good (opens in a new window)', 'ccs-wp-theme' ); ?>" width="1184" height="821"<?php echo ccs_theme_image_srcset( $ccs_cqc_badge, '176px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?> loading="lazy" decoding="async">
		</a>
	<?php endif; ?>
	<script
		type="text/javascript"
		src="<?php echo esc_url( 'https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=' . rawurlencode( $ccs_cqc_id ) . '&data-host=https://www.cqc.org.uk&type=location' ); ?>"
		onerror="var f=document.getElementById('page-cqc-fallback');if(f){f.classList.add('is-visible');}"
	></script>
</div>
