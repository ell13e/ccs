<?php
/**
 * Long-form page body: the editor's content in the page-body measure, with an
 * "On this page" contents rail beside it once a page has enough sections to
 * need one (four or more h2s: Care Guides, Working for Us, the policy pages).
 * Shorter pages render exactly as before.
 *
 * Must be called inside the loop. The rail is a plain list of in-page links,
 * sticky on wide screens and a compact block above the text on phones; no
 * script, so it cannot break.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_article = ccs_content_anchors( apply_filters( 'the_content', get_the_content() ) );
$ccs_has_toc = count( $ccs_article['toc'] ) >= 4;
?>
<div class="container container--lg">
	<?php if ( $ccs_has_toc ) : ?>
		<div class="article-layout">
			<nav class="article-toc" aria-labelledby="article-toc-heading">
				<p id="article-toc-heading" class="article-toc__title"><?php esc_html_e( 'On this page', 'ccs-wp-theme' ); ?></p>
				<ol class="article-toc__list" role="list">
					<?php foreach ( $ccs_article['toc'] as $ccs_toc_id => $ccs_toc_label ) : ?>
						<li><a class="article-toc__link" href="#<?php echo esc_attr( $ccs_toc_id ); ?>"><?php echo esc_html( $ccs_toc_label ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
			<div class="page-body page-body--flush entry-content article-layout__body">
				<?php echo $ccs_article['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output with heading ids added, printed as the_content() would. ?>
			</div>
		</div>
	<?php else : ?>
		<div class="page-body entry-content">
			<?php echo $ccs_article['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output with heading ids added, printed as the_content() would. ?>
		</div>
	<?php endif; ?>
</div>
