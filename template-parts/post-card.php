<?php
/**
 * Post card for listings (blog index, archives, search).
 *
 * Uses an <h2>, unlike template-parts/content.php which emits an <h1> for a
 * single post — reusing that on an archive produced one <h1> per post.
 *
 * Accepts an optional 'featured' arg (see home.php): the lead card on the blog
 * index runs wider and shows a longer excerpt, but is the same card otherwise,
 * so the two never drift apart visually.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_featured = ! empty( $args['featured'] );
$ccs_category = function_exists( 'ccs_primary_category' ) ? ccs_primary_category() : null;
$ccs_classes  = $ccs_featured ? 'post-card post-card--featured' : 'post-card';
?>

<li <?php post_class( $ccs_classes ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			the_post_thumbnail(
				$ccs_featured ? 'large' : 'medium_large',
				array(
					'class'   => 'post-card__img',
					'loading' => $ccs_featured ? 'eager' : 'lazy',
				)
			);
			?>
		</a>
	<?php else : ?>
		<?php
		/*
		 * A card with no image is not a card with a hole in it. Without this the
		 * grid mixed image-topped cards with text-only ones of a different
		 * height, which read as a broken layout rather than a deliberate one.
		 * The mark is decorative and hidden from assistive tech: the title
		 * beside it already says what the post is.
		 */
		?>
		<a class="post-card__media post-card__media--placeholder" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<span class="post-card__placeholder-mark" aria-hidden="true">&infin;</span>
		</a>
	<?php endif; ?>

	<div class="post-card__body">
		<p class="post-card__meta">
			<?php if ( $ccs_category ) : ?>
				<span class="post-card__category"><?php echo esc_html( $ccs_category->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( function_exists( 'ccs_reading_time_label' ) ) : ?>
				<span class="post-card__reading-time"><?php echo esc_html( ccs_reading_time_label() ); ?></span>
			<?php endif; ?>
		</p>
		<h2 class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="post-card__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), $ccs_featured ? 46 : 28 ) ); ?>
		</p>
		<span class="post-card__link" aria-hidden="true">
			<?php esc_html_e( 'Read more', 'ccs-wp-theme' ); ?> <span>&rarr;</span>
		</span>
	</div>
</li>
