<?php
/**
 * Homepage partnerships section (content guide §2b).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$partners = array(
	array(
		'name' => __( 'National Care Association', 'ccs-wp-theme' ),
		'logo' => 'national-care-association.png',
		'url'  => 'https://nationalcareassociation.org.uk/',
	),
	array(
		'name' => __( 'Disability Confident Committed', 'ccs-wp-theme' ),
		'logo' => 'disability-confident.png',
		'url'  => 'https://www.gov.uk/government/collections/disability-confident-campaign',
	),
	array(
		'name' => __( 'CV Minder', 'ccs-wp-theme' ),
		'logo' => 'cv-minder.png',
		'url'  => 'https://cvminder.co.uk/',
	),
	array(
		'name' => __( 'MidKent College', 'ccs-wp-theme' ),
		'logo' => 'midkent-college.png',
		'url'  => 'https://www.midkent.ac.uk/',
	),
	array(
		'name' => __( 'Kent Integrated Care Alliance (KiCA)', 'ccs-wp-theme' ),
		'logo' => 'kica.png',
		'url'  => 'https://www.kica.care/',
	),
	array(
		'name' => __( 'Care Quality Commission (CQC)', 'ccs-wp-theme' ),
		'logo' => 'cqc.png',
		'url'  => 'https://www.cqc.org.uk/',
	),
	array(
		'name' => __( 'iTrust', 'ccs-wp-theme' ),
		'logo' => 'itrust.png',
		'url'  => 'https://theitrustapp.com/',
	),
	array(
		'name' => __( 'NHS', 'ccs-wp-theme' ),
		'logo' => 'nhs.png',
		'url'  => 'https://www.nhs.uk/',
	),
	array(
		'name' => __( 'Homecare Association', 'ccs-wp-theme' ),
		'logo' => 'homecare-association.png',
		'url'  => 'https://www.homecareassociation.org.uk/',
	),
	array(
		'name' => __( 'Brain Injury Group', 'ccs-wp-theme' ),
		'logo' => 'brain-injury-group.png',
		'url'  => 'https://braininjurygroup.co.uk/',
	),
	array(
		'name' => __( 'Continuity Training Academy', 'ccs-wp-theme' ),
		'logo' => 'continuity-training-academy.png',
		'url'  => 'https://www.continuitytrainingacademy.co.uk/',
	),
	array(
		'name' => __( 'Restwell Retreats', 'ccs-wp-theme' ),
		'logo' => 'restwell.png',
		'url'  => 'https://restwellretreats.co.uk/',
	),
);

/*
 * Marquee layout (homepage, Nov 2026: the user asked for the badges on the
 * homepage, "maybe in a marquee"). A slim strip of the same logos moving
 * slowly; the full grid, with its links, stays on the About page. The logos
 * are printed twice so the loop has no seam: the second set is hidden from
 * assistive technology.
 * It pauses on hover and from its own button, and under
 * prefers-reduced-motion it does not move at all (rule 21).
 */
if ( isset( $args['layout'] ) && 'marquee' === $args['layout'] ) :
	?>
	<?php // A div, not a section: a slim strip that opens the white band below it, not a band of its own. ?>
	<div class="partner-marquee" role="group" aria-labelledby="partner-marquee-heading" data-ccs-marquee>
		<div class="partner-marquee__head container container--lg">
			<h2 id="partner-marquee-heading" class="partner-marquee__title"><?php echo esc_html( ccs_copy( 'partners_title' ) ); ?></h2>
			<?php // Icon-only: the two words beside the heading fought it, and wrapped badly on phones. The label lives in aria-label; interactive.js swaps it. ?>
			<button type="button" class="partner-marquee__toggle" data-ccs-marquee-toggle aria-pressed="false" aria-label="<?php esc_attr_e( 'Pause logos', 'ccs-wp-theme' ); ?>" data-pause="<?php esc_attr_e( 'Pause logos', 'ccs-wp-theme' ); ?>" data-play="<?php esc_attr_e( 'Play logos', 'ccs-wp-theme' ); ?>" hidden><span class="partner-marquee__icon" aria-hidden="true"></span></button>
		</div>
		<div class="partner-marquee__viewport">
			<div class="partner-marquee__track">
				<?php for ( $ccs_pm_set = 0; $ccs_pm_set < 2; $ccs_pm_set++ ) : ?>
					<ul class="partner-marquee__set" role="list"<?php echo 1 === $ccs_pm_set ? ' aria-hidden="true"' : ''; ?>>
						<?php foreach ( $partners as $partner ) : ?>
							<?php
							if ( ! file_exists( get_template_directory() . '/assets/images/partners/' . $partner['logo'] ) ) {
								continue;
							}
							?>
							<li class="partner-marquee__item">
								<?php
								/*
								 * Every logo links to its organisation (Nov 2026, user: "we need
								 * the logos linked wherever they are"). The strip stops while a
								 * pointer or keyboard focus is on it, so the link is not a moving
								 * target. The repeated set is skipped by the keyboard and by
								 * screen readers, so each link is met once.
								 */
								$ccs_pm_url = isset( $partner['url'] ) ? trim( $partner['url'] ) : '';
								?>
								<?php if ( $ccs_pm_url ) : ?>
									<a class="partner-marquee__link" href="<?php echo esc_url( $ccs_pm_url ); ?>" target="_blank" rel="noopener noreferrer"<?php echo 1 === $ccs_pm_set ? ' tabindex="-1"' : ''; ?>>
								<?php endif; ?>
								<img
									src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/' . $partner['logo'] ); ?>"
									alt="<?php echo esc_attr( $partner['name'] ); ?>"
									width="323"
									height="90"
									loading="lazy"
									decoding="async"
								>
								<?php if ( $ccs_pm_url ) : ?>
										<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
									</a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endfor; ?>
			</div>
		</div>
	</div>
	<?php
	return;
endif;
?>

<section class="home-partnerships" aria-labelledby="home-partnerships-heading">
	<div class="home-partnerships__inner container container--lg">
		<header class="ccs-head ccs-head--split">
			<h2 id="home-partnerships-heading" class="ccs-title">
				<?php
				echo esc_html( ccs_copy( 'partners_title' ) );
				?>
			</h2>
		</header>
		<ul class="home-partnerships__list">
			<?php foreach ( $partners as $partner ) : ?>
				<?php $logo_path = get_template_directory() . '/assets/images/partners/' . $partner['logo']; ?>
				<?php
				/*
				 * Every logo links to its organisation's own site; the addresses for
				 * iTrust and Continuity Training Academy were supplied by the user
				 * (Nov 2026). An entry with an empty 'url' prints as a plain image.
				 *
				 * rel="noopener" for the new tab; no "nofollow" — these are genuine
				 * partnerships and the outbound links are the point.
				 */
				$ccs_partner_url = isset( $partner['url'] ) ? trim( $partner['url'] ) : '';
				?>
				<li class="home-partnerships__item">
					<?php if ( $ccs_partner_url ) : ?>
						<a
							class="home-partnerships__link"
							href="<?php echo esc_url( $ccs_partner_url ); ?>"
							target="_blank"
							rel="noopener noreferrer"
						>
					<?php endif; ?>

					<?php if ( file_exists( $logo_path ) ) : ?>
						<img
							class="home-partnerships__logo"
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/' . $partner['logo'] ); ?>"
							alt="<?php echo esc_attr( $partner['name'] ); ?>"
							width="323"
							height="90"
							loading="lazy"
							decoding="async"
						>
					<?php else : ?>
						<?php echo esc_html( $partner['name'] ); ?>
					<?php endif; ?>

					<?php if ( $ccs_partner_url ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
