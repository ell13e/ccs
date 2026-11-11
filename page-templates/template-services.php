<?php
/**
 * Template Name: Services Hub
 *
 * Services landing page. The service cards come first, straight under the
 * hero, because choosing a service is why people land here: the page's own
 * lead paragraph sits over them as the section lede. The rest of the editor
 * copy (who we support, how we staff it, areas, costs) follows as a split
 * section, then the shared CTA band. Same .svc-card as the homepage and the
 * related-services row on each service page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_services = ccs_ordered_services();
?>

<main id="main" class="site-main site-main--services" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_content = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
		$ccs_intro   = $ccs_content['intro'];
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php if ( ! empty( $ccs_services ) ) : ?>
			<section class="ccs-section services-hub" aria-labelledby="services-hub-heading">
				<div class="container container--lg">
					<?php
					/*
					 * Split head, not centred: the lede is the page's own four-line
					 * intro, and centred text that long is hard to read (CCS/CLAUDE.md
					 * rule 10). Title left, intro right, both left-aligned.
					 */
					?>
					<header class="ccs-head ccs-head--split">
						<h2 id="services-hub-heading" class="ccs-title">
							<?php
							echo ccs_copy_accent( 'services_page_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
							?>
						</h2>
						<?php if ( trim( wp_strip_all_tags( $ccs_intro ) ) !== '' ) : ?>
							<div class="ccs-lede ccs-lede--prose"><?php echo $ccs_intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?></div>
						<?php endif; ?>
					</header>

					<?php get_template_part( 'template-parts/service-cards', null, array( 'services' => $ccs_services ) ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $ccs_content['sections'] ) || empty( $ccs_services ) ) : ?>
			<section class="ccs-section ccs-section--tint services-how" aria-labelledby="services-how-heading">
				<div class="container container--lg">
					<div class="ccs-split">
						<header class="ccs-head">
							<h2 id="services-how-heading" class="ccs-title">
								<?php
								echo esc_html( ccs_copy( 'services_page_promise_title' ) );
								?>
							</h2>
						</header>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
							<div class="page-body page-body--flush entry-content">
								<?php if ( empty( $ccs_services ) ) : ?>
									<?php echo $ccs_intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?>
								<?php endif; ?>
								<?php if ( ! empty( $ccs_content['sections'] ) ) : ?>
									<?php
									/*
									 * The editor's h2s sit under this section's own h2, so they
									 * print as h3: one outline, no two headings at the same level
									 * claiming the same rank.
									 */
									foreach ( $ccs_content['sections'] as $ccs_section ) :
										?>
										<h3><?php echo esc_html( $ccs_section['heading'] ); ?></h3>
										<?php echo $ccs_section['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</article>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
