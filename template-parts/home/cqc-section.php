<?php
/**
 * Homepage CQC section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cqc_url = defined( 'CCS_CQC_REPORT_URL' ) ? CCS_CQC_REPORT_URL : 'https://www.cqc.org.uk/provider/1-2624556588';
?>

<section class="home-cqc" aria-labelledby="home-cqc-heading">
	<div class="home-cqc__inner container container--lg">
		<h2 id="home-cqc-heading" class="home-cqc__heading">
			<?php esc_html_e( 'Regulated, rated, and reliable home care across Maidstone & Kent', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-cqc__subheading">
			<?php esc_html_e( "Proud to be rated 'Good' by the CQC", 'ccs-wp-theme' ); ?>
		</p>
		<p class="home-cqc__link-wrap">
			<a href="<?php echo esc_url( $cqc_url ); ?>" class="home-cqc__link" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'View our CQC profile', 'ccs-wp-theme' ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</p>
	</div>
</section>
