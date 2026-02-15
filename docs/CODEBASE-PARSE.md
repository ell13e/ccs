# CCS codebase parse

Single-document structural parse of the **CCS** WordPress theme (Continuity Care Services – Kent-based home care). Use this to orient in the repo, find where features live, and follow data/UI flow.

---

## 1. Project identity

| | |
|---|---|
| **Name** | Kent Care Provider / CCS WP Theme |
| **Text domain** | `ccs-wp-theme` |
| **Type** | WordPress classic + block hybrid theme (block styles + theme.json; pages use classic editor) |
| **Brand** | Continuity Care Services – home care, CQC-regulated; tagline “Your Team, Your Time, Your Life” |
| **Design** | `design-system/MASTER.md` + `theme.json` + `assets/css/design-system.css` |

**Constants (in `functions.php`):** `THEME_VERSION`, `THEME_DIR`, `THEME_URL`.

---

## 2. Tech stack

- **PHP:** Theme and inc classes; WordPress naming (`class-class-name.php`).
- **Autoloader:** `inc/class-autoloader.php` – resolves `CCS_*` and unprefixed names under `inc/` and `inc/*/`.
- **Front-end:** Vanilla CSS (design-system, components, header, footer, homepage, contact, resource-download-modal); vanilla JS (navigation, resource-download, consultation-form).
- **Editor:** Classic editor for **pages**; block editor for posts. Block patterns and editor styles present.
- **Tooling:** PHPStan (`phpstan.neon`); WP-CLI for critical CSS (`wp ccs regenerate-critical-css`).

---

## 3. Directory layout

```
CCS/
├── assets/
│   ├── css/          # design-system.css, components.css, header, footer, homepage, contact-page, resource-download-modal, critical, editor-style
│   └── js/           # navigation.js, resource-download.js, consultation-form.js
├── design-system/
│   └── MASTER.md     # Single source of truth: colours, typography, spacing, shadows, radius, components, WCAG
├── docs/             # Content guide, inventory/workflow (migration), project status, this parse
├── inc/              # All theme logic (see §5)
├── page-templates/   # PHP page templates (see §6)
├── template-parts/   # Reusable parts: home/*, breadcrumb, resource modals, content.php
├── footer.php
├── functions.php     # Loader: constants, autoloader, CPTs, taxonomies, requires, singletons
├── header.php
├── page.php, search.php
├── style.css         # Theme header only; real styles in assets
└── theme.json        # Block theme config: palette, typography, spacing, layout (contentSize 1280px, wide 1440px)
```

---

## 4. Entry points

- **Bootstrap:** `functions.php` – defines constants, registers autoloader, requires `theme-setup`, `header-footer-helpers`, `block-patterns`, `page-editor-enhancements`, `resource-downloads`, `resource-download-ajax`; instantiates `CCS_Theme_Activation`, `CCS_Welcome_Screen`, `CCS_Contact_Form`. WP-CLI adds `inc/cli/class-ccs-critical-css-command.php`.
- **Styles/scripts:** Enqueued in `inc/theme-setup.php` (`ccs_theme_scripts`). Order: design-system → components → header, footer, homepage (conditional), contact (conditional), resource-download (conditional), critical; JS: navigation, resource-download (conditional), consultation-form (conditional).
- **Menus:** `primary`, `footer`, `footer_company`, `footer_help` (registered in theme-setup).

---

## 5. Inc structure (logic)

| Area | Path | Purpose |
|------|------|--------|
| **Core** | `inc/class-autoloader.php` | PSR-like class → file under `inc/` |
| | `inc/theme-setup.php` | Supports, menus, enqueue, meta boxes, email/form handlers |
| | `inc/header-footer-helpers.php` | Contact info, footer fallback menus |
| | `inc/block-patterns.php` | Block patterns (CCS category) |
| | `inc/page-editor-enhancements.php` | Classic editor for pages, SEO meta box |
| **Resources (care guides)** | `inc/resource-downloads.php` | CPT `ccs_resource`, taxonomy, download table, metaboxes |
| | `inc/resource-download-ajax.php` | AJAX handler, email delivery, tokenised download URL |
| **Forms / contact** | `inc/class-contact-form.php` | Shortcode `[ccs_consultation_form]`, AJAX, CPT `ccs_enquiry`, emails |
| | `inc/api/class-ccs-form-handlers.php` | Front-end form handlers (enquiry, callback) |
| **Activation / admin** | `inc/class-theme-activation.php` | Demo pages, services, menus, Reading, permalinks; Reset Demo Content |
| | `inc/admin/class-welcome-screen.php` | Appearance → CCS Theme Setup (welcome, checklist, Reset Demo) |
| | `inc/admin/class-enquiry-manager.php` | Enquiry management UI |
| | `inc/admin/class-dashboard-widget.php` | Dashboard widget |
| **Post types & taxonomies** | `inc/post-types/class-register-post-types.php` | Services, Locations, Enquiries, Testimonials |
| | `inc/taxonomies/class-register-taxonomies.php` | Service Category, Condition, Location Areas |
| **Custom fields** | `inc/custom-fields/class-meta-box-base.php` | Base for meta boxes |
| | `inc/custom-fields/class-service-meta-box.php` | Service meta box |
| | `inc/custom-fields/class-location-meta-box.php` | Location meta box |
| | `inc/custom-fields/class-enquiry-meta-box.php` | Enquiry meta box |
| **Integrations** | `inc/integrations/class-email-notifications.php` | Admin/user/urgent (Slack/SMS/on-call) notifications |
| | `inc/integrations/class-analytics.php` | Analytics integration |
| **SEO** | `inc/seo/class-seo-optimizer.php` | SEO optimisation |
| | `inc/seo/class-structured-data.php` | Schema/structured data |
| **Performance** | `inc/performance/class-critical-css.php` | Critical CSS |
| | `inc/performance/class-cache-control.php` | Cache headers |
| | `inc/performance/class-image-optimization.php` | Image optimisation |
| | `inc/performance/class-lazy-load.php` | Lazy loading |
| **Blocks** | `inc/blocks/class-cta-block.php` | CTA block |
| | `inc/blocks/class-testimonial-block.php` | Testimonial block |
| | `inc/blocks/class-faq-block.php` | FAQ block |
| **Security / accessibility** | `inc/class-security.php` | Security helpers |
| | `inc/accessibility/class-accessibility-checker.php` | Accessibility checks |
| **Other** | `inc/core/class-error-handler.php` | Error handling |
| | `inc/customizer/class-theme-customizer.php` | Theme customizer |
| | `inc/cli/class-ccs-critical-css-command.php` | WP-CLI critical CSS command |

---

## 6. Page templates

| Template | File | Use |
|----------|------|-----|
| Homepage | `template-homepage.php` | Static front page; uses `template-parts/home/*` |
| About | `template-about.php` | About us |
| Contact | `template-contact.php` | Contact + consultation form |
| Care guides | `template-care-guides.php` | Downloadable resources (care guides); resource grid + modal |
| Getting started | `template-getting-started.php` | Care journey / getting started |
| FAQs | `template-faqs.php` | FAQs |
| CQC | `template-cqc.php` | CQC and our care |
| Privacy | `template-privacy.php` | Privacy policy |
| Terms | `template-terms.php` | Terms of use |
| Cookies | `template-cookies.php` | Cookie policy |
| Accessibility | `template-accessibility.php` | Accessibility statement |
| Unsubscribe | `template-unsubscribe.php` | Unsubscribe (e.g. from emails) |

---

## 7. Template parts

- **Home:** `hero`, `services`, `differentiators`, `why-choose-us`, `info-cards`, `testimonial`, `partnerships`, `cqc-section`, `scenarios` – used by `template-homepage.php`.
- **Global:** `breadcrumb.php`, `resource-download-modal.php`, `resource-unavailable-modal.php`, `content.php`, `content-none.php`.

---

## 8. Data model (CPTs & taxonomies)

- **service** – Services (archive at `/services/`); show_in_rest; meta via `CCS_Service_Meta_Box`.
- **location** – Locations; meta via `CCS_Location_Meta_Box`.
- **ccs_enquiry** – Enquiries from consultation form; meta via `CCS_Enquiry_Meta_Box`; no direct “Add new” in admin.
- **testimonial** – Testimonials.
- **ccs_resource** – Downloadable care guides; taxonomy and download table in `inc/resource-downloads.php`; delivery via `inc/resource-download-ajax.php`.

Taxonomies: Service Category, Condition, Location Areas (from `CCS_Register_Taxonomies`).

---

## 9. Assets (CSS/JS)

- **Global:** `design-system.css`, `components.css`, `header.css`, `footer.css`, `critical.css` (optional).
- **Page-specific:** `homepage.css`, `contact-page.css`, `resource-download-modal.css` (and matching JS where applicable).
- **JS:** `navigation.js` (global), `resource-download.js` (care guides page), `consultation-form.js` (contact/consultation).
- **Editor:** `editor-style.css` (classic editor for pages).

Enqueue logic and dependencies live in `inc/theme-setup.php`.

---

## 10. Design system

- **Source:** `design-system/MASTER.md` – colours (primary purple, secondary teal, neutrals, semantic), typography (Poppins/Open Sans, scale, weights), spacing, shadows, radius, component tokens, WCAG 2.1 AA.
- **Implementation:** `theme.json` (palette, typography, spacing, layout); `assets/css/design-system.css` (tokens and base styles). Components and page CSS build on these.

---

## 11. Docs and references

- **Content and UX:** `docs/CCS-THEME-AND-CONTENT-GUIDE.md` – copy, pages, menus, forms, URLs, implementation checklist.
- **Migration (historical):** `docs/FINALCTAIHOPE-inventory.md`, `docs/FINALCTAIHOPE-to-CCS-workflow.md` (CTA → CCS migration; FINALCTAIHOPE folder removed).
- **Design strategy:** `docs/DESIGN-SYSTEM-CRITICAL-CSS-STRATEGY.md`, `docs/PROJECT-STATUS-REPORT.md`.

---

## 12. Where to look for…

| Need | Look in |
|------|--------|
| Add a new page template | `page-templates/template-*.php`; assign in Admin or theme activation |
| Change global layout / header / footer | `header.php`, `footer.php`, `inc/header-footer-helpers.php`, `assets/css/header.css`, `footer.css` |
| Change homepage sections | `page-templates/template-homepage.php`, `template-parts/home/*.php`, `assets/css/homepage.css` |
| Resource (care guide) download flow | `inc/resource-downloads.php`, `inc/resource-download-ajax.php`, `template-parts/resource-download-modal.php`, `assets/js/resource-download.js`, `assets/css/resource-download-modal.css` |
| Consultation / contact form | `inc/class-contact-form.php`, `inc/api/class-ccs-form-handlers.php`, `assets/js/consultation-form.js`, `template-contact.php` |
| Enqueue new CSS/JS | `inc/theme-setup.php` (`ccs_theme_scripts`) |
| New CPT or taxonomy | `inc/post-types/class-register-post-types.php`, `inc/taxonomies/class-register-taxonomies.php`; meta boxes in `inc/custom-fields/` |
| Colours, type, spacing | `design-system/MASTER.md`, `theme.json`, `assets/css/design-system.css` |
| Block patterns | `inc/block-patterns.php` |
| SEO / schema | `inc/seo/class-seo-optimizer.php`, `inc/seo/class-structured-data.php` |

---

*Generated as a one-pass structural parse of the CCS theme. Update this doc when adding major subsystems or moving responsibilities.*
