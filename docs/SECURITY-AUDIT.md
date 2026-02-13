# CCS Theme – Security Audit Report

**Audit date:** 2025-02-13  
**Scope:** User input handling, form handlers, error handler, and related backend security (aligned with backend-security-coder practices).

---

## 1. Files Audited

| File | Purpose |
|------|--------|
| `inc/api/class-ccs-form-handlers.php` | AJAX enquiry + callback form handlers |
| `inc/core/class-error-handler.php` | Central error logging, form validation, AJAX error + client error logging |
| `inc/class-contact-form.php` | Consultation form shortcode, AJAX submit, rate limit, nonce, honeypot |
| `page-templates/template-contact.php` | Contact page (output escaping only) |
| `single-service.php` | Service template (output escaping only) |

---

## 2. What Security Measures Already Exist

### 2.1 CSRF protection

- **Form handlers (`class-ccs-form-handlers.php`):**  
  Nonce verified for both `submit_enquiry` and `request_callback` via `wp_verify_nonce( $nonce, $action )`. Rejected with JSON error and user message.
- **Contact form (`class-contact-form.php`):**  
  Nonce verified for consultation submit (`ccs_consultation_nonce` / `NONCE_ACTION`). Rejected with JSON error.
- **Error handler (`class-error-handler.php`):**  
  `ajax_log_client_error` verifies nonce `ccs_log_client_error` before logging; 403 on failure.

### 2.2 Input sanitization and validation

- **Form handlers:**  
  All relevant `$_POST` fields read with `isset()` and sanitized (e.g. `sanitize_text_field`, `sanitize_email`, `sanitize_textarea_field`) after `wp_unslash()`. Enquiry meta uses a fixed map of allowed keys and sanitizer callbacks; non-string values fall back to `sanitize_text_field` (scalar-safe). Required fields (name, email, phone) validated with `is_email()` where needed.
- **Contact form:**  
  All POST inputs sanitized (e.g. `sanitize_text_field`, `sanitize_email`, `wp_kses_post` for message). Select/options validated against allowlists (`consultation_service`, `consultation_with_whom`). Consent required before submit.
- **Error handler:**  
  Client error logging: `message` and `stack` sanitized (`sanitize_text_field`, `sanitize_textarea_field`), `url` with `esc_url_raw`. `log_form_failure` uses sanitized `REMOTE_ADDR` when adding IP to context.

### 2.3 Rate limiting

- **Form handlers:**  
  Per-IP rate limit (transient, 5 submissions per hour) applied before processing enquiry and callback. Rejected with JSON message.
- **Contact form:**  
  Separate per-IP rate limit (transient, 5 per 10 minutes). Rejected with JSON message.

### 2.4 Spam / bot mitigation

- **Form handlers:**  
  Honeypot field `_company`; if non-empty, request is treated as success (no error leak) and not processed.
- **Contact form:**  
  Honeypot `ccs_consultation_company_website`; same pattern.

### 2.5 Error and logging behaviour

- **Error handler:**  
  Logging gated by `WP_DEBUG_LOG` / `CCS_FORCE_ERROR_LOG`. No raw user input in log context; stack/URL sanitized. Generic user message when not in debug. `validate_form` supports required, email, length, regex; used with caller-supplied data.

### 2.6 Output and server variables

- **Templates:**  
  Output escaped (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` where HTML is allowed).  
- **Client IP:**  
  Both form handlers and contact form take first value from `X-Forwarded-For` when present, then `REMOTE_ADDR`, with `sanitize_text_field( wp_unslash( ... ) )`.

---

## 3. What’s Missing or Weak

### 3.1 Security headers

- **Missing:** No theme-level sending of common security headers:
  - `X-Frame-Options` (clickjacking)
  - `X-Content-Type-Options: nosniff` (MIME sniffing)
  - `Referrer-Policy` (referrer leakage)
  - Optional: `Permissions-Policy`, strict `Content-Security-Policy` (if not managed by server/CDN)
- **Recommendation:** Add a single place (e.g. `CCS_Security`) that sends these on front-end responses, unless already set by server/config.

### 3.2 Security-specific logging

- **Gap:** Failed security checks (invalid nonce, rate limit exceeded, honeypot filled) are not explicitly logged as security events. They are handled correctly (reject + user message) but there is no dedicated security event log for monitoring or review.
- **Recommendation:** Optional security logging (e.g. `[CCS Security]` + event type, IP, timestamp) to `error_log` or a dedicated log, behind a constant/filter so it can be enabled only where desired.

### 3.3 Error handler instantiation

- **Gap:** `CCS_Error_Handler` is not instantiated anywhere in the theme. Its methods (e.g. `log_form_failure`, `validate_form`, `send_ajax_error`) are only useful if something creates an instance and calls them.
- **Recommendation:** Either instantiate and inject the error handler where needed, or remove it if unused.

### 3.4 Minor hardening (optional)

- **Form handlers:**  
  Enquiry meta loop: if a POST value is an array, it is currently coerced with `sanitize_text_field( $raw )` (effectively empty). If you ever accept multi-value fields, sanitize each element and store consistently (e.g. array of strings or a single string). Not a current vulnerability if all current fields are scalar.
- **HSTS:**  
  Prefer configuring HSTS at server/CDN level. If the theme sends headers, HSTS can be added behind a filter/constant for environments that use HTTPS everywhere.

---

## 4. What Needs Improvement (prioritised)

1. **Add security headers**  
   Implement theme-level sending of `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` (and optionally `Permissions-Policy`) so they are present if the server does not set them.

2. **Optional security logging**  
   Provide a simple security event log (e.g. failed nonce, rate limit hit, honeypot) that can be turned on via constant or filter, and that does not log sensitive payloads.

3. **Use or remove `CCS_Error_Handler`**  
   Either wire `CCS_Error_Handler` into the theme (e.g. form handlers / contact form call `log_form_failure` on validation failure) or document/remove it to avoid dead code.

4. **Keep existing controls**  
   Keep nonce, rate limiting, honeypot, and sanitization as they are; no need to duplicate rate limiting in a central module.

---

## 5. Summary

| Control | Status | Notes |
|--------|--------|--------|
| CSRF (nonce) | In place | All relevant forms and AJAX actions |
| Input sanitization | In place | Consistent use of WordPress sanitizers and allowlists |
| Rate limiting | In place | Per form/flow in form handlers and contact form |
| Honeypot | In place | Enquiry, callback, consultation |
| Error handling | In place | No sensitive data in user-facing messages |
| Output escaping | In place | Templates use esc_* / wp_kses |
| Security headers | Missing | Add in theme (or ensure server sets them) |
| Security event logging | Missing | Optional, add behind a flag |

The theme already applies solid backend-security practices on input and CSRF. The main additions recommended are: (1) sending security headers from the theme when not set elsewhere, and (2) optional security logging for failed checks. Rate limiting is already implemented per form and should not be duplicated in a central security class.

---

## 6. Implementation follow-up

The following was added after the audit:

- **`inc/class-security.php` (class `CCS_Security`)**  
  - **Security headers:** Sent on `template_redirect` at priority 0 (before cache at 1). Headers: `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `X-XSS-Protection: 1; mode=block`. Filter `ccs_security_headers` allows changing or adding headers.  
  - **Security event logging:** Listens for `do_action( 'ccs_security_event', $event, $context )`. Logs to PHP `error_log` with `[CCS Security]` prefix when enabled via constant `CCS_LOG_SECURITY` or filter `ccs_log_security_events`. Only safe context keys (e.g. `ip`, `action`, `form`) are logged; no raw POST or tokens.  
- **Wiring:** `CCS_Security` is instantiated in `inc/theme-setup.php` via `ccs_register_security()` on `init` priority 10.

Form handlers and contact form can fire security events with:

```php
do_action( 'ccs_security_event', 'nonce_failed', array( 'action' => $action, 'ip' => $ip ) );
do_action( 'ccs_security_event', 'rate_limit_exceeded', array( 'form' => 'enquiry', 'ip' => $ip ) );
do_action( 'ccs_security_event', 'honeypot', array( 'form' => 'callback' ) );
```

Rate limiting was not added to `CCS_Security`; it remains per-form in the existing handlers.
