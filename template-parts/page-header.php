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
// Photo is opt-in via the page's own featured image — most inner pages have
// none yet and keep the plain band exactly as before.
$ccs_ph_has_photo = has_post_thumbnail( $ccs_ph_id );
?>

<header class="page-hero<?php echo $ccs_ph_has_photo ? ' page-hero--photo' : ''; ?>">
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
				<?php echo get_the_post_thumbnail( $ccs_ph_id, 'large', array( 'class' => 'page-hero__img', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</header>
