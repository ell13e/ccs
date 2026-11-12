# Inspiration: what to take, what to leave

The user's references are for structure, layout and visual cohesion. Colour
always stays CCS: plum, mint and warm neutrals. Never copy template code,
copy or imagery. Envato items are licensed per project, and their demo copy is
sometimes lifted from real providers.

## ElderlyCare X (Webflow): the benchmark
elderlycaretemplate.webflow.io (pages listed under /#pages; mobile matters as
much as desktop).

Take:
- **Calm rhythm.** Tall sections (roughly 1000–1500px at desktop) with generous
  space; mostly white grounds with an occasional pale band; one idea per section.
- **Alternation.** Centred heads above grids (services, testimonials, team),
  left-aligned heads beside media (why choose us, team intro). It reads as
  designed, not stacked.
- **Overlap and depth.** Cards whose panel overlaps the photo, an icon badge across
  the seam, a name tag overlapping a portrait. Soft, warm shadows rather than
  borders everywhere.
- **One icon language,** in round tinted badges.
- **Inner pages are short:** two to four sections each. Service: hero, about and
  sidebar, more services. Blog: featured post plus grid. Post: centred title,
  body, related. Contact: form beside info, then FAQ.
- **Mobile:** everything stacks to one column, buttons go full width, and
  imagery stays.

Leave:
- **A label over every section.** CCS rule 12 allows zero to two per page.
- Its colours, stock photography and copy.

## Sunnyaid (Envato HTML): structural blueprint for inner pages
Useful for contact (info boxes, form, map), team, FAQ and careers layouts. Its
demo copy reproduces a real franchise's wording, so take the layout ideas only.

## Templates reviewed and rejected (and why)
- **SenCare:** accent green on white measured 2.07:1, which fails WCAG for text.
- **Seniar:** coral buttons at 2.08:1, plus 61 stylesheets and 63 scripts per page.
- **Lesson:** a template that looks premium in a screenshot can fail the basics.
  Check contrast and weight before borrowing anything.

## The user's own direction
- **Accent:** `.ccs-accent` sets the word that matters in Lora italic, brand
  plum (mint on dark), with no underline or marker (Nov 2026, after the client
  rejected a brush stroke and then a fine rule). Reference: premium serif sites
  on Dribbble and Pinterest emphasise with italic alone. Use it sparingly.
- **"Purposeful, not banned":** cards, gradients and labels are all allowed when
  they mean something.
- **The bar is a premium agency build.** Rebuild the shared system rather than
  polishing one section.

## Techniques worth using (CSS-Tricks / FreeFrontend survey)
- **Container queries** for components whose layout depends on their own width:
  the contact form grid switches at `@container (min-width: 34rem)`.
- **Native `<details name>`** for exclusive accordions, plus `interpolate-size` /
  `::details-content` for a smooth open where supported. No JS.
- **`:user-invalid`** for form errors only after interaction.
- **`scroll-margin-top`** on anchored headings under a sticky header.
- **`scrollbar-gutter: stable`** while scroll-locking, so the page doesn't shift.
- **`color-mix()` / relative colour** for tints of tokens, instead of new hex values.
- **`text-wrap: balance`** on headings and **`pretty`** on ledes.
- **`@media (hover: hover)`** around hover lifts, so touch devices don't stick.
- **Caveat:** cascade layers lose to WordPress global styles, which are unlayered.
  Don't wrap theme CSS in `@layer`.

## Patterns to avoid (seen in AI-generated and template UIs)
- **Cards inside cards.** A box has to mean "its own thing".
- **Rainbow side bars,** and decorative status dots with no state behind them.
- **Purple-to-indigo or aurora gradients,** glows, neon and glassmorphism used for
  mood. A photo scrim for legibility is fine.
- **Emoji as icons.**
- **Uniform padding** on everything, and centring every paragraph.
- **Two primary buttons side by side.**
