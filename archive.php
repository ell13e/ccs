<?php
/**
 * Archives (category, tag, date, and CPT archives without a more specific template).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--archive" role="main">

	<header class="page-hero">
		<div class="page-hero__inner container container--lg">
			<?php get_template_part( 'template-parts/breadcrumb' ); ?>
			<h1 class="page-hero__title"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
			<?php
			$ccs_archive_desc = get_the_archive_description();
			if ( $ccs_archive_desc ) :
				?>
				<div class="page-hero__intro"><?php echo wp_kses_post( $ccs_archive_desc ); ?></div>
			<?php endif; ?>
		</div>
	</header>

	<div class="post-list-section">
		<div class="container container--lg">
			<?php if ( have_posts() ) : ?>
				<ul class="post-list" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/post-card' );
					endwhile;
					?>
				</ul>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( '&larr; Newer', 'ccs-wp-theme' ),
						'next_text' => __( 'Older &rarr;', 'ccs-wp-theme' ),
					)
				);
				?>
			<?php else : ?>
				<p class="post-list__empty"><?php esc_html_e( 'Nothing here just yet.', 'ccs-wp-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
