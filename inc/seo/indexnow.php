<?php
/**
 * IndexNow (Nov 2026).
 *
 * When a page or article is published, updated or taken down, tell the
 * search engines that use IndexNow (Bing, Yandex, Seznam, Naver, Yep) at once
 * instead of waiting for them to crawl. Google does not take part; it reads
 * the sitemap as before.
 *
 * - The key is made once and kept in an option. The search engines check it
 *   at /{key}.txt, which this file serves.
 * - Only runs on the live site: not on local or staging hosts, and not when
 *   an SEO plugin is on (Yoast and Rank Math send their own).
 * - Addresses are collected during the request and sent in one batch at the
 *   end, without making the editor wait. The same address is not sent twice
 *   within ten minutes.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CCS_INDEXNOW_KEY_OPTION = 'ccs_indexnow_key';
const CCS_INDEXNOW_ENDPOINT   = 'https://api.indexnow.org/indexnow';

/**
 * The site's IndexNow key, made on first use.
 *
 * @return string
 */
function ccs_indexnow_key() {
	$key = get_option( CCS_INDEXNOW_KEY_OPTION, '' );
	if ( ! is_string( $key ) || ! preg_match( '/^[a-f0-9]{32}$/', $key ) ) {
		$key = md5( wp_generate_password( 64, true, true ) );
		update_option( CCS_INDEXNOW_KEY_OPTION, $key, false );
	}
	return $key;
}

/**
 * Whether IndexNow should send anything from this site.
 *
 * @return bool
 */
function ccs_indexnow_enabled() {
	$enabled = true;
	if ( function_exists( 'ccs_seo_plugin_active' ) && ccs_seo_plugin_active() ) {
		$enabled = false;
	}
	if ( ! get_option( 'blog_public' ) ) {
		$enabled = false;
	}
	if ( function_exists( 'wp_get_environment_type' ) && 'production' !== wp_get_environment_type() ) {
		$enabled = false;
	}
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	if ( '' === $host || 'localhost' === $host || filter_var( $host, FILTER_VALIDATE_IP ) || preg_match( '/\.(localhost|test|local|invalid|example)$/', $host ) || preg_match( '/(^|\.)(staging|stage|dev|test|qa|preview)\./', $host ) ) {
		$enabled = false;
	}
	/**
	 * Filters whether IndexNow pings are sent.
	 *
	 * @param bool $enabled True on the live site.
	 */
	return (bool) apply_filters( 'ccs_indexnow_enabled', $enabled );
}

/**
 * Serves the key file at /{key}.txt so search engines can confirm the site owns the key.
 */
function ccs_indexnow_serve_key() {
	if ( ! ccs_indexnow_enabled() ) {
		return;
	}
	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$key  = ccs_indexnow_key();
	if ( $path !== (string) wp_parse_url( home_url( '/' . $key . '.txt' ), PHP_URL_PATH ) ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo esc_html( $key );
	exit;
}
add_action( 'init', 'ccs_indexnow_serve_key', 1 );

/**
 * Addresses waiting to be sent at the end of this request.
 *
 * @param string|null $url Address to add, or null to read the list.
 * @return string[]
 */
function ccs_indexnow_queue( $url = null ) {
	static $queue = array();
	if ( null !== $url ) {
		$queue[ $url ] = true;
	}
	return array_keys( $queue );
}

/**
 * Queues a post's address when it is published, updated while published, or taken down.
 *
 * @param string  $new_status New status.
 * @param string  $old_status Old status.
 * @param WP_Post $post       The post.
 */
function ccs_indexnow_on_status( $new_status, $old_status, $post ) {
	if ( 'publish' !== $new_status && 'publish' !== $old_status ) {
		return;
	}
	if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
		return;
	}
	if ( ! is_post_type_viewable( $post->post_type ) || ! ccs_indexnow_enabled() ) {
		return;
	}
	$url = get_permalink( $post );
	if ( ! $url || false !== strpos( $url, '?' ) ) {
		return;
	}
	if ( 'publish' !== $new_status && 'publish' === $old_status ) {
		// Taken down: get_permalink() may already give the draft address, so use the last live one if saved.
		$live = get_post_meta( $post->ID, '_ccs_indexnow_live_url', true );
		$url  = $live ? $live : $url;
	} else {
		update_post_meta( $post->ID, '_ccs_indexnow_live_url', esc_url_raw( $url ) );
	}
	ccs_indexnow_queue( $url );
}
add_action( 'transition_post_status', 'ccs_indexnow_on_status', 10, 3 );

/**
 * Sends the queued addresses in one batch at the end of the request.
 */
function ccs_indexnow_send() {
	$urls = ccs_indexnow_queue();
	if ( ! $urls || ! ccs_indexnow_enabled() ) {
		return;
	}
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	$send = array();
	foreach ( $urls as $url ) {
		$seen = 'ccs_indexnow_' . md5( $url );
		if ( get_transient( $seen ) || wp_parse_url( $url, PHP_URL_HOST ) !== $host ) {
			continue;
		}
		set_transient( $seen, 1, 10 * MINUTE_IN_SECONDS );
		$send[] = $url;
	}
	if ( ! $send ) {
		return;
	}
	$key = ccs_indexnow_key();
	wp_remote_post(
		CCS_INDEXNOW_ENDPOINT,
		array(
			'blocking' => false,
			'timeout'  => 2,
			'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'     => wp_json_encode(
				array(
					'host'        => $host,
					'key'         => $key,
					'keyLocation' => home_url( '/' . $key . '.txt' ),
					'urlList'     => array_slice( $send, 0, 10000 ),
				)
			),
		)
	);
}
add_action( 'shutdown', 'ccs_indexnow_send' );
