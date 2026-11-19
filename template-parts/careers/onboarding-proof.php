<?php
/**
 * What new starters say: figures and comments from the company's own
 * onboarding feedback form.
 *
 * Source (Nov 2026, supplied by the user): "Onboarding feedback form", 27
 * entries, of which two are test entries and are not counted, leaving 25 new
 * starters. Every figure below is a count from that form, and each comment is
 * a respondent's own words, shown with "New starter" and their job role, no
 * name. Use whole sentences: the first set were one-line answers to "what
 * was the highlight?" and the user pointed out they made no sense alone.
 *
 * The user's own summary of the form counted all 27 entries (4.93 out of 5,
 * "27 out of 27"). The site uses the 25 real ones: 4.92 is (23 x 5 + 2 x 4)
 * / 25; 24 of 25 found applying easy (one answered "extremely not easy");
 * 24 people rated the interview and all said very good or excellent, but
 * "24 of 24" beside "25 new starters" read as a mistake (user), so that line
 * was swapped for one all 25 answered: the onboarding materials were
 * extremely (18) or somewhat (7) easy to follow.
 * Nothing is rounded up or reworded. Both lists are editable (Customize > Page wording >
 * Careers) so they can be updated when the survey is run again.
 *
 * The first figure in the list is the headline one: it sits on the photo.
 * The comment on show carries the mint bar that marks a quotation everywhere
 * on the site (rule 2).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_op_pairs = static function ( $text ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$bits = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 === count( $bits ) && '' !== $bits[0] ) {
			$out[] = $bits;
		}
	}
	return $out;
};
$ccs_op_stats  = $ccs_op_pairs( ccs_copy( 'careers_proof_stats' ) );
$ccs_op_quotes = $ccs_op_pairs( ccs_copy( 'careers_proof_quotes' ) );
if ( empty( $ccs_op_stats ) && empty( $ccs_op_quotes ) ) {
	return;
}
?>
<?php
/*
 * Second layout (user: "redesign"). The first set the figures as a ruled
 * stack beside four comments under mint bars: two columns of loose text.
 * Now it is led by a photo, with the headline figure on a white tab lapping
 * the photo's corner (as the company logos do on About); the comments turn
 * one at a time, large, with dots and arrows (the stepper's script); and the
 * other figures are ticked lines under them. The user: "this concept is
 * really good, it just needs smoothing", so the spacing was evened and the
 * comments now cross-fade. (A plum number panel replaced the photo for a few
 * minutes in between and was put back.)
 */
$ccs_op_lead = ! empty( $ccs_op_stats ) ? array_shift( $ccs_op_stats ) : null;
?>
<section class="ccs-section ccs-section--flush-bottom careers-proof" aria-labelledby="careers-proof-heading">
	<div class="container container--lg">
		<div class="ccs-media-split ccs-media-split--flip careers-proof__layout">
			<div class="careers-proof__text">
				<h2 id="careers-proof-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'careers_proof_title' ) ); ?></h2>

				<?php if ( ! empty( $ccs_op_quotes ) ) : ?>
					<div class="quote-rotator" data-ccs-stepper>
						<span class="quote-rotator__mark" aria-hidden="true">&ldquo;</span>
						<div class="quote-rotator__panels">
							<?php foreach ( $ccs_op_quotes as $ccs_op_i => $ccs_op_quote ) : ?>
								<figure class="quote-rotator__panel" role="tabpanel" id="proof-quote-<?php echo (int) $ccs_op_i; ?>" aria-labelledby="proof-dot-<?php echo (int) $ccs_op_i; ?>">
									<blockquote><p><?php echo esc_html( $ccs_op_quote[0] ); ?></p></blockquote>
									<figcaption><?php echo esc_html( $ccs_op_quote[1] ); ?></figcaption>
								</figure>
							<?php endforeach; ?>
						</div>
						<?php if ( count( $ccs_op_quotes ) > 1 ) : ?>
							<?php // Round previous / next buttons with small dots between: the usual, expected testimonial controls (rule 18). ?>
							<div class="quote-rotator__controls">
								<button type="button" class="quote-rotator__arrow" data-ccs-step-prev aria-label="<?php esc_attr_e( 'Previous comment', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&larr;</span></button>
								<div class="quote-rotator__dots" role="tablist" aria-label="<?php esc_attr_e( 'Comments from new starters', 'ccs-wp-theme' ); ?>">
									<?php foreach ( $ccs_op_quotes as $ccs_op_i => $ccs_op_quote ) : ?>
										<button type="button" class="quote-rotator__dot" role="tab" id="proof-dot-<?php echo (int) $ccs_op_i; ?>" aria-controls="proof-quote-<?php echo (int) $ccs_op_i; ?>" aria-selected="<?php echo 0 === $ccs_op_i ? 'true' : 'false'; ?>">
											<span class="screen-reader-text">
												<?php
												/* translators: 1: this comment's position, 2: number of comments. */
												echo esc_html( sprintf( __( 'Comment %1$d of %2$d', 'ccs-wp-theme' ), $ccs_op_i + 1, count( $ccs_op_quotes ) ) );
												?>
											</span>
										</button>
									<?php endforeach; ?>
								</div>
								<button type="button" class="quote-rotator__arrow" data-ccs-step-next aria-label="<?php esc_attr_e( 'Next comment', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&rarr;</span></button>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php // Heading, one comment card and the source line only: a list of three more figures under the card made the section too busy (user). ?>
				<p class="careers-proof__source"><?php echo esc_html( ccs_copy( 'careers_proof_source' ) ); ?></p>
			</div>

			<div class="careers-proof__media">
				<?php
				ccs_section_photo(
					'careers/training-room.webp',
					array(
						'alt'   => __( 'New members of staff listening during a session in the training room', 'ccs-wp-theme' ),
						'sizes' => '(min-width: 900px) 480px, 92vw',
						'class' => 'careers-proof__photo',
					)
				);
				?>
				<?php if ( $ccs_op_lead ) : ?>
					<p class="careers-proof__badge" data-ccs-exception="rule 1: the headline figure, set on the photo as a label">
						<?php // Figure, what it is out of and for, then who gave it: on its own "4.92 out of 5" read as a random number (user). ?>
						<?php $ccs_op_lead_bits = array_map( 'trim', explode( '|', $ccs_op_lead[1], 2 ) ); ?>
						<strong><?php echo esc_html( $ccs_op_lead[0] ); ?></strong>
						<span><?php echo esc_html( $ccs_op_lead_bits[0] ); ?></span>
						<?php if ( ! empty( $ccs_op_lead_bits[1] ) ) : ?>
							<small><?php echo esc_html( $ccs_op_lead_bits[1] ); ?></small>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
