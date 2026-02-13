<?php
/**
 * Autoloader for theme classes.
 *
 * Loads classes from inc/ and inc/* subdirectories using WordPress file naming:
 * Class_Name → class-class-name.php
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Autoloader
 */
class CCS_Autoloader {

	/**
	 * Base directory for class files (inc/).
	 *
	 * @var string
	 */
	private static $base_dir;

	/**
	 * Register the autoloader.
	 *
	 * @param string $base_dir Optional. Path to inc directory. Defaults to THEME_DIR . '/inc'.
	 */
	public static function register( $base_dir = null ) {
		self::$base_dir = $base_dir ? $base_dir : THEME_DIR . '/inc';
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	/**
	 * Autoload callback: resolve class name to file and require if found.
	 *
	 * @param string $class Fully qualified class name.
	 */
	public static function load( $class ) {
		$filename = self::class_to_filename( $class );
		if ( ! $filename ) {
			return;
		}

		$path = self::$base_dir . '/' . $filename;
		if ( is_readable( $path ) ) {
			require_once $path;
			return;
		}

		// Search one level of subdirectories under inc/.
		$dirs = glob( self::$base_dir . '/*', GLOB_ONLYDIR );
		if ( ! is_array( $dirs ) ) {
			self::autoload_error( $class, 'Subdirectories not readable' );
			return;
		}

		foreach ( $dirs as $dir ) {
			$path = $dir . '/' . $filename;
			if ( is_readable( $path ) ) {
				require_once $path;
				return;
			}
		}

		// Class file not found; allow other autoloaders to run.
		// Uncomment below to surface missing-class issues during development:
		// self::autoload_error( $class, 'File not found: ' . $filename );
	}

	/**
	 * Convert WordPress class name to file name.
	 *
	 * My_Class_Name → class-my-class-name.php
	 *
	 * @param string $class Class name.
	 * @return string|null File name or null if not a valid theme class file name.
	 */
	private static function class_to_filename( $class ) {
		$name = strtolower( str_replace( '_', '-', $class ) );
		return 'class-' . $name . '.php';
	}

	/**
	 * Log or trigger error when autoload fails (for debugging).
	 *
	 * @param string $class  Class that was requested.
	 * @param string $reason Error reason.
	 */
	private static function autoload_error( $class, $reason ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( sprintf( '[CCS Autoloader] %s: %s', $class, $reason ) );
		}
	}
}
