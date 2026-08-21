# Build Log — CCS Home Care Site Finish

Historical record of the build session that took the Care + Careers site from
scaffolding to a verifiable, production-ready state. Consolidated from three
separate session-tracking files (`task_plan.md`, `progress.md`, `findings.md`)
that were kept in sync during the work; merged here as one readable log.

**Session:** 2025-02-17
**Goal:** Deliver a production-ready dual-purpose site (Care + Careers) with all
content, all UI, CQC and CV Minder widgets, populate options, and Classic
editor; frontend visual appeal optimized.
**Status:** All 5 phases complete. Manual QA in WP admin (Phase 5 checklist
below) still needs to be run/confirmed in a live environment.

---

## Requirements

- Dual-purpose site: Care (home, about, services, contact, resources) + Careers
  (hub, professional development, current vacancies, working for us).
- All content lives in the theme (e.g. `get_default_page_content()`); "populate"
  creates full copy, not placeholders.
- All UI built and styled; no placeholder layout.
- Classic editor preferred in WP admin; page content in `post_content`.
- Single image per page: Featured Image is fine; multiple images go in page
  content only.
- CQC widget on home + CQC page; CV Minder embed on Current Vacancies page.
- Populate options: pages, services, menus, entire site; Reset includes careers.

---

## Decisions made (with rationale)

| Decision | Rationale |
|----------|-----------|
| Classic editor for all pages | User preference; simpler editing of copy and images. |
| `run_with_scope()` for activation | Enables granular populate actions from the Welcome Screen (pages / services / menus / entire site independently). |
| `careers_page_ids` option | Lets the header switch to the careers menu when on the careers subtree. |
| Design tokens in `design-system.css` | Single source for visual appeal; Poppins/Open Sans, purple/mint palette. |
| Multi-image pages: images in content, not Featured Image | Each image stays assignable and editable in Classic editor. |

---

## Phase log

### Phase 1 — Admin populate scope and careers structure — complete

Refactored activation to `run_with_scope()`; persisted `page_ids` and
`careers_page_ids`; added careers pages and a Careers menu; header switches to
careers nav on careers pages; Welcome Screen gained four populate actions
(pages, services, menus, entire site) with nonces and success messages; Reset
now deletes careers pages/menu and re-creates them; updated reset confirm text.

**Files:** `inc/class-theme-activation.php`, `inc/theme-setup.php`,
`header.php`, `inc/admin/class-welcome-screen.php`

### Phase 2 — Services, widgets, content — complete

Customizer CQC/CV Minder options; CQC widget in `cqc-section` and
`template-cqc`; CV Minder embed in `template-current-vacancies`; careers hub
template; three services with full copy; optional CQC and Getting Started
pages with default content; `get_default_page_content()` expanded for
`cqc-and-our-care`, `getting-started`, FAQs (5–8 Q&As), resources, care-guides,
referral-information; news-and-updates intro; full legal text for
privacy-policy, terms-and-conditions, accessibility-statement, cookies (UK
home care provider, no placeholders).

**Files:** `inc/class-theme-activation.php`,
`inc/customizer/class-theme-customizer.php`,
`template-parts/home/cqc-section.php`, `page-templates/template-cqc.php`,
`template-current-vacancies.php`, `template-careers.php`,
`template-getting-started.php`

### Phase 3 — Schema, SEO, legal — complete

Added `get_faq_page_schema()` in `class-structured-data.php` for the FAQs page
(H2/P parsing); wired the FAQs template to output `FAQPage` schema. Removed
duplicate structured data from `class-seo-optimizer.php`
(`output_structured_data` action) so only `class-structured-data.php` outputs
JSON-LD. Confirmed careers pages get minimal schema (Organization +
BreadcrumbList); no JobPosting.

**Files:** `inc/seo/class-structured-data.php`, `inc/seo/class-seo-optimizer.php`

### Phase 4 — UI/UX and visual appeal — complete

Applied the design system site-wide via a `ds-root` body class
(`inc/theme-setup.php`). Aligned footer with design tokens:
`.site-footer-modern`, `.footer-modern-container`, top/grid/brand/heading,
`.footer-modern-links`, `.footer-modern-link`,
`.footer-modern-social-list a`, `.footer-modern-bottom`,
`.footer-modern-copyright` all use `--color-*`, `--space-*`, `--text-*`,
`--font-*`, `--focus-ring`; `.back-to-top` uses `--color-primary`,
`--color-primary-light`, `--shadow-elevated`, `--shadow-prominent`, spacing
and focus tokens. Responsive footer block uses `--space-*` tokens. Responsive
behaviour and WCAG touch targets/focus-visible already lived in
`responsive.css` and `design-system.css`.

**Files:** `inc/theme-setup.php` (`ccs_body_class_ds_root`), `assets/css/footer.css`

### Phase 5 — Verification and delivery — complete (docs); manual QA outstanding

Documented the Phase 5 verification checklist below. `.back-to-top` updated to
use `var(--color-background)` and spacing/typography tokens on mobile.
Codebase considered ready for manual QA in WP admin — that manual pass had not
been run as of this log.

**Files:** `assets/css/footer.css` (plus this log, replacing the three
session-tracking files it supersedes)

---

## Phase 5 verification checklist (run manually in WP admin)

1. **Populate:** Appearance → CCS Theme Setup → "Populate entire site".
   Confirm all care + careers pages are created; Primary, Footer, Careers
   menus assigned; Reading and permalinks set.
2. **URLs:** Open every URL: home, about, services listing, each service
   single, contact, resources, care-guides, faqs, referral-information,
   news-and-updates; careers hub, professional-development,
   current-vacancies, working-for-us; legal (privacy, terms, accessibility,
   cookies); optional cqc-and-our-care, getting-started. Each should have
   full content, no Lorem ipsum.
3. **Classic editor:** Edit a multi-section page (e.g. About); confirm
   content and image placeholders live in `post_content` and are editable in
   Classic editor.
4. **CQC widget:** Homepage CQC section and CQC page (if created) show the
   CQC rating script; Customizer "CQC widget data-id" / "Hide CQC widget"
   work if implemented.
5. **CV Minder:** Current Vacancies page shows the CV Minder embed; Customizer
   override works if implemented.
6. **Reset:** "Reset Demo Content" → then "Populate entire site" again;
   careers + care content recreated, menus restored.
7. **Nav:** From home, Primary menu shows a Careers link → `/careers/`; on
   `/careers/` or a child page, header shows the Careers menu; back on a care
   page, Primary menu returns.
8. **Responsive:** Spot-check home, about, one service, contact, careers hub,
   current vacancies at 375px, 768px, 1440px — one cohesive look, touch
   targets and focus visible throughout.

### Test results (not yet run/recorded)

| Test | Input | Expected | Actual | Status |
|------|-------|----------|--------|--------|
| Populate entire site | Welcome Screen action | All pages, services, menus created | — | Not run |
| Reset Demo Content | Welcome Screen action | Careers + care recreated | — | Not run |
| Classic editor content | Edit any page | Copy and images editable in Classic editor | — | Not run |
| Visual check | 375 / 768 / 1440px | Cohesive look; no gaps | — | Not run |

---

## Decision log — positioning reframe (resolved 2026-08-19)

A Notion task from 2026-07-29 ("Reframe CCS positioning — from DOM care to
complex/nurse-led care specialism") proposed making Complex & Continuing Care
the site's hero service instead of domiciliary/personal care, on the
reasoning that it faces less competition and pays higher hourly rates. As of
this consolidation, nothing anywhere (Notion's own Sitemap & Build Tracker
database included) had acted on this — every doc still reflected the old
domiciliary-led framing, with Complex & Continuing Care tagged P3 (Later) in
`reference/sitemap-urls.md`.

**Decision:** neither service is centred. All care types (domiciliary,
complex, and the rest) get equal weight — no hero service, no featured card.
**Rationale:** reflects what CCS actually does rather than picking a
service to lead with for competitive/commercial reasons alone.
**Changes made:** `reference/sitemap-urls.md` — Complex & Continuing Care
moved from P3 to P1 (same tier as Personal Care); `reference/copy/homepage.md`
— service grid explicitly marked as an unranked list, VERIFY flag cleared.

**Also decided:** service area broadened from the original three villages
(West Malling, Aylesford, Snodland) to cover Kent more widely — Maidstone,
Tonbridge, Tunbridge Wells, Sevenoaks, Ashford, Staplehurst, Headcorn, Thanet
(Margate/Ramsgate), Whitstable, Hythe, Rye, plus a general Kent catch-all
page. New location pages added to `reference/sitemap-urls.md`; homepage
"Areas we cover" copy updated to match.

**Still open** (from the original reframe task, not addressed by this
decision): whether this shifts the CTA/training-academy angle, and whether
carer availability is actually confirmed for the newly-added outer towns
(Thanet, Whitstable, Hythe, Rye especially) before committing to serve them
in copy. Worth confirming operationally before those location pages go live.

---

## Decision log — design system, careers hub, header CTA (2026-08-19)

**Design system:** must be responsive, with a proper spacing system, a
proper typography system, and genuinely consistent CSS site-wide (a small,
fixed set of button styles reused everywhere, not one-off classes per page)
— standard practice, not negotiable polish. A uni-project prototype
(`reference/finding-what-fits-prototype/`) already implements this well and
is the recommended foundation — see that folder's README for specifics.
Not yet actioned in the live theme code.

**Careers hub segregation:** the careers section should be fully separated
from care-service content — focused on training and benefits, no care
messaging bleeding in. Partially already true: `header.php` and
`class-theme-activation.php` already switch to a dedicated Careers nav via
`careers_page_ids` when on the careers subtree (see `docs/CODEBASE-PARSE.md`
§5). Worth auditing whether this goes far enough (visual identity, not just
nav) once build work resumes.

**Header CTA — "Switch to us":** Ellie wants a dedicated header button for
families looking to switch care providers, modelled on Superior Healthcare's
(superiorhealthcare.co.uk, not the dead superiorcare.co.uk) lime-green
"Switch To Us" button, which sits in their header alongside "Join Our Team"
and "Give Feedback" — a distinct CTA from the generic contact/consultation
flow. Not yet designed or built for CCS; pairs naturally with the
client-specific-recruitment USP already added to the homepage differentiator
module above.

**Team page addition:** Magdalena Zoledz ("Mags") — Recruitment Coordinator
— added to the "Who You'll Meet" roster in `docs/CCS-THEME-AND-CONTENT-GUIDE.md`
(now 8 people, not 7).

---

## Session: 2026-08-19 (evening) — visual overhaul, pass 1

First time this theme has ever been run in a real WordPress environment.
Everything before this was written blind. Local preview via WordPress
Playground (`npx @wp-playground/cli start`), theme auto-mounted, reviewed and
iterated in a browser at desktop / tablet / mobile.

### Design system

- **Typography switched to Lora (display serif) + Open Sans (body).** Replaces
  Poppins everywhere: `design-system.css`, `critical.css`, `editor-style.css`,
  `theme.json`, and the Google Fonts URL in `class-critical-css.php`.
- **Fluid type scale.** `--text-xs` … `--text-4xl` are now `clamp()` based, so
  type scales smoothly with the viewport instead of jumping at breakpoints.
  Existing per-breakpoint `font-size` overrides became redundant and were
  removed where they fought the scale.
- **Fluid spacing + new semantic tokens.** `--space-sm` … `--space-3xl` are
  `clamp()` based under the same names, so existing utility classes upgraded
  automatically. Added `--space-section`, `--space-section-lead`,
  `--page-width`, `--page-gutter`, `--measure`.
- **Serif is for section headings only.** h4/h5/h6 and all component/card
  titles use bold Open Sans, so cards never compete with the H2 above them.
- **Fixed a design-system specificity trap.** `.ds-root h1…h6` (0,1,1) beat
  every single-class component rule in the theme, silently forcing the serif
  and heading sizes onto components that had explicitly opted out (this is why
  the hero H1 rendered dark purple on the dark scrim, and why card titles
  stayed huge). Element selectors are now wrapped in `:where()`.
- **Added the missing `.screen-reader-text` utility.** It was referenced by
  `single-service.php` and the CQC section but never defined, so
  screen-reader-only text was rendering visibly.
- **Added `.btn-accent`** as a third button purpose, distinct from primary.

### Header

- Sticky with `backdrop-filter` blur and an `.is-scrolled` shadow (scroll
  handler in `navigation.js`), following the Restwell header pattern.
- **"Switch to us" CTA added** (`btn-accent`), desktop and mobile, per the
  Superior Healthcare pattern. Target is `ccs_switch_to_us_url`, defaulting to
  Contact until a dedicated page exists.
- **Primary nav restructured from 10 flat items to 6.** It was wrapping onto
  three lines. "Who You'll Meet", "CQC and Our Care" and "Getting Started" now
  nest under About Us; "News & Updates" joins Resources. `build_primary_menu()`
  was generalised to a `$primary_menu_children` map instead of hardcoding
  Resources as the only dropdown.
- Duplicate phone number suppressed at desktop (top bar keeps it on mobile,
  where `.header-actions` is hidden).

### Homepage

- **Hero rebuilt to match `reference/finding-what-fits-prototype/`**: full-bleed
  photo, dual-gradient scrim, content anchored bottom-left, white serif H1,
  mint italic brand promise, accent CTA + outline pill call button, quiet proof
  line. Replaces the two-column text/image grid. Contrast measured in-browser:
  17.7:1 white, 11.8:1 mint — passes AAA.
- **Real hero photography wired in** as a packaged default (`<picture>` with
  the three art-directed crops already in `assets/images/site-photos/`), so the
  page never renders an empty grey placeholder. A Customizer image still wins.
- **`differentiators.php` was orphaned** — the file existed but was never
  included by the homepage template, and had *no CSS at all*. Now wired in and
  fully styled; it carries the client-specific-recruitment USP.
- **Section order reworked** to follow the family's decision path: hero → CQC →
  why choose us → services → differentiators → testimonial → info cards →
  partnerships.
- Fixed `.home-why-choose__body` being centred (`margin-inline: auto`) while its
  heading was left-aligned, so copy appeared indented from its own heading.
- CQC band rebuilt as a two-column trust band with a tighter heading.

### Content and SEO

- **Pages had no meta descriptions at all** and used raw WP titles. Added
  `seo_title` / `seo_description` to the page definitions plus
  `set_page_seo_meta()`, wired into both branches of `ensure_pages()`. Home
  now: 50-char title, 130-char description, single 29-char H1.
- Title separator set to `|` per the copy deck.
- **Banned-word sweep** against the locked voice rules in
  `reference/copy/homepage.md` ("compassionate", "person-centred", "tailored",
  "in the comfort of your own home", "loved one"). Cleared from all of CCS's
  own copy across the homepage parts and the activation content. Verbatim
  review quotes keep their original wording, as the copy deck allows.
- **One CTA label everywhere**: "Book a free consultation" (was mixed with
  "Book a care consultation").
- Hero CTAs no longer split between families and job seekers — careers lives in
  the nav, so the hero has one clear primary action.

### Bugs found and fixed

- **CQC profile URL used `/provider/` instead of `/location/`.** The brief flags
  this four separate times; `CCS_CQC_REPORT_URL` is never defined, so the wrong
  `/provider/` fallback is what actually shipped. A location-registration ID
  404s under `/provider/`.
- **Homepage "FAQs" card linked to the services page**, not FAQs.
- **Homepage "Careers" card linked to the legacy
  `care-careers-maidstone-kent` page**, not the real `/careers/` hub.
- **Care-guide modals rendered on every page of the site**, putting two stray
  `<h2>`s into every page's heading outline and loading unused CSS/JS. Scoped
  via a new `ccs_needs_resource_downloads()` (the taxonomy check in it uses
  `ccs_resource_category`, the real slug).
- Phone number in the schema constant corrected to `01622 809 881` (verified
  against the live site's `tel:` link).

### Pass 2 — side-by-side against the prototype

Ran the theme and `reference/finding-what-fits-prototype/` in two browser tabs
at the same viewport and closed the gaps:

- **Real logo in the header.** Was plain bold text; now the packaged CCS
  infinity lockup (`assets/images/brand/ccs-logo-long.png`), still overridable
  via the Customizer.
- **Pill buttons** (`--btn-radius: 999px`) to match the prototype.
- **Hero photograph swapped** to the prototype's `home-hero-family.jpg`. Its
  subjects sit right of centre, which leaves the left band clear for the text
  panel; the previous crop put a face directly behind the headline.
- **Scrim lightened** so the photo reads rather than being washed purple.
- **Top utility bar removed.** The phone moved into the main row as a pill.
- **"Book a free consultation" removed from the header** — the hero and page
  CTAs already carry it, so it was competing with itself.
- **Phone kept visible on mobile.** `.header-actions` used to be hidden below
  1024px; with the top bar gone that would have removed the phone from the
  header entirely on the one device where calling matters most. Now the phone
  pill shows at every width (compacting to "Call" under 420px) and only
  "Switch to us" is hidden on small screens.
- **Dropdown affordance added.** "About Us" and "Resources" read as plain links
  with no indication of a menu; they now have a chevron that rotates on open.
- **Stray dashes under the nav fixed.** `navigation.js` injects submenu toggle
  buttons into every menu with children, but the desktop hide rule only covered
  `.site-header__menu`, not `.nav-desktop`.
- **Mobile hero de-densified.** It carried a heading, promise, four-line
  paragraph, two full-width buttons and a two-part proof line in one viewport.
  Mobile now drops the second support sentence and the duplicate call button
  (the header pill covers it), and the scrim leans on a stronger bottom-up
  gradient since the text spans full width.
- **Copy voice rules relaxed** in `reference/copy/homepage.md`. The hard
  banned-word list was over-strict. Now: no em dashes, nothing that sounds
  AI-written, one CTA label — with dignity-based terminology (how we refer to
  the people we support) kept as a firm rule, and marketing-cliché words
  demoted to judgement rather than prohibition.

### Pass 3 — mobile menu and hero proof line

- **Mobile menu rendered greyed-out and unreadable.** `.site-header` is
  `position: sticky` with a z-index, so it forms its own stacking context. The
  menu panel lives inside it, meaning the panel's z-index resolved against the
  header (20) rather than the page, and the `body::before` overlay at 25
  painted straight over the open menu. Fixed by lifting the whole header above
  the overlay while the menu is open.
- **Seven pages were unreachable on mobile.** `navigation.js` picked its menu
  with `#primary-menu || #mobile-menu-list`, so only the desktop list ever got
  submenu toggle buttons injected — and CSS hides those on desktop anyway. The
  mobile panel got none, leaving About Us (3 children) and Resources (4
  children) with no way to expand. Now iterates over both menus.
- **Mobile menu actions overlapped.** The phone, "Switch to us" and "Book a
  free consultation" sat inline with no styling. Phone removed (the header bar
  already shows a persistent Call pill at that width) and the two remaining
  actions are stacked full width in a `.mobile-menu__actions` block.
- **Hero proof line restyled to match the prototype**: mint dot separator
  instead of a white rule, mint bottom-border on the CQC link instead of a
  white underline, smaller and more muted so it reassures without competing
  with the CTA.
- **Dropdown chevrons added** to "About Us" and "Resources" — they previously
  read as plain links with no indication a menu existed.

### Pass 4 — inner pages, and a serious activation bug

**"Populate pages" was creating duplicate pages on every run.** The site had 63
pages where it should have had 21: `about-home-care-maidstone-2`, `-3`, `-4`
and so on. `ensure_pages()` looked pages up with
`get_page_by_path( $slug )`, but that function expects a full hierarchical
path, so a bare slug never matched any *child* page
(`home/about-home-care-maidstone`). Every run fell through to the create
branch and WordPress appended a numeric suffix to dodge the slug collision.
Now looks up by `name` + `post_parent`. Verified idempotent: repeated runs hold
at 21 pages with no numbered slugs. This also explains why new page meta
appeared not to apply — it was being written to freshly created duplicates
rather than the pages actually being served.

Other inner-page work:

- **New `template-parts/page-header.php`** — a cream band with breadcrumb,
  heading and optional intro. Inner pages previously opened with a bare `<h1>`
  on white and no visual anchor.
- **New `template-parts/cta-band.php`** — pre-footer "Ready when you are"
  block, so pages end with a next step instead of running into the footer.
  Suppressed on Contact, where the form is the action.
- **Human page headings.** Several `<h1>`s were raw keyword strings ("About
  Home Care Maidstone", "Home Care Services Kent"). Pages can now set
  `ccs_page_heading` and `ccs_page_intro`; the slug still carries the keywords
  and the title tag still carries the SEO phrasing.
- **Page body styling.** `.page-body` had no CSS at all — no measure, rhythm,
  list or heading treatment. Now constrained to `--measure` with proper
  vertical rhythm.
- Wired into `page.php` and `template-about.php`. Remaining templates
  (services, careers, FAQs, CQC, getting-started, content-page, single-service,
  single-location) still need the same treatment.
- `10+ years` corrected from `15+` in the hero and the CTA block pattern.
- **Mobile menu white gap fixed.** The panel used a hardcoded `4.5rem` top
  padding tuned for the older, taller header. Now positioned from a measured
  `--ccs-header-h` (published by `navigation.js` from the header's *bottom*
  edge, so the WP admin bar and any emergency banner are accounted for).
- **Mobile menu collapsed to ~49px tall** once positioned that way, because
  `.site-header` has a `backdrop-filter` — and an element with a
  backdrop-filter becomes the containing block for its `position: fixed`
  descendants. The panel was resolving `bottom: 0` against the header rather
  than the viewport. Backdrop-filter is now disabled while the menu is open.

### Pass 5 — URL architecture and the services hub

**Single service pages already existed and are sound** — `/services/complex-care/`
etc., each with its own title, meta description, canonical, a single H1, ~7 H2s
and correct `Service` + `Organization` JSON-LD. Confirmed rather than assumed.

Two structural bugs found while checking them:

- **Two competing services hubs.** The `service` CPT had `has_archive => true`,
  producing a second hub at `/services/` that targeted the same keyword as the
  real hub page — straight self-cannibalisation. The archive also took its `<h1>`
  from the first post, so it was headed "Complex Care". Archive disabled; the
  hub page does the job properly (intro, card grid, CTA). Singles unaffected.
- **Town pages were served under `/care-services/`.** The `location` CPT was
  registered with `'rewrite' => array( 'slug' => 'care-services' )` — almost
  certainly copy-pasted from the services CPT. A town is not a care service, and
  it collided with the services IA. Changed to `/areas/`, matching
  `reference/sitemap-urls.md` and the launch redirect map.

Verified after flushing rewrite rules: `/services/` 404s, `/services/complex-care/`
still 200s, `/areas/` 200s, `/care-services/` 404s.

Also:

- **New `page-templates/template-services.php`** — the services hub rendered
  through the default page template, so the services appeared only as a plain
  bulleted list of links. Now a proper card grid built from the CPT (with a
  stretched-link pattern so the whole card is clickable but only the title is a
  real tab stop). Intro copy no longer repeats the list.
- **Shared page-header and CTA bands wired into the remaining templates**: FAQs,
  content-page, CQC, getting-started, current-vacancies, care-guides, careers.
- **Three 0-byte template files deleted** (`template-cookies.php`,
  `template-privacy.php`, `template-terms.php`) — referenced nowhere; the legal
  pages all use `template-content-page.php`.

### Pass 6 — breadcrumbs and internal links

- **Breadcrumbs read "Home › Home › …"** on every page nested under the Home
  page, because that page is also the front page and was being included as an
  ancestor. Now skipped.
- **Breadcrumbs used raw post titles** ("Home Care Services Kent"). They now
  prefer `ccs_page_heading` where set.
- **CPT singles had no shared breadcrumb.** `single-service.php` carried its own
  inline markup whose middle crumb linked to
  `get_post_type_archive_link( 'service' )` — which returns false now the
  archive is disabled, so it rendered an empty href. Replaced with the shared
  partial, which now adds a hub crumb for `service` and `location` singles.
- **`ccs_page_url()` returned non-canonical URLs for every child page.** Same
  root cause as the duplicate-pages bug: `get_page_by_path( $slug )` needs a
  full hierarchical path, so child pages fell through to a fabricated
  `home_url( '/' . $slug . '/' )` that drops the parent segment — `/contact-us/`
  rather than `/home/contact-us/`. Those resolved only via WordPress's canonical
  redirect, so *every internal CTA on the site took a needless 301 hop*. Now
  resolves by slug across all pages. Verified: hero CTA, info cards and services
  CTA all return 200 directly with no redirect.

### Pass 7 — URL structure and a full internal-link audit

**Removed the redundant `/home/` nesting.** Every inner page was parented to the
Home page, which is itself the front page served at `/`. That produced URLs like
`/home/home-care-services-kent/` — a level deeper than needed, worse to read and
worse for SEO, for no benefit. Pages now sit at root; meaningful nesting is kept
(`/resources/faqs/`, `/careers/current-vacancies/`).

Then crawled every page and tested every internal link, which turned up:

- **`/care-careers-maidstone-kent/` 404'd.** The page was defined in `$pages`
  but was never in `ensure_pages()`'s creation order, so it never existed — while
  the primary-nav and footer fallbacks both linked to it. Fallbacks repointed at
  the real `/careers/` hub and the dead definition removed.
- **Header CTA and "Switch to us" defaulted to `home_url( '/contact/' )`** — a
  URL that doesn't exist (the slug is `contact-us`). Both now resolve the real
  permalink.
- **The footer's accessibility link rewrote a correct URL into a redirecting
  one.** It carried a `'fallback' => home_url( '/accessibility/' )` that applied
  whenever `ccs_page_url()` returned a top-level permalink — which, once that
  helper was fixed, was always. Removed.
- Hardcoded `/contact/` links in `single-service.php`, `block-patterns.php`,
  `scenarios.php` and the contact template's own cards, plus
  `/current-vacancies/` in the careers page content, all repointed.

Final audit: 12 pages, 23 unique internal links, every one returning 200 with no
redirect hop. (The careers `/current-vacancies/` link lives in stored page
content, so it needs one "Populate pages" run to land in the database.)

### Pass 8 — contact page layout

- **The contact form was rendering in the narrow right column** with a large
  empty gap on the left. `.contact-layout__inner` is a `1fr 380px` grid, and the
  breadcrumb was a direct child of it — so it consumed the first cell, pushing
  the form into the 380px column and wrapping the info column onto row two in
  the wide one. Exactly inverted. Breadcrumb moved out of the grid into its own
  row above.
- **The contact page had three different names for itself**: nav "Contact Us",
  breadcrumb "Get in touch", H1 "Book Your Free Care Consultation". Breadcrumb
  now matches the nav; the H1 stays action-oriented.

*Note for future debugging: Playground caches PHP opcodes and the browser caches
301s aggressively. When a template edit appears not to apply, restart the
Playground server and re-fetch with `cache: 'reload'` before assuming the code is
wrong — several "bugs" in this session were stale caches.*

### Pass 9 — team page, careers segregation, photo processing

- **New `page-templates/template-team.php` + `inc/team.php`.** The roster lives
  in `ccs_team_members()` rather than page content, so it's one place to edit
  when someone joins or leaves. Members without a photo render an initials tile,
  never an empty box — the brief is explicit that a blank slot reads as
  anonymity, which is the exact thing the page exists to counter.
- **Team page intro rewritten** so it sets up the grid instead of duplicating it
  (it previously named only two people, and not the Registered Manager).
- **Careers CTA band is now context-aware.** `ccs_is_careers_context()` was
  extracted from the header's inline logic; careers pages get "Fancy joining
  us? → See current vacancies" instead of offering a job seeker a care
  consultation.

**Team photography.** Ellie supplied updated headshots as full-resolution
originals (1.4–11MB each, 26MB total — Amanda's alone was 11MB at 2347×3286).
Serving those raw would have wrecked page load.

- Web versions generated into `assets/images/team/` at 800×800, ~130KB each
  (26MB → 860KB). Originals stay in `site-photos/` as the source to re-cut from.
- **Brightness normalised across the set.** Measured means ranged from 51.6% to
  76.9% — a 25-point spread that made the grid look like seven photos taken on
  different days. Corrected per-image via gamma, converging on a 68% target:
  final spread is 67.1–68.8%.
  *(Worth noting for anyone repeating this: crop first, then measure. Measuring
  the full original and applying the correction after cropping corrects the wrong
  region. And ImageMagick's `-gamma` maps `out = in^(1/g)`, so the correction
  factor is `log(mean)/log(target)` — inverting it makes the loop diverge to the
  clamps.)*
- Six superseded duplicates removed after confirming nothing referenced them.
- **Heidi Griffen still has no photo** — currently an initials tile.

### Pass 10 — blog templates

**`/news-and-updates/` was headed "Hello world!"** — the theme had no `home.php`,
`single.php` or `archive.php`, so the blog fell through to a bare `index.php`
that output no page header, no container, and one `<h1>` per post via
`template-parts/content.php`. The visible H1 was therefore WordPress's default
first post.

- **`home.php`** — blog index with the proper page header (taken from the
  assigned Posts page, not the first post), a card grid, pagination and CTA band.
- **`single.php`** — single post with page header, date, featured image and CTA.
- **`archive.php`** — category/tag/date archives, same treatment.
- **`template-parts/post-card.php`** — listing card using `<h2>`, so archives no
  longer emit multiple `<h1>`s.
- Breadcrumb gained an `is_home()` case; the blog index isn't `is_singular()`, so
  it previously fell through with only "Home" and rendered nothing.

### Pass 11 — the active-nav "dot"

The active nav indicator rendered as a small square dot rather than an
underline. Two compounding causes:

1. **Both the dropdown chevron and the active underline were `::after` on the
   same element.** An element has only one `::after`, so the two rules collided
   and the chevron's `0.4em` box won the dimensions — producing a 5.6×5.6px
   square. The underline now uses `::before`.
2. The underline's left/right insets were `--space-md` (~20–24px), tuned to the
   original nav padding. Tightening that padding to fit six items on one row left
   the insets wider than the box, collapsing the bar. Both now derive from a
   single `--nav-link-pad-x` variable so they can't drift apart again.

Result: a clean 61×2px underline under the label, with the chevron intact
beside it.

### Known workflow limitation

Page *content* is only written when a page is first created, so editing the
default copy in `class-theme-activation.php` requires a full "Reset Demo
Content" to take effect. That's destructive and impractical once a site has real
content. Worth reworking before handover — e.g. only refresh content for pages
still flagged `_ccs_demo_content` and untouched since creation.

### Housekeeping

- WordPress's default "Sample Page" is still published. Worth deleting before
  launch — left alone here because a theme shouldn't delete site content.

### Still open — a decision for Ellie

The documented IA in `reference/sitemap-urls.md` puts pages at root level
(`/about-us/`, `/care-services/`, `/areas/maidstone/`), but the build currently
nests most pages under `/home/` (`/home/about-home-care-maidstone/`). Aligning
them is the right end state and matches the redirect map, but it changes every
URL, so it wants doing deliberately in one pass rather than piecemeal.

### Still to do

- `template-parts/home/scenarios.php` is still orphaned. Its links
  (`/hospital-discharge/`, `/our-care/`, `/contact/`) don't match the site's URL
  structure and would 404; fix those before wiring it in.
- Only the homepage has had a design pass. About, Services, Who You'll Meet,
  Careers, Contact, FAQs and the single service/location templates have not.
- Careers section still needs its visual segregation from the care side (nav
  switching already works).
- **Playground caveat:** its CSP sandbox blocks Google Fonts and the CQC
  widget, so previews render the Georgia fallback rather than Lora, and the CQC
  rating widget area is empty. Both will work on a real host.

---

## Notes

- Plan reference (original planning doc for this session, not migrated
  here): `.cursor/plans/ccs_home_care_site_finish_aca6f733.plan.md`
- No errors were logged during this session.
- Skills used per phase: plan-writing and clean-code (Phase 1); clean-code
  plus content-creator, copywriting, copy-editing, seo-content-writer,
  seo-authority-builder, seo-cannibalization-detector (Phase 2);
  schema-markup and copy-editing/copywriting (Phase 3); frontend-design,
  ui-ux-designer, ui-ux-pro-max (Phase 4); clean-code and planning-with-files
  (Phase 5).
