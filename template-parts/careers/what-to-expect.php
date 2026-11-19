<?php
/**
 * Careers hub: "Here's what you can expect", four points on the tint band,
 * icon and text with no box (.feature-grid--plain): none of them is clickable
 * or a separate object, so a card would only add weight (CCS/CLAUDE.md rule 1).
 *
 * Copy carried over from the live site's careers page (Ellie's own wording,
 * kept close to verbatim rather than rewritten).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	array(
		'photo' => 'careers/trainer-with-learner.webp',
		'pos'   => '50% 12%',
		'alt'   => __( 'A trainer helping a new member of staff at a laptop', 'ccs-wp-theme' ),
		'title' => __( 'Support from day one', 'ccs-wp-theme' ),
		'body'  => __( "Practical training, a team you can rely on, and a manager who means it when they ask how you're doing.", 'ccs-wp-theme' ),
	),
	array(
		'photo' => 'site-photos/WhatsApp-Image-2025-05-23-at-13.39.58.webp',
		'pos'   => '50% 70%',
		'alt'   => __( 'A person in a wheelchair looking out over the beach and pier on a day out', 'ccs-wp-theme' ),
		'title' => __( 'Hours that fit your life', 'ccs-wp-theme' ),
		'body'  => __( 'School hours, overnight shifts or steady full-time work. We build the rota around you.', 'ccs-wp-theme' ),
	),
	array(
		'photo' => 'site-photos/WhatsApp-Image-2021-02-10-at-15.39.32.webp',
		'pos'   => '35% 40%',
		'alt'   => __( 'Members of the team talking through care notes together', 'ccs-wp-theme' ),
		'title' => __( "You're listened to", 'ccs-wp-theme' ),
		'body'  => __( "The people who deliver care often see problems first. If something's not right, we want to know.", 'ccs-wp-theme' ),
	),
	array(
		'photo' => 'careers/learner-notes.webp',
		'pos'   => '60% 20%',
		'alt'   => __( 'A learner taking notes during a practical training session', 'ccs-wp-theme' ),
		'title' => __( 'Room to grow', 'ccs-wp-theme' ),
		'body'  => __( 'After the Care Certificate, you can take health and social care qualifications or train in a new area of care.', 'ccs-wp-theme' ),
	),
);?>
<section class="ccs-section ccs-section--tint careers-expect" aria-labelledby="careers-expect-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center">
			<h2 id="careers-expect-heading" class="ccs-title">
				<?php
				/* translators: %s: the emphasised word, drawn with a brush underline. */
				printf( esc_html__( "Here's what you can %s", 'ccs-wp-theme' ), ccs_accent( __( 'expect', 'ccs-wp-theme' ) ) );
				?>
			</h2>
		</header>
		<ul class="feature-grid feature-grid--photo" role="list">
			<?php foreach ( $items as $item ) : ?>
				<?php // A photo stacked on each card instead of an icon badge (Nov 2026, user): real people say more about the job than four pictograms. ?>
				<li class="feature-card feature-card--photo" data-ccs-exception="rule 1: cards at the client's request (Nov 2026); as loose icon-and-text points this section looked unfinished">
					<?php
					ccs_section_photo(
						$item['photo'],
						array(
							'alt'   => $item['alt'],
							'sizes' => '(min-width: 700px) 240px, 92vw',
							'class' => 'feature-card__photo',
							'position' => $item['pos'],
						)
					);
					?>
					<div class="feature-card__body">
						<h3 class="feature-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="feature-card__text"><?php echo esc_html( $item['body'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
