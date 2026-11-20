/**
 * Small enhancements for the inner pages (Nov 2026). Each block does nothing
 * when its markup is absent, and every page is complete without this file:
 * the FAQ list shows every question, and the rate card shows the first kind
 * of care at weekday rates.
 *
 * 1. Filter ([data-ccs-filter]): narrows a list of <details> as you type.
 * 2. Rate finder ([data-ccs-rate-finder]): the figures for the kind of care
 *    and the days chosen.
 * 3. Resources hub ([data-ccs-hub]): search, and one category at a time.
 * 4. A link to one question (#faq-3) or one part of a guide opens it.
 */
( function () {
	'use strict';

	// 1. Filter a list of <details> by what is typed.
	document.querySelectorAll( '[data-ccs-filter]' ).forEach( function ( input ) {
		var list = document.querySelector( input.getAttribute( 'data-ccs-filter' ) );
		if ( ! list ) {
			return;
		}
		var items = Array.prototype.slice.call( list.querySelectorAll( 'details' ) );
		var empty = list.parentNode.querySelector( '[data-ccs-filter-empty]' );

		input.addEventListener( 'input', function () {
			var words = input.value.toLowerCase().split( /\s+/ ).filter( Boolean );
			var shown = 0;
			items.forEach( function ( item ) {
				var text = item.textContent.toLowerCase();
				var match = words.every( function ( word ) {
					return text.indexOf( word ) !== -1;
				} );
				item.hidden = ! match;
				if ( match ) {
					shown++;
				}
			} );
			if ( empty ) {
				empty.hidden = shown > 0;
			}
		} );
	} );

	// 2. Rate finder: the figures for the kind of care and the days chosen.
	document.querySelectorAll( '[data-ccs-rate-finder]' ).forEach( function ( root ) {
		var holder = root.querySelector( '[data-rate-data]' );
		var lines = Array.prototype.slice.call( root.querySelectorAll( '[data-rate-line]' ) );
		var rates;

		try {
			rates = JSON.parse( holder.textContent );
		} catch ( error ) {
			return;
		}
		if ( ! lines.length || ! rates ) {
			return;
		}

		function chosen( name ) {
			return root.querySelector( 'input[name="rate-' + name + '"]:checked' );
		}

		function draw() {
			var care = chosen( 'care' );
			var days = chosen( 'days' );
			if ( ! care || ! days || ! rates[ care.value ] ) {
				return;
			}
			lines.forEach( function ( line ) {
				var rate = rates[ care.value ][ line.getAttribute( 'data-rate-line' ) ];
				if ( rate ) {
					line.textContent = rate[ days.value ] || rate.weekday;
				}
			} );
		}

		root.addEventListener( 'change', draw );
		root.classList.add( 'is-enhanced' );
		draw();
	} );

	// 3. Resources hub: search and one category at a time.
	document.querySelectorAll( '[data-ccs-hub]' ).forEach( function ( root ) {
		var search = root.querySelector( '[data-hub-search]' );
		var items = Array.prototype.slice.call( root.querySelectorAll( '[data-hub-item]' ) );
		var count = root.querySelector( '[data-hub-count]' );
		var empty = root.querySelector( '[data-hub-empty]' );

		function draw() {
			var cat = root.querySelector( 'input[name="hub-cat"]:checked' );
			var want = cat ? cat.value : '';
			var words = search ? search.value.toLowerCase().split( /\s+/ ).filter( Boolean ) : [];
			var shown = 0;
			items.forEach( function ( item ) {
				var text = item.textContent.toLowerCase();
				var match = ( ! want || item.getAttribute( 'data-cat' ) === want ) && words.every( function ( word ) {
					return text.indexOf( word ) !== -1;
				} );
				item.hidden = ! match;
				if ( match ) {
					shown++;
				}
			} );
			if ( count ) {
				count.textContent = shown;
			}
			if ( empty ) {
				empty.hidden = shown > 0;
			}
		}

		root.addEventListener( 'change', draw );
		if ( search ) {
			search.addEventListener( 'input', draw );
		}
		root.classList.add( 'is-enhanced' );

		// ?show=care-guides arrives with that category already chosen.
		var asked = /[?&]show=([^&#]+)/.exec( window.location.search );
		if ( asked ) {
			var radio = root.querySelector( 'input[name="hub-cat"][value="' + decodeURIComponent( asked[ 1 ] ).replace( /[^a-z0-9-]/gi, '' ) + '"]' );
			if ( radio ) {
				radio.checked = true;
				draw();
			}
		}
	} );

	// 4. A link to one question or one part of a guide opens it.
	function openTarget() {
		var id = window.location.hash ? window.location.hash.slice( 1 ) : '';
		var target = id ? document.getElementById( id ) : null;
		if ( ! target ) {
			return;
		}
		if ( 'DETAILS' === target.tagName ) {
			target.open = true;
			target.scrollIntoView( { block: 'center' } );
		} else if ( 'tabpanel' === target.getAttribute( 'role' ) ) {
			var tab = document.getElementById( target.getAttribute( 'aria-labelledby' ) );
			if ( tab ) {
				tab.click();
				tab.closest( '[data-ccs-stepper]' ).scrollIntoView( { block: 'start' } );
			}
		}
	}
	window.addEventListener( 'load', openTarget );
	window.addEventListener( 'hashchange', openTarget );
}() );
