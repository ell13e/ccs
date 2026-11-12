<?php
/**
 * Homepage: the story video.
 *
 * The one piece of moving, human material CCS has (the registered manager
 * telling the company's story) lived only on the About page. Here it gets a
 * full-width frame of its own: the largest image on the page after the hero,
 * and the page's main splash of brand colour, since the poster is the brand's
 * own plum.
 *
 * Layout borrowed from the benchmark (ElderlyCare X "facilities" section): a
 * centred head over a wide video that straddles two grounds, so the frame
 * overlaps the seam between this section and the cream services band below it.
 * Click-to-load: nothing from YouTube is requested until someone presses play.
 *
 * Heading and text are shared with the About page (Customize > Page wording >
 * About page), so the two never drift apart.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_story_video = apply_filters( 'ccs_story_video_id', '7RJ-pdNh65o' );
if ( ! $ccs_story_video ) {
	return;
}
?>

<section class="home-story" aria-labelledby="home-story-heading">
	<div class="container container--lg">

		<header class="ccs-head ccs-head--center">
			<h2 id="home-story-heading" class="ccs-title">
				<?php echo esc_html( ccs_copy( 'about_story_title' ) ); ?>
			</h2>
		</header>

		<div class="home-story__media">
			<?php
			get_template_part(
				'template-parts/video-embed',
				null,
				array(
					'video_id' => $ccs_story_video,
					'title'    => __( 'Our story, told by Victoria Walker, Registered Manager and Company Director', 'ccs-wp-theme' ),
				)
			);
			?>
		</div>

	</div>
</section>
