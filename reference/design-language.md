# Continuity Design Language

> **Rebuild target, not the live site.** This describes the aesthetic direction for the CCS
> website rebuild planned in `reference/strategy.md` (the newest, most thorough planning doc),
> paired with the token values in `reference/design-tokens.md`. It is not what's currently
> implemented — the live theme's design system is `design-system/MASTER.md`, which matches
> `theme.json` and `assets/css/design-system.css`.

<aside>
🪞

**The aesthetic in one line:** calm, flat and domestic. A well-set brochure from a trusted local firm, not a tech product. Warmth comes from colour and real photographs; trust comes from order. Nothing decorates — everything answers a question a worried family arrived with.

</aside>

This page defines the five layers of how Continuity Care Services looks and feels online. It is the single source of truth for visual decisions: the UI lab file (`reference/continuity-ui-mock.html`) demonstrates these rules, and the Cursor rules file enforces them. Synthesised from thirteen competitor and concept references (Home Instead, Right at Home, Comfort Keepers, Visiting Angels, Nurse Next Door, Griswold, FirstLantic, plus agency concepts including Aims Homecare, Premier Homecare, Blue Water and Elite).

## 1 · Visual identity

*Colours, typography, layout, imagery — the most general layer.*

### Colour: warm paper, one authority colour

- **Off-white `#f6f5ef`** is the paper. Every page starts here.
- **Deep purple `#564298`** is the authority colour: the only button colour, the heading accent, and exactly one full-bleed statement band per page (the pre-footer CTA). Because it is rationed, it always reads as "this matters".
- **Soft lavender** `#ede7f9` is the workhorse warmth: hero panel, supporting section bands, hover tints, icon chips. It is in the purple family, so it always complements the CTA colour.
- **Soft mint** `#e3f7f4` is rationed to the occasional full-bleed band only — never on interactive elements, chips or hovers. The darker mint `#c4efea` is **retired from UI entirely**: it reads too dark and fights the purples. Tinted bands do the sectioning so borders and shadows don't have to.
- **Charcoal `#2e2e2e`** for body text, AAA greys `#45443f` / `#55524b` for secondary text. Never pure black, never low-contrast grey.
- Semantic colours are muted (error `#97474c`, success `#3f7d5d`) — forms should reassure, not alarm.

### Typography: two fonts, full stop

- **Poppins 600/700** for headings, **Open Sans 400/600** for everything else.
- Fluid scale with a hard **16px floor** — nothing on the site renders smaller.
- Body measure capped at **65ch**.
- **Accent words in headings are emphasised with colour (deep purple), never a different typeface.** Three separate studios in the inspo set reached for a serif italic accent word; we get the same emphasis flat, with colour.

### Layout: rhythm from colour

- 8pt spacing scale; sections breathe (48–64px vertical).
- Pages alternate the off-white base with soft mint or lavender bands, plus exactly one deep-purple statement band. Full-bleed colour changes are the section dividers — never waves, angles or shapes.
- Single column of attention. No competing CTAs side by side.

### Imagery: real, rounded, rationed

- Real photographs of the real team, with their permission. No stock, no AI imagery, no illustrated carers.
- Photos get **16px rounded masks**; **circles are reserved for faces**.
- The CQC logo is a first-class image, sized and placed like a trust mark, near decision points. The trust strip holds **evidence only** (CQC rating, registrations) — never a wall of decorative partner logos.
- **Optional "glimpse" gallery:** a flat grid of real care-moment photographs (16px masks, no overlays, no captions doing marketing) — only once a permissioned photo library exists. Real photos in quantity are proof; until then the slot stays empty rather than filled with stock.

## 2 · UI design

*The look and feel of the interface elements themselves.*

- **Buttons:** pill-shaped, 48px minimum target, Poppins 600. Deep purple is the only CTA colour — except on the purple band, where the button inverts to white with purple text.
- **Two actions maximum, clearly ranked:** the filled primary ("Book a free consultation") may be paired with one outline-pill secondary, and the secondary is always the phone call — a quieter path for people not ready to book, never a second competing offer. No third action on any screen.
- **Cards:** flat, white, 1px `#dedbd2` border, 16px radius. Cards are earned (comparable items only), never the default wrapper, never nested.
- **Elevation:** shadows belong to floating chrome only (sticky header, mobile bar, consent banner). Everything in the page flow is flat.
- **Hover and motion:** colour change only — border tints purple, background tints soft lavender, never mint. Nothing lifts, scales, glows or slides.
- **Icons:** inline SVG, stroke style, sized to the text, sitting inline with headings or in 48px soft-lavender chips. Never decorative icon grids.
- **Wayfinding chips:** large list-row links (56px, full border, arrow) for self-segmenting — the audience chips pattern.
- **Forms:** generous inputs (8px radius), visible labels, muted semantic states, one thing asked at a time.
- **FAQ:** native `details` elements. No JS accordions, no modals.
- **Header chrome:** the phone number is visible in the header, not hidden behind a contact page, and **"Check your postcode" is a persistent nav item** — coverage is the first question, so it lives in the navigation.

## 3 · Aesthetic

Kitchen-table calm. The site should feel the way a good first home visit feels: unhurried, plain-spoken, tidy, warm. Closer to well-set print than to a SaaS product. A worried adult child at 11pm should feel their shoulders drop — the page is ordered, the words are plain, the next step is obvious, and nothing is trying to dazzle them.

What it is **not**: corporate and anonymous (the failure mode of most UK care sites), and not the gradient-glassy polish of agency concept shots. Both read as "this was made to impress", and our visitor isn't here to be impressed.

## 4 · Design language

*The rules that make every page feel like the same site.*

1. **Colour does the sectioning.** Tinted full-bleed bands create rhythm; shapes never do.
2. **Flat elevation.** Shadow = floating chrome only. In-flow surfaces use 1px borders.
3. **Accent by colour, never typeface.** Two fonts; emphasis is deep purple, weight, or scale.
4. **The hero answers the two arrival questions** before any scroll: "do you cover my area?" (postcode checker) and "who is this care for?" (audience chips) — on a soft lavender panel.
5. **One CTA, repeated.** "Book a free consultation" everywhere; the purple statement band is its loudest moment, once per page.
6. **Cards are earned.** Comparable items only.
7. **Nothing below 16px, nothing below 7:1** contrast for body text. 48px touch targets.
8. **Plain English everywhere.** Pages named by need, not sector jargon; costs stated, not hidden.

**The banned list** (each of these appeared in the inspo set or in AI-default design): gradients, glassmorphism, glows · decorative blobs, dashed doodle paths, wavy or angled section dividers · serif or italic accent type · metric heroes and animated counters · stock or AI photography, flat illustrated carers · emoji in UI, em dashes in site copy · modals, carousels requiring interaction to read · nested cards, cards-as-default.

## 5 · Brand expression

*How Continuity-the-company shows up through the design.*

- **Continuity is the brand, so consistency is the message.** The same carers visit the same families — and every page shares the same rhythm, the same CTA, the same voice. Sameness by design is the brand promise made visual.
- **Local, named, real:** West Malling, Aylesford and Snodland appear in copy, footer and page titles. Real team photographs. A postcode checker in the nav. Everything says "we are actually here".
- **Trust is shown, not claimed:** CQC rating near every decision point, plain-English fees up front, DBS and training facts stated flatly. No superlatives doing the work evidence should do.
- **The voice is for Rachel:** the responsible adult child arranging care for a parent. Plain words ("home help", not "domiciliary"), short sentences, a kind tone that never rushes. A "no" (we don't cover your area) is delivered kindly, with a next step.
- **The recruitment funnel** (carers) keeps the identical visual system but speaks to a different reader — pride in the work, pay and training stated as plainly as fees are for families.

---

## What the inspo taught us

**Borrowed:**

- **Home Instead** — audience self-segmenting chips; arrival-questions hero
- **Plum NDIS concept** — alternating colour-band rhythm; the dark pre-footer CTA band
- **Aims Homecare** — coverage checker as a persistent nav item; phone number in the header chrome
- **Right at Home** — region-specific pages with local detail
- **Comfort Keepers / Griswold / Honor** — cost transparency as a feature, not a leak
- **Premier Homecare** — the ranked dual-CTA pair (filled primary + outline phone secondary); the photo-mosaic "glimpse into our care" gallery as trust evidence
- **FirstLantic** — mobile-first card stacking discipline; real third-party awards as recruitment-side proof (the CQC equivalent for the carers funnel)

**Consciously rejected:**

- Serif italic accent words (Aims, plum concept) → accent by colour instead
- Teal gradient heroes and glassy badges (Aims) → flat tinted bands
- Dashed doodle paths, blobs, flat illustrations (Blue Water) → real photography, no decoration
- Angled/wave section dividers (Elite) → full-bleed colour changes
- Carousel testimonials, metric heroes (several) → static, scannable trust evidence
- Arch and oval photo masks (Premier) → one photo shape: 16px rounded, circles for faces only
- Serif heading font and floating "5000+ happy customers" metric chips (FirstLantic) → two sans-serif fonts; claims are evidenced or absent
- Partner-logo walls (Premier's chamber-of-commerce strip) → trust strip carries regulator evidence only