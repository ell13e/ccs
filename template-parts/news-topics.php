<?php
/**
 * Topic menu for the news index and its topic archives.
 *
 * Structure after myhomelife.org.uk's news hub (client reference, Nov 2026):
 * topics down the left, one list of stories beside them. Each topic is a
 * plain link to its category archive, so it works without JavaScript, can be
 * bookmarked, and the back button behaves. The current one carries the plum
 * "you are here" bar (CCS/CLAUDE.md rule 2) and aria-current.
 *
 * "Uncategorised" is left out for the same reason ccs_primary_category()
 * hides it: a topic that says nothing is not a topic.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_topics = get_categories(
	array(
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_category' ) ),
		'orderby'    => 'name',
	)
);

// One topic is not a choice; the menu only earns its place with two or more.
if ( count( $ccs_topics ) < 2 ) {
	return;
}

$ccs_posts_page = (int) get_option( 'page_for_posts' );
$ccs_all_url    = $ccs_posts_page ? get_permalink( $ccs_posts_page ) : home_url( '/' );
$ccs_current_id = is_category() ? (int) get_queried_object_id() : 0;
$ccs_on_all     = is_home();
?>

<nav class="news-topics" aria-label="<?php esc_attr_e( 'News topics', 'ccs-wp-theme' ); ?>">
	<p class="news-topics__title" id="news-topics-title"><?php esc_html_e( 'Topics', 'ccs-wp-theme' ); ?></p>
	<ul class="news-topics__list" role="list" aria-labelledby="news-topics-title">
		<li>
			<a class="news-topics__link" href="<?php echo esc_url( $ccs_all_url ); ?>"<?php echo $ccs_on_all ? ' aria-current="page"' : ''; ?>>
				<?php esc_html_e( 'All stories', 'ccs-wp-theme' ); ?>
			</a>
		</li>
		<?php foreach ( $ccs_topics as $ccs_topic ) : ?>
			<li>
				<a class="news-topics__link" href="<?php echo esc_url( get_category_link( $ccs_topic ) ); ?>"<?php echo ( (int) $ccs_topic->term_id === $ccs_current_id ) ? ' aria-current="page"' : ''; ?>>
					<?php echo esc_html( $ccs_topic->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	/*
	 * On phones the pills scroll sideways, so on a topic page the current pill
	 * may start off screen. Scroll the row (only the row, never the page) so
	 * it shows. Small enough to sit with its markup.
	 */
	?>
	<script>
	( function () {
		var row = document.currentScript.previousElementSibling;
		var cur = row && row.querySelector( '[aria-current="page"]' );
		if ( cur && row.scrollWidth > row.clientWidth ) {
			row.scrollLeft += cur.getBoundingClientRect().left - row.getBoundingClientRect().left - parseFloat( getComputedStyle( row ).paddingLeft );
		}
	}() );
	</script>
</nav>
