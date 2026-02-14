/**
 * Site-wide interactions: reduced-motion class, smooth scroll, parallax hero.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * Add reduced-motion class to <html> when user prefers reduced motion.
	 */
	function initReducedMotion() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			document.documentElement.classList.add('reduced-motion');
		}
	}

	/**
	 * Smooth scroll for anchor links with fixed header offset.
	 * Uses 80px offset for header clearance; updates URL and focuses target.
	 */
	function initSmoothScroll() {
		var headerOffset = 80;

		document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
			anchor.addEventListener('click', function(e) {
				var targetId = this.getAttribute('href');
				if (targetId === '#') return;

				var target = document.querySelector(targetId);
				if (target) {
					e.preventDefault();
					var targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;

					window.scrollTo({
						top: targetPosition,
						behavior: 'smooth'
					});

					history.pushState(null, null, targetId);
					target.setAttribute('tabindex', '-1');
					target.focus({ preventScroll: true });
				}
			});
		});
	}

	/**
	 * Parallax effect on hero background (desktop only, respects reduced-motion).
	 */
	function initParallaxHero() {
		var hero = document.querySelector('.hero-homepage');
		if (!hero) return;
		if (window.matchMedia('(max-width: 768px)').matches) return;
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

		var ticking = false;

		function updateParallax() {
			var scrollY = window.scrollY || window.pageYOffset;
			var heroHeight = hero.offsetHeight;

			if (scrollY < heroHeight) {
				hero.style.setProperty('--scroll', scrollY);
			}
			ticking = false;
		}

		window.addEventListener('scroll', function() {
			if (!ticking) {
				window.requestAnimationFrame(updateParallax);
				ticking = true;
			}
		});

		updateParallax();
	}

	/**
	 * Run all init functions on DOM ready.
	 */
	function init() {
		initReducedMotion();
		initSmoothScroll();
		initParallaxHero();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
