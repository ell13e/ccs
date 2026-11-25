<?php
/**
 * Keelie's article: "Why care? Keelie's story" (Nov 2026).
 *
 * Keelie Varney, Care Manager, wrote a talk on why she chose care. Ellie
 * sent it on 25 Nov 2026 and asked for it as an article under From the team,
 * linked from the Careers page and Keelie's profile. Her words are as she
 * wrote them; only the greeting and "Thank you." of the talk are left off,
 * and a space before a comma is closed. The opening and closing lines are
 * ours and say nothing she did not.
 *
 * Created once (add_option is the lock), never re-created if the client
 * deletes it. The two links are added once each, only where they are not
 * already, and are ordinary text the client can edit.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CCS_KEELIE_ARTICLE_SLUG = 'why-care-keelies-story';

/**
 * The article's text.
 *
 * @return string HTML.
 */
function ccs_keelie_article_content() {
	$profile   = esc_url( home_url( '/who-youll-meet/keelie-varney/' ) );
	$vacancies = esc_url( home_url( '/careers/current-vacancies/' ) );
	$training  = esc_url( home_url( '/careers/professional-development/' ) );

	return '<p>Keelie Varney is our <a href="' . $profile . '">Care Manager</a>. She joined us as a Head Carer in 2023. These are her words on why care is worth choosing.</p>
<p>When people hear the word “care,” they often picture one thing: wiping bums and making tea. And yes, sometimes those things are part of the job. But care, especially complex care, is so much more than that.</p>
<p>Care is rehabilitation.<br>
Care is helping somebody rebuild their life after it’s been completely turned upside down by an accident, illness, or disability.<br>
It’s being there for someone at their lowest point and helping them either get back to the life they had, or helping them discover a new future they never thought was possible.</p>
<p>You become part of someone’s journey. You help people regain confidence, independence, and dignity. Some days you support someone to take their first steps again. Other days you help them simply believe in themselves again. That matters.</p>
<p>And honestly, I never planned for this career.</p>
<p>At school, I didn’t do particularly well. I was far more interested in being the class clown than listening in lessons. I became a mum at 18, and I needed a job that would help me provide for my baby. Care was accessible, I didn’t need any qualifications, so I thought, “That’ll do.”</p>
<p>But very quickly, I realised something: this wasn’t just a job for me. Care was my calling.</p>
<p>I realised I genuinely wanted to help people. And then I wanted more than that, I wanted to support the staff caring for those people too.</p>
<p>So, in my 30s, I sat my Maths and English GCSEs. I’m not going to lie, that was hard. And despite all the arguments I used to have with my maths teacher at school, it turns out… I actually did need maths to get somewhere!</p>
<p>After passing those exams, I completed my Level 3 qualifications to become a senior member of staff, supporting and leading others. Then I went on to complete my Level 5 Diploma because I wanted to keep learning and growing.</p>
<p>What started as “an easy job” became my passion and my career.</p>
<p>So if there’s one thing I’d like you to take away today, it’s this:</p>
<p>Care isn’t “just” care.<br>
It’s life-changing.<br>
It’s meaningful.<br>
It’s skilled.<br>
And it gives you the opportunity to make a genuine difference to another human being every single day.</p>
<p>Care is about helping people live their lives to the fullest, and sometimes, helping them believe they still can.</p>
<p>Thinking about a career in care? See our <a href="' . $vacancies . '">current vacancies</a>, or read about <a href="' . $training . '">the training we fund</a>.</p>';
}

/**
 * Publish the article once, after the team's profile pages exist.
 *
 * @return void
 */
function ccs_ensure_keelie_article_2026_11() {
	if ( get_option( 'ccs_keelie_article_2026_11' ) || ! get_option( 'ccs_team_profiles_2026_11' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_keelie_article_2026_11', '1', '', 'no' ) ) {
		return;
	}
	if ( get_posts( array( 'name' => CCS_KEELIE_ARTICLE_SLUG, 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		return;
	}
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
	$id     = wp_insert_post(
		array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_author'  => $admins ? (int) $admins[0]->ID : 1,
			'post_title'   => 'Why care? Keelie’s story',
			'post_name'    => CCS_KEELIE_ARTICLE_SLUG,
			'post_excerpt' => 'What started as “an easy job” became her passion and her career. Our Care Manager on why care is so much more than people think.',
			'post_content' => ccs_keelie_article_content(),
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return;
	}
	$term = get_term_by( 'slug', 'from-the-team', 'category' );
	if ( $term instanceof WP_Term ) {
		wp_set_post_categories( $id, array( (int) $term->term_id ), false );
	}
}
add_action( 'init', 'ccs_ensure_keelie_article_2026_11', 50 );

/**
 * The article's photo: the team at the office, a wide photo for the wide
 * frame at the top of an article. Keelie's square headshot was cropped to
 * her face there. Set once; a photo the client picks later is kept.
 *
 * @return void
 */
function ccs_keelie_article_photo_2026_11() {
	if ( get_option( 'ccs_keelie_article_photo_2026_11' ) || ! function_exists( 'ccs_articles_import_photo' ) ) {
		return;
	}
	$article = get_posts( array( 'name' => CCS_KEELIE_ARTICLE_SLUG, 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1 ) );
	if ( ! $article ) {
		return;
	}
	$current = (int) get_post_thumbnail_id( $article[0] );
	if ( ! $current || 'assets/images/team/keelie-varney.jpg' === get_post_meta( $current, '_ccs_source_theme_path', true ) ) {
		$photo = ccs_articles_import_photo( 'assets/images/site-photos-extra/team-group-office-window.webp', 'The Continuity Care Services team at the office' );
		if ( $photo ) {
			set_post_thumbnail( $article[0]->ID, $photo );
		}
	}
	update_option( 'ccs_keelie_article_photo_2026_11', '1', false );
}
add_action( 'init', 'ccs_keelie_article_photo_2026_11', 52 );

/**
 * Link the article from Keelie's profile and the Careers page, once each.
 *
 * @return void
 */
function ccs_link_keelie_article_2026_11() {
	if ( get_option( 'ccs_keelie_article_links_2026_11' ) || ! get_option( 'ccs_keelie_article_2026_11' ) ) {
		return;
	}
	$article = get_posts( array( 'name' => CCS_KEELIE_ARTICLE_SLUG, 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 1 ) );
	$profile = function_exists( 'ccs_team_profile_page' ) ? ccs_team_profile_page( 'keelie-varney' ) : null;
	$careers = get_page_by_path( 'careers' );
	if ( ! $article || ! $profile || ! $careers ) {
		return;
	}
	$url   = get_permalink( $article[0] );
	$lines = array(
		array( $profile, '<p>Read <a href="' . esc_url( $url ) . '">Keelie’s talk on why she chose care</a>.</p>' ),
		array( $careers, '<p>Not sure care is for you? Read <a href="' . esc_url( $url ) . '">our Care Manager Keelie’s story</a>.</p>' ),
	);
	foreach ( $lines as $line ) {
		if ( false === strpos( $line[0]->post_content, CCS_KEELIE_ARTICLE_SLUG ) ) {
			wp_update_post( wp_slash( array( 'ID' => $line[0]->ID, 'post_content' => rtrim( $line[0]->post_content ) . "\n\n" . $line[1] ) ) );
		}
	}
	update_option( 'ccs_keelie_article_links_2026_11', '1', false );
}
add_action( 'init', 'ccs_link_keelie_article_2026_11', 51 );
