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
