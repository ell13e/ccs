<?php
/**
 * A page's sections as the site's ordinary accordion (.ccs-faq, native
 * <details>, one open at a time): the same component as the FAQs page, the
 * switching page and "Good to know" on Getting started.
 *
 * Sixth form of this section (Nov 2026). Rejected here: tabs, a
 * sticky-heading scroll list, photo rows, a large-type index, and the
 * stepper ("don't like this for this": short sections left its card half
 * empty, and these are topics, not steps in order). The accordion is the
 * plainest thing in the kit and the one already used for this kind of
 * content everywhere else (rule 18).
 *
 * The client's words are untouched: each editor h2 is one entry.
 *
 * Args: id (unique prefix), title (the section's h2), sections (heading +
 * body pairs from ccs_content_sections()).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_tp_sections = isset( $args['sections'] ) && is_array( $args['sections'] ) ? array_values( $args['sections'] ) : array();
if ( empty( $ccs_tp_sections ) ) {
	return;
}
$ccs_tp_id = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : 'topics';
?>
<header class="ccs-head ccs-head--center">
	<h2 id="<?php echo esc_attr( $ccs_tp_id ); ?>-topics-heading" class="ccs-title"><?php echo esc_html( $args['title'] ); ?></h2>
</header>
<div class="ccs-faq careers-topics__faq">
	<?php foreach ( $ccs_tp_sections as $ccs_tp_i => $ccs_tp_section ) : ?>
		<details class="ccs-faq__item" name="<?php echo esc_attr( $ccs_tp_id ); ?>-topics"<?php echo 0 === $ccs_tp_i ? ' open' : ''; ?>>
			<summary class="ccs-faq__q"><?php echo esc_html( $ccs_tp_section['heading'] ); ?></summary>
			<div class="ccs-faq__a"><?php echo $ccs_tp_section['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?></div>
		</details>
	<?php endforeach; ?>
</div>
