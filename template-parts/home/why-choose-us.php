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
	array( 'icon' => 'shield', 'text' => ccs_copy( 'why_proof_1' ) ),
	array( 'icon' => 'clock', 'text' => ccs_copy( 'why_proof_2' ) ),
	array( 'icon' => 'people', 'text' => ccs_copy( 'why_proof_3' ) ),
	array( 'icon' => 'moon', 'text' => ccs_copy( 'why_proof_4' ) ),
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

		<?php if ( ! empty( $why_photos ) ) : ?>
			<?php
			/*
			 * One photograph, not a collage (calm pass, Nov 2026). The three photos
			 * differ in light, colour and setting, and together they were the
			 * busiest thing on the page. The list above is kept as a fallback order:
			 * the first file that exists is used.
			 */
			$why_rel  = $why_photos[0];
			$why_size = wp_getimagesize( get_template_directory() . '/assets/images/' . $why_rel );
			?>
			<figure class="why-photo" aria-hidden="true">
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $why_rel ); ?>"
					alt=""
					<?php if ( is_array( $why_size ) ) : ?>
						width="<?php echo (int) $why_size[0]; ?>"
						height="<?php echo (int) $why_size[1]; ?>"
					<?php endif; ?>
					<?php echo ccs_theme_image_srcset( $why_rel, '(min-width: 960px) 520px, 92vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset(). ?>
					loading="lazy"
					decoding="async"
				>
			</figure>
		<?php endif; ?>

		<div class="home-why-choose__content">
			<h2 id="home-why-heading" class="ccs-title">
				<?php
				echo esc_html( ccs_copy( 'why_title' ) );
				?>
			</h2>
			<p class="home-why-choose__subheading">
				<?php echo esc_html( ccs_copy( 'why_subheading' ) ); ?>
			</p>

			<p class="home-why-choose__text">
				<?php echo esc_html( ccs_copy( 'why_text_1' ) ); ?>
				<?php echo esc_html( ccs_copy( 'why_text_2' ) ); ?>
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
					<?php echo esc_html( ccs_copy( 'why_button_services' ) ); ?>
				</a>
			</div>
		</div>

	</div>
</section>
