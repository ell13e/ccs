<?php
/**
 * Template Name: Contact
 *
 * Tint page hero, a row of three ways to reach us lifted over its lower edge,
 * then the consultation form in a card beside a dark "prefer to talk?" card and
 * a short "what happens next". Same structure as the ElderlyCare X / Sunnyaid
 * contact pages, built from this theme's section system (docs/BUILD-STANDARD.md).
 *
 * The form keeps every hook assets/js/form-handler.js relies on:
 * form[data-ccs-action="submit_enquiry"], the enquiry_* field names, the
 * _company honeypot and [data-ccs-form-message]. id="contact-form" is kept too,
 * because other pages link straight to #contact-form.
 *
 * The old "How can we help?" cards under the form are gone: two of the three
 * linked back to #contact-form on this same page, so they sent people round in
 * a circle, and the third duplicated the call card.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone     = get_theme_mod( 'ccs_phone', '01234 567890' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
$ccs_hours     = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$ccs_email     = get_theme_mod( 'ccs_contact_email', get_option( 'admin_email' ) );
/*
 * Same fallback as footer.php. This defaulted to '' here, so out of the box the
 * footer printed the office address while the contact page, the one place
 * people come looking for it, did not.
 */
$ccs_address   = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
$ccs_map       = get_theme_mod( 'ccs_contact_map_embed', '' );
$ccs_parking   = get_theme_mod( 'ccs_contact_parking', '' );

$ccs_address_line = $ccs_address ? trim( preg_replace( '/\s*\n\s*/', ', ', $ccs_address ) ) : '';
/*
 * A plain link, not an embed: no third-party script or cookies load unless the
 * person chooses to follow it. The embed below is still used when one is set.
 */
$ccs_directions = $ccs_address_line ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $ccs_address_line ) : '';

$ccs_privacy_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'privacy-policy' ) : home_url( '/privacy-policy/' );

/* The same three steps as the homepage's "How care starts", filtered the same way. */
$ccs_next_steps = apply_filters(
	'ccs_home_steps',
	array(
		array(
			'title' => __( 'Talk to us', 'ccs-wp-theme' ),
			'text'  => __( 'A free consultation at your pace, on the phone or at the kitchen table.', 'ccs-wp-theme' ),
		),
		array(
			'title' => __( 'Meet your team', 'ccs-wp-theme' ),
			'text'  => __( 'We match carers to the person, not to a rota.', 'ccs-wp-theme' ),
		),
		array(
			'title' => __( 'Care begins calmly', 'ccs-wp-theme' ),
			'text'  => __( 'Planned, unhurried and reviewed as needs change.', 'ccs-wp-theme' ),
		),
	)
);
?>

<main id="main" class="site-main site-main--contact" role="main">

	<header class="page-hero contact-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php esc_html_e( 'Book your free care consultation', 'ccs-wp-theme' ); ?></h1>
				<p class="page-hero__intro">
					<?php esc_html_e( "Whether you're looking for home care in Maidstone or elsewhere in Kent, tell us what you'd like to talk about and we'll get in touch to arrange a call or a visit.", 'ccs-wp-theme' ); ?>
				</p>
			</div>
		</div>
	</header>

	<section class="contact-ways" aria-labelledby="contact-ways-heading">
		<div class="container container--lg">
			<h2 id="contact-ways-heading" class="screen-reader-text"><?php esc_html_e( 'Ways to reach us', 'ccs-wp-theme' ); ?></h2>
			<ul class="contact-ways__grid" role="list">

				<?php if ( $ccs_phone_tel ) : ?>
					<li class="contact-way">
						<span class="ccs-badge"><?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<h3 class="contact-way__title"><?php esc_html_e( 'Call us', 'ccs-wp-theme' ); ?></h3>
						<a class="contact-way__link" href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>"><?php echo esc_html( $ccs_phone ); ?></a>
						<?php if ( $ccs_hours ) : ?>
							<p class="contact-way__meta contact-info__hours-text"><?php echo esc_html( $ccs_hours ); ?></p>
						<?php endif; ?>
					</li>
				<?php endif; ?>

				<?php if ( $ccs_email ) : ?>
					<li class="contact-way">
						<span class="ccs-badge"><?php echo ccs_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<h3 class="contact-way__title"><?php esc_html_e( 'Email us', 'ccs-wp-theme' ); ?></h3>
						<a class="contact-way__link" href="<?php echo esc_url( 'mailto:' . antispambot( $ccs_email ) ); ?>" data-ccs-preview="contact_email"><?php echo esc_html( antispambot( $ccs_email ) ); ?></a>
						<p class="contact-way__meta"><?php esc_html_e( 'For anything that does not need an answer today.', 'ccs-wp-theme' ); ?></p>
					</li>
				<?php endif; ?>

				<?php if ( $ccs_address ) : ?>
					<li class="contact-way">
						<span class="ccs-badge"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<h3 class="contact-way__title"><?php esc_html_e( 'Visit our office', 'ccs-wp-theme' ); ?></h3>
						<address class="contact-way__address"><?php echo nl2br( esc_html( $ccs_address ) ); ?></address>
						<?php if ( $ccs_directions ) : ?>
							<a class="contact-way__link contact-way__link--small" href="<?php echo esc_url( $ccs_directions ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Get directions', 'ccs-wp-theme' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens Google Maps in a new window)', 'ccs-wp-theme' ); ?></span>
							</a>
						<?php endif; ?>
					</li>
				<?php endif; ?>

			</ul>
		</div>
	</section>

	<section class="contact-main" aria-labelledby="contact-form-heading">
		<div class="contact-main__inner container container--lg">

			<div class="contact-form-card">
				<p class="ccs-eyebrow"><?php esc_html_e( 'Free, no obligation', 'ccs-wp-theme' ); ?></p>
				<h2 id="contact-form-heading" class="contact-form-card__title"><?php esc_html_e( 'Tell us what you need', 'ccs-wp-theme' ); ?></h2>
				<p id="contact-form-desc" class="contact-form-card__desc">
					<?php esc_html_e( "A few details are enough to start. Let us know roughly how soon you'd like care to begin and we'll use it as a guide for when to get in touch.", 'ccs-wp-theme' ); ?>
				</p>

				<form id="contact-form" class="contact-form ccs-form" data-ccs-action="submit_enquiry" method="post" action="" novalidate aria-describedby="contact-form-desc contact-form-message">
					<div class="contact-form__grid">
						<div class="ccs-field">
							<label for="contact-name" class="ccs-field__label"><?php esc_html_e( 'Your name', 'ccs-wp-theme' ); ?></label>
							<input type="text" id="contact-name" name="enquiry_name" class="ccs-field__input" required aria-required="true" autocomplete="name">
						</div>

						<div class="ccs-field">
							<label for="contact-phone" class="ccs-field__label"><?php esc_html_e( 'Phone number', 'ccs-wp-theme' ); ?></label>
							<input type="tel" id="contact-phone" name="enquiry_phone" class="ccs-field__input" required aria-required="true" autocomplete="tel">
						</div>

						<div class="ccs-field">
							<label for="contact-email" class="ccs-field__label"><?php esc_html_e( 'Email address', 'ccs-wp-theme' ); ?></label>
							<input type="email" id="contact-email" name="enquiry_email" class="ccs-field__input" required aria-required="true" autocomplete="email" spellcheck="false">
						</div>

						<div class="ccs-field">
							<label for="contact-location" class="ccs-field__label">
								<?php esc_html_e( 'Town or area', 'ccs-wp-theme' ); ?>
								<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
							</label>
							<input type="text" id="contact-location" name="enquiry_location" class="ccs-field__input" autocomplete="address-level2" placeholder="<?php esc_attr_e( 'e.g. Maidstone, Tonbridge', 'ccs-wp-theme' ); ?>">
						</div>

						<div class="ccs-field">
							<label for="contact-care-type" class="ccs-field__label">
								<?php esc_html_e( 'Type of care', 'ccs-wp-theme' ); ?>
								<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
							</label>
							<select id="contact-care-type" name="enquiry_care_type" class="ccs-field__input ccs-field__select">
								<option value=""><?php esc_html_e( 'Choose one', 'ccs-wp-theme' ); ?></option>
								<option value="complex-care"><?php esc_html_e( 'Complex care', 'ccs-wp-theme' ); ?></option>
								<option value="personal-care"><?php esc_html_e( 'Personal care', 'ccs-wp-theme' ); ?></option>
								<option value="companionship"><?php esc_html_e( 'Companionship', 'ccs-wp-theme' ); ?></option>
								<option value="live-in"><?php esc_html_e( 'Live-in care', 'ccs-wp-theme' ); ?></option>
								<option value="visiting"><?php esc_html_e( 'Visiting care', 'ccs-wp-theme' ); ?></option>
								<option value="respite"><?php esc_html_e( 'Respite care', 'ccs-wp-theme' ); ?></option>
								<option value="not-sure"><?php esc_html_e( 'Not sure yet', 'ccs-wp-theme' ); ?></option>
							</select>
						</div>

						<?php
						/*
						 * Three choices shown as radios, not hidden in a dropdown: with two
						 * to four options, seeing them all is quicker than opening a list
						 * (CCS/CLAUDE.md rule 20). Same name and values as the old select,
						 * so the handler and the stored enquiry are unchanged.
						 */
						?>
						<fieldset class="ccs-field ccs-choices">
							<legend class="ccs-field__label">
								<?php esc_html_e( 'How soon?', 'ccs-wp-theme' ); ?>
								<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
							</legend>
							<?php
							$ccs_urgency = array(
								'urgent'    => __( 'As soon as possible', 'ccs-wp-theme' ),
								'soon'      => __( 'Within the next few weeks', 'ccs-wp-theme' ),
								'exploring' => __( 'Just exploring options', 'ccs-wp-theme' ),
							);
							foreach ( $ccs_urgency as $ccs_value => $ccs_label ) :
								?>
								<label class="ccs-choice">
									<input type="radio" name="enquiry_urgency" value="<?php echo esc_attr( $ccs_value ); ?>">
									<span><?php echo esc_html( $ccs_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</fieldset>

						<div class="ccs-field contact-form__full">
							<label for="contact-message" class="ccs-field__label">
								<?php esc_html_e( 'Anything you would like us to know', 'ccs-wp-theme' ); ?>
								<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
							</label>
							<textarea id="contact-message" name="enquiry_message" class="ccs-field__input ccs-field__textarea" rows="5" placeholder="<?php esc_attr_e( 'Tell us a bit about your situation and what you need.', 'ccs-wp-theme' ); ?>"></textarea>
						</div>
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
					<div id="contact-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
				</form>
			</div>

			<aside class="contact-aside" aria-label="<?php esc_attr_e( 'Other ways to get started', 'ccs-wp-theme' ); ?>">

				<?php if ( $ccs_phone_tel ) : ?>
					<div class="contact-call ccs-on-dark">
						<h2 class="contact-call__title"><?php esc_html_e( 'Prefer to talk?', 'ccs-wp-theme' ); ?></h2>
						<p class="contact-call__text"><?php esc_html_e( "If a form feels like too much right now, ring our office team and we'll talk it through.", 'ccs-wp-theme' ); ?></p>
						<?php
						/*
						 * The number itself, large, rather than a second filled button:
						 * the form's submit is this section's primary action, and a mint
						 * pill beside it made the two compete (CCS/CLAUDE.md rule 8).
						 * Still a 48px tap target.
						 */
						?>
						<a class="contact-call__phone" href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span>
								<span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_phone ); ?>
							</span>
						</a>
						<?php if ( $ccs_hours ) : ?>
							<p class="contact-call__hours"><?php echo esc_html( $ccs_hours ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $ccs_next_steps ) ) : ?>
					<div class="contact-next">
						<h2 class="contact-next__title"><?php esc_html_e( 'What happens next', 'ccs-wp-theme' ); ?></h2>
						<ol class="contact-next__list" role="list">
							<?php foreach ( $ccs_next_steps as $ccs_i => $ccs_step ) : ?>
								<li class="contact-next__item">
									<span class="contact-next__num" aria-hidden="true"><?php echo (int) $ccs_i + 1; ?></span>
									<div>
										<h3 class="contact-next__step"><?php echo esc_html( $ccs_step['title'] ); ?></h3>
										<p class="contact-next__text"><?php echo esc_html( $ccs_step['text'] ); ?></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>

				<?php if ( $ccs_map || $ccs_parking ) : ?>
					<div class="contact-visit">
						<?php if ( $ccs_map ) : ?>
							<div class="contact-visit__map">
								<?php
								$ccs_map_allowed = array(
									'iframe' => array(
										'src'             => true,
										'width'           => true,
										'height'          => true,
										'frameborder'     => true,
										'style'           => true,
										'title'           => true,
										'loading'         => true,
										'referrerpolicy'  => true,
										'allow'           => true,
										'allowfullscreen' => true,
									),
								);
								echo wp_kses( $ccs_map, $ccs_map_allowed );
								?>
							</div>
						<?php endif; ?>
						<?php if ( $ccs_parking ) : ?>
							<div class="contact-visit__body">
								<h2 class="contact-visit__title"><?php esc_html_e( 'Parking', 'ccs-wp-theme' ); ?></h2>
								<p class="contact-visit__text"><?php echo esc_html( $ccs_parking ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</aside>

		</div>
	</section>

</main>

<?php
get_footer();
