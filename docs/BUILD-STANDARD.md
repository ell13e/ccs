# Build standard — what a £30k build means for this theme

The bar the client has set: work is judged against a premium (~£30k) UK agency
build, with the **ElderlyCare X** template (elderlycaretemplate.webflow.io) as the
visual benchmark, inner pages included. This file turns that into checks and
techniques. Written Nov 2026 after the homepage rebuild; update it when the
status column changes.

## 1. Where CCS stands

| £30k standard | Status (Nov 2026) |
|---|---|
| Every template on one design system | Yes. All 20 page types audited 11 Nov 2026 at 1440 and 375: no flags (`run-audit.sh`), 33 internal links all 200. |
| Client can edit every word without a developer | Mostly. 134 strings are in the Customizer: Homepage content and Page wording (134 in all; the template headings and short lines on inner pages), from one registry (`inc/home-content.php`, printed with `ccs_copy()`). Verified inside WordPress: both panels register, HTML is stripped on save, and an override renders. Still in PHP: the sourced CQC statistic sentence, partner names, form labels, and location-page lines that contain the town name. |
| Images sized per device (`srcset`), LCP image < ~200KB | Mostly. Theme-bundled photos now print `srcset` from width variants (`ccs_theme_image_srcset()`, `inc/theme-images.php`): the hero serves 43–221KB WebP instead of a 411KB JPEG, and homepage images at desktop fell from about 1.5MB to about 0.6MB. Still without variants: team portraits (512px, right-sized for 2x) and partner logos. |
| Automated QA (links, a11y, visual regression) in CI | Partly. `bin/qa.sh` runs PHP syntax, the copy registry test, the design audit (20 page types at 1440 and 375) and the link check in one command; it passes locally. `.github/workflows/qa.yml` runs it on push and pull request but has not yet run on GitHub. No visual regression. |
| Living design system doc | Stale. `design-system/MASTER.md` still describes the pre-Nov 2026 system; its font line is corrected, the section system is still undocumented there. |
| Brand typefaces reach every visitor | Yes, since Nov 2026. Lora and Open Sans are self-hosted (`assets/fonts/*.woff2`, `@font-face` in `design-system.css`, two preloads). Before that the theme linked Google Fonts, which its own CSP blocked, so visitors saw Georgia and a system sans. |
| Forms meet WCAG 2.1 AA | Markup yes: visible labels, optional fields marked, `autocomplete` on personal fields (contact and service call-back). Not yet tested with a screen reader. |
| Conversion tracking | Events verified in the browser (11 Nov 2026): phone, email, main button and form success each fire once. Fixed: enquiries counted twice, failed validations counted as conversions, the mint Book button untracked, and the CSP blocking every tracking host (now allowed only when an ID is set). A cookie choice bar now asks before anything loads (on by default; tested: nothing loads before a choice, reject keeps it off, accept loads it). No ID is configured, so nothing has been confirmed inside GA4 itself. |
| Professional photography | No: real phone photos. Needs a shoot. Never colour-correct the existing set (client rule). |

## 2. CSS techniques to apply (from the CSS-Tricks guides, with our caveats)

**Cascade layers** (`@layer`): declare priority bands so a later band wins
regardless of specificity or file order (the class of bug hit in `footer.css`).
*Caveat for WordPress:* unlayered styles beat every layer, and WordPress injects
its global styles unlayered (`a:where(:not(.wp-element-button))`). Layer only our
low-priority foundations (tokens, resets, utilities); leave components and
sections unlayered, or WordPress's link colour will override our buttons.

**Container queries**: `container-type: inline-size` on a wrapper,
`@container (width > X)` on children. Use for every component that must work in
a grid, a sidebar and a phone (service card, team tile, review card, price card).
A container cannot style itself, and cannot size to its own content.

**Responsive images**: `srcset` with `w` descriptors plus `sizes`. Hand-written
`sizes` are usually wrong; measure with RespImageLint. `<picture>` only for art
direction (a different crop per breakpoint). For WordPress, content photos belong
in the **Media Library**: `wp_get_attachment_image()` then generates `srcset`
automatically and the client can replace the photo. Theme-bundled files get
neither. Resizing to make width variants is allowed; tone or colour changes are not.

To add a variant for a theme-bundled photo, put a resized copy beside the
original named `{name}-{width}w.webp` (`magick photo.jpg -resize 800x -quality 80
photo-800w.webp`) and pass the slot's `sizes` to `ccs_theme_image_srcset()`. No
variants, no srcset: nothing breaks.

**Subgrid**: `grid-row: span N; grid-template-rows: subgrid` lines up titles,
bodies and footers across a row of cards. Replaces the `flex: 1` push used on
`.review-card__quote` today.

**Intrinsic grids**: `repeat(auto-fit, minmax(250px, 1fr))` removes breakpoints,
but can strand an orphan row; keep explicit columns where the item count is
known (the 12-logo partner wall is 2/3/4 columns on purpose).

**Colour functions**: derive tints and hairlines from one token instead of
hand-computing them: `color-mix(in oklab, var(--color-primary) 8%, white)`,
`oklch(from var(--color-primary-dark) l c h / 0.16)`. Keep a hex fallback first;
re-check contrast on the computed result.

**Media features**: scope hover lifts to `@media (hover: hover)` so they do not
stick after a tap on touch screens; prefer range syntax
`@media (600px <= width < 900px)`. `@custom-media` (named breakpoints) still
needs a fallback as of Oct 2026 and the theme has no build step, so instead
standardise on four breakpoints: 600, 900, 1024, 1280px (the rebuild currently
uses eight distinct values; consolidate).

**Links and buttons**: our card pattern (link on the title, `::after` stretched
over the card) is the recommended one; wrapping a whole card in `<a>` makes
screen readers read the card before announcing the link. A button-styled
"Learn more" is still correctly an `<a>`. Open question for the client:
CSS-Tricks argues against `target="_blank"` merely to mark external links,
which is what CCS does (CQC report, reviews, partner logos); weigh that for an
older audience that relies on the back button.

## 3. Component patterns: use and avoid (FreeFrontend survey, Nov 2026)

FreeFrontend indexes CodePen demos; most of its 300+ categories (neon, glitch,
liquid glass, 3D, particles) are decoration this brand rules out. What carries
over:

**Accordions / FAQs.** Use native `<details>`/`<summary>`: keyboard and
screen-reader behaviour come free. One-open-at-a-time is now native with a
shared `name` attribute (`<details name="faq">`), no script. Animate height with
`interpolate-size: allow-keywords` (or `calc-size()`) plus `::details-content`;
browsers without them simply open instantly. Avoid the hidden checkbox/radio
hacks (announced as "checkbox"), `:target` accordions (scroll jumps, history
noise) and `max-height` transitions. CCS today: `assets/js/faq-accordion.js`
(button toggles, hand-written keyboard support, closes siblings), which native
`<details name>` replaces.

**Forms.** Persistent visible labels above fully bordered inputs (GOV.UK
pattern): not floating labels, not bottom-border-only fields, not
placeholder-as-label. Every personal-data field gets `autocomplete`
(`name`, `tel`, `email`, `postal-code`). Style errors with `:user-invalid`
(only after interaction, unlike `:invalid`) and `.field:has(input:user-invalid)`,
but CSS styling is not an error message: pair it with visible text linked by
`aria-describedby`, `aria-invalid`, and server-side validation.

**Accent underline.** Our `.ccs-accent` already uses the approach the best demos
use (`background-size` + `box-decoration-break`, smooth and multi-line safe).
Candidate refinement: draw it on as the heading enters view with
`animation-timeline: view()`, inside `@supports (animation-timeline: view())`
and `@media (prefers-reduced-motion: no-preference)`, so the static line is the
fallback everywhere else.

**Steps.** The `<ol>` stepper with a drawn connector (homepage "How care starts")
is sounder than anything in the progress-steps category; reuse it on Getting
Started.

### Inner-page kit (sections.css and components.css; build new pages from these)

| Need | Use | Where it's used |
|---|---|---|
| A band with the standard rhythm | `.ccs-section` + `--tint` / `--cream`; `--sm` (medium padding, `--section-pad-sm`) for supporting sections; `--flush-bottom` when the CTA band follows a white section | every inner template |
| Section head left, editor prose right | `.ccs-split` > `.ccs-head` + `.page-body.page-body--flush` (head is sticky at 900px+) | About, Team, Careers, FAQs, CQC, services hub |
| Page copy in designed components | `ccs_content_sections()` (intro + h2 sections), `ccs_content_anchors()` (h2 ids + contents list) in `inc/content-sections.php` | FAQs accordion, services hub lede, careers title |
| Long-form page with a contents rail | `get_template_part( 'template-parts/article-body' )` (rail appears at 4+ h2s) | default page, policy pages, Care Guides |
| Service cards | `get_template_part( 'template-parts/service-cards' )`, ordered by `ccs_ordered_services()` | services hub, location pages |
| Plain points with an icon | `.feature-grid(--4)` > `.feature-card`; add `.feature-grid--plain` when the points aren't separate objects (no box) | Careers "what to expect" (plain), location local support (boxed) |
| The story video, large | `template-parts/home/story` (`.home-story`): centred head over one wide click-to-load video on white. Wording shared with the About page. | homepage |
| A page hero | `template-parts/page-header` decides: full-bleed `.page-hero--photo` when the featured image is 1200px or wider (`ccs_hero_photo_fills_banner()`), `.page-hero--inset` (photo framed beside the text, capped at file width ÷ 1.4) when smaller, plain tint otherwise. The service hero uses the same test. | every inner template |
| Section head with a long lede | `.ccs-head--split` (title left, lede right, left-aligned) instead of centring a multi-line paragraph | services hub |
| Link cards | `.info-grid` > `.info-card` | homepage, careers sub-pages, related services |
| One-line prompt with a button | `.ccs-strip` | Team ("work with us"), Areas ("not on the list?") |
| People | `.ccs-team-grid` > `.team-tile` (initials tile when no photo) | homepage, About, Who you'll meet |
| Questions | native `<details class="ccs-faq__item" name>`; the FAQ block renders the same markup | FAQs, service pages, FAQ block |
| A form | `.ccs-field` / `__label` / `__optional` / `__input` / `__select` / `__textarea`; mark optional fields, not required ones; one column; `fieldset.ccs-field.ccs-choices` with `.ccs-choice` radios for 2–4 options; `novalidate` so `form-handler.js` shows inline `.ccs-field__error` messages | contact, service call-back |

Third-party embeds need their host in the CSP (`inc/class-security.php`):
`frame-src` for iframes, `script-src`/`style-src` for widgets. A missing host
fails silently in production, not just locally.

### Editable copy (homepage and page wording)

Homepage wording lives in `inc/home-content.php` as one registry: key, Customizer
section, label, default. Templates print `esc_html( ccs_copy( 'key' ) )`; a heading
with the brush underline uses `ccs_copy_accent( 'key' )`, where the editor marks
the word with asterisks ("Rated *Good* by the CQC"). An empty field falls back to
the default, so nothing can be blanked by accident. To make another string
editable, add a row and swap the template's `__()` for `ccs_copy()`; the Customizer
control appears on its own. Never hard-code new homepage copy in a template.

## 4. Definition of done (run before calling any page finished)

`bin/qa.sh` runs items 2 to 6 for every page type in one go; the rest are by hand.

1. **Real viewports.** Check 375 and 1440 in the browser pane's device emulation.
   Headless Chrome on macOS enforces a ~500px minimum window, so "390px"
   headless screenshots lay out wider and crop: never judge mobile from them.
   Open the pane on `127.0.0.1:9401`, not `localhost`: WordPress serves assets
   from 127.0.0.1 and the CSP blocks them cross-origin. For full-page mobile
   captures, frame the page in a same-origin 375px iframe (`frame-ancestors
   'self'` allows it) and screenshot that.
2. **Overflow:** `scrollWidth === clientWidth` at both widths, whole document,
   not just `main` (the header nav overflowed when menus were duplicated).
3. **Headings:** exactly one H1; no level skipped.
4. **Contrast:** scan every text node against its resolved background. Wait for
   load *and* transitions to settle: stylesheets load async and `.btn`
   transitions colours over 250ms, so an early scan reads half-faded buttons.
5. **Targets:** at least 24px (WCAG 2.2); aim for 44px for this audience.
6. **Links:** every internal link returns 200, footer included. Test the full
   list scraped from the page, not a hand-picked one.
7. **Fresh boot:** restart Playground (new database), fire six concurrent first
   requests, confirm the nav has six top-level items and the Privacy Policy is
   published. This catches activation races and placeholder-page problems.
8. **Stale assets:** asset URLs carry the file mtime (`ccs_version_theme_asset_by_mtime()`);
   when previewing, bypass cached HTML with a query string.
9. **Hero unchanged** unless the change was agreed: diff the hero block of
   `homepage.css` against the last approved version.
10. **Side by side** with the benchmark page at both widths before sign-off.
