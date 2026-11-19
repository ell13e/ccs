<?php
/**
 * One-time page text updates shipped with the theme.
 *
 * Page text lives in the WordPress database, so copy changes the client has
 * approved would otherwise only exist on the site where they were made. Each
 * update file holds, per page, the old text and the new. An update is applied
 * only where the page still holds exactly the old text: anything the client
 * has edited since is left alone. WordPress keeps a revision of every page it
 * changes, so each can be undone from the editor.
 *
 * Nov 2026: the approved copy rewrites (docs/COPY-REWRITES-FOR-APPROVAL.md)
 * and the em-dash clean-up (copy-update-2026-11); then a pass removing the
 * AI-writing tells left after it (copy-update-2026-11b, client: "scan the
 * entire website for ai copy and rewrite"): filler "actually", "properly",
 * and the "X, not Y" contrasts and "that is the whole point" lines. Each b
 * row's old text is the page as the first update leaves it, so on a fresh
 * site the two run in order.
 *
 * Links in the files are written with the address of the site they were
 * made on, or as {home}; both are compared as the current site's address,
 * so a page with links still matches on the live site.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Apply each update file once.
 */
function ccs_apply_content_updates() {
	// 11c (Nov 2026, user: "why is this not backlinked"): the About page's
	// closing "Learn more about the home care services…" line becomes a link
	// to the services page. It was bold text that read as a link and was not one.
	$updates = array( 'copy-update-2026-11', 'copy-update-2026-11b', 'copy-update-2026-11c' );
	$home    = untrailingslashit( home_url() );
	// The site the first file was made on; its links carry this address.
	$tok   = static function ( $s ) use ( $home ) {
		return str_replace( array( $home, 'http://127.0.0.1:9401' ), '{home}', (string) $s );
	};
	$untok = static function ( $s ) use ( $home ) {
		return str_replace( '{home}', $home, (string) $s );
	};
	$done    = (array) get_option( 'ccs_content_updates_done', array() );

	foreach ( $updates as $key ) {
		if ( in_array( $key, $done, true ) ) {
			continue;
		}
		$file = get_template_directory() . '/inc/data/' . $key . '.json';
		$rows = file_exists( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null;
		if ( ! is_array( $rows ) ) {
			continue;
		}

		foreach ( $rows as $row ) {
			// By slug at any level: get_page_by_path() needs the full path, which
			// misses child pages such as Resources > FAQs.
			$found = get_posts( array( 'name' => $row['slug'], 'post_type' => $row['type'], 'post_status' => array( 'publish', 'draft', 'private' ), 'numberposts' => 1, 'suppress_filters' => true ) );
			$post  = $found ? $found[0] : null;
			if ( ! $post instanceof WP_Post ) {
				continue;
			}
			$change = array( 'ID' => $post->ID );
			// Compare on trimmed text with normalised line endings, so a save
			// through the editor that only touched whitespace still matches.
			$norm = static function ( $s ) {
				return trim( str_replace( "\r\n", "\n", (string) $s ) );
			};
			if ( $tok( $norm( $post->post_content ) ) === $tok( $norm( $row['old_content'] ) ) && $row['old_content'] !== $row['new_content'] ) {
				$change['post_content'] = $untok( $tok( $row['new_content'] ) );
			}
			if ( $tok( $norm( $post->post_excerpt ) ) === $tok( $norm( $row['old_excerpt'] ) ) && $row['old_excerpt'] !== $row['new_excerpt'] ) {
				$change['post_excerpt'] = $untok( $tok( $row['new_excerpt'] ) );
			}
			if ( isset( $row['old_title'], $row['new_title'] ) && $post->post_title === $row['old_title'] ) {
				$change['post_title'] = $row['new_title'];
			}
			if ( count( $change ) > 1 ) {
				wp_update_post( wp_slash( $change ) );
			}
		}

		$done[] = $key;
		update_option( 'ccs_content_updates_done', $done, false );
	}
}
add_action( 'admin_init', 'ccs_apply_content_updates' );

/**
 * One-time featured-image swaps (Nov 2026). Each applies only while the post
 * still uses the old image, so a photo the client has chosen is never replaced.
 *
 * - "Paying for home care in Kent": the autumn-leaves strip (1350x400) cropped
 *   badly into blog cards; it now uses the Easter activity photo, which is
 *   already in the media library.
 */
function ccs_apply_thumbnail_updates() {
	if ( get_option( 'ccs_thumbnail_updates_2026_11' ) ) {
		return;
	}
	$swaps = array(
		array( 'post' => 'paying-for-home-care-in-kent', 'from' => 'client-creative-activity-autumn-leaves', 'to' => 'client-easter-activity' ),
	);
	foreach ( $swaps as $swap ) {
		$found = get_posts( array( 'name' => $swap['post'], 'post_type' => 'post', 'numberposts' => 1, 'suppress_filters' => true ) );
		if ( ! $found ) {
			continue;
		}
		$current = (int) get_post_thumbnail_id( $found[0]->ID );
		if ( ! $current || false === strpos( basename( (string) get_attached_file( $current ) ), $swap['from'] ) ) {
			continue;
		}
		$target = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids' ) );
		foreach ( $target as $att ) {
			if ( 0 === strpos( basename( (string) get_attached_file( $att ) ), $swap['to'] ) ) {
				set_post_thumbnail( $found[0]->ID, $att );
				break;
			}
		}
	}
	update_option( 'ccs_thumbnail_updates_2026_11', 1, false );
}
add_action( 'admin_init', 'ccs_apply_thumbnail_updates' );

/**
 * Current Vacancies hero (Nov 2026). It showed the same summer-party photo as
 * the About hero; it now shows a team member at an apprenticeships fair, which
 * suits a jobs page. Imported once into the Media Library (found again by its
 * theme path, never duplicated) and set only while the page still uses the old
 * image, so a photo the client has chosen is left alone.
 */
function ccs_apply_vacancies_hero_update() {
	if ( get_option( 'ccs_vacancies_hero_2026_11' ) ) {
		return;
	}
	update_option( 'ccs_vacancies_hero_2026_11', 1, false );

	$page = get_page_by_path( 'careers/current-vacancies' );
	if ( ! $page instanceof WP_Post ) {
		return;
	}
	$current = (int) get_post_thumbnail_id( $page->ID );
	if ( $current && false === strpos( basename( (string) get_attached_file( $current ) ), 'home-hero-desktop' ) ) {
		return;
	}

	$rel      = 'assets/images/site-photos/careers-apprenticeships-fair.webp';
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_ccs_source_theme_path', 'meta_value' => $rel, 'fields' => 'ids' ) );
	$att      = $existing ? (int) $existing[0] : 0;

	if ( ! $att ) {
		$source = get_template_directory() . '/' . $rel;
		$upload = wp_upload_dir();
		if ( ! file_exists( $source ) || ! empty( $upload['error'] ) ) {
			return;
		}
		$name = wp_unique_filename( $upload['path'], wp_basename( $source ) );
		$dest = trailingslashit( $upload['path'] ) . $name;
		if ( ! copy( $source, $dest ) ) {
			return;
		}
		$att = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/webp',
				'post_title'     => __( 'Careers: apprenticeships fair', 'ccs-wp-theme' ),
				'post_status'    => 'inherit',
			),
			$dest
		);
		if ( is_wp_error( $att ) || ! $att ) {
			return;
		}
		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $dest ) );
		update_post_meta( $att, '_ccs_source_theme_path', $rel );
	}

	set_post_thumbnail( $page->ID, $att );
}
add_action( 'admin_init', 'ccs_apply_vacancies_hero_update' );

/**
 * Costs extras as "charge | price" rows (Nov 2026, costs section redesign).
 *
 * A site that saved the extras list in the Customizer while it still held the
 * old sentence-per-line default would keep showing sentences in the new table.
 * Where the saved value is exactly that old default, drop it so the new
 * default rows apply. Anything the client wrote themselves is left alone.
 */
function ccs_apply_cost_extras_update() {
	if ( get_option( 'ccs_cost_extras_rows_done' ) ) {
		return;
	}

	$old_default = implode( "\n", array(
		'A one-off £30 fee when we set up your care package',
		'Evenings, weekends and complex care are charged at higher rates',
		'Bank holidays are charged at double, and Christmas Day, Boxing Day and New Year’s Day at treble',
		'Escort visits add mileage at 50p per mile, plus any parking',
	) );

	$saved = get_theme_mod( 'ccs_cost_extras', null );
	if ( is_string( $saved ) && trim( str_replace( "\r", '', $saved ) ) === $old_default ) {
		remove_theme_mod( 'ccs_cost_extras' );
	}

	update_option( 'ccs_cost_extras_rows_done', 1, false );
}
add_action( 'admin_init', 'ccs_apply_cost_extras_update' );

/**
 * "Working for Us" becomes "Working with us" (Nov 2026, user). The page title
 * and its careers-menu label are renamed once, and only while they still read
 * exactly the old way, so a title the client has since changed is left alone.
 * The address (/careers/working-for-us/) is unchanged, so no link breaks.
 */
function ccs_apply_working_with_us_rename() {
	if ( get_option( 'ccs_working_with_us_renamed' ) ) {
		return;
	}
	$targets = get_posts(
		array(
			'post_type'        => array( 'page', 'nav_menu_item' ),
			'post_status'      => 'any',
			'title'            => 'Working for Us',
			'numberposts'      => 20,
			'suppress_filters' => true,
		)
	);
	foreach ( $targets as $target ) {
		if ( 'Working for Us' === $target->post_title ) {
			wp_update_post( array( 'ID' => $target->ID, 'post_title' => 'Working with us' ) );
		}
	}
	update_option( 'ccs_working_with_us_renamed', 1, false );
}
add_action( 'admin_init', 'ccs_apply_working_with_us_rename' );

/**
 * Shorter careers-menu labels (Nov 2026, user: "nav bar is still cramped").
 * The careers bar held four long labels and two buttons in 1184px, so the
 * logo touched the first link. "Professional Development" and "Current
 * Vacancies" become "Development" and "Vacancies" in the menu only: the page
 * titles are unchanged. Applied once, and only to labels that still read
 * exactly the old way.
 */
function ccs_apply_short_careers_menu_labels() {
	if ( get_option( 'ccs_short_careers_menu_labels_v2' ) ) {
		return;
	}
	// A menu item for a page has no label of its own until one is typed: it
	// shows the page's title. So find the items by the page they point to,
	// and label only those still unlabelled or still carrying the page title.
	$labels = array(
		'professional-development' => 'Development',
		'current-vacancies'        => 'Vacancies',
	);
	foreach ( $labels as $slug => $new ) {
		$pages = get_posts( array( 'name' => $slug, 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'suppress_filters' => true ) );
		if ( empty( $pages ) ) {
			continue;
		}
		$items = get_posts(
			array(
				'post_type'        => 'nav_menu_item',
				'post_status'      => 'any',
				'numberposts'      => 20,
				'meta_key'         => '_menu_item_object_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-time update.
				'meta_value'       => (string) $pages[0]->ID, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- one-time update.
				'suppress_filters' => true,
			)
		);
		foreach ( $items as $item ) {
			if ( '' === $item->post_title || $pages[0]->post_title === $item->post_title ) {
				wp_update_post( array( 'ID' => $item->ID, 'post_title' => $new ) );
			}
		}
	}
	update_option( 'ccs_short_careers_menu_labels_v2', 1, false );
}
add_action( 'admin_init', 'ccs_apply_short_careers_menu_labels' );

/**
 * Friendlier opening on "Working with us" (Nov 2026, user: "the para could be
 * reworded more friendly"). Replaces the page's first paragraph once, and
 * only while it still reads exactly as shipped, so wording the client has
 * since changed is left alone. WordPress keeps a revision.
 */
function ccs_apply_working_intro_reword() {
	if ( get_option( 'ccs_working_intro_reworded' ) ) {
		return;
	}
	$old   = 'We are a small, family-run provider based in Maidstone. That shapes the job more than anything else on this page: you work with a handful of regular clients rather than a different address every hour, and you are known by name in the office.';
	$new   = 'We’re a small, family-run team in Maidstone, and you’ll feel that from your first day. You’ll look after a handful of regular clients and get to know them well, and everyone in the office will know your name.';
	$pages = get_posts( array( 'name' => 'working-for-us', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( ! empty( $pages ) && false !== strpos( $pages[0]->post_content, $old ) ) {
		wp_update_post( wp_slash( array( 'ID' => $pages[0]->ID, 'post_content' => str_replace( $old, $new, $pages[0]->post_content ) ) ) );
	}
	update_option( 'ccs_working_intro_reworded', 1, false );
}
add_action( 'admin_init', 'ccs_apply_working_intro_reword' );

/**
 * Shorter heading on the careers hub (Nov 2026, user: "too long"). The
 * heading is the page's own first h2, so it is changed once in the page,
 * and only while it still reads exactly as shipped.
 */
function ccs_apply_careers_heading_shorten() {
	if ( get_option( 'ccs_careers_heading_shortened' ) ) {
		return;
	}
	$old   = 'Why choose a career with Continuity?';
	$pages = get_posts( array( 'name' => 'careers', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( ! empty( $pages ) && false !== strpos( $pages[0]->post_content, $old ) ) {
		wp_update_post( wp_slash( array( 'ID' => $pages[0]->ID, 'post_content' => str_replace( $old, 'Why work with us?', $pages[0]->post_content ) ) ) );
	}
	update_option( 'ccs_careers_heading_shortened', 1, false );
}
add_action( 'admin_init', 'ccs_apply_careers_heading_shorten' );

/**
 * No named qualifications (Nov 2026, user: "we don't know the specific
 * qualifications we fund"). Two careers pages named Level 2, 3 and 5
 * diplomas. They now say what the company's job adverts say: there are
 * opportunities to take health and social care qualifications. Applied once,
 * and only to text that still reads as shipped.
 */
function ccs_apply_unnamed_qualifications() {
	if ( get_option( 'ccs_unnamed_qualifications' ) ) {
		return;
	}
	$get = static function ( $slug ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
		return $found ? $found[0] : null;
	};

	$page = $get( 'working-for-us' );
	$old  = 'Beyond that we fund the Level 2 and Level 3 Diploma in Adult Care and specialist training in areas like';
	if ( $page && false !== strpos( $page->post_content, $old ) ) {
		wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_content' => str_replace( $old, 'Beyond that there are opportunities to take health and social care qualifications, and specialist training in areas like', $page->post_content ) ) ) );
	}

	$page = $get( 'professional-development' );
	if ( $page && false !== strpos( $page->post_content, 'Level 5 Diploma in Leadership and Management' ) ) {
		$new     = "\n<p>There are opportunities to take health and social care qualifications while you work with us. Tell us where you want to get to and we will talk through which one fits.</p>\n";
		$content = preg_replace( '#(<h2[^>]*>\s*Qualifications we fund\s*</h2>).*?(?=<h2|\z)#s', '$1' . $new, $page->post_content, 1 );
		if ( is_string( $content ) && $content !== $page->post_content ) {
			$content = str_replace( '>Qualifications we fund<', '>Qualifications<', $content );
			wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_content' => $content ) ) );
		}
	}
	update_option( 'ccs_unnamed_qualifications', 1, false );
}
add_action( 'admin_init', 'ccs_apply_unnamed_qualifications' );
