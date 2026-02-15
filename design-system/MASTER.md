# CCS Design System — Master

**Continuity Care Services** — Wellness / care services (Kent-based home care).  
Single source of truth for brand, UI style, typography, and WCAG 2.1 AA compliance.

**Design intent:** Warmth, trust, and approachability for a healthcare context. Purple remains the brand colour but is used deliberately; teal and cream carry most of the visual weight so the experience feels calm and supportive.

---

## 1. Colour palette (revised roles)

**Same hex values; rebalanced usage** so purple is recognisable but not overwhelming. Use secondary (teal) and neutrals for most surfaces and section headings; reserve primary (purple) for key actions and emphasis.

### 1.1 Primary (brand purple)

| Token | Hex | Usage |
|-------|-----|--------|
| **Primary** | `#564298` | **Reserved for:** one primary CTA per view (e.g. “Get in touch”), in-page links, focus ring, active nav item. Do **not** use for every heading or every button. |
| **Primary light** | `#7B63B8` | Hover states for primary buttons/links; borders when needed. Ensure text on this meets contrast (see §6). |
| **Primary dark** | `#3F2F70` | Active/pressed states; optional for one key headline (e.g. hero) if desired. |

### 1.2 Secondary (calm / warmth)

| Token | Hex | Usage |
|-------|-----|--------|
| **Secondary** | `#a8ddd4` | Card backgrounds, section accents, secondary buttons, “GET STARTED”‑style CTA. Primary carrier of warmth. |
| **Secondary light** | `#C5EAE4` | Very soft backgrounds, highlights, hover for secondary buttons. |
| **Secondary dark** | `#85C4B8` | Secondary button hover; borders; semantic “success” where appropriate. |

### 1.3 Accent (subtle only)

| Token | Hex | Usage |
|-------|-----|--------|
| **Accent** | `#9b8fb5` | **Use sparingly:** list bullets, small decorative elements, tags, borders. Do **not** use for body text or large areas; contrast on cream/white is insufficient for body copy. |

### 1.4 Neutrals

| Token | Hex | Usage |
|-------|-----|--------|
| **Background** | `#ffffff` | Cards, panels, hero content boxes, stats boxes. |
| **Background warm** | `#f6f5ef` | Page and section backgrounds (cream). Default “page” colour. |
| **Text** | `#2e2e2e` | Body and headings by default. |
| **Text light** | `#666666` | Secondary copy, captions, nav secondary. Must be ≥4.5:1 on background. |
| **Border** | `#E0E0E0` | Dividers, input borders, card borders (if used). |

### 1.5 Semantic

| Token | Hex | Usage |
|-------|-----|--------|
| **Success** | `#85C4B8` | Confirmations, success states. |
| **Warning** | `#D4A843` | Warnings, caution. |
| **Urgent** | `#C64B4B` | Errors, “NEW” tags, critical alerts. Ensure ≥4.5:1 for any text. |
| **Info** | `#2563eb` | Informational messages (if needed). |

### 1.6 Application rules

- **Section headings (“How we can help”, “Our services”):** Prefer **text** (`#2e2e2e`) or **primary dark** for one hero-level heading only. Avoid solid purple for every section title.
- **Cards:** Background **background** (`#ffffff`) or **secondary light** for warmth; borders optional with **border** or **secondary**.
- **Buttons:** One primary (purple) per section; rest secondary (teal) or outline.
- **Bullets and list markers:** **Accent** or **secondary dark** instead of primary everywhere.

---

## 2. Typography

**Fonts:** Poppins (headings), Open Sans (body). Enqueue weights 400, 500, 600, 700 for both.

### 2.1 Scale, line-height, and letter-spacing

Body and long copy get more breathing room (line-height ≥1.5, preferably 1.6). Headings use tighter line-height for clear hierarchy.

| Token | Size | Line-height | Letter-spacing | Use |
|-------|------|-------------|----------------|-----|
| `text-xs` | 14px | 1.5 | 0 | Captions, labels, overlines |
| `text-sm` | 15px | 1.5 | 0 | Secondary copy, nav, footer |
| `text-base` | 16px | 1.6 | 0 | Body default |
| `text-md` | 18px | 1.55 | -0.01em | Lead, intro paragraph |
| `text-lg` | 22px | 1.35 | -0.01em | H4, card titles |
| `text-xl` | 28px | 1.3 | -0.02em | H3 |
| `text-2xl` | 35px | 1.25 | -0.02em | H2 |
| `text-3xl` | 44px | 1.2 | -0.02em | H1 |
| `text-4xl` | 48px | 1.2 | -0.02em | Hero headline |

**Implementation:** Store in CSS as `--text-*`, `--leading-*`, `--tracking-*` (or equivalent). Use `--leading-tight` (1.2), `--leading-snug` (1.35), `--leading-normal` (1.5), `--leading-relaxed` (1.6) where the scale references them.

### 2.2 Weights

- **Body:** 400 (normal), 600 (emphasis).
- **Headings:** 600 (H2–H4), 700 (H1, hero, key CTA).
- **UI:** 500 for labels and nav when needed.

### 2.3 Rhythm

- **Margin below headings:** Use spacing scale (e.g. `--space-sm` for H4/H5, `--space-md` for H2/H3, `--space-lg` for H1).
- **Paragraph margin:** `--space-md` (1.5rem) between body paragraphs.
- **List spacing:** `--space-xs` between list items; list margin `--space-sm` after heading.

---

## 3. Spacing scale

Base unit **0.5rem (8px)**. Scale from 0.5rem to 6rem for consistent rhythm and alignment with touch targets (min 44px).

| Token | Value | Rem | Use |
|-------|--------|-----|-----|
| `space-2xs` | 4px | 0.25rem | Icon–text gap, tight inline |
| `space-xs` | 8px | 0.5rem | Inline gaps, list item spacing |
| `space-sm` | 16px | 1rem | Component internal padding, gaps in small groups |
| `space-md` | 24px | 1.5rem | Section internal spacing, paragraph margin |
| `space-lg` | 32px | 2rem | Between cards, block margins |
| `space-xl` | 48px | 3rem | Section padding, hero padding |
| `space-2xl` | 64px | 4rem | Section separation |
| `space-3xl` | 96px | 6rem | Major section breaks |

**Touch targets:** Buttons and tappable links min **44×44px** (use at least `--space-sm` padding plus line-height to achieve height).

**Containers:** Max-width and horizontal padding as in `design-system.css`; use `--space-md` / `--space-lg` / `--space-xl` for viewport‑scaled padding.

---

## 4. Shadow system

Soft, warm shadows to support “Soft UI Evolution” and healthcare trust. All use a neutral grey with low opacity so they don’t feel cold.

| Token | Value | Use |
|-------|--------|-----|
| **Subtle** | `0 2px 8px rgba(46, 46, 46, 0.06)` | Cards at rest, stats boxes |
| **Elevated** | `0 4px 16px rgba(46, 46, 46, 0.08)` | Cards on hover, dropdowns |
| **Prominent** | `0 8px 24px rgba(46, 46, 46, 0.12)` | Modals, popovers, sticky headers |

Optional: **Focus shadow** for buttons (e.g. `0 0 0 3px rgba(86, 66, 152, 0.25)`) in addition to focus ring.

---

## 5. Border radius

Softer corners for a calmer, more approachable feel.

| Token | Value | Use |
|-------|--------|-----|
| `radius-sm` | 6px | Tags, small buttons, inputs |
| `radius-md` | 10px | Cards, default buttons |
| `radius-lg` | 14px | Hero blocks, large cards, stats |
| `radius-xl` | 20px | Feature panels, prominent CTAs |

Use `--radius-md` or `--radius-lg` for cards and hero content; avoid sharp corners on key components.

---

## 6. Component tokens

### 6.1 Cards

- **Background:** `--color-background` or `--color-secondary-light` for warmth.
- **Padding:** `--space-lg` (32px); optionally `--space-xl` on large breakpoints.
- **Border radius:** `--radius-md` or `--radius-lg`.
- **Shadow:** `--shadow-subtle` at rest; `--shadow-elevated` on hover (if interactive).
- **Border:** Optional `1px solid var(--color-border)` or very light `var(--color-secondary)`.
- **Internal spacing:** Title margin-bottom `--space-sm`; text margin-bottom `--space-md`; list spacing `--space-xs`. Use **text** colour for title; **accent** or **secondary dark** for list bullets.

### 6.2 Buttons

- **Primary (one per section):** Background `--color-primary`, text white, min height 44px, padding horizontal `--space-lg`, `--radius-md`. Hover: `--color-primary-dark`.
- **Secondary:** Background `--color-secondary` or `--color-secondary-dark`, text `--color-text` (ensure contrast ≥4.5:1). Same padding and radius. Hover: `--color-secondary-dark` or `--color-secondary-light`.
- **Outline / ghost:** Border `2px solid var(--color-primary)`, text `--color-primary`, transparent background. Hover: background `--color-primary`, text white.
- **Focus:** Visible ring `--focus-ring` (2px solid primary, 2px offset). Do not remove.

### 6.3 Hero

- **Container padding:** `--space-xl` vertical, `--space-lg` horizontal (scale to `--space-2xl` on large screens).
- **Background:** `--color-background-warm` or soft gradient (e.g. cream to secondary-light).
- **Headline:** `--text-4xl` or `--text-3xl`, `--color-text` or `--color-primary-dark` (not solid purple everywhere). Line-height `--leading-tight`, margin-bottom `--space-md`.
- **Lead paragraph:** `--text-md`, line-height 1.6, margin-bottom `--space-lg`.
- **CTA group:** Gap `--space-sm`; primary button + outline button as per §6.2.
- **Trust / stats strip:** Background `--color-background`, padding `--space-md` `--space-lg`, `--radius-lg`, `--shadow-subtle`. Text `--color-text` and `--color-text-light`; no large purple blocks.

### 6.4 Stats (e.g. “15+ Years of care experience”)

- **Container:** Background `--color-background`, padding `--space-md` `--space-lg`, border-radius `--radius-lg`, shadow `--shadow-subtle`.
- **Value/Number:** Bold, `--text-lg` or `--text-xl`, colour `--color-text` or `--color-primary-dark` (use primary sparingly).
- **Label:** `--text-sm` or `--text-base`, `--color-text-light`, margin-top `--space-xs`.
- **Spacing between stats:** `--space-md` or `--space-lg`; keep internal padding generous for readability.

---

## 7. WCAG 2.1 AA compliance

### 7.1 Contrast

- **Normal text:** ≥4.5:1 on background. **Valid pairs:** `#2e2e2e` on `#ffffff`, `#f6f5ef`; `#564298` on `#ffffff` or `#f6f5ef` for large text or UI components only.
- **Large text (18px+ or 14px bold+):** ≥3:1.
- **UI components:** ≥3:1 against adjacent background.
- **Avoid:** Body-sized text in `#9b8fb5` or `#a8ddd4` on cream/white. Use these for backgrounds, borders, bullets, or large decorative text only.

### 7.2 Focus

- All interactive elements must have a **visible focus indicator**. Use `:focus-visible` with `--focus-ring` (2px solid `--color-primary`, 2px offset). Do not remove outline without replacing with a ring.

### 7.3 Motion

- Respect `prefers-reduced-motion: reduce`: no essential information only by animation; set durations to 0 when the preference is set.

### 7.4 Text

- Body font size ≥16px; line-height ≥1.5 (prefer 1.6). Support zoom to 200% without loss of content or functionality.

---

## 8. File and token reference

- **Design tokens and utilities:** `assets/css/design-system.css`
- **This document:** `design-system/MASTER.md`

Implement the revised tokens in `design-system.css` (colour roles, typography scale with line-height/letter-spacing, spacing scale including `space-2xs`, shadow system, radius, and component tokens). Enqueue Poppins and Open Sans (e.g. Google Fonts) with weights 400, 500, 600, 700.

---

## 9. WordPress theme integration

- **theme.json** (theme root) and **assets/css/editor-style.css** expose the same design tokens (palette, typography, spacing) so the block/classic editor and front end stay aligned. Both are driven by this design system and `assets/css/design-system.css`.
- **Block patterns** (CCS Patterns category in the block inserter) are defined in `inc/block-patterns.php` and use theme.json presets for colours and spacing.
