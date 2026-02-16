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
	const SCOPES = array( 'general', 'pages', 'contact_page', 'services', 'menus', 'reading', 'permalinks' );

	/**
	 * Page definitions: slug => [ 'title', 'parent_slug' (optional), 'template' (optional) ].
	 *
	 * @var array<string, array>
	 */
	private $pages = array(
		'home'                        => array( 'title' => 'Home', 'template' => 'page-templates/template-homepage.php' ),
		'about-home-care-maidstone'   => array( 'title' => 'About Home Care Maidstone', 'parent_slug' => 'home', 'template' => 'page-templates/template-about.php' ),
		'home-care-services-kent'     => array( 'title' => 'Home Care Services Kent', 'parent_slug' => 'home' ),
		'who-youll-meet'              => array( 'title' => "Who You'll Meet", 'parent_slug' => 'home' ),
		'care-careers-maidstone-kent' => array( 'title' => 'Care Careers Maidstone Kent', 'parent_slug' => 'home' ),
		'contact-us'                  => array( 'title' => 'Contact Us', 'parent_slug' => 'home', 'template' => 'page-templates/template-contact.php' ),
		'resources'                   => array( 'title' => 'Resources', 'parent_slug' => 'home' ),
		'care-guides'                 => array( 'title' => 'Care Guides', 'parent_slug' => 'resources', 'template' => 'page-templates/template-care-guides.php' ),
		'faqs'                        => array( 'title' => 'FAQs', 'parent_slug' => 'resources', 'template' => 'page-templates/template-faqs.php' ),
		'referral-information'        => array( 'title' => 'Referral Information', 'parent_slug' => 'resources' ),
		'news-and-updates'            => array( 'title' => 'News & Updates', 'parent_slug' => 'home' ),
		'privacy-policy'              => array( 'title' => 'Privacy Policy', 'template' => 'page-templates/template-content-page.php' ),
		'terms-and-conditions'        => array( 'title' => 'Terms & Conditions', 'template' => 'page-templates/template-content-page.php' ),
		'accessibility-statement'     => array( 'title' => 'Accessibility Statement', 'template' => 'page-templates/template-content-page.php' ),
		'cookies'                     => array( 'title' => 'Cookie Policy', 'template' => 'page-templates/template-content-page.php' ),
		// Careers section (mini-site).
		'careers'                     => array( 'title' => 'Careers', 'template' => 'page-templates/template-careers.php' ),
		'professional-development'    => array( 'title' => 'Professional Development', 'parent_slug' => 'careers' ),
		'current-vacancies'           => array( 'title' => 'Current Vacancies', 'parent_slug' => 'careers', 'template' => 'page-templates/template-current-vacancies.php' ),
		'working-for-us'              => array( 'title' => 'Working for Us', 'parent_slug' => 'careers' ),
		// Optional care pages (Section 7).
		'cqc-and-our-care'            => array( 'title' => 'CQC and Our Care', 'parent_slug' => 'home', 'template' => 'page-templates/template-cqc.php' ),
		'getting-started'              => array( 'title' => 'Getting Started', 'parent_slug' => 'home', 'template' => 'page-templates/template-getting-started.php' ),
	);

	/**
	 * Service posts: post_name => [ 'title', 'excerpt', 'content' ].
	 *
	 * @var array<string, array>
	 */
	private $services = array();

	/**
	 * Primary menu item order (slug or special key). Children under 'resources' for dropdown.
	 *
	 * @var array
	 */
	private $primary_order = array(
		'home',
		'about-home-care-maidstone',
		'home-care-services-kent',
		'who-youll-meet',
		'careers',
		'resources',
		'news-and-updates',
		'cqc-and-our-care',
		'getting-started',
		'contact-us',
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
	}

	/**
	 * Define the three care service posts (title, excerpt, content).
	 */
	private function define_services() {
		$this->services = array(
			'domiciliary-care' => array(
				'title'   => 'Domiciliary Care',
				'excerpt' => 'Getting dressed. Making breakfast. Remembering the right meds at the right time. Our carers provide gentle assistance with everyday tasks, ensuring care calls are always scheduled to fit your daily routine.',
				'content' => "<p>At Continuity Care Services, we understand that sometimes you just need a helping hand with daily tasks. Our domiciliary care services provide compassionate support in the comfort of your own home.</p>
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
				'excerpt' => "Whether it's for a few hours or a few days, our team are here to step in and provide a client's family and friends with gentle, reliable respite support. Take some time to rest, you can't pour from an empty cup.",
				'content' => "<p>Caring for a loved one is rewarding, but it's also exhausting. Everyone needs a break. Our respite care services give family carers the time they need to rest, recharge, or simply take care of themselves.</p>
<h2>Flexible Respite Options:</h2>
<ul>
<li>Short breaks (a few hours to a day)</li>
<li>Extended respite (overnight or multiple days)</li>
<li>Regular scheduled breaks (weekly, fortnightly)</li>
<li>Emergency respite care</li>
<li>Holiday cover for regular carers</li>
</ul>
<h2>Seamless Continuity of Care</h2>
<p>We take time to understand your loved one's routines, preferences, and needs so the transition is smooth. Your family member will receive the same high standard of care they're used to.</p>
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
				'excerpt' => 'From epilepsy care to PEG and mobility support, we provide complex care in the comfort of your own home. We work closely with families, nurses and healthcare teams to ensure we get it right, every time.',
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
<h2>Person-Centred, Not Just Medical</h2>
<p>Yes, we're experts in clinical care. But we're also committed to ensuring our clients live full, happy lives. We support hobbies, outings, social connections, and everything that makes life worth living.</p>",
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
	 * @param array $scope Steps: general, pages, contact_page, services, menus, reading, permalinks.
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
			'about-home-care-maidstone' => '<p>Reliably supporting adults and children across Maidstone and Kent, we\'re here to provide personalised care, day or night, tailored to you. <strong>Our caring, local team is dedicated to supporting families across Kent.</strong></p>
<p>We don\'t rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day.</p>
<p>We believe that the best care doesn\'t stop when the to-do list is ticked; it continues through our staff showing up in a way that feels friendly, familiar, and person-centred. <strong>Learn more about the home care services we offer in Maidstone &amp; Kent.</strong></p>',
			'home-care-services-kent' => '<p>Whether you need a little help dressing in the mornings, round-the-clock complex care, or just someone to pop in for a cuppa and a catch-up, we\'re here to make life feel a little lighter.</p>
<p>We offer three main types of home care across Maidstone and Kent:</p>
<ul>
<li><a href="' . esc_url( home_url( '/services/domiciliary-care/' ) ) . '">Domiciliary Care</a> – day-to-day support with personal care, medication, meals and companionship</li>
<li><a href="' . esc_url( home_url( '/services/respite-care/' ) ) . '">Respite Care</a> – short breaks for family carers, from a few hours to extended stays</li>
<li><a href="' . esc_url( home_url( '/services/complex-care/' ) ) . '">Complex Care</a> – specialist clinical care at home, from epilepsy to PEG feeding and 24/7 support</li>
</ul>
<p>For expert home care Maidstone families trust, get in touch today and we\'ll create a plan tailored to your needs.</p>',
			'who-youll-meet' => '<p>Our team is at the heart of everything we do. You\'ll meet familiar, friendly faces who take the time to get to know you.</p>
<h2>Meet the team</h2>
<p>Keelie Varney and Nikki Mackay lead our care team with a focus on consistency, compassion and person-centred support. We invest in training and values so every member of our team delivers the same high standard of care.</p>
<p>We\'re here to make life feel a little lighter – your team, your time, your life.</p>',
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
			'careers' => '<p>Make a real impact by joining our team. We offer rewarding roles, flexible hours, and ongoing training. If you\'re passionate about helping others, explore how you can grow your career with us.</p>
<h2>Why join us</h2>
<ul>
<li>Flexible working hours</li>
<li>Competitive pay rates</li>
<li>Ongoing training and professional development</li>
<li>Supportive team environment</li>
<li>Make a real difference in people\'s lives</li>
</ul>
<p><a href="' . esc_url( home_url( '/current-vacancies/' ) ) . '">View current vacancies</a> or get in touch to find out more.</p>',
			'professional-development' => '<p>We invest in our team. From induction and mandatory training to specialist qualifications, we support your growth so you can deliver the best care and progress your career.</p>
<h2>Development opportunities</h2>
<p>Training, mentorship, and clear progression paths. More detail can be added here.</p>
<p><a href="' . esc_url( home_url( '/current-vacancies/' ) ) . '">View current vacancies</a></p>',
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
<p><a href="' . esc_url( home_url( '/current-vacancies/' ) ) . '">View current vacancies</a> or contact us to learn more.</p>',
			'cqc-and-our-care' => '<p>Continuity Care Services is registered with the Care Quality Commission (CQC). We are committed to delivering safe, effective, caring, responsive and well-led care. Our CQC rating reflects how we\'re performing.</p>
<p>We believe the best care doesn\'t stop at the checklist – it shows in our staff turning up in a way that feels friendly, familiar and person-centred. If you\'d like to know more about how we work or our latest inspection, view our CQC profile below or get in touch.</p>',
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

			$page = get_page_by_path( $slug, OBJECT, 'page' );
			if ( $page ) {
				$this->page_ids[ $slug ] = (int) $page->ID;
				$this->mark_as_demo( $page->ID, 'page' );
				if ( ! empty( $def['template'] ) ) {
					update_post_meta( $page->ID, '_wp_page_template', $def['template'] );
				}
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
				$this->mark_as_demo( $existing[0]->ID, 'service' );
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
		$resource_children = array( 'care-guides', 'faqs', 'referral-information' );
		$position = 0;
		foreach ( $this->primary_order as $slug ) {
			$title = isset( $this->primary_menu_labels[ $slug ] ) ? $this->primary_menu_labels[ $slug ] : null;
			if ( $slug === 'resources' ) {
				$parent_id = $this->add_menu_item( $menu_id, $this->page_ids['resources'], 0, $position, $title );
				$position++;
				foreach ( $resource_children as $child_slug ) {
					if ( isset( $this->page_ids[ $child_slug ] ) ) {
						$child_title = isset( $this->primary_menu_labels[ $child_slug ] ) ? $this->primary_menu_labels[ $child_slug ] : null;
						$this->add_menu_item( $menu_id, $this->page_ids[ $child_slug ], $parent_id, $position, $child_title );
						$position++;
					}
				}
				continue;
			}
			if ( isset( $this->page_ids[ $slug ] ) ) {
				$this->add_menu_item( $menu_id, $this->page_ids[ $slug ], 0, $position, $title );
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
	 * Handle Populate (pages / services / menus / entire) from Welcome Screen.
	 */
	public function handle_populate_request() {
		if ( ! isset( $_GET['ccs_populate'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}
		$scope = sanitize_text_field( wp_unslash( $_GET['ccs_populate'] ) );
		$allowed = array( 'pages', 'services', 'menus', 'entire' );
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
			WHERE p.post_type IN ('page', 'service')",
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
