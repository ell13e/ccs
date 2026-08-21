<?php
/**
 * Breadcrumb navigation for inner pages.
 * Outputs: Home > Parent (if any) > Current page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array();

// Home.
$items[] = array(
	'label' => __( 'Home', 'ccs-wp-theme' ),
	'url'   => home_url( '/' ),
);

if ( is_singular() ) {
	$post = get_queried_object();
	/*
	 * The "Home" page is the site's front page, so including it as an ancestor
	 * rendered "Home › Home › …" on every page nested under it. Skip whichever
	 * page is set as the front page.
	 */
	$front_id = (int) get_option( 'page_on_front' );

	/*
	 * CPT singles have no page ancestry, so give them their hub page as a crumb.
	 * Services point at the hub page rather than the CPT archive — that archive
	 * is deliberately disabled (it duplicated the hub).
	 */
	if ( $post instanceof WP_Post && ! $post->post_parent ) {
		if ( 'service' === $post->post_type && function_exists( 'ccs_page_url' ) ) {
			$hub = ccs_page_url( 'home-care-services-kent' );
			if ( $hub ) {
				$items[] = array( 'label' => __( 'Our care services', 'ccs-wp-theme' ), 'url' => $hub );
			}
		} elseif ( 'location' === $post->post_type ) {
			$areas = get_post_type_archive_link( 'location' );
			if ( $areas ) {
				$items[] = array( 'label' => __( 'Areas we cover', 'ccs-wp-theme' ), 'url' => $areas );
			}
		}
	}

	if ( $post instanceof WP_Post && $post->post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post ) );
		foreach ( $ancestors as $aid ) {
			if ( $front_id && (int) $aid === $front_id ) {
				continue;
			}
			$p = get_post( $aid );
			if ( $p instanceof WP_Post ) {
				$items[] = array( 'label' => get_the_title( $p ), 'url' => get_permalink( $p ) );
			}
		}
	}
	/*
	 * Prefer the friendly heading over the raw post title — several titles are
	 * keyword strings ("Home Care Services Kent") that read badly as a crumb.
	 */
	$crumb_label = get_post_meta( get_the_ID(), 'ccs_page_heading', true );
	$crumb_label = ( is_string( $crumb_label ) && trim( $crumb_label ) !== '' ) ? trim( $crumb_label ) : get_the_title();
	$items[]     = array( 'label' => $crumb_label, 'url' => '' );
} elseif ( is_home() ) {
	// Blog index (the assigned Posts page) isn't is_singular(), so it needs its
	// own case or it fell through with only "Home" and rendered nothing.
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$label         = $posts_page_id ? get_post_meta( $posts_page_id, 'ccs_page_heading', true ) : '';
	$label         = ( is_string( $label ) && trim( $label ) !== '' )
		? trim( $label )
		: ( $posts_page_id ? get_the_title( $posts_page_id ) : __( 'News & Updates', 'ccs-wp-theme' ) );
	$items[]       = array( 'label' => $label, 'url' => '' );
} elseif ( is_category() ) {
	$cat = get_queried_object();
	$items[] = array( 'label' => single_cat_title( '', false ), 'url' => '' );
} elseif ( is_search() ) {
	$items[] = array( 'label' => sprintf( __( 'Search results for: %s', 'ccs-wp-theme' ), get_search_query() ), 'url' => '' );
} elseif ( is_404() ) {
	$items[] = array( 'label' => __( 'Page not found', 'ccs-wp-theme' ), 'url' => '' );
} elseif ( is_archive() ) {
	$items[] = array( 'label' => wp_strip_all_tags( get_the_archive_title() ), 'url' => '' );
}

if ( count( $items ) < 2 ) {
	return;
}
?>

<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ccs-wp-theme' ); ?>">
	<ol class="breadcrumb__list">
		<?php foreach ( $items as $i => $item ) : ?>
			<li class="breadcrumb__item">
				<?php if ( $item['url'] !== '' && $i < count( $items ) - 1 ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" class="breadcrumb__link"><?php echo esc_html( $item['label'] ); ?></a>
					<span class="breadcrumb__sep" aria-hidden="true"><?php echo esc_html( apply_filters( 'ccs_breadcrumb_separator', '›' ) ); ?></span>
				<?php else : ?>
					<span class="breadcrumb__current" aria-current="page"><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
