/**
 * Scroll-reveal (Intersection Observer) and count-up for [data-animate] and .count-up[data-target].
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	var reducedMotion = false;

	function init() {
		reducedMotion = document.documentElement.classList.contains('reduced-motion') ||
			window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		initScrollReveal();
		initCountUp();
	}

	/**
	 * Intersection Observer: add .animated when [data-animate] enters view; unobserve after trigger.
	 */
	function initScrollReveal() {
		var elements = document.querySelectorAll('[data-animate]');
		if (!elements.length) return;

		var observer = new IntersectionObserver(
			function(entries) {
				entries.forEach(function(entry) {
					if (!entry.isIntersecting) return;

					var el = entry.target;
					var delay = el.getAttribute('data-delay');
					if (delay !== null && delay !== '') {
						el.style.setProperty('--scroll-animation-delay', delay);
					}

					el.classList.add('animated');
					observer.unobserve(el);
				});
			},
			{
				root: null,
				rootMargin: '0px 0px -10% 0px',
				threshold: 0
			}
		);

		elements.forEach(function(el) {
			if (reducedMotion) {
				el.classList.add('animated');
				return;
			}
			observer.observe(el);
		});
	}

	/**
	 * Count-up: animate .count-up[data-target] from 0 to data-target over data-duration (ms).
	 */
	function initCountUp() {
		var elements = document.querySelectorAll('.count-up[data-target]');
		if (!elements.length) return;

		var observer = new IntersectionObserver(
			function(entries) {
				entries.forEach(function(entry) {
					if (!entry.isIntersecting) return;

					var el = entry.target;
					var target = parseInt(el.getAttribute('data-target'), 10);
					var duration = parseInt(el.getAttribute('data-duration'), 10) || 2000;

					if (reducedMotion) {
						el.textContent = target;
						observer.unobserve(el);
						return;
					}

					animateCountUp(el, target, duration);
					observer.unobserve(el);
				});
			},
			{
				root: null,
				rootMargin: '0px 0px -10% 0px',
				threshold: 0
			}
		);

		elements.forEach(function(el) {
			observer.observe(el);
		});
	}

	/**
	 * Animate a number from 0 to target over duration (ms) using requestAnimationFrame.
	 */
	function animateCountUp(el, target, duration) {
		var start = null;
		var startVal = 0;

		function step(timestamp) {
			if (start === null) start = timestamp;
			var elapsed = timestamp - start;
			var progress = Math.min(elapsed / duration, 1);
			var easeOut = 1 - Math.pow(1 - progress, 3);
			var current = Math.round(startVal + (target - startVal) * easeOut);
			el.textContent = current;

			if (progress < 1) {
				window.requestAnimationFrame(step);
			} else {
				el.textContent = target;
			}
		}

		window.requestAnimationFrame(step);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
