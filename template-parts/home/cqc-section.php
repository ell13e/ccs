<?php
/**
 * Homepage CQC section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * CQC profile URLs must use /location/, not /provider/ — a location-level
 * registration ID resolves to a 404 under /provider/. Flagged four times in
 * reference/original-build-brief.md; the old /provider/ fallback here was
 * what actually shipped, since CCS_CQC_REPORT_URL is never defined.
 */
$cqc_url   = defined( 'CCS_CQC_REPORT_URL' ) ? CCS_CQC_REPORT_URL : get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' );
$cqc_hide  = get_theme_mod( 'ccs_cqc_widget_hide', false );
$cqc_id    = get_theme_mod( 'ccs_cqc_widget_data_id', '1-2624556588' );
$cqc_id    = $cqc_id ? $cqc_id : '1-2624556588';
// Official CQC artwork — same file the hero's floating badge uses, so a
// blocked/failed widget script (ad blockers, a strict CSP, cqc.org.uk being
// down) still leaves a real, on-brand badge rather than an empty box.
$cqc_badge     = get_template_directory() . '/assets/images/cqc-badges/CQC inspected and rated good RGB.jpg';
$cqc_badge_url = get_template_directory_uri() . '/assets/images/cqc-badges/CQC inspected and rated good RGB.jpg';
$cqc_has_badge = file_exists( $cqc_badge );
?>

<section class="home-cqc" aria-labelledby="home-cqc-heading">
	<div class="home-cqc__inner container container--lg">

		<div class="home-cqc__content">
			<h2 id="home-cqc-heading" class="home-cqc__heading">
				<?php esc_html_e( 'Rated Good by the CQC', 'ccs-wp-theme' ); ?>
			</h2>
			<p class="home-cqc__subheading">
				<?php esc_html_e( 'Every home care provider in England is inspected by the Care Quality Commission. Ours is a rating you can check yourself, not a badge we gave ourselves.', 'ccs-wp-theme' ); ?>
			</p>
			<p class="home-cqc__link-wrap">
				<a href="<?php echo esc_url( $cqc_url ); ?>" class="home-cqc__link" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Read our full CQC report', 'ccs-wp-theme' ); ?>
					<span aria-hidden="true">&rarr;</span>
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
				</a>
			</p>
		</div>

		<div class="home-cqc__badge">
			<?php if ( ! $cqc_hide ) : ?>
				<?php if ( $cqc_has_badge ) : ?>
					<?php /*
					 * Fallback badge, hidden by default and only revealed if the live
					 * widget script fails to load or execute — a strict CSP, an ad
					 * blocker, or cqc.org.uk itself being down all leave real users
					 * with a blank box otherwise (exactly what happens under this
					 * theme's own local-preview CSP). `<noscript>` alone doesn't cover
					 * that case, since JS is still enabled; the script's `onerror`
					 * does.
					 */ ?>
					<a
						id="cqc-widget-fallback"
						href="<?php echo esc_url( $cqc_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						style="display:none;"
					>
						<img
							src="<?php echo esc_url( $cqc_badge_url ); ?>"
							alt="<?php esc_attr_e( 'CQC inspected and rated Good', 'ccs-wp-theme' ); ?>"
							class="home-cqc__badge-img"
							width="1184"
							height="821"
						>
					</a>
				<?php endif; ?>
				<script
					type="text/javascript"
					src="<?php echo esc_url( 'https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=' . rawurlencode( $cqc_id ) . '&data-host=https://www.cqc.org.uk&type=location' ); ?>"
					<?php if ( $cqc_has_badge ) : ?>
						onerror="var f=document.getElementById('cqc-widget-fallback');if(f){f.style.display='block';}"
					<?php endif; ?>
				></script>
			<?php elseif ( $cqc_has_badge ) : ?>
				<a href="<?php echo esc_url( $cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
					<img
						src="<?php echo esc_url( $cqc_badge_url ); ?>"
						alt="<?php esc_attr_e( 'CQC inspected and rated Good', 'ccs-wp-theme' ); ?>"
						class="home-cqc__badge-img"
						width="1184"
						height="821"
						loading="lazy"
						decoding="async"
					>
				</a>
			<?php endif; ?>
		</div>

	</div>
</section>
