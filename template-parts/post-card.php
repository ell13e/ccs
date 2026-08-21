<?php
/**
 * Post card for listings (blog index, archives, search).
 *
 * Uses an <h2>, unlike template-parts/content.php which emits an <h1> for a
 * single post — reusing that on an archive produced one <h1> per post.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'medium_large', array( 'class' => 'post-card__img', 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="post-card__body">
		<p class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>
		<h2 class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="post-card__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?>
		</p>
		<span class="post-card__link" aria-hidden="true">
			<?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span>&rarr;</span>
		</span>
	</div>
</li>
