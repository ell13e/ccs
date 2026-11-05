<?php
/**
 * Homepage "Why choose us": photo collage beside the argument.
 *
 * This was the one section on the page with no imagery at all: a heading rail
 * and four paragraphs, in a sector where (reference/inspo-teardowns.md) "the
 * warmest sites pair a calm UI with emotional photography". Both the ElderlyCare
 * X template and Harmony Care answer the same "why us" question with a
 * staggered collage of real moments beside the copy, and that is the layout
 * here, using the business's own photographs: three different people, all
 * mid-moment, none repeated from the service cards below. Cropped by the
 * browser only; never filtered or tone-adjusted.
 *
 * The four proof points are claims the site already makes and can stand behind
 * (the CQC rating, the years and coverage from the hero, the rest from this
 * section's copy); they move from a footnote-sized tick list into the shared
 * icon badge, which is how the ElderlyCare template sets its feature points.
 *
 * Copy is unchanged. The best line on the site ("how you like your tea") stays
 * set as a display quote.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
$about_url    = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'about-home-care-maidstone' ) : home_url( '/about-home-care-maidstone/' );

$why_proof = array(
	array( 'icon' => 'shield', 'text' => __( 'Rated Good by the CQC', 'ccs-wp-theme' ) ),
	array( 'icon' => 'clock', 'text' => __( '10+ years caring for Kent families', 'ccs-wp-theme' ) ),
	array( 'icon' => 'people', 'text' => __( 'Adults and children supported', 'ccs-wp-theme' ) ),
	array( 'icon' => 'moon', 'text' => __( 'Care day or night, across Kent', 'ccs-wp-theme' ) ),
);

/*
 * Collage photographs, largest first. Paths are relative to assets/images/.
 * A missing file is skipped, so the collage degrades to two photos, or none,
 * rather than rendering an empty frame.
 */
$why_photos = array(
	'site-photos-extra/client-summer-party-laineys-care-farm.webp',
	'site-photos/client-easter-activity.webp',
	'site-photos/WhatsApp-Image-2025-06-13-at-11.53.40.webp',
);
$why_photos = array_values(
	array_filter(
		$why_photos,
		static function ( $rel ) {
			return file_exists( get_template_directory() . '/assets/images/' . $rel );
		}
	)
);
?>

<section class="home-why-choose" aria-labelledby="home-why-heading">
	<div class="home-why-choose__inner container container--lg">

		<?php if ( count( $why_photos ) >= 2 ) : ?>
			<div class="why-collage why-collage--<?php echo (int) count( $why_photos ); ?>" aria-hidden="true">
				<?php foreach ( $why_photos as $why_i => $why_rel ) : ?>
					<?php $why_size = wp_getimagesize( get_template_directory() . '/assets/images/' . $why_rel ); ?>
					<div class="why-collage__photo why-collage__photo--<?php echo (int) $why_i + 1; ?>">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $why_rel ); ?>"
							alt=""
							<?php if ( is_array( $why_size ) ) : ?>
								width="<?php echo (int) $why_size[0]; ?>"
								height="<?php echo (int) $why_size[1]; ?>"
							<?php endif; ?>
							loading="lazy"
							decoding="async"
						>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="home-why-choose__content">
			<h2 id="home-why-heading" class="ccs-title">
				<?php
				esc_html_e( 'Why Choose Us for Home Care in Maidstone?', 'ccs-wp-theme' );
				?>
			</h2>
			<p class="home-why-choose__subheading">
				<?php esc_html_e( 'The difference is who turns up, and how often it’s the same person.', 'ccs-wp-theme' ); ?>
			</p>

			<p class="home-why-choose__text">
				<?php esc_html_e( 'We support adults and children across Maidstone and the rest of Kent, day or night. Our carers live locally, and the same small team comes back each visit.', 'ccs-wp-theme' ); ?>
				<?php esc_html_e( 'We don’t rush, and we don’t rotate staff every other week.', 'ccs-wp-theme' ); ?>
			</p>

			<blockquote class="home-why-choose__pull">
				<p>
					<?php esc_html_e( 'We take the time to learn how you like your tea, what makes you laugh, and what puts you at ease on a harder day.', 'ccs-wp-theme' ); ?>
				</p>
				<cite class="home-why-choose__pull-cite">
					<?php esc_html_e( 'That’s the part a care plan can’t write down.', 'ccs-wp-theme' ); ?>
				</cite>
			</blockquote>

			<p class="home-why-choose__text">
				<?php esc_html_e( 'Good care doesn’t stop when the task list is finished. It shows in whether the person at the door already knows how the week has gone.', 'ccs-wp-theme' ); ?>
			</p>

			<ul class="why-proof" role="list">
				<?php foreach ( $why_proof as $why_point ) : ?>
					<li class="why-proof__item">
						<span class="ccs-badge ccs-badge--sm"><?php echo ccs_icon( $why_point['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
						<span class="why-proof__text"><?php echo esc_html( $why_point['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="ccs-actions">
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn btn-quiet">
					<?php esc_html_e( 'Learn more about our services', 'ccs-wp-theme' ); ?>
				</a>
				<a href="<?php echo esc_url( $about_url ); ?>" class="btn btn-quiet">
					<?php esc_html_e( 'Our care approach', 'ccs-wp-theme' ); ?>
				</a>
			</div>
		</div>

	</div>
</section>
