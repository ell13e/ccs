<?php
/**
 * Single service template (Nov 2026, photo-led): hero with the starting price
 * on a tab over the photo; the opening paragraph, what's included as ticked
 * lists and the rest in an accordion, beside the call-back card; the rate
 * table; questions; the closing card; related services.
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

	$ccs_phone     = get_theme_mod( 'ccs_phone', '01622 809 881' );
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

	/*
	 * The editor text, split at its headings (Nov 2026). The client pointed to
	 * ultimacare.co.uk's service pages: an introduction, then what the service
	 * covers as a set of cards, then questions, a person to talk to, related
	 * services and reviews. Sections that are lists ("What we help with",
	 * "Conditions we support") become the cards; sections that are prose stay
	 * in the reading column beside the call-back card. The words are the
	 * client's own, unchanged; only a trailing colon on a heading is dropped,
	 * since the heading is no longer followed directly by its list.
	 */
	$ccs_svc_split  = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
	$ccs_svc_intro  = $ccs_svc_split['intro'];
	$ccs_svc_prose  = array();
	$ccs_svc_lists  = array();
	foreach ( $ccs_svc_split['sections'] as $ccs_sec ) {
		$ccs_sec['heading'] = rtrim( $ccs_sec['heading'], " :\t" );
		if ( preg_match( '#<(ul|ol)[\s>]#i', $ccs_sec['body'] ) ) {
			$ccs_svc_lists[] = $ccs_sec;
		} else {
			$ccs_svc_prose[] = $ccs_sec;
		}
	}

	/*
	 * Questions: only the service's own (Services > edit > FAQs). The general
	 * questions from the FAQs page used to fill in here, but they repeated the
	 * same text on all three service pages and the FAQs page, which search
	 * engines read as duplicate content (Nov 2026). With no questions of its
	 * own, the section does not show.
	 */
	$ccs_svc_faqs     = array();
	$ccs_svc_faq_page = null;
	foreach ( $faqs as $ccs_faq ) {
		$ccs_q = isset( $ccs_faq['question'] ) ? trim( $ccs_faq['question'] ) : '';
		if ( '' !== $ccs_q ) {
			$ccs_svc_faqs[] = array( 'q' => $ccs_q, 'a' => wpautop( esc_html( isset( $ccs_faq['answer'] ) ? $ccs_faq['answer'] : '' ) ) );
		}
	}
	foreach ( array( 'faqs', 'resources/faqs' ) as $ccs_path ) {
		$ccs_svc_faq_page = $ccs_svc_faq_page ? $ccs_svc_faq_page : get_page_by_path( $ccs_path );
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
			<?php
			/*
			 * The starting price on a tab over the hero photo (the layout the
			 * user chose). It replaces the CQC card that sat here: the rating
			 * is in the footer of every page. Only where a published rate is
			 * mapped to this service and the hero has a photo to sit on.
			 */
			$ccs_svc_groups = function_exists( 'ccs_fee_groups_for_service' ) ? ccs_fee_groups_for_service( get_post_field( 'post_name', $post_id ) ) : array();
			$ccs_svc_fees   = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
			?>
			<?php if ( $ccs_svc_groups && isset( $ccs_svc_fees[ $ccs_svc_groups[0] ][0] ) ) : ?>
				<?php $ccs_svc_first = $ccs_svc_fees[ $ccs_svc_groups[0] ][0]; ?>
				<p class="service-hero__price" data-ccs-exception="rule 1: the starting price, set on the photo as a label">
					<strong><?php echo esc_html( $ccs_svc_first['weekday'] ); ?></strong>
					<span><?php echo esc_html( $ccs_svc_first['night'] ? __( 'a night', 'ccs-wp-theme' ) : __( 'an hour', 'ccs-wp-theme' ) ); ?></span>
					<small><?php esc_html_e( 'Starting rate', 'ccs-wp-theme' ); ?></small>
				</p>
			<?php endif; ?>
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
				<?php
				/*
				 * Photo-led layout (Nov 2026; the user called these pages "awfully
				 * laid out" and chose this structure). The left column is now, in
				 * order: the opening paragraph as a lede; what the service includes
				 * as ticked lists (each list the client wrote, under its own
				 * heading); and the remaining sections in the site's accordion.
				 * It was a long column of prose, with the lists pushed into cards in
				 * a band further down. The words are the client's own, unchanged.
				 */
				?>
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_svc_intro ) ) ) : ?>
					<div class="ccs-lede ccs-lede--large service-intro"><?php echo $ccs_svc_intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
				<?php endif; ?>

				<?php
				$ccs_svc_ticks = array();
				foreach ( $ccs_svc_lists as $ccs_sec ) {
					if ( preg_match_all( '#<li[^>]*>(.*?)</li>#s', $ccs_sec['body'], $ccs_svc_m ) ) {
						$ccs_svc_ticks[] = array( $ccs_sec['heading'], array_map( 'wp_strip_all_tags', $ccs_svc_m[1] ) );
					}
				}
				$ccs_svc_feature_items = array();
				foreach ( $features as $row ) {
					if ( ! empty( $row['feature_text'] ) ) {
						$ccs_svc_feature_items[] = $row['feature_text'];
					}
				}
				if ( $ccs_svc_feature_items ) {
					$ccs_svc_ticks[] = array( ccs_copy( 'service_included_title' ), $ccs_svc_feature_items );
				}
				?>
				<?php foreach ( $ccs_svc_ticks as $ccs_svc_tick ) : ?>
					<div class="service-included">
						<h2 class="service-included__title"><?php echo esc_html( ucfirst( strtolower( $ccs_svc_tick[0] ) ) ); ?></h2>
						<ul class="tick-list" role="list">
							<?php foreach ( $ccs_svc_tick[1] as $ccs_svc_item ) : ?>
								<li><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><?php echo esc_html( trim( $ccs_svc_item ) ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>

				<?php if ( ! empty( $ccs_svc_prose ) ) : ?>
					<div class="ccs-faq service-more">
						<?php foreach ( $ccs_svc_prose as $ccs_sec ) : ?>
							<details class="ccs-faq__item" name="service-more">
								<summary class="ccs-faq__q"><?php echo esc_html( $ccs_sec['heading'] ); ?></summary>
								<div class="ccs-faq__a"><?php echo $ccs_sec['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( function_exists( 'ccs_fee_groups_for_service' ) && ! ccs_fee_groups_for_service( get_post_field( 'post_name', $post_id ) ) ) : ?>
					<?php // No rate card is mapped to this service yet, so the page points to the published rates instead of showing none. ?>
					<p class="service-rates-link">
						<a class="ccs-text-link" href="<?php echo esc_url( function_exists( 'ccs_page_url' ) ? ccs_page_url( 'costs-and-funding' ) : home_url( '/costs-and-funding/' ) ); ?>"><?php esc_html_e( 'See our published rates', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</p>
				<?php endif; ?>

				<?php if ( $has_price ) : ?>
					<aside class="service-pricing" aria-labelledby="service-pricing-heading">
						<h2 id="service-pricing-heading" class="service-pricing__heading"><?php echo esc_html( ccs_copy( 'service_pricing_title' ) ); ?></h2>
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
								<?php echo esc_html( ccs_copy( 'service_pricing_note' ) ); ?>
							</p>
							<?php if ( $funding ) : ?>
								<div class="service-pricing__funding"><?php echo wp_kses_post( nl2br( esc_html( $funding ) ) ); ?></div>
							<?php endif; ?>
						</div>
					</aside>
				<?php endif; ?>

			</div>

			<aside class="service-sidebar" aria-labelledby="service-sidebar-heading">
				<div class="service-sidebar__sticky">
					<div class="service-sidebar__card">
						<p class="ccs-eyebrow"><?php echo esc_html( ccs_copy( 'service_callback_eyebrow' ) ); ?></p>
						<h2 id="service-sidebar-heading" class="service-sidebar__heading"><?php echo esc_html( ccs_copy( 'service_callback_title' ) ); ?></h2>
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
							<?php ccs_heard_about_field( 'service-cb-heard-about' ); ?>
							<input type="text" name="_company" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="ccs-honeypot">
							<button type="submit" class="btn btn-primary service-sidebar__submit"><?php esc_html_e( 'Request a call back', 'ccs-wp-theme' ); ?></button>
							<div id="callback-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
						</form>
					</div>
				</div>
			</aside>
		</div>
	</div>

	<?php
	/*
	 * What this service costs: the rate cards from the published fee schedule
	 * that apply to it (Nov 2026). Unique to each service page, and the
	 * question families most want answered. The shared "How care starts" steps
	 * that sat here were removed: the same three steps on every page made the
	 * service pages read as near-duplicates.
	 */
	get_template_part( 'template-parts/rate-table', null, array( 'service' => get_post_field( 'post_name', $post_id ) ) );
	?>

	<?php if ( ! empty( $ccs_svc_faqs ) ) : ?>
		<section class="ccs-section ccs-section--tint service-faqs" aria-labelledby="service-faqs-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="service-faqs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'service_faq_title' ) ); ?></h2>
						<?php if ( $ccs_svc_faq_page instanceof WP_Post ) : ?>
							<a class="ccs-text-link service-faqs__more" href="<?php echo esc_url( get_permalink( $ccs_svc_faq_page ) ); ?>"><?php echo esc_html( ccs_copy( 'service_faq_more' ) ); ?> <span aria-hidden="true">&rarr;</span></a>
						<?php endif; ?>
					</header>
					<?php
					/*
					 * Native <details>: keyboard and screen-reader behaviour for
					 * free, and the shared name makes them exclusive (one open at
					 * a time) without a script. See docs/BUILD-STANDARD.md §3.
					 */
					?>
					<div class="ccs-faq">
						<?php foreach ( $ccs_svc_faqs as $ccs_i => $ccs_faq ) : ?>
							<details class="ccs-faq__item" name="service-faq"<?php echo 0 === $ccs_i ? ' open' : ''; ?>>
								<summary class="ccs-faq__q"><?php echo esc_html( $ccs_faq['q'] ); ?></summary>
								<div class="ccs-faq__a"><?php echo wp_kses_post( $ccs_faq['a'] ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

	<?php if ( ! empty( $related ) ) : ?>
		<section class="service-related" aria-labelledby="service-related-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="service-related-heading" class="ccs-title">
						<?php
						echo ccs_copy_accent( 'service_related_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
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
				?>
				<ul class="svc-grid ccs-swipe" role="list">
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
								<span class="ccs-badge ccs-badge--ring svc-card__badge"><?php echo ccs_icon( ccs_service_icon( $rel->post_name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<h3 class="svc-card__title">
									<a href="<?php echo esc_url( get_permalink( $rel ) ); ?>" class="svc-card__link"><?php echo esc_html( get_the_title( $rel ) ); ?></a>
								</h3>
								<?php
								/*
								 * The starting price, not the excerpt: each service's excerpt is
								 * already its own hero intro, so repeating it here duplicated
								 * text across the service pages (Nov 2026).
								 */
								$ccs_rel_groups = function_exists( 'ccs_fee_groups_for_service' ) ? ccs_fee_groups_for_service( $rel->post_name ) : array();
								$ccs_rel_fees   = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
								if ( $ccs_rel_groups && isset( $ccs_rel_fees[ $ccs_rel_groups[0] ][0] ) ) :
									$ccs_rel_first = $ccs_rel_fees[ $ccs_rel_groups[0] ][0];
									?>
									<p class="svc-card__text">
										<?php
										/* translators: %s: price, e.g. £36.38. */
										echo esc_html( sprintf( $ccs_rel_first['night'] ? __( 'From %s a night', 'ccs-wp-theme' ) : __( 'From %s an hour', 'ccs-wp-theme' ), $ccs_rel_first['weekday'] ) );
										?>
									</p>
								<?php endif; ?>
								<span class="svc-card__cue" aria-hidden="true"><?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span class="svc-card__arrow">&rarr;</span></span>
							</div>
						</li>
					<?php endforeach; ?>
					<?php if ( count( $ccs_related_shown ) < 3 ) : ?>
						<li class="info-card service-related__hub">
							<span class="ccs-badge"><?php echo ccs_icon( 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<p class="info-card__label"><?php echo esc_html( ccs_copy( 'service_hub_label' ) ); ?></p>
							<h3 class="info-card__title">
								<a class="info-card__link" href="<?php echo esc_url( $ccs_services_hub_url ); ?>"><?php echo esc_html( ccs_copy( 'service_hub_title' ) ); ?></a>
							</h3>
							<p class="info-card__text"><?php echo esc_html( ccs_copy( 'service_hub_text' ) ); ?></p>
							<span class="info-card__cue" aria-hidden="true"><?php echo esc_html( ccs_copy( 'service_hub_link' ) ); ?> <span class="info-card__arrow">&rarr;</span></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/*
	 * No reviews block here (Nov 2026): the same three reviews on every
	 * service page made the pages read as near-duplicates to search engines.
	 * Reviews stay on the homepage.
	 */
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
