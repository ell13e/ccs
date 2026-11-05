<?php
/**
 * Content helpers for inner templates: split page content into an intro and
 * headed sections, and list services in reading order.
 *
 * Inner templates lay a page's own editor copy into designed components (an
 * accordion on the FAQs page, a lede over the service cards on the services
 * hub) without asking editors to use anything but ordinary paragraphs and
 * headings. Everything before the first heading is the intro; each h2 (or h3,
 * when the page has no h2) starts a section that runs to the next one.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Split filtered content (the_content output) at its top-level headings.
 *
 * @param string $html Filtered post content.
 * @return array{intro: string, sections: array<int, array{heading: string, body: string}>}
 */
function ccs_content_sections( $html ) {
	$out = array(
		'intro'    => $html,
		'sections' => array(),
	);

	$level = preg_match( '#<h2[\s>]#i', $html ) ? '2' : '3';

	if ( ! preg_match_all( '#<h' . $level . '[^>]*>(.*?)</h' . $level . '>#is', $html, $m, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ) {
		return $out;
	}

	$out['intro'] = trim( substr( $html, 0, $m[0][0][1] ) );
	$count        = count( $m );

	for ( $i = 0; $i < $count; $i++ ) {
		$from              = $m[ $i ][0][1] + strlen( $m[ $i ][0][0] );
		$to                = isset( $m[ $i + 1 ] ) ? $m[ $i + 1 ][0][1] : strlen( $html );
		$out['sections'][] = array(
			'heading' => trim( wp_strip_all_tags( $m[ $i ][1][0] ) ),
			'body'    => trim( substr( $html, $from, $to - $from ) ),
		);
	}

	return $out;
}

/**
 * Published services in the site's reading order: everyday care first, the
 * most specialist last (the homepage order). menu_order is 0 on every seeded
 * service, so ordering by it alone fell back to alphabetical and opened on
 * Complex Care. Services not in the list follow in menu_order, then title.
 *
 * @param int $limit Maximum number of services.
 * @return WP_Post[]
 */
function ccs_ordered_services( $limit = 12 ) {
	$services = get_posts(
		array(
			'post_type'      => 'service',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);

	$rank = array_flip( array( 'domiciliary-care', 'respite-care', 'complex-care' ) );
	usort(
		$services,
		static function ( $a, $b ) use ( $rank ) {
			return ( $rank[ $a->post_name ] ?? PHP_INT_MAX ) <=> ( $rank[ $b->post_name ] ?? PHP_INT_MAX );
		}
	);

	return $services;
}

/**
 * Give every h2 in filtered content an id and return them as a contents list.
 *
 * Existing ids are kept, so a heading an editor has already linked to keeps
 * its anchor. Duplicate headings get -2, -3 suffixes.
 *
 * @param string $html Filtered post content.
 * @return array{html: string, toc: array<string, string>} Content with ids, and id => heading text.
 */
function ccs_content_anchors( $html ) {
	$toc  = array();
	$used = array();

	$html = preg_replace_callback(
		'#<h2(\s[^>]*)?>(.*?)</h2>#is',
		static function ( $m ) use ( &$toc, &$used ) {
			$attrs = isset( $m[1] ) ? $m[1] : '';
			$label = trim( wp_strip_all_tags( $m[2] ) );

			if ( '' === $label ) {
				return $m[0];
			}

			if ( preg_match( '#\sid=(["\'])(.*?)\1#i', $attrs, $idm ) ) {
				$id = $idm[2];
			} else {
				$base = sanitize_title( $label );
				$base = '' !== $base ? $base : 'section';
				$id   = $base;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}

			$used[ $id ] = true;
			$toc[ $id ]  = $label;

			return '<h2' . $attrs . '>' . $m[2] . '</h2>';
		},
		$html
	);

	return array(
		'html' => $html,
		'toc'  => $toc,
	);
}
