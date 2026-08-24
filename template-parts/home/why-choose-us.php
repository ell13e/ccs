<?php
/**
 * Homepage Why Choose Us section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'home-care-services-kent' ) : home_url( '/home-care-services-kent/' );
?>

<section class="home-why-choose" aria-labelledby="home-why-heading">
	<div class="home-why-choose__inner container container--lg">
		<?php
		/*
		 * Heading and subheading are wrapped so the grid has exactly two children
		 * — the rail and the prose. Left as three siblings they wrapped onto a
		 * second row and the two-column layout collapsed.
		 */
		?>
		<div class="home-why-choose__heading-group">
			<p class="ccs-eyebrow"><?php esc_html_e( 'Why families choose us', 'ccs-wp-theme' ); ?></p>
			<h2 id="home-why-heading" class="home-why-choose__heading">
				<?php esc_html_e( 'Why Choose Us for Home Care in Maidstone?', 'ccs-wp-theme' ); ?>
			</h2>
			<p class="home-why-choose__subheading">
				<?php esc_html_e( 'The difference is who turns up, and how often it’s the same person.', 'ccs-wp-theme' ); ?>
			</p>
			<?php
			/*
			 * Proof rail. On a wide screen this column held a heading and one
			 * italic line against five paragraphs beside it, so most of the rail
			 * was empty and the section read as a wall of prose with a label.
			 *
			 * Every point below is a claim the site already makes and can stand
			 * behind — the CQC rating from the section above, the years and
			 * coverage from the hero trust strip, the rest from this section's
			 * own copy. Nothing here is a new statistic: an unverifiable number
			 * on a care provider's homepage is worse than an empty column.
			 */
			$why_proof = array(
				__( 'Rated Good by the CQC', 'ccs-wp-theme' ),
				__( '10+ years caring for Kent families', 'ccs-wp-theme' ),
				__( 'Adults and children supported', 'ccs-wp-theme' ),
				__( 'Care day or night, across Kent', 'ccs-wp-theme' ),
			);
			?>
			<ul class="home-why-choose__proof" role="list">
				<?php foreach ( $why_proof as $why_point ) : ?>
					<li class="home-why-choose__proof-item">
						<svg class="home-why-choose__proof-icon" viewBox="0 0 20 20" width="20" height="20" aria-hidden="true" focusable="false">
							<circle cx="10" cy="10" r="10" fill="currentColor" opacity="0.14" />
							<path d="M6 10.4l2.6 2.6L14.4 7.2" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<span><?php echo esc_html( $why_point ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="home-why-choose__body">
			<p>
				<?php esc_html_e( 'We support adults and children across Maidstone and the rest of Kent, day or night. Our carers live locally, and the same small team comes back each visit.', 'ccs-wp-theme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'We don’t rush, and we don’t rotate staff every other week.', 'ccs-wp-theme' ); ?>
			</p>
			<?php
			/*
			 * Set as a display quote rather than left inside the paragraph above.
			 * This is the most distinctive sentence on the site — every competitor
			 * writes "care that comes from the heart"; nobody else writes about how
			 * you take your tea. Buried in the middle of a three-paragraph block it
			 * was doing none of the work it is capable of.
			 */
			?>
			<blockquote class="home-why-choose__pull">
				<p>
					<?php esc_html_e( 'We take the time to learn how you like your tea, what makes you laugh, and what puts you at ease on a harder day.', 'ccs-wp-theme' ); ?>
				</p>
				<cite class="home-why-choose__pull-cite">
					<?php esc_html_e( 'That’s the part a care plan can’t write down.', 'ccs-wp-theme' ); ?>
				</cite>
			</blockquote>
			<p>
				<?php esc_html_e( 'Good care doesn’t stop when the task list is finished. It shows in whether the person at the door already knows how the week has gone.', 'ccs-wp-theme' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $services_url ); ?>" class="home-why-choose__link">
					<?php esc_html_e( 'Learn more about our services', 'ccs-wp-theme' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</p>
		</div>
	</div>
</section>
