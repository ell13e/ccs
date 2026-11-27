<?php
/**
 * This site's details for the CCS SEO plugin (Nov 2026).
 *
 * The SEO code moved out of the theme into its own plugin (ccs-seo), which
 * works on any site. Everything specific to Continuity of Care Services is
 * handed to it here, through the plugin's filters: contact details and CQC
 * rating from the Customizer, the service and area page wording, the schema
 * for services, areas and the FAQ page, and the old website's redirects.
 * Nothing here runs unless the plugin asks; without the plugin the theme
 * shows WordPress's own titles and nothing else (and says so in the admin).
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CCS_SEO_PLUGIN = 'ccs-seo/ccs-seo.php';

/** County added to service titles ("Complex care in Kent"). */
const CCS_SEO_COUNTY = 'Kent';

// -----------------------------------------------------------------------------
// The plugin itself: switched on with the theme, and missed if it isn't there.
// -----------------------------------------------------------------------------

/**
 * Switch the plugin on if it's installed but not active. Runs once from the
 * theme's setup and the one-time updates, never against a site owner who
 * turned it off afterwards.
 *
 * @return bool Whether it is now active.
 */
function ccs_activate_seo_plugin() {
	if ( ! function_exists( 'activate_plugin' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	if ( is_plugin_active( CCS_SEO_PLUGIN ) ) {
		return true;
	}
	if ( ! file_exists( WP_PLUGIN_DIR . '/' . CCS_SEO_PLUGIN ) ) {
		return false;
	}
	return null === activate_plugin( CCS_SEO_PLUGIN );
}

/**
 * One-time update: switch the plugin on for a site that already runs the theme.
 */
function ccs_activate_seo_plugin_2026_11() {
	if ( get_option( 'ccs_activate_seo_plugin_2026_11' ) ) {
		return;
	}
	// Retry on later visits until the plugin has been uploaded.
	if ( ccs_activate_seo_plugin() ) {
		update_option( 'ccs_activate_seo_plugin_2026_11', '1', false );
	}
}

/**
 * Warn in the admin when the plugin isn't running: without it the site has
 * no descriptions, sharing tags, schema or old-site redirects.
 */
function ccs_seo_plugin_missing_notice() {
	if ( function_exists( 'ccs_seo_setting' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'The CCS SEO plugin is not active.', 'ccs-wp-theme' ) . '</strong> ' .
		esc_html__( 'Without it the site has no page descriptions, sharing previews, search engine data or redirects from the old website. Install and activate it under Plugins.', 'ccs-wp-theme' ) . '</p></div>';
}
add_action( 'admin_notices', 'ccs_seo_plugin_missing_notice' );

// -----------------------------------------------------------------------------
// Settings defaults: the Customizer values the theme already collects.
// -----------------------------------------------------------------------------

/**
 * Default for a plugin setting, from this site's Customizer.
 *
 * @param mixed  $default Plugin's built-in default.
 * @param string $key     Setting key.
 * @return mixed
 */
function ccs_seo_bridge_setting_default( $default, $key ) {
	$address = trim( (string) get_theme_mod( 'ccs_contact_address', '' ) );
	switch ( $key ) {
		case 'org_legal_name':
			// Companies House: CONTINUITY OF CARE SERVICES LIMITED, 09440482.
			return 'Continuity of Care Services Limited';
		case 'org_alternate_name':
			// The short name the site itself uses ("Care Manager at CCS").
			return 'CCS';
		case 'org_company_number':
			// Companies House: CONTINUITY OF CARE SERVICES LIMITED, checked 27 Nov 2026.
			return '09440482';
		case 'org_founding_date':
			// 2016, confirmed by the client on 27 Nov 2026, as the About page timeline
			// says ("2016 | Where it began"). Companies House shows the company was
			// incorporated on 16 Feb 2015; the year the business gives is used.
			return '2016';
		case 'org_type':
			// LocalBusiness only: "HomeHealthCareService" is not a schema.org type
			// (schema.org/HomeHealthCareService is a 404, checked 27 Nov 2026), so
			// search engines ignored it. The settings page offers real types only.
			return 'LocalBusiness';
		case 'separator':
			return '|';
		case 'phone':
			$phone = trim( (string) get_theme_mod( 'ccs_phone', '' ) );
			return '' !== $phone ? $phone : '+44 1622 809881';
		case 'email':
			// The address the site shows (Customizer, else the office address).
			return function_exists( 'ccs_contact_email' ) ? (string) ccs_contact_email() : trim( (string) get_theme_mod( 'ccs_contact_email', '' ) );
		case 'address_street':
			// The Customizer address is free text, so it all goes in as the street.
			return '' !== $address ? (string) preg_replace( '/\s*\R\s*/u', ', ', $address ) : 'The Maidstone Studios, New Cut Road';
		case 'address_locality':
			return 'Maidstone';
		case 'address_region':
			return 'Kent';
		case 'address_postcode':
			return '' !== $address ? '' : 'ME14 5NZ';
		case 'address_country':
			return 'GB';
		case 'hours':
			// Same default the header and contact page show.
			return trim( (string) get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' ) );
		case 'social_profiles':
			return implode( "\n", ccs_seo_bridge_profiles() );
		case 'share_image':
			return absint( get_theme_mod( 'ccs_share_image', 0 ) );
		case 'google_verification':
			return (string) get_theme_mod( 'ccs_search_console_verification', '' );
		case 'contact_page':
			$pages = get_pages(
				array(
					'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value' => 'page-templates/template-contact.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					'number'     => 1,
				)
			);
			return $pages ? (int) $pages[0]->ID : 0;
	}
	return $default;
}
add_filter( 'ccs_seo_setting_default', 'ccs_seo_bridge_setting_default', 10, 2 );

/**
 * Profiles elsewhere: the known ones, replaced by any the Customizer has for
 * the same network, plus the CQC report.
 *
 * @return string[]
 */
function ccs_seo_bridge_profiles() {
	// The profiles the old website linked to (checked 27 Nov 2026).
	$social = array(
		'https://www.facebook.com/continuityofcareservices',
		'https://www.instagram.com/continuityofcareservices',
		'https://www.linkedin.com/company/continuitycareservices',
	);
	foreach ( array( 'ccs_facebook_url', 'ccs_linkedin_url', 'ccs_twitter_url' ) as $mod ) {
		$link = trim( (string) get_theme_mod( $mod, '' ) );
		if ( '' === $link ) {
			continue;
		}
		$host   = strtolower( (string) wp_parse_url( $link, PHP_URL_HOST ) );
		$social = array_values(
			array_filter(
				$social,
				static function ( $existing ) use ( $host ) {
					return '' === $host || false === strpos( strtolower( $existing ), str_replace( 'www.', '', $host ) );
				}
			)
		);
		$social[] = esc_url_raw( $link );
	}
	// The CQC listing: the regulator's page about this service (the same link the site's CQC sections use).
	$social[]   = esc_url_raw( (string) get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' ) );
	$cqc_report = trim( (string) get_theme_mod( 'ccs_cqc_report_url', '' ) );
	if ( '' !== $cqc_report ) {
		$social[] = esc_url_raw( $cqc_report );
	}
	return array_values( array_unique( array_filter( $social ) ) );
}

// -----------------------------------------------------------------------------
// Titles and descriptions for services and areas.
// -----------------------------------------------------------------------------

/**
 * Services keep their SEO fields in the Service details box, so they don't get a second one.
 *
 * @param string[] $types Post types.
 * @return string[]
 */
function ccs_seo_bridge_box_types( $types ) {
	return array_values( array_diff( $types, array( 'service' ) ) );
}
add_filter( 'ccs_seo_box_post_types', 'ccs_seo_bridge_box_types' );

/**
 * Service titles: their own SEO title, else "[Service] in Kent", since people
 * search for care with a place in it. Area titles: "Home Care in [Town], [County]".
 *
 * @param string  $title Post title.
 * @param WP_Post $post  Post.
 * @return string
 */
function ccs_seo_bridge_title( $title, $post ) {
	if ( 'service' === $post->post_type ) {
		$custom = get_post_meta( $post->ID, 'service_seo_title', true );
		if ( is_string( $custom ) && '' !== trim( $custom ) ) {
			return trim( $custom );
		}
		if ( '' === $title || false !== stripos( $title, CCS_SEO_COUNTY ) ) {
			return $title;
		}
		return $title . ' in ' . CCS_SEO_COUNTY;
	}
	if ( 'location' === $post->post_type ) {
		$town   = get_post_meta( $post->ID, 'location_town', true );
		$county = get_post_meta( $post->ID, 'location_county', true );
		$town   = is_string( $town ) && '' !== trim( $town ) ? trim( $town ) : get_the_title( $post->ID );
		$county = is_string( $county ) && '' !== trim( $county ) ? trim( $county ) : CCS_SEO_COUNTY;
		/* translators: 1: town name, 2: county name */
		return sprintf( __( 'Home Care in %1$s, %2$s', 'ccs-wp-theme' ), $town, $county );
	}
	return $title;
}
add_filter( 'ccs_seo_title_part', 'ccs_seo_bridge_title', 10, 2 );

/**
 * A service's description lives in its Service details box.
 *
 * @param string  $desc Description from the SEO box.
 * @param WP_Post $post Post.
 * @return string
 */
function ccs_seo_bridge_custom_description( $desc, $post ) {
	return 'service' === $post->post_type ? (string) get_post_meta( $post->ID, 'service_meta_description', true ) : $desc;
}
add_filter( 'ccs_seo_custom_description', 'ccs_seo_bridge_custom_description', 10, 2 );

/**
 * A service's short description comes before its excerpt and body text.
 *
 * @param string  $text '' by default.
 * @param WP_Post $post Post.
 * @return string
 */
function ccs_seo_bridge_description_source( $text, $post ) {
	if ( 'service' !== $post->post_type ) {
		return $text;
	}
	$short = get_post_meta( $post->ID, 'service_short_description', true );
	return is_string( $short ) ? trim( $short ) : $text;
}
add_filter( 'ccs_seo_description_source', 'ccs_seo_bridge_description_source', 10, 2 );

/**
 * Area descriptions start with the place: "Maidstone, Kent – …".
 *
 * @param string  $text Description.
 * @param WP_Post $post Post.
 * @return string
 */
function ccs_seo_bridge_auto_description( $text, $post ) {
	if ( 'location' !== $post->post_type ) {
		return $text;
	}
	$town   = get_post_meta( $post->ID, 'location_town', true );
	$county = get_post_meta( $post->ID, 'location_county', true );
	if ( $town || $county ) {
		$loc  = implode( ', ', array_filter( array( trim( (string) $town ), trim( (string) $county ) ) ) );
		$text = $loc . ' – ' . $text;
	}
	return $text;
}
add_filter( 'ccs_seo_auto_description', 'ccs_seo_bridge_auto_description', 10, 2 );

/**
 * A page's banner intro (ccs_page_intro). inc/switching.php adds the Switch page's.
 *
 * @param string $intro   Intro.
 * @param int    $post_id Page ID.
 * @return string
 */
function ccs_seo_bridge_page_intro( $intro, $post_id ) {
	if ( '' !== trim( (string) $intro ) || ! $post_id ) {
		return $intro;
	}
	$meta = get_post_meta( $post_id, 'ccs_page_intro', true );
	return is_string( $meta ) ? $meta : '';
}
add_filter( 'ccs_seo_page_intro', 'ccs_seo_bridge_page_intro', 5, 2 );

/**
 * Descriptions for the News page and topic pages that have none of their own.
 *
 * @param string $desc    Default.
 * @param string $context 'home' or the taxonomy.
 * @param string $name    Term name.
 * @return string
 */
function ccs_seo_bridge_archive_description( $desc, $context, $name ) {
	if ( 'home' === $context ) {
		return __( 'News, stories and advice from the Continuity of Care Services team, home care in Maidstone and across Kent.', 'ccs-wp-theme' );
	}
	if ( 'category' === $context ) {
		/* translators: %s: topic name. */
		return sprintf( __( '%s: articles from Continuity of Care Services, home care in Maidstone and across Kent.', 'ccs-wp-theme' ), $name );
	}
	return $desc;
}
add_filter( 'ccs_seo_archive_description', 'ccs_seo_bridge_archive_description', 10, 3 );

/**
 * Sharing image when a page has no featured image and no share image is set:
 * the homepage hero, as chosen in the Customizer or as packaged.
 *
 * @param string $url '' by default.
 * @return string
 */
function ccs_seo_bridge_share_image( $url ) {
	$id = absint( get_theme_mod( 'ccs_hero_image', 0 ) );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'large' );
		if ( is_array( $src ) && ! empty( $src[0] ) ) {
			return $src[0];
		}
	}
	$packaged = '/assets/images/site-photos/home-hero-family.jpg';
	if ( file_exists( get_template_directory() . $packaged ) ) {
		return get_template_directory_uri() . $packaged;
	}
	return $url;
}
add_filter( 'ccs_seo_fallback_share_image', 'ccs_seo_bridge_share_image' );

// -----------------------------------------------------------------------------
// Sitemap.
// -----------------------------------------------------------------------------

/**
 * Pages, posts, services and areas; not resources or other internal types.
 *
 * @return string[]
 */
function ccs_seo_bridge_sitemap_types() {
	return array( 'post', 'page', 'service', 'location' );
}
add_filter( 'ccs_seo_sitemap_post_types', 'ccs_seo_bridge_sitemap_types' );

/**
 * Leave the form thank-you page out of the sitemap.
 *
 * @param int[]  $post_ids  IDs.
 * @param string $post_type Post type.
 * @return int[]
 */
function ccs_seo_bridge_sitemap_exclude( $post_ids, $post_type ) {
	$thank_you = get_theme_mod( 'ccs_analytics_thank_you_page', '' );
	if ( '' === $thank_you || 'page' !== $post_type ) {
		return $post_ids;
	}
	$page = get_page_by_path( trim( $thank_you ), OBJECT, 'page' );
	if ( $page instanceof WP_Post ) {
		$post_ids[] = $page->ID;
	}
	return $post_ids;
}
add_filter( 'ccs_sitemap_exclude_post_ids', 'ccs_seo_bridge_sitemap_exclude', 10, 2 );

// -----------------------------------------------------------------------------
// Schema: organisation extras, services, areas, the FAQ page.
// -----------------------------------------------------------------------------

/**
 * The logo for search engines when no Site Logo is uploaded: the square brand
 * mark (512 × 512), the version Google's logo guidelines suit best.
 *
 * @param string $url Site Logo URL, or ''.
 * @return string
 */
function ccs_seo_bridge_logo( $url ) {
	$mark = '/assets/images/brand/ccs-logo-mark.png';
	return '' === $url && file_exists( get_template_directory() . $mark ) ? get_template_directory_uri() . $mark : $url;
}
add_filter( 'ccs_seo_logo_url', 'ccs_seo_bridge_logo' );

/**
 * Organisation extras the plugin's settings don't cover: the area served,
 * the published hourly rate and the CQC rating.
 *
 * @param array $schema Organisation node.
 * @return array
 */
function ccs_seo_bridge_organization( $schema ) {
	// Kent is a county: an administrative area, not a state.
	$schema['areaServed'] = array(
		'@type' => 'AdministrativeArea',
		'name'  => 'Kent',
	);
	$schema['priceRange'] = '££';

	// The published rate, from the same setting the homepage shows.
	$rate_raw = (string) get_theme_mod( 'ccs_cost_from_rate', '£36.38' );
	if ( preg_match( '/([\d]+(?:\.[\d]{1,2})?)/', $rate_raw, $rate_match ) ) {
		$schema['makesOffer'] = array(
			'@type'              => 'Offer',
			'name'               => __( 'Home care, hourly', 'ccs-wp-theme' ),
			'priceSpecification' => array(
				'@type'         => 'UnitPriceSpecification',
				'price'         => $rate_match[1],
				'priceCurrency' => 'GBP',
				'unitCode'      => 'HUR',
				'description'   => (string) get_theme_mod( 'ccs_cost_basis', __( 'Personal care, weekdays 7am–10pm', 'ccs-wp-theme' ) ),
			),
		);
	}

	// The CQC rating: issued by the regulator, not self-declared.
	$cqc_rating = trim( (string) get_theme_mod( 'ccs_cqc_rating', '' ) );
	if ( '' !== $cqc_rating ) {
		$credential = array(
			'@type'              => 'EducationalOccupationalCredential',
			'credentialCategory' => 'CQC rating',
			'name'               => sprintf( 'CQC rated %s', $cqc_rating ),
			'recognizedBy'       => array(
				'@type' => 'GovernmentOrganization',
				'name'  => 'Care Quality Commission',
				'url'   => 'https://www.cqc.org.uk',
			),
		);
		$cqc_report = trim( (string) get_theme_mod( 'ccs_cqc_report_url', '' ) );
		if ( '' !== $cqc_report ) {
			$credential['url'] = esc_url_raw( $cqc_report );
		}
		$schema['hasCredential'] = $credential;
	}
	return $schema;
}
add_filter( 'ccs_structured_data_organization', 'ccs_seo_bridge_organization' );

/**
 * Add this site's own nodes to the page's graph.
 *
 * @param array $graphs Nodes.
 * @return array
 */
function ccs_seo_bridge_schema_graph( $graphs ) {
	$post = get_queried_object();
	if ( is_singular( 'service' ) && $post instanceof WP_Post ) {
		$graphs[] = ccs_seo_bridge_service_schema( $post );
		$graphs[] = ccs_seo_bridge_service_faq_schema( $post );
	}
	if ( is_singular( 'location' ) && $post instanceof WP_Post ) {
		$graphs[] = ccs_seo_bridge_location_schema( $post );
	}
	if ( is_page_template( 'page-templates/template-faqs.php' ) && $post instanceof WP_Post ) {
		$graphs[] = ccs_seo_bridge_faq_page_schema( $post );
	}
	return array_values( array_filter( $graphs ) );
}
add_filter( 'ccs_seo_schema_graph', 'ccs_seo_bridge_schema_graph' );

/**
 * Short description for schema: a service's own summary, else the excerpt, else 35 words.
 *
 * @param WP_Post $post Post.
 * @return string
 */
function ccs_seo_bridge_post_description( WP_Post $post ) {
	if ( 'service' === $post->post_type ) {
		$short = get_post_meta( $post->ID, 'service_short_description', true );
		if ( is_string( $short ) && '' !== trim( $short ) ) {
			return wp_strip_all_tags( trim( $short ) );
		}
	}
	$excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : '';
	if ( '' !== $excerpt ) {
		return wp_strip_all_tags( trim( $excerpt ) );
	}
	$content = get_post_field( 'post_content', $post->ID );
	return '' !== $content ? wp_trim_words( wp_strip_all_tags( $content ), 35 ) : '';
}

/**
 * Service node, with prices when the service has them.
 *
 * @param WP_Post $post Service.
 * @return array
 */
function ccs_seo_bridge_service_schema( WP_Post $post ) {
	$name = get_the_title( $post->ID );
	$url  = get_permalink( $post->ID );
	if ( empty( $name ) || empty( $url ) ) {
		return array();
	}
	$schema      = array(
		'@type'    => 'Service',
		'@id'      => $url . '#service',
		'name'     => $name,
		'url'      => $url,
		'serviceType' => $name,
		'provider' => array( '@id' => home_url( '/' ) . '#organization' ),
		// Every service is offered across the areas covered, all in Kent.
		'areaServed' => array(
			'@type' => 'AdministrativeArea',
			'name'  => CCS_SEO_COUNTY,
		),
	);
	$description = ccs_seo_bridge_post_description( $post );
	if ( '' !== $description ) {
		$schema['description'] = $description;
	}
	$from = get_post_meta( $post->ID, 'service_price_from', true );
	$to   = get_post_meta( $post->ID, 'service_price_to', true );
	$from = '' !== $from && is_numeric( $from ) ? (float) $from : null;
	$to   = '' !== $to && is_numeric( $to ) ? (float) $to : null;
	if ( null !== $from || null !== $to ) {
		$schema['offers'] = array(
			'@type'         => 'Offer',
			'priceCurrency' => 'GBP',
		);
		if ( null !== $from && null !== $to && $from !== $to ) {
			$schema['offers']['priceSpecification'] = array(
				'@type'    => 'PriceSpecification',
				'minPrice' => $from,
				'maxPrice' => $to,
			);
		} else {
			$schema['offers']['price'] = null !== $from ? $from : $to;
		}
	}
	return $schema;
}

/**
 * FAQPage from a service's own questions.
 *
 * @param WP_Post $post Service.
 * @return array
 */
function ccs_seo_bridge_service_faq_schema( WP_Post $post ) {
	$faqs = get_post_meta( $post->ID, 'service_faqs', true );
	if ( empty( $faqs ) || ! is_array( $faqs ) ) {
		return array();
	}
	$questions = array();
	foreach ( $faqs as $item ) {
		$q = is_array( $item ) && isset( $item['question'] ) ? trim( (string) $item['question'] ) : '';
		$a = is_array( $item ) && isset( $item['answer'] ) ? trim( (string) $item['answer'] ) : '';
		if ( '' !== $q ) {
			$questions[] = ccs_seo_bridge_question( $q, '' !== $a ? $a : $q );
		}
	}
	return $questions ? array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink( $post->ID ) . '#faq',
		'mainEntity' => $questions,
	) : array();
}

/**
 * Area page node: the home care service in that town, from the one business.
 *
 * Not a LocalBusiness: there is one office (Maidstone), and Google's rules say
 * structured data must describe what is really there. Each area page is about
 * the service reaching that town, so it is a Service with areaServed (research
 * and reasons: docs/SEO-PLUGIN-PLAN.md, Nov 2026). Same @id as before, so links
 * to it keep working.
 *
 * @param WP_Post $post Area.
 * @return array
 */
function ccs_seo_bridge_location_schema( WP_Post $post ) {
	$url  = get_permalink( $post->ID );
	$town = trim( (string) get_post_meta( $post->ID, 'location_town', true ) );
	$town = '' !== $town ? $town : get_the_title( $post->ID );
	if ( empty( $url ) || '' === $town ) {
		return array();
	}
	$county = trim( (string) get_post_meta( $post->ID, 'location_county', true ) );
	$area   = array(
		'@type' => 'City',
		'name'  => $town,
	);
	if ( '' !== $county ) {
		$area['containedInPlace'] = array(
			'@type' => 'AdministrativeArea',
			'name'  => $county,
		);
	}
	$schema = array(
		'@type'       => 'Service',
		'@id'         => $url . '#location',
		/* translators: %s: town name. */
		'name'        => sprintf( __( 'Home care in %s', 'ccs-wp-theme' ), $town ),
		'serviceType' => __( 'Home care', 'ccs-wp-theme' ),
		'url'         => $url,
		'areaServed'  => $area,
		'provider'    => array( '@id' => home_url( '/' ) . '#organization' ),
	);
	$description = ccs_seo_bridge_post_description( $post );
	if ( '' !== $description ) {
		$schema['description'] = $description;
	}
	return $schema;
}

/**
 * FAQPage for the FAQs page: its H2 + paragraph pairs, then the topic questions.
 *
 * @param WP_Post $post FAQs page.
 * @return array
 */
function ccs_seo_bridge_faq_page_schema( WP_Post $post ) {
	$questions = array();
	$content   = (string) get_post_field( 'post_content', $post->ID );
	if ( '' === $content || ! preg_match_all( '#<h2[^>]*>(.*?)</h2>\s*<p>(.*?)</p>#s', $content, $matches, PREG_SET_ORDER ) ) {
		return array();
	}
	foreach ( $matches as $m ) {
		$q = wp_strip_all_tags( trim( $m[1] ) );
		$a = wp_strip_all_tags( trim( $m[2] ) );
		if ( '' !== $q ) {
			$questions[] = ccs_seo_bridge_question( $q, '' !== $a ? $a : $q );
		}
	}
	if ( function_exists( 'ccs_more_faqs' ) ) {
		foreach ( ccs_more_faqs()['groups'] as $group ) {
			foreach ( $group['items'] as $item ) {
				$questions[] = ccs_seo_bridge_question( $item['q'], $item['a'] );
			}
		}
	}
	return $questions ? array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink( $post->ID ) . '#faq',
		'mainEntity' => $questions,
	) : array();
}

/**
 * One question and answer.
 *
 * @param string $q Question.
 * @param string $a Answer.
 * @return array
 */
function ccs_seo_bridge_question( $q, $a ) {
	return array(
		'@type'          => 'Question',
		'name'           => $q,
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $a,
		),
	);
}

// -----------------------------------------------------------------------------
// Breadcrumbs, page types and team profiles.
// -----------------------------------------------------------------------------

/**
 * Breadcrumb data built from the trail visitors see (ccs_breadcrumb_items()),
 * so the two always match, as Google requires.
 *
 * @param array $items Plugin's own trail.
 * @return array
 */
function ccs_seo_bridge_breadcrumbs( $items ) {
	if ( ! function_exists( 'ccs_breadcrumb_items' ) || ! ( is_singular() || is_home() || is_category() || is_post_type_archive() ) ) {
		return $items;
	}
	$here  = is_singular() ? (string) get_permalink() : '';
	$trail = array();
	foreach ( ccs_breadcrumb_items() as $item ) {
		$url = '' !== $item['url'] ? $item['url'] : $here;
		if ( '' === $url ) {
			if ( is_home() && get_option( 'page_for_posts' ) ) {
				$url = (string) get_permalink( (int) get_option( 'page_for_posts' ) );
			} elseif ( is_category() ) {
				$link = get_term_link( get_queried_object() );
				$url  = is_wp_error( $link ) ? '' : $link;
			} elseif ( is_post_type_archive() ) {
				$url = (string) get_post_type_archive_link( (string) get_query_var( 'post_type' ) );
			}
		}
		$trail[] = array(
			'name' => wp_strip_all_tags( html_entity_decode( (string) $item['label'], ENT_QUOTES, 'UTF-8' ) ),
			'url'  => $url,
		);
	}
	return $trail;
}
add_filter( 'ccs_seo_breadcrumb_items', 'ccs_seo_bridge_breadcrumbs' );

/**
 * The team member a page is the profile of, or null.
 *
 * @return array|null
 */
function ccs_seo_bridge_team_member() {
	if ( ! is_page() || ! function_exists( 'ccs_team_member_by_slug' ) ) {
		return null;
	}
	$slug = (string) get_post_meta( get_queried_object_id(), 'ccs_team_member', true );
	return '' !== $slug ? ccs_team_member_by_slug( $slug ) : null;
}

/**
 * Most specific page type: About page, and team members' profile pages
 * ("an employee page on a company website" is one of Google's own examples).
 *
 * @param array $schema Web page node.
 * @return array
 */
function ccs_seo_bridge_webpage( $schema ) {
	if ( is_page_template( 'page-templates/template-about.php' ) ) {
		$schema['@type'] = 'AboutPage';
	}
	if ( ccs_seo_bridge_team_member() ) {
		$schema['@type']      = 'ProfilePage';
		$schema['mainEntity'] = array( '@id' => get_permalink() . '#person' );
	}
	return $schema;
}
add_filter( 'ccs_seo_webpage_schema', 'ccs_seo_bridge_webpage' );

/**
 * The person a profile page is about: name, role and photo as the page shows them.
 *
 * @param array $graphs Nodes.
 * @return array
 */
function ccs_seo_bridge_person_schema( $graphs ) {
	$member = ccs_seo_bridge_team_member();
	if ( ! $member ) {
		return $graphs;
	}
	$url    = get_permalink();
	$person = array(
		'@type'    => 'Person',
		'@id'      => $url . '#person',
		'name'     => $member['name'],
		'url'      => $url,
		'worksFor' => array( '@id' => home_url( '/' ) . '#organization' ),
	);
	if ( ! empty( $member['role'] ) ) {
		$person['jobTitle'] = $member['role'];
	}
	$photo = ! empty( $member['photo'] ) ? '/assets/images/team/' . $member['photo'] : '';
	if ( '' !== $photo && file_exists( get_template_directory() . $photo ) ) {
		$person['image'] = get_template_directory_uri() . $photo;
	}
	$graphs[] = $person;
	return $graphs;
}
add_filter( 'ccs_seo_schema_graph', 'ccs_seo_bridge_person_schema' );

// -----------------------------------------------------------------------------
// Redirects from the old website.
// -----------------------------------------------------------------------------

add_filter(
	'ccs_seo_redirects',
	static function ( $map ) {
		return array_merge( (array) $map, ccs_old_site_redirects() );
	}
);

add_filter(
	'ccs_seo_redirect_target',
	static function ( $target, $path ) {
		return '' !== $target ? $target : ccs_old_site_redirect_target( $path );
	},
	10,
	2
);

add_filter(
	'ccs_seo_redirect_origin_labels',
	static function ( $labels ) {
		$labels['code'] = __( 'Old website', 'ccs-wp-theme' );
		return $labels;
	}
);

// -----------------------------------------------------------------------------
// Carried over from the theme's own SEO box.
// -----------------------------------------------------------------------------

/**
 * Anything typed into the box before it was fixed (Nov 2026): a title or
 * description saved to the old keys moves to the ones the site reads, once,
 * where the page has none there yet.
 */
function ccs_seo_box_keys_2026_11() {
	if ( get_option( 'ccs_seo_box_keys_2026_11' ) || ! add_option( 'ccs_seo_box_keys_2026_11', '1', '', 'no' ) ) {
		return;
	}
	$pairs = array(
		'_ccs_seo_meta_title'       => 'ccs_seo_title',
		'_ccs_seo_meta_description' => 'ccs_meta_description',
	);
	foreach ( $pairs as $old => $new ) {
		$ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'meta_key'       => $old, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			)
		);
		foreach ( $ids as $id ) {
			$value = trim( (string) get_post_meta( $id, $old, true ) );
			if ( '' !== $value && '' === trim( (string) get_post_meta( $id, $new, true ) ) ) {
				update_post_meta( $id, $new, $value );
			}
		}
	}
}
add_action( 'init', 'ccs_seo_box_keys_2026_11', 50 );
