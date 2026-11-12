<?php
/**
 * One-time page text updates shipped with the theme.
 *
 * Page text lives in the WordPress database, so copy changes the client has
 * approved would otherwise only exist on the site where they were made. Each
 * update file holds, per page, the old text and the new. An update is applied
 * only where the page still holds exactly the old text: anything the client
 * has edited since is left alone. WordPress keeps a revision of every page it
 * changes, so each can be undone from the editor.
 *
 * Nov 2026: the approved copy rewrites (docs/COPY-REWRITES-FOR-APPROVAL.md)
 * and the em-dash clean-up.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Apply each update file once.
 */
function ccs_apply_content_updates() {
	$updates = array( 'copy-update-2026-11' );
	$done    = (array) get_option( 'ccs_content_updates_done', array() );

	foreach ( $updates as $key ) {
		if ( in_array( $key, $done, true ) ) {
			continue;
		}
		$file = get_template_directory() . '/inc/data/' . $key . '.json';
		$rows = file_exists( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null;
		if ( ! is_array( $rows ) ) {
			continue;
		}

		foreach ( $rows as $row ) {
			// By slug at any level: get_page_by_path() needs the full path, which
			// misses child pages such as Resources > FAQs.
			$found = get_posts( array( 'name' => $row['slug'], 'post_type' => $row['type'], 'post_status' => array( 'publish', 'draft', 'private' ), 'numberposts' => 1, 'suppress_filters' => true ) );
			$post  = $found ? $found[0] : null;
			if ( ! $post instanceof WP_Post ) {
				continue;
			}
			$change = array( 'ID' => $post->ID );
			// Compare on trimmed text with normalised line endings, so a save
			// through the editor that only touched whitespace still matches.
			$norm = static function ( $s ) {
				return trim( str_replace( "\r\n", "\n", (string) $s ) );
			};
			if ( $norm( $post->post_content ) === $norm( $row['old_content'] ) && $row['old_content'] !== $row['new_content'] ) {
				$change['post_content'] = $row['new_content'];
			}
			if ( $norm( $post->post_excerpt ) === $norm( $row['old_excerpt'] ) && $row['old_excerpt'] !== $row['new_excerpt'] ) {
				$change['post_excerpt'] = $row['new_excerpt'];
			}
			if ( count( $change ) > 1 ) {
				wp_update_post( wp_slash( $change ) );
			}
		}

		$done[] = $key;
		update_option( 'ccs_content_updates_done', $done, false );
	}
}
add_action( 'admin_init', 'ccs_apply_content_updates' );

/**
 * One-time featured-image swaps (Nov 2026). Each applies only while the post
 * still uses the old image, so a photo the client has chosen is never replaced.
 *
 * - "Paying for home care in Kent": the autumn-leaves strip (1350x400) cropped
 *   badly into blog cards; it now uses the Easter activity photo, which is
 *   already in the media library.
 */
function ccs_apply_thumbnail_updates() {
	if ( get_option( 'ccs_thumbnail_updates_2026_11' ) ) {
		return;
	}
	$swaps = array(
		array( 'post' => 'paying-for-home-care-in-kent', 'from' => 'client-creative-activity-autumn-leaves', 'to' => 'client-easter-activity' ),
	);
	foreach ( $swaps as $swap ) {
		$found = get_posts( array( 'name' => $swap['post'], 'post_type' => 'post', 'numberposts' => 1, 'suppress_filters' => true ) );
		if ( ! $found ) {
			continue;
		}
		$current = (int) get_post_thumbnail_id( $found[0]->ID );
		if ( ! $current || false === strpos( basename( (string) get_attached_file( $current ) ), $swap['from'] ) ) {
			continue;
		}
		$target = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids' ) );
		foreach ( $target as $att ) {
			if ( 0 === strpos( basename( (string) get_attached_file( $att ) ), $swap['to'] ) ) {
				set_post_thumbnail( $found[0]->ID, $att );
				break;
			}
		}
	}
	update_option( 'ccs_thumbnail_updates_2026_11', 1, false );
}
add_action( 'admin_init', 'ccs_apply_thumbnail_updates' );

/**
 * Current Vacancies hero (Nov 2026). It showed the same summer-party photo as
 * the About hero; it now shows a team member at an apprenticeships fair, which
 * suits a jobs page. Imported once into the Media Library (found again by its
 * theme path, never duplicated) and set only while the page still uses the old
 * image, so a photo the client has chosen is left alone.
 */
function ccs_apply_vacancies_hero_update() {
	if ( get_option( 'ccs_vacancies_hero_2026_11' ) ) {
		return;
	}
	update_option( 'ccs_vacancies_hero_2026_11', 1, false );

	$page = get_page_by_path( 'careers/current-vacancies' );
	if ( ! $page instanceof WP_Post ) {
		return;
	}
	$current = (int) get_post_thumbnail_id( $page->ID );
	if ( $current && false === strpos( basename( (string) get_attached_file( $current ) ), 'home-hero-desktop' ) ) {
		return;
	}

	$rel      = 'assets/images/site-photos/careers-apprenticeships-fair.webp';
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_ccs_source_theme_path', 'meta_value' => $rel, 'fields' => 'ids' ) );
	$att      = $existing ? (int) $existing[0] : 0;

	if ( ! $att ) {
		$source = get_template_directory() . '/' . $rel;
		$upload = wp_upload_dir();
		if ( ! file_exists( $source ) || ! empty( $upload['error'] ) ) {
			return;
		}
		$name = wp_unique_filename( $upload['path'], wp_basename( $source ) );
		$dest = trailingslashit( $upload['path'] ) . $name;
		if ( ! copy( $source, $dest ) ) {
			return;
		}
		$att = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/webp',
				'post_title'     => __( 'Careers: apprenticeships fair', 'ccs-wp-theme' ),
				'post_status'    => 'inherit',
			),
			$dest
		);
		if ( is_wp_error( $att ) || ! $att ) {
			return;
		}
		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $dest ) );
		update_post_meta( $att, '_ccs_source_theme_path', $rel );
	}

	set_post_thumbnail( $page->ID, $att );
}
add_action( 'admin_init', 'ccs_apply_vacancies_hero_update' );

/**
 * Costs extras as "charge | price" rows (Nov 2026, costs section redesign).
 *
 * A site that saved the extras list in the Customizer while it still held the
 * old sentence-per-line default would keep showing sentences in the new table.
 * Where the saved value is exactly that old default, drop it so the new
 * default rows apply. Anything the client wrote themselves is left alone.
 */
function ccs_apply_cost_extras_update() {
	if ( get_option( 'ccs_cost_extras_rows_done' ) ) {
		return;
	}

	$old_default = implode( "\n", array(
		'A one-off £30 fee when we set up your care package',
		'Evenings, weekends and complex care are charged at higher rates',
		'Bank holidays are charged at double, and Christmas Day, Boxing Day and New Year’s Day at treble',
		'Escort visits add mileage at 50p per mile, plus any parking',
	) );

	$saved = get_theme_mod( 'ccs_cost_extras', null );
	if ( is_string( $saved ) && trim( str_replace( "\r", '', $saved ) ) === $old_default ) {
		remove_theme_mod( 'ccs_cost_extras' );
	}

	update_option( 'ccs_cost_extras_rows_done', 1, false );
}
add_action( 'admin_init', 'ccs_apply_cost_extras_update' );
