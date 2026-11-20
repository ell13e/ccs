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
		<?php
		/*
		 * Redesigned to match the homepage (Nov 2026). The title-left split
		 * with boxed rows was "hated". Now: the standard header, a field
		 * that narrows the questions as you type (the help-centre pattern from
		 * the Pinterest research), the questions as the homepage's numbered
		 * accordion in one centred column, and "Question not here?" as the
		 * homepage's prompt card with the phone as its button.
		 */
		get_template_part( 'template-parts/page-header' );
		?>

		<section class="ccs-section faq-section" aria-labelledby="faq-heading">
			<div class="container container--lg">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
					<?php if ( ! empty( $ccs_faqs ) ) : ?>
						<header class="ccs-head ccs-head--center">
							<h2 id="faq-heading" class="ccs-title">
								<?php
								echo ccs_copy_accent( 'faqs_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
								?>
							</h2>
							<?php if ( trim( wp_strip_all_tags( $ccs_intro ) ) !== '' ) : ?>
								<div class="ccs-lede"><?php echo wp_kses_post( $ccs_intro ); ?></div>
							<?php endif; ?>
						</header>
						<div class="hub__search faq-find" role="search">
							<label class="screen-reader-text" for="faq-search"><?php esc_html_e( 'Search the questions', 'ccs-wp-theme' ); ?></label>
							<span class="hub__search-icon" aria-hidden="true"><?php echo ccs_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<input type="search" id="faq-search" class="hub__search-input" placeholder="<?php esc_attr_e( 'Search the questions', 'ccs-wp-theme' ); ?>" autocomplete="off" data-ccs-faq-search>
						</div>
						<?php
						/*
						 * Topics down the left, that topic's questions on the right
						 * (Nov 2026, from a reference the client sent: a rail of
						 * categories beside one accordion). The page's own fourteen
						 * questions are the first topic; the hundred more
						 * (inc/data/more-faqs.json, the client's text) follow, one
						 * topic per kind of care. On a phone the rail is a single
						 * "Topic" menu. Without script every topic shows in turn
						 * under its name and the rail is a list of links to them.
						 * The search looks through every topic. Each service page
						 * links to its own topic here by its id.
						 */
						$ccs_faq_items = array();
						foreach ( $ccs_faqs as $ccs_faq_n => $ccs_faq ) {
							// An id each, so another page can link straight to a question.
							$ccs_faq_items[] = array( 'heading' => $ccs_faq['heading'], 'body' => ccs_mark_link_lines( $ccs_faq['body'] ), 'id' => 'faq-' . ( $ccs_faq_n + 1 ) );
						}
						$ccs_topics = array(
							array( 'id' => 'most-asked', 'label' => __( 'Most asked', 'ccs-wp-theme' ), 'items' => $ccs_faq_items, 'links' => array(), 'list_id' => 'faq-list' ),
						);
						$ccs_more   = function_exists( 'ccs_more_faqs' ) ? ccs_more_faqs() : array( 'groups' => array() );
						foreach ( $ccs_more['groups'] as $ccs_more_group ) {
							$ccs_more_items = array();
							foreach ( $ccs_more_group['items'] as $ccs_more_item ) {
								$ccs_more_items[] = array(
									'heading' => $ccs_more_item['q'],
									'body'    => '<p>' . esc_html( $ccs_more_item['a'] ) . '</p>',
									'id'      => 'faq-more-' . (int) $ccs_more_item['n'],
								);
							}
							$ccs_topics[] = array( 'id' => $ccs_more_group['id'], 'label' => $ccs_more_group['heading'], 'items' => $ccs_more_items, 'links' => isset( $ccs_more_group['links'] ) ? $ccs_more_group['links'] : array(), 'list_id' => '' );
						}
						?>
						<div class="faq-browse" data-ccs-faq-browse>
							<nav class="faq-rail" aria-label="<?php esc_attr_e( 'Question topics', 'ccs-wp-theme' ); ?>">
								<div class="faq-rail__pick">
									<label class="faq-rail__pick-label" for="faq-topic"><?php esc_html_e( 'Topic', 'ccs-wp-theme' ); ?></label>
									<select id="faq-topic" class="faq-rail__select" data-ccs-faq-pick>
										<?php foreach ( $ccs_topics as $ccs_topic ) : ?>
											<option value="<?php echo esc_attr( $ccs_topic['id'] ); ?>"><?php echo esc_html( $ccs_topic['label'] ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<ul class="faq-rail__list" role="list">
									<?php foreach ( $ccs_topics as $ccs_topic_n => $ccs_topic ) : ?>
										<li><a class="faq-rail__link" href="#<?php echo esc_attr( $ccs_topic['id'] ); ?>"<?php echo 0 === $ccs_topic_n ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $ccs_topic['label'] ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</nav>
							<div class="faq-panels" id="faq-panels">
								<?php foreach ( $ccs_topics as $ccs_topic_n => $ccs_topic ) : ?>
									<section class="faq-panel<?php echo 0 === $ccs_topic_n ? ' is-current' : ''; ?>" id="<?php echo esc_attr( $ccs_topic['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $ccs_topic['id'] ); ?>-title">
										<h3 class="faq-panel__title" id="<?php echo esc_attr( $ccs_topic['id'] ); ?>-title"><?php echo esc_html( $ccs_topic['label'] ); ?></h3>
										<?php get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_topic['items'], 'name' => 'faqs-' . $ccs_topic['id'], 'id' => $ccs_topic['list_id'] ) ); ?>
										<?php if ( $ccs_topic['links'] ) : ?>
											<div class="faq-group__links">
												<p class="faq-group__links-title"><?php esc_html_e( 'Further reading', 'ccs-wp-theme' ); ?></p>
												<ul role="list">
													<?php foreach ( $ccs_topic['links'] as $ccs_more_link ) : ?>
														<li><a href="<?php echo esc_url( $ccs_more_link['url'] ); ?>"><?php echo esc_html( $ccs_more_link['label'] ); ?></a></li>
													<?php endforeach; ?>
												</ul>
											</div>
										<?php endif; ?>
									</section>
								<?php endforeach; ?>
								<?php if ( ! empty( $ccs_more['intro'] ) ) : ?>
									<p class="faq-panels__note"><?php echo esc_html( $ccs_more['intro'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
						<p class="num-acc__empty" data-ccs-faq-empty hidden><?php esc_html_e( 'No question matches that. Try another word, or ask us below.', 'ccs-wp-theme' ); ?></p>
					<?php else : ?>
						<h2 id="faq-heading" class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></h2>
						<div class="page-body page-body--flush entry-content">
							<?php echo $ccs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?>
						</div>
					<?php endif; ?>
				</article>
			</div>
		</section>

		<?php if ( $ccs_phone_tel ) : ?>
			<section class="ccs-section ccs-section--sm ccs-section--tint faq-ask-section">
				<div class="container container--lg">
					<div class="prompt-card has-doodle">
						<?php echo ccs_doodle( 'speech-bubble', 'doodle--corner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
						<span class="ccs-badge" aria-hidden="true"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<div>
							<h2 class="prompt-card__title"><?php echo esc_html( ccs_copy( 'faqs_box_title' ) ); ?></h2>
							<p class="prompt-card__text">
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
						<div class="prompt-card__actions">
							<a class="btn btn-primary" href="<?php echo esc_url( $ccs_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
						</div>
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
