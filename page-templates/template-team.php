<?php
/**
 * Template Name: Who You'll Meet
 *
 * Team page. Per reference/original-build-brief.md this is the single biggest
 * trust differentiator on a care site: families deciding whether to let a
 * stranger into a relative's home look for real names and real faces, and the
 * sites that show them convert best.
 *
 * Roster is defined in ccs_team_members() (inc/team.php) so it stays in one
 * place rather than being buried in page content.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ccs_team = function_exists( 'ccs_team_members' ) ? ccs_team_members() : array();
?>

<main id="main" class="site-main site-main--team" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php if ( trim( get_the_content() ) !== '' ) : ?>
				<div class="container container--lg">
					<div class="page-body entry-content">
						<?php the_content(); ?>
					</div>
				</div>
			<?php endif; ?>
		</article>
		<?php
	endwhile;
	?>

	<?php if ( ! empty( $ccs_team ) ) : ?>
		<section class="team-section" aria-labelledby="team-heading">
			<div class="container container--lg">
				<h2 id="team-heading" class="team-section__heading">
					<?php esc_html_e( 'The team', 'ccs-wp-theme' ); ?>
				</h2>
				<ul class="team-grid" role="list">
					<?php foreach ( $ccs_team as $member ) : ?>
						<li class="team-card">
							<div class="team-card__media">
								<?php if ( ! empty( $member['photo'] ) && file_exists( get_template_directory() . '/assets/images/team/' . $member['photo'] ) ) : ?>
									<img
										class="team-card__img"
										src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team/' . $member['photo'] ); ?>"
										alt="<?php echo esc_attr( $member['name'] ); ?>"
										loading="lazy"
										decoding="async"
									>
								<?php else : ?>
									<?php
									/*
									 * Initials placeholder rather than a blank box. The brief is
									 * explicit: never leave an empty image slot on this page,
									 * because it reads as anonymity — the exact problem the page
									 * exists to solve.
									 */
									$parts    = preg_split( '/[\s-]+/', $member['name'] );
									$initials = '';
									foreach ( $parts as $part ) {
										if ( $part !== '' ) {
											$initials .= mb_substr( $part, 0, 1 );
										}
									}
									?>
									<span class="team-card__initials" aria-hidden="true"><?php echo esc_html( mb_strtoupper( mb_substr( $initials, 0, 2 ) ) ); ?></span>
								<?php endif; ?>
							</div>
							<div class="team-card__body">
								<h3 class="team-card__name"><?php echo esc_html( $member['name'] ); ?></h3>
								<p class="team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
