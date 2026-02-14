<?php
/**
 * Schema.org Organization JSON-LD output.
 *
 * Single canonical Organization block (name, url, logo, contactPoint).
 * Hooked to wp_head; duplicate inline block removed from header.php.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Schema.org Organization JSON-LD in head.
 */
function ccs_output_organization_schema() {
	$schema = ccs_get_organization_schema();
	if ( empty( $schema ) ) {
		return;
	}
	$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( $json === false ) {
		return;
	}
	echo '<script type="application/ld+json">' . $json . "</script>\n";
}
add_action( 'wp_head', 'ccs_output_organization_schema', 2 );

/**
 * Build Organization schema array (name, url, logo, contactPoint).
 *
 * @return array Schema array or empty if invalid.
 */
function ccs_get_organization_schema() {
	$name = get_bloginfo( 'name' );
	$url  = home_url( '/' );
	if ( empty( $name ) || empty( $url ) ) {
		return array();
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => $name,
		'url'      => $url,
	);

	$logo_url = '';
	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_src( $logo_id, 'full' ) : null;
		if ( ! empty( $logo[0] ) ) {
			$logo_url = $logo[0];
		}
	}
	if ( $logo_url !== '' ) {
		$schema['logo'] = $logo_url;
	}

	$phone = get_theme_mod( 'ccs_phone', '' );
	if ( is_string( $phone ) && trim( $phone ) !== '' ) {
		$tel = preg_replace( '/\s+/', '', trim( $phone ) );
		$schema['contactPoint'] = array(
			'@type'             => 'ContactPoint',
			'telephone'         => $tel,
			'contactType'       => 'customer service',
			'areaServed'        => 'GB',
			'availableLanguage' => 'English',
		);
	}

	return $schema;
}

/**
 * Output CQC-related Schema.org JSON-LD (HealthAndBeautyBusiness / LocalBusiness with credential).
 * Renders on front page only. CQC registration 1-2624556588, rating "Good" = 4/5.
 *
 * @since 1.0.0
 */
function ccs_output_cqc_schema() {
	if ( ! is_front_page() ) {
		return;
	}
	$schema = ccs_get_cqc_schema();
	if ( empty( $schema ) ) {
		return;
	}
	$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( $json === false ) {
		return;
	}
	echo '<script type="application/ld+json">' . $json . "</script>\n";
}
add_action( 'wp_head', 'ccs_output_cqc_schema', 3 );

/**
 * Build CQC schema array: LocalBusiness with hasCredential, aggregateRating, recognizedBy.
 *
 * @return array Schema array or empty if invalid.
 */
function ccs_get_cqc_schema() {
	$name = get_bloginfo( 'name' );
	$url  = home_url( '/' );
	if ( empty( $name ) || empty( $url ) ) {
		return array();
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => array( 'LocalBusiness', 'HealthAndBeautyBusiness' ),
		'name'            => $name,
		'url'             => $url,
		'hasCredential'   => array(
			'@type'         => 'EducationalOccupationalCredential',
			'credentialId'  => '1-2624556588',
			'name'          => 'CQC Registration',
			'recognizedBy'  => array(
				'@type' => 'Organization',
				'name'  => 'Care Quality Commission',
				'url'   => 'https://www.cqc.org.uk',
			),
		),
		'aggregateRating' => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => 4,
			'bestRating'  => 5,
			'worstRating' => 1,
			'ratingCount' => 1,
			'name'        => 'Good',
		),
	);

	return $schema;
}
