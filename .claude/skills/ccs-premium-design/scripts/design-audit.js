/*
 * CCS design audit: accessibility basics plus the design-rule metrics from
 * CLAUDE.md. Defines window.ccsDesignAudit(doc, win) → plain object.
 *
 * Normally run through run-audit.sh (headless, any width, many pages). It can
 * also be pasted into the browser pane's javascript tool, then called as
 * ccsDesignAudit(document, window).
 *
 * Every metric is a prompt for judgement, not a verdict: see
 * references/review-checklist.md for what each one means.
 */
(function () {
	'use strict';

	function rgb(s) {
		var m = String(s).match(/[\d.]+/g);
		return m ? m.map(Number) : [0, 0, 0, 0];
	}
	function alpha(c) { return c.length > 3 ? c[3] : 1; }
	function lum(c) {
		return [c[0], c[1], c[2]].map(function (v) {
			v /= 255;
			return v <= 0.04045 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
		}).reduce(function (a, v, i) { return a + v * [0.2126, 0.7152, 0.0722][i]; }, 0);
	}
	function saturation(c) {
		var r = c[0] / 255, g = c[1] / 255, b = c[2] / 255;
		var max = Math.max(r, g, b), min = Math.min(r, g, b), l = (max + min) / 2;
		if (max === min) return 0;
		var d = max - min;
		return l > 0.5 ? d / (2 - max - min) : d / (max + min);
	}
	function hex(c) {
		return '#' + [c[0], c[1], c[2]].map(function (v) { return ('0' + Math.round(v).toString(16)).slice(-2); }).join('');
	}
	function label(el) {
		var c = (typeof el.className === 'string' ? el.className : '').trim().split(/\s+/)[0];
		return el.tagName.toLowerCase() + (el.id ? '#' + el.id : '') + (c ? '.' + c : '');
	}
	function text(el, n) { return (el.textContent || '').replace(/\s+/g, ' ').trim().slice(0, n || 40); }

	window.ccsDesignAudit = function (doc, win) {
		// Jump every running transition/animation to its end state, so async
		// stylesheets fading in can't produce half-faded false contrast fails.
		if (doc.getAnimations) {
			doc.getAnimations().forEach(function (a) { try { a.finish(); } catch (e) {} });
		}
		var de = doc.documentElement, main = doc.querySelector('main') || doc.body, R = {};
		function cs(el) { return win.getComputedStyle(el); }
		function visible(el) {
			var r = el.getBoundingClientRect(), s = cs(el);
			return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none' && !el.closest('[hidden],.screen-reader-text');
		}
		function bgOf(el) {
			var stack = [];
			for (var e = el; e; e = e.parentElement) {
				var b = rgb(cs(e).backgroundColor);
				if (alpha(b) > 0) { stack.push(b); if (alpha(b) >= 1) break; }
			}
			var base = [255, 255, 255];
			for (var i = stack.length - 1; i >= 0; i--) {
				var a = alpha(stack[i]);
				base = base.map(function (v, j) { return stack[i][j] * a + v * (1 - a); });
			}
			return base;
		}

		// ---- Accessibility basics -------------------------------------------
		R.overflow = de.scrollWidth - de.clientWidth;
		R.h1 = doc.querySelectorAll('h1').length;
		var hs = Array.prototype.slice.call(doc.querySelectorAll('h1,h2,h3,h4,h5,h6'));
		R.skips = [];
		for (var i = 1; i < hs.length; i++) {
			var a = +hs[i - 1].tagName[1], b = +hs[i].tagName[1];
			if (b > a + 1) R.skips.push('h' + a + '→h' + b + ' "' + text(hs[i], 30) + '"');
		}
		R.smallTargets = Array.prototype.slice.call(doc.querySelectorAll('a,button,summary,input,select,textarea')).filter(function (e) {
			if (!visible(e) || e.classList.contains('ccs-honeypot')) return false;
			var hit = (e.matches('input,select,textarea') && e.closest('label')) || e; // tapped through its label
			var r = hit.getBoundingClientRect(), s = cs(e);
			var inline = s.display === 'inline' && e.parentElement && e.parentElement.textContent.trim().length > e.textContent.trim().length + 3;
			return (r.height < 24 || r.width < 24) && !inline;
		}).map(function (e) { return (text(e, 24) || e.getAttribute('aria-label') || label(e)) + '@' + Math.round(e.getBoundingClientRect().height) + 'px'; });
		R.contrast = [];
		Array.prototype.forEach.call(doc.querySelectorAll('main *, footer *'), function (el) {
			if (!Array.prototype.some.call(el.childNodes, function (n) { return n.nodeType === 3 && n.textContent.trim(); })) return;
			if (!visible(el) || el.closest('.page-hero--photo,.home-hero,.cta-card__media,.cqc-widget')) return;
			var s = cs(el), fg = rgb(s.color), bg = bgOf(el), a = alpha(fg);
			var f = [0, 1, 2].map(function (j) { return fg[j] * a + bg[j] * (1 - a); });
			var L1 = lum(f), L2 = lum(bg), ratio = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
			var size = parseFloat(s.fontSize), bold = parseInt(s.fontWeight, 10) >= 700;
			var need = (size >= 24 || (bold && size >= 18.66)) ? 3 : 4.5;
			if (ratio < need) R.contrast.push(label(el) + ' "' + text(el, 20) + '" ' + ratio.toFixed(2) + ':1');
		});

		// ---- Rule 12: labels and the mint underline -------------------------
		R.eyebrows = Array.prototype.slice.call(main.querySelectorAll('.ccs-eyebrow,.page-hero__eyebrow')).filter(visible).map(function (e) { return text(e, 40); });
		R.accents = Array.prototype.slice.call(main.querySelectorAll('.ccs-accent')).filter(visible).map(function (e) { return text(e, 30); });

		// ---- Rule 1: boxes and box-in-box ---------------------------------------
		function isCard(el) {
			if (/^(IMG|BUTTON|INPUT|SELECT|TEXTAREA|IFRAME|SVG|PICTURE|VIDEO|SUMMARY)$/.test(el.tagName)) return false;
			if (el.classList.contains('btn') || el.closest('.cqc-widget')) return false;
			// A rounded photo is an image, not a container.
			if (el.querySelector('img,video,iframe') && !(el.textContent || '').trim()) return false;
			var s = cs(el), r = el.getBoundingClientRect();
			if (r.width < 150 || r.height < 90 || parseFloat(s.borderTopLeftRadius) < 12) return false;
			var shadow = s.boxShadow !== 'none';
			var border = parseFloat(s.borderTopWidth) > 0 && alpha(rgb(s.borderTopColor)) > 0.05;
			var own = rgb(s.backgroundColor);
			var differs = alpha(own) > 0.5 && el.parentElement && hex(bgOf(el.parentElement)) !== hex(bgOf(el));
			return shadow || border || differs;
		}
		var cards = Array.prototype.slice.call(main.querySelectorAll('*')).filter(function (e) { return visible(e) && isCard(e); });
		R.cards = cards.length;
		R.maxCardDepth = 0;
		R.nestedCards = [];
		cards.forEach(function (c) {
			var d = 1;
			for (var p = c.parentElement; p && p !== main; p = p.parentElement) { if (cards.indexOf(p) !== -1) d++; }
			if (d > R.maxCardDepth) R.maxCardDepth = d;
			if (d >= 2) R.nestedCards.push(label(c) + ' (depth ' + d + ')');
		});
		R.nestedCards = R.nestedCards.slice(0, 8);
		// Boxes with nothing clickable inside: rule 1's prime suspects. Listed
		// once per component class, with how many there are.
		var statics = {};
		cards.forEach(function (c) {
			if (c.closest('[data-ccs-exception]')) return; // declared in markup, with its reason
			if (c.matches('a,button,form') || c.querySelector('a,button,input,select,textarea,summary,iframe,.cqc-widget')) return;
			var k = label(c); statics[k] = (statics[k] || 0) + 1;
		});
		R.staticBoxes = Object.keys(statics).map(function (k) { return k + ' ×' + statics[k]; });

		// ---- Rule 9: centred multi-line text ---------------------------------------
		R.centredParagraphs = Array.prototype.slice.call(main.querySelectorAll('p,.ccs-lede,li,dd')).filter(function (e) {
			if (!visible(e) || e.closest('[data-ccs-exception]')) return false; // declared exceptions are listed, not flagged
			var s = cs(e), lh = parseFloat(s.lineHeight) || parseFloat(s.fontSize) * 1.5;
			return s.textAlign === 'center' && e.getBoundingClientRect().height > lh * 2.3;
		}).map(function (e) { return label(e) + ' "' + text(e, 30) + '" (' + Math.round(e.getBoundingClientRect().height / (parseFloat(cs(e).lineHeight) || 24)) + ' lines)'; });

		// ---- Rules 2, 3, 4: accent spend ------------------------------------------
		var bars = {};
		Array.prototype.forEach.call(main.querySelectorAll('*'), function (e) {
			var s = cs(e);
			// A side bar is a left edge heavier than the other edges, not an
			// all-round border (buttons, inputs) that happens to include the left.
			var lw = parseFloat(s.borderLeftWidth);
			if (lw >= 2 && s.borderLeftStyle !== 'none' && parseFloat(s.borderTopWidth) < lw && parseFloat(s.borderRightWidth) < lw && visible(e)) {
				var c = rgb(s.borderLeftColor);
				if (alpha(c) > 0.3 && saturation(c) > 0.15) { var h = hex(c); (bars[h] = bars[h] || []).push(label(e)); }
			}
		});
		R.sideBarColours = Object.keys(bars).map(function (h) { return h + ' ×' + bars[h].length + ' (' + bars[h][0] + ')'; });
		R.dots = Array.prototype.slice.call(main.querySelectorAll('*')).filter(function (e) {
			if (e.closest('.cqc-widget,[data-ccs-exception]') || !visible(e)) return false;
			var r = e.getBoundingClientRect(), s = cs(e), c = rgb(s.backgroundColor);
			return r.width >= 4 && r.width <= 14 && Math.abs(r.width - r.height) < 2 && parseFloat(s.borderTopLeftRadius) >= r.width / 2 - 1 && alpha(c) > 0.3 && saturation(c) > 0.2 && !e.textContent.trim();
		}).map(label).slice(0, 8);
		R.gradients = Array.prototype.slice.call(doc.querySelectorAll('body *')).filter(function (e) {
			var bi = cs(e).backgroundImage;
			return visible(e) && /gradient\(/.test(bi) && !/^url\("data:image\/svg/.test(bi);
		}).map(label).slice(0, 8);
		R.glows = Array.prototype.slice.call(main.querySelectorAll('*')).filter(function (e) {
			if (e.closest('.home-hero')) return false; // The hero is frozen by the brief; judge it separately.
			var s = cs(e), m = (s.boxShadow + ' ' + s.textShadow).match(/rgba?\([^)]*\)\s+[-\d.]+px\s+[-\d.]+px\s+(\d+)px/g) || [];
			return m.some(function (x) {
				var c = rgb(x), blur = parseFloat(x.split(/\s+/).slice(-1)[0]);
				// A glow is a bright, saturated, wide blur; dark drop shadows aren't glows.
				return blur >= 24 && saturation(c) > 0.5 && alpha(c) > 0.25 && lum(c) > 0.12;
			});
		}).map(label).slice(0, 8);

		// ---- Rule 6: emoji -------------------------------------------------------------
		var em = (doc.body.innerText || '').match(/[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}\u{2B50}]/gu);
		R.emoji = em ? em.slice(0, 10) : [];

		// ---- Rule 7: one primary per section ------------------------------------------
		R.primaryPerSection = Array.prototype.slice.call(doc.querySelectorAll('main > section, main > header, main section, .site-header')).map(function (s) {
			var n = Array.prototype.slice.call(s.querySelectorAll(':scope .btn-primary, :scope .btn-accent')).filter(function (b) {
				return visible(b) && b.closest('section, header') === s;
			}).length;
			return { s: label(s), n: n };
		}).filter(function (x) { return x.n > 1; }).map(function (x) { return x.s + ' ×' + x.n; });

		// ---- Rule 8: secondary actions dressed as primaries -------------------------
		// The site's primary actions: book, call (the number), send/request a form,
		// apply. Anything else in a filled button is a secondary action at the
		// wrong level.
		// "Search" counts: on a search or 404 page, searching is the primary action.
		var primaryLabel = /book a free consultation|send my request|request a call|^call\b|^\+?\d[\d\s]{6,}$|current vacancies|apply|^search$/i;
		R.filledSecondary = Array.prototype.slice.call(main.querySelectorAll('.btn-primary,.btn-accent')).filter(function (b) {
			// Declared exceptions are listed, not flagged, as everywhere else in this audit.
			return visible(b) && !b.closest('[data-ccs-exception]') && !primaryLabel.test(text(b, 60));
		}).map(function (b) { return '"' + text(b, 40) + '"'; });

		// The same primary action twice in the first screen (e.g. a header Book
		// button directly above the hero's Book button): sections differ, so
		// primaryPerSection can't see it, but the reader sees both at once.
		var firstScreen = win.innerHeight || 900, seen = {};
		Array.prototype.forEach.call(doc.querySelectorAll('.btn-primary,.btn-accent'), function (b) {
			if (!visible(b)) return;
			var r = b.getBoundingClientRect();
			if (r.top > firstScreen || r.bottom < 0) return;
			var t = text(b, 60).toLowerCase(); if (t) seen[t] = (seen[t] || 0) + 1;
		});
		R.repeatedPrimary = Object.keys(seen).filter(function (t) { return seen[t] > 1; }).map(function (t) { return '"' + t + '" ×' + seen[t]; });

		// ---- Rule 9: section spacing ----------------------------------------------------
		var pads = {};
		Array.prototype.forEach.call(main.querySelectorAll('section'), function (sec) {
			if (sec.parentElement && sec.parentElement.closest('section')) return; // top-level sections only
			if (!visible(sec)) return;
			var v = Math.round(parseFloat(cs(sec).paddingTop)) + 'px';
			pads[v] = (pads[v] || 0) + 1;
		});
		R.sectionPaddings = pads;

		// ---- Rule 10: type ---------------------------------------------------------------
		var fams = {};
		Array.prototype.forEach.call(doc.querySelectorAll('body *'), function (e) {
			if (!Array.prototype.some.call(e.childNodes, function (n) { return n.nodeType === 3 && n.textContent.trim(); }) || !visible(e)) return;
			if (e.closest('.cqc-widget')) return;
			fams[cs(e).fontFamily.split(',')[0].replace(/["']/g, '').trim()] = 1;
		});
		R.fontFamilies = Object.keys(fams);

		// ---- Rules 14–22 (UI do-and-don't sources) ----------------------------------
		var controls = Array.prototype.slice.call(doc.querySelectorAll('main a, main button, main summary, main input:not([type=hidden]), main select, main textarea, header a, header button')).filter(function (e) {
			if (!visible(e) || e.classList.contains('ccs-honeypot')) return false;
			var st = cs(e);
			return !(st.display === 'inline' && e.parentElement && e.parentElement.textContent.trim().length > e.textContent.trim().length + 3);
		});
		// 14: under 44px is below Apple's guideline and this audience's target.
		R.under44 = controls.filter(function (e) {
			// The regulator's widget and the frozen hero are judged separately.
			if (e.closest('.cqc-widget,.home-hero')) return false;
			// A stretched link (::after covering its card) is as big as the card.
			var after = win.getComputedStyle(e, '::after');
			if (after.content !== 'none' && after.position === 'absolute') return false;
			// A control inside its label is tapped through the label.
			var hit = (e.matches('input,select,textarea') && e.closest('label')) || e;
			var r = hit.getBoundingClientRect(); return r.height < 44;
		}).map(function (e) { return (text(e, 20) || e.getAttribute('aria-label') || label(e)) + '@' + Math.round(e.getBoundingClientRect().height); }).slice(0, 12);

		// 15: stretched or under-resolved images (assumes a 2x screen for "soft").
		R.distortedImages = []; R.softImages = []; R.retinaSoftImages = [];
		Array.prototype.forEach.call(main.querySelectorAll('img'), function (img) {
			if (!visible(img) || !img.naturalWidth || img.closest('.cqc-widget')) return;
			var r = img.getBoundingClientRect(), st = cs(img);
			var fit = st.objectFit;
			var natural = img.naturalWidth / img.naturalHeight, shown = r.width / r.height;
			if ((fit === 'fill' || fit === '') && Math.abs(natural - shown) / natural > 0.04) R.distortedImages.push(label(img) + ' ' + (img.currentSrc || img.src).split('/').pop().slice(0, 40));
			// The best file the browser could choose on a 2x screen: the largest
			// srcset candidate, not whichever 1x file this headless run picked.
			var best = img.naturalWidth;
			var set = img.getAttribute('srcset');
			if (set) {
				best = Math.max.apply(null, set.split(',').map(function (c) { var m = c.trim().match(/\s(\d+)w$/); return m ? +m[1] : 0; }));
				var nat = img.getAttribute('width') && img.getAttribute('height') ? img.getAttribute('width') / img.getAttribute('height') : natural;
				natural = nat || natural;
			}
			// need1x: pixels to fill the box once; soft means blurred even on an
			// ordinary screen. retinaSoft: sharp at 1x, short of a 2x screen.
			var need1x = fit === 'cover' ? Math.max(r.width, r.height * natural) : r.width;
			// Up to ~10% upscaling is not visible, so "soft" starts below 0.9x.
			if (r.width > 120 && best >= need1x * 0.9 && best < need1x * 1.4) R.retinaSoftImages.push((img.currentSrc || img.src).split('/').pop().slice(0, 40) + ': ' + best + 'px, ' + (best / need1x).toFixed(2) + 'x');
			var need = need1x * 2;
			if (best < need1x * 0.9 && r.width > 120) R.softImages.push((img.currentSrc || img.src).split('/').pop().slice(0, 40) + ': largest ' + best + 'px for a ' + Math.round(r.width) + '×' + Math.round(r.height) + ' box');
		});
		R.softImages = R.softImages.slice(0, 8);

		// 16: an inset child as round as (or rounder than) its rounded parent.
		R.nestedRadius = [];
		Array.prototype.forEach.call(main.querySelectorAll('*'), function (el) {
			var p = el.parentElement; if (!p || !visible(el)) return;
			var pr = parseFloat(cs(p).borderTopLeftRadius), er = parseFloat(cs(el).borderTopLeftRadius);
			if (pr < 8 || er < 8) return;
			var box = el.getBoundingClientRect();
			if (er >= Math.min(box.width, box.height) / 2 - 1) return; // circles and pills aren't nested corners
			var a = el.getBoundingClientRect(), b = p.getBoundingClientRect();
			var inset = a.left - b.left > 4 && a.top - b.top > 4;
			if (inset && er >= pr && R.nestedRadius.length < 6) R.nestedRadius.push(label(el) + ' ' + er + 'px in ' + label(p) + ' ' + pr + 'px');
		});

		// 17: small text, pure black, tight leading, long uppercase.
		var small = {}, black = {}, tight = {}, upper = {};
		Array.prototype.forEach.call(doc.querySelectorAll('main *, footer *'), function (el) {
			if (!Array.prototype.some.call(el.childNodes, function (n) { return n.nodeType === 3 && n.textContent.trim(); }) || !visible(el)) return;
			if (el.closest('.cqc-widget,.screen-reader-text')) return;
			var st = cs(el), fs = parseFloat(st.fontSize), t = text(el, 200);
			if (fs < 13) small[label(el) + ' ' + fs + 'px'] = 1;
			var c = rgb(st.color); if (c[0] + c[1] + c[2] < 15) black[label(el)] = 1;
			if (el.tagName === 'P' && t.length > 120) { var lh = parseFloat(st.lineHeight); if (lh && lh / fs < 1.5) tight[label(el) + ' ' + (lh / fs).toFixed(2)] = 1; }
			if (st.textTransform === 'uppercase' && t.split(/\s+/).length > 3) upper[label(el) + ' "' + text(el, 24) + '"'] = 1;
		});
		R.smallText = Object.keys(small).slice(0, 8);
		R.pureBlack = Object.keys(black).slice(0, 8);
		R.tightLeading = Object.keys(tight).slice(0, 8);
		R.longUppercase = Object.keys(upper).slice(0, 8);

		// 19: vague action labels.
		R.vagueLabels = Array.prototype.slice.call(doc.querySelectorAll('main a, main button, main input[type=submit]')).filter(function (e) {
			if (!visible(e)) return false;
			var t = (e.value || text(e, 40) || '').trim().toLowerCase();
			return /^(submit|ok|okay|click here|here|go|more|link|send)$/.test(t);
		}).map(function (e) { return '"' + (e.value || text(e, 20)) + '"'; });

		// 20: a dropdown with four or fewer real choices would read better as radios.
		R.shortSelects = Array.prototype.slice.call(main.querySelectorAll('select')).filter(function (sel) {
			return visible(sel) && Array.prototype.filter.call(sel.options, function (o) { return o.value !== ''; }).length <= 4;
		}).map(function (sel) { return label(sel) + ' (' + (sel.name || '') + ')'; });

		// 21: autoplay media; links that open a new tab without saying so.
		R.autoplay = Array.prototype.slice.call(doc.querySelectorAll('video[autoplay],audio[autoplay],iframe[src*="autoplay=1"]')).map(label);
		R.newTabUnannounced = Array.prototype.slice.call(doc.querySelectorAll('a[target=_blank]')).filter(function (a) {
			if (!visible(a) || a.closest('.cqc-widget')) return false;
			var said = (a.textContent + ' ' + (a.getAttribute('aria-label') || '') + ' ' + (a.getAttribute('title') || '')).toLowerCase();
			return !/new (window|tab)/.test(said);
		}).map(function (a) { return (text(a, 24) || (a.querySelector('img') && a.querySelector('img').alt) || label(a)) + ' → ' + (a.hostname || ''); }).slice(0, 8);

		// 22: images with no alt attribute at all (alt="" is fine for decoration).
		R.missingAlt = Array.prototype.slice.call(doc.querySelectorAll('img:not([alt])')).filter(visible).map(function (i) { return (i.currentSrc || i.src).split('/').pop().slice(0, 40); });

		// ---- Bands: two sections on the same ground, both padded, read as one long
		// block with a seam of doubled space (checklist: "bands alternate").
		R.sameGroundBands = [];
		// Every top-level block in main (sections, headers, and plain divs such as
		// .service-body), flattening a wrapping <article>, then the footer.
		var bands = [];
		Array.prototype.forEach.call(main.children, function (c) {
			if (c.tagName === 'ARTICLE' || (c.tagName === 'DIV' && !c.className && c.children.length)) {
				Array.prototype.forEach.call(c.children, function (cc) { bands.push(cc); });
			} else { bands.push(c); }
		});
		var foot = doc.querySelector('footer.site-footer, #colophon'); if (foot) bands.push(foot);
		bands = bands.filter(function (b) { return visible(b) && b.getBoundingClientRect().height > 100 && !/^(SCRIPT|STYLE)$/.test(b.tagName); });
		for (var bi = 1; bi < bands.length; bi++) {
			var prev = bands[bi - 1], cur = bands[bi];
			if (hex(bgOf(prev)) === hex(bgOf(cur))) {
				var gap = parseFloat(cs(prev).paddingBottom) + parseFloat(cs(cur).paddingTop);
				if (gap > 160) R.sameGroundBands.push(label(prev) + ' + ' + label(cur) + ' on ' + hex(bgOf(cur)) + ' (' + Math.round(gap) + 'px gap)');
			}
		}

		// ---- 22: every control shows a focus indicator when focused by keyboard.
		R.noFocusStyle = [];
		var sample = controls.filter(function (e) { return !e.closest('.cqc-widget'); }).slice(0, 60);
		var before = doc.activeElement;
		sample.forEach(function (e) {
			var base = cs(e), o0 = base.outlineStyle + base.outlineWidth, b0 = base.boxShadow, c0 = base.borderColor, bg0 = base.backgroundColor, td0 = base.textDecorationLine;
			try { e.focus({ preventScroll: true, focusVisible: true }); } catch (x) { e.focus(); }
			if (doc.activeElement !== e) return;
			var f = cs(e), fo = f.outlineStyle !== 'none' && parseFloat(f.outlineWidth) > 0;
			var changed = fo || f.boxShadow !== b0 || f.borderColor !== c0 || f.backgroundColor !== bg0 || f.textDecorationLine !== td0;
			// Stretched links often draw the ring on their ::after layer.
			['::after', '::before'].forEach(function (pe) {
				var ps = win.getComputedStyle(e, pe);
				if (ps.content !== 'none' && ps.outlineStyle !== 'none' && parseFloat(ps.outlineWidth) > 0) changed = true;
			});
			// Stretched-link cards show focus on the card (:focus-within).
			var card = e.closest('.info-card,.svc-card,.area-card');
			if (!changed && card) { changed = cs(card).outlineStyle !== 'none' || cs(card).boxShadow !== 'none'; }
			if (!changed) R.noFocusStyle.push(text(e, 24) || e.getAttribute('aria-label') || label(e));
		});
		if (before && before.focus) { try { before.focus({ preventScroll: true }); } catch (x) {} }
		R.noFocusStyle = R.noFocusStyle.slice(0, 10);

		// Deliberate exceptions, declared in markup with data-ccs-exception="reason".
		// Reported, not flagged: the reason is right there in the template.
		var ex = {};
		Array.prototype.forEach.call(doc.querySelectorAll('[data-ccs-exception]'), function (e) {
			var k = label(e) + ': ' + e.getAttribute('data-ccs-exception'); ex[k] = (ex[k] || 0) + 1;
		});
		R.declaredExceptions = Object.keys(ex).map(function (k) { return k + (ex[k] > 1 ? ' ×' + ex[k] : ''); });

		return R;
	};
})();
