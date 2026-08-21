# Careers & Recruitment Keyword Research (Google Trends, GB, 12mo)

**Source:** 14 raw Google Trends exports (GB region, 2025-08-17 to 2026-08-16), saved into
`assets/images/data/` and archived here at `docs/research/careers-keyword-data-raw/`.
Consolidated into this doc so the findings are usable without re-parsing CSVs.

**Scope:** This is the recruitment/job-seeker side of keyword research — who's searching for
care *work*, not care *services*. It complements, not replaces, the client-acquisition
keyword research already in `reference/strategy.md` ("What UK searchers actually type").
None of the query clusters here are Kent/Maidstone-specific — they're national search
behaviour for care-sector job terms, useful for terminology and content-gap decisions on
the Careers side, not for local SEO.

---

## Validated findings

### 1. "Support worker" and "care assistant" both outrank generic terms — use them as headings
Direct comparison over the full year: **support worker** (78–100 index) consistently runs
noticeably higher than **care assistant** (45–60). Both dwarf "care worker" as a standalone
term (~4–7 in the same 12 months, per the `home care` comparison series). **Takeaway:**
job-facing headings and page titles should lead with "Support Worker" / "Care Assistant" —
already the case in the new [Careers hub copy](../../page-templates/template-careers.php) and
[current vacancies listings](../../page-templates/template-current-vacancies.php) via CV
Minder — this data confirms that wording choice rather than changing it.

### 2. "Role of a [job]" / "duties of a [job]" is a live, currently-breaking-out content gap
The `care worker role / care assistant duties / care assistant responsibilities / social care
worker role / duties of a healthcare assistant` series sat at **zero for 50 of the last 53
weeks**, then broke out hard in the final 3 weeks of data (Aug 2 → Aug 16, 2026): 14→41→58 for
"care worker role" alone, with the other four terms all showing first-ever volume in the same
window. The standalone `role of a care worker` query is the single highest-volume term in its
own cluster (100, vs. 73 for "social worker role"). **This is a fresh, currently-forming
demand spike, not historical noise.** A short, honest "What does a [Care Assistant / Support
Worker] actually do day-to-day" page or section would land directly on live search intent —
and is exactly the kind of content the existing "Real Support" / "Room to Grow" cards on the
Careers hub gesture at without answering literally.

### 3. "How to become a healthcare assistant" — same pattern, same window
Flat at 0 for the entire year, then 100 → 49 in the final two weeks of data. Same signal as
above: a role-pathway explainer ("how to become a carer with us," qualifications needed, no
experience necessary) is timely, not evergreen filler.

### 4. Qualifications/training searches are a real top-of-funnel, mostly NVQ/BTEC/Level 2–5
The `health and social care` cluster is dominated by course-and-qualification queries: BTEC,
NVQ (levels 2–5), GCSE, diploma, "what is health and social care." These read as
students/career-changers researching *before* they're job-ready — a "no qualifications
needed to start, we'll train you" message (which the existing "Room to Grow" / Care
Certificate copy already implies) is well-aimed at this audience if made more explicit and
findable (e.g. its own FAQ entry or a line in Professional Development).

### 5. "Care worker visa" / "skilled worker visa" is a large, hot cluster — needs Ellie's call before acting
`care worker visa`, `uk care worker visa`, `skilled worker visa`, `health and care worker
visa` all sit in the 24–79 range in the "care support worker" comparison set, and "healthcare
assistant jobs with visa sponsorship" is a +40% riser in its own cluster. This is a
substantial, currently-active search volume tied to the UK's Health and Care Worker visa
route. **Flagging, not acting**: I haven't added any visa/sponsorship content, because
whether CCS is a licensed sponsor is a factual claim with real Home Office compliance
consequences if stated incorrectly — this needs Ellie's confirmation before any copy goes
near it, one way or the other.

---

## Noise — read but discarded, not usable signal

- **`carer support payment` cluster** — Carer's Allowance, Universal Credit, Council Tax
  Support, Pension Credit, "carer support payment Scotland." This is unpaid family carers
  searching for state benefits, not job-seekers or care-service clients. Not CCS's audience
  on either side of the site.
- **Two unrelated queries inside that same cluster** — a "vocal or active opposition to our
  fundamental values" Prevent-duty exam question and a second safeguarding-training exam
  question. These are Google Trends grouping artefacts (co-occurring searches, not
  semantically related to care recruitment) — ignore.
- **`adult social care [+city]` cluster** — Leeds, Essex, Birmingham, Cornwall, Somerset,
  Nottingham, Leicester, Hampshire, Sheffield, Lincolnshire, East Sussex, Gloucestershire,
  Coventry, Plymouth. These are people trying to find their **local council's** adult social
  services phone number — not Kent-specific, not about CCS, not a recruitment or client
  signal. Confirms nothing actionable for CCS's own location pages.

---

## Suggested next steps (not yet built)

1. A short "What the role actually involves" page or FAQ section for Care Assistant /
   Support Worker roles, timed to the current breakout — highest-confidence, lowest-effort
   item above.
2. A visible "no experience or qualifications needed to start — we train you" line, since
   the qualifications-research audience is real but currently has to infer this rather than
   read it directly.
3. Ask Ellie directly: does CCS hold a Home Office sponsor licence for the Health and Care
   Worker visa route? Only build visa-related content after that's confirmed either way.

## Raw data

The 14 source CSVs (`time_series_*`, `searched_with_top-searches_*`,
`searched_with_rising-searches_*`) are archived in
[`careers-keyword-data-raw/`](careers-keyword-data-raw/) for re-analysis if needed. They were
originally saved into `assets/images/data/`, which isn't a sensible home for research data in
a theme repo (it's not an image, and that directory ships as a web-accessible theme asset) —
moved here instead.
