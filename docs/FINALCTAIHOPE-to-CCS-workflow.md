# FINALCTAIHOPE → CCS workflow

This doc describes how to get the **full features and functionality of the CTA site** (FINALCTAIHOPE) into CCS—the whole thing. FINALCTAIHOPE is a copy of another theme you made; the goal is to migrate its capabilities (UI, blocks, templates, copy, behaviour) into CCS so CCS becomes the single site with that feature set. The workflow is repeatable and can be applied in passes (e.g. by section or by feature area) until the migration is complete.

**When to use:** Planning or executing the full CTA → CCS migration; onboarding someone (or an agent) to the migration; deciding which skills to invoke at each phase. The steps (Take, Tailor, Optimize, Remove) can be run per feature/section or for the whole inventory; the end state is CCS having the CTA site’s features and functionality, and FINALCTAIHOPE no longer needed (or stripped).

**Constraints:** FINALCTAIHOPE is a copy and can be broken. CCS cannot sit broken—CCS must not be broken. CCS is the source of truth; every change must preserve CCS behaviour and align with [design-system/MASTER.md](../design-system/MASTER.md). The aim is the whole CTA feature set in CCS, not one-off element moves in isolation.

---

## Step 1 – Take

**What to do:** Inventory the **full feature set and functionality** of the CTA site (FINALCTAIHOPE). Identify every UI/functional element that must end up in CCS: hero, CTAs, sections, blocks, templates, patterns, forms, any custom behaviour. For each, list associated assets: images, fonts, CSS/SCSS, JS, and PHP helpers or includes. Note template/block/pattern usage and enqueues. You can do this in one full pass or break it into sections; the outcome is a complete picture of what “the whole thing” is.

**Done when:** You have a clear inventory of what the CTA site does and what it’s made of (elements + files + references). No implementation in CCS yet. You know the scope of the migration.

**Deliverable:** [docs/FINALCTAIHOPE-inventory.md](FINALCTAIHOPE-inventory.md) – full CTA feature set, inc modules, page templates, template parts, assets, and scope statement for migration.

**Skills to use:**

- **wordpress-router** – Use at the start to classify the repo (theme vs block theme vs full site) and route to the right workflow.
- **wp-project-triage** – Run to get a structured JSON report (project kind, tooling, tests, version hints) so you know how FINALCTAIHOPE is built and what conventions to follow for the whole migration.

---

## Step 2 – Tailor in CCS

**What to do:** Bring the CTA site’s features and functionality into CCS (copy/adapt markup, assets, and logic). **Use the CTA files and structure**—migrate the templates, template parts, and inc modules—then **tailor the content** to CCS. Do this in passes if needed (e.g. by section or feature area). Adapt everything to CCS brand and company: colours, typography, spacing, and tone per [design-system/MASTER.md](../design-system/MASTER.md). Adjust layout and UI to fit existing patterns; clean up copy (tone, terminology, CTAs) so it reads as CCS.

**Content must be tailored to CCS goals.** CCS is a home care provider (Maidstone & Kent), not a training academy. Use each CTA file/section, but fill it with CCS content:

- **Resources / downloadable resources** → Use the same files and UI (e.g. resource downloads, modals). Tailor content to CCS: **Home care guides**, FAQs, Referral Information (see [CCS-THEME-AND-CONTENT-GUIDE.md](CCS-THEME-AND-CONTENT-GUIDE.md)). No course catalogues or training-only resources.
- **CQC Hub** (page template, sections) → Use the file/structure. Tailor to CCS: e.g. **“CQC and our care”** (registration, rating, reassurance), not a training/compliance resource hub.
- **Group Training** (page template, nav slot) → Use the file/structure. Tailor to CCS: e.g. **“Book a care consultation”** / **“Arrange a visit”** (Contact/consultation form) or a page **“Getting started with care”** / **“How we work with you”** (care journey, assessment, next steps). No training bookings.
- Any copy, CTAs, or sections that refer to courses, Eventbrite, or employer training → Rewrite for CCS (care enquiries, consultations, careers, service info).

**Done when:** All required features and functionality from the CTA site exist in CCS, match the design system, and read as CCS (brand + copy). No CTA-only content (group training, CQC hub as training hub) remains; everything aligns with CCS goals. No broken assets or missing enqueues.

**Skills to use:**

- **wordpress-router** – Already used in Take; reuse if you’re switching context (e.g. from FINALCTAIHOPE to CCS) to re-classify.
- **ui-ux-pro-max** or **frontend-design** – Use for: layout, design tokens, colour/typography, spacing, responsiveness, hover/focus states, and visual polish. Align with MASTER.md tokens and application rules.
- **wcag-audit-patterns** (optional) – Use when the element has forms, buttons, or custom UI that must meet WCAG 2.2; run audits and apply remediation.
- **copywriting** / **copy-editing** (optional) – Use when “cleaning up the copy” is more than light tweaks: headlines, body copy, CTAs, and tone.
- **wpds** (optional) – Use only if CCS uses the WordPress Design System (WPDS) for components/tokens; follow WPDS MCP and docs for those UIs.

---

## Step 3 – Optimize

**What to do:** Harden the full migrated implementation and align with WordPress and block-theme best practices: theme.json for global styles, correct block registration and attributes, security (input/output) for any forms or sensitive behaviour, and any performance or static-analysis checks the project already uses. Apply to everything that came from the CTA site.

**Done when:** All migrated features follow block-theme patterns where applicable, pass security checks for user input/auth, and fit the repo’s tooling (e.g. PHPStan clean, build passes). The “whole thing” in CCS is production-ready.

**Skills to use:**

- **wp-project-triage** – Use to get/refresh the structured view of the repo and guardrails before making changes.
- **wp-block-themes** – Use for: theme.json (presets, settings, styles), templates and template parts, patterns, style variations; and for debugging “styles not applying” or editor/theme mismatches.
- **wp-block-development** – Use when adding or changing blocks: block.json (apiVersion 3, attributes, supports, viewScriptModule), registration, save/render, deprecations, and build/test.
- **wp-plugin-development** – Use when the element involves plugin logic: hooks, activation/deactivation, settings, security (nonces, capabilities, sanitization/escaping). Overlaps with security below for auth/input.
- **cc-skill-security-review** (antigravity: `cc-skill-security-review`) – Use when touching auth, user input, API endpoints, secrets, or sensitive behaviour; follow the checklist (secrets, validation, output escaping, etc.).
- **wp-interactivity-api** – Use when the element uses or needs `data-wp-*` directives, `@wordpress/interactivity`, or viewScriptModule-based interactivity; for hydration and directive behaviour.
- **wp-rest-api** – Use when the element depends on or adds REST routes (custom endpoints, register_rest_route, permission_callback, schema).
- **wp-performance** (optional) – Use when the new element might affect TTFB or backend load; measure and optimize (queries, autoload, cron, HTTP calls).
- **wp-phpstan** (optional) – Use when the project has PHPStan; run or fix analysis after PHP changes so the baseline/errors stay clean.
- **wp-wpcli-and-ops** (optional) – Use when you need WP-CLI for verification (e.g. cache flush, search-replace) or scripting as part of the move.

---

## Step 4 – Remove from FINALCTAIHOPE

**What to do:** Once the full feature set lives in CCS, remove the migrated content from FINALCTAIHOPE. Delete elements and all associated files; remove enqueues, `get_template_part` calls, and references to deleted scripts/styles/markup. You can do this incrementally (as each piece is migrated) or in one pass at the end. Leave FINALCTAIHOPE in a consistent state (no broken includes or 404s). End state: FINALCTAIHOPE no longer holds the CTA features that now live in CCS.

**Done when:** Everything that was migrated is gone from FINALCTAIHOPE; no references or enqueues point to deleted resources. CCS has the whole CTA feature set; FINALCTAIHOPE does not (or is retired).

**Skills to use:**

- **wp-project-triage** (optional) – Re-run after deletions to confirm structure.
- **wp-wpcli-and-ops** – Use if you need WP-CLI for search-replace (e.g. URL or path cleanup) or cache flush.
- **wp-plugin-development** or **wp-block-development** – Use if you’re removing plugin/theme logic from FINALCTAIHOPE.

---

## Validation

**Overall:** CCS has the full features and functionality of the CTA site and behaves correctly; FINALCTAIHOPE no longer contains the migrated content or broken references to it.

**Checks:** Run the repo’s usual checks (build, lint, PHPStan if present, smoke test key pages). For blocks, confirm they insert and save; for templates/parts, confirm they render. Verify critical user flows (CTAs, forms, navigation) work in CCS.

---

## Skills quick reference

| Skill | When to use | What it gives |
|-------|-------------|---------------|
| wordpress-router | Start of task; classifying repo | Repo kind, route to workflow, decision tree |
| wp-project-triage | Before changes; after big structural changes | JSON report: kind, tooling, tests, version hints |
| wp-block-themes | theme.json, templates/parts, patterns, style variations | theme.json, templates/parts, patterns, style variations, debugging |
| wp-block-development | New or changed blocks | block.json, registration, save/render, deprecations, viewScriptModule |
| wp-plugin-development | Plugin structure, hooks, settings, security | Architecture, lifecycle, Settings API, security baseline |
| cc-skill-security-review | Auth, input, APIs, secrets, payments | Checklist: secrets, validation, escaping, auth |
| ui-ux-pro-max / frontend-design | Layout, tokens, accessibility, visual polish | Layout, tokens, responsiveness, states, design quality |
| wcag-audit-patterns | Accessibility of new/changed UI | WCAG 2.2 audit, remediation, manual checks |
| wp-interactivity-api | data-wp-* / viewScriptModule interactivity | Directives, store/state/actions, hydration |
| wp-rest-api | Custom REST routes or CPT exposure | register_rest_route, schema, permission_callback |
| wp-performance | Suspected backend slowness from new code | Profiling, queries, autoload, cron, measurement |
| wp-phpstan | Project uses PHPStan; after PHP changes | phpstan.neon, baselines, WordPress typing |
| wp-wpcli-and-ops | DB/search-replace, cache, plugin/theme ops | WP-CLI safety, search-replace, db, cache |
| wpds | CCS uses WPDS components/tokens | WPDS MCP, components, design tokens |
| copywriting / copy-editing | Cleaning headlines, body, CTAs, tone | Copy passes, alignment, clarity |
