<?php
/**
 * Responsive images for photographs bundled with the theme.
 *
 * Media Library images get a srcset from WordPress; files shipped in
 * assets/images/ get none. This reads pre-made width variants that sit beside
 * the original and prints them as srcset.
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
		// The name must be this photo's name, a width and nothing else. "client-pottery" was picking up "client-pottery-wheel-300w", another photo's smaller copies (Nov 2026).
		if ( preg_match( '/^' . preg_quote( $info['filename'], '/' ) . '-(\d+)w\.(?:webp|jpe?g|png|avif)$/i', basename( $file ), $m ) ) {
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

	// The photo sheet (assets/images/media-metadata.csv) has the last word on alt text, so descriptions can be reviewed and edited in one place.
	$sheet_alt = ccs_photo_sheet_value( $rel, 'alt_text' );
	if ( '' !== $sheet_alt ) {
		$args['alt'] = $sheet_alt;
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

/**
 * The photo chosen for a service that has no featured image of its own.
 *
 * Client, Nov 2026: "make sure we arent repeating photos", then picked these
 * from her Envato Elements subscription (no uniforms). Models, not CCS
 * clients or staff, so the alt text describes the scene and names no one.
 * The files are licensed for the site and kept out of the public repository
 * (.gitignore), so a missing file returns null and the caller falls back.
 * Used by the service page's opening and by every service card, so a card
 * and the page it leads to show the same picture.
 *
 * @param string $slug Service slug.
 * @return array|null File (relative to assets/images), alt text, object-position.
 */
function ccs_service_chosen_photo( $slug ) {
	$chosen = array(
		'companionship-care' => array( 'stock/tea-outside.webp', __( 'An older woman smiling as tea is poured at a garden table', 'ccs-wp-theme' ), '30% 40%' ),
		'elderly-care'       => array( 'stock/stroll-in-the-park.webp', __( 'An older woman with a walker and a younger woman walking together in a park', 'ccs-wp-theme' ), '50% 50%' ),
		'dementia-care'      => array( 'stock/baking-together.webp', __( 'Two women laughing as they fill cake cases at a kitchen worktop', 'ccs-wp-theme' ), '50% 8%' ),
		'long-term-care'     => array( 'stock/wheelchair-and-chess.webp', __( 'A man leaning in to help an older gentleman in a wheelchair beside an outdoor chess table', 'ccs-wp-theme' ), '50% 60%' ),
		'cancer-care'        => array( 'stock/woman-with-her-dog.webp', __( 'A woman in a headscarf on her sofa, stroking a black dog resting on her lap', 'ccs-wp-theme' ), '35% 40%' ),
		'stroke-care'        => array( 'stock/mother-and-daughter-walk.webp', __( 'A younger woman with her hands on the shoulders of a smiling older woman on a woodland path', 'ccs-wp-theme' ), '50% 35%' ),
		'nursing-care'       => array( 'stock/help-with-tablets.webp', __( 'A woman sitting beside an older woman in an armchair as she takes her tablets with a glass of water', 'ccs-wp-theme' ), '50% 10%' ),
		'personal-care'      => array( 'stock/at-the-dressing-table.webp', __( 'A woman leaning in beside an older woman at her dressing table, both smiling', 'ccs-wp-theme' ), '35% 40%' ),
		'continuous-care'    => array( 'stock/guitar-at-home.webp', __( 'An older man laughing on his sofa as a younger man plays the guitar', 'ccs-wp-theme' ), '50% 40%' ),
		'palliative-care'    => array( 'stock/bedside-visit.webp', __( 'An older woman resting in bed with someone sitting close beside her', 'ccs-wp-theme' ), '50% 40%' ),
		'spinal-injury-care' => array( 'stock/laughing-on-the-patio.webp', __( 'A man in a wheelchair laughing with a woman on a sunny patio', 'ccs-wp-theme' ), '50% 40%' ),
		'cerebral-palsy-care' => array( 'stock/laughing-in-the-park.webp', __( 'A man in a wheelchair laughing as the man with him points something out on a path through a park', 'ccs-wp-theme' ), '35% 35%' ),
		'overnight-care'     => array( 'stock/settling-in-for-the-night.webp', __( 'A woman straightening the pillows behind an older woman sitting up in bed', 'ccs-wp-theme' ), '50% 28%' ),
		'disability-care'    => array( 'stock/watering-the-plants.webp', __( 'A man in a wheelchair watering a houseplant on a shelf at home', 'ccs-wp-theme' ), '60% 40%' ),
		'younger-adults-care' => array( 'stock/baking-at-the-table.webp', __( 'A woman and a young man laughing as they bake at a kitchen table', 'ccs-wp-theme' ), '55% 40%' ),
		// The client's pick; which page it belongs on was a guess (Nov 2026), so check with her.
		'brain-injury-care'  => array( 'stock/unpacking-the-shopping.webp', __( 'A woman unpacking shopping at the kitchen table with an older man in a wheelchair', 'ccs-wp-theme' ), '40% 40%' ),
		'parkinsons-care'    => array( 'stock/walking-frame-in-the-park.webp', __( 'A young woman walking beside an older woman who is using a walking frame in a park', 'ccs-wp-theme' ), '50% 35%' ),
	);
	if ( ! isset( $chosen[ $slug ] ) || ! file_exists( get_template_directory() . '/assets/images/' . $chosen[ $slug ][0] ) ) {
		return null;
	}
	return $chosen[ $slug ];
}

/**
 * Whether a service has a photograph for its card: a featured image or a chosen photo.
 *
 * @param WP_Post $service Service post.
 * @return bool
 */
function ccs_service_has_photo( $service ) {
	return has_post_thumbnail( $service ) || null !== ccs_service_chosen_photo( $service->post_name );
}

/**
 * The chosen photo as a card image (decorative: the card's title is the link).
 *
 * @param string $slug  Service slug.
 * @param string $sizes The sizes attribute for the card's slot.
 * @return string Escaped img tag, or an empty string.
 */
function ccs_service_card_photo( $slug, $sizes ) {
	$photo = ccs_service_chosen_photo( $slug );
	if ( ! $photo ) {
		return '';
	}
	return sprintf(
		'<img src="%1$s" alt="" loading="lazy" decoding="async"%2$s style="object-position:%3$s">',
		esc_url( get_template_directory_uri() . '/assets/images/' . $photo[0] ),
		ccs_theme_image_srcset( $photo[0], $sizes ),
		esc_attr( $photo[2] )
	);
}

/**
 * One value from the photo sheet, assets/images/media-metadata.csv (Nov
 * 2026, after the sheet used on the Restwell site): filename, alt_text,
 * title, caption, description. bin/build-media-csv.py writes the sheet from
 * the templates and keeps anything edited in it.
 *
 * @param string $rel    Path relative to assets/images/.
 * @param string $column Column name.
 * @return string The value, or '' when the photo or column is not in the sheet.
 */
function ccs_photo_sheet_value( $rel, $column ) {
	static $sheet = null;
	if ( null === $sheet ) {
		$sheet = array();
		$file  = get_template_directory() . '/assets/images/media-metadata.csv';
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen -- a small local file shipped with the theme.
		$handle = is_readable( $file ) ? fopen( $file, 'r' ) : false;
		if ( $handle ) {
			$header = fgetcsv( $handle );
			while ( is_array( $header ) && false !== ( $row = fgetcsv( $handle ) ) ) {
				if ( count( $row ) === count( $header ) ) {
					$row                       = array_combine( $header, $row );
					$sheet[ $row['filename'] ] = $row;
				}
			}
			fclose( $handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		}
	}
	return isset( $sheet[ $rel ][ $column ] ) ? trim( (string) $sheet[ $rel ][ $column ] ) : '';
}
