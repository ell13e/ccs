<?php
/**
 * Search form.
 *
 * Overrides WordPress's default get_search_form() markup, which shipped its own
 * unstyled label/input/submit and ignored the design system entirely — visible
 * on search.php's no-results state and on 404.php, i.e. exactly the two moments
 * someone is already lost and least able to absorb a jarring bit of interface.
 *
 * The label is visible rather than placeholder-only. Placeholder-as-label
 * disappears the moment typing starts, which is a known problem for anyone
 * relying on short-term memory — a real consideration for this site's readers —
 * and leaves nothing for a screen reader to announce on an empty field.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_search_id = 'ccs-search-' . wp_unique_id();
?>

<form role="search" method="get" class="ccs-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="ccs-search-form__label" for="<?php echo esc_attr( $ccs_search_id ); ?>">
		<?php esc_html_e( 'Search this site', 'ccs-wp-theme' ); ?>
	</label>
	<div class="ccs-search-form__row">
		<input
			type="search"
			id="<?php echo esc_attr( $ccs_search_id ); ?>"
			class="ccs-search-form__input"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
			autocomplete="off"
			placeholder="<?php esc_attr_e( 'Respite care, costs, Maidstone…', 'ccs-wp-theme' ); ?>"
		>
		<button type="submit" class="btn btn-primary ccs-search-form__submit">
			<?php esc_html_e( 'Search', 'ccs-wp-theme' ); ?>
		</button>
	</div>
</form>
