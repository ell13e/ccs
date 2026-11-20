<?php
/**
 * Areas hub (/areas/): every location as a card.
 *
 * This URL is the "Areas we cover" crumb on every location page, and used to
 * fall through to the blog archive template: "Archives: Locations" over a
 * list of post excerpts. Now it answers the question the crumb asks, one card
 * per town with the villages it covers.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_phone     = get_theme_mod( 'ccs_phone', '01622 809 881' );
$ccs_phone_tel = $ccs_phone ? preg_replace( '/\s+/', '', $ccs_phone ) : '';

$ccs_areas = get_posts(
	array(
		'post_type'      => 'location',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
?>

<main id="main" class="site-main site-main--areas" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<h1 class="page-hero__title"><?php echo esc_html( ccs_copy( 'areas_title' ) ); ?></h1>
				<p class="page-hero__intro">
					<?php echo esc_html( ccs_copy( 'areas_lede' ) ); ?>
				</p>
			</div>
		</div>
	</header>

	<?php
	/*
	 * Inner-page pass, Nov 2026 (user: "too repetitive and visually boring").
	 * This was twelve identical cards, ten of them carrying the same line,
	 * "Respite and complex care", under the same pin. The towns are now grouped
	 * by the care offered there, and the group's heading says it once:
	 * - a small group (three towns or fewer; today Maidstone and Aylesford,
	 *   the only places with all three services) stays as cards, larger;
	 * - a longer group is one white panel of rows: town, villages, arrow.
	 * Groups come from ccs_location_services(), fullest offer first, so a
	 * change to a town's services regroups the page with no template edit.
	 */
	$ccs_area_groups = array();
	foreach ( $ccs_areas as $ccs_area ) {
		$ccs_area_label = ccs_location_services_label( $ccs_area->ID );
		if ( ! isset( $ccs_area_groups[ $ccs_area_label ] ) ) {
			$ccs_area_groups[ $ccs_area_label ] = array(
				'count' => count( ccs_location_services( $ccs_area->ID ) ),
				'areas' => array(),
			);
		}

		$ccs_area_town     = get_post_meta( $ccs_area->ID, 'location_town', true );
		$ccs_area_villages = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $ccs_area->ID, 'location_areas_covered', true ) ) ) );
		$ccs_area_shown    = array_slice( $ccs_area_villages, 0, 5 );
		$ccs_area_more     = count( $ccs_area_villages ) - count( $ccs_area_shown );
		$ccs_area_line     = implode( ', ', $ccs_area_shown );
		if ( $ccs_area_more > 0 ) {
			/* translators: 1: comma-separated village names, 2: number of further villages. */
			$ccs_area_line = sprintf( _n( '%1$s and %2$d more', '%1$s and %2$d more', $ccs_area_more, 'ccs-wp-theme' ), $ccs_area_line, $ccs_area_more );
		}

		$ccs_area_groups[ $ccs_area_label ]['areas'][] = array(
			'town'     => $ccs_area_town ? $ccs_area_town : get_the_title( $ccs_area ),
			'url'      => get_permalink( $ccs_area ),
			'villages' => $ccs_area_line,
		);
	}
	uasort(
		$ccs_area_groups,
		static function ( $a, $b ) {
			return $b['count'] <=> $a['count'];
		}
	);
	$ccs_area_i = 0;
	?>

	<?php foreach ( $ccs_area_groups as $ccs_area_label => $ccs_area_group ) : ?>
		<?php
		$ccs_area_cards = count( $ccs_area_group['areas'] ) <= 3;
		// Cards sit on white, the white panel on the tint, so the grounds alternate.
		$ccs_area_class = $ccs_area_cards ? 'ccs-section ccs-section--sm areas-hub' : 'ccs-section ccs-section--sm ccs-section--tint areas-hub';
		++$ccs_area_i;
		?>
		<section class="<?php echo esc_attr( $ccs_area_class ); ?>" aria-labelledby="areas-group-<?php echo (int) $ccs_area_i; ?>">
			<div class="container container--lg">
				<?php
				/*
				 * A map beside the first group (Nov 2026). A design review found this
				 * page was type only: no photo, no map. The towns with the fullest
				 * offer now sit beside a map of Kent, the same Google embed the town
				 * pages use, so the page has one substantial object and shows where
				 * "Kent" is at a glance. Only the first card group gets it.
				 */
				$ccs_area_with_map = $ccs_area_cards && 1 === $ccs_area_i;
				?>
				<?php if ( $ccs_area_with_map ) : ?>
					<div class="areas-hub__split">
						<div class="areas-hub__split-main">
				<?php endif; ?>
				<h2 id="areas-group-<?php echo (int) $ccs_area_i; ?>" class="areas-hub__group"><?php echo esc_html( $ccs_area_label ); ?></h2>

				<?php if ( $ccs_area_cards ) : ?>
					<ul class="area-grid area-grid--feature" role="list">
						<?php foreach ( $ccs_area_group['areas'] as $ccs_area_item ) : ?>
							<li class="area-card">
								<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<h3 class="area-card__title">
									<a class="area-card__link" href="<?php echo esc_url( $ccs_area_item['url'] ); ?>"><?php echo esc_html( $ccs_area_item['town'] ); ?></a>
								</h3>
								<?php if ( '' !== $ccs_area_item['villages'] ) : ?>
									<p class="area-card__villages"><?php echo esc_html( $ccs_area_item['villages'] ); ?></p>
								<?php endif; ?>
								<span class="area-card__cue" aria-hidden="true">
									<?php
									/* translators: %s: town name. */
									echo esc_html( sprintf( __( 'Care in %s', 'ccs-wp-theme' ), $ccs_area_item['town'] ) );
									?>
									&rarr;
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php if ( $ccs_area_with_map ) : ?>
						</div>
						<div class="areas-hub__map">
							<iframe
								src="<?php echo esc_url( 'https://www.google.com/maps?q=' . rawurlencode( 'Kent, UK' ) . '&z=9&output=embed' ); ?>"
								title="<?php esc_attr_e( 'Map of Kent', 'ccs-wp-theme' ); ?>"
								loading="lazy"
							></iframe>
						</div>
					</div>
					<?php endif; ?>
				<?php else : ?>
					<ul class="area-list" role="list">
						<?php foreach ( $ccs_area_group['areas'] as $ccs_area_item ) : ?>
							<li class="area-row">
								<h3 class="area-row__title">
									<a class="area-row__link" href="<?php echo esc_url( $ccs_area_item['url'] ); ?>"><?php echo esc_html( $ccs_area_item['town'] ); ?></a>
								</h3>
								<?php if ( '' !== $ccs_area_item['villages'] ) : ?>
									<p class="area-row__villages"><?php echo esc_html( $ccs_area_item['villages'] ); ?></p>
								<?php endif; ?>
								<span class="area-row__cue" aria-hidden="true">&rarr;</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	<?php endforeach; ?>

	<?php if ( ! empty( $ccs_areas ) && $ccs_phone_tel ) : ?>
		<section class="ccs-section ccs-section--sm ccs-section--flush-bottom areas-hub-ask" aria-labelledby="areas-ask-heading">
			<div class="container container--lg">
				<div class="ccs-strip areas-hub__strip">
					<span class="ccs-badge"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div class="ccs-strip__text">
						<h2 id="areas-ask-heading" class="ccs-strip__title"><?php echo ccs_copy_accent( 'areas_strip_title' ); ?></h2>
						<p class="ccs-strip__lede"><?php echo esc_html( ccs_copy( 'areas_strip_text' ) ); ?></p>
					</div>
					<a class="btn btn-quiet ccs-strip__action" href="<?php echo esc_url( 'tel:' . $ccs_phone_tel ); ?>">
						<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
						<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_phone ); ?></span>
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
