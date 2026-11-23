<?php
/**
 * Search form, in the theme's own markup (search.php and 404.php).
 *
 * The label is visible, not placeholder-only: a placeholder vanishes once
 * typing starts and gives a screen reader nothing to announce on an empty field.
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
