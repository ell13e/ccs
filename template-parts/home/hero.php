<?php
/**
 * Homepage hero: full-bleed photo, gradient scrim, content anchored bottom-left.
 *
 * Structure follows the "Finding what fits" prototype in
 * reference/finding-what-fits-prototype/ (the agreed design quality bar):
 * immersive photo rather than a side-by-side column, dual-gradient scrim so
 * white type stays legible over any image, and an F-pattern content order —
 * H1, then the brand promise, then support copy, then actions, then quiet proof.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_image_id = get_theme_mod( 'ccs_hero_image', 0 );
$hero_image    = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
$hero_alt      = $hero_image_id ? get_post_meta( $hero_image_id, '_wp_attachment_image_alt', true ) : '';
$hero_alt      = is_string( $hero_alt ) ? trim( $hero_alt ) : '';

$hero_contact_url  = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );

$hero_contact  = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
$hero_cqc_url  = get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' );
$hero_years    = absint( get_theme_mod( 'ccs_hero_stat_value', '10' ) );

// Floating CQC rating badge (official artwork), overlapping the hero photo —
// the same treatment used by every regulated competitor benchmarked in the
// UI audit (Home Instead, Bluebird). Links to the specific CQC report/profile
// via $hero_cqc_url, same target the old text-only link used.
$hero_cqc_badge_file = get_template_directory() . '/assets/images/cqc-badges/CQC inspected and rated good RGB.jpg';
$hero_cqc_badge_src  = get_template_directory_uri() . '/assets/images/cqc-badges/CQC inspected and rated good RGB.jpg';
$hero_has_cqc_badge  = file_exists( $hero_cqc_badge_file );

/*
 * Packaged default hero, so the homepage never renders empty before someone
 * sets an image in the Customizer. Single wide source: this photo's subjects
 * sit right of centre, which leaves the left band clear for the text panel,
 * so one crop works across breakpoints via object-position.
 */
$hero_img_base     = get_template_directory_uri() . '/assets/images/site-photos/';
$hero_default_file = 'home-hero-family.jpg';
$hero_has_fallback = file_exists( get_template_directory() . '/assets/images/site-photos/' . $hero_default_file );
?>

<section class="home-hero" aria-labelledby="home-hero-heading">

	<?php if ( $hero_image ) : ?>
		<img class="home-hero__bg" src="<?php echo esc_url( $hero_image ); ?>" alt="" aria-hidden="true" fetchpriority="high" decoding="async">
	<?php elseif ( $hero_has_fallback ) : ?>
		<img class="home-hero__bg" src="<?php echo esc_url( $hero_img_base . $hero_default_file ); ?>" alt="" aria-hidden="true" width="2400" height="1600"<?php echo ccs_theme_image_srcset( 'site-photos/' . $hero_default_file, '100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?> fetchpriority="high" decoding="async">
	<?php endif; ?>

	<div class="home-hero__scrim" aria-hidden="true"></div>


	<?php
	/*
	 * container--lg, not --xl. This was the one container on the entire site
	 * using the wider 1024px→1440px scale — every other section, on every page,
	 * uses container--lg (1024px). At wide viewports that put the hero headline
	 * up to 200px further out than the header logo directly above it and the
	 * CQC/Why Choose/Services headings directly below it, which is the specific
	 * misalignment this fixes. .home-hero__panel's own max-width: min(42rem,100%)
	 * already keeps the headline from feeling lost inside the wider column, so
	 * nothing about the hero's proportions actually depended on --xl.
	 */
	?>
	<div class="home-hero__inner container container--lg">
		<div class="home-hero__panel">

			<h1 id="home-hero-heading" class="home-hero__title">
				<?php echo esc_html( ccs_copy( 'hero_title' ) ); ?>
			</h1>

			<p class="home-hero__promise">
				<?php echo esc_html( ccs_copy( 'hero_promise' ) ); ?>
			</p>

			<?php
			/*
			 * Cut back (Nov 2026; the client found the hero busy): heading, promise
			 * and one action. The support paragraph said what the next sections
			 * say, and the proof line repeated the CQC badge beside it.
			 */
			?>
			<div class="home-hero__actions">
				<a href="<?php echo esc_url( $hero_contact_url ); ?>" class="btn btn-accent home-hero__cta">
					<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
				</a>
			</div>

			<?php
			/*
			 * The CQC rating set in the site's own type on the photo (client, Nov
			 * 2026: "a nicely styled version of the CQC frame, not just in a card").
			 * It replaced the regulator's badge image, which sat in a white box. The
			 * rating and date come from Customize > CQC Information, as on the CQC
			 * page, and link to the full report on cqc.org.uk. Bottom-right on the
			 * content edge at desktop, under the button on phones.
			 */
			$hero_ratings   = array(
				'outstanding'          => __( 'Outstanding', 'ccs-wp-theme' ),
				'good'                 => __( 'Good', 'ccs-wp-theme' ),
				'requires-improvement' => __( 'Requires improvement', 'ccs-wp-theme' ),
				'inadequate'           => __( 'Inadequate', 'ccs-wp-theme' ),
			);
			$hero_rating    = (string) get_theme_mod( 'ccs_cqc_rating', '' );
			$hero_rating    = isset( $hero_ratings[ $hero_rating ] ) ? $hero_rating : 'good';
			$hero_rated_on  = (string) get_theme_mod( 'ccs_cqc_rating_date', '6 December 2023' );
			?>
			<a class="hero-rating hero-rating--<?php echo esc_attr( $hero_rating ); ?>" href="<?php echo esc_url( $hero_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
				<span class="hero-rating__label"><?php esc_html_e( 'CQC overall rating', 'ccs-wp-theme' ); ?></span>
				<span class="hero-rating__value"><span class="hero-rating__dot" aria-hidden="true"></span><?php echo esc_html( $hero_ratings[ $hero_rating ] ); ?></span>
				<span class="hero-rating__meta">
					<?php
					if ( '' !== $hero_rated_on ) {
						/* translators: %s: date the CQC rating was published. */
						printf( esc_html__( 'Published %s', 'ccs-wp-theme' ), esc_html( $hero_rated_on ) );
						echo ' &middot; ';
					}
					esc_html_e( 'Read the report', 'ccs-wp-theme' );
					?>
					<span aria-hidden="true">&rarr;</span>
				</span>
				<span class="screen-reader-text"><?php esc_html_e( '(opens cqc.org.uk in a new window)', 'ccs-wp-theme' ); ?></span>
			</a>


		</div>
	</div>
</section>

<?php
/*
 * The three ways in, as one card lapping over the foot of the hero. It
 * answers the three questions people arrive with: can I talk to someone now,
 * I already have care that is not working, where are you. The hero keeps one
 * action, Book a free consultation (CLAUDE.md rule 8). The client asked for
 * this strip back in this form after a two-route version (Nov 2026).
 */
$hero_switch_url  = function_exists( 'ccs_switch_url' ) ? ccs_switch_url() : home_url( '/contact-us/' );
$hero_hours       = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$hero_address     = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
$hero_address_one = $hero_address ? trim( preg_replace( '/\s*\n\s*/', ', ', $hero_address ) ) : '';
$hero_directions  = $hero_address_one ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $hero_address_one ) : '';
?>
<nav class="home-routes" aria-label="<?php esc_attr_e( 'Ways to get started', 'ccs-wp-theme' ); ?>">
	<div class="container container--lg">
		<ul class="home-routes__card" role="list">

			<?php if ( ! empty( $hero_contact['phone'] ) ) : ?>
				<li class="home-routes__item">
					<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div class="home-routes__body">
						<p class="home-routes__title"><?php esc_html_e( 'Talk to us today', 'ccs-wp-theme' ); ?></p>
						<a class="home-routes__link home-routes__link--phone" href="<?php echo esc_url( $hero_contact['phone_link'] ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $hero_contact['phone'] ); ?>
						</a>
						<?php if ( $hero_hours ) : ?>
							<p class="home-routes__meta"><?php echo esc_html( $hero_hours ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endif; ?>

			<li class="home-routes__item home-routes__item--switch">
				<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'switch' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<div class="home-routes__body">
					<p class="home-routes__title"><?php esc_html_e( 'Already have care?', 'ccs-wp-theme' ); ?></p>
					<p class="home-routes__meta"><?php esc_html_e( 'Moving from another provider? We plan the handover with you.', 'ccs-wp-theme' ); ?></p>
					<a class="home-routes__link" href="<?php echo esc_url( $hero_switch_url ); ?>"><?php esc_html_e( 'How switching works', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
				</div>
			</li>

			<?php if ( $hero_address ) : ?>
				<li class="home-routes__item home-routes__item--office">
					<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div class="home-routes__body">
						<p class="home-routes__title"><?php esc_html_e( 'Our Maidstone office', 'ccs-wp-theme' ); ?></p>
						<address class="home-routes__meta"><?php echo nl2br( esc_html( $hero_address ) ); ?></address>
						<?php if ( $hero_directions ) : ?>
							<a class="home-routes__link" href="<?php echo esc_url( $hero_directions ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Get directions', 'ccs-wp-theme' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens Google Maps in a new window)', 'ccs-wp-theme' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</li>
			<?php endif; ?>

		</ul>
	</div>
</nav>
