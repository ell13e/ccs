<?php
/**
 * Stepper: a sequence shown one item at a time (assets/js/interactive.js).
 *
 * A rail of tabs with a line that fills to the current item, the item's
 * number or year large, its text, and Back / Next. Without JavaScript every
 * item is listed in order. Built Nov 2026 for Getting started (the steps) and
 * reused for the company journey on About, so the two behave alike (rule 18).
 *
 * Args:
 * - id          string  Prefix for element ids, unique on the page.
 * - labelledby  string  Id of the heading that names the tab list.
 * - variant     string  '' (numbered steps) or 'years' (a timeline: the tab is
 *                       the year itself, as a pill, with no name under it).
 * - items       array   Each: tab (text in the dot), name (tab's name and the
 *                       Next label), num (the large numeral), title, body (HTML).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_st_items = isset( $args['items'] ) && is_array( $args['items'] ) ? array_values( $args['items'] ) : array();
if ( empty( $ccs_st_items ) ) {
	return;
}

$ccs_st_id      = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : 'stepper';
$ccs_st_label   = isset( $args['labelledby'] ) ? $args['labelledby'] : '';
$ccs_st_years   = isset( $args['variant'] ) && 'years' === $args['variant'];
$ccs_st_count   = count( $ccs_st_items );
$ccs_st_classes = 'ccs-stepper' . ( $ccs_st_years ? ' ccs-stepper--years' : '' );
?>
<div class="<?php echo esc_attr( $ccs_st_classes ); ?>" data-ccs-stepper style="--ccs-step-count: <?php echo (int) $ccs_st_count; ?>;">
	<div class="ccs-stepper__rail">
		<span class="ccs-stepper__progress" aria-hidden="true"></span>
		<div class="ccs-stepper__tabs" role="tablist"<?php echo $ccs_st_label ? ' aria-labelledby="' . esc_attr( $ccs_st_label ) . '"' : ''; ?>>
			<?php foreach ( $ccs_st_items as $ccs_st_i => $ccs_st_item ) : ?>
				<button type="button" class="ccs-stepper__tab" role="tab" id="<?php echo esc_attr( $ccs_st_id . '-tab-' . $ccs_st_i ); ?>" aria-controls="<?php echo esc_attr( $ccs_st_id . '-panel-' . $ccs_st_i ); ?>" aria-selected="<?php echo 0 === $ccs_st_i ? 'true' : 'false'; ?>">
					<span class="ccs-stepper__dot"<?php echo $ccs_st_years ? '' : ' aria-hidden="true"'; ?>><?php echo esc_html( $ccs_st_item['tab'] ); ?></span>
					<?php if ( ! $ccs_st_years ) : ?>
						<span class="ccs-stepper__name"><?php echo esc_html( $ccs_st_item['name'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="ccs-stepper__panels">
		<?php foreach ( $ccs_st_items as $ccs_st_i => $ccs_st_item ) : ?>
			<div class="ccs-stepper__panel" role="tabpanel" id="<?php echo esc_attr( $ccs_st_id . '-panel-' . $ccs_st_i ); ?>" aria-labelledby="<?php echo esc_attr( $ccs_st_id . '-tab-' . $ccs_st_i ); ?>" tabindex="0">
				<span class="ccs-stepper__num" aria-hidden="true"><?php echo esc_html( $ccs_st_item['num'] ); ?></span>
				<div class="ccs-stepper__text">
					<?php if ( ! $ccs_st_years ) : ?>
						<p class="ccs-stepper__count">
							<?php
							/* translators: 1: this step's position, 2: number of steps. */
							echo esc_html( sprintf( __( 'Step %1$d of %2$d', 'ccs-wp-theme' ), $ccs_st_i + 1, $ccs_st_count ) );
							?>
						</p>
					<?php endif; ?>
					<h3 class="ccs-stepper__title">
						<?php if ( $ccs_st_years ) : ?>
							<span class="screen-reader-text"><?php echo esc_html( $ccs_st_item['name'] ); ?>: </span>
						<?php endif; ?>
						<?php echo esc_html( $ccs_st_item['title'] ); ?>
					</h3>
					<div class="ccs-stepper__body"><?php echo wp_kses_post( $ccs_st_item['body'] ); ?></div>
					<div class="ccs-stepper__nav">
						<?php if ( $ccs_st_i > 0 ) : ?>
							<button type="button" class="btn btn-quiet ccs-stepper__go" data-ccs-step-go="<?php echo (int) ( $ccs_st_i - 1 ); ?>">
								<span aria-hidden="true">&larr;</span> <?php esc_html_e( 'Back', 'ccs-wp-theme' ); ?>
							</button>
						<?php endif; ?>
						<?php if ( $ccs_st_i < $ccs_st_count - 1 ) : ?>
							<button type="button" class="btn btn-quiet ccs-stepper__go" data-ccs-step-go="<?php echo (int) ( $ccs_st_i + 1 ); ?>">
								<?php
								/* translators: %s: the next step's title, or the next year. */
								echo esc_html( sprintf( __( 'Next: %s', 'ccs-wp-theme' ), $ccs_st_items[ $ccs_st_i + 1 ]['name'] ) );
								?>
								<span aria-hidden="true">&rarr;</span>
							</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
