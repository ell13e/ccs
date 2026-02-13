/**
 * CCS Theme Setup welcome page: "You're all set up!" toggle and docs overlay.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function () {
	'use strict';

	var toggle = document.querySelector('.ccs-welcome-all-set-toggle');
	var panel = document.getElementById('ccs-welcome-all-set-list');
	var docsOverlay = document.getElementById('ccs-welcome-docs');

	if (toggle && panel) {
		toggle.addEventListener('click', function () {
			var expanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', !expanded);
			panel.hidden = expanded;
		});
	}

	if (docsOverlay) {
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				var closeLink = docsOverlay.querySelector('a[href*="themes.php"]');
				if (closeLink) {
					closeLink.click();
				}
			}
		});
	}
})();
