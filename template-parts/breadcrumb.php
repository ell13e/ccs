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

$items = function_exists( 'ccs_breadcrumb_items' ) ? ccs_breadcrumb_items() : array();

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
