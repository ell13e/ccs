# CTA (FINALCTAIHOPE) → CCS migration – Step 1 inventory

**Purpose:** Scope of the full CTA feature set to migrate into CCS. No implementation yet; this is the “Take” deliverable.

**Source:** `FINALCTAIHOPE/cta-wp-theme` (Continuity Training Academy theme).  
**Target:** CCS repo root (Kent Care Provider theme).  
**Live site:** https://www.continuitycareservices.co.uk

---

## 1. Triage summary

| | **CCS** (repo root) | **CTA** (FINALCTAIHOPE/cta-wp-theme) |
|---|---|---|
| **Kind** | wp-theme (classic) | wp-theme (classic) |
| **Theme name** | Kent Care Provider | Continuity Training Academy |
| **Block theme** | No | No |
| **theme.json** | Yes (root) | Yes |
| **Composer** | No | Yes |
| **PHPUnit** | No | Yes (phpunit.xml) |
| **Node/package.json** | No | Yes (Vite) |
| **Block JSON files** | 0 | 0 |
| **Interactivity API** | No | No |

Both are classic PHP themes with a theme.json; CTA has more tooling (Composer, PHPUnit, Vite).

---

## 2. CTA feature set (what to migrate)

### 2.1 Core theme files

- **Templates:** `front-page.php`, `home.php`, `index.php`, `page.php`, `single.php`, `single-course.php`, `single-course_event.php`, `archive-course.php`, `archive-course_event.php`, `search.php`, `404.php`, `coming-soon.php`
- **Config / root:** `functions.php`, `style.css`, `theme.json`, `robots-txt-config.php`, `searchform.php`

### 2.2 Page templates (CTA)

All under `page-templates/`:

- `page-about.php`, `page-accessibility.php`, `page-contact.php`, `page-cookies.php`, `page-cqc-hub.php`, `page-downloadable-resources.php`, `page-faqs.php`, `page-group-training.php`, `page-news.php`, `page-privacy.php`, `page-search.php`, `page-terms.php`, `page-unsubscribe.php`

**CCS already has:** `template-homepage.php`, `template-contact.php` (under `page-templates/`).

### 2.3 Template parts (CTA)

- `breadcrumb.php`, `course-card.php`, `cqc-requirements-section.php`, `resource-download-modal.php`, `resource-unavailable-modal.php`, `booking-modal.php`

**CCS already has:** `content-none.php`, `content.php`, `home/` (directory).

### 2.4 Inc modules (CTA – 56 files)

Grouped by area:

**Theme foundation**

- `theme-setup.php`, `theme-options.php`, `customizer.php`, `nav-walkers.php`, `block-patterns.php`, `page-editor-enhancements.php`, `content-templates.php`, `custom-repeaters.php`, `coming-soon.php`

**Post types / content**

- `post-types.php`, `course-category-limits.php`, `content-templates.php`

**SEO**

- `seo.php`, `seo-admin.php`, `seo-schema.php`, `seo-implementation.php`, `seo-global-settings.php`, `seo-verification.php`, `seo-search-console.php`, `seo-image-sitemap.php`, `seo-links-redirects.php`

**Resources / downloads / newsletter**

- `resource-admin-page.php`, `resource-ajax-handlers.php`, `resource-downloads.php`, `resource-email-delivery.php`, `newsletter-automation.php`, `newsletter-automation-builder.php`, `newsletter-subscribers.php`

**Forms / submissions**

- `form-submissions-admin.php`, `ajax-handlers.php`

**Events**

- `eventbrite-integration.php`, `event-schema.php`, `event-management-ui.php`, `populate-test-events.php`

**AI / content**

- `ai-content-assistant.php`, `ai-chat-widget.php`, `ai-course-assistant.php`, `ai-alt-text.php`, `ai-provider-fallback.php`

**Marketing / integrations**

- `facebook-lead-ads-webhook.php`, `facebook-conversions-api.php`, `discount-codes.php`, `smart-internal-linker.php`

**Admin / settings**

- `admin.php`, `api-keys-settings.php`, `media-library-folders.php`, `data-importer.php`, `create-phase1-posts.php`, `auto-populate-articles.php`

**Performance / technical**

- `performance-helpers.php`, `cache-helpers.php`, `cwv-optimization.php`

**ACF / structure**

- `acf-fields.php`

**Other**

- `robots-txt-config.php` (root)

### 2.5 Assets (CTA)

- **CSS:** `assets/css/` (e.g. accordion, resource-download-modal, admin-review-picker, seo-admin, plus others)
- **JS:** `assets/js/` and `src/` (Vite build)
- **Data:** `data/` – `team-members.json`, `news-articles.json`, `courses-database.json`, `site-settings.json`, `scheduled-courses.json` (reference/seed data)

### 2.6 Other CTA-only files

- `tests/` (PHPUnit), `composer.json`, `package.json`, `vite.config.js`, `.stubs/`, `inc.backup/` (skip for migration)
- `docs/` in CTA theme – treat as reference; migrate only if needed for CCS

---

## 3. What CCS already has (no duplicate work)

- **Inc:** `theme-setup.php`, `block-patterns.php`, `page-editor-enhancements.php`, `header-footer-helpers.php`, `class-*` (autoloader, contact form, security, theme activation)
- **Templates:** `template-homepage.php`, `template-contact.php`
- **Template parts:** `home/` (e.g. hero, services, differentiators), `content.php`, `content-none.php`
- **Design:** `design-system/MASTER.md`, `theme.json`, `assets/` (header, homepage, footer, etc.)

---

## 4. Scope statement (Step 1 “done when”)

- **Inventory:** Every CTA UI/functional element and its files are listed above (templates, template parts, inc modules, assets, data references).
- **Scope of migration:** Bring into CCS all CTA features that CCS should have: equivalent page templates and template parts, inc logic (adapted to CCS brand and MASTER.md), and associated CSS/JS/assets. Omit CTA-only one-offs (e.g. Eventbrite, CTA-specific AI, newsletter automation) unless you explicitly decide to adopt them in CCS; in that case they stay in scope.
- **Next:** Step 2 – Tailor: bring chosen features into CCS in passes (by section or feature area), align with MASTER.md and CCS brand. **Use the CTA files; tailor all content to CCS** (see §5 below).

---

## 5. Content tailoring to CCS (mandatory for Step 2)

**Use the CTA files and structure; tailor the content to CCS.** CCS is a **home care provider** (Maidstone & Kent). CTA is a training academy—so keep the files and UI, but fill them with CCS content.

| CTA file / concept | Use file? | Tailor to CCS as |
|-------------------|-----------|------------------|
| **Resources / downloadable resources** (page, modals, downloads) | Yes | **Home care guides**, FAQs, Referral Information. Same UI; content = care guides and referral info, not course catalogues. |
| **CQC Hub** (page template, sections) | Yes | **CQC and our care** (registration, rating, reassurance). Same structure; content = CQC reassurance, not training/compliance hub. |
| **Group Training** (page template, nav slot) | Yes | **Book a care consultation** / **Arrange a visit** (Contact/consultation) or **Getting started with care** / **How we work with you** (care journey, assessment). Same page slot; no training bookings. |
| Course events, Eventbrite, course cards | Use if repurposing | Omit or repurpose (e.g. events for families). Replace course copy with service/care content. |
| Course cards, “book training” CTAs | Yes (structure) | Service cards, care FAQs, **Book consultation** / **Contact us** CTAs. |
| About, Contact, Privacy, Terms, FAQs, News | Yes | Map to CCS; copy and tone = CCS (care, team, consultation, careers). |

Every migrated element must support CCS goals: care enquiries, consultations, careers, service information, and trust (CQC rating, partners). Use the files; tailor the copy.

---

## 6. Suggested migration passes (for Step 2)

1. **Layout and global:** theme.json refinements, header/footer patterns, navigation (from CTA where useful). Nav tailored to CCS: Group Training slot → e.g. Book a care consultation; Resources = **Home care guides**, FAQs, Referral Information.
2. **Page templates:** map CTA page templates to CCS (about, contact, privacy, terms, FAQs, resources, news). Do not add Group Training or CQC Hub as training hub; use the table in §5 above.
3. **Template parts:** modals (e.g. resource download if used for care guides), breadcrumb, CQC section (reassurance only), etc., as needed for CCS content model. No course cards unless repurposed for “care options” or services.
4. **Inc logic:** migrate in groups (e.g. SEO, resources/downloads, forms, events, admin) and only what CCS will use; drop or stub CTA-only integrations until product decision.
5. **Assets:** copy and adapt CTA CSS/JS into CCS `assets/`, namespaced and aligned with MASTER.md tokens.

Use this doc as the single scope reference for “the whole thing” until the migration is complete.

---

## 7. Decision log (contextual evaluation)

Use this section when deciding how to handle each CTA element. Each row states the **decision**, **context** (why), and **logic/skill** applied. Apply the same reasoning when you encounter similar elements.

| Element | Decision | Context | Logic / skill |
|--------|----------|---------|----------------|
| **Resources / downloads (page, CPT, modals)** | Use file; tailor content | CCS needs lead-capture downloads for families and referrers, not course catalogues. Same UX (modal, email delivery) fits "send me this care guide". | **Content strategy:** CCS goals = care enquiries, consultations, trust. Resource CPT and modal already in CCS (`ccs_resource`, `resource-download-modal.php`); keep structure, label public-facing as **Home care guides**; content = care guides, FAQs, referral info. **Accessibility:** Modal already has role="dialog", aria-modal, labelled close—retain; only tailor strings. |
| **CQC Hub (page template, sections)** | Use file; tailor content | CQC matters for trust; CTA used it for training/compliance. CCS needs "we're regulated and rated" reassurance, not a training hub. | **Copy/positioning:** Same page structure and sections; replace training/compliance copy with **CQC and our care** (registration, rating, how we're inspected). No course/CQC-training crossover. |
| **Group Training (page + nav slot)** | Use file; repurpose slot | CCS has no group training. The slot is high-value real estate for care enquiries. | **Information architecture:** Reuse template for a **care journey** page (e.g. "Getting started with care" / "How we work with you") or **Book a care consultation** / **Arrange a visit**. Nav label = consultation or care journey, not "Group Training". |
| **Course cards / "book training" CTAs** | Use structure; replace copy | Card layout and CTA pattern are reusable; "course" and "book training" are not. | **UI + copy:** Keep card component and CTA pattern; content = service cards, care FAQs, **Book consultation** / **Contact us**. No Eventbrite or training booking. |
| **About, Contact, Privacy, Terms, FAQs, News** | Use file; tailor copy | Standard pages; tone and content must be CCS (care, team, consultation, careers). | **Copywriting/tone:** Map 1:1; replace CTA/academy voice with CCS voice and Section 2/2b messaging from [CCS-THEME-AND-CONTENT-GUIDE.md](CCS-THEME-AND-CONTENT-GUIDE.md). |
| **Resource download modal (lead capture)** | Use as-is; tailor strings only | Form and behaviour (name, email, consent, email delivery) fit "email me this care guide". No structural change needed. | **Security (cc-skill-security-review):** Keep nonces, validation, escaping. **Accessibility (wcag-audit-patterns):** Already has required labels, aria-describedby, role="alert" on errors; only translate/tailor visible text (e.g. "Get this free resource" → "Get this care guide" if desired). |
| **Course events / Eventbrite** | Omit or repurpose | CCS does not sell training events. Optional: repurpose for "events for families" (e.g. info sessions) if product decision later. | **Scope:** Out of scope unless explicitly adopted; then treat as "events" not "courses". |
| **SEO, forms, breadcrumb, CQC section part** | Use file; tailor content | Technical and structural pieces are theme-agnostic; only labels and copy need to be CCS. | **wp-block-themes / theme conventions:** Migrate inc/ and template parts; replace any CTA/academy references in strings and schema with CCS name and care-focused copy. |
| **Inc: newsletter, AI, Eventbrite, Facebook Lead Ads** | Decide per feature | CCS may or may not need newsletter, AI, or ad integrations. | **Product/scope:** Per §4, omit CTA-only one-offs unless explicitly adopted. When in doubt, stub or skip; add in a later pass if required. |

**Summary:** Use CTA files and structure everywhere they serve a CCS goal; tailor only content, labels, and copy. Drop or stub CTA-only features (training, courses, Eventbrite) unless there is an explicit decision to repurpose them.
