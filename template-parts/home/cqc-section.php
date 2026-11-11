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
			 * Eyebrow: one of only two on the homepage (the other is costs' "No
			 * hidden fees"), kept because it says something the heading cannot.
			 * Not decoration: this band's whole job is to say
			 * that the rating comes from outside the company, and the heading alone
			 * ("Rated Good by the CQC") does not distinguish a regulator's finding
			 * from a self-description. The label does that before the heading is
			 * read.
			 */
			?>
			<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'cqc_eyebrow' ) ); ?></p>
			<h2 id="home-cqc-heading" class="ccs-title">
				<?php
				// The word between *asterisks* gets the brush underline; ccs_copy_accent() escapes the rest.
				echo ccs_copy_accent( 'cqc_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
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
			<p class="ccs-lede" data-ccs-exception="rule 10: a sourced statistic that cannot be shortened, centred over the regulator's tile; three short lines at desktop">
				<?php
				printf(
					/* translators: %s: linked phrase citing the Homecare Association analysis. */
					esc_html__( 'Only around one in six home care services in England has a current CQC rating: %s. Ours is published by the regulator, so you can read it for yourself.', 'ccs-wp-theme' ),
					sprintf(
						'<a class="home-cqc__source" href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="screen-reader-text">%3$s</span></a>',
						esc_url( 'https://www.homecareassociation.org.uk/resource/unseen-and-unrated-more-than-four-in-five-community-social-care-services-in-england-have-no-current-cqc-rating-new-analysis-finds.html' ),
						esc_html__( 'most have either never been assessed or carry one that is years out of date', 'ccs-wp-theme' ),
						esc_html__( ' (source: Homecare Association analysis, June 2026, opens in a new window)', 'ccs-wp-theme' )
					)
				);
				?>
			</p>
			<?php
			/*
			 * No separate "Read our full CQC report" button (Nov 2026): the
			 * regulator's tile below already links to the report, and the two
			 * side by side said the same thing twice.
			 */
			?>
		</div>

		<?php
		/*
		 * The rating, set out in the site's own type rather than the regulator's
		 * widget in a box (client, Nov 2026: "style this nicer without just
		 * shoving it in a card"). It shows what CQC asks providers to display
		 * online: the overall rating, the date, and a link to the report on
		 * cqc.org.uk. The rating and date come from Customize > CQC Information,
		 * so they must be updated there after each inspection; the widget used to
		 * do that automatically.
		 */
		$cqc_ratings = array(
			'outstanding'          => __( 'Outstanding', 'ccs-wp-theme' ),
			'good'                 => __( 'Good', 'ccs-wp-theme' ),
			'requires-improvement' => __( 'Requires improvement', 'ccs-wp-theme' ),
			'inadequate'           => __( 'Inadequate', 'ccs-wp-theme' ),
		);
		$cqc_rating_key  = (string) get_theme_mod( 'ccs_cqc_rating', '' );
		$cqc_rating_key  = isset( $cqc_ratings[ $cqc_rating_key ] ) ? $cqc_rating_key : 'good';
		$cqc_rating_date = (string) get_theme_mod( 'ccs_cqc_rating_date', '6 December 2023' );
		?>
		<dl class="cqc-rating cqc-rating--<?php echo esc_attr( $cqc_rating_key ); ?>">
			<div class="cqc-rating__item">
				<dt class="cqc-rating__label"><?php esc_html_e( 'CQC overall rating', 'ccs-wp-theme' ); ?></dt>
				<dd class="cqc-rating__value">
					<span class="cqc-rating__dot" aria-hidden="true"></span>
					<?php echo esc_html( $cqc_ratings[ $cqc_rating_key ] ); ?>
				</dd>
			</div>
			<?php if ( '' !== $cqc_rating_date ) : ?>
				<div class="cqc-rating__item">
					<dt class="cqc-rating__label"><?php esc_html_e( 'Rating published', 'ccs-wp-theme' ); ?></dt>
					<dd class="cqc-rating__value cqc-rating__value--date"><?php echo esc_html( $cqc_rating_date ); ?></dd>
				</div>
			<?php endif; ?>
			<div class="cqc-rating__item">
				<dt class="cqc-rating__label"><?php esc_html_e( 'Full report', 'ccs-wp-theme' ); ?></dt>
				<dd class="cqc-rating__value cqc-rating__value--link">
					<a href="<?php echo esc_url( $cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Read it on cqc.org.uk', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
					</a>
				</dd>
			</div>
		</dl>

	</div>
</section>
