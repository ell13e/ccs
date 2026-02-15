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
	if ( $post instanceof WP_Post && $post->post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post ) );
		foreach ( $ancestors as $aid ) {
			$p = get_post( $aid );
			if ( $p instanceof WP_Post ) {
				$items[] = array( 'label' => get_the_title( $p ), 'url' => get_permalink( $p ) );
			}
		}
	}
	$items[] = array( 'label' => get_the_title(), 'url' => '' );
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
