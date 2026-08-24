<?php
/**
 * Reusable inner-page header band.
 *
 * Inner pages previously rendered a bare <h1> on white with no visual anchor,
 * so every page below the homepage felt unfinished. This gives them a
 * consistent cream band with breadcrumb, heading and optional lede.
 *
 * Heading falls back to the post title, but pages can set a friendlier one via
 * the `ccs_page_heading` meta (several page titles are keyword strings like
 * "About Home Care Maidstone", which read badly as a visible heading).
 * A short intro can be set via `ccs_page_intro`.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_ph_id      = get_the_ID();
$ccs_ph_heading = get_post_meta( $ccs_ph_id, 'ccs_page_heading', true );
$ccs_ph_heading = ( is_string( $ccs_ph_heading ) && trim( $ccs_ph_heading ) !== '' ) ? trim( $ccs_ph_heading ) : get_the_title( $ccs_ph_id );
$ccs_ph_intro   = get_post_meta( $ccs_ph_id, 'ccs_page_intro', true );
$ccs_ph_intro   = is_string( $ccs_ph_intro ) ? trim( $ccs_ph_intro ) : '';
/*
 * Photo hero is opt-in via the page's own featured image, minus policy pages.
 * Resolved by ccs_page_uses_photo_hero() (inc/theme-setup.php) rather than
 * calling has_post_thumbnail() here, so this and the has-photo-hero body class
 * can never disagree about which pages get the dark full-bleed treatment — the
 * header's white logo and nav depend on that answer being the same in both.
 */
$ccs_ph_has_photo = function_exists( 'ccs_page_uses_photo_hero' )
	? ccs_page_uses_photo_hero( $ccs_ph_id )
	: has_post_thumbnail( $ccs_ph_id );
?>

<header class="page-hero<?php echo $ccs_ph_has_photo ? ' page-hero--photo' : ''; ?>">
	<?php if ( $ccs_ph_has_photo ) : ?>
		<div class="page-hero__scrim" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="page-hero__inner container container--lg">
		<div class="page-hero__text">
			<?php get_template_part( 'template-parts/breadcrumb' ); ?>
			<h1 class="page-hero__title"><?php echo esc_html( $ccs_ph_heading ); ?></h1>
			<?php if ( $ccs_ph_intro !== '' ) : ?>
				<p class="page-hero__intro"><?php echo esc_html( $ccs_ph_intro ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $ccs_ph_has_photo ) : ?>
			<div class="page-hero__media">
				<?php
				/*
				 * 'full', not 'large', and an explicit sizes of 100vw. Both matter,
				 * and both were making this hero look far softer than the source files
				 * deserve:
				 *
				 * - 'large' caps the src at WordPress's 1024px large size, so the
				 *   fallback behind a full-bleed banner was a 1024px file.
				 * - WordPress's default sizes attribute was
				 *   "(max-width: 1024px) 100vw, 1024px" — correct for an image sitting
				 *   in a content column, wrong for one spanning the viewport. It told
				 *   the browser the image would never render wider than 1024px, so on
				 *   a 1265px desktop at 2x (2530 device pixels of banner) the browser
				 *   dutifully picked the 1024w candidate and ignored the larger one
				 *   already sitting in the srcset.
				 *
				 * This hero is always 100vw (see .page-hero--photo in components.css),
				 * so say so and let the browser choose properly.
				 */
				echo get_the_post_thumbnail(
					$ccs_ph_id,
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
