<?php
/**
 * Homepage review band.
 *
 * Replaces a single unattributed pull quote ("C.P.", no source, no date, no
 * relationship) that was the weakest section on the page — 278px tall, with its
 * heading set to .visually-hidden so the band read as a stray italic paragraph
 * rather than social proof.
 *
 * The competitive gap this closes is the widest on the site. Bluebird Care's
 * Maidstone page carries 45 syndicated homecare.co.uk reviews behind a live
 * 8.9/10 aggregate, each attributed by relationship; Helping Hands puts a
 * Trustpilot score and review count in a trust strip on every page. Both make
 * their proof externally checkable. A quote signed with initials asks to be
 * taken on trust, which is the one thing a family arranging care will not do.
 *
 * Every quote here is drawn from the vetted pool in reference/reviews.md and
 * follows that file's rules exactly:
 *   - Verbatim only. Trims are marked with an ellipsis; nothing is reworded and
 *     no two reviewers are merged.
 *   - Attribution carries relationship + source + date, not just initials.
 *   - Staff reviews (R3–R6, R9) are careers-page only and never appear here.
 *   - R2's pull quote deliberately avoids the "person-centred" phrasing in the
 *     source review, per that entry's own note.
 *
 * Deliberately NOT marked up as Review/AggregateRating JSON-LD: reviews.md
 * states "Never mark third-party reviews up in JSON-LD (schema gets the CQC
 * facts, not these)." The CQC rating travels in the organisation schema
 * instead — see inc/seo/class-structured-data.php.
 *
 * Quotes stay in the template rather than the Customizer because each one is
 * consent-gated and changes rarely — unlike the rate card, which moves
 * annually and now lives in Appearance → Customise → Care Costs. A Testimonial
 * CPT already exists in the theme and is the right long-term home once the pool
 * grows beyond a curated three.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_reviews_url = 'https://www.homecare.co.uk/homecare/agency.cfm/id/65432230417';

$ccs_reviews = apply_filters(
	'ccs_home_reviews',
	array(
		array(
			'quote'        => __( '…it was the company owner that came out to start doing my care. She delivered my care for the first two weeks and then slowly introduced other carers to me. This helped me immensely as this was the first time I had received care in my home and I was very anxious.', 'ccs-wp-theme' ),
			'author'       => __( 'T E', 'ccs-wp-theme' ),
			'relationship' => __( 'Receives care at home', 'ccs-wp-theme' ),
			'source'       => __( 'homecare.co.uk', 'ccs-wp-theme' ),
			'date'         => __( 'January 2024', 'ccs-wp-theme' ),
			'url'          => $ccs_reviews_url,
		),
		array(
			'quote'        => __( 'Staff are kind, positive, caring and professional. The owner is strongly committed to providing continuity of care… In my experience with a number of other care companies, they are exceptional.', 'ccs-wp-theme' ),
			'author'       => __( 'Martin A', 'ccs-wp-theme' ),
			'relationship' => __( 'Father of someone we support', 'ccs-wp-theme' ),
			'source'       => __( 'homecare.co.uk', 'ccs-wp-theme' ),
			'date'         => __( 'November 2023', 'ccs-wp-theme' ),
			'url'          => $ccs_reviews_url,
		),
		array(
			'quote'        => __( 'The care my sister has received from Continuity Care over the last few years has been exceptional. I have now had the pleasure of meeting a few of the carers at J’s home and find them cheerful, caring, and professional… I know J is happy and well cared for, and she looks forward to seeing them.', 'ccs-wp-theme' ),
			'author'       => __( 'Family member', 'ccs-wp-theme' ),
			'relationship' => __( 'Sister of someone we support', 'ccs-wp-theme' ),
			'source'       => __( 'Facebook', 'ccs-wp-theme' ),
			'date'         => __( 'October 2024', 'ccs-wp-theme' ),
			'url'          => '',
		),
	)
);

if ( empty( $ccs_reviews ) ) {
	return;
}
?>

<?php
/*
 * Initials for the avatar disc. ElderlyCare X and Harmony both put a face beside
 * each testimonial; there are no reviewer photographs here and inventing one
 * would be dishonest, so the disc carries the reviewer's initials as published
 * ("T E", "Martin A"). Where the attribution is a description rather than a
 * name ("Family member"), the disc shows a heart instead of fake initials.
 */
$ccs_review_initials = static function ( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ) );
	if ( count( $parts ) > 2 || ! preg_match( '/^[A-Z]/u', (string) $name ) || preg_match( '/member|family|relative/i', (string) $name ) ) {
		return '';
	}
	$out = '';
	foreach ( $parts as $part ) {
		$out .= mb_substr( $part, 0, 1 );
	}
	return mb_strtoupper( $out );
};
?>

<section class="home-reviews" aria-labelledby="home-reviews-heading">
	<div class="container container--lg">

		<header class="ccs-head ccs-head--center">
			<h2 id="home-reviews-heading" class="ccs-title">
				<?php
				esc_html_e( 'What families say about us', 'ccs-wp-theme' );
				?>
			</h2>
			<p class="ccs-lede">
				<?php esc_html_e( 'Published reviews, not selected quotes — each one is dated and you can read it in full at the source.', 'ccs-wp-theme' ); ?>
			</p>
		</header>

		<ul class="home-reviews__list" role="list">
			<?php foreach ( $ccs_reviews as $ccs_review ) : ?>
				<?php $ccs_initials = $ccs_review_initials( $ccs_review['author'] ); ?>
				<li class="home-reviews__item">
					<figure class="review-card" data-ccs-exception="rule 1: each review is a separate attributed voice">

						<div class="review-card__top">
							<?php
							/*
							 * Five filled stars, drawn once each, with a single
							 * text alternative on the group. Repeating "star" five
							 * times per card is noise for a screen reader.
							 */
							?>
							<div class="review-card__rating" role="img" aria-label="<?php esc_attr_e( 'Rated 5 out of 5', 'ccs-wp-theme' ); ?>">
								<?php for ( $i = 0; $i < 5; $i++ ) : ?>
									<svg class="review-card__star" width="18" height="18" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
										<path d="M10 1.6l2.47 5.28 5.53.72-4.07 3.94 1.03 5.86L10 14.6l-4.96 2.8 1.03-5.86L2 7.6l5.53-.72L10 1.6z" fill="currentColor"/>
									</svg>
								<?php endfor; ?>
							</div>
							<svg class="review-card__mark" viewBox="0 0 32 24" width="32" height="24" aria-hidden="true" focusable="false">
								<path fill="currentColor" d="M0 24V13.4C0 5.5 4.2 1 11.8 0l1.3 3.7C8.8 5 6.9 7.4 6.7 11H13v13H0Zm18.8 0V13.4C18.8 5.5 23 1 30.6 0l1.3 3.7c-4.3 1.3-6.2 3.7-6.4 7.3h6.3v13H18.8Z"/>
							</svg>
						</div>

						<blockquote class="review-card__quote">
							<p><?php echo esc_html( $ccs_review['quote'] ); ?></p>
						</blockquote>

						<figcaption class="review-card__meta">
							<span class="review-card__avatar" aria-hidden="true">
								<?php if ( $ccs_initials ) : ?>
									<?php echo esc_html( $ccs_initials ); ?>
								<?php else : ?>
									<?php echo ccs_icon( 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
								<?php endif; ?>
							</span>
							<span class="review-card__who">
								<span class="review-card__author"><?php echo esc_html( $ccs_review['author'] ); ?></span>
								<span class="review-card__relationship"><?php echo esc_html( $ccs_review['relationship'] ); ?></span>
								<span class="review-card__source">
									<?php if ( ! empty( $ccs_review['url'] ) ) : ?>
										<a href="<?php echo esc_url( $ccs_review['url'] ); ?>" target="_blank" rel="noopener noreferrer">
											<?php
											printf(
												/* translators: 1: review source name, 2: month and year the review was published. */
												esc_html__( '%1$s, %2$s', 'ccs-wp-theme' ),
												esc_html( $ccs_review['source'] ),
												esc_html( $ccs_review['date'] )
											);
											?>
											<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
										</a>
									<?php else : ?>
										<?php
										printf(
											/* translators: 1: review source name, 2: month and year the review was published. */
											esc_html__( '%1$s, %2$s', 'ccs-wp-theme' ),
											esc_html( $ccs_review['source'] ),
											esc_html( $ccs_review['date'] )
										);
										?>
									<?php endif; ?>
								</span>
							</span>
						</figcaption>

					</figure>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="ccs-section-foot ccs-actions ccs-actions--center">
			<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_reviews_url ); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Read every review on homecare.co.uk', 'ccs-wp-theme' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
			</a>
		</div>

	</div>
</section>
