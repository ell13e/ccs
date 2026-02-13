/**
 * Consultation form: AJAX submit, client-side validation, message display.
 * Expects window.ccsConsultationForm: { ajaxUrl, nonce }
 * Form: data-ccs-action="submit_ccs_consultation_form"
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	var config = window.ccsConsultationForm || {};
	var ajaxUrl = config.ajaxUrl || '';
	var nonce = config.nonce || '';

	if (!ajaxUrl || !nonce) return;

	var form = document.getElementById('ccs-consultation-form');
	if (!form || form.getAttribute('data-ccs-action') !== 'submit_ccs_consultation_form') return;

	var messageEl = form.querySelector('.ccs-form-message');
	if (!messageEl) messageEl = form.querySelector('[id="ccs-consultation-form-message"]');

	/**
	 * Show message in form message area.
	 * @param {string} text
	 * @param {string} type 'success' | 'error'
	 */
	function showMessage(text, type) {
		if (messageEl) {
			messageEl.textContent = text;
			messageEl.className = 'ccs-form-message ccs-form-message--' + type;
			messageEl.setAttribute('role', 'alert');
			messageEl.hidden = false;
		} else {
			window.alert(text);
		}
	}

	/**
	 * Set loading state on submit button.
	 * @param {boolean} loading
	 */
	function setLoading(loading) {
		var submit = form.querySelector('button[type="submit"]');
		if (submit) {
			submit.disabled = loading;
			submit.setAttribute('aria-busy', loading ? 'true' : 'false');
		}
	}

	/**
	 * Client-side validation: name, phone, email, consent required.
	 * @returns {{ valid: boolean, message: string }}
	 */
	function validate() {
		var name = form.querySelector('[name="consultation_name"]');
		var phone = form.querySelector('[name="consultation_phone"]');
		var email = form.querySelector('[name="consultation_email"]');
		var consent = form.querySelector('[name="consultation_consent"]');
		var nameVal = name ? name.value.trim() : '';
		var phoneVal = phone ? phone.value.trim() : '';
		var emailVal = email ? email.value.trim() : '';
		if (!nameVal) return { valid: false, message: 'Your name is required.' };
		if (!phoneVal) return { valid: false, message: 'Your phone number is required.' };
		if (!emailVal) return { valid: false, message: 'Your email is required.' };
		if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
			return { valid: false, message: 'Please enter a valid email address.' };
		}
		if (!consent || !consent.checked) {
			return { valid: false, message: 'You must consent to us storing your details to submit this form.' };
		}
		return { valid: true, message: '' };
	}

	form.addEventListener('submit', function(e) {
		e.preventDefault();

		var v = validate();
		if (!v.valid) {
			showMessage(v.message, 'error');
			return;
		}

		var data = new FormData(form);
		data.append('action', 'submit_ccs_consultation_form');
		data.append('ccs_consultation_nonce', nonce);

		setLoading(true);
		if (messageEl) messageEl.hidden = true;

		fetch(ajaxUrl, {
			method: 'POST',
			body: data,
			credentials: 'same-origin'
		})
			.then(function(res) { return res.json(); })
			.then(function(json) {
				setLoading(false);
				var msg = (json.data && json.data.message) ? json.data.message : (json.message || 'Thank you. We will be in touch shortly.');
				if (json.success) {
					showMessage(msg, 'success');
					form.reset();
					var newsletter = form.querySelector('[name="consultation_newsletter"]');
					if (newsletter) newsletter.checked = true;
				} else {
					showMessage(msg, 'error');
				}
			})
			.catch(function() {
				setLoading(false);
				showMessage('Something went wrong. Please try again.', 'error');
			});
	});
})();
