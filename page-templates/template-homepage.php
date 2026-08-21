<?php
/**
 * Template Name: Homepage
 *
 * Full-width homepage per content guide §2b: hero, why choose us, differentiators, CQC, care options (services), info cards, partnerships, testimonial.
 *
 * Note: template-parts/home/scenarios.php exists but is deliberately NOT included here yet.
 * Its scenarios and links now match the site's URL structure and the "no rushed care" USP;
 * still needs a design pass before it's wired in.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone = get_theme_mod( 'ccs_phone', '01234 567890' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
?>

<main id="main" class="site-main site-main--homepage" role="main">

	<?php
	/*
	 * Section order follows the family's decision path:
	 * hero (who/what/where + CTA) -> CQC (regulatory proof, checked early)
	 * -> why choose us (narrative) -> services (the offer)
	 * -> differentiators (concrete proof) -> testimonial (social proof)
	 * -> info cards (next steps) -> partnerships (supporting credibility).
	 */
	?>
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php get_template_part( 'template-parts/home/cqc-section' ); ?>
	<?php get_template_part( 'template-parts/home/why-choose-us' ); ?>
	<?php get_template_part( 'template-parts/home/services' ); ?>
	<?php get_template_part( 'template-parts/home/differentiators' ); ?>
	<?php get_template_part( 'template-parts/home/testimonial' ); ?>
	<?php get_template_part( 'template-parts/home/info-cards' ); ?>
	<?php get_template_part( 'template-parts/home/partnerships' ); ?>

</main>

<?php
/* Schema.org: WebPage + LocalBusiness for homepage */
$schema_logo = '';
if ( has_custom_logo() ) {
	$logo_id = get_theme_mod( 'custom_logo' );
	$logo    = wp_get_attachment_image_src( $logo_id, 'full' );
	if ( $logo ) {
		$schema_logo = $logo[0];
	}
}
$schema_org = array(
	'@context' => 'https://schema.org',
	'@graph'   => array(
		array(
			'@type'           => 'WebPage',
			'@id'             => esc_url( home_url( '/' ) ) . '#webpage',
			'url'             => esc_url( home_url( '/' ) ),
			'name'            => wp_get_document_title(),
			'description'     => get_bloginfo( 'description' ) ?: __( 'Complex and personal home care in Kent — a small, consistent team matched to you, for however long you need us.', 'ccs-wp-theme' ),
			'isPartOf'        => array(
				'@id' => esc_url( home_url( '/' ) ) . '#website',
			),
			'primaryImageOfPage' => get_the_post_thumbnail_url( get_queried_object_id(), 'full' ) ?: $schema_logo,
		),
		array(
			'@type' => 'WebSite',
			'@id'   => esc_url( home_url( '/' ) ) . '#website',
			'url'   => esc_url( home_url( '/' ) ),
			'name'  => get_bloginfo( 'name' ),
			'publisher' => array( '@id' => esc_url( home_url( '/' ) ) . '#organization' ),
		),
		array(
			'@type'       => 'LocalBusiness',
			'@id'         => esc_url( home_url( '/' ) ) . '#organization',
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ) ?: __( 'Home care in Maidstone & Kent. Complex care, personal care and companionship — your team, your time, your life.', 'ccs-wp-theme' ),
			'url'         => esc_url( home_url( '/' ) ),
			'areaServed'  => array(
				'@type' => 'AdministrativeArea',
				'name'  => 'Kent',
			),
			'address' => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Maidstone',
				'addressRegion'   => 'Kent',
				'addressCountry'  => 'GB',
			),
		),
	),
);
if ( $schema_logo ) {
	$schema_org['@graph'][2]['logo'] = $schema_logo;
}
if ( $ccs_phone_tel ) {
	$schema_org['@graph'][2]['telephone'] = $ccs_phone_tel;
}
?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema_org ); ?></script>

<?php
get_footer();
