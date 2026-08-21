# Continuity — Design Tokens

> **Rebuild target, not the live site.** This is the token spec for the "Mint Purple" rebuild
> direction laid out in `reference/strategy.md` (the newest, most thorough planning doc) —
> paired with the rationale in `reference/design-language.md`. The theme running today uses a
> different palette and token names; that's documented in `design-system/MASTER.md`, which
> matches `theme.json` and `assets/css/design-system.css` exactly. Use *this* file when building
> toward the rebuild; use `MASTER.md` for anything touching the current live theme.

The single source of truth for values **once the rebuild lands**. style.css `:root` and theme.json
must match this file exactly at that point. Colours not listed here do not exist.

## 1. Brand colours (the six theme.json palette entries)

| Token | Hex | Role |
| --- | --- | --- |
| `--ccs-purple-deep` | `#564298` | The ONE primary CTA fill per page, heading accent words, the single statement band |
| `--ccs-purple-mid` | `#8b68da` | Focus rings, nav markers, hover borders |
| `--ccs-lavender` | `#a68edd` | Quote/testimonial borders, small decorative accents |
| `--ccs-mint` | `#c4efea` | RETIRED from UI. Stays in the palette for editor completeness only — never used in components, hovers or bands |
| `--ccs-offwhite` | `#f6f5ef` | Page base background |
| `--ccs-charcoal` | `#2e2e2e` | Default body text |

## 2. Supporting tints (CSS custom properties only — NEVER in the editor palette)

| Token | Hex | Role |
| --- | --- | --- |
| `--ccs-lavender-soft` | `#ede7f9` | The workhorse tint: section bands, hover backgrounds, hero panel |
| `--ccs-mint-soft` | `#e3f7f4` | Occasional pale band tint ONLY — never hovers, never components |
| `--ccs-purple-deep-hover` | `#463380` | Darkened state of the primary CTA |
| `--ccs-border` | `#dedbd2` | 1px card/panel borders, dividers |
| `--ccs-white` | `#ffffff` | Card surfaces on tinted bands + the inverse button on the purple band ONLY |
| `--ccs-grey-strong` | `#45443f` | Secondary text (AAA on off-white) |
| `--ccs-grey` | `#55524b` | Muted text, captions (AAA on white) |

Rules: grey text sits on white/off-white only, never on tinted bands.
No absolute `#000` anywhere. No colours beyond this file.

## 3. Semantic form colours (style.css §2.3)

| Token | Hex | Role |
| --- | --- | --- |
| `--ccs-error` | `#97474c` | Error text + icon |
| `--ccs-error-bg` | `#f8eeee` | Error field/message background |
| `--ccs-success` | `#3f7d5d` | Success text + icon |
| `--ccs-success-bg` | `#ecf5f0` | Success field/message background |
| `--ccs-warning` | `#8a7349` | Warning text + icon |
| `--ccs-warning-bg` | `#f7f4ec` | Warning background |
| `--ccs-info` | `#4a6678` | Info text + icon |
| `--ccs-info-bg` | `#eef2f5` | Info background |

Errors are never colour-alone: always icon + plain-English text.

## 4. Typography

- Families: Poppins (headings, weights 600 + 700) and Open Sans
  (body, weights 400 + 600). Two families, four files, full stop.
- Self-hosted latin-subset woff2 in assets/fonts/, preloaded,
  font-display: swap.
- All sizes rem with clamp() for fluid scaling. Nothing renders
  below 16px: the smallest text floors at 1rem.
- line-height: 1.5 unitless on body text. Prose max-width 65ch.
- Scale anchors:
  - `--font-size-body: clamp(1rem, 0.95rem + 0.25vw, 1.125rem)`
  - `--font-size-h1: clamp(1.75rem, 1.45rem + 1.5vw, 2.5rem)`
  - The full authoritative scale lives in style.css §2.5.
- Heading accent words are emphasised with COLOUR (deep purple),
  never a different typeface, never italics.

## 5. Spacing (8pt scale — no magic numbers)

`--space-1: 8px` · `--space-2: 16px` · `--space-3: 24px` ·
`--space-4: 32px` · `--space-5: 40px` · `--space-6: 48px` ·
`--space-7: 56px` · `--space-8: 64px`

## 6. Radii

- `--radius-s: 8px` — inputs, small chips
- `--radius-m: 16px` — cards, photo masks, panels
- `--radius-pill: 999px` — buttons, audience chips
- Circles are reserved for faces only. One radius family — no
  arches, ovals or cut-out shapes.

## 7. Elevation (flat system)

- `--shadow-rest: 0 2px 8px rgba(46, 46, 46, 0.06)`
- `--shadow-float: 0 -4px 16px rgba(46, 46, 46, 0.08)`
- Shadows belong to floating chrome only (sticky header, mobile
  bar, consent banner). In-flow cards and panels use 1px
  `--ccs-border` borders and background tints — never shadows,
  never hover lifts.

## 8. Layout

- `--cqc-widget-min-height: 120px` (reserved slot, CLS guard)
- Content wrap: ~1120–1200px max-width.
- Breakpoints (mobile-first min-width): 480 / 768 / 834 / 1024 / 1440.

## 9. Usage grammar — strong defaults, not handcuffs

These defaults describe what made the inspo set work. Follow them unless
your reading of reference/inspo-teardowns.md suggests a better move — if
so, say what you're deviating from and which reference justifies it, then
build it. Hard limits that never flex: colours from this file only,
accessibility, the AI-tells ban list in ccs-theme.mdc, no mint hovers.

- Colour proportion roughly 60/30/10: off-white base / soft tints
  + white surfaces / purple accents.
- ONE deep-purple filled CTA per page. Everything else stays lighter.
- Pages alternate the off-white base with soft lavender (workhorse)
  or soft mint (occasional) bands, plus exactly one deep-purple
  statement band (pre-footer CTA, inverse white button).
- Hovers: tint soft lavender or darken the purple. NEVER mint,
  never translateY, never a growing shadow.
- Focus: 2px solid `--ccs-purple-mid` outline, visible everywhere,
  never hidden under sticky bars.
- Banned outright: gradients, glassmorphism, glows, blobs, doodles,
  wavy/angled dividers, one-sided accent borders, pure #000/#fff.