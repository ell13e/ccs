# Design System → Critical CSS Update Strategy

**Purpose:** Plan updates to critical CSS and design-system tokens after the MASTER.md design system revision (Prompt 1.1), without changing the existing defer mechanism, conditional enqueues, or WP-CLI command structure.

**References:**
- `design-system/MASTER.md` — revised colour roles, typography scale, spacing, shadows, radius, component tokens
- `inc/performance/class-critical-css.php` — critical CSS inlining, 14KB limit, deferred handles
- `assets/css/critical.css` — fallback critical CSS (above-the-fold only)
- `assets/css/design-system.css` — full tokens and utilities (deferred)
- `inc/cli/class-ccs-critical-css-command.php` — `wp ccs regenerate-critical-css`

---

## 1. Critical vs deferred: what goes where

### 1.1 Principle

- **Critical (inlined, &lt;14KB):** Only what is needed for **first paint** — LCP, header, hero, primary/secondary buttons, skip-link, emergency banner, container. No full design-system utilities.
- **Deferred (design-system, components, etc.):** Full token set, typography scale with letter-spacing, spacing and shadow utilities, card/stat styles, responsive overrides, template-specific CSS.

### 1.2 Critical CSS should contain

| Category | Content |
|----------|--------|
| **Reset** | Minimal (box-sizing, text-size-adjust, body margin). Unchanged. |
| **:root tokens** | **Subset** used by critical selectors only (see §2). Colours, font stack, type scale sizes, line-heights, spacing used in header/hero/buttons, focus ring, one radius and one shadow if hero stats are above the fold. |
| **Typography base** | `body`, `h1`–`h3` / `.heading-*` using tokens. Add `--leading-snug` and body `line-height: 1.6` if we adopt MASTER. |
| **Layout** | `.container` (padding from spacing scale). Unchanged. |
| **Header** | Skip-link, emergency banner, `.site-header`, nav, CTA, toggle. Uses colour and spacing tokens only. |
| **Hero** | `.home-hero`, grid, title, subtitle, CTAs, trust line. Uses spacing, type, colour; optional `--shadow-subtle` and `--radius-lg` for a stats/trust strip. |
| **Buttons** | `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-phone` with radius from design system. |

### 1.3 Deferred only (do not put in critical)

| Category | Content |
|----------|--------|
| **Full colour set** | Accent, secondary-light, semantic (success, warning, info), and any token not used by critical selectors. |
| **Full typography** | Letter-spacing (`--tracking-*`), full scale with per-level line-height/letter-spacing. |
| **Full spacing** | `--space-2xs`, `--space-3xl` and all utility classes (m-*, p-*, gap-*). |
| **Shadow system** | `--shadow-elevated`, `--shadow-prominent`, focus shadow. |
| **Full radius** | `--radius-xl`; radius utilities. |
| **Components** | Cards, stats blocks, forms, modals, footer — all in deferred stylesheets. |
| **Utilities** | `.text-*`, `.bg-*`, `.border-*`, margin/padding/gap helpers. |

### 1.4 Rule of thumb

If a CSS custom property is **referenced by a selector in `critical.css`**, define it in `critical.css` so the first paint is correct. If it is only used by deferred styles, define it only in `design-system.css`.

---

## 2. CSS custom properties to add

### 2.1 In `critical.css` (minimal set for above-the-fold)

Align with MASTER naming; keep critical under 14KB by adding only what critical selectors use.

| Token | Value | Notes |
|-------|--------|------|
| **Colours** | | |
| `--color-background-warm` | `#f6f5ef` | Alias or replace `--color-background-grey` for MASTER naming. Use for hero/section background. |
| `--color-secondary-light` | `#C5EAE4` | Optional; only if hero or header uses it. |
| `--color-accent` | `#9b8fb5` | Optional; only if critical has list bullets or tags. |
| **Spacing** | | |
| `--space-2xs` | `0.25rem` | Only if used in critical (e.g. icon–text gap). |
| `--space-3xl` | `6rem` | Only if used in critical (e.g. section block). |
| **Typography** | | |
| `--leading-snug` | `1.35` | For subheadings in hero if desired. |
| `--font-family-heading` | `"Poppins", …` | If hero title uses it; else keep single `--font-family` for critical. |
| **Radius** | | |
| `--radius-sm` | `6px` | Replace `--btn-radius` (0.375rem) with `--radius-sm` for buttons if design system standardises on 6px. |
| `--radius-md` | `10px` | For buttons/cards in critical. |
| `--radius-lg` | `14px` | For hero trust/stats strip if present. |
| **Shadow** | | |
| `--shadow-subtle` | `0 2px 8px rgba(46, 46, 46, 0.06)` | Only if hero has a stats/trust box that needs it. |

**Remove or rename in critical:** `--btn-radius` / `--card-radius` → use `--radius-sm`, `--radius-md`, `--radius-lg` from MASTER.

### 2.2 In `design-system.css` (full set)

Add all tokens required by MASTER; no 14KB limit here.

| Category | Tokens to add / update |
|----------|-------------------------|
| **Colours** | `--color-background-warm` (alias for or rename of `--color-background-grey`), `--color-info` (#2563eb). Ensure accent, secondary-light, secondary-dark, semantic set match MASTER. |
| **Typography** | `--leading-snug` (1.35), `--tracking-tight` (-0.02em), `--tracking-normal` (0); per-level letter-spacing if implemented as utilities. |
| **Spacing** | `--space-2xs` (0.25rem), keep `--space-3xl` (6rem). |
| **Shadows** | `--shadow-subtle`, `--shadow-elevated`, `--shadow-prominent` (values in MASTER §4); optional `--shadow-focus` for buttons. |
| **Radius** | `--radius-xl` (20px). Keep sm/md/lg; ensure values match MASTER (6, 10, 14, 20). |
| **Deprecate** | Remove or alias `--shadow-soft` / `--shadow-soft-lg` to `--shadow-subtle` / `--shadow-elevated` so MASTER naming is canonical. |

---

## 3. Performance impact assessment

| Change | Impact | Mitigation |
|--------|--------|------------|
| **Adding variables to critical.css** | Small increase in inline CSS size (tens to low hundreds of bytes per token). Total critical remains well under 14KB if we only add the minimal set in §2.1. | Add only tokens that critical selectors actually use; do not duplicate the full design-system :root block. |
| **New rules in critical.css** | Each new selector/rule increases byte count. | Avoid adding full utility classes to critical. Prefer reusing existing critical selectors with new token values (e.g. same .home-hero__* with updated spacing/radius). |
| **Deferred design-system.css** | Slightly larger file due to new tokens and utilities. No change to load timing (still deferred). | No mitigation needed; growth is modest. |
| **FOUC risk** | If a selector in critical.css uses a variable that is only defined in deferred CSS, that property will be wrong until deferred loads. | Define in critical.css every custom property that critical selectors reference (§1.4). |
| **14KB trim** | `class-critical-css.php` trims to first 14KB when serving from option or file. Adding too much to critical.css could push useful rules past the cut. | After adding tokens, measure `critical.css` size; if approaching 14KB, move non-essential rules or token definitions to a “critical-extended” that is loaded deferred (not recommended unless necessary). Prefer keeping critical minimal. |

**Recommendation:** Implement the minimal critical token set (§2.1); implement the full set in design-system.css. Re-run `wp ccs regenerate-critical-css --clear` (or re-import from file) after changes and confirm (a) no FOUC on hero/header/buttons, (b) inline block stays under 14KB.

---

## 4. Cache invalidation strategy for regenerating critical CSS

### 4.1 How storage works

- **Stored critical CSS:** Saved in `wp_options` under key `ccs_critical_css` (see `CCS_Critical_CSS::OPTION_KEY`). Value is an array keyed by template type (`default`, `front_page`, `home`, `single`, `page`, etc.); each value is the raw CSS string for that template.
- **Fallback:** If no stored CSS for the current template (or for `default`), the theme reads `assets/css/critical.css` from disk and trims to 14KB.
- **Served size:** When serving, CSS is always trimmed to the first 14KB (`trim_to_max_bytes`).

### 4.2 When to invalidate

Invalidate (refresh) critical CSS when:

1. **Design token or critical layout changes** — e.g. after updating `critical.css` or `design-system/MASTER.md` and implementing token changes in `critical.css`.
2. **New above-the-fold layout** — new sections or components that appear in the first viewport and are styled in critical.
3. **Theme version bump** — optional; can be used to force a one-time clear so all users get file-based fallback until per-template CSS is regenerated.

### 4.3 Invalidation options (no code change to class or CLI)

| Action | Command / step | Effect |
|--------|-----------------|--------|
| **Clear all stored CSS** | `wp ccs regenerate-critical-css --clear` | Every request uses fallback file `assets/css/critical.css` from disk. Any file change is picked up immediately. No per-template override. |
| **Refresh default from file** | `wp ccs regenerate-critical-css --template=default --from-file=assets/css/critical.css` | Stores current `critical.css` for `default` template. Other template keys unchanged. |
| **Refresh front_page from file** | `wp ccs regenerate-critical-css --template=front_page --from-file=assets/css/critical.css` | Same for `front_page`. Repeat for other template types if you have template-specific critical files. |
| **Clear one template** | `wp ccs regenerate-critical-css --template=page` | Removes stored CSS for `page`; that template falls back to file. |

### 4.4 Recommended workflow after design system updates

1. **Update `assets/css/critical.css`** with the minimal new tokens and any selector changes (§2.1).
2. **Update `assets/css/design-system.css`** with the full token set (§2.2).
3. **Regenerate so file is used everywhere:**  
   `wp ccs regenerate-critical-css --clear`  
   Result: all templates use the updated `critical.css` from disk (no option storage). No cache key change needed.
4. **Optional — store per template again:** If you later use Critical/Penthouse to generate per-URL critical CSS, import with `--template=<type> --from-file=<path>` for each type. Until then, file fallback is sufficient.

### 4.5 Optional: version-based invalidation (future)

To force invalidation on theme upgrade without running WP-CLI manually:

- Define a theme version constant (e.g. `CCS_THEME_VERSION`).
- When saving or reading stored critical CSS, store a version with it (e.g. in the option array: `version` => `CCS_THEME_VERSION`).
- In `get_critical_css()`, if the stored `version` is less than current `CCS_THEME_VERSION`, ignore stored CSS and fall back to file (and optionally clear the option). This requires a small change in `class-critical-css.php` and possibly the CLI. Document in this strategy if implemented.

---

## 5. What stays unchanged

- **Defer mechanism:** `ccs_deferred_style_handles` and `defer_stylesheet_tag()` logic unchanged. design-system, components, header, responsive, theme-style, homepage, service-page, location-page, contact-page remain deferred.
- **Conditional enqueues:** No change to which styles are enqueued per template; only the content of `critical.css` and `design-system.css` changes.
- **WP-CLI command:** `wp ccs regenerate-critical-css` with `--clear`, `--template`, `--from-file` remains as-is. No new subcommands required for this strategy.
- **14KB limit:** `CRITICAL_CSS_MAX_BYTES` and `trim_to_max_bytes()` unchanged.
- **Preload / fonts:** Preconnect and preload for Google Fonts unchanged.

---

## 6. Implementation checklist

- [ ] Add to **critical.css** only: tokens in §2.1 that critical selectors use; replace `--btn-radius` / `--card-radius` with `--radius-*` where appropriate; ensure `--color-background-warm` (or grey) and hero/button colours match MASTER.
- [ ] Add to **design-system.css**: full list §2.2 (colour, typography, spacing, shadow, radius); add utility classes for new tokens if needed.
- [ ] Set `body` line-height to 1.6 in critical if MASTER is adopted for body.
- [ ] Run `wp ccs regenerate-critical-css --clear` after editing critical.css.
- [ ] Confirm first paint (header, hero, buttons) with new tokens and no FOUC; confirm inline size &lt; 14KB.
- [ ] Document in PROJECT-STATUS-REPORT or README that after critical/design-system CSS changes, run `wp ccs regenerate-critical-css --clear` (or re-import from file per template).
