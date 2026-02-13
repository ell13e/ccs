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
	 * Validate enquiry form (name, email, phone required).
	 * @param {HTMLFormElement} form
	 * @returns {{ valid: boolean, message: string }}
	 */
	function validateEnquiryForm(form) {
		var name = form.querySelector('[name="enquiry_name"]');
		var email = form.querySelector('[name="enquiry_email"]');
		var phone = form.querySelector('[name="enquiry_phone"]');
		var nameVal = name ? name.value.trim() : '';
		var emailVal = email ? email.value.trim() : '';
		var phoneVal = phone ? phone.value.trim() : '';
		if (!nameVal) return { valid: false, message: 'Please enter your name.', field: name };
		if (!phoneVal) return { valid: false, message: 'Please enter your phone number.', field: phone };
		if (!emailVal) return { valid: false, message: 'Please enter your email address.', field: email };
		if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
			return { valid: false, message: 'Please enter a valid email address.', field: email };
		}
		return { valid: true, message: '' };
	}

	/**
	 * Validate callback form (name, phone required).
	 * @param {HTMLFormElement} form
	 * @returns {{ valid: boolean, message: string }}
	 */
	function validateCallbackForm(form) {
		var name = form.querySelector('[name="name"]');
		var phone = form.querySelector('[name="phone"]');
		var nameVal = name ? name.value.trim() : '';
		var phoneVal = phone ? phone.value.trim() : '';
		if (!nameVal) return { valid: false, message: 'Please enter your name.', field: name };
		if (!phoneVal) return { valid: false, message: 'Please enter your phone number.', field: phone };
		return { valid: true, message: '' };
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
			var validation = action === 'submit_enquiry' ? validateEnquiryForm(form) : validateCallbackForm(form);
			if (!validation.valid) {
				showMessage(form, validation.message, 'error', validation.field || null);
				if (validation.field) {
					validation.field.focus();
				}
				return;
			}
			submitForm(form, action);
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
