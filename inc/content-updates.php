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
	// 11d (Nov 2026, client): the FAQ's "How quickly can care start?" answer now
	// says "often starts within a month", matching the homepage steps intro.
	// 11e: the same sentence, shortened after the client's copy review.
	// 11f (Nov 2026, client): the FAQ page's seven questions replaced by the
	// client's fourteen "Questions about care at home", each with its NHS,
	// council or CQC link.
	// 11g (Nov 2026, client: "why is getting started signposted in resources"):
	// the Resources page no longer lists Getting started, which is a step in
	// arranging care and sits under About, and its line about the common
	// questions names what the new questions cover.
	// 11i (Nov 2026, the client's review of Getting started, "a light edit, not a
	// rewrite"): four passages replaced with her wording (no "sales call", no
	// "being processed", no suggestion that the plan starts out wrong); the first
	// call and the assessment visit are each said to be free; the on-call number
	// is said not to be an emergency service.
	// 11j (Nov 2026, client's text): the Getting started lead paragraph.
	// 11k (Nov 2026, client: "dont promise a team of two to four carers, or that
	// we will working to the hospital discharge date, but that we wil try"):
	// Getting started no longer gives a team size, and says we will do our best
	// to have care ready for the day someone comes home.
	// 11h (Nov 2026, client's notes on the services wording): seven service
	// descriptions say what the carer does. Overnight and continuous care are told
	// apart; "pain relief", "rest peacefully" and "medication on time" are gone.
	$updates = array( 'copy-update-2026-11', 'copy-update-2026-11b', 'copy-update-2026-11c', 'copy-update-2026-11d', 'copy-update-2026-11e', 'copy-update-2026-11f', 'copy-update-2026-11g', 'copy-update-2026-11h', 'copy-update-2026-11i', 'copy-update-2026-11j', 'copy-update-2026-11k' );
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

/**
 * "Working for Us" becomes "Working with us" (Nov 2026, user). The page title
 * and its careers-menu label are renamed once, and only while they still read
 * exactly the old way, so a title the client has since changed is left alone.
 * The address stayed /careers/working-for-us/ until 24 Nov 2026, when it was
 * changed to match the title (see ccs_working_with_us_address() below).
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
	$pages = get_posts( array( 'name' => 'working-with-us', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( ! empty( $pages ) && false !== strpos( $pages[0]->post_content, $old ) ) {
		wp_update_post( wp_slash( array( 'ID' => $pages[0]->ID, 'post_content' => str_replace( $old, $new, $pages[0]->post_content ) ) ) );
	}
	update_option( 'ccs_working_intro_reworded', 1, false );
}

/**
 * The services hub no longer says "three kinds of home care" (Nov 2026, user
 * approved the reword: the site now has eight services). No count is given,
 * so the sentence holds if services are added. Replaces the first two sentences
 * and drops the third, once, each only while it still reads exactly as shipped. WordPress keeps a
 * revision.
 */
function ccs_apply_services_intro_reword() {
	if ( get_option( 'ccs_services_intro_reword_2026_11' ) ) {
		return;
	}
	// Each sentence is swapped on its own, so one the client has edited is left alone.
	$swaps = array(
		'We provide three kinds of home care across Maidstone and the surrounding Kent towns.' => 'We provide home care across Maidstone and the surrounding Kent towns.',
		'Most people start with one and move between them as things change' => 'Most people start with one kind of care and move between them as things change',
		// The closing sentence went (user: "i hate this wording"), in either form the page may hold.
		' That is the point of arranging care with one provider rather than several.' => '',
		' — which is the point of arranging care with one provider rather than several.' => '.',
	);
	$pages = get_posts( array( 'name' => 'home-care-services-kent', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( ! empty( $pages ) ) {
		$content = strtr( $pages[0]->post_content, $swaps );
		if ( $content !== $pages[0]->post_content ) {
			wp_update_post( wp_slash( array( 'ID' => $pages[0]->ID, 'post_content' => $content ) ) );
		}
	}
	update_option( 'ccs_services_intro_reword_2026_11', 1, false );
}

/**
 * The client's notes on the services wording (Nov 2026). Once, and each
 * sentence only while it still reads as shipped, so anything edited since is
 * left alone. WordPress keeps a revision.
 *   - "Most people start with one kind of care and move between them as
 *     things change" goes from the services page intro (it read as if moving
 *     between services were the norm).
 *   - "Choosing between them" keeps its reassurance and drops the guess at
 *     how much support people end up needing.
 *   - Two sentences on that page lose their dashes (house style).
 *   - The services page says we will do our best to have care ready for a
 *     hospital discharge, in place of "we work to the discharge date".
 *   - Overnight care no longer promises that anyone will "rest peacefully".
 */
function ccs_apply_services_wording_2026_11h() {
	if ( get_option( 'ccs_services_wording_2026_11m' ) ) {
		return;
	}
	$edit = static function ( $slug, $type, $swaps ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => $type, 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
		if ( $found ) {
			$content = strtr( $found[0]->post_content, $swaps );
			if ( $content !== $found[0]->post_content ) {
				wp_update_post( wp_slash( array( 'ID' => $found[0]->ID, 'post_content' => $content ) ) );
			}
		}
	};
	$edit(
		'home-care-services-kent',
		'page',
		array(
			' Most people start with one kind of care and move between them as things change.' => '',
			'If you are not sure which service fits, you do not need to decide before calling. The assessment visit exists to work that out with you.' => 'If you already know what kind of care you need, that’s great. If you don’t, please don’t worry. Tell us what’s been feeling hard lately, and we’ll work out the rest together.',
			'Not sure which kind of care is right? That’s fine. You don’t need to know before you call. We’ll come and see you, and work it out together.' => 'If you already know what kind of care you need, that’s great. If you don’t, please don’t worry. Tell us what’s been feeling hard lately, and we’ll work out the rest together.',
			'You don’t have to know what kind of care you need. Tell us what’s been feeling hard lately, and when we come to see you we’ll work out the rest together.' => 'If you already know what kind of care you need, that’s great. If you don’t, please don’t worry. Tell us what’s been feeling hard lately, and we’ll work out the rest together.',
			// A promise softened to what can be kept (client: say that we will try).
			'If you are coming out of hospital, we work to the discharge date and take a handover from the ward.' => 'If you are coming out of hospital, we will do our best to have care ready for the day you come home, and we take a handover from the ward.',
			// The reassurance stays; the guess at how much support people end up needing goes (client's review), and the wording is softer (client: "friendlier, softer").
			'If you are not sure which service fits, you do not need to decide before calling. The assessment visit exists to work that out, and it is common to start with less support than you expected to need — or more, for a short while, and then step down.' => 'If you already know what kind of care you need, that’s great. If you don’t, please don’t worry. Tell us what’s been feeling hard lately, and we’ll work out the rest together.',
			'If you are not sure which service fits, you do not need to decide before calling. The assessment visit exists to work that out, and it is common to start with less support than you expected to need, or with more for a short while and then step down.' => 'If you already know what kind of care you need, that’s great. If you don’t, please don’t worry. Tell us what’s been feeling hard lately, and we’ll work out the rest together.',
			'the change in breathing — the early signs that keep people out of hospital.' => 'the change in breathing: the early signs that keep people out of hospital.',
			'ring and ask — the honest answer depends' => 'ring and ask. The honest answer depends',
		)
	);
	$edit(
		'overnight-care',
		'service',
		array(
			'providing discreet and compassionate support so you and your loved ones can rest peacefully.' => 'there to help when you need it, so family at home can sleep too.',
		)
	);
	update_option( 'ccs_services_wording_2026_11m', 1, false );
}

/**
 * The About page's closing link, shortened to fit one line (Nov 2026, client:
 * its last two words sat alone on a second line, and split evenly over two
 * lines it "looks worse"). Once, and only while it still reads as shipped.
 */
function ccs_apply_about_link_shorten_2026_11() {
	if ( get_option( 'ccs_about_link_shortened_2026_11' ) ) {
		return;
	}
	$found = get_posts( array( 'name' => 'about-home-care-maidstone', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( $found ) {
		$content = strtr(
			$found[0]->post_content,
			array(
				'Learn more about the home care services we offer in Maidstone &amp; Kent.' => 'Learn more about our home care services',
				'Learn more about the home care services we offer in Maidstone & Kent.' => 'Learn more about our home care services',
			)
		);
		if ( $content !== $found[0]->post_content ) {
			wp_update_post( wp_slash( array( 'ID' => $found[0]->ID, 'post_content' => $content ) ) );
		}
	}
	update_option( 'ccs_about_link_shortened_2026_11', 1, false );
}

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

	$page = $get( 'working-with-us' );
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


/**
 * The page's address matches its title (Nov 2026, client: "that page URL
 * should be working with us"): /careers/working-with-us/, not
 * /careers/working-for-us/. The theme's own code now uses the new slug
 * throughout, so on a site that already has the page it is renamed before
 * anything else looks for it, and the old address is sent on to the new one
 * for good, so no link or bookmark breaks.
 */
function ccs_working_with_us_address() {
	if ( get_option( 'ccs_working_with_us_address' ) ) {
		return;
	}
	$old   = 'working-' . 'for-us';
	$pages = get_posts(
		array(
			'name'             => $old,
			'post_type'        => 'page',
			'post_status'      => 'any',
			'numberposts'      => 1,
			'suppress_filters' => true,
		)
	);
	if ( $pages && ! get_posts( array( 'name' => 'working-with-us', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) ) ) {
		wp_update_post(
			array(
				'ID'        => $pages[0]->ID,
				'post_name' => 'working-with-us',
			)
		);
	}
	update_option( 'ccs_working_with_us_address', 1, false );
}
add_action( 'init', 'ccs_working_with_us_address', 5 );

// The old address is sent to the new one in inc/old-site-redirects.php, with the old website's addresses.

/**
 * The About page's closing line is a link (Nov 2026, client, pasting the
 * sentence: it read as a prompt to click and went nowhere). An earlier
 * update was meant to link it, but it looked for the line's older, longer
 * wording, which new sites never had, so the line stayed bold text. It is
 * linked once here, and only where it is still the plain bold line.
 */
function ccs_link_about_services_line_2026_11() {
	if ( get_option( 'ccs_about_services_line_2026_11' ) ) {
		return;
	}
	$found = get_posts( array( 'name' => 'about-home-care-maidstone', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( ! $found ) {
		return;
	}
	update_option( 'ccs_about_services_line_2026_11', 1, false );
	$old = '<strong>Learn more about our home care services</strong>';
	if ( false !== strpos( $found[0]->post_content, $old ) ) {
		$new = '<a href="' . esc_url( home_url( '/home-care-services-kent/' ) ) . '">Learn more about our home care services</a>';
		wp_update_post( wp_slash( array( 'ID' => $found[0]->ID, 'post_content' => str_replace( $old, $new, $found[0]->post_content ) ) ) );
	}
}
add_action( 'init', 'ccs_link_about_services_line_2026_11', 50 );

/**
 * The news article about the summer party at Lainey's Care Farm is not
 * wanted (client, 24 Nov 2026: "I don't need the Lainey's article"). New
 * sites no longer get it; on a site that already has it, it goes to the bin
 * once, where it can be restored.
 */
function ccs_remove_laineys_article_2026_11() {
	if ( get_option( 'ccs_laineys_article_removed_2026_11' ) ) {
		return;
	}
	update_option( 'ccs_laineys_article_removed_2026_11', 1, false );
	$found = get_posts( array( 'name' => 'summer-at-laineys-care-farm', 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 1, 'suppress_filters' => true ) );
	if ( $found ) {
		wp_trash_post( $found[0]->ID );
	}
}
add_action( 'init', 'ccs_remove_laineys_article_2026_11', 60 );

/**
 * The shopping article's text (25 Nov 2026, client: "expand articles not
 * fully written"). The old site's article stopped after promising "a list
 * of essential tips". The first paragraph is the author's, unchanged; the
 * tips follow the NCSC's "Shopping and paying safely online" (reviewed 24
 * Nov 2026), Citizens Advice on card refunds, and Report Fraud, which
 * replaced Action Fraud for England, Wales and Northern Ireland.
 *
 * @param bool $expanded False for the old site's single paragraph.
 * @return string HTML.
 */
function ccs_shopping_article_content( $expanded = true ) {
	$first = '<p>As Black Friday and the holiday shopping season approach, online scams are on the rise. In 2023 alone, £85.9 million was lost to online purchase scams, with 92% of these frauds originating on the internet. To help you stay safe while shopping online, we’ve compiled a list of essential tips. Protect your finances, personal information, and peace of mind with these guidelines.</p>';
	if ( ! $expanded ) {
		return $first;
	}
	$link = static function ( $url, $text ) {
		return '<a href="' . esc_url( $url ) . '">' . $text . '</a>';
	};
	return $first . '
<h2>Check the shop is real</h2>
<p>If you haven’t bought from a shop before, look it up and read what people say about it on websites you trust. Go to the shop by typing its address or searching for it yourself, rather than following a link in an email, text or advert. If a deal looks too good to be true, take a moment before you buy.</p>
<h2>Be wary of links in emails and texts</h2>
<p>If a message doesn’t feel right, don’t click the link. You can forward suspicious emails to report@phishing.gov.uk and suspicious texts to 7726.</p>
<h2>Keep your accounts secure</h2>
<p>Use a strong password that you don’t use anywhere else for your shopping, email and bank accounts, and turn on two-step verification where it’s offered. Some websites now let you sign in with a passkey, which is safer still.</p>
<h2>Only give the details they need</h2>
<p>Fill in only what’s needed to complete the order, and check out as a guest if you don’t need an account. Rather than saving your card with every shop, you can keep it in your phone, browser or password manager.</p>
<h2>Pay in a way that protects you</h2>
<p>A credit card gives you the most protection. If a single item costs more than £100 and no more than £30,000, the card company can be responsible along with the seller if something goes wrong. Debit card payments can often be reversed through chargeback. Never pay a shop you don’t know by bank transfer. ' . $link( 'https://www.citizensadvice.org.uk/consumer/somethings-gone-wrong-with-a-purchase/getting-your-money-back-if-you-paid-by-card-or-paypal/', 'Citizens Advice explains how to get your money back' ) . '.</p>
<h2>If something goes wrong</h2>
<p>Contact your bank straight away, using the number on the back of your card or its official app. Then report it to ' . $link( 'https://www.reportfraud.police.uk/', 'Report Fraud' ) . ' if you live in England, Wales or Northern Ireland, or to Police Scotland on 101 if you live in Scotland.</p>
<p>If you help an older relative or someone you care for with their shopping, these are worth going through together.</p>
<p>This advice is based on guidance from the ' . $link( 'https://www.ncsc.gov.uk/guidance/shopping-online-securely', 'National Cyber Security Centre' ) . '.</p>';
}

/**
 * Give an existing site the expanded shopping article, once, only where the
 * article still holds the old site's single paragraph.
 *
 * @return void
 */
function ccs_expand_shopping_article_2026_11() {
	if ( get_option( 'ccs_expand_shopping_article_2026_11' ) ) {
		return;
	}
	$found = get_posts( array( 'name' => 'stay-safe-online-shopping-tips', 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1 ) );
	if ( ! $found ) {
		return;
	}
	if ( trim( $found[0]->post_content ) === ccs_shopping_article_content( false ) ) {
		wp_update_post( wp_slash( array( 'ID' => $found[0]->ID, 'post_content' => ccs_shopping_article_content() ) ) );
	}
	update_option( 'ccs_expand_shopping_article_2026_11', '1', false );
}

/**
 * The old website's articles instead of the ones written for this build
 * (client, 25 Nov 2026: "copy over these articles instead of the ones we've
 * made up"). Moves the seven written articles to the bin (restorable from
 * Posts > Bin) and adds the old site's shopping article if it is missing,
 * word for word, with its old date and image. The other three old articles
 * and Keelie's are untouched.
 *
 * @return void
 */
function ccs_retire_written_articles_2026_11() {
	if ( get_option( 'ccs_retire_written_articles_2026_11' ) ) {
		return;
	}
	$written = array(
		'signs-a-relative-needs-help-at-home',
		'what-happens-at-a-care-assessment',
		'paying-for-home-care-in-kent',
		'home-care-or-a-care-home',
		'nhs-continuing-healthcare-explained',
		'coming-home-from-hospital-kent',
		'what-home-care-costs-kent-examples',
	);
	foreach ( $written as $slug ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'private', 'future' ), 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( $found ) {
			wp_trash_post( (int) $found[0] );
		}
	}

	$slug = 'stay-safe-online-shopping-tips';
	if ( ! get_posts( array( 'name' => $slug, 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
		$id     = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_author'   => $admins ? (int) $admins[0]->ID : 1,
				'post_title'    => 'Stay Safe While Shopping Online',
				'post_name'     => $slug,
				'post_date'     => '2024-11-27 12:43:42',
				'post_excerpt'  => 'As Black Friday and the holiday shopping season approach, online scams are on the rise.',
				'post_content'  => ccs_shopping_article_content(),
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			$term = get_term_by( 'name', 'News', 'category' );
			if ( ! $term instanceof WP_Term ) {
				$made = wp_insert_term( 'News', 'category' );
				$term = is_wp_error( $made ) ? null : get_term( (int) $made['term_id'], 'category' );
			}
			if ( $term instanceof WP_Term ) {
				wp_set_post_categories( $id, array( (int) $term->term_id ), false );
			}
			if ( function_exists( 'ccs_articles_import_photo' ) ) {
				$photo = ccs_articles_import_photo( 'assets/images/site-photos-extra/cyber-safe-with-ccs.webp', 'Cyber safe with CCS' );
				if ( $photo ) {
					set_post_thumbnail( $id, $photo );
				}
			}
		}
	}
	update_option( 'ccs_retire_written_articles_2026_11', '1', false );
}

/**
 * Sharper photos for the domiciliary and respite services (client's photos,
 * 25 Nov 2026, all with consent). The old ones were 550px files shown on
 * about 34 pages. Swapped once, only where the service still has the old
 * photo (or none), so a photo picked in WordPress is kept.
 *
 * @return void
 */
function ccs_swap_service_photos_2026_11() {
	if ( get_option( 'ccs_swap_service_photos_2026_11' ) || ! function_exists( 'ccs_articles_import_photo' ) ) {
		return;
	}
	$swaps = array(
		'domiciliary-care' => array( 'assets/images/site-photos/ccs-domiciliary-care.webp', 'assets/images/site-photos-extra/domiciliary-care-birthday-at-home.webp', 'Domiciliary care: a birthday at home' ),
		'respite-care'     => array( 'assets/images/site-photos/ccs-respite-care-guitar-1.webp', 'assets/images/site-photos-extra/respite-care-day-out-at-the-castle.webp', 'Respite care: a day out at the castle' ),
	);
	foreach ( $swaps as $slug => $swap ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'service', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( ! $found ) {
			continue;
		}
		$current = (int) get_post_thumbnail_id( $found[0] );
		if ( $current && get_post_meta( $current, '_ccs_source_theme_path', true ) !== $swap[0] ) {
			continue;
		}
		$photo = ccs_articles_import_photo( $swap[1], $swap[2] );
		if ( $photo ) {
			set_post_thumbnail( (int) $found[0], $photo );
		}
	}
	update_option( 'ccs_swap_service_photos_2026_11', '1', false );
}

/**
 * Photos for the three old-site articles that had none (client's photos, 25
 * Nov 2026, all with consent; the old site's own two were an apprenticeship
 * fair and an awards night, unrelated to the articles). Set once, only on an
 * article with no photo, so one picked in WordPress is kept.
 *
 * @return void
 */
function ccs_article_photos_2026_11() {
	if ( get_option( 'ccs_article_photos_2026_11' ) || ! function_exists( 'ccs_articles_import_photo' ) ) {
		return;
	}
	$photos = array(
		'proud-to-be-a-nurse-maidstone'         => array( 'assets/images/site-photos-extra/article-nurse-training-session.webp', 'Team training around a care bed' ),
		'the-right-person-makes-the-difference' => array( 'assets/images/site-photos-extra/article-friends-on-the-sofa.webp', 'Two friends on the sofa' ),
		'end-of-life-care-experience'           => array( 'assets/images/site-photos-extra/caregiver-supporting-patient.webp', 'Hands held in support' ),
	);
	foreach ( $photos as $slug => $photo ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( ! $found || has_post_thumbnail( $found[0] ) ) {
			continue;
		}
		$id = ccs_articles_import_photo( $photo[0], $photo[1] );
		if ( $id ) {
			set_post_thumbnail( (int) $found[0], $id );
		}
	}
	update_option( 'ccs_article_photos_2026_11', '1', false );
}

/**
 * Use one business name everywhere: "Continuity of Care Services" (client,
 * 25 Nov 2026). Replaces "Continuity Care Services" in saved content once:
 * page and post titles, text and excerpts, image alt text and captions, the
 * theme's own fields (ccs_ meta), the site title and the Customizer copy.
 * Web and email addresses contain no spaces, so they never match.
 *
 * @return void
 */
function ccs_business_name_2026_11() {
	if ( get_option( 'ccs_business_name_2026_11' ) ) {
		return;
	}
	global $wpdb;
	$old = 'Continuity Care Services';
	$new = 'Continuity of Care Services';
	$like = '%' . $wpdb->esc_like( $old ) . '%';

	foreach ( array( 'post_title', 'post_content', 'post_excerpt' ) as $col ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET {$col} = REPLACE({$col}, %s, %s) WHERE {$col} LIKE %s", $old, $new, $like ) );
	}

	// Meta may be serialised, so go through the API rather than REPLACE().
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id, post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE %s AND ( meta_key LIKE %s OR meta_key = '_wp_attachment_image_alt' )", $like, $wpdb->esc_like( 'ccs_' ) . '%' ) );
	$swap = static function ( $value ) use ( &$swap, $old, $new ) {
		if ( is_string( $value ) ) {
			return str_replace( $old, $new, $value );
		}
		if ( is_array( $value ) ) {
			return array_map( $swap, $value );
		}
		return $value;
	};
	foreach ( (array) $rows as $row ) {
		$value = get_metadata_by_mid( 'post', (int) $row->meta_id );
		if ( $value ) {
			update_metadata_by_mid( 'post', (int) $row->meta_id, $swap( $value->meta_value ) );
		}
	}

	foreach ( array( 'blogname', 'blogdescription' ) as $option ) {
		$value = get_option( $option );
		if ( is_string( $value ) && false !== strpos( $value, $old ) ) {
			update_option( $option, str_replace( $old, $new, $value ) );
		}
	}
	$mods = get_theme_mods();
	if ( is_array( $mods ) ) {
		$changed = $swap( $mods );
		if ( $changed !== $mods ) {
			update_option( 'theme_mods_' . get_option( 'stylesheet' ), $changed );
		}
	}

	clean_post_cache( 0 );
	wp_cache_flush();
	update_option( 'ccs_business_name_2026_11', '1', false );
}

/**
 * The same name change for user names, which articles show as their author
 * (and in their schema): display name, nickname, first and last name.
 *
 * @return void
 */
function ccs_business_name_users_2026_11() {
	if ( get_option( 'ccs_business_name_users_2026_11' ) ) {
		return;
	}
	$old = 'Continuity Care Services';
	$new = 'Continuity of Care Services';
	foreach ( get_users( array( 'search' => '*Continuity*', 'search_columns' => array( 'display_name', 'user_nicename', 'user_login' ) ) ) as $user ) {
		if ( false !== strpos( $user->display_name, $old ) ) {
			wp_update_user( array( 'ID' => $user->ID, 'display_name' => str_replace( $old, $new, $user->display_name ) ) );
		}
	}
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_key, meta_value FROM {$wpdb->usermeta} WHERE meta_key IN ('nickname','first_name','last_name') AND meta_value LIKE %s", '%' . $wpdb->esc_like( $old ) . '%' ) );
	foreach ( (array) $rows as $row ) {
		update_user_meta( (int) $row->user_id, $row->meta_key, str_replace( $old, $new, $row->meta_value ) );
	}
	update_option( 'ccs_business_name_users_2026_11', '1', false );
}

/**
 * One list of enquiries (Nov 2026): move anything the old consultation form
 * saved ("Consultation Enquiries", post type ccs_enquiry) into Enquiries,
 * where every form on the site saves. The two use the same field names, so
 * each entry keeps its details; it is marked as coming from the old form.
 * Also clears the old form's shortcode from the Contact page.
 *
 * @return void
 */
function ccs_merge_consultation_enquiries_2026_11() {
	if ( get_option( 'ccs_merge_consultation_enquiries_2026_11' ) ) {
		return;
	}
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", 'ccs_enquiry' ) );
	foreach ( (array) $ids as $id ) {
		$id = (int) $id;
		set_post_type( $id, 'enquiry' );
		if ( '' === (string) get_post_meta( $id, 'enquiry_source', true ) ) {
			update_post_meta( $id, 'enquiry_source', 'Old consultation form' );
		}
	}
	$contact = get_page_by_path( 'contact-us' );
	if ( $contact && '[ccs_consultation_form]' === trim( $contact->post_content ) ) {
		wp_update_post(
			array(
				'ID'           => $contact->ID,
				'post_content' => '',
			)
		);
	}
	delete_transient( 'ccs_dashboard_stats' ); // The dashboard widget's counts (CCS_Dashboard_Widget::TRANSIENT).
	update_option( 'ccs_merge_consultation_enquiries_2026_11', '1', false );
}

/**
 * Every one-time update in this file, in the order they were written.
 *
 * Each checks the site before it changes anything and records that it ran,
 * so running the list again changes nothing. Add new updates to the end.
 *
 * @return string[] Function names.
 */
function ccs_content_update_steps() {
	return array(
		'ccs_apply_content_updates',
		'ccs_apply_thumbnail_updates',
		'ccs_apply_vacancies_hero_update',
		'ccs_apply_cost_extras_update',
		'ccs_apply_working_with_us_rename',
		'ccs_apply_short_careers_menu_labels',
		'ccs_apply_working_intro_reword',
		'ccs_apply_services_intro_reword',
		'ccs_apply_services_wording_2026_11h',
		'ccs_apply_about_link_shorten_2026_11',
		'ccs_apply_careers_heading_shorten',
		'ccs_apply_unnamed_qualifications',
		'ccs_retire_written_articles_2026_11',
		'ccs_expand_shopping_article_2026_11',
		'ccs_swap_service_photos_2026_11',
		'ccs_article_photos_2026_11',
		'ccs_business_name_2026_11',
		'ccs_business_name_users_2026_11',
		'ccs_activate_seo_plugin_2026_11',
		'ccs_merge_consultation_enquiries_2026_11',
		'ccs_care_guides_coming_soon_2026_11',
	);
}

/**
 * Run every one-time update.
 *
 * @return void
 */
function ccs_run_content_updates() {
	foreach ( ccs_content_update_steps() as $step ) {
		if ( function_exists( $step ) ) {
			$step();
		}
	}
}
add_action( 'admin_init', 'ccs_run_content_updates' );

/**
 * Run the updates once on the first ordinary visit after the theme is set up
 * or updated, as well as when the admin is opened (Nov 2026).
 *
 * They used to run only when someone opened the admin, so a fresh site showed
 * older wording until then: Professional Development kept its em dashes after
 * the clean-up that removes them. This waits until setup has finished (its
 * page list is saved and its lock released), so an update never looks for a
 * page that is still being created, and runs again only when this file
 * changes. One request at a time: the others carry on as normal.
 *
 * @return void
 */
function ccs_run_content_updates_on_visit() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	$signature = (string) filemtime( __FILE__ ) . '-' . (string) filesize( __FILE__ ) . '-' . md5( implode( ',', ccs_content_update_steps() ) );
	if ( get_option( 'ccs_content_updates_signature' ) === $signature ) {
		return;
	}
	if ( ! get_option( 'ccs_theme_activation_page_ids' ) || false !== get_option( 'ccs_theme_activation_lock', false ) ) {
		return;
	}

	global $wpdb;
	$lock = 'ccs_content_updates_lock';
	// One atomic insert, as the activation lock does: only one request wins.
	$won = $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO `{$wpdb->options}` ( `option_name`, `option_value`, `autoload` ) VALUES ( %s, %s, 'no' )", $lock, (string) time() ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( ! $won ) {
		$since = (int) $wpdb->get_var( $wpdb->prepare( "SELECT `option_value` FROM `{$wpdb->options}` WHERE `option_name` = %s", $lock ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( $since > time() - 300 ) {
			return;
		}
		$wpdb->delete( $wpdb->options, array( 'option_name' => $lock ) ); // A lock left by a crashed run.
		return;
	}
	try {
		ccs_run_content_updates();
		update_option( 'ccs_content_updates_signature', $signature, true );
	} finally {
		$wpdb->delete( $wpdb->options, array( 'option_name' => $lock ) );
		wp_cache_delete( $lock, 'options' );
	}
}
add_action( 'wp_loaded', 'ccs_run_content_updates_on_visit' );
