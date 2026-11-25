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
					/*
					 * A page may set its own heading (location_heading): Thanet
					 * names its three towns, and areas without domiciliary care say what
					 * they do get (inc/location-services.php).
					 */
					$ccs_heading = get_post_meta( $post_id, 'location_heading', true );
					/* translators: %s: town name. */
					echo esc_html( $ccs_heading ? $ccs_heading : sprintf( __( 'Home care in %s', 'ccs-wp-theme' ), $town ) );
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
				<?php
				/*
				 * Areas written for professionals (location_audience; none at present, for
				 * case managers) point to the referral page as well.
				 */
				if ( 'professional' === get_post_meta( $post_id, 'location_audience', true ) ) :
					$ccs_refer = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'referral-information' ) : home_url( '/resources/referral-information/' );
					?>
					<p class="location-hero__refer">
						<a class="ccs-text-link" href="<?php echo esc_url( $ccs_refer ); ?>"><?php esc_html_e( 'Referral information for case managers', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</p>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/cqc-hero-card' ); ?>
		</div>
	</header>

	<section class="ccs-section location-about" aria-labelledby="location-about-heading">
		<div class="container container--lg">
			<div class="location-about__grid">
				<div class="location-about__main">
					<header class="ccs-head">
						<?php
						/*
						 * One drawing per area (approved Nov 2026), so the areas don't all
						 * carry the same picture. Each has a local hook where there is one
						 * (tea rooms for Tunbridge Wells, hop farms for Paddock Wood). A new
						 * area falls back to the bird house until it is given its own.
						 */
						$ccs_area_doodles = array(
							'ashford'         => 'bird-house',
							'maidstone'       => 'tree',
							'medway'          => 'pen-and-ink',
							'tunbridge-wells' => 'tea-cup',
							'tonbridge'       => 'wellies',
							'paddock-wood'    => 'wheelbarrow',
							'west-malling'    => 'flowers',
							'aylesford'       => 'bird',
							'snodland'        => 'watering-can-flowers',
							'whitstable'      => 'sun-hat',
							'herne-bay'       => 'sun-and-clouds',
							'thanet'          => 'paint-palette',
						);
						$ccs_town_key     = sanitize_title( $town );
						$ccs_area_doodle  = isset( $ccs_area_doodles[ $ccs_town_key ] ) ? $ccs_area_doodles[ $ccs_town_key ] : 'bird-house';
						echo ccs_doodle( $ccs_area_doodle, 'doodle--above' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle().
						?>
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
								/* translators: 1: the care offered, e.g. "Respite and complex care", 2: town name. */
								echo esc_html( sprintf( __( '%1$s in %2$s and the villages around it, with a small team matched to you and kept consistent.', 'ccs-wp-theme' ), ccs_location_services_label( $post_id ), $town ) );
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
				$ccs_has_areas = ! empty( $areas_list ) || $county || $postcode;
				/*
				 * "Working alongside" (local hospitals, NHS Continuing Healthcare and
				 * council contacts) was removed from the page (client, Nov 2026: "we
				 * can't confirm we work alongside them"). The fields stay in the
				 * editor for reference but are not published.
				 */
				?>
				<?php if ( $ccs_has_areas || $has_team ) : ?>
				<aside class="location-about__aside location-card" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: town name. */ __( 'Coverage around %s', 'ccs-wp-theme' ), $town ) ); ?>">
					<?php if ( $ccs_has_areas ) : ?>
						<div class="location-card__group">
							<div class="location-card__head">
								<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<h3 class="location-card__title"><?php esc_html_e( 'Areas we cover', 'ccs-wp-theme' ); ?></h3>
							</div>
							<?php if ( ! empty( $areas_list ) ) : ?>
								<?php
								$ccs_places = array_values( array_unique( array_merge( array( $town ), $areas_list ) ) );
								$ccs_last   = array_pop( $ccs_places );
								?>
								<p class="location-card__areas">
									<?php
									echo esc_html(
										$ccs_places
											/* translators: 1: comma-separated places, 2: the last place. */
											? sprintf( __( '%1$s and %2$s', 'ccs-wp-theme' ), implode( ', ', $ccs_places ), $ccs_last )
											: $ccs_last
									);
									?>
								</p>
							<?php endif; ?>
							<p class="location-card__note">
								<?php
								$parts = array_filter(
									array(
										$county,
										/* translators: %s: postcode area, e.g. ME. */
										$postcode ? sprintf( __( '%s postcodes', 'ccs-wp-theme' ), $postcode ) : '',
									)
								);
								if ( $parts ) {
									echo esc_html( implode( ', ', $parts ) . '. ' );
								}
								if ( $ccs_phone_tel ) {
									printf(
										/* translators: %s: office phone number, as a link. */
										esc_html__( 'Just outside these? We may still be able to help. Call %s and we will tell you whether we can cover where you live.', 'ccs-wp-theme' ),
										'<a href="' . esc_url( 'tel:' . $ccs_phone_tel ) . '">' . esc_html( $ccs_phone ) . '</a>'
									);
								} else {
									esc_html_e( 'Just outside these? We may still be able to help. Call us and we will tell you whether we can cover where you live.', 'ccs-wp-theme' );
								}
								?>
							</p>
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
					echo ccs_accent_text( sprintf( __( 'Care we *provide* in %s', 'ccs-wp-theme' ), $town ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_accent_text().
					?>
				</h2>
			</header>
			<?php
			/*
			 * Every service offered in this area. location_services records only
			 * which of the three main services (domiciliary, respite, complex) an
			 * area gets; every other service is offered everywhere (client, 23 Nov
			 * 2026). These cards used to show just the listed main services, so
			 * ten areas showed two cards while Maidstone showed all twenty
			 * (client, 25 Nov 2026: "some areas dont show all the services we
			 * provide there"). Now only a main service the area does not get is
			 * left out.
			 */
			$ccs_area_services = function_exists( 'ccs_location_services' ) ? ccs_location_services( $post_id ) : array();
			$ccs_card_args     = array( 'town' => $town );
			if ( function_exists( 'ccs_area_card_photos' ) ) {
				$ccs_card_args['photos'] = ccs_area_card_photos( get_post_field( 'post_name', $post_id ) );
			}
			if ( $ccs_area_services && function_exists( 'ccs_ordered_services' ) ) {
				$ccs_main_missing          = array_diff( array( 'domiciliary-care', 'respite-care', 'complex-care' ), $ccs_area_services );
				$ccs_card_args['services'] = array_values(
					array_filter(
						ccs_ordered_services(),
						static function ( $svc ) use ( $ccs_main_missing ) {
							return ! in_array( $svc->post_name, $ccs_main_missing, true );
						}
					)
				);
			}
			get_template_part( 'template-parts/service-cards', null, $ccs_card_args );

			?>
			<?php
			/*
			 * A list of every other service offered here, as seventeen link
			 * buttons under the cards, was tried on 23 Nov 2026 and removed the
			 * same day (client: "it arguably looks worse now"). The page text
			 * says in one sentence that the rest of the services are offered
			 * here, with one link.
			 */
			?>
			<div class="ccs-section-foot ccs-actions ccs-actions--center">
				<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_services_hub ); ?>"><?php esc_html_e( 'See all our services', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>
	</section>

	<?php
	/*
	 * Nov 2026, after bluebirdcare.co.uk/maidstone (the client's reference for
	 * area pages): a map of the town beside where our office is, then how care
	 * starts, the people behind it, what families say, and a person to talk
	 * to. All built from parts the rest of the site already uses.
	 *
	 * The map is Google's basic embed (no API key), lazy loaded, centred on the
	 * coordinates stored for this area (the same ones the LocalBusiness schema
	 * below uses); www.google.com is already in the CSP's frame-src. Plain map
	 * images, so it works on every phone. OpenStreetMap's embed was tried
	 * first, but it now needs WebGL and showed blank where that is missing.
	 */
	$ccs_office      = get_theme_mod( 'ccs_contact_address', "The Maidstone Studios, New Cut Road\nMaidstone, Kent ME14 5NZ" );
	$ccs_office_line = $ccs_office ? trim( preg_replace( '/\s*\n\s*/', ', ', $ccs_office ) ) : '';
	$ccs_hours       = get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' );
	$ccs_map_lat     = is_numeric( $lat ) ? (float) $lat : 0.0;
	$ccs_map_lng     = is_numeric( $lng ) ? (float) $lng : 0.0;
	$ccs_has_map     = $ccs_map_lat && $ccs_map_lng;
	/*
	 * The map is searched by the town's name, so it labels the town rather
	 * than showing a bare coordinate (client, Nov 2026); the stored
	 * coordinates decide only whether this area gets a map. The one link is a
	 * route from the town to the office.
	 */
	$ccs_place = trim( $town . ', ' . ( $county ? $county : 'Kent' ) . ', UK' );
	if ( $ccs_has_map ) {
		$ccs_map_src = 'https://www.google.com/maps?q=' . rawurlencode( $ccs_place ) . '&z=11&output=embed';
	}
	/*
	 * How far the area is from the office, as the crow flies, rounded to the
	 * mile (Nov 2026). A fact particular to each area page, replacing a
	 * sentence that was the same on all six. The office is The Maidstone
	 * Studios, ME14 5NZ (filterable for a move).
	 */
	$ccs_office_ll = apply_filters( 'ccs_office_coordinates', array( 51.2810, 0.5480 ) );
	$ccs_miles     = 0;
	if ( $ccs_has_map ) {
		$ccs_dlat  = deg2rad( $ccs_map_lat - $ccs_office_ll[0] );
		$ccs_dlng  = deg2rad( $ccs_map_lng - $ccs_office_ll[1] );
		$ccs_h     = sin( $ccs_dlat / 2 ) ** 2 + cos( deg2rad( $ccs_office_ll[0] ) ) * cos( deg2rad( $ccs_map_lat ) ) * sin( $ccs_dlng / 2 ) ** 2;
		$ccs_miles = (int) round( 3958.8 * 2 * atan2( sqrt( $ccs_h ), sqrt( 1 - $ccs_h ) ) );
	}
	/*
	 * The link opens the office's own page in Google Maps, which has the
	 * Directions button on it (Nov 2026, user: "doesn't work properly"). The
	 * directions URL it replaced started from "Your location" and showed
	 * "could not calculate directions" whenever Google did not have one. Same
	 * link as the Contact page.
	 */
	$ccs_directions = ccs_directions_url( $ccs_office_line );
	?>
	<?php
	/*
	 * The shared steps, team and reviews sections that followed here were
	 * removed (Nov 2026): repeated word for word on all six area pages, they
	 * made up most of each page's text, and search engines treat pages that
	 * similar as duplicates. Each area page now carries only its own facts.
	 */
	?>

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
	<?php if ( $ccs_has_map ) : ?>
		<section class="ccs-section location-map" aria-labelledby="location-map-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="location-map-heading" class="ccs-title">
						<?php
						// "Where we visit around Herne Bay" (client, Nov 2026: "does this title make sense?"): "visit around" is not natural, and it sounded like sightseeing.
						/* translators: %s: town name. */
						echo ccs_accent_text( sprintf( __( 'In and *around* %s', 'ccs-wp-theme' ), $town ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_accent_text().
						?>
					</h2>
				</header>
				<?php
				/*
				 * One card (client, Nov 2026: the heading, a paragraph, an office box
				 * and the map at different heights "looks messy"): the office details
				 * on the left, the map filling the right edge to edge.
				 */
				?>
				<div class="location-map__card">
					<div class="location-map__details">
						<div class="location-map__office">
							<span class="ccs-badge ccs-badge--sm" aria-hidden="true"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<p class="location-map__office-title"><?php esc_html_e( 'Our office', 'ccs-wp-theme' ); ?></p>
						</div>
						<?php if ( $ccs_office_line ) : ?>
							<p class="location-map__office-text"><?php echo esc_html( $ccs_office_line ); ?></p>
						<?php endif; ?>
						<?php if ( $ccs_hours ) : ?>
							<p class="location-map__office-text"><?php echo esc_html( $ccs_hours ); ?></p>
						<?php endif; ?>
						<p class="location-map__office-note">
							<?php
							if ( $ccs_miles > 20 && ! empty( $areas_list ) ) {
								/*
								 * Far from the office (Thanet, Herne Bay, Whitstable): the
								 * mileage says nothing useful, so name the places visited.
								 */
								/* translators: 1: area name, 2: three places in it. */
								echo esc_html( sprintf( __( 'Our carers visit homes across %1$s, including %2$s.', 'ccs-wp-theme' ), $town, implode( ', ', array_slice( $areas_list, 0, 3 ) ) ) );
							} elseif ( $ccs_miles >= 3 ) {
								/* translators: 1: town name, 2: number of miles. */
								echo esc_html( sprintf( _n( '%1$s is about %2$d mile from our office.', '%1$s is about %2$d miles from our office.', $ccs_miles, 'ccs-wp-theme' ), $town, $ccs_miles ) );
							} else {
								/* translators: %s: town name. */
								echo esc_html( sprintf( __( 'Our office is here in %s.', 'ccs-wp-theme' ), $town ) );
							}
							?>
						</p>
						<?php if ( $ccs_directions ) : ?>
							<p class="location-map__links">
								<a class="ccs-text-link" href="<?php echo esc_url( $ccs_directions ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Get directions to our office', 'ccs-wp-theme' ); ?>
									<span class="screen-reader-text"> <?php esc_html_e( '(opens Google Maps in a new window)', 'ccs-wp-theme' ); ?></span>
								</a>
							</p>
						<?php endif; ?>
					</div>
					<div class="location-map__frame">
						<iframe
							src="<?php echo esc_url( $ccs_map_src ); ?>"
							title="<?php echo esc_attr( sprintf( /* translators: %s: town name. */ __( 'Map of %s', 'ccs-wp-theme' ), $town ) ); ?>"
							loading="lazy"
						></iframe>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/*
	 * Who to call (Nov 2026). The page's Local support groups (Areas > edit),
	 * as a family would look for them. Three earlier forms were dropped: a
	 * grid of white text boxes and a panel of rows (client: "I don't like
	 * the styling of those"; both were a directory, ordered by organisation),
	 * then an accordion beside the map, which a review of our own found
	 * wanting: the company was missing from its own section, four of the
	 * five numbers were hidden behind a tap, and the map had nothing to do
	 * with the list. So:
	 * - the section opens with us: not sure where to start, ring the office;
	 * - each row is headed by the situation, in the family's words, with the
	 *   organisation under it and its number always in view, as a button;
	 * - the hospital row says 999 and 111 first;
	 * - a line says when the details were last checked;
	 * - the map has its own section again, about where we visit.
	 *
	 * The situation is chosen from the organisation's name; a row that
	 * matches nothing is headed by the organisation's name.
	 */
	$ccs_support_rows = $support_items;
	if ( $gp ) {
		$ccs_support_rows[] = array(
			'name'    => __( 'Community resources', 'ccs-wp-theme' ),
			'contact' => $gp,
		);
	}
	$ccs_support_kind = static function ( $name ) {
		foreach ( array( 'council' => 'council', 'hospital|treatment' => 'hospital', 'carer' => 'carers', 'hospice' => 'hospice', 'age uk' => 'age' ) as $pattern => $kind ) {
			if ( preg_match( '/' . $pattern . '/i', $name ) ) {
				return $kind;
			}
		}
		return '';
	};
	$ccs_support_heads = array(
		'council'  => __( 'I need a care assessment from the council', 'ccs-wp-theme' ),
		'hospital' => __( 'Someone needs hospital or urgent care', 'ccs-wp-theme' ),
		'carers'   => __( 'I’m the one doing the caring', 'ccs-wp-theme' ),
		'hospice'  => __( 'We’re thinking about care at the end of life', 'ccs-wp-theme' ),
		'age'      => __( 'I’d like advice or local services for an older person', 'ccs-wp-theme' ),
	);
	$ccs_support_phone = '0\d{2,4}(?: \d{2,7}){1,3}';
	$ccs_support_text  = static function ( $text ) use ( $ccs_support_phone ) {
		return preg_replace_callback(
			'/(?<!\d)' . $ccs_support_phone . '(?!\d)/',
			static function ( $m ) {
				return '<a href="tel:' . preg_replace( '/\D/', '', $m[0] ) . '">' . $m[0] . '</a>';
			},
			esc_html( $text )
		);
	};
	$ccs_who_rows = array();
	foreach ( $ccs_support_rows as $ccs_row ) {
		$ccs_row_text  = array();
		$ccs_row_hours = array();
		$ccs_row_calls = array();
		// A line that is only a number (an optional label before it, opening hours after) becomes the button; any other line is description.
		foreach ( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $ccs_row['contact'] ) ), 'strlen' ) as $ccs_row_line ) {
			if ( preg_match( '/^([^\d]{0,40}?)(' . $ccs_support_phone . ')(?:,\s*(.+))?$/', $ccs_row_line, $ccs_row_m ) ) {
				$ccs_row_calls[] = array( trim( $ccs_row_m[1] ), $ccs_row_m[2] );
				if ( ! empty( $ccs_row_m[3] ) ) {
					$ccs_row_hours[] = $ccs_row_m[3];
				}
			} else {
				$ccs_row_text[] = $ccs_row_line;
			}
		}
		$ccs_row_kind  = $ccs_support_kind( (string) $ccs_row['name'] );
		$ccs_who_rows[] = array(
			'kind'    => $ccs_row_kind,
			'heading' => isset( $ccs_support_heads[ $ccs_row_kind ] ) ? $ccs_support_heads[ $ccs_row_kind ] : (string) $ccs_row['name'],
			'org'     => isset( $ccs_support_heads[ $ccs_row_kind ] ) ? (string) $ccs_row['name'] : '',
			'text'    => $ccs_row_text,
			'hours'   => $ccs_row_hours,
			'calls'   => $ccs_row_calls,
		);
	}
	$ccs_who_us = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array();
	?>
	<?php
	/*
	 * Not shown (23 Nov 2026). The client saw four forms of this section in
	 * a day and liked none ("it arguably looks worse now"): the area pages
	 * were among the pages she was happy with, and this made them longer and
	 * busier. The checked local details stay in each area's Local support
	 * groups, and the section stays here, switched off, until a form is
	 * agreed from sketches: add_filter( 'ccs_area_who_to_call', '__return_true' ).
	 */
	?>
	<?php if ( $ccs_who_rows && apply_filters( 'ccs_area_who_to_call', false ) ) : ?>
		<section class="ccs-section ccs-section--tint ccs-section--flush-bottom who-call" aria-labelledby="who-call-heading">
			<div class="container container--lg">
				<?php // The heading and our own number across the top, then the rows in two columns: stacked beside the heading they made the tallest section on the page. ?>
				<div class="who-call__layout">
					<header class="ccs-head who-call__head">
						<h2 id="who-call-heading" class="ccs-title">
							<?php
							/* translators: %s: town name. */
							echo ccs_accent_text( sprintf( __( 'Who to *call* in %s', 'ccs-wp-theme' ), $town ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_accent_text().
							?>
						</h2>
						<div class="who-call__intro">
						<p class="ccs-lede"><?php esc_html_e( 'Not sure where to start? Ring us first. We will tell you what we can do, and point you to the right place for anything we can’t.', 'ccs-wp-theme' ); ?></p>
						<?php if ( ! empty( $ccs_who_us['phone'] ) && ! empty( $ccs_who_us['phone_link'] ) ) : ?>
							<p class="who-call__us">
								<a class="btn btn-primary" href="<?php echo esc_url( $ccs_who_us['phone_link'] ); ?>">
									<?php
									/* translators: %s: phone number. */
									echo esc_html( sprintf( __( 'Call us on %s', 'ccs-wp-theme' ), $ccs_who_us['phone'] ) );
									?>
								</a>
							</p>
						<?php endif; ?>
						</div>
					</header>
					<div class="who-call__body">
						<ul class="who-list<?php echo count( $ccs_who_rows ) % 2 ? ' who-list--odd' : ''; ?>" role="list">
							<?php foreach ( $ccs_who_rows as $ccs_who ) : ?>
								<li class="who-row<?php echo 'hospital' === $ccs_who['kind'] ? ' who-row--wide' : ''; ?>">
									<div class="who-row__words">
										<h3 class="who-row__title"><?php echo esc_html( $ccs_who['heading'] ); ?></h3>
										<?php if ( 'hospital' === $ccs_who['kind'] ) : ?>
											<p class="who-row__first"><?php esc_html_e( 'In an emergency call 999. For urgent advice that is not an emergency, call NHS 111.', 'ccs-wp-theme' ); ?></p>
										<?php endif; ?>
										<?php if ( '' !== $ccs_who['org'] ) : ?>
											<p class="who-row__org"><?php echo esc_html( $ccs_who['org'] ); ?></p>
										<?php endif; ?>
										<?php foreach ( $ccs_who['text'] as $ccs_who_line ) : ?>
											<p class="who-row__text"><?php echo $ccs_support_text( $ccs_who_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then phone links added. ?></p>
										<?php endforeach; ?>
										<?php if ( $ccs_who['hours'] ) : ?>
											<p class="who-row__hours"><?php echo esc_html( implode( ', ', $ccs_who['hours'] ) ); ?></p>
										<?php endif; ?>
									</div>
									<?php if ( $ccs_who['calls'] ) : ?>
										<p class="who-row__calls">
											<?php foreach ( $ccs_who['calls'] as $ccs_who_call ) : ?>
												<a class="btn btn-quiet who-row__call" href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $ccs_who_call[1] ) ); ?>">
													<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
													<span><span class="screen-reader-text"><?php echo esc_html( '' !== $ccs_who['org'] ? $ccs_who['org'] : $ccs_who['heading'] ); ?>: </span><?php echo esc_html( '' !== $ccs_who_call[0] ? $ccs_who_call[0] . ' ' : '' ); ?><span class="who-row__num"><?php echo esc_html( $ccs_who_call[1] ); ?></span></span>
												</a>
											<?php endforeach; ?>
										</p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
						<p class="who-call__checked">
							<?php
							/* translators: %s: month and year the details were last checked. */
							echo esc_html( sprintf( __( 'These are other organisations’ details, last checked in %s.', 'ccs-wp-theme' ), apply_filters( 'ccs_area_local_support_checked', __( 'November 2026', 'ccs-wp-theme' ) ) ) );
							?>
						</p>
					</div>
				</div>
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
