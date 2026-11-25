<?php
/**
 * Article helpers (Nov 2026).
 *
 * This file used to create four question articles written for the SEO plan
 * (home care or a care home, NHS Continuing Healthcare, coming home from
 * hospital, worked cost examples). The client asked on 25 Nov 2026 for the
 * old website's articles "instead of the ones we've made up", so they are no
 * longer created; ccs_retire_written_articles_2026_11() in content-updates.php
 * moves them to the bin on sites that already have them. What remains is the
 * photo import used by the articles that are created (see
 * inc/article-keelie-2026-11.php).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Import a theme photo into the Media Library once (found again by its
 * theme path, never duplicated).
 *
 * @param string $rel   Path inside the theme.
 * @param string $title Attachment title.
 * @return int Attachment ID, or 0.
 */
function ccs_articles_import_photo( $rel, $title ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_ccs_source_theme_path', 'meta_value' => $rel, 'fields' => 'ids' ) );
	if ( $existing ) {
		return (int) $existing[0];
	}
	$source = get_template_directory() . '/' . $rel;
	$upload = wp_upload_dir();
	if ( ! file_exists( $source ) || ! empty( $upload['error'] ) ) {
		return 0;
	}
	$name = wp_unique_filename( $upload['path'], wp_basename( $source ) );
	$dest = trailingslashit( $upload['path'] ) . $name;
	if ( ! copy( $source, $dest ) ) {
		return 0;
	}
	$type = wp_check_filetype( $name, null );
	$att  = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => $title, 'post_status' => 'inherit' ), $dest );
	if ( is_wp_error( $att ) || ! $att ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $dest ) );
	update_post_meta( $att, '_ccs_source_theme_path', $rel );
	return (int) $att;
}
