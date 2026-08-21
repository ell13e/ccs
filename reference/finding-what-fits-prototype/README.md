# Finding What Fits — prototype (2026-08-19)

A working HTML/CSS/JS build of the "Finding what fits" interactive care-finder
tool, built as a university project and supplied by Ellie as the design
quality bar for the real site ("a lot more premium, less I made this in my
bedroom"). Source: `~/Developer/Projects/uni/finding-what-fits.html` (part of
a larger "Be Family First" campaign project for the same uni assignment).

**This is the actual implementation of two things that existed only as plans
before now:**
- The Notion page `ccs-care-finder-copy` (finalised 6 Aug 2026) — a full
  3-question routing copy deck for this exact tool, with real 2025–26 hourly
  rates and a "palliative firewall" safety rule — had no code anywhere until
  this file. **Note:** that rate card's stated period ended 30 June 2026;
  re-verify current rates before reusing its numbers.
- The Notion page "CCS Landing Page — Be Family First" (4 Aug 2026)
  described a single-file HTML/CSS landing page in CCS Mint Purple; this uni
  project is the fuller build-out of that same campaign concept.

## Why this is worth building the real design system from

`css/finding-what-fits.css` (1180 lines) already implements, cleanly, most of
what was asked for as a general standard for the whole site:

- **Fluid typography scale** — `clamp()`-based `--text-xs` through
  `--text-3xl`, two-font system (Lora serif for headings, Open Sans for
  body).
- **Semantic spacing system** — numbered `--space-1` … `--space-8` steps,
  plus named roles (`--space-section`, `--space-block`, `--space-stack`,
  `--space-card`, etc.) so components reference *purpose*, not raw pixel
  values.
- **One page-geometry system** — `--page`, `--gutter`, `--measure` used
  consistently for every horizontal band (`.shell`, `.rail`,
  `.header-inner`, `.footer-wrap`) instead of ad-hoc max-widths per section.
- **A 3-button system with clear purposes**, not one-off button classes per
  page: `.btn-primary` (deep purple, main actions), `.btn-accent` (spearmint,
  the tool's own "Find what fits" CTA), `.btn-ghost` (outline, secondary).
- Already built on the **Mint Purple** palette (`--deep:#564298`,
  `--purple:#8b68da`, `--lilac:#a68edd`) — the intended rebuild direction
  per `reference/design-tokens.md`, not the older live-site palette.

**Recommendation, not yet actioned:** use this token/spacing/button system
(or a close adaptation of it) as the actual design-system foundation for the
CCS theme rebuild, rather than building a new one from scratch — it already
meets the "consistent CSS, proper spacing and typography system, standard
best practices" bar Ellie asked for on 2026-08-19.

## Files here

- `finding-what-fits.html` — full page markup (header, hero, questions,
  testimonial, the tool itself, SEO care-types band, contact band, footer).
- `css/finding-what-fits.css` — the full design system described above.
- `js/finding-what-fits.js` (744 lines) — the tool's question/routing/cost
  logic.
- `js/ccs-logic.js` (100 lines) — shared header/nav utility logic.
- `assets/` — logo files, hero photo, infinity brand pattern used by this
  build.

Not yet checked against the current live theme's PHP structure, WCAG
requirements, or the corrected phone number / team roster / area list from
this session's other decisions — treat as a design/interaction reference to
adapt, not a drop-in replacement.
