<?php
/**
 * Homepage Why Choose Us section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home/home-care-services-kent/' );
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
				<?php
				echo wp_kses_post(
					__( 'Reliably supporting adults and children across Maidstone and Kent, we\'re here to provide personalised care, day or night, tailored to you. <strong>Our caring, local team is dedicated to supporting families across Kent.</strong> We don\'t rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day. We believe that the best care doesn\'t stop when the to-do list is ticked; it continues through our staff showing up in a way that feels friendly, familiar, and person-centred. <strong>Learn more about the home care services we offer in Maidstone & Kent.</strong>', 'ccs-wp-theme' )
				);
				?>
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
