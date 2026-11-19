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

					<?php
					/*
					 * Twenty-one services (Nov 2026): the ones with a photograph lead
					 * as cards; then every service, grouped as the menu groups them
					 * ("Everyday care", "Condition-led care"), as rows: name, one
					 * line, arrow. A grid of twenty-one cards, most of them an icon on
					 * a tile, was the layout a design review marked down.
					 */
					$ccs_hub_photo  = array_values( array_filter( $ccs_services, 'has_post_thumbnail' ) );
					$ccs_hub_groups = function_exists( 'ccs_service_groups' ) ? ccs_service_groups() : array();
					?>
					<?php if ( $ccs_hub_photo && $ccs_hub_groups ) : ?>
						<?php get_template_part( 'template-parts/service-cards', null, array( 'services' => $ccs_hub_photo ) ); ?>
					<?php elseif ( ! $ccs_hub_groups ) : ?>
						<?php get_template_part( 'template-parts/service-cards', null, array( 'services' => $ccs_services ) ); ?>
					<?php endif; ?>

					<?php
					/*
					 * Every service as a typographic index: two soft panels, the names
					 * set large in the heading face and flowing like a sentence, each a
					 * link. The rows of name-plus-description it replaces were "ugly"
					 * (client, who asked for something more creative).
					 */
					?>
					<?php if ( $ccs_hub_groups ) : ?>
						<div class="services-index">
							<?php foreach ( $ccs_hub_groups as $ccs_hub_key => $ccs_hub_label ) : ?>
								<nav class="services-index__panel" id="<?php echo esc_attr( $ccs_hub_key ); ?>" aria-labelledby="services-index-<?php echo esc_attr( $ccs_hub_key ); ?>" data-ccs-exception="rule 1: one group of services, a set of links that belongs together">
									<h2 class="services-index__title" id="services-index-<?php echo esc_attr( $ccs_hub_key ); ?>"><?php echo esc_html( $ccs_hub_label ); ?></h2>
									<ul class="services-index__list" role="list">
										<?php foreach ( $ccs_services as $ccs_hub_svc ) : ?>
											<?php
											if ( ccs_service_group( $ccs_hub_svc->post_name ) !== $ccs_hub_key ) {
												continue;
											}
											?>
											<li><a href="<?php echo esc_url( get_permalink( $ccs_hub_svc ) ); ?>"><?php echo esc_html( get_the_title( $ccs_hub_svc ) ); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</nav>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $ccs_content['sections'] ) || empty( $ccs_services ) ) : ?>
			<section class="ccs-section ccs-section--tint services-how" aria-labelledby="services-how-heading">
				<div class="container container--lg">
					<?php
					/*
					 * Inner-page pass, Nov 2026. This was a title in an empty left
					 * column beside 1,650px of text. The topics are now the site's
					 * accordion (same as FAQs and "Good to know"), first one open:
					 * six headings on one screen, each answer a tap away.
					 *
					 * Tried and rejected by the user the same day: the title beside a
					 * photo with the topics two across under hairlines. Loose text
					 * under rules read as unfinished ("thin is not calm"), and the one
					 * unused carer-and-client photo is too dark for a slot that size.
					 */
					?>
					<div class="ccs-split">
						<header class="ccs-head">
							<h2 id="services-how-heading" class="ccs-title">
								<?php
								echo esc_html( ccs_copy( 'services_page_promise_title' ) );
								?>
							</h2>
						</header>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
							<?php if ( empty( $ccs_services ) ) : ?>
								<div class="page-body page-body--flush entry-content">
									<?php echo $ccs_intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $ccs_content['sections'] ) ) : ?>
								<div class="ccs-faq">
									<?php foreach ( $ccs_content['sections'] as $ccs_i => $ccs_section ) : ?>
										<details class="ccs-faq__item" name="services-how"<?php echo 0 === $ccs_i ? ' open' : ''; ?>>
											<summary class="ccs-faq__q"><?php echo esc_html( $ccs_section['heading'] ); ?></summary>
											<div class="ccs-faq__a"><?php echo $ccs_section['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?></div>
										</details>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
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
