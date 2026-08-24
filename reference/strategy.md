# CCS Website Rebuild — Build HQ

<aside>
✨

**TLDR:** This is the build HQ for the new CCS website. Below you've got the research-backed design principles, the consumer-psychology levers that actually make families enquire (scored by impact), and a **visual site map** of the whole structure. The live **Sitemap & Build Tracker** database at the bottom is where we build each page out and track progress. 🚀

</aside>

## 🎯 What's In This Area

**📐 Design principles** — what the best home-care sites do, backed by research

**👥 Who we're designing for** — Responsible Rachel *and* the older reader, and how their eyes, hands and memory shape the build

**🗺️ The CCS care journey** — the emotional stages a family moves through, and what each page must do at each one

**🧠 Psychology levers** — why families enquire, scored by impact (PLFS)

**🥊 Kent competitor benchmarks** — what local & national providers do structurally

**🧭 Information architecture & navigation** — the rules behind the page tree (shallow, sticky, thumb-friendly)

**🌳 The site-structure map** — a visual tree of every page and how it nests

**⏱️ The homepage** — the first-five-seconds trust architecture + the differentiator module

**📄 Page templates that convert** — service, location, About/trust & advice blueprints

**🎨 Inspo** — sector benchmarks, design galleries & purple-palette references to steal patterns from

**🧩 Page wireframes** — annotated low-fidelity layouts for every key template

**🏅 CQC rating display** — the legal rules for showing your rating (Regulation 20A)

**🎨 Design language** — colour, type, components & widgets for a warm, trustworthy feel

**🔧 Build standards** — schema, accessibility (WCAG matrix), forms & performance to hand-code

**🧑‍💻 Coding standards** — the WordPress CSS + PHP house style for hand-coding the theme

**🔍 SEO architecture** — pillar pages, topic clusters, internal links & what Google's ranking systems actually reward

**♿ Accessibility standards** — the W3C rulebook: what's new in WCAG 2.2, the ARIA rules & ATAG

**🎓 Learning Lab** — theory summaries & mindmaps tailored to this build, plus the taste-training reference diet

**🗂 Sitemap & Build Tracker** — the live database to build the site from

**🔧 WordPress SEO in Practice** — the go-live checklist, on-page habits & local SEO moves

**🩺 Long-Term Site Health** — the 7-point maintenance checklist that prevents 3 AM surprises

**📚 Standards & A11y Resource Library** — every standard, expert blog & deep-dive from the research pass, tracked in a database

---

## 📐 Research-Backed Principles

<aside>
⏱️

**Answer the trust question in the first 5 seconds.** A person on a home-care site isn't shopping for a service — they're deciding whether to trust a stranger with their parent's safety. Lead with reassurance: your CQC rating, real specifics, and the *Your Team, Your Time, Your Life* promise.

</aside>

### 👀 Visual-first scanning + above-the-fold rules

<aside>
👁️

**TLDR:** People **scan**. Visuals teach faster than text. What sits **above the fold** becomes the first impression — and it must invite the scroll.

</aside>

- **Our brains are built for visual info:** learning speed tends to stack like **Video → Images → Icons → Text** (fast → slow). Use the highest-value visual format you can responsibly support.
- **Design for scanning, not reading:** assume most visitors won’t read every line — give clear headings, short blocks, and obvious “next” cues.
- **Content type + placement matters:** put the most important trust + decision info where eyes land first (top of page + first screen).
- **Above the fold = first impression (≈ first 3 seconds):**
    - Give one clear promise + trust signal + primary CTA
    - Keep it calm and scannable (no dense paragraphs)
- **Simple layout model (useful when wireframing):**
    - **Header / above the fold:** hero area (video/image), value proposition, and the CTA
    - **Body / below the fold:** supporting icon cards, supporting copy, FAQs, and proof blocks
- **Below the fold = engagement (≈ the next 30 seconds+):**
    - Use supporting detail, FAQs, and proof points for the “I need to be sure” reader
- **The top must entice the scroll:** include a visible hint there’s more (e.g. “How it works in 3 steps”, a partial next section, or a clear downward cue), so the journey continues.
- **The website is the digital front door** — for most families it's the first interaction with CCS, and the vast majority research providers online *before* ever picking up the phone. It has to do the reassuring for you.[[1]](https://nanoglobals.com/home-care-websites/)[[2]](https://getdeardoc.com/blog/healthcare-website-conversion)
- **Clear, shallow navigation wins** — families in a stressful moment need Services, Areas, Costs and Contact within one click. Clean information architecture directly lifts enquiries.[[3]](https://www.proweaver.com/grow-business-by-non-medical-home-care-web-design)
- **One service = one page** — a dedicated page per service (Personal Care, Companionship, Respite…) answers specific needs *and* wins local search far better than a single combined list.[[4]](https://shiftcare.com/us/blog/how-to-build-a-great-website-for-home-care-agencies)
- **Location pages drive local SEO** — West Malling, Aylesford and Snodland each deserve their own page; naming the town is what gets you found locally.[[5]](https://www.alorahealth.com/website-seo-tips-for-home-health-care-agencies/)
- **Every page drives ONE action** — book a free care consultation. Healthcare sites typically convert only ~2–5% of visitors, so a single, repeated, low-pressure CTA is what moves the needle.[[6]](https://www.tebra.com/theintake/checklists-and-guides/digital-marketing/conversion-marketing-for-healthcare-practices)
- **Make everything reachable in three clicks** — Services, Areas, Costs and Contact should each be one tap from anywhere. Shallow, sticky navigation beats deep dropdown menus for stressed visitors.[[7]](https://ahamediagroup.com/blog/information-architecture-vs-sitemap-healthcare/)
- **Run a second, separate funnel for carer recruitment** — the site has to win families *and* hire carers, so keep "Join the Team" structurally isolated from the care-seeker journey so the two never collide.[[8]](https://info.recruitics.com/blog/career-sites-for-healthcare)

<aside>
🧭

**CCS guardrails baked into the structure:**

- Lead every page with **continuity + carer matching + an unhurried, planned start** — never hospital-discharge or crisis-takeover angles.
- **Staff or service-user stories/testimonials are fine, initials only** (e.g. "J.S.") — never a full name. Pair them with the **CQC rating, regulation badges, third-party review scores, and genuinely helpful educational content**, don't rely on them alone.
- Write for **"Responsible Rachel"** — the adult son or daughter researching care for a parent — not for a faceless "audience".

**Copy standards.** Terse and checkable on purpose — cross-referenced against a real, more mature sibling project's own standards doc rather than invented from scratch:

- No "not X, it's Y" constructions ("it's not just what we do, it's how we do it") — state what the thing is.
- No filler phrases: "in order to", "it's important to note", "whether you… or…".
- Headings carry a real keyword or the reader's actual need — never a clever line that could belong to any provider ("Explore Your Care Options" tells nobody anything; "Home care services in Maidstone and across Kent" does).
- No fabrication. Never invent a statistic, a claim about a condition, or a fact not already verified in this repo or told to Claude directly. If a detail isn't confirmed, write it as an open question, not a guess.
- Every statistic gets an inline source link. A stats-heavy sentence with no citation is a claim, not evidence.
- Address the reader directly without naming who they're arranging care for ("mum", "your child") — the relationship is theirs to bring, not ours to assume.
- Testimonials stay initials-only per the guardrail above; this applies equally to any first-person quote used anywhere on the site, not just the homepage.
</aside>

---

## 👥 Who We're Designing For

*The research is blunt: a home-care site has two readers — and most of the time they're looking at the same screen. Design for both, or lose both.*

<aside>
👩‍💼

**Reader 1 — "Responsible Rachel", the decision-maker.** The adult son or daughter, usually 40–60, researching care for a parent. Time-poor, stressed, carrying guilt and worry — her head is already full before she lands. She isn't shopping for a service; she's deciding whether to trust a stranger with her mum's safety. So every page has to *lower* the load: one clear promise, lots of white space, scannable blocks, one obvious next step — never a wall of choices.[[NN/G]](https://www.nngroup.com/articles/usability-for-senior-citizens/)

</aside>

<aside>
👵

**Reader 2 — the older person themselves.** More and more, the parent is on the site too, often on a tablet. Designing for them isn't politeness, it's the core audience — and it means building for real, gradual changes in how they see, tap and remember:

- **Eyes** — lower contrast sensitivity and long-sightedness → big base font, charcoal on off-white (never pale grey on white), text that scales to 200% without breaking.
- **Hands** — arthritis and tremor make small targets painful → minimum 48×48px tap targets, links spaced apart, no hover-only dropdowns.
- **Memory & focus** — short-term memory load is real → sticky nav, breadcrumbs, linear journeys, labels that never vanish.
</aside>

<aside>
📊

**Why it's worth the effort:** in usability studies older adults completed standard web tasks only ~52% of the time, averaging nearly ten minutes — almost always because of friction a designer could have removed. Every bit of clarity you build in is a family you keep.[[NN/G]](https://www.nngroup.com/articles/usability-for-senior-citizens/)[[W3C WAI]](https://www.w3.org/WAI/older-users/developing/)

</aside>

---

## 🗺️ The CCS Care Journey

*Families don't arrive ready to buy — it typically takes six to eight touchpoints before someone enquires. Map the site to the family's emotional journey (not your org chart) and you meet Rachel exactly where she is at each step.[[Experity]](https://www.experityhealth.com/resource/blog/customer-journey-telehealth/)*

| **Stage** | **How the family feels** | **What the site must do here** | **Where on the site** |
| --- | --- | --- | --- |
| **1. Noticing something's changed** | Anxious, unsure, looking for validation | Calm, reassuring educational content and gentle "is it time for a little help?" guides; a clear, human value proposition; zero pressure | Home · Advice & Resources |
| **2. Weighing up options** | Analytical but stressed, actively deciding who to trust | Clear service breakdowns, what makes CCS different, the CQC rating front and centre, plain-English costs | Service pages · How It Works · Costs & Funding |
| **3. Choosing & planning the start** | Logistical, money-aware, wants to know what happens next | Transparent costs, a picture of the planned, unhurried start, and a gentle qualifying enquiry on a GDPR-safe form | How It Works · Contact |
| **4. Care underway** | Wants reassurance and consistency | Lead with continuity of care and the matched-carer-team promise; make it effortless to reach the team | How It Works · Contact |
| **5. Living well with care** | Settled, reassured, needs may change over time | Helpful ongoing resources and an easy route to adjust care as needs change | Advice & Resources · Contact |

<aside>
🧭

**Two deliberate CCS swaps from the textbook journey:** the sector's standard map has a *crisis / hospital-discharge* entry point and a *testimonial-collection* step at the end. CCS drops both — lead every stage with **continuity, carer matching and a calm, planned start**, and build trust through CQC, regulation and helpful content rather than client stories.

</aside>

---

## 🧠 Consumer-Psychology Levers

*The five behavioural levers with the highest leverage for a home-care site, scored on the PLFS scale (impact + fit + speed + ethics − effort, capped at 15). All are ethical — no false scarcity, no fake reviews, full cost transparency.*

| **Lever** | **Why it works (psychology)** | **Where on the site** | **PLFS** |
| --- | --- | --- | --- |
| **Authority & trust signals** | Credentials (CQC rating, regulation) reduce the perceived risk of trusting a stranger with a loved one | Home hero · Quality & Safety · every footer | 14 |
| **Risk reversal** | "Free, no-obligation consultation" removes the cost of saying yes, so hesitation drops | Every CTA · Contact · How It Works | 14 |
| **Reduce choice (Hick's Law)** | One clear primary action per page stops decision overwhelm in an already-stressful moment | Main nav · single CTA on every page | 13 |
| **Jobs-to-be-Done framing** | Speaking to Rachel's real job — "keep Mum safe and independent at home" — beats generic feature lists | Home · Service pages · Advice hub | 12 |
| **Local familiarity** | Seeing their own town named builds instant relevance and trust | Areas We Cover pages | 11 |

---

## 🥊 What Kent Competitors Do

*Structural patterns from real local and national providers — borrow the structure, keep the calm CCS tone.*

| **Provider** | **Structural move worth noting** | **The take for CCS** |
| --- | --- | --- |
| **Elizabeth's Rose** (Maidstone) | A deep directory of hyper-local hubs — separate pages for Bearsted, Barming, Coxheath and Loose, each naming local landmarks and local carers | Give every town its own page (e.g. home care in West Malling), not one combined "areas" list |
| **Caremark · Bluebird · Home Instead** | National brands run per-branch sub-directories with their own service pages, contact form and CQC rating | Use one consistent location template and repeat it per area |
| **Goe Daycare & Elizabeth's Rose** | Live CQC rating widget right in the hero — not buried in the About page | Put the CQC "Good" badge site-wide in the header and footer |
| **Audley Care** | Nests domiciliary care under its village locations for a continuous-care story | Shows that tying care to a place builds trust |
| **Radfield Home Care** | A dedicated "Guides / Advice & Support" hub answering long-tail questions | The model for the Advice & Resources section |

*Sources: [Elizabeth's Rose](https://elizabethsrose.co.uk/), [Caremark Maidstone](https://www.caremark.co.uk/maidstone/), [Goe Daycare](https://www.goedaycare.co.uk/), [Audley Care](https://www.audleyvillages.co.uk/audley-care), [Radfield Guides](https://www.radfieldhomecare.co.uk/guides).*

<aside>
🧭

**Where CCS deliberately diverges:** much of the sector leads with *crisis* and *hospital discharge* — the "answer in 8 seconds" panic framing. CCS does the opposite on purpose: calm, planned, unhurried starts and continuity of care. Copy competitors' *structure*, not their urgency. And where they lean on staff faces and client testimonials for trust, CCS leads with CQC, regulation and genuinely helpful content instead.

</aside>

---

## 🧭 Information Architecture & Navigation

*The map below only works if the structure under it is sound. Information architecture — how pages are organised and labelled — is a different job from navigation, the menu people use to move around. Get the architecture muddled and no amount of pretty menu design will save it; people just leave.[[Optimal Workshop]](https://www.optimalworkshop.com/blog/information-architecture-vs-navigation-creating-a-seamless-user-experience)*

### 🌲 A shallow, top-down tree

- Broad parent sections (Care Services, Areas We Cover, Costs, About, Advice) branching into specific child pages — the pattern that works best for care sites.[[Farm]](https://www.farmpd.com/farm-blog/9-information-architecture-patterns-for-healthcare-design)
- **Keep it shallow.** Services, Areas, Costs and Contact should each be one tap from anywhere and never more than three clicks deep. Bury pricing five levels down and you tax an older reader's working memory until they give up.
- **One service = one page. One town = one page.** This answers exact needs *and* wins local search (see the map below).

### 📌 Navigation rules for this audience

- **Sticky top nav** that stays put as the page scrolls, so a tired reader never has to scroll back up to reorient — exactly the fix that turned around navigation struggles on the Athena Care Homes site.[[Impact Media]](https://www.impactmedia.co.uk/case-studies/scalable-wordpress-website-for-award-winning-athena-care-homes-group/)
- **Desktop:** if the services list grows, a mega-menu lets people see the tiers at a glance and jump straight to their need. **Mobile:** use sequential "tap to slide deeper" menus, not cramped nested accordions.
- **Small supportive icons** next to menu items help scanning — but always with a clear text label, never instead of one.[[NN/G]](https://www.nngroup.com/articles/menu-design/)
- **Motor-friendly:** space links apart, make everything keyboard-operable with a visible focus state, and avoid hover-only fly-out menus that need a steady hand.[[W3C WAI]](https://www.w3.org/WAI/older-users/developing/)
- **One persistent phone number in the header**, on every page — never make a worried family hunt for how to reach you.

### 👍 The “rule of thumb” (mobile reach zones)

<aside>
📱

**TLDR:** Put primary actions where thumbs naturally reach — **bottom area = best**, **top corners = worst**.

</aside>

- Many people use phones **one-handed**, so UI elements in the bottom “natural” thumb zone are easier (and faster) to tap.
- **Best practice:** place the **primary CTA** (e.g. “Book a free consultation”) in the **lower portion** of the screen on mobile (sticky bottom bar or bottom-anchored button), not top-right.
- **Worst practice:** putting the main CTA in the **top-right corner** forces a stretch, increases friction, and can reduce clicks.
- **Left-handed use:** the reach zones are the same but **mirrored** — avoid relying on a single corner for your key action.

---

## 🌳 The Optimal Site-Structure Map

*A designed site-map graphic for the rebuild — the homepage links down to every section (chain icons = internal links), each section branches to its child pages, the journey funnels to the single conversion, and the dashed red card flags what we avoid: orphan pages.*

![CCS website site map](strategy-images/sitemap2.png)

CCS website site map

<aside>
🚫

**No orphan pages.** Every page in the map is reachable from the nav or its parent section — that red dashed box is exactly what we're *avoiding*. Orphan pages (live, but linked from nowhere) are invisible to visitors and barely crawled by Google, so each new page must link in from its parent section **and** the footer/sitemap.

</aside>

---

## ⏱️ The Homepage — First-Five-Seconds Trust

*A family decides whether to trust you in roughly five seconds. The homepage is a calm, top-to-bottom flow that answers "can I trust these people with my mum?" before it asks for anything.[[Nanoglobals]](https://nanoglobals.com/home-care-websites/)*

### Hero (the very top)

- One clear, human value proposition — the *Your Team, Your Time, Your Life* promise, not generic marketing fluff.
- The single deep-purple CTA: **book a free, no-obligation care consultation**.
- Persistent phone number anchored top-right.
- Warmth from approved imagery and the ∞ brand graphics — never staged staff or client faces.

### Straight under the hero — the trust strip

- The CQC "Good" rating badge, regulation/registration marks, and any third-party review score. No testimonials or client stories — CCS earns trust with regulation and genuinely helpful content instead.

### 🥊 The differentiator module (most sites bury this — you lead with it)

<aside>
🛡️

The highest-trust thing CCS can say up front: **"a fully managed, CQC-regulated home-care service — not an introductory agency."** Spell out why that matters — CCS is regulated, inspected, and insured, and takes full responsibility for its carers, whereas an introductory agency isn't always regulated, so care quality can't be guaranteed. Pair it with the continuity promise: a consistent, *matched* carer team and a planned, unhurried start.[[Vertex]](https://vertexplatformsolutions.com/insights/cqc-website-requirements-care-providers)[[Alina]](https://alinahomecare.com/home-care/mobility-care/)

</aside>

### Scannable service grid

- A simple grid of care types with clear icons, so Rachel can confirm in seconds that you do the specific thing she needs. Soft tones, generous type, one message per screen.

---

## 📄 Page Templates That Convert

*Most families never see your homepage first — they land deep on a service or town page straight from Google. So every page has to stand alone as its own little conversion engine.[[Nanoglobals]](https://nanoglobals.com/home-care-websites/)*

### 🩺 Service pages (one per service)

- Open with an empathetic, plain-English overview of that need — second person ("if mornings are getting harder for your mum…"), never clinical jargon.
- Show how CCS delivers it with continuity and a matched carer team.
- One repeated CTA: book a free consultation.
- Build trust with CQC, regulation and genuinely useful detail — not client testimonials.

### 📍 Location pages (one per town)

- Name the town in the heading and the copy — West Malling, Aylesford, Snodland — that naming is what gets you found locally.
- Local, human, area-specific content plus the same single CTA. A "find care near you" entry point feeds these.

### 🤝 About / trust pages

- In this sector the About area is a *primary* conversion tool, not a formality — families want to know who they're trusting before they call.
- CCS does this without staff photos or founder-face features: lead with the care philosophy, the *Your Team, Your Time, Your Life* promise, the CQC rating, regulation and brand values. Transparency earns the trust the rest of the sector tries to get from faces.

### 📚 Advice & Resources hub

- Educational guides, FAQs and care-planning explainers that answer the long-tail questions families actually Google. Positions CCS as the calm, helpful authority — and quietly does heavy local-SEO lifting.

---

## 🎨 Inspo

*Swipe file — steal patterns, not pixels. Everything here is filtered through the CCS rules: one CTA, CQC-led trust, no testimonials, no staged faces.*

### 🏡 Sector benchmarks — home care done well

| Site | Why it's worth a look | Steal this |
| --- | --- | --- |
| [Radfield Home Care](https://www.radfieldhomecare.co.uk/) | Regularly cited as one of the best-designed UK home care sites — warm, calm, scannable | Service grid layout · soft colour blocking |
| [Right at Home UK](https://www.rightathome.co.uk/) | National provider with a strong local-first pattern | Postcode "find local care" search — maps to our Areas hub |
| [The Good Care Group](https://www.thegoodcaregroup.com/) | Leads with CQC credentials right in the page title — regulation as the hero trust signal | CQC-first messaging · advice/support content hub |
| [Audley Villages](https://www.audleyvillages.co.uk/) | Premium, unhurried feel — proof that care sites don't have to look clinical or cluttered | Whitespace · calm pacing · quality photography style |

### 📚 Galleries & roundups

- [Home Care Websites: 10+ Design Examples](https://nanoglobals.com/home-care-websites/) — scored on the exact things we care about: first-five-seconds trust, conversion, credentials
- [5 Best Home Care Agency Website Designs](https://sagapixel.com/web-design/home-care-agencies/) — short, sector-specific shortlist
- [18 Best Healthcare Website Designs](https://www.webstacks.com/blog/healthcare-website-design) — broader healthcare polish for component ideas
- [15 Best Healthcare Website Examples](https://www.framer.com/blog/healthcare-website-design-examples/) — modern layout + booking-flow patterns

### 💜 Purple palette inspo

- [25 Beautiful Purple Websites — Muzli](https://medium.muz.li/25-beautiful-examples-of-purple-websites-7bae9441251e) — how dark vs pastel purples change the mood (our #564298 = authority, #a68edd = tenderness)
- [35+ Well-Designed Purple Websites](https://www.sitebuilderreport.com/inspiration/purple-websites) — big browsable set
- [CSSDA Purple Gallery](https://www.cssdesignawards.com/website-gallery?color=Purple) — award-level execution for micro-interaction ideas





<aside>
✂️

**Steal vs skip**

✅ **Steal:** postcode/town finder · live CQC rating widget · calm whitespace · one repeated CTA · advice hub as trust-builder

❌ **Skip:** testimonial carousels · staged staff photos · urgent/crisis angles · multiple competing CTAs · clinical blue-and-white sameness

</aside>

---

## 🧩 Page Wireframes

### 🏠 1 · Homepage

![Wireframe 1 · Homepage — v2, inspo-led redesign](wireframes/01-homepage.png)

Wireframe 1 · Homepage — v2, inspo-led redesign

*Covers: Home.*

![Wireframe 1 · Homepage — tablet & mobile](wireframes/01-homepage-resp.png)

Wireframe 1 · Homepage — tablet & mobile

*Hi-fi responsive device frames — Desktop 1440px · Tablet 834px · Mobile 390px, in full CCS Mint Purple brand styling. Open the HTML file in a browser to view it at full quality:*

[CCS Homepage — responsive device frames (HTML)](wireframes/ccs_responsive_device_frames.html)

CCS Homepage — responsive device frames (HTML)

### 🧭 2 · Care Services hub

![Wireframe 2 · Care Services hub — v2, inspo-led redesign](wireframes/02-services-hub.png)

Wireframe 2 · Care Services hub — v2, inspo-led redesign

*Covers: the Care Services landing page.*

![Wireframe 2 · Care Services hub — tablet & mobile](wireframes/02-services-hub-resp.png)

Wireframe 2 · Care Services hub — tablet & mobile

### 🩺 3 · Service page

![Wireframe 3 · Service page template — v2, inspo-led redesign](wireframes/03-service-page.png)

Wireframe 3 · Service page template — v2, inspo-led redesign

*Covers: Personal Care · Companionship · Respite · Dementia · 24/7 Care.*

![Wireframe 3 · Service page template — tablet & mobile](wireframes/03-service-page-resp.png)

Wireframe 3 · Service page template — tablet & mobile

### 📍 4 · Areas We Cover hub

![Wireframe 4 · Areas We Cover hub — v2, inspo-led redesign](wireframes/04-areas-hub.png)

Wireframe 4 · Areas We Cover hub — v2, inspo-led redesign

*Covers: the Areas We Cover landing page.*

![Wireframe 4 · Areas We Cover hub — tablet & mobile](wireframes/04-areas-hub-resp.png)

Wireframe 4 · Areas We Cover hub — tablet & mobile

### 🏘️ 5 · Location page

![Wireframe 5 · Location page template — v2, inspo-led redesign](wireframes/05-location-page.png)

Wireframe 5 · Location page template — v2, inspo-led redesign

*Covers: West Malling · Aylesford · Snodland · Maidstone.*

![Wireframe 5 · Location page template — tablet & mobile](wireframes/05-location-page-resp.png)

Wireframe 5 · Location page template — tablet & mobile

### 🪜 6 · How It Works

![Wireframe 6 · How It Works — v2, inspo-led redesign](wireframes/06-how-it-works.png)

Wireframe 6 · How It Works — v2, inspo-led redesign

*Covers: How It Works.*

![Wireframe 6 · How It Works — tablet & mobile](wireframes/06-how-it-works-resp.png)

Wireframe 6 · How It Works — tablet & mobile

### 💷 7 · Costs & Funding

![Wireframe 7 · Costs & Funding — v2, inspo-led redesign](wireframes/07-costs-funding.png)

Wireframe 7 · Costs & Funding — v2, inspo-led redesign

*Covers: Costs & Funding.*

![Wireframe 7 · Costs & Funding — tablet & mobile](wireframes/07-costs-funding-resp.png)

Wireframe 7 · Costs & Funding — tablet & mobile

### 🤝 8 · About / trust content

![Wireframe 8 · About / trust template — v2, inspo-led redesign](wireframes/08-about-trust.png)

Wireframe 8 · About / trust template — v2, inspo-led redesign

*Covers: Why Choose CCS · Our Approach · Who You’ll Meet.*

![Wireframe 8 · About / trust template — tablet & mobile](wireframes/08-about-trust-resp.png)

Wireframe 8 · About / trust template — tablet & mobile

### 🏅 9 · Quality & Safety (CQC)

![Wireframe 9 · Quality & Safety (CQC) — v2, inspo-led redesign](wireframes/09-quality-safety.png)

Wireframe 9 · Quality & Safety (CQC) — v2, inspo-led redesign

*Covers: Quality & Safety — structured around the five CQC questions.*

![Wireframe 9 · Quality & Safety (CQC) — tablet & mobile](wireframes/09-quality-safety-resp.png)

Wireframe 9 · Quality & Safety (CQC) — tablet & mobile

### 📚 10a · Advice & Resources hub

![Wireframe 10a · Advice & Resources hub — v2, inspo-led redesign](wireframes/10a-advice-hub.png)

Wireframe 10a · Advice & Resources hub — v2, inspo-led redesign

*Covers: the Advice & Resources landing page.*

![Wireframe 10a · Advice & Resources hub — tablet & mobile](wireframes/10a-advice-hub-resp.png)

Wireframe 10a · Advice & Resources hub — tablet & mobile

### 📄 10b · Article / FAQ layout

![Wireframe 10b · Article / FAQ template — v2, inspo-led redesign](wireframes/10b-article-faq.png)

Wireframe 10b · Article / FAQ template — v2, inspo-led redesign

*Covers: Care Guides · FAQs · Funding Explainers.*

![Wireframe 10b · Article / FAQ template — tablet & mobile](wireframes/10b-article-faq-resp.png)

Wireframe 10b · Article / FAQ template — tablet & mobile

### ✉️ 11 · Contact & booking

![Wireframe 11 · Contact & booking — v2, inspo-led redesign](wireframes/11-contact-booking.png)

Wireframe 11 · Contact & booking — v2, inspo-led redesign

*Covers: Contact / Book a free consultation.*

![Wireframe 11 · Contact & booking — tablet & mobile](wireframes/11-contact-booking-resp.png)

Wireframe 11 · Contact & booking — tablet & mobile

---

## 🏅 CQC Rating — Display It Right (it's the law)

<aside>
⚖️

**This isn't best practice, it's a legal duty.** Under Regulation 20A, a registered provider must display its most recent CQC rating on its website "legibly and conspicuously" within **21 calendar days** of a report being published. CQC inspectors check the website *before* they visit and can prosecute non-compliance without warning — so the badge can't be hidden in the footer.[[CQC]](https://www.cqc.org.uk/cqc-ratings-and-promotional-graphics/how-providers-must-display-ratings)[[Vertex]](https://vertexplatformsolutions.com/insights/cqc-website-requirements-care-providers)

</aside>

- **Above the fold and sitewide** — put the rating in the header or a persistent module, plus the footer, visible without hunting.
- **Link to the live CQC profile** on [cqc.org.uk](http://cqc.org.uk) where the full report lives. You may **not** upload a self-hosted PDF of the report instead.
- **Two ways to show it — pick one:**
    - **CQC widget (recommended):** an official embeddable widget that pulls live data and auto-updates when a new rating publishes, so you can never miss the 21-day window. Carve out a fixed block in the design to house it.[[CQC widget]](https://www.cqc.org.uk/cqc-ratings-and-promotional-graphics/how-providers-must-display-ratings/cqc-widget-posters)
    - **Custom badge:** the CQC promotional graphic restyled in Mint Purple — prettier, but *you* must manually update the graphic, date and report link within 21 days of any inspection, and remove "Good/Outstanding" graphics straight away if a rating ever drops.[[CQC graphics]](https://www.cqc.org.uk/cqc-ratings-and-promotional-graphics/promotional-graphics-providers/good-outstanding-graphics)
- **The five questions** behind the rating — safe, effective, caring, responsive, well-led — make a ready-built structure for a "Quality & Safety" page.[[Access Group]](https://www.theaccessgroup.com/en-gb/blog/cqc-standards-the-cqc-fundamental-standards-and-cqc-5-standards/)

---

## 🎨 Design Language & Look-and-Feel

*How the rebuild should feel — the CCS Mint Purple brand spec from the [Notion Brand Style Guide](https://app.notion.com/p/Notion-Brand-Style-Guide-ab758e187cd74f5f8a2b56c305dafcb8?pvs=21), crossed with the UX research on what makes home-care sites convert.*

<aside>
💜

**The feeling to engineer: "a calm exhale in the middle of a stressful decision."** Glance at any page and the gut reaction should be *warm, trustworthy, calm* — never *medical leaflet*, never rushed, corporate or cluttered. The research says the best home-care sites move away from clinical blue toward warmer, premium tones — and CCS already lives there with soft Mint Purple. So the job is to apply the existing brand faithfully, not invent a new look.

</aside>

### 🧩 Icon design principles (for a consistent CCS set)

<aside>
🧱

**TLDR:** Pick one grid, one stroke weight, one corner style, and one angle system — then make every icon obey it.

</aside>

- **Base grid:** design on a **24×24px** grid (your “box” for every icon).
- **Safe space:** keep a consistent **2px padding** inside the 24×24 so icons breathe and look the same size side-by-side.
- **Keyshapes:** define a shared set of **basic proportions** (circle/square/rect) that icons snap to, so the set feels cohesive.
- **Strokes:** use a consistent **2px stroke** for all outline icons (curves + angles + interior/exterior).
- **Corners:** commit to one corner treatment across the whole set (e.g. **rounded corners**) and apply it everywhere.
- **Angles:** use **45° angles** for clean anti-aliasing, and keep angle variants on a consistent step (e.g. **15° increments**).
- **Consistency check:** review the set as a group (not one-by-one) — alignment, optical size, stroke rhythm, and whitespace should match across every glyph.

### 🎨 Colour — CCS "Mint Purple"

- **Build straight from the brand kit, not a generic palette.** Soft purple resting on lots of off-white, with mint as a small fresh breath — *purple = dignity and calm, mint = freshness and health, off-white + charcoal = quiet and grown-up.* Purple already sidesteps the clinical blue most care sites default to, so CCS is *ahead* of the research, not behind it.
- **One repeated CTA, in deep purple.** Make *book a free consultation* the only deep-purple `#564298` button (or the signature purple gradient) so it always reads as "the action" — everything else stays lighter so nothing competes.
- **Mint is a highlight, not a second brand colour** — use `#c4efea` sparingly for accents and gentle section lifts so the purple stays the hero. *(Correction from earlier: gold belongs to the sister brands CTA and Restwell — CCS has no gold in its palette.)*
- **Never pure black** — body text is charcoal `#2e2e2e` on off-white `#f6f5ef` for an easy, non-clinical read.
- **Mind contrast (the one watch-out):** the lighter purples fail on white, so use deep purple `#564298` for text and buttons to clear **4.5:1 (aim 7:1)** for the older audience, and keep lavender/mint for fills and backgrounds, not body text.

| Hex | Role | Where it goes |
| --- | --- | --- |
| `#564298` | Primary deep purple | Headlines, logo, the one CTA button, key accents |
| `#8b68da` | Mid purple | Secondary buttons, links, gradients, icon fills |
| `#a68edd` | Lavender | Soft fills, hover states, dividers, backgrounds |
| `#c4efea` | Mint | Highlights, secondary accents, subtle section fills |
| `#f6f5ef` | Off-white | Page backgrounds, breathing space |
| `#2e2e2e` | Charcoal | Body text + captions (never pure black) |

*Signature gradient: deep purple `#564298` → mid purple `#8b68da` — run on headers and hero (the ∞ infinity-logo gradient).*

### 🔠 Typography — Poppins + Open Sans

- **Two brand fonts, no others:** **Poppins** (bold) for titles and headlines — geometric, friendly, confident — and **Open Sans** for headings and body — clean, highly readable, ADHD-friendly. Both are clean sans-serifs, so this still meets the research's "one calm type voice, no clutter" rule for older readers.
- Set type in **relative units (rem/em)** so visitors can scale to 200% without breaking the layout.
- Push contrast **past the WCAG AA 4.5:1 minimum toward 7:1 (AAA)** for the older audience — charcoal `#2e2e2e`, never pure black.

### 🧩 Components & layout

- **Soft, rounded, breathable** — rounded corners, gentle purple→mint gradients, and the **∞ infinity mark** (the brand's continuity symbol) as a recurring graphic device, all on generous off-white space. One message per screen.
- **Large touch targets** — minimum 48×48px (~15mm) — for less precise motor control on phones and tablets.
- Lean on a **tested component library** (the NHS Design System is the benchmark for this audience) for predictable forms, cards and summary lists — restyled in Mint Purple.
- **Generous white space** to cut fatigue and point the eye at the one action; avoid fiddly dropdowns and sliders.

### 🃏 Responsive card (auto-layout) — component notes

<aside>
🃏

**TLDR:** Build the card as reusable blocks, then use **auto-layout + wrap + min/max widths** so it fluidly shifts from desktop → tablet → mobile without redesign.

</aside>

- **Build the card from blocks (then compose):**
    - Thumbnail (image)
    - Info block (title + short description)
    - Tags/chips (in a wrap container)
    - Button / primary CTA
- **Auto-layout rules (Figma → translates directly to CSS flex/grid thinking):**
    - Use a single parent auto-layout for the full card
    - Keep consistent spacing tokens between blocks (8pt system)
    - Use “Hug” for height where possible to avoid awkward empty space
- **Make it responsive with min widths (key move):**
    - Set thumbnail + info block to **Fill** with a **Min width** so they can sit side-by-side until the container gets too narrow
    - When the container drops below the min width, the layout naturally stacks (desktop → mobile)
- **Set card Min/Max width:**
    - Apply **Min width** and **Max width** to the whole card so it doesn’t get squashed or stretch to silly-wide on large screens
- **Multiple cards/grid:**
    - Put cards in an auto-layout row with **Wrap**
    - Give each card the same min/max widths so the grid reflows cleanly across breakpoints

### 🔎 Search field (component notes)

<aside>
🔎

**TLDR:** Search is a *component*, not a one-off input — define **size + typography + states + results list** once and reuse it everywhere.

</aside>

- **Sizing (recommended “M / ideal”):**
    - Height: **48px**
    - Horizontal padding: **16px**
    - Icon: **24×24px** with **12px** left inset (inside padding)
    - Placeholder/body text: **16px** font with **24px** line-height
- **Results list:**
    - Clear label (optional): “Search results”
    - Row height: **32px** (comfortable scan target)
    - Vertical spacing: **10–12px** between elements (keep the rhythm consistent)
- **Interactive states to design + build:**
    - **Default**
    - **Hover** (subtle background lift)
    - **Active/Focus** (clear focus ring for accessibility)
    - **Disabled**
    - **Filled**
    - **No results** (kind, plain-English message)

### ⚙️ Interactive trust-builders

- Reframe "Contact us" into a gentle **qualifying / triage widget** ("who's it for? what support? what timeline?") — lower stakes, and it routes complex-care vs companionship enquiries.
- Consider **self-serve consultation booking** with real-time availability for late-night researchers.

### 🏗 Build it as a design system

- Define reusable tokens and components once (colour, type, spacing, buttons, cards). Rigorous design-system + CRO work drove a **35% then 80%+ conversion lift** for Helping Hands Home Care — consistency converts.

<aside>
🖼️

**Imagery — the brand already settles this.** UX research pushes *real carer/client photos* for trust, but both the CCS guardrail **and** the brand spec rule that out (*no staff or service-user photos without permission*). So there's no tension to resolve: get the warmth from **approved warm/human stock, illustration, the ∞ brand graphics and soft purple→mint patterns, real Kent and home settings, and CQC + regulation trust signals** — never staged faces.

</aside>

*Sources: [NanoGlobals](https://nanoglobals.com/home-care-websites/), [W3C WAI](https://www.w3.org/WAI/older-users/developing/), [IBM](https://www.ibm.com/think/insights/accessible-design-aging-population), [Caregiver Homes](https://new.drupal.org/case-study/caregiver-homes), [UserWay](https://userway.org/blog/web-accessibility-for-the-elderly/).*

---

## 🔧 Build Standards for the Rebuild

*The technical layer to bake in as you hand-code the new theme — tick each off per template.*

### 🏷 Schema markup (JSON-LD)

Roughly 80% of home-care sites skip this, so it's an easy edge. Add to each page's <head>:

- [ ]  **LocalBusiness** — name, address, telephone, opening hours, geo coordinates, image
- [ ]  **Service** — serviceType (e.g. Dementia Care), areaServed, provider — one per service page
- [ ]  **Organization** — logo, url, sameAs (socials + CQC registry), company registration
- [ ]  **Review / AggregateRating** — ratingValue, reviewCount — for star rich-snippets in search

### ♿ Accessibility (WCAG 2.2 AA — "POUR")

Your audience skews older, so this is ethical *and* a legal must:

- [ ]  **Perceivable** — 4.5:1 contrast minimum, text scalable to 200%, alt text, never colour alone
- [ ]  **Operable** — full keyboard navigation, visible focus states, generous touch targets
- [ ]  **Understandable** — consistent nav, plain language, persistent form labels (not placeholder-only), no surprise pop-ups
- [ ]  **Robust** — semantic heading order (H1 → H2 → H3), ARIA labels on interactive elements

| **WCAG criterion** | **Level** | **What it means for the build** |
| --- | --- | --- |
| 1.4.4 Resize Text | AA | Containers stay fluid — text scales to 200% with no overlap, clipping or sideways scroll |
| 1.4.3 Contrast (min) | AA | 4.5:1 or higher — deep purple `#564298` for text, never pale purple or grey on white |
| 1.4.1 Use of Colour | A | Never colour alone — a form error needs an icon and text, not just a red outline |
| 2.4.7 Focus Visible | AA | Every interactive element has a clear, high-visibility keyboard focus state |
| 3.3.2 Labels / Instructions | A | Permanent visible labels outside each field — never placeholder-only |
| 2.5.5 Target Size | AAA | Enlarge tap targets so shaky hands hit them first time (~48px) |

*(Conformance target upgraded 2.1 → 2.2 — WCAG 2.2 is purely additive, and the nine new criteria are mapped to this build in the ♿ Accessibility Standards section below.)*

### 🔠 Type & legibility for older eyes

- [ ]  Sans-serif only (Poppins + Open Sans — already your brand)
- [ ]  No ALL-CAPS for reading text (it slows word recognition), no underlines except real links, bold only for sparse emphasis
- [ ]  Line spacing at least 1.5× the font size — the safer floor for older readers
- [ ]  Body text set in rem/em so it scales cleanly

### 📐 Spacing & layout system

*The rules behind the regenerated tablet/mobile wireframes — carry these into the coded theme so the spacing never drifts.*

- [ ]  **8pt spacing tokens** — define the scale once as CSS variables (`--space-1: 8px` → `--space-8: 64px`) and only ever use those, so spacing stays systematic instead of "whatever looked right that day"
- [ ]  **Consistent section rhythm** — one uniform gap between page sections (≈32px on desktop/tablet, ≈24px on mobile)
- [ ]  **Internal ≤ external** — padding *inside* a card is always smaller than the gap *between* cards, so related items visually group together
- [ ]  **Cap the text column** — max-width on prose of ~65–75 characters, even inside the wider page container, so desktop paragraphs never balloon
- [ ]  **Touch target size** — every tappable element ≥44–48px: links, town chips, accordion/FAQ rows, form fields, sticky-bar buttons
- [ ]  **Touch target spacing** — ≥8px clear gap between adjacent targets (footer link lists, inline links, chips) so a shaky tap can't hit the wrong one (WCAG 2.5.8)
- [ ]  **Fluid type with `clamp()`** — font sizes scale smoothly between breakpoints instead of jumping, and the layout holds at 200% zoom
- [ ]  **Reserve a fixed-height slot for the live CQC widget** — it loads from CQC's servers, so give it a fixed container or it shoves the page down as it loads (protects CLS *and* an older reader's place on the page)

*Sources: [8-point grid](https://medium.com/built-to-adapt/intro-to-the-8-point-grid-system-d2573cde8632), [Cieden spacing](https://cieden.com/book/sub-atomic/spacing/spacing-best-practices), [Red Hat spacing](https://ux.redhat.com/foundations/spacing/), [Baymard line length](https://baymard.com/blog/line-length-readability), [WCAG 2.5.8](https://www.w3.org/WAI/WCAG22/Understanding/target-size-minimum.html), [NN/g touch targets](https://www.nngroup.com/articles/touch-target-size/), [Material 3](https://m3.material.io/foundations/designing/structure).*

### ⚡ Performance (Core Web Vitals)

Families often browse on mobile or older devices, so speed is part of the trust window:

- [ ]  Fast First Contentful Paint and minimal layout shift (CLS)
- [ ]  Lazy-load and compress images and video

### 📝 Enquiry form standards (don't lose them at the final hurdle)

*The form is the last gate, and older users abandon clumsy ones fast.[[TechGuilds]](https://www.techguilds.com/blog/accessible-web-forms-for-older-adults/)*

- [ ]  **Single column**, top to bottom — multi-column forms make people miss fields
- [ ]  **Multi-step** for anything longer (e.g. a care assessment), with a "Step 1 of 4" progress bar to shrink the perceived effort
- [ ]  **Forgiving inputs** — accept phone numbers with spaces, brackets or dashes; never reject on hidden formatting rules
- [ ]  **Kind, plain-English errors** shown right next to the field ("Please include your area code"), never a cryptic code or a summary box buried at the top
- [ ]  **Permanent labels** outside every field, never placeholder-only
- [ ]  GDPR-safe, and only ask for what you genuinely need

### 🧭 Smarter contact (phase 2, optional)

- [ ]  Consider a short "qualifying" enquiry form (who's it for? what support? what timeline?) that routes leads — gentler than a blank form, and it separates complex-care from companionship enquiries

*Sources: [Homecare Boost](https://www.homecareboost.com/guides/home-care-marketing/how-to-design-a-home-care-website), [Alora Health](https://www.alorahealth.com/website-seo-tips-for-home-health-care-agencies/), [W3C WAI](https://www.w3.org/WAI/older-users/developing/), [Koru UX](https://www.koruux.com/ux-wcag-accessibility/).*

---

## 🧑‍💻 Coding Standards — The House Style for the Theme

*The WordPress core coding standards — the official house rules for hand-coded themes — plus the CSS craft references they're built on, distilled for this rebuild. Lock these in before the first line of theme code gets written.*

<aside>
✍️

**The one-line philosophy (idiomatic-css):** all code in a codebase should look like a single person typed it. Consistency beats personal preference — choose each rule once, then follow it without fail; if in doubt, use existing, common patterns.[[idiomatic-css]](https://github.com/necolas/idiomatic-css)

</aside>

### 🎨 CSS rules (WordPress core standard)

- **Structure** — indent with **tabs, not spaces** · one selector per line · one property–value pair per line · closing brace flush left, in line with the start of the selector · two blank lines between sections, one between blocks
- **Selectors** — `lowercase-with-hyphens` (never camelCase or underscores) · human-readable names that describe what they style · double quotes in attribute selectors (`input[type="text"]`) · never over-qualify: `.container`, not `div.container`
- **Properties** — everything lowercase except font names · shorthand hex (`#fff`, never `#FFFFFF`) · `rgba()` only when opacity is actually needed · use shorthand for `background`, `border`, `font`, `margin`, `padding` — except when deliberately overriding one value
- **Property order (grouped, never random)** — Display → Positioning → Box model → Colours & Typography → Other · margins/paddings in TRBL order (top, right, bottom, left)
- **Values** — space after the colon, semicolon on every declaration · numeric font weights (`700`, never `bold`) · no units on `0` values · leading zero on decimals (`0.5`, not `.5`)
- **Media queries** — keep them grouped at the bottom of the stylesheet, rule sets indented one level · test above *and* below every breakpoint
- **Commenting** — comment liberally · long stylesheets get a numbered table of contents (`1.0`, `1.1`, `2.0`…) so sections are jump-searchable · break comment lines at ~80 characters
- **Best practices** — try to *remove* code before adding more · no magic numbers (one-off fixes like `margin-top: 37px`) · style the element itself, don't reach through parents (`.highlight`, not `.highlight a`) · prefer `line-height` over fixed `height` · never restate browser defaults

[[WP CSS standards]](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)

### 📏 Why line-height must be unitless (the Meyer rule)

- `line-height: 1.5em` computes to a **fixed pixel value** that's passed down to every descendant — so a big heading inherits a line height sized for body text and overlaps itself.
- Unitless `line-height: 1.5` is inherited as a **scaling factor** instead: every element multiplies it by its *own* font size. Same number, completely different (correct) behaviour.
- This is the *why* behind the 1.5 line-spacing rule already in Build Standards — on `body`, always `1.5`, never `1.5em`.[[Meyer]](https://meyerweb.com/eric/thoughts/2006/02/08/unitless-line-heights/)

### 🐘 PHP naming conventions (WordPress core standard)

| **Thing** | **Convention** | **CCS example** |
| --- | --- | --- |
| Functions & variables | `lowercase_underscores` — never camelCase, no cryptic abbreviations | `ccs_get_service_card()`, `$enquiry_type` |
| Classes, traits, interfaces | `Capitalized_Words_With_Underscores`; acronyms ALL CAPS | `CCS_Schema_Output` (like core's `WP_HTTP`) |
| Constants | ALL CAPS with underscores | `CCS_THEME_VERSION` |
| Hooks (actions & filters) | `lowercase_underscores`; dynamic parts interpolated in double quotes | `do_action( "ccs_{$service}_loaded" )` |
| File names | lowercase, hyphen-separated | `front-page.php` |
| Class files | `class-` prefix, class-name underscores become hyphens | `class-ccs-schema-output.php` |
- **Prefix everything** — a unique `ccs_` prefix on functions, hooks and constants is the theme's namespace; it's what stops a plugin function with the same name from white-screening the site.
- **Quick hygiene set from the same standard:** single quotes unless interpolating · `require_once` for unconditional includes · full `<?php` tags only, never shorthand · braces on every block, even one-liners · tabs for indentation here too.

[[WP PHP standards]](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/#naming-conventions)

### 🧰 Pre-launch CSS audit (WordPress's own tool)

- **css-audit** is the npm CLI WordPress uses to health-check its own core CSS — run it over the finished theme stylesheets: `npm run css-audit -- css/* --all`
- It reports: unique colour count · `!important` usage · top-10 most specific selectors · `display: none` instances · media-query and typography breakdowns.
- **The CCS pass marks:** colours = the 6 brand tokens + the semantic set and *nothing else* (anything extra = palette drift) · `!important` ≈ 0 · specificity stays flat — the "max 1 level of nesting" rule keeps it that way.[[css-audit]](https://github.com/WordPress/css-audit)

---

### 🐘 PHP — the full house rules

Beyond naming, the official PHP standard sets the whole style. The non-negotiables for the CCS theme:

- **Yoda conditions** — `if ( true === $the_force )`: constant on the left, so a missed `=` becomes a parse error instead of a silent bug
- **Braces always**, even for one-line `if`s — and use `elseif`, never `else if`
- **Long array syntax** — `array( 1, 2, 3 )`, not `[ 1, 2, 3 ]`: the standard explicitly calls it more readable, particularly for people with vision difficulties (very on-brand for CCS)
- **Tabs for indentation**, spaces only for mid-line alignment; multi-item arrays get one item per line with a trailing comma
- **Single quotes** unless evaluating something in the string — alternate quote styles instead of escaping
- **Full `<?php` tags only** — shorthand `<?` and `<?=` are banned
- **Database safety** — any direct query goes through `$wpdb->prepare()` with `%s` / `%d` placeholders, escaping as close to the query as possible
- **The never-list** — `eval()`, `extract()`, `goto`, the backtick shell operator, lazy `@` error suppression, and assignments inside conditionals
- **Readability beats cleverness** — prefer self-explanatory string flags (`'slowly'`) over mystery booleans (`true`)

### 🌐 HTML — markup rules

- **Always quote attribute values** — unquoted attributes are a genuine security vulnerability, not just untidy
- Boolean attributes stay bare: `disabled`, never `disabled="true"`
- All tags and attributes lowercase; human-readable values (like `title` text) keep proper capitalisation
- Self-closing tags get exactly one space before the slash: 
``
- Run every finished template through the **W3C validator** before launch
- Indentation mirrors logical structure (tabs) — and embedded PHP blocks match the surrounding HTML's indent level

### 🟨 JavaScript — house style

Same "one person typed it" philosophy, adapted from the jQuery style guide:

- `const` / `let`, never `var` in new code — `const` unless the value gets reassigned, declared at first use
- **Strict equality only** — `===`, never `==`
- camelCase for variables & functions · `UpperCamelCase` for classes & constructors · `SCREAMING_SNAKE_CASE` for true constants · acronyms stay capitalised (`currentDOMDocument`)
- Single quotes for strings; **semicolons always** — never rely on automatic semicolon insertion
- "When in doubt, space it out" — spaces inside parens and brackets: `foo( arg )`, `arr[ 0 ]`
- Braces on every `if` / `for` / `while`, always multi-line; tabs for indentation; lines ~80 chars (100 max)

### 📝 Inline documentation — code that explains itself

<aside>
💬

**The golden rule:** every function, hook, class and file gets a DocBlock. Summaries are *third-person singular* — "Displays the booking form." not "Display the booking form." (Test: it should still make sense with "It…" in front.)

</aside>

The PHPDoc template for every CCS theme function:

```php
/**
 * Summary — what it does, one sentence, full stop.
 *
 * @since 1.0.0
 *
 * @param type $var Optional. Description. Accepts 'x', 'y'. Default 'x'.
 * @return type Description.
 */
```

- **`@since` is a living changelog** — add a new `@since` line (version + sentence) whenever arguments or behaviour change
- **File headers on every file** — a summary DocBlock saying what the file is for and where it fits
- **`@package` is the theme name** (e.g. `CCS_Theme`) — `@package WordPress` is reserved for core and must not be used by themes
- **No `@author` tag** — WordPress policy, to avoid implying ownership over code
- Inline comments: `// Sentence case with a period.` — multi-line comments open with `/*`, never `/**` (double-asterisk is reserved for DocBlocks and confuses parsers)
- JavaScript mirrors all of this with **JSDoc** — `/**` blocks with `@since`, `@param`, `@return`
- *Why bother?* Documentation is a gift to future-you — six months from now, the DocBlock is the only one who remembers why the function exists

### 🤖 Repo hygiene (if the theme lives on GitHub)

- Repo docs (README etc.) follow the WP Markdown style guide — `_italic_`, `**bold**`, fenced code blocks with language tags
- If you ever add GitHub Actions CI: start every workflow with `permissions: {}` and grant per-job read-only access, pin third-party actions to full commit SHAs, set `persist-credentials: false` on checkout, and pass untrusted values (PR titles, issue text) through `env:` variables — never straight into `run:` (template injection)

## 🔍 SEO Architecture — Pillars, Clusters & Internal Links

*Good news: the site-structure map above already is a topic-cluster architecture — this section names the strategy, adds the linking rules that make it rank, and checks it against Google's own documentation and live UK search data.*

<aside>
🏛️

**The big idea — topical authority.** Google rewards sites that cover a topic comprehensively and credibly (E-E-A-T: Experience, Expertise, Authoritativeness, Trust). A pillar page gives the broad overview; cluster pages go deep on each subtopic; internal links tie them together so visitors and Google both understand "this site is an authority on home care in Kent." That's how a small local site outranks national brands on its own turf.

</aside>

### 🏛 The CCS pillar–cluster map

| **Pillar page** | **Cluster pages** | **Pillar type** |
| --- | --- | --- |
| Care Services hub | Personal Care · Companionship · Respite · every service page | Guide |
| Areas We Cover hub | West Malling · Aylesford · Snodland · every town page | Local pillar |
| Advice & Resources hub | "What is home care?" · funding guides · how-to articles | What-Is + How-To |
- **Rules of the model:** every cluster page links up to its pillar, the pillar links down to every cluster, and related clusters link sideways to each other
- **Plan by topic, not keyword list** — one page per search intent. This is what prevents keyword cannibalisation (two of your own pages fighting for the same query)
- Pillar pages attract the most backlinks because they're the most comprehensive — make the Advice hub genuinely the best "home care 101" resource in Kent

### 🔗 Internal links — how authority flows

- **PageRank is alive** — still one of Google's core ranking systems (2024-leaked internals showed several variants in active use). Internal links pass that authority page-to-page
- The homepage holds the most authority → deliberate links from it to the pages that need to rank (services + towns)
- **Anchor text:** brief (five words or fewer), descriptive, keyword-rich — never "click here"
- The 3-click rule is also a *ranking* rule — Google treats deeper pages as less important
- Links placed in real content carry more weight than footer link dumps (the "reasonable surfer" principle)
- **Pre-launch link audit:** no broken links · no orphan pages · no pages with a single incoming link · no internal redirect chains · no nofollow on internal links · everything HTTPS

### 🤖 What Google's own docs say to optimise for

- **"Helpful content" is now core ranking** — original, people-first content wins; content written for search engines gets demoted. The calm, genuinely useful Advice hub is exactly the right strategy
- **Site diversity system:** Google rarely shows more than two pages from one site per query — every town page must earn its own spot with genuinely local content, and the deduplication system punishes near-identical pages (no find-and-replace-the-town-name templates!)
- **Exact-match domains get no bonus** — a "kent-home-care" domain wouldn't help; build the brand instead
- Care is health-adjacent (**YMYL**) — Google's reliable-information systems elevate demonstrably trustworthy pages, so accuracy and citing NHS / CQC / [gov.uk](http://gov.uk) sources matter extra

### 🧰 Google's starter checklist for the build

- **Descriptive URLs** grouped in directories: `/care-services/personal-care/`, `/areas/west-malling/` — URL words appear as breadcrumbs in results
- **Unique title tag + meta description per page** — title = page topic + CCS (+ town); the meta description often becomes the search snippet
- **One canonical URL per piece of content** — redirect any duplicates
- **Images near relevant text with descriptive alt text** — an accessibility and SEO win in one
- **Structured data** — LocalBusiness, FAQ and Breadcrumb markup unlock richer results (ties straight into the schema item in Build Standards)
- Submit an **XML sitemap** and verify indexing in Search Console
- Link out to sources you trust (NHS, CQC, [gov.uk](http://gov.uk)); add `nofollow` to anything you can't vouch for

### 📈 What UK searchers actually type (Google Trends, past 3 months)

- **"home care near me", "care at home", "in home care"** lead the generic queries → confirms the town-page strategy and "near me" local optimisation
- **The market searches "care home" more than "home care"** — and "what is a residential care home" is rising. A comparison guide ("Care home vs home care: what's the difference?") is a high-demand What-Is cluster page that educates *and* captures the wrong-term searchers
- **"home care jobs" sits in the UK top 5** → validates keeping the recruitment funnel separate and strong
- Rising queries are dominated by **named providers** — people search brands by name, so the homepage must own the "Continuity of Care Services" result (clear site name, good title links, favicon)
- **"care home negligence solicitors" is rising** — safety anxiety is live in this market; the CQC-led trust strategy answers exactly that fear

## ♿ Accessibility Standards — The Official Rulebook

*The W3C documents behind the WCAG matrix in Build Standards — which one to open when, what changed in WCAG 2.2, and the ARIA rules for the few hand-coded components that need them.*

<aside>
🎯

**Conformance target: WCAG 2.2 AA, with hand-picked AAA upgrades.** WCAG 2.2 is purely additive — conform to 2.2 and you conform to 2.1 automatically — and W3C recommends adopting 2.2 even where formal obligations still cite older versions. But blanket AAA across a whole site is *officially not recommended* (some AAA criteria can't be satisfied for all content), so CCS keeps doing exactly what it's doing: **AA as the floor, plus chosen AAA wins for older readers** (7:1 contrast, ~48px targets).[[WCAG 2.2]](https://www.w3.org/TR/WCAG22/)[[Conformance]](https://www.w3.org/WAI/WCAG21/Understanding/conformance#levels)

</aside>

### 🗺️ The W3C document map — which one to open when

| **Document** | **What it is** | **When to open it** |
| --- | --- | --- |
| [WCAG 2.2 spec](https://www.w3.org/TR/WCAG22/) | The normative standard — testable success criteria at three stacking levels: A → AA → AAA | Settling "what exactly does the rule say" |
| [How to Meet (Quickref)](https://www.w3.org/WAI/WCAG22/quickref/) | The whole standard as a filterable checklist, with sufficient techniques + documented failures per criterion | **The daily build reference** — filter to 2.2 and your levels |
| [Understanding WCAG 2.2](https://www.w3.org/WAI/WCAG22/Understanding/) | Per-criterion intent, who benefits, and worked examples | When a criterion feels ambiguous for your case |
| [Techniques](https://www.w3.org/WAI/WCAG21/Understanding/understanding-techniques) | Sufficient = reliable ways to pass · Advisory = nice extras · Failures = proven ways to fail. Techniques are **informative, not required** — only the success criteria are; failures, though, *do* prove non-conformance | Picking an implementation route, or checking a suspected fail |
| [WAI-ARIA 1.2](https://www.w3.org/TR/wai-aria/) | The vocabulary of roles, states & properties that feeds assistive technology | Reference when a component genuinely needs ARIA |
| [ARIA APG](https://www.w3.org/WAI/ARIA/apg/) | The living pattern library — accessible components with full keyboard-interaction specs, landmarks, accessible names | Building the mobile menu & FAQ accordions |
| [Using ARIA](https://www.w3.org/TR/using-aria/) | Now a discontinued draft — but its rules of ARIA use live on (below); APG replaced it | The rules below; go to APG for patterns |
| [ATAG 2.0](https://www.w3.org/WAI/standards-guidelines/atag/) | Accessibility of *authoring tools* themselves | Judging WordPress & plugins, not the site (below) |

<aside>
📄

**Conformance fine print worth knowing:** conformance is **full-page only** — "the page conforms except the widget" doesn't exist — and **every responsive variation** of a page (desktop, tablet, mobile) must conform. That puts the third-party live CQC widget inside conformance scope on every page it appears on, so its rendered markup needs checking too.[[Conformance]](https://www.w3.org/WAI/WCAG21/Understanding/conformance#levels)

</aside>

### 🆕 New in WCAG 2.2 — mapped to this build

| **New criterion** | **Level** | **What it means for CCS** |
| --- | --- | --- |
| 2.4.11 Focus Not Obscured (Minimum) | AA | The sticky header and mobile call/book bar must never hide the keyboard focus — tab through every template with both pinned |
| 2.5.7 Dragging Movements | AA | Nothing may *require* dragging — if a carousel ever sneaks in, it needs prev/next buttons |
| 2.5.8 Target Size (Minimum) | AA | ≥24×24px is now the legal floor for targets — the CCS 48px house rule clears it twice over |
| 3.2.6 Consistent Help | A | The phone number + contact route must sit in the same relative place on every page — the persistent header number is now a conformance requirement, not just good UX |
| 3.3.7 Redundant Entry | A | A multi-step enquiry form must never re-ask for info already given in the same session — carry answers through the qualifying form's steps |
| 3.3.8 Accessible Authentication (Minimum) | AA | No cognitive tests (puzzles, transcription) to log in or submit — use honeypot anti-spam, never puzzle CAPTCHAs, on the form and WP login |
| 2.4.12 · 2.4.13 · 3.3.9 (Focus Not Obscured Enhanced · Focus Appearance · Accessible Auth Enhanced) | AAA | Optional polish — the generous visible focus states already planned cover the spirit of these |

*(Also: old 4.1.1 Parsing is obsolete and removed in 2.2 — one less thing.)*

### 🦮 The rules of ARIA (for the few components that need it)

<aside>
⚠️

**No ARIA is better than bad ARIA.** ARIA changes what assistive tech *believes* about an element — it adds zero behaviour. Give something `role="button"` and you've promised screen-reader users a button; you still have to script the focus and the Enter/Space handling yourself.

</aside>

1. **Use native HTML first** — if `<button>`, `<nav>`, `<details>` or a real `<label>` does the job, use it instead of re-purposing a `<div>` with a role bolted on.
2. **Don't change native semantics** — wrap instead: `<div role="tab"><h2>…</h2></div>`, never `<h2 role="tab">`.
3. **Every interactive ARIA control must work by keyboard** — focusable, and operable with Enter *and* Space for buttons.
4. **Never put `role="presentation"` or `aria-hidden="true"` on a focusable element** — it makes people focus on "nothing". (`display: none` already removes things from the accessibility tree — no extra attributes needed.)
5. **Landmarks + accessible names (the APG layer)** — semantic regions (`header`, `nav`, `main`, `footer`), exactly one `main`, and a distinguishing label wherever a landmark repeats (main nav vs footer nav).

**Where this build actually needs ARIA:** the mobile menu and FAQ accordions (APG disclosure pattern — `aria-expanded` + `aria-controls` on the trigger) and form errors (`aria-describedby` per field + a status message screen readers announce). Everything else: plain semantic HTML.[[Using ARIA]](https://www.w3.org/TR/using-aria/)[[APG]](https://www.w3.org/WAI/ARIA/apg/)

### 🛠 ATAG — the standard that judges WordPress, not your site

- ATAG covers **authoring tools**: Part A — the tool's own UI must be accessible to authors with disabilities; Part B — the tool must enable, support and guide *all* authors toward producing accessible (WCAG) content. CMSs are explicitly in scope.
- For this build: visitors' experience is judged by **WCAG**; **ATAG** is the lens for the tooling — when weighing plugins or admin features, prefer ones that nudge accessible output (e.g. prompting for alt text). Also a tidy citation for uni work on tooling choices.[[ATAG]](https://www.w3.org/WAI/standards-guidelines/atag/)

### ✅ Additions to the per-template launch pass

- [ ]  Tab through each template with sticky header + mobile bar pinned — focus never hidden (2.4.11)
- [ ]  Every target ≥24px (house rule: 48px), including inline links sitting close together (2.5.8)
- [ ]  Phone + contact route in identical relative position on every page (3.2.6)
- [ ]  Multi-step form never re-asks for anything (3.3.7) · no drag-only interactions (2.5.7)
- [ ]  Honeypot anti-spam instead of puzzle CAPTCHAs on the form and WP login (3.3.8)
- [ ]  Run css-audit on the finished stylesheets — 6 brand tokens + semantic set, near-zero `!important`, flat specificity
- [ ]  ARIA five-rules pass on the mobile menu, FAQ accordions and form errors

---

## 🎓 Learning Lab — Theory, Taste & This Build

*Three activities make a designer learn faster: theory (read + actually summarise), practice (build things — this entire HQ is the practice arm), and taste (review great work daily until you can say why it's good). Every theory topic below is summarised and tied back to a real decision already on this page — theory sticks when it's attached to a thing you made.*

<aside>
🧠

**How to use this (ADHD-proof):** one toggle a day, that's it. Read the summary, find the **On this build** line, then scroll to that part of the HQ and look at the decision with fresh eyes. Twenty minutes, done, dopamine.

</aside>

```mermaid
mindmap
  root((Learn design faster))
    Theory
      Foundations
        Web design history
        Visual hierarchy
        Typography
        Grid and composition
        8pt spacing system
      Patterns
        Laws of UX
        NNg heuristics
        Rams and Norman
      Process
        UX research flow
        Job stories
        Design systems
        Dev handoff
      Context
        Branding
        Site types
        UX writing
        Mobile and adaptive
        Web2 vs Web3
        AI and UX
    Practice
      This CCS rebuild
    Taste
      Daily reference diet
      Curated galleries
      Say out loud why it is good
```

### 1 · History of web design

- **The arc:** text-only pages (1991) → table-and-spacer-GIF layouts → Flash maximalism → CSS standards and the glossy "web 2.0" look → responsive design (Ethan Marcotte, 2010) → flat design and mobile-first → design systems and tokens → AI-assisted design now.
- **The pattern behind the arc:** every era is a response to a constraint — bandwidth, browsers, screen sizes, then team scale. Understand the constraint and the style of each era makes sense.
- **Why it matters:** trends are cyclical (gradients came back!), but *conventions accumulate* — logo top-left, nav on top, footer sitemap — and breaking them costs real usability.
- **On this build:** the CCS site is a textbook current-era build — responsive, token-driven, conventional layout, with warmth coming from colour and type rather than decoration.

### 2 · Visual hierarchy

- **Definition:** controlling what the eye sees first, second and third — using size, weight, colour, contrast, position and whitespace.
- **Scanning patterns:** people scan F-shapes on text-heavy pages and Z-shapes on sparse ones; the top-left of every block earns the most attention.
- **The squint test:** blur your eyes at any design — whatever still stands out is your *real* hierarchy. If everything stands out, nothing does.
- **One level at a time:** each screen gets exactly one primary element, a few secondary ones, and quiet everything else.
- **On this build:** hero promise → trust strip → single deep-purple CTA is a deliberate hierarchy; every other button stays lighter so the CTA wins the squint test on all 12 wireframes.

### 3 · Typography — print & web

- **Web design is ~95% typography** — type does most of the communicating, so type decisions are design decisions.
- **Core controls:** typeface, a modular size scale (never random sizes), weight, line-height (1.5 for body), line length (50–75 characters) and letter spacing.
- **Print vs web:** print is fixed; web type must survive zoom, reflow and unknown screens — so relative units (rem/em) and a 200% zoom test are non-negotiable.
- **Performance is typography too:** every font weight is a download — limit weights and use font-display swap so text never vanishes while loading.
- **On this build:** Poppins headlines + Open Sans body, rem-based, 1.5 line-height, capped line length — all already locked into Build Standards above.

### 4 · Grid & composition

- **The grid is the silent designer:** a 12-column grid with consistent gutters makes alignment decisions for you and keeps pages calm.
- **Gestalt principles do the grouping:** proximity (close = related), similarity, alignment and common region — spacing communicates relationships before a single word is read.
- **Whitespace is a material,** not leftover space — it directs the eye and lowers cognitive load, which is critical for stressed visitors.
- **Composition tools:** balance (symmetry = calm, asymmetry = energy), repetition for rhythm, contrast for emphasis.
- **On this build:** the wireframes run consistent gutters and the internal-≤-external padding rule, so cards group visually without drawing boxes around everything.

### 5 · The 8-point system (and its 4pt / 5pt cousins)

- **The idea:** every spacing and sizing value is a multiple of 8; a 4pt half-step exists for fine detail like icon padding and type tweaks; 5pt/10pt systems suit 10-based brands.
- **Why 8 specifically:** most screen dimensions divide cleanly by 8, so layouts scale crisply across pixel densities — and a fixed menu of values ends every "13px or 15px?" debate forever.
- **In practice:** define the scale once as tokens (8, 16, 24, 32, 48, 64) and only ever pick from the menu — consistency comes free.
- **On this build:** the regenerated tablet/mobile wireframes and the Spacing & layout system checklist are 8pt end-to-end.

### 6 · UX patterns — the big one ⭐

- **From [Laws of UX](https://lawsofux.com/) — the must-memorise set:**
    - **Hick's Law** — more choices = slower decisions. One primary action per page.
    - **Fitts's Law** — big, close targets are faster and easier to hit. Size *and* position matter.
    - **Jakob's Law** — users spend most of their time on *other* sites; they expect yours to work the same way.
    - **Miller's Law** — working memory holds ~7 items; chunk everything.
    - **Peak-End Rule** — people judge an experience by its peak and its ending. The enquiry form *is* the ending — make it kind.
    - **Von Restorff effect** — the one different-looking thing gets remembered. Spend your "different" on the CTA, nowhere else.
    - **Aesthetic-Usability effect** — beautiful things are *perceived* as easier to use and forgiven more.
- [**NN/g's 10 usability heuristics](https://www.nngroup.com/articles/ten-usability-heuristics/):** visibility of system status · match to the real world · user control · consistency · error prevention · recognition over recall · flexibility · minimalist design · good error recovery · help and documentation. Use them as a review checklist against every wireframe.
- **On this build:** single CTA (Hick's + Von Restorff) · 48px targets (Fitts's) · conventional nav and footer (Jakob's) · five service cards (Miller's) · forgiving, kind forms (Peak-End).

```mermaid
mindmap
  root((UX patterns))
    Hicks Law
      Fewer choices, faster decisions
      CCS - one CTA per page
    Fitts Law
      Big close targets win
      CCS - 48px tap targets
    Jakobs Law
      Familiar beats clever
      CCS - conventional nav and footer
    Millers Law
      Chunk into about 7
      CCS - five service cards
    Peak End Rule
      The form is the end - make it kind
    Von Restorff
      One thing stands out
      CCS - the deep purple CTA
    Aesthetic Usability
      Beautiful feels easier to use
```

### 7 · Rams & Norman — the design canon

- **Dieter Rams' 10 principles:** good design is innovative, useful, aesthetic, understandable, unobtrusive, honest, long-lasting, thorough to the last detail, environmentally friendly — and *as little design as possible*.
- **Don Norman (The Design of Everyday Things):** affordances (what a thing lets you do), signifiers (what *tells* you it does that), feedback, mapping, and designing for error — people don't make mistakes, designs do.
- **The shared core:** clarity is kindness. If someone has to think about *how* to use it, the design has quietly failed.
- **On this build:** permanent form labels = signifiers · forgiving phone inputs = designing for error · the one-message-per-screen rule = as little design as possible.

### 8 · Mobile, adaptive & iOS vs Android

- **Responsive vs adaptive:** responsive fluidly reflows one layout; adaptive serves fixed layouts per breakpoint. The modern default is responsive, **mobile-first** — style the small screen first, enhance upward.
- **iOS (Human Interface Guidelines) vs Android (Material Design):** 44pt vs 48dp minimum targets, tab bar vs bottom nav/drawer idioms, SF vs Roboto type systems, different back-button behaviour.
- **Rule of thumb for the web:** you're platform-neutral, so follow the *stricter* guideline (48px) and web conventions rather than either OS.
- **The thumb zone:** the bottom of a phone screen is the easy reach — put the primary action there.
- **On this build:** the tablet/mobile wireframes are mobile-first thinking drawn out — sticky call/book bar in the thumb zone, 48px targets, no hover-dependent menus.

### 9 · Design libraries & UI kits

- **What it is:** one source of truth for tokens (colour, type, spacing), components (buttons, cards, fields) and patterns (forms, navs) — mirrored in the design tool and in code.
- **Why it's required:** consistency (learn the button once, trust it everywhere), speed (compose instead of redraw) and quality (fix once, fixed everywhere). It's how design survives more than one person and more than one month.
- **The hierarchy:** tokens → components → patterns → pages. Change a token and the whole site updates.
- **On this build:** the Design Language section + the spacing-token checklist are the seed of exactly this; the NHS Design System is the benchmark library for this audience.

### 10 · Dev handoff & UX review

- **A good handoff is a conversation, not a file drop:** annotated designs, named tokens, real content (never lorem ipsum), and the edge states designers forget but devs hit first — empty, loading, error, long-text.
- **Spec the behaviour, not just the picture:** what happens on tap, on error, on a slow connection, at 200% zoom?
- **UX review:** before anything ships, walk the built thing against the NN/g heuristics and the design side by side — catch drift early, kindly.
- **On this build:** the numbered build notes on every wireframe *are* the handoff annotations, and the Build Standards checklists are the review rubric.

### 11 · Branding & brand research

- **Brand is a trust shortcut** — the feeling people get before reading a word. It's positioning, voice, colour, type and *behaviour*, not just a logo.
- **How it affects web/business/UX:** consistent brands are perceived as more credible and convert better; brand sets the emotional baseline that the UX must then honour — a calm brand with a pushy popup is a broken promise.
- **Brand research in practice:** competitor audits, attribute mapping (calm vs urgent, premium vs accessible), and testing whether the identity triggers the associations you intend.
- **On this build:** Mint Purple is doing strategic work — purple = dignity, mint = freshness, off-white = calm — and the whole HQ enforces brand-as-behaviour: no crisis framing, one gentle CTA, no staged faces.

### 12 · UX research — when and how

- **When it's required:** when the cost of being wrong is high, when the audience isn't you, or when data and opinions disagree. **When it's not:** questions a heuristic already answers, or researching as a way to avoid shipping.
- **The classic flow:** discovery → personas → customer journey map → job stories → wireframes → visuals → usability testing → A/B testing and other conversion methods.
- **Cheap beats perfect:** five users surface ~85% of usability problems; a hallway test today beats a lab study next quarter.
- **A/B testing honestly:** one variable, enough traffic, a real hypothesis — otherwise it's astrology with dashboards.
- **On this build:** Responsible Rachel (persona), the CCS Care Journey table (CJM), the wireframes and the psychology-levers table are this exact flow, already run in order.

### 13 · Job stories ⭐

- **The format:** *When* (situation), *I want to* (motivation), *so I can* (outcome). Context-first and demographics-free.
- **Why they beat vague personas:** they capture the *trigger moment* — the same person needs completely different things in different situations.
- **A CCS one:** "*When* I notice Mum struggling with mornings after her fall, *I want to* understand what home care involves and costs, *so I can* bring my family a plan instead of a panic."
- **Use them to design:** every page template should answer one job story above the fold.
- **On this build:** the Jobs-to-be-Done lever in the psychology table and the journey-stage table are job-story thinking applied.

### 14 · Site types & their construction

- **E-commerce:** browse → product page → cart → checkout; conversion lives in product-page clarity and checkout friction.
- **B2B / SaaS product:** longer cycle — landing → features/pricing → proof → demo or trial; several stakeholders read the same page with different questions.
- **Landing page:** one audience, one message, one CTA — everything else deleted.
- **Content / social:** engagement loops, feeds, infinite scroll — different metrics, different ethics.
- **CCS is a local lead-gen service site:** closest to a *system of landing pages* — families land deep from Google, so every template stands alone as its own converter, with trust doing the work a checkout never could.

### 15 · UX writing

- **The interface is a conversation** — labels, buttons and error messages are the script. Many "design" problems are actually wording problems.
- **The rules:** verb-first buttons ("Book a free consultation", never "Submit") · front-load keywords · plain language around grade-7 reading level · errors that say what happened *and* how to fix it · never blame the user.
- **Microcopy converts:** the words beside a field ("no obligation, no rush") often move conversion more than the button colour does.
- **On this build:** the single CTA wording, the reassurance strips, and the kind plain-English error standards in the form checklist are all UX-writing decisions.

### 16 · Web2 vs Web3 patterns

- **Web2:** accounts and passwords, central servers, trust the provider, recover by email — the patterns every user already knows.
- **Web3:** wallets instead of accounts, transactions with gas fees, no password resets (lose the key, lose everything), trust the protocol instead of the company — which makes onboarding brutally hard UX.
- **What transfers:** the heuristics don't change — feedback, error prevention and recognition matter *more* when actions are irreversible.
- **On this build:** nothing — and that's the lesson. CCS's audience needs maximum familiarity (Jakob's Law), so Web2 conventions all the way.

### 17 · AI × UX/UI

- **AI as a design tool:** ChatGPT-class models for copy drafts, research synthesis and job stories; Midjourney-class for moodboards; code generation for instant prototypes. Iteration speed goes up enormously — so **taste becomes the bottleneck and the differentiator**.
- **The UX *of* AI products:** designing for uncertainty — confidence cues, easy undo, human handoff, and never pretending the machine is sure when it isn't.
- **The risk:** AI output is convincingly *average*; without strong pattern knowledge and references you can't tell good from merely plausible.
- **On this build:** this whole HQ — research, wireframes, spacing passes — is AI-assisted practice; the human jobs were the brief, the guardrails and the taste calls. That's the new shape of the role.

### ➕ Optional · Product work, agile & the designer's role

- **Dual-track agile:** discovery (research, wireframes) runs one sprint ahead of delivery (build, QA), so devs are never waiting on undesigned work.
- **Ceremonies that matter to design:** backlog refinement (scope design work early), sprint review (show the real thing), retro (fix the process, not the people).
- **The designer's real job in a team:** be the user's lawyer in every trade-off conversation, and make the cheap version of any idea testable *fast*.

### 🧰 Cheat sheets — distilled quick reference

*The cheat sheets from your swipe file, translated into CCS terms. Drag the original images in under each toggle if you want them side by side.*

### 🔘 Buttons — anatomy, styles & states

- **Action hierarchy:** Primary · Secondary · Tertiary · CTA · FAB — each page should use the hierarchy deliberately, never five buttons shouting at once.
- **CCS mapping:** Primary/CTA = the one deep-purple `#564298` "Book a free consultation" button · Secondary = soft lavender fill (e.g. "Call us") · Tertiary = ghost/outline for low-stakes actions ("Read more") · **skip the FAB** — the mobile sticky call/book bar already owns that job, and floating round buttons confuse older users.
- **Styles to know:** solid, text, ghost, flat, raised, icon, icon + text, dropdown, round, toggle. CCS default = solid + rounded corners; always **icon + text, never icon-only** (the permanent-labels rule).
- **Design EVERY button in all its states:** enabled · hover · **focused** · pressed · disabled · selected. The focused state isn't optional — it's WCAG 2.4.7 Focus Visible from the accessibility matrix above. Define each state once as design-system tokens, not per page.

### 📱 Web breakpoints

- **The standard set:** mobile ≤480px · mobile landscape ≤768px · tablet ≤834px · tablet landscape ≤1024px · laptop/desktop ≤1440px and up.
- **How to use them:** build **mobile-first** — base styles for the smallest screen, then layer min-width media queries upward (ties to Learning Lab topic 8).
- **Content-out rule:** the named breakpoints are a starting grid, but also add a breakpoint wherever *your content* breaks — usually when the text line length blows past ~75 characters or cards get squeezed.
- **On this build:** the responsive wireframes are drawn at exactly 834px (tablet) and 390px (mobile), and the fluid `clamp()` type rule in Build Standards keeps the in-between widths from ever looking broken.

### 🔧 WordPress theme essentials

- **Theme anatomy:** `style.css` (theme definition) · `index.php` · `header.php` / `footer.php` / `sidebar.php` (pulled in with `get_header()`, `get_footer()`, `get_sidebar()`) · `page.php` (page template) · `single.php` (post template) · `functions.php` (special functions) · `404.php`.
- **The Loop — the heart of every template:** `if (have_posts())` → `while (have_posts()) : the_post()` → your markup → `endwhile`.
- **Tags you'll use constantly:** `the_title()`, `the_content()`, `the_permalink()`, `bloginfo('name')`, `wp_list_pages()`, and conditionals like `is_front_page()`, `is_page()`, `is_single()` for per-page logic.
- **On this build:** one custom page template per page *type* — service, location, advice article — mirrors the one-template-repeated pattern in the Sitemap & Build Tracker, and `functions.php` is where the JSON-LD schema from Build Standards gets injected into the head.

### 🚀 WordPress launch & maintenance checklist

- **Setup:** quality hosting, the latest WordPress version, SSL from day one — for this audience, a slow or "not secure" site loses trust before a single word is read.
- **Development:** delete unused themes/plugins and the sample content, custom 404 page, favicon, timezone set, analytics connected, contact form tested end-to-end — and **301-redirect every old CCS URL** so the existing local-SEO equity survives the rebuild.
- **Security (non-negotiable for a care brand):** keep core and plugins updated, two-factor login for admins, limit login attempts + brute-force protection, disable the in-dashboard file editor, review security logs regularly.
- **Backups:** automated, scheduled and stored off-site — plus a manual backup before any big change.
- **SEO:** an SEO plugin, unique page titles under 70 characters and meta descriptions under 156, clean permalinks, XML sitemap submitted to Google, alt text on every image — this pairs with the JSON-LD schema checklist in Build Standards.
- **Launch:** proofread everything, test every form, click every link, check real devices at the 480/834/1024 breakpoints, and confirm page load stays fast (Core Web Vitals above).
- **Maintenance:** a weekly update-and-backup slot plus uptime monitoring — a stale, broken site quietly undoes all the trust the design built.

### 🎨 CSS essentials

- **Units:** `rem`/`em` are relative (1em = parent font size) — these power the "scales to 200%" accessibility rule; `px` is absolute — fine for borders, wrong for text.
- **Dimensions:** `max-width` is the workhorse — it's how the 65–75-character text-column cap gets built; `min-height` reserves space (hello, fixed CQC-widget slot).
- **Text properties:** `line-height` (1.5 body), `letter-spacing`, `text-align`, `white-space`, `text-transform` (which you'll *avoid* — no ALL-CAPS rule).
- **On this build:** the whole Spacing & layout checklist compiles down to these few properties plus CSS custom properties (`--space-1` to `--space-8`) for the 8pt tokens — master `max-width`, `rem`, `line-height` and the spacing variables and you can hand-code 90% of the theme.

### ✨ Minimal-UI rules — whitespace, type, palette, focus & polish

- **Whitespace:** giving the design room to breathe is what makes navigation feel easy — fewer items per row and real gaps between cards beat cramming. The +16 rhythm gap and internal-≤-external padding rule in the wireframes are this card drawn out (and Learning Lab topic 4 covers the why).
- **Simple typefaces:** minimal, clean fonts make text and the whole design more readable — the cheat sheet's "good" column literally lists **Poppins**, so the CCS pairing (Poppins + Open Sans, nothing else) is already textbook. Skip decorative, monospace or script faces everywhere.
- **Limited colour palette:** fewer colours strengthens each one and stops the page feeling busy — CCS runs exactly six tokens (deep purple, mid purple, lavender, mint, off-white, charcoal), with deep purple reserved for the single CTA so it never competes with anything.
- **Choice limitation:** minimalism's real superpower is concentration — fewer elements on screen means the one thing that matters gets all the attention. This is Hick's Law in picture form (Learning Lab topic 6), and CCS already runs it as doctrine: one message per screen, one CTA per page.
- **Little details matter:** polish goes deeper than colours and placement — consistent capitalisation, even padding, aligned tab underlines, the right text greys. It's why the 8pt tokens and design-system approach exist: define the details once and every page inherits them (and the Aesthetic-Usability effect means that polish literally makes the site *feel* easier to use).
- **Deep shadows:** heavy black drop-shadows are the classic giveaway of amateur UI — they add eye strain and harsh contrast. Use soft, low-opacity shadows instead, exactly matching the CCS "never pure black" rule: gentle elevation on the rounded cards, charcoal `#2e2e2e` everywhere ink is needed.

![Minimal UI shadow example](strategy-images/minimal-ui-shadows-example.png)

### 🌈 Gradients that don't look dirty

- **Natural beats unnatural:** our eyes accept some colour combinations as natural and reject others — neighbouring (analogous) hues blend beautifully, while clashing brights (green → magenta) feel synthetic and cheap. The CCS signature gradient — deep purple `#564298` → mid purple `#8b68da` — is two neighbours on the wheel, which is exactly why it already reads as calm rather than loud.
- **Steal palettes from nature:** sunsets, water, leaves — nature is a free library of gradients that always look right. If CCS ever needs a second gradient (a hero variant, a section lift), sample a dusk sky in the purple–lavender range rather than inventing one in a picker.
- **Pick tones for a mood, not at random:** every gradient sets an emotional temperature. CCS's mood is "calm exhale" — so stay in soft, low-saturation purples and the mint family; never hot pinks or oranges, however pretty the reference.
- **Complementary colours need a bridge:** if two hues sit far apart on the wheel, blending them directly produces a muddy grey middle — add a third in-between stop to keep it clean. CCS rarely needs this (the brand gradient is analogous), but it's the fix if purple ever has to meet mint in one fill.
- **Max 2–3 colours per gradient:** more stops = a striped mess. The brand gradient uses exactly two — keep it that way.

### 🎨 Colour roles — primary, accent, semantic, neutrals

- **Primary (brand):** the colour most prominent in the product — one or two is optimal. CCS = deep purple `#564298`, with mid purple `#8b68da` as its gradient partner.
- **Accent:** derived from the primary via the colour wheel (analogous, monochromatic, complementary…) and used for actions, underscores and highlights. CCS = mint `#c4efea` — an accent by design, never a second brand colour.
- **Semantic:** the four signalling colours rooted in colour psychology — **red** = error/danger · **green** = success/safety · **yellow** = warning/caution · **blue** = information. ⚠️ **This is the one gap in the CCS palette** — the brand kit never needed them, but the enquiry form will: define muted, accessible versions once as tokens (soft NHS-style tones, not alarm-bright), and remember the "never colour alone" rule — a form error needs icon + text, not just red.
- **Neutrals:** text, borders, icons and section backgrounds — white/black/grey schemes. CCS = charcoal `#2e2e2e` on off-white `#f6f5ef`, plus the soft greys already in the wireframes.
- **On this build:** the palette table in Design Language maps 1:1 onto this system (primary ✓ accent ✓ neutrals ✓) — the only thing to add before coding the forms is the semantic token set.

### 👀 Taste — the daily reference diet

*The habit beats the list: ten minutes a day reviewing great work, every day, and saying out loud why it's good — hierarchy? the one CTA? why does the spacing feel right? Naming the why is what builds taste; passive scrolling builds nothing.*

| Source | Best for |
| --- | --- |
| [Mobbin](https://mobbin.com/) | Real shipped app flows — the most honest UX pattern library |
| [Awwwards](https://www.awwwards.com/) | Award-level interaction and art direction |
| [Behance](https://www.behance.net/) (curated filter) | Deep case studies with process shown |
| [godly.website](http://godly.website) | Hand-picked web design, very high signal |
| [Nicely Done](https://nicelydone.club/) | SaaS patterns and flows |
| [Readymag examples](https://readymag.com/examples/) | Editorial layout and typography play |
| [SaaS Landing Page](https://saaslandingpage.com/) | Landing-page structures that convert |
| [Are.na](http://Are.na) · Pinterest · Instagram | Building your own swipe-file boards |

<aside>
⚠️

**The Dribbble caveat:** lovely pixels, but full of concept shots that ignore real UX patterns and would collapse under even an easy flow. Enjoy it — but cross-check anything you want to *learn from* against Mobbin, which only shows shipped products.

</aside>

*The 🎨 Inspo section above is this habit already applied to CCS — sector benchmarks filtered through the brand rules: steal patterns, not pixels.*

---

## 🗂 Sitemap & Build Tracker

*Every page from the tree, as a live database — track build status, the one CTA, the target keyword and the psychology lever for each. Use the 🚦 Build Board to work it like a workflow, 🔥 Launch Priority to see only the P1 pages, and 🗂 Full Sitemap for the whole map by section.*

[Sitemap & Build Tracker](sitemap-urls.md)

---

## 🔧 WordPress SEO in Practice

*Hostinger's 30-point WordPress SEO guide and Fizz Designs' 2026 developer roadmap, translated for our build. Both lean on plugins (Yoast, AIOSEO, Rank Math) — a hand-coded theme gets the same outcomes by building metadata, schema and the sitemap straight into the theme, with none of the plugin weight.*

### 🚀 The go-live checklist

- [ ]  **Untick "Discourage search engines"** in Settings → Reading — the classic launch-day gotcha that silently de-indexes the whole site
- [ ]  **HTTPS everywhere** — SSL is a page-experience signal, and browsers flag non-HTTPS sites as "not secure"
- [ ]  **XML sitemap submitted to Google Search Console** (and Bing Webmaster Tools — that covers DuckDuckGo and Yahoo too) — review it first and exclude junk page types
- [ ]  **Clean permalinks** — post-name structure, no IDs or dates in URLs
- [ ]  **301-redirect every old Elementor URL** to its new home — redirects preserve link equity; a 404 throws it away
- [ ]  **Connect Search Console + GA4 from day one** — the free source of truth for queries, clicks and positions

### ✍️ On-page habits for every page

- **Meta title under ~60 characters**, unique, keyword near the front; **meta description 50–160 characters** with an active-voice call to action — metadata drives click-through rate more than rankings
- **One H1 per page**, headings in strict order (H2 → H3, never skipping); question-style headings can win Featured Snippets and "People Also Ask" spots
- **Images:** compress, lazy-load, alt text around 80–125 characters, readable lowercase-hyphen file names (`home-care-west-malling.jpg`, not `IMG_0001.jpg`)
- **Show a "last updated" date** — readers and Google both prefer visibly fresh content
- **Breadcrumbs on every page** (with Breadcrumb schema) — a navigation aid that also appears in search results

### ⚙️ Technical guardrails

- **Core Web Vitals — LCP, INP, CLS — are ranking signals.** A lean hand-coded theme is exactly the advantage here; verify with PageSpeed Insights or Lighthouse
- **Canonical tags** wherever the same content is reachable at multiple URLs
- **Noindex low-value pages** — tag archives and thin category pages dilute site authority
- **One keyword = one page** — check Search Console's query report for cannibalization (two pages splitting clicks for the same term), and refocus one of them if so

### 📍 Local SEO — CCS's biggest lever

<aside>
📍

**76% of people who search for a nearby business visit one within a day.** A complete **Google Business Profile** (exact name, address & phone, photos, fresh Google reviews) plus town-specific pages — *home care in West Malling, Aylesford, Snodland* — is the single highest-impact SEO move for a local care provider.

</aside>

### 📈 What the fuller Trends data adds

- **"care home reviews" +10% and "cqc" in the top 20** — families actively vet providers. Make the CQC rating prominent on-site and cultivate Google reviews (off-site, so it doesn't clash with the no-testimonials brand rule)
- **"care home fees", "care home costs" and "attendance allowance" all rank in the top queries** — honest funding-and-costs guidance is a high-trust Advice-hub cluster
- **"live in home care", "palliative care at home" and "end of life care at home" all rising** — each specialised service earns its own page
- **"care home jobs near me" +7%, "jobs near me" +6%** — more confirmation the recruitment funnel deserves its own section
- **Rising queries are dominated by provider brand names** (Helping Hands +120%) — people search providers *by name*, so a clean, complete branded search result matters
- **"local authority funding for care homes" +160%** — with "care act 2014" and "cqc" rising alongside it. How council funding works (needs assessments, the Care Act route) is a fast-rising question that belongs in plain English on the Costs & Funding page, right next to the NHS routes (continuing healthcare, funded nursing care)
- **"care home resident evictions uk" is a Breakout query** — alongside the rising negligence-solicitor searches, more proof that safety-and-security anxiety drives this market. CCS's "stay in your own home" framing answers that fear directly: nobody can be evicted from their own home
- **Generic "adult day care" is *declining* (−20%, "near me" −50%) while dementia-specific day care surges (+100%)** — families find day care through the condition, not the category, so day-care content belongs on the dementia page and in dementia advice articles, not as a standalone page
- **"Carer" is rising (+20%) while "caregiver" is falling (−30%)** — UK searchers say *carer*; the American "caregiver" is fading from the vocabulary. Site copy should always say "carer" / "care assistant", never "caregiver"
- **"carer allowance" +30% and "carer support" +30%** — unpaid *family* carers (Rachel before she ever enquires) are searching for help with their own situation. A plain-English Carer's Allowance / "support for family carers" advice article meets her earlier in the journey — and links naturally to the respite page, her pressure valve
- **Safeguarding is a live search topic** — "adult safeguarding" +10%, "6 principles of adult safeguarding" +20%, "duty of candour" +20% and "cqc" +20% all rising. A plain-English "how we keep people safe" treatment on the Quality & Safety page speaks straight to it (and the parallel "safeguarding training" demand, +8%, belongs to sister brand CTA — not the CCS site)
- **Career-pathway searches (social worker, "support worker jobs" +10%, "carer jobs" +10%) are a self-contained professional ecosystem** — registers, locum work, toolkits, salaries — with zero family commercial intent. One more confirmation the recruitment funnel stays structurally separate from the care-seeker journey
- **The recruitment funnel has its own local-SEO game** — "jobs hiring near me" is a Breakout, "care home agency near me" +60%, "jobs in care home near me" +20%. Candidates search locally exactly like families do, so the Join the Team section deserves its own location-flavoured copy ("care jobs in West Malling & Maidstone") and the same near-me optimisation as the care-seeker pages
- **Candidates go direct to employers, not job boards** — Indeed searches are *falling* (−4% to −8%) while named-employer job searches surge (Agincare Breakout, Avery Healthcare +170%, HC-One +120%, Bupa +90%). A careers page that owns the "CCS jobs" branded search earns its keep — and the same searchers are weighing care work against retail (B&M jobs +60%, McDonald's +20%, Asda +20%), so recruitment copy should sell what a till can't: relationships, continuity, and work that matters

### 🧭 Service-by-Service Search Demand

*Related-query Trends data for each care type CCS offers — what families actually type into Google, and the copy lesson hiding in each one.*

| **Care type** | **What UK searchers are doing** | **The lesson for CCS** |
| --- | --- | --- |
| **Palliative & end-of-life** | "palliative care" +10%; the rising queries are pure fear-and-confusion: "does palliative care mean you are dying" +450%, "does palliative care mean end of life" +30%, "palliative pronunciation" +1,050% (people can't even say the word); comparison confusion — "end of life care vs palliative care" +20%, "palliative care vs hospice" +2%; "is palliative care free" +10%; "when should someone be offered palliative care" +350%; "5 stages of palliative care" +160%. Heavy clinical noise too (BNF, midazolam +1,100%, syringe drivers) — clinicians share this search term | Families fear the word and don't know what it means — a gentle "what palliative care actually means" explainer is one of the highest-demand, highest-trust pages CCS could publish. It should directly untangle palliative vs end-of-life vs hospice, answer "is it free?", and stay in plain family language — the clinical traffic on this term makes jargon-free copy even more important |
| **Dementia** | "when should someone with dementia go into a care home" +1,750%; at-home demand rising across the board — "dementia care at home" +30%, "dementia care in the home" +20%, "dementia care in your own home" +50% — while "residential dementia care" declines −7%; "day care for dementia patients" +100%, "dementia day care near me" +40%; "dementia respite care near me" +50%; "help with dementia care" +20%; "person centred care" +30%; "vascular dementia" +30%; "care act 2014" +80% | The exact moment of doubt CCS exists for — answer the care-home question honestly and present home-based dementia care as the alternative (the trend data now firmly backs it), with respite & day care linked as the family's pressure valves. Use "person-centred care" language — families search it — and condition-specific advice (e.g. vascular dementia) deepens topical authority |
| **Nursing care** | Searches pull heavily toward care homes (+20% across the board), but the at-home strand is rising — "private nursing care at home" +30%, "private at home nursing care" +20%, "nursing care at home" +10%; "difference between care home and nursing home" +20% (both phrasings); funding cluster strong — "nhs funded nursing care" +60%, "nhs continuing healthcare" +50%, "attendance allowance" +30%, "funded nursing care" +20%; "last minute care and nursing" +20%; plus heavy professional noise (NMC, RCN, nursing jobs) | More care-home vs home-care disambiguation — "care home vs nursing home (vs home care)" is a ready-made comparison article, and "private nursing care at home" is the rising family intent to speak to directly. The NHS funding routes (continuing healthcare, funded nursing care, attendance allowance) belong in plain English on the Costs & Funding page |
| **Personal care** | "what is personal care" is the top query; "personal care at home" +30%; "personal alarms for the elderly" +80%, "taking care personal alarms" +140% — but much of this term's traffic is care-sector students & workers, not families | Open the service page with a dignified plain-English definition; assistive tech like personal alarms is worth a mention in advice content |
| **Respite care** | "respite care at home" +30% vs "in home respite care" −10% — demand splits between at-home and care-home respite; county-level searches exploding ("respite care oxfordshire" +3,250%, birmingham +170%, somerset +150%); "respite care for disabled adults" +2,850%; "respite care for dementia patients" +100%; "how much does respite care cost" +40%, "how to get respite care" +20%, "what does respite care mean" +50%; "emergency respite care" +9% | Respite is searched *locally* — a "Respite care in Kent" angle is a genuine opportunity. Lead the page with the at-home option (CCS's differentiator vs care-home respite), answer cost / funding / "how to get it" questions directly on the page, and give emergency & short-notice respite its own section |
| **Domiciliary care** | Definition queries dominate but are *declining* — "what is domiciliary care" −30%, "domiciliary care definition" −40% (though "define domiciliary care" is a Breakout); "domiciliary care near me" +20%; "respite care" is a Breakout among domiciliary searchers; the rest is trade noise — insurance +100%, software +250%, jobs, businesses for sale | It's industry jargon — say "home care" in customer-facing copy and define "domiciliary care" once, prominently, to capture the definition searches. The falling definitional volume suggests the term is fading from family vocabulary (one more reason not to build pages around it), but "near me" +20% means the term still deserves to appear on the page — and the respite Breakout says link the respite page prominently from it |
| **Mental health support** | Only 5 related queries exist for the term, and they're all national signposting — "mind" +170%, "samaritans" Breakout, "camhs", "nhs mental health services". None are provider-shopping queries | There's no local commercial search to win here — people in this moment search the charities and the NHS, not providers. If CCS offers mental-health support at home, fold it into service copy in plain family language, and signpost Mind / Samaritans / NHS in advice content — genuinely helpful signposting is also a YMYL trust signal |
| **Learning disability support** | Just 3 related queries — "learning disability support worker" −10%, "what is a learning disability" −2%, "is autism a learning disability" −20%; mostly definitional and job-seeker traffic, all flat-to-declining | Another low-volume term — a clear plain-English definition plus answering "is autism a learning disability?" in advice content covers the entire demand; the support-worker query belongs to the recruitment funnel, not the family journey |
| **Companion care** ⚠️ | In UK search the term belongs to **Companion Care Vets (Pets at Home)** — nearly every related query is veterinary | Never title the page "Companion Care" — use **"Companionship care"** or "companionship at home" so CCS isn't competing with a vet chain for its own service name |
| **Live-in care** | The only significant related query is "live-in care jobs" +8% | An unambiguous term with low question-volume — a straightforward service page covers it, plus the recruitment angle |

<aside>
💡

**The pattern across every care type: the fastest-rising queries are *questions*.** "What is…", "when should…", "does it mean…" — families are educating themselves long before they ever search for a provider. Every question above is a ready-made Advice-hub article title that internally links to its service page — exactly the pillar-and-cluster model in action.

</aside>

## 🩺 Long-Term Site Health — Maintenance That Prevents 3 AM Surprises

*A veteran maintainer's 7-point checklist plus the security habits from the developer roadmap. The theme isn't finished at launch — sites fail quietly, over months, in predictable ways.*

### The 7-point checklist

1. **Track slow creeping queries** — log the top 20 slow database queries monthly and compare; the killers are the ones getting 10% slower every month (usually a plugin with a badly indexed table)
2. **Watch error-log *patterns*, not spikes** — one random warning is normal; a new warning appearing 600 times in 48 hours is not
3. **Check WP-Cron reliability** — when cron sticks, scheduled tasks pile up, temporary data never clears, and plugins half-break in ways that are impossible to reproduce
4. **Audit orphaned data** — form builders and page builders love leaving tables behind; a year of bloat causes slowdowns caching can't hide (very relevant when retiring Elementor)
5. **Test third-party flows quarterly** — contact forms, SMTP, any API; integrations fail silently and logs sometimes lie
6. **Hunt staging leftovers** — debug flags, staging keys, disabled security rules; one forgotten flag can tank performance
7. **Test backup restores** — a backup that has never been restore-tested is not a backup

### 🔐 Security & update cadence

- Auto-apply **minor core updates**; test **major updates on staging**, then push live within ~48 hours
- **2FA on every admin account**, a non-default login URL, daily off-site backups, and firewall/malware scanning
- Deploy theme changes **via Git** (GitHub Actions or a deployment service) rather than FTP-ing files — version control means instant rollback

## 📚 Standards & A11y Resource Library

*Every source from the standards research pass — the official documents above, plus the expert blogs, UK gov resources and technical deep-dives that WordPress's own accessibility standard recommends. ⭐ Start Here is the daily-reference core; tick things off in Status as you explore them.*

[Standards & A11y Resource Library](a11y-checklist.md)


[Continuity Design Language](design-language.md)

[Inspo Teardowns — Look & Feel of Each Reference](inspo-teardowns.md)