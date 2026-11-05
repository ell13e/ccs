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
		'label' => __( 'Apply', 'ccs-wp-theme' ),
		'text'  => __( 'The roles we are recruiting for now, and how to apply.', 'ccs-wp-theme' ),
		'cue'   => __( 'See vacancies', 'ccs-wp-theme' ),
	),
	'working-for-us'           => array(
		'icon'  => 'people',
		'label' => __( 'Day to day', 'ccs-wp-theme' ),
		'text'  => __( 'Pay, rotas and the support you can expect once you join.', 'ccs-wp-theme' ),
		'cue'   => __( 'Read more', 'ccs-wp-theme' ),
	),
	'professional-development' => array(
		'icon'  => 'shield',
		'label' => __( 'Training', 'ccs-wp-theme' ),
		'text'  => __( 'Funded, paid training, from the Care Certificate to specialist clinical skills.', 'ccs-wp-theme' ),
		'cue'   => __( 'Read more', 'ccs-wp-theme' ),
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
								/* translators: %s: the emphasised word, drawn with a brush underline. */
								printf( esc_html__( 'Care work, %s well', 'ccs-wp-theme' ), ccs_accent( __( 'done', 'ccs-wp-theme' ) ) );
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
							esc_html_e( 'Find your way in', 'ccs-wp-theme' );
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
