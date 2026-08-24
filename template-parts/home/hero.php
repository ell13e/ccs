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
		<img class="home-hero__bg" src="<?php echo esc_url( $hero_img_base . $hero_default_file ); ?>" alt="" aria-hidden="true" width="2400" height="1600" fetchpriority="high" decoding="async">
	<?php endif; ?>

	<div class="home-hero__scrim" aria-hidden="true"></div>

	<?php if ( $hero_has_cqc_badge ) : ?>
		<a
			href="<?php echo esc_url( $hero_cqc_url ); ?>"
			class="home-hero__cqc-badge"
			target="_blank"
			rel="noopener noreferrer"
			aria-label="<?php esc_attr_e( 'CQC inspected and rated Good — read our full report (opens in a new window)', 'ccs-wp-theme' ); ?>"
		>
			<img
				src="<?php echo esc_url( $hero_cqc_badge_src ); ?>"
				alt=""
				width="1184"
				height="821"
				loading="eager"
				decoding="async"
			>
		</a>
	<?php endif; ?>

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
				<?php esc_html_e( 'Home care in Maidstone &amp; Kent', 'ccs-wp-theme' ); ?>
			</h1>

			<p class="home-hero__promise">
				<?php esc_html_e( 'Your team. Your time. Your life.', 'ccs-wp-theme' ); ?>
			</p>

			<p class="home-hero__support">
				<?php esc_html_e( 'From everyday help at home to complex and specialist care, we match you with a small team of carers you’ll get to know.', 'ccs-wp-theme' ); ?>
				<span class="home-hero__support-extra"><?php esc_html_e( 'You’ll see the same faces at every visit.', 'ccs-wp-theme' ); ?></span>
			</p>

			<div class="home-hero__actions">
				<a href="<?php echo esc_url( $hero_contact_url ); ?>" class="btn btn-accent home-hero__cta">
					<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
				</a>
				<?php if ( ! empty( $hero_contact['phone'] ) ) : ?>
					<a href="<?php echo esc_url( $hero_contact['phone_link'] ); ?>" class="home-hero__call">
						<?php
						/* translators: %s: office phone number */
						printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $hero_contact['phone'] ) );
						?>
					</a>
				<?php endif; ?>
			</div>

			<p class="home-hero__proof">
				<span>
					<?php
					/* translators: %d: years of care experience */
					printf( wp_kses_post( __( '<strong>%d+ years</strong> caring for Kent families', 'ccs-wp-theme' ) ), absint( $hero_years ) );
					?>
				</span>
				<span class="home-hero__proof-sep" aria-hidden="true"></span>
				<a class="home-hero__cqc" href="<?php echo esc_url( $hero_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'CQC rated Good', 'ccs-wp-theme' ); ?>
				</a>
			</p>

		</div>
	</div>
</section>
