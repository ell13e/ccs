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

		// Page-specific schemas.
		if ( is_front_page() && is_page() ) {
			$website = $this->get_website_schema();
			if ( ! empty( $website ) ) {
				$graphs[] = $website;
			}
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
		$defaults = array(
			'@type'            => 'HomeHealthCareService',
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

		if ( ! empty( $logo_url ) ) {
			$defaults['logo'] = $logo_url;
		}

		$schema = apply_filters( 'ccs_structured_data_organization', $defaults );

		// Ensure required type and name.
		$schema['@type'] = 'HomeHealthCareService';
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
