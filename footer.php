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
		<p class="site-footer__copy">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
