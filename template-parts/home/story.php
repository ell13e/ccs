<?php
/**
 * Homepage: the story video (the registered manager telling the company's
 * story), in one wide frame. Click-to-load: nothing from YouTube is requested
 * until someone presses play.
 *
 * The heading is the homepage's own (Nov 2026, client review: "Where it
 * started", shared with the About page, did not say what this section is).
 * It says there is a video to watch; the About page keeps its heading.
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
				<?php echo esc_html( ccs_copy( 'story_title' ) ); ?>
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
