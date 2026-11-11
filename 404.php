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
		'label' => ccs_copy( 'e404_link_1_title' ),
		'desc'  => ccs_copy( 'e404_link_1_text' ),
	),
	array(
		'slug'  => 'contact-us',
		'label' => ccs_copy( 'e404_link_2_title' ),
		'desc'  => ccs_copy( 'e404_link_2_text' ),
	),
	array(
		'slug'  => 'getting-started',
		'label' => ccs_copy( 'e404_link_3_title' ),
		'desc'  => ccs_copy( 'e404_link_3_text' ),
	),
	array(
		'slug'  => 'resources/faqs',
		'label' => ccs_copy( 'e404_link_4_title' ),
		'desc'  => ccs_copy( 'e404_link_4_text' ),
	),
);
?>

<main id="main" class="site-main site-main--404" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<div class="page-hero__text">
				<p class="page-hero__eyebrow"><?php echo esc_html( ccs_copy( 'e404_eyebrow' ) ); ?></p>
				<h1 class="page-hero__title"><?php echo esc_html( ccs_copy( 'e404_title' ) ); ?></h1>
				<p class="page-hero__intro">
					<?php echo esc_html( ccs_copy( 'e404_text' ) ); ?>
				</p>
			</div>
		</div>
	</header>

	<div class="error-404">
		<div class="container container--lg">

			<?php if ( $ccs_404_phone ) : ?>
				<p class="error-404__phone-line">
					<?php echo esc_html( ccs_copy( 'e404_phone_line' ) ); ?>
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
				<h2 class="error-404__search-heading"><?php echo esc_html( ccs_copy( 'e404_search_title' ) ); ?></h2>
				<?php get_search_form(); ?>
			</div>

		</div>
	</div>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
