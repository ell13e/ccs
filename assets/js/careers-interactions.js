/**
 * Careers page: sticky Apply button.
 * Show when past hero, hide when #cvm_content is in view; smooth scroll to #cvm_content; 5s pulse.
 * Respects prefers-reduced-motion.
 *
 * @package CCS_WP_Theme
 */

(function () {
	'use strict';

	if ( ! document.body.classList.contains( 'page-template-template-careers' ) ) {
		return;
	}

	var hero = document.querySelector( '.careers-hero' );
	var target = document.getElementById( 'cvm_content' );
	if ( ! hero || ! target ) {
		return;
	}

	var reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	if ( reducedMotion ) {
		return;
	}

	var HEADER_OFFSET = 80;
	var rafId = null;
	var stickyVisible = false;
	var sticky = null;

	function createStickyButton() {
		var btn = document.createElement( 'a' );
		btn.href = '#cvm_content';
		btn.className = 'sticky-cta sticky-cta--careers-apply';
		btn.setAttribute( 'aria-label', 'Scroll to job application section' );
		btn.textContent = 'Apply';
		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			target.setAttribute( 'tabindex', '-1' );
			var top = target.getBoundingClientRect().top + window.pageYOffset - HEADER_OFFSET;
			window.scrollTo( { top: top, behavior: 'smooth' } );
			target.focus( { preventScroll: true } );
			if ( window.history && window.history.pushState ) {
				window.history.pushState( null, '', '#cvm_content' );
			}
		} );
		document.body.appendChild( btn );
		return btn;
	}

	function updateVisibility() {
		var scrollY = window.pageYOffset || document.documentElement.scrollTop;
		var heroBottom = hero.offsetHeight;
		var targetRect = target.getBoundingClientRect();
		var targetInView = targetRect.top < window.innerHeight;

		var shouldShow = scrollY > heroBottom && ! targetInView;
		if ( shouldShow === stickyVisible ) {
			return;
		}
		stickyVisible = shouldShow;
		sticky.classList.toggle( 'sticky-cta--visible', shouldShow );
	}

	function onScroll() {
		if ( rafId ) {
			return;
		}
		rafId = requestAnimationFrame( function () {
			rafId = null;
			updateVisibility();
		} );
	}

	sticky = createStickyButton();
	updateVisibility();

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', updateVisibility );
})();
