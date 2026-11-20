<?php
/**
 * The numbered accordion from the homepage's "What we do differently", for
 * any list of a heading and its text (Nov 2026): a number, the heading in
 * the heading face, a plus that turns to a minus, hairlines between, the
 * first one open. Native <details name>, so it works by keyboard and without
 * script. It replaces the boxed .ccs-faq rows on the pages redesigned to
 * match the homepage.
 *
 * Args: items (each: heading, body as HTML), name (the details group),
 * id (optional, for the filter field), class (optional), open (index to
 * open, default 0; -1 for none).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_na_items = isset( $args['items'] ) && is_array( $args['items'] ) ? array_values( $args['items'] ) : array();
if ( ! $ccs_na_items ) {
	return;
}
$ccs_na_name = isset( $args['name'] ) ? sanitize_html_class( $args['name'] ) : 'num-acc';
$ccs_na_open = isset( $args['open'] ) ? (int) $args['open'] : 0;
?>
<div class="num-acc<?php echo ! empty( $args['class'] ) ? ' ' . esc_attr( $args['class'] ) : ''; ?>"<?php echo ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>>
	<?php foreach ( $ccs_na_items as $ccs_na_i => $ccs_na_item ) : ?>
		<details class="num-acc__item" name="<?php echo esc_attr( $ccs_na_name ); ?>"<?php echo $ccs_na_open === $ccs_na_i ? ' open' : ''; ?><?php echo ! empty( $ccs_na_item['id'] ) ? ' id="' . esc_attr( $ccs_na_item['id'] ) . '"' : ''; ?>>
			<summary class="num-acc__q">
				<span class="num-acc__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $ccs_na_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<span class="num-acc__title"><?php echo esc_html( $ccs_na_item['heading'] ); ?></span>
			</summary>
			<div class="num-acc__a"><?php echo $ccs_na_item['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output or markup built and escaped by the caller. ?></div>
		</details>
	<?php endforeach; ?>
</div>
