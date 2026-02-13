<?php
/**
 * WP-CLI command: wp ccs regenerate-critical-css
 *
 * Regenerate or clear stored critical CSS per template type.
 * Use with Critical (https://github.com/addyosmani/critical) or Penthouse
 * to generate per-URL CSS, then import: --template=page --from-file=path/to/critical.css
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Class CCS_Critical_CSS_Command
 */
class CCS_Critical_CSS_Command {

	/**
	 * Regenerate or clear stored critical CSS.
	 *
	 * ## OPTIONS
	 *
	 * [--template=<type>]
	 * : Template type to update. One of: default, front_page, home, single, page, singular_service, singular_location, archive, search, 404.
	 *
	 * [--from-file=<path>]
	 * : Path to a CSS file to store for the given (or default) template. If omitted with --template, clears that template's stored CSS.
	 *
	 * [--clear]
	 * : Clear all stored critical CSS (theme will fall back to assets/css/critical.css).
	 *
	 * ## EXAMPLES
	 *
	 *     # Clear all stored critical CSS
	 *     wp ccs regenerate-critical-css --clear
	 *
	 *     # Store critical CSS for "page" template from a file
	 *     wp ccs regenerate-critical-css --template=page --from-file=/path/to/page-critical.css
	 *
	 *     # Store for front_page from file
	 *     wp ccs regenerate-critical-css --template=front_page --from-file=./critical-front.css
	 *
	 *     # Clear stored CSS for default template only (fall back to file for that type)
	 *     wp ccs regenerate-critical-css --template=default
	 *
	 * @param array $args       Positional args (unused).
	 * @param array $assoc_args --template, --from-file, --clear.
	 */
	public function __invoke( $args, $assoc_args ) {
		$clear   = isset( $assoc_args['clear'] ) && $assoc_args['clear'];
		$template = isset( $assoc_args['template'] ) ? trim( $assoc_args['template'] ) : '';
		$from_file = isset( $assoc_args['from-file'] ) ? trim( $assoc_args['from-file'] ) : '';

		$types = CCS_Critical_CSS::get_template_types();

		if ( $clear ) {
			CCS_Critical_CSS::clear_stored_css();
			WP_CLI::success( 'Cleared all stored critical CSS. Theme will use assets/css/critical.css as fallback.' );
			return;
		}

		if ( $template !== '' && ! in_array( $template, $types, true ) ) {
			WP_CLI::error( sprintf( 'Invalid --template. Use one of: %s', implode( ', ', $types ) ) );
		}

		if ( $from_file !== '' ) {
			$path = $from_file;
			if ( ! preg_match( '#^/#', $path ) ) {
				$path = get_template_directory() . '/' . $path;
			}
			if ( ! is_readable( $path ) ) {
				WP_CLI::error( sprintf( 'File not readable: %s', $path ) );
			}
			$css = file_get_contents( $path );
			if ( $css === false ) {
				WP_CLI::error( 'Could not read file contents.' );
			}
			$template = $template !== '' ? $template : 'default';
			CCS_Critical_CSS::save_css_for_template( $template, $css );
			WP_CLI::success( sprintf( 'Stored critical CSS for template "%s" (%d bytes).', $template, strlen( $css ) ) );
			return;
		}

		if ( $template !== '' ) {
			$stored = get_option( CCS_Critical_CSS::OPTION_KEY, array() );
			if ( ! is_array( $stored ) ) {
				$stored = array();
			}
			unset( $stored[ $template ] );
			update_option( CCS_Critical_CSS::OPTION_KEY, $stored );
			WP_CLI::success( sprintf( 'Cleared stored critical CSS for template "%s".', $template ) );
			return;
		}

		WP_CLI::log( 'Usage: --clear to clear all; --template=<type> [--from-file=<path>] to set/clear one template.' );
		WP_CLI::log( 'Template types: ' . implode( ', ', $types ) );
	}
}

WP_CLI::add_command( 'ccs regenerate-critical-css', 'CCS_Critical_CSS_Command' );
