<?php
/**
 * Template Name: Getting Started
 *
 * What happens between the first call and the first visit. The words are the
 * client's own page content; this template lays them out (Nov 2026 rebuild,
 * after the client said the page needed significant work: it was one 4,000px
 * column of text on a drawn rail).
 *
 * How the editor content is read (ccs_content_sections()):
 * - the paragraph before the first h2 is the introduction;
 * - h2s that start with a number ("1. The first conversation") are the
 *   steps, shown as numbered rows, number and title left, text right;
 * - every other h2 ("Paying for care", "Safeguarding…") is a question in a
 *   "Good to know" accordion, so the long reference sections are one tap away
 *   instead of all on screen.
 * The client edits the page as before; renumbering or adding an h2 just works.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--getting-started" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_gs_html  = apply_filters( 'the_content', get_the_content() );
		$ccs_gs       = function_exists( 'ccs_content_sections' ) ? ccs_content_sections( $ccs_gs_html ) : array( 'intro' => $ccs_gs_html, 'sections' => array() );
		$ccs_gs_steps = array();
		$ccs_gs_more  = array();

		foreach ( $ccs_gs['sections'] as $ccs_gs_section ) {
			if ( preg_match( '/^\s*(\d+)[.)]\s*(.+)$/u', $ccs_gs_section['heading'], $ccs_gs_m ) ) {
				$ccs_gs_steps[] = array(
					'num'   => $ccs_gs_m[1],
					'title' => $ccs_gs_m[2],
					'body'  => $ccs_gs_section['body'],
				);
			} else {
				$ccs_gs_more[] = $ccs_gs_section;
			}
		}
		?>

		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php if ( empty( $ccs_gs_steps ) ) : ?>
			<?php // No numbered steps (the client restructured the page): show it as a plain article. ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'ccs-section page-content' ); ?>>
				<div class="container container--lg">
					<div class="page-body entry-content">
						<?php echo $ccs_gs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?>
					</div>
				</div>
			</article>
		<?php else : ?>

			<section class="ccs-section gs-steps" aria-labelledby="gs-steps-heading">
				<div class="container container--lg">

					<header class="gs-intro">
						<h2 id="gs-steps-heading" class="ccs-title"><?php esc_html_e( 'From first call to first visit', 'ccs-wp-theme' ); ?></h2>
						<?php if ( '' !== trim( wp_strip_all_tags( $ccs_gs['intro'] ) ) ) : ?>
							<div class="ccs-lede gs-intro__lede">
								<?php echo wp_kses_post( $ccs_gs['intro'] ); ?>
							</div>
						<?php endif; ?>
					</header>

					<ol class="gs-step-list" role="list">
						<?php foreach ( $ccs_gs_steps as $ccs_gs_step ) : ?>
							<li class="gs-step">
								<div class="gs-step__head">
									<span class="gs-step__num" aria-hidden="true"><?php echo esc_html( str_pad( $ccs_gs_step['num'], 2, '0', STR_PAD_LEFT ) ); ?></span>
									<h3 class="gs-step__title"><?php echo esc_html( $ccs_gs_step['title'] ); ?></h3>
								</div>
								<div class="gs-step__body">
									<?php echo wp_kses_post( $ccs_gs_step['body'] ); ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>

				</div>
			</section>

			<?php if ( ! empty( $ccs_gs_more ) ) : ?>
				<section class="ccs-section ccs-section--tint gs-more" aria-labelledby="gs-more-heading">
					<div class="container container--lg">
						<div class="ccs-split">
							<header class="ccs-head">
								<h2 id="gs-more-heading" class="ccs-title"><?php esc_html_e( 'Good to know', 'ccs-wp-theme' ); ?></h2>
							</header>
							<div class="ccs-faq">
								<?php foreach ( $ccs_gs_more as $ccs_gs_i => $ccs_gs_item ) : ?>
									<details class="ccs-faq__item" name="getting-started"<?php echo 0 === $ccs_gs_i ? ' open' : ''; ?>>
										<summary class="ccs-faq__q"><?php echo esc_html( $ccs_gs_item['heading'] ); ?></summary>
										<div class="ccs-faq__a"><?php echo wp_kses_post( $ccs_gs_item['body'] ); ?></div>
									</details>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>

		<?php endif; ?>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
