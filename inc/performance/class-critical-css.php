<?php
/**
 * Critical CSS: inline above-the-fold styles and defer the rest.
 *
 * Outputs assets/css/critical.css inline in <head> for fast first paint.
 * Defers main stylesheets with media="print" and onload="this.media='all'",
 * with <noscript> fallback for users without JavaScript.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Critical_CSS
 */
class CCS_Critical_CSS {

	/**
	 * Relative path to the critical CSS file (from theme root).
	 *
	 * @var string
	 */
	const CRITICAL_CSS_PATH = 'assets/css/critical.css';

	/**
	 * Style handles to load as deferred (non-blocking).
	 *
	 * @var string[]
	 */
	private $deferred_handles = array(
		'ccs-design-system',
		'ccs-components',
		'ccs-header',
		'ccs-theme-style',
		'ccs-homepage',
		'ccs-service-page',
		'ccs-location-page',
		'ccs-contact-page',
	);

	/**
	 * Hook into wp_head and style_loader_tag.
	 */
	public function __construct() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_head', array( $this, 'inline_critical_css' ), 1 );
		add_filter( 'style_loader_tag', array( $this, 'defer_stylesheet_tag' ), 10, 4 );
	}

	/**
	 * Output critical CSS inline in <head>.
	 */
	public function inline_critical_css() {
		$path = THEME_DIR . '/' . self::CRITICAL_CSS_PATH;

		if ( ! is_readable( $path ) ) {
			return;
		}

		$css = file_get_contents( $path );
		if ( $css === false || $css === '' ) {
			return;
		}

		$css = trim( $css );
		$css = wp_strip_all_tags( $css );
		// Prevent breaking out of the style tag if file contained literal </style>.
		$css = str_replace( '</style>', '<\/style>', $css );

		if ( $css === '' ) {
			return;
		}

		printf(
			"<style id=\"ccs-critical-css\">\n%s\n</style>\n",
			$css
		);
	}

	/**
	 * Defer non-critical stylesheets: media="print" + onload, with noscript fallback.
	 *
	 * @param string $html  The link tag for the enqueued style.
	 * @param string $handle The style's registered handle.
	 * @param string $href  The stylesheet's source URL.
	 * @param string $media The stylesheet's media attribute.
	 * @return string Modified link tag (and optional noscript).
	 */
	public function defer_stylesheet_tag( $html, $handle, $href, $media ) {
		if ( ! in_array( $handle, $this->deferred_handles, true ) ) {
			return $html;
		}

		// Replace media="all" with media="print" so the browser doesn't block rendering.
		$deferred = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
		$deferred = str_replace( 'media="all"', 'media="print" onload="this.media=\'all\'"', $deferred );

		// Add noscript fallback so styles load when JS is disabled.
		$noscript = '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '" media="all" /></noscript>';

		return $deferred . $noscript;
	}
}
