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
?>
<section class="ccs-section ccs-section--flush-bottom referral">
	<div class="container container--lg">
		<div class="referral__layout">

			<div class="referral__main">
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_rf['intro'] ) ) ) : ?>
					<div class="ccs-lede ccs-lede--large referral__lede">
						<?php echo wp_kses_post( $ccs_rf['intro'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $ccs_rf['sections'] ) : ?>
					<div class="ccs-faq referral__faq">
						<?php foreach ( $ccs_rf['sections'] as $ccs_rf_i => $ccs_rf_section ) : ?>
							<details class="ccs-faq__item" name="referral"<?php echo 0 === $ccs_rf_i ? ' open' : ''; ?>>
								<summary class="ccs-faq__q"><?php echo esc_html( $ccs_rf_section['heading'] ); ?></summary>
								<div class="ccs-faq__a"><?php echo wp_kses_post( $ccs_rf_section['body'] ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $ccs_rf_contact['phone'] ) ) : ?>
				<aside class="referral__aside" aria-label="<?php esc_attr_e( 'Make a referral', 'ccs-wp-theme' ); ?>">
					<div class="contact-call ccs-on-dark">
						<h2 class="contact-call__title"><?php esc_html_e( 'Make a referral', 'ccs-wp-theme' ); ?></h2>
						<a class="contact-call__phone" href="<?php echo esc_url( $ccs_rf_contact['phone_link'] ); ?>">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_rf_contact['phone'] ); ?></span>
						</a>
						<?php if ( $ccs_rf_hours ) : ?>
							<p class="contact-call__hours"><?php echo esc_html( $ccs_rf_hours ); ?></p>
						<?php endif; ?>
						<a class="btn btn-ghost-on-dark referral__write" href="<?php echo esc_url( $ccs_rf_url ); ?>"><?php esc_html_e( 'Send us a message', 'ccs-wp-theme' ); ?></a>
					</div>
				</aside>
			<?php endif; ?>

		</div>
	</div>
</section>
