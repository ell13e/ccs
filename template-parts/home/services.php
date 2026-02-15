<?php
/**
 * Homepage care options: §2b "Explore Your Care Options" and three cards (Domiciliary, Respite, Complex).
 * Uses service CPT when available; fallback copy from content guide §3.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_overview_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home/home-care-services-kent/' );
$contact_url           = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/home/contact-us/' );

// Try to get three service posts (Domiciliary, Respite, Complex) by slug.
$service_slugs = array( 'domiciliary-care', 'respite-care', 'complex-care' );
$service_posts = array();
foreach ( $service_slugs as $slug ) {
	$posts = get_posts(
		array(
			'post_type'      => 'service',
			'name'           => $slug,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		)
	);
	if ( ! empty( $posts ) ) {
		$service_posts[] = $posts[0];
	}
}

// Fallback copy from content guide §3 when no or fewer than 3 service posts.
$fallback_services = array(
	array(
		'title'    => __( 'Domiciliary Care', 'ccs-wp-theme' ),
		'intro'    => __( 'Personalised care in the comfort of your own home. We support daily living, from personal care and medication to companionship and household tasks, so you can live independently and safely.', 'ccs-wp-theme' ),
		'link_url' => home_url( '/services/domiciliary-care/' ),
	),
	array(
		'title'    => __( 'Respite Care', 'ccs-wp-theme' ),
		'intro'    => __( 'Short-term support when you or your family need a break. Flexible respite options from a few hours to overnight or longer, so carers can recharge while your loved one is in safe hands.', 'ccs-wp-theme' ),
		'link_url' => home_url( '/services/respite-care/' ),
	),
	array(
		'title'    => __( 'Complex Care', 'ccs-wp-theme' ),
		'intro'    => __( 'Expert support for individuals with complex health needs. Our trained team works with healthcare professionals and families to deliver clinical and personal care, day or night.', 'ccs-wp-theme' ),
		'link_url' => home_url( '/services/complex-care/' ),
	),
);

// Build list for output: use service posts when we have them, else fallback (with correct permalinks for CPT).
$services = array();
if ( count( $service_posts ) >= 3 ) {
	foreach ( $service_posts as $post ) {
		$services[] = array(
			'title'   => get_the_title( $post ),
			'intro'   => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( get_post_field( 'post_content', $post ), 25 ),
			'link_url' => get_permalink( $post ),
		);
	}
} else {
	foreach ( $fallback_services as $i => $fb ) {
		$slug = $service_slugs[ $i ];
		$q   = get_posts( array( 'post_type' => 'service', 'name' => $slug, 'post_status' => 'publish', 'posts_per_page' => 1 ) );
		$services[] = array(
			'title'   => $fb['title'],
			'intro'   => $fb['intro'],
			'link_url' => ! empty( $q ) ? get_permalink( $q[0] ) : $fb['link_url'],
		);
	}
}
?>

<section class="home-services" aria-labelledby="home-services-heading">
	<div class="home-services__inner container container--lg">
		<p class="home-services__small-heading">
			<?php esc_html_e( 'Beginning your home care journey', 'ccs-wp-theme' ); ?>
		</p>
		<h2 id="home-services-heading" class="home-services__heading">
			<?php esc_html_e( 'Explore Your Care Options', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-services__intro">
			<?php
			esc_html_e(
				"Whether you need a little help dressing in the mornings, round-the-clock complex care, or just someone to pop in for a cuppa and a catch-up, we're here to make life feel a little lighter. For expert home care Maidstone families trust, get in touch today, and we'll create a plan tailored to your needs.",
				'ccs-wp-theme'
			);
			?>
		</p>
		<div class="home-services__grid">
			<?php foreach ( $services as $svc ) : ?>
				<div class="home-service-col">
					<h3 class="home-service-col__title"><?php echo esc_html( $svc['title'] ); ?></h3>
					<p class="home-service-col__intro"><?php echo esc_html( $svc['intro'] ); ?></p>
					<a href="<?php echo esc_url( $svc['link_url'] ); ?>" class="home-service-col__link">
						<?php echo esc_html( $svc['title'] ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="home-services__cta-box">
			<p class="home-services__cta-text">
				<?php esc_html_e( 'Tell us what you need. We’ll match you with a care plan that works.', 'ccs-wp-theme' ); ?>
			</p>
			<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-primary btn-lg home-services__cta-btn">
				<?php esc_html_e( 'Book a care consultation', 'ccs-wp-theme' ); ?>
			</a>
		</div>
	</div>
</section>
