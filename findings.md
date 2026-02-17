# Findings & Decisions – CCS Home Care Site

## Requirements
- Dual-purpose site: Care (home, about, services, contact, resources) + Careers (hub, professional development, current vacancies, working for us)
- All content in theme (e.g. `get_default_page_content()`); populate creates full copy
- All UI built and styled; no placeholders for layout
- Classic editor preferred in WP admin; page content in post_content
- Single image per page: Featured Image OK; multiple images: in page content only
- CQC widget (home + CQC page); CV Minder embed (Current vacancies page)
- Populate options: pages, services, menus, entire site; Reset includes careers

## Research Findings
- Theme activation: run_with_scope() steps (general, pages, contact_page, services, menus, reading, permalinks); page_ids and careers_page_ids stored in options for standalone "Populate menus".
- Legal and news content: get_default_page_content() now includes news-and-updates (short intro for blog listing) and full UK home-care-appropriate policy text for privacy, terms, accessibility, and cookies (no placeholders).

## Skills to Use (Dictated by Plan)
| Phase / area | Skills to use |
|--------------|----------------|
| Phase 1 (admin, careers) | plan-writing, clean-code |
| Phase 2 (services, widgets, copy) | clean-code; content-creator, copywriting, copy-editing, seo-content-writer, seo-authority-builder, seo-cannibalization-detector |
| Phase 3 (schema, legal) | schema-markup; copy-editing or copywriting for legal |
| Phase 4 (UI/UX, visual appeal) | frontend-design, ui-ux-designer, ui-ux-pro-max |
| Phase 5 (verification) | clean-code; planning-with-files |
| After 2 view/browser/search ops | planning-with-files: **update findings.md** (Research Findings or Visual/Browser Findings). See task_plan.md “When to Update the Planning .md Files”. |

## Technical Decisions
| Decision | Rationale |
|----------|-----------|
| Classic editor for all pages | User preference; simpler editing of copy and images |
| run_with_scope() for activation | Enables granular populate actions from Welcome Screen |
| careers_page_ids option | Header can switch to careers menu when on careers subtree |
| Design tokens in design-system.css | Single source for visual appeal; Poppins/Open Sans, purple/mint palette |

## Issues Encountered
| Issue | Resolution |
|-------|------------|
|       |            |

## Resources
- Plan: `.cursor/plans/ccs_home_care_site_finish_aca6f733.plan.md`
- Activation: `inc/class-theme-activation.php`
- Design system: `assets/css/design-system.css`
- Theme setup: `inc/theme-setup.php`

## Visual/Browser Findings
- Design system applied site-wide via `.ds-root` on body; footer and back-to-top use design tokens. Header/homepage use #fff on dark backgrounds (acceptable; no inverse token in design-system).

## Phase 5 Verification Checklist (run manually in WP admin)
1. **Populate:** Appearance → CCS Theme Setup → "Populate entire site". Confirm all care + careers pages created; Primary, Footer, Careers menus assigned; Reading and permalinks set.
2. **URLs:** Open each URL from plan Section 10: home, about, services listing, each service single, contact, resources, care-guides, faqs, referral-information, news-and-updates; careers hub, professional-development, current-vacancies, working-for-us; legal (privacy, terms, accessibility, cookies); optional cqc-and-our-care, getting-started. Each has full content, no Lorem.
3. **Classic editor:** Edit a multi-section page (e.g. About); confirm content and image placeholders are in post_content and editable in Classic editor.
4. **CQC widget:** Homepage CQC section and CQC page (if created) show CQC rating script; Customizer "CQC widget data-id" / "Hide CQC widget" work if implemented.
5. **CV Minder:** Current vacancies page shows CV Minder embed; Customizer override works if implemented.
6. **Reset:** "Reset Demo Content" → then "Populate entire site" again; careers + care content recreated, menus restored.
7. **Nav:** From home, Primary shows Careers link → /careers/; on /careers/ or child, header shows Careers menu; back on care page, Primary menu.
8. **Responsive:** Spot-check home, about, one service, contact, careers hub, current vacancies at 375px, 768px, 1440px; one cohesive look, touch targets and focus visible.
