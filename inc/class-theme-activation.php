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

	const DEMO_META_KEY = '_ccs_demo_content';
	const MENUS_OPTION  = 'ccs_theme_activation_menus';

	/**
	 * Page definitions: slug => [ 'title', 'parent_slug' (optional), 'template' (optional) ].
	 *
	 * @var array<string, array>
	 */
	private $pages = array(
		'home'                        => array( 'title' => 'Home', 'template' => 'page-templates/template-homepage.php' ),
		'about-home-care-maidstone'   => array( 'title' => 'About Home Care Maidstone', 'parent_slug' => 'home', 'template' => 'page-templates/template-about.php' ),
		'home-care-services-kent'      => array( 'title' => 'Home Care Services Kent', 'parent_slug' => 'home' ),
		'who-youll-meet'              => array( 'title' => "Who You'll Meet", 'parent_slug' => 'home' ),
		'care-careers-maidstone-kent'  => array( 'title' => 'Care Careers Maidstone Kent', 'parent_slug' => 'home' ),
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
		'care-careers-maidstone-kent',
		'resources', // parent; children: care-guides, faqs, referral-information
		'news-and-updates',
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
		'care-careers-maidstone-kent',
		'faqs',
		'contact-us',
		'privacy-policy',
		'terms-and-conditions',
		'cookies',
		'accessibility-statement',
	);

	/**
	 * Stored page IDs by slug after creation/lookup.
	 *
	 * @var array<string, int>
	 */
	private $page_ids = array();

	/**
	 * Constructor: hook activation and admin UI.
	 */
	public function __construct() {
		add_action( 'after_switch_theme', array( $this, 'run' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_reset_request' ) );
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
		$this->ensure_general_settings();
		$this->ensure_pages();
		$this->ensure_contact_page_content();
		$this->ensure_services();
		$this->ensure_menus();
		$this->ensure_reading_settings();
		$this->ensure_permalinks();
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
			'resources' => '<p>Useful information about our home care services, FAQs, and resources for professionals.</p>',
			'care-guides' => '<p>Educational resources to help you understand home care options, care planning and what to expect. More content can be added here.</p>',
			'faqs' => '<p>Frequently asked questions about our home care in Maidstone and Kent. We\'ll add answers here – or <a href="' . esc_url( home_url( '/home/contact-us/' ) ) . '">get in touch</a> and we\'ll be happy to help.</p>',
			'referral-information' => '<p>Information for professionals making referrals. We work with GPs, social workers, nurses and other health and care teams across Kent. Contact us to discuss a referral.</p>',
			'privacy-policy' => '<p>This page outlines how we collect, use and protect your personal data. Please replace this placeholder with your full privacy policy.</p>',
			'terms-and-conditions' => '<p>Terms and conditions of using our website and services. Please replace this placeholder with your full terms.</p>',
			'accessibility-statement' => '<p>We are committed to making our website accessible. If you have difficulty accessing any content or need information in a different format, please contact us.</p>',
			'cookies' => '<p>This page explains how we use cookies and similar technologies. Please replace this placeholder with your full cookie policy.</p>',
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
		// Create in dependency order: home first, then resources, then children.
		$order = array( 'home', 'resources', 'about-home-care-maidstone', 'home-care-services-kent', 'who-youll-meet', 'care-careers-maidstone-kent', 'contact-us', 'news-and-updates', 'care-guides', 'faqs', 'referral-information', 'privacy-policy', 'terms-and-conditions', 'accessibility-statement', 'cookies' );

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

		$primary_term_id = $this->get_or_create_menu( 'Primary', 'primary', $stored );
		$footer_term_id  = $this->get_or_create_menu( 'Footer', 'footer', $stored );

		$this->build_primary_menu( $primary_term_id );
		$this->build_footer_menu( $footer_term_id );

		$locations['primary'] = $primary_term_id;
		$locations['footer']  = $footer_term_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		update_option( self::MENUS_OPTION, array(
			'primary' => $primary_term_id,
			'footer'  => $footer_term_id,
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
			if ( $slug === 'resources' ) {
				$parent_id = $this->add_menu_item( $menu_id, $this->page_ids['resources'], 0, $position );
				$position++;
				foreach ( $resource_children as $child_slug ) {
					if ( isset( $this->page_ids[ $child_slug ] ) ) {
						$this->add_menu_item( $menu_id, $this->page_ids[ $child_slug ], $parent_id, $position );
						$position++;
					}
				}
				continue;
			}
			if ( isset( $this->page_ids[ $slug ] ) ) {
				$this->add_menu_item( $menu_id, $this->page_ids[ $slug ], 0, $position );
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
	 * @param int $menu_id   Nav menu term_id.
	 * @param int $object_id Page (or post) ID.
	 * @param int $parent    Parent menu item DB ID (0 for top-level).
	 * @param int $position  Menu order.
	 * @return int Menu item post ID.
	 */
	private function add_menu_item( $menu_id, $object_id, $parent, $position ) {
		$item = array(
			'menu-item-object-id' => $object_id,
			'menu-item-object'    => 'page',
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
			'menu-item-parent-id' => $parent,
		);
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
		foreach ( array( 'primary', 'footer' ) as $loc ) {
			if ( ! empty( $stored[ $loc ] ) ) {
				wp_delete_nav_menu( (int) $stored[ $loc ] );
			}
		}
		delete_option( self::MENUS_OPTION );

		$this->page_ids = array();
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
