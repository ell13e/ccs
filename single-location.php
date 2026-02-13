<?php
/**
 * Single location template: hero, areas covered, partnerships, services, support, team.
 * Uses location meta: town, county, areas_covered, hospitals, CHC, council, support groups, team.
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

	$post_id   = get_the_ID();
	$town      = get_post_meta( $post_id, 'location_town', true );
	$county    = get_post_meta( $post_id, 'location_county', true );
	$postcode  = get_post_meta( $post_id, 'location_postcode_area', true );
	$areas_raw = get_post_meta( $post_id, 'location_areas_covered', true );
	$lat       = get_post_meta( $post_id, 'location_latitude', true );
	$lng       = get_post_meta( $post_id, 'location_longitude', true );
	$hospitals = get_post_meta( $post_id, 'location_local_hospitals', true );
	$gp        = get_post_meta( $post_id, 'location_local_gp_practices', true );
	$chc       = get_post_meta( $post_id, 'location_chc_contact', true );
	$council   = get_post_meta( $post_id, 'location_council_adult_services', true );
	$support   = get_post_meta( $post_id, 'location_local_support_groups', true );
	$team_size = get_post_meta( $post_id, 'location_team_size', true );
	$coord_name = get_post_meta( $post_id, 'location_coordinator_name', true );
	$coord_photo = get_post_meta( $post_id, 'location_coordinator_photo', true );
	$families  = get_post_meta( $post_id, 'location_families_supported', true );

	$town       = $town ?: get_the_title();
	$hospitals  = is_array( $hospitals ) ? $hospitals : array();
	$support    = is_array( $support ) ? $support : array();
	$areas_list = array_filter( array_map( 'trim', explode( "\n", (string) $areas_raw ) ) );
	$has_team   = ( $coord_name !== '' ) || ( (int) $coord_photo > 0 ) || ( (int) $team_size > 0 ) || ( (int) $families > 0 );

	$ccs_phone    = get_theme_mod( 'ccs_phone', '01234 567890' );
	$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';
?>

<main id="main" class="site-main site-main--location" role="main">

	<!-- 1. HERO -->
	<header class="location-hero">
		<div class="location-hero__inner container container--lg">
			<h1 class="location-hero__title"><?php echo esc_html( $town ); ?></h1>
			<?php if ( $county || $postcode ) : ?>
				<p class="location-hero__subtitle">
					<?php
					$parts = array_filter( array( $county, $postcode ? sprintf( __( 'Postcode area: %s', 'ccs-wp-theme' ), $postcode ) : '' ) );
					echo esc_html( implode( ' · ', $parts ) );
					?>
				</p>
			<?php endif; ?>
			<?php if ( $ccs_phone_tel ) : ?>
				<a href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>" class="btn btn-phone btn-lg location-hero__cta"><?php echo esc_html( $ccs_phone ); ?></a>
			<?php endif; ?>
		</div>
		<div class="location-hero__map">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', array( 'class' => 'location-hero__map-img', 'alt' => '' ) ); ?>
			<?php elseif ( $lat && $lng ) : ?>
				<div class="location-hero__map-placeholder" aria-hidden="true" style="--location-lat: <?php echo esc_attr( $lat ); ?>; --location-lng: <?php echo esc_attr( $lng ); ?>;">
					<span><?php esc_html_e( 'Map', 'ccs-wp-theme' ); ?></span>
				</div>
			<?php else : ?>
				<div class="location-hero__map-placeholder" aria-hidden="true"><span><?php esc_html_e( 'Map', 'ccs-wp-theme' ); ?></span></div>
			<?php endif; ?>
		</div>
	</header>

	<!-- 2. AREAS COVERED -->
	<?php if ( ! empty( $areas_list ) ) : ?>
		<section class="location-areas" aria-labelledby="location-areas-heading">
			<div class="location-areas__inner container container--lg">
				<h2 id="location-areas-heading" class="location-areas__heading"><?php esc_html_e( 'Areas we cover', 'ccs-wp-theme' ); ?></h2>
				<ul class="location-areas__list">
					<?php foreach ( $areas_list as $area ) : ?>
						<li><span class="location-areas__badge"><?php echo esc_html( $area ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<!-- 3. LOCAL PARTNERSHIPS -->
	<?php if ( ! empty( $hospitals ) || $chc || $council ) : ?>
		<section class="location-partners" aria-labelledby="location-partners-heading">
			<div class="location-partners__inner container container--lg">
				<h2 id="location-partners-heading" class="location-partners__heading"><?php esc_html_e( 'Local partnerships', 'ccs-wp-theme' ); ?></h2>

				<?php if ( ! empty( $hospitals ) ) : ?>
					<h3 class="location-partners__subheading"><?php esc_html_e( 'Hospitals we work with', 'ccs-wp-theme' ); ?></h3>
					<div class="location-partners__cards">
						<?php foreach ( $hospitals as $h ) :
							$name = isset( $h['hospital_name'] ) ? trim( $h['hospital_name'] ) : '';
							if ( $name === '' ) {
								continue;
							}
							$phone   = isset( $h['hospital_phone'] ) ? trim( $h['hospital_phone'] ) : '';
							$address = isset( $h['hospital_address'] ) ? trim( $h['hospital_address'] ) : '';
						?>
							<div class="location-partner-card card">
								<div class="location-partner-card__body card-body">
									<h4 class="location-partner-card__title"><?php echo esc_html( $name ); ?></h4>
									<?php if ( $phone ) : ?>
										<p class="location-partner-card__phone">
											<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
										</p>
									<?php endif; ?>
									<?php if ( $address ) : ?>
										<p class="location-partner-card__address"><?php echo esc_html( $address ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $chc ) : ?>
					<div class="location-partners__contact location-partners__contact--chc">
						<h3 class="location-partners__contact-heading"><?php esc_html_e( 'CHC contact', 'ccs-wp-theme' ); ?></h3>
						<div class="location-partners__contact-body"><?php echo wp_kses_post( nl2br( esc_html( $chc ) ) ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $council ) : ?>
					<div class="location-partners__contact location-partners__contact--council">
						<h3 class="location-partners__contact-heading"><?php esc_html_e( 'Council adult services', 'ccs-wp-theme' ); ?></h3>
						<div class="location-partners__contact-body"><?php echo wp_kses_post( nl2br( esc_html( $council ) ) ); ?></div>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- 4. SERVICES PROVIDED -->
	<section class="location-services" aria-labelledby="location-services-heading">
		<div class="location-services__inner container container--lg">
			<h2 id="location-services-heading" class="location-services__heading"><?php esc_html_e( 'Services we provide here', 'ccs-wp-theme' ); ?></h2>
			<?php if ( get_the_content() ) : ?>
				<div class="location-services__summary entry-content">
					<?php the_content(); ?>
				</div>
			<?php else : ?>
				<p class="location-services__summary"><?php esc_html_e( 'We offer complex care, personal care and companionship across this area. Care plans are tailored to you and your family.', 'ccs-wp-theme' ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="btn btn-secondary location-services__link"><?php esc_html_e( 'View all services', 'ccs-wp-theme' ); ?></a>
		</div>
	</section>

	<!-- 5. LOCAL SUPPORT -->
	<?php if ( ! empty( $support ) || $gp ) : ?>
		<section class="location-support" aria-labelledby="location-support-heading">
			<div class="location-support__inner container container--lg">
				<h2 id="location-support-heading" class="location-support__heading"><?php esc_html_e( 'Local support', 'ccs-wp-theme' ); ?></h2>

				<?php if ( ! empty( $support ) ) : ?>
					<h3 class="location-support__subheading"><?php esc_html_e( 'Support groups', 'ccs-wp-theme' ); ?></h3>
					<ul class="location-support__groups">
						<?php foreach ( $support as $s ) :
							$gname = isset( $s['group_name'] ) ? trim( $s['group_name'] ) : '';
							$gcontact = isset( $s['group_contact'] ) ? trim( $s['group_contact'] ) : '';
							if ( $gname === '' && $gcontact === '' ) {
								continue;
							}
						?>
							<li class="location-support__group">
								<?php if ( $gname ) : ?><strong><?php echo esc_html( $gname ); ?></strong><?php endif; ?>
								<?php if ( $gcontact ) : ?>
									<span class="location-support__group-contact"><?php echo wp_kses_post( nl2br( esc_html( $gcontact ) ) ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $gp ) : ?>
					<h3 class="location-support__subheading"><?php esc_html_e( 'Community resources', 'ccs-wp-theme' ); ?></h3>
					<div class="location-support__resources"><?php echo wp_kses_post( nl2br( esc_html( $gp ) ) ); ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- 6. TEAM INFO -->
	<?php if ( $has_team ) : ?>
		<section class="location-team" aria-labelledby="location-team-heading">
			<div class="location-team__inner container container--md">
				<h2 id="location-team-heading" class="location-team__heading"><?php esc_html_e( 'Your local team', 'ccs-wp-theme' ); ?></h2>
				<div class="location-team__content">
					<?php if ( (int) $coord_photo > 0 ) : ?>
						<?php echo wp_get_attachment_image( (int) $coord_photo, 'medium', false, array( 'class' => 'location-team__photo' ) ); ?>
					<?php endif; ?>
					<div class="location-team__details">
						<?php if ( $coord_name ) : ?>
							<p class="location-team__name"><?php echo esc_html( $coord_name ); ?></p>
							<p class="location-team__role"><?php esc_html_e( 'Location coordinator', 'ccs-wp-theme' ); ?></p>
						<?php endif; ?>
						<dl class="location-team__stats">
							<?php if ( (int) $team_size > 0 ) : ?>
								<div class="location-team__stat">
									<dt><?php esc_html_e( 'Team size', 'ccs-wp-theme' ); ?></dt>
									<dd><?php echo esc_html( number_format_i18n( (int) $team_size ) ); ?></dd>
								</div>
							<?php endif; ?>
							<?php if ( (int) $families > 0 ) : ?>
								<div class="location-team__stat">
									<dt><?php esc_html_e( 'Families supported', 'ccs-wp-theme' ); ?></dt>
									<dd><?php echo esc_html( number_format_i18n( (int) $families ) ); ?></dd>
								</div>
							<?php endif; ?>
						</dl>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
	// Schema.org LocalBusiness (service area / location)
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'name'        => get_bloginfo( 'name' ) . ' – ' . $town,
		'description' => wp_trim_words( get_the_content(), 40 ) ?: sprintf( __( 'Care services in %s and surrounding areas.', 'ccs-wp-theme' ), $town ),
		'url'         => get_permalink(),
		'areaServed'  => array(
			'@type' => 'City',
			'name'  => $town,
		),
	);
	if ( $county ) {
		$schema['areaServed']['containedInPlace'] = array( '@type' => 'AdministrativeArea', 'name' => $county );
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
