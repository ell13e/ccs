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
		'title'   => __( 'Consistent carers', 'ccs-wp-theme' ),
		'desc'    => __( 'We aim for the same small team to visit you so you build a relationship and don’t have to keep explaining your needs.', 'ccs-wp-theme' ),
		'detail'  => __( 'Introductions and handovers are part of every new care package.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Person-centred planning', 'ccs-wp-theme' ),
		'desc'    => __( 'Care plans are built around you and your family, not a one-size-fits-all checklist.', 'ccs-wp-theme' ),
		'detail'  => __( 'We review plans regularly and adjust as your situation changes.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Transparent pricing', 'ccs-wp-theme' ),
		'desc'    => __( 'No hidden fees. You’ll know the cost of your care before you commit, and we’ll help you understand funding options if needed.', 'ccs-wp-theme' ),
		'detail'  => __( 'We can advise on direct payments, NHS continuing healthcare and other funding.', 'ccs-wp-theme' ),
	),
	array(
		'title'   => __( 'Local and responsive', 'ccs-wp-theme' ),
		'desc'    => __( 'Based in Maidstone, we cover Kent. That means we can respond quickly and our teams know the area.', 'ccs-wp-theme' ),
		'detail'  => __( 'We serve towns and villages across the county.', 'ccs-wp-theme' ),
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
			<?php esc_html_e( 'Why choose us', 'ccs-wp-theme' ); ?>
		</h2>
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
