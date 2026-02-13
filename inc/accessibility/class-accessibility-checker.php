<?php
/**
 * WCAG 2.1 AA accessibility checker – admin notices for common issues.
 *
 * Runs checks against theme templates and options; surfaces notices in admin.
 * Does not replace manual testing or automated tools (axe, WAVE).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Accessibility_Checker
 */
class CCS_Accessibility_Checker {

	const TRANSIENT_KEY = 'ccs_a11y_check_results';
	const TRANSIENT_TTL  = 3600; // 1 hour

	/**
	 * WCAG 2.1 AA checklist (reference + what we can auto-check).
	 *
	 * @return array[]
	 */
	public static function get_checklist() {
		return array(
			// 1. Color contrast
			array(
				'id'          => 'color-contrast',
				'title'       => __( 'Color contrast', 'ccs-wp-theme' ),
				'criterion'   => '1.4.3 Contrast (Minimum) AA',
				'requirement'  => __( 'Body text ≥4.5:1, large text ≥3:1 on background.', 'ccs-wp-theme' ),
				'check_type'  => 'manual',
				'how_to_check' => __( 'Audit design-system.css and all CSS: --color-text on --color-background, --color-primary on white, --color-urgent on white, --color-text-light on white. Use contrast checker.', 'ccs-wp-theme' ),
			),
			// 2. Keyboard / focus
			array(
				'id'          => 'skip-link',
				'title'       => __( 'Skip to content link', 'ccs-wp-theme' ),
				'criterion'   => '2.4.1 Bypass Blocks',
				'requirement'  => __( 'Skip link targets #main; visible on focus.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'focus-visible',
				'title'       => __( 'Visible focus indicators', 'ccs-wp-theme' ),
				'criterion'   => '2.4.7 Focus Visible',
				'requirement'  => __( 'All interactive elements have visible focus (e.g. :focus-visible).', 'ccs-wp-theme' ),
				'check_type'  => 'manual',
				'how_to_check' => __( 'Tab through header, nav, buttons, form controls. Check design-system.css and components.css for --focus-ring.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'keyboard-tab-order',
				'title'       => __( 'Logical tab order', 'ccs-wp-theme' ),
				'criterion'   => '2.4.3 Focus Order',
				'requirement'  => __( 'Tab order follows visual order; no tabindex positive.', 'ccs-wp-theme' ),
				'check_type'  => 'manual',
			),
			// 3. ARIA
			array(
				'id'          => 'aria-nav-label',
				'title'       => __( 'Navigation ARIA label', 'ccs-wp-theme' ),
				'criterion'   => '4.1.2 Name, Role, Value',
				'requirement'  => __( 'Primary nav has aria-label.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'aria-toggle-expanded',
				'title'       => __( 'Menu toggle aria-expanded', 'ccs-wp-theme' ),
				'criterion'   => '4.1.2 Name, Role, Value',
				'requirement'  => __( 'Hamburger button has aria-expanded and aria-controls.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'aria-accordion',
				'title'       => __( 'FAQ / accordion aria-expanded', 'ccs-wp-theme' ),
				'criterion'   => '4.1.2 Name, Role, Value',
				'requirement'  => __( 'Accordion buttons have aria-expanded and aria-controls.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'aria-live',
				'title'       => __( 'Dynamic content (form messages)', 'ccs-wp-theme' ),
				'criterion'   => '4.1.3 Status Messages',
				'requirement'  => __( 'Form success/error messages have role="alert" or aria-live.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'landmarks',
				'title'       => __( 'Landmark roles', 'ccs-wp-theme' ),
				'criterion'   => '1.3.1 Info and Relationships',
				'requirement'  => __( 'Header role="banner", main role="main", footer role="contentinfo", nav aria-label.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			// 4. Forms
			array(
				'id'          => 'form-labels',
				'title'       => __( 'Form labels associated', 'ccs-wp-theme' ),
				'criterion'   => '1.3.1, 3.3.2 Labels or Instructions',
				'requirement'  => __( 'Every form control has a visible label with for/id.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'form-required',
				'title'       => __( 'Required fields indicated', 'ccs-wp-theme' ),
				'criterion'   => '3.3.2 Labels or Instructions',
				'requirement'  => __( 'Required fields have required attribute and visual indicator (e.g. * with aria-hidden).', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			array(
				'id'          => 'form-errors-announced',
				'title'       => __( 'Error messages announced', 'ccs-wp-theme' ),
				'criterion'   => '3.3.1 Error Identification, 3.3.3 Error Suggestion',
				'requirement'  => __( 'Validation errors in role="alert" or aria-live so screen readers announce them.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			// 5. Images
			array(
				'id'          => 'image-alt',
				'title'       => __( 'Image alt text', 'ccs-wp-theme' ),
				'criterion'   => '1.1.1 Non-text Content',
				'requirement'  => __( 'Decorative: alt="". Informative: descriptive alt, no "image of" prefix.', 'ccs-wp-theme' ),
				'check_type'  => 'auto',
			),
			// 6. Headings
			array(
				'id'          => 'heading-one-h1',
				'title'       => __( 'One H1 per page', 'ccs-wp-theme' ),
				'criterion'   => '1.3.1 Info and Relationships',
				'requirement'  => __( 'Only one H1 per page; describes main content.', 'ccs-wp-theme' ),
				'check_type'  => 'manual',
				'how_to_check' => __( 'Homepage: hero H1. Contact: H1 "Send us a message". Service: H1 = title. Location: H1 = town. Single post: H1 = title.', 'ccs-wp-theme' ),
			),
			array(
				'id'          => 'heading-hierarchy',
				'title'       => __( 'Heading hierarchy (no skips)', 'ccs-wp-theme' ),
				'criterion'   => '1.3.1 Info and Relationships',
				'requirement'  => __( 'No skipped levels (e.g. H1 then H3).', 'ccs-wp-theme' ),
				'check_type'  => 'manual',
			),
		);
	}

	/**
	 * Run automatic checks (template/option scan).
	 *
	 * @return array [ 'id' => [ 'status' => 'pass'|'warn'|'fail', 'message' => '' ], ... ]
	 */
	public static function run_checks() {
		$results = array();
		$theme_dir = get_template_directory();

		// Skip link
		$header = file_get_contents( $theme_dir . '/header.php' );
		$results['skip-link'] = array(
			'status'  => ( strpos( $header, 'skip-link' ) !== false && strpos( $header, '#main' ) !== false ) ? 'pass' : 'fail',
			'message' => __( 'Skip link present and targets #main.', 'ccs-wp-theme' ),
		);
		if ( $results['skip-link']['status'] !== 'pass' ) {
			$results['skip-link']['message'] = __( 'Add a skip link (e.g. <a href="#main" class="skip-link">Skip to content</a>) in header.php.', 'ccs-wp-theme' );
		}

		// Nav aria-label
		$results['aria-nav-label'] = array(
			'status'  => ( strpos( $header, 'aria-label' ) !== false && strpos( $header, 'site-navigation' ) !== false ) ? 'pass' : 'fail',
			'message' => __( 'Primary nav has aria-label.', 'ccs-wp-theme' ),
		);
		if ( $results['aria-nav-label']['status'] !== 'pass' ) {
			$results['aria-nav-label']['message'] = __( 'Add aria-label to the primary <nav> in header.php.', 'ccs-wp-theme' );
		}

		// Toggle aria-expanded / aria-controls
		$results['aria-toggle-expanded'] = array(
			'status'  => ( strpos( $header, 'aria-expanded' ) !== false && strpos( $header, 'aria-controls' ) !== false && strpos( $header, 'primary-menu' ) !== false ) ? 'pass' : 'fail',
			'message' => __( 'Menu toggle has aria-expanded and aria-controls.', 'ccs-wp-theme' ),
		);
		if ( $results['aria-toggle-expanded']['status'] !== 'pass' ) {
			$results['aria-toggle-expanded']['message'] = __( 'Add aria-expanded and aria-controls="primary-menu" to the hamburger button.', 'ccs-wp-theme' );
		}

		// FAQ block aria-expanded
		$faq_block = file_get_contents( $theme_dir . '/inc/blocks/class-faq-block.php' );
		$results['aria-accordion'] = array(
			'status'  => ( strpos( $faq_block, 'aria-expanded' ) !== false && strpos( $faq_block, 'aria-controls' ) !== false ) ? 'pass' : 'fail',
			'message' => __( 'FAQ accordion buttons have aria-expanded and aria-controls.', 'ccs-wp-theme' ),
		);
		if ( $results['aria-accordion']['status'] !== 'pass' ) {
			$results['aria-accordion']['message'] = __( 'Ensure FAQ block outputs aria-expanded and aria-controls on each question button.', 'ccs-wp-theme' );
		}

		// Form message role=alert
		$contact = file_get_contents( $theme_dir . '/page-templates/template-contact.php' );
		$results['aria-live'] = array(
			'status'  => ( strpos( $contact, 'role="alert"' ) !== false || strpos( $contact, "role='alert'" ) !== false ) && strpos( $contact, 'data-ccs-form-message' ) !== false ? 'pass' : 'fail',
			'message' => __( 'Form message container has role="alert" for screen reader announcement.', 'ccs-wp-theme' ),
		);
		if ( $results['aria-live']['status'] !== 'pass' ) {
			$results['aria-live']['message'] = __( 'Add role="alert" to the form message div and ensure JS updates it on submit.', 'ccs-wp-theme' );
		}

		// Form labels
		$labels_ok = preg_match_all( '/<label\s+[^>]*for=["\']([^"\']+)["\']/', $contact, $label_fors )
			&& preg_match_all( '/<input[^>]+id=["\']([^"\']+)["\']/', $contact, $input_ids );
		$select_ok = preg_match_all( '/<select[^>]+id=["\']([^"\']+)["\']/', $contact, $select_ids );
		$results['form-labels'] = array(
			'status'  => ( $labels_ok && ( ! empty( $input_ids[1] ) || ! empty( $select_ids[1] ) ) ) ? 'pass' : 'warn',
			'message' => __( 'Contact form uses <label for="..."> with matching id on controls.', 'ccs-wp-theme' ),
		);
		if ( $results['form-labels']['status'] !== 'pass' ) {
			$results['form-labels']['message'] = __( 'Ensure every input/select has an associated <label for="id">.', 'ccs-wp-theme' );
		}

		// Required fields
		$has_required = strpos( $contact, 'required' ) !== false && ( strpos( $contact, 'aria-hidden="true"' ) !== false || strpos( $contact, 'required' ) !== false );
		$results['form-required'] = array(
			'status'  => $has_required ? 'pass' : 'warn',
			'message' => __( 'Required fields have required attribute and optional visual indicator.', 'ccs-wp-theme' ),
		);
		if ( $results['form-required']['status'] !== 'pass' ) {
			$results['form-required']['message'] = __( 'Add required to mandatory inputs and indicate with * (aria-hidden) or text.', 'ccs-wp-theme' );
		}

		// Form errors announced (role=alert already checked in aria-live)
		$results['form-errors-announced'] = $results['aria-live'];

		// Hero image alt
		$hero_image_id = get_theme_mod( 'ccs_hero_image', 0 );
		if ( $hero_image_id ) {
			$alt = get_post_meta( (int) $hero_image_id, '_wp_attachment_image_alt', true );
			$results['image-alt'] = array(
				'status'  => is_string( $alt ) && trim( $alt ) !== '' ? 'pass' : 'warn',
				'message' => is_string( $alt ) && trim( $alt ) !== '' ? __( 'Hero image has alt text in Media Library.', 'ccs-wp-theme' ) : __( 'Hero image is set but alt is empty. Add descriptive alt in Media Library, or leave empty if purely decorative.', 'ccs-wp-theme' ),
			);
		} else {
			$results['image-alt'] = array(
				'status'  => 'pass',
				'message' => __( 'No hero image set, or theme uses attachment alt when set.', 'ccs-wp-theme' ),
			);
		}

		// Landmarks: header, main, footer (main is in page templates)
		$footer = file_get_contents( $theme_dir . '/footer.php' );
		$has_banner = strpos( $header, 'role="banner"' ) !== false || strpos( $header, "role='banner'" ) !== false;
		$has_main = strpos( $contact, 'id="main"' ) !== false && ( strpos( $contact, 'role="main"' ) !== false || strpos( $contact, "role='main'" ) !== false );
		$has_contentinfo = strpos( $footer, 'role="contentinfo"' ) !== false || strpos( $footer, "role='contentinfo'" ) !== false;
		$results['landmarks'] = array(
			'status'  => ( $has_banner && $has_main && $has_contentinfo ) ? 'pass' : 'fail',
			'message' => ( $has_banner && $has_main && $has_contentinfo ) ? __( 'Header, main, and footer have correct landmark roles.', 'ccs-wp-theme' ) : __( 'Ensure <header role="banner">, <main id="main" role="main">, <footer role="contentinfo">.', 'ccs-wp-theme' ),
		);

		return $results;
	}

	/**
	 * Get check results (from transient or run).
	 *
	 * @return array
	 */
	public static function get_results() {
		$cached = get_transient( self::TRANSIENT_KEY );
		if ( is_array( $cached ) ) {
			return $cached;
		}
		$results = self::run_checks();
		set_transient( self::TRANSIENT_KEY, $results, self::TRANSIENT_TTL );
		return $results;
	}

	/**
	 * Clear cached results (e.g. after theme update).
	 */
	public static function clear_cache() {
		delete_transient( self::TRANSIENT_KEY );
	}

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'switch_theme', array( __CLASS__, 'clear_cache' ) );
	}

	/**
	 * Admin init: show notices.
	 */
	public static function admin_init() {
		$dismissed = get_option( 'ccs_a11y_notices_dismissed', array() );
		if ( ! is_array( $dismissed ) ) {
			$dismissed = array();
		}

		$results = self::get_results();
		$has_fail = false;
		$has_warn = false;
		foreach ( $results as $id => $r ) {
			if ( isset( $r['status'] ) && $r['status'] === 'fail' ) {
				$has_fail = true;
			}
			if ( isset( $r['status'] ) && $r['status'] === 'warn' ) {
				$has_warn = true;
			}
		}

		if ( ! $has_fail && ! $has_warn ) {
			return;
		}
		if ( in_array( 'all', $dismissed, true ) ) {
			return;
		}

		add_action( 'admin_notices', function () use ( $results, $dismissed ) {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
			if ( $screen && $screen->id !== 'dashboard' && $screen->id !== 'themes' ) {
				return;
			}

			$fail_count = 0;
			$warn_count = 0;
			foreach ( $results as $r ) {
				if ( isset( $r['status'] ) && $r['status'] === 'fail' ) {
					$fail_count++;
				}
				if ( isset( $r['status'] ) && $r['status'] === 'warn' ) {
					$warn_count++;
				}
			}

			$message = sprintf(
				/* translators: 1: number of failures, 2: number of warnings */
				__( 'Accessibility check: %1$d issue(s) need attention, %2$d warning(s). <a href="%3$s">View full WCAG 2.1 AA checklist</a> or <a href="%4$s">dismiss</a>.', 'ccs-wp-theme' ),
				$fail_count,
				$warn_count,
				esc_url( add_query_arg( 'ccs_a11y', '1', admin_url( 'themes.php' ) ) ),
				esc_url( add_query_arg( 'ccs_a11y_dismiss', '1', admin_url( 'themes.php' ) ) )
			);

			echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post( $message ) . '</p></div>';
		} );

		// Dismiss handler
		if ( isset( $_GET['ccs_a11y_dismiss'] ) && $_GET['ccs_a11y_dismiss'] === '1' ) {
			update_option( 'ccs_a11y_notices_dismissed', array( 'all' ) );
			wp_safe_redirect( remove_query_arg( 'ccs_a11y_dismiss' ) );
			exit;
		}

		// Full checklist on themes.php?ccs_a11y=1 (pagenow set before admin_notices)
		add_action( 'admin_notices', array( __CLASS__, 'maybe_render_checklist' ), 5 );
	}

	/**
	 * Admin notice: full WCAG checklist table when themes.php?ccs_a11y=1.
	 */
	public static function maybe_render_checklist() {
		$pagenow = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';
		if ( $pagenow !== 'themes.php' || ! isset( $_GET['ccs_a11y'] ) || $_GET['ccs_a11y'] !== '1' ) {
			return;
		}
		$results = self::get_results();
		echo '<div class="notice notice-info" style="margin: 1em 0;"><p><strong>' . esc_html__( 'WCAG 2.1 AA accessibility checklist', 'ccs-wp-theme' ) . '</strong></p>';
		echo self::render_checklist_table( $results );
		echo '</div>';
	}

	/**
	 * Render full checklist (for themes.php?ccs_a11y=1 or docs).
	 *
	 * @param array $results Optional. Result of get_results().
	 * @return string HTML table.
	 */
	public static function render_checklist_table( $results = null ) {
		if ( $results === null ) {
			$results = self::get_results();
		}
		$checklist = self::get_checklist();
		$out = '<div class="ccs-a11y-checklist-wrap"><table class="widefat striped"><thead><tr><th>' . esc_html__( 'Check', 'ccs-wp-theme' ) . '</th><th>' . esc_html__( 'Criterion', 'ccs-wp-theme' ) . '</th><th>' . esc_html__( 'Requirement', 'ccs-wp-theme' ) . '</th><th>' . esc_html__( 'Status', 'ccs-wp-theme' ) . '</th></tr></thead><tbody>';
		foreach ( $checklist as $item ) {
			$id = $item['id'];
			$res = isset( $results[ $id ] ) ? $results[ $id ] : array( 'status' => 'manual', 'message' => '' );
			$status = isset( $res['status'] ) ? $res['status'] : 'manual';
			if ( $status === 'manual' && isset( $item['how_to_check'] ) ) {
				$status_label = __( 'Manual check', 'ccs-wp-theme' );
				$status_class = 'manual';
			} else {
				$status_label = $status === 'pass' ? __( 'Pass', 'ccs-wp-theme' ) : ( $status === 'warn' ? __( 'Warning', 'ccs-wp-theme' ) : __( 'Fail', 'ccs-wp-theme' ) );
				$status_class = $status;
			}
			$msg = isset( $res['message'] ) ? $res['message'] : ( isset( $item['how_to_check'] ) ? $item['how_to_check'] : '' );
			$out .= '<tr><td><strong>' . esc_html( $item['title'] ) . '</strong></td><td>' . esc_html( $item['criterion'] ) . '</td><td>' . esc_html( $item['requirement'] ) . '</td><td class="ccs-a11y-status-' . esc_attr( $status_class ) . '">' . esc_html( $status_label ) . ( $msg ? '<br><small>' . esc_html( $msg ) . '</small>' : '' ) . '</td></tr>';
		}
		$out .= '</tbody></table></div>';
		return $out;
	}
}
