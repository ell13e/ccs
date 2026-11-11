<?php
/**
 * Template Name: Careers Hub
 *
 * Careers hub: photo hero, the page's own copy in a split intro (its first
 * heading becomes the section title), "what you can expect" on the tint band,
 * then the three careers sub-pages as link cards. The CTA band switches to its
 * careers variant on these pages (template-parts/cta-band.php).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/*
 * Sub-pages as cards. These used to print as a bare bulleted list of three
 * links at the end of the copy, the weakest thing on the page for the
 * question most visitors arrive with ("is there a job?").
 */
$ccs_careers_ids  = (array) get_option( 'ccs_careers_page_ids', array() );
$ccs_careers_meta = array(
	'current-vacancies'        => array(
		'icon'  => 'badge',
		'label' => ccs_copy( 'careers_card_1_label' ),
		'text'  => ccs_copy( 'careers_card_1_text' ),
		'cue'   => ccs_copy( 'careers_card_1_link' ),
	),
	'working-for-us'           => array(
		'icon'  => 'people',
		'label' => ccs_copy( 'careers_card_2_label' ),
		'text'  => ccs_copy( 'careers_card_2_text' ),
		'cue'   => ccs_copy( 'careers_card_2_link' ),
	),
	'professional-development' => array(
		'icon'  => 'shield',
		'label' => ccs_copy( 'careers_card_3_label' ),
		'text'  => ccs_copy( 'careers_card_3_text' ),
		'cue'   => ccs_copy( 'careers_card_3_link' ),
	),
);

$ccs_careers_cards = array();
foreach ( $ccs_careers_meta as $ccs_slug => $ccs_meta ) {
	$ccs_child = ! empty( $ccs_careers_ids[ $ccs_slug ] ) ? get_post( $ccs_careers_ids[ $ccs_slug ] ) : null;
	if ( $ccs_child instanceof WP_Post && 'publish' === $ccs_child->post_status ) {
		$ccs_careers_cards[] = array_merge( $ccs_meta, array( 'post' => $ccs_child ) );
	}
}
?>

<main id="main" class="site-main site-main--careers" role="main">
	<?php
	while ( have_posts() ) :
		the_post();

		$ccs_content  = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
		$ccs_sections = $ccs_content['sections'];
		$ccs_lead     = ! empty( $ccs_sections ) ? array_shift( $ccs_sections ) : null;
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<section class="ccs-section careers-intro" aria-labelledby="careers-intro-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="careers-intro-heading" class="ccs-title">
							<?php
							if ( $ccs_lead ) {
								echo esc_html( $ccs_lead['heading'] );
							} else {
								echo ccs_copy_accent( 'careers_expect_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
							}
							?>
						</h2>
					</header>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
						<div class="page-body page-body--flush entry-content">
							<?php
							// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would.
							echo $ccs_content['intro'];
							if ( $ccs_lead ) {
								echo $ccs_lead['body'];
							}
							foreach ( $ccs_sections as $ccs_section ) {
								echo '<h3>' . esc_html( $ccs_section['heading'] ) . '</h3>';
								echo $ccs_section['body'];
							}
							// phpcs:enable
							?>
						</div>
					</article>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/careers/what-to-expect' ); ?>

		<?php if ( ! empty( $ccs_careers_cards ) ) : ?>
			<section class="ccs-section ccs-section--sm ccs-section--flush-bottom careers-explore" aria-labelledby="careers-explore-heading">
				<div class="container container--lg">
					<header class="ccs-head ccs-head--center">
						<h2 id="careers-explore-heading" class="ccs-title">
							<?php
							echo esc_html( ccs_copy( 'careers_ways_title' ) );
							?>
						</h2>
					</header>
					<ul class="info-grid" role="list">
						<?php foreach ( $ccs_careers_cards as $ccs_card ) : ?>
							<li class="info-card">
								<span class="ccs-badge"><?php echo ccs_icon( $ccs_card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
								<p class="info-card__label"><?php echo esc_html( $ccs_card['label'] ); ?></p>
								<h3 class="info-card__title">
									<a class="info-card__link" href="<?php echo esc_url( get_permalink( $ccs_card['post'] ) ); ?>"><?php echo esc_html( get_the_title( $ccs_card['post'] ) ); ?></a>
								</h3>
								<p class="info-card__text"><?php echo esc_html( $ccs_card['text'] ); ?></p>
								<span class="info-card__cue" aria-hidden="true"><?php echo esc_html( $ccs_card['cue'] ); ?> <span class="info-card__arrow">&rarr;</span></span>
							</li>
						<?php endforeach; ?>
					</ul>
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
