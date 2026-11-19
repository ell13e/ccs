<?php
/**
 * Resources page body (Nov 2026).
 *
 * This page was a column of prose with an "On this page" rail: a design
 * review rated it the weakest on the site (nothing to look at). It is a hub,
 * so it is now laid out as one: one photo, and beside it the page's own text
 * as rows a family can scan and tap. Structure after the rule-divided link
 * lists seen in the Pinterest and Dribbble research (name, one line, arrow).
 *
 * Nothing is written here. The rows are read from the editor content:
 * - a paragraph that opens with a link becomes a row: the link is the row's
 *   name, the rest of the sentence its line (first letter capitalised);
 * - any other paragraph stays as it was written, under its heading;
 * - a closing section with no such paragraph ("Still stuck?") becomes the
 *   site's strip.
 * So the client can add, remove or reword entries in the editor as before.
 *
 * Must be called inside the loop.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_rh        = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
$ccs_rh_groups = array();
$ccs_rh_strip  = null;
$ccs_rh_count  = count( $ccs_rh['sections'] );

foreach ( $ccs_rh['sections'] as $ccs_rh_i => $ccs_rh_section ) {
	$ccs_rh_rows  = array();
	$ccs_rh_prose = '';
	if ( preg_match_all( '#<p[^>]*>(.*?)</p>#is', $ccs_rh_section['body'], $ccs_rh_ps ) ) {
		foreach ( $ccs_rh_ps[1] as $ccs_rh_p ) {
			if ( preg_match( '#^\s*<a\s[^>]*href="([^"]+)"[^>]*>(.*?)</a>\s*(.*)$#is', $ccs_rh_p, $ccs_rh_m ) ) {
				$ccs_rh_line   = trim( wp_strip_all_tags( $ccs_rh_m[3] ) );
				$ccs_rh_rows[] = array(
					'url'   => $ccs_rh_m[1],
					'title' => trim( wp_strip_all_tags( $ccs_rh_m[2] ) ),
					'line'  => function_exists( 'mb_strtoupper' ) ? mb_strtoupper( mb_substr( $ccs_rh_line, 0, 1 ) ) . mb_substr( $ccs_rh_line, 1 ) : ucfirst( $ccs_rh_line ),
				);
			} else {
				$ccs_rh_prose .= '<p>' . $ccs_rh_p . '</p>';
			}
		}
	} else {
		$ccs_rh_prose = $ccs_rh_section['body'];
	}

	if ( ! $ccs_rh_rows && $ccs_rh_i === $ccs_rh_count - 1 && '' !== $ccs_rh_prose ) {
		$ccs_rh_strip = array(
			'title' => $ccs_rh_section['heading'],
			'text'  => preg_replace( '#</?p[^>]*>#i', ' ', $ccs_rh_prose ),
		);
		continue;
	}

	$ccs_rh_groups[] = array(
		'heading' => $ccs_rh_section['heading'],
		'prose'   => $ccs_rh_prose,
		'rows'    => $ccs_rh_rows,
	);
}
?>
<section class="ccs-section ccs-section--flush-bottom resources-hub">
	<div class="container container--lg">
		<div class="resources-hub__layout">

			<div class="resources-hub__main">
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_rh['intro'] ) ) ) : ?>
					<div class="ccs-lede ccs-lede--large resources-hub__lede">
						<?php echo wp_kses_post( $ccs_rh['intro'] ); ?>
					</div>
				<?php endif; ?>

				<?php foreach ( $ccs_rh_groups as $ccs_rh_group ) : ?>
					<div class="link-rows__group">
						<h2 class="link-rows__heading"><?php echo esc_html( $ccs_rh_group['heading'] ); ?></h2>
						<?php if ( '' !== $ccs_rh_group['prose'] ) : ?>
							<div class="link-rows__prose entry-content"><?php echo wp_kses_post( $ccs_rh_group['prose'] ); ?></div>
						<?php endif; ?>
						<?php if ( $ccs_rh_group['rows'] ) : ?>
							<ul class="link-rows" role="list">
								<?php foreach ( $ccs_rh_group['rows'] as $ccs_rh_row ) : ?>
									<li class="link-row">
										<h3 class="link-row__title"><a class="link-row__link" href="<?php echo esc_url( $ccs_rh_row['url'] ); ?>"><?php echo esc_html( $ccs_rh_row['title'] ); ?></a></h3>
										<?php if ( '' !== $ccs_rh_row['line'] ) : ?>
											<p class="link-row__line"><?php echo esc_html( $ccs_rh_row['line'] ); ?></p>
										<?php endif; ?>
										<span class="link-row__cue" aria-hidden="true">&rarr;</span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="resources-hub__media">
				<?php
				// A photo used nowhere else on the site (the review found photos repeated).
				ccs_section_photo(
					'site-photos/WhatsApp-Image-2025-05-21-at-10.51.44-3.webp',
					array(
						'alt'   => __( 'A woman sitting in a meadow of buttercups, holding a flower', 'ccs-wp-theme' ),
						'sizes' => '(min-width: 900px) 440px, 92vw',
						'class' => 'resources-hub__photo',
					)
				);
				?>
			</div>

		</div>

		<?php if ( $ccs_rh_strip ) : ?>
			<div class="ccs-strip resources-hub__strip">
				<span class="ccs-badge" aria-hidden="true"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<div class="ccs-strip__text">
					<h2 class="ccs-strip__title"><?php echo esc_html( $ccs_rh_strip['title'] ); ?></h2>
					<p class="ccs-strip__lede"><?php echo wp_kses_post( trim( $ccs_rh_strip['text'] ) ); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
