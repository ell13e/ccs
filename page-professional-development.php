<?php
/**
 * Careers > Professional Development (page slug: professional-development).
 *
 * WordPress picks this file for the page by its slug. The page was the
 * default long-form layout; the user asked for more visual interest (Nov
 * 2026). Its opening paragraph sits beside a photo of the training room and
 * its sections are in the site's accordion. A drawn career path (rising bars, then a
 * numbered line) was tried twice and removed at the user's request: the
 * "Where it leads" section says it in words.
 *
 * A "my journey" story from a member of staff (the user suggested Shayna or
 * Keelie) prints only once its fields are filled in Customize > Page wording
 * > Careers: nothing is written on their behalf.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_pd_story = trim( (string) get_theme_mod( 'ccs_copy_pd_story_text', '' ) );
$ccs_pd_name  = trim( (string) get_theme_mod( 'ccs_copy_pd_story_name', '' ) );
?>

<main id="main" class="site-main site-main--careers-page" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-header' );
		get_template_part(
			'template-parts/careers/sub-page',
			null,
			array(
				'id'          => 'development',
				// Each careers page has its own photos: none is used twice across the section (user).
				'photo'       => 'careers/child-cpr.webp',
				'position'    => '50% 25%',
				'alt'         => __( 'A trainer holding a child resuscitation manikin during a first aid session', 'ccs-wp-theme' ),
				'title'       => ccs_copy( 'pd_intro_title' ),
				'tabs_title'  => ccs_copy( 'pd_tabs_title' ),
				'skip_topics' => true,
			)
		);

		/*
		 * Second pass (user: "this page could be so much better"). It was an
		 * intro and an accordion; the training academy now has its own
		 * photo-and-tab feature between them: heading, one paragraph, a link.
		 * Tried and removed the same day: the qualifications as three cards
		 * with the level set large ("this isn't good"), and a ticked list of
		 * eight specialist topics ("busy and messy"). Both stay in the
		 * accordion, in the client's own words.
		 */
		$ccs_pd_content = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
		$ccs_pd_rest    = $ccs_pd_content['sections'];
		$ccs_pd_badge  = array_map( 'trim', explode( '|', ccs_copy( 'pd_academy_badge' ), 3 ) );
		?>

		<section class="ccs-section ccs-section--tint pd-academy" aria-labelledby="pd-academy-heading">
			<div class="container container--lg">
				<div class="ccs-media-split ccs-media-split--flip pd-academy__layout">
					<div class="pd-academy__text">
						<h2 id="pd-academy-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'pd_academy_title' ) ); ?></h2>
						<p class="pd-academy__lede"><?php echo esc_html( ccs_copy( 'pd_academy_text' ) ); ?></p>
						<a class="btn btn-quiet" href="https://www.continuitytrainingacademy.co.uk/" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Visit the Training Academy', 'ccs-wp-theme' ); ?>
							<span aria-hidden="true">&nearr;</span>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
						</a>
					</div>
					<div class="careers-benefits__media">
						<?php
						ccs_section_photo(
							'journey/cta-trainer.webp',
							array(
								'alt'   => __( 'A Continuity Training Academy trainer presenting beside a flipchart', 'ccs-wp-theme' ),
								'sizes' => '(min-width: 900px) 480px, 92vw',
								'class' => 'careers-benefits__photo',
							)
						);
						?>
						<?php if ( count( $ccs_pd_badge ) > 1 ) : ?>
							<p class="careers-benefits__badge" data-ccs-exception="rule 1: a figure about the academy, set on its photo as a label"><strong><?php echo esc_html( $ccs_pd_badge[0] ); ?></strong><span><?php echo esc_html( $ccs_pd_badge[1] ); ?></span><?php if ( ! empty( $ccs_pd_badge[2] ) ) : ?><small><?php echo esc_html( $ccs_pd_badge[2] ); ?></small><?php endif; ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $ccs_pd_rest ) ) : ?>
			<section class="ccs-section careers-topics" aria-labelledby="development-topics-heading">
				<div class="container container--lg">
					<?php
					get_template_part(
						'template-parts/careers/topics',
						null,
						array(
							'id'       => 'development',
							'title'    => ccs_copy( 'pd_tabs_title' ),
							'sections' => $ccs_pd_rest,
						)
					);
					?>
				</div>
			</section>
		<?php endif; ?>

		<?php
		/*
		 * "My journey". Until a fuller story is supplied (Customize > Page
		 * wording > Careers), this shows two sentences from Keelie Varney's own
		 * profile on this site, word for word, with her photo and a link to the
		 * rest. Nothing is written on anyone's behalf. If her profile text ever
		 * loses those sentences, the section does not print.
		 */
		$ccs_pd_photo = '';
		$ccs_pd_link  = '';
		$ccs_pd_role  = trim( (string) get_theme_mod( 'ccs_copy_pd_story_role', '' ) );
		if ( '' === $ccs_pd_story || '' === $ccs_pd_name ) {
			$ccs_pd_story = '';
			foreach ( function_exists( 'ccs_team_members' ) ? ccs_team_members() : array() as $ccs_pd_member ) {
				if ( 'Keelie Varney' !== $ccs_pd_member['name'] || empty( $ccs_pd_member['bio'] ) ) {
					continue;
				}
				$ccs_pd_lines = array(
					'I began my journey with CCS in May 2023 as a Senior Support Worker and have over 20 years of experience in the care sector.',
					'I have completed NVQs to progress from Care Worker to Senior level, and am now Care Manager.',
				);
				$ccs_pd_bio   = wp_strip_all_tags( $ccs_pd_member['bio'] );
				if ( false !== strpos( $ccs_pd_bio, $ccs_pd_lines[0] ) && false !== strpos( $ccs_pd_bio, $ccs_pd_lines[1] ) ) {
					$ccs_pd_story = implode( "\n\n", $ccs_pd_lines );
					$ccs_pd_name  = $ccs_pd_member['name'];
					$ccs_pd_role  = $ccs_pd_member['role'];
					$ccs_pd_link  = function_exists( 'ccs_team_profile_url' ) ? ccs_team_profile_url( $ccs_pd_member ) : '';
					if ( ! empty( $ccs_pd_member['photo'] ) && file_exists( get_template_directory() . '/assets/images/team/' . $ccs_pd_member['photo'] ) ) {
						$ccs_pd_photo = get_template_directory_uri() . '/assets/images/team/' . $ccs_pd_member['photo'];
					}
				}
			}
		}
		?>
		<?php if ( '' !== $ccs_pd_story && '' !== $ccs_pd_name ) : ?>
			<section class="ccs-section ccs-section--tint careers-story" aria-labelledby="careers-story-heading">
				<div class="container container--lg">
					<figure class="careers-story__quote">
						<h2 id="careers-story-heading" class="careers-story__label"><?php esc_html_e( 'My journey', 'ccs-wp-theme' ); ?></h2>
						<blockquote><?php echo wp_kses_post( wpautop( esc_html( $ccs_pd_story ) ) ); ?></blockquote>
						<figcaption>
							<?php if ( '' !== $ccs_pd_photo ) : ?>
								<img class="careers-story__photo" src="<?php echo esc_url( $ccs_pd_photo ); ?>" alt="" width="512" height="512" loading="lazy" decoding="async">
							<?php endif; ?>
							<strong><?php echo esc_html( $ccs_pd_name ); ?></strong>
							<?php if ( '' !== $ccs_pd_role ) : ?>
								<span><?php echo esc_html( $ccs_pd_role ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $ccs_pd_link ) : ?>
								<a class="ccs-text-link" href="<?php echo esc_url( $ccs_pd_link ); ?>">
									<?php
									/* translators: %s: a first name. */
									echo esc_html( sprintf( __( 'Read more about %s', 'ccs-wp-theme' ), strtok( $ccs_pd_name, ' ' ) ) );
									?>
									<span aria-hidden="true">&rarr;</span>
								</a>
							<?php endif; ?>
						</figcaption>
					</figure>
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
