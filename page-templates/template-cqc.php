<?php
/**
 * Template Name: CQC and Our Care
 *
 * Photo hero, the page's own copy in a split intro, then the same CQC band as
 * the homepage (template-parts/home/cqc-section.php: live widget, fallback
 * badge, report link), then the CTA band. The band's CSS lives in
 * sections.css so it renders here too; it used to sit in homepage.css, which
 * left this page with an unstyled, full-width fallback badge stacked over the
 * live widget.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--cqc" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php if ( trim( get_the_content() ) !== '' ) : ?>
			<section class="ccs-section cqc-intro" aria-labelledby="cqc-intro-heading">
				<div class="container container--lg">
					<div class="ccs-split">
						<header class="ccs-head">
							<h2 id="cqc-intro-heading" class="ccs-title">
								<?php
								echo esc_html( ccs_copy( 'cqc_page_title' ) );
								?>
							</h2>
						</header>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
							<div class="page-body page-body--flush entry-content">
								<?php the_content(); ?>
							</div>
						</article>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/home/cqc-section' ); ?>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
