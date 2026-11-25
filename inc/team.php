<?php
/**
 * Team roster.
 *
 * Single source of truth for the people shown on the "Who You'll Meet" page,
 * so the list isn't buried in page content where it's easy to miss when someone
 * joins or leaves.
 *
 * Roster confirmed with Ellie 2026-08-19. Jacqueline Ellis (Field Care
 * Supervisor) added 2026-11-16. Jacqueline's and Shayna-Rae's
 * photos were replaced the same day with versions the client edited (their
 * ID badge set, 1080x1350, kept as the underscore-named originals). The web
 * versions are cropped and resized only, framed by measured face size to
 * match the rest of the team: face about 48% of the width, its top about
 * 22% down, centred. Hani Ahmed (formerly Care Manager),
 * Zoe Commons (formerly Domiciliary Care Manager) and Heidi Griffen (formerly
 * Field Care Supervisor, left Nov 2026) have left CCS and must not
 * appear anywhere on the site. Trish Henley and Jennifer Boorman are still staff
 * but are deliberately not featured on the public team page.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Team members shown on the public team page, in display order.
 *
 * `photo` is a filename in assets/images/team/ — square-cropped (512×512)
 * web versions of the raw uploads. Crop only — no brightness/colour
 * correction, per Ellie's instruction (2026-08-20).
 *
 * Members without a photo fall back to an initials tile rather than an empty slot.
 *
 * Nov 2026: each member also has a profile page (client, after the
 * ElderlyCare X team member page). 'intro' is the line under their name
 * there, written only from facts the site already states (their role;
 * that Nikki and Keelie are named on the consultation form; that Victoria
 * tells the company's story in the video). 'video' is a YouTube ID. 'bio'
 * is the person's own words, as published on the client's current site
 * (continuitycareservices.co.uk/home/who-youll-meet/, Nov 2026), for the
 * four who have one there; it fills the profile page's editor once, and
 * from then on the editor is where it is changed. A profile with an empty
 * editor hides its "About" section and stays out of search.
 *
 * @return array<int, array{name: string, role: string, photo: string, slug: string, intro: string, video?: string}>
 */
function ccs_team_members() {
	$members = array(
		array(
			'name'  => 'Victoria Walker',
			'role'  => __( 'Registered Manager', 'ccs-wp-theme' ),
			'photo' => 'victoria-walker.jpg',
			'intro' => __( 'Victoria is our Registered Manager and Company Director. In the video below she tells the story of how Continuity of Care started here in Kent, and what it set out to do differently.', 'ccs-wp-theme' ),
			'video' => '7RJ-pdNh65o',
			'bio'   => '<p>My name is Victoria Walker, I am the Registered Manager and Company Director of Continuity of Care Services. I fell into care in 2008 and have never looked back.</p><p>I have previous experience as a Registered Manager and Director working for another company, but set up Continuity in April 2016, taking with me my ethos and the need to drive forward a change in the care industry.</p>',
		),
		array(
			'name'  => 'Amanda Carter',
			'role'  => __( 'General Manager', 'ccs-wp-theme' ),
			'photo' => 'amanda-carter.jpg',
			'intro' => __( 'Amanda is our General Manager.', 'ccs-wp-theme' ),
		),
		array(
			'name'  => 'Nikki Mackay',
			'role'  => __( 'Senior Clinical Manager', 'ccs-wp-theme' ),
			'photo' => 'nikki-mackay.jpg',
			'intro' => __( 'Nikki is our Senior Clinical Manager. When you book a free consultation, you can ask to speak with her.', 'ccs-wp-theme' ),
			'bio'   => '<p>My name is Nikki, and I am the Senior Clinical Manager at Continuity Care Services, having joined in April 2023.</p><p>I began my care career in Residential Services in 1995 and qualified as a Registered Learning Disability Nurse in 2000, initially working within a challenging behaviour service. After completing the THORN degree in Mental Health, I became a Community Learning Disability Nurse in 2005, supporting individuals with health needs while they lived independently or in residential settings. Between 2012 and 2015, I worked as a Health Visitor before returning to support people with learning disabilities and complex needs in the Continuing Health Care team.</p><p>At CCS, I am focused on developing our new Nursing service, including funding Nursing Associates to support complex clients in the community. I work closely with the Care Manager and teams to ensure an excellent standard of care for those we support.</p>',
		),
		array(
			'name'  => 'Keelie Varney',
			'role'  => __( 'Care Manager', 'ccs-wp-theme' ),
			'photo' => 'keelie-varney.jpg',
			'intro' => __( 'Keelie is our Care Manager. When you book a free consultation, you can ask to speak with her.', 'ccs-wp-theme' ),
			'bio'   => '<p>Hello, my name is Keelie, and I am the Care Manager at CCS. I began my journey with CCS in May 2023 as a Head Carer and have over 20 years of experience in the care sector. After briefly working in sales management, I returned to care, where I find true job satisfaction.</p><p>My experience spans dementia care in nursing homes, home care for older people, and complex care for individuals with brain injuries and challenging behaviours. I have completed NVQs to progress from Care Worker to Senior level, and am now Care Manager. I thrive on supporting both clients and staff, ensuring person-centred care.</p><p>I collaborate closely with Nikki, our Senior Clinical Manager, in developing tailored care plans and to ensure that families meet carers before services begin. These are digitised on our app for ease of use and accessibility for both families and staff. I’m also proud to champion skin integrity within the company.</p><p>One of my key achievements has been transitioning CCS from paper-based daily logs to a digital system, modernising our processes. I have since completed my Level 5 Diploma in Leadership for Health and Social Care with a Distinction, and am now working towards becoming a Registered Manager.</p>',
			// Her progression, in her own words (her email of 23 Nov 2026, unedited): shown as "My journey" on Professional Development.
			'journey' => <<<'JOURNEY'
I joined Continuity of Care Services in May 2023 as a Head Carer for a complex care package. Although the package already had an established team in place, CCS recognised my Level 3 qualification and experience in complex care and quickly identified other opportunities for me within the organisation. This led to me progressing into a Senior Head of Complex Support role, where I supported our Head Carers to lead their teams effectively, acted as a key point of contact between field staff, families and management, and contributed to reviewing and updating care plans. Working closely with the people we support allowed me to bring a more person-centred perspective to care planning and service delivery.

After a year with CCS, I was promoted to Deputy Manager. From the beginning, I was keen to progress my career, and I felt that CCS's person-centred approach aligned with my own values and aspirations. The support I received from the organisation played a significant part in my development. While working as Deputy Manager, I was enrolled onto the Level 5 Diploma in Leadership for Health and Social Care. I was proud to achieve a Distinction, which further strengthened my knowledge, confidence and leadership skills. I have since progressed to the role of Care Manager and am currently working towards becoming a Registered Manager.

For anyone considering a career in care, my advice would be to embrace every opportunity to learn and gain experience. Care is an incredibly rewarding profession where no two days are the same, and building strong relationships with the people you support is at the heart of everything you do. Stay open to feedback, never stop learning, and remember that progression is possible if you are willing to work hard and remain committed to providing high-quality, person-centred care. With the right support and determination, there are many opportunities to develop and grow within the sector.
JOURNEY
			,
		),
		array(
			'name'  => 'Shayna-Rae Fuller',
			'role'  => __( 'Field Care Supervisor', 'ccs-wp-theme' ),
			'photo' => 'shayna-rae-fuller.jpg',
			'intro' => __( 'Shayna-Rae is one of our Field Care Supervisors.', 'ccs-wp-theme' ),
		),
		array(
			'name'  => 'Jacqueline Ellis',
			'role'  => __( 'Field Care Supervisor', 'ccs-wp-theme' ),
			'photo' => 'jacqueline-ellis.jpg',
			'intro' => __( 'Jacqueline is one of our Field Care Supervisors.', 'ccs-wp-theme' ),
		),
		array(
			'name'  => 'Magdalena Zoledz',
			'role'  => __( 'Recruitment Coordinator', 'ccs-wp-theme' ),
			'photo' => 'magdalena-zoledz.jpg',
			'intro' => __( 'Magdalena is our Recruitment Coordinator.', 'ccs-wp-theme' ),
			'bio'   => '<p>Hi, my name is Magdalena and I joined CCS team in March 2025 as their Recruitment Coordinator. I am a dedicated recruitment and compliance officer, with over 16 years experience in the healthcare industry. Throughout this time I have worked in various roles, including recruitment, compliance, and the HR department. I’m always on the look out for ways to make the recruitment process smoother for everyone.</p><p>I feel it’s important for a company’s recruitment process to be simple, straight forward and exciting for all involved, candidates, hiring managers, clients, and recruitment teams alike. I look forward to supporting all applicants through the full- cycle recruitment process and will do my best to make this a seamless experience. Outside of work, you can normally find me adventuring, through travelling or participation in outdoor activities!</p>',
		),
	);

	foreach ( $members as $i => $member ) {
		$members[ $i ]['slug'] = sanitize_title( $member['name'] );
	}

	/**
	 * Filter the public team roster.
	 *
	 * @param array $members Team members.
	 */
	return apply_filters( 'ccs_team_members', $members );
}

/**
 * A team member by profile slug.
 *
 * @param string $slug Slug, e.g. "victoria-walker".
 * @return array|null
 */
function ccs_team_member_by_slug( $slug ) {
	foreach ( ccs_team_members() as $member ) {
		if ( $member['slug'] === $slug ) {
			return $member;
		}
	}
	return null;
}

/**
 * The published profile page for a team member, if there is one.
 *
 * @param string $slug  Member slug.
 * @param bool   $fresh Re-read the pages (after creating them).
 * @return WP_Post|null
 */
function ccs_team_profile_page( $slug, $fresh = false ) {
	static $cache = null;
	if ( null === $cache || $fresh ) {
		$cache = array();
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'meta_key'       => 'ccs_team_member', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- a handful of pages.
			)
		);
		foreach ( $pages as $page ) {
			$cache[ (string) get_post_meta( $page->ID, 'ccs_team_member', true ) ] = $page;
		}
	}
	return isset( $cache[ $slug ] ) ? $cache[ $slug ] : null;
}

/**
 * Link to a team member's profile, or '' if it has none.
 *
 * @param array $member Member from ccs_team_members().
 * @return string
 */
function ccs_team_profile_url( $member ) {
	$page = empty( $member['slug'] ) ? null : ccs_team_profile_page( $member['slug'] );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Whether a profile page has the person's own words yet (the editor).
 *
 * @param int $page_id Page ID.
 * @return bool
 */
function ccs_team_profile_has_bio( $page_id ) {
	return '' !== trim( wp_strip_all_tags( (string) get_post_field( 'post_content', $page_id ) ) );
}

/**
 * Create a profile page for each member once, as a child of the team page,
 * so it has a normal URL (/who-youll-meet/victoria-walker/), breadcrumb and
 * editor. The editor is left empty for the person's own words.
 */
function ccs_create_team_profiles_2026_11() {
	/*
	 * Each member's page is created once: the slugs already handled are
	 * recorded, so someone added to the roster later gets a page, but a page
	 * the client deletes is never put back.
	 */
	$done    = get_option( 'ccs_team_profiles_done', null );
	$members = ccs_team_members();
	if ( null === $done ) {
		// Sites from before the record: everyone then on the roster was handled.
		$done = get_option( 'ccs_team_profiles_2026_11' ) ? array_diff( wp_list_pluck( $members, 'slug' ), array( 'jacqueline-ellis' ) ) : array();
	}
	$todo = array_diff( wp_list_pluck( $members, 'slug' ), (array) $done );
	if ( ! $todo ) {
		if ( false === get_option( 'ccs_team_profiles_done' ) ) {
			update_option( 'ccs_team_profiles_done', array_values( (array) $done ), false );
		}
		return;
	}
	$parent = get_page_by_path( 'who-youll-meet' );
	if ( ! $parent instanceof WP_Post || 'publish' !== $parent->post_status ) {
		return;
	}
	update_option( 'ccs_team_profiles_done', array_values( array_unique( array_merge( (array) $done, $todo ) ) ), false );
	update_option( 'ccs_team_profiles_2026_11', '1', false );
	foreach ( $members as $i => $member ) {
		if ( ! in_array( $member['slug'], $todo, true ) || ccs_team_profile_page( $member['slug'] ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_parent'  => $parent->ID,
				'post_title'   => $member['name'],
				'post_name'    => $member['slug'],
				'post_content' => isset( $member['bio'] ) ? $member['bio'] : '',
				'menu_order'   => $i,
				'meta_input'   => array(
					'_wp_page_template' => 'page-templates/template-team-member.php',
					'ccs_team_member'   => $member['slug'],
				),
			)
		);
	}
	ccs_team_profile_page( '', true );
}
add_action( 'init', 'ccs_create_team_profiles_2026_11', 45 );

/**
 * Victoria's title in her own bio (Nov 2026, confirmed by Ellie): Registered
 * Manager and Company Director, as on the rest of the site. The bio copied
 * from the old website said "Managing Director and Registered Manager". Runs
 * once, and changes only that phrase where the page still holds it, so
 * anything edited since is left alone.
 */
function ccs_fix_victoria_title_2026_11() {
	if ( get_option( 'ccs_victoria_title_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	$page = ccs_team_profile_page( 'victoria-walker' );
	if ( ! $page ) {
		return;
	}
	update_option( 'ccs_victoria_title_2026_11', '1', false );
	$old = 'I am the Managing Director and Registered Manager of Continuity of Care Services.';
	$new = 'I am the Registered Manager and Company Director of Continuity of Care Services.';
	if ( false !== strpos( $page->post_content, $old ) ) {
		wp_update_post(
			wp_slash(
				array(
					'ID'           => $page->ID,
					'post_content' => str_replace( $old, $new, $page->post_content ),
				)
			)
		);
	}
}
add_action( 'init', 'ccs_fix_victoria_title_2026_11', 47 );

/**
 * Keelie's Level 5 (her email of 23 Nov 2026): passed with a Distinction, so
 * her bio no longer says she is working towards it. On a site where her
 * profile already exists, that one sentence is replaced once, and only where
 * the page still holds it.
 */
function ccs_fix_keelie_level5_2026_11() {
	if ( get_option( 'ccs_keelie_level5_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	$page = ccs_team_profile_page( 'keelie-varney' );
	if ( ! $page ) {
		return;
	}
	update_option( 'ccs_keelie_level5_2026_11', '1', false );
	$old = 'Currently, I am working towards my Level 5 qualification – a testament to my dedication and commitment to providing exceptional care and support.';
	$new = 'I have since completed my Level 5 Diploma in Leadership for Health and Social Care with a Distinction, and am now working towards becoming a Registered Manager.';
	if ( false !== strpos( $page->post_content, $old ) ) {
		wp_update_post(
			wp_slash(
				array(
					'ID'           => $page->ID,
					'post_content' => str_replace( $old, $new, $page->post_content ),
				)
			)
		);
	}
}
add_action( 'init', 'ccs_fix_keelie_level5_2026_11', 47 );

/**
 * Nikki is Senior Clinical Manager (confirmed by Victoria Walker, 24 Nov
 * 2026). Her own bio and Keelie's both said "Clinical Manager", copied from
 * the old website. On a site where the profiles already exist, the two
 * phrases are corrected once, and only where the page still holds them.
 */
function ccs_fix_nikki_title_2026_11() {
	if ( get_option( 'ccs_nikki_title_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	$swaps = array(
		'nikki-mackay'  => array( 'I am the Clinical Manager at Continuity Care Services', 'I am the Senior Clinical Manager at Continuity Care Services' ),
		'keelie-varney' => array( 'Nikki, our Clinical Manager,', 'Nikki, our Senior Clinical Manager,' ),
	);
	$seen  = 0;
	foreach ( $swaps as $slug => $swap ) {
		$page = ccs_team_profile_page( $slug );
		if ( ! $page ) {
			continue;
		}
		++$seen;
		if ( false !== strpos( $page->post_content, $swap[0] ) ) {
			wp_update_post(
				wp_slash(
					array(
						'ID'           => $page->ID,
						'post_content' => str_replace( $swap[0], $swap[1], $page->post_content ),
					)
				)
			);
		}
	}
	if ( count( $swaps ) === $seen ) {
		update_option( 'ccs_nikki_title_2026_11', '1', false );
	}
}
add_action( 'init', 'ccs_fix_nikki_title_2026_11', 47 );

/**
 * Keelie joined as a Head Carer, not a Senior Support Worker (Ellie, 25 Nov
 * 2026, matching Keelie's own account of her journey). Her profile page was
 * created from the old bio, so correct the wording there once, only where
 * the old sentence is still in place.
 */
function ccs_fix_keelie_start_2026_11() {
	if ( get_option( 'ccs_keelie_start_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	$page = ccs_team_profile_page( 'keelie-varney' );
	if ( ! $page ) {
		return;
	}
	$old = 'in May 2023 as a Senior Support Worker and have';
	if ( false !== strpos( $page->post_content, $old ) ) {
		wp_update_post(
			wp_slash(
				array(
					'ID'           => $page->ID,
					'post_content' => str_replace( $old, 'in May 2023 as a Head Carer and have', $page->post_content ),
				)
			)
		);
	}
	update_option( 'ccs_keelie_start_2026_11', '1', false );
}
add_action( 'init', 'ccs_fix_keelie_start_2026_11', 47 );

/**
 * Heidi Griffen left in Nov 2026 (Ellie, 23 Nov 2026). She is off the roster
 * above; on a site where her profile page was already created, move it to
 * the bin once (it can be restored from there, and is never recreated,
 * because her slug stays in the record of pages already handled).
 */
function ccs_remove_left_profile_2026_11() {
	if ( get_option( 'ccs_left_profile_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	update_option( 'ccs_left_profile_2026_11', '1', false );
	$page = get_page_by_path( 'who-youll-meet/heidi-griffen' );
	if ( $page instanceof WP_Post && 'heidi-griffen' === get_post_meta( $page->ID, 'ccs_team_member', true ) ) {
		wp_trash_post( $page->ID );
		ccs_team_profile_page( '', true );
	}
}
add_action( 'init', 'ccs_remove_left_profile_2026_11', 48 );

/**
 * Fill the editor of profiles created before the bios were added, once,
 * and only where it is still empty.
 */
function ccs_fill_team_bios_2026_11() {
	if ( get_option( 'ccs_team_bios_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_team_bios_2026_11', '1', '', 'no' ) ) {
		return;
	}
	foreach ( ccs_team_members() as $member ) {
		$page = empty( $member['bio'] ) ? null : ccs_team_profile_page( $member['slug'] );
		if ( $page && ! ccs_team_profile_has_bio( $page->ID ) ) {
			wp_update_post(
				wp_slash(
					array(
						'ID'           => $page->ID,
						'post_content' => $member['bio'],
					)
				)
			);
		}
	}
}
add_action( 'init', 'ccs_fill_team_bios_2026_11', 46 );

/**
 * A profile without the person's own words is kept out of search (and the
 * sitemap) until it has them: a name, a role and a photo alone would read
 * as a thin page.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function ccs_team_profile_robots( $robots ) {
	if ( is_page() && get_post_meta( get_queried_object_id(), 'ccs_team_member', true ) && ! ccs_team_profile_has_bio( get_queried_object_id() ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ccs_team_profile_robots' );

/**
 * Leave profiles without a bio out of the sitemap.
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function ccs_team_profile_sitemap( $args, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$skip = array();
	foreach ( ccs_team_members() as $member ) {
		$page = ccs_team_profile_page( $member['slug'] );
		if ( $page && ! ccs_team_profile_has_bio( $page->ID ) ) {
			$skip[] = $page->ID;
		}
	}
	if ( $skip ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $skip );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'ccs_team_profile_sitemap', 10, 2 );
