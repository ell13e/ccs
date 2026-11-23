<?php
/**
 * Reusable inner-page header band: breadcrumb, heading and optional lede.
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
// A template can hand the banner its intro (the vacancies page passes the
// page's own opening text, so it is not a second block above the job list).
if ( '' === $ccs_ph_intro && ! empty( $args['intro'] ) && is_string( $args['intro'] ) ) {
	$ccs_ph_intro = trim( $args['intro'] );
}
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
/*
 * Inset variant: the page has a photo, but one too small to fill a banner
 * sharply. Same tint hero as a photo-less page, with the photo framed beside
 * the text (see ccs_hero_photo_fills_banner()). Policy pages never get one.
 */
$ccs_ph_inset = ! $ccs_ph_has_photo
	&& has_post_thumbnail( $ccs_ph_id )
	&& ! in_array( get_post_field( 'post_name', $ccs_ph_id ), array( 'privacy-policy', 'terms-and-conditions', 'accessibility-statement', 'cookies' ), true );
?>

<header class="page-hero<?php echo $ccs_ph_has_photo ? ' page-hero--photo' : ( $ccs_ph_inset ? ' page-hero--inset' : '' ); ?>">
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
		<?php if ( $ccs_ph_inset ) : ?>
			<div class="page-hero__media" style="<?php echo esc_attr( '--ccs-photo-max:' . ccs_hero_photo_max_width( $ccs_ph_id ) . 'px' ); ?>">
				<?php
				echo get_the_post_thumbnail(
					$ccs_ph_id,
					'full',
					array(
						'class'         => 'page-hero__img',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 900px) 24rem, 92vw',
					)
				);
				?>
			</div>
		<?php endif; ?>
		<?php if ( $ccs_ph_has_photo ) : ?>
			<div class="page-hero__media">
				<?php
				/*
				 * 'full', not 'large', and an explicit sizes of 100vw. 'large' caps the
				 * src at 1024px, and WordPress's default sizes attribute
				 * ("(max-width: 1024px) 100vw, 1024px") tells the browser the image never
				 * renders wider than that, so a 2x desktop picked the 1024w candidate. This
				 * hero is always 100vw (.page-hero--photo in components.css).
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
		<?php
		/*
		 * One object beside the heading: the photo when the page has one, the
		 * CQC card when it does not (Nov 2026). Both together put the card on
		 * top of the photo. The live widget is also in the footer of every page.
		 */
		if ( ! $ccs_ph_inset ) {
			get_template_part( 'template-parts/cqc-hero-card' );
		}
		?>
	</div>
</header>
