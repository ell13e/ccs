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
		get_template_part( 'template-parts/page-header' );

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
						 * Fourth form (Nov 2026). The guides were folded into the
						 * Resources hub, then given this page back when the client
						 * made Resources a signpost page. The cards that widened
						 * across a grid had a "weird looking jump", so each guide is
						 * now a full-width row that opens downwards: its heading
						 * never changes, nothing above it moves, and opening one
						 * does not close another.
						 */
						?>
						<div class="guides-read">
							<?php if ( '' !== trim( wp_strip_all_tags( $ccs_cg['intro'] ) ) ) : ?>
								<div class="hub__lede"><?php echo wp_kses_post( $ccs_cg['intro'] ); ?></div>
							<?php endif; ?>
							<ul class="hub__list" role="list">
								<?php foreach ( $ccs_cg['sections'] as $ccs_cg_i => $ccs_cg_section ) : ?>
									<li class="hub-item hub-item--read">
										<details class="hub-read" id="care-guide-panel-<?php echo (int) $ccs_cg_i; ?>">
											<summary class="hub-read__summary">
												<span class="hub-item__text">
													<span class="hub-item__title" role="heading" aria-level="2"><?php echo esc_html( $ccs_cg_section['heading'] ); ?></span>
												</span>
												<span class="hub-read__mark" aria-hidden="true"></span>
											</summary>
											<div class="hub-read__body entry-content"><?php echo wp_kses_post( ccs_mark_link_lines( $ccs_cg_section['body'] ) ); ?></div>
										</details>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
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
