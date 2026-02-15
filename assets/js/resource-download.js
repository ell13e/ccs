(function () {
  'use strict';

  let currentResourceName = '';
  let downloadModalPreviousFocus = null;
  let unavailableModalPreviousFocus = null;

  function getCfg() {
    if (!window.ccsResourceDownload || !ccsResourceDownload.ajaxUrl || !ccsResourceDownload.nonce) return null;
    return { ajaxUrl: ccsResourceDownload.ajaxUrl, nonce: ccsResourceDownload.nonce };
  }

  function openModal(resourceId, resourceName) {
    const modal = document.getElementById('resource-download-modal');
    if (!modal) return;

    downloadModalPreviousFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;
    currentResourceName = resourceName || '';

    const idInput = document.getElementById('resource-download-resource-id');
    if (idInput) idInput.value = String(resourceId || '');

    const subtitle = document.getElementById('resource-download-subtitle');
    if (subtitle && resourceName) {
      subtitle.textContent = "We'll email you a secure download link for \"" + resourceName + "\".";
    }

    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    setTimeout(function () {
      const first = document.getElementById('resource-first-name');
      if (first) first.focus();
    }, 50);
  }

  function closeModal() {
    const modal = document.getElementById('resource-download-modal');
    if (!modal) return;

    const canReturnTo = downloadModalPreviousFocus &&
      document.body.contains(downloadModalPreviousFocus) &&
      !downloadModalPreviousFocus.closest('[aria-hidden="true"]');
    const returnTo = canReturnTo ? downloadModalPreviousFocus : document.body;
    returnTo.focus();
    downloadModalPreviousFocus = null;

    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function openUnavailableModal(resourceName) {
    const modal = document.getElementById('resource-unavailable-modal');
    if (!modal) return;

    unavailableModalPreviousFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;

    const subtitle = document.getElementById('resource-unavailable-subtitle');
    if (subtitle) {
      subtitle.textContent = resourceName
        ? "\"" + resourceName + "\" is not available for download right now. Please try again later or contact us if you need help."
        : "This care guide is not available for download right now. Please try again later or contact us if you need help.";
    }

    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    setTimeout(function () {
      const closeBtn = modal.querySelector('[data-resource-unavailable-close]');
      if (closeBtn) closeBtn.focus();
    }, 50);
  }

  function closeUnavailableModal() {
    const modal = document.getElementById('resource-unavailable-modal');
    if (!modal) return;

    const canReturnTo = unavailableModalPreviousFocus &&
      document.body.contains(unavailableModalPreviousFocus) &&
      !unavailableModalPreviousFocus.closest('[aria-hidden="true"]');
    const returnTo = canReturnTo ? unavailableModalPreviousFocus : document.body;
    returnTo.focus();
    unavailableModalPreviousFocus = null;

    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function showFieldError(fieldId, errorId, msg) {
    const field = document.getElementById(fieldId);
    const err = document.getElementById(errorId);
    if (field) field.setAttribute('aria-invalid', 'true');
    if (err) {
      err.textContent = msg;
      err.style.display = 'block';
    }
  }

  function clearFieldError(fieldId, errorId) {
    const field = document.getElementById(fieldId);
    const err = document.getElementById(errorId);
    if (field) field.setAttribute('aria-invalid', 'false');
    if (err) {
      err.textContent = '';
      err.style.display = 'none';
    }
  }

  function clearAllErrors() {
    clearFieldError('resource-first-name', 'resource-first-name-error');
    clearFieldError('resource-last-name', 'resource-last-name-error');
    clearFieldError('resource-email', 'resource-email-error');
    clearFieldError('resource-phone', 'resource-phone-error');
    clearFieldError('resource-dob', 'resource-dob-error');
    clearFieldError('resource-consent', 'resource-consent-error');
  }

  function submitForm(form) {
    const cfg = getCfg();
    if (!cfg) return;

    clearAllErrors();

    const resourceId = document.getElementById('resource-download-resource-id') && document.getElementById('resource-download-resource-id').value ? document.getElementById('resource-download-resource-id').value : '';
    const firstName = document.getElementById('resource-first-name') && document.getElementById('resource-first-name').value ? document.getElementById('resource-first-name').value.trim() : '';
    const lastName = document.getElementById('resource-last-name') && document.getElementById('resource-last-name').value ? document.getElementById('resource-last-name').value.trim() : '';
    const email = document.getElementById('resource-email') && document.getElementById('resource-email').value ? document.getElementById('resource-email').value.trim() : '';
    const phone = document.getElementById('resource-phone') && document.getElementById('resource-phone').value ? document.getElementById('resource-phone').value.trim() : '';
    const dob = document.getElementById('resource-dob') && document.getElementById('resource-dob').value ? document.getElementById('resource-dob').value : '';
    const consent = document.getElementById('resource-consent') ? document.getElementById('resource-consent').checked : false;

    var hasErrors = false;
    if (!resourceId) hasErrors = true;
    if (!firstName) {
      showFieldError('resource-first-name', 'resource-first-name-error', 'First name is required.');
      hasErrors = true;
    }
    if (!lastName) {
      showFieldError('resource-last-name', 'resource-last-name-error', 'Last name is required.');
      hasErrors = true;
    }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      showFieldError('resource-email', 'resource-email-error', 'Please enter a valid email address.');
      hasErrors = true;
    }
    if (!consent) {
      showFieldError('resource-consent', 'resource-consent-error', 'Consent is required.');
      hasErrors = true;
    }
    if (hasErrors) return;

    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn ? btn.textContent : '';
    if (btn) {
      btn.disabled = true;
      btn.textContent = 'Sending…';
    }

    var fd = new FormData();
    fd.append('action', 'ccs_request_resource_download');
    fd.append('nonce', cfg.nonce);
    fd.append('resource_id', resourceId);
    fd.append('first_name', firstName);
    fd.append('last_name', lastName);
    fd.append('email', email);
    fd.append('phone', phone);
    fd.append('date_of_birth', dob);
    fd.append('consent', consent ? 'true' : 'false');

    fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (res) { return res.json(); })
      .then(function (json) {
        if (btn) {
          btn.disabled = false;
          btn.textContent = originalText || 'Email me the care guide';
        }
        if (json && json.success) {
          closeModal();
          var msg = (json.data && json.data.message) ? json.data.message : 'Thanks! Check your email for the download link.';
          alert(msg);
          form.reset();
          return;
        }
        if (json && json.data && json.data.code === 'resource_unavailable') {
          closeModal();
          openUnavailableModal(currentResourceName || 'This care guide');
          form.reset();
          return;
        }
        var errors = json && json.data && json.data.errors ? json.data.errors : null;
        if (errors) {
          if (errors.first_name) showFieldError('resource-first-name', 'resource-first-name-error', errors.first_name);
          if (errors.last_name) showFieldError('resource-last-name', 'resource-last-name-error', errors.last_name);
          if (errors.email) showFieldError('resource-email', 'resource-email-error', errors.email);
          if (errors.phone) showFieldError('resource-phone', 'resource-phone-error', errors.phone);
          if (errors.date_of_birth) showFieldError('resource-dob', 'resource-dob-error', errors.date_of_birth);
          if (errors.consent) showFieldError('resource-consent', 'resource-consent-error', errors.consent);
        }
      })
      .catch(function () {
        if (btn) {
          btn.disabled = false;
          btn.textContent = originalText || 'Email me the care guide';
        }
      });
  }

  function init() {
    const modal = document.getElementById('resource-download-modal');
    if (!modal) return;

    document.addEventListener('click', function (e) {
      var t = e.target;
      if (!t || !t.closest) return;

      if (t.closest('[data-resource-modal-close]')) {
        e.preventDefault();
        closeModal();
        return;
      }
      if (t.closest('[data-resource-unavailable-close]')) {
        e.preventDefault();
        closeUnavailableModal();
        return;
      }
      var btn = t.closest('.resource-download-btn');
      if (btn) {
        e.preventDefault();
        var rid = btn.getAttribute('data-resource-id');
        var name = btn.getAttribute('data-resource-name') || '';
        openModal(rid, name);
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        var downloadModal = document.getElementById('resource-download-modal');
        var unavailableModal = document.getElementById('resource-unavailable-modal');
        if (downloadModal && downloadModal.getAttribute('aria-hidden') === 'false') closeModal();
        if (unavailableModal && unavailableModal.getAttribute('aria-hidden') === 'false') closeUnavailableModal();
      }
    });

    var form = document.getElementById('resource-download-form');
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        submitForm(form);
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
