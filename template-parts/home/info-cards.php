<?php
/**
 * Homepage information cards: Our Care Approach, FAQs, Careers (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_url   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'about-home-care-maidstone' ) : home_url( '/home/about-home-care-maidstone/' );
$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home/home-care-services-kent/' );
$careers_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'care-careers-maidstone-kent' ) : home_url( '/home/care-careers-maidstone-kent/' );

$cards = array(
	array(
		'subheading' => __( 'About Us', 'ccs-wp-theme' ),
		'title'      => __( 'Our Care Approach', 'ccs-wp-theme' ),
		'body'       => __( 'Compassionate care, tailored to you. We\'re dedicated to supporting your independence, dignity, and wellbeing, by delivering trusted care services with a personal touch. Discover how our team makes a difference every day.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Learn More', 'ccs-wp-theme' ),
		'cta_url'    => $about_url,
	),
	array(
		'subheading' => __( 'FAQs', 'ccs-wp-theme' ),
		'title'      => __( 'Home Care FAQs', 'ccs-wp-theme' ),
		'body'       => __( 'Have questions about our home care in Maidstone & Kent? Find answers about our services, care plans, and what to expect. If you\'re finding the care search overwhelming, or need more information, our team is just a call away.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Get Answers', 'ccs-wp-theme' ),
		'cta_url'    => $services_url,
	),
	array(
		'subheading' => __( 'Careers', 'ccs-wp-theme' ),
		'title'      => __( 'Care Careers in Kent', 'ccs-wp-theme' ),
		'body'       => __( 'Make a real impact by joining our team. Offering rewarding roles, flexible hours, and ongoing training, we\'d love to hear from you. If you\'re passionate about helping others, explore how you can grow your career with us.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Explore Roles', 'ccs-wp-theme' ),
		'cta_url'    => $careers_url,
	),
);
?>

<section class="home-info-cards" aria-labelledby="home-info-cards-heading">
	<div class="home-info-cards__inner container container--lg">
		<h2 id="home-info-cards-heading" class="visually-hidden">
			<?php esc_html_e( 'Find out more', 'ccs-wp-theme' ); ?>
		</h2>
		<div class="home-info-cards__grid">
			<?php foreach ( $cards as $card ) : ?>
				<article class="home-info-card card">
					<div class="home-info-card__body card-body">
						<p class="home-info-card__subheading">
							<?php echo esc_html( $card['subheading'] ); ?>
						</p>
						<h3 class="home-info-card__title">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>
						<p class="home-info-card__text">
							<?php echo esc_html( $card['body'] ); ?>
						</p>
						<a href="<?php echo esc_url( $card['cta_url'] ); ?>" class="home-info-card__link">
							<?php echo esc_html( $card['cta_text'] ); ?>
							<span aria-hidden="true">&rarr;</span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
