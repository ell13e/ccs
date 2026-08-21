# Homepage copy deck — reference/copy/homepage.md
Status: v2 (11 Jun 2026). H1 option 4 locked. Phone, DBS claim and terminology verified.

**Resolved 2026-08-19** (see decision log in `BUILD-LOG.md`): the site does not lead with a single "hero" service (neither domiciliary nor complex care is centred) — all care types get equal weight, reflected in the 6-card service grid below, which stays an unranked list. Town list broadened from the original three-village scope to cover Kent more widely; see the "Areas we cover" section below and `reference/sitemap-urls.md`.

## Voice rules (apply to every section)

*Relaxed 2026-08-19. The previous version carried a hard banned-word list that
was over-strict and kept flagging copy that read perfectly well. The two rules
that actually matter are the first two; the rest is guidance, not a filter.*

**Hard rules**
- **No em dashes.** Anywhere. Use a comma, a full stop, or a colon.
- **Nothing that sounds AI-written.** No wallpaper phrasing that could belong to
  any care provider in the country. If a sentence would survive a find-and-
  replace of the company name, rewrite it.
- One CTA label, always the exact same string: **"Book a free consultation"**.

**Voice**
- Calm, straight-talking neighbour. Short sentences. Second person. Active
  voice. Contractions are good.
- Prefer the specific over the general: "how you like your tea" beats
  "personalised support". Concrete detail is what stops copy sounding generic.

**How we refer to people — this one is a rule, and it's about dignity**
- People we support: "families", "the people we support", "individuals we
  support". Avoid "service users" unless a regulatory or funding context
  genuinely requires it. People are not a caseload, and the language shouldn't
  reduce them to one.
- Staff: "carers" (lowercase), "team", "teams", "care staff", "care teams".
- Write about people as people: name them, describe what they actually like and
  do. Never write about someone as a condition ("a dementia client") or a task.

**Marketing wallpaper — judgement, not a ban**
- Words like *compassionate*, *tailored*, *person-centred* and *peace of mind*
  aren't forbidden. They're just the sector's default filler, and every
  competitor uses them. Reach for them only when they're genuinely the clearest
  word, never to pad a sentence.
- Verbatim review quotes keep their original wording regardless. Never edit a
  quote to fit the voice.

## SEO meta
- Title: Home Care in Maidstone & Kent | Continuity of Care Services
- Meta description: Home care for children and adults in Maidstone and across Kent, from a small, matched team of carers you'll get to know. CQC regulated. Book a free consultation.
- Note: individual towns are NOT named here on purpose. Each town gets its own location page that targets its own "home care in [town]" searches, so the homepage stays broad and never competes with them.

## Hero (lavender panel, ~55/45, photo right)
- Eyebrow: Home care for children and adults in Maidstone and Kent
- H1 option 4 (animated): "Your team. Your time. Your life."
  - Static prefix "Your " + typed/deleted rotating word: team. -> time. -> life.
  - Runs ONCE, then settles permanently on "Your life." (caret blinks twice, fades out)
  - Type ~80ms/char, hold full word ~1800ms, delete ~45ms/char
  - Rotating word + caret (2px bar) in deep purple = this IS the accent word
  - Real DOM: <h1 aria-label="Your team. Your time. Your life."> with animated
    spans aria-hidden="true" (screen readers + crawlers get the full sentence)
  - prefers-reduced-motion: no animation, render static "Your team. Your time. Your life."
  - Rotating span min-width: 5ch so the lead and CTAs never shift
  - No other motion in the hero
- Accent: the rotating word + caret carry the deep purple accent (see spec above)
- Lead: From everyday home care to complex and specialist support in Kent, we match you with a small team of carers you'll get to know, and you'll see the same faces at every visit.
- Primary CTA: Book a free consultation
- Secondary CTA: Call 01622 809881
- Image: real photo, 16px rounded mask. Consented photos of carers, families and individuals we support are available and welcome here. No stock, no AI imagery, no staged faces.

## Trust strip (directly under hero, fixed-height slot)
- Label: Regulated and registered
- Item 1: live CQC widget (rating: Good, auto-updates)
- Item 2: Fully managed, CQC-regulated provider
- Item 3: Enhanced DBS checks on all staff, office and care teams

## Differentiator module
- H2: A fully managed service, not an introduction agency
- Accent word: managed
- Body: Some companies introduce you to a carer and step back. We don't. CCS is regulated, inspected and insured, and we take full responsibility for the care our team gives. You get a planned start, a matched team and one number to call. If nobody on our current team is the right fit for your hours, your home or just doesn't get on with you, we recruit someone specifically for you. You're never stuck with whoever's free.   <!-- Added 2026-08-19: core USP per Ellie — client-specific recruitment, not just matching from the existing pool -->
- Sub-note (optional, near "matched team"): This is the USP, not a side benefit — most home care providers only offer you whoever is available. CCS will recruit new staff specifically for a client if the existing team isn't the right personal or practical fit.

## For the family (the Rachel block)
- H2: You don't have to do it all
- Accent word: all
- Body: A job, a family, and now care to arrange on top of it all. We plan it properly, with no rush and no pressure, so you can go back to being family instead of being the carer.
- Text link: How care starts -> /how-it-works/

## Service grid (6 cards, unranked — equal weight, no hero service)   <!-- Card set + wording verified against the sitemap and CCS's CQC registration. Dementia/companionship can join as cards 7-8 if they're standalone pages in the sitemap -->
1. Domiciliary care: Everyday help at home. Washing, dressing, meals and medication, done with patience. -> About domiciliary care
2. Complex care: Specialist support at home for children and adults with higher clinical needs. -> About complex care
3. Learning disability support: Consistent carers who help people live their own lives, their own way. -> About learning disability support
4. Respite care: Short breaks so family carers can properly rest. -> About respite care
5. Palliative care: Calm, steady support at home, day and night. -> About palliative care
6. Care for children and young people: Support for the whole family, from a team your child can get to know. -> About children's care

## How it works (3 steps)
- H2: How care starts
- Accent word: starts
1. Talk to us. A free consultation at your pace, on the phone or at the kitchen table.
2. Meet your team. We match carers to the person, not to a rota.
3. Care begins calmly. Planned, unhurried and reviewed as needs change.
- Text link: See the full process -> /how-it-works/

## Review band (quiet)   <!-- NEW 11 Jun: not in Wireframe 1. Addition justified by the rules-file social-proof pairing rule (trust signal + real testimonial on every key page). Real published reviews only, verbatim, light trims marked with "...". Never mark these up in JSON-LD. -->
- H2: What families say
- Accent word: families
- Quote 1: "It was the company owner that came out to start doing my care. She delivered my care for the first two weeks and then slowly introduced other carers to me." (T E, receives care at home, homecare.co.uk review, January 2024)
- Quote 2: "The owner is strongly committed to providing continuity of care... In my experience with a number of other care companies, they are exceptional." (Martin A, father of someone we support, homecare.co.uk review, November 2023)
- Text link: Read more reviews on homecare.co.uk -> https://www.homecare.co.uk/homecare/agency.cfm/id/65432230417   <!-- external link to the live profile; swap to an on-site /reviews/ page if one ever joins the sitemap -->

## Areas we cover
- H2: Where we work
- Body: Maidstone, West Malling, Aylesford, Snodland, Tonbridge, Tunbridge Wells, Sevenoaks, Ashford, Staplehurst, Headcorn, Thanet, Whitstable, Hythe, Rye and towns across Kent.   <!-- Decision 2026-08-19: broadened from the original three-village scope. See BUILD-LOG.md decision log. -->
- Text link: All areas we cover -> /areas/

## Pre-footer statement band (deep purple, inverse button)
- H2: Ready when you are
- Body: Start with a conversation, not a commitment. Free, no obligation, no pressure.
- Inverse CTA: Book a free consultation
- Secondary: Call 01622 809881