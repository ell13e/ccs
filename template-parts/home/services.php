<?php
/**
 * Homepage services overview: three columns (Complex Care, Personal Care, Companionship).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = array(
	array(
		'title'   => __( 'Complex care', 'ccs-wp-theme' ),
		'intro'   => __( 'For people with ongoing medical or mobility needs who want to stay at home. We work with your healthcare team and family.', 'ccs-wp-theme' ),
		'items'   => array(
			__( 'PEG feeding and medication', 'ccs-wp-theme' ),
			__( 'Mobility and rehabilitation support', 'ccs-wp-theme' ),
			__( 'Dementia and cognitive support', 'ccs-wp-theme' ),
			__( 'End-of-life care', 'ccs-wp-theme' ),
		),
		'link_text' => __( 'Complex care', 'ccs-wp-theme' ),
		'link_url'  => home_url( '/services/complex-care/' ),
	),
	array(
		'title'   => __( 'Personal care', 'ccs-wp-theme' ),
		'intro'   => __( 'Day-to-day support with washing, dressing, eating and medication so you can remain independent at home.', 'ccs-wp-theme' ),
		'items'   => array(
			__( 'Washing and dressing', 'ccs-wp-theme' ),
			__( 'Meal preparation and eating', 'ccs-wp-theme' ),
			__( 'Medication reminders', 'ccs-wp-theme' ),
			__( 'Continence support', 'ccs-wp-theme' ),
		),
		'link_text' => __( 'Personal care', 'ccs-wp-theme' ),
		'link_url'  => home_url( '/services/personal-care/' ),
	),
	array(
		'title'   => __( 'Companionship', 'ccs-wp-theme' ),
		'intro'   => __( 'Regular visits for company, outings and light household tasks. Reduces isolation and keeps you connected.', 'ccs-wp-theme' ),
		'items'   => array(
			__( 'Company and conversation', 'ccs-wp-theme' ),
			__( 'Shopping and errands', 'ccs-wp-theme' ),
			__( 'Light housework', 'ccs-wp-theme' ),
			__( 'Escorts to appointments', 'ccs-wp-theme' ),
		),
		'link_text' => __( 'Companionship', 'ccs-wp-theme' ),
		'link_url'  => home_url( '/services/companionship/' ),
	),
);
?>

<section class="home-services" aria-labelledby="home-services-heading">
	<div class="home-services__inner container container--lg">
		<h2 id="home-services-heading" class="home-services__heading" data-animate="fade-up" data-delay="0">
			<?php esc_html_e( 'Our services', 'ccs-wp-theme' ); ?>
		</h2>
		<div class="home-services__grid">
			<?php foreach ( $services as $i => $svc ) : ?>
				<div class="home-service-col" data-animate="fade-up" data-delay="<?php echo (int) $i * 100; ?>">
					<h3 class="home-service-col__title"><?php echo esc_html( $svc['title'] ); ?></h3>
					<p class="home-service-col__intro"><?php echo esc_html( $svc['intro'] ); ?></p>
					<ul class="home-service-col__list">
						<?php foreach ( $svc['items'] as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( $svc['link_url'] ); ?>" class="home-service-col__link">
						<?php echo esc_html( $svc['link_text'] ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="home-services__cta-box">
			<p class="home-services__cta-text">
				<?php esc_html_e( 'Tell us what you need. We’ll match you with a care plan that works.', 'ccs-wp-theme' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-lg home-services__cta-btn">
				<?php esc_html_e( 'Get in touch', 'ccs-wp-theme' ); ?>
			</a>
		</div>
	</div>
</section>
