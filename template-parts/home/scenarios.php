<?php
/**
 * Homepage scenarios section: three cards (hospital discharge, 24/7 care, comparing options).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$scenarios = array(
	array(
		'title'       => __( 'Hospital discharge', 'ccs-wp-theme' ),
		'description' => __( 'Need support straight after a hospital stay? We coordinate with the hospital and your GP so you can return home safely with a clear care plan.', 'ccs-wp-theme' ),
		'bullets'     => array(
			__( 'Coordination with hospital and GP', 'ccs-wp-theme' ),
			__( 'Smooth transition home', 'ccs-wp-theme' ),
			__( 'Clear care plan from day one', 'ccs-wp-theme' ),
		),
		'link_text'   => __( 'Hospital discharge support', 'ccs-wp-theme' ),
		'link_url'    => home_url( '/hospital-discharge/' ),
		'urgent'      => true,
		'icon'        => 'hospital',
	),
	array(
		'title'       => __( '24/7 care at home', 'ccs-wp-theme' ),
		'description' => __( 'Round-the-clock care in your own home. We support people with complex needs, dementia, or those who simply feel safer with someone there day and night.', 'ccs-wp-theme' ),
		'bullets'     => array(
			__( 'Live-in or visiting care', 'ccs-wp-theme' ),
			__( 'Complex and dementia care', 'ccs-wp-theme' ),
			__( 'Consistent, familiar carers', 'ccs-wp-theme' ),
		),
		'link_text'   => __( '24/7 care', 'ccs-wp-theme' ),
		'link_url'    => home_url( '/our-care/' ),
		'urgent'      => false,
		'icon'        => 'home',
	),
	array(
		'title'       => __( 'Comparing care options', 'ccs-wp-theme' ),
		'description' => __( 'Not sure whether you need care at home, respite, or something else? We help you understand your options and what might work best for you and your family.', 'ccs-wp-theme' ),
		'bullets'     => array(
			__( 'No obligation advice', 'ccs-wp-theme' ),
			__( 'Care at home vs other options', 'ccs-wp-theme' ),
			__( 'Clear, jargon-free guidance', 'ccs-wp-theme' ),
		),
		'link_text'   => __( 'Compare options', 'ccs-wp-theme' ),
		'link_url'    => home_url( '/contact/' ),
		'urgent'      => false,
		'icon'        => 'compare',
	),
);
?>

<section class="home-scenarios" aria-labelledby="home-scenarios-heading">
	<div class="home-scenarios__inner container container--lg">
		<h2 id="home-scenarios-heading" class="home-scenarios__heading">
			<?php esc_html_e( 'How we can help', 'ccs-wp-theme' ); ?>
		</h2>
		<div class="home-scenarios__grid">
			<?php foreach ( $scenarios as $s ) : ?>
				<article class="home-scenario-card card">
					<div class="home-scenario-card__header">
						<?php if ( ! empty( $s['urgent'] ) ) : ?>
							<span class="badge badge-urgent"><?php esc_html_e( 'Urgent', 'ccs-wp-theme' ); ?></span>
						<?php endif; ?>
						<h3 class="home-scenario-card__title"><?php echo esc_html( $s['title'] ); ?></h3>
					</div>
					<div class="home-scenario-card__body card-body">
						<p class="home-scenario-card__description"><?php echo esc_html( $s['description'] ); ?></p>
						<ul class="home-scenario-card__list">
							<?php foreach ( $s['bullets'] as $bullet ) : ?>
								<li><?php echo esc_html( $bullet ); ?></li>
							<?php endforeach; ?>
						</ul>
						<a href="<?php echo esc_url( $s['link_url'] ); ?>" class="home-scenario-card__link">
							<?php echo esc_html( $s['link_text'] ); ?>
							<span aria-hidden="true">&rarr;</span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="home-scenarios__footer-cta">
			<?php esc_html_e( 'Still not sure?', 'ccs-wp-theme' ); ?>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Talk to us', 'ccs-wp-theme' ); ?></a>
		</p>
	</div>
</section>
