/**
 * Live preview for Theme Customizer (contact, emergency banner).
 *
 * Binds to postMessage settings and updates the preview frame without refresh.
 *
 * @package CCS_WP_Theme
 */

(function () {
	'use strict';

	wp.customize.bind('preview-ready', function () {
		// Phone (header pill and emergency banner "Call now" link)
		wp.customize('ccs_phone', function (value) {
			value.bind(function (to) {
				var tel = (to || '').replace(/\s+/g, '');
				var display = to || '';
				document.querySelectorAll('.header-actions__phone').forEach(function (el) {
					el.textContent = display;
					el.href = tel ? 'tel:' + tel : '#';
					el.style.display = display ? '' : 'none';
				});
				document.querySelectorAll('.emergency-banner__phone').forEach(function (el) {
					el.href = tel ? 'tel:' + tel : '#';
					el.style.display = tel ? '' : 'none';
				});
			});
		});

		/*
		 * Office hours no longer render in the header (see header.php's "Top
		 * utility bar removed" note) — they're on the contact-page template
		 * (.contact-info__hours-text) and single-service sidebar
		 * (.service-sidebar__hours) instead, neither of which is present while
		 * previewing most pages. No single selector covers both, so there's no
		 * live-preview binding for ccs_office_hours; the saved value still
		 * applies correctly wherever it's actually printed.
		 */

		// Emergency banner: enabled
		wp.customize('ccs_emergency_banner_enabled', function (value) {
			value.bind(function (to) {
				var bar = document.querySelector('.emergency-banner');
				if (!bar) return;
				bar.style.display = to ? '' : 'none';
			});
		});

		function setEmergencyBannerContent() {
			var container = document.querySelector('.emergency-banner__text');
			if (!container) return;
			var text = wp.customize('ccs_emergency_banner').get() || '';
			var link = wp.customize('ccs_emergency_banner_link').get() || '';
			var esc = function (s) {
				var div = document.createElement('div');
				div.textContent = s;
				return div.innerHTML;
			};
			container.innerHTML = link ? '<a href="' + esc(link) + '">' + esc(text) + '</a>' : text;
		}

		// Emergency banner: text
		wp.customize('ccs_emergency_banner', function (value) {
			value.bind(setEmergencyBannerContent);
		});

		// Emergency banner: link (optional)
		wp.customize('ccs_emergency_banner_link', function (value) {
			value.bind(setEmergencyBannerContent);
		});

		// Contact email (if any element uses it in the preview)
		wp.customize('ccs_contact_email', function (value) {
			value.bind(function (to) {
				document.querySelectorAll('[data-ccs-preview="contact_email"]').forEach(function (el) {
					el.textContent = to || '';
					if (el.tagName === 'A') el.href = to ? 'mailto:' + to : '#';
				});
			});
		});
	});
})();
