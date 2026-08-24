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
		'url'  => '',
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
		'url'  => '',
	),
	array(
		'name' => __( 'Restwell Retreats', 'ccs-wp-theme' ),
		'logo' => 'restwell.png',
		'url'  => 'https://www.restwellretreats.co.uk/',
	),
);
?>

<section class="home-partnerships" aria-labelledby="home-partnerships-heading">
	<div class="home-partnerships__inner container container--lg">
		<p class="ccs-eyebrow"><?php esc_html_e( 'Trusted by', 'ccs-wp-theme' ); ?></p>
		<h2 id="home-partnerships-heading" class="home-partnerships__heading">
			<?php esc_html_e( 'Who we work with', 'ccs-wp-theme' ); ?>
		</h2>
		<p class="home-partnerships__subheading">
			<?php esc_html_e( 'The organisations that regulate us, train our carers and set the standards we work to — alongside the sister services we run ourselves. Several of them you can check us against directly.', 'ccs-wp-theme' ); ?>
		</p>
		<ul class="home-partnerships__list">
			<?php foreach ( $partners as $partner ) : ?>
				<?php $logo_path = get_template_directory() . '/assets/images/partners/' . $partner['logo']; ?>
				<?php
				/*
				 * Logos link out where the destination has been verified. Two are
				 * deliberately left as plain images with an empty 'url': iTrust and
				 * Continuity Training Academy could not be resolved to a confirmed
				 * official site, and guessing a URL on a real care company's homepage
				 * risks pointing families at the wrong organisation. Add the URL to the
				 * array above and the markup links them automatically.
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
