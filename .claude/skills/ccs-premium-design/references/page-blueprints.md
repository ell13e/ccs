# Page blueprints

Section order per page type. Each one gives the page's job, its primary action,
and the sections in order. Section notes use the kit names from BUILD-STANDARD
§3. "Benchmark" lines record what ElderlyCare X does on the equivalent page
(profiled Nov 2026), so you can see where CCS follows it and where it
deliberately doesn't.

Contents: Homepage · Services hub · Service single · Location single · Areas hub ·
About · Team · Contact · FAQs · CQC · Careers hub · Vacancies · Long-form/policy ·
Blog index · Blog post · 404 · Search

---

## Homepage (`template-homepage.php`)
Job: show that this is a trustworthy local provider and get a consultation booked.
Primary action: Book a free consultation (hero); phone as the second option.
1. Hero: photo, larger headline, promise line, one action (Book), proof line, CQC badge. Reworked with the client's agreement, Nov 2026; further changes still need agreement.
1b. Route strip (`.home-routes`): one white card lapping over the hero's foot: talk to us today (phone, hours), already have care (to the switching page), our office (hidden on phones).
2. CQC band (tint): the eyebrow "Independently regulated" is one of only two on the page; the live widget in a card that fits it, shown a third larger.
3. Why choose us (white): one photo beside copy, proof chips, one quiet button.
4. Services (tint): split head, three `.svc-card`s, a short CTA line.
5. Story video (white): centred head, one wide click-to-load video.
6. Differentiators (deep plum, the page's one dark band): heading, line and Book button left; four tonal panels with icons right.
7. How care starts (tint): `.ccs-steps` as three white cards with plum numerals and a chevron between.
8. Costs (white): centred head with the eyebrow "No hidden fees"; one wide two-part price panel (rate on ink, extras, funding note and link on white).
9. Testimonials (tint). 10. Team teaser (white, `.ccs-team-grid`). 11. Closing CTA card (tint).
Grounds are set in one block at the end of homepage.css. "Where to go next"
was dropped and the partner wall moved to About (calm pass, Nov 2026).
Benchmark: label + H2 on every section, alternating centred and left heads,
mostly white grounds with an occasional pale grey band, ~1000–1500px sections.
CCS keeps the rhythm and alternation, not the labels.

## Services hub (`template-services.php`)
Job: help someone pick the right kind of care. Primary: a service card, then booking.
1. Photo page hero. 2. `.ccs-head--split` (title left, the page's own lead
paragraph right) over `template-parts/service-cards` (white). 3. "How we work":
`.ccs-split`, editor h2s rendered as h3 (tint). 4. CTA band.
Benchmark: one long section, a centred head over a dense service grid.

## Service single (`single-service.php`)
Job: explain one service and get a call-back or booking. Primary: call-back form.
1. Hero with intro and actions. 2. Content (page-body) beside a sticky call-back card
(eyebrow "Free, no obligation"), features as proof chips, pricing, native FAQ.
3. Steps. 4. Related services (cream, `.svc-card`s plus a hub card when fewer than three). 5. CTA band.
Benchmark: label + H1 + button + image; "About this service" beside small
sidebar cards; "More services".

## Location single (`single-location.php`)
Job: answer "do you come to me?" and "who will I deal with?". Primary: Book.
1. Tint `.page-hero`: "Home care in {town}", excerpt, Book plus a quiet call button.
2. Copy beside ONE coverage panel (areas pills, postcode note, "Working alongside",
local team), groups split by hairlines, not separate cards. 3. Service cards (tint).
4. Local support (feature cards, only if data). 5. CTA band.

## Areas hub (`archive-location.php`)
Job: route to the right town. 1. Tint hero. 2. `.area-grid` of `.area-card`s
(town, first five villages plus "and N more"). 3. `.ccs-strip` "Not on the list?" with
the phone. 4. CTA band.

## About (`template-about.php`)
Job: who we are and why trust us. 1. Photo hero. 2. `.ccs-split` intro (title left,
editor copy right). 3. Team teaser (`template-parts/home/team`). 4. Story video beside
its head (`.about-story`, play button bottom-right, clear of the poster's lettering). 5. Partner logo wall (tint). 6. CTA.
Benchmark: label + H1 + images, values cards, "Come and visit us", team.

## Team (`template-team.php`)
Job: real names and faces before anyone comes to the door.
1. Photo hero. 2. `.ccs-split` intro. 3. Full `.ccs-team-grid` on tint (eyebrow
"Management and office": says these aren't the carers). 4. `.ccs-strip`
"Want to work with us?" leading to careers. 5. CTA band.

## Contact (`template-contact.php`)
Job: make getting in touch effortless. Primary: the form, with the phone equally easy.
1. Tint hero, H1 "Book your free care consultation". 2. Three contact cards lifted
over the hero edge (call, email, visit; these are actionable, so they are cards).
3. Form card (`.ccs-field`, optional fields marked, eyebrow "Free, no obligation")
beside a dark "Prefer to talk?" call card and a plain "What happens next" list.
No CTA band (this page is the CTA).
Benchmark: form beside info cards; "Come and visit us"; FAQ.

## Switching (`template-switch.php`, slug `switch-to-us`)
Job: families unhappy with their current provider. The header's "Switch to us"
button and the homepage route strip lead here. Primary: the switching form.
1. Tint hero: "Switching your home care to us", intro, "Start my switch" (jumps to
the form) and a quiet call button. 2. How switching works (white, `.ccs-steps`).
3. Questions families ask before switching (tint, native `<details name>`).
4. Form card beside the dark call card (contact-page anatomy): name, phone,
email, current provider, when current care ends (radios), what is not working.
Enquiries arrive tagged "Switching provider", with the answers at the top of
the message. No CTA band. Wording in Customize > Page wording > Switching page.

## FAQs (`template-faqs.php`)
Job: answer the worrying questions quickly. 1. Hero. 2. `.ccs-split`: sticky head with
the page intro and a "Question not here?" box (phone plus contact), native `<details name>`
accordion of the page's own h2s, first one open. 3. CTA band. FAQPage schema stays.

## CQC (`template-cqc.php`)
1. Photo hero. 2. `.ccs-split` intro. 3. The shared CQC band (live widget, fallback
badge, report button). 4. CTA band.

## Careers hub (`template-careers.php`)
Job: is this a good place to work, and is there a job? Primary: See vacancies.
1. Photo hero. 2. `.ccs-split`: the editor's first h2 becomes the section title.
3. "What you can expect": `.feature-grid--plain` on tint (icon and text, no boxes).
4. Sub-pages as `.info-card`s (white, flush-bottom). 5. Careers CTA band (no photo).

## Vacancies (`template-current-vacancies.php`)
Intro, then the CV Minder iframe (its tall pinned-iframe mechanics are the
vendor's; don't restyle them). The portal only renders on the registered domain.

## Long-form and policy pages (`page.php`, `template-content-page.php`, Care Guides)
`template-parts/article-body`: reading measure ~46rem, sticky "On this page" rail
at four or more h2s. Policy pages use the plain tint hero (never a photo).

## Blog index (`home.php`)
Job: show that the provider is active and helpful; route to articles.
Recommended: tint hero; one featured (latest) post large beside its excerpt;
a three-column grid of post cards (image, category label, title, date); pagination;
CTA band. Benchmark: H1 "Our Blog" with a featured post card, then a "Latest
posts" grid with a load-more button.

## Blog post (`single.php`)
Job: be genuinely useful, then offer the next step.
Recommended: centred title with category link and date above it (category is
information, so it earns its label); featured image wide; body via the
article measure; related posts (three cards); CTA band.
Benchmark: centred H1 with category and date, body, "Related posts".

## 404 / Search
404: tint hero, a short apology, search plus three useful links (services, contact,
home). Benchmark: centred "404" label, H1, one button. Search: tint hero with the
query, results as a simple list (title, excerpt, URL), no cards needed.
