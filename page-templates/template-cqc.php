<?php
/**
 * Template Name: CQC and Our Care
 *
 * Photo hero, then one feature: the page's own heading and text with the
 * report link, beside a photo carrying the rating on a tab. Then the CTA band.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--cqc" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php
		/*
		 * One photo with the rating on a tab over its corner, and the page's
		 * own heading and text beside it with the report link (Nov 2026). The
		 * user rejected the title-left intro, the centred band and the two-part
		 * verdict panel that came before; this is the photo-and-tab object she
		 * approved on the careers pages. The homepage keeps its own CQC band.
		 *
		 * The rating and date come from Customize > CQC Information, as in
		 * template-parts/home/cqc-section.php. Nothing here is invented.
		 */
		$ccs_cqc_url     = defined( 'CCS_CQC_REPORT_URL' ) ? CCS_CQC_REPORT_URL : get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' );
		$ccs_cqc_ratings = array(
			'outstanding'          => __( 'Outstanding', 'ccs-wp-theme' ),
			'good'                 => __( 'Good', 'ccs-wp-theme' ),
			'requires-improvement' => __( 'Requires improvement', 'ccs-wp-theme' ),
			'inadequate'           => __( 'Inadequate', 'ccs-wp-theme' ),
		);
		$ccs_cqc_key     = (string) get_theme_mod( 'ccs_cqc_rating', '' );
		$ccs_cqc_key     = isset( $ccs_cqc_ratings[ $ccs_cqc_key ] ) ? $ccs_cqc_key : 'good';
		$ccs_cqc_date    = (string) get_theme_mod( 'ccs_cqc_rating_date', '6 December 2023' );
		?>
		<section class="ccs-section ccs-section--flush-bottom cqc-page" aria-labelledby="cqc-intro-heading">
			<div class="container container--lg">
				<div class="ccs-media-split ccs-media-split--flip cqc-feature">

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cqc-feature__text page-content' ); ?>>
						<h2 id="cqc-intro-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'cqc_page_title' ) ); ?></h2>
						<?php if ( trim( get_the_content() ) !== '' ) : ?>
							<div class="page-body page-body--flush entry-content">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>
						<a class="btn btn-quiet cqc-feature__report" href="<?php echo esc_url( $ccs_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Read the report on cqc.org.uk', 'ccs-wp-theme' ); ?>
							<span aria-hidden="true">&nearr;</span>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
						</a>
						<p class="cqc-feature__note">
							<?php
							// Same sourced sentence as the homepage band; see the note there before editing.
							printf(
								/* translators: %s: linked phrase citing the Homecare Association analysis. */
								esc_html__( 'Only around one in six home care services in England has a current CQC rating: %s.', 'ccs-wp-theme' ),
								sprintf(
									'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="screen-reader-text">%3$s</span></a>',
									esc_url( 'https://www.homecareassociation.org.uk/resource/unseen-and-unrated-more-than-four-in-five-community-social-care-services-in-england-have-no-current-cqc-rating-new-analysis-finds.html' ),
									esc_html__( 'most have either never been assessed or carry one that is years out of date', 'ccs-wp-theme' ),
									esc_html__( ' (source: Homecare Association analysis, June 2026, opens in a new window)', 'ccs-wp-theme' )
								)
							);
							?>
						</p>
					</article>

					<div class="careers-benefits__media cqc-feature__media">
						<?php
						ccs_section_photo(
							'site-photos/ccs-complex-care-wheelchair-1.webp',
							array(
								'alt'   => __( 'Two women sitting together in the sun on a patio, smiling', 'ccs-wp-theme' ),
								'sizes' => '(min-width: 900px) 480px, 92vw',
								'class' => 'careers-benefits__photo',
							)
						);
						?>
						<p class="careers-benefits__badge" data-ccs-exception="rule 1: the regulator's rating, set on the photo as a label">
							<strong><?php echo esc_html( $ccs_cqc_ratings[ $ccs_cqc_key ] ); ?></strong>
							<span><?php esc_html_e( 'CQC overall rating', 'ccs-wp-theme' ); ?></span>
							<?php if ( '' !== $ccs_cqc_date ) : ?>
								<small>
									<?php
									/* translators: %s: the date the CQC rating was published. */
									echo esc_html( sprintf( __( 'Published %s', 'ccs-wp-theme' ), $ccs_cqc_date ) );
									?>
								</small>
							<?php endif; ?>
						</p>
					</div>

				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
