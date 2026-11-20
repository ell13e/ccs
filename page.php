<?php
/**
 * Default page template
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--page" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php
		// Resources (Nov 2026): the first sentence of the page's own intro is
		// the header's one line, so the body starts straight at the first
		// section. A second big heading under the title "looks worse", and a
		// loose paragraph above the first photo "looks a bit strange" (client).
		$ccs_page_head = array();
		if ( 'resources' === get_post_field( 'post_name', get_the_ID() ) ) {
			$ccs_page_parts = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
			$ccs_page_lead  = trim( wp_strip_all_tags( $ccs_page_parts['intro'] ) );
			if ( preg_match( '/^.+?[.!?](?=\s|$)/u', $ccs_page_lead, $ccs_page_m ) ) {
				$ccs_page_head['intro'] = $ccs_page_m[0];
			}
		}
		get_template_part( 'template-parts/page-header', null, $ccs_page_head );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php
			// Two default-template pages have their own body layout (Nov 2026).
			$ccs_page_slug = get_post_field( 'post_name', get_the_ID() );
			if ( 'resources' === $ccs_page_slug ) {
				get_template_part( 'template-parts/resources-hub' );
			} elseif ( 'referral-information' === $ccs_page_slug ) {
				get_template_part( 'template-parts/referral-body' );
			} else {
				get_template_part( 'template-parts/article-body' );
			}
			?>
		</article>
		<?php
	endwhile;
	?>

	<?php
	// The referral page ends on its own form; the closing card under it was a second plum block straight after the first.
	if ( ! is_page( 'referral-information' ) ) {
		get_template_part( 'template-parts/cta-band' );
	}
	?>
</main>

<?php
get_footer();
