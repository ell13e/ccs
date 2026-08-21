<?php
/**
 * Homepage Why Choose Us section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
?>

<section class="home-why-choose" aria-labelledby="home-why-heading">
	<div class="home-why-choose__inner container container--lg">
		<h2 id="home-why-heading" class="home-why-choose__heading">
			<?php esc_html_e( 'Why Choose Us for Home Care in Maidstone?', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-why-choose__subheading">
			<?php esc_html_e( "It's not just what we do, it's how we do it.", 'ccs-wp-theme' ); ?>
		</p>
		<div class="home-why-choose__body">
			<p>
				<?php esc_html_e( 'We support adults and children across Maidstone and Kent, day or night. Our carers are local, and the same small team comes back to you each visit.', 'ccs-wp-theme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'We don’t rush, and we don’t rotate staff every other week. We take the time to learn how you like your tea, what makes you laugh, and what puts you at ease on a harder day. That’s the part a care plan can’t write down.', 'ccs-wp-theme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Good care doesn’t stop when the to-do list is ticked. It carries on in how our carers show up: familiar, unhurried, and glad to see you.', 'ccs-wp-theme' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $services_url ); ?>" class="home-why-choose__link">
					<?php esc_html_e( 'Learn more about our services', 'ccs-wp-theme' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</p>
		</div>
	</div>
</section>
