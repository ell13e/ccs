/**
 * Small enhancements for the inner pages (Nov 2026). Each block does nothing
 * when its markup is absent, and every page is complete without this file:
 * the FAQ list shows every question, and the rate card shows the first kind
 * of care at weekday rates.
 *
 * 1. Filter ([data-ccs-filter]): narrows a list of <details> as you type,
 *    and a second list of topics with their own questions.
 * 2. Rate finder ([data-ccs-rate-finder]): the figures for the kind of care
 *    and the days chosen.
 * 3. Resources hub ([data-ccs-hub]): search, and one category at a time.
 * 4. A link to one question (#faq-3) or one part of a guide opens it.
 */
( function () {
	'use strict';

	// 1. Filter a list of <details> by what is typed. A second list of
	//    topics, each holding its own questions ([data-ccs-filter-groups]),
	//    is searched too: topics with a match open, the rest are hidden, and
	//    clearing the field puts everything back as it was.
	document.querySelectorAll( '[data-ccs-filter]' ).forEach( function ( input ) {
		var list = document.querySelector( input.getAttribute( 'data-ccs-filter' ) );
		if ( ! list ) {
			return;
		}
		var items = Array.prototype.slice.call( list.querySelectorAll( 'details' ) );
		var empty = list.parentNode.querySelector( '[data-ccs-filter-empty]' );
		var moreSel = input.getAttribute( 'data-ccs-filter-groups' );
		var more = moreSel ? document.querySelector( moreSel ) : null;
		var groups = more ? Array.prototype.slice.call( more.children ).filter( function ( el ) {
			return 'DETAILS' === el.tagName;
		} ) : [];
		var moreHead = more && more.parentNode ? more.parentNode.querySelector( '.faq-more__head' ) : null;

		function matches( el, words ) {
			var text = el.textContent.toLowerCase();
			return words.every( function ( word ) {
				return text.indexOf( word ) !== -1;
			} );
		}

		input.addEventListener( 'input', function () {
			var words = input.value.toLowerCase().split( /\s+/ ).filter( Boolean );
			var shown = 0;
			var shownMore = 0;
			items.forEach( function ( item ) {
				var match = matches( item, words );
				item.hidden = ! match;
				if ( match ) {
					shown++;
				}
			} );
			groups.forEach( function ( group ) {
				var found = 0;
				Array.prototype.forEach.call( group.querySelectorAll( 'details' ), function ( q ) {
					var match = matches( q, words );
					q.hidden = ! match;
					if ( match ) {
						found++;
					}
				} );
				group.hidden = 0 === found;
				group.open = words.length > 0 && found > 0;
				shownMore += found;
			} );
			if ( moreHead ) {
				moreHead.hidden = groups.length > 0 && 0 === shownMore;
			}
			if ( list ) {
				list.hidden = 0 === shown && shownMore > 0;
			}
			if ( empty ) {
				empty.hidden = shown + shownMore > 0;
			}
		} );
	} );

	// 1b. FAQ topics ([data-ccs-faq-browse]): a rail of topics beside one
	//     topic's questions. Choosing a topic (rail link, the "Topic" menu on
	//     a phone, or a link to #topic or #question) shows it; typing in the
	//     search shows every matching question in every topic instead.
	document.querySelectorAll( '[data-ccs-faq-browse]' ).forEach( function ( root ) {
		var panels = Array.prototype.slice.call( root.querySelectorAll( '.faq-panel' ) );
		var links = Array.prototype.slice.call( root.querySelectorAll( '.faq-rail__link' ) );
		var pick = root.querySelector( '[data-ccs-faq-pick]' );
		var scope = root.parentNode;
		var search = scope.querySelector( '[data-ccs-faq-search]' );
		var empty = scope.querySelector( '[data-ccs-faq-empty]' );
		if ( ! panels.length ) {
			return;
		}
		root.classList.add( 'is-enhanced' );

		function show( id ) {
			var found = false;
			panels.forEach( function ( panel ) {
				var on = panel.id === id;
				panel.classList.toggle( 'is-current', on );
				found = found || on;
			} );
			if ( ! found ) {
				panels[ 0 ].classList.add( 'is-current' );
				id = panels[ 0 ].id;
			}
			links.forEach( function ( link ) {
				if ( link.getAttribute( 'href' ) === '#' + id ) {
					link.setAttribute( 'aria-current', 'true' );
				} else {
					link.removeAttribute( 'aria-current' );
				}
			} );
			if ( pick ) {
				pick.value = id;
			}
		}

		function clearSearch() {
			if ( search && search.value ) {
				search.value = '';
				filter();
			}
		}

		function filter() {
			var words = search ? search.value.toLowerCase().split( /\s+/ ).filter( Boolean ) : [];
			var total = 0;
			root.classList.toggle( 'is-searching', words.length > 0 );
			panels.forEach( function ( panel ) {
				var found = 0;
				Array.prototype.forEach.call( panel.querySelectorAll( 'details' ), function ( q ) {
					var text = q.textContent.toLowerCase();
					var match = words.every( function ( word ) {
						return text.indexOf( word ) !== -1;
					} );
					q.hidden = ! match;
					if ( match ) {
						found++;
					}
				} );
				panel.hidden = words.length > 0 && 0 === found;
				total += found;
			} );
			if ( empty ) {
				empty.hidden = total > 0;
			}
		}

		// A link to a topic, or to a question inside one.
		function fromHash( scroll ) {
			var id = decodeURIComponent( window.location.hash.slice( 1 ) );
			var target = id ? document.getElementById( id ) : null;
			var panel = target && target.closest ? target.closest( '.faq-panel' ) : null;
			if ( ! panel || ! root.contains( panel ) ) {
				return;
			}
			clearSearch();
			show( panel.id );
			if ( target !== panel && 'DETAILS' === target.tagName ) {
				target.open = true;
			}
			if ( scroll ) {
				target.scrollIntoView();
			}
		}

		links.forEach( function ( link ) {
			link.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				clearSearch();
				show( link.getAttribute( 'href' ).slice( 1 ) );
				if ( window.history && window.history.replaceState ) {
					window.history.replaceState( null, '', link.getAttribute( 'href' ) );
				}
			} );
		} );
		if ( pick ) {
			pick.addEventListener( 'change', function () {
				clearSearch();
				show( pick.value );
			} );
		}
		if ( search ) {
			search.addEventListener( 'input', filter );
		}
		window.addEventListener( 'hashchange', function () {
			fromHash( true );
		} );
		fromHash( true );
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
