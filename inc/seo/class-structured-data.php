<?php
/**
 * Schema.org JSON-LD structured data output.
 *
 * Outputs Organization (HomeHealthCareService), WebSite, Service, LocalBusiness,
 * ContactPage, FAQPage, and BreadcrumbList as appropriate per page.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Structured_Data
 */
class CCS_Structured_Data {

	/**
	 * Hook output to wp_head.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'output_schema' ), 1 );
	}

	/**
	 * Output all applicable JSON-LD scripts for the current page.
	 */
	public function output_schema() {
		if ( is_admin() || is_feed() || is_robots() ) {
			return;
		}

		$graphs = $this->get_schema_for_current_page();
		if ( empty( $graphs ) ) {
			return;
		}

		$json = wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graphs ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( $json === false ) {
			return;
		}

		echo '<!-- Schema.org JSON-LD (CCS) -->' . "\n";
		echo '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>' . "\n";
	}

	/**
	 * Collect all schema objects for the current request.
	 *
	 * @return array List of schema arrays (merged into @graph).
	 */
	public function get_schema_for_current_page() {
		$graphs = array();

		// Organization (HomeHealthCareService) on all pages.
		$org = $this->get_organization_schema();
		if ( ! empty( $org ) ) {
			$graphs[] = $org;
		}

		/*
		 * WebSite on every page, not just the front page. The WebPage node below
		 * declares isPartOf #website, so restricting the WebSite node to the
		 * homepage left that reference dangling on every inner page — an @id
		 * pointing at an entity that was never defined in the graph.
		 */
		$website = $this->get_website_schema();
		if ( ! empty( $website ) ) {
			$graphs[] = $website;
		}

		$webpage = $this->get_webpage_schema();
		if ( ! empty( $webpage ) ) {
			$graphs[] = $webpage;
		}

		if ( is_singular( 'service' ) ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$service_schema = $this->get_service_schema( $post );
				if ( ! empty( $service_schema ) ) {
					$graphs[] = $service_schema;
				}
				$faq_schema = $this->get_faq_schema( $post );
				if ( ! empty( $faq_schema ) ) {
					$graphs[] = $faq_schema;
				}
			}
		}

		if ( is_singular( 'location' ) ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$location_schema = $this->get_location_schema( $post );
				if ( ! empty( $location_schema ) ) {
					$graphs[] = $location_schema;
				}
			}
		}

		if ( is_singular( 'post' ) ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$article_schema = $this->get_article_schema( $post );
				if ( ! empty( $article_schema ) ) {
					$graphs[] = $article_schema;
				}
			}
		}

		if ( is_page_template( 'page-templates/template-contact.php' ) ) {
			$contact_schema = $this->get_contact_page_schema();
			if ( ! empty( $contact_schema ) ) {
				$graphs[] = $contact_schema;
			}
		}

		// FAQPage for standalone FAQs page (template-faqs).
		if ( is_page_template( 'page-templates/template-faqs.php' ) ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$faq_page_schema = $this->get_faq_page_schema( $post );
				if ( ! empty( $faq_page_schema ) ) {
					$graphs[] = $faq_page_schema;
				}
			}
		}

		// Breadcrumb on all pages except homepage.
		if ( ! is_front_page() ) {
			$breadcrumb = $this->get_breadcrumb_schema();
			if ( ! empty( $breadcrumb ) ) {
				$graphs[] = $breadcrumb;
			}
		}

		return $graphs;
	}

	/**
	 * HomeHealthCareService organization schema (name, URL, logo, address, contact, hours, area served, social).
	 *
	 * @return array Schema array or empty if invalid.
	 */
	public function get_organization_schema() {
		$name = get_bloginfo( 'name' );
		$url  = home_url( '/' );
		if ( empty( $name ) || empty( $url ) ) {
			return array();
		}

		$logo_url = $this->get_logo_url();

		/*
		 * Typed as BOTH LocalBusiness and HomeHealthCareService. An array of
		 * types is valid schema.org and is how one entity says two true things
		 * at once. This matters because until 2026-08-23 the theme shipped two
		 * competing nodes that each claimed this same @id — one typed
		 * HomeHealthCareService (here) and one typed LocalBusiness (in
		 * class-seo-optimizer.php) — plus a third untyped Organization block
		 * hardcoded into header.php. Consumers merging by @id received
		 * contradictory statements about what this business is. There is now one
		 * node, and it carries both types.
		 */
		$defaults = array(
			'@type'            => array( 'LocalBusiness', 'HomeHealthCareService' ),
			'@id'              => $url . '#organization',
			'name'             => $name,
			'url'              => $url,
			'address'          => array(),
			'telephone'         => '',
			'email'            => '',
			'openingHoursSpecification' => array(),
			'areaServed'       => array(
				'@type' => 'State',
				'name'  => 'Kent',
			),
			'sameAs'           => array(),
		);

		/*
		 * Decoded: get_bloginfo() returns HTML-escaped text, which is right for
		 * markup but wrong inside JSON-LD — the tagline was publishing as
		 * "Maidstone &amp; Kent". JSON-LD carries plain strings; the encoder
		 * handles escaping.
		 */
		$description = html_entity_decode( (string) get_bloginfo( 'description' ), ENT_QUOTES, 'UTF-8' );
		if ( trim( $description ) !== '' ) {
			$defaults['description'] = $description;
		}

		if ( ! empty( $logo_url ) ) {
			$defaults['logo']  = $logo_url;
			$defaults['image'] = $logo_url;
		}

		/*
		 * Populate from the Customizer values the theme already collects. These
		 * settings existed but were never wired into the graph, so every one of
		 * these properties previously fell through to the unset() calls below
		 * and the business published no address, phone, hours or social profiles
		 * at all — the fields local search relies on most.
		 */
		/*
		 * Defaults match the rest of the theme (header.php, the contact template)
		 * so schema never silently omits a field the page is visibly displaying.
		 * The registered number is carried over from the removed builder in
		 * class-seo-optimizer.php; a Customizer value replaces it.
		 */
		$phone = trim( (string) get_theme_mod( 'ccs_phone', '' ) );
		$defaults['telephone'] = $phone !== '' ? $phone : '+44 1622 809881';

		$email = trim( (string) get_theme_mod( 'ccs_contact_email', '' ) );
		if ( $email !== '' && is_email( $email ) ) {
			$defaults['email'] = $email;
		}

		/*
		 * The address is a free-text textarea, so it cannot be split reliably
		 * into street/locality/postcode. It goes in as streetAddress with the
		 * locality, region and country stated explicitly — accurate for a
		 * Maidstone-based provider and far better than omitting the address.
		 * Override via the ccs_structured_data_organization filter if a fully
		 * structured address is ever needed.
		 */
		$address = trim( (string) get_theme_mod( 'ccs_contact_address', '' ) );
		if ( $address !== '' ) {
			$defaults['address'] = array(
				'streetAddress'   => preg_replace( '/\s*\R\s*/u', ', ', $address ),
				'addressLocality' => 'Maidstone',
				'addressRegion'   => 'Kent',
				'addressCountry'  => 'GB',
			);
		} else {
			/*
			 * Registered office, carried over from the (never-hooked, now
			 * removed) schema builder in class-seo-optimizer.php so the real
			 * address is not lost. Used only until someone fills in the
			 * Customizer's "Office address" field, which then wins.
			 */
			$defaults['address'] = array(
				'streetAddress'   => 'The Maidstone Studios, New Cut Road',
				'addressLocality' => 'Maidstone',
				'addressRegion'   => 'Kent',
				'postalCode'      => 'ME14 5NZ',
				'addressCountry'  => 'GB',
			);
		}

		/*
		 * priceRange is a coarse affordability band, not the actual rate. "££"
		 * rather than the "$$" the old builder used, which was wrong for a UK
		 * business.
		 */
		$defaults['priceRange'] = '££';

		/*
		 * The actual published rate, as a machine-readable Offer. Publishing
		 * real prices is this provider's clearest advantage over every
		 * benchmarked competitor — none of Home Instead, Bluebird or Superior
		 * publishes rates at all — so it is worth making legible to search
		 * engines and assistants, not only to human readers. Sourced from the
		 * same Customizer setting the homepage renders, so the two cannot drift.
		 */
		$rate_raw = (string) get_theme_mod( 'ccs_cost_from_rate', '£36.38' );
		if ( preg_match( '/([\d]+(?:\.[\d]{1,2})?)/', $rate_raw, $rate_match ) ) {
			$defaults['makesOffer'] = array(
				'@type'            => 'Offer',
				'name'             => __( 'Home care, hourly', 'ccs-wp-theme' ),
				'priceSpecification' => array(
					'@type'         => 'UnitPriceSpecification',
					'price'         => $rate_match[1],
					'priceCurrency' => 'GBP',
					'unitCode'      => 'HUR',
					/* translators: describes what the published hourly rate covers. */
					'description'   => (string) get_theme_mod( 'ccs_cost_basis', __( 'per hour for personal care, weekdays 7am–10pm', 'ccs-wp-theme' ) ),
				),
			);
		}

		$hours = $this->get_opening_hours_spec();
		if ( ! empty( $hours ) ) {
			$defaults['openingHoursSpecification'] = $hours;
		}

		/*
		 * Known profiles as defaults, again carried over from the removed
		 * builder. A Customizer value for the same network replaces its default
		 * rather than sitting alongside it.
		 */
		$social = array(
			'https://www.instagram.com/continuityofcareservices',
			'https://www.linkedin.com/company/continuitycareservices',
		);
		foreach ( array( 'ccs_facebook_url', 'ccs_linkedin_url', 'ccs_twitter_url' ) as $mod ) {
			$link = trim( (string) get_theme_mod( $mod, '' ) );
			if ( $link === '' ) {
				continue;
			}
			$host = strtolower( (string) wp_parse_url( $link, PHP_URL_HOST ) );
			$social = array_values( array_filter(
				$social,
				static function ( $existing ) use ( $host ) {
					return $host === '' || strpos( strtolower( $existing ), str_replace( 'www.', '', $host ) ) === false;
				}
			) );
			$social[] = esc_url_raw( $link );
		}
		$cqc_report = trim( (string) get_theme_mod( 'ccs_cqc_report_url', '' ) );
		if ( $cqc_report !== '' ) {
			$social[] = esc_url_raw( $cqc_report );
		}
		if ( ! empty( $social ) ) {
			$defaults['sameAs'] = array_values( array_unique( $social ) );
		}

		/*
		 * The CQC rating is the single strongest third-party trust signal this
		 * business holds, and it is regulator-issued rather than self-declared.
		 * Publishing it as a credential lets it travel with the entity.
		 */
		$cqc_rating = trim( (string) get_theme_mod( 'ccs_cqc_rating', '' ) );
		if ( $cqc_rating !== '' ) {
			$credential = array(
				'@type'                => 'EducationalOccupationalCredential',
				'credentialCategory'   => 'CQC rating',
				'name'                 => sprintf( 'CQC rated %s', $cqc_rating ),
				'recognizedBy'         => array(
					'@type' => 'GovernmentOrganization',
					'name'  => 'Care Quality Commission',
					'url'   => 'https://www.cqc.org.uk',
				),
			);
			if ( $cqc_report !== '' ) {
				$credential['url'] = esc_url_raw( $cqc_report );
			}
			$defaults['hasCredential'] = $credential;
		}

		$schema = apply_filters( 'ccs_structured_data_organization', $defaults );

		// Ensure required type and name.
		$schema['@type'] = array( 'LocalBusiness', 'HomeHealthCareService' );
		$schema['name']  = $name;
		$schema['url']   = $url;

		// Normalize optional fields so we don't output empty strings where schema expects objects/arrays.
		if ( ! empty( $schema['address'] ) && is_array( $schema['address'] ) ) {
			$schema['address'] = array_merge( array( '@type' => 'PostalAddress' ), $schema['address'] );
		} else {
			unset( $schema['address'] );
		}
		if ( empty( $schema['telephone'] ) ) {
			unset( $schema['telephone'] );
		}
		if ( empty( $schema['email'] ) ) {
			unset( $schema['email'] );
		}
		if ( empty( $schema['openingHoursSpecification'] ) || ! is_array( $schema['openingHoursSpecification'] ) ) {
			unset( $schema['openingHoursSpecification'] );
		}
		if ( empty( $schema['sameAs'] ) || ! is_array( $schema['sameAs'] ) ) {
			unset( $schema['sameAs'] );
		}

		return $schema;
	}

	/**
	 * Build openingHoursSpecification from the free-text office-hours setting.
	 *
	 * The Customizer stores hours as prose ("Mon–Fri 9am–5pm"), which cannot be
	 * converted safely in the general case. This parses only the one shape the
	 * theme actually ships and returns an empty array for anything else —
	 * emitting a confidently wrong set of opening hours would be worse than
	 * emitting none, since it can surface directly in search results and send
	 * someone to a closed office. Anything more exotic should be supplied
	 * through the ccs_structured_data_organization filter.
	 *
	 * @return array openingHoursSpecification nodes, or empty.
	 */
	private function get_opening_hours_spec() {
		// Same default the header and contact template use, so the parsed hours
		// match what the site actually displays.
		$raw = trim( (string) get_theme_mod( 'ccs_office_hours', 'Mon–Fri 9am–5pm' ) );
		if ( $raw === '' ) {
			return array();
		}

		$days = array(
			'mon' => 'Monday',
			'tue' => 'Tuesday',
			'wed' => 'Wednesday',
			'thu' => 'Thursday',
			'fri' => 'Friday',
			'sat' => 'Saturday',
			'sun' => 'Sunday',
		);
		$order = array_keys( $days );

		// Normalise en/em dashes to a plain hyphen before matching.
		$text = strtolower( str_replace( array( "\u{2013}", "\u{2014}" ), '-', $raw ) );

		if ( ! preg_match( '/([a-z]{3})[a-z]*\s*-\s*([a-z]{3})[a-z]*\s+(\d{1,2})(?::(\d{2}))?\s*(am|pm)\s*-\s*(\d{1,2})(?::(\d{2}))?\s*(am|pm)/', $text, $m ) ) {
			return array();
		}

		$from = array_search( $m[1], $order, true );
		$to   = array_search( $m[2], $order, true );
		if ( $from === false || $to === false || $to < $from ) {
			return array();
		}

		$to24 = static function ( $hour, $min, $meridiem ) {
			$hour = (int) $hour;
			if ( $meridiem === 'pm' && $hour !== 12 ) {
				$hour += 12;
			}
			if ( $meridiem === 'am' && $hour === 12 ) {
				$hour = 0;
			}
			return sprintf( '%02d:%02d', $hour, (int) $min );
		};

		return array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array_values( array_map(
					static function ( $key ) use ( $days ) {
						return $days[ $key ];
					},
					array_slice( $order, $from, ( $to - $from ) + 1 )
				) ),
				'opens'     => $to24( $m[3], $m[4], $m[5] ),
				'closes'    => $to24( $m[6], $m[7], $m[8] ),
			),
		);
	}

	/**
	 * WebPage node for the current request, linked to the site and publisher.
	 *
	 * Previously supplied by class-seo-optimizer.php in a competing graph. It
	 * lives here now so every JSON-LD node the theme emits shares one @graph and
	 * one set of @ids.
	 *
	 * @return array Schema array or empty.
	 */
	public function get_webpage_schema() {
		$url  = home_url( '/' );
		$here = is_front_page() ? $url : ( is_singular() ? get_permalink() : '' );
		if ( empty( $here ) ) {
			return array();
		}

		$schema = array(
			'@type'    => 'WebPage',
			'@id'      => $here . '#webpage',
			'url'      => $here,
			'name'     => wp_get_document_title(),
			'isPartOf' => array( '@id' => $url . '#website' ),
			'about'    => array( '@id' => $url . '#organization' ),
		);

		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$thumb_id = get_post_thumbnail_id( $post->ID );
				if ( $thumb_id ) {
					$src = wp_get_attachment_image_src( $thumb_id, 'large' );
					if ( is_array( $src ) && ! empty( $src[0] ) ) {
						$schema['primaryImageOfPage'] = $src[0];
					}
				}
				$schema['datePublished'] = get_the_date( 'c', $post );
				$schema['dateModified']  = get_the_modified_date( 'c', $post );
			}
		}

		return $schema;
	}

	/**
	 * BlogPosting schema for a news post.
	 *
	 * News posts previously emitted only the generic WebPage node every singular
	 * URL gets, which says a URL exists but not that it is an article, who wrote
	 * it, or when. Without that, a news item cannot qualify for article
	 * treatment in search results and carries none of the author or freshness
	 * signals that make dated content worth surfacing at all.
	 *
	 * BlogPosting rather than NewsArticle: NewsArticle is for journalism from a
	 * news organisation, and Google's own guidance is that misapplying it to a
	 * company blog is a mismatch. These are provider updates and care advice, so
	 * BlogPosting is the honest type.
	 *
	 * @param WP_Post $post Post object.
	 * @return array Schema array or empty.
	 */
	public function get_article_schema( $post ) {
		if ( ! $post instanceof WP_Post ) {
			return array();
		}

		$url  = home_url( '/' );
		$here = get_permalink( $post );
		if ( empty( $here ) ) {
			return array();
		}

		$schema = array(
			'@type'            => 'BlogPosting',
			'@id'              => $here . '#article',
			'isPartOf'         => array( '@id' => $here . '#webpage' ),
			'mainEntityOfPage' => array( '@id' => $here . '#webpage' ),
			'headline'         => html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', (int) $post->post_author ),
			),
			'publisher'        => array( '@id' => $url . '#organization' ),
			'inLanguage'       => get_bloginfo( 'language' ),
		);

		$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 40, '' );
		$excerpt = trim( wp_strip_all_tags( (string) $excerpt ) );
		if ( $excerpt !== '' ) {
			$schema['description'] = $excerpt;
		}

		$thumb_id = get_post_thumbnail_id( $post->ID );
		if ( $thumb_id ) {
			$src = wp_get_attachment_image_src( $thumb_id, 'large' );
			if ( is_array( $src ) && ! empty( $src[0] ) ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $src[0],
					'width'  => isset( $src[1] ) ? (int) $src[1] : null,
					'height' => isset( $src[2] ) ? (int) $src[2] : null,
				);
				$schema['image'] = array_filter(
					$schema['image'],
					static function ( $value ) {
						return null !== $value;
					}
				);
			}
		}

		$categories = get_the_category( $post->ID );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			/*
			 * Decode entities before they reach the graph. Term names come back
			 * HTML-encoded, so a category named "Funding & Costs" was being
			 * published to consumers as the literal string "Funding &amp;
			 * Costs" — JSON-LD is data, not markup, and nothing downstream
			 * decodes it again.
			 */
			$schema['articleSection'] = array_map(
				static function ( $name ) {
					return html_entity_decode( $name, ENT_QUOTES, 'UTF-8' );
				},
				wp_list_pluck( $categories, 'name' )
			);
		}

		$word_count = self::count_words( $post );
		if ( $word_count > 0 ) {
			$schema['wordCount'] = $word_count;
		}

		return $schema;
	}

	/**
	 * Word count of a post's rendered body text.
	 *
	 * Shared by the article schema and the reading-time estimate shown on news
	 * templates so the two can never disagree about how long a post is.
	 *
	 * @param WP_Post $post Post object.
	 * @return int Word count, 0 when the post has no body text.
	 */
	public static function count_words( $post ) {
		if ( ! $post instanceof WP_Post ) {
			return 0;
		}

		$text = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
		$text = trim( preg_replace( '/\s+/u', ' ', $text ) );
		if ( $text === '' ) {
			return 0;
		}

		return count( preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY ) );
	}

	/**
	 * WebSite schema with SearchAction (homepage only).
	 *
	 * @return array Schema array.
	 */
	public function get_website_schema() {
		$url = home_url( '/' );
		$schema = array(
			'@type'       => 'WebSite',
			'@id'         => $url . '#website',
			'name'        => get_bloginfo( 'name' ),
			'url'         => $url,
			'description' => get_bloginfo( 'description' ),
			'publisher'   => array(
				'@id' => $url . '#organization',
			),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'     => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => $url . '?s={search_term_string}',
				),
				'query-input' => 'required name=search_term_string',
			),
		);

		return $schema;
	}

	/**
	 * Service schema for a single service post (provider ref, offers from pricing).
	 *
	 * @param WP_Post $post Service post.
	 * @return array Schema array or empty.
	 */
	public function get_service_schema( WP_Post $post ) {
		$name = get_the_title( $post->ID );
		$url  = get_permalink( $post->ID );
		if ( empty( $name ) || empty( $url ) ) {
			return array();
		}

		$schema = array(
			'@type'    => 'Service',
			'@id'      => $url . '#service',
			'name'     => $name,
			'url'      => $url,
			'provider' => array(
				'@id' => home_url( '/' ) . '#organization',
			),
		);

		$description = $this->get_post_description( $post );
		if ( $description !== '' ) {
			$schema['description'] = $description;
		}

		$price_from = get_post_meta( $post->ID, 'service_price_from', true );
		$price_to   = get_post_meta( $post->ID, 'service_price_to', true );
		$price_from = $price_from !== '' && is_numeric( $price_from ) ? (float) $price_from : null;
		$price_to   = $price_to !== '' && is_numeric( $price_to ) ? (float) $price_to : null;
		if ( $price_from !== null || $price_to !== null ) {
			$schema['offers'] = array(
				'@type'         => 'Offer',
				'priceCurrency' => 'GBP',
			);
			if ( $price_from !== null && $price_to !== null && $price_from !== $price_to ) {
				$schema['offers']['priceSpecification'] = array(
					'@type'    => 'PriceSpecification',
					'minPrice' => $price_from,
					'maxPrice' => $price_to,
				);
			} else {
				$schema['offers']['price'] = $price_from !== null ? $price_from : $price_to;
			}
		}

		return $schema;
	}

	/**
	 * FAQPage schema from service FAQs repeater (only if FAQs exist).
	 *
	 * @param WP_Post $post Service post.
	 * @return array Schema array or empty.
	 */
	public function get_faq_schema( WP_Post $post ) {
		$faqs = get_post_meta( $post->ID, 'service_faqs', true );
		if ( empty( $faqs ) || ! is_array( $faqs ) ) {
			return array();
		}

		$questions = array();
		foreach ( $faqs as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$q = isset( $item['question'] ) ? trim( (string) $item['question'] ) : '';
			$a = isset( $item['answer'] ) ? trim( (string) $item['answer'] ) : '';
			if ( $q === '' ) {
				continue;
			}
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => $q,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $a !== '' ? $a : $q,
				),
			);
		}

		if ( empty( $questions ) ) {
			return array();
		}

		return array(
			'@type'       => 'FAQPage',
			'@id'         => get_permalink( $post->ID ) . '#faq',
			'mainEntity'  => $questions,
		);
	}

	/**
	 * FAQPage schema for the standalone FAQs page (parses H2/P pairs from page content).
	 *
	 * @param WP_Post $post FAQs page post.
	 * @return array Schema array or empty.
	 */
	public function get_faq_page_schema( WP_Post $post ) {
		$content = get_post_field( 'post_content', $post->ID );
		if ( $content === '' ) {
			return array();
		}

		// Match H2 (question) followed by P (answer). Default content uses single <p> per answer.
		$pattern = '#<h2[^>]*>(.*?)</h2>\s*<p>(.*?)</p>#s';
		if ( ! preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
			return array();
		}

		$questions = array();
		foreach ( $matches as $m ) {
			$q = wp_strip_all_tags( trim( $m[1] ) );
			$a = wp_strip_all_tags( trim( $m[2] ) );
			if ( $q === '' ) {
				continue;
			}
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => $q,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $a !== '' ? $a : $q,
				),
			);
		}

		if ( empty( $questions ) ) {
			return array();
		}

		return array(
			'@type'       => 'FAQPage',
			'@id'         => get_permalink( $post->ID ) . '#faq',
			'mainEntity'  => $questions,
		);
	}

	/**
	 * LocalBusiness schema for a location post (address, geo).
	 *
	 * @param WP_Post $post Location post.
	 * @return array Schema array or empty.
	 */
	public function get_location_schema( WP_Post $post ) {
		$name = get_the_title( $post->ID );
		$url  = get_permalink( $post->ID );
		if ( empty( $name ) || empty( $url ) ) {
			return array();
		}

		$town     = get_post_meta( $post->ID, 'location_town', true );
		$county   = get_post_meta( $post->ID, 'location_county', true );
		$postcode = get_post_meta( $post->ID, 'location_postcode_area', true );
		$lat      = get_post_meta( $post->ID, 'location_latitude', true );
		$lng      = get_post_meta( $post->ID, 'location_longitude', true );

		$schema = array(
			'@type' => 'LocalBusiness',
			'@id'   => $url . '#location',
			'name'  => $name,
			'url'   => $url,
			'parentOrganization' => array(
				'@id' => home_url( '/' ) . '#organization',
			),
		);

		$address_parts = array_filter( array( $town, $county, $postcode ) );
		if ( ! empty( $address_parts ) ) {
			$schema['address'] = array(
				'@type'           => 'PostalAddress',
				'addressLocality' => $town ?: null,
				'addressRegion'   => $county ?: null,
				'postalCode'      => $postcode ?: null,
			);
			$schema['address'] = array_filter( $schema['address'] );
		}

		if ( $lat !== '' && $lng !== '' && is_numeric( $lat ) && is_numeric( $lng ) ) {
			$schema['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) $lat,
				'longitude' => (float) $lng,
			);
		}

		$description = $this->get_post_description( $post );
		if ( $description !== '' ) {
			$schema['description'] = $description;
		}

		return $schema;
	}

	/**
	 * ContactPage schema for the contact template.
	 *
	 * @return array Schema array.
	 */
	public function get_contact_page_schema() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return array();
		}

		$url = get_permalink( $post->ID );
		return array(
			'@type'            => 'ContactPage',
			'@id'              => $url . '#contactpage',
			'url'              => $url,
			'name'             => get_the_title( $post->ID ),
			'mainEntityOfPage' => array(
				'@id' => $url . '#webpage',
			),
		);
	}

	/**
	 * BreadcrumbList schema (all pages except homepage).
	 *
	 * @return array Schema array or empty.
	 */
	public function get_breadcrumb_schema() {
		$items = $this->get_breadcrumb_items();
		if ( count( $items ) < 2 ) {
			return array();
		}

		$list_items = array();
		$position   = 1;
		foreach ( $items as $item ) {
			$list_items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => $item['name'],
				'item'     => $item['url'],
			);
			$position++;
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list_items,
		);
	}

	/**
	 * Build breadcrumb trail: Home, then archive/service/location/page as applicable.
	 *
	 * @return array List of array( 'name', 'url' ).
	 */
	private function get_breadcrumb_items() {
		$items = array();
		$items[] = array(
			'name' => __( 'Home', 'ccs-wp-theme' ),
			'url'  => home_url( '/' ),
		);

		if ( is_singular( 'service' ) ) {
			$post_type = get_post_type_object( 'service' );
			$archive   = get_post_type_archive_link( 'service' );
			if ( $archive && $post_type ) {
				$items[] = array(
					'name' => $post_type->labels->name,
					'url'  => $archive,
				);
			}
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$items[] = array(
					'name' => get_the_title( $post->ID ),
					'url'  => get_permalink( $post->ID ),
				);
			}
		} elseif ( is_singular( 'location' ) ) {
			$post_type = get_post_type_object( 'location' );
			$archive   = get_post_type_archive_link( 'location' );
			if ( $archive && $post_type ) {
				$items[] = array(
					'name' => $post_type->labels->name,
					'url'  => $archive,
				);
			}
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$items[] = array(
					'name' => get_the_title( $post->ID ),
					'url'  => get_permalink( $post->ID ),
				);
			}
		} elseif ( is_post_type_archive() ) {
			$post_type = get_queried_object();
			if ( $post_type && isset( $post_type->labels->name ) ) {
				$items[] = array(
					'name' => $post_type->labels->name,
					'url'  => get_post_type_archive_link( $post_type->name ),
				);
			}
		} elseif ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$items[] = array(
					'name' => get_the_title( $post->ID ),
					'url'  => get_permalink( $post->ID ),
				);
			}
		}

		return $items;
	}

	/**
	 * Logo URL from theme custom logo or empty.
	 *
	 * @return string URL or empty.
	 */
	private function get_logo_url() {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( empty( $custom_logo_id ) ) {
			return '';
		}
		$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		return is_array( $image ) && ! empty( $image[0] ) ? $image[0] : '';
	}

	/**
	 * Short description or excerpt for a post (for schema description).
	 *
	 * @param WP_Post $post Post object.
	 * @return string Sanitized text.
	 */
	private function get_post_description( WP_Post $post ) {
		if ( $post->post_type === 'service' ) {
			$short = get_post_meta( $post->ID, 'service_short_description', true );
			if ( is_string( $short ) && trim( $short ) !== '' ) {
				return wp_strip_all_tags( trim( $short ) );
			}
		}
		$excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : '';
		if ( $excerpt !== '' ) {
			return wp_strip_all_tags( trim( $excerpt ) );
		}
		$content = get_post_field( 'post_content', $post->ID );
		if ( $content !== '' ) {
			return wp_trim_words( wp_strip_all_tags( $content ), 35 );
		}
		return '';
	}
}
