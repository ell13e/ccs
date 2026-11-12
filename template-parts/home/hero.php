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
			 * The regulator's own live rating widget (Nov 2026). Providers must show
			 * their CQC rating on their website's home page, and the client asked
			 * for the official widget here. It replaced a rating set in the site's
			 * type, which the client didn't like. The widget is CQC's markup and is
			 * not restyled; we only place it, clip its corners and add a shadow.
			 * If the script is blocked or CQC is down, the official "Inspected and
			 * rated" artwork shows instead, linking to the same report.
			 */
			$hero_cqc_id = get_theme_mod( 'ccs_cqc_widget_data_id', '1-2624556588' );
			$hero_cqc_id = $hero_cqc_id ? $hero_cqc_id : '1-2624556588';
			?>
			<div class="hero-cqc">
				<?php if ( $hero_has_cqc_badge ) : ?>
					<a id="hero-cqc-fallback" class="hero-cqc__fallback" href="<?php echo esc_url( $hero_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $hero_cqc_badge_src ); ?>" alt="<?php esc_attr_e( 'CQC inspected and rated Good (opens in a new window)', 'ccs-wp-theme' ); ?>" width="1184" height="821"<?php echo ccs_theme_image_srcset( 'cqc-badges/CQC inspected and rated good RGB.jpg', '200px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?> loading="lazy" decoding="async">
					</a>
				<?php endif; ?>
				<script
					type="text/javascript"
					src="<?php echo esc_url( 'https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=' . rawurlencode( $hero_cqc_id ) . '&data-host=https://www.cqc.org.uk&type=location' ); ?>"
					onerror="var f=document.getElementById('hero-cqc-fallback');if(f){f.classList.add('is-visible');}"
				></script>
			</div>


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
<?php
/*
 * One structure for all three routes (Nov 2026, client: "messy inside"):
 * a small label, one main line that is the link (the whole item is
 * clickable through it), and one short supporting line.
 */
$hero_addr_lines = array_values( array_filter( array_map( 'trim', preg_split( '/\n/', (string) $hero_address ) ) ) );
$hero_addr_parts = $hero_addr_lines ? array_map( 'trim', explode( ',', $hero_addr_lines[0], 2 ) ) : array();
$hero_addr_main  = $hero_addr_parts ? $hero_addr_parts[0] : '';
$hero_postcode   = ( isset( $hero_addr_lines[1] ) && preg_match( '/[A-Z]{1,2}\d[A-Z\d]?\s*\d[A-Z]{2}\s*$/i', $hero_addr_lines[1], $hero_pc ) ) ? strtoupper( trim( $hero_pc[0] ) ) : '';
$hero_addr_rest  = trim( implode( ', ', array_filter( array( preg_replace( '/^The\s+/i', '', $hero_addr_main ), $hero_postcode ) ) ) );
?>
<nav class="home-routes" aria-label="<?php esc_attr_e( 'Ways to get started', 'ccs-wp-theme' ); ?>">
	<div class="container container--lg">
		<ul class="home-routes__card" role="list">

			<?php if ( ! empty( $hero_contact['phone'] ) ) : ?>
				<li class="home-routes__item">
					<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div class="home-routes__body">
						<a class="home-routes__main" data-ccs-exception="rule 14: the whole route item is the tap target, through a stretched link" href="<?php echo esc_url( $hero_contact['phone_link'] ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $hero_contact['phone'] ); ?></a>
						<?php if ( $hero_hours ) : ?>
							<p class="home-routes__meta"><?php echo esc_html( $hero_hours ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endif; ?>

			<li class="home-routes__item home-routes__item--switch">
				<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'switch' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<div class="home-routes__body">
					<a class="home-routes__main" data-ccs-exception="rule 14: the whole route item is the tap target, through a stretched link" href="<?php echo esc_url( $hero_switch_url ); ?>"><?php esc_html_e( 'Switch to us', 'ccs-wp-theme' ); ?></a>
					<p class="home-routes__meta"><?php esc_html_e( 'We plan the handover for you', 'ccs-wp-theme' ); ?></p>
				</div>
			</li>

			<?php if ( $hero_addr_main ) : ?>
				<li class="home-routes__item home-routes__item--office">
					<span class="ccs-badge home-routes__badge" aria-hidden="true"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div class="home-routes__body">
						<?php if ( $hero_directions ) : ?>
							<a class="home-routes__main" data-ccs-exception="rule 14: the whole route item is the tap target, through a stretched link" href="<?php echo esc_url( $hero_directions ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Maidstone office', 'ccs-wp-theme' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(directions in Google Maps, opens in a new window)', 'ccs-wp-theme' ); ?></span></a>
						<?php else : ?>
							<p class="home-routes__main"><?php esc_html_e( 'Maidstone office', 'ccs-wp-theme' ); ?></p>
						<?php endif; ?>
						<?php if ( $hero_addr_rest ) : ?>
							<p class="home-routes__meta"><?php echo esc_html( $hero_addr_rest ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endif; ?>

		</ul>
	</div>
</nav>
