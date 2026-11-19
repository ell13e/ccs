<?php
/**
 * Homepage "How care starts" — three steps.
 *
 * Specified in reference/copy/homepage.md ("How it works (3 steps)", H2 "How care
 * starts", accent word "starts") and never built. reference/strategy.md asks the
 * top of the page to "include a visible hint there's more (e.g. 'How it works in
 * 3 steps')", and its journey table puts "what happens next" at the stage where
 * a family is "logistical, money-aware, wants to know what happens next".
 *
 * That is also the gap the ElderlyCare X template's "Easy care in three steps"
 * block fills, and the one the page had: it explained who we are, what we offer
 * and what it costs, but never what actually happens when you pick up the phone.
 * Not knowing is what stops most first calls.
 *
 * Copy rewritten by the client (Nov 2026). The lead-in now says care "often
 * starts within a month" of agreeing the plan. The FAQ page (editor content)
 * still says "within one to two weeks"; the two should agree.
 *
 * Voice rules (reference/copy/homepage.md): no em dashes, one CTA label. This
 * block deliberately ends on a link to the full process rather than a second
 * "Book a free consultation" button: the services band directly above already
 * asks, and the closing band asks again below, so a third ask in six screens
 * would be pressure rather than help.
 *
 * Filterable so the steps can be edited without touching the template.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_steps = apply_filters(
	'ccs_home_steps',
	array(
		array(
			'title' => ccs_copy( 'steps_1_title' ),
			'text'  => ccs_copy( 'steps_1_text' ),
		),
		array(
			'title' => ccs_copy( 'steps_2_title' ),
			'text'  => ccs_copy( 'steps_2_text' ),
		),
		array(
			'title' => ccs_copy( 'steps_3_title' ),
			'text'  => ccs_copy( 'steps_3_text' ),
		),
	)
);

if ( empty( $ccs_steps ) ) {
	return;
}

$ccs_steps_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'getting-started' ) : home_url( '/getting-started/' );
?>

<section class="home-steps" aria-labelledby="home-steps-heading">
	<div class="home-steps__inner container container--lg">

		<header class="ccs-head ccs-head--center">
			<h2 id="home-steps-heading" class="ccs-title">
				<?php
				echo esc_html( ccs_copy( 'steps_title' ) );
				?>
			</h2>
			<p class="ccs-lede">
				<?php echo esc_html( ccs_copy( 'steps_lede' ) ); ?>
			</p>
		</header>

		<?php
		/*
		 * role="list" on the <ol>: list-style:none removes the list semantics in
		 * VoiceOver/Safari, and the numbering is exactly the information a screen
		 * reader user needs here. The visible numeral is aria-hidden because the
		 * list already announces "1 of 3".
		 */
		?>
		<ol class="ccs-steps ccs-steps--panel" role="list" data-ccs-exception="rule 1: one soft panel holding the sequence, the same anatomy as the route strip under the hero">
			<?php foreach ( $ccs_steps as $ccs_i => $ccs_step ) : ?>
				<li class="ccs-step">
					<span class="ccs-step__num" aria-hidden="true"><?php echo (int) $ccs_i + 1; ?></span>
					<h3 class="ccs-step__title"><?php echo esc_html( $ccs_step['title'] ); ?></h3>
					<p class="ccs-step__text"><?php echo esc_html( $ccs_step['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="ccs-section-foot ccs-actions ccs-actions--center">
			<a class="ccs-text-link" href="<?php echo esc_url( $ccs_steps_url ); ?>">
				<?php echo esc_html( ccs_copy( 'steps_button' ) ); ?> <span aria-hidden="true">&rarr;</span>
			</a>
		</div>

	</div>
</section>
