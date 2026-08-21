# CCS WordPress Theme — Complete Build Brief

This file is the single source of truth for building the Continuity Care Services WordPress theme from scratch. Read this entire file before writing any code.

---

## THE RULES — NEVER BREAK THESE

- Classic PHP WordPress theme — NOT a block theme
- No ACF (Advanced Custom Fields) — zero `get_field()` calls anywhere, ever
- No Gutenberg-first patterns — classic editor preferred
- No build tools — no Webpack, no Vite, no npm build step, no Composer
- No plugins required for core behaviour — everything lives in the theme
- Vanilla PHP, CSS, and JS only
- Custom meta boxes coded by hand using `add_meta_box()` and `get_post_meta()`
- Text domain: `ccs-wp-theme`
- PHP 7.4+ compatible
- WordPress 5.9+ target
- Build the visual layer COMPLETELY before touching any backend wiring
- One section per prompt — never combine multiple sections in one build step

---

## THE BUSINESS

**Company:** Continuity Care Services (CCS)
**Type:** CQC-regulated domiciliary and complex care provider
**Location:** Maidstone, Kent
**Website:** https://www.continuitycareservices.co.uk
**Tagline:** Your Team, Your Time, Your Life

### Contact Details
- **Phone:** 01622 809881
- **Email:** office@continuitycareservices.co.uk
- **Address:** The Maidstone Studios, New Cut Road, Maidstone, Kent ME14 5NZ
- **CQC Registration:** 1-2624556588 (Rated: Good)
- **CQC Profile URL:** https://www.cqc.org.uk/location/1-2624556588
  - ⚠️ Must use `/location/` not `/provider/` in this URL

### Social Media
- **Facebook Messenger:** https://m.me/821174384562849
- **Instagram:** http://instagram.com/continuityofcareservices/
- **LinkedIn:** http://linkedin.com/company/continuitycareservices
- **Threads:** https://www.threads.com/@continuityofcareservices

### Team
> ⚠️ **Outdated as of 2026-08-19** — this roster and the "Who You'll Meet"
> spec below (line ~380) reflect an earlier team snapshot. Hani Ahmed and
> Zoe Commons have left CCS; do not use their names/photos anywhere on the
> site. Victoria Walker is now Registered Manager (not Owner/Managing
> Director) and Keelie Varney is now Care Manager (not Care Quality &
> Compliance Support Officer). Heidi Griffen (Field Care Supervisor) has
> joined and is not listed below. **Current roster lives in
> `docs/CCS-THEME-AND-CONTENT-GUIDE.md` — treat that as authoritative,
> this file is kept as historical reference only.**

- **Victoria Walker** — Owner / Managing Director
- **Nikki Mackay** — Senior Clinical Manager
- **Keelie Varney** — Care Quality & Compliance Support Officer
- **Trish Henley** — Finance Assistant
- **Amanda Carter** — General Manager
- **Shayna-Rae Fuller** — Field Care Supervisor
- **Danielle King** — Field Care Supervisor
- **Hani Ahmed** — Care Manager
- **Jennifer Boorman** — Complex Care Assistant & Lead Healthcare Trainer
- **Magdalena Zoledz** — Recruitment Coordinator
- **Zoe Commons** — Domiciliary Care Manager

### Consultation Form — Named Contacts
Keelie Varney and Nikki Mackay are the two named options on the consultation form's "With Whom?" field.

---

## BRAND & DESIGN SYSTEM

### Colours — CSS Custom Properties

```css
/* Primary — deep purple. ONE primary CTA per view only. Never for every heading. */
--color-primary: #564298;
--color-primary-light: #7B63B8;   /* hover states */
--color-primary-dark: #3F2F70;    /* active states, one hero heading only */

/* Secondary — light teal. Card backgrounds, section accents, secondary buttons. */
--color-secondary: #a8ddd4;
--color-secondary-light: #C5EAE4; /* soft backgrounds, hover */
--color-secondary-dark: #85C4B8;  /* button hover, borders, success */

/* Accent — use sparingly: bullets, tags, borders only. NOT body text. */
--color-accent: #9b8fb5;

/* Neutrals */
--color-background: #ffffff;        /* cards, panels, hero content boxes */
--color-background-warm: #f6f5ef;   /* cream — default page/section background */
--color-text: #2e2e2e;              /* all body text and headings */
--color-text-light: #666666;        /* secondary copy, captions */
--color-border: #E0E0E0;

/* Semantic */
--color-success: #85C4B8;
--color-warning: #D4A843;
--color-urgent: #C64B4B;
```

### The Single Most Important Colour Rule
> Purple `#564298` is reserved for **one** primary CTA per view. Teal and cream carry the visual weight. Section headings use `#2e2e2e` (charcoal). Only one hero-level heading may use `#3F2F70`.

### Typography
- **Heading font:** Poppins — weights 400, 500, 600, 700 (load from Google Fonts)
- **Body font:** Open Sans — weights 400, 500, 600, 700 (load from Google Fonts)

```css
--font-family-heading: "Poppins", -apple-system, sans-serif;
--font-family-body: "Open Sans", -apple-system, sans-serif;

/* Scale */
--text-xs: 0.875rem;   /* 14px — captions, labels */
--text-sm: 0.9375rem;  /* 15px — nav, footer, secondary */
--text-base: 1rem;     /* 16px — body default */
--text-md: 1.125rem;   /* 18px — lead paragraph */
--text-lg: 1.375rem;   /* 22px — H4, card titles */
--text-xl: 1.75rem;    /* 28px — H3 */
--text-2xl: 2.1875rem; /* 35px — H2 */
--text-3xl: 2.75rem;   /* 44px — H1 */
--text-4xl: 3rem;      /* 48px — hero headline */

/* Line heights */
--leading-tight: 1.2;
--leading-snug: 1.35;
--leading-normal: 1.5;
--leading-relaxed: 1.6;

/* Weights */
--font-weight-normal: 400;
--font-weight-medium: 500;
--font-weight-semibold: 600;
--font-weight-bold: 700;
```

### Spacing Scale (8px base)
```css
--space-2xs: 0.25rem;  /* 4px */
--space-xs: 0.5rem;    /* 8px */
--space-sm: 1rem;      /* 16px */
--space-md: 1.5rem;    /* 24px — paragraph margin, component internal */
--space-lg: 2rem;      /* 32px — between cards */
--space-xl: 3rem;      /* 48px — section padding */
--space-2xl: 4rem;     /* 64px — section separation */
--space-3xl: 6rem;     /* 96px — major section breaks */
```

### Shadows & Radius
```css
--shadow-subtle: 0 2px 8px rgba(46,46,46,0.06);     /* cards at rest */
--shadow-elevated: 0 4px 16px rgba(46,46,46,0.08);  /* hover, dropdowns */
--shadow-prominent: 0 8px 24px rgba(46,46,46,0.12); /* modals, sticky header */

--radius-sm: 6px;   /* tags, inputs */
--radius-md: 10px;  /* cards, buttons */
--radius-lg: 14px;  /* hero blocks, large cards */
--radius-xl: 20px;  /* feature panels */
```

### Focus & Motion
```css
--focus-ring-width: 2px;
--focus-ring-offset: 2px;
--focus-ring-color: var(--color-primary);
```
- All interactive elements must have `:focus-visible` with the focus ring
- Respect `prefers-reduced-motion: reduce` — set animation durations to 0

### WCAG 2.1 AA
- Normal text: minimum 4.5:1 contrast ratio
- Large text (18px+): minimum 3:1
- Touch targets: minimum 44×44px
- Body font size: minimum 16px, line-height minimum 1.5 (prefer 1.6)

---

## THEME FILE STRUCTURE

```
ccs-wp-theme/
├── style.css                          ← Theme declaration
├── functions.php                      ← Bootstrap: constants, autoloader, require inc files
├── index.php
├── header.php                         ← Full header with careers context-switching
├── footer.php                         ← Footer with back-to-top
├── page.php
├── search.php
├── single-service.php                 ← Single service template
├── single-location.php                ← Single location template
├── assets/
│   ├── css/
│   │   ├── design-system.css          ← All CSS tokens (build this first)
│   │   ├── header.css
│   │   ├── homepage.css
│   │   ├── components.css
│   │   ├── service-page.css
│   │   ├── location-page.css
│   │   ├── contact-page.css
│   │   ├── footer.css
│   │   ├── responsive.css
│   │   └── critical.css               ← Above-fold CSS for inlining
│   └── js/
│       ├── navigation.js              ← Mobile menu, submenus, ESC, scroll lock
│       ├── consultation-form.js       ← AJAX form handler
│       ├── form-handler.js            ← Generic AJAX forms
│       ├── faq-accordion.js
│       └── analytics-events.js
├── template-parts/
│   ├── home/
│   │   ├── hero.php
│   │   ├── why-choose-us.php
│   │   ├── scenarios.php
│   │   ├── services.php
│   │   ├── cqc-section.php
│   │   ├── testimonial.php
│   │   ├── differentiators.php
│   │   ├── info-cards.php
│   │   └── partnerships.php
│   ├── careers/
│   │   └── cv-minder-embed.php
│   ├── breadcrumb.php
│   ├── resource-download-modal.php
│   └── resource-unavailable-modal.php
├── page-templates/
│   ├── template-homepage.php
│   ├── template-contact.php
│   ├── template-about.php
│   ├── template-careers.php
│   ├── template-current-vacancies.php
│   ├── template-care-guides.php
│   ├── template-faqs.php
│   ├── template-getting-started.php
│   ├── template-cqc.php
│   ├── template-content-page.php      ← Used for Privacy, Terms, Accessibility, Cookies
│   └── template-unsubscribe.php
└── inc/
    ├── class-autoloader.php
    ├── class-theme-activation.php     ← Auto-creates all pages on activation
    ├── class-contact-form.php         ← Consultation form shortcode + AJAX
    ├── class-security.php
    ├── theme-setup.php                ← Theme supports, menus, enqueue
    ├── header-footer-helpers.php      ← ccs_get_contact_info(), menu fallbacks
    ├── page-editor-enhancements.php
    ├── resource-downloads.php
    ├── resource-download-ajax.php
    ├── post-types/
    │   └── class-register-post-types.php
    ├── taxonomies/
    │   └── class-register-taxonomies.php
    ├── custom-fields/
    │   ├── class-meta-box-base.php
    │   ├── class-service-meta-box.php
    │   ├── class-location-meta-box.php
    │   └── class-enquiry-meta-box.php
    ├── customizer/
    │   └── class-theme-customizer.php
    ├── seo/
    │   ├── class-seo-optimizer.php
    │   └── class-structured-data.php
    └── admin/
        ├── class-welcome-screen.php   ← WP Admin: Appearance → CCS Theme Setup
        └── class-enquiry-manager.php
```

---

## CUSTOM POST TYPES

### `service` — Care services
- Public, has archive, REST enabled
- URL slug: `/services/`
- Used for: Domiciliary Care, Respite Care, Complex Care

### `location` — Location pages
- Public, has archive, REST enabled
- URL slug: `/locations/`

### `ccs_enquiry` — Form submissions
- Private (`show_in_rest: false`), admin-only
- Created automatically on consultation form submit
- Menu icon: dashicons-email-alt

### `testimonial`
- Public, REST enabled

### Taxonomies
- `service_category` — on service CPT
- `condition` — on service CPT (e.g. dementia, epilepsy, MS)
- `location_area` — on location CPT

---

## META FIELD NAMES

All fields use vanilla `get_post_meta()`. These are the exact key names — never change them.

### Service post meta
```
service_short_description   — text, max 200 chars
service_icon                — Dashicon class string e.g. dashicons-heart
service_setup_time          — text e.g. "Usually 24-48 hours"
service_urgent              — "1" or "0" — if 1, shows "Call now" CTA
service_price_from          — decimal (£)
service_price_to            — decimal (£)
service_typical_hours       — text e.g. "1-2 hours per visit"
service_funding_options     — textarea
service_features            — serialised array of {feature_text, feature_icon}
service_faqs                — serialised array of {question, answer}
service_seo_title           — text
service_meta_description    — text, max 160 chars
```

### Location post meta
```
location_town                     — text e.g. "Maidstone"
location_county                   — text, default "Kent"
location_postcode_area            — text e.g. "ME"
location_areas_covered            — textarea, one area per line
location_latitude                 — decimal
location_longitude                — decimal
location_local_hospitals          — array of {hospital_name, hospital_phone, hospital_address}
location_local_gp_practices       — textarea
location_chc_contact              — textarea (Continuing Healthcare contact)
location_council_adult_services   — textarea
location_local_support_groups     — array of {group_name, group_contact}
location_team_size                — integer
location_coordinator_name         — text
location_coordinator_photo        — attachment ID (integer)
location_families_supported       — integer
location_population_65_plus       — integer (optional)
```

---

## PAGE STRUCTURE & URLS

All pages are auto-created by `class-theme-activation.php` on theme activation.

| Page | URL | Template |
|------|-----|----------|
| Home (front page) | `/` | template-homepage.php |
| About Home Care Maidstone | `/home/about-home-care-maidstone/` | template-about.php |
| Home Care Services Kent | `/home/home-care-services-kent/` | (default) |
| Who You'll Meet | `/home/who-youll-meet/` | (default) |
| Care Careers Maidstone Kent | `/home/care-careers-maidstone-kent/` | (default) |
| Contact Us | `/home/contact-us/` | template-contact.php |
| Resources | `/home/resources/` | (default, parent) |
| Care Guides | `/home/resources/care-guides/` | template-care-guides.php |
| FAQs | `/home/resources/faqs/` | template-faqs.php |
| Referral Information | `/home/resources/referral-information/` | (default) |
| News & Updates | `/home/news-and-updates/` | (set as Posts page) |
| CQC and Our Care | `/home/cqc-and-our-care/` | template-cqc.php |
| Getting Started | `/home/getting-started/` | template-getting-started.php |
| Careers | `/careers/` | template-careers.php |
| Professional Development | `/careers/professional-development/` | (default) |
| Current Vacancies | `/careers/current-vacancies/` | template-current-vacancies.php |
| Working for Us | `/careers/working-for-us/` | (default) |
| Privacy Policy | `/privacy-policy/` | template-content-page.php |
| Terms & Conditions | `/terms-and-conditions/` | template-content-page.php |
| Accessibility Statement | `/accessibility-statement/` | template-content-page.php |
| Cookie Policy | `/cookies/` | template-content-page.php |

---

## WHO YOU'LL MEET PAGE — CONTENT SPEC

**URL:** `/home/who-youll-meet/`
**Template:** `template-about.php` (or default page template with custom sections)

**Why this page matters:** Research across the top-performing care sites (Alliance Homecare, nanoglobals.com 2026 review) identifies real staff photos as the single biggest differentiator. Alliance Homecare shows 16+ headshots with LinkedIn links and scores the highest trust rating of any site in the review. Senior Helpers and HomeWell show zero staff photos and score lowest. The gap is visible in five seconds.

A family deciding whether to let a stranger into their parent's home will look at this page. It must show real people with real names. Generic team sections lose clients.

**Page heading:** `Meet the People Behind Your Care`
**Subheading:** `We believe the best care starts with knowing who is walking through your door.`

**Page intro copy:**
`Every person on our team is here because they genuinely care — not just about ticking the boxes, but about the people behind each care plan. Get to know us.`

**Team grid spec:**
- Show ALL named team members from the brief as cards
- Each card: photo (required — placeholder if not yet provided), name, job title, short 1–2 sentence bio
- Cards use `--radius-lg`, `--shadow-subtle`
- On mobile: 1 column. Tablet: 2 columns. Desktop: 3 columns. Gap: `--grid-gap-cards`

**Team members to display (in this order):**

| Name | Title | Notes |
|------|-------|-------|
| Victoria Walker | Owner & Managing Director | Lead card, slightly larger or featured position |
| Nikki Mackay | Senior Clinical Manager | Named on consultation form — critical to show |
| Amanda Carter | General Manager | |
| Hani Ahmed | Care Manager | |
| Zoe Commons | Domiciliary Care Manager | |
| Keelie Varney | Care Quality & Compliance Support Officer | Named on consultation form — critical to show |
| Jennifer Boorman | Complex Care Assistant & Lead Healthcare Trainer | |
| Shayna-Rae Fuller | Field Care Supervisor | |
| Danielle King | Field Care Supervisor | |
| Magdalena Zoledz | Recruitment Coordinator | |
| Trish Henley | Finance Assistant | |

**Photo guidance:** Real photographs only. No stock imagery. If professional photos are not yet available, use a warm placeholder with the person's initials styled using the CCS palette. Never leave a blank image space — that reads as anonymity, which is the exact problem this page solves.

**CTA at bottom of page:** `Ready to meet your care team in person?` → `Book a free consultation` (purple button)

### WordPress Reading Settings
- Homepage displays: A static page
- Homepage: the "Home" page
- Posts page: the "News & Updates" page
- Permalinks: Post name `/%postname%/`

### WordPress General Settings
- Site Title: `Continuity Care Services`
- Tagline: `Home Care in Maidstone & Kent - Your Team, Your Time, Your Life`
- Email: `office@continuitycareservices.co.uk`

---

## MENUS

### Primary Menu (shows on all care pages)
```
Home
About Us          → /home/about-home-care-maidstone/
Our Services      → /home/home-care-services-kent/
Who You'll Meet   → /home/who-youll-meet/
Careers           → /careers/
Resources         → (dropdown parent)
  ↳ Care Guides   → /home/resources/care-guides/
  ↳ FAQs          → /home/resources/faqs/
  ↳ Referral Information → /home/resources/referral-information/
News & Updates    → /home/news-and-updates/
Contact Us        → /home/contact-us/
```

### Careers Menu (shows only when on /careers/ or any child page)
```
Careers Home          → /careers/
Professional Development → /careers/professional-development/
Current Vacancies     → /careers/current-vacancies/
Working for Us        → /careers/working-for-us/
```
The header detects careers context by checking if the current page is `/careers/` or any descendant, then switches `theme_location` from `primary` to `careers`.

### Footer Menu
About Us | Services | Careers | FAQs | Contact Us | Privacy Policy | Terms & Conditions | Cookie Policy | Accessibility Statement

---

## HOMEPAGE — EXACT COPY

### Hero Section

**Visual hierarchy plan (build to this, not against it):**
- Primary focal point: H1 headline + primary CTA button. Everything else supports them.
- What breathes: the right column image, the space between headline and CTA row, padding around the stats strip.
- What's dense (deliberately): the trust badge row below the CTAs — three compact items that pre-empt doubt.
- Typographic relationships: H1 at `--text-4xl`, description at `--text-md`, badge text at `--text-xs`.
- The stats row numbers use `--text-stat` (60–72px fluid). They are data, not headings — they must read as numbers at a glance, not as part of the prose hierarchy.

**The audience this section is written for:** The person landing here is most likely an adult child — a son or daughter who is scared, exhausted, and making this decision under pressure. They are not the person who will receive care. Write to their fear of making the wrong choice, not to the clinical needs of the care recipient. Every word in this hero must answer "Can I trust these people with my parent?"

- **H1:** `Home Care in Maidstone & Kent — Your Team, Your Time, Your Life`
- **H2:** `Trusted Home Care Services in Maidstone & Kent`
- **Description:** `Compassionately supporting children and adults with domiciliary, disability, respite, complex, and palliative care, day or night, 24/7.`
- **Primary CTA (purple button):** `Explore Our Services` → `/home/home-care-services-kent/`
- **Secondary CTA (teal outline button):** `Explore Career Paths` → `/home/care-careers-maidstone-kent/`
- **Trust badge strip (below CTAs, inline row):** `CQC Regulated & Rated Good` | `Based in Maidstone, Kent` | `Available 24/7`
  - Each badge has a small icon/dot. This strip is load-bearing trust infrastructure — do not omit it or move it below the fold.
- **Stats row (three stats, rendered with `--text-stat` numbers):**
  1. `15+` / `Years of experience`  (editable via Customizer: `ccs_hero_stat_value`)
  2. `CQC` / `Rated Good`
  3. `24/7` / `Care available`
  - Stats are displayed in a horizontal strip. On mobile, they stack 1×3 or wrap to 3-across compact. Each stat uses `--text-stat` for the value and `--text-sm` for the label.
- **Layout:** Two-column grid — left column: text content (H1, description, CTA row, trust badges), right column: hero image with stats row overlaid at the bottom or rendered below the image on mobile.
- **Image guidance:** Show a real human interaction — a carer with a client or family. Warm, candid, not staged. Never a stock photo of a nurse pointing at a clipboard.

### Why Choose Us Section
- **Heading:** `Why Choose Us for Home Care in Maidstone?`
- **Subheading:** `It's not just what we do, it's how we do it.`
- **Body:** `Reliably supporting adults and children across Maidstone and Kent, we're here to provide personalised care, day or night, tailored to you. Our caring, local team is dedicated to supporting families across Kent. We don't rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day. We believe that the best care doesn't stop when the to-do list is ticked; it continues through our staff showing up in a way that feels friendly, familiar, and person-centred. Learn more about the home care services we offer in Maidstone & Kent.`
- **Link:** `Learn more about our services` → `/home/home-care-services-kent/`

### CQC Section
- **Heading:** `Regulated, rated, and reliable home care across Maidstone & Kent`
- **Subheading:** `Proud to be rated 'Good' by the CQC`
- **Link:** `View our CQC profile` → `https://www.cqc.org.uk/location/1-2624556588`
- CQC JS widget: `<script src="https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=1-2624556588&data-host=https://www.cqc.org.uk&type=location"></script>`
- Widget can be hidden via Customizer toggle `ccs_cqc_widget_hide`

### Services / Care Options Section
- **Small heading:** `Beginning your home care journey`
- **Main heading:** `Explore Your Care Options`
- **Body:** `Whether you need a little help dressing in the mornings, round-the-clock complex care, or just someone to pop in for a cuppa and a catch-up, we're here to make life feel a little lighter. For expert home care Maidstone families trust, get in touch today, and we'll create a plan tailored to your needs.`
- Three service cards: Domiciliary Care, Respite Care, Complex Care
- This section should try to query the `service` CPT by slug first. If no posts exist yet, use hardcoded fallback copy.
- **Bottom CTA:** `Book a care consultation` → `/home/contact-us/`

### How We Can Help / Scenarios Section
Three scenario cards:
1. **Hospital discharge** (urgent badge) — `Need support straight after a hospital stay? We coordinate with the hospital and your GP so you can return home safely with a clear care plan.`
2. **24/7 care at home** — `Round-the-clock care in your own home. We support people with complex needs, dementia, or those who simply feel safer with someone there day and night.`
3. **Comparing care options** — `Not sure whether you need care at home, respite, or something else? We help you understand your options and what might work best for you and your family.`

### Testimonial Section
> "I am delighted to express my gratitude for the outstanding care CCS delivered to my paraplegic father, requiring complex care. The skilled and compassionate team went above and beyond, addressing his unique needs with unwavering dedication. Their expertise and empathy transformed what could have been a challenging situation into a positive and reassuring experience for him and his family."
>
> — Claire Pitchford

### Partnerships Section
- **Heading:** `Our Local & National Partnerships`
- **Subheading:** `We're proud to collaborate with local, regional, and national organisations to enhance care and opportunities for our clients and staff.`
- **11 partners:** National Care Association | Disability Confident Committed | CV Minder | MidKent College | Kent Integrated Care Alliance (KiCA) | Care Quality Commission (CQC) | iTrust | NHS | Homecare Association | Brain Injury Group | Continuity Training Academy

### Info Cards Section (three cards)
1. **Our Care Approach** — Subheading: "About Us". Body: `Compassionate care, tailored to you. We're dedicated to supporting your independence, dignity, and wellbeing, by delivering trusted care services with a personal touch. Discover how our team makes a difference every day.` CTA: `Learn More` → `/home/about-home-care-maidstone/`
2. **Home Care FAQs** — Subheading: "FAQs". Body: `Have questions about our home care in Maidstone & Kent? Find answers about our services, care plans, and what to expect. If you're finding the care search overwhelming, or need more information, our team is just a call away.` CTA: `Get Answers` → `/home/resources/faqs/`
3. **Care Careers in Kent** — Subheading: "Careers". Body: `Make a real impact by joining our team. Offering rewarding roles, flexible hours, and ongoing training, we'd love to hear from you. If you're passionate about helping others, explore how you can grow your career with us.` CTA: `Explore Roles` → `/home/care-careers-maidstone-kent/`

---

## SERVICE POSTS

Three posts to create in WP Admin once theme is active.

### Domiciliary Care
- **Slug:** `domiciliary-care`
- **Excerpt:** `Getting dressed. Making breakfast. Remembering the right meds at the right time. Our carers provide gentle assistance with everyday tasks, ensuring care calls are always scheduled to fit your daily routine.`
- `service_urgent`: 0

### Respite Care
- **Slug:** `respite-care`
- **Excerpt:** `Whether it's for a few hours or a few days, our team are here to step in and provide a client's family and friends with gentle, reliable respite support. Take some time to rest, you can't pour from an empty cup.`
- `service_urgent`: 0

### Complex Care
- **Slug:** `complex-care`
- **Excerpt:** `From epilepsy care to PEG and mobility support, we provide complex care in the comfort of your own home. We work closely with families, nurses and healthcare teams to ensure we get it right, every time.`
- `service_urgent`: 0

---

## CONSULTATION FORM

Built into theme as class `CCS_Contact_Form`. Shortcode: `[ccs_consultation_form]`.
- AJAX submit (no page reload)
- Rate limited: 5 submissions per 10 minutes per IP
- Honeypot field: `company_website` (hidden from users, traps bots)
- Nonce secured
- Stores to `ccs_enquiry` CPT
- Sends HTML email notification

### Fields
| Field | Type | Required |
|-------|------|----------|
| Your Name | text | Yes |
| Your Phone Number | tel | Yes |
| Your Email | email | Yes |
| Select Service | multi-select | No — options: Domiciliary Care, Respite Care, Complex Care, I'm Not Sure |
| With Whom? | select | No — options: Anyone, Keelie Varney, Nikki Mackay |
| Preferred Date | date | No |
| Preferred Time | time | No |
| Any Information You'd Like Us To Know? | textarea | No |
| I consent to CCS storing my details to respond to this enquiry | checkbox | Yes |
| I agree to CCS storing my details to send me newsletters and updates | checkbox | No, default on |
| reCAPTCHA | — | Yes |
| Submit button | — | Label: "Send Request" |

### Contact page heading & description
- **H1:** `Book Your Free Care Consultation`
- **Description:** `Please let us know what you'd like to discuss below, whether you're seeking home care in Maidstone or across Kent, and we'll get in touch to arrange a call or visit. We can't promise we'll be able to make your exact date, but we'll use this as a rough guideline on when to get in touch!`

---

## THIRD-PARTY INTEGRATIONS

### CV Minder (job listings)
Embedded as an iframe on the Current Vacancies page (`template-current-vacancies.php`).
⚠️ **NEVER change this URL:**
```
https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713
```

### CQC Widget
JavaScript embed from cqc.org.uk. `data-id="1-2624556588"`. Appears on homepage and `/home/cqc-and-our-care/`. Can be hidden via Customizer toggle `ccs_cqc_widget_hide`.

### Customizer Settings (configured after activation)
```
ccs_phone                → 01622 809881
ccs_contact_email        → office@continuitycareservices.co.uk
ccs_contact_address      → The Maidstone Studios, New Cut Road, Maidstone, Kent ME14 5NZ
ccs_office_hours         → Mon-Fri 9am-5pm
ccs_cqc_url              → https://www.cqc.org.uk/location/1-2624556588
ccs_cqc_widget_data_id   → 1-2624556588
ccs_facebook_url         → https://m.me/821174384562849
ccs_linkedin_url         → http://linkedin.com/company/continuitycareservices
ccs_hero_stat_value      → 15
```

---

## HEADER BEHAVIOUR

The header has two nav contexts:

1. **Primary nav** — shown on all care/general pages
2. **Careers nav** — shown only when on `/careers/` or any child page

Detection logic:
- Check if current page ID is in the `ccs_careers_page_ids` option (stored on activation)
- OR check if any ancestor page has the slug `careers`
- If either is true: `theme_location = 'careers'`
- Otherwise: `theme_location = 'primary'`

### Header structure
- **Top bar** (cream background `#f6f5ef`): CQC badge left, phone number right
- **Main header**: logo left, nav centre, "Book a care consultation" CTA button right (purple)
- Sticky on scroll
- Mobile hamburger menu at 1024px breakpoint
- Mobile menu uses `hidden` HTML attribute (not CSS display:none) — accessibility correct
- Emergency banner (optional, Customizer toggle): appears above top bar

---

## BRAND VOICE

- Warm and compassionate — not clinical
- Personal and approachable — like a knowledgeable neighbour, not a corporate brochure
- Honest — e.g. "We can't promise we'll be able to make your exact date"
- Uses plain English — no jargon
- Uses specific, human details — e.g. "from how they like their toast to what puts them at ease on a tough day"

### Key phrases (use consistently)
- "Your Team, Your Time, Your Life"
- "It's not just what we do, it's how we do it"
- "Home care Maidstone families trust"
- "Compassionately supporting children and adults"
- "Day or night, 24/7"
- "Friendly, familiar, and person-centred"
- "We're here to make life feel a little lighter"

### Target audiences

**Audience hierarchy matters. Write in this order:**

1. **Adult children making care decisions** — The primary conversion audience. They are not the person who will receive care. They are scared of making the wrong choice, under time pressure (often triggered by a hospital discharge or a fall), and comparing multiple providers in a single session. Every headline, every CTA, every piece of body copy should pass this test: *does this reduce the fear of an exhausted adult child at 11pm trying to find care for their parent?*
2. **The person seeking care for themselves** — Secondary audience. Often older, may be browsing on a phone with low digital confidence. Needs large text, clear language, visible phone number, and no jargon.
3. **Professionals making referrals** (GPs, discharge planners, social workers) — Want credentials, CQC status, response times, and a direct contact. The CQC section and a clear professional referral path serves them.
4. **Care workers seeking jobs** — Served by the Careers section and the context-switching navigation. Do not conflate this audience with care-seekers in shared sections.

---

## SEO

- **Homepage title:** `Home Care Maidstone | Personalised Home Care Service in Kent`
- **Primary keywords:** Home Care Maidstone, Home Care Kent, Domiciliary Care Maidstone, Complex Care Kent, Respite Care Maidstone
- **Long-tail:** "expert home care Maidstone families trust", "home care services in Maidstone & Kent"
- Schema.org JSON-LD on all key pages: Organization, WebSite, LocalBusiness, Service, FAQPage, BreadcrumbList

### Condition-Based SEO — Priority Pages to Build

Research (nanoglobals.com, ComForCare case study) shows that families search by condition and situation, not by service category. "Home care Maidstone" is their first search; "dementia care at home Maidstone" or "care after hospital discharge Kent" is their second. The second search converts at higher rates because intent is specific.

The `condition` taxonomy on the `service` CPT supports this. Each condition should eventually have its own dedicated page. Priority conditions to build first:

| Condition | Target URL | Search intent |
|-----------|-----------|---------------|
| Dementia / Alzheimer's | `/services/dementia-care-maidstone/` | "dementia home care Maidstone" |
| Post-hospital discharge | `/services/hospital-discharge-care-kent/` | "care after hospital stay Kent" |
| Epilepsy | `/services/epilepsy-care-at-home/` | "epilepsy support at home Kent" |
| Palliative / end of life | `/services/palliative-care-maidstone/` | "end of life care at home Maidstone" |
| Physical disability | `/services/disability-care-kent/` | "care for disabled adults Kent" |

Each condition page should follow the buyer's journey structure: emotional hook (acknowledge the situation) → what this means practically → how CCS handles it → CQC/team proof → CTA.

The `service_faqs` meta field on each service post is the right place for condition-specific FAQ content. These generate FAQPage schema and capture long-tail voice search queries.

### Content depth target

Top-converting care sites (Comfort Keepers: 276 articles, ComForCare: 285+) invest heavily in structured educational content. CCS doesn't need to match that overnight, but the blog/care-guides section should be treated as an SEO asset from day one. Priority topics:

1. "What to expect from a home care assessment"
2. "How to talk to your parent about getting help at home"
3. "What does CQC 'Good' mean for home care?"
4. "Domiciliary vs complex care — what's the difference?"
5. "How hospital discharge care works in Kent"

---

## CONVERSION ARCHITECTURE

The homepage is not a brochure. It is a structured argument that moves a frightened decision-maker from "I found a care website" to "I'm booking a consultation." Every section has a job.

### The buyer's journey this homepage must mirror

1. **Emotional hook** (Hero) — Speak directly to the person making this decision. Acknowledge the situation. Offer reassurance immediately.
2. **Problem identification** (Why Choose Us) — Validate the worry: rotating carers, impersonal service, not being heard. Then explain how CCS is different.
3. **Solution validation** (Services + Scenarios) — Show them you understand their specific situation. Not "we do domiciliary care" but "if your parent just came out of hospital, here's what we do."
4. **Proof** (CQC section + Testimonial) — Third-party evidence. CQC "Good" is the single most powerful trust signal CCS has. A named testimonial from a real family member reinforces it.
5. **Conversion** (Info Cards + footer CTA) — After the proof is established, offer the next step. Not before.

### Rules that must not be broken

- **CTA placement:** A purple "Book a care consultation" CTA must appear after the proof sections (at minimum after the testimonial). Do not stack two purple CTAs within the same visible viewport.
- **Testimonials are not decoration:** Claire Pitchford's quote is positioned after Services and Scenarios — where doubt would have formed — on purpose. Do not move it higher to "add warmth." Its position IS its function.
- **The Scenarios section is conversion-critical:** Families search by situation, not by service category. "Need support after a hospital stay" converts better than "Domiciliary Care." The three scenarios must use human language, not clinical terminology. The urgent badge on hospital-discharge is intentional — it signals immediate response capability.
- **Stats must be front-loaded:** Research across the top-performing care sites shows that data-driven numbers (15+ years, CQC rated, 24/7) placed early reduce the number of questions a visitor needs answered before converting. They do not belong at the bottom.
- **Real people, not abstractions:** Every section that mentions the team (Who You'll Meet, Why Choose Us) must move toward showing faces and names. The gap between "we are a caring team" (generic) and "Victoria Walker, Managing Director, has been in care for 15+ years" (specific) is the gap between a visitor bouncing and a visitor booking.

### Named care approach — "The CCS Way"

Top-converting care sites trademark a methodology (The Grandma Rule, DementiaWise, LIFE Profile Assessment). CCS has a natural candidate embedded in the existing brand voice:

> *"It's not just what we do, it's how we do it."*

This should be developed into a named, repeatable idea across the site — referenced on the About page, the Why Choose Us section, and in the consultation form flow. Suggested name: **"The CCS Commitment"** or **"Person-First Care"**. Final name to be confirmed with the client. Until then, the phrase "It's not just what we do, it's how we do it" is the anchor.

---

## BUILD ORDER

Follow this order strictly. Never skip ahead.

1. `assets/css/design-system.css` — tokens only, no component styles
2. `header.php` + `assets/css/header.css` — visual only, hardcoded contact details
3. `template-parts/home/hero.php` + homepage hero CSS
4. Each homepage section one at a time (why-choose-us → cqc-section → services → scenarios → testimonial → partnerships → info-cards)
5. `footer.php` + `assets/css/footer.css`
6. `assets/css/components.css` — buttons, cards, forms, badges
7. `assets/css/responsive.css`
8. **Only once visual layer is complete:** wire `functions.php`, `inc/theme-setup.php`, post types, taxonomies, meta boxes
9. `inc/class-theme-activation.php` — auto-create pages
10. `inc/class-contact-form.php` — consultation form
11. Single templates: `single-service.php`, `single-location.php`
12. Page templates one at a time

---

## FACTS THAT MUST NEVER BE WRONG

- Phone: **01622 809881** (not 01622 689 047 — that's an old incorrect number)
- CQC URL: **https://www.cqc.org.uk/location/1-2624556588** — `/location/` not `/provider/`
- CV Minder URL: **https://cvminder.com/jobportal/index.php?gid=60&pk=2347289374823605326759060200713** — never change
- Text domain: **ccs-wp-theme**
- Purple = one primary CTA per view only
- No ACF anywhere
- No build tools
- Homepage services section works with OR without service posts in the database (smart fallback)
- The `run_with_scope()` activation function must be included — it auto-creates all pages on activation
