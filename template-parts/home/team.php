<?php
/**
 * Homepage "meet the people" strip.
 *
 * Both the ElderlyCare X template and Harmony Care give their team a section on
 * the homepage, and it is the strongest human signal either one has. It is a
 * stronger one here than for most providers, because it is literally the brand
 * promise: "You'll see the same faces at every visit" (hero) and "the same small
 * team comes back each visit" (why-choose-us). The page makes that claim in text
 * four times and, until this section, showed nobody.
 *
 * page-templates/template-team.php states the principle in its own docblock:
 * what a family arranging care needs is to be able to "look for real names and
 * real faces" before a stranger comes into a relative's home. The roster, order
 * and photographs come from ccs_team_members() (inc/team.php), the single source
 * the Team page also reads, so a change there updates both. These are the same
 * people and photographs already published on that page, not new material.
 *
 * Four, not eight: the homepage shows the people a family is most likely to deal
 * with first (the managers), and hands the rest to the Team page. Members with no
 * photograph are skipped here rather than shown as initials, because a row of
 * faces with one grey tile in it undoes the point of the row.
 *
 * Photographs are cropped by the browser (object-fit) and nothing else: no
 * filter, no tone adjustment, so the set stays exactly as supplied.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_team_all = function_exists( 'ccs_team_members' ) ? ccs_team_members() : array();
$ccs_team_dir = get_template_directory() . '/assets/images/team/';
$ccs_team_uri = get_template_directory_uri() . '/assets/images/team/';

$ccs_team_show = array();
foreach ( $ccs_team_all as $ccs_member ) {
	if ( ! empty( $ccs_member['photo'] ) && file_exists( $ccs_team_dir . $ccs_member['photo'] ) ) {
		$ccs_team_show[] = $ccs_member;
	}
	if ( count( $ccs_team_show ) >= 4 ) {
		break;
	}
}

if ( count( $ccs_team_show ) < 3 ) {
	return;
}

$ccs_team_url = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'who-youll-meet' ) : home_url( '/who-youll-meet/' );
?>

<section class="home-team" aria-labelledby="home-team-heading">
	<div class="home-team__inner container container--lg">

		<header class="ccs-head ccs-head--center">
			<h2 id="home-team-heading" class="ccs-title">
				<?php
				echo esc_html( ccs_copy( 'team_title' ) );
				?>
			</h2>
			<p class="ccs-lede">
				<?php echo esc_html( ccs_copy( 'team_lede' ) ); ?>
			</p>
		</header>

		<ul class="ccs-team-grid" role="list">
			<?php foreach ( $ccs_team_show as $ccs_member ) : ?>
				<li class="home-team__item">
					<?php
					/*
					 * alt="" because the caption directly beneath names the person; a
					 * second "Victoria Walker" from the image would be read out twice.
					 */
					?>
					<figure class="team-tile">
						<div class="team-tile__photo">
							<img
								src="<?php echo esc_url( $ccs_team_uri . $ccs_member['photo'] ); ?>"
								alt=""
								width="512"
								height="512"
								loading="lazy"
								decoding="async"
							>
						</div>
						<figcaption class="team-tile__tag">
							<span class="team-tile__name"><?php echo esc_html( $ccs_member['name'] ); ?></span>
							<span class="team-tile__role"><?php echo esc_html( $ccs_member['role'] ); ?></span>
						</figcaption>
					</figure>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="ccs-section-foot ccs-actions ccs-actions--center">
			<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_team_url ); ?>">
				<?php echo esc_html( ccs_copy( 'team_button' ) ); ?>
			</a>
		</div>

	</div>
</section>
