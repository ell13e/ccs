<?php
/**
 * News display helpers: reading time, category chips, related posts.
 *
 * Shared by home.php, archive.php, single.php and template-parts/post-card.php
 * so the blog index, the archives and a single article all describe a post the
 * same way. Before these, a post carried only its date on a listing and its
 * date on the article — no topic, no author, no sense of length — which is the
 * minimum a reader needs to decide whether to open something.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Estimated reading time for a post, in whole minutes.
 *
 * 200 words per minute is the conventional figure for adult reading of general
 * prose, and is deliberately conservative here: this audience skews older and
 * is often reading while managing something stressful, so rounding up (via
 * ceil, and a floor of one minute) is the kinder error to make. A stated time
 * that turns out to be short is a small broken promise; one that turns out
 * generous costs the reader nothing.
 *
 * @param int|WP_Post|null $post Post to measure. Defaults to the current post.
 * @return int Minutes, minimum 1.
 */
function ccs_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return 1;
	}

	$words = class_exists( 'CCS_Structured_Data' ) ? CCS_Structured_Data::count_words( $post ) : 0;
	if ( $words < 1 ) {
		return 1;
	}

	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Reading time as a translated, display-ready string.
 *
 * @param int|WP_Post|null $post Post to measure. Defaults to the current post.
 * @return string e.g. "4 min read".
 */
function ccs_reading_time_label( $post = null ) {
	$minutes = ccs_reading_time( $post );

	return sprintf(
		/* translators: %s: estimated reading time in minutes. */
		_n( '%s min read', '%s min read', $minutes, 'ccs-wp-theme' ),
		number_format_i18n( $minutes )
	);
}

/**
 * The single category best used to label a post.
 *
 * Listings have room for one label, not a list. Where a post sits in several
 * categories the first non-default one wins; "Uncategorised" is skipped
 * entirely rather than shown, because a label that says nothing is worse than
 * no label — it occupies the same space and spends the reader's attention.
 *
 * @param int|WP_Post|null $post Post to inspect. Defaults to the current post.
 * @return WP_Term|null Term, or null when there is nothing worth showing.
 */
function ccs_primary_category( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return null;
	}

	$categories = get_the_category( $post->ID );
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return null;
	}

	$default = (int) get_option( 'default_category' );

	foreach ( $categories as $category ) {
		if ( (int) $category->term_id !== $default ) {
			return $category;
		}
	}

	return null;
}

/**
 * Posts related to the given one, for the "more like this" block on an article.
 *
 * Category first, then recency as a fallback, because a related list that comes
 * back empty is worse than a loosely related one: the end of an article is the
 * one moment a reader has already decided they are interested, and offering
 * them nothing there ends the visit.
 *
 * @param int|WP_Post|null $post  Post to find relatives for. Defaults to current.
 * @param int              $limit Maximum posts to return.
 * @return WP_Post[] Posts, possibly empty.
 */
function ccs_related_posts( $post = null, $limit = 3 ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	$limit = max( 1, (int) $limit );
	$args  = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $limit,
		'post__not_in'        => array( $post->ID ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$category_ids = wp_get_post_categories( $post->ID );
	if ( ! empty( $category_ids ) && ! is_wp_error( $category_ids ) ) {
		$related = get_posts( array_merge( $args, array( 'category__in' => $category_ids ) ) );
		if ( count( $related ) >= $limit ) {
			return $related;
		}

		// Top up with recent posts rather than returning a short list.
		if ( ! empty( $related ) ) {
			$exclude = array_merge( array( $post->ID ), wp_list_pluck( $related, 'ID' ) );
			$filler  = get_posts(
				array_merge(
					$args,
					array(
						'posts_per_page' => $limit - count( $related ),
						'post__not_in'   => $exclude,
					)
				)
			);

			return array_merge( $related, $filler );
		}
	}

	return get_posts( $args );
}
