<?php
/**
 * Automatic image optimisation: WebP generation, WebP serving, size limits,
 * custom image sizes, and responsive srcset/sizes.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Image_Optimization
 */
class CCS_Image_Optimization {

	/**
	 * WebP quality (0–100).
	 *
	 * @var int
	 */
	const WEBP_QUALITY = 85;

	/**
	 * Max dimension (width or height) for uploads; images larger than this are resized.
	 *
	 * @var int
	 */
	const MAX_DIMENSION = 2400;

	/**
	 * Whether the current request accepts WebP (cached per request).
	 *
	 * @var bool|null
	 */
	private static $accepts_webp;

	/**
	 * Hook into WordPress.
	 */
	public function __construct() {
		// Custom image sizes (must run before uploads so new sizes are generated).
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ), 10 );

		// Cap upload dimensions and resize on upload.
		add_filter( 'big_image_size_threshold', array( $this, 'filter_big_image_size_threshold' ), 10, 4 );

		// Generate WebP for all image sizes on upload.
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_webp_for_attachment' ), 10, 2 );

		// Serve WebP when browser accepts it (single source and srcset).
		add_filter( 'wp_get_attachment_image_src', array( $this, 'filter_attachment_image_src' ), 10, 4 );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'filter_image_srcset' ), 10, 5 );

		// Ensure responsive img: srcset and sizes (WordPress adds these by default; we only ensure lazy loading is possible).
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'ensure_responsive_attributes' ), 10, 3 );
	}

	/**
	 * Register custom image sizes: Hero, Card, Thumbnail.
	 */
	public function register_image_sizes() {
		add_image_size( 'ccs-hero', 1200, 600, true );
		add_image_size( 'ccs-card', 600, 400, true );
		add_image_size( 'ccs-thumbnail', 300, 300, true );
	}

	/**
	 * Set max upload dimension so WordPress resizes images above this.
	 *
	 * @param int    $threshold    Current threshold (default 2560).
	 * @param array  $imagesize    Result of getimagesize() on the upload.
	 * @param string $file         Full path to the file.
	 * @param int    $attachment_id Attachment ID.
	 * @return int New threshold in pixels.
	 */
	public function filter_big_image_size_threshold( $threshold, $imagesize, $file, $attachment_id ) {
		return self::MAX_DIMENSION;
	}

	/**
	 * Generate WebP version for the main file and all registered sizes.
	 *
	 * @param array $metadata    Attachment metadata from wp_generate_attachment_metadata.
	 * @param int   $attachment_id Attachment ID.
	 * @return array Unchanged metadata.
	 */
	public function generate_webp_for_attachment( $metadata, $attachment_id ) {
		if ( ! $this->gd_webp_available() || empty( $metadata['file'] ) ) {
			return $metadata;
		}

		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) {
			return $metadata;
		}

		$base_path = trailingslashit( $upload_dir['basedir'] ) . dirname( $metadata['file'] );
		$base_url  = trailingslashit( $upload_dir['baseurl'] ) . dirname( $metadata['file'] );

		$files_to_convert = array();

		// Full size (main file).
		$full_path = path_join( $upload_dir['basedir'], $metadata['file'] );
		if ( $this->is_supported_image( $full_path ) ) {
			$files_to_convert[] = array(
				'path' => $full_path,
				'url'  => path_join( $upload_dir['baseurl'], $metadata['file'] ),
			);
		}

		// All intermediate sizes.
		if ( ! empty( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
			foreach ( $metadata['sizes'] as $size_data ) {
				if ( empty( $size_data['file'] ) ) {
					continue;
				}
				$path = path_join( $base_path, $size_data['file'] );
				if ( $this->is_supported_image( $path ) ) {
					$files_to_convert[] = array(
						'path' => $path,
						'url'  => path_join( $base_url, $size_data['file'] ),
					);
				}
			}
		}

		foreach ( $files_to_convert as $item ) {
			$this->create_webp_file( $item['path'] );
		}

		return $metadata;
	}

	/**
	 * Create a WebP file next to the given image path (same name, .webp extension).
	 *
	 * @param string $image_path Full filesystem path to a JPEG or PNG.
	 * @return bool True if WebP was written, false otherwise.
	 */
	private function create_webp_file( $image_path ) {
		if ( ! is_readable( $image_path ) ) {
			return false;
		}

		$webp_path = $this->path_to_webp( $image_path );
		if ( $webp_path === $image_path ) {
			return false;
		}

		$image = $this->load_image( $image_path );
		if ( ! $image ) {
			return false;
		}

		$result = imagewebp( $image, $webp_path, self::WEBP_QUALITY );
		$this->free_image( $image );

		if ( $result && is_readable( $webp_path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get WebP path for a given image path (replaces extension with .webp).
	 *
	 * @param string $path File path.
	 * @return string WebP path or same path if not a supported type.
	 */
	private function path_to_webp( $path ) {
		$ext = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		if ( $ext === 'jpeg' || $ext === 'jpg' || $ext === 'png' ) {
			return preg_replace( '/\.(jpe?g|png)$/i', '.webp', $path );
		}
		return $path;
	}

	/**
	 * Get WebP URL for a given image URL.
	 *
	 * @param string $url Image URL.
	 * @return string WebP URL or original if not supported extension.
	 */
	private function url_to_webp( $url ) {
		return preg_replace( '/\.(jpe?g|png)(\?.*)?$/i', '.webp$2', $url );
	}

	/**
	 * Load GD image resource from file path.
	 *
	 * @param string $path Full path to JPEG or PNG.
	 * @return \GdImage|resource|false GD image (GdImage in PHP 8+, resource in PHP 7) or false.
	 */
	private function load_image( $path ) {
		$ext = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		if ( $ext === 'jpg' || $ext === 'jpeg' ) {
			return imagecreatefromjpeg( $path );
		}
		if ( $ext === 'png' ) {
			$im = imagecreatefrompng( $path );
			if ( $im ) {
				imagealphablending( $im, true );
				imagesavealpha( $im, true );
			}
			return $im;
		}
		return false;
	}

	/**
	 * Free GD image resource.
	 *
	 * @param \GdImage|resource $image GD image (GdImage in PHP 8+, resource in PHP 7).
	 */
	private function free_image( $image ) {
		$is_gd = ( PHP_VERSION_ID >= 80000 && $image instanceof \GdImage )
			|| ( is_resource( $image ) && get_resource_type( $image ) === 'gd' );
		if ( $is_gd ) {
			imagedestroy( $image );
		}
	}

	/**
	 * Check if path is a supported image type for WebP conversion.
	 *
	 * @param string $path File path.
	 * @return bool
	 */
	private function is_supported_image( $path ) {
		$ext = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		return in_array( $ext, array( 'jpg', 'jpeg', 'png' ), true );
	}

	/**
	 * Whether GD supports WebP (imagewebp exists and works).
	 *
	 * @return bool
	 */
	private function gd_webp_available() {
		return function_exists( 'imagewebp' ) && function_exists( 'imagecreatefromjpeg' );
	}

	/**
	 * Whether the current request accepts WebP (from Accept header).
	 *
	 * @return bool
	 */
	private function request_accepts_webp() {
		if ( self::$accepts_webp !== null ) {
			return self::$accepts_webp;
		}

		$accept = isset( $_SERVER['HTTP_ACCEPT'] ) ? (string) $_SERVER['HTTP_ACCEPT'] : '';
		self::$accepts_webp = ( strpos( $accept, 'image/webp' ) !== false );
		return self::$accepts_webp;
	}

	/**
	 * Check if a WebP file exists for the given full image URL (by resolving to path).
	 *
	 * @param string $image_url Full URL to the original image.
	 * @return bool
	 */
	private function webp_exists_for_url( $image_url ) {
		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) {
			return false;
		}

		$base_url = $upload_dir['baseurl'];
		if ( strpos( $image_url, $base_url ) !== 0 ) {
			return false;
		}

		$relative = substr( $image_url, strlen( $base_url ) );
		$relative = ltrim( $relative, '/' );
		$path     = path_join( $upload_dir['basedir'], $relative );
		$webp_path = $this->path_to_webp( $path );

		return ( $webp_path !== $path && is_readable( $webp_path ) );
	}

	/**
	 * Filter wp_get_attachment_image_src: return WebP URL when browser accepts it and WebP exists.
	 *
	 * @param array|false $image         Array of (url, width, height, cropped) or false.
	 * @param int         $attachment_id Attachment ID.
	 * @param string|int[] $size         Requested size.
	 * @param bool        $icon          Whether icon was requested.
	 * @return array|false
	 */
	public function filter_attachment_image_src( $image, $attachment_id, $size, $icon ) {
		if ( ! $image || ! is_array( $image ) || empty( $image[0] ) ) {
			return $image;
		}

		if ( ! $this->request_accepts_webp() ) {
			return $image;
		}

		if ( ! $this->webp_exists_for_url( $image[0] ) ) {
			return $image;
		}

		$image[0] = $this->url_to_webp( $image[0] );
		return $image;
	}

	/**
	 * Filter wp_calculate_image_srcset: replace each source URL with WebP when accepted and available.
	 *
	 * @param array  $sources    Array of width => array( 'url', 'descriptor', 'value' ).
	 * @param array  $size_array Array of width and height.
	 * @param string $image_src  Main image URL.
	 * @param array  $image_meta Attachment meta.
	 * @param int    $attachment_id Attachment ID.
	 * @return array
	 */
	public function filter_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		if ( ! is_array( $sources ) || ! $this->request_accepts_webp() ) {
			return $sources;
		}

		foreach ( $sources as $width => $source ) {
			if ( ! isset( $source['url'] ) ) {
				continue;
			}
			if ( $this->webp_exists_for_url( $source['url'] ) ) {
				$sources[ $width ]['url'] = $this->url_to_webp( $source['url'] );
			}
		}

		return $sources;
	}

	/**
	 * Ensure attachment image has decoding and (optionally) loading for responsive + lazy.
	 *
	 * WordPress adds srcset/sizes by default. We add decoding="async" and leave loading to CCS_Lazy_Load.
	 *
	 * @param array   $attr       Image attributes.
	 * @param WP_Post $attachment Attachment.
	 * @param string|int[] $size  Size.
	 * @return array
	 */
	public function ensure_responsive_attributes( $attr, $attachment, $size ) {
		if ( ! is_array( $attr ) ) {
			$attr = array();
		}
		$attr['decoding'] = 'async';
		return $attr;
	}
}
