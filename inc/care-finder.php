<?php
/**
 * "Which care is right for us?" (Nov 2026, SEO plan item 3).
 *
 * Four questions a worried relative can answer without knowing any care
 * terms, ending in the services that fit and the free consultation. It is
 * its own page under Resources, not part of the services hub: a finder on
 * the hub was tried and dropped (see page-templates/template-services.php),
 * so the hub stays a plain grid and this page is a separate route for
 * people who do not know where to start.
 *
 * No script: the form posts back to the page, which works out the answer,
 * so it works on any phone. POST, not GET: a typed condition is health
 * information and must not end up in a URL, where browser history, server
 * logs and analytics would keep it. Nothing is stored.
 * Every answer maps only to services the site already offers.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slug of the page, under Resources.
 */
const CCS_CARE_FINDER_SLUG = 'which-care';

/**
 * The questions: name => legend and value => [label, service slug or ''].
 *
 * @return array<string, array{legend:string, options:array<string, array{0:string, 1:string}>}>
 */
function ccs_care_finder_questions() {
	return array(
		'need'  => array(
			'legend'  => __( 'What would help most right now?', 'ccs-wp-theme' ),
			'options' => array(
				'break'    => array( __( 'A break for the family member who does most of the caring', 'ccs-wp-theme' ), 'respite-care' ),
				'everyday' => array( __( 'Help with everyday things, like washing, dressing and meals', 'ccs-wp-theme' ), 'personal-care' ),
				'company'  => array( __( 'Company, and someone to get out and about with', 'ccs-wp-theme' ), 'companionship-care' ),
				'health'   => array( __( 'Care for a serious or long-term health condition', 'ccs-wp-theme' ), 'complex-care' ),
				'end'      => array( __( 'Care towards the end of life', 'ccs-wp-theme' ), 'palliative-care' ),
			),
		),
		'when'  => array(
			'legend'  => __( 'When is help needed most?', 'ccs-wp-theme' ),
			'options' => array(
				'day'    => array( __( 'During the day', 'ccs-wp-theme' ), '' ),
				'night'  => array( __( 'Through the night', 'ccs-wp-theme' ), 'overnight-care' ),
				'always' => array( __( 'Around the clock', 'ccs-wp-theme' ), 'continuous-care' ),
				'unsure' => array( __( 'Not sure yet', 'ccs-wp-theme' ), '' ),
			),
		),
		'who'   => array(
			'legend'  => __( 'Who is the care for?', 'ccs-wp-theme' ),
			'options' => array(
				'older'   => array( __( 'Someone in later life', 'ccs-wp-theme' ), 'elderly-care' ),
				'younger' => array( __( 'A younger adult', 'ccs-wp-theme' ), 'younger-adults-care' ),
			),
		),
	);
}

/**
 * Conditions for the type-to-search question (Nov 2026, user: "a more
 * inclusive way of giving people the option"). Six radio buttons left out
 * most people; a dropdown of twenty is hard on a phone. So the question is
 * a text box with suggestions (a native datalist, no script), and whatever
 * is typed is matched here. Only conditions the site's service pages
 * already say we support are listed, each pointing to that service.
 * Anything else typed is not turned away: the result invites a free
 * consultation instead (see the template).
 *
 * label => [service slug, words that match it]. A word ending in a space or
 * under four letters must match as a whole word ("ms", "mnd", "peg ").
 *
 * @return array<string, array{0:string, 1:string[]}>
 */
function ccs_care_finder_conditions() {
	return array(
		__( 'Dementia', 'ccs-wp-theme' )                     => array( 'dementia-care', array( 'dementia', 'memory loss', 'memory problem' ) ),
		__( 'Alzheimer’s disease', 'ccs-wp-theme' )          => array( 'dementia-care', array( 'alzheimer', 'alzhiemer' ) ),
		__( 'Vascular dementia', 'ccs-wp-theme' )            => array( 'dementia-care', array( 'vascular dementia' ) ),
		__( 'Lewy body dementia', 'ccs-wp-theme' )           => array( 'dementia-care', array( 'lewy' ) ),
		__( 'Frontotemporal dementia', 'ccs-wp-theme' )      => array( 'dementia-care', array( 'frontotemporal', 'ftd' ) ),
		__( 'Parkinson’s disease', 'ccs-wp-theme' )          => array( 'parkinsons-care', array( 'parkinson' ) ),
		__( 'Stroke', 'ccs-wp-theme' )                       => array( 'stroke-care', array( 'stroke' ) ),
		__( 'Acquired brain injury', 'ccs-wp-theme' )        => array( 'brain-injury-care', array( 'brain injur', 'head injur', 'abi' ) ),
		__( 'Spinal cord injury', 'ccs-wp-theme' )           => array( 'spinal-injury-care', array( 'spinal', 'paraplegi', 'tetraplegi', 'quadriplegi' ) ),
		__( 'Cancer', 'ccs-wp-theme' )                       => array( 'cancer-care', array( 'cancer', 'tumour', 'tumor', 'chemo', 'leukaemia', 'lymphoma' ) ),
		__( 'Cerebral palsy', 'ccs-wp-theme' )               => array( 'cerebral-palsy-care', array( 'cerebral palsy' ) ),
		__( 'Learning disability', 'ccs-wp-theme' )          => array( 'disability-care', array( 'learning disab', 'learning difficult', 'down syndrome', 'down\'s', 'downs syndrome' ) ),
		__( 'Physical disability', 'ccs-wp-theme' )          => array( 'disability-care', array( 'physical disab', 'wheelchair' ) ),
		__( 'Autism', 'ccs-wp-theme' )                       => array( 'disability-care', array( 'autis', 'asd' ) ),
		__( 'Multiple sclerosis (MS)', 'ccs-wp-theme' )      => array( 'complex-care', array( 'multiple sclerosis', 'ms' ) ),
		__( 'Motor neurone disease (MND)', 'ccs-wp-theme' )  => array( 'complex-care', array( 'motor neuron', 'mnd', 'als' ) ),
		__( 'Epilepsy', 'ccs-wp-theme' )                     => array( 'complex-care', array( 'epilep', 'seizure' ) ),
		__( 'PEG or tube feeding', 'ccs-wp-theme' )          => array( 'complex-care', array( 'peg', 'tube fe', 'feeding tube' ) ),
		__( 'Tracheostomy', 'ccs-wp-theme' )                 => array( 'complex-care', array( 'trache', 'trachy' ) ),
		__( 'Ventilation support', 'ccs-wp-theme' )          => array( 'complex-care', array( 'ventilat' ) ),
		__( 'Catheter, stoma or wound care', 'ccs-wp-theme' ) => array( 'nursing-care', array( 'catheter', 'stoma', 'wound' ) ),
	);
}

/**
 * Services for whatever condition text was typed. Several conditions in
 * one answer ("dementia and diabetes") each count.
 *
 * @param string $text Typed text.
 * @return array{services:string[], matched:bool}
 */
function ccs_care_finder_match_conditions( $text ) {
	$norm = static function ( $s ) {
		return preg_replace( '/[^a-z0-9\' ]+/', ' ', str_replace( '’', "'", strtolower( (string) $s ) ) );
	};
	$hay  = ' ' . $norm( $text ) . ' ';
	// Typed words of four letters or more also match as the start of a
	// condition's word, so an unfinished "park" or "demen" still counts.
	$typed = array_filter(
		preg_split( '/\s+/', trim( $hay ) ),
		static function ( $t ) {
			return strlen( $t ) >= 4;
		}
	);
	$out  = array();
	foreach ( ccs_care_finder_conditions() as $label => $row ) {
		$words = array_merge( array( strtolower( $label ) ), $row[1] );
		foreach ( $words as $word ) {
			$word  = trim( $norm( $word ) );
			$short = strlen( $word ) < 4;
			$hit   = $short ? preg_match( '/\b' . preg_quote( $word, '/' ) . '\b/', $hay ) : false !== strpos( $hay, $word );
			if ( ! $hit && ! $short ) {
				foreach ( $typed as $t ) {
					if ( preg_match( '/\b' . preg_quote( $t, '/' ) . '/', $word ) ) {
						$hit = true;
						break;
					}
				}
			}
			if ( $hit ) {
				$out[ $row[0] ] = true;
				break;
			}
		}
	}
	return array(
		'services' => array_keys( $out ),
		'matched'  => (bool) $out,
	);
}

/**
 * The typed condition text, trimmed and capped.
 *
 * @return string
 */
function ccs_care_finder_condition_text() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- read-only form, nothing is stored.
	$text = isset( $_POST['cond'] ) ? sanitize_text_field( wp_unslash( $_POST['cond'] ) ) : '';
	return function_exists( 'mb_substr' ) ? mb_substr( $text, 0, 100 ) : substr( $text, 0, 100 );
}

/**
 * The answers in the request, keeping only known values.
 *
 * @return array<string, string> name => value.
 */
function ccs_care_finder_answers() {
	$answers = array();
	foreach ( ccs_care_finder_questions() as $name => $q ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- read-only form, nothing is stored.
		$value = isset( $_POST[ $name ] ) ? sanitize_key( wp_unslash( $_POST[ $name ] ) ) : '';
		if ( isset( $q['options'][ $value ] ) ) {
			$answers[ $name ] = $value;
		}
	}
	return $answers;
}

/**
 * The services the answers point to, in question order (the typed
 * conditions after "when"), no repeats.
 *
 * @param array<string, string> $answers    From ccs_care_finder_answers().
 * @param string[]              $cond_slugs From ccs_care_finder_match_conditions().
 * @return WP_Post[]
 */
function ccs_care_finder_services( array $answers, array $cond_slugs = array() ) {
	$questions = ccs_care_finder_questions();
	$slugs     = array();
	foreach ( $answers as $name => $value ) {
		$slugs[] = $questions[ $name ]['options'][ $value ][1];
		if ( 'when' === $name ) {
			$slugs = array_merge( $slugs, $cond_slugs );
		}
	}
	if ( ! isset( $answers['when'] ) ) {
		$slugs = array_merge( $slugs, $cond_slugs );
	}
	$out = array();
	foreach ( $slugs as $slug ) {
		if ( '' === $slug || isset( $out[ $slug ] ) ) {
			continue;
		}
		$post = get_page_by_path( $slug, OBJECT, 'service' );
		if ( $post instanceof WP_Post && 'publish' === $post->post_status ) {
			$out[ $slug ] = $post;
		}
	}
	return array_values( $out );
}

/**
 * Create the page once, under Resources. Never re-created if deleted.
 */
function ccs_ensure_care_finder_page() {
	if ( get_option( 'ccs_care_finder_page_v1' ) ) {
		return;
	}
	$parent = get_page_by_path( 'resources' );
	if ( ! $parent instanceof WP_Post ) {
		return; // Pages still being built on a new install; try again later.
	}
	if ( ! add_option( 'ccs_care_finder_page_v1', '1', '', 'yes' ) ) {
		return;
	}
	if ( get_page_by_path( 'resources/' . CCS_CARE_FINDER_SLUG ) ) {
		return;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_parent'  => $parent->ID,
			'post_title'   => __( 'Which care is right for us?', 'ccs-wp-theme' ),
			'post_name'    => CCS_CARE_FINDER_SLUG,
			'post_excerpt' => __( 'Answer four short questions and see which kinds of care fit, then talk it through with us for free.', 'ccs-wp-theme' ),
			'post_content' => '',
			'meta_input'   => array(
				'ccs_page_intro' => __( 'Four short questions. You don’t need to know any care terms.', 'ccs-wp-theme' ),
			),
		),
		true
	);
	if ( ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_page_template', 'page-templates/template-care-finder.php' );
	}
}
add_action( 'init', 'ccs_ensure_care_finder_page', 41 );

/**
 * Address of the page, or '' when it does not exist.
 *
 * @return string
 */
function ccs_care_finder_url() {
	$page = get_page_by_path( 'resources/' . CCS_CARE_FINDER_SLUG );
	return $page instanceof WP_Post && 'publish' === $page->post_status ? get_permalink( $page ) : '';
}
