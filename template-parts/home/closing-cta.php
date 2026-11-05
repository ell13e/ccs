<?php
/**
 * Homepage closing CTA band.
 *
 * The page previously ran out of road: the last content section was the partner
 * logo wall, then the footer, with no final call to action anywhere below the
 * mid-page services CTA. A reader who scrolled the whole homepage — by
 * definition the most engaged visitor on the site — arrived at a row of faded
 * logos and had nothing to do next.
 *
 * reference/inspo-teardowns.md identifies the fix as the one move every strong
 * reference in the set shares: "The strong concepts all end on a dark statement
 * band — plum NDIS's dark plum pre-footer, Premier's dark green CTA card: one
 * saturated full-bleed moment holding the final call to action just before the
 * footer." It is also described there as tonal inversion used as narrative
 * punctuation — the page audibly ends.
 *
 * Shape: an inset card, not a full-bleed stripe. The ElderlyCare X template ends
 * its pages on a rounded brand-colour card holding a photograph beside the ask,
 * and that is the form here: the deepest plum (--color-band-deepest, one step
 * darker than the differentiators band so the two are never a literal repeat),
 * a real photograph of hands held, and the ranked pair of actions the hero
 * uses. A flat full-width stripe of colour said "end of page"; a card with a
 * person in it says "here is who you will be talking to".
 *
 * Careers pages get their own CTA elsewhere and must not be offered a
 * client-facing "book a consultation" — see ccs_is_careers_context().
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_cta_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
$ccs_cta_phone   = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
?>

<?php
$ccs_cta_photo_rel = 'site-photos/caregiver-supporting-patient-1.webp';
$ccs_cta_photo     = file_exists( get_template_directory() . '/assets/images/' . $ccs_cta_photo_rel );
?>

<section class="home-closing-cta" aria-labelledby="home-closing-cta-heading">
	<div class="container container--lg">
		<div class="cta-card<?php echo $ccs_cta_photo ? ' cta-card--photo' : ''; ?>">

			<div class="cta-card__body">
				<h2 id="home-closing-cta-heading" class="ccs-title ccs-title--on-dark">
					<?php
					esc_html_e( 'Not sure where to start?', 'ccs-wp-theme' );
					?>
				</h2>
				<p class="ccs-lede ccs-lede--on-dark">
					<?php esc_html_e( 'Most people who call us are not ready to arrange anything yet — they just want to talk it through with someone who knows how this works. That conversation is free, and there is nothing to sign at the end of it.', 'ccs-wp-theme' ); ?>
				</p>

				<?php
				/*
				 * Ranked dual CTA, the same anatomy as the hero's pair: filled mint
				 * primary, outlined ghost secondary, same height and radius.
				 */
				?>
				<div class="ccs-actions cta-card__actions">
					<a href="<?php echo esc_url( $ccs_cta_contact ); ?>" class="btn btn-accent">
						<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
					</a>
					<?php if ( ! empty( $ccs_cta_phone['phone'] ) ) : ?>
						<a href="<?php echo esc_url( $ccs_cta_phone['phone_link'] ); ?>" class="btn btn-ghost-on-dark">
							<?php
							/* translators: %s: office phone number */
							printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_cta_phone['phone'] ) );
							?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $ccs_cta_photo ) : ?>
				<div class="cta-card__media" aria-hidden="true">
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_cta_photo_rel ); ?>"
						alt=""
						width="1350"
						height="900"
						loading="lazy"
						decoding="async"
					>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
