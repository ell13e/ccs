<?php
/**
 * Reusable pre-footer CTA: the homepage's closing card, on every inner page.
 *
 * Inner pages previously ended by running straight into the footer with no
 * next step, and then on a full-width purple stripe that no longer matched the
 * homepage. They now end on the same .cta-card (assets/css/sections.css): the
 * deepest plum, the ranked button pair, and on care pages the same photograph,
 * so every page closes the same way. Copy follows the "Ready when you are"
 * block in reference/copy/homepage.md.
 *
 * Suppressed on the Contact page, where the form itself is the action.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_page_template( 'page-templates/template-contact.php' ) ) {
	return;
}

$ccs_cta_contact = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );

/*
 * Careers pages get a careers CTA. Offering a job seeker "Book a free care
 * consultation" is the wrong ask entirely, and undermines the segregation
 * between the care side and the careers side.
 */
$ccs_is_careers = function_exists( 'ccs_is_careers_context' ) && ccs_is_careers_context();

if ( $ccs_is_careers ) {
	$ccs_cta_url     = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'current-vacancies' ) : home_url( '/careers/current-vacancies/' );
	$ccs_cta_heading = ccs_copy( 'cta_careers_title' );
	$ccs_cta_text    = ccs_copy( 'cta_careers_text' );
	$ccs_cta_label   = ccs_copy( 'cta_careers_button' );
} else {
	$ccs_cta_url     = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	$ccs_cta_heading = ccs_copy( 'cta_title' );
	$ccs_cta_text    = ccs_copy( 'cta_text' );
	$ccs_cta_label   = __( 'Book a free consultation', 'ccs-wp-theme' );
}

/*
 * Care pages repeat the homepage card's photograph (hands held). Careers pages
 * go without: a job seeker should not be shown the image used to reassure a
 * family.
 */
// A real CCS photo (Nov 2026). The hands-holding image used here looked like
// stock and, with this band on every page, appeared 15 times across the site.
$ccs_cta_photo_rel = 'site-photos/client-pottery.webp';
$ccs_cta_photo     = ! $ccs_is_careers && file_exists( get_template_directory() . '/assets/images/' . $ccs_cta_photo_rel );

?>

<section class="cta-band" aria-labelledby="cta-band-heading">
	<div class="container container--lg">
		<div class="cta-card<?php echo $ccs_cta_photo ? ' cta-card--photo' : ''; ?>">

			<div class="cta-card__body">
				<h2 id="cta-band-heading" class="ccs-title ccs-title--on-dark">
					<?php echo esc_html( $ccs_cta_heading ); ?>
				</h2>
				<p class="ccs-lede ccs-lede--on-dark">
					<?php echo esc_html( $ccs_cta_text ); ?>
				</p>
				<div class="ccs-actions cta-card__actions">
					<a href="<?php echo esc_url( $ccs_cta_url ); ?>" class="btn btn-accent">
						<?php echo esc_html( $ccs_cta_label ); ?>
					</a>
					<?php if ( ! empty( $ccs_cta_contact['phone'] ) ) : ?>
						<a href="<?php echo esc_url( $ccs_cta_contact['phone_link'] ); ?>" class="btn btn-ghost-on-dark">
							<?php
							/* translators: %s: office phone number */
							printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_cta_contact['phone'] ) );
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
						width="800"
						height="800"
						<?php echo ccs_theme_image_srcset( $ccs_cta_photo_rel, '(min-width: 900px) 450px, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
						loading="lazy"
						decoding="async"
					>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
