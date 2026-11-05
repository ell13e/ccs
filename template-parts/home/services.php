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

$services_overview_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
$contact_url           = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );

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
		'intro'    => __( 'Everyday help at home. Washing, dressing, meals and medication, plus company and a hand with the housework, done at your pace.', 'ccs-wp-theme' ),
		'link_url' => home_url( '/services/domiciliary-care/' ),
	),
	array(
		'title'    => __( 'Respite Care', 'ccs-wp-theme' ),
		'intro'    => __( 'Short breaks so family carers can properly rest. A few hours, an overnight, or longer, with someone you’ve already met stepping in.', 'ccs-wp-theme' ),
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

/*
 * Imagery. These cards were three bordered boxes of prose, which is what made
 * the section read as a template rather than as this company's work — and the
 * theme already ships photographs named for exactly these three services that
 * nothing was using.
 *
 * A service post's own featured image wins when it has one; otherwise fall back
 * to the packaged asset. Cards render around 400px wide, so the 550px fallbacks
 * hold up here in a way they would not in a full-bleed hero.
 */
$ccs_service_fallback_images = array(
	'domiciliary-care' => 'site-photos/ccs-domiciliary-care.webp',
	'respite-care'     => 'site-photos-extra/ccs-respite-care-guitar.webp',
	'complex-care'     => 'site-photos/ccs-complex-care-maidstone-24-7.webp',
);

/*
 * Intrinsic dimensions travel with the URL. The card's own box is fixed by
 * `aspect-ratio: 4 / 3` in homepage.css, so layout is already stable without
 * them — but an <img> with no width/height still reports as a CLS risk to
 * every auditing tool, and leaves the browser nothing to work with in the
 * (rare) event the stylesheet is slow or absent. Both sources can supply real
 * numbers, so neither has to guess: attachments carry theirs in the metadata,
 * and packaged files are measured through wp_getimagesize(), which caches so
 * this is not a filesystem read on every request.
 */
foreach ( $services as $i => $svc ) {
	$image  = '';
	$width  = 0;
	$height = 0;

	if ( ! empty( $service_posts[ $i ] ) && has_post_thumbnail( $service_posts[ $i ] ) ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $service_posts[ $i ] ), 'large' );
		if ( is_array( $src ) && ! empty( $src[0] ) ) {
			$image  = $src[0];
			$width  = isset( $src[1] ) ? (int) $src[1] : 0;
			$height = isset( $src[2] ) ? (int) $src[2] : 0;
		}
	}

	if ( ! $image && isset( $service_slugs[ $i ], $ccs_service_fallback_images[ $service_slugs[ $i ] ] ) ) {
		$rel  = $ccs_service_fallback_images[ $service_slugs[ $i ] ];
		$path = get_template_directory() . '/assets/images/' . $rel;
		if ( file_exists( $path ) ) {
			$image = get_template_directory_uri() . '/assets/images/' . $rel;
			$size  = wp_getimagesize( $path );
			if ( is_array( $size ) ) {
				$width  = isset( $size[0] ) ? (int) $size[0] : 0;
				$height = isset( $size[1] ) ? (int) $size[1] : 0;
			}
		}
	}

	$services[ $i ]['image']        = $image;
	$services[ $i ]['image_width']  = $width;
	$services[ $i ]['image_height'] = $height;
}
?>

<?php
/*
 * One icon per service, by position in $service_slugs (domiciliary, respite,
 * complex). Set in the shared badge (ccs_icon() + .ccs-badge) so these match
 * every other icon on the page.
 */
$ccs_service_icons = array( 'home', 'cup', 'pulse' );
?>

<section class="home-services" aria-labelledby="home-services-heading">
	<div class="container container--lg">

		<header class="ccs-head ccs-head--split">
			<h2 id="home-services-heading" class="ccs-title">
				<?php
				esc_html_e( 'Home care services in Maidstone and across Kent', 'ccs-wp-theme' );
				?>
			</h2>
			<p class="ccs-lede">
				<?php esc_html_e( 'Some people need a hand for an hour in the morning. Others need someone there through the night, or a team trained for more complex health needs. We’ll work out which it is with you, before anything is agreed.', 'ccs-wp-theme' ); ?>
			</p>
		</header>

		<?php
		/*
		 * Card anatomy, from the ElderlyCare X service cards: photograph on top, a
		 * white panel lapping over its lower edge, and the service's badge sitting
		 * across the seam. The overlap is what turns "photo with a caption" into a
		 * designed object. The whole card is one link (stretched ::after on the
		 * title link), so there is one large target and the title is read once.
		 */
		?>
		<ul class="home-services__grid" role="list">
			<?php foreach ( $services as $i => $svc ) : ?>
				<li class="svc-card">
					<?php if ( ! empty( $svc['image'] ) ) : ?>
						<div class="svc-card__media">
							<img
								src="<?php echo esc_url( $svc['image'] ); ?>"
								alt=""
								<?php if ( ! empty( $svc['image_width'] ) && ! empty( $svc['image_height'] ) ) : ?>
									width="<?php echo esc_attr( (string) $svc['image_width'] ); ?>"
									height="<?php echo esc_attr( (string) $svc['image_height'] ); ?>"
								<?php endif; ?>
								loading="lazy"
								decoding="async"
							>
						</div>
					<?php endif; ?>
					<div class="svc-card__body">
						<span class="ccs-badge ccs-badge--ring svc-card__badge"><?php echo ccs_icon( $ccs_service_icons[ $i ] ?? 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<h3 class="svc-card__title">
							<a href="<?php echo esc_url( $svc['link_url'] ); ?>" class="svc-card__link"><?php echo esc_html( $svc['title'] ); ?></a>
						</h3>
						<p class="svc-card__text"><?php echo esc_html( $svc['intro'] ); ?></p>
						<span class="svc-card__cue" aria-hidden="true">
							<?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span class="svc-card__arrow">&rarr;</span>
						</span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="home-services__cta">
			<p class="home-services__cta-text">
				<?php esc_html_e( 'Tell us what’s happening and we’ll say honestly whether we can help.', 'ccs-wp-theme' ); ?>
			</p>
			<div class="ccs-actions ccs-actions--center">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_overview_url ); ?>" class="btn btn-quiet">
					<?php esc_html_e( 'See all our services', 'ccs-wp-theme' ); ?>
				</a>
			</div>
		</div>

	</div>
</section>
