<?php
/**
 * Single location (area) page.
 *
 * Tint hero with breadcrumb, "Home care in {town}", the excerpt and the two
 * actions; then the page copy beside an "areas we cover" card and any local
 * hospitals and contacts; the service cards on the tint band; local support
 * and team details when they exist; the shared CTA band.
 *
 * The old hero had a grey gradient box captioned "Map" on every location that
 * had no featured image, which was all of them. It is gone: the villages
 * covered are the useful answer to "do you come to me?", so they lead instead.
 *
 * Uses location meta: town, county, postcode area, areas covered, lat/lng (for
 * schema), hospitals, CHC and council contacts, support groups, GP/community
 * resources, coordinator, team size and families supported.
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

	$post_id     = get_the_ID();
	$town        = get_post_meta( $post_id, 'location_town', true );
	$county      = get_post_meta( $post_id, 'location_county', true );
	$postcode    = get_post_meta( $post_id, 'location_postcode_area', true );
	$areas_raw   = get_post_meta( $post_id, 'location_areas_covered', true );
	$lat         = get_post_meta( $post_id, 'location_latitude', true );
	$lng         = get_post_meta( $post_id, 'location_longitude', true );
	$hospitals   = get_post_meta( $post_id, 'location_local_hospitals', true );
	$gp          = get_post_meta( $post_id, 'location_local_gp_practices', true );
	$chc         = get_post_meta( $post_id, 'location_chc_contact', true );
	$council     = get_post_meta( $post_id, 'location_council_adult_services', true );
	$support     = get_post_meta( $post_id, 'location_local_support_groups', true );
	$team_size   = get_post_meta( $post_id, 'location_team_size', true );
	$coord_name  = get_post_meta( $post_id, 'location_coordinator_name', true );
	$coord_photo = get_post_meta( $post_id, 'location_coordinator_photo', true );
	$families    = get_post_meta( $post_id, 'location_families_supported', true );

	$town       = $town ? $town : get_the_title();
	$hospitals  = is_array( $hospitals ) ? $hospitals : array();
	$support    = is_array( $support ) ? $support : array();
	$areas_list = array_filter( array_map( 'trim', explode( "\n", (string) $areas_raw ) ) );
	$has_team   = ( $coord_name !== '' ) || ( (int) $coord_photo > 0 ) || ( (int) $team_size > 0 ) || ( (int) $families > 0 );

	$hospital_names = array();
	foreach ( $hospitals as $h ) {
		$name = isset( $h['hospital_name'] ) ? trim( $h['hospital_name'] ) : '';
		if ( '' !== $name ) {
			$hospital_names[] = array(
				'name'    => $name,
				'phone'   => isset( $h['hospital_phone'] ) ? trim( $h['hospital_phone'] ) : '',
				'address' => isset( $h['hospital_address'] ) ? trim( $h['hospital_address'] ) : '',
			);
		}
	}

	$ccs_phone     = get_theme_mod( 'ccs_phone', '01622 809 881' );
	$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
	$ccs_contact   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
	/*
	 * The hub page, not get_post_type_archive_link( 'service' ): that archive is
	 * disabled (see template-parts/breadcrumb.php), so "View all services" here
	 * used to 301 to the homepage.
	 */
	$ccs_services_hub = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
	?>

<main id="main" class="site-main site-main--location" role="main">

	<header class="page-hero location-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title">
					<?php
					/* translators: %s: town name. */
					echo esc_html( sprintf( __( 'Home care in %s', 'ccs-wp-theme' ), $town ) );
					?>
				</h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="page-hero__intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<div class="ccs-actions location-hero__actions">
					<a href="<?php echo esc_url( $ccs_contact ); ?>" class="btn btn-primary"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
					<?php if ( $ccs_phone_tel ) : ?>
						<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn btn-quiet">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_phone ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>

	<section class="ccs-section location-about" aria-labelledby="location-about-heading">
		<div class="container container--lg">
			<div class="location-about__grid">
				<div class="location-about__main">
					<header class="ccs-head">
						<h2 id="location-about-heading" class="ccs-title">
							<?php
							/* translators: %s: town name, drawn with a brush underline. */
							printf( esc_html__( 'A team that knows %s', 'ccs-wp-theme' ), ccs_accent( $town ) );
							?>
						</h2>
					</header>
					<div class="page-body page-body--flush entry-content">
						<?php if ( trim( get_the_content() ) !== '' ) : ?>
							<?php the_content(); ?>
						<?php else : ?>
							<p>
								<?php
								/* translators: %s: town name. */
								echo esc_html( sprintf( __( 'We provide domiciliary, respite and complex care in %s and the villages around it, with a small team matched to you and kept consistent.', 'ccs-wp-theme' ), $town ) );
								?>
							</p>
						<?php endif; ?>
					</div>
				</div>

				<?php
				/*
				 * One panel with groups divided by hairlines, not a card per group.
				 * "Working alongside" was a full card for a single hospital name: a
				 * box with almost nothing in it (CCS/CLAUDE.md rule 1).
				 */
				$ccs_has_areas   = ! empty( $areas_list ) || $county || $postcode;
				$ccs_has_partner = ! empty( $hospital_names ) || $chc || $council;
				?>
				<?php if ( $ccs_has_areas || $ccs_has_partner || $has_team ) : ?>
				<aside class="location-about__aside location-card" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: town name. */ __( 'Coverage around %s', 'ccs-wp-theme' ), $town ) ); ?>">
					<?php if ( $ccs_has_areas ) : ?>
						<div class="location-card__group">
							<div class="location-card__head">
								<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<h3 class="location-card__title"><?php esc_html_e( 'Areas we cover', 'ccs-wp-theme' ); ?></h3>
							</div>
							<?php if ( ! empty( $areas_list ) ) : ?>
								<ul class="location-card__pills" role="list">
									<li><?php echo esc_html( $town ); ?></li>
									<?php foreach ( $areas_list as $area ) : ?>
										<li><?php echo esc_html( $area ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( $county || $postcode ) : ?>
								<p class="location-card__meta">
									<?php
									$parts = array_filter(
										array(
											$county,
											/* translators: %s: postcode area, e.g. ME. */
											$postcode ? sprintf( __( '%s postcodes', 'ccs-wp-theme' ), $postcode ) : '',
										)
									);
									echo esc_html( implode( ', ', $parts ) );
									?>
								</p>
							<?php endif; ?>
							<p class="location-card__note">
								<?php esc_html_e( 'Just outside these? Ring and ask. The honest answer depends on whether we can staff it consistently.', 'ccs-wp-theme' ); ?>
							</p>
						</div>
					<?php endif; ?>

					<?php if ( $ccs_has_partner ) : ?>
						<div class="location-card__group">
							<h3 class="location-card__title location-card__title--sub"><?php esc_html_e( 'Working alongside', 'ccs-wp-theme' ); ?></h3>
							<dl class="location-card__list">
								<?php foreach ( $hospital_names as $hosp ) : ?>
									<div>
										<dt><?php echo esc_html( $hosp['name'] ); ?></dt>
										<?php if ( $hosp['address'] ) : ?>
											<dd><?php echo esc_html( $hosp['address'] ); ?></dd>
										<?php endif; ?>
										<?php if ( $hosp['phone'] ) : ?>
											<dd><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $hosp['phone'] ) ); ?>"><?php echo esc_html( $hosp['phone'] ); ?></a></dd>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
								<?php if ( $chc ) : ?>
									<div>
										<dt><?php esc_html_e( 'NHS Continuing Healthcare', 'ccs-wp-theme' ); ?></dt>
										<dd><?php echo nl2br( esc_html( $chc ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then line breaks added. ?></dd>
									</div>
								<?php endif; ?>
								<?php if ( $council ) : ?>
									<div>
										<dt><?php esc_html_e( 'Council adult services', 'ccs-wp-theme' ); ?></dt>
										<dd><?php echo nl2br( esc_html( $council ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then line breaks added. ?></dd>
									</div>
								<?php endif; ?>
							</dl>
						</div>
					<?php endif; ?>

					<?php if ( $has_team ) : ?>
						<div class="location-card__group">
							<?php if ( (int) $coord_photo > 0 || $coord_name ) : ?>
								<div class="location-card__person">
									<?php if ( (int) $coord_photo > 0 ) : ?>
										<?php echo wp_get_attachment_image( (int) $coord_photo, 'thumbnail', false, array( 'class' => 'location-card__photo', 'alt' => '' ) ); ?>
									<?php endif; ?>
									<?php if ( $coord_name ) : ?>
										<div>
											<h3 class="location-card__title"><?php echo esc_html( $coord_name ); ?></h3>
											<p class="location-card__meta"><?php esc_html_e( 'Location coordinator', 'ccs-wp-theme' ); ?></p>
										</div>
									<?php endif; ?>
								</div>
							<?php else : ?>
								<h3 class="location-card__title"><?php esc_html_e( 'Your local team', 'ccs-wp-theme' ); ?></h3>
							<?php endif; ?>
							<?php if ( (int) $team_size > 0 || (int) $families > 0 ) : ?>
								<dl class="location-card__stats">
									<?php if ( (int) $team_size > 0 ) : ?>
										<div>
											<dt><?php esc_html_e( 'Carers in the team', 'ccs-wp-theme' ); ?></dt>
											<dd><?php echo esc_html( number_format_i18n( (int) $team_size ) ); ?></dd>
										</div>
									<?php endif; ?>
									<?php if ( (int) $families > 0 ) : ?>
										<div>
											<dt><?php esc_html_e( 'Families supported', 'ccs-wp-theme' ); ?></dt>
											<dd><?php echo esc_html( number_format_i18n( (int) $families ) ); ?></dd>
										</div>
									<?php endif; ?>
								</dl>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</aside>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="ccs-section ccs-section--tint location-services" aria-labelledby="location-services-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="location-services-heading" class="ccs-title">
					<?php
					/* translators: %s: town name. */
					echo esc_html( sprintf( __( 'Care we provide in %s', 'ccs-wp-theme' ), $town ) );
					?>
				</h2>
			</header>
			<?php get_template_part( 'template-parts/service-cards' ); ?>
			<div class="ccs-section-foot ccs-actions ccs-actions--center">
				<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_services_hub ); ?>"><?php esc_html_e( 'See all our services', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>
	</section>

	<?php
	$support_items = array();
	foreach ( $support as $s ) {
		$gname    = isset( $s['group_name'] ) ? trim( $s['group_name'] ) : '';
		$gcontact = isset( $s['group_contact'] ) ? trim( $s['group_contact'] ) : '';
		if ( '' !== $gname || '' !== $gcontact ) {
			$support_items[] = array(
				'name'    => $gname,
				'contact' => $gcontact,
			);
		}
	}
	?>
	<?php if ( ! empty( $support_items ) || $gp ) : ?>
		<section class="ccs-section ccs-section--flush-bottom location-support" aria-labelledby="location-support-heading">
			<div class="container container--lg">
				<header class="ccs-head">
					<h2 id="location-support-heading" class="ccs-title">
						<?php
						/* translators: %s: town name. */
						echo esc_html( sprintf( __( 'Local support in %s', 'ccs-wp-theme' ), $town ) );
						?>
					</h2>
				</header>
				<ul class="feature-grid" role="list">
					<?php foreach ( $support_items as $item ) : ?>
						<li class="feature-card">
							<?php if ( $item['name'] ) : ?>
								<h3 class="feature-card__title"><?php echo esc_html( $item['name'] ); ?></h3>
							<?php endif; ?>
							<?php if ( $item['contact'] ) : ?>
								<p class="feature-card__text"><?php echo nl2br( esc_html( $item['contact'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then line breaks added. ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
					<?php if ( $gp ) : ?>
						<li class="feature-card">
							<h3 class="feature-card__title"><?php esc_html_e( 'Community resources', 'ccs-wp-theme' ); ?></h3>
							<p class="feature-card__text"><?php echo nl2br( esc_html( $gp ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then line breaks added. ?></p>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>

</main>

	<?php
	// Schema.org LocalBusiness (service area / location).
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'name'        => get_bloginfo( 'name' ) . ' – ' . $town,
		/* translators: %s: town name. */
		'description' => wp_trim_words( wp_strip_all_tags( get_the_content() ), 40 ) ?: sprintf( __( 'Care services in %s and surrounding areas.', 'ccs-wp-theme' ), $town ),
		'url'         => get_permalink(),
		'areaServed'  => array(
			'@type' => 'City',
			'name'  => $town,
		),
	);
	if ( $county ) {
		$schema['areaServed']['containedInPlace'] = array(
			'@type' => 'AdministrativeArea',
			'name'  => $county,
		);
	}
	if ( $ccs_phone_tel ) {
		$schema['telephone'] = $ccs_phone_tel;
	}
	if ( $lat && $lng ) {
		$schema['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		);
	}
	?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema ); ?></script>

	<?php
endwhile;
get_footer();
