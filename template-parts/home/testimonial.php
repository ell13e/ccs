<?php
/**
 * Homepage testimonial – Claire Pitchford (content guide §5).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="home-testimonial" aria-labelledby="home-testimonial-heading">
	<div class="home-testimonial__inner container container--lg">
		<h2 id="home-testimonial-heading" class="visually-hidden">
			<?php esc_html_e( 'What our families say', 'ccs-wp-theme' ); ?>
		</h2>
		<blockquote class="home-testimonial__quote">
			<p class="home-testimonial__text">
				<?php
				esc_html_e(
					"I am delighted to express my gratitude for the outstanding care CCS delivered to my paraplegic father, requiring complex care. The skilled and compassionate team went above and beyond, addressing his unique needs with unwavering dedication. Their expertise and empathy transformed what could have been a challenging situation into a positive and reassuring experience for him and his family.",
					'ccs-wp-theme'
				);
				?>
			</p>
			<cite class="home-testimonial__cite">
				<?php esc_html_e( 'Claire Pitchford', 'ccs-wp-theme' ); ?>
			</cite>
		</blockquote>
	</div>
</section>
