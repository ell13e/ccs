---
name: ccs-premium-design
description: Design, build, restyle, polish or review any page, section, template or component of the Continuity Care Services (CCS) WordPress theme to its premium agency standard, using the user's design rules (purposeful cards, a colour budget, meaningful indicators, brand-led colour, an icon system, no decorative glow, one dominant colour, four-level hierarchy, varied spacing, content-led width, few labels) and their four-step workflow (identity, wireframe, design spec, iterate with feedback), with ElderlyCare X and Sunnyaid as structural references. Use this whenever the work touches how the CCS site looks or behaves on screen, including vague asks like "make it look premium", "this looks off", "it's jumpy", "rebuild the blog page", "polish the footer", or critiques of spacing, cards, colours, labels, underlines, buttons or animation, even if the user doesn't mention the rules or the skill.
---

# CCS premium design

Build and review CCS pages so they read as one calm, warm, premium site. The
readers are families arranging home care in Maidstone and Kent: often older,
often worried, often on a phone. Every decision should make the next step
("Book a free consultation", or a call) feel easy and trustworthy.

## The rules (summary; the full version, with checks, is `references/design-rules.md`)

These are the user's rules. They ask for judgement, not prohibition: each
pattern is allowed when it means something here.

1. **A box means "this is its own thing".** Card only what is actionable or a
   separate object. Group everything else with whitespace, proximity and type.
   Never a box in a box.
2. **Colour is a budget for the whole page.** Decide what gets the accent and why.
   Side bars only for a real meaning, in one colour, everywhere the same.
3. **Indicators carry a labelled state, or go.** No decorative dots.
4. **Colour comes from the brand,** not from trends. Plum, mint, warm neutrals.
   Gradients only where they do a job (photo scrims).
5. **An icon system, not emoji:** `ccs_icon()`, one style, sized by context.
6. **No decorative glow or aurora.** Dark bands get depth from type, contrast and
   surfaces; effects respond to the user or communicate something.
7. **One dominant colour, one accent, one neutral** (plum, mint, cream/white;
   roughly 60-30-10).
8. **Four-level hierarchy:** primary action, then main heading, then supporting
   text, then secondary actions. Secondary actions are never filled buttons.
9. **Spacing varies on purpose:** tight (8px) within groups, medium (24px) between
   items, generous (48px and up) around what matters. Not one padding for every
   section.
10. **Width follows content:** 60–80 character reading width, wide grids,
    asymmetric splits; body left-aligned, centre only short headings.
11. **Fundamentals:** one focal point, the squint test, two fonts on a scale, 4.5:1
    contrast, align to a grid, more space when in doubt.
12. **Labels and the underline are rare:** zero to two eyebrows per page, each
    adding information; the mint underline on the one word that matters.
13. **Unity over invention:** build from the kit before inventing; new patterns
    must be reusable and documented.
14. **Touch, reach and fit:** mobile-first, no zoom or sideways scroll, 44px
    controls (24px floor for inline links), the main action in thumb reach.
15. **Imagery:** real photos, sharp at 2x, never stretched, a scrim under text; flag
    a photo too small for its slot rather than upscale it.
16. **Light, depth and corners:** soft shadows from above; layers two shades
    apart; inner radii smaller than outer.
17. **Readable type:** body 16px or more, nothing under 13px, ink not black,
    sentence case, line height at least 1.5.
18. **Conventions:** familiar patterns; alike looks means alike behaviour; fixed
    places for nav, phone and Book; only links look clickable.
19. **Words:** plain and human; specific button labels; link text that stands alone.
20. **Forms and feedback:** one column; radios for two to four choices; inline
    errors that say what to do; visible loading and success.
21. **Respect attention:** no autoplay, pop-ups or silent new tabs; fast pages;
    never hide the critical.
22. **Accessibility beyond text:** 3:1 for controls; colour never the only cue;
    alt text; keyboard.
23. **Evidence over opinion:** real content, real phones, recorded reasons.

The theme's `CLAUDE.md` uses the same numbering, and code comments cite it
("CCS/CLAUDE.md rule 8").

Standing constraints live in the theme's `CLAUDE.md`: keep the hero's energy,
never invent claims, the copy voice, no photo colour correction, Envato is
reference only, add embeds to the CSP, commit only when asked. The component
kit and definition of done are in `docs/BUILD-STANDARD.md`.

## Workflow: the user's four steps

### Step 1. Visual identity (restate it for this page)

CCS is settled: warm, established, personal, calm, never techy. Plum
`--color-primary` #564298 (ink #2B2347), mint `--color-secondary` #a8ddd4
(#85C4B8 on light), warm white, cream and lilac grounds; Lora headings, Open
Sans body. Structure references: ElderlyCare X (rhythm, alternation, overlap
and depth, short inner pages) and Sunnyaid (inner-page layouts); see
`references/inspiration.md` for what to take and what to leave. Note the parts
that matter for the page in hand: who arrives here, and in what state of mind?

### Step 2. Rough wireframe (before any code)

Find the page type in `references/page-blueprints.md`. Then write a short list:

- The page's one job and its **single primary action**.
- Sections in order of importance, each with its ground (white, tint, cream, deep)
  so bands alternate.
- For each section: the most important element, and **which things are
  actionable**. Only those are candidates for a card.

If the request is a fix rather than a page ("the menu is jumpy"), this step is
reproducing and measuring the problem; don't guess at visual bugs.

### Step 3. Detailed design spec (in design vocabulary, then build)

Before writing markup, state:

- **Hierarchy:** levels 1–4 for the page, by name.
- **Spacing:** which sections get generous padding (`--section-pad`) and which
  get medium (`--section-pad-sm`); tight, medium and generous inside components.
- **Colour roles:** where mint appears and what it means; where the one or two
  labels and underlines go (or none).
- **Width and alignment:** which sections are split, which are centred over a grid,
  and the reading measure.

Then build from the kit (BUILD-STANDARD §3): `.ccs-section` bands, `.ccs-split`,
`.ccs-head(--center|--split)`, `template-parts/service-cards`, `.info-card`,
`.feature-grid--plain`, `.ccs-strip`, `.ccs-team-grid`, native `<details>` FAQ,
`.ccs-field` forms, `template-parts/article-body`, the `cta-band`. A new pattern
must be reusable, tokens only, in `sections.css` or the page's own conditional
stylesheet, and documented in BUILD-STANDARD §3.

Theme conventions:

- Editor copy stays editable: lay it out with `ccs_content_sections()` /
  `ccs_content_anchors()` (`inc/content-sections.php`). Flag content problems;
  don't silently rewrite the client's text.
- Escape everything; only `ccs_icon()` and the_content output print raw, each with a
  phpcs note. Translatable strings, with `translators:` comments for placeholders.
- Comments explain *why* (what was wrong, the trade-off), so nobody "fixes" a
  deliberate choice.
- New copy: sentence case, no em dashes, the CTA label is "Book a free
  consultation", nothing that can't be sourced.

Motion, when it's involved: things that change together transition together on
the same token (`--duration-normal` 250ms, `--ease-out`); never add a transition
class in the frame an element leaves `display:none` (render first, then add the
class; hide again after the transition); animate transform and opacity only; read
durations from CSS so reduced motion (0ms) is honoured; keep above-the-fold CSS
render-blocking.

### Step 4. Iterate with design feedback (evidence, then critique, then fix)

1. Run the audit at both widths (paths relative to the theme root):
   `.claude/skills/ccs-premium-design/scripts/run-audit.sh --width 1440 /path/ ...`
   and again with `--width 375`. It reports overflow, h1, heading skips, tap targets
   and contrast (after finishing transitions, so no false fails), and the rule
   metrics:
   - eyebrows and accents;
   - card count, static boxes and nested boxes;
   - centred multi-line text and side-bar colours;
   - dots, emoji, glows and gradients;
   - two primaries in one section, filled secondary buttons;
   - section padding values and font families;
   - targets under 44px, stretched or soft images, oversized inner radii;
   - text under 13px, pure black, tight leading, long uppercase;
   - vague labels, short dropdowns, autoplay, unannounced new tabs, missing alt.
2. Take screenshots: desktop with headless Chrome, phone with `scripts/mobile-shot.sh`
   (true 375px width).
3. Walk `references/review-checklist.md` and critique in the rules' vocabulary,
   naming the rule each finding breaks. Fix, then re-run until clean, or
   until every remaining flag is a deliberate exception you can justify in a
   sentence.
4. Check links with `scripts/link-check.sh` (redirects count as failures).

**Done means all of these, run after the last edit:**

- The audit reports no flags on every page at 1440 and 375, with every run
  finished. Deliberate exceptions are declared in markup
  (`data-ccs-exception="rule N: reason"`), so they're listed, not flagged.
- The screenshot critique at both widths finds nothing left to fix against
  the blueprints.
- Links all return 200, and if activation code changed, a fresh boot passes.

Things only the client can supply don't block completion: they can't be fixed
in code without breaking a rule. Examples: a higher-resolution original for a
photo the audit reports as "sharp at 1x, short of 2x", or a copy or claims
decision. List them in the report as **content requests**, each with what's
needed and why. Don't present them as open design questions. Anything the
code *can* fix (a stretched or visibly soft image, a broken loader, a
missing state) is not a content request; fix it.

Environment gotchas:

- Use `http://127.0.0.1:9401` in the pane, not localhost.
- The pane is often a background page, where timers are clamped and
  transitions don't run. Test motion headlessly by seeking
  `getAnimations()` (see the checklist's Motion section).
- Page HTML is cached, so add `?fresh=N`.

## Report

Tell the user, plainly, rule by rule:

- what changed and which rule or reference drove it;
- any deliberate exception and its reason;
- the evidence (audit numbers, screenshots);
- anything that is their call (copy, claims, photos, brand).

## Review mode

When asked to critique rather than build, do Step 4 and report findings ranked by
how much they hurt the reader, each tied to a rule, with the fix you'd make. Apply
fixes when the user asks, or has already said to go ahead.
