<?php
/**
 * Homepage information cards: Our Care Approach, FAQs, Careers (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_url   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'about-home-care-maidstone' ) : home_url( '/about-home-care-maidstone/' );
$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
// FAQs previously pointed at $services_url (wrong destination); careers pointed at the
// legacy 'care-careers-maidstone-kent' page rather than the real /careers/ hub. Both fixed 2026-08-19.
$faqs_url    = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'faqs' ) : home_url( '/resources/faqs/' );
$careers_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'careers' ) : home_url( '/careers/' );

$cards = array(
	array(
		'subheading' => __( 'About Us', 'ccs-wp-theme' ),
		'title'      => __( 'Our Care Approach', 'ccs-wp-theme' ),
		'body'       => __( 'We’re a family-run, CQC-regulated provider in Maidstone. See how we build a care team around one person rather than filling a rota, and what that looks like week to week.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Learn more', 'ccs-wp-theme' ),
		'cta_url'    => $about_url,
	),
	array(
		'subheading' => __( 'FAQs', 'ccs-wp-theme' ),
		'title'      => __( 'Home Care FAQs', 'ccs-wp-theme' ),
		'body'       => __( 'What does care cost? How quickly can it start? Who will actually turn up? Straight answers to the questions families ask us most, with no jargon.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Get answers', 'ccs-wp-theme' ),
		'cta_url'    => $faqs_url,
	),
	array(
		'subheading' => __( 'Careers', 'ccs-wp-theme' ),
		'title'      => __( 'Care Careers in Kent', 'ccs-wp-theme' ),
		'body'       => __( 'Flexible hours, proper training, and a team that backs you up. If you want care work where you’re given time to do the job well, we’d like to hear from you.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Explore roles', 'ccs-wp-theme' ),
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
