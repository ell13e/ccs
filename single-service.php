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
	 * Related services with a photograph come first (Nov 2026). Five of the
	 * eight services have no photo yet, and a row of three placeholder tiles
	 * under "Other ways we can help" read as unfinished. The pool is every
	 * other service in the site's order; the ones with a featured image lead.
	 */
	if ( function_exists( 'ccs_ordered_services' ) ) {
		$ccs_rel_pool = array_values(
			array_filter(
				ccs_ordered_services(),
				static function ( $svc ) use ( $post_id ) {
					return (int) $svc->ID !== (int) $post_id;
				}
			)
		);
		if ( $ccs_rel_pool ) {
			$related = array_merge(
				array_filter( $ccs_rel_pool, 'has_post_thumbnail' ),
				array_filter(
					$ccs_rel_pool,
					static function ( $svc ) {
						return ! has_post_thumbnail( $svc );
					}
				)
			);
		}
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
	// The copy pack's questions for this service lead (inc/service-copy.php); the client's own, from the editor, still win.
	$ccs_svc_pack = function_exists( 'ccs_service_copy' ) ? ccs_service_copy( get_post_field( 'post_name', $post_id ) ) : array();
	if ( ! $ccs_svc_faqs && ! empty( $ccs_svc_pack['faqs'] ) ) {
		foreach ( $ccs_svc_pack['faqs'] as $ccs_faq ) {
			$ccs_svc_faqs[] = array( 'q' => $ccs_faq[0], 'a' => wpautop( esc_html( $ccs_faq[1] ) ) );
		}
		$ccs_svc_faqs[] = array( 'q' => __( 'How can care be paid for?', 'ccs-wp-theme' ), 'a' => wpautop( esc_html__( 'Some people pay for their own care. Others have part or all of it paid by Kent County Council after a free needs assessment, or by the NHS through Continuing Healthcare when the main need is a health need. Our Costs page lists every rate, and we can talk the options through with you.', 'ccs-wp-theme' ) ) );
	}
	// No questions of its own in the editor: the built-in plain-English set for this kind of care (inc/service-guides.php).
	if ( ! $ccs_svc_faqs && function_exists( 'ccs_service_guide_faqs' ) ) {
		foreach ( ccs_service_guide_faqs( get_post_field( 'post_name', $post_id ) ) as $ccs_faq ) {
			$ccs_svc_faqs[] = array( 'q' => $ccs_faq['q'], 'a' => wpautop( esc_html( $ccs_faq['a'] ) ) );
		}
	}
	$ccs_svc_topic = function_exists( 'ccs_more_faqs_for_service' ) ? ccs_more_faqs_for_service( get_post_field( 'post_name', $post_id ) ) : null;
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
	<?php
	/*
	 * The site's standard header (Nov 2026 redesign). The service's photo
	 * moved into the first section of the body, where it sits beside the
	 * opening text with the starting price on a tab, so a service with a
	 * small photo and one with none open the same way.
	 */
	?>
	<header class="page-hero<?php echo $ccs_service_has_photo ? ' page-hero--photo' : ''; ?>">
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

	<?php
	/*
	 * The body is template-parts/service-body.php (Nov 2026): what this care
	 * is, who it helps, how its options differ, what it costs beside the
	 * call-back card, then "Good to know". The call-back card is built here,
	 * captured, and handed to the part, which decides where it sits.
	 */
	ob_start();
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
							<button type="submit" class="btn btn-accent service-sidebar__submit"><?php esc_html_e( 'Request a call back', 'ccs-wp-theme' ); ?></button>
							<div id="callback-form-message" data-ccs-form-message class="ccs-form-message" role="alert" aria-live="polite" hidden></div>
						</form>
	<?php
	$ccs_svc_form = ob_get_clean();
	$ccs_svc_more = ! empty( $ccs_svc_prose ) || ( count( $ccs_svc_lists ) + ( array_filter( wp_list_pluck( $features, 'feature_text' ) ) ? 1 : 0 ) ) > 1;
	get_template_part(
		'template-parts/service-body',
		null,
		array(
			'post_id'   => $post_id,
			'intro'     => $ccs_svc_intro,
			'lists'     => $ccs_svc_lists,
			'prose'     => $ccs_svc_prose,
			'features'  => $features,
			'form'      => $ccs_svc_form,
			'phone'     => $ccs_phone,
			'phone_tel' => $ccs_phone_tel,
			'hours'     => $ccs_office_hrs,
		)
	);
	?>

	<?php if ( ! empty( $ccs_svc_faqs ) ) : ?>
		<section class="ccs-section ccs-section--sm service-faqs" aria-labelledby="service-faqs-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="service-faqs-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'service_faq_title' ) ); ?></h2>
					<?php if ( $ccs_svc_faq_page instanceof WP_Post ) : ?>
						<p class="ccs-lede">
							<?php
							printf(
								/* translators: %s: link to the FAQs page. */
								esc_html__( 'Can’t find the answer? %s, or ring us.', 'ccs-wp-theme' ),
								// Straight to this service's own topic on the FAQs page where there is one; the page opens it.
								$ccs_svc_topic
									? '<a href="' . esc_url( get_permalink( $ccs_svc_faq_page ) . '#' . $ccs_svc_topic['id'] ) . '">' . esc_html__( 'See more questions about this kind of care', 'ccs-wp-theme' ) . '</a>'
									: '<a href="' . esc_url( get_permalink( $ccs_svc_faq_page ) ) . '">' . esc_html__( 'See all our questions and answers', 'ccs-wp-theme' ) . '</a>'
							);
							?>
						</p>
					<?php endif; ?>
				</header>
				<?php
				// Full-width rows, the open one in deep plum (after the service pages the client pointed to).
				$ccs_svc_faq_items = array();
				foreach ( $ccs_svc_faqs as $ccs_faq ) {
					$ccs_svc_faq_items[] = array( 'heading' => $ccs_faq['q'], 'body' => wp_kses_post( $ccs_faq['a'] ) );
				}
				get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_svc_faq_items, 'name' => 'service-faq' ) );
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/*
	 * Where this service is provided: a line of town links (Nov 2026). No
	 * service page linked to a town before. The towns come from the same
	 * setting the area pages read, so none is claimed that does not get it.
	 */
	$ccs_svc_towns = function_exists( 'ccs_service_areas' ) ? ccs_service_areas( get_post_field( 'post_name', $post_id ) ) : array();
	?>
	<?php if ( $ccs_svc_towns ) : ?>
		<section class="ccs-section ccs-section--sm svc-where" aria-labelledby="svc-where-heading">
			<div class="container container--lg">
				<h2 id="svc-where-heading" class="svc-where__title">
					<?php
					/* translators: %s: service name in lower case. */
					echo esc_html( sprintf( __( 'Where we provide %s', 'ccs-wp-theme' ), function_exists( 'ccs_service_menu_label' ) ? strtolower( ccs_service_menu_label( get_the_title( $post_id ) ) ) : strtolower( get_the_title( $post_id ) ) ) );
					?>
				</h2>
				<ul class="svc-where__list" role="list">
					<?php foreach ( $ccs_svc_towns as $ccs_svc_town ) : ?>
						<li><a class="btn btn-quiet" href="<?php echo esc_url( get_permalink( $ccs_svc_town ) ); ?>"><?php echo esc_html( preg_replace( '/^(Home care in |Care in )|,\s*Kent$|:.*$/i', '', get_the_title( $ccs_svc_town ) ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
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
				/*
				 * A row of like cards (client, Nov 2026: "companionship looks so
				 * different?", of one card with an icon tile and no price beside
				 * two with a photo and a price). When at least two other services
				 * have a photograph, only those are shown, and the last place is
				 * the link to every service. A chosen photo counts as well as a
				 * featured image (ccs_service_has_photo()).
				 */
				$ccs_related_photo = array_values( array_filter( $ccs_related_shown, 'ccs_service_has_photo' ) );
				if ( count( $ccs_related_photo ) >= 2 ) {
					$ccs_related_shown = $ccs_related_photo;
				}
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
							<?php elseif ( ccs_service_chosen_photo( $rel->post_name ) ) : ?>
								<div class="svc-card__media">
									<?php echo ccs_service_card_photo( $rel->post_name, '(min-width: 1280px) 400px, (min-width: 860px) 31vw, 92vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_service_card_photo(). ?>
								</div>
							<?php else : ?>
								<?php // No photo yet: the service's icon tile in the photo's place, as template-parts/service-cards.php. Without it the panel's overlap pulled the card up over the heading. ?>
								<div class="svc-card__media svc-card__media--icon" aria-hidden="true" data-ccs-exception="rule 1: stands in for the card's photo until one is chosen; the whole card is the link"><?php echo ccs_icon( ccs_service_icon( $rel->post_name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></div>
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
