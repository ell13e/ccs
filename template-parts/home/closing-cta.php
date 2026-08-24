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
 * Runs on --color-band-deepest, one step darker than the differentiators
 * band above rather than a repeat of it — two identical dark plum sections
 * read as a glitch, not as two intentional beats. Both are tonal steps of the
 * same brand hue (see design-system.css §"Bands"), which is what stops a
 * banded page reading as stripy while still letting the final band be the
 * darkest, most saturated moment on the page.
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

<section class="home-closing-cta" aria-labelledby="home-closing-cta-heading">
	<div class="container container--lg">
		<div class="home-closing-cta__inner">

			<div class="home-closing-cta__body">
				<p class="ccs-eyebrow ccs-eyebrow--on-dark"><?php esc_html_e( 'Get in touch', 'ccs-wp-theme' ); ?></p>
				<h2 id="home-closing-cta-heading" class="home-closing-cta__heading">
					<?php esc_html_e( 'Not sure where to start?', 'ccs-wp-theme' ); ?>
				</h2>
				<p class="home-closing-cta__text">
					<?php esc_html_e( 'Most people who call us are not ready to arrange anything yet — they just want to talk it through with someone who knows how this works. That conversation is free, and there is nothing to sign at the end of it.', 'ccs-wp-theme' ); ?>
				</p>
			</div>

			<div class="home-closing-cta__actions">
				<a href="<?php echo esc_url( $ccs_cta_contact ); ?>" class="btn btn-accent home-closing-cta__btn">
					<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
				</a>

				<?php
				/*
				 * Ranked dual CTA, not a filled button beside a small underlined
				 * text link. reference/inspo-teardowns.md names this pattern
				 * explicitly as the set's cleanest action grammar: "same size, same
				 * radius, different visual weight" — Premier's filled/outline pair.
				 * The hero already draws exactly this shape (.home-hero__cta +
				 * .home-hero__call); the page's closing ask was the one place that
				 * downgraded the second action to plain text instead of matching it.
				 */
				?>
				<?php if ( ! empty( $ccs_cta_phone['phone'] ) ) : ?>
					<a href="<?php echo esc_url( $ccs_cta_phone['phone_link'] ); ?>" class="btn btn-ghost-on-dark home-closing-cta__btn">
						<?php
						/* translators: %s: office phone number */
						printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_cta_phone['phone'] ) );
						?>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
