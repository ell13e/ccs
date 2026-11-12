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
 * Footer, redesigned Nov 2026 (client: "footer needs redesigning"). Three
 * rows on the ink ground:
 * 1. The brand: logo and the promise line, with the phone opposite.
 * 2. Four columns of equal standing: Contact (address, hours), Company,
 *    Help, Where we work.
 * 3. A hairline, then copyright left and the CQC mark right.
 * The site tagline is no longer printed here: it was a keyword string
 * ("Home Care in Maidstone & Kent - Your Team...") read as a sentence.
 */
$footer_phone   = get_theme_mod( 'ccs_phone', '01622 809 881' );
$footer_address = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
$footer_hours   = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
$footer_email   = get_theme_mod( 'ccs_contact_email', '' );
$ccs_footer_locations = get_posts( array(
	'post_type'              => 'location',
	'post_status'            => 'publish',
	'numberposts'            => 12,
	'orderby'                => 'title',
	'order'                  => 'ASC',
	'suppress_filters'       => false,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
) );

/*
 * Town only. Every one of these posts is titled "Home Care in X",
 * which would repeat the same three words down the whole column and
 * bury the only word that differs.
 *
 * Deduplicated by town, because a footer must never list the same
 * place twice however the content got there. Duplicate location
 * posts are exactly what this install had: the theme's provisioning
 * is idempotent, but two PHP workers racing the same activation
 * request insert two copies of everything — the same fault that
 * doubled the primary nav. Resilient here rather than assuming the
 * data is clean.
 */
$ccs_footer_towns = array();
foreach ( $ccs_footer_locations as $ccs_loc ) {
	$ccs_loc_town = get_post_meta( $ccs_loc->ID, 'location_town', true );
	if ( ! is_string( $ccs_loc_town ) || trim( $ccs_loc_town ) === '' ) {
		$ccs_loc_town = preg_replace( '/^Home Care in\s+/i', '', get_the_title( $ccs_loc ) );
	}
	$ccs_loc_town = trim( (string) $ccs_loc_town );
	$ccs_loc_key  = strtolower( $ccs_loc_town );
	if ( $ccs_loc_town === '' || isset( $ccs_footer_towns[ $ccs_loc_key ] ) ) {
		continue;
	}
	$ccs_footer_towns[ $ccs_loc_key ] = array(
		'town' => $ccs_loc_town,
		'url'  => get_permalink( $ccs_loc ),
	);
}

?>
<footer id="colophon" class="site-footer site-footer-modern ft" role="contentinfo">
	<div class="ft__inner container container--lg">

		<div class="ft__top">
			<div class="ft__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php elseif ( file_exists( get_template_directory() . '/assets/images/brand/ccs-logo-long.png' ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ft__logo" rel="home">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/ccs-logo-long.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="1617" height="276" loading="lazy" decoding="async">
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ft__logo ft__logo--text" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
				<p class="ft__promise"><?php esc_html_e( 'Your team. Your time. Your life.', 'ccs-wp-theme' ); ?></p>
			</div>
			<?php if ( $footer_phone ) : ?>
				<div class="ft__call">
					<span class="ft__call-label"><?php esc_html_e( 'Talk to our Maidstone office', 'ccs-wp-theme' ); ?></span>
					<a class="ft__phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $footer_phone ) ); ?>"><?php echo esc_html( $footer_phone ); ?></a>
				</div>
			<?php endif; ?>
		</div>

		<div class="ft__grid">
			<div class="ft__col">
				<h3 class="ft__heading"><?php esc_html_e( 'Visit us', 'ccs-wp-theme' ); ?></h3>
				<?php if ( $footer_address ) : ?>
					<address class="ft__text"><?php echo nl2br( esc_html( $footer_address ) ); ?></address>
				<?php endif; ?>
				<?php if ( $footer_hours ) : ?>
					<p class="ft__text ft__hours"><?php echo esc_html( $footer_hours ); ?></p>
				<?php endif; ?>
				<?php if ( $footer_email ) : ?>
					<p class="ft__text"><a class="ft__link" href="<?php echo esc_url( 'mailto:' . antispambot( $footer_email ) ); ?>"><?php echo esc_html( antispambot( $footer_email ) ); ?></a></p>
				<?php endif; ?>
			</div>

			<nav class="ft__col" aria-label="<?php esc_attr_e( 'Company', 'ccs-wp-theme' ); ?>">
				<h3 class="ft__heading"><?php esc_html_e( 'Company', 'ccs-wp-theme' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_company' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer_company', 'menu_class' => 'ft__links footer-modern-links', 'container' => false, 'link_class' => 'ft__link footer-modern-link' ) );
				} else {
					ccs_footer_company_fallback_menu();
				}
				?>
			</nav>

			<nav class="ft__col" aria-label="<?php esc_attr_e( 'Help and support', 'ccs-wp-theme' ); ?>">
				<h3 class="ft__heading"><?php esc_html_e( 'Help', 'ccs-wp-theme' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_help' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer_help', 'menu_class' => 'ft__links footer-modern-links', 'container' => false, 'link_class' => 'ft__link footer-modern-link' ) );
				} else {
					ccs_footer_help_fallback_menu();
				}
				?>
			</nav>

			<?php if ( ! empty( $ccs_footer_towns ) ) : ?>
				<nav class="ft__col" aria-label="<?php esc_attr_e( 'Areas we cover', 'ccs-wp-theme' ); ?>">
					<h3 class="ft__heading"><?php esc_html_e( 'Where we work', 'ccs-wp-theme' ); ?></h3>
					<ul class="ft__links ft__links--towns">
						<?php foreach ( $ccs_footer_towns as $ccs_town ) : ?>
							<li><a class="ft__link" href="<?php echo esc_url( $ccs_town['url'] ); ?>"><?php echo esc_html( $ccs_town['town'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</div>

		<div class="ft__bottom">
			<p class="ft__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			<?php
			$facebook = get_theme_mod( 'ccs_facebook_url', '' );
			$linkedin = get_theme_mod( 'ccs_linkedin_url', '' );
			if ( $facebook || $linkedin ) :
				?>
				<ul class="ft__social">
					<?php if ( $facebook ) : ?>
						<li><a class="ft__link" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer">Facebook<span class="screen-reader-text"> <?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span></a></li>
					<?php endif; ?>
					<?php if ( $linkedin ) : ?>
						<li><a class="ft__link" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn<span class="screen-reader-text"> <?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<div class="ft__cqc"><?php get_template_part( 'template-parts/cqc-regulated-badge', null, array( 'variant' => 'white' ) ); ?></div>
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
