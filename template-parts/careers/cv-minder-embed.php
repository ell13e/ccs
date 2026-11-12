<?php
/**
 * CV Minder job portal embed (Current Vacancies page).
 * When Customizer "Use CV Minder" is on: output iframe. When off: fallback CTA.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$use_cvm = get_theme_mod( 'ccs_cvm_use', true );

if ( ! $use_cvm ) {
	?>
	<div class="cvm-fallback container container--lg">
		<p><?php esc_html_e( 'View our current vacancies or contact us to find out about opportunities.', 'ccs-wp-theme' ); ?></p>
		<p><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact-us' ) ) ?: home_url( '/contact-us/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact us', 'ccs-wp-theme' ); ?></a></p>
	</div>
	<?php
	return;
}

$iframe_src = get_theme_mod( 'ccs_cvm_iframe_src', 'https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713' );
$iframe_src = $iframe_src ? $iframe_src : 'https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713';
?>

<div class="cvm-embed" id="cvm_content">
	<iframe id="cvm_jobframe" name="cvm_jobframe" src="<?php echo esc_url( $iframe_src ); ?>" allowtransparency="true" frameborder="0" marginwidth="0" marginheight="0" scrolling="auto" title="<?php esc_attr_e( 'Jobs posted by CV Minder', 'ccs-wp-theme' ); ?>"></iframe>
</div>
<?php
/*
 * Auto-fit, if CV Minder supports it. A page cannot read the height of another
 * site's content in a frame, so the frame has a fixed height and scrolls
 * (current-vacancies.css). If the portal posts its height to the parent page,
 * as many embeds do, the frame takes that height instead and the inner scroll
 * disappears. Accepts messages from cvminder.com only, in the common forms: a
 * number, {height: n}, or the iFrameResizer "[iFrameSizer]id:height:..." string.
 * If nothing is ever sent, nothing changes.
 */
?>
<script>
(function () {
	var wrap = document.getElementById('cvm_content');
	var frame = document.getElementById('cvm_jobframe');
	if (!wrap || !frame) return;
	window.addEventListener('message', function (e) {
		if (!/^https:\/\/([a-z0-9-]+\.)*cvminder\.com$/i.test(e.origin)) return;
		if (e.source !== frame.contentWindow) return;
		var d = e.data, h = 0;
		if (typeof d === 'number') h = d;
		else if (d && typeof d === 'object' && d.height) h = parseFloat(d.height);
		else if (typeof d === 'string') {
			var m = d.match(/^\[iFrameSizer\][^:]*:(\d+(?:\.\d+)?)/) || d.match(/^\s*(?:height[:=])?\s*(\d{3,5})\s*$/i);
			if (m) h = parseFloat(m[1]);
		}
		if (h > 200 && h < 20000) {
			wrap.style.height = Math.ceil(h) + 'px';
			wrap.classList.add('cvm-embed--fitted');
		}
	});
})();
</script>
