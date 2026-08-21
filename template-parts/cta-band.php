<?php
/**
 * Reusable pre-footer CTA band.
 *
 * Inner pages previously ended by running straight into the footer with no
 * next step. Copy follows the "Ready when you are" block in
 * reference/copy/homepage.md.
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
	$ccs_cta_heading = __( 'Fancy joining us?', 'ccs-wp-theme' );
	$ccs_cta_text    = __( 'We are always glad to hear from people who want to do this job properly. Have a look at what is open right now.', 'ccs-wp-theme' );
	$ccs_cta_label   = __( 'See current vacancies', 'ccs-wp-theme' );
} else {
	$ccs_cta_url     = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	$ccs_cta_heading = __( 'Ready when you are', 'ccs-wp-theme' );
	$ccs_cta_text    = __( 'Start with a conversation, not a commitment. Free, no obligation, no pressure.', 'ccs-wp-theme' );
	$ccs_cta_label   = __( 'Book a free consultation', 'ccs-wp-theme' );
}
?>

<section class="cta-band" aria-labelledby="cta-band-heading">
	<div class="cta-band__inner container container--lg">
		<div class="cta-band__copy">
			<h2 id="cta-band-heading" class="cta-band__heading">
				<?php echo esc_html( $ccs_cta_heading ); ?>
			</h2>
			<p class="cta-band__text">
				<?php echo esc_html( $ccs_cta_text ); ?>
			</p>
		</div>
		<div class="cta-band__actions">
			<a href="<?php echo esc_url( $ccs_cta_url ); ?>" class="btn btn-accent btn-lg">
				<?php echo esc_html( $ccs_cta_label ); ?>
			</a>
			<?php if ( ! empty( $ccs_cta_contact['phone'] ) ) : ?>
				<a href="<?php echo esc_url( $ccs_cta_contact['phone_link'] ); ?>" class="cta-band__call">
					<?php
					/* translators: %s: office phone number */
					printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_cta_contact['phone'] ) );
					?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
