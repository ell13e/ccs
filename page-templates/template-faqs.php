<?php
/**
 * Template Name: FAQs
 *
 * Questions on the right as a native <details> accordion, a sticky section head
 * on the left with the page intro and a way to ask something that is not
 * listed: the ElderlyCare X FAQ layout, in the section system.
 *
 * Questions come from the page's own content, so editors keep writing an
 * ordinary page: every h2 (or h3) is a question and everything up to the next
 * heading is its answer (ccs_content_sections(), inc/content-sections.php).
 * Anything before the first heading is the intro. If a
 * page has no headings at all it falls back to plain prose, so nothing an
 * editor writes can disappear. inc/seo/class-structured-data.php reads the same
 * h2 + paragraph pairs for the FAQPage schema.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone     = get_theme_mod( 'ccs_phone', '01622 809 881' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
$ccs_contact   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
?>

<main id="main" class="site-main site-main--faqs" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_html  = apply_filters( 'the_content', get_the_content() );
		$ccs_split = ccs_content_sections( $ccs_html );
		$ccs_intro = $ccs_split['intro'];
		$ccs_faqs  = $ccs_split['sections'];
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<section class="ccs-section ccs-section--flush-bottom faq-section" aria-labelledby="faq-heading">
			<div class="container container--lg">
				<div class="ccs-split faq-split">

					<header class="ccs-head">
						<h2 id="faq-heading" class="ccs-title">
							<?php
							echo ccs_copy_accent( 'faqs_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
							?>
						</h2>
						<?php if ( trim( wp_strip_all_tags( $ccs_intro ) ) !== '' ) : ?>
							<div class="ccs-lede ccs-lede--prose"><?php echo wp_kses_post( $ccs_intro ); ?></div>
						<?php endif; ?>

						<?php if ( $ccs_phone_tel ) : ?>
							<div class="faq-ask">
								<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<div>
									<p class="faq-ask__title"><?php echo esc_html( ccs_copy( 'faqs_box_title' ) ); ?></p>
									<p class="faq-ask__text">
										<?php
										printf(
											/* translators: 1: phone link, 2: contact page link. */
											esc_html__( 'Call %1$s or %2$s.', 'ccs-wp-theme' ),
											'<a href="' . esc_url( 'tel:' . $ccs_phone_tel ) . '">' . esc_html( $ccs_phone ) . '</a>',
											'<a href="' . esc_url( $ccs_contact ) . '">' . esc_html__( 'send us a message', 'ccs-wp-theme' ) . '</a>'
										);
										?>
									</p>
								</div>
							</div>
						<?php endif; ?>
					</header>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
						<?php if ( ! empty( $ccs_faqs ) ) : ?>
							<div class="ccs-faq">
								<?php foreach ( $ccs_faqs as $ccs_i => $ccs_faq ) : ?>
									<details class="ccs-faq__item" name="faqs"<?php echo 0 === $ccs_i ? ' open' : ''; ?>>
										<summary class="ccs-faq__q"><?php echo esc_html( $ccs_faq['heading'] ); ?></summary>
										<div class="ccs-faq__a"><?php echo $ccs_faq['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?></div>
									</details>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<div class="page-body page-body--flush entry-content">
								<?php echo $ccs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?>
							</div>
						<?php endif; ?>
					</article>

				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
