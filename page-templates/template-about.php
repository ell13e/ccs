<?php
/**
 * Template Name: About
 *
 * Photo hero, then the page's own copy in a split intro, the people (the same
 * "Meet the people" band as the homepage, linking on to Who you'll meet), and
 * Victoria's story video beside its section head. Built from the section
 * system; no About-only components.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--about" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php
		/*
		 * Intro beside one real photo (Nov 2026). The title-left,
		 * paragraph-right split it replaces was rejected by the user on the CQC
		 * page ("don't use this"), and left this section with no image.
		 */
		?>
		<section class="ccs-section about-intro" aria-labelledby="about-intro-heading">
			<div class="container container--lg">
				<div class="ccs-media-split">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content about-intro__text' ); ?>>
						<h2 id="about-intro-heading" class="ccs-title">
							<?php
							echo ccs_copy_accent( 'about_intro_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
							?>
						</h2>
						<div class="page-body page-body--flush entry-content">
							<?php the_content(); ?>
						</div>
					</article>
					<?php // The drawing and its note sit under the photo, so the right side carries some of the weight (client: "too left side heavy", with the drawing, heading, two paragraphs and link all at the left). The note's words are the client's own. ?>
					<div class="about-intro__side">
						<?php
						ccs_section_photo(
							'site-photos/Untitled-1.webp',
							array(
								'alt'   => __( 'Two women smiling over tea and cake, one wearing a hat decorated with ribbons', 'ccs-wp-theme' ),
								'sizes' => '(min-width: 900px) 520px, 92vw',
							)
						);
						?>
						<?php echo ccs_doodle_note( 'heart-hand', __( "your preference,\nour priority", 'ccs-wp-theme' ), 'about-intro__note' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle_note(). ?>
					</div>
				</div>
			</div>
		</section>

		<?php
		/*
		 * The company journey (Nov 2026, user: "an interesting about us page
		 * for our company journey"). Milestones are from the staff induction
		 * deck's "Our story so far", reworded for families and editable in
		 * Customize > Page wording > About page: one line per milestone, as
		 * Year | Heading | Text.
		 *
		 * Drawn as a path: a ribbon winds down the middle (the brand mark is a
		 * loop of ribbon), milestones sit either side of it with the year set
		 * large, and the ribbon fills in plum as the page scrolls
		 * (assets/js/interactive.js; fully drawn without it). A first version
		 * reused the stepper and the user asked for something more creative.
		 *
		 * Three milestones carry a photo, each from the company it is about:
		 * the founder, the Training Academy's classroom, Restwell's garden.
		 * Left out on purpose: the deck's "a decade of CQC-rated Good care"
		 * (the first rating was 2017).
		 */
		$ccs_journey_photos = array(
			// Third value true: a round portrait rather than a landscape frame.
			'2016' => array( 'site-photos/ccs-victoria-walker-registered-manager.webp', __( 'Victoria Walker, who founded Continuity of Care Services', 'ccs-wp-theme' ), true ),
			// Not the classroom photo: that one leads the Training Academy feature further down.
			'2020' => array( 'journey/cta-trainer.webp', __( 'A Continuity Training Academy trainer presenting beside a flipchart', 'ccs-wp-theme' ) ),
			'2026' => array( 'journey/restwell-garden.webp', __( 'The garden and patio at Restwell Retreats in Whitstable', 'ccs-wp-theme' ) ),
		);
		$ccs_journey        = array();
		foreach ( preg_split( '/\r\n|\r|\n/', ccs_copy( 'about_journey' ) ) as $ccs_journey_line ) {
			$ccs_journey_bits = array_map( 'trim', explode( '|', $ccs_journey_line, 3 ) );
			if ( 3 === count( $ccs_journey_bits ) && '' !== $ccs_journey_bits[0] && '' !== $ccs_journey_bits[2] ) {
				$ccs_journey[] = $ccs_journey_bits;
			}
		}
		?>
		<?php if ( ! empty( $ccs_journey ) ) : ?>
			<?php
			/*
			 * Second pass (user: "so much better but still not polished and
			 * professional enough ... more reactive"). What changed, after looking
			 * at scroll-driven history components: the heading and one very large
			 * year sit in a sticky column and the year changes as you scroll;
			 * every milestone is left-aligned in one column (the zigzag put
			 * right-aligned text on one side); a milestone is quiet until
			 * reached, then its mark fills; marks sit on the ribbon's curve; the
			 * founder's portrait is a round portrait, not a cropped landscape;
			 * and the path ends at "Today".
			 */
			$ccs_journey_first = $ccs_journey[0][0];
			$ccs_journey_last  = $ccs_journey[ count( $ccs_journey ) - 1 ][0];
			?>
			<section class="ccs-section ccs-section--tint about-journey" aria-labelledby="about-journey-heading">
				<div class="container container--lg">
					<div class="journey-layout" data-ccs-journey>
						<header class="journey-aside">
							<h2 id="about-journey-heading" class="ccs-title"><?php echo ccs_copy_accent( 'about_journey_title' ); ?></h2>
							<p class="journey-aside__range"><?php echo esc_html( $ccs_journey_first ); ?> <span aria-hidden="true">&rarr;</span><span class="screen-reader-text"><?php esc_html_e( 'to', 'ccs-wp-theme' ); ?></span> <?php echo esc_html( $ccs_journey_last ); ?></p>
							<p class="journey-aside__year" aria-hidden="true" data-ccs-journey-year><?php echo esc_html( $ccs_journey_first ); ?></p>
						</header>
						<ol class="journey" role="list">
							<?php foreach ( $ccs_journey as $ccs_journey_item ) : ?>
								<?php $ccs_journey_photo = isset( $ccs_journey_photos[ $ccs_journey_item[0] ] ) ? $ccs_journey_photos[ $ccs_journey_item[0] ] : null; ?>
								<li class="journey__item" data-year="<?php echo esc_attr( $ccs_journey_item[0] ); ?>">
									<span class="journey__dot" aria-hidden="true"></span>
									<p class="journey__year"><?php echo esc_html( $ccs_journey_item[0] ); ?></p>
									<h3 class="journey__title"><?php echo esc_html( $ccs_journey_item[1] ); ?></h3>
									<p class="journey__text"><?php echo function_exists( 'ccs_link_mentions' ) ? ccs_link_mentions( esc_html( $ccs_journey_item[2] ), 1 ) : esc_html( $ccs_journey_item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then links to known pages added. ?></p>
									<?php
									if ( $ccs_journey_photo ) {
										ccs_section_photo(
											$ccs_journey_photo[0],
											array(
												'alt'   => $ccs_journey_photo[1],
												'sizes' => '(min-width: 900px) 460px, 80vw',
												'class' => 'journey__photo' . ( ! empty( $ccs_journey_photo[2] ) ? ' journey__photo--portrait' : ' ccs-photo--landscape' ),
											)
										);
									}
									?>
								</li>
							<?php endforeach; ?>
							<li class="journey__item journey__item--end" data-year="<?php echo esc_attr( $ccs_journey_last ); ?>">
								<span class="journey__dot" aria-hidden="true"></span>
								<p class="journey__end"><?php esc_html_e( 'Today', 'ccs-wp-theme' ); ?></p>
							</li>
						</ol>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="ccs-section about-story" aria-labelledby="about-story-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="about-story-heading" class="ccs-title">
							<?php
							echo ccs_copy_accent( 'about_story_title' );
							?>
						</h2>
						<p class="ccs-lede">
							<?php echo esc_html( ccs_copy( 'about_story_text' ) ); ?>
						</p>
					</header>
					<div class="about-story__media">
						<?php
						get_template_part(
							'template-parts/video-embed',
							null,
							array(
								'video_id' => '7RJ-pdNh65o',
								/*
								 * Was 'Our Story Starts Here — Victoria Walker, …' in single
								 * quotes, where PHP does not expand \u escapes: the accessible name
								 * of the play button literally read "backslash u 2014".
								 */
								'title'    => __( 'Our story, told by Victoria Walker, Registered Manager and Company Director', 'ccs-wp-theme' ),
							)
						);
						?>
					</div>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/home/team' ); ?>

		<?php
		/*
		 * The two sister companies (user: "explain and mention both"). Each is
		 * a photo beside the explanation, alternating sides, with a few facts
		 * and a link out. A first version was two white cards with a logo and
		 * a paragraph, which the user rejected. What each company is comes from
		 * its own website and the induction deck; the photos are its own.
		 * The three facts per company are the ones a family would care about
		 * (user: the first set, hoist weight and minutes to the sea, were "odd
		 * things to put there"). The Trustpilot score is from the Academy's own
		 * homepage and Restwell's Google rating was supplied by the user, both
		 * Nov 2026: they are editable because ratings move.
		 */
		$ccs_family = array(
			array(
				'name'  => __( 'Continuity Training Academy', 'ccs-wp-theme' ),
				'logo'  => 'continuity-training-academy.png',
				'text'  => ccs_copy( 'about_family_cta' ),
				'facts' => ccs_copy( 'about_family_cta_facts' ),
				'url'   => 'https://www.continuitytrainingacademy.co.uk/',
				'short' => __( 'the Training Academy', 'ccs-wp-theme' ),
				'photo' => 'journey/cta-training-room.webp',
				'alt'   => __( 'Learners around a table in the Continuity Training Academy classroom', 'ccs-wp-theme' ),
			),
			array(
				'name'  => __( 'Restwell Retreats', 'ccs-wp-theme' ),
				'logo'  => 'restwell.png',
				'text'  => ccs_copy( 'about_family_restwell' ),
				'facts' => ccs_copy( 'about_family_restwell_facts' ),
				'url'   => 'https://restwellretreats.co.uk/',
				'short' => __( 'Restwell Retreats', 'ccs-wp-theme' ),
				'photo' => 'journey/restwell-bungalow.webp',
				'alt'   => __( 'The Restwell Retreats bungalow in Whitstable, seen from its level driveway', 'ccs-wp-theme' ),
			),
		);
		?>
		<section class="ccs-section about-family" aria-labelledby="about-family-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="about-family-heading" class="ccs-title"><?php echo ccs_copy_accent( 'about_family_title' ); ?></h2>
				</header>
				<?php foreach ( $ccs_family as $ccs_family_i => $ccs_family_item ) : ?>
					<div class="ccs-media-split family-feature<?php echo 1 === $ccs_family_i % 2 ? ' ccs-media-split--flip' : ''; ?>">
						<div class="family-feature__text">
							<h3 class="family-feature__name"><?php echo esc_html( $ccs_family_item['name'] ); ?></h3>
							<p class="family-feature__lede"><?php echo esc_html( $ccs_family_item['text'] ); ?></p>
							<?php
							// Facts are "figure | label" pairs separated by semicolons, so a
							// figure can hold a comma (2,000+).
							$ccs_family_facts = array();
							foreach ( explode( ';', $ccs_family_item['facts'] ) as $ccs_family_fact ) {
								$ccs_family_bits = array_map( 'trim', explode( '|', $ccs_family_fact, 2 ) );
								if ( 2 === count( $ccs_family_bits ) && '' !== $ccs_family_bits[0] ) {
									$ccs_family_facts[] = $ccs_family_bits;
								}
							}
							?>
							<?php if ( ! empty( $ccs_family_facts ) ) : ?>
								<?php // Ticked lines, figure in bold. ?>
								<ul class="family-feature__facts" role="list">
									<?php foreach ( $ccs_family_facts as $ccs_family_fact ) : ?>
										<li><?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?><span><strong><?php echo esc_html( $ccs_family_fact[0] ); ?></strong> <?php echo esc_html( $ccs_family_fact[1] ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_family_item['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php
								/* translators: %s: a company's short name, such as Restwell Retreats. */
								echo esc_html( sprintf( __( 'Visit %s', 'ccs-wp-theme' ), $ccs_family_item['short'] ) );
								?>
								<span aria-hidden="true">&nearr;</span>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
							</a>
						</div>
						<div class="family-feature__media">
							<?php
							ccs_section_photo(
								$ccs_family_item['photo'],
								array(
									'alt'   => $ccs_family_item['alt'],
									'sizes' => '(min-width: 900px) 560px, 92vw',
									'class' => 'family-feature__photo',
								)
							);
							?>
							<?php if ( file_exists( get_template_directory() . '/assets/images/partners/' . $ccs_family_item['logo'] ) ) : ?>
								<?php // The company's own mark, on a white tab lapping the photo's corner. ?>
								<?php // Linked like every other logo; the button beside it is the keyboard's route, so this one is skipped in the tab order. ?>
								<a class="family-feature__badge" href="<?php echo esc_url( $ccs_family_item['url'] ); ?>" target="_blank" rel="noopener noreferrer" tabindex="-1">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/' . $ccs_family_item['logo'] ); ?>" alt="<?php echo esc_attr( $ccs_family_item['name'] ); ?>" width="323" height="90" loading="lazy" decoding="async">
									<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<?php
	endwhile;
	?>

	<?php
	// Moved from the homepage (calm pass, Nov 2026): "who we work with" belongs with "who we are".
	get_template_part( 'template-parts/home/partnerships' );
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
