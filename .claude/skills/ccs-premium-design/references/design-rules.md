# The design rules, in full

The user's rules, adapted to CCS. Each one gives: what goes wrong, the
principle, what it means on this site, and how to check it. They are about
judgement, not bans. "Don't overuse cards" means every card must earn its box,
not that cards are forbidden. "Don't reach for purple gradients" means a
gradient has to fit the brand and the moment, not that gradients are out.
When you break one on purpose, be able to say why in one sentence.

Contents: 1 Containers · 2 Colour budget and side bars · 3 Status indicators ·
4 Colour from the brand · 5 Icons, not emoji · 6 Effects and dark surfaces ·
7 Colour count · 8 Hierarchy · 9 Spacing · 10 Width and alignment ·
11 Fundamentals · 12 Labels and the underline · 13 Unity · 14 Touch, reach
and fit · 15 Imagery · 16 Light, depth and corners · 17 Readable type ·
18 Conventions · 19 Words in the interface · 20 Forms and feedback ·
21 Respect attention and time · 22 Accessibility beyond text contrast ·
23 Evidence over opinion · The workflow

Sources: rules 1–13 come from the user's AI-UI anti-pattern notes and The Crit's
design fundamentals. Rules 14–23 come from the UI do-and-don't sources they
added (Nov 2026): UX Design Institute, Apple's UI design tips, Design Bootcamp
(Medium), Milestone, The Next Web, Adham Dannaway, Princepal's guidelines, the
"Dos and Don'ts for UI Design" 50-tip e-book and its Scribd excerpt (pages
1–5 and 7; the rest is behind a paywall), and a SlideShare deck that is mostly
design-file housekeeping.

The numbering matches the theme's `CLAUDE.md`, and code comments cite it.

---

## 1. Containers: a box means "this is its own thing"
**What goes wrong.** Everything goes in a card, the cards go in cards, and nothing
reads as more important than anything else. It's tidy, and it's noise.
**Principle.** Group with whitespace, proximity and type. Box only things that are
independently actionable or clearly separate objects.
**On CCS.** These earn a box: a service, a town, a person (team tile), a link to
another page (info card), a form, the price offer, the regulator's widget, the
contact methods. These don't: steps in a process, a list of differentiators,
a pull quote, coverage facts, "what happens next", proof points. They are
reading, so they sit on the band with spacing and type doing the grouping.
**Check.** The audit's `staticBoxes` (boxes containing nothing clickable) and
`nestedCards`. For each one, ask what would be lost if it were only spacing
and a heading.

## 2. Colour budget and side bars
**What goes wrong.** Every block gets a coloured left bar, each a different colour.
Locally it looks styled; globally it's chaos, and four highlights cancel out.
**Principle.** Colour is a shared resource for the whole page. Decide what gets the
accent and why, then hold the line.
**On CCS.** Mint marks quotations (one bar style) and the underline word.
Plum marks "you are here" in the menu and the primary action. One meaning
per colour, the same on every page.
**Check.** `sideBarColours` (at most one or two, each with a stated meaning),
`accents`, a look at the 1440 screenshot for colour spend.

## 3. Status indicators
**What goes wrong.** Coloured dots on nav items, headings and labels that look like
data and mean nothing.
**Principle.** A dot or badge maps to a real state, and the reader is told what
it means (a text label or a legend). If nothing changes state, remove it.
**On CCS.** The CQC widget's green "Good" is the regulator's labelled state, so
it stays. The hero's tiny separators are typography, like a middle dot, not
status. Nothing else should look like a status light.
**Check.** `dots`.

## 4. Colour comes from the brand
**What goes wrong.** Purple-to-indigo gradients, borrowed from tech marketing,
because they read as "modern". It's a trend, not a decision.
**Principle.** Colour comes from what the product is and who it's for.
**On CCS.** Plum is the brand's own colour (the logo), warm and steady; mint is
its fresh accent; cream and lilac grounds keep it soft. Gradients are fine
where they do a job: a plum scrim keeping text legible on a photo, a tonal
shift within plum. Not as atmosphere, and never as a tech-product
purple-to-blue sweep.
**Check.** `gradients` (scrims are expected; anything else needs a reason).

## 5. Icons, not emoji
**What goes wrong.** Emoji as nav icons, bullets and headers: a shortcut that never
decided on iconography.
**Principle.** Interfaces need an icon system: consistent, purposeful, sized for context.
**On CCS.** One set, `ccs_icon()` (`inc/icons.php`): line icons on a 24px grid, one
stroke weight, in round tinted badges (3.5rem, small 2.75rem) or inline at
1.25em. Add a new icon to the set, never a one-off. Emoji only in messages and
social copy.
**Check.** `emoji`.

## 6. Effects and dark surfaces
**What goes wrong.** Aurora backgrounds, glowing text and radial blooms behind the hero
for no reason: decoration that competes with content.
**Principle.** Dark sections earn depth through type, contrast and surface levels.
An effect must respond to the user or communicate something.
**On CCS.** The deep plum bands (differentiators, CTA card) use flat colour, white
type and one lighter surface step. Shadows are soft, warm and neutral
(`--shadow-card`, `--shadow-float`); no coloured glows. Motion responds to
interaction (hover lift, the menu sheet), never loops for mood.
**Check.** `glows`, plus a look at every dark band.

## 7. Colour count
**What goes wrong.** Five or six saturated colours shouting at once, with no hierarchy.
**Principle.** One dominant colour, one accent, one neutral; everything else is noise.
**On CCS.** Dominant plum, accent mint, neutrals white, cream and lilac (roughly
60% neutral, 30% plum, 10% mint). Error red and success are functional and
appear only in those states.
**Check.** A squint at the screenshot; nothing outside the tokens in new CSS.

## 8. Hierarchy
**What goes wrong.** Everything gets the same emphasis, so there's no obvious place to start.
**Principle.** Decide the most important thing on each screen and make it dominate,
in four levels:
1. **Primary action:** highest contrast, filled, prominent. On CCS: "Book a free
   consultation" (mint on dark, plum on light) or the phone.
2. **Main heading:** the largest, darkest text, with space around it.
3. **Supporting content:** medium size, `--color-ink-soft`, easy to scan.
4. **Secondary actions and details:** smaller and quieter. On CCS: `.btn-quiet` or a
   text link. "Read the CQC report", "See all services" and "Learn more" are
   secondary, so they never get a filled button.
**Check.** `primaryPerSection`, `filledSecondary`, and the squint test.

## 9. Spacing
**What goes wrong.** Uniform padding everywhere, which feels mechanical; or cramped; or adrift.
**Principle.** Vary spacing on purpose:
- **Tight (about 8px, 0.5rem):** inside a group. Label to field, title to meta, icon
  to its text.
- **Medium (about 24px, 1.5rem):** between items in a set, and between parts of a section.
- **Generous (48px and up; `--section-pad`, about 72–144px):** around sections that
  matter and around the primary call to action.
**On CCS.** Not every section gets the same padding: the key sections (CQC,
why, services, the closing CTA) get `--section-pad`; supporting ones (partners,
"where to go next", related services) get `--section-pad-sm`. Two sections on
the same ground share one gap (`--flush-bottom`).
**Check.** `sectionPaddings`: a single value across every section is the
mechanical-spacing smell.

## 10. Width and alignment
**What goes wrong.** Everything centred in one narrow column.
**Principle.** Width follows the content. Reading text 60–80 characters (about
46rem); grids and tables can go wide; heroes and intros can be asymmetric
(text left, visual right). Left-align body text; centre only short headings.
**On CCS.** `.ccs-split` and `.ccs-head--split` give asymmetry; centred heads are
for short titles above grids, and go left on phones.
**Check.** `centredParagraphs`.

## 11. Fundamentals (The Crit, design fundamentals)
- **Balance, contrast, emphasis, proportion, movement, white space, unity:** one
  focal point per screen; if two things aren't the same, make them clearly different.
- **Squint test:** blur the screenshot; the order of importance should survive.
- **Type:** two fonts (Lora, Open Sans), a type scale (the `--text-*` and `--title-size`
  tokens), body line height 1.5–1.7, line length 50–75 characters, body left-aligned.
- **Colour 60-30-10;** body contrast at least 4.5:1.
- **Align to a grid;** no edges that nearly line up.
- **When in doubt, more space;** crowded reads amateur.

## 12. Labels and the underline
A small label over a heading must add information (who is speaking, a promise,
a qualifier), not restate the title: zero to two per page. The mint underline
marks the one word that matters, on one or two headings per page.
**Check.** `eyebrows`, `accents`.

## 13. Unity over invention
**Principle.** The same colours, fonts and spacing patterns on every page.
**On CCS.** Build from the kit (BUILD-STANDARD §3) before inventing; a new
pattern must be reusable, use tokens, and be documented there.

## 14. Touch, reach and fit
**What goes wrong.** Pages that make you zoom or scroll sideways; small controls packed
so close they're tapped by mistake; the main action out of thumb reach.
**Principle.** Design mobile-first, the same experience on every device. Primary
content fits the screen. Controls are at least 44×44pt (Apple), with room
between them. The main action is large and reachable (Fitts's law).
**On CCS.** 44px is the target for this audience (older readers, phones); 24px is
the hard floor (WCAG 2.5.8), allowed only for inline links in sentences.
The call button floats within thumb reach on phones; buttons go full width under
600px. No horizontal scroll at 375.
**Check.** `overflow`; `smallTargets` (under 24); `under44` (under 44: review each).

## 15. Imagery
**What goes wrong.** Generic stock, low-resolution or stretched images, text over busy
photos, an image that confuses more than it explains.
**Principle.** Images support the story, at high resolution (2x for retina), in their
true aspect ratio. Text on a photo gets a scrim or overlay. No image is better
than an unclear one.
**On CCS.** Real CCS photography first (`assets/images/site-photos*`), stock only
where nothing real exists; crop and resize, never recolour; `object-fit: cover`
in a fixed `aspect-ratio`, never stretched; `wp_get_attachment_image` with honest
`sizes` so the 2x candidate is chosen; a plum scrim on every photo hero, and no
CSS brightness or saturation filter on photos (the scrim does that job). A hero
photo under 1200px wide gets the inset hero automatically instead of being
stretched full-bleed.
**Check.** `distortedImages`, `softImages` (rendered larger than the file can sharply fill).

## 16. Light, depth and corners
**What goes wrong.** Heavy dark shadows, light coming from several directions, layers that
don't separate, inner corners as round as their container.
**Principle.** Imitate real light: from above, so shadows fall below and are subtle.
A layer above another (a dropdown, a lifted card) differs by at least two
shades. A nested element's corner radius is smaller than its container's.
**On CCS.** `--shadow-card` and `--shadow-float` are soft, warm and downward; never
a black blur. Radii step down: a 24px card holds 16px or smaller inner elements.
**Check.** `nestedRadius` (inner radius at least as big as an inset parent's).

## 17. Readable type
**What goes wrong.** Tiny text, pure black, long uppercase, thin weights at small sizes,
too many sizes, over-wide lines.
**Principle.** Body text at least 16px (Apple's floor is 11pt; this audience needs
more), small print no smaller than 13px. Dark grey or ink rather than #000.
Sentence case; uppercase only for one-to-three-word labels. Regular and bold
weights at small sizes. Line height at least 1.5. Lines by character count,
not pixel width. Few sizes.
**On CCS.** Ink #2B2347 and ink-soft, never black; Open Sans has a tall x-height;
eyebrows and footer headings are the only uppercase.
**Check.** `smallText` (under 13px), `pureBlack`, `tightLeading` (paragraphs under 1.5),
`longUppercase`.

## 18. Conventions and consistency
**What goes wrong.** Reinventing patterns for novelty; the same function in different
places on different pages; plain text styled to look clickable; similar-looking
things that behave differently.
**Principle.** Follow users' mental models and platform conventions. Things that
look alike behave alike, and things that differ look different. Navigation stays
in the same place on every page. Only links look like links.
**On CCS.** Header, body, footer on every page; the phone and Book are always in
the same places; links in prose are underlined; cards that are links show a
"Read more →" cue and lift on hover; nothing else lifts.
**Check.** Manual: compare two pages side by side; tab through a page.

## 19. Words in the interface
**What goes wrong.** Robotic labels, jargon, vague buttons ("Submit", "OK", "Click
here"), link text that means nothing out of context.
**Principle.** Plain, human language. Buttons say what happens ("Send my request",
"Book a free consultation"). Link text makes sense on its own.
**On CCS.** No care-sector jargon without explanation (say "NHS Continuing
Healthcare", not "CHC"); a link's visible or accessible text names its
destination; the voice rules in CLAUDE.md apply.
**Check.** `vagueLabels`.

## 20. Forms and feedback
**What goes wrong.** Multi-column forms that break the flow; dropdowns for two or three
choices; errors far from the field, without a reason; no sign anything
happened.
**Principle.** One column for short forms. Two to four options shown as radio buttons,
not a dropdown (Hick's law: fewer, visible choices). Labels always visible;
format hints where a format matters. Errors appear next to the field and say
why. Every action gives feedback (loading, success).
**On CCS.** Contact and call-back forms are single column, mark optional fields,
use radios for short choices, show inline errors (`aria-invalid` and
`aria-describedby`), and a loading state on submit.
**Check.** `shortSelects` (a select with four or fewer options); manually submit
the form empty and with bad input.

## 21. Respect attention and time
**What goes wrong.** Autoplay video and sound, scroll hijacking, pop-ups, links that
silently open new tabs, clutter, slow pages, too many competing calls to action.
**Principle.** Users control their browsing. Load fast (optimised images, lazy
loading, few third parties). Keep pages to what the reader came for. Don't hide
critical information behind interaction; do hide what's irrelevant to the task.
**On CCS.** Video is click-to-load; no pop-ups; a link that opens a new window
says so (screen-reader text); WebP images, lazy below the fold; third-party
scripts only via the CSP allowlist; phone and address never hidden.
**Check.** `autoplay`, `newTabUnannounced`.

## 22. Accessibility beyond text contrast
**Principle.** Interface parts (form borders, icons that carry meaning, focus rings)
reach 3:1 against what's next to them (WCAG 1.4.11). Colour is never the only
signal: links are underlined, errors have text, states have a shape or weight
change. Images have useful alt text, or empty alt when decorative. Everything
works by keyboard with a visible focus ring.
**On CCS.** The field border #8C8A99 is 3.4:1 on white; the current nav item gets a bar
as well as colour; decorative photos have `alt=""` with the name in the caption.
**Check.** `missingAlt`; the contrast scan; a keyboard tab-through.

## 23. Evidence over opinion
**Principle.** Know the audience before deciding; design with real content, never
lorem ipsum; test on real devices and with real people; record the reasoning
behind each design change.
**On CCS.** Real copy and photos in every build; the 375px harness plus a real phone
before sign-off; code comments carry the why; when the user's feedback and a rule
disagree, raise it rather than silently choosing.

---

## The workflow (the user's four steps, applied)

1. **Visual identity.** For CCS this is settled: warm, established, personal, calm;
   readers are families arranging care, often older, often on phones; plum, mint
   and warm neutrals; Lora and Open Sans; references are ElderlyCare X for
   structure and Sunnyaid for inner-page layouts. Restate the parts that matter
   for the page in hand.
2. **Rough wireframe.** Before code, list the sections in order of importance, mark
   the single most important element on each screen, and mark which things are
   actionable (the only candidates for boxes).
3. **Detailed design spec.** Write it in design vocabulary: what is level 1, 2, 3
   and 4 in the hierarchy; spacing tiers per section; colour roles; where the
   one label and the one underline go; which bands. Then build it.
4. **Iterate with design feedback.** Run the audit, look at 1440 and 375, and
   critique in the same vocabulary ("the hierarchy is unclear, the CTA isn't
   leading"; "this is boxed but nothing in it is clickable"; "the spacing is
   uniform"). Fix and repeat until the critique comes back clean.
