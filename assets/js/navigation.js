/**
 * Mobile navigation: menu toggle, accordion submenus, ESC close, body scroll lock.
 * Breakpoint: 1024px (mobile below, desktop 1024+).
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	var MOBILE_BREAKPOINT = 1024;
	var NAV_OPEN_CLASS = 'nav-open';
	var NAV_OPEN_CLASS_NAV = 'is-open';
	var SUBMENU_EXPANDED_CLASS = 'is-expanded';

	/* CTA-style: #mobile-menu-button toggles #mobile-navigation; else legacy .site-header__toggle + #site-navigation */
	var toggle = document.querySelector('#mobile-menu-button') || document.querySelector('.site-header__toggle');
	var mobilePanel = document.querySelector('#mobile-navigation');
	var nav = mobilePanel || document.querySelector('#site-navigation');
	var menu = document.querySelector('#primary-menu') || document.querySelector('#mobile-menu-list');

	/* Labels (can be overridden via wp_localize_script) */
	var labels = window.ccsNavigation || {};
	var openLabel = labels.openMenu || 'Open menu';
	var closeLabel = labels.closeMenu || 'Close menu';
	var expandLabel = labels.expandSubmenu || 'Expand submenu';
	var collapseLabel = labels.collapseSubmenu || 'Collapse submenu';

	function isMobile() {
		return window.innerWidth < MOBILE_BREAKPOINT;
	}

	var isCtaStyle = !!mobilePanel;

	function setMenuOpen(open) {
		if (!toggle || !nav) return;
		toggle.setAttribute('aria-expanded', open);
		toggle.setAttribute('aria-label', open ? closeLabel : openLabel);
		if (isCtaStyle) {
			if (open) {
				nav.removeAttribute('hidden');
			} else {
				nav.setAttribute('hidden', '');
			}
			nav.classList.toggle(NAV_OPEN_CLASS_NAV, open);
			document.body.classList.toggle('ccs-mobile-nav-open', open);
			document.body.style.overflow = open ? 'hidden' : '';
		} else {
			nav.classList.toggle(NAV_OPEN_CLASS_NAV, open);
			document.documentElement.classList.toggle(NAV_OPEN_CLASS, open);
			document.body.style.overflow = open ? 'hidden' : '';
		}
	}

	function closeMenu() {
		setMenuOpen(false);
	}

	function openMenu() {
		setMenuOpen(true);
	}

	function toggleMenu() {
		var open = toggle.getAttribute('aria-expanded') === 'true';
		setMenuOpen(!open);
	}

	/* ESC to close */
	function onKeyDown(e) {
		if (e.key !== 'Escape') return;
		if (nav && nav.classList.contains(NAV_OPEN_CLASS_NAV)) {
			closeMenu();
			toggle.focus();
		} else {
			/* Close any expanded submenu on mobile */
			var expanded = menu ? menu.querySelector('.menu-item.' + SUBMENU_EXPANDED_CLASS) : null;
			if (expanded && isMobile()) {
				var btn = expanded.querySelector('.nav-sub-toggle');
				if (btn) {
					expanded.classList.remove(SUBMENU_EXPANDED_CLASS);
					btn.setAttribute('aria-expanded', 'false');
					btn.setAttribute('aria-label', expandLabel);
				}
			}
		}
	}

	/* Toggle button click */
	if (toggle && nav) {
		toggle.addEventListener('click', function() {
			toggleMenu();
		});
	}

	document.addEventListener('keydown', onKeyDown);

	/* Click overlay to close */
	document.addEventListener('click', function(e) {
		if (!nav || !nav.classList.contains(NAV_OPEN_CLASS_NAV) || !isMobile()) return;
		if (nav.contains(e.target) || (toggle && toggle.contains(e.target))) return;
		closeMenu();
	});

	/* Body scroll lock: ensure overflow reset if viewport resizes to desktop while open */
	window.addEventListener('resize', function() {
		if (!isMobile() && nav && nav.classList.contains(NAV_OPEN_CLASS_NAV)) {
			closeMenu();
		}
		if (isMobile()) return;
		document.body.style.overflow = '';
	});

	/* Submenu accordion: inject toggle buttons and bind */
	if (menu) {
		var parents = menu.querySelectorAll('.menu-item-has-children');
		var submenuIndex = 0;

		parents.forEach(function(item) {
			var link = item.querySelector(':scope > a');
			var sub = item.querySelector(':scope > .sub-menu');
			if (!link || !sub) return;

			var id = sub.id;
			if (!id) {
				id = 'submenu-' + (submenuIndex++);
				sub.id = id;
			}

			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'nav-sub-toggle';
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-controls', id);
			btn.setAttribute('aria-label', expandLabel);
			btn.innerHTML = '<span class="nav-sub-toggle__icon" aria-hidden="true"></span>';

			link.parentNode.insertBefore(btn, link.nextSibling);

			btn.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				if (!isMobile()) return;
				var expanded = item.classList.toggle(SUBMENU_EXPANDED_CLASS);
				btn.setAttribute('aria-expanded', expanded);
				btn.setAttribute('aria-label', expanded ? collapseLabel : expandLabel);
			});

			/* Keyboard: Enter/Space on link - on mobile, prevent default and expand if has children */
			link.addEventListener('click', function(e) {
				if (!isMobile() || !item.classList.contains('menu-item-has-children')) return;
				if (link.getAttribute('href') === '#' || link.getAttribute('href') === '') {
					e.preventDefault();
					item.classList.toggle(SUBMENU_EXPANDED_CLASS);
					var exp = item.classList.contains(SUBMENU_EXPANDED_CLASS);
					btn.setAttribute('aria-expanded', exp);
					btn.setAttribute('aria-label', exp ? collapseLabel : expandLabel);
				}
			});
		});
	}
})();
