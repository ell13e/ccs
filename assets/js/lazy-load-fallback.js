/**
 * Lazy-load fallback for browsers that do not support native img loading="lazy".
 *
 * When the server outputs lazy images with data-src (and optional data-srcset),
 * this script either restores src for native lazy loading or uses
 * IntersectionObserver to load images when they enter the viewport.
 *
 * For IE11: load an IntersectionObserver polyfill before this script
 * (see class-lazy-load.php for optional polyfill snippet).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function () {
	'use strict';

	var PLACEHOLDER = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

	function loadImage(img) {
		var src = img.getAttribute('data-src');
		if (src) {
			img.src = src;
			img.removeAttribute('data-src');
		}
		var srcset = img.getAttribute('data-srcset');
		if (srcset) {
			img.setAttribute('srcset', srcset);
			img.removeAttribute('data-srcset');
		}
		img.removeAttribute('loading');
	}

	function supportsNativeLazy() {
		return 'loading' in HTMLImageElement.prototype;
	}

	// Images that have data-src were output by the server for lazy loading.
	var lazyImages = document.querySelectorAll('img[data-src]');
	if (!lazyImages.length) {
		return;
	}

	if (supportsNativeLazy()) {
		// Restore src/srcset so the browser can apply native lazy loading.
		lazyImages.forEach(loadImage);
		return;
	}

	// No native support: load when in view via IntersectionObserver.
	if (typeof IntersectionObserver === 'undefined') {
		lazyImages.forEach(loadImage);
		return;
	}

	var observer = new IntersectionObserver(
		function (entries, obs) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}
				loadImage(entry.target);
				obs.unobserve(entry.target);
			});
		},
		{
			rootMargin: '100px 0px',
			threshold: 0.01
		}
	);

	lazyImages.forEach(function (img) {
		observer.observe(img);
	});
})();
