/**
 * Small progressive enhancements. Each block is a no-op when its markup is
 * absent, and the page is complete without this file.
 *
 * 1. Stepper ([data-ccs-stepper]): shows one step at a time, with a rail of
 *    numbered tabs, previous/next buttons and arrow-key support. Without JS
 *    every step is simply listed in order.
 * 2. Marquee ([data-ccs-marquee]): a pause/play button for the moving partner
 *    logos (WCAG 2.2.2: anything that moves for more than five seconds needs
 *    a way to stop it). CSS already pauses on hover and stops it entirely
 *    under prefers-reduced-motion.
 * 3. Journey ([data-ccs-journey]): see the block below.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-ccs-stepper]' ).forEach( function ( root ) {
		var tabs = Array.prototype.slice.call( root.querySelectorAll( '[role="tab"]' ) );
		var panels = Array.prototype.slice.call( root.querySelectorAll( '[role="tabpanel"]' ) );
		var progress = root.querySelector( '.ccs-stepper__progress' );

		if ( ! tabs.length || tabs.length !== panels.length ) {
			return;
		}

		var now = 0;

		function show( index, focus ) {
			index = Math.max( 0, Math.min( tabs.length - 1, index ) );
			now = index;
			tabs.forEach( function ( tab, i ) {
				var on = i === index;
				tab.setAttribute( 'aria-selected', on ? 'true' : 'false' );
				tab.tabIndex = on ? 0 : -1;
				tab.classList.toggle( 'is-done', i < index );
				panels[ i ].hidden = ! on;
			} );
			if ( progress ) {
				progress.style.setProperty( '--ccs-step-progress', tabs.length > 1 ? index / ( tabs.length - 1 ) : 1 );
			}
			if ( focus ) {
				tabs[ index ].focus();
			}
		}

		tabs.forEach( function ( tab, i ) {
			tab.addEventListener( 'click', function () {
				show( i, false );
			} );
			tab.addEventListener( 'keydown', function ( event ) {
				var next = null;
				if ( 'ArrowRight' === event.key || 'ArrowDown' === event.key ) {
					next = ( i + 1 ) % tabs.length;
				} else if ( 'ArrowLeft' === event.key || 'ArrowUp' === event.key ) {
					next = ( i - 1 + tabs.length ) % tabs.length;
				} else if ( 'Home' === event.key ) {
					next = 0;
				} else if ( 'End' === event.key ) {
					next = tabs.length - 1;
				}
				if ( null !== next ) {
					event.preventDefault();
					show( next, true );
				}
			} );
		} );

		root.querySelectorAll( '[data-ccs-step-go]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				show( parseInt( button.getAttribute( 'data-ccs-step-go' ), 10 ), false );
				// Keep the panel's top in view on phones, where the rail scrolls away.
				var top = root.getBoundingClientRect().top;
				if ( top < 0 ) {
					root.scrollIntoView( { block: 'start' } );
				}
			} );
		} );

		// Previous / next that go round (the comment rotator).
		var prev = root.querySelector( '[data-ccs-step-prev]' );
		var next = root.querySelector( '[data-ccs-step-next]' );
		if ( prev ) {
			prev.addEventListener( 'click', function () {
				show( ( now - 1 + tabs.length ) % tabs.length, false );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				show( ( now + 1 ) % tabs.length, false );
			} );
		}

		root.classList.add( 'is-enhanced' );
		show( 0, false );
	} );

	// 3. Journey ([data-ccs-journey]): as the list is scrolled the line fills,
	//    each milestone's mark fills when the line reaches it, and the large year in
	//    the sticky column shows the milestone being read.
	var journeys = Array.prototype.slice.call( document.querySelectorAll( '[data-ccs-journey]' ) );
	var calm = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	journeys.forEach( function ( root ) {
		var list = root.querySelector( '.journey' );
		var items = Array.prototype.slice.call( root.querySelectorAll( '.journey__item' ) );
		var bigYear = root.querySelector( '[data-ccs-journey-year]' );
		var shown = bigYear ? bigYear.textContent : '';
		var swap = null;

		if ( ! list || ! items.length ) {
			return;
		}

		function draw() {
			var box = list.getBoundingClientRect();
			var line = window.innerHeight * 0.55;
			var current = items[ 0 ];
			list.style.setProperty( '--journey-progress', Math.max( 0, Math.min( 1, ( line - box.top ) / box.height ) ).toFixed( 4 ) );
			items.forEach( function ( item ) {
				var reached = item.getBoundingClientRect().top <= line;
				item.classList.toggle( 'is-in', reached );
				if ( reached ) {
					current = item;
				}
			} );
			items.forEach( function ( item ) {
				item.classList.toggle( 'is-current', item === current );
			} );
			var year = current.getAttribute( 'data-year' );
			if ( bigYear && year && year !== shown ) {
				shown = year;
				bigYear.classList.add( 'is-changing' );
				window.clearTimeout( swap );
				swap = window.setTimeout( function () {
					bigYear.textContent = year;
					bigYear.classList.remove( 'is-changing' );
				}, 180 );
			}
		}

		if ( calm ) {
			return;
		}

		// draw() runs straight from the scroll event, not through
		// requestAnimationFrame: a throttled or background page pauses frames,
		// and milestones must never be left faint because a frame did not come.
		root.classList.add( 'is-enhanced' );
		draw();
		window.addEventListener( 'scroll', draw, { passive: true } );
		window.addEventListener( 'resize', draw );
	} );

	document.querySelectorAll( '[data-ccs-marquee]' ).forEach( function ( root ) {
		var button = root.querySelector( '[data-ccs-marquee-toggle]' );
		if ( ! button ) {
			return;
		}
		button.hidden = false;
		button.addEventListener( 'click', function () {
			var paused = root.classList.toggle( 'is-paused' );
			button.setAttribute( 'aria-pressed', paused ? 'true' : 'false' );
			button.setAttribute( 'aria-label', paused ? button.getAttribute( 'data-play' ) : button.getAttribute( 'data-pause' ) );
		} );
	} );
}() );
