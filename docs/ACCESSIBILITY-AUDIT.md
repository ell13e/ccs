# Accessibility Audit Report

**Theme:** CCS WP Theme  
**Date:** 2025-02-13  
**Standards:** WCAG 2.1 Level AA, WebAIM contrast guidelines  
**Scope:** Header, footer, forms, colours, keyboard, heading hierarchy

---

## 1. Header (`header.php`)

| Check | Status | Notes |
|-------|--------|--------|
| Skip to content link at top | ✅ Pass | `<a href="#main" class="skip-link">` present; target `#main` exists on `<main>` in all templates. |
| `<nav>` has `aria-label="Primary navigation"` | ✅ Pass | `aria-label="<?php esc_attr_e( 'Primary navigation', ... ); ?>"` on `#site-navigation`. |
| Menu toggle has `aria-expanded`, `aria-controls` | ✅ Pass | Button has `aria-expanded="false"`, `aria-controls="primary-menu"`. `navigation.js` toggles `aria-expanded` and `aria-label` (Open/Close menu). |
| Logo has descriptive alt text | ✅ Pass | Filter `ccs_custom_logo_alt` in `inc/theme-setup.php` sets logo `alt` to site name when empty. |

---

## 2. Footer (`footer.php`)

| Check | Status | Notes |
|-------|--------|--------|
| `<footer>` has `role="contentinfo"` | ✅ Pass | `role="contentinfo"` on `#colophon`. |
| Social links have screen reader text | ✅ Pass | Footer outputs social links when `ccs_facebook_url`, `ccs_linkedin_url`, `ccs_twitter_url` are set; each link has `aria-label` (e.g. "Facebook (opens in new window)"), `target="_blank"`, `rel="noopener noreferrer"`. |
| Copyright info readable | ✅ Pass | Copyright and site name in `.site-footer__copy`; text colour inherits from design system (sufficient contrast). |

---

## 3. Forms

### 3.1 Contact form (`page-templates/template-contact.php`)

| Check | Status | Notes |
|-------|--------|--------|
| All inputs have `<label>` with `for` matching `id` | ✅ Pass | Every input/select/textarea has an associated label; `for`/`id` match. |
| Required fields have `aria-required="true"` | ✅ Pass | Added to `#contact-name`, `#contact-email`, `#contact-phone`. |
| Error/success message has `role="alert"` | ✅ Pass | `#contact-form-message` has `role="alert"` and `aria-live="polite"`. |
| Form associated with message via `aria-describedby` | ✅ Pass | Form has `aria-describedby="contact-form-message"`. |

### 3.2 Consultation form (`inc/class-contact-form.php`)

| Check | Status | Notes |
|-------|--------|--------|
| Labels and `for`/`id` | ✅ Pass | All fields have correct labels. |
| `aria-required` | ✅ Pass | Added to name, phone, email, consent checkbox. |
| Form message | ✅ Pass | Form has `aria-describedby` and message div has `role="alert"`. |

### 3.3 Service sidebar callback form (`single-service.php`)

| Check | Status | Notes |
|-------|--------|--------|
| Labels | ✅ Pass | Labels use `screen-reader-text`; `for`/`id` match. |
| `aria-required` | ✅ Pass | Added to name and phone inputs. |
| Message | ✅ Pass | Form has `aria-describedby="callback-form-message"`; message has `role="alert"`, `aria-live="polite"`. |

---

## 4. Colours (WebAIM contrast)

| Check | Status | Notes |
|-------|--------|--------|
| Body text on background ≥ 4.5:1 | ✅ Pass | `--color-text: #2e2e2e` on `--color-background: #FFFFFF` ≈ 12.6:1. |
| Large text (18pt+ or 14pt+ bold) ≥ 3:1 | ✅ Pass | Same palette; large text meets 3:1. |
| Focus indicators visible | ✅ Pass | `:focus-visible` uses `--focus-ring` (2px solid primary); defined in `design-system.css` and `critical.css`. Buttons, links, inputs, toggle have focus styles. |

**Contrast reference:** WebAIM Contrast Checker — #2e2e2e on #ffffff passes AA and AAA for normal and large text.

---

## 5. Keyboard navigation

| Check | Status | Notes |
|-------|--------|--------|
| Tab order logical | ✅ Pass | Native DOM order; no `tabindex` used except `tabindex="-1"` on honeypot and menu sub-toggles where appropriate. |
| All interactive elements reachable | ✅ Pass | Links, buttons, form controls, menu toggle are in tab order. |
| Focus visible (outline or ring) | ✅ Pass | `:focus-visible` applied to `a`, `button`, and `[tabindex]:not([tabindex="-1"])` with `--focus-ring`. |
| No keyboard traps | ✅ Pass | Mobile menu closes on ESC (`navigation.js`); no modal or overlay that traps focus without a close path. |

---

## 6. Heading hierarchy

| Check | Status | Notes |
|-------|--------|--------|
| One `h1` per page | ✅ Pass | Homepage: hero has single `h1`. Contact: one `h1` ("Send us a message"). Single service: one `h1` (title). Index/content: one `h1` (entry title). |
| No skipped levels | ✅ Pass | Home: h1 → h2 (sections) → h3 (cards). Contact: h1 → h2 → h3. Single service: h1 → h2 → h3. |

---

## Summary

- **Pass:** Skip link, nav ARIA, menu toggle ARIA and JS, footer role, form labels, message `role="alert"`, contrast, focus styles, tab order, heading structure.
- **Fixes applied (2025-02-13):**
  1. **Logo alt** — `inc/theme-setup.php`: added `ccs_custom_logo_alt` filter so custom logo image gets site name as `alt` when `alt` is empty.
  2. **Footer social** — `footer.php`: added social links block when `ccs_facebook_url`, `ccs_linkedin_url`, `ccs_twitter_url` are set; each link has `aria-label` (e.g. "Facebook (opens in new window)"), `target="_blank"`, `rel="noopener noreferrer"`.
  3. **Contact form** — `page-templates/template-contact.php`: `aria-describedby="contact-form-message"` on form; `aria-required="true"` on name, email, phone inputs.
  4. **Consultation form** — `inc/class-contact-form.php`: `aria-required="true"` on name, phone, email, consent checkbox (form already had `aria-describedby`).
  5. **Callback form** — `single-service.php`: `aria-describedby="callback-form-message"` on form; `aria-required="true"` on name and phone inputs.

---

## References

- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [WCAG 2.1 Level AA](https://www.w3.org/WAI/WCAG21/quickref/?currentsidebar=%23col_customize&level=aa)
- Theme design tokens: `assets/css/design-system.css` (WCAG 2.1 AA aligned per MASTER.md)
