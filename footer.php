<?php
/**
 * Footer template
 *
 * @package CCS_WP_Theme
 */
?>
</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<?php
		$facebook = get_theme_mod( 'ccs_facebook_url', '' );
		$linkedin = get_theme_mod( 'ccs_linkedin_url', '' );
		$twitter  = get_theme_mod( 'ccs_twitter_url', '' );
		if ( $facebook !== '' || $linkedin !== '' || $twitter !== '' ) :
			$social_links = array();
			if ( $facebook !== '' ) {
				$social_links[] = array(
					'url'   => $facebook,
					'name'  => 'Facebook',
					'label' => __( 'Facebook (opens in new window)', 'ccs-wp-theme' ),
				);
			}
			if ( $linkedin !== '' ) {
				$social_links[] = array(
					'url'   => $linkedin,
					'name'  => 'LinkedIn',
					'label' => __( 'LinkedIn (opens in new window)', 'ccs-wp-theme' ),
				);
			}
			if ( $twitter !== '' ) {
				$social_links[] = array(
					'url'   => $twitter,
					'name'  => 'Twitter',
					'label' => __( 'Twitter (opens in new window)', 'ccs-wp-theme' ),
				);
			}
			?>
			<nav class="site-footer__social" aria-label="<?php esc_attr_e( 'Social media', 'ccs-wp-theme' ); ?>">
				<ul class="site-footer__social-list">
					<?php foreach ( $social_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $link['label'] ); ?>"><?php echo esc_html( $link['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
		<p class="site-footer__copy">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
