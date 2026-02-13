<?php
/**
 * Single service template: hero, two-column content + sidebar, bottom CTA.
 * Uses service meta: short_description, features, pricing, faqs, service_urgent.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$post_id    = get_the_ID();
	$short_desc = get_post_meta( $post_id, 'service_short_description', true );
	$features   = get_post_meta( $post_id, 'service_features', true );
	$faqs       = get_post_meta( $post_id, 'service_faqs', true );
	$price_from = get_post_meta( $post_id, 'service_price_from', true );
	$price_to   = get_post_meta( $post_id, 'service_price_to', true );
	$typical    = get_post_meta( $post_id, 'service_typical_hours', true );
	$funding    = get_post_meta( $post_id, 'service_funding_options', true );
	$is_urgent  = (string) get_post_meta( $post_id, 'service_urgent', true ) === '1';

	$ccs_phone     = get_theme_mod( 'ccs_phone', '01234 567890' );
	$ccs_phone_tel  = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
	$ccs_office_hrs = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );

	$features = is_array( $features ) ? $features : array();
	$faqs     = is_array( $faqs ) ? $faqs : array();
	$has_price = ( $price_from !== '' && (float) $price_from > 0 ) || ( $price_to !== '' && (float) $price_to > 0 );

	// Related services: same service_category, exclude current, limit 5
	$terms    = get_the_terms( $post_id, 'service_category' );
	$related  = array();
	if ( $terms && ! is_wp_error( $terms ) ) {
		$term_ids = array_map( 'intval', wp_list_pluck( $terms, 'term_id' ) );
		$related  = get_posts( array(
			'post_type'      => 'service',
			'post__not_in'   => array( $post_id ),
			'posts_per_page' => 5,
			'tax_query'      => array(
				array(
					'taxonomy' => 'service_category',
					'field'    => 'term_id',
					'terms'    => $term_ids,
				),
			),
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		) );
	}
	if ( empty( $related ) ) {
		$related = get_posts( array(
			'post_type'      => 'service',
			'post__not_in'   => array( $post_id ),
			'posts_per_page' => 5,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		) );
	}
?>

<main id="main" class="site-main site-main--service" role="main">

	<!-- Hero: full-width, light background -->
	<header class="service-hero">
		<div class="service-hero__inner container container--lg">
			<nav class="service-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ccs-wp-theme' ); ?>">
				<ol class="service-hero__breadcrumb-list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ccs-wp-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Services', 'ccs-wp-theme' ); ?></a></li>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>
			<h1 class="service-hero__title"><?php the_title(); ?></h1>
			<?php if ( $short_desc ) : ?>
				<p class="service-hero__desc"><?php echo esc_html( $short_desc ); ?></p>
			<?php endif; ?>
			<?php if ( $ccs_phone_tel ) : ?>
				<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn btn-phone btn-lg service-hero__cta">
					<?php echo esc_html( $ccs_phone ); ?>
				</a>
			<?php endif; ?>
		</div>
	</header>

	<!-- Main content: two-column -->
	<div class="service-body">
		<div class="service-body__inner container container--lg">
			<div class="service-body__main">
				<?php if ( get_the_content() ) : ?>
					<div class="service-content entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $features ) ) : ?>
					<section class="service-features" aria-labelledby="service-features-heading">
						<h2 id="service-features-heading" class="service-features__heading"><?php esc_html_e( 'What’s included', 'ccs-wp-theme' ); ?></h2>
						<ul class="service-features__list">
							<?php foreach ( $features as $row ) :
								$text = isset( $row['feature_text'] ) ? $row['feature_text'] : '';
								if ( $text === '' ) {
									continue;
								}
							?>
								<li class="service-features__item">
									<?php echo esc_html( $text ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endif; ?>

				<?php if ( $has_price ) : ?>
					<aside class="service-pricing" aria-labelledby="service-pricing-heading">
						<h2 id="service-pricing-heading" class="service-pricing__heading"><?php esc_html_e( 'Pricing', 'ccs-wp-theme' ); ?></h2>
						<div class="service-pricing__box">
							<?php if ( $price_from !== '' && (float) $price_from > 0 ) : ?>
								<p class="service-pricing__range">
									<?php
									if ( $price_to !== '' && (float) $price_to > 0 && (float) $price_to !== (float) $price_from ) {
										printf(
											/* translators: 1: from price, 2: to price */
											esc_html__( 'From £%1$s to £%2$s', 'ccs-wp-theme' ),
											esc_html( number_format( (float) $price_from, 2 ) ),
											esc_html( number_format( (float) $price_to, 2 ) )
										);
									} else {
										printf(
											/* translators: %s: price */
											esc_html__( 'From £%s', 'ccs-wp-theme' ),
											esc_html( number_format( (float) $price_from, 2 ) )
										);
									}
									?>
								</p>
							<?php endif; ?>
							<?php if ( $typical ) : ?>
								<p class="service-pricing__hours"><?php echo esc_html( $typical ); ?></p>
							<?php endif; ?>
							<?php if ( $funding ) : ?>
								<div class="service-pricing__funding"><?php echo wp_kses_post( nl2br( esc_html( $funding ) ) ); ?></div>
							<?php endif; ?>
						</div>
					</aside>
				<?php endif; ?>

				<?php if ( ! empty( $faqs ) ) : ?>
					<section class="service-faqs" aria-labelledby="service-faqs-heading">
						<h2 id="service-faqs-heading" class="service-faqs__heading"><?php esc_html_e( 'Frequently asked questions', 'ccs-wp-theme' ); ?></h2>
						<dl class="service-faqs__list">
							<?php foreach ( $faqs as $faq ) :
								$q = isset( $faq['question'] ) ? trim( $faq['question'] ) : '';
								$a = isset( $faq['answer'] ) ? $faq['answer'] : '';
								if ( $q === '' ) {
									continue;
								}
							?>
								<div class="service-faqs__item">
									<dt class="service-faqs__q"><?php echo esc_html( $q ); ?></dt>
									<dd class="service-faqs__a"><?php echo wp_kses_post( nl2br( esc_html( $a ) ) ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</section>
				<?php endif; ?>
			</div>

			<aside class="service-sidebar">
				<div class="service-sidebar__sticky">
					<div class="service-sidebar__contact">
						<h2 class="service-sidebar__contact-heading"><?php esc_html_e( 'Get in touch', 'ccs-wp-theme' ); ?></h2>
						<?php if ( $ccs_phone_tel ) : ?>
							<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="service-sidebar__phone"><?php echo esc_html( $ccs_phone ); ?></a>
						<?php endif; ?>
						<?php if ( $ccs_office_hrs ) : ?>
							<p class="service-sidebar__hours"><?php echo esc_html( $ccs_office_hrs ); ?></p>
						<?php endif; ?>

						<form class="service-sidebar__form ccs-form" data-ccs-action="request_callback" method="post" action="" aria-describedby="callback-form-message">
							<label for="service-cb-name" class="screen-reader-text"><?php esc_html_e( 'Your name', 'ccs-wp-theme' ); ?></label>
							<input type="text" id="service-cb-name" name="name" required aria-required="true" placeholder="<?php esc_attr_e( 'Your name', 'ccs-wp-theme' ); ?>" class="service-sidebar__input">
							<label for="service-cb-phone" class="screen-reader-text"><?php esc_html_e( 'Phone number', 'ccs-wp-theme' ); ?></label>
							<input type="tel" id="service-cb-phone" name="phone" required aria-required="true" placeholder="<?php esc_attr_e( 'Phone number', 'ccs-wp-theme' ); ?>" class="service-sidebar__input">
							<label for="service-cb-time" class="screen-reader-text"><?php esc_html_e( 'Preferred callback time (optional)', 'ccs-wp-theme' ); ?></label>
							<input type="text" id="service-cb-time" name="preferred_time" placeholder="<?php esc_attr_e( 'Preferred callback time', 'ccs-wp-theme' ); ?>" class="service-sidebar__input" autocomplete="off">
							<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">
							<button type="submit" class="btn btn-primary service-sidebar__submit"><?php esc_html_e( 'Request a call back', 'ccs-wp-theme' ); ?></button>
							<div id="callback-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
						</form>
					</div>

					<?php if ( ! empty( $related ) ) : ?>
						<div class="service-sidebar__related">
							<h3 class="service-sidebar__related-heading"><?php esc_html_e( 'Related services', 'ccs-wp-theme' ); ?></h3>
							<ul class="service-sidebar__related-list">
								<?php foreach ( $related as $rel ) : ?>
									<li><a href="<?php echo esc_url( get_permalink( $rel ) ); ?>"><?php echo esc_html( get_the_title( $rel ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</aside>
		</div>
	</div>

	<!-- Bottom CTA: full-width, urgency-based -->
	<section class="service-cta service-cta--<?php echo $is_urgent ? 'urgent' : 'default'; ?>" aria-labelledby="service-cta-heading">
		<div class="service-cta__inner container container--md">
			<h2 id="service-cta-heading" class="service-cta__heading">
				<?php
				if ( $is_urgent ) {
					esc_html_e( 'Need help now?', 'ccs-wp-theme' );
				} else {
					esc_html_e( 'Ready to find out more?', 'ccs-wp-theme' );
				}
				?>
			</h2>
			<p class="service-cta__text">
				<?php
				if ( $is_urgent ) {
					esc_html_e( 'Call us to discuss urgent care and we’ll respond as quickly as we can.', 'ccs-wp-theme' );
				} else {
					esc_html_e( 'Request a callback or call us to talk through your options.', 'ccs-wp-theme' );
				}
				?>
			</p>
			<div class="service-cta__actions">
				<?php if ( $ccs_phone_tel ) : ?>
					<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn <?php echo $is_urgent ? 'btn-phone' : 'btn-secondary'; ?> btn-lg"><?php echo esc_html( $ccs_phone ); ?></a>
				<?php endif; ?>
				<?php if ( ! $is_urgent ) : ?>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-lg"><?php esc_html_e( 'Get in touch', 'ccs-wp-theme' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>

<?php
	// Schema.org: Service + FAQPage
	$schema_service = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Service',
		'name'     => get_the_title(),
		'description' => $short_desc ?: wp_trim_words( get_the_content(), 30 ),
		'provider' => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
	);
	if ( $has_price && $price_from !== '' && (float) $price_from > 0 ) {
		$schema_service['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (float) $price_from,
			'priceCurrency' => 'GBP',
		);
	}

	$schema_faq = array();
	if ( ! empty( $faqs ) ) {
		$faq_entries = array();
		foreach ( $faqs as $faq ) {
			$q = isset( $faq['question'] ) ? trim( $faq['question'] ) : '';
			$a = isset( $faq['answer'] ) ? trim( $faq['answer'] ) : '';
			if ( $q === '' ) {
				continue;
			}
			$faq_entries[] = array(
				'@type'          => 'Question',
				'name'           => $q,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $a,
				),
			);
		}
		if ( ! empty( $faq_entries ) ) {
			$schema_faq = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_entries,
			);
		}
	}
?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema_service ); ?></script>
<?php if ( ! empty( $schema_faq ) ) : ?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema_faq ); ?></script>
<?php endif; ?>

<?php
endwhile;
get_footer();
