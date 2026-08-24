<?php
/**
 * 404 — page not found.
 *
 * Without this file a missing URL fell through to index.php, which renders
 * template-parts/content-none.php inside a bare <main> with no container: an
 * unstyled "Nothing Found / No content matched your request." pinned to the
 * left edge of the viewport, outside the site's layout grid entirely.
 *
 * That is a poor page anywhere and a bad one here. The people most likely to
 * hit a dead URL on this site are following an old link from a leaflet, a
 * directory listing or a hospital discharge pack — often while arranging care
 * under pressure. A dead end that offers no phone number and no route onward
 * is the opposite of what the rest of the site promises. So this page does
 * three things: says plainly what happened, hands over the phone number, and
 * offers the handful of destinations people actually arrive looking for.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_404_contact   = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
$ccs_404_phone     = isset( $ccs_404_contact['phone'] ) ? $ccs_404_contact['phone'] : '';
$ccs_404_phone_tel = isset( $ccs_404_contact['phone_link'] ) ? $ccs_404_contact['phone_link'] : '';

/*
 * Destinations, in the order people arrive looking for them. Resolved through
 * ccs_page_url() so each one follows the page's real permalink rather than a
 * guessed path — several of these are child pages, and a guessed URL would
 * take a 301 hop or miss entirely if a slug is ever edited.
 */
$ccs_404_links = array(
	array(
		'slug'  => 'home-care-services-kent',
		'label' => __( 'Our care services', 'ccs-wp-theme' ),
		'desc'  => __( 'Domiciliary, respite and complex care at home.', 'ccs-wp-theme' ),
	),
	array(
		'slug'  => 'contact-us',
		'label' => __( 'Arrange a conversation', 'ccs-wp-theme' ),
		'desc'  => __( 'Talk it through with someone local. No commitment.', 'ccs-wp-theme' ),
	),
	array(
		'slug'  => 'getting-started',
		'label' => __( 'Getting started', 'ccs-wp-theme' ),
		'desc'  => __( 'What happens between first call and first visit.', 'ccs-wp-theme' ),
	),
	array(
		'slug'  => 'resources/faqs',
		'label' => __( 'Common questions', 'ccs-wp-theme' ),
		'desc'  => __( 'Costs, timescales, and who your carers would be.', 'ccs-wp-theme' ),
	),
);
?>

<main id="main" class="site-main site-main--404" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<p class="page-hero__eyebrow"><?php esc_html_e( 'Page not found', 'ccs-wp-theme' ); ?></p>
				<h1 class="page-hero__title"><?php esc_html_e( 'That page isn’t here any more', 'ccs-wp-theme' ); ?></h1>
				<p class="page-hero__intro">
					<?php esc_html_e( 'The link may be out of date, or the address may have a typo in it. Nothing is wrong at your end — here’s where most people are heading.', 'ccs-wp-theme' ); ?>
				</p>
			</div>
		</div>
	</header>

	<div class="error-404">
		<div class="container container--lg">

			<?php if ( $ccs_404_phone ) : ?>
				<p class="error-404__phone-line">
					<?php esc_html_e( 'If it’s easier to just ask someone, call', 'ccs-wp-theme' ); ?>
					<a href="<?php echo esc_url( $ccs_404_phone_tel ); ?>" class="error-404__phone"><?php echo esc_html( $ccs_404_phone ); ?></a>
				</p>
			<?php endif; ?>

			<ul class="error-404__links" role="list">
				<?php foreach ( $ccs_404_links as $ccs_link ) : ?>
					<?php
					$ccs_href = function_exists( 'ccs_page_url' )
						? ccs_page_url( $ccs_link['slug'] )
						: home_url( '/' . $ccs_link['slug'] . '/' );
					?>
					<li class="error-404__link-item">
						<a class="error-404__link" href="<?php echo esc_url( $ccs_href ); ?>">
							<span class="error-404__link-label"><?php echo esc_html( $ccs_link['label'] ); ?></span>
							<span class="error-404__link-desc"><?php echo esc_html( $ccs_link['desc'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="error-404__search">
				<h2 class="error-404__search-heading"><?php esc_html_e( 'Or search the site', 'ccs-wp-theme' ); ?></h2>
				<?php get_search_form(); ?>
			</div>

		</div>
	</div>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
