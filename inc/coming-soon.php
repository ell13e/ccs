<?php
/**
 * "Coming soon" for a page that isn't ready (Nov 2026: Care guides, which
 * launch before their guides are approved).
 *
 * A tick-box in the page editor. While it's on:
 * - the page shows a short "being written" message in place of its content
 *   (template-care-guides.php), with the usual header and closing band;
 * - search engines are asked not to index it, and it leaves the sitemap,
 *   through the CCS SEO plugin's ccs_seo_noindex filter: a placeholder page
 *   is low-value content, and Google can only judge it if it can see it;
 * - the site's own search leaves it out, so the draft text can't be found.
 * Untick it when the content is ready and the page returns as it was.
 *
 * Offered on templates that know how to show the state (only Care guides).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CCS_COMING_SOON_META = '_ccs_coming_soon';

/**
 * Page templates that can show the "coming soon" state.
 *
 * @return string[]
 */
function ccs_coming_soon_templates() {
	return array( 'page-templates/template-care-guides.php' );
}

/**
 * Whether a page is set to "coming soon".
 *
 * @param int $post_id Page ID.
 * @return bool
 */
function ccs_is_coming_soon( $post_id ) {
	return $post_id
		&& in_array( (string) get_page_template_slug( $post_id ), ccs_coming_soon_templates(), true )
		&& '1' === get_post_meta( $post_id, CCS_COMING_SOON_META, true );
}

/**
 * The tick-box, beside Publish.
 */
function ccs_coming_soon_add_box() {
	add_meta_box( 'ccs_coming_soon', __( 'Coming soon', 'ccs-wp-theme' ), 'ccs_coming_soon_box', 'page', 'side', 'high' );
}
add_action( 'add_meta_boxes_page', 'ccs_coming_soon_add_box' );

/**
 * Box contents.
 *
 * @param WP_Post $post Page.
 */
function ccs_coming_soon_box( $post ) {
	if ( ! in_array( (string) get_page_template_slug( $post ), ccs_coming_soon_templates(), true ) ) {
		echo '<p class="description">' . esc_html__( 'Only the Care guides page can be shown as "coming soon".', 'ccs-wp-theme' ) . '</p>';
		return;
	}
	wp_nonce_field( 'ccs_coming_soon', 'ccs_coming_soon_nonce' );
	?>
	<label>
		<input type="checkbox" name="ccs_coming_soon" value="1" <?php checked( get_post_meta( $post->ID, CCS_COMING_SOON_META, true ), '1' ); ?>>
		<?php esc_html_e( 'Show this page as "coming soon"', 'ccs-wp-theme' ); ?>
	</label>
	<p class="description"><?php esc_html_e( 'Visitors see a short "being written" message instead of the content below, and search engines are asked to leave the page out until you untick this.', 'ccs-wp-theme' ); ?></p>
	<?php
}

/**
 * Save the tick-box.
 *
 * @param int $post_id Page ID.
 */
function ccs_coming_soon_save( $post_id ) {
	if ( ! isset( $_POST['ccs_coming_soon_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccs_coming_soon_nonce'] ) ), 'ccs_coming_soon' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, CCS_COMING_SOON_META, isset( $_POST['ccs_coming_soon'] ) ? '1' : '' );
}
add_action( 'save_post_page', 'ccs_coming_soon_save' );

/**
 * Hidden from search engines while "coming soon" (the SEO plugin applies it to
 * the robots tag, the sitemap and its SEO check).
 *
 * @param bool $noindex Whether hidden already.
 * @param int  $post_id Post ID.
 * @return bool
 */
function ccs_coming_soon_noindex( $noindex, $post_id ) {
	return $noindex || ccs_is_coming_soon( (int) $post_id );
}
add_filter( 'ccs_seo_noindex', 'ccs_coming_soon_noindex', 10, 2 );

/**
 * Leave "coming soon" pages out of the sitemap.
 *
 * @param int[]  $ids       IDs left out.
 * @param string $post_type Post type.
 * @return int[]
 */
function ccs_coming_soon_sitemap( $ids, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $ids;
	}
	return array_merge( (array) $ids, ccs_coming_soon_ids() );
}
add_filter( 'ccs_sitemap_exclude_post_ids', 'ccs_coming_soon_sitemap', 10, 2 );

/**
 * IDs of published pages set to "coming soon".
 *
 * @return int[]
 */
function ccs_coming_soon_ids() {
	$ids = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => 20,
			'meta_key'       => CCS_COMING_SOON_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- a handful of pages.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	return array_values( array_filter( array_map( 'intval', $ids ), 'ccs_is_coming_soon' ) );
}

/**
 * Leave "coming soon" pages out of the site's own search.
 *
 * @param WP_Query $query Query.
 */
function ccs_coming_soon_search( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$ids = ccs_coming_soon_ids();
	if ( $ids ) {
		$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $ids ) );
	}
}
add_action( 'pre_get_posts', 'ccs_coming_soon_search' );

/**
 * One-time update: Care guides launch as "coming soon" (client, 27 Nov 2026),
 * until the guides are approved.
 */
function ccs_care_guides_coming_soon_2026_11() {
	if ( get_option( 'ccs_care_guides_coming_soon_2026_11' ) ) {
		return;
	}
	$page = get_page_by_path( 'resources/care-guides' );
	if ( $page instanceof WP_Post ) {
		update_post_meta( $page->ID, CCS_COMING_SOON_META, '1' );
		update_option( 'ccs_care_guides_coming_soon_2026_11', '1', false );
	}
}

/**
 * The "coming soon" section: one sentence, then the pages that help now, as
 * link cards (the Careers page's "explore" pattern, BUILD-STANDARD §3: each
 * card is a link to another page, so a box is right, CCS/CLAUDE.md rule 1).
 */
function ccs_coming_soon_render() {
	$cards = array();
	$faqs  = get_page_by_path( 'resources/faqs' );
	if ( $faqs instanceof WP_Post && 'publish' === $faqs->post_status ) {
		$cards[] = array(
			'post' => $faqs,
			'icon' => 'question',
			'text' => __( 'Short answers on costs, carers and choosing a provider.', 'ccs-wp-theme' ),
			'cue'  => __( 'Read the questions', 'ccs-wp-theme' ),
		);
	}
	$costs = get_page_by_path( 'costs-and-funding' );
	if ( $costs instanceof WP_Post && 'publish' === $costs->post_status ) {
		$cards[] = array(
			'post' => $costs,
			'icon' => 'home',
			'text' => __( 'Our rates, and the ways care at home can be paid for.', 'ccs-wp-theme' ),
			'cue'  => __( 'See costs and funding', 'ccs-wp-theme' ),
		);
	}
	$finder = function_exists( 'ccs_care_finder_url' ) && '' !== ccs_care_finder_url() ? get_page_by_path( 'resources/' . CCS_CARE_FINDER_SLUG ) : null;
	if ( $finder instanceof WP_Post ) {
		$cards[] = array(
			'post' => $finder,
			'icon' => 'search',
			'text' => __( 'A few questions that point you to the kind of care that fits.', 'ccs-wp-theme' ),
			'cue'  => __( 'Find the right care', 'ccs-wp-theme' ),
		);
	}
	?>
	<section class="ccs-section ccs-section--sm ccs-section--flush-bottom coming-soon" aria-labelledby="coming-soon-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="coming-soon-heading" class="ccs-title"><?php esc_html_e( 'Our guides are being written', 'ccs-wp-theme' ); ?></h2>
				<p class="ccs-lede"><?php esc_html_e( 'Until they are ready, these pages answer the questions families ask us most.', 'ccs-wp-theme' ); ?></p>
			</header>
			<?php if ( $cards ) : ?>
				<ul class="info-grid" role="list">
					<?php foreach ( $cards as $card ) : ?>
						<li class="info-card">
							<span class="ccs-badge"><?php echo ccs_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<h3 class="info-card__title">
								<a class="info-card__link" href="<?php echo esc_url( get_permalink( $card['post'] ) ); ?>"><?php echo esc_html( get_the_title( $card['post'] ) ); ?></a>
							</h3>
							<p class="info-card__text"><?php echo esc_html( $card['text'] ); ?></p>
							<span class="info-card__cue" aria-hidden="true"><?php echo esc_html( $card['cue'] ); ?> <span class="info-card__arrow">&rarr;</span></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
