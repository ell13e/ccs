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
		// Phone (header top bar and emergency banner "Call now" link)
		wp.customize('ccs_phone', function (value) {
			value.bind(function (to) {
				var tel = (to || '').replace(/\s+/g, '');
				var display = to || '';
				document.querySelectorAll('.site-header__phone').forEach(function (el) {
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

		// Office hours (header top bar)
		wp.customize('ccs_office_hours', function (value) {
			value.bind(function (to) {
				var el = document.querySelector('.site-header__hours');
				if (el) {
					el.textContent = to || '';
					el.style.display = to ? '' : 'none';
				}
			});
		});

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
