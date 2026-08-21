# Competitor Functionality Teardown — Superior, Home Instead, Helping Hands, Bluebird Care

<aside>
🔧

**Purpose:** functional/UX pattern research for the CCS rebuild — booking flows, navigation structure, interactive tools, careers segregation, multi-location handling. This is deliberately **not** a look-and-feel exercise; `reference/inspo-teardowns.md` already covers colour/type/layout for Home Instead and five other agencies. This document is the complementary "what does it *do*, and how does it work" pass, focused on the four sites Ellie hadn't looked at yet: Superior Healthcare, Home Instead's Maidstone branch specifically, Helping Hands, and Bluebird Care.

**Method:** live browsing on 2026-08-19, including stepping through enquiry/application forms field-by-field (never hitting final Submit) to document actual flow logic rather than guessing from screenshots.

</aside>

---

## 1 · Superior Healthcare (superiorhealthcare.co.uk)

Nurse-led complex care specialist covering Kent, Essex, Surrey, Sussex, Hampshire. Confirmed live at `superiorhealthcare.co.uk` (not `.com` — that domain is dead, as already flagged).

### Header CTA hierarchy
Three distinct buttons sit in the header, each solving a different job:
- **Switch To Us** (lime green, filled) — client acquisition, for people already receiving care elsewhere.
- **Join Our Team** (navy, filled) — recruitment.
- **Give Feedback** (outline) — a third CTA type neither of the other three competitors have as a persistent header button: an existing-stakeholder feedback/complaints channel.

Below the header sits a secondary tab-style row — **Complex Care / Training / Work For Us** — that changes highlight colour depending on which section you're in (green for Complex Care, blue for Training, navy for Work For Us), giving each major section a distinct accent while keeping one shared layout system. A floating "Talk to us today" widget with a **Contact us now** button sits fixed to the page.

### "Switch To Us" flow — stepped through
This is **not** a multi-step wizard — it's a single-page form embedded directly in the landing page (no separate steps, no "Next" button). Fields: name, telephone, email, organisation (optional), a **"Type of care required"** dropdown listing 13 specific complex conditions (Long Term Ventilation, Stoma Care, Tracheostomy Care, Spinal Muscular Atrophy, Motor Neurone Disease, Brain Injury Care, Spinal Injury, Cerebral Palsy, Epilepsy, Stroke, Huntington's, Parkinson's, Degenerative/Neurological), a **"Who is the care for?"** dropdown that explicitly segments B2B referrers from families — *Myself / A family member / A patient (I'm a Case Manager) / A patient (I'm an ICB) / A Personal Health Budget (PHB)* — then a free-text message box and consent checkbox. The B2B segmentation in that second dropdown is the notable bit: this single form serves families, case managers, and NHS commissioners at once by asking who's asking.

### "Give Feedback" flow — stepped through
Also single-page, structured for CQC-style compliance rather than lead gen: relationship to the company (Staff Member / Service User / Supplier or professional organisation / Member of Public), feedback type (Staff recognition / Suggestion / Concern or Problem / Complaint), name, email, free-text details, "can we contact you?" yes/no, and an explicit consent line stating submissions go to the internal HR team and are kept confidential.

### Complex Care section
`/complex-care/` branches into **Who We Work With** (Case Managers / ICBs / Personal Health Budget / Clients & Families — four distinct sub-pages for four distinct referrer types), **Types of Care** (Adults / Children / Live-In), and **Conditions We Support** (13 individual condition pages, matching the "Switch To Us" dropdown list — each condition gets its own URL, its own SEO page).

### County-level location pages — the pattern most relevant to CCS's town-list expansion
`superiorhealthcare.co.uk/at-home-care-kent/` (and equivalent `-essex`, `-surrey`, `-sussex`, `-hampshire` URLs) are genuinely localised, not templated with a find/replace of the county name. The Kent page names specific local hospitals (William Harvey, QEQM, Kent & Canterbury, Maidstone Hospital, Medway Maritime), specific councils (Kent County Council, Medway Council), specific NHS bodies (Kent and Medway ICB), and specific recruitment towns (Maidstone, Ashford, Canterbury, Dover, Gillingham, Chatham, Tunbridge Wells, Sevenoaks, Tonbridge). This is the strongest example among the four sites of a location page doing real local SEO work rather than a shell with the town name swapped in — directly useful as CCS expands from 3 villages to a long Kent town list.

### Careers ("Work For Us") — segregation assessment
**Not** segregated onto a separate subdomain or distinct visual identity — it's a same-header, same-nav, same-colour-system page, distinguished only by the secondary tab row going navy-active. The hamburger menu (revealed via a slide-out drawer, not a mega-menu) lists: Complex Care, Training, Work For Us, About Us, Real Stories, News, Contact Us, with all three header CTA buttons repeated at the bottom of the drawer. Careers content includes a "Search jobs" board, a standalone "Upload your CV" modal button (separate from the full application form), a role list (9 named roles: Children's Complex Care Nurse, Children's Senior/Complex Care Assistant, Clinical Nurse Supervisor, Complex Care Assistant/Nurse, Rapid Response Assistant/Nurse, Senior Complex Care Assistant), and a full application form at the bottom asking for preferred working days and preferred shifts as multi-select fields, plus CV upload. **Takeaway for CCS: this is the "light-touch" end of careers segregation — worth contrasting against Home Instead's and Bluebird's much harder separation below, since Ellie wants heavy segregation.**

### Training — a distinct revenue-generating business line
`/training/` is not just internal staff onboarding — it's marketed as **CPD-accredited courses sold to external healthcare professionals** (Tracheostomy Awareness, Spinal/Brain Injury Awareness, Epilepsy with Rescue Medication, Enteral Feeding, Positive Behaviour Support, Ventilation/Cough Assist), accredited to the Care Certificate, National Occupational Standards and RQF. Dedicated training facilities are listed with a Google Map (Whitstable, Maidstone, Brighton, Chelmsford, Gosport) and an FAQ block (cost, locations, CPD accreditation, trainer qualifications). This is a genuinely distinctive functional pattern none of the other three sites have: training-as-a-product, not just training-as-staff-development.

---

## 2 · Home Instead — Maidstone branch (homeinstead.co.uk/maidstone/)

### Branch/location architecture
The entire site is templated per-branch, not just a single "find your local office" landing page. Visiting `/maidstone/` sets a persistent branch preference: a green **"Maidstone ×"** chip appears in a secondary bar under the header (with a "Remove office" control) and stays present even when you navigate to the generic national homepage — the whole site (What We Do, Why Us, How It Works, Advice & Support, blog) then reads as "your local Home Instead," including a "Local blogs, news and events" feed of Maidstone-specific articles (e.g. "Enjoying Summer in Maidstone," "Starting the Care Conversation in Maidstone," "A Career in Care in Maidstone"). The branch's phone number is embedded directly in the sticky secondary bar next to the location chip.

Note: the phone number shown varied between visits (01622 956499 on the branch page chrome vs. 01622 873414 inside the enquiry form and page `<title>`) — likely a call-tracking swap based on referrer/session, not an error, but worth being aware of if replicating call-tracking on the CCS site.

CQC "**Outstanding**" rating is shown as a badge (green pill + the actual CQC logo) fixed to the bottom-right corner of the hero photo — a persistent, always-visible trust signal rather than something buried in a footer or About page.

### Care enquiry flow — stepped through (2 steps, did not submit)
Reached via the header's **"Enquire Now"** button, which routes to a dedicated, stripped-down landing page (`/care-enquiry/`) with minimal navigation — just the logo and a "Click here for any other non-care related enquiries" escape hatch.
- **Step 1:** First name, last name, email, telephone, and a required radio choice — *"It's for a loved one" / "It's for me"* — then Next.
- **Step 2:** A free-text box ("A little information about your care needs"), a **postcode field** used specifically to route the enquiry to the correct local office (the copy literally says "this helps us to link you to the right local office" — confirming postcode-based office routing happens *after* the initial contact-detail capture, not before), a marketing-consent Yes/No radio, then Submit.

This is a clean, low-friction 2-step pattern: identity + audience first, then need + postcode + consent. No branching logic beyond the audience radio (which doesn't visibly change step 2's content).

### Careers — full subdomain segregation
The "Join your local team" link goes to `/recruitment/maidstone/` — a careers landing page that swaps the **entire header nav** for a careers-specific one (Join Us / Why Join Us / Is Caregiving For Me? / Apply To Become A Care Pro), replacing the care-services nav (What We Do / Why Us / How It Works / Advice & Support / Enquire Now) entirely, while keeping the same logo and green brand colour. It surfaces employee-satisfaction stats (96% proud to work there, 95% feel motivated, 88% see themselves staying 12 months, 82% would recommend) and lists roles by contract type (Full-time / Part-time / Live-in), each filtered to show only Maidstone-specific listings at the bottom.

Clicking through to actually apply ("Apply to be a Care Professional") lands on a **genuinely separate subdomain**: `join.homeinstead.co.uk`. This is a single-page (not multi-step) application form: first/last name, email, phone, postcode, a "right to work in the UK" radio set (UK/Irish citizen / EU Settlement Status / On a Visa / Would need visa sponsorship / No), a driving-licence yes/no, a role-type radio (Full-time / Part-time / Live-in Care), and a marketing-consent opt-in, then Apply.

**This is the clearest example among the four sites of what CCS is asking for: a genuinely distinct subdomain for the actual application transaction**, even though the marketing/landing content for careers lives within the main site's own path structure and branding.

### No dedicated cost/eligibility calculator found
Checked "What We Do" and the main nav for any interactive care-finder or cost tool — none exists. Care types are presented as a static categorised list (Home Care: Personal Care, Daytime Care, Companionship, Home Help & Housekeeping, Respite; Specialist Care: Palliative, Dementia, Arthritis & Mobility, Parkinson's, Cancer, Neurological; plus Live-In). No audience-segmentation quiz on the homepage either (contrary to the general Home Instead brand pattern noted in `inspo-teardowns.md` — that "who is the care for" hero chip pattern wasn't present on this branch-specific UK build).

---

## 3 · Helping Hands (helpinghandshomecare.co.uk)

The most feature-dense of the four sites — richest header, most distinct tools, most aggressive proactive-chat use.

### Header and trust bar
Top announcement bar always visible: "We would love to chat so please **request a callback** from our expert team. Alternatively, our lines are open Mon-Fri 8am-7pm, Sat-Sun 9am-5.30pm." Main header has **Request a call**, **Search**, and **Find your branch** (with a location pin icon) as persistent right-aligned actions, sitting above a mega-menu nav: Home Care Services / Condition-led Care / Everyday Care / **Cost of Care** / Jobs / About Us / Help & Advice. A trust-signal strip runs immediately under the nav on every page: CQC & CIW Regulated · Trustpilot rating (stars + count) · "Receive care in 24 hours" · "Industry leading carer training."

### "New customer" vs "existing customer" phone segmentation — the standout pattern
On the Maidstone branch landing page, the hero splits into two clearly labelled boxes with different opening-hours text and **different phone numbers**: a "New customer" box with a **Request a callback** CTA, and an "Existing customer" box with a direct dial number. This isn't a one-off — the branch directory (see below) applies this same two-number split to essentially every one of ~150 UK branches. **This is worth strongly considering for CCS**: it routes prospective clients toward a low-commitment callback request while giving existing clients instant direct access, without either audience wading through the other's content.

### "Cost of Care" — mega-menu section, stepped through
Clicking "Cost of Care" reveals a mega-menu with five sub-pages: Cost of Live-in Care, Cost of Visiting Care, Cost of Respite Care, Cost of Overnight Care, Funding Options. The main page (`/costs-funding/`) has a **"Calculate my care"** button — but this is **not an interactive calculator**. It links to `/costs-funding/cost-of-home-care/`, a static informational page publishing real headline rates (visiting from £32.40/hr with a £4.50 call-out fee, live-in from £1,675/week for one person), a "what affects cost" list (severity of condition, support level, frequency, duration, geography, antisocial hours), a home-care-vs-residential-care comparison table, the £23,250 means-testing threshold explained, and an FAQ accordion. It also spins off a large SEO cluster of condition-specific cost pages (Cost of Diabetes Care, Cost of Cerebral Palsy Care, Cost of Cancer Care, Cost of Alzheimer's Care, etc.) — the same "one page per condition" pattern Superior uses for conditions, applied instead to cost.

**Notable finding: landing on this cost page auto-triggers a proactive chat popup** ("Helping Hands Online Assistant... We noticed you're investigating the costs of care at Helping Hands. Our prices are designed to match your specific situation and requirements — speak with one of our live chat advisers to get an estimate.") — i.e. even the "calculator" CTA ultimately routes to a human conversation rather than computing a number on-screen. **None of the four competitor sites actually compute a live, self-serve cost estimate** — every "calculator"-labelled entry point (this one, and Bluebird's chatbot below) is lead-gen dressed as a calculator. This is a genuine, concrete gap CCS's planned "Finding What Fits" tool (which does compute an actual number from the Notion copy deck's rate card) could exploit as real competitive differentiation, not just a nice-to-have.

### "Request a callback" flow — stepped through (single step)
One page, not multi-step: first name, last name, phone (with a UK flag/country-code selector), email, "Which care services do you require?" dropdown, then a full address capture — postcode, street address, county, city (notably more fields than Home Instead's or Bluebird's forms, which ask only for postcode) — consent text, and a **"Verify & Submit"** button (the "Verify" wording implies a captcha/bot-check step, not visually confirmed further).

### Branch locator — the richest of the four
`/our-locations/` combines a postcode/area search box, a **search-radius dropdown** (10/25/50/100/200/500 miles), an interactive map with pins, and — below the map — a **full A–Z branch directory** listing ~150 UK branches, each with its new-customer and existing-customer phone numbers and full address. Maidstone's listing: 01622 528652 (new customer) / 01622 236098 (existing customer) — note this differs from the `01622 965344` number in CCS's own competitive-landscape doc, so that number may be stale and worth re-verifying if ever quoted back to Ellie.

### Careers — hybrid segregation
`/jobs/` is a normal in-nav page (same purple header/branding), covering Live-in and Visiting carer roles, pay ("up to £750/week" live-in), benefits (referral bonus, benefits portal, 10% care discount, 24hr on-call support), and a Glassdoor-sourced trust stat ("31% above average employer rating in Healthcare, based on 1,016 reviews"). But the **actual job search/application function lives on a fully separate domain**: `careers.helpinghands.co.uk` (redirects from `jobs.helpinghands.co.uk`) — a distinct job-board application with its own simplified nav (Search / Job Alerts / Applications / Login / Register), its own cookie consent, and a "← Helping Hands Website" link back to the main site. The **Login/Register + Job Alerts subscription** functionality is something neither Superior nor the Home Instead careers subdomain has — a genuine applicant-account system.

---

## 4 · Bluebird Care — Maidstone branch (bluebirdcare.co.uk/maidstone)

### Branch page structure
A sub-nav bar directly under the header reads **"Maidstone / Change office"** (a location-switcher link) followed by branch-specific tabs: Overview / Our services / Meet the team / About us / Local insights / Blog / **Enquire now** (highlighted as a solid-blue active tab, distinct from the others). The CQC rating is shown as a **floating card overlapping the hero photo** (bottom-right corner) — "CQC overall rating: Good, 24 April 2019, See the report" — a persistent-badge treatment similar to Home Instead's but styled as a literal overlapping card rather than a corner pill.

### Hyperlocal "areas we cover" list
Explicitly names small villages, not just the town: Sutton Valence, Harrietsham, Ulcombe, Kingswood, West Malling, Kings Hill, Marden, Leybourne, Staplehurst, Yalding, Collier Street. This granularity (villages around Maidstone, not just Kent-wide) is close to the scale CCS needs for its own expanding town list.

### Third-party review aggregation embedded directly on the branch page
Rather than a testimonial carousel of hand-picked quotes, the Maidstone page embeds **45 real reviews sourced from homecare.co.uk**, displayed with a live aggregate score (8.9/10) plus a "Write a review" and "Read all 45 reviews" pair of links, each review dated and attributed by relationship (e.g. "Daughter of Client," "Wife of Client"). This is a stronger trust mechanism than curated quotes — it's a full syndicated review feed, unfiltered by star rating for display (the page says "displaying our 4 & 5 star reviews" but even those are mixed in tone/detail, reading as authentic rather than cherry-picked marketing copy).

### AI chatbot with explicit branching — stepped through (did not submit)
A chat widget ("Angie," badged "Pairly" in the footer — a third-party live-chat/AI platform) auto-opens on page load with four quick-reply buttons:
- "I need home care and I know how much I need"
- "I need home care and need help estimating my needs"
- "I'm looking for a job"
- "Something else"

Clicking the "help estimating my needs" option does **not** produce an on-screen estimate — the bot replies "We'll ask for a few basic details so the local care team can understand what you need and get back to you," then asks for name, phone number and postcode (i.e. it's a lead-capture funnel with a needs-estimation framing, not a real calculator — the same pattern as Helping Hands' "Calculate my care," confirming the gap noted above). The chatbot's inclusion of a **"I'm looking for a job"** branch is notable: it handles both client leads and candidate leads in one widget, routed by the first click, rather than needing two separate chat entry points.

Note that opening this chat also silently swapped the header into a "Find your local care office" postcode-search bar (postcode/area field + "How can we help" dropdown + "Find care" button) — the header itself becomes a locator tool in that state, separate from the chat.

### "Enquire now" flow — stepped through (single step)
Single-page form at `/maidstone/contact-us`: an "Enquiry subject" dropdown (General enquiry / Request a call back / **Request a brochure**), a "Who is the enquiry for?" toggle (Me / Loved one), then name, phone, postcode, email, message, and Submit. The **brochure-request option** sitting alongside call-back and general-enquiry is a distinct third path for people who want to self-serve information rather than talk to someone yet — Helping Hands has the same "Request a brochure" option elsewhere on its site, but Bluebird folds it directly into the main contact form as a dropdown choice rather than a separate page.

The same page also states an **existing-customer-specific instruction**: "If you're an existing customer, please use our office number during opening hours. You will also have an out of hours number to reach us should you need when our office is closed" — again the same new-vs-existing-customer thinking Helping Hands applies more systematically.

### Careers — the hardest segregation of all four sites
The header's "Careers" link carries an explicit **external-link icon** and opens `bluebirdcarecareers.co.uk` — a completely separate, fully-designed site with its own nav (Job search / Our roles / Why join us? / Help & guidance / Case studies / News / Locations), its own hero and postcode-based job search (with a distance-radius dropdown, same pattern as Helping Hands' branch locator), its own stats bar (20+ years, 10k+ Care Experts, 780k hours of care delivered monthly, 220 offices nationwide), its own Trustpilot badge (4.6/5), and its own role taxonomy organised into four bands: **Care Delivery** (Care Expert, Live-in Care Expert, Lead Care Expert, Care Champion, Care Mentor & Coach), **Customer Focused** (Coordinator, Supervisor, Lead Supervisor, Lead Coordinator), **Admin & Office** (PR & Marketing Executive, Office Administrator, Bookkeeper/Accounts Assistant), and **Management & Leadership** (Registered Manager, Operations Manager, Recruitment & HR Manager, Training Manager). This is the strongest, most complete example among all four sites of "heavily segregated" careers: full domain separation, full independent IA, shared logo/colour only.

---

## Patterns worth adapting for CCS

- **Careers segregation — clear spectrum, pick a point on it.** Superior does the least (same nav, same page, different tab colour). Home Instead segregates the *marketing* content by nav-swap within its own domain but pushes the actual *application* onto a separate subdomain (`join.homeinstead.co.uk`). Helping Hands does the same subdomain split but adds applicant accounts (Login/Register + Job Alerts). Bluebird goes furthest — a fully independent site (`bluebirdcarecareers.co.uk`) with its own IA, stats, and role taxonomy, linked via an explicit "opens externally" icon in the header. Given Ellie's "heavily segregated" requirement plus the "recruit specifically for you" USP, **Bluebird's model is the closest match** — a distinct subdomain/domain with its own hero, own trust stats, and a clear visual "you're leaving the client-facing site" signal (the external-link icon is a cheap, effective cue worth copying regardless of how far the technical segregation goes).

- **The planned "Finding What Fits" tool is a genuine differentiator, not just parity.** All three competitors that have anything resembling a cost/needs tool (Helping Hands' "Calculate my care," Bluebird's chatbot "estimating my needs" branch) route to a human rather than computing a number. None does real-time self-serve estimation. If CCS's prototype (`reference/finding-what-fits-prototype/`) actually surfaces a computed answer from its 3-question routing logic and rate card, that's a functional edge worth foregrounding in marketing copy, not hiding as "just another form."

- **County/town-level location pages need real local content, not templated shells.** Superior's Kent page (naming actual hospitals, councils, ICB, and a dozen specific recruiting towns) is the strongest model here — directly transferable to CCS's town-list expansion. Bluebird's village-level "areas we cover" list (naming Sutton Valence, Harrietsham, Ulcombe etc., not just "Maidstone and surrounding areas") shows the right level of granularity for a Kent-based agency at CCS's scale. Home Instead's approach — the whole site re-flowing per branch with a persistent location chip and branch-specific blog feed — is more infrastructure than CCS likely needs at 3-to-a-longer-list of towns, but the location-chip UI pattern (a small removable badge showing "you're viewing X" persisted across pages) is cheap to build and worth considering once there's more than one branch/area page.

- **New-customer vs existing-customer contact segmentation is close to free and clearly valued in this sector.** Helping Hands applies it as a structural pattern across ~150 branches (two phone numbers, always labelled); Bluebird applies it more lightly (one line of copy pointing existing customers to their office number). Given CCS doesn't yet have a "Switch to us" header CTA, this is a good complementary pattern to build alongside it: existing clients get a fast, un-gated path (direct number), while prospective clients get routed through a qualifying form/callback — which also naturally produces the audience-segmentation data Superior's dropdown captures explicitly ("who is the care for" / "who are you").

- **CQC trust-badge placement: always high, always linked, never buried.** All three UK-regulated competitors (Home Instead, Helping Hands, Bluebird) place the CQC rating as a persistent visual badge overlapping or pinned beside the hero image, always hyperlinked to the live CQC report page — never just a text mention lower down. CCS is rated Good (per the competitive-landscape doc) and should adopt the same "badge in the hero, linked to the actual report" treatment rather than a passive mention.

- **Branch locators, where present, share a common shape**: postcode/area text input → radius filter (Helping Hands: 10–500mi dropdown; Bluebird careers: distance dropdown) → map with pins → full text directory below the map for accessibility/SEO (Helping Hands' A–Z list is the most complete example). If CCS ever needs a locator (once past a handful of towns), this text-directory-below-map pattern is worth copying wholesale — it works for both users who want to click a pin and users/search-engines who just want a scannable list.

- **A structured, CQC-oriented feedback/complaints form is worth having somewhere on the site**, even if not in the header like Superior's. Superior's "Give Feedback" form (segmented by relationship to the company, then by feedback type, routed explicitly to HR with a confidentiality statement) is a compliance-friendly pattern CCS could adapt as a quieter footer/contact-page link rather than a top-level button, satisfying the same "we take feedback and complaints seriously" signal CQC inspectors look for.

- **None of the four sites offer a family/client portal or login** for existing clients (rota viewing, invoices, care plan). Helping Hands' careers subdomain does have applicant Login/Register, but there's no client-facing account system on any of the four. This is either a genuine gap CCS could fill, or confirmation that it's not expected/needed at this market tier — worth a judgement call rather than treating its absence as an oversight to fix.

- **Live chat is universal on Helping Hands and Bluebird** (not seen on Superior or Home Instead's Maidstone build), and both use it proactively/contextually — auto-opening with page-aware copy ("we noticed you're investigating costs...") rather than sitting passively as an icon. If CCS adds chat, contextual triggering on high-intent pages (cost/services pages) rather than a blanket always-open widget appears to be the pattern actually in use here.
