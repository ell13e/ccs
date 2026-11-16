<?php
/**
 * Template Name: Team member
 *
 * One person's profile (Nov 2026; client, after the ElderlyCare X team
 * member page: "would be good to have something like this for each of the
 * team members"). Their photo large beside their name, role and a line
 * about them; then their own words, if the editor has any (with Victoria's
 * story video under hers); the rest of the team; the shared CTA band.
 *
 * The person comes from the roster (inc/team.php) through the page's
 * ccs_team_member field. The page's editor holds their own words; nothing
 * is written for them, so until it is filled in that section is left out
 * and the page is kept out of search (ccs_team_profile_robots()).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_tm_slug   = (string) get_post_meta( get_the_ID(), 'ccs_team_member', true );
$ccs_tm        = function_exists( 'ccs_team_member_by_slug' ) ? ccs_team_member_by_slug( $ccs_tm_slug ) : null;
$ccs_tm_name   = $ccs_tm ? $ccs_tm['name'] : get_the_title();
$ccs_tm_first  = strtok( $ccs_tm_name, ' ' );
$ccs_tm_role   = $ccs_tm ? $ccs_tm['role'] : '';
$ccs_tm_intro  = $ccs_tm && ! empty( $ccs_tm['intro'] ) ? $ccs_tm['intro'] : '';
$ccs_tm_video  = $ccs_tm && ! empty( $ccs_tm['video'] ) ? $ccs_tm['video'] : '';
$ccs_tm_dir    = get_template_directory() . '/assets/images/team/';
$ccs_tm_uri    = get_template_directory_uri() . '/assets/images/team/';
$ccs_tm_photo  = $ccs_tm && ! empty( $ccs_tm['photo'] ) && file_exists( $ccs_tm_dir . $ccs_tm['photo'] ) ? $ccs_tm_uri . $ccs_tm['photo'] : '';
$ccs_tm_bio    = function_exists( 'ccs_team_profile_has_bio' ) && ccs_team_profile_has_bio( get_the_ID() );
$ccs_contact   = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
$ccs_team_url  = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'who-youll-meet' ) : home_url( '/who-youll-meet/' );
$ccs_contact_i = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );

$ccs_tm_others = array();
foreach ( function_exists( 'ccs_team_members' ) ? ccs_team_members() : array() as $ccs_member ) {
	if ( $ccs_member['slug'] !== $ccs_tm_slug ) {
		$ccs_tm_others[] = $ccs_member;
	}
}

/*
 * Grounds alternate after the tint hero (CLAUDE.md rule 4), whichever of the
 * optional sections a page has.
 */
$ccs_tm_tint = false;
$ccs_tm_band = static function () use ( &$ccs_tm_tint ) {
	$ccs_tm_tint = ! $ccs_tm_tint;
	return $ccs_tm_tint ? '' : ' ccs-section--tint';
};
?>

<main id="main" class="site-main site-main--profile" role="main">

	<header class="page-hero profile-hero">
		<div class="container container--lg profile-hero__grid">
			<?php if ( $ccs_tm_photo ) : ?>
				<div class="profile-hero__photo">
					<img src="<?php echo esc_url( $ccs_tm_photo ); ?>" alt="<?php echo esc_attr( $ccs_tm_name ); ?>" width="512" height="512" fetchpriority="high" decoding="async">
				</div>
			<?php endif; ?>
			<div class="profile-hero__text">
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<?php if ( $ccs_tm_role ) : ?>
					<p class="profile-hero__role"><?php echo esc_html( $ccs_tm_role ); ?></p>
				<?php endif; ?>
				<h1 class="page-hero__title profile-hero__name"><?php echo esc_html( $ccs_tm_name ); ?></h1>
				<?php if ( $ccs_tm_intro ) : ?>
					<p class="page-hero__intro profile-hero__intro"><?php echo esc_html( $ccs_tm_intro ); ?></p>
				<?php endif; ?>
				<div class="ccs-actions profile-hero__actions">
					<a class="btn btn-primary" href="<?php echo esc_url( $ccs_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
					<?php if ( ! empty( $ccs_contact_i['phone_link'] ) ) : ?>
						<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_contact_i['phone_link'] ); ?>">
							<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
							<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $ccs_contact_i['phone'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>

	<?php if ( $ccs_tm_bio ) : ?>
		<section class="ccs-section<?php echo esc_attr( $ccs_tm_band() ); ?> profile-about" aria-labelledby="profile-about-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="profile-about-heading" class="ccs-title">
							<?php
							/* translators: %s: first name. */
							printf( esc_html__( 'About %s', 'ccs-wp-theme' ), ccs_accent( $ccs_tm_first ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ccs_accent() escapes.
							?>
						</h2>
					</header>
					<div class="page-body page-body--flush entry-content">
						<?php
						while ( have_posts() ) :
							the_post();
							the_content();
						endwhile;
						?>
						<?php if ( $ccs_tm_video ) : ?>
							<div class="profile-about__video">
								<?php
								get_template_part(
									'template-parts/video-embed',
									null,
									array(
										'video_id' => $ccs_tm_video,
										/* translators: 1: name, 2: role. */
										'title'    => sprintf( __( 'Our story, told by %1$s, %2$s', 'ccs-wp-theme' ), $ccs_tm_name, $ccs_tm_role ),
									)
								);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// The video has its own section only when there is no "About" for it to sit in.
	if ( $ccs_tm_video && ! $ccs_tm_bio ) :
		?>
		<section class="ccs-section<?php echo esc_attr( $ccs_tm_band() ); ?> profile-video" aria-labelledby="profile-video-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="profile-video-heading" class="ccs-title">
						<?php
						/* translators: %s: first name. */
						echo esc_html( sprintf( __( 'Our story, told by %s', 'ccs-wp-theme' ), $ccs_tm_first ) );
						?>
					</h2>
				</header>
				<div class="profile-video__media">
					<?php
					get_template_part(
						'template-parts/video-embed',
						null,
						array(
							'video_id' => $ccs_tm_video,
							/* translators: 1: name, 2: role. */
							'title'    => sprintf( __( 'Our story, told by %1$s, %2$s', 'ccs-wp-theme' ), $ccs_tm_name, $ccs_tm_role ),
						)
					);
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	if ( $ccs_tm_others ) :
		/*
		 * The last section before the CTA band: on white, it drops its bottom
		 * padding, as the team page does, so two white grounds don't leave a
		 * wide empty gap between them.
		 */
		$ccs_tm_last = $ccs_tm_band();
		$ccs_tm_last = '' === $ccs_tm_last ? ' ccs-section--flush-bottom' : $ccs_tm_last;
		?>
		<section class="ccs-section<?php echo esc_attr( $ccs_tm_last ); ?> profile-team" aria-labelledby="profile-team-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--split">
					<h2 id="profile-team-heading" class="ccs-title"><?php esc_html_e( 'Others you’ll meet', 'ccs-wp-theme' ); ?></h2>
					<a class="ccs-text-link" href="<?php echo esc_url( $ccs_team_url ); ?>"><?php esc_html_e( 'The whole team', 'ccs-wp-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
				</header>
				<?php get_template_part( 'template-parts/team-tiles', null, array( 'members' => $ccs_tm_others ) ); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
