<?php
/**
 * The body of a service page (Nov 2026).
 *
 * Four layouts of "text, tick lists and a side form" were rejected, and the
 * client then sent sixteen pages to read so the page would carry real
 * information. So the page is now a run of short blocks, each with one job,
 * the same on all eight services (structure after lovettcare.co.uk's service
 * pages, which the client called brilliant; CCS colours and type):
 *
 * The order follows Helping Hands' single service pages, which the client
 * pointed to as good ("they do good singular pages"); CCS colours and type:
 *   1. What this care is: a centred heading, one paragraph, the page's own
 *      opening text. The photo and price tab stay in the page header.
 *   2. One card to page through: what it includes, who it helps, how its
 *      options differ (the benefits card from "Working with us").
 *   3. How care starts, as the stepper from Getting started.
 *   4. What it costs, beside the call-back card.
 *   5. "Good to know": the rest of the page's own content, in the accordion.
 *
 * The explanatory text comes from ccs_service_guide() (inc/service-guides.php);
 * the intro, lists and sections are the page's own editor content, unchanged.
 *
 * Args: post_id, intro (HTML), lists, prose (sections), features (rows),
 * form (HTML of the call-back card).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_sb_id    = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$ccs_sb_slug  = get_post_field( 'post_name', $ccs_sb_id );
$ccs_sb_intro = isset( $args['intro'] ) ? (string) $args['intro'] : '';
$ccs_sb_prose = isset( $args['prose'] ) && is_array( $args['prose'] ) ? $args['prose'] : array();
$ccs_sb_form  = isset( $args['form'] ) ? (string) $args['form'] : '';
$ccs_sb_guide = function_exists( 'ccs_service_guide' ) ? ccs_service_guide( $ccs_sb_slug ) : array();

// The page's own lists: each a heading and its items.
$ccs_sb_lists = array();
foreach ( isset( $args['lists'] ) && is_array( $args['lists'] ) ? $args['lists'] : array() as $ccs_sb_sec ) {
	if ( preg_match_all( '#<li[^>]*>(.*?)</li>#s', $ccs_sb_sec['body'], $ccs_sb_m ) ) {
		$ccs_sb_lists[] = array( $ccs_sb_sec['heading'], array_map( 'wp_strip_all_tags', $ccs_sb_m[1] ) );
	}
}
$ccs_sb_feature_items = array();
foreach ( isset( $args['features'] ) && is_array( $args['features'] ) ? $args['features'] : array() as $ccs_sb_row ) {
	if ( ! empty( $ccs_sb_row['feature_text'] ) ) {
		$ccs_sb_feature_items[] = $ccs_sb_row['feature_text'];
	}
}
if ( $ccs_sb_feature_items ) {
	$ccs_sb_lists[] = array( ccs_copy( 'service_included_title' ), $ccs_sb_feature_items );
}
$ccs_sb_first = $ccs_sb_lists ? $ccs_sb_lists[0] : null;
$ccs_sb_rest  = array_slice( $ccs_sb_lists, 1 );

$ccs_sb_groups  = function_exists( 'ccs_fee_groups_for_service' ) ? ccs_fee_groups_for_service( $ccs_sb_slug ) : array();
$ccs_sb_fees    = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
$ccs_sb_costs   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'costs-and-funding' ) : home_url( '/costs-and-funding/' );
$ccs_sb_name    = mb_strtolower( get_the_title( $ccs_sb_id ) );
?>

<?php
/*
 * Pack layout (Nov 2026). When the client's copy pack has this service
 * (inc/service-copy.php), the page is built from it, in the order of the
 * single service pages she pointed to as good (Helping Hands): the opening,
 * then each section of the pack as its own band, then how care starts, the
 * cost beside the call-back card, and the pack's questions (printed by
 * single-service.php). A section whose items have a lead-in becomes a row
 * of cards, a heading and one line each; plain items become a ticked list
 * in one white panel; text alone stays text. The page's older editor text
 * is not printed on these pages: the pack replaces it.
 */
$ccs_sb_copy = function_exists( 'ccs_service_copy' ) ? ccs_service_copy( $ccs_sb_slug ) : array();
?>
<?php if ( $ccs_sb_copy ) : ?>
	<section class="ccs-section svc-what" aria-labelledby="svc-what-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center svc-what__head">
				<h2 id="svc-what-heading" class="ccs-title"><?php echo esc_html( ccs_sentence_case( $ccs_sb_copy['title'] ) ); ?></h2>
			</header>
			<div class="svc-what__body">
				<?php foreach ( $ccs_sb_copy['intro'] as $ccs_sb_i => $ccs_sb_para ) : ?>
					<p<?php echo 0 === $ccs_sb_i ? ' class="svc-what__lede"' : ''; ?>><?php echo esc_html( $ccs_sb_para ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php foreach ( $ccs_sb_copy['sections'] as $ccs_sb_n => $ccs_sb_sec ) : ?>
		<?php
		$ccs_sb_leads = $ccs_sb_sec['items'] && '' !== $ccs_sb_sec['items'][0][0];
		$ccs_sb_tint  = 0 === $ccs_sb_n % 2;
		?>
		<section class="ccs-section<?php echo $ccs_sb_tint ? ' ccs-section--tint' : ''; ?> svc-part" aria-labelledby="svc-part-<?php echo (int) $ccs_sb_n; ?>">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="svc-part-<?php echo (int) $ccs_sb_n; ?>" class="ccs-title"><?php echo esc_html( ccs_sentence_case( $ccs_sb_sec['heading'] ) ); ?></h2>
				</header>
				<?php if ( $ccs_sb_sec['paras'] ) : ?>
					<div class="svc-part__text">
						<?php foreach ( $ccs_sb_sec['paras'] as $ccs_sb_para ) : ?>
							<p><?php echo esc_html( $ccs_sb_para ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( $ccs_sb_leads ) : ?>
					<ul class="svc-points svc-points--<?php echo (int) min( 4, count( $ccs_sb_sec['items'] ) ); ?><?php echo $ccs_sb_tint ? '' : ' svc-points--tint'; ?>" role="list">
						<?php foreach ( $ccs_sb_sec['items'] as $ccs_sb_item ) : ?>
							<li class="svc-point" data-ccs-exception="rule 1: one part of the service, a heading and its line, set in a row to be compared">
								<h3 class="svc-point__title"><?php echo esc_html( ccs_sentence_case( $ccs_sb_item[0] ) ); ?></h3>
								<p class="svc-point__text"><?php echo esc_html( $ccs_sb_item[1] ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php elseif ( $ccs_sb_sec['items'] ) : ?>
					<ul class="benefit-rotator__list svc-ticks<?php echo $ccs_sb_tint ? '' : ' svc-ticks--tint'; ?>" role="list" data-ccs-exception="rule 1: the list of what is included, one panel">
						<?php foreach ( $ccs_sb_sec['items'] as $ccs_sb_item ) : ?>
							<li><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><?php echo esc_html( $ccs_sb_item[1] ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	<?php endforeach; ?>

	<?php
	$ccs_sb_k     = count( $ccs_sb_copy['sections'] );
	$ccs_sb_steps = array();
	foreach ( array( 1, 2, 3 ) as $ccs_sb_n ) {
		$ccs_sb_steps[] = array(
			'tab'   => (string) $ccs_sb_n,
			'name'  => ccs_copy( 'steps_' . $ccs_sb_n . '_title' ),
			'num'   => '0' . $ccs_sb_n,
			'title' => ccs_copy( 'steps_' . $ccs_sb_n . '_title' ),
			'body'  => '<p>' . esc_html( ccs_copy( 'steps_' . $ccs_sb_n . '_text' ) ) . '</p>',
		);
	}
	?>
	<section class="ccs-section<?php echo 0 === $ccs_sb_k % 2 ? ' ccs-section--tint' : ''; ?> svc-steps" aria-labelledby="svc-steps-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="svc-steps-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'steps_title' ) ); ?></h2>
			</header>
			<?php get_template_part( 'template-parts/stepper', null, array( 'id' => 'svc-steps', 'labelledby' => 'svc-steps-heading', 'items' => $ccs_sb_steps ) ); ?>
		</div>
	</section>

	<section class="ccs-section<?php echo 1 === $ccs_sb_k % 2 ? ' ccs-section--tint' : ''; ?> svc-costs-section">
		<div class="container container--lg">
			<div class="svc-split svc-split--form">
				<?php if ( $ccs_sb_groups ) : ?>
					<?php get_template_part( 'template-parts/service-costs', null, array( 'service' => $ccs_sb_slug, 'bare' => true, 'on_white' => 0 === $ccs_sb_k % 2 ) ); ?>
				<?php else : ?>
					<div class="svc-costs-note">
						<h2 class="svc-heading">
							<?php
							/* translators: %s: service name. */
							echo esc_html( sprintf( __( 'What %s costs', 'ccs-wp-theme' ), $ccs_sb_name ) );
							?>
						</h2>
						<p class="svc-lede"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
						<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_sb_costs ); ?>"><?php esc_html_e( 'See our published rates', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</div>
				<?php endif; ?>
				<?php echo $ccs_sb_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built in single-service.php. ?>
			</div>
		</div>
	</section>
	<?php return; ?>
<?php endif; ?>

<?php // 1. What this care is: a centred heading, one paragraph, then the page's own opening text. ?>
<section class="ccs-section svc-what" aria-labelledby="svc-what-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center svc-what__head">
			<?php if ( ! empty( $ccs_sb_guide['what'] ) ) : ?>
				<h2 id="svc-what-heading" class="ccs-title"><?php echo esc_html( $ccs_sb_guide['what'][0] ); ?></h2>
				<p class="ccs-lede svc-what__lede"><?php echo esc_html( $ccs_sb_guide['what'][1] ); ?></p>
			<?php else : ?>
				<h2 id="svc-what-heading" class="ccs-title"><?php echo esc_html( get_the_title( $ccs_sb_id ) ); ?></h2>
			<?php endif; ?>
		</header>
		<?php if ( '' !== trim( wp_strip_all_tags( $ccs_sb_intro ) ) ) : ?>
			<div class="svc-what__intro entry-content"><?php echo $ccs_sb_intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
		<?php endif; ?>
	</div>
</section>

<?php
/*
 * 2. One card to page through: what it includes, who it helps, how the
 * options differ. The card the client likes on "Working with us" (the
 * benefits card), in place of three stacked bands of tiles she called ugly
 * and asked to be rethought ("think outside the box"). Without JavaScript
 * the three panels simply follow one another.
 */
$ccs_sb_panels = array();
if ( $ccs_sb_first ) {
	$ccs_sb_panels[] = array( 'title' => ucfirst( strtolower( $ccs_sb_first[0] ) ), 'list' => $ccs_sb_first[1] );
}
if ( ! empty( $ccs_sb_guide['who'] ) ) {
	$ccs_sb_panels[] = array( 'title' => __( 'Who it helps', 'ccs-wp-theme' ), 'list' => $ccs_sb_guide['who'] );
}
if ( ! empty( $ccs_sb_guide['compare'] ) ) {
	$ccs_sb_panels[] = array( 'title' => $ccs_sb_guide['compare'][0], 'pairs' => $ccs_sb_guide['compare'][1] );
}
?>
<?php if ( $ccs_sb_panels ) : ?>
	<section class="ccs-section ccs-section--tint svc-turn-section" aria-label="<?php esc_attr_e( 'About this care', 'ccs-wp-theme' ); ?>">
		<div class="container container--lg">
			<div class="quote-rotator quote-rotator--on-tint benefit-rotator svc-turn" data-ccs-stepper>
				<div class="quote-rotator__panels">
					<?php foreach ( $ccs_sb_panels as $ccs_sb_pi => $ccs_sb_panel ) : ?>
						<div class="quote-rotator__panel benefit-rotator__panel" role="tabpanel" id="svc-turn-panel-<?php echo (int) $ccs_sb_pi; ?>" aria-labelledby="svc-turn-dot-<?php echo (int) $ccs_sb_pi; ?>">
							<div class="benefit-rotator__head">
								<h2 class="benefit-rotator__title svc-turn__title"><?php echo esc_html( $ccs_sb_panel['title'] ); ?></h2>
							</div>
							<?php if ( ! empty( $ccs_sb_panel['list'] ) ) : ?>
								<ul class="benefit-rotator__list svc-turn__list" role="list">
									<?php foreach ( $ccs_sb_panel['list'] as $ccs_sb_item ) : ?>
										<li><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><?php echo esc_html( trim( $ccs_sb_item ) ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							<?php else : ?>
								<dl class="svc-turn__pairs">
									<?php foreach ( $ccs_sb_panel['pairs'] as $ccs_sb_pair ) : ?>
										<div>
											<dt><?php echo esc_html( $ccs_sb_pair[0] ); ?></dt>
											<dd><?php echo esc_html( $ccs_sb_pair[1] ); ?></dd>
										</div>
									<?php endforeach; ?>
								</dl>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( count( $ccs_sb_panels ) > 1 ) : ?>
					<div class="quote-rotator__controls">
						<button type="button" class="quote-rotator__arrow" data-ccs-step-prev aria-label="<?php esc_attr_e( 'Previous', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&larr;</span></button>
						<div class="quote-rotator__dots" role="tablist" aria-label="<?php esc_attr_e( 'About this care', 'ccs-wp-theme' ); ?>">
							<?php foreach ( $ccs_sb_panels as $ccs_sb_pi => $ccs_sb_panel ) : ?>
								<button type="button" class="quote-rotator__dot" role="tab" id="svc-turn-dot-<?php echo (int) $ccs_sb_pi; ?>" aria-controls="svc-turn-panel-<?php echo (int) $ccs_sb_pi; ?>" aria-selected="<?php echo 0 === $ccs_sb_pi ? 'true' : 'false'; ?>">
									<span class="screen-reader-text"><?php echo esc_html( $ccs_sb_panel['title'] ); ?></span>
								</button>
							<?php endforeach; ?>
						</div>
						<button type="button" class="quote-rotator__arrow" data-ccs-step-next aria-label="<?php esc_attr_e( 'Next', 'ccs-wp-theme' ); ?>"><span aria-hidden="true">&rarr;</span></button>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
/*
 * 3. How care starts, as the stepper from Getting started (which the client
 * likes), not the three-column panel: one step at a time, with Next.
 */
$ccs_sb_steps = array();
foreach ( array( 1, 2, 3 ) as $ccs_sb_n ) {
	$ccs_sb_steps[] = array(
		'tab'   => (string) $ccs_sb_n,
		'name'  => ccs_copy( 'steps_' . $ccs_sb_n . '_title' ),
		'num'   => '0' . $ccs_sb_n,
		'title' => ccs_copy( 'steps_' . $ccs_sb_n . '_title' ),
		'body'  => '<p>' . esc_html( ccs_copy( 'steps_' . $ccs_sb_n . '_text' ) ) . '</p>',
	);
}
?>
<section class="ccs-section svc-steps" aria-labelledby="svc-steps-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center">
			<h2 id="svc-steps-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'steps_title' ) ); ?></h2>
		</header>
		<?php get_template_part( 'template-parts/stepper', null, array( 'id' => 'svc-steps', 'labelledby' => 'svc-steps-heading', 'items' => $ccs_sb_steps ) ); ?>
	</div>
</section>

<?php // 4. What it costs, beside the call-back card. ?>
<section class="ccs-section ccs-section--tint svc-costs-section">
	<div class="container container--lg">
		<div class="svc-split svc-split--form">
			<?php if ( $ccs_sb_groups ) : ?>
				<?php get_template_part( 'template-parts/service-costs', null, array( 'service' => $ccs_sb_slug, 'bare' => true ) ); ?>
			<?php else : ?>
				<?php // No rate card is mapped to this service yet, so the page points to the published rates instead of showing none. ?>
				<div class="svc-costs-note">
					<h2 class="svc-heading">
						<?php
						/* translators: %s: service name. */
						echo esc_html( sprintf( __( 'What %s costs', 'ccs-wp-theme' ), $ccs_sb_name ) );
						?>
					</h2>
					<p class="svc-lede"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
					<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_sb_costs ); ?>"><?php esc_html_e( 'See our published rates', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
				</div>
			<?php endif; ?>
			<?php echo $ccs_sb_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built in single-service.php. ?>
		</div>
	</div>
</section>

<?php // 5. Good to know: the rest of the page's own content. ?>
<?php if ( $ccs_sb_rest || $ccs_sb_prose ) : ?>
	<section class="ccs-section svc-more" aria-labelledby="svc-more-heading">
		<div class="container container--lg">
			<div class="svc-more__inner">
				<header class="ccs-head ccs-head--center">
					<h2 id="svc-more-heading" class="ccs-title"><?php esc_html_e( 'Good to know', 'ccs-wp-theme' ); ?></h2>
				</header>
				<div class="ccs-faq">
					<?php $ccs_sb_i = 0; ?>
					<?php foreach ( $ccs_sb_rest as $ccs_sb_list ) : ?>
						<details class="ccs-faq__item" name="service-more"<?php echo 0 === $ccs_sb_i++ ? ' open' : ''; ?>>
							<summary class="ccs-faq__q"><?php echo esc_html( ucfirst( strtolower( $ccs_sb_list[0] ) ) ); ?></summary>
							<div class="ccs-faq__a">
								<ul class="svc-list__items">
									<?php foreach ( $ccs_sb_list[1] as $ccs_sb_item ) : ?>
										<li><?php echo esc_html( trim( $ccs_sb_item ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</details>
					<?php endforeach; ?>
					<?php foreach ( $ccs_sb_prose as $ccs_sb_sec ) : ?>
						<details class="ccs-faq__item" name="service-more"<?php echo 0 === $ccs_sb_i++ ? ' open' : ''; ?>>
							<summary class="ccs-faq__q"><?php echo esc_html( $ccs_sb_sec['heading'] ); ?></summary>
							<div class="ccs-faq__a"><?php echo $ccs_sb_sec['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
