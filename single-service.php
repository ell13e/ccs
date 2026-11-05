<?php
/**
 * Single service template: photo hero, content beside a sticky call-back card,
 * the three steps, other services as photo cards, shared closing card.
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
			'posts_per_page' => 3,
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
			'posts_per_page' => 3,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		) );
	}
?>

<main id="main" class="site-main site-main--service" role="main">

	<?php
	/*
	 * Layout follows the service-detail page of the Sunnyaid template the client
	 * chose as the structural reference (colours stay CCS's own): photo hero,
	 * content beside a sticky contact card, the three steps, other services as
	 * photo cards, then the shared closing card. Before this, the page was a
	 * column of editor text beside a placeholder-only form and ended on its own
	 * bespoke CTA band.
	 *
	 * Hero intro: the service's short description when one is set, otherwise
	 * its excerpt (which the homepage service cards already use, so the two
	 * places describe the service in the same words). None of the services
	 * currently has a short description, which left this hero with a title and
	 * nothing under it.
	 *
	 * 'full' plus sizes="100vw" for the hero image, as in page-header.php: a
	 * full-bleed banner must not be capped at the 1024px 'large' size.
	 */
	/*
	 * Full-bleed only when the photo can fill a banner sharply; otherwise the
	 * inset variant frames it beside the text (ccs_hero_photo_fills_banner(),
	 * inc/theme-setup.php). The body class uses the same test, so the header
	 * only goes white-on-photo when there is a full-bleed photo under it.
	 */
	$ccs_service_has_photo = function_exists( 'ccs_hero_photo_fills_banner' ) ? ccs_hero_photo_fills_banner( $post_id ) : has_post_thumbnail( $post_id );
	$ccs_service_inset     = ! $ccs_service_has_photo && has_post_thumbnail( $post_id );
	$ccs_service_intro     = $short_desc ? $short_desc : ( has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : '' );
	$ccs_contact_url       = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	$ccs_services_hub_url  = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
	?>
	<header class="page-hero<?php echo $ccs_service_has_photo ? ' page-hero--photo' : ( $ccs_service_inset ? ' page-hero--inset' : '' ); ?>">
		<?php if ( $ccs_service_has_photo ) : ?>
			<div class="page-hero__scrim" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php if ( $ccs_service_intro ) : ?>
					<p class="page-hero__intro"><?php echo esc_html( $ccs_service_intro ); ?></p>
				<?php endif; ?>
				<?php
				/*
				 * The homepage hero's ranked pair: filled mint primary, outlined
				 * ghost call. It replaces a lone phone-number pill, which made a
				 * phone call the only action on a page whose visitors are mostly
				 * still deciding.
				 */
				?>
				<div class="ccs-actions service-hero__actions">
					<?php
					/*
					 * Mint and the white ghost belong on the dark photo; on the light
					 * tint band they are plum and quiet. The ghost button used to stay
					 * white here, invisible on a pale ground (rule 8).
					 */
					?>
					<a href="<?php echo esc_url( $ccs_contact_url ); ?>" class="btn <?php echo $ccs_service_has_photo ? 'btn-accent' : 'btn-primary'; ?>">
						<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
					</a>
					<?php if ( $ccs_phone_tel ) : ?>
						<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn <?php echo $ccs_service_has_photo ? 'btn-ghost-on-dark' : 'btn-quiet'; ?>">
							<?php
							/* translators: %s: office phone number */
							printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_phone ) );
							?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $ccs_service_inset ) : ?>
				<div class="page-hero__media" style="<?php echo esc_attr( '--ccs-photo-max:' . ccs_hero_photo_max_width( $post_id ) . 'px' ); ?>">
					<?php
					echo get_the_post_thumbnail(
						$post_id,
						'full',
						array(
							'class'         => 'page-hero__img',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'sizes'         => '(min-width: 900px) 24rem, 92vw',
						)
					);
					?>
				</div>
			<?php endif; ?>
			<?php if ( $ccs_service_has_photo ) : ?>
				<div class="page-hero__media">
					<?php
					echo get_the_post_thumbnail(
						$post_id,
						'full',
						array(
							'class'         => 'page-hero__img',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'sizes'         => '100vw',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</header>

	<div class="service-body">
		<div class="service-body__inner container container--lg">
			<div class="service-body__main">
				<?php if ( get_the_content() ) : ?>
					<?php
					/*
					 * page-body typography (components.css) without its page-level
					 * width and padding: the same headings, tick-badge lists and
					 * quote panels as every other content page, inside a column.
					 */
					?>
					<div class="service-content page-body page-body--flush entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $features ) ) : ?>
					<section class="service-features" aria-labelledby="service-features-heading">
						<h2 id="service-features-heading" class="service-features__heading"><?php esc_html_e( 'What’s included', 'ccs-wp-theme' ); ?></h2>
						<ul class="why-proof" role="list">
							<?php foreach ( $features as $row ) :
								$text = isset( $row['feature_text'] ) ? $row['feature_text'] : '';
								if ( $text === '' ) {
									continue;
								}
							?>
								<li class="why-proof__item">
									<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
									<span class="why-proof__text"><?php echo esc_html( $text ); ?></span>
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
							<?php
							/*
							 * Same caveat as the homepage price card, for the same reason:
							 * a lone "From £X" reads as a flat rate.
							 */
							?>
							<p class="service-pricing__caveat">
								<?php esc_html_e( 'Your actual cost depends on the type of care you need and when it happens — this is our lowest rate, not a flat price.', 'ccs-wp-theme' ); ?>
							</p>
							<?php if ( $funding ) : ?>
								<div class="service-pricing__funding"><?php echo wp_kses_post( nl2br( esc_html( $funding ) ) ); ?></div>
							<?php endif; ?>
						</div>
					</aside>
				<?php endif; ?>

				<?php if ( ! empty( $faqs ) ) : ?>
					<section class="service-faqs" aria-labelledby="service-faqs-heading">
						<h2 id="service-faqs-heading" class="service-faqs__heading"><?php esc_html_e( 'Frequently asked questions', 'ccs-wp-theme' ); ?></h2>
						<?php
						/*
						 * Native <details>: keyboard and screen-reader behaviour for
						 * free, and the shared name makes them exclusive (one open at
						 * a time) without a script. See docs/BUILD-STANDARD.md §3.
						 */
						?>
						<div class="ccs-faq">
							<?php foreach ( $faqs as $faq ) :
								$q = isset( $faq['question'] ) ? trim( $faq['question'] ) : '';
								$a = isset( $faq['answer'] ) ? $faq['answer'] : '';
								if ( $q === '' ) {
									continue;
								}
							?>
								<details class="ccs-faq__item" name="service-faq">
									<summary class="ccs-faq__q"><?php echo esc_html( $q ); ?></summary>
									<div class="ccs-faq__a"><?php echo wp_kses_post( wpautop( esc_html( $a ) ) ); ?></div>
								</details>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</div>

			<aside class="service-sidebar" aria-labelledby="service-sidebar-heading">
				<div class="service-sidebar__sticky">
					<div class="service-sidebar__card">
						<p class="ccs-eyebrow"><?php esc_html_e( 'Free, no obligation', 'ccs-wp-theme' ); ?></p>
						<h2 id="service-sidebar-heading" class="service-sidebar__heading"><?php esc_html_e( 'Ask us to call you back', 'ccs-wp-theme' ); ?></h2>
						<?php if ( $ccs_phone_tel ) : ?>
							<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="service-sidebar__phone">
								<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<span><?php echo esc_html( $ccs_phone ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( $ccs_office_hrs ) : ?>
							<p class="service-sidebar__hours"><?php echo esc_html( $ccs_office_hrs ); ?></p>
						<?php endif; ?>

						<?php
						/*
						 * Visible labels above every field, not placeholders. The
						 * labels used to be screen-reader-only, so sighted visitors
						 * saw prompts that vanished as soon as they began typing, and
						 * "optional" appeared only in the hidden label. autocomplete
						 * on name and phone meets WCAG 2.1 1.3.5 (Identify Input
						 * Purpose), which this form failed, and lets the browser fill
						 * them in for people who find typing hard.
						 */
						?>
						<form class="service-sidebar__form ccs-form" data-ccs-action="request_callback" method="post" action="" novalidate aria-describedby="callback-form-message">
							<div class="ccs-field">
								<label for="service-cb-name" class="ccs-field__label"><?php esc_html_e( 'Your name', 'ccs-wp-theme' ); ?></label>
								<input type="text" id="service-cb-name" name="name" required aria-required="true" autocomplete="name" class="ccs-field__input">
							</div>
							<div class="ccs-field">
								<label for="service-cb-phone" class="ccs-field__label"><?php esc_html_e( 'Phone number', 'ccs-wp-theme' ); ?></label>
								<input type="tel" id="service-cb-phone" name="phone" required aria-required="true" autocomplete="tel" class="ccs-field__input">
							</div>
							<div class="ccs-field">
								<label for="service-cb-time" class="ccs-field__label">
									<?php esc_html_e( 'Best time to call', 'ccs-wp-theme' ); ?>
									<span class="ccs-field__optional"><?php esc_html_e( '(optional)', 'ccs-wp-theme' ); ?></span>
								</label>
								<input type="text" id="service-cb-time" name="preferred_time" class="ccs-field__input" autocomplete="off">
							</div>
							<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">
							<button type="submit" class="btn btn-primary service-sidebar__submit"><?php esc_html_e( 'Request a call back', 'ccs-wp-theme' ); ?></button>
							<div id="callback-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
						</form>
					</div>
				</div>
			</aside>
		</div>
	</div>

	<?php get_template_part( 'template-parts/home/steps' ); ?>

	<?php if ( ! empty( $related ) ) : ?>
		<section class="service-related" aria-labelledby="service-related-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="service-related-heading" class="ccs-title">
						<?php
						/* translators: %s: the emphasised word, drawn with a brush underline. */
						printf( esc_html__( 'Other ways we can %s', 'ccs-wp-theme' ), ccs_accent( __( 'help', 'ccs-wp-theme' ) ) );
						?>
					</h2>
				</header>
				<?php
				/*
				 * Other services as the homepage's photo cards. Featured images go
				 * through wp_get_attachment_image(), so they arrive with a srcset
				 * and sizes the browser can choose from (docs/BUILD-STANDARD.md §2).
				 * With fewer than three related services, the last slot is a link
				 * to the services hub instead of a hole in the grid.
				 */
				$ccs_related_shown = array_slice( $related, 0, 3 );
				$ccs_rel_icons     = array(
					'domiciliary-care' => 'home',
					'respite-care'     => 'cup',
					'complex-care'     => 'pulse',
				);
				?>
				<ul class="svc-grid" role="list">
					<?php foreach ( $ccs_related_shown as $rel ) : ?>
						<li class="svc-card">
							<?php if ( has_post_thumbnail( $rel ) ) : ?>
								<div class="svc-card__media">
									<?php
									echo wp_get_attachment_image(
										get_post_thumbnail_id( $rel ),
										'large',
										false,
										array(
											'alt'     => '',
											'loading' => 'lazy',
											'sizes'   => '(min-width: 1280px) 400px, (min-width: 860px) 31vw, 92vw',
										)
									);
									?>
								</div>
							<?php endif; ?>
							<div class="svc-card__body">
								<span class="ccs-badge ccs-badge--ring svc-card__badge"><?php echo ccs_icon( $ccs_rel_icons[ $rel->post_name ] ?? 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<h3 class="svc-card__title">
									<a href="<?php echo esc_url( get_permalink( $rel ) ); ?>" class="svc-card__link"><?php echo esc_html( get_the_title( $rel ) ); ?></a>
								</h3>
								<?php if ( has_excerpt( $rel ) ) : ?>
									<p class="svc-card__text"><?php echo esc_html( get_the_excerpt( $rel ) ); ?></p>
								<?php endif; ?>
								<span class="svc-card__cue" aria-hidden="true"><?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span class="svc-card__arrow">&rarr;</span></span>
							</div>
						</li>
					<?php endforeach; ?>
					<?php if ( count( $ccs_related_shown ) < 3 ) : ?>
						<li class="info-card service-related__hub">
							<span class="ccs-badge"><?php echo ccs_icon( 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<p class="info-card__label"><?php esc_html_e( 'All services', 'ccs-wp-theme' ); ?></p>
							<h3 class="info-card__title">
								<a class="info-card__link" href="<?php echo esc_url( $ccs_services_hub_url ); ?>"><?php esc_html_e( 'Everything we offer at home', 'ccs-wp-theme' ); ?></a>
							</h3>
							<p class="info-card__text"><?php esc_html_e( 'See every kind of care we provide across Maidstone and Kent, and who each one is for.', 'ccs-wp-theme' ); ?></p>
							<span class="info-card__cue" aria-hidden="true"><?php esc_html_e( 'See all services', 'ccs-wp-theme' ); ?> <span class="info-card__arrow">&rarr;</span></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/*
	 * The shared closing card, as on every other page. This replaces a bespoke
	 * .service-cta band; its "urgent" variant offered a phone number only, and
	 * the card already carries the call button beside the consultation link.
	 */
	get_template_part( 'template-parts/cta-band' );
	?>

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
