<?php
/**
 * When a browser darkens the site for the reader (Nov 2026).
 *
 * The site has one, light, design. Some browsers darken light sites for
 * readers who use dark mode: Chrome's Auto Dark Theme (on Android, and on
 * desktop through a setting), and extensions such as Dark Reader. They
 * recolour what the stylesheets paint, but leave images alone, so the plum
 * doodles, icons and logo stayed plum on a near-black page and almost
 * disappeared, and the handwriting's background-coloured edge turned light
 * and made the letters look bold (client, Nov 2026).
 *
 * This marks the page when that's happening, so assets/css/design-system.css
 * and inner-pages.css can adjust only then. A prefers-color-scheme: dark rule
 * can't be used: it also matches phones that don't darken anything (an
 * iPhone in dark mode shows the normal light site), and would change the
 * light design there (Chrome's "Auto Dark Theme" article says the same).
 *
 * Detection is Chrome's documented test: with color-scheme: light, the
 * system "canvas" colour is white unless Chrome is darkening the page. Dark
 * Reader marks the page itself (data-darkreader-scheme="dark" on <html>, from
 * its source), so the styles match that too. Runs at the top of <head>, so
 * the page never shows the wrong version first.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add .ccs-auto-dark to <html> when the browser is darkening the page.
 */
function ccs_auto_dark_marker() {
	?>
	<script>(function(){try{var d=document.documentElement,e=document.createElement('div');e.style.cssText='display:none;background-color:canvas;color-scheme:light';d.appendChild(e);var dark=getComputedStyle(e).backgroundColor!=='rgb(255, 255, 255)';d.removeChild(e);if(dark){d.classList.add('ccs-auto-dark');}}catch(x){}})();</script>
	<?php
}
add_action( 'wp_head', 'ccs_auto_dark_marker', 1 );
