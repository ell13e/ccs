<?php
/**
 * Homepage differentiators: six items in 2-column grid with number badge, title, description, detail.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	array(
		'title'   => __( 'CQC regulated', 'ccs-wp-theme' ),
		'desc'    => __( 'We meet the Care Quality Commission’s standards so you can be confident in the quality and safety of our care.', 'ccs-wp-theme' ),
		'detail'  => __( 'Our latest CQC report is available on request and we’re happy to talk through what it means for you.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'A team matched to you, not whoever is free', 'ccs-wp-theme' ),
		'desc'    => __( 'We aim for the same small team to visit you so you build a relationship and don’t have to keep explaining your needs. If nobody in our current team is the right fit for your hours, your home or just doesn’t get on with you personally, we recruit someone specifically for you.', 'ccs-wp-theme' ),
		'detail'  => __( 'Introductions and handovers are part of every new care package. You’re never stuck with whoever happens to be on the rota.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Care plans built around you', 'ccs-wp-theme' ),
		'desc'    => __( 'Your care plan starts with how you actually live, not a one-size-fits-all checklist.', 'ccs-wp-theme' ),
		'detail'  => __( 'We review plans regularly and adjust as your situation changes.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Transparent pricing', 'ccs-wp-theme' ),
		'desc'    => __( 'No hidden fees. You’ll know the cost of your care before you commit, and we’ll help you understand funding options if needed.', 'ccs-wp-theme' ),
		'detail'  => __( 'We can advise on direct payments, NHS continuing healthcare and other funding.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Local, and quick to respond', 'ccs-wp-theme' ),
		'desc'    => __( 'We’re based in Maidstone and work across Kent, so our teams already know your area and can get to you quickly.', 'ccs-wp-theme' ),
		'detail'  => __( 'From Maidstone and West Malling to Tonbridge, Tunbridge Wells, Ashford, Thanet and towns in between.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Family involvement', 'ccs-wp-theme' ),
		'desc'    => __( 'We keep families in the loop and work with you to make sure everyone is comfortable with the care we provide.', 'ccs-wp-theme' ),
		'detail'  => __( 'Regular updates and a named contact for your family.', 'ccs-wp-theme' ),
	),
);
?>

<section class="home-differentiators" aria-labelledby="home-diff-heading">
	<div class="home-differentiators__inner container container--lg">
		<h2 id="home-diff-heading" class="home-differentiators__heading">
			<?php esc_html_e( 'What makes us different', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-differentiators__intro">
			<?php esc_html_e( 'Plenty of providers say the same things. Here’s what we actually do differently.', 'ccs-wp-theme' ); ?>
		</p>
		<div class="home-differentiators__grid">
			<?php foreach ( $items as $i => $item ) : ?>
				<article class="home-diff-item">
					<span class="home-diff-item__number" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
					<h3 class="home-diff-item__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="home-diff-item__desc"><?php echo esc_html( $item['desc'] ); ?></p>
					<div class="home-diff-item__detail">
						<?php echo esc_html( $item['detail'] ); ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
