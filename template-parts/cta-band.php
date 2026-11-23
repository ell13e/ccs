<?php
/**
 * Reusable pre-footer CTA: the homepage's closing card (.cta-card,
 * assets/css/sections.css) on every inner page, so every page closes the same
 * way. Copy follows the "Ready when you are" block in
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
/*
 * Two photos in rotation since 24 Nov 2026: the pottery photos are removed
 * from the site (client: "we can't use photos of this young man"), and no
 * photo of him may be added back.
 * Three photos in rotation (Nov 2026). A design review found the pottery
 * photo closing about twenty pages, and repeated photos are something the
 * client has rejected. The page's address picks one, so a page always shows the
 * same photo and neighbouring pages usually differ. The two added photos are
 * landscape crops of client originals (crop and resize only, rule 15), used
 * nowhere else on the site. Each entry: file, width, height, object-position.
 */
$ccs_cta_photos    = array(
	array( 'site-photos/client-afternoon-tea.webp', 1000, 667, '50% 30%' ),
	array( 'site-photos/client-activity-laughing.webp', 1000, 667, '60% 40%' ),
);
// Keyed on the page's address, so archives (no ID) take part in the rotation too.
// The 'f' prefix is only a seed: of those tried, it spread today's pages most evenly (8, 8 and 9).
$ccs_cta_key       = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
$ccs_cta_pick      = $ccs_cta_photos[ absint( crc32( 'f' . trim( $ccs_cta_key, '/' ) ) ) % count( $ccs_cta_photos ) ];
if ( ! file_exists( get_template_directory() . '/assets/images/' . $ccs_cta_pick[0] ) ) {
	$ccs_cta_pick = $ccs_cta_photos[0];
}
$ccs_cta_photo_rel = $ccs_cta_pick[0];
$ccs_cta_photo     = ! $ccs_is_careers && file_exists( get_template_directory() . '/assets/images/' . $ccs_cta_photo_rel );

?>

<section class="cta-band" aria-labelledby="cta-band-heading">
	<div class="container container--lg">
		<div class="cta-card<?php echo $ccs_cta_photo ? ' cta-card--photo' : ''; ?>">

			<div class="cta-card__body">
				<h2 id="cta-band-heading" class="ccs-title ccs-title--on-dark">
					<?php echo esc_html( $ccs_cta_heading ); ?>
				</h2>
				<?php // A blank line in the text starts a new paragraph (the client's Nov 2026 wording runs to two). ?>
				<?php foreach ( array_filter( array_map( 'trim', preg_split( '/\R\s*\R/', (string) $ccs_cta_text ) ) ) as $ccs_cta_para ) : ?>
					<p class="ccs-lede ccs-lede--on-dark"><?php echo esc_html( $ccs_cta_para ); ?></p>
				<?php endforeach; ?>
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
						width="<?php echo (int) $ccs_cta_pick[1]; ?>"
						height="<?php echo (int) $ccs_cta_pick[2]; ?>"
						style="object-position: <?php echo esc_attr( $ccs_cta_pick[3] ); ?>;"
						<?php echo ccs_theme_image_srcset( $ccs_cta_photo_rel, '(min-width: 900px) 450px, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
						loading="lazy"
						decoding="async"
					>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
