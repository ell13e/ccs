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
get_template_part( 'template-parts/resource-download-modal' );
get_template_part( 'template-parts/resource-unavailable-modal' );
?>

<?php wp_footer(); ?>
</body>
</html>
