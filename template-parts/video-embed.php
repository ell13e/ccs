<?php
/**
 * Click-to-load YouTube embed (facade pattern).
 *
 * Renders a static poster image + play button; the real iframe (youtube-nocookie.com,
 * so no tracking cookie is set until someone actually chooses to watch) is only
 * swapped in on click. Keeps every page that uses this from paying YouTube's ~1MB
 * of embed JS on load for a video most visitors never press play on.
 *
 * Usage: get_template_part( 'template-parts/video-embed', null, array(
 *     'video_id' => '7RJ-pdNh65o',
 *     'title'    => 'Our story, from our registered manager Victoria Walker',
 * ) );
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$video_id = isset( $args['video_id'] ) ? sanitize_text_field( $args['video_id'] ) : '';
if ( $video_id === '' ) {
	return;
}
$video_title = isset( $args['title'] ) ? $args['title'] : __( 'Play video', 'ccs-wp-theme' );
$poster      = 'https://i.ytimg.com/vi/' . rawurlencode( $video_id ) . '/maxresdefault.jpg';
?>
<div class="video-embed" data-video-id="<?php echo esc_attr( $video_id ); ?>" data-video-title="<?php echo esc_attr( $video_title ); ?>">
	<button type="button" class="video-embed__trigger" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title */ __( 'Play: %s', 'ccs-wp-theme' ), $video_title ) ); ?>">
		<img src="<?php echo esc_url( $poster ); ?>" alt="" class="video-embed__poster" loading="lazy" decoding="async">
		<span class="video-embed__play" aria-hidden="true"></span>
	</button>
</div>
