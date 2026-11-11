# CCS theme: working rules

Continuity Care Services: home care in Maidstone and Kent. The people reading
this site are often older, or worried about someone who is, and often on a
phone. The site should feel warm, established, personal and calm, never like a
tech product. The bar is a premium agency build (ElderlyCare X is the
benchmark); `docs/BUILD-STANDARD.md` holds the component kit and the definition
of done.

## How to read the design rules

These are defaults for judgement, not bans. "Don't overuse cards" means every
card has to earn its box, not that cards are forbidden. When a page breaks one
of these on purpose, the reason should be clear from the design, and from a
short code comment where it isn't obvious. "It looked a bit plain" is not a
reason.

## Design rules

Numbered the same as the skill (`.claude/skills/ccs-premium-design/references/design-rules.md`,
which has the full reasoning, sources and how to check each one). Code comments
cite these numbers. Rules 1–13 come from the user's AI-UI pattern notes and
design fundamentals; 14–23 from the UI do-and-don't sources they added.

**1. A box means "this is its own thing."** Card only what is independently
actionable or a clearly separate object: a service, a town, a person, a form,
a link to another page, the price card, the regulator's widget. Group everything
else with whitespace, proximity and type: steps, differentiators, quotes,
coverage facts, intros. No box inside a box. Before adding a card, ask what
would be lost if it were spacing and a heading.

**2. Accent colour is a budget for the whole page.** Decide what gets the accent
and why, and hold it across pages. Side bars and rules mark a real meaning, in
one colour, the same everywhere: mint for quotations, plum for "you are here".
No rotating colours on sibling items. If everything is highlighted, nothing is.

**3. Indicators carry a labelled state, or they go.** The CQC widget's green
"Good" is the regulator's signal and stays. No decorative dots on headings,
nav or labels.

**4. Colour comes from the brand, not the trend.** Plum (`--color-primary`
#564298, ink #2B2347, deep #332659) is the brand's own; mint (`--color-secondary`
#a8ddd4, #85C4B8 on light) is its accent; grounds are white and
`--color-band-tint` #F1EFF7 only, strictly alternating, with one deep plum band
per page and the ink footer. Cream is retired (Nov 2026): warm cream beside
cool lilac made the bands fight, and the client called the page chaotic. Gradients only where they do a job (a scrim keeping
text legible on a photo); never a tech-style purple-to-indigo sweep for mood.

**5. Icons, not emoji.** One set, `ccs_icon()` in `inc/icons.php`: line icons on a
24px grid at one stroke, sized by context (badge 3.5rem, small 2.75rem, inline
1.25em). Add new icons to the set; no one-offs. Emoji only in messages.

**6. No decorative effects.** No aurora, glow or bloom. Dark bands get depth
from type, contrast and surface levels; shadows are soft and neutral. An effect
must respond to the user or communicate something.

**7. One dominant colour, one accent, one neutral.** Plum, mint, warm neutrals,
roughly 60% neutral, 30% plum, 10% mint. Error red (#A83A3A) and success are
used only for those states. A new hue needs a reason the palette can't meet.

**8. Four-level hierarchy.** (1) the primary action (Book a free consultation,
or the phone) is the highest contrast; (2) the main heading is the largest,
darkest text; (3) supporting text is medium, in `--color-ink-soft`; (4) secondary
actions ("read the report", "see all", "learn more") are quiet: `.btn-quiet` or a
link, never filled. Never two filled buttons side by side. Squint test.

**9. Spacing varies on purpose.** Tight (about 8px) inside a group, medium (about
24px) between items, generous (48px and up) around what matters.
`--section-pad` for sections carrying the argument, `--section-pad-sm`
(`.ccs-section--sm`) for supporting ones. One padding on every section reads
as machine-made. Tokens only.

**10. Width and alignment follow the content.** Reading text 50–75 characters
(`.page-body` 46rem); grids may go wide; use asymmetry (`.ccs-split`,
`.ccs-head--split`). Body left-aligned; centre only short headings, and centred
heads go left on phones.

**11. Fundamentals.** One focal point per screen; if two things differ, make
the difference clear. Two typefaces (Lora headings, Open Sans body) on the token
scale, body line height 1.6–1.75, contrast at least 4.5:1, align to a grid, and
when in doubt, more space.

**12. Labels and the underline are rare.** An eyebrow must add something the
heading doesn't (a promise, who is speaking, a qualifier): zero to two per page.
The mint underline marks the one word that matters, on one or two headings per
page.

**13. Unity over invention.** Build from the kit (`docs/BUILD-STANDARD.md` §3)
before creating anything new. A new pattern must be reusable and documented.

**14. Touch, reach and fit.** Mobile-first; nothing needs zooming or sideways
scrolling. Controls 44px for this audience (24px is the floor, for inline
links in sentences only), with space between them; the main action large and
in thumb reach.

**15. Imagery.** Real CCS photos first, high resolution (good for 2x screens),
in their true aspect ratio (`object-fit: cover` in a fixed ratio), a scrim
whenever text sits on a photo, and never a brightness or colour filter. No
image beats a confusing one. A photo too small for its slot is never upscaled:
a hero photo under 1200px wide drops to the inset hero automatically
(`ccs_hero_photo_fills_banner()`), and anything else too small is flagged to
the user.

**16. Light, depth and corners.** Light from above: soft shadows that fall below,
never heavy or black. Layers differ by at least two shades. Inner corner radii
are smaller than their container's (circles and pills excepted).

**17. Readable type.** Body 16px and up, nothing under 13px, ink rather than
black, sentence case (uppercase only for labels of three words or fewer),
regular and bold weights at small sizes, line height at least 1.5.

**18. Conventions and consistency.** Familiar patterns over novelty. Things that
look alike behave alike; navigation, the phone and Book stay in the same places;
only links look clickable, and prose links are underlined.

**19. Words in the interface.** Plain, human words; no unexplained jargon. Buttons
say what happens ("Send my request"), never "Submit" or "OK"; link text makes
sense on its own.

**20. Forms and feedback.** Short forms are one column. Two to four choices are
radios, not a dropdown. Labels always visible; optional fields marked. Errors
appear under the field, say what to do, and clear when fixed. Every action shows
feedback (loading, success).

**21. Respect attention and time.** No autoplay, scroll hijacking or pop-ups. A
link that opens a new window says so. Fast pages: optimised images, lazy below the
fold, third parties only via the CSP. Never hide the phone, address or
anything else critical.

**22. Accessibility beyond text contrast.** Interface parts (field borders,
meaningful icons, focus rings) reach 3:1. Colour is never the only signal. Useful
alt text, or `alt=""` when decorative. Everything works by keyboard.

**23. Evidence over opinion.** Real content, never lorem ipsum; check on a real phone
as well as the 375px harness; record the reason for a design change in a comment;
when the user's feedback and a rule disagree, raise it.

## Workflow (the user's four steps; the skill runs them)

1. **Visual identity:** restate what matters for this page (audience, tone,
   palette, type, references).
2. **Rough wireframe:** sections in order of importance; the one primary action;
   which things are actionable (the only candidates for boxes).
3. **Detailed design spec:** hierarchy levels, spacing tiers, colour roles, where
   the label and underline go, then build.
4. **Iterate with design feedback:** run
   `.claude/skills/ccs-premium-design/scripts/run-audit.sh` at 1440 and 375, look at
   screenshots, critique in these rules' terms, fix, repeat. Then run the
   BUILD-STANDARD definition of done.

## Calm first (client feedback, Nov 2026)

The client judged the site "messy and chaotic" after a pass that added
coloured blocks behind photos. The response was to take things away (not yet
signed off by the client):

- Two grounds, alternating. Sections of white cards sit on the tint; sections
  of text or one image sit on white.
- One photograph per section, in one frame. No collages: the photos differ too
  much in light and colour to sit together.
- No decorative shapes, offset blocks or two-tone grounds.
- Fewer sections. Before adding one, remove one.
- When asked to make it "look better", simplify and unify before adding.
- Thin is not calm. The client called the unboxed steps, the text-only
  differentiators, the old costs layout and the floating CQC widget "awful".
  A section needs one substantial object (a card row, a panel, a photo); loose
  text with small marks reads as unfinished. Rule 1 allows a box here when the
  alternative is that.

## Standing constraints

- **The homepage hero keeps its current energy and tone.** Tiny polish only,
  unless a change is agreed. (Nov 2026, agreed: larger headline, one action,
  and the route strip below it. Same photo, scrim and copy.)
- **Never invent claims, numbers, reviews or ratings.** CQC facts come from
  cqc.org.uk.
- **New copy:** no em dashes, sentence case, and the main call-to-action label
  is "Book a free consultation". Editor content belongs to the client:
  flag problems in it, don't silently rewrite it.
- **Photos:** crop and resize only, never colour or brightness correction.
- **Envato templates are structural reference only.** Never commit or
  redistribute template code.
- **Third-party embeds:** a new embed needs its host in the CSP
  (`inc/class-security.php`), or it fails silently in production.
- **Git:** commit or push only when asked.
- **Previewing locally:** the site runs at `http://127.0.0.1:9401`. Use that, not
  `localhost`, since assets are served from 127.0.0.1. Add `?fresh=` to bypass
  cached HTML.
