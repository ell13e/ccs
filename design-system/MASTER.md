# CCS Design System — Master

**Continuity Care Services** — Wellness / care services (Kent-based home care).  
Single source of truth for brand, UI style, typography, and WCAG 2.1 AA compliance.

---

## 1. Brand colours

Use these exact hex values. Do not lighten/darken for “primary-light” without checking contrast.

| Role        | Hex       | Usage |
|------------|-----------|--------|
| **Primary**   | `#564298` | Headings, primary buttons, links, focus ring |
| **Secondary** | `#a8ddd4` | Backgrounds, cards, calm accents |
| **Accent**    | `#9b8fb5` | Secondary UI, tags, subtle emphasis |
| **Background**| `#f6f5ef` | Page/section background (cream) |
| **Text**      | `#2e2e2e` | Body and heading text |

### Derived tokens (theme implementation)

- **Primary light/dark:** Use for hovers and borders only; ensure any text on them meets contrast (see §5).
- **Secondary light/dark:** Tint/shade of `#a8ddd4` for gradients or borders.
- **Text light:** `#666666` or similar for secondary copy; must be ≥4.5:1 on background.
- **Background:** `#ffffff` for cards/panels on cream; body can be `#f6f5ef`.

---

## 2. UI style recommendation

**Recommended: Soft UI Evolution**

- **Why:** Fits wellness/care: soft shadows, rounded corners, subtle depth. Feels approachable and calm without looking clinical. Works well with purple/teal/cream.
- **Apply:** Rounded corners (e.g. 8–12px), soft box-shadows, generous padding, clear hierarchy. Avoid hard edges and heavy borders.

**Alternative: Nature Distilled**

- Organic shapes, natural palette, calm. Use if the brand shifts toward a more “natural” or earthy feel; current palette already supports it.

**Implementation (Soft UI Evolution)**

- Border radius: `--radius-sm: 6px`, `--radius-md: 10px`, `--radius-lg: 14px`.
- Shadow: `--shadow-soft: 0 2px 8px rgba(46, 46, 46, 0.08)`; stronger for cards if needed.
- Buttons/links: rounded, clear focus ring; primary = filled `#564298`, secondary = outline or `#a8ddd4` background.

---

## 3. Typography

**Validated pairing**

- **Headings:** **Poppins** (400, 500, 600, 700). Use for H1–H6 and nav.
- **Body:** **Open Sans** (400, 600, 700). Use for paragraphs, lists, forms, captions.

**Why it works**

- Poppins: friendly, modern; good for trust and approachability in care.
- Open Sans: highly readable at small sizes; strong for long-form and forms.
- Contrast between the two keeps hierarchy clear.

**Scale (modular, ~1.25)** — use in CSS as in `design-system.css`

| Token    | Size  | Use |
|----------|-------|-----|
| `text-xs`  | 14px | Captions, labels |
| `text-sm`  | 15px | Secondary copy |
| `text-base`| 16px | Body default |
| `text-md`  | 18px | Lead, intro |
| `text-lg`  | 22px | H4/H5 |
| `text-xl`  | 28px | H3 |
| `text-2xl` | 35px | H2 |
| `text-3xl` | 44px | H1 |
| `text-4xl` | 48px | Hero |

**Weights**

- Body: 400 (normal), 600 (emphasis).
- Headings: 600 (H2–H4), 700 (H1, key CTAs).

**Line height**

- Body: 1.5 minimum (1.6 for long copy).
- Headings: 1.2–1.3.

---

## 4. Spacing and layout

- **Base unit:** 8px.
- **Tokens:** `xs` 8, `sm` 16, `md` 24, `lg` 32, `xl` 48, `2xl` 64, `3xl` 96 (as in CSS).
- **Touch targets:** Minimum 44×44px for links/buttons (WCAG 2.5.5).
- **Container:** Max-widths as in `design-system.css`; padding scales with viewport.

---

## 5. WCAG 2.1 AA compliance

### Contrast

- **Normal text:** ≥4.5:1 on background.  
  **Valid pairs:** `#2e2e2e` on `#ffffff`, `#f6f5ef`; `#564298` on `#ffffff` or `#f6f5ef` (large text or UI).
- **Large text (18px+ or 14px bold+):** ≥3:1.
- **UI components:** ≥3:1 against adjacent background.
- **Avoid:** Body-sized text in `#9b8fb5` or `#a8ddd4` on cream/white (check with a contrast tool). Use these for backgrounds, borders, or large decorative text only; keep body and small UI text in `#2e2e2e` or `#564298` on light backgrounds.

### Focus

- All interactive elements (links, buttons, form controls) must have a **visible focus indicator**.
- Use `:focus-visible` with a 2px solid ring in `#564298` and 2px offset (already in `design-system.css`). Do not remove outline without replacing with a ring.

### Motion

- Respect `prefers-reduced-motion: reduce`: no essential information conveyed only by animation; durations set to 0 in CSS when the preference is set (already in `design-system.css`).

### Text

- Body font size ≥16px; line height ≥1.5. Support zoom to 200% without loss of content or functionality.

---

## 6. Component guidelines

- **Buttons:** Primary = `#564298` bg, white text; Secondary = outline or `#a8ddd4`; min height 44px, padding horizontal ≥24px.
- **Cards:** Soft shadow, `--radius-md`, padding `--space-md`/`--space-lg`; headings in Poppins, body in Open Sans.
- **Links:** Colour `#564298`; underline on focus/hover for clarity.
- **Forms:** Labels in `#2e2e2e`; inputs with visible border and focus ring; error state in a distinct colour (e.g. `--color-urgent`) with sufficient contrast.

---

## 7. File and token reference

- **Design tokens and utilities:** `assets/css/design-system.css`
- **This document:** `design-system/MASTER.md`

Enqueue Poppins and Open Sans (e.g. Google Fonts) in the theme; weights 400, 600, 700 for both. Use `--font-family-heading` and `--font-family-body` from the CSS for consistency.
