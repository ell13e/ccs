/**
 * FAQ accordion: expand/collapse, keyboard, one-open-at-a-time.
 *
 * @package CCS_WP_Theme
 */

(function () {
	'use strict';

	var TOGGLE_SELECTOR = '[data-ccs-faq-toggle]';
	var ITEM_CLASS = 'ccs-faq__item';
	var OPEN_CLASS = 'is-open';

	function run() {
		var blocks = document.querySelectorAll('.ccs-faq');
		blocks.forEach(function (block) {
			initBlock(block);
		});
	}

	function initBlock(block) {
		var toggles = block.querySelectorAll(TOGGLE_SELECTOR);
		toggles.forEach(function (btn) {
			btn.addEventListener('click', function () {
				toggleItem(block, btn);
			});
			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					toggleItem(block, btn);
				}
			});
		});
	}

	function toggleItem(block, button) {
		var item = button.closest('.' + ITEM_CLASS);
		if (!item) return;

		var isOpen = button.getAttribute('aria-expanded') === 'true';
		var answer = item.querySelector('.ccs-faq__answer');
		if (!answer) return;

		if (isOpen) {
			closeItem(button, item, answer);
		} else {
			// Optional: one open at a time — close others in this block
			var others = block.querySelectorAll('.' + ITEM_CLASS);
			others.forEach(function (other) {
				if (other === item) return;
				var otherBtn = other.querySelector(TOGGLE_SELECTOR);
				var otherAnswer = other.querySelector('.ccs-faq__answer');
				if (otherBtn && otherAnswer) closeItem(otherBtn, other, otherAnswer);
			});
			openItem(button, item, answer);
		}
	}

	function openItem(button, item, answer) {
		button.setAttribute('aria-expanded', 'true');
		answer.removeAttribute('hidden');
		item.classList.add(OPEN_CLASS);
	}

	function closeItem(button, item, answer) {
		button.setAttribute('aria-expanded', 'false');
		answer.setAttribute('hidden', '');
		item.classList.remove(OPEN_CLASS);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', run);
	} else {
		run();
	}
})();
