# CCS WordPress Theme — Technical Architecture

**Continuity Care Services** — Kent home care.  
Technical architecture for the custom WordPress theme: modular structure, performance, accessibility, SEO, admin UX, and scalability.

**Reference:** [CCS-THEME-AND-CONTENT-GUIDE.md](./CCS-THEME-AND-CONTENT-GUIDE.md) (business requirements), [design-system/MASTER.md](../design-system/MASTER.md) (design system).

**Note:** The content guide may refer to “care service” as a business term; the theme implements this as the **`service`** post type (slug `services`).

---

## 1. Requirements & constraints

| Requirement | Target | Constraint |
|-------------|--------|------------|
| **Modular** | Plug-and-play activation of features | No code required to enable/disable; optional thin entry files for clarity |
| **Performance** | &lt;2s page load, 90+ PageSpeed | Critical CSS inline; defer non-critical; no render-blocking JS |
| **Accessibility** | WCAG 2.1 AA out of the box | Semantic HTML, focus, contrast, labels; design system enforces |
| **SEO** | Schema markup, meta, canonical | Organization, WebSite, Service, LocalBusiness, BreadcrumbList, FAQ |
| **Admin-friendly** | No code for content updates | CPTs + meta boxes + Customizer; content guide as single reference |
| **Scalable** | Future services/locations | CPT + taxonomy pattern; template hierarchy and template-parts |

**Stack:** PHP 7.4+, WordPress 5.9+, theme-only (no required plugins for core behaviour).

---

## 2. inc/ folder structure and entry points

### 2.1 Current vs proposed layout

The theme already uses a **class-based** structure under `inc/` with an autoloader. The following keeps that pattern and adds **optional thin entry files** where they improve clarity and plug-and-play (e.g. a single `inc/setup.php` that bootstraps theme support and delegates to classes).

**Proposed inc/ layout:**

```
inc/
├── setup.php                    # [NEW] Single entry: theme support, menus, sidebars; requires theme-setup.php or inlines
├── enqueue.php                  # [NEW] Single entry: scripts/styles registration; delegates to theme-setup or class
├── class-autoloader.php        # (existing)
├── theme-setup.php             # (existing) Theme supports, enqueue, meta boxes, all init hooks
├── custom-post-types.php       # [OPTIONAL ALIAS] Requires post-types/class-register-post-types.php; instantiate
├── schema-markup.php            # [OPTIONAL ALIAS] Requires seo/class-structured-data.php; instantiate
├── admin-dashboard.php          # [OPTIONAL ALIAS] Requires admin classes; instantiate dashboard + welcome
├── admin/
│   ├── class-dashboard-widget.php
│   ├── class-welcome-screen.php
│   └── class-enquiry-manager.php
├── api/
│   └── class-ccs-form-handlers.php
├── accessibility/
│   └── class-accessibility-checker.php
├── blocks/
│   ├── class-cta-block.php
│   ├── class-faq-block.php
│   └── class-testimonial-block.php
├── core/
│   └── class-error-handler.php
├── custom-fields/
│   ├── class-meta-box-base.php
│   ├── class-service-meta-box.php
│   ├── class-location-meta-box.php
│   └── class-enquiry-meta-box.php
├── customizer/
│   └── class-theme-customizer.php
├── integrations/
│   ├── class-email-notifications.php
│   └── class-analytics.php
├── performance/
│   ├── class-critical-css.php
│   ├── class-cache-control.php
│   ├── class-image-optimization.php
│   └── class-lazy-load.php
├── post-types/
│   └── class-register-post-types.php
├── seo/
│   ├── class-seo-optimizer.php
│   └── class-structured-data.php
├── taxonomies/
│   └── class-register-taxonomies.php
├── cli/
│   └── class-ccs-critical-css-command.php
├── class-contact-form.php
├── class-security.php
├── class-theme-activation.php
└── class-welcome-screen.php  →  use admin/class-welcome-screen.php if consolidated
```

**Recommendation:** Keep **functions.php** as the single loader (as now). Optionally:

- **inc/setup.php** — Require once from functions.php; inside it, call `ccs_theme_setup()` and any other “theme support / menus” hooks, or require `theme-setup.php`. Use if you want a single-named “setup” entry.
- **inc/enqueue.php** — Either move `ccs_theme_scripts()` and `ccs_defer_scripts()` here and require from theme-setup.php, or keep them in theme-setup and add a one-line require in functions that pulls in a file which only registers enqueue hooks. Keeps “enqueue” discoverable.
- **custom-post-types.php**, **schema-markup.php**, **admin-dashboard.php** — Thin files that require the right class and call `new CCS_*()`. Useful for developers who expect these names; functions.php can continue to instantiate directly for minimal indirection.

**Plug-and-play:** Features are already modular (each class hooks into `init` or other actions). To “disable” a feature, comment out or remove the single instantiation in functions.php (or the optional inc/ entry file). No feature flags are required unless you add an options UI to toggle them.

### 2.2 functions.php bootstrap order (current, keep)

1. Constants (THEME_VERSION, THEME_DIR, THEME_URL)
2. Autoloader
3. CPTs (`CCS_Register_Post_Types`)
4. Taxonomies (`CCS_Register_Taxonomies`)
5. theme-setup.php (supports, menus, enqueue, meta boxes, structured data, SEO, performance, security, blocks, accessibility)
6. Theme activation
7. Welcome screen
8. Contact form
9. WP-CLI critical CSS command (when WP_CLI defined)

No change required; optional entry files only add clarity.

---

## 3. Asset loading strategy

### 3.1 Goals

- **First paint &lt;2s:** Critical CSS inlined in `<head>` (≤14KB).
- **Non-blocking:** Non-critical CSS deferred (e.g. `media="print"` + `onload="this.media='all'"`).
- **Scripts:** Defer where possible (navigation, form-handler, consultation-form); no `document.write`.

### 3.2 Current implementation (keep and extend)

- **Critical CSS** (`inc/performance/class-critical-css.php`):
  - Stored per template type in option `ccs_critical_css`.
  - Inlined in `wp_head` (first ~14KB).
  - Fallback: `assets/css/critical.css`.
  - Regenerate: `wp ccs regenerate-critical-css` (WP-CLI).

- **Deferred styles:** Filter `ccs_deferred_style_handles` lists: design-system, components, header, responsive, theme-style, homepage, service-page, location-page, contact-page. Each is loaded with `media="print"` and switched to `all` on load.

- **Scripts:** `ccs_defer_scripts` adds `defer` to navigation, form-handler, consultation-form.

### 3.3 Strategy summary

| Asset type | Strategy |
|------------|----------|
| Critical CSS | Inline in `<head>` from option or `assets/css/critical.css`; max 14KB |
| Non-critical CSS | Enqueue as usual; style_loader_tag rewrites to deferred (print/onload) |
| Fonts | Preconnect to Google Fonts; preload main font stylesheet |
| JS (nav, forms) | Defer; localize for AJAX URL and nonces |
| Template-specific CSS | Enqueue only on matching template (homepage, single service, single location, contact) |

### 3.4 Checklist for new stylesheets

- Add handle to `ccs_deferred_style_handles` (or omit if truly critical).
- Enqueue with correct dependencies (design-system → components → …).
- If a new page template, add conditional enqueue and consider adding a critical CSS template key for `wp ccs regenerate-critical-css`.

---

## 4. Template hierarchy and reusability

### 4.1 WordPress template hierarchy (theme usage)

| Request | Template used |
|---------|----------------|
| Front page (static) | `page-templates/template-homepage.php` (assigned in Reading) |
| Single service | `single-service.php` → `single.php` fallback |
| Single location | `single-location.php` → `single.php` fallback |
| Contact page | `page-templates/template-contact.php` |
| Generic page | `page.php` |
| Generic single post | `single.php` |
| Archive (e.g. services) | `archive-service.php` if present, else `archive.php` |
| 404 | `404.php` |
| Index | `index.php` |

### 4.2 Reusable parts (template-parts)

Use `get_template_part( 'template-parts/...' )` for:

- **Header:** `header.php` (global); optional `template-parts/header-hero` for homepage hero.
- **Footer:** `footer.php` (global).
- **Cards:** Service card, location card, testimonial card (design system components).
- **Sections:** CQC block, partnerships, info cards, consultation form block.
- **Forms:** Consultation form markup (logic in shortcode/class).

Suggested template-parts (add or rename to match design-system/MASTER.md):

- `template-parts/cards/service-card`
- `template-parts/cards/location-card`
- `template-parts/cards/testimonial-card`
- `template-parts/sections/cqc-section`
- `template-parts/sections/partnerships`
- `template-parts/sections/info-cards`
- `template-parts/consultation-form` (wrapper; form itself via shortcode)

### 4.3 Naming and overrides

- **Page templates:** `page-templates/template-{name}.php`; assign in Page → Attributes → Template.
- **Single CPT:** `single-{post_type}.php` (e.g. `single-service.php`, `single-location.php`).
- **Archive:** `archive-{post_type}.php` if you need a custom services/locations listing.
- Child themes can override any of these by copying the file into the child theme.

---

## 5. Database schema (custom fields)

WordPress stores CPT data in `wp_posts`; custom fields in `wp_postmeta` (meta_key, meta_value per post_id). Schema below = meta keys and intended use.

### 5.1 Service (post type `service`)

| Meta key | Type | Use |
|----------|------|-----|
| `service_icon` | text | Dashicon class (e.g. dashicons-heart) |
| `service_short_description` | text | Short description (max 200 chars) |
| `service_setup_time` | text | Setup time description |
| `service_urgent` | checkbox | Urgent availability |
| `service_price_from` | text | Price from |
| `service_price_to` | text | Price to |
| `service_typical_hours` | text | Typical hours |
| `service_funding_options` | textarea | Funding options |
| `service_features` | serialized array | List of { feature_text, feature_icon } |
| `service_faqs` | serialized array | List of { question, answer } (also for FAQ schema) |
| `service_seo_title` | text | Override meta title |
| `service_meta_description` | textarea | Meta description |

### 5.2 Location (post type `location`)

| Meta key | Type | Use |
|----------|------|-----|
| `location_town` | text | Town |
| `location_county` | text | County |
| `location_postcode_area` | text | Postcode area |
| `location_areas_covered` | textarea | Areas covered |
| `location_latitude` | text | Latitude |
| `location_longitude` | text | Longitude |
| `location_population_65_plus` | text | Demographics |
| `location_families_supported` | text | Demographics |
| `location_local_hospitals` | serialized array | { hospital_name, hospital_phone, hospital_address } |
| `location_local_gp_practices` | textarea | GP practices |
| `location_chc_contact` | textarea | CHC contact |
| `location_council_adult_services` | textarea | Council adult services |
| `location_local_support_groups` | serialized array | { group_name, group_contact } |
| `location_team_size` | text | Team size |
| `location_coordinator_name` | text | Coordinator name |
| `location_coordinator_photo` | attachment ID | Coordinator photo |

### 5.3 Enquiry (post type `ccs_enquiry`)

Stored by form submission and admin; used for consultation requests and CRM-style columns/filters.

| Meta key | Type | Use |
|----------|------|-----|
| `enquiry_name`, `enquiry_email`, `enquiry_phone` | text | Contact |
| `enquiry_preferred_contact` | text | Preferred contact method |
| `enquiry_care_type`, `enquiry_conditions` | text | Care type / conditions |
| `enquiry_urgency`, `enquiry_location`, `enquiry_message` | text | Details |
| `enquiry_source`, `enquiry_landing_page`, `enquiry_referrer` | text | Attribution |
| `enquiry_utm_*` | text | UTM params |
| `enquiry_status`, `enquiry_assigned_to` | text | Workflow |
| `enquiry_follow_up_date`, `enquiry_notes` | text/date | Admin |
| `enquiry_communications` | serialized array | Log of communications |
| `enquiry_converted_date`, `enquiry_contract_value` | text | Conversion |

### 5.4 Options (theme-wide)

- **Critical CSS:** `ccs_critical_css` (array keyed by template type).
- **Customizer:** Theme mods (contact, social, CQC, analytics, etc.) — stored in `wp_options` / theme mods as per Customizer API.

No separate tables; use `wp_postmeta` and options only.

---

## 6. Admin UI and dashboard customization

### 6.1 Principle

**Admin-friendly = no code for content.** All copy, services, locations, and contact info are editable via:

- **Pages/Posts:** Standard editor.
- **Custom post types:** Services, Locations, Enquiries (and Testimonials if used).
- **Meta boxes:** Service Details, Location Details, Enquiry details (and status/assignee/notes).
- **Customizer:** Contact (phone, email, address), social links, CQC number/rating, analytics IDs, emergency banner.
- **Menus:** Primary and Footer via Appearance → Menus.
- **Widgets:** Sidebar and footer if used.

### 6.2 Dashboard and admin features (current)

- **Appearance → CCS Theme Setup (Welcome):** Quick start checklist, theme info, “Reset Demo Content”, server requirements.
- **Dashboard widget:** Stats, chart, recent enquiries (class-dashboard-widget).
- **Enquiry list table:** Custom columns, filters, bulk/row actions, export (class-enquiry-manager).
- **Meta boxes:** Service, Location, Enquiry (custom-fields/*).

### 6.3 Customizer (class-theme-customizer)

Use for:

- Contact details (phone, email, address)
- Social links (Facebook Messenger, Instagram, LinkedIn, Threads)
- CQC registration number and rating
- Analytics (GA4, etc.)
- Emergency banner (on/off, message)

No code required: admins change these under Appearance → Customize.

### 6.4 Content guide as single reference

- **docs/CCS-THEME-AND-CONTENT-GUIDE.md** is the single reference for what to create (pages, service posts, menus, form fields, copy). Theme code does not duplicate this; theme provides the templates and fields so that following the guide is enough.

---

## 7. Performance optimization checklist

Use to reach **&lt;2s load** and **90+ PageSpeed** (mobile).

### 7.1 Already in theme

- [x] Critical CSS inlined; non-critical deferred
- [x] Script defer (navigation, form-handler, consultation-form)
- [x] Preconnect/preload for fonts
- [x] Template-specific CSS enqueued only on relevant templates
- [x] Lazy loading for images (native + fallback)
- [x] Image optimization (WebP, srcset, max dimensions)
- [x] Cache-Control headers (class-cache-control)
- [x] Security headers (class-security)

### 7.2 Server and hosting

- [ ] PHP 7.4+ (or 8.x recommended)
- [ ] Object cache (Redis/Memcached) if high traffic
- [ ] CDN for static assets (optional)
- [ ] HTTPS only

### 7.3 WordPress and plugins

- [ ] Caching plugin (e.g. page cache) or server-level page cache
- [ ] Minify HTML/JS/CSS (plugin or build step) — ensure no breakage with deferred CSS
- [ ] Limit plugins; avoid heavy page builders if not needed
- [ ] Database: optimize tables; remove unused post meta

### 7.4 Theme-level checks

- [ ] No render-blocking JS in head (keep defer)
- [ ] Critical CSS regenerated after major layout/CSS changes (`wp ccs regenerate-critical-css`)
- [ ] Image sizes: use appropriate thumbnails and srcset; avoid oversized images
- [ ] No unnecessary external requests in critical path (fonts already preconnected/preloaded)
- [ ] Third-party scripts (analytics, chat) loaded async or after load

### 7.5 Measuring

- Run PageSpeed Insights (mobile + desktop) on homepage, a service single, contact page.
- Target: LCP &lt;2.5s, FID &lt;100ms, CLS &lt;0.1; Performance score 90+.
- If score &lt;90: identify largest blocks (often images or JS); then optimize (lazy load, defer, resize, critical CSS).

---

## 8. Accessibility (WCAG 2.1 AA)

- **Design system** (MASTER.md): Colour contrast, focus states, typography, component patterns.
- **Theme:** Semantic HTML (header, nav, main, footer, sections, headings); form labels and ARIA where needed; skip link; logo alt from site name when missing.
- **Checker:** `CCS_Accessibility_Checker` (admin notices and checklist).
- **Forms:** Consultation form shortcode and meta boxes use proper labels and structure; reCAPTCHA and consent must remain accessible (plugin/config).

No separate doc here; design system + theme implementation + checker cover “out of the box” AA.

---

## 9. SEO (schema and on-page)

- **Structured data** (`class-structured-data.php`): Organization (HomeHealthCareService), WebSite, Service, LocalBusiness, ContactPage, FAQPage, BreadcrumbList — output in `wp_head` as JSON-LD.
- **On-page** (`class-seo-optimizer.php`): Meta titles, meta descriptions, Open Graph, Twitter Card, canonical, sitemap behaviour.

Schema is automatic per page type; meta can be overridden per post via Service/Location meta boxes (service_seo_title, service_meta_description) or SEO plugin if installed.

---

## 10. Scalability (future services/locations)

- **More services:** Add posts to CPT `service`; no code change. Use taxonomies if you need categories (e.g. service_category).
- **More locations:** Add posts to CPT `location`; same.
- **New CPTs:** Copy pattern: register in `class-register-post-types.php`, add meta box in custom-fields/, add `single-{post_type}.php` and optional `archive-{post_type}.php`, extend structured data if needed.
- **New templates:** Add `page-templates/template-*.php` and conditional enqueue for CSS; add critical CSS template key if desired.

The current architecture supports growth without a rewrite.

---

## 11. Optional inc/ entry files (implementation)

If you introduce the optional thin entry files, implement as follows.

### inc/setup.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once THEME_DIR . '/inc/theme-setup.php';
// Optional: any extra theme_support or menu registration here.
```

Then in functions.php, replace `require_once THEME_DIR . '/inc/theme-setup.php';` with `require_once THEME_DIR . '/inc/setup.php';`.

### inc/enqueue.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Move ccs_theme_scripts and ccs_defer_scripts here from theme-setup.php,
// then in theme-setup.php: require_once THEME_DIR . '/inc/enqueue.php';
```

Or keep enqueue in theme-setup and add a comment in enqueue.php that points to theme-setup for the actual hooks.

### inc/custom-post-types.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
new CCS_Register_Post_Types();
```

Then in functions.php use `require_once THEME_DIR . '/inc/custom-post-types.php';` instead of instantiating in place (autoloader will load class from post-types/).

### inc/schema-markup.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! is_admin() ) {
    new CCS_Structured_Data();
}
```

Register on `init` (e.g. from this file) so it runs at the right time; or keep current init hook in theme-setup and only require this file for clarity.

### inc/admin-dashboard.php

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( is_admin() ) {
    new CCS_Welcome_Screen();
    add_action( 'init', function() { new CCS_Dashboard_Widget(); }, 20 );
    add_action( 'init', function() { new CCS_Enquiry_Manager(); }, 20 );
}
```

Then in functions.php, replace the three instantiations with `require_once THEME_DIR . '/inc/admin-dashboard.php';` (and remove the separate init hooks for dashboard and enquiry manager from theme-setup if they were there).

---

**Document version:** 1.0  
**Theme version:** 1.0.0  
**Last updated:** 2025
