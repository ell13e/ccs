/**
 * Analytics event tracking: GA4, Facebook Pixel, Google Ads.
 *
 * Respects cookie consent: when consentRequired is true, tracking scripts
 * load only after ccsAnalytics.enableTracking() is called (e.g. from consent banner).
 * Events: phone_click, email_click, form_start, form_submit, button_click, scroll_depth,
 * thank_you_page (conversion).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function () {
	'use strict';

	var config = window.ccsAnalyticsConfig || {};
	var ga4Id = config.ga4Id || '';
	var fbPixelId = config.fbPixelId || '';
	var gadsId = config.gadsId || '';
	var gadsLabel = config.gadsLabel || '';
	var consentRequired = !!config.consentRequired;
	var isThankYouPage = !!config.isThankYouPage;

	var trackingEnabled = !consentRequired;

	/**
	 * Enable tracking (load scripts and flush queue). Call from consent banner when user accepts.
	 */
	function enableTracking() {
		if (trackingEnabled) return;
		trackingEnabled = true;
		if (typeof window.ccsAnalyticsLoadScripts === 'function') {
			window.ccsAnalyticsLoadScripts();
		}
		eventQueue.forEach(function (args) {
			sendEvent.apply(null, args);
		});
		eventQueue = [];
	}

	var eventQueue = [];

	function sendEvent(eventName, eventParams) {
		eventParams = eventParams || {};
		if (!trackingEnabled) {
			eventQueue.push([eventName, eventParams]);
			return;
		}

		// Data layer (for GTM or debugging).
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
			event: 'ccs_' + eventName,
			eventParams: eventParams
		});

		// GA4
		if (ga4Id && typeof gtag === 'function') {
			gtag('event', eventName, eventParams);
		}

		// Facebook Pixel
		if (fbPixelId && typeof fbq === 'function') {
			var fbEvent = eventParams.fbEvent || mapToFbEvent(eventName);
			if (fbEvent) {
				fbq('track', fbEvent, eventParams.fbCustomData || {});
			}
		}

		// Google Ads conversion (for conversion events only).
		if (gadsId && (eventName === 'conversion' || eventName === 'form_submit' || eventName === 'thank_you_page')) {
			if (typeof gtag === 'function') {
				var sendTo = gadsLabel ? gadsId + '/' + gadsLabel : gadsId;
				gtag('event', 'conversion', { send_to: sendTo });
			}
		}
	}

	function mapToFbEvent(eventName) {
		var map = {
			form_submit: 'Lead',
			form_start: 'Lead',
			phone_click: 'Contact',
			email_click: 'Contact',
			thank_you_page: 'Lead',
			conversion: 'Lead'
		};
		return map[eventName] || null;
	}

	/**
	 * Track phone link click.
	 */
	function onPhoneClick(e) {
		var link = e.target.closest('a[href^="tel:"]');
		if (!link) return;
		sendEvent('phone_click', {
			link_url: link.getAttribute('href') || '',
			link_text: (link.textContent || '').trim().slice(0, 100)
		});
	}

	/**
	 * Track email link click.
	 */
	function onEmailClick(e) {
		var link = e.target.closest('a[href^="mailto:"]');
		if (!link) return;
		sendEvent('email_click', {
			link_url: link.getAttribute('href') || '',
			link_text: (link.textContent || '').trim().slice(0, 100)
		});
	}

	/**
	 * Track form start (first focus/interaction).
	 */
	function onFormStart(e) {
		var form = e.target.closest('form');
		if (!form || form.dataset.ccsAnalyticsFormTracked) return;
		form.dataset.ccsAnalyticsFormTracked = '1';
		var action = form.getAttribute('data-ccs-action') || form.action || '';
		sendEvent('form_start', { form_action: action });
	}

	/**
	 * Form submit is handled by form-handler.js; we listen for custom event or same form submit for non-AJAX.
	 */
	function onFormSubmit(e) {
		var form = e.target.closest('form');
		if (!form) return;
		// AJAX forms (data-ccs-action) report through ccs_form_success, and only
		// once the server has accepted them. Counting their submit event as well
		// recorded every enquiry twice, and counted attempts that failed
		// validation as conversions.
		if (form.hasAttribute('data-ccs-action')) return;
		var action = form.getAttribute('data-ccs-action') || form.action || '';
		sendEvent('form_submit', { form_action: action });
	}

	/**
	 * Track CTA / primary button clicks (.btn-primary, .btn-accent, .btn-phone, or data-ccs-track).
	 */
	function onButtonClick(e) {
		// .btn-accent is the mint "Book a free consultation" button used on the
		// hero and every closing band: the site's main call to action, and it was
		// missing from this list, so its clicks were never recorded.
		var btn = e.target.closest('.btn-primary, .btn-accent, .btn-phone, [data-ccs-track]');
		if (!btn) return;
		var label = (btn.textContent || btn.getAttribute('aria-label') || '').trim().slice(0, 100);
		sendEvent('button_click', { button_text: label });
	}

	/**
	 * Scroll depth: 25%, 50%, 75%, 100%.
	 */
	var scrollDepths = { 25: false, 50: false, 75: false, 100: false };

	function onScroll() {
		var h = document.documentElement.scrollHeight - window.innerHeight;
		if (h <= 0) return;
		var pct = Math.round((window.scrollY / h) * 100);
		[25, 50, 75, 100].forEach(function (threshold) {
			if (!scrollDepths[threshold] && pct >= threshold) {
				scrollDepths[threshold] = true;
				sendEvent('scroll_depth', { depth: threshold });
			}
		});
	}

	/**
	 * Cookie choice bar (markup from CCS_Analytics::render_consent_bar()).
	 * The choice is kept in localStorage, which is not sent to any server.
	 * No stored choice: show the bar. "granted": load tracking. "denied": do
	 * nothing. If storage is unavailable the bar is shown on each page and the
	 * choice simply lasts for that page.
	 */
	var CONSENT_KEY = 'ccsAnalyticsConsent';

	function readConsent() {
		try { return window.localStorage.getItem(CONSENT_KEY); } catch (e) { return null; }
	}

	function initConsent() {
		var bar = document.getElementById('ccs-consent');
		var stored = readConsent();

		if (stored === 'granted') {
			enableTracking();
			return;
		}
		if (!bar || stored === 'denied') return;

		bar.hidden = false;
		// Lift the floating call button clear of the bar: the phone number must
		// never be covered (CLAUDE.md rule 21).
		document.documentElement.style.setProperty('--ccs-consent-height', bar.offsetHeight + 'px');
		document.body.classList.add('ccs-consent-open');

		bar.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-ccs-consent]');
			if (!btn) return;
			var choice = btn.getAttribute('data-ccs-consent');
			try { window.localStorage.setItem(CONSENT_KEY, choice); } catch (err) { /* choice lasts for this page only */ }
			bar.hidden = true;
			document.body.classList.remove('ccs-consent-open');
			document.documentElement.style.removeProperty('--ccs-consent-height');
			if (choice === 'granted') enableTracking();
		});
	}

	function init() {
		if (!ga4Id && !fbPixelId && !gadsId) return;

		if (consentRequired) initConsent();

		// Thank you page: fire conversion once.
		if (isThankYouPage) {
			sendEvent('thank_you_page', { page: 'thank_you' });
			sendEvent('conversion', { type: 'thank_you_page' });
		}

		// Delegated listeners.
		document.addEventListener('click', function (e) {
			onPhoneClick(e);
			onEmailClick(e);
			onButtonClick(e);
		});

		document.addEventListener('focusin', onFormStart);

		// AJAX form success: form-handler.js dispatches ccs_form_success; we track form_submit + conversion.
		document.addEventListener('ccs_form_success', function (e) {
			var action = (e.detail && e.detail.action) || '';
			sendEvent('form_submit', { form_action: action });
		});

		// Non-AJAX form submit (fallback).
		document.addEventListener('submit', onFormSubmit, true);

		// Scroll depth (throttled).
		var scrollTicking = false;
		window.addEventListener('scroll', function () {
			if (scrollTicking) return;
			scrollTicking = true;
			requestAnimationFrame(function () {
				onScroll();
				scrollTicking = false;
			});
		}, { passive: true });
	}

	// Expose for consent banner.
	window.ccsAnalytics = {
		enableTracking: enableTracking,
		sendEvent: sendEvent,
		get trackingEnabled() { return trackingEnabled; }
	};

	// When consent is required, scripts are not in the page yet; provide a loader for when consent is given.
	if (consentRequired && (ga4Id || fbPixelId || gadsId)) {
		window.ccsAnalyticsLoadScripts = function () {
			var firstGtagId = ga4Id || gadsId;
			if (firstGtagId && typeof gtag === 'undefined') {
				window.dataLayer = window.dataLayer || [];
				function gtag() { dataLayer.push(arguments); }
				window.gtag = gtag;
				gtag('js', new Date());
				var s = document.createElement('script');
				s.async = true;
				s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(firstGtagId);
				document.head.appendChild(s);
				if (ga4Id) {
					gtag('config', ga4Id, { anonymize_ip: true, allow_google_signals: false, allow_ad_personalization_signals: false });
				}
				if (gadsId) {
					gtag('config', gadsId, { anonymize_ip: true });
				}
			}
			if (fbPixelId && typeof fbq === 'undefined') {
				var f = window; var b = document; var e = 'script'; var v = 'https://connect.facebook.net/en_US/fbevents.js';
				var n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments); };
				if (!f._fbq) f._fbq = n; n.push = n; n.loaded = true; n.version = '2.0'; n.queue = [];
				var t = b.createElement(e); t.async = true; t.src = v;
				var s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s);
				fbq('set', 'autoConfig', false);
				fbq('init', fbPixelId);
				fbq('track', 'PageView');
			}
		};
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
