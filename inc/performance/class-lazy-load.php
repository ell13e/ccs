<?php
/**
 * Native lazy loading for images.
 *
 * Adds loading="lazy" to post content images, post thumbnails, and
 * attachment images. Skips the first above-the-fold image, admin, and feeds.
 * Enqueues a JS fallback for browsers that do not support native lazy loading.
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
	 * Placeholder image (1x1 transparent GIF) for lazy images so fallback JS can defer loading.
	 *
	 * @var string
	 */
	const PLACEHOLDER = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_fallback_script' ), 15 );
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
	 * Output lazy img markup: loading="lazy", real URL in data-src, placeholder in src.
	 *
	 * Allows the JS fallback to defer loading in browsers that ignore loading="lazy".
	 *
	 * @param string $html Full img tag.
	 * @return string Modified HTML.
	 */
	private function lazy_img_markup( $html ) {
		$html = $this->ensure_loading_attribute( $html, 'lazy' );

		// Move src to data-src and set placeholder so fallback JS can control when to load.
		if ( preg_match( '/\ssrc\s*=\s*(["\'])([^"\']+)\1/is', $html, $src_match ) ) {
			$url = $src_match[2];
			if ( strpos( $url, 'data:' ) !== 0 ) {
				$html = preg_replace( '/\ssrc\s*=\s*["\'][^"\']*["\']/is', ' src="' . esc_attr( self::PLACEHOLDER ) . '" data-src="' . esc_attr( $url ) . '"', $html, 1 );
				// Move srcset to data-srcset so browser does not load it; fallback JS restores it.
				if ( preg_match( '/\ssrcset\s*=\s*(["\'])([^"\']+)\1/is', $html, $set_match ) ) {
					$html = preg_replace( '/\ssrcset\s*=\s*["\'][^"\']*["\']/is', ' data-srcset="' . esc_attr( $set_match[2] ) . '"', $html, 1 );
				}
			}
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

	/**
	 * Enqueue fallback script for browsers that do not support native loading="lazy".
	 */
	public function enqueue_fallback_script() {
		if ( is_admin() || is_feed() ) {
			return;
		}

		wp_enqueue_script(
			'ccs-lazy-load-fallback',
			THEME_URL . '/assets/js/lazy-load-fallback.js',
			array(),
			THEME_VERSION,
			true
		);

		// Optional: load IntersectionObserver polyfill for IE11 (uncomment if supporting IE11).
		// wp_enqueue_script(
		// 	'intersection-observer-polyfill',
		// 	'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver',
		// 	array(),
		// 	null,
		// 	true
		// );
		// wp_script_add_data( 'ccs-lazy-load-fallback', 'after', array( 'intersection-observer-polyfill' ) );
	}
}
