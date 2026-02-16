<?php
/**
 * CV Minder job portal embed (Current Vacancies page).
 * When Customizer "Use CV Minder" is on: output iframe. When off: fallback CTA.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$use_cvm = get_theme_mod( 'ccs_cvm_use', true );

if ( ! $use_cvm ) {
	?>
	<div class="cvm-fallback container container--lg">
		<p><?php esc_html_e( 'View our current vacancies or contact us to find out about opportunities.', 'ccs-wp-theme' ); ?></p>
		<p><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact-us' ) ) ?: home_url( '/contact-us/' ) ); ?>" class="button button--primary"><?php esc_html_e( 'Contact us', 'ccs-wp-theme' ); ?></a></p>
	</div>
	<?php
	return;
}

$iframe_src = get_theme_mod( 'ccs_cvm_iframe_src', 'https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713' );
$iframe_src = $iframe_src ? $iframe_src : 'https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713';
?>

<div class="cvm-embed" id="cvm_content">
	<iframe id="cvm_jobframe" name="cvm_jobframe" src="<?php echo esc_url( $iframe_src ); ?>" allowtransparency="true" frameborder="0" marginwidth="0" marginheight="0" scrolling="auto" title="<?php esc_attr_e( 'Jobs posted by CV Minder', 'ccs-wp-theme' ); ?>"></iframe>
</div>
