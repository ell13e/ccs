<?php
/**
 * One story in the news list: topic and date, title, two lines of summary,
 * and the photo on the right.
 *
 * A row, not a card (CCS/CLAUDE.md rule 1): the stories are one list, so they
 * are grouped by hairlines, not boxed one by one. The whole row is clickable
 * through the title's ::after; the title is the only tab stop. No "Read more"
 * button: ten identical buttons said nothing the title doesn't.
 *
 * A post with no featured image gets no thumbnail and no stand-in mark; its
 * text takes the full width. A placeholder would pretend there is a photo.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_category  = function_exists( 'ccs_primary_category' ) ? ccs_primary_category() : null;
$ccs_has_image = has_post_thumbnail();
$ccs_first     = ! empty( $args['first'] );
?>

<li <?php post_class( $ccs_has_image ? 'post-row' : 'post-row post-row--no-image' ); ?>>
	<div class="post-row__body">
		<p class="post-row__meta">
			<?php if ( $ccs_category ) : ?>
				<span class="post-row__category"><?php echo esc_html( $ccs_category->name ); ?></span>
			<?php endif; ?>
			<?php // UK order (25 November 2026) whatever the site's date setting. ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
		</p>
		<h2 class="post-row__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="post-row__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
	</div>

	<?php if ( $ccs_has_image ) : ?>
		<div class="post-row__media" aria-hidden="true">
			<?php
			the_post_thumbnail(
				'medium_large',
				array(
					'class'   => 'post-row__img',
					'alt'     => '',
					'loading' => $ccs_first ? 'eager' : 'lazy',
					'sizes'   => '(min-width: 768px) 240px, 112px',
				)
			);
			?>
		</div>
	<?php endif; ?>
</li>
