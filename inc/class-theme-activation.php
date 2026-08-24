<?php
/**
 * Theme activation: demo pages, service posts, menus, Reading, permalinks.
 * Idempotent and supports "Reset Demo Content" in admin.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Theme_Activation
 */
class CCS_Theme_Activation {

	const DEMO_META_KEY       = '_ccs_demo_content';
	const SEEDED_HASH_META    = '_ccs_seeded_content_hash';
	const MENUS_OPTION        = 'ccs_theme_activation_menus';
	const PAGE_IDS_OPTION     = 'ccs_theme_activation_page_ids';
	const CAREERS_PAGE_OPTION = 'ccs_careers_page_ids';

	/** Scope steps for run_with_scope(). */
	const SCOPES = array( 'general', 'pages', 'contact_page', 'services', 'locations', 'news', 'menus', 'reading', 'permalinks' );

	/**
	 * Page definitions: slug => [ 'title', 'parent_slug' (optional), 'template' (optional) ].
	 *
	 * Top-level pages are deliberately NOT nested under the Home page. Home is the
	 * front page (served at "/"), so parenting other pages to it only produced
	 * redundant, deeper URLs like /home/home-care-services-kent/ — worse to read
	 * and worse for SEO. Meaningful nesting (Resources, Careers) is kept.
	 *
	 * @var array<string, array>
	 */
	private $pages = array(
		'home'                        => array(
			'title'           => 'Home',
			'template'        => 'page-templates/template-homepage.php',
			'seo_title'       => 'Home Care in Maidstone & Kent',
			'seo_description' => "Home care for children and adults in Maidstone and across Kent. A small, matched team of carers you'll get to know. CQC regulated.",
		),
		'about-home-care-maidstone'   => array(
			'title'           => 'About Home Care Maidstone',
			'heading'         => 'About us',
			'intro'           => 'We\'re a Kent home care provider built around one idea: you should know the people who come into your home, and they should know you.',
			'template'        => 'page-templates/template-about.php',
			'hero_image'      => 'assets/images/site-photos-extra/client-summer-party-laineys-care-farm.webp',
			'seo_title'       => 'About Us',
			'seo_description' => 'Meet Continuity of Care Services — a CQC-regulated, family-run home care provider in Maidstone and Kent. Real carers, matched to you, not a rota.',
		),
		'home-care-services-kent'     => array(
			'title'           => 'Home Care Services Kent',
			'heading'         => 'Our care services',
			'intro'           => 'From everyday help at home to complex and specialist care, across Maidstone and Kent.',
			'template'        => 'page-templates/template-services.php',
			'hero_image'      => 'assets/images/site-photos/services-hero-desktop.webp',
			'seo_title'       => 'Our Care Services',
			'seo_description' => 'Domiciliary, respite and complex care at home across Maidstone and Kent. CQC regulated, matched carers, transparent pricing. Book a free consultation.',
		),
		'who-youll-meet'              => array(
			'title'           => "Who You'll Meet",
			'template'        => 'page-templates/template-team.php',
			'heading'         => "Who you'll meet",
			'intro'           => 'The people behind your care, with real names and real faces. You should know who is walking through your door.',
			'hero_image'      => 'assets/images/site-photos-extra/caregiver-supporting-patient.webp',
			'seo_title'       => "Who You'll Meet",
			'seo_description' => "Meet the real people behind your care — our Kent-based team, from registered manager to field care supervisors. Real names, real photos.",
		),
		'contact-us'                  => array(
			'title'           => 'Contact Us',
			// Breadcrumb/label matches the nav ("Contact Us"); the page's own H1 stays
			// action-oriented ("Book Your Free Care Consultation"). Three different
			// labels for one page was just confusing.
			'heading'         => 'Contact us',
			'intro'           => 'Tell us what you need and we\'ll arrange a call or a visit. No pressure, no obligation.',
			'template'        => 'page-templates/template-contact.php',
			'seo_title'       => 'Contact Us',
			'seo_description' => 'Get in touch with Continuity of Care Services. Call 01622 809 881 or book a free care consultation online — Maidstone and Kent.',
		),
		'resources'                   => array( 'title' => 'Resources' ),
		'care-guides'                 => array( 'title' => 'Care Guides', 'parent_slug' => 'resources', 'template' => 'page-templates/template-care-guides.php', 'hero_image' => 'assets/images/site-photos-extra/client-creative-activity-autumn-leaves.webp' ),
		'faqs'                        => array( 'title' => 'FAQs', 'parent_slug' => 'resources', 'template' => 'page-templates/template-faqs.php' ),
		'referral-information'        => array( 'title' => 'Referral Information', 'parent_slug' => 'resources' ),
		'news-and-updates'            => array( 'title' => 'News & Updates' ),
		'privacy-policy'              => array( 'title' => 'Privacy Policy', 'template' => 'page-templates/template-content-page.php' ),
		'terms-and-conditions'        => array( 'title' => 'Terms & Conditions', 'template' => 'page-templates/template-content-page.php' ),
		'accessibility-statement'     => array( 'title' => 'Accessibility Statement', 'template' => 'page-templates/template-content-page.php' ),
		'cookies'                     => array( 'title' => 'Cookie Policy', 'template' => 'page-templates/template-content-page.php' ),
		// Careers section (mini-site).
		'careers'                     => array(
			'title'           => 'Careers',
			'heading'         => 'Careers in care',
			'intro'           => 'Flexible hours, proper training, and enough time to do the job well.',
			'template'        => 'page-templates/template-careers.php',
			'hero_image'      => 'assets/images/site-photos/IMG-20240930-WA0058-scaled-1.webp',
			'seo_title'       => 'Careers in Care',
			'seo_description' => 'Join our Kent home care team. Flexible hours, ongoing training and real support — see current vacancies and what it\'s like to work with us.',
		),
		'professional-development'    => array( 'title' => 'Professional Development', 'parent_slug' => 'careers' ),
		'current-vacancies'           => array( 'title' => 'Current Vacancies', 'parent_slug' => 'careers', 'template' => 'page-templates/template-current-vacancies.php', 'hero_image' => 'assets/images/site-photos/home-hero-desktop.webp' ),
		'working-for-us'              => array( 'title' => 'Working for Us', 'parent_slug' => 'careers' ),
		// Optional care pages (Section 7).
		'cqc-and-our-care'            => array( 'title' => 'CQC and Our Care', 'template' => 'page-templates/template-cqc.php', 'hero_image' => 'assets/images/site-photos/client-easter-activity.webp' ),
		'getting-started'              => array( 'title' => 'Getting Started', 'template' => 'page-templates/template-getting-started.php' ),
	);

	/**
	 * Service posts: post_name => [ 'title', 'excerpt', 'content' ].
	 *
	 * @var array<string, array>
	 */
	private $services = array();

	/**
	 * Location posts: post_name => [ 'title', 'excerpt', 'content', 'meta' ].
	 *
	 * @var array<string, array>
	 */
	private $locations = array();

	/**
	 * Primary menu item order (slug or special key). Children under 'resources' for dropdown.
	 *
	 * @var array
	 */
	/**
	 * Top-level primary menu slugs, in order. Restructured 2026-08-19 from a flat
	 * 10-item list (wrapped to 3 lines in the header) down to 6 — items that used
	 * to sit flat now nest under About Us / Resources via $primary_menu_children.
	 *
	 * @var array
	 */
	private $primary_order = array(
		'home',
		'home-care-services-kent',
		'about-home-care-maidstone',
		'resources',
		'careers',
		'contact-us',
	);

	/**
	 * Dropdown children for top-level primary menu items (parent slug => child slugs, in order).
	 * Any slug not listed here (and not 'home', which has no dropdown) renders flat.
	 *
	 * @var array<string, array<int, string>>
	 */
	private $primary_menu_children = array(
		'about-home-care-maidstone' => array( 'who-youll-meet', 'cqc-and-our-care', 'getting-started' ),
		'resources'                 => array( 'care-guides', 'faqs', 'referral-information', 'news-and-updates' ),
	);

	/**
	 * Footer menu slugs in order.
	 *
	 * @var array
	 */
	private $footer_order = array(
		'about-home-care-maidstone',
		'home-care-services-kent',
		'careers',
		'faqs',
		'contact-us',
		'privacy-policy',
		'terms-and-conditions',
		'cookies',
		'accessibility-statement',
	);

	/**
	 * Short navigation labels for primary menu (slug => label). Used so menu items don't use long page titles.
	 *
	 * @var array<string, string>
	 */
	private $primary_menu_labels = array(
		'home'                        => 'Home',
		'about-home-care-maidstone'   => 'About Us',
		'home-care-services-kent'     => 'Our Services',
		'who-youll-meet'              => "Who You'll Meet",
		'careers'                     => 'Careers',
		'resources'                   => 'Resources',
		'care-guides'                 => 'Care Guides',
		'faqs'                        => 'FAQs',
		'referral-information'        => 'Referral Information',
		'news-and-updates'            => 'News & Updates',
		'cqc-and-our-care'            => 'CQC and Our Care',
		'getting-started'             => 'Getting Started',
		'contact-us'                  => 'Contact Us',
	);

	/**
	 * Stored page IDs by slug after creation/lookup.
	 *
	 * @var array<string, int>
	 */
	private $page_ids = array();

	/**
	 * Careers page IDs (for header menu switch). Populated in ensure_pages(), persisted to option.
	 *
	 * @var array<string, int>
	 */
	private $careers_page_ids = array();

	/**
	 * Constructor: hook activation and admin UI.
	 */
	public function __construct() {
		add_action( 'after_switch_theme', array( $this, 'run' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_reset_request' ) );
		add_action( 'admin_init', array( $this, 'handle_populate_request' ) );
		$this->define_services();
		$this->define_locations();
	}

	/**
	 * Define the three care service posts (title, excerpt, content).
	 */
	private function define_services() {
		$this->services = array(
			'domiciliary-care' => array(
				'title'   => 'Domiciliary Care',
				'image'   => 'assets/images/site-photos/ccs-domiciliary-care.webp',
				'excerpt' => 'Domiciliary care in Maidstone and across Kent — gentle, unhurried help with everyday tasks at home, from carers who come back to you visit after visit.',
				'content' => "<p>Domiciliary care is everyday help at home — getting dressed, making breakfast, remembering the right medication at the right time. Continuity of Care Services provides domiciliary care in Maidstone and across Kent, and our carers give you steady, unhurried support rather than rushing through a checklist.</p>
<h2>What We Help With:</h2>
<ul>
<li>Personal care (washing, dressing, grooming)</li>
<li>Medication reminders and administration</li>
<li>Meal preparation and assistance with eating</li>
<li>Light housekeeping and laundry</li>
<li>Shopping and errands</li>
<li>Companionship and social interaction</li>
</ul>
<h2>Your Schedule, Your Way</h2>
<p>We don't believe in rigid routines that don't fit your life. Whether you need a morning visit to help you get ready for the day, or evening support to help you wind down, we'll create a care plan that works for you.</p>
<h2>Consistency You Can Count On</h2>
<p>We don't rotate staff every week. You'll work with the same familiar faces who take the time to learn how you like your tea, what makes you laugh, and what puts you at ease.</p>",
			),
			'respite-care' => array(
				'title'   => 'Respite Care',
				'image'   => 'assets/images/site-photos/ccs-respite-care-guitar-1.webp',
				'excerpt' => "Respite care in Maidstone and across Kent, for a few hours or a few days. A carer who's already met the person you care for steps in, so you can properly rest.",
				'content' => "<p>Caring for someone you love is rewarding, and it's also exhausting. Everyone needs a break. Continuity of Care Services provides respite care in Maidstone and throughout Kent, giving family carers the time they need to rest, recharge, or simply take care of themselves.</p>
<h2>Flexible Respite Options:</h2>
<ul>
<li>Short breaks (a few hours to a day)</li>
<li>Extended respite (overnight or multiple days)</li>
<li>Regular scheduled breaks (weekly, fortnightly)</li>
<li>Emergency respite care</li>
<li>Holiday cover for regular carers</li>
</ul>
<h2>Seamless Continuity of Care</h2>
<p>We take time to learn the routines, preferences and needs of the person you care for, so the handover is smooth. They get the same standard of care they're used to.</p>
<h2>Give Yourself Permission to Rest</h2>
<p>There's no guilt in needing a break. Whether you need to attend an appointment, catch up on sleep, visit friends, or just have some time to yourself, we're here to make that possible.</p>
<h2>What Our Respite Care Includes:</h2>
<ul>
<li>All aspects of personal care</li>
<li>Medication administration</li>
<li>Meal preparation</li>
<li>Engaging activities and companionship</li>
<li>Continuation of care routines</li>
<li>Regular updates to family members</li>
</ul>",
			),
			'complex-care' => array(
				'title'   => 'Complex Care',
				'image'   => 'assets/images/site-photos/ccs-complex-care-wheelchair-1.webp',
				'excerpt' => 'Complex care at home in Maidstone and across Kent, from epilepsy care to PEG and mobility support. A trained team working closely with your family and your healthcare team.',
				'content' => "<p>Complex care at home requires specialist knowledge, clinical skills, and unwavering attention to detail. Continuity of Care Services provides complex care across Maidstone and Kent, and our trained team supports people with complex health needs alongside their families and healthcare professionals.</p>
<h2>Conditions We Support:</h2>
<ul>
<li>Epilepsy and seizure management</li>
<li>Spinal injuries and paralysis</li>
<li>Tracheostomy care</li>
<li>PEG feeding and nutrition support</li>
<li>Ventilation support</li>
<li>Cerebral palsy</li>
<li>Brain injuries</li>
<li>Multiple sclerosis</li>
<li>Motor neurone disease</li>
<li>Acquired brain injury</li>
</ul>
<h2>Clinical Care at Home</h2>
<p>Our carers receive specialist training in clinical procedures and work closely with healthcare professionals to deliver:</p>
<ul>
<li>Medication management and administration</li>
<li>Clinical observations and monitoring</li>
<li>Moving and handling with specialist equipment</li>
<li>Personal care with dignity</li>
<li>Catheter and stoma care</li>
<li>Wound care</li>
<li>Night sits and waking night care</li>
</ul>
<h2>Working as Part of Your Team</h2>
<p>We don't work in isolation. We collaborate with occupational therapists, physiotherapists, speech and language therapists, community nurses, GPs and consultants, social workers, and family members.</p>
<h2>24/7 Support When You Need It</h2>
<p>Complex care needs don't follow a 9–5 schedule. Our team is available day and night to provide the consistent, skilled care required.</p>
<h2>A life, not just a condition</h2>
<p>Yes, we're experts in clinical care. But we're also committed to ensuring our clients live full, happy lives. We support hobbies, outings, social connections, and everything that makes life worth living.</p>",
			),
		);
	}

	/**
	 * Define the location posts (post type: location).
	 *
	 * Launch-priority (P1) towns only, per reference/sitemap-urls.md. P2/P3 towns
	 * (Sevenoaks, Ashford, Staplehurst, Headcorn, Thanet, Whitstable, Hythe, Rye)
	 * are not yet defined here.
	 *
	 * 'meta' only carries fields we can state with confidence: town/county/postcode
	 * area, nearby villages, approximate map coordinates, and hospital *names* (no
	 * phone numbers or addresses — those go stale and a wrong one on a care site is
	 * actively harmful). GP practices, CHC contact, council adult services contact,
	 * support groups, coordinator and team stats are deliberately left unset: they
	 * need real, current, Ellie-verified detail, not a guess. Fill them in via the
	 * Location Details meta box on each post.
	 *
	 * @return void
	 */
	private function define_locations() {
		$this->locations = array(
			'maidstone'       => array(
				'title'   => 'Home Care in Maidstone',
				'excerpt' => 'Home care in Maidstone from a locally based team who know the town and the villages around it — the same small team every visit, not a rotating rota.',
				/*
				 * "Home care in Maidstone" reinforced in the opening sentence (previously
				 * only in the title and excerpt, never the body) and linked to the two
				 * services most searched alongside a town name: domiciliary and complex
				 * care. Real internal links, not just keyword mentions — genuine SEO
				 * value plus a real next step for a reader who lands here from a "home
				 * care Maidstone" search and hasn't decided which service they need yet.
				 */
				'content' => '<p>Continuity of Care Services provides home care in Maidstone from our own base in the town, so it\'s the area we know best — which GP surgeries run late clinics, which pharmacies deliver, how the traffic moves at different times of day. That local knowledge means less time explaining and more time getting on with your day.</p>
<p>We support families across Maidstone and in the villages around it — Bearsted, Loose, Barming, Coxheath and Detling among them — with everything from a short <a href="' . home_url( '/services/domiciliary-care/' ) . '">domiciliary care</a> visit to round-the-clock <a href="' . home_url( '/services/complex-care/' ) . '">complex care</a> at home. Whatever the need, you\'re matched with the same small team, not a rotating rota.</p>',
				'meta'    => array(
					'location_town'           => 'Maidstone',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'ME',
					'location_areas_covered'  => "Bearsted\nLoose\nBarming\nCoxheath\nDetling\nGrove Green\nPenenden Heath\nOtham",
					'location_latitude'       => 51.2704,
					'location_longitude'      => 0.5227,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Maidstone Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
			'west-malling'    => array(
				'title'   => 'Home Care in West Malling',
				'excerpt' => 'Home care in West Malling and the villages around it, from a team based just down the road in Maidstone.',
				'content' => '<p>West Malling is a small town with a strong sense of itself — the high street, the abbey, the villages that ring it. We provide home care here the way the town works: unhurried, familiar, nothing rushed.</p>
<p>Families in West Malling, Kings Hill, Offham, Leybourne and Ditton are supported by carers matched to them and kept consistent, whether that\'s a short daily visit or ongoing complex care. Your carer knows your routine because they\'re the one who\'s been coming.</p>',
				'meta'    => array(
					'location_town'           => 'West Malling',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'ME',
					'location_areas_covered'  => "Kings Hill\nOffham\nLeybourne\nDitton\nEast Malling\nLarkfield",
					'location_latitude'       => 51.2870,
					'location_longitude'      => 0.4090,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Maidstone Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
			'aylesford'       => array(
				'title'   => 'Home Care in Aylesford',
				'excerpt' => 'Home care in Aylesford and along the Medway villages, with carers who stay the same visit to visit.',
				'content' => '<p>Aylesford sits on the Medway, with a spread of villages either side that we know well. Care here means the same familiar face at the door, not a different carer every week.</p>
<p>We support people across Aylesford, Eccles, Ditton, Wouldham and Blue Bell Hill, from a few hours of help a week to complex, clinically-led care at home. Every plan starts with a free, no-obligation conversation about what would actually help.</p>',
				'meta'    => array(
					'location_town'           => 'Aylesford',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'ME',
					'location_areas_covered'  => "Eccles\nDitton\nWouldham\nBlue Bell Hill\nWalderslade\nLarkfield",
					'location_latitude'       => 51.3009,
					'location_longitude'      => 0.4707,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Maidstone Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
			'snodland'        => array(
				'title'   => 'Home Care in Snodland',
				'excerpt' => 'Home care in Snodland and the villages along the Medway, from a small, consistent local team.',
				'content' => '<p>Snodland and the villages around it — Halling, Wouldham, Birling, Ryarsh — sit just north of our Maidstone base, and it\'s an area we visit often. That means shorter travel time for your carer and more of it spent with you.</p>
<p>Whether you need daily help getting started in the morning or ongoing complex care, we build a plan around your routine and keep the same small team coming back, so nobody\'s a stranger.</p>',
				'meta'    => array(
					'location_town'           => 'Snodland',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'ME',
					'location_areas_covered'  => "Halling\nWouldham\nBirling\nRyarsh\nBurham",
					'location_latitude'       => 51.3327,
					'location_longitude'      => 0.4479,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Maidstone Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
			'tonbridge'       => array(
				'title'   => 'Home Care in Tonbridge',
				'excerpt' => 'Home care in Tonbridge and the surrounding villages, with a consistent, matched team of carers.',
				'content' => '<p>Tonbridge is a market town with the river and the castle at its centre, and villages spreading out from it in every direction. We cover the town and those villages with the same promise: the carer who starts with you is the one who stays.</p>
<p>Families in Tonbridge, Hildenborough, Leigh, Hadlow and East Peckham are supported with everything from short daily visits to complex care at home, all CQC regulated and built around what actually helps, not a standard package.</p>',
				'meta'    => array(
					'location_town'           => 'Tonbridge',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'TN',
					'location_areas_covered'  => "Hildenborough\nLeigh\nHadlow\nEast Peckham\nTudeley\nHigham",
					'location_latitude'       => 51.1931,
					'location_longitude'      => 0.2748,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Tunbridge Wells Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
			'tunbridge-wells' => array(
				'title'   => 'Home Care in Tunbridge Wells',
				'excerpt' => 'Home care in Royal Tunbridge Wells and the villages around it, delivered by carers you\'ll actually get to know.',
				'content' => '<p>Royal Tunbridge Wells and the villages that surround it — Southborough, Rusthall, Pembury, Speldhurst — are part of our regular patch. However spread out the address, the approach is the same: a small, matched team, not a different carer each visit.</p>
<p>From a short visit for personal care to full complex care at home, every plan starts with a free conversation about what would help, and stays flexible as your needs change.</p>',
				'meta'    => array(
					'location_town'           => 'Tunbridge Wells',
					'location_county'         => 'Kent',
					'location_postcode_area'  => 'TN',
					'location_areas_covered'  => "Southborough\nRusthall\nPembury\nSpeldhurst\nLangton Green\nSherwood",
					'location_latitude'       => 51.1324,
					'location_longitude'      => 0.2637,
					'location_local_hospitals' => array(
						array( 'hospital_name' => 'Tunbridge Wells Hospital', 'hospital_phone' => '', 'hospital_address' => '' ),
					),
				),
			),
		);
	}

	/**
	 * Run full setup (idempotent).
	 */
	public function run() {
		$this->run_with_scope( self::SCOPES );
	}

	/**
	 * Run activation steps for the given scope. Use for granular populate from Welcome Screen.
	 *
	 * @param array $scope Steps: general, pages, contact_page, services, locations, news, menus, reading, permalinks.
	 */
	public function run_with_scope( array $scope ) {
		$scope = array_intersect( $scope, self::SCOPES );
		if ( empty( $scope ) ) {
			return;
		}

		// If we're only running menus (or reading/permalinks), load page IDs from options.
		if ( in_array( 'menus', $scope, true ) && ! in_array( 'pages', $scope, true ) ) {
			$this->page_ids       = (array) get_option( self::PAGE_IDS_OPTION, array() );
			$this->careers_page_ids = (array) get_option( self::CAREERS_PAGE_OPTION, array() );
		}

		if ( in_array( 'general', $scope, true ) ) {
			$this->ensure_general_settings();
		}
		if ( in_array( 'pages', $scope, true ) ) {
			$this->ensure_pages();
			update_option( self::PAGE_IDS_OPTION, $this->page_ids );
			update_option( self::CAREERS_PAGE_OPTION, $this->careers_page_ids );
		}
		if ( in_array( 'contact_page', $scope, true ) ) {
			$this->ensure_contact_page_content();
		}
		if ( in_array( 'services', $scope, true ) ) {
			$this->ensure_services();
		}
		if ( in_array( 'locations', $scope, true ) ) {
			$this->ensure_locations();
		}
		if ( in_array( 'news', $scope, true ) ) {
			$this->ensure_news_posts();
		}
		if ( in_array( 'menus', $scope, true ) ) {
			$this->ensure_menus();
		}
		if ( in_array( 'reading', $scope, true ) ) {
			$this->ensure_reading_settings();
		}
		if ( in_array( 'permalinks', $scope, true ) ) {
			$this->ensure_permalinks();
		}
	}

	/**
	 * Is a site title/tagline still an untouched installer placeholder?
	 *
	 * The previous check compared blogname against 'Just another WordPress site',
	 * which is WordPress's default *tagline*, never its default title — so no
	 * real install ever matched it and the site title was left alone. On this
	 * build that left "My WordPress Website" (the title WordPress Playground and
	 * the wp-admin installer's own placeholder both ship) rendering in the
	 * <title> tag, og:title, og:site_name, og:image:alt, the footer brand and the
	 * copyright line — the site name is the single most repeated piece of brand
	 * copy on every page, so a placeholder there undoes the rest of the branding.
	 *
	 * Matching is trimmed and case-insensitive because installers vary in casing,
	 * and deliberately limited to a known placeholder list: anything a human has
	 * actually typed is left untouched.
	 *
	 * @param mixed $value Stored option value.
	 * @return bool True when the value is empty or a known placeholder.
	 */
	private static function is_placeholder_site_value( $value ) {
		if ( ! is_string( $value ) ) {
			return true;
		}

		$value = trim( $value );
		if ( $value === '' ) {
			return true;
		}

		$placeholders = array(
			'just another wordpress site',
			'my wordpress website',
			'my wordpress site',
			'my blog',
			'my site',
			'wordpress',
			'wordpress site',
			'site title',
			'blog',
		);

		return in_array( strtolower( $value ), $placeholders, true );
	}

	/**
	 * Set General settings from content guide (only if still at defaults).
	 */
	private function ensure_general_settings() {
		if ( self::is_placeholder_site_value( get_option( 'blogname', '' ) ) ) {
			update_option( 'blogname', 'Continuity Care Services' );
		}
		if ( self::is_placeholder_site_value( get_option( 'blogdescription', '' ) ) ) {
			update_option( 'blogdescription', 'Home Care in Maidstone & Kent - Your Team, Your Time, Your Life' );
		}
		$admin_email = get_option( 'admin_email', '' );
		if ( $admin_email === '' || strpos( $admin_email, 'wordpress' ) !== false ) {
			update_option( 'admin_email', 'office@continuitycareservices.co.uk' );
		}

		/*
		 * British English. WordPress installs default to en_US, which then
		 * renders <html lang="en-US"> on a Kent care provider whose copy,
		 * spelling, currency and regulator are all British — a wrong locale
		 * signal for a business whose entire proposition is being local, and a
		 * wrong pronunciation hint for screen readers.
		 *
		 * Only applied when the option is still empty (the stored value for a
		 * default en_US install), so a deliberate choice of any other language
		 * is never overwritten. Guarded on the timezone too, for the same
		 * reason: UK offices should not be publishing UTC opening hours.
		 */
		$this->ensure_author_display_name();

		if ( get_option( 'WPLANG', '' ) === '' ) {
			update_option( 'WPLANG', 'en_GB' );
		}
		if ( get_option( 'timezone_string', '' ) === '' ) {
			update_option( 'timezone_string', 'Europe/London' );
		}
	}

	/**
	 * Default page content by slug (from docs/CCS-THEME-AND-CONTENT-GUIDE.md). Empty for home, contact-us, news-and-updates.
	 *
	 * @param string $slug Page slug.
	 * @return string Post content (may be empty).
	 */
	private function get_default_page_content( $slug ) {
		$content = array(
			'about-home-care-maidstone' => '<p>Reliably supporting adults and children across Maidstone and Kent, we\'re here to provide care that fits your life, day or night. <strong>Our local team supports families right across Kent.</strong></p>
<p>We don\'t rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day.</p>
<p>We believe that the best care doesn\'t stop when the to-do list is ticked; it carries on in how our carers show up: familiar, unhurried and glad to see you. <strong>Learn more about the home care services we offer in Maidstone &amp; Kent.</strong></p>',
			// The service list itself is rendered as a card grid by
			// page-templates/template-services.php, so this intro no longer repeats it.
			'home-care-services-kent' => '<p class="lead">We provide three kinds of home care across Maidstone and the surrounding Kent towns. Most people start with one and move between them as things change — which is the point of arranging care with one provider rather than several.</p>

<h2>Who we support</h2>
<p>Adults and children living at home, including people with dementia, Parkinson\'s, MS, acquired brain injury, epilepsy, stroke, and those needing PEG feeding, catheter care or overnight support. We work with people funding their own care, people funded by Kent County Council, and people receiving NHS Continuing Healthcare.</p>

<h2>The part that does not change</h2>
<p>Whichever service you use, the way we staff it is the same. You get a small named team rather than whoever the rota produces. They meet you before they start. They keep working with you as your needs change, so a move from an hour a day to overnight support does not mean explaining yourself to a new set of faces.</p>
<p>That continuity is not just about comfort. A carer who was there last week is the one who notices the new bruise, the untouched shopping, the change in breathing — the early signs that keep people out of hospital.</p>

<h2>Coordinating with everyone else involved</h2>
<p>Care at home rarely involves only us. We work alongside district nurses, GPs, occupational therapists, social workers, case managers and hospital discharge teams, and we will talk to them directly rather than leaving families to relay messages. If you are coming out of hospital, we work to the discharge date and take a handover from the ward.</p>

<h2>Choosing between them</h2>
<p>If you are not sure which service fits, you do not need to decide before calling. The assessment visit exists to work that out, and it is common to start with less support than you expected to need — or more, for a short while, and then step down.</p>

<h2>Areas we cover</h2>
<p>Maidstone and the villages around it, plus Aylesford, Snodland, West Malling, Tonbridge and Tunbridge Wells. If you are just outside, ring and ask — the honest answer depends on whether we can staff it consistently, and consistency is the thing we will not compromise on.</p>

<h2>What it costs</h2>
<p>Our hourly rate and what it includes are published in full, along with what is charged extra and when. There are no assessment fees and no minimum contract. See ' . '<a href="' . esc_url( home_url( '/resources/faqs/' ) ) . '">common questions</a>' . ' for the detail, or ' . '<a href="' . esc_url( home_url( '/contact-us/' ) ) . '">get in touch</a>' . ' and we will talk it through.</p>',
			// The roster itself is rendered by page-templates/template-team.php from
			// ccs_team_members(), so this intro sets it up rather than listing names.
			'who-youll-meet' => '<p>Letting someone into your home is a big thing to ask. So before you do, here is who we are.</p>
<p>Every person below is office or management team, and they are the people you will actually speak to when you call. Your carers are matched to you separately, and you meet them before care starts, never on the doorstep on day one.</p>
<p>If someone is not the right fit, tell us and we will change it. That is the whole point.</p>',
			'care-careers-maidstone-kent' => '<p>Make a real impact by joining our team. Offering rewarding roles, flexible hours, and ongoing training, we\'d love to hear from you. If you\'re passionate about helping others, explore how you can grow your career with us.</p>
<h2>Why join us</h2>
<ul>
<li>Flexible working hours</li>
<li>Competitive pay rates</li>
<li>Ongoing training and development</li>
<li>Supportive team environment</li>
<li>Make a real difference in people\'s lives</li>
</ul>
<p>For care careers in Maidstone and Kent, get in touch or view current openings.</p>',
			'resources' => '<p class="lead">Practical information for families arranging care, and for the professionals referring into it. If you cannot find what you need here, ring the office — we would rather answer a question than have you guess.</p>

<h2>For families</h2>
<p>' . '<a href="' . esc_url( home_url( '/getting-started/' ) ) . '">Getting started</a>' . ' walks through what actually happens between the first phone call and the first visit: the assessment, the care plan, meeting your team, and how the first week is handled.</p>
<p>' . '<a href="' . esc_url( home_url( '/resources/care-guides/' ) ) . '">Our care guides</a>' . ' cover the wider questions — understanding the difference between home care and residential care, what a care plan should contain, and what to expect in the first few months.</p>
<p>' . '<a href="' . esc_url( home_url( '/resources/faqs/' ) ) . '">Common questions</a>' . ' answers the things people ask most: what it costs, how quickly care can start, whether you get the same carers, and what happens if someone is unwell.</p>

<h2>For health and social care professionals</h2>
<p>' . '<a href="' . esc_url( home_url( '/resources/referral-information/' ) ) . '">Referral information</a>' . ' sets out how to refer, what information helps us respond quickly, and our capacity for complex and overnight packages. We accept referrals from GPs, district nurses, hospital discharge teams, social workers, case managers and ICB commissioners.</p>

<h2>Our regulator</h2>
<p>We are registered with and inspected by the Care Quality Commission. ' . '<a href="' . esc_url( home_url( '/cqc-and-our-care/' ) ) . '">CQC and our care</a>' . ' explains what our rating covers and links to the CQC\'s own report, which is published by them rather than by us.</p>

<h2>News and updates</h2>
<p>' . '<a href="' . esc_url( home_url( '/news-and-updates/' ) ) . '">News and updates</a>' . ' carries care advice, funding explainers and occasional news from the team.</p>

<h2>Still stuck?</h2>
<p>Ring the office during working hours, or ' . '<a href="' . esc_url( home_url( '/contact-us/' ) ) . '">send us a message</a>' . ' and we will come back to you. If your question is urgent and you are already receiving care from us, use the on-call number in your care plan.</p>',
			'care-guides' => '<p class="lead">Plain-English guidance for families weighing up care at home. None of it is specific to us — it is the information we find ourselves repeating on the phone, written down so you can read it in your own time.</p>

<h2>How do you know when help is needed?</h2>
<p>It is rarely one moment. The signs that usually matter are practical and cumulative: post piling up unopened, a fridge with very little in it or a lot that is out of date, meals getting simpler, the same story told twice in one visit, clothes staying on longer than usual, unexplained bruising, or a world that is quietly getting smaller — clubs dropped, friends not rung, the car used less.</p>
<p>None of these on its own means someone needs care. Several together usually means it is worth a conversation.</p>

<h2>The kinds of care available</h2>
<p><strong>Home care</strong> (also called domiciliary care) is visiting support in someone\'s own home, from half an hour a day to several visits daily. <strong>Live-in care</strong> places a carer in the home full time. <strong>Respite care</strong> covers a family carer taking a break. <strong>Complex or clinical care</strong> covers needs like PEG feeding, ventilation or epilepsy management, and requires specifically trained staff. <strong>Residential and nursing homes</strong> are the alternative when care at home is no longer safe or affordable.</p>
<p>Home care is usually cheaper than a residential placement for lower levels of need, and usually more expensive above roughly six to seven hours a day. That crossover is worth doing the arithmetic on honestly.</p>

<h2>Paying for care in Kent</h2>
<p>There are three routes, and many people use more than one:</p>
<ul>
<li><strong>Self-funded.</strong> If capital is above the upper threshold (£23,250 in England at the time of writing), care is usually paid for privately. Attendance Allowance is not means-tested and is frequently missed — it is worth checking.</li>
<li><strong>Local authority funded.</strong> Kent County Council must carry out a needs assessment for anyone who appears to need care, regardless of means, and you are entitled to ask for one.</li>
<li><strong>NHS Continuing Healthcare.</strong> Fully NHS-funded care for people whose needs are primarily health rather than social. The assessment is notoriously difficult, and being turned down at the checklist stage is not the end of the process.</li>
</ul>

<h2>What a care plan should contain</h2>
<p>A care plan is not a schedule. A good one records what happens at each visit and in what order, what "normal" looks like for that person so changes get noticed, medication and who administers versus prompts it, preferences and dislikes, who to contact and in what circumstances, and what to do when things do not go to plan. You should have a copy in the house, and you should be able to understand it without a care background.</p>

<h2>Coming out of hospital</h2>
<p>Discharge often moves faster than families expect. You can ask for a needs assessment before discharge, and you do not have to accept the first placement or package offered if it is not suitable. A provider should be willing to take a handover directly from the ward rather than relying on a summary letter.</p>

<h2>Questions worth asking any provider</h2>
<ul>
<li>How many different carers will realistically visit, and will we meet them first?</li>
<li>What happens when our regular carer is on leave or off sick?</li>
<li>Are visits ever cut short, and how is that recorded?</li>
<li>Who do we ring at 9pm on a Sunday, and do they have access to the care plan?</li>
<li>What is the CQC rating, when was the last inspection, and what did it say?</li>
<li>What is charged on top of the hourly rate, and what notice is needed to stop?</li>
</ul>
<p>You are entitled to straight answers to all of these before agreeing to anything.</p>

<h2>If something goes wrong</h2>
<p>Every provider must have a complaints procedure and must give it to you on request. Concerns about someone\'s safety can be raised directly with Kent County Council\'s safeguarding team or with the Care Quality Commission, and you do not have to go through the provider first.</p>

<p>' . '<a href="' . esc_url( home_url( '/resources/faqs/' ) ) . '">Common questions</a>' . ' covers our own rates, timescales and staffing, and ' . '<a href="' . esc_url( home_url( '/contact-us/' ) ) . '">get in touch</a>' . ' if you would rather just ask someone.</p>',
			'faqs' => '<p>Common questions about our home care in Maidstone and Kent. Can\'t find what you need? <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Get in touch</a> and we\'ll be happy to help.</p>
<h2>What areas do you cover?</h2>
<p>We provide home care across Maidstone and Kent, including surrounding towns and villages. If you\'re unsure whether we cover your area, please contact us and we\'ll confirm.</p>
<h2>How quickly can care start?</h2>
<p>We aim to arrange a care consultation within a few days of your enquiry. Once we\'ve agreed a care plan, we can often start within one to two weeks, depending on your needs and our team availability.</p>
<h2>Can I have the same carer each time?</h2>
<p>Yes. We prioritise consistency so you see familiar faces. We match you with a small team who get to know you and your routine, so care feels personal and predictable.</p>
<h2>What types of care do you offer?</h2>
<p>We offer domiciliary care (day-to-day support with personal care, medication and companionship), respite care (short breaks for family carers), and complex care (specialist clinical support at home). You can read more on our <a href="' . esc_url( home_url( '/home-care-services-kent/' ) ) . '">Our Services</a> page.</p>
<h2>How do I pay for care?</h2>
<p>Care can be funded privately, through local authority support, or via health funding depending on your situation. We can discuss options during your consultation and signpost you to advice if needed.</p>
<h2>Are you regulated by the CQC?</h2>
<p>Yes. We are registered with the Care Quality Commission. You can view our latest CQC rating on our <a href="' . esc_url( home_url( '/cqc-and-our-care/' ) ) . '">CQC and Our Care</a> page or on the CQC website.</p>
<h2>What if I need to change or stop care?</h2>
<p>You can change your care plan or hours at any time by talking to us. If you need to stop care, we ask for notice where possible so we can support a smooth handover.</p>',
			'referral-information' => '<p>We work with GPs, social workers, nurses, hospital discharge teams and other health and care professionals across Maidstone and Kent. If you are referring a patient or client for home care, the information below will help.</p>
<h2>What we offer</h2>
<p>We provide domiciliary care, respite care and complex care at home. We can support adults and children, and we tailor packages to assessed needs and family preferences.</p>
<h2>Referral process</h2>
<p>Contact us by phone or email with the person\'s details and, where possible, a summary of needs or care plan. We will arrange an assessment and provide a written plan and costings. We are happy to attend meetings or speak with other professionals with the client\'s or family\'s consent.</p>
<h2>Contact</h2>
<p>For referrals or professional enquiries, please <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a>. We aim to respond within one working day.</p>',
			'news-and-updates' => '<p>News, updates and articles from Continuity Care Services. We share practical tips, service updates and stories from our team and the people we support across Maidstone and Kent.</p>
<p>For the latest vacancies and careers news, visit our <a href="' . esc_url( home_url( '/careers/' ) ) . '">Careers</a> section. For care enquiries, <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">get in touch</a>.</p>',
			'privacy-policy' => '<h2>Who we are</h2>
<p>Continuity Care Services ("we", "us", "our") is a home care provider. This privacy policy explains how we collect, use, store and protect your personal data when you use our website, contact us, or receive our care services. We are committed to protecting your privacy and complying with UK data protection law (UK GDPR and the Data Protection Act 2018).</p>
<h2>Data we collect</h2>
<p>We may collect: name, address, phone number, email address; information about your care needs and preferences; relevant health or next-of-kin information where necessary to provide care; and technical data (e.g. IP address) when you use our website. We collect this when you enquire, when we assess and deliver care, and when you use our contact forms or sign up to communications.</p>
<h2>How we use your data</h2>
<p>We use your data to: provide and manage care services; communicate with you and your family or representatives; comply with legal and regulatory obligations (including CQC); improve our services and website; and, with your consent, send you information you have requested. We do not sell your data.</p>
<h2>Legal basis</h2>
<p>We process personal data where necessary for the performance of a contract (e.g. providing care), to comply with legal obligations, for our legitimate interests (e.g. improving services) where those interests are not overridden by your rights, or with your consent where required.</p>
<h2>Sharing your data</h2>
<p>We may share data with: health and social care professionals (e.g. GPs, social workers) where relevant to your care and with consent or where the law allows; regulators (e.g. CQC) when required; and trusted service providers (e.g. IT, payroll) under strict agreements. We do not transfer your data outside the UK unless necessary and with appropriate safeguards.</p>
<h2>Retention</h2>
<p>We keep your data only as long as necessary for the purposes above or as required by law. Care records are retained in line with regulatory and legal requirements.</p>
<h2>Your rights</h2>
<p>You have the right to: access your data; have it corrected; request erasure (in certain cases); restrict or object to processing; and data portability. You may also complain to the ICO (ico.org.uk). To exercise your rights or ask questions, contact us using the details on our <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Contact</a> page.</p>
<h2>Changes</h2>
<p>We may update this policy from time to time. The latest version will be on this page.</p>',
			'terms-and-conditions' => '<h2>Use of this website</h2>
<p>By using the Continuity Care Services website you agree to these terms. If you do not agree, please do not use the site. We may change these terms at any time; continued use after changes means you accept the updated terms.</p>
<h2>Information on the site</h2>
<p>We aim to keep the information on this website accurate and up to date, but we do not guarantee that it is complete or current. Content is for general information only and does not replace professional advice. You should contact us directly for care enquiries and confirm any details that affect your decisions.</p>
<h2>Care services</h2>
<p>Care services are subject to separate agreements, care plans and our policies. Nothing on this website constitutes a binding offer until we have agreed terms with you in writing. Fees, scope of care and conditions will be set out in your care agreement.</p>
<h2>Intellectual property</h2>
<p>All content on this site (text, images, logos) is owned by Continuity Care Services or our licensors. You may not copy, reproduce or use it for commercial purposes without our written permission.</p>
<h2>Links</h2>
<p>Our site may link to other websites. We are not responsible for their content or privacy practices. Links are not an endorsement.</p>
<h2>Liability</h2>
<p>To the extent permitted by law, we exclude liability for any loss or damage arising from use of this website or reliance on its content. We do not exclude liability for death or personal injury caused by our negligence, or for fraud.</p>
<h2>Contact</h2>
<p>For questions about these terms, please <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a>.</p>',
			'accessibility-statement' => '<h2>Our commitment</h2>
<p>Continuity Care Services is committed to making our website accessible to as many people as possible, in line with the Equality Act 2010 and WCAG 2.1 Level AA where practicable. We want everyone to be able to find information about our home care services and contact us easily.</p>
<h2>What we do</h2>
<p>We use clear headings and structure, descriptive link text, sufficient colour contrast, and we aim to ensure the site is usable with keyboard navigation and screen readers. We test our pages on different devices and with accessibility in mind.</p>
<h2>Known limitations</h2>
<p>Some older content or third-party content (e.g. embedded widgets) may not fully meet all accessibility criteria. We are working to improve these areas. If you encounter a specific problem, please tell us so we can fix it.</p>
<h2>Getting information in another format</h2>
<p>If you need information from this website in a different format (e.g. large print, plain language, or by phone), please <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a>. We will do our best to help and will not charge you for this.</p>
<h2>Feedback and complaints</h2>
<p>We welcome feedback. If you are not happy with how we have responded to an accessibility concern, you can contact the Equality Advisory and Support Service (EASS) or the Equality Commission in your region.</p>
<p>We review this statement regularly and update it when we make changes to the site or our practices.</p>',
			'cookies' => '<h2>What are cookies?</h2>
<p>Cookies are small text files stored on your device when you visit a website. They help the site work properly, remember your preferences, and give us insight into how the site is used (e.g. number of visitors).</p>
<h2>How we use cookies</h2>
<p>We use cookies that are strictly necessary for the site to function (e.g. security, load balancing). We may use analytics cookies to understand how visitors use our site so we can improve it; these may be first-party or from trusted providers. We do not use advertising cookies that track you across other websites.</p>
<h2>Your choices</h2>
<p>You can set your browser to refuse or delete cookies. Some parts of the site may not work as well if you disable cookies. You can also opt out of analytics where we use tools that offer an opt-out.</p>
<h2>Third-party cookies</h2>
<p>Our site may include content from third parties (e.g. CQC widget, job portal iframe). Those services may set their own cookies; we do not control them. Please check their privacy policies for more information.</p>
<h2>Updates</h2>
<p>We may update this cookie policy from time to time. The latest version will be on this page. If you have questions, please <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a>.</p>',
			'careers' => '<h2>Why choose a career with Continuity?</h2>
<p>We do things differently. At Continuity of Care Services, you\'re more than just a name on a rota. We\'re a people-first care company based in Maidstone, Kent, and our values apply to our team as much as our clients.</p>
<p>We aim to deliver compassionate care to not only our clients, but our carers, through a workplace culture built on trust, support, and effective communication. Our promise to prioritise the value of \'Your Time, Your Team, Your Life\' is just as much to you, as it is to the clients we work with.</p>
<p><a href="' . esc_url( home_url( '/careers/current-vacancies/' ) ) . '">View current vacancies</a> or get in touch to find out more.</p>',
			'professional-development' => '<p class="lead">Training here is funded, paid, and scheduled during working hours. We would rather carry the cost than have people learning clinical tasks on their own time or, worse, on the job.</p>

<h2>Before your first unsupervised shift</h2>
<p>Everyone completes the Care Certificate — the fifteen standards set nationally for anyone new to care — alongside mandatory training in safeguarding adults and children, moving and handling, infection prevention, basic life support, food hygiene, medication administration, and the Mental Capacity Act and Deprivation of Liberty Safeguards.</p>
<p>You then shadow an experienced carer on the actual rounds you will be working, for as long as it takes to feel ready. Nobody is signed off to a schedule.</p>

<h2>Qualifications we fund</h2>
<ul>
<li><strong>Level 2 Diploma in Care</strong> — the standard qualification for a care worker, usually taken in the first year.</li>
<li><strong>Level 3 Diploma in Adult Care</strong> — for carers taking on more complex packages or moving towards a senior role.</li>
<li><strong>Level 5 Diploma in Leadership and Management for Adult Care</strong> — the qualification required to register as a manager with the Care Quality Commission.</li>
</ul>
<p>These are delivered with local providers including MidKent College, and we cover the cost and the study time.</p>

<h2>Specialist and clinical training</h2>
<p>Complex care packages need training beyond the core. Depending on the clients you support, this can include PEG and enteral feeding, epilepsy awareness and administration of rescue medication, catheter and stoma care, tracheostomy awareness, stroke and acquired brain injury, Parkinson\'s, end-of-life and palliative care, and dementia-specific practice.</p>
<p>Clinical competencies are signed off by a registered nurse and refreshed on a schedule, not once. Where a client\'s needs are unusual, we arrange training specific to that person before the package starts — including training delivered by the family, who usually know the routine better than anyone.</p>

<h2>Supervision and reflection</h2>
<p>You get regular one-to-one supervision with a senior member of staff, plus spot checks and observed practice — the point of which is coaching, not catching people out. Incidents and near misses are discussed openly and used to change how we work, which is also what the CQC\'s "well-led" domain is looking for.</p>

<h2>Where it leads</h2>
<p>Carer, senior carer, field supervisor, care coordinator, deputy manager, registered manager. Almost everyone in our office started on the rounds, and we recruit internally first as a matter of policy. If you want to move towards nursing, we will support an access route and work around your study.</p>

<p>' . '<a href="' . esc_url( home_url( '/careers/current-vacancies/' ) ) . '">Current vacancies</a>' . ' lists current openings, and ' . '<a href="' . esc_url( home_url( '/careers/working-for-us/' ) ) . '">Working for us</a>' . ' covers pay, rotas and day-to-day support.</p>',
			'current-vacancies' => '<p>Browse our current vacancies and apply online. We\'re always looking for caring, reliable people to join our team across Maidstone and Kent.</p>
<p>Use the job portal below to see open roles and submit your application.</p>',
			'working-for-us' => '<p class="lead">We are a small, family-run provider in Maidstone, not a national franchise. That shapes the job more than anything else on this page: you work with a handful of regular clients rather than a different address every hour, and you are known by name in the office.</p>

<h2>What we pay for</h2>
<p>We pay for travel time between calls and mileage, not just contact time. Your rota is built around the visits you actually have, so you are not unpaid for gaps you cannot use. Enhanced rates apply to weekends and bank holidays, and we tell you the rate before you accept a shift, not after.</p>

<h2>The rota, honestly</h2>
<p>You are assigned a small group of regular clients and you keep them. It means you learn how someone likes their tea, what their dog is called, and what "not quite right today" looks like for them specifically — which is the part of this job that makes it a profession rather than a task list.</p>
<p>It also means we cannot promise infinite flexibility. Continuity works both ways: clients rely on seeing you, so we build rotas together rather than issuing them, and we need reasonable notice for changes.</p>

<h2>What we ask</h2>
<p>Kindness and reliability first — we can teach the rest. You will need the right to work in the UK and to pass an enhanced DBS check, which we pay for. A driving licence and access to a car help considerably given the area we cover, though not every round requires one.</p>
<p>No previous care experience is needed. Several of our team came from retail, hospitality and warehouse work, and some of the best carers we have started with no qualifications at all.</p>

<h2>Support while you are working</h2>
<p>You shadow an experienced carer before working alone, for as long as it takes rather than a fixed number of shifts. There is always someone on call out of hours — a real person with access to the client\'s care plan, not an answering service. You get regular one-to-one supervision, and if a call goes badly you can ring the office and talk it through the same day.</p>

<h2>Training and progression</h2>
<p>Everyone completes the Care Certificate, paid, before working unsupervised. Beyond that we fund the Level 2 and Level 3 Diploma in Adult Care and specialist training in areas like PEG feeding, epilepsy and rescue medication, catheter care, and moving and handling. ' . '<a href="' . esc_url( home_url( '/careers/professional-development/' ) ) . '">Professional development</a>' . ' sets out the routes in detail.</p>
<p>Our senior carers, coordinators and field supervisors were nearly all recruited from our own carer team. It is a genuine progression route, not a line on a job advert.</p>

<h2>Open roles</h2>
<p>' . '<a href="' . esc_url( home_url( '/careers/current-vacancies/' ) ) . '">Current vacancies</a>' . ' lists what we are currently recruiting for. If nothing fits but you would like us to keep your details, send them anyway — turnover in the sector means roles open regularly.</p>',
			'cqc-and-our-care' => '<p>Continuity Care Services is registered with the Care Quality Commission (CQC). We are committed to delivering safe, effective, caring, responsive and well-led care. Our CQC rating reflects how we\'re performing.</p>
<p>We believe the best care doesn\'t stop at the checklist – it shows in our carers turning up in a way that feels friendly and familiar. If you\'d like to know more about how we work or our latest inspection, view our CQC profile below or get in touch.</p>',
			'getting-started' => '<p class="lead">Most people ring us because something has shifted. A fall, a hospital discharge letter, a parent who is managing but not quite as well as last year. You do not need to have worked out what you want before you get in touch — that is what the first conversation is for.</p>

<h2>1. The first conversation</h2>
<p>You speak to someone in our Maidstone office, not a call centre. We will ask what is happening, what a normal day looks like now, and what has changed. Expect about twenty minutes. There is nothing to sign and no visit booked at the end of it unless you want one.</p>
<p>If we are not the right fit — if you need a nursing home, or live outside the area we cover — we will say so and point you somewhere better. It happens, and it is a more useful answer than a sales call.</p>

<h2>2. The assessment visit</h2>
<p>A senior member of our team comes to you, usually within a few days. This is a conversation in your front room, not a clipboard exercise. We look at the practical things — stairs, bathroom, medication, mealtimes, who else is involved — and the things that matter just as much: what time you actually like to get up, which programmes you would rather not miss, whether you want company or would prefer someone to get on quietly.</p>
<p>Family are welcome to be there. So is a social worker, district nurse or case manager if one is already involved. If care is being arranged after a hospital stay, we will work to the discharge date rather than our own diary.</p>

<h2>3. Your care plan</h2>
<p>We write the plan with you, and you keep a copy in your home. It sets out what happens at each visit, in what order, and what to do when things do not go to plan — who to call, what your preferences are, which medication needs prompting rather than administering.</p>
<p>It also records the things that are easy to lose between shifts: that you take milk in first, that the back door sticks, that you would rather be called Mrs Whitmore than by your first name. Small details, but they are the difference between being looked after and being processed.</p>

<h2>4. Meeting your care team</h2>
<p>You are matched with a small named team — usually two to four carers — chosen for the shifts you need and, where we can, for who is likely to get on with you. You meet them before they start. If someone is not right, tell us and we will change it; that is a normal request, not a complaint.</p>
<p>Keeping the team small is the whole point. It is what makes it possible for a carer to notice that you are quieter than usual, or that the fridge is fuller than it should be — the kind of early sign that only shows up to someone who was there last week too.</p>

<h2>5. The first week</h2>
<p>A senior carer works alongside the team for the first visits so the handover happens in person rather than on paper. They check the plan matches reality, because it rarely does at first — timings shift, tasks turn out to take longer, something obvious gets missed. We would rather adjust it in week one than have you live with it.</p>
<p>We call you at the end of the first week to ask what is working and what is not.</p>

<h2>6. Keeping in touch</h2>
<p>Every visit is logged, and family members who you have agreed can see them are able to. If a carer has a concern — a new bruise, a missed meal, a change in mood — it is raised the same day, not saved for a review.</p>
<p>We review the plan formally after six weeks and then at least every six months, and any time your needs change. If you go into hospital, we will keep in contact with the ward so that coming home does not mean starting again from scratch.</p>

<h2>If something changes suddenly</h2>
<p>Care needs rarely change on a schedule. If you need more support quickly — after a fall, an infection, or a change in medication — call the office and we will look at it that day. Out of hours there is always someone on call, and they have access to your plan.</p>

<h2>Safeguarding and raising a concern</h2>
<p>Every carer is DBS-checked, trained in safeguarding before their first shift, and works to a code of conduct we will show you on request. If you are ever unhappy with something, tell the office — you will get a named person, a timescale, and an answer in writing. Concerns about someone’s safety are acted on immediately and, where required, reported to the local authority and the Care Quality Commission.</p>
<p>You can read the CQC’s own assessment of us on our ' . '<a href="' . esc_url( home_url( '/cqc-and-our-care/' ) ) . '">CQC and our care</a>' . ' page.</p>

<h2>Paying for care</h2>
<p>Some people fund their own care, some receive funding from Kent County Council, and some are funded through NHS Continuing Healthcare. We will tell you honestly which is likely to apply and what the process involves, including where you may be entitled to an assessment you have not been offered. Our rates and what they include are set out in the ' . '<a href="' . esc_url( home_url( '/resources/faqs/' ) ) . '">common questions</a>' . '.</p>

<h2>When you are ready</h2>
<p>There is no obligation at any point before care starts, and no notice period to agree in advance. If you would like to talk it through, ' . '<a href="' . esc_url( home_url( '/contact-us/' ) ) . '">send us a message</a>' . ' or call the office — a conversation costs you nothing and usually makes the next step clearer.</p>',
		);
		if ( isset( $content[ $slug ] ) ) {
			return $content[ $slug ];
		}
		return '';
	}

	/**
	 * Update a seeded page's copy — but only while it is still the theme's own.
	 *
	 * ensure_pages() deliberately never touched post_content on an existing page,
	 * which is the right instinct (nothing should silently overwrite what an
	 * editor has written) but left the seeded copy frozen: improving the wording
	 * in get_default_page_content() changed nothing on any site where the pages
	 * already existed, so the defaults could never be maintained after launch.
	 *
	 * The fix is to tell the two cases apart rather than guess. At seed time the
	 * page records a hash of exactly what was written into it. On a later
	 * populate, if the current content still hashes to that value, nobody has
	 * touched it and it is safe to refresh; if it differs by so much as a comma,
	 * a human has been in there and it is left completely alone.
	 *
	 * Pages seeded before this meta existed carry no hash. Those get their
	 * current content recorded as the baseline and are NOT modified on that run —
	 * the conservative reading, since without a baseline there is no way to know
	 * whether the copy is original or edited. They become refreshable from the
	 * next populate onwards.
	 *
	 * @param WP_Post $page Existing page.
	 * @param string  $slug Page slug, used to look up the current default copy.
	 * @return bool True when the content was refreshed.
	 */
	private function refresh_seeded_content( $page, $slug ) {
		if ( ! $page instanceof WP_Post ) {
			return false;
		}

		$default = $this->get_default_page_content( $slug );
		if ( $default === '' ) {
			return false;
		}

		$current = (string) $page->post_content;
		$stored  = get_post_meta( $page->ID, self::SEEDED_HASH_META, true );

		// No baseline yet: record one, change nothing.
		if ( ! is_string( $stored ) || $stored === '' ) {
			update_post_meta( $page->ID, self::SEEDED_HASH_META, md5( $current ) );
			return false;
		}

		// Edited by a human, or already identical to the default — either way, leave it.
		if ( $stored !== md5( $current ) || $current === $default ) {
			return false;
		}

		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => $default,
			)
		);
		update_post_meta( $page->ID, self::SEEDED_HASH_META, md5( $default ) );

		return true;
	}

	/**
	 * Create or get all pages; store IDs in $this->page_ids.
	 */
	private function ensure_pages() {
		// Create in dependency order: home tree, then careers tree, then legal.
		$order = array(
			'home',
			'resources',
			'about-home-care-maidstone',
			'home-care-services-kent',
			'who-youll-meet',
			'contact-us',
			'news-and-updates',
			'care-guides',
			'faqs',
			'referral-information',
			'cqc-and-our-care',
			'getting-started',
			'careers',
			'professional-development',
			'current-vacancies',
			'working-for-us',
			'privacy-policy',
			'terms-and-conditions',
			'accessibility-statement',
			'cookies',
		);

		foreach ( $order as $slug ) {
			if ( ! isset( $this->pages[ $slug ] ) ) {
				continue;
			}
			$def = $this->pages[ $slug ];
			$parent_id = 0;
			if ( ! empty( $def['parent_slug'] ) && isset( $this->page_ids[ $def['parent_slug'] ] ) ) {
				$parent_id = (int) $this->page_ids[ $def['parent_slug'] ];
			}

			/*
			 * Look up by slug AND parent, not get_page_by_path( $slug ).
			 * get_page_by_path() expects a full hierarchical path, so a bare slug
			 * never matched any child page ("home/about-home-care-maidstone").
			 * Every populate run therefore fell through to the create branch and
			 * WordPress appended -2, -3, -4 … to dodge the slug collision, silently
			 * multiplying the page count on each run.
			 */
			$existing = get_posts(
				array(
					'post_type'        => 'page',
					'name'             => $slug,
					'post_parent'      => $parent_id,
					'post_status'      => array( 'publish', 'draft', 'pending', 'private' ),
					'numberposts'      => 1,
					'suppress_filters' => false,
				)
			);
			$page = ! empty( $existing ) ? $existing[0] : null;
			if ( $page ) {
				$this->page_ids[ $slug ] = (int) $page->ID;
				$this->mark_as_demo( $page->ID, 'page' );
				if ( ! empty( $def['template'] ) ) {
					update_post_meta( $page->ID, '_wp_page_template', $def['template'] );
				}
				$this->set_page_seo_meta( $page->ID, $def );
				$this->refresh_seeded_content( $page, $slug );
				continue;
			}

			$post_data = array(
				'post_type'    => 'page',
				'post_title'   => $def['title'],
				'post_name'    => $slug,
				'post_content' => $this->get_default_page_content( $slug ),
				'post_status'  => 'publish',
				'post_author'  => $this->get_author_id(),
				'post_parent'  => $parent_id,
			);
			$id = wp_insert_post( $post_data, true );
			if ( is_wp_error( $id ) ) {
				continue;
			}
			$this->page_ids[ $slug ] = (int) $id;
			$this->mark_as_demo( $id, 'page' );
			if ( ! empty( $def['template'] ) ) {
				update_post_meta( $id, '_wp_page_template', $def['template'] );
			}
			$this->set_page_seo_meta( $id, $def );
			update_post_meta( $id, self::SEEDED_HASH_META, md5( (string) $post_data['post_content'] ) );
		}

		// Persist careers page IDs for header menu switch.
		$careers_slugs = array( 'careers', 'professional-development', 'current-vacancies', 'working-for-us' );
		$this->careers_page_ids = array();
		foreach ( $careers_slugs as $cslug ) {
			if ( isset( $this->page_ids[ $cslug ] ) ) {
				$this->careers_page_ids[ $cslug ] = $this->page_ids[ $cslug ];
			}
		}
	}

	/**
	 * Set SEO title/description post meta from a page definition, if present.
	 *
	 * Read by CCS_SEO_Optimizer via the ccs_seo_title / ccs_meta_description meta
	 * keys. Without this, pages fall back to WordPress's raw post title and output
	 * no meta description at all — confirmed missing on Home before this fix
	 * (2026-08-19). Only writes keys that are defined, so hand-edited values in
	 * wp-admin are preserved for pages with no definition.
	 *
	 * @param int   $page_id Page post ID.
	 * @param array $def     Page definition (may contain 'seo_title', 'seo_description').
	 */
	private function set_page_seo_meta( $page_id, $def ) {
		if ( ! empty( $def['seo_title'] ) ) {
			update_post_meta( (int) $page_id, 'ccs_seo_title', $def['seo_title'] );
		}
		if ( ! empty( $def['seo_description'] ) ) {
			update_post_meta( (int) $page_id, 'ccs_meta_description', $def['seo_description'] );
		}
		// Visible <h1>. Several page titles are keyword strings ("About Home Care
		// Maidstone") that read badly as a heading; the slug still carries the keywords.
		if ( ! empty( $def['heading'] ) ) {
			update_post_meta( (int) $page_id, 'ccs_page_heading', $def['heading'] );
		}
		if ( ! empty( $def['intro'] ) ) {
			update_post_meta( (int) $page_id, 'ccs_page_intro', $def['intro'] );
		}
		// Page-header hero photo, read via has_post_thumbnail() in
		// template-parts/page-header.php. Backfill-only, like the service
		// images above — never overrides an image an editor already set.
		if ( ! empty( $def['hero_image'] ) && ! has_post_thumbnail( $page_id ) ) {
			$this->set_featured_image_from_theme( $page_id, $def['hero_image'], get_the_title( $page_id ) );
		}
	}

	/**
	 * Set Contact page content to consultation form shortcode (auto-insert on activation).
	 */
	private function ensure_contact_page_content() {
		$slug = 'contact-us';
		if ( ! isset( $this->page_ids[ $slug ] ) ) {
			return;
		}
		$id = (int) $this->page_ids[ $slug ];
		$content = '[ccs_consultation_form]';
		$page = get_post( $id );
		if ( ! $page || $page->post_type !== 'page' ) {
			return;
		}
		if ( $page->post_content === $content ) {
			return;
		}
		wp_update_post( array(
			'ID'           => $id,
			'post_content' => $content,
		) );
	}

	/**
	 * Create or get the three service posts (post type: service).
	 */
	private function ensure_services() {
		$this->dedupe_seeded_posts( 'service' );

		foreach ( $this->services as $post_name => $data ) {
			$existing = $this->find_seeded_post( 'service', $post_name, $data['title'] );
			if ( $existing ) {
				$id = $existing;
				$this->mark_as_demo( $id, 'service' );
				// Backfill only — never overrides a featured image an editor has
				// already set, but fixes it for posts created before this image
				// existed in $this->services (like every service post in any
				// pre-existing install).
				if ( ! empty( $data['image'] ) && ! has_post_thumbnail( $id ) ) {
					$this->set_featured_image_from_theme( $id, $data['image'], $data['title'] );
				}
				continue;
			}

			$id = wp_insert_post( array(
				'post_type'    => 'service',
				'post_title'   => $data['title'],
				'post_name'    => $post_name,
				'post_content' => $data['content'],
				'post_excerpt' => $data['excerpt'],
				'post_status'  => 'publish',
				'post_author'  => $this->get_author_id(),
			), true );
			if ( ! is_wp_error( $id ) ) {
				$this->mark_as_demo( $id, 'service' );
				if ( ! empty( $data['image'] ) ) {
					$this->set_featured_image_from_theme( $id, $data['image'], $data['title'] );
				}
			}
		}
	}

	/**
	 * Import a theme-bundled image (if not already imported) and set it as a
	 * post's featured image.
	 *
	 * @param int    $post_id       Post to attach the thumbnail to.
	 * @param string $relative_path Theme-relative image path.
	 * @param string $title         Attachment title if it needs importing.
	 */
	private function set_featured_image_from_theme( $post_id, $relative_path, $title = '' ) {
		$attach_id = $this->import_theme_image_as_attachment( $relative_path, $title );
		if ( $attach_id ) {
			set_post_thumbnail( $post_id, $attach_id );
		}
	}


	/**
	 * Seed the News & Updates section and clear WordPress's own sample content.
	 *
	 * The blog was the one section of the site left at WordPress defaults: the
	 * News & Updates index listed a single post titled "Hello world!" whose body
	 * invited the reader to start editing, and /sample-page/ was still live and
	 * indexable. On a site whose credibility rests on looking like a real,
	 * running care business, an untouched WordPress demo post is worse than an
	 * empty section — it says nobody has been here.
	 *
	 * Posts are seeded with real categories because the news templates label
	 * every card with one, and a category-less post shows a gap where the topic
	 * should be.
	 */
	private function ensure_news_posts() {
		$this->remove_default_wordpress_content();

		foreach ( $this->get_news_posts() as $post_name => $data ) {
			$existing = $this->find_seeded_post( 'post', $post_name, $data['title'] );
			if ( $existing ) {
				$this->mark_as_demo( $existing, 'post' );
				if ( ! empty( $data['image'] ) && ! has_post_thumbnail( $existing ) ) {
					$this->set_featured_image_from_theme( $existing, $data['image'], $data['title'] );
				}
				continue;
			}

			$id = wp_insert_post( array(
				'post_type'    => 'post',
				'post_title'   => $data['title'],
				'post_name'    => $post_name,
				'post_content' => $data['content'],
				'post_excerpt' => $data['excerpt'],
				'post_status'  => 'publish',
				'post_author'  => $this->get_author_id(),
				/*
				 * Staggered back from today so the index has a believable
				 * publishing rhythm and the "lead story" is genuinely the most
				 * recent, rather than four posts sharing one timestamp and
				 * ordering by ID.
				 */
				'post_date'    => gmdate( 'Y-m-d H:i:s', strtotime( '-' . (int) $data['days_ago'] . ' days' ) ),
			), true );

			if ( is_wp_error( $id ) ) {
				continue;
			}

			$this->mark_as_demo( $id, 'post' );

			$term = $this->ensure_category( $data['category'] );
			if ( $term ) {
				wp_set_post_categories( $id, array( $term ), false );
			}

			if ( ! empty( $data['image'] ) ) {
				$this->set_featured_image_from_theme( $id, $data['image'], $data['title'] );
			}
		}
	}

	/**
	 * Get (or create) a category by name, returning its term ID.
	 *
	 * @param string $name Category display name.
	 * @return int Term ID, or 0 on failure.
	 */
	private function ensure_category( $name ) {
		$existing = get_term_by( 'name', $name, 'category' );
		if ( $existing instanceof WP_Term ) {
			return (int) $existing->term_id;
		}

		$created = wp_insert_term( $name, 'category' );
		if ( is_wp_error( $created ) ) {
			return 0;
		}

		return isset( $created['term_id'] ) ? (int) $created['term_id'] : 0;
	}

	/**
	 * Trash WordPress's default "Hello world!" post and "Sample Page".
	 *
	 * Matched by the IDs WordPress assigns them on install (1 and 2) *and* by
	 * their content, then verified before removal: an install where someone has
	 * since edited post 1 into a real article must not lose it. Trashed rather
	 * than deleted, for the same reason as the de-duplication pass.
	 */
	private function remove_default_wordpress_content() {
		$candidates = array(
			1 => array( 'type' => 'post', 'title' => 'Hello world!' ),
			2 => array( 'type' => 'page', 'title' => 'Sample Page' ),
		);

		foreach ( $candidates as $id => $expected ) {
			$post = get_post( $id );
			if ( ! $post instanceof WP_Post ) {
				continue;
			}
			if ( $post->post_type !== $expected['type'] || $post->post_status === 'trash' ) {
				continue;
			}
			// Only remove it if it is still WordPress's untouched default.
			if ( strcasecmp( trim( $post->post_title ), $expected['title'] ) !== 0 ) {
				continue;
			}
			wp_trash_post( $id );
		}
	}

	/**
	 * News post definitions: slug => [title, excerpt, content, category, image, days_ago].
	 *
	 * A deliberate mix of the two things a care provider's news section is
	 * actually read for — practical advice from someone who has arranged care
	 * before, and evidence that the organisation is a real place where things
	 * happen. Competitor research (reference/competitor-functionality-teardown.md)
	 * found the same split on every benchmarked site.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	private function get_news_posts() {
		$funding_url  = home_url( '/resources/referral-information/' );
		$contact_url  = home_url( '/contact-us/' );
		$services_url = home_url( '/home-care-services-kent/' );
		$started_url  = home_url( '/getting-started/' );

		return array(
			'signs-a-relative-needs-help-at-home' => array(
				'title'    => 'The quiet signs a relative may need help at home',
				'category' => 'Care Advice',
				'image'    => 'assets/images/site-photos-extra/caregiver-supporting-patient.webp',
				'days_ago' => 6,
				'excerpt'  => 'Most families do not notice a single moment when a parent starts to struggle. It shows up in small things — the post piling up, the fridge half empty, the same story twice in one visit.',
				'content'  => '<p>Almost nobody rings us because of one dramatic event. Far more often it is a daughter who has driven home from a Sunday visit with a nagging feeling she cannot quite name, and who spends the week wondering whether she is worrying about nothing.</p>
<p>She usually is not. The early signs that someone is finding home harder are small, and they are easy to explain away one at a time.</p>
<h2>What families tend to notice first</h2>
<ul>
<li><strong>The house changes before the person does.</strong> Post stacking up unopened, bins not going out, a fridge with very little in it or a lot that is out of date.</li>
<li><strong>Meals get simpler.</strong> Toast and biscuits replace cooked food. Weight comes off slowly enough that it is only obvious in photographs.</li>
<li><strong>Repetition inside one conversation.</strong> Not forgetting a name — everyone does that — but telling you the same story twice in an afternoon.</li>
<li><strong>Clothes stay on longer.</strong> The same jumper on three visits running usually means washing and dressing have become an effort worth avoiding.</li>
<li><strong>Unexplained bruising,</strong> or a new habit of holding onto furniture while crossing a room.</li>
<li><strong>The world gets smaller.</strong> Clubs dropped, friends not rung, the car used less and less.</li>
</ul>
<h2>Why it is so hard to raise</h2>
<p>Because the person concerned is very often managing, and knows it, and does not want the conversation any more than you do. Independence is not a small thing to be asked to hand over, and most people push back hard the first time it is suggested — not because they disagree, but because of what agreeing seems to mean.</p>
<p>It helps to talk about the specific task rather than the general decline. "Shall we get someone in to help with the shower, because the bath is getting risky" is a conversation about a shower. "I think you need care" is a conversation about the rest of someone\'s life.</p>
<h2>Starting small usually works better</h2>
<p>A couple of hours a week is a real option, and it is where a lot of the families we support begin. It gives everyone a way to test the idea without anything feeling permanent, and it is far easier to increase support later than to recover from an arrangement that was pushed too fast.</p>
<p>If you are at the stage of wondering rather than deciding, our <a href="' . $started_url . '">getting started guide</a> walks through what happens next, and you are welcome to <a href="' . $contact_url . '">talk it through with us</a> without committing to anything.</p>',
			),
			'what-happens-at-a-care-assessment'   => array(
				'title'    => 'What actually happens at a home care assessment',
				'category' => 'Getting Started',
				'image'    => 'assets/images/site-photos/ccs-domiciliary-care.webp',
				'days_ago' => 19,
				'excerpt'  => 'People often expect something clinical and formal. In practice it is a conversation in your front room, usually over a cup of tea, about how you like your day to go.',
				'content'  => '<p>"Assessment" is an unhelpful word for what this is. It sounds like a test, and it makes people tidy the house and worry about giving the wrong answer. There is no wrong answer, and we are not marking anything.</p>
<p>What actually happens is that one of our managers comes to you, sits down, and asks about your day.</p>
<h2>What we talk about</h2>
<ul>
<li><strong>How your day runs now.</strong> When you like to get up, when you eat, what you can manage comfortably and what has started to take longer than it used to.</li>
<li><strong>Where help would make the most difference.</strong> This is often not what families expect. Sometimes it is the shower; sometimes it is having someone there while a spouse goes to their own hospital appointments.</li>
<li><strong>Health and medication,</strong> including anything a district nurse, GP or hospital team is already involved in, so nothing gets duplicated or missed.</li>
<li><strong>The house itself</strong> — stairs, the bathroom, where the trip hazards are, whether a small change to the layout would remove a risk entirely.</li>
<li><strong>Who else is around.</strong> Family nearby, neighbours who look in, anyone who already helps.</li>
<li><strong>What you would like the carer to be like.</strong> This matters more than people expect, and it is the part of the conversation we spend the most time on.</li>
</ul>
<h2>How long it takes</h2>
<p>Usually about an hour. Nothing is signed on the day, and there is no obligation at the end of it — a fair number of the assessments we do end with us pointing someone toward a different kind of support entirely, because that is what they actually needed.</p>
<h2>What happens afterwards</h2>
<p>We write up a care plan and send it to you. You read it, tell us what we have got wrong, and we change it. Then we match you with a small team of carers and introduce them before they start, so the first visit is not the first time you meet.</p>
<p>The plan is not fixed. We review it regularly and adjust as things change, which they do.</p>
<p>You can see the <a href="' . $services_url . '">types of care we provide</a>, or <a href="' . $contact_url . '">arrange an assessment</a> whenever you are ready.</p>',
			),
			'summer-at-laineys-care-farm'         => array(
				'title'    => 'A summer afternoon at Lainey\'s Care Farm',
				'category' => 'Company News',
				'image'    => 'assets/images/site-photos-extra/client-summer-party-laineys-care-farm.webp',
				'days_ago' => 33,
				'excerpt'  => 'Our summer party moved to Lainey\'s Care Farm this year — animals, a barbecue, and a good deal more dancing than anyone had planned for.',
				'content'  => '<p>Every summer we get everyone together — the people we support, their families, and the carers who see them week in and week out. This year we spent the afternoon at Lainey\'s Care Farm, and it was comfortably the best one we have done.</p>
<h2>How the day went</h2>
<p>The animals did most of the work. There is something about a paddock full of goats that dissolves the awkwardness of a room full of people who know each other well in one context and not at all in another. Families who had only ever spoken to their relative\'s carer on a doorstep spent the afternoon talking properly for the first time.</p>
<p>There was a barbecue, there was cake, and there was a stretch in the middle of the afternoon where somebody put music on and a good number of people who had firmly stated they would not be dancing were dancing.</p>
<h2>Why we keep doing it</h2>
<p>Home care is, by its nature, delivered one household at a time. That is the point of it — but it does mean the people we support can go a long time without meeting anyone else in the same position, and so can their families.</p>
<p>One afternoon a year does not fix that. It does, though, give people a few hours of being somewhere with others who understand the shape of their week without needing it explained.</p>
<p>Thank you to Lainey\'s Care Farm for having us, and to everyone who came. We are already looking at dates for next year.</p>',
			),
			'paying-for-home-care-in-kent'        => array(
				'title'    => 'Paying for home care in Kent: the questions families ask us most',
				'category' => 'Funding & Costs',
				'image'    => 'assets/images/site-photos-extra/client-creative-activity-autumn-leaves.webp',
				'days_ago' => 47,
				'excerpt'  => 'Cost is usually the first question and the one people feel most awkward asking. Here is how funding for home care in Kent actually works, in plain terms.',
				'content'  => '<p>People apologise for asking about money. They should not — it is a sensible question, it is usually the thing standing between a family and a decision, and a provider who is vague about it is telling you something.</p>
<p>Our rates are published in full on our homepage, including what is charged at a higher rate and what is not charged for at all. Below is the part that is less obvious: where the money comes from.</p>
<h2>The three ways home care gets paid for</h2>
<h3>1. Privately</h3>
<p>You arrange care directly and pay for it yourself. Most straightforward, and the fastest to set up — care can usually start within days rather than weeks.</p>
<h3>2. Through the local authority</h3>
<p>Kent County Council can arrange a needs assessment, which is free and which anyone can request. If you are assessed as needing care, a separate financial assessment decides how much of it the council contributes toward. Savings and capital above the national threshold generally mean paying in full; below it, the council contributes on a sliding scale.</p>
<p>Worth knowing: the needs assessment and the financial assessment are separate things. Having savings does not disqualify you from being assessed, and the assessment itself is useful even if you end up paying privately, because it puts what you need on record.</p>
<h3>3. Through the NHS</h3>
<p>Where someone\'s needs are primarily health-related rather than social, NHS Continuing Healthcare may cover the cost of care in full. It is assessed differently from local authority funding and is not means-tested. Complex care packages are the most common route to it.</p>
<h2>What tends to trip people up</h2>
<ul>
<li><strong>Assuming savings rule you out of everything.</strong> They affect local authority contributions. They do not affect NHS Continuing Healthcare, and they do not stop you having a needs assessment.</li>
<li><strong>Waiting for a crisis.</strong> Assessments take time. Starting the conversation before care is urgent gives you options that an emergency does not.</li>
<li><strong>Comparing hourly rates alone.</strong> Ask what is charged on bank holidays, what happens if a visit runs over, and whether there is a fee to set the package up. Those are where quoted rates and actual invoices tend to part company.</li>
</ul>
<p>We will talk you through which of these applies to your situation, including when the answer is that another provider or another kind of support fits you better. Our <a href="' . $funding_url . '">referral information</a> covers the process for professionals, and you can <a href="' . $contact_url . '">ask us directly</a> at any point.</p>',
			),
		);
	}

	/**
	 * Create or get the location posts (post type: location).
	 *
	 * Meta is only written on first creation (matches ensure_services()'s
	 * behaviour, and the same known limitation applies: editing $this->locations
	 * later won't touch posts that already exist).
	 */
	private function ensure_locations() {
		$this->dedupe_seeded_posts( 'location' );

		foreach ( $this->locations as $post_name => $data ) {
			$existing = $this->find_seeded_post( 'location', $post_name, $data['title'] );
			if ( $existing ) {
				$this->mark_as_demo( $existing, 'location' );
				continue;
			}

			$id = wp_insert_post( array(
				'post_type'    => 'location',
				'post_title'   => $data['title'],
				'post_name'    => $post_name,
				'post_content' => $data['content'],
				'post_excerpt' => $data['excerpt'],
				'post_status'  => 'publish',
				'post_author'  => $this->get_author_id(),
				'meta_input'   => isset( $data['meta'] ) ? $data['meta'] : array(),
			), true );
			if ( ! is_wp_error( $id ) ) {
				$this->mark_as_demo( $id, 'location' );
			}
		}
	}

	/**
	 * Find an already-seeded post by slug, falling back to an exact title match.
	 *
	 * The slug-only lookup this replaces could miss a post it had itself created
	 * moments earlier: if anything (a re-fired activation hook, a populate run
	 * landing in the same request, a slug already claimed elsewhere) made
	 * WordPress assign a suffixed slug like `aylesford-2`, the next lookup for
	 * `aylesford` found nothing and seeded the town a second time. That is how
	 * this site ended up with two Aylesford location pages on two live URLs and
	 * two West Malling pages fighting over one — duplicate content on exactly
	 * the pages whose whole purpose is ranking for a town name.
	 *
	 * Title is the right fallback key because it is what the duplicates actually
	 * share: WordPress will silently rewrite a colliding slug, but it never
	 * touches the title.
	 *
	 * @param string $post_type Post type to search.
	 * @param string $post_name Intended slug.
	 * @param string $title     Intended title.
	 * @return int Post ID, or 0 when none exists.
	 */
	private function find_seeded_post( $post_type, $post_name, $title ) {
		$by_slug = get_posts( array(
			'post_type'        => $post_type,
			'name'             => $post_name,
			'post_status'      => 'any',
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		) );
		if ( ! empty( $by_slug ) ) {
			return (int) $by_slug[0];
		}

		$by_title = get_posts( array(
			'post_type'        => $post_type,
			'title'            => $title,
			'post_status'      => 'any',
			'posts_per_page'   => 1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
		) );

		return ! empty( $by_title ) ? (int) $by_title[0] : 0;
	}

	/**
	 * Trash duplicate seeded posts of a type, keeping the earliest of each title.
	 *
	 * Hardening find_seeded_post() stops new duplicates being created, but does
	 * nothing about the ones already in the database — and duplicates are only a
	 * problem once they are published and indexable. Runs before seeding so a
	 * populate both cleans up and re-seeds in one pass.
	 *
	 * Trashed, not deleted: these are real posts that an editor may have written
	 * into, and a wrong guess here should be recoverable from the Trash rather
	 * than final. Only posts this theme seeded (marked via mark_as_demo) are
	 * considered, so a location page an editor added by hand is never touched.
	 *
	 * @param string $post_type Post type to de-duplicate.
	 * @return int Number of posts trashed.
	 */
	private function dedupe_seeded_posts( $post_type ) {
		$posts = get_posts( array(
			'post_type'        => $post_type,
			'post_status'      => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page'   => -1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'suppress_filters' => false,
			'meta_query'       => array(
				array(
					'key'   => self::DEMO_META_KEY,
					'value' => '1',
				),
			),
		) );

		$seen    = array();
		$trashed = 0;

		foreach ( $posts as $post ) {
			$key = strtolower( trim( $post->post_title ) );
			if ( $key === '' ) {
				continue;
			}
			if ( isset( $seen[ $key ] ) ) {
				wp_trash_post( $post->ID );
				++$trashed;
				continue;
			}
			$seen[ $key ] = $post->ID;
		}

		return $trashed;
	}

	/**
	 * Create Primary and Footer menus and assign to theme locations.
	 */
	private function ensure_menus() {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		$stored    = get_option( self::MENUS_OPTION, array() );

		$primary_term_id  = $this->get_or_create_menu( 'Primary', 'primary', $stored );
		$footer_term_id   = $this->get_or_create_menu( 'Footer', 'footer', $stored );
		$careers_term_id  = $this->get_or_create_menu( 'Careers', 'careers', $stored );

		$this->build_primary_menu( $primary_term_id );
		$this->build_footer_menu( $footer_term_id );
		$this->build_careers_menu( $careers_term_id );

		$locations['primary']  = $primary_term_id;
		$locations['footer']   = $footer_term_id;
		$locations['careers']  = $careers_term_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		update_option( self::MENUS_OPTION, array(
			'primary'  => $primary_term_id,
			'footer'   => $footer_term_id,
			'careers'  => $careers_term_id,
		) );
	}

	/**
	 * Get existing menu by location or name, or create one. Clears existing items so menu can be rebuilt.
	 *
	 * @param string $name     Menu name.
	 * @param string $location Theme location key.
	 * @param array  $stored   Stored term IDs from option.
	 * @return int Menu term_id.
	 */
	private function get_or_create_menu( $name, $location, $stored ) {
		if ( ! empty( $stored[ $location ] ) ) {
			$term = get_term( $stored[ $location ], 'nav_menu' );
			if ( $term && ! is_wp_error( $term ) ) {
				$this->clear_menu_items( $term->term_id );
				return (int) $term->term_id;
			}
		}

		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			if ( $menu->name === $name ) {
				$this->clear_menu_items( $menu->term_id );
				return (int) $menu->term_id;
			}
		}

		$term_id = wp_create_nav_menu( $name );
		return is_wp_error( $term_id ) ? 0 : (int) $term_id;
	}

	/**
	 * Remove all items from a nav menu.
	 *
	 * @param int $menu_id Nav menu term_id.
	 */
	private function clear_menu_items( $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );
		if ( ! is_array( $items ) ) {
			return;
		}
		foreach ( $items as $item ) {
			wp_delete_post( (int) $item->ID, true );
		}
	}

	/**
	 * Build Primary menu with Resources dropdown.
	 *
	 * @param int $menu_id Nav menu term_id.
	 */
	private function build_primary_menu( $menu_id ) {
		if ( ! $menu_id ) {
			return;
		}
		$position = 0;
		foreach ( $this->primary_order as $slug ) {
			if ( ! isset( $this->page_ids[ $slug ] ) ) {
				continue;
			}
			$title     = isset( $this->primary_menu_labels[ $slug ] ) ? $this->primary_menu_labels[ $slug ] : null;
			$parent_id = $this->add_menu_item( $menu_id, $this->page_ids[ $slug ], 0, $position, $title );
			$position++;

			if ( ! isset( $this->primary_menu_children[ $slug ] ) ) {
				continue;
			}
			foreach ( $this->primary_menu_children[ $slug ] as $child_slug ) {
				if ( ! isset( $this->page_ids[ $child_slug ] ) ) {
					continue;
				}
				$child_title = isset( $this->primary_menu_labels[ $child_slug ] ) ? $this->primary_menu_labels[ $child_slug ] : null;
				$this->add_menu_item( $menu_id, $this->page_ids[ $child_slug ], $parent_id, $position, $child_title );
				$position++;
			}
		}
	}

	/**
	 * Build Careers menu (flat list: hub, professional development, current vacancies, working for us).
	 *
	 * @param int $menu_id Nav menu term_id.
	 */
	private function build_careers_menu( $menu_id ) {
		if ( ! $menu_id ) {
			return;
		}
		$careers_order = array( 'careers', 'professional-development', 'current-vacancies', 'working-for-us' );
		$labels = array(
			'careers'                 => 'Careers',
			'professional-development' => 'Professional Development',
			'current-vacancies'       => 'Current Vacancies',
			'working-for-us'          => 'Working for Us',
		);
		$position = 0;
		foreach ( $careers_order as $slug ) {
			if ( isset( $this->page_ids[ $slug ] ) ) {
				$title = isset( $labels[ $slug ] ) ? $labels[ $slug ] : null;
				$this->add_menu_item( $menu_id, $this->page_ids[ $slug ], 0, $position, $title );
				$position++;
			}
		}
	}

	/**
	 * Build Footer menu (flat list).
	 *
	 * @param int $menu_id Nav menu term_id.
	 */
	private function build_footer_menu( $menu_id ) {
		if ( ! $menu_id ) {
			return;
		}
		$position = 0;
		foreach ( $this->footer_order as $slug ) {
			if ( isset( $this->page_ids[ $slug ] ) ) {
				$this->add_menu_item( $menu_id, $this->page_ids[ $slug ], 0, $position );
				$position++;
			}
		}
	}

	/**
	 * Add a page as nav menu item.
	 *
	 * @param int         $menu_id   Nav menu term_id.
	 * @param int         $object_id Page (or post) ID.
	 * @param int         $parent    Parent menu item DB ID (0 for top-level).
	 * @param int         $position  Menu order.
	 * @param string|null $title     Optional. Navigation label; if omitted, WordPress uses the page title.
	 * @return int Menu item post ID.
	 */
	private function add_menu_item( $menu_id, $object_id, $parent, $position, $title = null ) {
		$item = array(
			'menu-item-object-id' => $object_id,
			'menu-item-object'    => 'page',
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
			'menu-item-parent-id' => $parent,
		);
		if ( $title !== null && $title !== '' ) {
			$item['menu-item-title'] = $title;
		}
		return wp_update_nav_menu_item( $menu_id, 0, $item );
	}

	/**
	 * Give the seeding author a display name fit to appear as a byline.
	 *
	 * News articles carry "By {author}" and publish a schema.org author node, so
	 * the author's display name is public-facing copy on this site rather than
	 * an internal label. Left at the WordPress default it read "By admin" on
	 * every article and shipped `"author": {"name": "admin"}` to search engines —
	 * which undercuts an article about arranging care for a relative more than
	 * having no byline would.
	 *
	 * Only touched while it is still the username-derived default, so a real
	 * name a person has set is never overwritten. Falls back to the business
	 * name because these are provider updates published by the organisation.
	 */
	private function ensure_author_display_name() {
		$user_id = $this->get_author_id();
		if ( ! $user_id ) {
			return;
		}

		$user = get_userdata( $user_id );
		if ( ! $user instanceof WP_User ) {
			return;
		}

		$display  = trim( (string) $user->display_name );
		$defaults = array( '', strtolower( $user->user_login ), 'admin', 'administrator' );
		if ( ! in_array( strtolower( $display ), $defaults, true ) ) {
			return;
		}

		$name = get_option( 'blogname', '' );
		$name = is_string( $name ) && trim( $name ) !== '' ? trim( $name ) : 'Continuity Care Services';

		wp_update_user( array(
			'ID'           => $user_id,
			'display_name' => $name,
		) );
	}

	/**
	 * Set Reading: static front page = Home, posts page = News & Updates.
	 */
	private function ensure_reading_settings() {
		if ( isset( $this->page_ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $this->page_ids['home'] );
		}
		if ( isset( $this->page_ids['news-and-updates'] ) ) {
			update_option( 'page_for_posts', $this->page_ids['news-and-updates'] );
		}
	}

	/**
	 * Set permalink structure to /%postname%/ and flush rules.
	 */
	private function ensure_permalinks() {
		if ( get_option( 'permalink_structure' ) !== '/%postname%/' ) {
			update_option( 'permalink_structure', '/%postname%/' );
			flush_rewrite_rules();
		}
	}

	/**
	 * Mark a post as demo content (for reset).
	 *
	 * @param int    $post_id Post ID.
	 * @param string $type    Optional. 'page' or 'service'.
	 */
	private function mark_as_demo( $post_id, $type = 'page' ) {
		update_post_meta( (int) $post_id, self::DEMO_META_KEY, 1 );
	}

	/**
	 * Get first available admin user ID.
	 *
	 * @return int
	 */
	private function get_author_id() {
		$users = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
		return ! empty( $users ) ? (int) $users[0]->ID : 1;
	}

	/**
	 * Import a theme-bundled image into the media library, so it can be used as a
	 * real featured image (has_post_thumbnail(), get_the_post_thumbnail(), etc.)
	 * rather than a hardcoded <img> tag. Idempotent: re-running activation finds
	 * the same attachment by its recorded source path instead of duplicating it.
	 *
	 * @param string $relative_path Path relative to the theme root, e.g. 'assets/images/site-photos/foo.webp'.
	 * @param string $title         Optional attachment title.
	 * @return int Attachment ID, or 0 on failure.
	 */
	private function import_theme_image_as_attachment( $relative_path, $title = '' ) {
		$existing = get_posts( array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => '_ccs_source_theme_path',
			'meta_value'     => $relative_path,
		) );
		if ( ! empty( $existing ) ) {
			return (int) $existing[0]->ID;
		}

		$source_path = get_template_directory() . '/' . $relative_path;
		if ( ! file_exists( $source_path ) ) {
			return 0;
		}

		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) {
			return 0;
		}

		$filename        = wp_unique_filename( $upload_dir['path'], wp_basename( $source_path ) );
		$destination     = trailingslashit( $upload_dir['path'] ) . $filename;
		$file_contents   = file_get_contents( $source_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( $file_contents === false || file_put_contents( $destination, $file_contents ) === false ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			return 0;
		}

		$filetype   = wp_check_filetype( $filename, null );
		$attach_id  = wp_insert_attachment(
			array(
				'post_mime_type' => $filetype['type'],
				'post_title'     => $title !== '' ? $title : preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'    => $this->get_author_id(),
			),
			$destination
		);
		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			return 0;
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}
		wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $destination ) );
		update_post_meta( $attach_id, '_ccs_source_theme_path', $relative_path );

		return (int) $attach_id;
	}

	/**
	 * Register admin menu for Reset Demo Content.
	 * Skipped when CCS_Welcome_Screen is present (it registers "CCS Theme Setup" under Appearance).
	 */
	public function register_admin_menu() {
		if ( class_exists( 'CCS_Welcome_Screen' ) ) {
			return;
		}
		add_theme_page(
			__( 'Theme Setup', 'ccs-wp-theme' ),
			__( 'Theme Setup', 'ccs-wp-theme' ),
			'manage_options',
			'ccs-theme-setup',
			array( $this, 'render_setup_page' )
		);
	}

	/**
	 * Handle Populate (pages / services / locations / menus / entire) from Welcome Screen.
	 */
	public function handle_populate_request() {
		if ( ! isset( $_GET['ccs_populate'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}
		$scope = sanitize_text_field( wp_unslash( $_GET['ccs_populate'] ) );
		$allowed = array( 'pages', 'services', 'locations', 'news', 'menus', 'entire' );
		if ( ! in_array( $scope, $allowed, true ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccs_populate_' . $scope ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'ccs-wp-theme' ) );
		}
		if ( $scope === 'entire' ) {
			$this->run_with_scope( self::SCOPES );
		} elseif ( $scope === 'pages' ) {
			$this->run_with_scope( array( 'general', 'pages' ) );
		} elseif ( $scope === 'services' ) {
			$this->run_with_scope( array( 'services' ) );
		} elseif ( $scope === 'locations' ) {
			$this->run_with_scope( array( 'locations' ) );
		} elseif ( $scope === 'news' ) {
			$this->run_with_scope( array( 'news' ) );
		} else {
			$this->run_with_scope( array( 'menus', 'reading', 'permalinks' ) );
		}
		wp_safe_redirect( add_query_arg( 'ccs_populate_done', $scope, admin_url( 'themes.php?page=ccs-theme-setup' ) ) );
		exit;
	}

	/**
	 * Handle Reset Demo Content button (nonce + redirect).
	 */
	public function handle_reset_request() {
		if ( ! isset( $_GET['ccs_reset_demo'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccs_reset_demo' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'ccs-wp-theme' ) );
		}
		$this->reset_demo_content();
		wp_safe_redirect( add_query_arg( 'ccs_demo_reset', '1', admin_url( 'themes.php?page=ccs-theme-setup' ) ) );
		exit;
	}

	/**
	 * Delete all demo pages/posts and menus, then re-run setup.
	 */
	private function reset_demo_content() {
		global $wpdb;

		$post_ids = $wpdb->get_col( $wpdb->prepare(
			"SELECT p.ID FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = %s AND pm.meta_value = '1'
			WHERE p.post_type IN ('page', 'post', 'service', 'location')",
			self::DEMO_META_KEY
		) );
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $id ) {
				wp_delete_post( (int) $id, true );
			}
		}

		$stored = get_option( self::MENUS_OPTION, array() );
		foreach ( array( 'primary', 'footer', 'careers' ) as $loc ) {
			if ( ! empty( $stored[ $loc ] ) ) {
				wp_delete_nav_menu( (int) $stored[ $loc ] );
			}
		}
		delete_option( self::MENUS_OPTION );
		delete_option( self::PAGE_IDS_OPTION );
		delete_option( self::CAREERS_PAGE_OPTION );

		$this->page_ids        = array();
		$this->careers_page_ids = array();
		$this->run();
	}

	/**
	 * Render Theme Setup admin page with Reset button.
	 */
	public function render_setup_page() {
		$reset_url = wp_nonce_url( add_query_arg( 'ccs_reset_demo', '1', admin_url( 'themes.php?page=ccs-theme-setup' ) ), 'ccs_reset_demo' );
		$message   = isset( $_GET['ccs_demo_reset'] ) ? __( 'Demo content has been reset and recreated.', 'ccs-wp-theme' ) : '';
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Theme Setup', 'ccs-wp-theme' ); ?></h1>
			<?php if ( $message ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
			<?php endif; ?>
			<p><?php esc_html_e( 'On theme activation, the theme creates demo pages, service posts, menus, and configures Reading and permalinks. You can reset that demo content below and have it recreated.', 'ccs-wp-theme' ); ?></p>
			<p>
				<a href="<?php echo esc_url( $reset_url ); ?>" class="button button-primary" onclick="return confirm('<?php echo esc_js( __( 'This will delete all demo pages, the three care service posts, and the Primary/Footer menus, then recreate them. Continue?', 'ccs-wp-theme' ) ); ?>');"><?php esc_html_e( 'Reset Demo Content', 'ccs-wp-theme' ); ?></a>
			</p>
		</div>
		<?php
	}
}
