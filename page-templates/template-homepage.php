<?php
/**
 * Template Name: Homepage
 *
 * Full-width homepage per content guide §2b: hero, why choose us, differentiators, CQC, care options (services), info cards, partnerships, testimonial.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone = get_theme_mod( 'ccs_phone', '01622 809 881' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
?>

<main id="main" class="site-main site-main--homepage" role="main">

	<?php
	/*
	 * Section order: hero, partner marquee, why choose us, services, steps, the
	 * story video, differentiators, reviews, team, news, closing CTA. Grounds are
	 * set in one block at the end of homepage.css.
	 */
	?>
	<?php
	/*
	 * Calm pass (Nov 2026): eleven bands, down from thirteen. "Where to go next"
	 * repeated the navigation and was dropped; the partner logo wall (twelve
	 * multicoloured logos) moved to the About page, where "who we work with" is
	 * the question being asked. Grounds are set in one block at the end of
	 * homepage.css.
	 */
	?>
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php
	/*
	 * Lighter pass (Nov 2026). The client found the CQC band, "What we do
	 * differently", "How care starts" and "What care costs" ugly, and the costs
	 * band overwhelming. The CQC band left the homepage: the rating is in the
	 * hero (badge and "CQC rated Good", both linking to the report) and the live
	 * widget now sits in the footer of every page. The other three were rebuilt
	 * to show less at once. Grounds alternate white / tint from "Why choose us".
	 */
	?>
	<?php get_template_part( 'template-parts/home/partnerships', null, array( 'layout' => 'marquee' ) ); ?>
	<?php get_template_part( 'template-parts/home/why-choose-us' ); ?>
	<?php get_template_part( 'template-parts/home/services' ); ?>
	<?php get_template_part( 'template-parts/home/steps' ); ?>
	<?php get_template_part( 'template-parts/home/story' ); ?>
	<?php get_template_part( 'template-parts/home/differentiators' ); ?>
	<?php get_template_part( 'template-parts/home/testimonial' ); ?>
	<?php // Costs: removed from the homepage (Nov 2026); prices now sit on the service cards. ?>
	<?php get_template_part( 'template-parts/home/team' ); ?>
	<?php get_template_part( 'template-parts/home/news' ); ?>
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
