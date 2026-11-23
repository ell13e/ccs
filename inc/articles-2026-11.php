<?php
/**
 * Question articles (Nov 2026, SEO plan items 1 and 2).
 *
 * One page per question families search for that the site did not yet
 * answer on its own page (the FAQs answer council funding and NHS
 * Continuing Healthcare in a few lines; the existing articles cover the
 * signs, the assessment visit and the three ways care is paid for). Each
 * links to the services, costs and contact pages, so search visitors land
 * one step from an enquiry.
 *
 * Two kinds of sentence, as in inc/service-guides.php:
 * - how care and funding work in England, true of any provider, each with
 *   the NHS, GOV.UK or Kent County Council page that says so;
 * - what Continuity of Care Services does, taken only from what the site
 *   already says (Getting started, the costs page, the FAQs). Nothing new
 *   is claimed about the company. "We will do our best to have care ready"
 *   is the client's own wording (copy update 11k).
 *
 * The costs article works through the published rates (1 September 2026
 * to 31 August 2027, CCS_Theme_Customizer::default_fee_schedule()). Its
 * figures are typed in, not read from the Customizer: when the rates change
 * in September 2027, update the article or take it down.
 *
 * Created once (add_option is the lock), published, never re-created if the
 * client deletes one. The client should read them before launch.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The articles, slug => data.
 *
 * @return array<string, array<string, string>>
 */
function ccs_articles_2026_11() {
	$svc     = static function ( $slug, $text ) {
		return '<a href="' . esc_url( home_url( '/services/' . $slug . '/' ) ) . '">' . $text . '</a>';
	};
	$costs   = esc_url( home_url( '/costs-and-funding/' ) );
	$contact = esc_url( home_url( '/contact-us/' ) );
	$start   = esc_url( home_url( '/getting-started/' ) );
	$faqs    = esc_url( home_url( '/resources/faqs/' ) );
	$ext     = static function ( $url, $text ) {
		return '<a href="' . esc_url( $url ) . '">' . $text . '</a>';
	};

	return array(
		'home-care-or-a-care-home'           => array(
			'title'    => 'Home care or a care home? How families decide',
			'category' => 'care-advice',
			'image'    => 'assets/images/site-photos/ccs-respite-care-guitar-1.webp',
			'excerpt'  => 'The choice is rarely as simple as home or a home. It turns on what someone needs now, what they want to keep, and whether home can be made to work.',
			'content'  => '<p>Families often come to us with the question already framed as home or a care home. In practice it is usually a different question: what does this person need now, what do they want to keep, and can that happen safely at home?</p>
<h2>What care at home can cover</h2>
<p>Care at home is not only a short visit. It can be a few hours a week, ' . $svc( 'overnight-care', 'someone there overnight' ) . ', ' . $svc( 'continuous-care', 'care around the clock' ) . ', or ' . $svc( 'complex-care', 'complex care' ) . ' for conditions that need clinical skills. It can also be ' . $svc( 'respite-care', 'respite care' ) . ', so the family member doing most of the caring gets a break.</p>
<p>The person stays in their own home, with their own routine, neighbours, garden and things around them. For many people that is the whole point.</p>
<h2>When a care home may suit better</h2>
<ul>
<li><strong>Nursing needed at any hour.</strong> A nursing home has registered nurses on duty day and night. Some needs are easier to meet there.</li>
<li><strong>Loneliness rather than tasks.</strong> Some people want company more than help, and a home with others around them can suit them better than visits.</li>
<li><strong>A home that cannot be adapted.</strong> Stairs, bathrooms and access can usually be worked around, but not always.</li>
</ul>
<h2>Questions worth asking</h2>
<ul>
<li>What does the person themselves want? If they have the capacity to decide, it is their decision.</li>
<li>Could equipment or adaptations make home safer? The council can ' . $ext( 'https://www.gov.uk/apply-needs-assessment-social-services', 'assess what would help' ) . ', and the assessment is free.</li>
<li>Who is doing the caring now, and for how much longer can they? A ' . $ext( 'https://www.nhs.uk/social-care-and-support/support-and-benefits-for-carers/carer-assessments/', 'carer’s assessment' ) . ' looks at their needs too.</li>
<li>How will it be paid for? For care at home, the home you live in is not counted in the council’s financial assessment. For a permanent move into a care home it can be. ' . $ext( 'https://www.kent.gov.uk/social-care-and-health/adult-social-care/paying-for-care-and-support/how-much-you-will-pay-for-care-and-support', 'Kent County Council explains the rules' ) . '.</li>
</ul>
<h2>Talking it through</h2>
<p>Not sure which kind of care you are looking at? <a href="' . esc_url( home_url( '/resources/which-care/' ) ) . '">Answer four short questions</a> to see which kinds fit.</p>
<p>A free consultation with us is a conversation about what is happening and what would help. If we are not the right fit, perhaps because a nursing home would serve someone better, we will say so and point you to a more suitable service. <a href="' . $start . '">Here is how getting started works</a>, or <a href="' . $contact . '">get in touch</a>.</p>',
		),
		'coming-home-from-hospital-kent'     => array(
			'title'    => 'Coming home from hospital: arranging care in Kent',
			'category' => 'getting-started',
			'image'    => 'assets/images/site-photos/ccs-complex-care-maidstone-24-7.webp',
			'excerpt'  => 'Short-term help after a hospital stay is often free for up to six weeks. What to ask before discharge, and how longer-term care is arranged.',
			'content'  => '<p>A hospital discharge can come round quickly. If someone you love is likely to need help when they get home, the best time to start asking questions is while they are still on the ward.</p>
<h2>Ask the ward about the discharge plan</h2>
<p>Hospitals have staff whose job is to plan discharges. Ask who is arranging it, what help they expect will be needed at home, and when the likely date is. You can ask to be involved in the planning.</p>
<h2>Short-term help is often free</h2>
<p>After a hospital stay, a fall, an injury or an illness, the council or the NHS may arrange short-term support to help someone get back on their feet. It is sometimes called reablement or intermediate care, and in England it is free for up to six weeks. ' . $ext( 'https://www.nhs.uk/social-care-and-support/care-after-a-hospital-stay/care-to-support-recovery-after-leaving-hospital-or-after-a-fall-injury-or-illness/', 'The NHS explains it here' ) . '. Ask whether it is being arranged.</p>
<h2>If care will be needed for longer</h2>
<ul>
<li>A ' . $ext( 'https://www.kent.gov.uk/social-care-and-health/adult-social-care/care-and-support/how-to-get-adult-social-care-support/care-needs-assessments', 'care needs assessment' ) . ' from the council looks at what support is needed. It is free, including for people who expect to pay for their own care.</li>
<li>Someone with complex, ongoing health needs may be eligible for NHS Continuing Healthcare. <a href="' . esc_url( home_url( '/nhs-continuing-healthcare-explained/' ) ) . '">How that assessment works</a>.</li>
<li>You can also arrange care directly with a provider and pay for it yourself. That is often the quickest route.</li>
</ul>
<h2>Before the day itself</h2>
<ul>
<li>Medicines: what is new, what has stopped, and who will manage them.</li>
<li>Equipment: whether a bed, frame, commode or rails will be in place before they arrive.</li>
<li>Getting in: keys, or a key safe, for anyone visiting to help.</li>
<li>The first evening: food in the fridge, the heating on, and someone to ring.</li>
</ul>
<h2>Arranging care with us</h2>
<p>If care is being arranged after a hospital stay, tell us the likely discharge date when you first call. We will do our best to have care ready for the day someone comes home. The first call and the assessment visit are both free. <a href="' . $start . '">How getting started works</a>, or <a href="' . $contact . '">get in touch</a>.</p>',
		),
		'nhs-continuing-healthcare-explained' => array(
			'title'    => 'NHS Continuing Healthcare: how it works and how to ask for an assessment',
			'category' => 'funding-costs',
			'image'    => 'assets/images/site-photos-extra/ccs-complex-care-wheelchair.webp',
			'excerpt'  => 'NHS Continuing Healthcare can pay for care at home in full and is not means-tested. Who it is for, how the assessment works, and what happens if the answer is no.',
			'content'  => '<p>NHS Continuing Healthcare is care arranged and paid for by the NHS for adults whose main need is a health need. It can be provided at home. It is not means-tested, so savings and income make no difference to whether someone qualifies.</p>
<h2>Who it is for</h2>
<p>It is for adults with a “primary health need”: needs that are mainly about health rather than everyday social care. Eligibility depends on what someone needs and how complex, intense or unpredictable those needs are, not on a particular diagnosis.</p>
<h2>How the assessment works</h2>
<ol>
<li><strong>The checklist.</strong> A nurse, doctor, social worker or other professional fills in a short screening checklist. Ask a GP, district nurse, social worker or the hospital team whether one should be done.</li>
<li><strong>The full assessment.</strong> If the checklist suggests someone may be eligible, a team of professionals carries out a full assessment, looking at needs such as breathing, nutrition, mobility, medication and behaviour. The person and their family can take part.</li>
<li><strong>The decision.</strong> The local NHS decides on eligibility. In Kent and Medway that is NHS Kent and Medway.</li>
</ol>
<p>For someone whose condition is getting worse quickly and who may be nearing the end of life, there is a fast track that can put care in place without the full process.</p>
<h2>If someone is eligible</h2>
<p>The NHS arranges and pays for the care package. Someone living at home can ask for a personal health budget, which gives more say over how the care is provided and by whom. Eligibility is usually reviewed after three months and then at least once a year.</p>
<h2>If the answer is no</h2>
<p>You can ask the NHS to review the decision. Someone who is not eligible may still get help from the council after a ' . $ext( 'https://www.gov.uk/apply-needs-assessment-social-services', 'needs assessment' ) . ', or a package funded jointly by the council and the NHS.</p>
<p>' . $ext( 'https://www.nhs.uk/social-care-and-support/money-work-and-benefits/nhs-continuing-healthcare/', 'Read the NHS guide to Continuing Healthcare' ) . '.</p>
<h2>Where we come in</h2>
<p>Where the NHS pays for care at home, we can be the provider. Our ' . $svc( 'complex-care', 'complex care' ) . ' team works alongside case managers, healthcare professionals and families. <a href="' . $costs . '">What care costs and ways to pay</a>, or <a href="' . $contact . '">talk to us</a>.</p>',
		),
		'what-home-care-costs-kent-examples' => array(
			'title'    => 'What home care costs in Kent: worked examples from our rates',
			'category' => 'funding-costs',
			'image'    => 'assets/images/site-photos-extra/team-group-office-window.webp',
			'excerpt'  => 'An hourly rate is hard to picture. Here is what a week of care comes to in five common patterns, worked out from our published rates.',
			'content'  => '<p>An hourly rate on its own is hard to picture. So here are five common patterns of care, worked out from <a href="' . $costs . '">our published rates</a> for 1 September 2026 to 31 August 2027.</p>
<p>These are “from” prices, before the £30 one-off fee for setting up a care package. They are not a quote: after a free consultation we give you a personalised quote for the care you need.</p>
<h2>An hour every morning, seven days a week</h2>
<p>Help getting up, washed, dressed and breakfasted. Five weekday hours at £36.38 and two weekend hours at £43.31.</p>
<p><strong>From £268.52 a week.</strong></p>
<h2>Three hours every weekday afternoon</h2>
<p>' . $svc( 'respite-care', 'Respite care' ) . ' so a family carer can get out, rest or get things done. Fifteen weekday hours at £36.38.</p>
<p><strong>From £545.70 a week.</strong></p>
<h2>A weekend away for the family carer</h2>
<p>A carer from 8am to 10pm on Saturday and Sunday, with a sleep-in night in between. Twenty-eight weekend hours at £43.31 and one sleep-in night at £242.49.</p>
<p><strong>From £1,455.17 for the weekend.</strong></p>
<h2>Someone awake through every night for a week</h2>
<p>A ' . $svc( 'overnight-care', 'waking night' ) . ' carer, for someone who needs help during the night. Seven waking nights at £323.00.</p>
<p><strong>From £2,261.00 a week.</strong></p>
<h2>A day of complex care</h2>
<p>' . $svc( 'complex-care', 'Complex care' ) . ' from carers trained for conditions such as epilepsy, spinal injury or PEG feeding. Eight weekday hours at £40.43.</p>
<p><strong>From £323.44 a day.</strong></p>
<h2>What else can change the total</h2>
<ul>
<li>Bank holidays are charged at double rate.</li>
<li>Christmas Day, Boxing Day, New Year’s Day and New Year’s Eve after 7pm are charged at treble rate.</li>
<li>Escort visits are 50p a mile, plus parking.</li>
</ul>
<h2>Help with paying</h2>
<p>Many families pay for care themselves. Some get help from the council, through direct payments, or from the NHS. <a href="' . esc_url( home_url( '/paying-for-home-care-in-kent/' ) ) . '">The three ways care is paid for</a> and <a href="' . $faqs . '">our funding questions</a> explain more, or <a href="' . $contact . '">ask us for a quote</a>.</p>',
		),
	);
}

/**
 * Import a theme photo into the Media Library once (found again by its
 * theme path, never duplicated).
 *
 * @param string $rel   Path inside the theme.
 * @param string $title Attachment title.
 * @return int Attachment ID, or 0.
 */
function ccs_articles_import_photo( $rel, $title ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_ccs_source_theme_path', 'meta_value' => $rel, 'fields' => 'ids' ) );
	if ( $existing ) {
		return (int) $existing[0];
	}
	$source = get_template_directory() . '/' . $rel;
	$upload = wp_upload_dir();
	if ( ! file_exists( $source ) || ! empty( $upload['error'] ) ) {
		return 0;
	}
	$name = wp_unique_filename( $upload['path'], wp_basename( $source ) );
	$dest = trailingslashit( $upload['path'] ) . $name;
	if ( ! copy( $source, $dest ) ) {
		return 0;
	}
	$type = wp_check_filetype( $name, null );
	$att  = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => $title, 'post_status' => 'inherit' ), $dest );
	if ( is_wp_error( $att ) || ! $att ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $dest ) );
	update_post_meta( $att, '_ccs_source_theme_path', $rel );
	return (int) $att;
}

/**
 * Create the articles once.
 */
function ccs_ensure_articles_2026_11() {
	if ( get_option( 'ccs_articles_2026_11' ) || ! get_option( 'ccs_area_services_2026_11b' ) ) {
		return;
	}
	if ( ! add_option( 'ccs_articles_2026_11', '1', '', 'no' ) ) {
		return;
	}
	// The same author as the seeded articles, so the byline is filled in.
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
	$author = $admins ? (int) $admins[0]->ID : 1;
	foreach ( ccs_articles_2026_11() as $slug => $a ) {
		if ( get_posts( array( 'name' => $slug, 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_author'  => $author,
				'post_title'   => $a['title'],
				'post_name'    => $slug,
				'post_excerpt' => $a['excerpt'],
				'post_content' => $a['content'],
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		// By slug: the stored name of "Funding & Costs" is HTML-escaped.
		$term = get_term_by( 'slug', $a['category'], 'category' );
		if ( $term instanceof WP_Term ) {
			wp_set_post_categories( $id, array( (int) $term->term_id ), false );
		}
		$photo = ccs_articles_import_photo( $a['image'], $a['title'] );
		if ( $photo ) {
			set_post_thumbnail( $id, $photo );
		}
	}
}
add_action( 'init', 'ccs_ensure_articles_2026_11', 40 );
