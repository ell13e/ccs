<?php
/**
 * Template Name: Services Hub
 *
 * Services landing page. The service cards come first, straight under the
 * hero, because choosing a service is why people land here: the page's own
 * lead paragraph sits over them as the section lede. The rest of the editor
 * copy (who we support, how we staff it, areas, costs) follows as a split
 * section, then the shared CTA band. Same .svc-card as the homepage and the
 * related-services row on each service page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_services = ccs_ordered_services();
?>

<main id="main" class="site-main site-main--services" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_content = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
		$ccs_intro   = $ccs_content['intro'];
		?>
		<?php
		/*
		 * Redesigned to match the homepage (Nov 2026, client: "I really hate
		 * the visual design"). The page was a short left-aligned band, three
		 * cards, two panels of names and a boxed accordion beside an empty
		 * column. Now, in the homepage's own objects:
		 *   - the site's standard header, as on every other inner page;
		 *   - every service as the same card in one even grid (see below);
		 *   - the page's remaining text as the numbered accordion beside one
		 *     photo, exactly as "What we do differently" on the homepage.
		 * The words are all the editor's or the Customizer's.
		 */
		$ccs_hub_groups  = function_exists( 'ccs_service_groups' ) ? ccs_service_groups() : array();
		$ccs_hub_by      = array();
		foreach ( $ccs_services as $ccs_hub_svc ) {
			$ccs_hub_by[ function_exists( 'ccs_service_group' ) ? ccs_service_group( $ccs_hub_svc->post_name ) : 'everyday' ][] = $ccs_hub_svc;
		}
		/*
		 * "Choosing between them" comes before the lists (Nov 2026, client:
		 * someone unsure which service fits should be told they need not
		 * decide before they have to read twenty of them). It is still the
		 * editor's own section, found by its heading and lifted out of the
		 * closing accordion; with the heading renamed it stays where it was.
		 */
		$ccs_hub_choose = null;
		foreach ( $ccs_content['sections'] as $ccs_hub_i => $ccs_hub_section ) {
			if ( false !== stripos( $ccs_hub_section['heading'], 'choosing' ) ) {
				$ccs_hub_choose = $ccs_hub_section;
				unset( $ccs_content['sections'][ $ccs_hub_i ] );
				break;
			}
		}
		get_template_part( 'template-parts/page-header' );
		?>

		<?php
		/*
		 * Fourth form (Nov 2026). The finder and then the cards-plus-directory
		 * both missed; the client pointed to All Care's services page
		 * (all-care.co.uk/our-services): a left-aligned heading and paragraph,
		 * then one plain, even grid in which every service is the same card,
		 * a photo on top, the name, two lines and a "See more" button.
		 * Structure only; CCS's own look. With twenty services the grid is in
		 * the menu's two groups, each under its heading, services with a
		 * photograph first.
		 *
		 * A service with no photograph yet shows its icon on a tile in the
		 * photo's place, as the area pages do. All Care's page works because
		 * every card has its own photo: choosing those is the client's call.
		 */
		$ccs_hub_sets = array();
		if ( $ccs_hub_groups ) {
			foreach ( $ccs_hub_groups as $ccs_hub_key => $ccs_hub_label ) {
				if ( ! empty( $ccs_hub_by[ $ccs_hub_key ] ) ) {
					$ccs_hub_sets[ $ccs_hub_key ] = array( $ccs_hub_label, $ccs_hub_by[ $ccs_hub_key ] );
				}
			}
		}
		if ( ! $ccs_hub_sets && $ccs_services ) {
			$ccs_hub_sets['all'] = array( '', $ccs_services );
		}
		?>
		<?php
		/*
		 * Each group is its own band with a full section title (client: the
		 * group names were "not very titley", with little separation, when
		 * they were small headings inside one section). The first band also
		 * carries the page's heading and lead paragraph.
		 */
		$ccs_hub_n = 0;
		?>
		<?php foreach ( $ccs_hub_sets as $ccs_hub_key => $ccs_hub_set ) : ?>
			<?php
			// A to Z by name within the group (client, Nov 2026: "alphabetical
			// within each group"). Services with a photograph used to come first.
			$ccs_hub_list = $ccs_hub_set[1];
			usort(
				$ccs_hub_list,
				static function ( $a, $b ) {
					return strcasecmp( get_the_title( $a ), get_the_title( $b ) );
				}
			);
			$ccs_hub_count = count( $ccs_hub_list );
			?>
			<section class="ccs-section<?php echo 1 === $ccs_hub_n % 2 ? ' ccs-section--tint' : ''; ?> services-hub" id="<?php echo esc_attr( $ccs_hub_key ); ?>" aria-labelledby="care-group-<?php echo esc_attr( $ccs_hub_key ); ?>">
				<div class="container container--lg">
					<header class="care-group__head">
						<h2 class="care-group__title" id="care-group-<?php echo esc_attr( $ccs_hub_key ); ?>"><?php echo esc_html( '' !== $ccs_hub_set[0] ? $ccs_hub_set[0] : __( 'All our services', 'ccs-wp-theme' ) ); ?></h2>
						<p class="care-group__count">
							<?php
							/* translators: %d: number of services in the group. */
							echo esc_html( sprintf( _n( '%d service', '%d services', $ccs_hub_count, 'ccs-wp-theme' ), $ccs_hub_count ) );
							?>
						</p>
					</header>
					<?php
					/*
					 * No opening section (client, Nov 2026: three forms of one were
					 * disliked, and the sketches after them). As on the Areas page,
					 * the lists start straight under the page header, which already
					 * says what the page is. "Choosing between them" is the editor's
					 * own paragraph, as the lead under the first list's heading.
					 */
					?>
					<?php if ( 0 === $ccs_hub_n && $ccs_hub_choose ) : ?>
						<div class="care-group__lede"><?php echo wp_kses_post( $ccs_hub_choose['body'] ); ?></div>
					<?php endif; ?>
					<ul class="care-grid" role="list">
						<?php foreach ( $ccs_hub_list as $ccs_hub_svc ) : ?>
							<li class="care-card">
								<?php if ( has_post_thumbnail( $ccs_hub_svc ) ) : ?>
									<div class="care-card__media">
										<?php echo wp_get_attachment_image( get_post_thumbnail_id( $ccs_hub_svc ), 'large', false, array( 'alt' => '', 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 400px, (min-width: 640px) 46vw, 92vw' ) ); ?>
									</div>
								<?php else : ?>
									<div class="care-card__media care-card__media--icon" aria-hidden="true" data-ccs-exception="rule 1: stands in for the card's photo until one is chosen; the whole card is the link"><?php echo ccs_icon( function_exists( 'ccs_service_icon' ) ? ccs_service_icon( $ccs_hub_svc->post_name ) : 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></div>
								<?php endif; ?>
								<div class="care-card__body">
									<h3 class="care-card__title"><a class="care-card__link" href="<?php echo esc_url( get_permalink( $ccs_hub_svc ) ); ?>"><?php echo esc_html( get_the_title( $ccs_hub_svc ) ); ?></a></h3>
									<?php if ( has_excerpt( $ccs_hub_svc ) ) : ?>
										<p class="care-card__text"><?php echo esc_html( get_the_excerpt( $ccs_hub_svc ) ); ?></p>
									<?php endif; ?>
									<span class="btn btn-quiet care-card__cue" aria-hidden="true"><?php esc_html_e( 'See more', 'ccs-wp-theme' ); ?> <span>&rarr;</span></span>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
			<?php ++$ccs_hub_n; ?>
		<?php endforeach; ?>

		<?php if ( ! empty( $ccs_content['sections'] ) || empty( $ccs_services ) ) : ?>
			<section class="ccs-section<?php echo 1 === $ccs_hub_n % 2 ? ' ccs-section--tint' : ' ccs-section--flush-bottom'; ?> services-how" aria-labelledby="services-how-heading">
				<div class="container container--lg">
					<div class="photo-acc">
						<figure class="photo-acc__photo" data-ccs-exception="rule 15: one photograph in its frame">
							<?php
							// A photo used nowhere else on the site.
							printf(
								'<img src="%1$s" alt="%2$s" width="900" height="1070" loading="lazy" decoding="async"%3$s>',
								esc_url( get_template_directory_uri() . '/assets/images/site-photos/WhatsApp-Image-2025-04-30-at-15.12.20-e1752499831227.webp' ),
								esc_attr__( 'A carer leaning in beside a young man in a wheelchair at a football ground, both smiling', 'ccs-wp-theme' ),
								ccs_theme_image_srcset( 'site-photos/WhatsApp-Image-2025-04-30-at-15.12.20-e1752499831227.webp', '(min-width: 960px) 480px, 92vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
							);
							?>
						</figure>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'photo-acc__body page-content' ); ?>>
							<h2 id="services-how-heading" class="ccs-title"><?php echo ccs_copy_accent( 'services_page_promise_title' ); ?></h2>
							<?php if ( empty( $ccs_services ) ) : ?>
								<div class="photo-acc__lede entry-content"><?php echo wp_kses_post( $ccs_intro ); ?></div>
							<?php endif; ?>
							<?php
							$ccs_hub_items = array();
							foreach ( $ccs_content['sections'] as $ccs_section ) {
								$ccs_hub_items[] = array( 'heading' => $ccs_section['heading'], 'body' => $ccs_section['body'] );
							}
							get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_hub_items, 'name' => 'services-how' ) );
							?>
						</article>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
