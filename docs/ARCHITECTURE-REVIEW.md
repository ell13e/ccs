# CCS Theme — Architecture Review

**Skills applied:** `/architecture`, `/senior-architect`  
**Scope:** inc/ structure, asset loading, performance, security, scalability, admin UX.  
**Reference:** [CCS-THEME-ARCHITECTURE.md](./CCS-THEME-ARCHITECTURE.md).

---

## 1. File structure and separation of concerns

### Current state

- **Single entry:** `functions.php` loads autoloader, then instantiates CPTs, taxonomies, theme-setup, activation, welcome, contact form, WP-CLI command. No separate `inc/setup.php` or `inc/enqueue.php`; all enqueue and theme support live in **`inc/theme-setup.php`**.
- **Modularity:** Classes under `inc/` (admin, api, blocks, custom-fields, performance, post-types, seo, taxonomies) are feature-scoped and hook into `init` / `wp_head` etc. Disabling a feature = remove one instantiation from functions.php.
- **Template parts:** Hero, Why Choose Us, CQC section are in `template-parts/` and included from `template-homepage.php`; block CSS is enqueued conditionally by template.

### Verdict

- **Separation of concerns:** Good. Presentation (templates), logic (classes), and bootstrap (functions.php + theme-setup) are clear.
- **Optional thin entry files (setup.php, enqueue.php):** Documented in CCS-THEME-ARCHITECTURE.md as optional. Not required for correctness; add only if the team wants a single-named “enqueue” or “setup” file for discoverability.
- **Recommendation:** Keep current structure. If desired, add **`inc/enqueue.php`** that only contains `ccs_theme_scripts()` and `ccs_defer_scripts()` and require it from theme-setup.php so “where are styles/scripts registered?” has one obvious file. No change to behaviour.

---

## 2. Performance architecture

### Asset loading (theme-setup.php)

- **Critical CSS:** Handled by `inc/performance/class-critical-css.php`; inlined in `wp_head`; fallback `assets/css/critical.css`.
- **Non-critical CSS:** Deferred via `style_loader_tag` (e.g. `media="print"` + `onload="this.media='all'"`). Handles: design-system, components, scroll-animations, card-effects, header, responsive, theme-style; conditionally homepage blocks (hero, why-choose-us, cqc-section), service, location, contact, careers (sticky-cta).
- **Scripts:** Enqueued in footer; `ccs_defer_scripts()` adds `defer` to navigation, form-handler, consultation-form, site-interactions, scroll-animations, careers-interactions. No render-blocking JS in head.
- **Template-specific JS/CSS:** Homepage-only and careers-only assets are conditional; avoids loading hero/CQC CSS on every page.

### Gaps and recommendations

| Item | Status | Recommendation |
|------|--------|----------------|
| **Cache busting** | Theme uses `THEME_VERSION` for query string | For dev, consider `filemtime()` for CSS/JS in development (e.g. `if ( defined( 'WP_DEBUG' ) && WP_DEBUG` use filemtime). |
| **Consultation form / confetti** | Enqueued from `class-contact-form.php` with the form shortcode | Confetti script loads only where the consultation form is rendered; no change needed. |
| **Critical CSS regeneration** | WP-CLI command documented | Ensure `wp ccs regenerate-critical-css` is run after major layout/CSS changes. |

---

## 3. Scalability

- **Services / locations:** Add posts to CPTs; no code change. Taxonomies available if needed.
- **New templates:** Add `page-templates/template-*.php` and conditional enqueue in theme-setup; add critical CSS template key if desired.
- **Child themes:** Override any template or partial; structure supports it.
- **Recommendation:** No refactor. Optional **`/lib`** folder for pure helper functions (e.g. `ccs_format_phone()`, `ccs_get_theme_mod_default()`) can be added later if helpers grow; not required now.

---

## 4. Security

- **Forms:** Consultation form uses `wp_nonce_field` and `wp_verify_nonce`; input sanitized (`sanitize_text_field`, `sanitize_email`); honeypot and server-side validation present.
- **Admin:** Reset demo and export use `current_user_can` and `wp_verify_nonce`; welcome checklist nonce verified.
- **Output:** Escaping used (`esc_html`, `esc_attr`, `esc_url`) in templates and admin output.
- **Recommendation:** Keep nonce and capability checks on any new form or admin action; continue escaping all dynamic output.

---

## 5. Admin experience

- **Content without code:** Pages, CPTs (services, locations, enquiries), Customizer (contact, CQC, social, emergency banner), menus. Matches “no code for content” goal.
- **Meta boxes:** Service, location, enquiry meta defined in architecture; implemented in custom-fields/.
- **Recommendation:** No structural change. Ensure Customizer and meta box labels match content guide so copy updates don’t require code.

---

## 6. Recommended improvements (short list)

1. **Document enqueue strategy in theme-setup.php** — Add a short comment block at top of `ccs_theme_scripts()` listing conditional groups (global, homepage, careers, contact) and defer list. Done in Task 7.
2. **Optional inc/enqueue.php** — Move only the two enqueue functions into `inc/enqueue.php` and require from theme-setup if the team wants a single “enqueue” entry point; behaviour unchanged.
3. **Asset versioning** — Keep `THEME_VERSION` for production; optionally use `filemtime()` in development for easier cache busting during active development.
4. **CPT in plugin** — Moving Service/Location CPT to a small must-use or standalone plugin would improve portability (e.g. reuse on another site). Not required for single-site; consider only if multi-site or reuse is planned.

---

## 7. Summary

| Area | Result |
|------|--------|
| File structure | Modular; optional thin entries documented. |
| Performance | Critical CSS, deferred assets, conditional loading; aligned with &lt;2s and 90+ target. |
| Scalability | CPT + template hierarchy supports growth. |
| Security | Nonces, sanitization, escaping, capability checks in place. |
| Admin UX | Customizer + meta boxes + CPTs; no code for content. |

No blocking issues. Optional refinements (enqueue.php, lib folder, CPT plugin) can be adopted when useful.

---

**Document version:** 1.0  
**Review date:** 2025-02  
**Skills:** /architecture, /senior-architect
