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
	$find = static function () use ( $rel ) {
		$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_ccs_source_theme_path', 'meta_value' => $rel, 'fields' => 'ids', 'suppress_filters' => true, 'cache_results' => false ) );
		return $existing ? (int) $existing[0] : 0;
	};
	$id = $find();
	if ( $id ) {
		return $id;
	}
	/*
	 * One request imports a photo at a time (Nov 2026): on a fresh site two
	 * requests at once both found no copy and both imported it. The lock is an
	 * atomic insert, as the activation and update locks are; the request that
	 * loses waits briefly, then uses the copy the winner made.
	 */
	global $wpdb;
	$lock = 'ccs_import_lock_' . md5( $rel );
	$take = static function () use ( $wpdb, $lock ) {
		return (bool) $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO `{$wpdb->options}` ( `option_name`, `option_value`, `autoload` ) VALUES ( %s, %s, 'no' )", $lock, (string) time() ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	};
	$won = $take();
	if ( ! $won ) {
		// A lock older than a minute was left by a request that crashed.
		$since = (int) $wpdb->get_var( $wpdb->prepare( "SELECT `option_value` FROM `{$wpdb->options}` WHERE `option_name` = %s", $lock ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( $since && time() - $since > MINUTE_IN_SECONDS ) {
			$wpdb->delete( $wpdb->options, array( 'option_name' => $lock ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$won = $take();
		}
	}
	if ( ! $won ) {
		for ( $i = 0; $i < 20; $i++ ) {
			usleep( 250000 );
			$id = $find();
			if ( $id ) {
				return $id;
			}
		}
		return 0;
	}
	// Another request may have finished between the first look and the lock.
	$id = $find();
	if ( ! $id ) {
		$id = ccs_articles_import_photo_now( $rel, $title );
	}
	$wpdb->delete( $wpdb->options, array( 'option_name' => $lock ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	return $id;
}

/**
 * The import itself (call ccs_articles_import_photo(), which holds the lock).
 *
 * @param string $rel   Path inside the theme.
 * @param string $title Attachment title.
 * @return int Attachment ID, or 0.
 */
function ccs_articles_import_photo_now( $rel, $title ) {
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
