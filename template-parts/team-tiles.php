<?php
/**
 * Team tiles (.ccs-team-grid of .team-tile), shared by the homepage, the
 * team page and each profile page, so a person looks the same everywhere.
 * Each tile links to the person's profile when they have one (Nov 2026):
 * the name is the link, stretched over the tile, so the whole tile is one
 * target and the name is read once.
 *
 * Args: 'members' (array from ccs_team_members()), 'item_class' (optional
 * class for each <li>).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_tt_members = isset( $args['members'] ) && is_array( $args['members'] ) ? $args['members'] : array();
$ccs_tt_class   = isset( $args['item_class'] ) ? (string) $args['item_class'] : '';
$ccs_tt_dir     = get_template_directory() . '/assets/images/team/';
$ccs_tt_uri     = get_template_directory_uri() . '/assets/images/team/';

if ( empty( $ccs_tt_members ) ) {
	return;
}
?>
<ul class="ccs-team-grid" role="list">
	<?php foreach ( $ccs_tt_members as $ccs_tt ) : ?>
		<?php
		$ccs_tt_photo = ! empty( $ccs_tt['photo'] ) && file_exists( $ccs_tt_dir . $ccs_tt['photo'] );
		$ccs_tt_url   = function_exists( 'ccs_team_profile_url' ) ? ccs_team_profile_url( $ccs_tt ) : '';
		?>
		<li<?php echo $ccs_tt_class ? ' class="' . esc_attr( $ccs_tt_class ) . '"' : ''; ?>>
			<figure class="team-tile<?php echo $ccs_tt_url ? ' team-tile--link' : ''; ?>">
				<div class="team-tile__photo">
					<?php if ( $ccs_tt_photo ) : ?>
						<?php
						/*
						 * alt="" because the name is the caption directly below; a
						 * second "Victoria Walker" from the image would be read twice.
						 */
						?>
						<img src="<?php echo esc_url( $ccs_tt_uri . $ccs_tt['photo'] ); ?>" alt="" width="512" height="512" loading="lazy" decoding="async">
					<?php else : ?>
						<?php
						$ccs_tt_initials = '';
						foreach ( preg_split( '/[\s-]+/', $ccs_tt['name'] ) as $ccs_tt_part ) {
							if ( '' !== $ccs_tt_part ) {
								$ccs_tt_initials .= mb_substr( $ccs_tt_part, 0, 1 );
							}
						}
						?>
						<span class="team-tile__initials" aria-hidden="true"><?php echo esc_html( mb_strtoupper( mb_substr( $ccs_tt_initials, 0, 2 ) ) ); ?></span>
					<?php endif; ?>
				</div>
				<figcaption class="team-tile__tag">
					<?php if ( $ccs_tt_url ) : ?>
						<a class="team-tile__name team-tile__link" href="<?php echo esc_url( $ccs_tt_url ); ?>"><?php echo esc_html( $ccs_tt['name'] ); ?></a>
					<?php else : ?>
						<span class="team-tile__name"><?php echo esc_html( $ccs_tt['name'] ); ?></span>
					<?php endif; ?>
					<span class="team-tile__role"><?php echo esc_html( $ccs_tt['role'] ); ?></span>
				</figcaption>
			</figure>
		</li>
	<?php endforeach; ?>
</ul>
