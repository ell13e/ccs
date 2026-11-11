<?php
/**
 * Checks the editable homepage copy registry without booting WordPress.
 *
 *   php bin/test-home-copy.php
 *
 * Fails (exit 1) when a template asks for a key the registry lacks, a field
 * points at a section that does not exist, a default is empty, or the
 * override and accent rules stop behaving as documented in inc/home-content.php.
 *
 * @package CCS_WP_Theme
 */

define( 'ABSPATH', '/' );
$ccs_test_mods = array();

function __( $text ) {
	return $text;
}
function esc_html( $text ) {
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8', false );
}
function get_theme_mod( $key, $fallback = '' ) {
	global $ccs_test_mods;
	return isset( $ccs_test_mods[ $key ] ) ? $ccs_test_mods[ $key ] : $fallback;
}
function ccs_accent( $word ) {
	return '<span class="ccs-accent">' . esc_html( $word ) . '</span>';
}

$root = dirname( __DIR__ );
require $root . '/inc/home-content.php';

$fails    = array();
$fields   = ccs_copy_fields();
$sections = ccs_copy_sections();

foreach ( $fields as $key => $field ) {
	if ( ! isset( $sections[ $field['section'] ] ) ) {
		$fails[] = "field $key: unknown section {$field['section']}";
	}
	if ( '' === trim( $field['default'] ) ) {
		$fails[] = "field $key: empty default";
	}
}

$templates = array_merge( glob( $root . '/*.php' ), glob( $root . '/page-templates/*.php' ), glob( $root . '/template-parts/*.php' ), glob( $root . '/template-parts/home/*.php' ) );
$used      = array();
foreach ( $templates as $template ) {
	preg_match_all( "/ccs_copy(?:_accent)?\( '([a-z0-9_]+)' \)/", file_get_contents( $template ), $m );
	foreach ( $m[1] as $key ) {
		$used[ $key ] = true;
		if ( ! isset( $fields[ $key ] ) ) {
			$fails[] = basename( $template ) . ": key $key is not in the registry";
		}
	}
}

foreach ( array_keys( $fields ) as $key ) {
	if ( ! isset( $used[ $key ] ) ) {
		$fails[] = "field $key: in the registry but no template prints it";
	}
}

$expect = static function ( $label, $actual, $wanted ) use ( &$fails ) {
	if ( $actual !== $wanted ) {
		$fails[] = "$label: got \"$actual\", wanted \"$wanted\"";
	}
};

$expect( 'default', ccs_copy( 'steps_title' ), $fields['steps_title']['default'] );
$ccs_test_mods['ccs_copy_steps_title'] = 'How your care begins';
$expect( 'override', ccs_copy( 'steps_title' ), 'How your care begins' );
$ccs_test_mods['ccs_copy_steps_title'] = '   ';
$expect( 'blank falls back', ccs_copy( 'steps_title' ), $fields['steps_title']['default'] );
$expect( 'accent', ccs_copy_accent( 'cqc_title' ), 'Rated <span class="ccs-accent">Good</span> by the CQC' );
$ccs_test_mods['ccs_copy_cqc_title'] = 'Rated <b>Good</b> & more';
$expect( 'accent escapes html', ccs_copy_accent( 'cqc_title' ), 'Rated &lt;b&gt;Good&lt;/b&gt; &amp; more' );
$ccs_test_mods['ccs_copy_cqc_title'] = '*Good*, say the *CQC*';
$expect( 'one accent only', ccs_copy_accent( 'cqc_title' ), '<span class="ccs-accent">Good</span>, say the CQC' );

if ( $fails ) {
	echo implode( "\n", $fails ), "\n";
	exit( 1 );
}
echo count( $fields ), " editable copy fields OK\n";
