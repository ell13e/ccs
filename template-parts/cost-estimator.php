<?php
/**
 * Weekly cost estimator (assets/js/cost-estimator.js).
 *
 * Taken off the Costs page in Nov 2026: the user called a rates table plus a
 * calculator "ugly and complex" and pointed to a plain page of prices
 * instead. Kept here, unused, so it can come back with one line:
 * get_template_part( 'template-parts/cost-estimator' ).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
?>
	<?php
	/*
	 * Weekly cost estimator. Hidden until assets/js/cost-estimator.js runs,
	 * so a visitor without JavaScript never sees controls that do nothing;
	 * the rate table above still answers the question for them.
	 */
	$ccs_est_rates = function_exists( 'ccs_estimator_rates' ) ? ccs_estimator_rates() : array();
	?>
	<?php if ( ! empty( $ccs_est_rates ) ) : ?>
		<section id="costs-estimate" class="ccs-section ccs-section--tint costs-estimate" aria-labelledby="costs-estimate-heading" data-ccs-estimator data-rates="<?php echo esc_attr( wp_json_encode( $ccs_est_rates ) ); ?>" hidden>
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="costs-estimate-heading" class="ccs-title"><?php esc_html_e( 'Estimate your week', 'ccs-wp-theme' ); ?></h2>
					<p class="ccs-lede"><?php esc_html_e( 'Choose the kind of care and the hours you think you’ll need, at our published daytime rates.', 'ccs-wp-theme' ); ?></p>
				</header>

				<?php
				/*
				 * One calculator card (client, Nov 2026: the two-column estimator
				 * "needs work"): the three controls in a row, then the answer in a
				 * band beneath them, with the note and the next step.
				 */
				?>
				<div class="calc" data-ccs-exception="rule 1: the calculator, one tool">
					<div class="calc__controls">
						<fieldset class="calc__field">
							<legend class="calc__label"><?php esc_html_e( 'Kind of care', 'ccs-wp-theme' ); ?></legend>
							<div class="estimator__kinds">
								<?php foreach ( $ccs_est_rates as $ccs_i => $ccs_rate ) : ?>
									<label class="estimator__kind">
										<input type="radio" name="est_kind" value="<?php echo (int) $ccs_i; ?>"<?php checked( 0, $ccs_i ); ?>>
										<span><?php echo esc_html( $ccs_rate['name'] ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>

						<?php
						$ccs_steppers = array(
							array( 'id' => 'est-weekday', 'name' => 'est_weekday', 'label' => __( 'Weekday hours', 'ccs-wp-theme' ), 'hint' => __( 'Mon–Fri, 7am–10pm, each week', 'ccs-wp-theme' ), 'value' => 5, 'max' => 75 ),
							array( 'id' => 'est-weekend', 'name' => 'est_weekend', 'label' => __( 'Weekend hours', 'ccs-wp-theme' ), 'hint' => __( 'Sat–Sun, 7am–10pm, each week', 'ccs-wp-theme' ), 'value' => 0, 'max' => 30 ),
						);
						?>
						<?php foreach ( $ccs_steppers as $ccs_st ) : ?>
							<div class="calc__field">
								<label class="calc__label" for="<?php echo esc_attr( $ccs_st['id'] ); ?>"><?php echo esc_html( $ccs_st['label'] ); ?></label>
								<div class="estimator__stepper">
									<button type="button" class="estimator__step" data-step="-1" aria-controls="<?php echo esc_attr( $ccs_st['id'] ); ?>">
										<span aria-hidden="true">&minus;</span><span class="screen-reader-text"><?php esc_html_e( 'One hour less', 'ccs-wp-theme' ); ?></span>
									</button>
									<input type="number" id="<?php echo esc_attr( $ccs_st['id'] ); ?>" name="<?php echo esc_attr( $ccs_st['name'] ); ?>" class="estimator__input" value="<?php echo (int) $ccs_st['value']; ?>" min="0" max="<?php echo (int) $ccs_st['max']; ?>" step="1" inputmode="numeric" aria-describedby="<?php echo esc_attr( $ccs_st['id'] ); ?>-hint">
									<button type="button" class="estimator__step" data-step="1" aria-controls="<?php echo esc_attr( $ccs_st['id'] ); ?>">
										<span aria-hidden="true">+</span><span class="screen-reader-text"><?php esc_html_e( 'One hour more', 'ccs-wp-theme' ); ?></span>
									</button>
								</div>
								<p class="calc__hint" id="<?php echo esc_attr( $ccs_st['id'] ); ?>-hint"><?php echo esc_html( $ccs_st['hint'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="calc__result" aria-live="polite">
						<div class="calc__total">
							<p class="calc__total-label"><?php esc_html_e( 'Estimated cost a week', 'ccs-wp-theme' ); ?></p>
							<p class="calc__week" data-est="week">£0.00</p>
							<p class="calc__month">
								<?php
								printf(
									/* translators: %s: monthly amount. */
									esc_html__( 'About %s a month', 'ccs-wp-theme' ),
									'<span data-est="month">£0</span>'
								);
								?>
							</p>
						</div>
						<ul class="calc__lines" role="list">
							<li data-est="weekday-line"></li>
							<li data-est="weekend-line"></li>
						</ul>
						<div class="calc__action">
							<a class="btn btn-primary" href="<?php echo esc_url( $ccs_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
						</div>
					</div>
					<p class="calc__note"><?php esc_html_e( 'An estimate, not a quote. It leaves out nights, bank holidays and the one-off £30 set-up fee.', 'ccs-wp-theme' ); ?></p>
				</div>
			</div>
		</section>
	<?php endif; ?>

