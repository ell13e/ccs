<?php
/**
 * Template Name: Which Care
 *
 * Four radio questions in one form card (rule 1: a form is its own thing),
 * then, once answered, the matching services as the site's usual service
 * cards. The CTA band below carries the free consultation, so the result
 * has no button of its own (rule 8). No script; see inc/care-finder.php.
 *
 * Questions are optional: whatever is answered narrows the suggestion, and
 * nothing answered still shows the consultation, since "we don't know" is
 * exactly what that call is for.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_cf_q        = ccs_care_finder_questions();
$ccs_cf_answers  = ccs_care_finder_answers();
// phpcs:ignore WordPress.Security.NonceVerification.Missing -- read-only form, nothing is stored.
$ccs_cf_sent     = isset( $_POST['found'] );
$ccs_cf_cond     = $ccs_cf_sent ? ccs_care_finder_condition_text() : '';
$ccs_cf_match    = ccs_care_finder_match_conditions( $ccs_cf_cond );
$ccs_cf_services = $ccs_cf_sent ? ccs_care_finder_services( $ccs_cf_answers, $ccs_cf_match['services'] ) : array();
// Something typed that matched nothing: never a "no", an invitation to talk.
$ccs_cf_unknown  = '' !== $ccs_cf_cond && ! $ccs_cf_match['matched'];
?>

<main id="main" class="site-main site-main--care-finder" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-header' );
		?>

		<section class="ccs-section care-finder" aria-label="<?php esc_attr_e( 'Questions', 'ccs-wp-theme' ); ?>">
			<div class="container">
				<form class="care-finder__form ccs-surface ccs-surface--pad-lg" method="post" action="<?php echo esc_url( get_permalink() . '#care-finder-result' ); ?>">
					<input type="hidden" name="found" value="1">
					<?php foreach ( $ccs_cf_q as $ccs_cf_name => $ccs_cf_question ) : ?>
						<fieldset class="ccs-field ccs-choices">
							<legend class="ccs-field__label"><?php echo esc_html( $ccs_cf_question['legend'] ); ?></legend>
							<?php foreach ( $ccs_cf_question['options'] as $ccs_cf_value => $ccs_cf_option ) : ?>
								<label class="ccs-choice">
									<input type="radio" name="<?php echo esc_attr( $ccs_cf_name ); ?>" value="<?php echo esc_attr( $ccs_cf_value ); ?>" <?php checked( isset( $ccs_cf_answers[ $ccs_cf_name ] ) && $ccs_cf_answers[ $ccs_cf_name ] === $ccs_cf_value ); ?>>
									<span><?php echo esc_html( $ccs_cf_option[0] ); ?></span>
								</label>
							<?php endforeach; ?>
						</fieldset>
						<?php if ( 'when' === $ccs_cf_name ) : ?>
							<div class="ccs-field care-finder__cond">
								<label for="care-finder-cond" class="ccs-field__label"><?php esc_html_e( 'Is a condition shaping the care?', 'ccs-wp-theme' ); ?></label>
								<p id="care-finder-cond-hint" class="ccs-field__hint"><?php esc_html_e( 'Start typing to see suggestions, or describe it in your own words. Leave it blank if none, or if you’re not sure.', 'ccs-wp-theme' ); ?></p>
								<input type="text" id="care-finder-cond" name="cond" class="ccs-field__input" list="care-finder-conditions" autocomplete="off" maxlength="100" aria-describedby="care-finder-cond-hint" value="<?php echo esc_attr( $ccs_cf_cond ); ?>">
								<datalist id="care-finder-conditions">
									<?php foreach ( array_keys( ccs_care_finder_conditions() ) as $ccs_cf_label ) : ?>
										<option value="<?php echo esc_attr( $ccs_cf_label ); ?>"></option>
									<?php endforeach; ?>
								</datalist>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<div class="care-finder__foot">
						<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Show the care that fits', 'ccs-wp-theme' ); ?></button>
						<p class="care-finder__note"><?php esc_html_e( 'Answer as many as you can. Your answers aren’t stored or sent to our team.', 'ccs-wp-theme' ); ?></p>
					</div>
				</form>
			</div>
		</section>

		<?php if ( $ccs_cf_sent ) : ?>
			<section class="ccs-section ccs-section--tint care-finder-result" id="care-finder-result" aria-labelledby="care-finder-result-heading" tabindex="-1">
				<div class="container">
					<header class="ccs-head">
						<h2 id="care-finder-result-heading" class="ccs-title">
							<?php
							echo $ccs_cf_services && ! $ccs_cf_unknown
								? esc_html__( 'Care that may fit', 'ccs-wp-theme' )
								: esc_html__( 'Let’s talk it through', 'ccs-wp-theme' );
							?>
						</h2>
						<p class="ccs-lede">
							<?php
							if ( $ccs_cf_unknown ) {
								esc_html_e( 'We don’t have a page for that, but it doesn’t mean we can’t help. Tell us about it in a free consultation and we’ll say honestly whether we’re the right fit.', 'ccs-wp-theme' );
								if ( $ccs_cf_services ) {
									echo ' ' . esc_html__( 'Meanwhile, these fit your other answers.', 'ccs-wp-theme' );
								}
							} else {
								echo $ccs_cf_services
									? esc_html__( 'A starting point, not a decision. Many people have more than one of these, and a free consultation is where we work out the mix.', 'ccs-wp-theme' )
									: esc_html__( 'That’s fine. Not knowing what you need is what the free consultation is for.', 'ccs-wp-theme' );
							}
							?>
						</p>
					</header>
					<?php
					if ( $ccs_cf_services ) {
						get_template_part( 'template-parts/service-cards', null, array( 'services' => $ccs_cf_services ) );
					}
					?>
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
