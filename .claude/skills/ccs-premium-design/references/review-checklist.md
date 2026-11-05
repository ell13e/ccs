# Design review checklist

Run this on every page you build or review. "Measure" says how to check it.
Most items are reported by `scripts/design-audit.js`. A finding is a prompt to
think, not an automatic fail: a deliberate exception with a reason is fine.
Declare it in the markup, `data-ccs-exception="rule N: reason"`, and the audit
lists it under "declared exceptions" instead of flagging it, so the reason
lives with the code and a new, undeclared case is still caught.

## Hierarchy (rule 8)
- [ ] Secondary actions ("read the report", "see all", "learn more") are quiet
      (`.btn-quiet` or a link), never filled. Measure: `filledSecondary`.
- [ ] One primary action per view. Measure: `primaryPerSection` in the audit;
      more than one `.btn-primary`/`.btn-accent` in the same section needs a reason.
      Pair a primary with `.btn-quiet` or a text link.
- [ ] The heading is the largest text and the primary button the highest contrast.
      Squint test on the 1440 screenshot: blur it (or view at 25%); the order of
      importance should survive.
- [ ] Supporting text in `--color-ink-soft` at body or lede size; secondary
      links small and quiet.

## Containers (rule 1)
- [ ] Every card is its own object (clickable or distinct). Measure:
      `staticBoxes` (boxes with nothing clickable inside; each needs a reason or
      should become spacing and type) and `nestedCards` (a box in a box).
      Current deliberate exceptions: the homepage price card (a rate card people
      compare) and review cards (separate attributed voices).
- [ ] Prose, intros and fact lists are grouped by spacing, not boxed.

## Colour (rules 2, 4, 6, 7)
- [ ] Accent spent deliberately. Measure: `sideBarColours` (one or two at most, each
      with a meaning), `accents` (mint underline, one or two per page), `gradients`
      (only scrims or tonal plum).
- [ ] No new hues outside tokens. Error red and success are only for states.
- [ ] 60-30-10 at a glance: light grounds dominate, plum structures, mint highlights.

## Labels and indicators (rules 3, 12)
- [ ] Eyebrows: zero to two per page, each adding information. Measure: `eyebrows`
      list; read each against its heading.
- [ ] Dots and badges map to a real, labelled state. Measure: `dots` (excludes the
      CQC widget).

## Icons (rule 5)
- [ ] Only `ccs_icon()` glyphs, no emoji. Measure: `emoji`.

## Space, width, alignment (rules 9, 10)
- [ ] Spacing varies: tight (8px) within groups, medium (24px) between items;
      `--section-pad` for sections carrying the argument, `--section-pad-sm`
      (`.ccs-section--sm`) for supporting ones. Measure: `sectionPaddings` (the
      report marks a near-uniform page).
- [ ] Reading measure 50–75 characters; body left-aligned. Measure:
      `centredParagraphs` (centred text blocks over two lines).
- [ ] Bands alternate; no two identical grounds touch without reason.

## Type and fundamentals (rule 11)
- [ ] Lora and Open Sans only. Measure: `fontFamilies`.
- [ ] Sizes from tokens; body line-height 1.6–1.75.

## Touch, images, depth, type (rules 14–17)
- [ ] Targets 44px (`under44`; inline sentence links excepted), nothing under 24px.
- [ ] No stretched images (`distortedImages`); none visibly soft (`softImages`: the file
      upscaled by more than ~10% to fill its box at 1x). `retinaSoftImages` lists photos
      that are sharp at 1x but short of a 2x screen: they need larger originals from
      the client, never upscaling. Hero photos under 1200×600 drop to the inset hero.
- [ ] Inner radii smaller than their container (`nestedRadius`); shadows soft and downward.
- [ ] No text under 13px (`smallText`), no #000 (`pureBlack`), paragraphs at 1.5+
      (`tightLeading`), uppercase only for labels of three words or fewer (`longUppercase`).

## Conventions, words, forms, attention (rules 18–22)
- [ ] Two pages side by side: nav, phone and Book in the same places; same components alike.
- [ ] No "Submit", "OK" or "Click here" (`vagueLabels`); link text that stands alone.
- [ ] Forms one column; no dropdown with four or fewer options (`shortSelects`); submit
      empty and with a bad email: errors under each field, focus on the first,
      clearing on fix.
- [ ] No autoplay (`autoplay`); every new-window link says so (`newTabUnannounced`).
- [ ] `missingAlt` empty; keyboard tab-through with visible focus.

## Motion
- [ ] Things that change together transition together (same token).
- [ ] No transitions out of `display:none` in the same frame; transform and
      opacity only; reduced motion honoured.
- [ ] How to verify: headless Chrome loads a same-origin harness page that
      frames the site, triggers the interaction, then pauses every animation
      in `frame.contentDocument.getAnimations()` and seeks
      `currentTime = t` before the screenshot. Do it at 0/60/125/190/250ms. Stub
      `setTimeout` in the frame when testing close, so hide timers don't fire
      during the capture.

## Accessibility and robustness (definition of done, BUILD-STANDARD §4)
- [ ] No horizontal overflow at 375 and 1440. One h1. No heading level skipped.
- [ ] Text contrast at least 4.5:1 (3:1 for large text) after transitions settle.
- [ ] Targets at least 24px (aim for 44px). Inline links in sentences are exempt.
- [ ] Every internal link returns 200 (`scripts/link-check.sh`).
- [ ] Hero untouched unless agreed; homepage regression diff if shared CSS moved.
- [ ] Compared side by side with the benchmark page at both widths.
