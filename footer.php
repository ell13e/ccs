<?php
/**
 * Footer template (CTA-style)
 *
 * @package CCS_WP_Theme
 */
?>
</div><!-- #content -->

<footer id="colophon" class="site-footer site-footer-modern" role="contentinfo">
	<div class="footer-modern-container">
		<div class="footer-modern-top">
			<div class="footer-modern-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-modern-logo-link" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<p class="footer-modern-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<?php endif; ?>
				<?php
				/*
				 * Name, address, phone and hours together. The footer previously
				 * carried only a phone number, which left the single most
				 * important block of local-SEO data on any site — consistent NAP
				 * — incomplete, and gave a visitor no way to see where the
				 * office actually is or when someone will answer.
				 *
				 * The address falls back to the registered office so the block
				 * is never empty on a fresh install; a Customizer value wins.
				 * Same default as the organisation schema in
				 * inc/seo/class-structured-data.php, so the published address
				 * and the marked-up address cannot disagree.
				 */
				$footer_phone   = get_theme_mod( 'ccs_phone', '01234 567890' );
				$footer_address = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
				$footer_hours   = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
				if ( $footer_phone || $footer_address || $footer_hours ) :
					?>
					<div class="footer-modern-contact">
						<?php if ( $footer_phone ) : ?>
							<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $footer_phone ) ); ?>" class="footer-modern-contact__phone"><?php echo esc_html( $footer_phone ); ?></a>
						<?php endif; ?>
						<?php if ( $footer_address ) : ?>
							<address class="footer-modern-contact__address"><?php echo nl2br( esc_html( $footer_address ) ); ?></address>
						<?php endif; ?>
						<?php if ( $footer_hours ) : ?>
							<p class="footer-modern-contact__hours">
								<span class="footer-modern-contact__hours-label"><?php esc_html_e( 'Office hours', 'ccs-wp-theme' ); ?></span>
								<?php echo esc_html( $footer_hours ); ?>
							</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/cqc-regulated-badge', null, array( 'variant' => 'purple' ) ); ?>
			</div>
		</div>

		<div class="footer-modern-grid">
			<nav class="footer-modern-col" aria-label="<?php esc_attr_e( 'Company', 'ccs-wp-theme' ); ?>">
				<h3 class="footer-modern-heading"><?php esc_html_e( 'Company', 'ccs-wp-theme' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_company' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_company',
							'menu_class'     => 'footer-modern-links',
							'container'      => false,
							'link_class'     => 'footer-modern-link',
						)
					);
				} else {
					ccs_footer_company_fallback_menu();
				}
				?>
			</nav>

			<nav class="footer-modern-col" aria-label="<?php esc_attr_e( 'Help and support', 'ccs-wp-theme' ); ?>">
				<h3 class="footer-modern-heading"><?php esc_html_e( 'Help', 'ccs-wp-theme' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_help' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_help',
							'menu_class'     => 'footer-modern-links',
							'container'      => false,
							'link_class'     => 'footer-modern-link',
						)
					);
				} else {
					ccs_footer_help_fallback_menu();
				}
				?>
			</nav>

			<?php
			/*
			 * Areas covered, built from the published location posts rather than
			 * a hardcoded list, so it stays true as towns are added and every
			 * entry is a real internal link to a real page.
			 *
			 * This is the footer's biggest local-SEO gap closed. The benchmarked
			 * competitors all name places explicitly — Bluebird lists villages
			 * around Maidstone (Sutton Valence, Harrietsham, Ulcombe, Marden,
			 * Staplehurst…), Superior's Kent page names hospitals, councils and
			 * the ICB — and both rank on that specificity. This footer named
			 * nowhere at all. It also fills the empty right-hand half the two
			 * column grid left behind.
			 */
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

			if ( ! empty( $ccs_footer_towns ) ) :
				?>
				<nav class="footer-modern-col" aria-label="<?php esc_attr_e( 'Areas we cover', 'ccs-wp-theme' ); ?>">
					<h3 class="footer-modern-heading"><?php esc_html_e( 'Where we work', 'ccs-wp-theme' ); ?></h3>
					<ul class="footer-modern-links">
						<?php foreach ( $ccs_footer_towns as $ccs_town ) : ?>
							<li>
								<a class="footer-modern-link" href="<?php echo esc_url( $ccs_town['url'] ); ?>">
									<?php echo esc_html( $ccs_town['town'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</div>

		<?php
		$facebook = get_theme_mod( 'ccs_facebook_url', '' );
		$linkedin = get_theme_mod( 'ccs_linkedin_url', '' );
		$twitter  = get_theme_mod( 'ccs_twitter_url', '' );
		$has_social = $facebook !== '' || $linkedin !== '' || $twitter !== '';
		if ( $has_social ) :
			$social_links = array();
			if ( $facebook !== '' ) {
				$social_links[] = array( 'url' => $facebook, 'name' => 'Facebook', 'label' => __( 'Facebook (opens in new window)', 'ccs-wp-theme' ) );
			}
			if ( $linkedin !== '' ) {
				$social_links[] = array( 'url' => $linkedin, 'name' => 'LinkedIn', 'label' => __( 'LinkedIn (opens in new window)', 'ccs-wp-theme' ) );
			}
			if ( $twitter !== '' ) {
				$social_links[] = array( 'url' => $twitter, 'name' => 'Twitter', 'label' => __( 'Twitter (opens in new window)', 'ccs-wp-theme' ) );
			}
			?>
			<nav class="footer-modern-social" aria-label="<?php esc_attr_e( 'Social media', 'ccs-wp-theme' ); ?>">
				<ul class="footer-modern-social-list">
					<?php foreach ( $social_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $link['label'] ); ?>"><?php echo esc_html( $link['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<div class="footer-modern-bottom">
			<p class="footer-modern-copyright">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'ccs-wp-theme' ); ?>
			</p>
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
	<span aria-hidden="true"><?php esc_html_e( 'Back to top', 'ccs-wp-theme' ); ?></span>
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
