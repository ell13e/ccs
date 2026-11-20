<?php
/**
 * Template Name: Care Guides
 *
 * Lists ccs_resource posts as cards; each card has a "Get this care guide" button
 * that opens the resource download modal (lead capture). Modal and JS enqueued in theme.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--care-guides" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php
		/*
		 * Third form (Nov 2026). A boxed accordion, then a reader card with a
		 * rail of parts, both missed; the client pointed to Age UK's "Home and
		 * care guides" page: a side menu of the section's pages, an opening
		 * paragraph, and a two-column grid of guides, each a picture, a title,
		 * a short description and a link. Structure only; CCS's own look.
		 *
		 * Each heading in the page's own content is one guide, and its card
		 * opens the guide in place (see below). No script is needed. A page with
		 * fewer than three headings prints as an ordinary long-form page.
		 */
		$ccs_cg         = trim( get_the_content() ) !== '' ? ccs_content_sections( apply_filters( 'the_content', get_the_content() ) ) : array( 'intro' => '', 'sections' => array() );
		$ccs_cg_reader  = count( $ccs_cg['sections'] ) >= 3;
		$ccs_cg_count   = count( $ccs_cg['sections'] );
		// The page's own opening sentence is the header's line, as on Resources.
		// As a loose paragraph above the cards it "looks a bit random" (client).
		// When a single guide is open the header keeps its usual line.
		$ccs_cg_head = array();
		$ccs_cg_lead = trim( wp_strip_all_tags( $ccs_cg['intro'] ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
		if ( $ccs_cg_reader && '' !== $ccs_cg_lead && empty( $_GET['guide'] ) && preg_match( '/^.+?[.!?](?=\s|$)/u', $ccs_cg_lead, $ccs_cg_lead_m ) ) {
			$ccs_cg_head['intro'] = $ccs_cg_lead_m[0];
		}
		get_template_part( 'template-parts/page-header', null, $ccs_cg_head );

		// The side menu: this page's parent and the pages beside it.
		$ccs_cg_parent = wp_get_post_parent_id( get_the_ID() );
		$ccs_cg_nav    = $ccs_cg_parent ? get_pages( array( 'parent' => $ccs_cg_parent, 'sort_column' => 'menu_order,post_title' ) ) : array();

		// A picture for each guide: an icon from the set, chosen by what the heading is about.
		$ccs_cg_icon = static function ( $heading, $n ) {
			$map = array(
				'pay|cost|fund'       => 'badge',
				'hospital|discharge'  => 'pulse',
				'wrong|complain'      => 'shield',
				'plan'                => 'clock',
				'question|ask'        => 'people',
				'kind|type|available' => 'heart',
				'help|need|sign'      => 'home',
			);
			foreach ( $map as $pattern => $icon ) {
				if ( preg_match( '/' . $pattern . '/i', $heading ) ) {
					return $icon;
				}
			}
			$spare = array( 'heart', 'home', 'cup', 'moon' );
			return $spare[ $n % count( $spare ) ];
		};
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php if ( $ccs_cg_reader ) : ?>
				<div class="ccs-section ccs-section--flush-bottom care-guide" id="guides">
					<div class="container container--lg">
						<?php
						/*
						 * Fifth form (Nov 2026). The client disliked the rows that
						 * opened and sent a reference: a grid of resource cards, each
						 * a picture tile over a title, a line and a link. So the
						 * guides are the site's own cards (.care-card, as on the
						 * services page), each with a drawing from the client's packs
						 * on its tile, its heading, its opening sentence and "Read
						 * the guide". A card leads to that guide set out on its own
						 * (this page with ?guide=its-name): a real address, nothing
						 * opens or jumps, and "All care guides" leads back.
						 *
						 * Still one page of editor content: every h2 is a guide.
						 */
						$ccs_cg_art = static function ( $heading, $n ) {
							$map = array(
								'pay|cost|fund'       => 'folder',
								'hospital|discharge'  => 'stethoscope',
								'wrong|complain'      => 'umbrella',
								'plan'                => 'checklist',
								'question|ask'        => 'speech-bubble',
								'kind|type|available' => 'armchair',
								'help|need|sign'      => 'lightbulb',
							);
							foreach ( $map as $pattern => $doodle ) {
								if ( preg_match( '/' . $pattern . '/i', $heading ) ) {
									return $doodle;
								}
							}
							$spare = array( 'books', 'tea-cup', 'notebook', 'letter' );
							return $spare[ $n % count( $spare ) ];
						};
						$ccs_cg_guides = array();
						foreach ( $ccs_cg['sections'] as $ccs_cg_i => $ccs_cg_section ) {
							$ccs_cg_plain = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $ccs_cg_section['body'] ) ) );
							// The guide's opening sentence, or its first 150 characters.
							// It stops at a colon too (a sentence that introduces a list), which then ends in a full stop.
							$ccs_cg_first = preg_match( '/^(.{40,170}?)([.?!:])(\s|$)/u', $ccs_cg_plain, $ccs_cg_m ) ? $ccs_cg_m[1] . ( ':' === $ccs_cg_m[2] ? '.' : $ccs_cg_m[2] ) : wp_html_excerpt( $ccs_cg_plain, 150, '…' );
							$ccs_cg_guides[ sanitize_title( $ccs_cg_section['heading'] ) ] = array(
								'heading' => $ccs_cg_section['heading'],
								'body'    => $ccs_cg_section['body'],
								'first'   => $ccs_cg_first,
								'art'     => $ccs_cg_art( $ccs_cg_section['heading'], $ccs_cg_i ),
							);
						}
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- a read-only choice of which guide to show.
						$ccs_cg_slug  = isset( $_GET['guide'] ) ? sanitize_title( wp_unslash( $_GET['guide'] ) ) : '';
						$ccs_cg_open  = isset( $ccs_cg_guides[ $ccs_cg_slug ] ) ? $ccs_cg_guides[ $ccs_cg_slug ] : null;
						$ccs_cg_index = get_permalink() . '#guides';
						?>
						<?php if ( $ccs_cg_open ) : ?>
							<div class="guide-read">
								<p class="guide-read__back"><a class="btn btn-quiet" href="<?php echo esc_url( $ccs_cg_index ); ?>"><span aria-hidden="true">&larr;</span> <?php esc_html_e( 'All care guides', 'ccs-wp-theme' ); ?></a></p>
								<h2 class="ccs-title guide-read__title"><?php echo esc_html( $ccs_cg_open['heading'] ); ?></h2>
								<div class="guide-read__body entry-content"><?php echo wp_kses_post( ccs_mark_link_lines( $ccs_cg_open['body'] ) ); ?></div>
								<p class="guide-read__back guide-read__back--end"><a class="btn btn-quiet" href="<?php echo esc_url( $ccs_cg_index ); ?>"><span aria-hidden="true">&larr;</span> <?php esc_html_e( 'All care guides', 'ccs-wp-theme' ); ?></a></p>
							</div>
						<?php else : ?>
							<ul class="care-grid guide-grid" role="list">
								<?php foreach ( $ccs_cg_guides as $ccs_cg_key => $ccs_cg_guide ) : ?>
									<li class="care-card guide-tile">
										<div class="guide-tile__art" aria-hidden="true" data-ccs-exception="rule 1: the card's picture; the whole card is the link"><?php echo ccs_doodle( $ccs_cg_guide['art'], 'guide-tile__doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?></div>
										<div class="care-card__body">
											<h2 class="care-card__title"><a class="care-card__link" href="<?php echo esc_url( add_query_arg( 'guide', $ccs_cg_key, get_permalink() ) . '#guides' ); ?>"><?php echo esc_html( $ccs_cg_guide['heading'] ); ?></a></h2>
											<p class="care-card__text"><?php echo esc_html( $ccs_cg_guide['first'] ); ?></p>
											<span class="btn btn-quiet care-card__cue" aria-hidden="true"><?php esc_html_e( 'Read the guide', 'ccs-wp-theme' ); ?> <span>&rarr;</span></span>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			<?php elseif ( trim( get_the_content() ) !== '' ) : ?>
				<div class="ccs-section"><?php get_template_part( 'template-parts/article-body' ); ?></div>
			<?php endif; ?>

			<?php
			$resources = get_posts(
				array(
					'post_type'      => 'ccs_resource',
					'post_status'    => 'publish',
					'numberposts'    => -1,
					'orderby'        => 'menu_order title',
					'order'          => 'ASC',
				)
			);
			if ( ! empty( $resources ) ) :
				?>
				<div class="care-guides-grid-wrap">
					<div class="container container--lg">
						<ul class="care-guides-grid" aria-label="<?php esc_attr_e( 'Care guides', 'ccs-wp-theme' ); ?>">
							<?php
							foreach ( $resources as $resource ) {
								$id    = $resource->ID;
								$title = get_the_title( $id );
								$excerpt = has_excerpt( $id ) ? get_the_excerpt( $id ) : '';
								?>
								<li class="care-guide-card">
									<?php if ( get_the_post_thumbnail( $id, 'medium' ) ) : ?>
										<div class="care-guide-card__image">
											<?php echo get_the_post_thumbnail( $id, 'medium' ); ?>
										</div>
									<?php endif; ?>
									<div class="care-guide-card__body">
										<h2 class="care-guide-card__title"><?php echo esc_html( $title ); ?></h2>
										<?php if ( $excerpt ) : ?>
											<p class="care-guide-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
										<?php endif; ?>
										<button type="button" class="btn btn-primary resource-download-btn" data-resource-id="<?php echo esc_attr( (string) $id ); ?>" data-resource-name="<?php echo esc_attr( $title ); ?>">
											<?php esc_html_e( 'Get this care guide', 'ccs-wp-theme' ); ?>
										</button>
									</div>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
		</article>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
