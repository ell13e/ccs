<?php
/**
 * One icon set for the whole theme.
 *
 * The ElderlyCare X template the client benchmarked against carries a single
 * icon language through every page: the same stroke weight, the same rounded
 * joins, always set in the same round tinted badge. That consistency is a large
 * part of why it reads as one designed system. Before this file the theme had
 * three unrelated line icons in one homepage section and nothing anywhere else.
 *
 * Drawn on a 24px grid, 1.7px stroke, round caps and joins, currentColor, so an
 * icon inherits whatever colour its badge or link sets. All are decorative:
 * every use sits next to text that says the same thing, so the SVG is
 * aria-hidden and needs no title.
 *
 * Usage: echo ccs_icon( 'home' );  // already-safe markup
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG markup for a named icon.
 *
 * @param string $name  Icon key (see $paths below).
 * @param string $class Extra class(es) for the <svg>.
 * @return string SVG markup, or '' for an unknown name.
 */
function ccs_icon( $name, $class = '' ) {
	static $paths = array(
		// Services.
		'home'     => '<path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9v11h13V9"/><path d="M12 17.2s-3-1.8-3-3.8a1.6 1.6 0 0 1 3-.8 1.6 1.6 0 0 1 3 .8c0 2-3 3.8-3 3.8Z"/>',
		'cup'      => '<path d="M4.5 9.5h11v4.5a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5V9.5Z"/><path d="M15.5 11h1.5a2.5 2.5 0 0 1 0 5h-1.7"/><path d="M8 3.5c0 1 1 1.4 1 2.4S8 7.3 8 7.3"/><path d="M11.5 3.5c0 1 1 1.4 1 2.4s-1 1.4-1 1.4"/>',
		'pulse'    => '<path d="M19.4 12.9 12 20.2l-7.4-7.3a4.6 4.6 0 0 1 7.4-5.4 4.6 4.6 0 0 1 7.4 5.4Z"/><path d="M3.5 12.5h4.2l1.6-2.6 2.4 4.8 1.6-2.2h7.2"/>',
		// Proof points.
		'shield'   => '<path d="M12 3.2 4.8 6v5.6c0 4.4 3 8 7.2 9.2 4.2-1.2 7.2-4.8 7.2-9.2V6L12 3.2Z"/><path d="m8.8 12.1 2.2 2.2 4.3-4.4"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'people'   => '<circle cx="9" cy="8.5" r="3.2"/><path d="M3.5 20c0-3.2 2.5-5.5 5.5-5.5s5.5 2.3 5.5 5.5"/><circle cx="17.2" cy="10" r="2.4"/><path d="M16 15.2c2.6.2 4.5 2.3 4.5 4.8"/>',
		'moon'     => '<path d="M19.5 14.6A8 8 0 1 1 9.4 4.5a6.4 6.4 0 0 0 10.1 10.1Z"/>',
		// Next steps / internal links.
		'question' => '<path d="M20.5 12.2c0 4-3.8 7.2-8.5 7.2a10 10 0 0 1-2.7-.36L4.5 20.5l1.2-3.5A6.7 6.7 0 0 1 3.5 12.2c0-4 3.8-7.2 8.5-7.2s8.5 3.2 8.5 7.2Z"/><path d="M10.1 10.1a2 2 0 1 1 2.6 1.9c-.5.2-.8.6-.8 1.1v.4"/><path d="M12 15.6h.01"/>',
		'badge'    => '<path d="M9 4.5h6v3H9z"/><rect x="4" y="7.5" width="16" height="12" rx="2"/><path d="M8.5 12.5h7"/><path d="M8.5 15.5h4"/>',
		'heart'    => '<path d="M19.4 12.9 12 20.2l-7.4-7.3a4.6 4.6 0 0 1 7.4-5.4 4.6 4.6 0 0 1 7.4 5.4Z"/>',
		// Contact.
		'phone'    => '<path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24c1.1.37 2.3.57 3.6.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.6 21 3 13.4 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.3.2 2.5.57 3.6a1 1 0 0 1-.25 1l-2.2 2.2Z"/>',
		'mail'     => '<rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4 7 8 6 8-6"/>',
		'pin'      => '<path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.4"/>',
		'check'    => '<path d="m5 12.5 4.2 4.2L19 7"/>',
		// Switching provider: two arrows passing each other.
		'switch'   => '<path d="M4 8h14"/><path d="m14.5 4.5 3.5 3.5-3.5 3.5"/><path d="M20 16H6"/><path d="m9.5 12.5-3.5 3.5 3.5 3.5"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$classes = trim( 'ccs-icon ' . $class );

	return '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}
