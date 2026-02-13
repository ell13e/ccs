<?php
/**
 * Template Name: Contact
 *
 * Two-column: contact form (left), contact info + map (right). Quick contact cards below.
 * Form submits via AJAX (submit_enquiry); honeypot, loading, success/error, conversion.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone    = get_theme_mod( 'ccs_phone', '01234 567890' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
$ccs_hours    = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$ccs_email    = get_theme_mod( 'ccs_contact_email', get_option( 'admin_email' ) );
$ccs_address  = get_theme_mod( 'ccs_contact_address', '' );
$ccs_map      = get_theme_mod( 'ccs_contact_map_embed', '' );
$ccs_parking  = get_theme_mod( 'ccs_contact_parking', '' );
?>

<main id="main" class="site-main site-main--contact" role="main">

	<div class="contact-layout">
		<div class="contact-layout__inner container container--lg">

			<!-- LEFT: Contact form -->
			<div class="contact-form-col">
				<h1 class="contact-form-col__title"><?php esc_html_e( 'Send us a message', 'ccs-wp-theme' ); ?></h1>
				<form id="contact-form" class="contact-form ccs-form" data-ccs-action="submit_enquiry" method="post" action="" novalidate aria-describedby="contact-form-message">
					<label for="contact-name" class="contact-form__label"><?php esc_html_e( 'Name', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
					<input type="text" id="contact-name" name="enquiry_name" class="contact-form__input" required aria-required="true" autocomplete="name">

					<label for="contact-email" class="contact-form__label"><?php esc_html_e( 'Email', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
					<input type="email" id="contact-email" name="enquiry_email" class="contact-form__input" required aria-required="true" autocomplete="email">

					<label for="contact-phone" class="contact-form__label"><?php esc_html_e( 'Phone', 'ccs-wp-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
					<input type="tel" id="contact-phone" name="enquiry_phone" class="contact-form__input" required aria-required="true" autocomplete="tel">

					<label for="contact-care-type" class="contact-form__label"><?php esc_html_e( 'Care type', 'ccs-wp-theme' ); ?></label>
					<select id="contact-care-type" name="enquiry_care_type" class="contact-form__select">
						<option value=""><?php esc_html_e( '— Select —', 'ccs-wp-theme' ); ?></option>
						<option value="complex-care"><?php esc_html_e( 'Complex care', 'ccs-wp-theme' ); ?></option>
						<option value="personal-care"><?php esc_html_e( 'Personal care', 'ccs-wp-theme' ); ?></option>
						<option value="companionship"><?php esc_html_e( 'Companionship', 'ccs-wp-theme' ); ?></option>
						<option value="live-in"><?php esc_html_e( 'Live-in care', 'ccs-wp-theme' ); ?></option>
						<option value="visiting"><?php esc_html_e( 'Visiting care', 'ccs-wp-theme' ); ?></option>
						<option value="respite"><?php esc_html_e( 'Respite care', 'ccs-wp-theme' ); ?></option>
						<option value="hospital-discharge"><?php esc_html_e( 'Hospital discharge', 'ccs-wp-theme' ); ?></option>
						<option value="not-sure"><?php esc_html_e( 'Not sure yet', 'ccs-wp-theme' ); ?></option>
					</select>

					<label for="contact-location" class="contact-form__label"><?php esc_html_e( 'Location / area', 'ccs-wp-theme' ); ?></label>
					<input type="text" id="contact-location" name="enquiry_location" class="contact-form__input" autocomplete="address-level2" placeholder="<?php esc_attr_e( 'e.g. Maidstone, Tonbridge', 'ccs-wp-theme' ); ?>">

					<label for="contact-urgency" class="contact-form__label"><?php esc_html_e( 'Urgency', 'ccs-wp-theme' ); ?></label>
					<select id="contact-urgency" name="enquiry_urgency" class="contact-form__select">
						<option value=""><?php esc_html_e( '— Select —', 'ccs-wp-theme' ); ?></option>
						<option value="urgent"><?php esc_html_e( 'Urgent (e.g. hospital discharge)', 'ccs-wp-theme' ); ?></option>
						<option value="soon"><?php esc_html_e( 'Within the next few weeks', 'ccs-wp-theme' ); ?></option>
						<option value="exploring"><?php esc_html_e( 'Just exploring options', 'ccs-wp-theme' ); ?></option>
					</select>

					<label for="contact-message" class="contact-form__label"><?php esc_html_e( 'Message', 'ccs-wp-theme' ); ?></label>
					<textarea id="contact-message" name="enquiry_message" class="contact-form__textarea" rows="5" placeholder="<?php esc_attr_e( 'Tell us a bit about your situation and what you need.', 'ccs-wp-theme' ); ?>"></textarea>

					<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">

					<button type="submit" class="btn btn-primary contact-form__submit"><?php esc_html_e( 'Send message', 'ccs-wp-theme' ); ?></button>
					<div id="contact-form-message" data-ccs-form-message class="ccs-form-message contact-form__message" role="alert" aria-live="polite" hidden></div>
				</form>
			</div>

			<!-- RIGHT: Contact info -->
			<aside class="contact-info-col">
				<div class="contact-info">
					<?php if ( $ccs_phone_tel ) : ?>
						<p class="contact-info__phone-wrap">
							<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="contact-info__phone"><?php echo esc_html( $ccs_phone ); ?></a>
						</p>
					<?php endif; ?>

					<?php if ( $ccs_hours ) : ?>
						<div class="contact-info__hours">
							<h3 class="contact-info__hours-heading"><?php esc_html_e( 'Office hours', 'ccs-wp-theme' ); ?></h3>
							<div class="contact-info__hours-calendar">
								<p class="contact-info__hours-text"><?php echo esc_html( $ccs_hours ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $ccs_email ) : ?>
						<p class="contact-info__email">
							<a href="<?php echo esc_url( 'mailto:' . antispambot( $ccs_email ) ); ?>"><?php echo esc_html( antispambot( $ccs_email ) ); ?></a>
						</p>
					<?php endif; ?>

					<?php if ( $ccs_address ) : ?>
						<div class="contact-info__address">
							<h3 class="contact-info__address-heading"><?php esc_html_e( 'Office address', 'ccs-wp-theme' ); ?></h3>
							<address class="contact-info__address-text"><?php echo nl2br( esc_html( $ccs_address ) ); ?></address>
						</div>
					<?php endif; ?>

					<?php if ( $ccs_map ) : ?>
						<div class="contact-info__map">
							<?php
							$allowed = array(
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
							echo wp_kses( $ccs_map, $allowed );
							?>
						</div>
					<?php endif; ?>

					<?php if ( $ccs_parking ) : ?>
						<div class="contact-info__parking">
							<h3 class="contact-info__parking-heading"><?php esc_html_e( 'Parking', 'ccs-wp-theme' ); ?></h3>
							<p class="contact-info__parking-text"><?php echo esc_html( $ccs_parking ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $ccs_phone_tel ) : ?>
						<div class="contact-info__emergency" role="note">
							<p class="contact-info__emergency-text"><?php esc_html_e( 'Urgent hospital discharge? Call now.', 'ccs-wp-theme' ); ?></p>
							<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn btn-phone contact-info__emergency-cta"><?php echo esc_html( $ccs_phone ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</aside>

		</div>
	</div>

	<!-- Quick contact cards -->
	<section class="contact-cards" aria-labelledby="contact-cards-heading">
		<div class="contact-cards__inner container container--lg">
			<h2 id="contact-cards-heading" class="contact-cards__heading"><?php esc_html_e( 'How can we help?', 'ccs-wp-theme' ); ?></h2>
			<div class="contact-cards__grid">
				<a href="<?php echo esc_url( home_url( '/contact/#contact-form' ) ); ?>" class="contact-card card">
					<div class="contact-card__body card-body">
						<h3 class="contact-card__title"><?php esc_html_e( 'Book assessment', 'ccs-wp-theme' ); ?></h3>
						<p class="contact-card__desc"><?php esc_html_e( 'Schedule a visit so we can understand your needs and discuss options.', 'ccs-wp-theme' ); ?></p>
						<span class="contact-card__link"><?php esc_html_e( 'Get in touch', 'ccs-wp-theme' ); ?> &rarr;</span>
					</div>
				</a>
				<?php if ( $ccs_phone_tel ) : ?>
					<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="contact-card contact-card--urgent card">
						<div class="contact-card__body card-body">
							<h3 class="contact-card__title"><?php esc_html_e( 'Emergency care', 'ccs-wp-theme' ); ?></h3>
							<p class="contact-card__desc"><?php esc_html_e( 'Urgent situations including hospital discharge. We’ll respond as quickly as we can.', 'ccs-wp-theme' ); ?></p>
							<span class="contact-card__link"><?php esc_html_e( 'Call now', 'ccs-wp-theme' ); ?> &rarr;</span>
						</div>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/contact/#contact-form' ) ); ?>" class="contact-card card">
					<div class="contact-card__body card-body">
						<h3 class="contact-card__title"><?php esc_html_e( 'General enquiry', 'ccs-wp-theme' ); ?></h3>
						<p class="contact-card__desc"><?php esc_html_e( 'Questions about our services, funding, or how we work. We’re happy to help.', 'ccs-wp-theme' ); ?></p>
						<span class="contact-card__link"><?php esc_html_e( 'Send message', 'ccs-wp-theme' ); ?> &rarr;</span>
					</div>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
