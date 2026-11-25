<?php
/**
 * AI search crawlers in robots.txt (Nov 2026).
 *
 * ChatGPT search, Claude and Perplexity each send a search crawler that
 * fetches pages so they can be cited with a link back to the site. They
 * already fall under "User-agent: *", but naming them makes the choice
 * explicit and visible to anyone reading robots.txt.
 *
 * A crawler that finds its own group ignores the "*" group, so each named
 * group repeats the same rules (keep wp-admin out, allow admin-ajax).
 *
 * Bots that collect pages to train AI models (GPTBot, ClaudeBot, CCBot,
 * Google-Extended) are deliberately left alone: whether to block them is a
 * business decision, not an SEO one. Use the ccs_seo_ai_blocked_bots filter
 * to block them.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the AI search crawler group (and any blocked bots) to robots.txt.
 *
 * @param string $output    robots.txt as WordPress built it.
 * @param bool   $is_public Whether the site asks to be indexed.
 * @return string
 */
function ccs_seo_robots_ai( $output, $is_public ) {
	if ( ! $is_public ) {
		return $output;
	}
	if ( function_exists( 'ccs_seo_plugin_active' ) && ccs_seo_plugin_active() ) {
		return $output;
	}

	/**
	 * AI search crawlers named and allowed in robots.txt.
	 *
	 * @param string[] $bots User-agent tokens.
	 */
	$allowed = (array) apply_filters( 'ccs_seo_ai_search_bots', array( 'OAI-SearchBot', 'Claude-SearchBot', 'PerplexityBot' ) );

	/**
	 * Bots blocked from the whole site (none by default).
	 *
	 * @param string[] $bots User-agent tokens, e.g. 'GPTBot', 'ClaudeBot', 'CCBot', 'Google-Extended'.
	 */
	$blocked = (array) apply_filters( 'ccs_seo_ai_blocked_bots', array() );

	$clean = static function ( $bots ) {
		return array_values( array_filter( array_map( static function ( $bot ) {
			return preg_replace( '/[^A-Za-z0-9._-]/', '', (string) $bot );
		}, $bots ) ) );
	};
	$allowed = $clean( $allowed );
	$blocked = array_diff( $clean( $blocked ), $allowed );

	$admin = wp_parse_url( admin_url(), PHP_URL_PATH );
	$admin = $admin ? $admin : '/wp-admin/';
	$block = '';

	if ( $allowed ) {
		$block .= "\n# AI search crawlers: allowed, so answers can cite and link to this site.\n";
		foreach ( $allowed as $bot ) {
			$block .= 'User-agent: ' . $bot . "\n";
		}
		$block .= 'Disallow: ' . $admin . "\n";
		$block .= 'Allow: ' . $admin . "admin-ajax.php\n";
	}
	if ( $blocked ) {
		$block .= "\n";
		foreach ( $blocked as $bot ) {
			$block .= 'User-agent: ' . $bot . "\n";
		}
		$block .= "Disallow: /\n";
	}
	if ( '' === $block ) {
		return $output;
	}

	// Keep the Sitemap line last, where people expect it.
	if ( preg_match( '/\n\s*Sitemap:/i', $output, $m, PREG_OFFSET_CAPTURE ) ) {
		$at = $m[0][1];
		return rtrim( substr( $output, 0, $at ) ) . "\n" . $block . "\n" . ltrim( substr( $output, $at ) );
	}
	return rtrim( $output ) . "\n" . $block;
}
add_filter( 'robots_txt', 'ccs_seo_robots_ai', 20, 2 );
