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
	 * -> differentiators (concrete proof) -> costs (the question families ask
	 * first, and the one competitors refuse to answer) -> testimonial (social
	 * proof) -> info cards (next steps) -> partnerships (supporting credibility)
	 * -> closing CTA (the ask).
	 */
	?>
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php get_template_part( 'template-parts/home/cqc-section' ); ?>
	<?php get_template_part( 'template-parts/home/why-choose-us' ); ?>
	<?php get_template_part( 'template-parts/home/services' ); ?>
	<?php get_template_part( 'template-parts/home/differentiators' ); ?>
	<?php get_template_part( 'template-parts/home/costs' ); ?>
	<?php get_template_part( 'template-parts/home/testimonial' ); ?>
	<?php get_template_part( 'template-parts/home/info-cards' ); ?>
	<?php get_template_part( 'template-parts/home/partnerships' ); ?>
	<?php get_template_part( 'template-parts/home/closing-cta' ); ?>

</main>

<?php
/*
 * The WebPage + WebSite + LocalBusiness graph that used to be emitted here was
 * removed on 2026-08-23. It declared #organization as LocalBusiness while
 * CCS_Structured_Data simultaneously declared the same @id as
 * HomeHealthCareService, and #website twice — so the homepage published two
 * conflicting definitions of the same two entities. All three node types are
 * now produced by inc/seo/class-structured-data.php inside one @graph, with
 * #organization carrying both types as an array.
 */
get_footer();
