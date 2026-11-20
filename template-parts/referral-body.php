<?php
/**
 * Referral information page body (Nov 2026).
 *
 * The page was three short paragraphs on an otherwise empty page (a design
 * review rated it joint weakest). Same words, now as two objects side by
 * side: the page's opening paragraph and its sections in the site's standard
 * accordion, and the plum call card the Contact and Switch pages use, since
 * a referrer's next step is to ring or write. The sections are read from the
 * editor content at its headings; nothing is written here but the card's
 * heading.
 *
 * Must be called inside the loop.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_rf         = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
$ccs_rf_contact = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
$ccs_rf_hours   = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$ccs_rf_url     = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );

/*
 * Third form (Nov 2026). An accordion beside a call card, then a numbered
 * panel, both missed; the client pointed to five referral pages (Best Homes
 * Support, Great Prospects, MHA, Sutton Health and Care). What they share: a
 * form as the page's main object, a short account of what happens next, and
 * the referral team's phone and email beside it. So the page is one card in
 * two halves, the same card as "What it costs" on the service pages: the
 * page's own sections on the left, numbered, and on the plum half the phone
 * and the call-back form.
 *
 * The form is the site's existing call-back form: the referrer's own name
 * and number only, recorded with the page it came from. It deliberately does
 * not ask for the person's name, date of birth or needs. A full online
 * referral form (as Best Homes has) would send and store health details of
 * a third party, which needs the client's decision on consent, storage and
 * who receives it before it is built.
 */
$ccs_rf_email = function_exists( 'ccs_contact_email' ) ? ccs_contact_email() : '';
?>
<section class="ccs-section referral" aria-labelledby="referral-form-heading">
	<div class="container container--lg">
		<div class="cost-call referral__card" data-ccs-exception="rule 1: how to refer and the way to start, one object">
			<div class="cost-call__costs referral__steps">
				<?php // The page's opening paragraph heads the card's left half: on its own above the card it stood in a large gap (client: "gappy"). ?>
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_rf['intro'] ) ) ) : ?>
					<div class="referral__lede"><?php echo wp_kses_post( $ccs_rf['intro'] ); ?></div>
				<?php endif; ?>
				<?php if ( $ccs_rf['sections'] ) : ?>
					<ol class="refer-steps" role="list">
						<?php foreach ( $ccs_rf['sections'] as $ccs_rf_i => $ccs_rf_section ) : ?>
							<li class="refer-step">
								<span class="refer-step__num" aria-hidden="true"><?php echo (int) $ccs_rf_i + 1; ?></span>
								<div>
									<h2 class="refer-step__title"><?php echo esc_html( $ccs_rf_section['heading'] ); ?></h2>
									<div class="refer-step__text"><?php echo wp_kses_post( $ccs_rf_section['body'] ); ?></div>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
			</div>

			<div class="cost-call__form ccs-on-dark">
				<h2 id="referral-form-heading" class="cost-call__title"><?php esc_html_e( 'Start a referral', 'ccs-wp-theme' ); ?></h2>
				<?php if ( ! empty( $ccs_rf_contact['phone'] ) ) : ?>
					<a class="cost-call__phone" href="<?php echo esc_url( $ccs_rf_contact['phone_link'] ); ?>">
						<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
						<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_rf_contact['phone'] ); ?></span>
					</a>
				<?php endif; ?>
				<p class="cost-call__hours">
					<?php echo esc_html( $ccs_rf_hours ); ?>
					<?php if ( $ccs_rf_email && is_email( $ccs_rf_email ) ) : ?>
						<br><a class="referral__email" href="<?php echo esc_url( 'mailto:' . $ccs_rf_email ); ?>"><?php echo esc_html( $ccs_rf_email ); ?></a>
					<?php endif; ?>
				</p>
				<p class="referral__note"><?php esc_html_e( 'Or leave your own name and number and we will ring you back. Please do not put the person’s details in this form: we will take those by phone.', 'ccs-wp-theme' ); ?></p>

				<form class="ccs-form" data-ccs-action="request_callback" method="post" action="" novalidate aria-describedby="referral-form-message">
					<div class="ccs-field">
						<label for="referral-name" class="ccs-field__label"><?php esc_html_e( 'Your name', 'ccs-wp-theme' ); ?></label>
						<input type="text" id="referral-name" name="name" required aria-required="true" autocomplete="name" class="ccs-field__input">
					</div>
					<div class="ccs-field">
						<label for="referral-phone" class="ccs-field__label"><?php esc_html_e( 'Direct phone number', 'ccs-wp-theme' ); ?></label>
						<input type="tel" id="referral-phone" name="phone" required aria-required="true" autocomplete="tel" class="ccs-field__input">
					</div>
					<div class="ccs-field">
						<label for="referral-time" class="ccs-field__label">
							<?php esc_html_e( 'Best time to call', 'ccs-wp-theme' ); ?>
							<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
						</label>
						<input type="text" id="referral-time" name="preferred_time" class="ccs-field__input" autocomplete="off">
					</div>
					<?php
					if ( function_exists( 'ccs_heard_about_field' ) ) {
						ccs_heard_about_field( 'referral-heard-about' );
					}
					?>
					<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">
					<button type="submit" class="btn btn-accent"><?php esc_html_e( 'Ask us to call you back', 'ccs-wp-theme' ); ?></button>
					<div id="referral-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
				</form>
			</div>
		</div>
	</div>
</section>
