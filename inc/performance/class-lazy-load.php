<?php
/**
 * Native lazy loading for images.
 *
 * Adds loading="lazy" to post content images, post thumbnails, and
 * attachment images. Skips the first above-the-fold image, admin, and feeds.
 * Native only: the real src stays in place (no placeholder, no JS fallback).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Lazy_Load
 */
class CCS_Lazy_Load {

	/**
	 * Count of post thumbnails output this request (first is above fold).
	 *
	 * @var int
	 */
	private $thumbnail_count = 0;

	/**
	 * Hook into WordPress filters.
	 */
	public function __construct() {
		if ( is_admin() || is_feed() ) {
			return;
		}

		add_filter( 'the_content', array( $this, 'filter_content_images' ), 20 );
		add_filter( 'post_thumbnail_html', array( $this, 'filter_post_thumbnail_html' ), 10, 5 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_attachment_image_attributes' ), 10, 3 );
		// No fallback script any more: see lazy_img_markup().
	}

	/**
	 * Add loading="lazy" to images in post content, except the first (above fold).
	 *
	 * @param string $content Post content.
	 * @return string Filtered content.
	 */
	public function filter_content_images( $content ) {
		if ( empty( $content ) || strpos( $content, '<img' ) === false ) {
			return $content;
		}

		$count = 0;

		$content = preg_replace_callback(
			'/<img\s([^>]*?)>/is',
			function ( $matches ) use ( &$count ) {
				$img = $matches[0];
				$count++;

				// Skip first image (above the fold).
				if ( $count === 1 ) {
					return $this->ensure_loading_attribute( $img, 'eager' );
				}

				// Already has loading attribute: leave as-is (respect explicit eager/lazy).
				if ( preg_match( '/\sloading\s*=\s*(["\']?)(eager|lazy)\1/is', $img ) ) {
					return $img;
				}

				return $this->lazy_img_markup( $img );
			},
			$content
		);

		return $content;
	}

	/**
	 * Add loading="lazy" to post thumbnail HTML; first thumbnail gets eager.
	 *
	 * @param string       $html              Thumbnail HTML.
	 * @param int          $post_id           Post ID.
	 * @param string       $post_thumbnail_id  Thumbnail attachment ID.
	 * @param string|int[] $size              Size.
	 * @param array        $attr              Image attributes.
	 * @return string Filtered HTML.
	 */
	public function filter_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( empty( $html ) || strpos( $html, '<img' ) === false ) {
			return $html;
		}

		$this->thumbnail_count++;
		if ( $this->thumbnail_count === 1 ) {
			return $this->ensure_loading_attribute( $html, 'eager' );
		}

		return $this->lazy_img_markup( $html );
	}

	/**
	 * Add loading="lazy" to attachment image attributes.
	 *
	 * Used by wp_get_attachment_image() (thumbnails, custom field images rendered
	 * via that function, etc.). First thumbnail on the page is overridden to eager
	 * via post_thumbnail_html.
	 *
	 * @param array   $attr       Image attributes.
	 * @param WP_Post $attachment Attachment post.
	 * @param string|int[] $size  Size.
	 * @return array Filtered attributes.
	 */
	public function filter_attachment_image_attributes( $attr, $attachment, $size ) {
		if ( ! is_array( $attr ) ) {
			$attr = array();
		}

		// Do not override if already set (e.g. by theme).
		if ( isset( $attr['loading'] ) ) {
			return $attr;
		}

		$attr['loading'] = 'lazy';
		$attr['decoding'] = 'async';

		return $attr;
	}

	/**
	 * Lazy img markup: native loading="lazy" with the real src and srcset kept.
	 *
	 * This used to move src into data-src behind a 1×1 placeholder for a JS
	 * fallback to swap back. In every current browser that script restored all
	 * of them at once and stripped loading="lazy", so images loaded eagerly
	 * anyway, and with JS slow or blocked they never loaded at all: cards showed
	 * an empty tinted box (CCS/CLAUDE.md rule 21). Native lazy loading is
	 * supported everywhere this site needs to run, so the browser does it.
	 *
	 * @param string $html Full img tag.
	 * @return string Modified HTML.
	 */
	private function lazy_img_markup( $html ) {
		$html = $this->ensure_loading_attribute( $html, 'lazy' );
		if ( ! preg_match( '/\sdecoding\s*=/i', $html ) ) {
			$html = preg_replace( '/<img(\s)/i', '<img decoding="async"$1', $html, 1 );
		}
		return $html;
	}

	/**
	 * Ensure an img tag has a loading attribute with the given value.
	 *
	 * @param string $html   Full img tag or HTML containing one img.
	 * @param string $value  'lazy' or 'eager'.
	 * @return string Modified HTML.
	 */
	private function ensure_loading_attribute( $html, $value ) {
		$value = ( $value === 'lazy' ) ? 'lazy' : 'eager';

		if ( preg_match( '/\sloading\s*=\s*["\']?(?:eager|lazy)["\']?/is', $html ) ) {
			return preg_replace( '/\sloading\s*=\s*["\']?(?:eager|lazy)["\']?/is', ' loading="' . esc_attr( $value ) . '"', $html );
		}

		// Insert before closing > of opening tag.
		return preg_replace( '/<img(\s)/i', '<img loading="' . esc_attr( $value ) . '"$1', $html, 1 );
	}
}
