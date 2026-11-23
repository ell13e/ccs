<?php
/**
 * Homepage "meet the people" strip: the brand promise ("the same faces at
 * every visit") shown, not just stated.
 *
 * The roster, order and photographs come from ccs_team_members() (inc/team.php),
 * the single source the Team page also reads, so a change there updates both.
 *
 * Four, not eight: the people a family is most likely to deal with first (the
 * managers); the rest are on the Team page. Members with no photograph are
 * skipped here rather than shown as initials.
 *
 * Photographs are cropped by the browser (object-fit) and nothing else: no
 * filter, no tone adjustment.
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
		</header>

		<?php
		// Shared tiles; each links to the person's profile (Nov 2026).
		get_template_part( 'template-parts/team-tiles', null, array( 'members' => $ccs_team_show, 'item_class' => 'home-team__item' ) );
		?>

		<div class="ccs-section-foot ccs-actions ccs-actions--center">
			<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_team_url ); ?>">
				<?php echo esc_html( ccs_copy( 'team_button' ) ); ?>
			</a>
		</div>

	</div>
</section>
