<?php
/**
 * Template Name: Switch to us
 *
 * For families moving their home care from another provider. One job: get
 * the switching conversation started. Primary action: the form; the phone is
 * equally easy. Wording is editable under Customize > Page wording >
 * Switching page (inc/home-content.php).
 *
 * Sections: tint hero with the two routes in (form, phone); how switching
 * works (white, the same step cards as the homepage); the questions people
 * ask before switching (tint, native details); the form beside the call card
 * (white). No closing band: this page is the call to action, like Contact.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_contact     = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
$ccs_hours       = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$ccs_privacy_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'privacy-policy' ) : home_url( '/privacy-policy/' );

$ccs_switch_steps = array(
	array( 'title' => ccs_copy( 'switch_step_1_title' ), 'text' => ccs_copy( 'switch_step_1_text' ) ),
	array( 'title' => ccs_copy( 'switch_step_2_title' ), 'text' => ccs_copy( 'switch_step_2_text' ) ),
	array( 'title' => ccs_copy( 'switch_step_3_title' ), 'text' => ccs_copy( 'switch_step_3_text' ) ),
);

// Written out rather than built in a loop, so bin/test-home-copy.php can see every key.
$ccs_switch_faqs = array(
	array( 'q' => ccs_copy( 'switch_faq_1_q' ), 'a' => ccs_copy( 'switch_faq_1_a' ) ),
	array( 'q' => ccs_copy( 'switch_faq_2_q' ), 'a' => ccs_copy( 'switch_faq_2_a' ) ),
	array( 'q' => ccs_copy( 'switch_faq_3_q' ), 'a' => ccs_copy( 'switch_faq_3_a' ) ),
);
?>

<main id="main" class="site-main site-main--switch" role="main">

	<header class="page-hero switch-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php echo esc_html( ccs_copy( 'switch_title' ) ); ?></h1>
				<p class="page-hero__intro"><?php echo esc_html( ccs_copy( 'switch_intro' ) ); ?></p>
				<div class="ccs-actions switch-hero__actions">
					<a class="btn btn-primary" href="#switch-form" data-ccs-exception="rule 8: the page's primary action, a jump to the switching form below"><?php echo esc_html( ccs_copy( 'switch_button' ) ); ?></a>
					<?php if ( ! empty( $ccs_contact['phone'] ) ) : ?>
						<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_contact['phone_link'] ); ?>">
							<?php
							/* translators: %s: office phone number */
							printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_contact['phone'] ) );
							?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>

	<section class="ccs-section switch-steps" aria-labelledby="switch-steps-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="switch-steps-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'switch_steps_title' ) ); ?></h2>
			</header>
			<ol class="ccs-steps ccs-steps--panel" role="list" data-ccs-exception="rule 1: one soft panel holding the sequence, as on the homepage">
				<?php foreach ( $ccs_switch_steps as $ccs_i => $ccs_step ) : ?>
					<li class="ccs-step">
						<span class="ccs-step__num" aria-hidden="true"><?php echo (int) $ccs_i + 1; ?></span>
						<h3 class="ccs-step__title"><?php echo esc_html( $ccs_step['title'] ); ?></h3>
						<p class="ccs-step__text"><?php echo esc_html( $ccs_step['text'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<section class="ccs-section ccs-section--tint switch-faqs" aria-labelledby="switch-faqs-heading">
		<div class="container container--lg">
			<div class="ccs-split">
				<header class="ccs-head">
					<h2 id="switch-faqs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'switch_faqs_title' ) ); ?></h2>
				</header>
				<div class="ccs-faq">
					<?php foreach ( $ccs_switch_faqs as $ccs_i => $ccs_faq ) : ?>
						<details class="ccs-faq__item" name="switch-faq"<?php echo 0 === $ccs_i ? ' open' : ''; ?>>
							<summary class="ccs-faq__q"><?php echo esc_html( $ccs_faq['q'] ); ?></summary>
							<div class="ccs-faq__a"><p><?php echo esc_html( $ccs_faq['a'] ); ?></p></div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-main switch-form-section" id="switch-form" aria-labelledby="switch-form-heading">
		<div class="contact-main__inner container container--lg">

			<div class="contact-form-card">
				<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'switch_form_eyebrow' ) ); ?></p>
				<h2 id="switch-form-heading" class="contact-form-card__title"><?php echo esc_html( ccs_copy( 'switch_form_title' ) ); ?></h2>
				<p id="switch-form-desc" class="contact-form-card__desc"><?php echo esc_html( ccs_copy( 'switch_form_text' ) ); ?></p>

				<form class="contact-form ccs-form" data-ccs-action="submit_enquiry" method="post" action="" novalidate aria-describedby="switch-form-desc switch-form-message">
					<?php // Tags the enquiry so the office sees at once that this family is switching provider. ?>
					<input type="hidden" name="enquiry_source" value="switching">

					<div class="contact-form__grid">
						<div class="ccs-field">
							<label for="switch-name" class="ccs-field__label"><?php esc_html_e( 'Your name', 'ccs-wp-theme' ); ?></label>
							<input type="text" id="switch-name" name="enquiry_name" class="ccs-field__input" required aria-required="true" autocomplete="name">
						</div>

						<div class="ccs-field">
							<label for="switch-phone" class="ccs-field__label"><?php esc_html_e( 'Phone number', 'ccs-wp-theme' ); ?></label>
							<input type="tel" id="switch-phone" name="enquiry_phone" class="ccs-field__input" required aria-required="true" autocomplete="tel">
						</div>

						<div class="ccs-field">
							<label for="switch-email" class="ccs-field__label">
								<?php esc_html_e( 'Email address', 'ccs-wp-theme' ); ?>
								<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
							</label>
							<input type="email" id="switch-email" name="enquiry_email" class="ccs-field__input" autocomplete="email" spellcheck="false">
						</div>

						<?php
						/*
						 * Everything optional sits behind one disclosure (Nov 2026: the
						 * client found the full form tight and overwhelming). What is
						 * needed to call someone back, a name and a number, is all that
						 * shows; the rest is one tap away for those who want to add it.
						 */
						?>
						<?php
						/*
						 * Required (client, Nov 2026): the office needs to know who the
						 * family is leaving and when, to plan a handover with no gap.
						 */
						?>
						<div class="ccs-field">
							<label for="switch-provider" class="ccs-field__label"><?php esc_html_e( 'Your current care provider', 'ccs-wp-theme' ); ?></label>
							<input type="text" id="switch-provider" name="switch_provider" class="ccs-field__input" autocomplete="off" required aria-required="true" data-ccs-error="<?php esc_attr_e( 'Please tell us who provides your care now. If you are not sure of the name, describe them.', 'ccs-wp-theme' ); ?>">
						</div>

						<fieldset class="ccs-field ccs-choices" data-ccs-required data-ccs-error="<?php esc_attr_e( 'Please choose when your current care ends. Not sure is fine.', 'ccs-wp-theme' ); ?>">
							<legend class="ccs-field__label"><?php esc_html_e( 'When does your current care end?', 'ccs-wp-theme' ); ?></legend>
							<?php foreach ( ccs_switch_timing_options() as $ccs_value => $ccs_label ) : ?>
								<label class="ccs-choice">
									<input type="radio" name="switch_timing" value="<?php echo esc_attr( $ccs_value ); ?>">
									<span><?php echo esc_html( $ccs_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</fieldset>

						<?php ccs_heard_about_field( 'switch-heard-about' ); ?>

						<details class="ccs-more contact-form__full">
							<summary class="ccs-more__summary"><?php esc_html_e( 'Anything else we should know (optional)', 'ccs-wp-theme' ); ?></summary>
							<div class="ccs-more__fields">
								<div class="ccs-field">
									<label for="switch-message" class="ccs-field__label">
										<?php esc_html_e( 'What is not working at the moment?', 'ccs-wp-theme' ); ?>
										<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
									</label>
									<textarea id="switch-message" name="enquiry_message" class="ccs-field__input ccs-field__textarea" rows="5"></textarea>
								</div>
							</div>
						</details>
					</div>

					<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">

					<div class="contact-form__foot">
						<button type="submit" class="btn btn-primary contact-form__submit"><?php esc_html_e( 'Send my request', 'ccs-wp-theme' ); ?></button>
						<p class="contact-form__privacy">
							<?php
							printf(
								/* translators: %s: link to the privacy policy. */
								esc_html__( 'We use these details only to reply to you. See our %s.', 'ccs-wp-theme' ),
								'<a href="' . esc_url( $ccs_privacy_url ) . '">' . esc_html__( 'privacy policy', 'ccs-wp-theme' ) . '</a>'
							);
							?>
						</p>
					</div>
					<div id="switch-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
				</form>
			</div>

			<aside class="contact-aside" aria-label="<?php esc_attr_e( 'Talk to us instead', 'ccs-wp-theme' ); ?>">
				<?php if ( ! empty( $ccs_contact['phone'] ) ) : ?>
					<div class="contact-call ccs-on-dark">
						<h2 class="contact-call__title"><?php echo esc_html( ccs_copy( 'contact_talk_title' ) ); ?></h2>
						<p class="contact-call__text"><?php echo esc_html( ccs_copy( 'switch_call_text' ) ); ?></p>
						<a class="contact-call__phone" href="<?php echo esc_url( $ccs_contact['phone_link'] ); ?>">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_contact['phone'] ); ?></span>
						</a>
						<?php if ( $ccs_hours ) : ?>
							<p class="contact-call__hours"><?php echo esc_html( $ccs_hours ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</aside>

		</div>
	</section>

</main>

<?php
get_footer();
