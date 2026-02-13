<?php
/**
 * Analytics and conversion tracking: GA4, Facebook Pixel, Google Ads.
 *
 * Outputs tracking codes in wp_head (respecting cookie consent when required).
 * Conversion tracking: thank you page, form submissions, phone/email clicks.
 * Event tracking is handled by assets/js/analytics-events.js.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Analytics
 */
class CCS_Analytics {

	/**
	 * Theme mod keys for tracking IDs and options.
	 */
	const MOD_GA4_ID           = 'ccs_analytics_ga4_id';
	const MOD_FB_PIXEL_ID      = 'ccs_analytics_fb_pixel_id';
	const MOD_GADS_ID          = 'ccs_analytics_gads_id';
	const MOD_GADS_LABEL       = 'ccs_analytics_gads_label';
	const MOD_REQUIRE_CONSENT  = 'ccs_analytics_require_consent';
	const MOD_THANK_YOU_PAGE   = 'ccs_analytics_thank_you_page';

	/**
	 * Constructor: front-end hooks only. Customizer section is in CCS_Theme_Customizer.
	 */
	public function __construct() {
		if ( is_admin() ) {
			return;
		}
		add_action( 'wp_head', array( $this, 'output_tracking_and_data_layer' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_events_script' ), 20 );
	}

	/**
	 * Output tracking scripts and data layer in wp_head (or only data layer if consent required).
	 */
	public function output_tracking_and_data_layer() {
		$ga4_id    = get_theme_mod( self::MOD_GA4_ID, '' );
		$fb_id     = get_theme_mod( self::MOD_FB_PIXEL_ID, '' );
		$gads_id   = get_theme_mod( self::MOD_GADS_ID, '' );
		$gads_lbl  = get_theme_mod( self::MOD_GADS_LABEL, '' );
		$consent   = (bool) get_theme_mod( self::MOD_REQUIRE_CONSENT, false );
		$thank_you = get_theme_mod( self::MOD_THANK_YOU_PAGE, '' );

		$has_any = ( $ga4_id !== '' || $fb_id !== '' || $gads_id !== '' );
		if ( ! $has_any ) {
			return;
		}

		$is_thank_you = $this->is_thank_you_page( $thank_you );

		// Data layer for GTM/events (always output so our JS can use it).
		echo "\n<script>\nwindow.dataLayer = window.dataLayer || [];\n";
		echo "window.dataLayer.push({\n";
		echo "  'ccsAnalytics': {\n";
		echo "    'consentRequired': " . ( $consent ? 'true' : 'false' ) . ",\n";
		echo "    'thankYouPage': " . wp_json_encode( $is_thank_you ) . ",\n";
		echo "    'ga4Id': " . wp_json_encode( $ga4_id ) . ",\n";
		echo "    'fbPixelId': " . wp_json_encode( $fb_id ) . ",\n";
		echo "    'gadsId': " . wp_json_encode( $gads_id ) . ",\n";
		echo "    'gadsLabel': " . wp_json_encode( $gads_lbl ) . "\n";
		echo "  }\n";
		echo "});\n</script>\n";

		// If consent required, do not output third-party scripts; analytics-events.js will load them when consent is given.
		if ( $consent ) {
			return;
		}

		// Google Analytics 4 (with IP anonymization).
		if ( $ga4_id !== '' ) {
			$this->output_ga4_script( $ga4_id );
		}

		// Facebook Pixel.
		if ( $fb_id !== '' ) {
			$this->output_fb_pixel_script( $fb_id );
		}

		// Google Ads conversion (global site tag + conversion config).
		if ( $gads_id !== '' ) {
			$this->output_google_ads_script( $gads_id, $gads_lbl );
		}
	}

	/**
	 * Output GA4 gtag script with anonymize IP.
	 *
	 * @param string $measurement_id GA4 Measurement ID (e.g. G-XXXXXXXXXX).
	 */
	private function output_ga4_script( $measurement_id ) {
		$id = preg_replace( '/[^A-Za-z0-9_-]/', '', $measurement_id );
		if ( $id === '' ) {
			return;
		}
		?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){ dataLayer.push(arguments); }
	gtag('js', new Date());
	gtag('config', '<?php echo esc_js( $id ); ?>', {
		'anonymize_ip': true,
		'allow_google_signals': false,
		'allow_ad_personalization_signals': false
	});
</script>
		<?php
	}

	/**
	 * Output Facebook Pixel base code.
	 *
	 * @param string $pixel_id Facebook Pixel ID (numeric).
	 */
	private function output_fb_pixel_script( $pixel_id ) {
		$id = preg_replace( '/\D/', '', $pixel_id );
		if ( $id === '' ) {
			return;
		}
		?>
<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments);};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('set', 'autoConfig', false);
	fbq('init', '<?php echo esc_js( $id ); ?>');
	fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_attr( $id ); ?>&ev=PageView&noscript=1" alt="" /></noscript>
		<?php
	}

	/**
	 * Output Google Ads global site tag and conversion snippet.
	 *
	 * @param string $conversion_id e.g. AW-123456789.
	 * @param string $conversion_label Optional label.
	 */
	private function output_google_ads_script( $conversion_id, $conversion_label = '' ) {
		$id = preg_replace( '/[^A-Za-z0-9_-]/', '', $conversion_id );
		if ( $id === '' ) {
			return;
		}
		?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){ dataLayer.push(arguments); }
	gtag('js', new Date());
	gtag('config', '<?php echo esc_js( $id ); ?>', { 'anonymize_ip': true });
</script>
		<?php
		// Conversion is fired by JS (thank you page or form submit); no inline conversion here.
	}

	/**
	 * Check if current request is the thank you page (for conversion).
	 *
	 * @param string $thank_you_slug Page slug or path from theme mod.
	 * @return bool
	 */
	private function is_thank_you_page( $thank_you_slug ) {
		if ( $thank_you_slug === '' ) {
			return false;
		}
		$thank_you_slug = trim( $thank_you_slug, "/ \t\n\r\0\x0B" );
		if ( $thank_you_slug === '' ) {
			return false;
		}
		if ( is_page( $thank_you_slug ) ) {
			return true;
		}
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$path = trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
		return $path === $thank_you_slug || substr( $path, -strlen( $thank_you_slug ) - 1 ) === '/' . $thank_you_slug;
	}

	/**
	 * Enqueue analytics-events.js and pass config (IDs, consent, thank you).
	 */
	public function enqueue_events_script() {
		$ga4_id    = get_theme_mod( self::MOD_GA4_ID, '' );
		$fb_id     = get_theme_mod( self::MOD_FB_PIXEL_ID, '' );
		$gads_id   = get_theme_mod( self::MOD_GADS_ID, '' );
		$gads_lbl  = get_theme_mod( self::MOD_GADS_LABEL, '' );
		$consent   = (bool) get_theme_mod( self::MOD_REQUIRE_CONSENT, false );
		$thank_you = get_theme_mod( self::MOD_THANK_YOU_PAGE, '' );

		$has_any = ( $ga4_id !== '' || $fb_id !== '' || $gads_id !== '' );
		if ( ! $has_any ) {
			return;
		}

		wp_enqueue_script(
			'ccs-analytics-events',
			THEME_URL . '/assets/js/analytics-events.js',
			array(),
			THEME_VERSION,
			true
		);

		$thank_you_slug = trim( (string) $thank_you, "/ \t\n\r\0\x0B" );

		wp_localize_script( 'ccs-analytics-events', 'ccsAnalyticsConfig', array(
			'ga4Id'           => $ga4_id,
			'fbPixelId'       => $fb_id,
			'gadsId'          => $gads_id,
			'gadsLabel'       => $gads_lbl,
			'consentRequired' => $consent,
			'thankYouSlug'    => $thank_you_slug,
			'isThankYouPage'  => $this->is_thank_you_page( $thank_you_slug ),
			'homeUrl'         => home_url( '/' ),
		) );
	}
}
