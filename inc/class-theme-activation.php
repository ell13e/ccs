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
	const MENUS_OPTION        = 'ccs_theme_activation_menus';
	const PAGE_IDS_OPTION     = 'ccs_theme_activation_page_ids';
	const CAREERS_PAGE_OPTION = 'ccs_careers_page_ids';

	/** Scope steps for run_with_scope(). */
	const SCOPES = array( 'general', 'pages', 'contact_page', 'services', 'locations', 'menus', 'reading', 'permalinks' );

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
		'care-guides'                 => array( 'title' => 'Care Guides', 'parent_slug' => 'resources', 'template' => 'page-templates/template-care-guides.php' ),
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
			'seo_title'       => 'Careers in Care',
			'seo_description' => 'Join our Kent home care team. Flexible hours, ongoing training and real support — see current vacancies and what it\'s like to work with us.',
		),
		'professional-development'    => array( 'title' => 'Professional Development', 'parent_slug' => 'careers' ),
		'current-vacancies'           => array( 'title' => 'Current Vacancies', 'parent_slug' => 'careers', 'template' => 'page-templates/template-current-vacancies.php' ),
		'working-for-us'              => array( 'title' => 'Working for Us', 'parent_slug' => 'careers' ),
		// Optional care pages (Section 7).
		'cqc-and-our-care'            => array( 'title' => 'CQC and Our Care', 'template' => 'page-templates/template-cqc.php' ),
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
				'excerpt' => 'Getting dressed. Making breakfast. Remembering the right meds at the right time. Our carers provide gentle assistance with everyday tasks, ensuring care calls are always scheduled to fit your daily routine.',
				'content' => "<p>At Continuity Care Services, we understand that sometimes you just need a helping hand with daily tasks. Our domiciliary care gives you steady, unhurried support at home.</p>
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
				'excerpt' => "Whether it's for a few hours or a few days, our team are here to step in and provide a client's family and friends with gentle, reliable respite support. Take some time to rest, you can't pour from an empty cup.",
				'content' => "<p>Caring for someone you love is rewarding, and it's also exhausting. Everyone needs a break. Our respite care services give family carers the time they need to rest, recharge, or simply take care of themselves.</p>
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
				'excerpt' => 'From epilepsy care to PEG and mobility support, we provide complex care at home. We work closely with families, nurses and healthcare teams to ensure we get it right, every time.',
				'content' => "<p>Complex care requires specialist knowledge, clinical skills, and unwavering attention to detail. At Continuity Care Services, our trained care team provides expert support for individuals with complex health needs.</p>
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
				'excerpt' => 'Home care across Maidstone and the surrounding villages, delivered by a small team who get to know you, not just your care plan.',
				'content' => '<p>Maidstone is where Continuity of Care Services is based, so it\'s the area our team knows best — which GP surgeries run late clinics, which pharmacies deliver, how the traffic moves at different times of day. That local knowledge means less time explaining and more time getting on with your day.</p>
<p>We support families across the town and in the villages around it — Bearsted, Loose, Barming, Coxheath and Detling among them — with everything from a short daily visit to round-the-clock complex care. Whatever the need, you\'re matched with the same small team, not a rotating rota.</p>',
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
	 * @param array $scope Steps: general, pages, contact_page, services, locations, menus, reading, permalinks.
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
	 * Set General settings from content guide (only if still at defaults).
	 */
	private function ensure_general_settings() {
		$blogname = get_option( 'blogname', '' );
		if ( $blogname === '' || $blogname === 'Just another WordPress site' ) {
			update_option( 'blogname', 'Continuity Care Services' );
		}
		$blogdescription = get_option( 'blogdescription', '' );
		if ( $blogdescription === '' || $blogdescription === 'Just another WordPress site' ) {
			update_option( 'blogdescription', 'Home Care in Maidstone & Kent - Your Team, Your Time, Your Life' );
		}
		$admin_email = get_option( 'admin_email', '' );
		if ( $admin_email === '' || strpos( $admin_email, 'wordpress' ) !== false ) {
			update_option( 'admin_email', 'office@continuitycareservices.co.uk' );
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
			'home-care-services-kent' => '<p>Whether you need a little help dressing in the mornings, round-the-clock complex care, or just someone to pop in for a cuppa and a catch-up, we\'re here to make life feel a little lighter.</p>
<p>Every care plan starts the same way: a free conversation about what would actually help, at your pace. Nothing is fixed until you are happy with it.</p>',
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
			'resources' => '<p>Here you\'ll find practical information about our home care services in Maidstone and Kent: care guides to help you understand your options, answers to frequently asked questions, and referral information for health and social care professionals.</p>
<ul>
<li><a href="' . esc_url( home_url( '/care-guides/' ) ) . '">Care Guides</a> – understanding home care, care planning and what to expect</li>
<li><a href="' . esc_url( home_url( '/faqs/' ) ) . '">FAQs</a> – common questions about our care, areas, and how we work</li>
<li><a href="' . esc_url( home_url( '/referral-information/' ) ) . '">Referral Information</a> – for GPs, social workers and other professionals</li>
</ul>
<p>If you can\'t find what you need, <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a> and we\'ll be happy to help.</p>',
			'care-guides' => '<p>Educational resources to help you understand home care options, care planning and what to expect when you or a family member need support at home.</p>
<h2>Understanding home care</h2>
<p>Home care (domiciliary care) means support in your own home – from a few hours a week to round-the-clock care. It can include help with personal care, medication, meals, companionship and household tasks. We work with you to create a plan that fits your life.</p>
<h2>Care planning</h2>
<p>We start with a consultation to understand your needs, preferences and goals. From that we build a care plan that we review regularly. You and your family are at the centre of every decision.</p>
<h2>What to expect</h2>
<p>You\'ll meet a small, consistent team who get to know you. We focus on the same faces and routines so care feels familiar and reliable. For more detail, see our <a href="' . esc_url( home_url( '/faqs/' ) ) . '">FAQs</a> or <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">get in touch</a>.</p>',
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
			'professional-development' => '<p>We invest in our team. From induction and mandatory training to specialist qualifications, we support your growth so you can deliver the best care and progress your career.</p>
<h2>Development opportunities</h2>
<p>Training, mentorship, and clear progression paths. More detail can be added here.</p>
<p><a href="' . esc_url( home_url( '/careers/current-vacancies/' ) ) . '">View current vacancies</a></p>',
			'current-vacancies' => '<p>Browse our current vacancies and apply online. We\'re always looking for caring, reliable people to join our team across Maidstone and Kent.</p>
<p>Use the job portal below to see open roles and submit your application.</p>',
			'working-for-us' => '<p>What it\'s like to work at Continuity Care Services: our culture, benefits, and the day-to-day reality of supporting clients and families.</p>
<h2>Benefits</h2>
<ul>
<li>Flexible hours to suit your life</li>
<li>Competitive pay and holiday</li>
<li>Training and career development</li>
<li>Supportive, local team</li>
</ul>
<p><a href="' . esc_url( home_url( '/careers/current-vacancies/' ) ) . '">View current vacancies</a> or contact us to learn more.</p>',
			'cqc-and-our-care' => '<p>Continuity Care Services is registered with the Care Quality Commission (CQC). We are committed to delivering safe, effective, caring, responsive and well-led care. Our CQC rating reflects how we\'re performing.</p>
<p>We believe the best care doesn\'t stop at the checklist – it shows in our carers turning up in a way that feels friendly and familiar. If you\'d like to know more about how we work or our latest inspection, view our CQC profile below or get in touch.</p>',
			'getting-started' => '<p>Taking the first step towards home care can feel overwhelming. We\'re here to make it straightforward.</p>
<p>After you get in touch, we\'ll arrange a no-obligation consultation to understand your situation, answer your questions and discuss how we can help. Together we\'ll agree a care plan that fits your life. When you\'re ready, we\'ll introduce you to your care team and get started.</p>
<p><strong>Ready to talk?</strong> <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Contact us</a> to book a care consultation or find out more.</p>',
		);
		if ( isset( $content[ $slug ] ) ) {
			return $content[ $slug ];
		}
		return '';
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
		foreach ( $this->services as $post_name => $data ) {
			$existing = get_posts( array(
				'post_type'      => 'service',
				'name'           => $post_name,
				'post_status'    => 'any',
				'posts_per_page' => 1,
			) );
			if ( ! empty( $existing ) ) {
				$id = $existing[0]->ID;
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
	 * Create or get the location posts (post type: location).
	 *
	 * Meta is only written on first creation (matches ensure_services()'s
	 * behaviour, and the same known limitation applies: editing $this->locations
	 * later won't touch posts that already exist).
	 */
	private function ensure_locations() {
		foreach ( $this->locations as $post_name => $data ) {
			$existing = get_posts( array(
				'post_type'      => 'location',
				'name'           => $post_name,
				'post_status'    => 'any',
				'posts_per_page' => 1,
			) );
			if ( ! empty( $existing ) ) {
				$this->mark_as_demo( $existing[0]->ID, 'location' );
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
		$allowed = array( 'pages', 'services', 'locations', 'menus', 'entire' );
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
			WHERE p.post_type IN ('page', 'service', 'location')",
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
