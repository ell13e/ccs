<?php
/**
 * Template Name: Who You'll Meet
 *
 * Team page. Per reference/original-build-brief.md this is the single biggest
 * trust differentiator on a care site: families deciding whether to let a
 * stranger into a relative's home look for real names and real faces, and the
 * sites that show them convert best.
 *
 * Built from the section system: page hero, a split intro (section head left,
 * the page's own editor copy right), the full team in the same .team-tile the
 * homepage uses, a careers card, then the shared CTA band.
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

$ccs_team     = function_exists( 'ccs_team_members' ) ? ccs_team_members() : array();
$ccs_careers  = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'careers' ) : home_url( '/careers/' );
?>

<main id="main" class="site-main site-main--team" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php if ( trim( get_the_content() ) !== '' ) : ?>
			<section class="ccs-section team-intro" aria-labelledby="team-intro-heading">
				<div class="container container--lg">
					<div class="ccs-split">
						<header class="ccs-head">
							<h2 id="team-intro-heading" class="ccs-title">
								<?php
								echo ccs_copy_accent( 'team_page_intro_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent().
								?>
							</h2>
						</header>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
							<div class="page-body page-body--flush entry-content">
								<?php the_content(); ?>
							</div>
						</article>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>

	<?php if ( ! empty( $ccs_team ) ) : ?>
		<section class="ccs-section ccs-section--tint team-people" aria-labelledby="team-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<?php // The "Management and office" eyebrow went (Nov 2026 eyebrow review): the paragraph above already says who is shown. ?>
					<h2 id="team-heading" class="ccs-title">
						<?php
						echo esc_html( ccs_copy( 'team_page_title' ) );
						?>
					</h2>
				</header>

				<?php
				// Shared tiles; each links to the person's profile (Nov 2026).
				get_template_part( 'template-parts/team-tiles', null, array( 'members' => $ccs_team ) );
				?>
			</div>
		</section>
	<?php endif; ?>

	<section class="ccs-section ccs-section--flush-bottom team-join" aria-labelledby="team-join-heading">
		<div class="container container--lg">
			<div class="ccs-strip">
				<span class="ccs-badge"><?php echo ccs_icon( 'people' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<div class="ccs-strip__text">
					<h2 id="team-join-heading" class="ccs-strip__title"><?php echo esc_html( ccs_copy( 'team_page_strip_title' ) ); ?></h2>
					<p class="ccs-strip__lede"><?php echo esc_html( ccs_copy( 'team_page_strip_text' ) ); ?></p>
				</div>
				<a class="btn btn-quiet ccs-strip__action" href="<?php echo esc_url( $ccs_careers ); ?>"><?php echo esc_html( ccs_copy( 'team_page_strip_button' ) ); ?></a>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
