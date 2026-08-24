<?php
/**
 * Homepage information cards: Our Care Approach, FAQs, Careers (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_url   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'about-home-care-maidstone' ) : home_url( '/about-home-care-maidstone/' );
$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
// FAQs previously pointed at $services_url (wrong destination); careers pointed at the
// legacy 'care-careers-maidstone-kent' page rather than the real /careers/ hub. Both fixed 2026-08-19.
$faqs_url    = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'faqs' ) : home_url( '/resources/faqs/' );
$careers_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'careers' ) : home_url( '/careers/' );

$cards = array(
	array(
		'subheading' => __( 'About Us', 'ccs-wp-theme' ),
		'title'      => __( 'Our Care Approach', 'ccs-wp-theme' ),
		'body'       => __( 'We’re a family-run, CQC-regulated provider in Maidstone. See how we build a care team around one person rather than filling a rota, and what that looks like week to week.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Learn more', 'ccs-wp-theme' ),
		'cta_url'    => $about_url,
		'icon'       => 'approach',
	),
	array(
		'subheading' => __( 'FAQs', 'ccs-wp-theme' ),
		'title'      => __( 'Home Care FAQs', 'ccs-wp-theme' ),
		'body'       => __( 'What does care cost? How quickly can it start? Who will actually turn up? Straight answers to the questions families ask us most, with no jargon.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Get answers', 'ccs-wp-theme' ),
		'cta_url'    => $faqs_url,
		'icon'       => 'faqs',
	),
	array(
		'subheading' => __( 'Careers', 'ccs-wp-theme' ),
		'title'      => __( 'Care Careers in Kent', 'ccs-wp-theme' ),
		'body'       => __( 'Flexible hours, proper training, and a team that backs you up. If you want care work where you’re given time to do the job well, we’d like to hear from you.', 'ccs-wp-theme' ),
		'cta_text'   => __( 'Explore roles', 'ccs-wp-theme' ),
		'cta_url'    => $careers_url,
		'icon'       => 'careers',
	),
);

/*
 * Three flat text columns on a cream band gave the eye nothing to land on, and
 * this section sits between the review cards and the logo wall — the two most
 * visually active blocks on the page — so it read as a gap rather than a
 * choice. An icon per column is the lightest fix that keeps the editorial
 * treatment the rest of this page settled on: no card, no border, no shadow,
 * just a mark that makes each column findable at a glance.
 *
 * Inline SVG rather than an icon font or image files: three small paths cost
 * nothing, need no extra request, and inherit currentColor so they stay in the
 * palette automatically. All decorative — every card's heading already says
 * what it is — so they carry aria-hidden and never need alt text.
 */
$card_icons = array(
	// Two figures side by side: the care team built around one person.
	'approach' => '<circle cx="9" cy="8.5" r="3.2"/><circle cx="17.5" cy="10" r="2.4"/><path d="M3.5 20.5c0-3.2 2.5-5.5 5.5-5.5s5.5 2.3 5.5 5.5"/><path d="M16 15.2c2.6.2 4.5 2.3 4.5 5.3"/>',
	// Speech bubble with a question mark.
	'faqs'     => '<path d="M20.5 12.2c0 4-3.8 7.2-8.5 7.2a10 10 0 0 1-2.7-.36L4.5 20.5l1.2-3.5A6.7 6.7 0 0 1 3.5 12.2c0-4 3.8-7.2 8.5-7.2s8.5 3.2 8.5 7.2Z"/><path d="M10.1 10.1a2 2 0 1 1 2.6 1.9c-.5.2-.8.6-.8 1.1v.4"/><path d="M12 15.6h.01"/>',
	// Badge and lanyard: a carer joining the team.
	'careers'  => '<path d="M9 4.5h6v3H9z"/><rect x="4" y="7.5" width="16" height="12" rx="2"/><path d="M8.5 12.5h7"/><path d="M8.5 15.5h4"/>',
);
?>

<section class="home-info-cards" aria-labelledby="home-info-cards-heading">
	<div class="home-info-cards__inner container container--lg">
		<h2 id="home-info-cards-heading" class="visually-hidden">
			<?php esc_html_e( 'Find out more', 'ccs-wp-theme' ); ?>
		</h2>
		<div class="home-info-cards__grid">
			<?php foreach ( $cards as $card ) : ?>
				<article class="home-info-card card">
					<div class="home-info-card__body card-body">
						<?php if ( ! empty( $card['icon'] ) && isset( $card_icons[ $card['icon'] ] ) ) : ?>
							<svg class="home-info-card__icon" viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<?php echo $card_icons[ $card['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG path data defined above, not user input. ?>
							</svg>
						<?php endif; ?>
						<p class="home-info-card__subheading">
							<?php echo esc_html( $card['subheading'] ); ?>
						</p>
						<h3 class="home-info-card__title">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>
						<p class="home-info-card__text">
							<?php echo esc_html( $card['body'] ); ?>
						</p>
						<a href="<?php echo esc_url( $card['cta_url'] ); ?>" class="home-info-card__link">
							<?php echo esc_html( $card['cta_text'] ); ?>
							<span aria-hidden="true">&rarr;</span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
