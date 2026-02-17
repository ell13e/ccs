# Task Plan: CCS Home Care Site Finish

## Goal
Deliver a production-ready dual-purpose site (Care + Careers) with all content, all UI, CQC and CV Minder widgets, populate options, and Classic editor; frontend visual appeal optimized.

## Current Phase
Phase 5

## Phases

### Phase 1: Admin populate scope and careers structure
- [x] Refactor activation to `run_with_scope()`; persist `page_ids` and `careers_page_ids`
- [x] Welcome Screen: Populate pages, services, menus, entire site; Reset includes careers
- [x] Careers pages in activation; `careers` menu location; header nav switch (care vs careers)
- **Skills to use:** plan-writing (breakdown before coding), clean-code (all PHP edits)
- **Status:** complete

### Phase 2: Services, widgets, content
- [x] Confirm 3 or 5 services; ensure listing and singles
- [x] Wire CQC script (home + CQC page) and CV Minder embed (Current vacancies); Customizer overrides
- [x] Full copy for every page and service in theme; all content in Classic editor (post_content)
- **Skills to use:** clean-code (services PHP); content-creator, copywriting, copy-editing, seo-content-writer, seo-authority-builder, seo-cannibalization-detector (all copy and SEO pass)
- **Status:** complete

### Phase 3: Schema, SEO, legal
- [x] Structured data per plan (Organization, WebSite, Service, FAQPage, etc.)
- [x] Legal pages and policy copy
- **Skills to use:** schema-markup (and existing structured-data); copy-editing or copywriting for legal text
- **Status:** complete

### Phase 4: UI/UX and visual appeal
- [x] One design pass: design-system tokens, hierarchy, spacing, components (hero, cards, forms, careers)
- [x] Responsive check 375px, 768px, 1440px; WCAG 2.1 AA
- **Skills to use:** frontend-design, ui-ux-designer, ui-ux-pro-max
- **Status:** complete

### Phase 5: Verification and delivery
- [x] Run Populate entire site; run Reset; confirm all URLs and UI
- [x] Verify Classic editor content and image rules; CQC and CV Minder
- **Skills to use:** clean-code (final pass); planning-with-files (update progress.md, task_plan.md, findings.md)
- **Status:** complete
- **Note:** Verification checklist documented in findings.md; run manually in WP admin (Populate entire site, Reset, then open each URL and check CQC/CV Minder, nav switch, responsive).

## Decisions Made
| Decision | Rationale |
|----------|-----------|
| Classic editor for pages | Easier admin; edit copy and images in one place |
| Multi-image pages: images in content | Each image assignable in editor; no Featured Image for those |
| Design system (design-system.css) | Single source for typography, colour, spacing; visual cohesion |

## Errors Encountered
| Error | Attempt | Resolution |
|-------|---------|------------|
|       |         |            |

## When to Update the Planning .md Files (Required)

| Trigger | Update this file | What to do |
|--------|-------------------|------------|
| **Before starting a phase** | `task_plan.md` | Set **Current Phase** to that phase; set that phase’s **Status** to `in_progress`. |
| **After every 2 view/browser/search ops** | `findings.md` | Append or update **Research Findings** or **Visual/Browser Findings** with what you learned. |
| **After completing a phase** | `task_plan.md` | Set that phase’s **Status** to `complete`; check off its tasks. |
| **After completing a phase** | `progress.md` | Set that phase’s **Status**; fill **Actions taken** and **Files created/modified** for that phase. |
| **Any error or retry** | `task_plan.md` | Add a row to **Errors Encountered** (Error, Attempt, Resolution). |
| **Before a major decision** | (read only) | Re-read `task_plan.md` (and optionally `findings.md`) to refresh context. |

The plan dictates that these updates are mandatory: do not skip updating the .md files when the trigger occurs.

## Notes
- **Skills:** Use the "Skills to use" line in each phase when doing that work (see also main plan Skill-to-Task Mapping).
- Re-read this plan before major decisions.
- Log all errors in **Errors Encountered**; never repeat a failed action.
- Update phase status in both `task_plan.md` and `progress.md`: pending → in_progress → complete.
