<?php
/**
 * Block patterns – pre-built content blocks for the block editor.
 * Category: CCS Patterns (Continuity Care Services).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block pattern category for CCS.
 */
function ccs_register_block_pattern_category() {
	register_block_pattern_category( 'ccs-patterns', array(
		'label'       => __( 'CCS Patterns', 'ccs-wp-theme' ),
		'description' => __( 'Pre-designed content blocks for Continuity Care Services.', 'ccs-wp-theme' ),
	) );
}
add_action( 'init', 'ccs_register_block_pattern_category' );

/**
 * Register block patterns.
 */
function ccs_register_block_patterns() {
	$contact_url = esc_url( home_url( '/contact/' ) );

	// Service / CTA box
	register_block_pattern( 'ccs-wp-theme/service-cta-box', array(
		'title'       => __( 'Service CTA Box', 'ccs-wp-theme' ),
		'description' => __( 'A highlighted box with heading, short text and primary button (e.g. Get in touch).', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'cta', 'service', 'call to action', 'button' ),
		'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}},"border":{"radius":"14px"}},"backgroundColor":"secondary-light"} -->
<div class="wp-block-group has-secondary-light-background-color has-background" style="border-radius:14px;padding-top:var(--wp--preset--spacing--lg);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg);padding-left:var(--wp--preset--spacing--lg)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">How we can help</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We provide person-centred care at home across Kent. Get in touch to discuss your needs or arrange a visit.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . $contact_url . '">Get in touch</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
	) );

	// Contact prompt
	register_block_pattern( 'ccs-wp-theme/contact-prompt', array(
		'title'       => __( 'Contact Prompt', 'ccs-wp-theme' ),
		'description' => __( 'A short line with a link or button to the contact page.', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'contact', 'prompt', 'link' ),
		'content'     => '<!-- wp:paragraph -->
<p>Questions about our care services? <a href="' . $contact_url . '">Contact us</a> for a friendly chat or to arrange a visit.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . $contact_url . '">Contact us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->',
	) );

	// Trust / stats strip
	register_block_pattern( 'ccs-wp-theme/trust-stats-strip', array(
		'title'       => __( 'Trust &amp; Stats Strip', 'ccs-wp-theme' ),
		'description' => __( 'A row of 2–3 stat-style items (number + label) with CCS spacing and colours.', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'stats', 'trust', 'numbers', 'strip' ),
		'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}},"border":{"radius":"14px"}},"backgroundColor":"background"} -->
<div class="wp-block-group has-background-background-color has-background" style="border-radius:14px;padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--lg)"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.75rem","fontWeight":"700"}}} -->
<p class="has-text-align-center" style="font-size:1.75rem;font-weight:700">15+</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.9375rem"}}} -->
<p class="has-text-align-center" style="font-size:0.9375rem">Years of care experience</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.75rem","fontWeight":"700"}}} -->
<p class="has-text-align-center" style="font-size:1.75rem;font-weight:700">100%</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.9375rem"}}} -->
<p class="has-text-align-center" style="font-size:0.9375rem">Person-centred approach</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.75rem","fontWeight":"700"}}} -->
<p class="has-text-align-center" style="font-size:1.75rem;font-weight:700">Kent</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.9375rem"}}} -->
<p class="has-text-align-center" style="font-size:0.9375rem">Serving our local community</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
	) );

	// Quote / testimonial
	register_block_pattern( 'ccs-wp-theme/quote-testimonial', array(
		'title'       => __( 'Quote or Testimonial', 'ccs-wp-theme' ),
		'description' => __( 'A quote block with optional attribution.', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'quote', 'testimonial', 'citation' ),
		'content'     => '<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>They took time to understand what mattered to us and have been flexible and kind. We couldn’t ask for better care at home.</p><cite>— Family member</cite></blockquote>
<!-- /wp:quote -->',
	) );

	// Key points list
	register_block_pattern( 'ccs-wp-theme/key-points-list', array(
		'title'       => __( 'Key Points List', 'ccs-wp-theme' ),
		'description' => __( 'A list with checkmark-style or styled list items.', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'list', 'key points', 'checklist', 'benefits' ),
		'content'     => '<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">What we offer</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><li>Person-centred care plans tailored to you</li><li>Experienced, trained care staff</li><li>Flexible visits from short calls to live-in care</li><li>Support with daily living and companionship</li></ul>
<!-- /wp:list -->',
	) );

	// Secondary CTA (Find out more)
	register_block_pattern( 'ccs-wp-theme/find-out-more-cta', array(
		'title'       => __( 'Find Out More CTA', 'ccs-wp-theme' ),
		'description' => __( 'A compact CTA with heading and secondary-style button.', 'ccs-wp-theme' ),
		'categories'  => array( 'ccs-patterns' ),
		'keywords'    => array( 'cta', 'find out more', 'button' ),
		'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md"}}}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--md)"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Want to know more?</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Read about our services or get in touch for a no-obligation conversation.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/services/' ) ) . '">Find out more</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
	) );
}
add_action( 'init', 'ccs_register_block_patterns' );
