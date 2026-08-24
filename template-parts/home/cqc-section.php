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
			<?php
			/*
			 * Eyebrow, in the shared .ccs-eyebrow component every section on this
			 * page now opens with. Not decoration: this band's whole job is to say
			 * that the rating comes from outside the company, and the heading alone
			 * ("Rated Good by the CQC") does not distinguish a regulator's finding
			 * from a self-description. The label does that before the heading is
			 * read.
			 */
			?>
			<p class="ccs-eyebrow"><?php esc_html_e( 'Independently regulated', 'ccs-wp-theme' ); ?></p>
			<h2 id="home-cqc-heading" class="home-cqc__heading">
				<?php esc_html_e( 'Rated Good by the CQC', 'ccs-wp-theme' ); ?>
			</h2>
			<?php
			/*
			 * Previously read "Every home care provider in England is inspected by the
			 * Care Quality Commission", which is the opposite of the truth and gave
			 * away the strongest thing this section has to say.
			 *
			 * Registration is mandatory; assessment is not happening. Homecare
			 * Association analysis published 10 June 2026, using CQC data from
			 * 5 May 2026 across ~14,597 community social care locations, found 83.5%
			 * had no current rating — 36.9% never assessed at all, 46.6% carrying one
			 * four to ten years old. That share has gone 60% (2024) → 70% (2025) →
			 * 83.5% (2026), and the number of locations holding a current rating fell
			 * from 5,118 to 2,413.
			 * https://www.homecareassociation.org.uk/resource/unseen-and-unrated-more-than-four-in-five-community-social-care-services-in-england-have-no-current-cqc-rating-new-analysis-finds.html
			 *
			 * Deliberately says "around one in six" rather than printing 16.5%: the
			 * figure moves every year and a stale statistic on the page would be a
			 * worse error than a rounded one. Revisit when the next analysis lands.
			 *
			 * NOTE: this copy does not claim CCS's own rating is current, because that
			 * depends on the last inspection date. If it is within four years, saying
			 * so explicitly here is the single strongest sentence available.
			 */
			?>
			<p class="home-cqc__subheading">
				<?php
				printf(
					/* translators: %s: linked phrase citing the Homecare Association analysis. */
					esc_html__( 'Only around one in six home care services in England has a current CQC rating — %s. Ours is published by the regulator, not by us, and you can read it yourself.', 'ccs-wp-theme' ),
					sprintf(
						'<a class="home-cqc__source" href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="screen-reader-text">%3$s</span></a>',
						esc_url( 'https://www.homecareassociation.org.uk/resource/unseen-and-unrated-more-than-four-in-five-community-social-care-services-in-england-have-no-current-cqc-rating-new-analysis-finds.html' ),
						esc_html__( 'most have either never been assessed or carry one that is years out of date', 'ccs-wp-theme' ),
						esc_html__( ' (source: Homecare Association analysis, June 2026, opens in a new window)', 'ccs-wp-theme' )
					)
				);
				?>
			</p>
		</div>

		<?php
		/*
		 * The badge now sits in a card with the report link inside it, rather
		 * than floating loose in the right-hand column with the link stranded
		 * back under the paragraph on the left.
		 *
		 * Two problems, one fix. The badge is a ~165px box; against a 1280px
		 * container that left roughly a third of this band visibly empty
		 * between the prose and the artwork, which is what made the site's most
		 * important trust section read as the least designed one. And the proof
		 * (the badge) and the way to check it (the link) were in different
		 * columns, so neither carried the other. As one bounded unit the card
		 * has enough width to balance the prose, and "here is the rating, here
		 * is the report" reads as a single claim.
		 */
		?>
		<div class="home-cqc__badge ccs-surface ccs-surface--pad">
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
					 *
					 * Hidden by CSS on the front page above 767px, where the hero paints
					 * this same badge barely a screen earlier — see .home-cqc__badge in
					 * homepage.css. That has to be a media query rather than a PHP check:
					 * the hero badge is hidden below 767px, so gating it in PHP would
					 * leave mobile users with no badge at all when the widget is blocked,
					 * which is the exact failure this fallback exists to prevent.
					 */ ?>
					<?php
					/*
					 * No inline style here. It used to carry style="display:none;", with
					 * the onerror handler below flipping it to style="display:block" —
					 * which meant the front-page desktop suppression rule three lines
					 * away in homepage.css could only ever win with !important, because
					 * nothing else outranks an inline style. Visibility is a class now
					 * (.is-visible, toggled by onerror instead of a style property), so it
					 * is decided by ordinary CSS specificity: the suppression rule
					 * (body.home .home-cqc__badge #cqc-widget-fallback.is-visible) simply
					 * carries more selectors than the base show rule and wins on its own.
					 */
					?>
					<a
						id="cqc-widget-fallback"
						href="<?php echo esc_url( $cqc_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
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
						onerror="var f=document.getElementById('cqc-widget-fallback');if(f){f.classList.add('is-visible');}"
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
