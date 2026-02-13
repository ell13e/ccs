<?php
/**
 * Homepage "Why Choose Us" conversion section.
 * Content from CCS-THEME-AND-CONTENT-GUIDE.md Section 2b. Design: two-column (text left, image right), Soft UI, pull quote, teal link.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image_id = get_theme_mod( 'ccs_why_choose_us_image', 0 );
$services_page_id = get_theme_mod( 'ccs_services_page', 0 );
$services_url = $services_page_id ? get_permalink( $services_page_id ) : home_url( '/home/home-care-services-kent/' );

$heading = __( 'Why Choose Us for Home Care in Maidstone?', 'ccs-wp-theme' );
/* translators: %1$s and %2$s are placeholders for <em>what</em> and <em>how</em> */
$subheading = sprintf( __( "It's not just %1\$s we do, it's %2\$s we do it.", 'ccs-wp-theme' ), '<em>' . esc_html__( 'what', 'ccs-wp-theme' ) . '</em>', '<em>' . esc_html__( 'how', 'ccs-wp-theme' ) . '</em>' );
$quote = __( 'Our caring, local team is dedicated to supporting families across Kent.', 'ccs-wp-theme' );
$link_text = __( 'Learn more about the home care services we offer in Maidstone & Kent.', 'ccs-wp-theme' );
$image_alt = __( 'Young woman supported by Continuity of Care Services home care staff at the Summer Party at Lainey\'s Care Farm in Kent', 'ccs-wp-theme' );
?>

<section id="why-choose-us" class="why-choose-us" aria-labelledby="why-choose-us-heading">
	<div class="why-choose-us__inner container container--lg">
		<div class="why-choose-us__grid">
			<div class="why-choose-us__content">
				<h2 id="why-choose-us-heading" class="why-choose-us__heading" data-animate="fade-up" data-delay="0"><?php echo esc_html( $heading ); ?></h2>
				<p class="why-choose-us__subheading"><?php echo $subheading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- contains intentional <em> ?></p>
				<div class="why-choose-us__body" data-animate="fade-up" data-delay="100">
					<p><?php
						echo esc_html__(
							'Reliably supporting adults and children across Maidstone and Kent, we\'re here to provide personalised care, day or night, tailored to you.',
							'ccs-wp-theme'
						);
					?></p>
					<blockquote class="why-choose-us__quote" cite="">
						<?php echo esc_html( $quote ); ?>
					</blockquote>
					<p><?php
						echo esc_html__(
							'We don\'t rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day. We believe that the best care doesn\'t stop when the to-do list is ticked; it continues through our staff showing up in a way that feels friendly, familiar, and person-centred.',
							'ccs-wp-theme'
						);
					?></p>
					<p class="why-choose-us__cta">
						<a href="<?php echo esc_url( $services_url ); ?>" class="why-choose-us__link"><?php echo esc_html( $link_text ); ?></a>
					</p>
				</div>
			</div>
			<?php if ( $image_id ) : ?>
				<figure class="why-choose-us__media">
					<?php
					echo wp_get_attachment_image(
						(int) $image_id,
						'large',
						false,
						array(
							'class'    => 'why-choose-us__image',
							'alt'      => $image_alt,
							'loading'  => 'lazy',
							'sizes'    => '(min-width: 1024px) 42vw, (min-width: 768px) 48vw, 100vw',
							'decoding' => 'async',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
