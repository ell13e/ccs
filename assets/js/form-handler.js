/**
 * Front-end form handler: AJAX submit, validation, loading states, conversion tracking.
 * Expects window.ccsFormHandler: { ajaxUrl, nonceEnquiry, nonceCallback }
 *
 * Forms: data-ccs-action="submit_enquiry" or data-ccs-action="request_callback"
 * Honeypot: input name _company (hidden, leave empty)
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	var config = window.ccsFormHandler || {};
	var ajaxUrl = config.ajaxUrl || '';

	if (!ajaxUrl) return;

	/**
	 * Show message in form message area or as alert.
	 * @param {HTMLFormElement} form
	 * @param {string} message
	 * @param {string} type 'success' | 'error'
	 * @param {HTMLElement|null} firstInvalidField Optional. Field to link via aria-describedby for errors.
	 */
	function showMessage(form, message, type, firstInvalidField) {
		var container = form.querySelector('[data-ccs-form-message]');
		if (container) {
			container.textContent = message;
			container.className = 'ccs-form-message ccs-form-message--' + type;
			container.setAttribute('role', 'alert');
			container.hidden = false;
			if (type === 'error' && firstInvalidField && container.id) {
				firstInvalidField.setAttribute('aria-describedby', container.id);
			}
			container.hidden = message === '';
		} else {
			window.alert(message);
		}
	}

	/**
	 * Set loading state on form.
	 * @param {HTMLFormElement} form
	 * @param {boolean} loading
	 */
	function setLoading(form, loading) {
		var submit = form.querySelector('[type="submit"]');
		if (submit) {
			submit.disabled = loading;
			submit.setAttribute('aria-busy', loading ? 'true' : 'false');
			if (loading) {
				form.classList.add('ccs-form--loading');
			} else {
				form.classList.remove('ccs-form--loading');
			}
		}
	}

	/**
	 * Validate enquiry form (name and phone required; email optional, checked if given).
	 * @param {HTMLFormElement} form
	 * @returns {Array<{field: HTMLElement, message: string}>} Every problem found; empty when valid.
	 */
	function validateEnquiryForm(form) {
		var name = form.querySelector('[name="enquiry_name"]');
		var email = form.querySelector('[name="enquiry_email"]');
		var phone = form.querySelector('[name="enquiry_phone"]');
		var errors = [];
		if (name && !name.value.trim()) errors.push({ field: name, message: 'Please enter your name.' });
		if (phone && !phone.value.trim()) errors.push({ field: phone, message: 'Please enter a phone number so we can call you back.' });
		// Email is optional (Nov 2026): a name and a phone number are enough to
		// call someone back. If one is given, it must look complete.
		if (email) {
			var emailVal = email.value.trim();
			if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
				errors.push({ field: email, message: 'This email address looks incomplete. Check it has an @ and an ending like .co.uk.' });
			}
		}
		return errors;
	}

	/**
	 * Other required questions (Nov 2026): the "How did you hear about us?"
	 * dropdown on every form, and the switching form's current-care questions.
	 * Any select or text input marked required that is empty, and any fieldset
	 * marked data-ccs-required with nothing chosen. The message comes from the
	 * field's data-ccs-error, so each says what to do.
	 * @param {HTMLFormElement} form
	 * @param {Array} errors Appended to.
	 */
	function validateRequiredExtras(form, errors) {
		var handled = { enquiry_name: 1, enquiry_email: 1, enquiry_phone: 1, name: 1, phone: 1 };
		Array.prototype.forEach.call(form.querySelectorAll('select[required], input[required]:not([type="radio"]):not([type="checkbox"])'), function (field) {
			if (handled[field.name] || field.value.trim()) return;
			errors.push({ field: field, message: field.getAttribute('data-ccs-error') || 'Please fill this in.' });
		});
		Array.prototype.forEach.call(form.querySelectorAll('fieldset[data-ccs-required]'), function (set) {
			if (set.querySelector('input:checked')) return;
			var first = set.querySelector('input');
			if (first) errors.push({ field: first, message: set.getAttribute('data-ccs-error') || 'Please choose one.' });
		});
		return errors;
	}

	/**
	 * Validate callback form (name, phone required).
	 * @param {HTMLFormElement} form
	 * @returns {Array<{field: HTMLElement, message: string}>} Every problem found; empty when valid.
	 */
	function validateCallbackForm(form) {
		var name = form.querySelector('[name="name"]');
		var phone = form.querySelector('[name="phone"]');
		var errors = [];
		if (name && !name.value.trim()) errors.push({ field: name, message: 'Please enter your name.' });
		if (phone && !phone.value.trim()) errors.push({ field: phone, message: 'Please enter a phone number so we can call you back.' });
		return errors;
	}

	/*
	 * Inline errors: each message sits directly under its field and says what
	 * to do, the field is marked aria-invalid, and the message is joined to the
	 * field's aria-describedby. It used to be one message at the foot of the
	 * form about the first problem only, far from the field it meant
	 * (CCS/CLAUDE.md rule 20). The summary at the foot stays, for screen
	 * readers and anyone who scrolled past.
	 */
	function fieldErrorId(field) {
		return (field.id || field.name) + '-error';
	}

	function clearFieldError(field) {
		var id = fieldErrorId(field);
		var el = document.getElementById(id);
		if (el) el.parentNode.removeChild(el);
		field.removeAttribute('aria-invalid');
		var described = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(function(x) { return x && x !== id; });
		if (described.length) {
			field.setAttribute('aria-describedby', described.join(' '));
		} else {
			field.removeAttribute('aria-describedby');
		}
	}

	function showFieldError(field, message) {
		clearFieldError(field);
		var id = fieldErrorId(field);
		var el = document.createElement('p');
		el.id = id;
		el.className = 'ccs-field__error';
		el.textContent = message;
		var wrap = field.closest('.ccs-field') || field.parentNode;
		wrap.appendChild(el);
		field.setAttribute('aria-invalid', 'true');
		var described = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean);
		described.push(id);
		field.setAttribute('aria-describedby', described.join(' '));
	}

	/**
	 * The page the person was looking at when they asked to hear from us, so
	 * the office knows what they were reading (client, Nov 2026). Forms on a
	 * page people are sent to (contact, switching) carry
	 * data-ccs-context="referrer" and report the page that sent them; any
	 * other form reports its own page. Only this site's pages count, and
	 * nothing is stored in the browser: the server turns the address into the
	 * page's title (inc/forms.php, ccs_enquiry_booked_from()).
	 * @param {HTMLFormElement} form
	 * @returns {string} A same-site URL, or '' when they came straight to the form.
	 */
	function bookedFromPage(form) {
		var here = window.location.href.split('#')[0];
		if (form.getAttribute('data-ccs-context') !== 'referrer') return here;
		var ref = document.referrer || '';
		try {
			var url = new URL(ref);
			if (url.host !== window.location.host) return '';
			if (url.href.split('#')[0] === here) return '';
			return url.href;
		} catch (e) {
			return '';
		}
	}

	/**
	 * Submit form via AJAX.
	 * @param {HTMLFormElement} form
	 * @param {string} action - 'submit_enquiry' | 'request_callback'
	 */
	function submitForm(form, action) {
		var formData = new FormData(form);
		formData.set('action', action);
		if (action === 'submit_enquiry' && config.nonceEnquiry) {
			formData.set('nonce', config.nonceEnquiry);
		}
		if (action === 'request_callback' && config.nonceCallback) {
			formData.set('nonce', config.nonceCallback);
		}

		var bookedFrom = bookedFromPage(form);
		formData.set('enquiry_booked_from', bookedFrom);
		formData.set('enquiry_form_page', window.location.href.split('#')[0]);

		setLoading(form, true);
		showMessage(form, '', 'success');

		fetch(ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
			.then(function(res) { return res.json(); })
			.then(function(data) {
				setLoading(form, false);
				if (data && data.success) {
					showMessage(form, data.data && data.data.message ? data.data.message : 'Thank you.', 'success');
					form.reset();
					document.dispatchEvent(new CustomEvent('ccs_form_success', { detail: { action: action } }));
				} else {
					showMessage(form, (data && data.data && data.data.message) ? data.data.message : 'Something went wrong. Please try again.', 'error');
				}
			})
			.catch(function() {
				setLoading(form, false);
				showMessage(form, 'Something went wrong. Please try again.', 'error');
			});
	}

	/**
	 * Attach handler to a form.
	 * @param {HTMLFormElement} form
	 */
	function attachForm(form) {
		var action = form.getAttribute('data-ccs-action');
		if (action !== 'submit_enquiry' && action !== 'request_callback') return;

		form.addEventListener('submit', function(e) {
			e.preventDefault();
			Array.prototype.forEach.call(form.querySelectorAll('[aria-invalid="true"]'), clearFieldError);
			var errors = validateRequiredExtras(form, action === 'submit_enquiry' ? validateEnquiryForm(form) : validateCallbackForm(form));
			if (errors.length) {
				errors.forEach(function(err) { showFieldError(err.field, err.message); });
				showMessage(form, errors.length === 1 ? errors[0].message : 'Please check the ' + errors.length + ' fields marked above.', 'error');
				errors[0].field.focus();
				return;
			}
			showMessage(form, '', 'success');
			submitForm(form, action);
		});

		// Choosing an answer clears its error (selects and radio groups fire change, not input).
		form.addEventListener('change', function(e) {
			var t = e.target;
			if (!t || !t.closest) return;
			if (t.type === 'radio') {
				var set = t.closest('fieldset');
				var first = set && set.querySelector('input');
				if (first && first.getAttribute('aria-invalid') === 'true') clearFieldError(first);
			} else if (t.getAttribute('aria-invalid') === 'true' && t.value) {
				clearFieldError(t);
			}
		});

		// A field's error goes as soon as the person fixes it, not on the next submit.
		form.addEventListener('input', function(e) {
			if (e.target.getAttribute && e.target.getAttribute('aria-invalid') === 'true' && e.target.value.trim()) {
				clearFieldError(e.target);
			}
		});
	}

	/**
	 * Init: find all forms and attach.
	 */
	function init() {
		var forms = document.querySelectorAll('form[data-ccs-action]');
		for (var i = 0; i < forms.length; i++) {
			attachForm(forms[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
