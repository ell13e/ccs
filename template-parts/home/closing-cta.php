<?php
/**
 * Homepage closing CTA band: the final call to action before the footer.
 *
 * An inset card, not a full-bleed stripe (after the ElderlyCare X template):
 * the deepest plum (--color-band-deepest), a real photograph, and a ranked
 * pair of actions.
 *
 * Careers pages get their own CTA elsewhere and must not be offered a
 * client-facing "book a consultation": see ccs_is_careers_context().
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
// A real CCS photo (Nov 2026). The hands-holding image used here looked like
// stock and, with this band on every page, appeared 15 times across the site.
// 24 Nov 2026: the pottery photo that was here is removed from the site (client: "we can't use photos of this young man").
// 25 Nov 2026: the same photo as a full-size portrait crop. The slot is tall on
// wide screens, and the 1000x667 landscape file was enlarged until it blurred.
$ccs_cta_photo_rel = 'site-photos-extra/band-afternoon-tea.webp';
$ccs_cta_photo     = file_exists( get_template_directory() . '/assets/images/' . $ccs_cta_photo_rel );
?>

<section class="home-closing-cta" aria-labelledby="home-closing-cta-heading">
	<div class="container container--lg">
		<div class="cta-card<?php echo $ccs_cta_photo ? ' cta-card--photo' : ''; ?>">

			<div class="cta-card__body">
				<h2 id="home-closing-cta-heading" class="ccs-title ccs-title--on-dark">
					<?php
					echo esc_html( ccs_copy( 'closing_title' ) );
					?>
				</h2>
				<p class="ccs-lede ccs-lede--on-dark">
					<?php echo esc_html( ccs_copy( 'closing_text' ) ); ?>
				</p>
				<p class="ccs-lede ccs-lede--on-dark">
					<?php echo esc_html( ccs_copy( 'closing_text_2' ) ); ?>
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
						width="1200"
						height="1500"
						style="object-position: 50% 30%;"
						<?php echo ccs_theme_image_srcset( $ccs_cta_photo_rel, '(min-width: 900px) 450px, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
						loading="lazy"
						decoding="async"
					>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
