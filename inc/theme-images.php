<?php
/**
 * Responsive images for photographs bundled with the theme.
 *
 * Media Library images get a srcset from WordPress. Files shipped in
 * assets/images/ got none, so every screen downloaded the full-size original:
 * the homepage hero sent 411KB to a phone, and a 1600px photo filled a 205px
 * collage tile. This reads pre-made width variants that sit beside the
 * original and prints them as srcset.
 *
 * Variant naming: `{original name}-{width}w.{ext}` in the same folder, e.g.
 * `home-hero-family.jpg` + `home-hero-family-800w.webp`. Variants are resized
 * copies only (never tone or colour changes: client rule). To add one:
 *
 *   magick photo.jpg -resize 800x -quality 80 photo-800w.webp
 *
 * A file with no variants simply prints no srcset, so nothing breaks when a
 * photo is swapped before its variants are made.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Width variants available for a bundled image.
 *
 * @param string $rel Path relative to assets/images/, e.g. 'site-photos/foo.webp'.
 * @return array<int,string> Map of width in px => URL, ascending. Empty when none exist.
 */
function ccs_theme_image_variants( $rel ) {
	static $cache = array();

	if ( isset( $cache[ $rel ] ) ) {
		return $cache[ $rel ];
	}

	$dir      = get_template_directory() . '/assets/images/';
	$uri      = get_template_directory_uri() . '/assets/images/';
	$info     = pathinfo( $rel );
	$rel_dir  = ( isset( $info['dirname'] ) && '.' !== $info['dirname'] ) ? $info['dirname'] . '/' : '';
	$variants = array();

	// glob() treats [ ] * ? as patterns; none of our filenames contain them, but
	// escape anyway so a future upload with brackets cannot match the wrong file.
	$pattern = $dir . $rel_dir . addcslashes( $info['filename'], '[]*?' ) . '-*w.*';
	$files   = glob( $pattern );

	foreach ( is_array( $files ) ? $files : array() as $file ) {
		if ( preg_match( '/-(\d+)w\.(?:webp|jpe?g|png|avif)$/i', $file, $m ) ) {
			// rawurlencode each segment: the CQC artwork has spaces in its name.
			$variants[ (int) $m[1] ] = $uri . $rel_dir . rawurlencode( basename( $file ) );
		}
	}

	ksort( $variants );
	$cache[ $rel ] = $variants;

	return $variants;
}

/**
 * The srcset and sizes attributes for a bundled image, ready to print inside an <img>.
 *
 * The original stays in `src` as the fallback. It is only added to the srcset
 * when no variant is at least as wide, so a browser never has to choose the
 * heavier original over a WebP copy of the same width.
 *
 * @param string $rel   Path relative to assets/images/.
 * @param string $sizes The sizes attribute: the slot's rendered width per breakpoint.
 * @return string Escaped ` srcset="…" sizes="…"`, or '' when the image has no variants.
 */
function ccs_theme_image_srcset( $rel, $sizes ) {
	$variants = ccs_theme_image_variants( $rel );

	if ( empty( $variants ) ) {
		return '';
	}

	$path = get_template_directory() . '/assets/images/' . $rel;
	$size = file_exists( $path ) ? wp_getimagesize( $path ) : false;

	if ( is_array( $size ) && ! empty( $size[0] ) && (int) $size[0] > max( array_keys( $variants ) ) ) {
		$segments                     = array_map( 'rawurlencode', explode( '/', $rel ) );
		$variants[ (int) $size[0] ] = get_template_directory_uri() . '/assets/images/' . implode( '/', $segments );
		ksort( $variants );
	}

	$parts = array();
	foreach ( $variants as $width => $url ) {
		$parts[] = esc_url( $url ) . ' ' . (int) $width . 'w';
	}

	return ' srcset="' . implode( ', ', $parts ) . '" sizes="' . esc_attr( $sizes ) . '"';
}

/**
 * One bundled photograph in the shared section frame (.ccs-photo).
 *
 * Added Nov 2026 for the inner pages: between the hero and the closing band
 * most of them had no imagery at all, so every page read as a heading beside
 * paragraphs. One real photo per section, in one frame (CCS/CLAUDE.md, "Calm
 * first"); the browser crops it, nothing is filtered (rule 15).
 *
 * Prints nothing when the file is missing, so a removed photo leaves a text
 * section, never an empty frame.
 *
 * @param string $rel  Path relative to assets/images/.
 * @param array  $args alt (string, '' when decorative), sizes (string),
 *                     class (extra classes), position (CSS object-position).
 * @return void
 */
function ccs_section_photo( $rel, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'alt'      => '',
			'sizes'    => '(min-width: 900px) 480px, 92vw',
			'class'    => '',
			'position' => '',
		)
	);

	$path = get_template_directory() . '/assets/images/' . $rel;
	if ( ! file_exists( $path ) ) {
		return;
	}

	$size     = wp_getimagesize( $path );
	$segments = array_map( 'rawurlencode', explode( '/', $rel ) );
	$src      = get_template_directory_uri() . '/assets/images/' . implode( '/', $segments );
	?>
	<figure class="ccs-photo<?php echo '' !== $args['class'] ? ' ' . esc_attr( $args['class'] ) : ''; ?>">
		<img
			src="<?php echo esc_url( $src ); ?>"
			alt="<?php echo esc_attr( $args['alt'] ); ?>"
			<?php if ( is_array( $size ) ) : ?>
				width="<?php echo (int) $size[0]; ?>"
				height="<?php echo (int) $size[1]; ?>"
			<?php endif; ?>
			<?php if ( '' !== $args['position'] ) : ?>
				style="object-position: <?php echo esc_attr( $args['position'] ); ?>;"
			<?php endif; ?>
			<?php echo ccs_theme_image_srcset( $rel, $args['sizes'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
			loading="lazy"
			decoding="async"
		>
	</figure>
	<?php
}
