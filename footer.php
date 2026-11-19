<?php
/**
 * Footer template (CTA-style)
 *
 * @package CCS_WP_Theme
 */
?>
</div><!-- #content -->

<?php
/*
 * Footer, rebuilt Nov 2026 (client, sharing a reference: "do you think our
 * footer could be something like this?"). A pale, rounded panel instead of
 * the dark band: the logo, promise and how to reach the office on the left;
 * three short link columns on the right (Care, About, Areas); one slim
 * bottom row for copyright, the policy pages and the CQC mark. The faint
 * infinity mark in the corner is the same watermark the page heroes use.
 * Social links appear only when they are set in the Customizer.
 */
$footer_phone   = get_theme_mod( 'ccs_phone', '01622 809 881' );
$footer_address = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
$footer_hours   = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$footer_email   = get_theme_mod( 'ccs_contact_email', '' );
$ccs_ft_url     = static function ( $slug ) {
	return function_exists( 'ccs_page_url' ) ? ccs_page_url( $slug ) : home_url( '/' . $slug . '/' );
};
$ccs_ft_service = static function ( $slug ) {
	$found = get_posts( array( 'post_type' => 'service', 'name' => $slug, 'post_status' => 'publish', 'numberposts' => 1, 'no_found_rows' => true ) );
	return $found ? get_permalink( $found[0] ) : home_url( '/services/' . $slug . '/' );
};

/*
 * Every service, in the site's order (Nov 2026: eight, where this column
 * listed three). Names in sentence case, as the rest of the footer. Costs and
 * Getting started moved to the second column so the two stay a similar length.
 */
$ccs_ft_services = array();
// The first eight only: with twenty-one services (Nov 2026) the column would run to the foot of the page. The last link goes to them all.
foreach ( array_slice( function_exists( 'ccs_ordered_services' ) ? ccs_ordered_services() : array(), 0, 8 ) as $ccs_ft_post ) {
	$ccs_ft_services[] = array( ucfirst( strtolower( get_the_title( $ccs_ft_post ) ) ), get_permalink( $ccs_ft_post ) );
}
if ( $ccs_ft_services ) {
	$ccs_ft_services[] = array( __( 'All our services', 'ccs-wp-theme' ), $ccs_ft_url( 'home-care-services-kent' ) );
}
if ( ! $ccs_ft_services ) {
	$ccs_ft_services = array(
		array( __( 'Domiciliary care', 'ccs-wp-theme' ), $ccs_ft_service( 'domiciliary-care' ) ),
		array( __( 'Respite care', 'ccs-wp-theme' ), $ccs_ft_service( 'respite-care' ) ),
		array( __( 'Complex care', 'ccs-wp-theme' ), $ccs_ft_service( 'complex-care' ) ),
	);
}

$ccs_ft_columns = array(
	array(
		'title' => __( 'Care', 'ccs-wp-theme' ),
		'links' => $ccs_ft_services,
	),
	array(
		'title' => __( 'About', 'ccs-wp-theme' ),
		'links' => array(
			array( __( 'Costs and funding', 'ccs-wp-theme' ), $ccs_ft_url( 'costs-and-funding' ) ),
			array( __( 'Getting started', 'ccs-wp-theme' ), $ccs_ft_url( 'getting-started' ) ),
			array( __( 'Who you’ll meet', 'ccs-wp-theme' ), $ccs_ft_url( 'who-youll-meet' ) ),
			array( __( 'CQC and our care', 'ccs-wp-theme' ), $ccs_ft_url( 'cqc-and-our-care' ) ),
			array( __( 'FAQs', 'ccs-wp-theme' ), $ccs_ft_url( 'faqs' ) ),
			array( __( 'Careers', 'ccs-wp-theme' ), $ccs_ft_url( 'careers' ) ),
			array( __( 'Contact us', 'ccs-wp-theme' ), $ccs_ft_url( 'contact-us' ) ),
		),
	),
);

/*
 * The towns, by name only ("Home Care in X" on every line buried the one
 * word that differs), each once, Maidstone first.
 */
$ccs_ft_towns = array();
foreach ( get_posts( array( 'post_type' => 'location', 'post_status' => 'publish', 'numberposts' => 30, 'orderby' => 'title', 'order' => 'ASC', 'no_found_rows' => true ) ) as $ccs_loc ) {
	$ccs_town = trim( (string) get_post_meta( $ccs_loc->ID, 'location_town', true ) );
	$ccs_town = '' !== $ccs_town ? $ccs_town : preg_replace( '/^Home Care in\s+/i', '', get_the_title( $ccs_loc ) );
	$ccs_key  = strtolower( $ccs_town );
	if ( '' === $ccs_town || isset( $ccs_ft_towns[ $ccs_key ] ) ) {
		continue;
	}
	$ccs_ft_towns[ $ccs_key ] = array( $ccs_town, get_permalink( $ccs_loc ) );
}
uksort(
	$ccs_ft_towns,
	static function ( $a, $b ) {
		return ( 'maidstone' === $a ? -1 : ( 'maidstone' === $b ? 1 : strcmp( $a, $b ) ) );
	}
);

$ccs_ft_lines = array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $footer_address ) ) ) );
$ccs_ft_legal = array(
	array( __( 'Privacy policy', 'ccs-wp-theme' ), $ccs_ft_url( 'privacy-policy' ) ),
	array( __( 'Terms', 'ccs-wp-theme' ), $ccs_ft_url( 'terms-and-conditions' ) ),
	array( __( 'Accessibility', 'ccs-wp-theme' ), $ccs_ft_url( 'accessibility-statement' ) ),
	array( __( 'Cookies', 'ccs-wp-theme' ), $ccs_ft_url( 'cookies' ) ),
);
$ccs_ft_social = array_filter(
	array(
		'Facebook' => get_theme_mod( 'ccs_facebook_url', '' ),
		'LinkedIn' => get_theme_mod( 'ccs_linkedin_url', '' ),
	)
);
?>
<footer id="colophon" class="site-footer ftp" role="contentinfo">
	<div class="container container--lg">
		<div class="ftp__panel">

			<div class="ftp__main">
				<div class="ftp__brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ftp__logo" rel="home">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/ccs-logo-long.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="1617" height="276" loading="lazy" decoding="async">
					</a>
					<p class="ftp__promise"><?php esc_html_e( 'Your team. Your time. Your life.', 'ccs-wp-theme' ); ?></p>
					<?php if ( $footer_phone ) : ?>
						<a class="ftp__phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $footer_phone ) ); ?>"><?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><?php echo esc_html( $footer_phone ); ?></span></a>
					<?php endif; ?>
					<address class="ftp__address">
						<?php echo wp_kses( implode( '<br>', array_map( 'esc_html', $ccs_ft_lines ) ), array( 'br' => array() ) ); ?>
						<?php if ( $footer_hours ) : ?>
							<br><span class="ftp__hours"><?php echo esc_html( $footer_hours ); ?></span>
						<?php endif; ?>
						<?php if ( $footer_email ) : ?>
							<br><a class="ftp__link" href="<?php echo esc_url( 'mailto:' . antispambot( $footer_email ) ); ?>"><?php echo esc_html( antispambot( $footer_email ) ); ?></a>
						<?php endif; ?>
					</address>
				</div>

				<div class="ftp__cols">
					<?php foreach ( $ccs_ft_columns as $ccs_col ) : ?>
						<nav class="ftp__col" aria-label="<?php echo esc_attr( $ccs_col['title'] ); ?>">
							<h2 class="ftp__heading"><?php echo esc_html( $ccs_col['title'] ); ?></h2>
							<ul class="ftp__links" role="list">
								<?php foreach ( $ccs_col['links'] as $ccs_link ) : ?>
									<li><a class="ftp__link" href="<?php echo esc_url( $ccs_link[1] ); ?>"><?php echo esc_html( $ccs_link[0] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endforeach; ?>
					<?php if ( $ccs_ft_towns ) : ?>
						<nav class="ftp__col ftp__col--areas" aria-label="<?php esc_attr_e( 'Areas we cover', 'ccs-wp-theme' ); ?>">
							<h2 class="ftp__heading"><?php esc_html_e( 'Areas', 'ccs-wp-theme' ); ?></h2>
							<ul class="ftp__links ftp__links--towns" role="list">
								<?php foreach ( $ccs_ft_towns as $ccs_town ) : ?>
									<li><a class="ftp__link" href="<?php echo esc_url( $ccs_town[1] ); ?>"><?php echo esc_html( $ccs_town[0] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
			</div>

			<div class="ftp__bottom">
				<p class="ftp__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
				<ul class="ftp__legal" role="list">
					<?php foreach ( $ccs_ft_legal as $ccs_link ) : ?>
						<li><a class="ftp__link" href="<?php echo esc_url( $ccs_link[1] ); ?>"><?php echo esc_html( $ccs_link[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="ftp__end">
					<?php foreach ( $ccs_ft_social as $ccs_net => $ccs_href ) : ?>
						<a class="ftp__link" href="<?php echo esc_url( $ccs_href ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $ccs_net ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span></a>
					<?php endforeach; ?>
					<div class="ftp__cqc"><?php get_template_part( 'template-parts/cqc-regulated-badge', null, array( 'variant' => 'purple' ) ); ?></div>
				</div>
			</div>

		</div>
	</div>
</footer>

<?php
$ccs_fab_contact = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array();
if ( ! empty( $ccs_fab_contact['phone'] ) ) :
	?>
	<a
		href="<?php echo esc_url( $ccs_fab_contact['phone_link'] ); ?>"
		class="mobile-call-fab"
		aria-label="<?php echo esc_attr( sprintf( /* translators: %s: phone number */ __( 'Call us: %s', 'ccs-wp-theme' ), $ccs_fab_contact['phone'] ) ); ?>"
	>
		<svg class="mobile-call-fab__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
			<path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.46.57 3.58a1 1 0 0 1-.25 1.01l-2.2 2.2Z" fill="currentColor" />
		</svg>
	</a>
<?php endif; ?>

<button type="button" id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'ccs-wp-theme' ); ?>" aria-hidden="true" title="<?php esc_attr_e( 'Back to top', 'ccs-wp-theme' ); ?>">
	<svg class="back-to-top__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 19V5"/><path d="m5.5 11.5 6.5-6.5 6.5 6.5"/></svg>
</button>

<script>
(function() {
	var btn = document.getElementById('back-to-top');
	if (!btn) return;
	var threshold = 300;
	function updateVisibility() {
		var show = window.scrollY > threshold;
		btn.classList.toggle('visible', show);
		btn.setAttribute('aria-hidden', show ? 'false' : 'true');
	}
	function scrollToTop() {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	}
	window.addEventListener('scroll', function() { requestAnimationFrame(updateVisibility); }, { passive: true });
	window.addEventListener('load', updateVisibility);
	btn.addEventListener('click', scrollToTop);
})();
</script>

<?php
// Only on pages that can actually trigger a care-guide download; see ccs_needs_resource_downloads().
if ( ! function_exists( 'ccs_needs_resource_downloads' ) || ccs_needs_resource_downloads() ) {
	get_template_part( 'template-parts/resource-download-modal' );
	get_template_part( 'template-parts/resource-unavailable-modal' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
