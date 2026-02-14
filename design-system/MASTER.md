# CCS Design System — Master

**Continuity Care Services** — Kent-based home care.  
Single source of truth for brand, UI style, typography, components, and WCAG 2.1 AA compliance.

**Tagline:** *Your Team, Your Time, Your Life*  
**Audience:** Families (Maidstone/Kent), individuals needing care, care workers, professional referrers.  
**Conversion goals:** Free consultation bookings, career applications.

---

## 1. Brand colours

Use these exact hex values. Do not lighten/darken for “primary-light” without checking contrast.

| Role         | Hex       | Usage |
|-------------|-----------|--------|
| **Primary**    | `#564298` | Headings, primary buttons, links, focus ring |
| **Secondary**  | `#a8ddd4` | Backgrounds, cards, calm accents |
| **Accent**     | `#9b8fb5` | Secondary UI, tags, subtle emphasis |
| **Background** | `#f6f5ef` | Page/section background (cream) |
| **Text**       | `#2e2e2e` | Body and heading text |

### Derived tokens (theme implementation)

- **Primary light/dark:** Use for hovers and borders only; ensure any text on them meets contrast (see §7).
- **Secondary light/dark:** Tint/shade of `#a8ddd4` for gradients or borders.
- **Text light:** `#666666` or similar for secondary copy; must be ≥4.5:1 on background.
- **Background:** `#ffffff` for cards/panels on cream; body can be `#f6f5ef`.

---

## 2. Recommended UI style (from 67+ options)

### Primary recommendation: **Soft UI Evolution**

- **Why:** Evolved soft UI with better contrast and accessibility. Fits wellness/care: soft shadows, rounded corners (8–12px), subtle depth. Feels approachable and calm without looking clinical. Works well with purple/teal/cream. WCAG AA+ capable.
- **Apply:** Rounded corners (`--radius-sm` 6px, `--radius-md` 10px, `--radius-lg` 14px), soft box-shadows, generous padding, clear hierarchy. Avoid hard edges and heavy borders.
- **CSS keywords:** `box-shadow: 0 2px 4px` (softer multi-layer), `border-radius: 8-12px`, `animation: 200-300ms smooth`, `outline: 2-3px on focus`, contrast 4.5:1+.

### Alternatives (if brand shifts)

| Style              | When to use |
|--------------------|-------------|
| **Nature Distilled** | Muted earthy, warmth, organic. Use if brand leans more “natural” or artisan; palette supports terracotta/sand/olive. |
| **Trust & Authority** | Certificates, CQC, compliance. Use for regulatory-heavy sections; professional blue/grey accents, badge styling. |
| **Accessible & Ethical** | When doubling down on WCAG AAA; 7:1+ contrast, symbol-based indicators, high-integrity patterns. |

### Implementation (Soft UI Evolution)

- Border radius: `--radius-sm: 6px`, `--radius-md: 10px`, `--radius-lg: 14px`.
- Shadow: `--shadow-soft: 0 2px 8px rgba(46, 46, 46, 0.08)`; stronger for cards: `--shadow-soft-lg: 0 4px 16px rgba(46, 46, 46, 0.1)`.
- Buttons/links: rounded, clear focus ring; primary = filled `#564298`, secondary = outline or `#a8ddd4` background.

---

## 3. Landing page pattern (healthcare/wellness service)

**Recommended structure:** **Hero + Testimonials + Trust + CTA** (conversion-optimised, trust-led).

| Order | Section            | Purpose |
|-------|--------------------|--------|
| 1     | Hero               | Headline, value prop, primary CTA (Book free consultation), optional hero image (warm, non-clinical) |
| 2     | Problem / need     | Short “why home care” or “when to consider care” |
| 3     | Services overview  | Domiciliary, Respite, Complex care (24/7) — cards or list |
| 4     | Trust & authority  | CQC “Good” badge, certs, safety/quality statements |
| 5     | Testimonials       | 3–5 quotes with photo + name + role; carousel or grid |
| 6     | How it works       | 3–4 steps (e.g. Call → Assessment → Care plan → Your team) |
| 7     | Consultation CTA   | Form or prominent “Book free consultation” + phone |
| 8     | Footer              | Contact, careers link, referrer info |

**CTA placement:** Sticky nav CTA + hero CTA + post-testimonials + end of page. Single primary CTA: “Book a free consultation”. Secondary: “Join our team” (careers).

**Trust strategy:** CQC rating visible early; testimonials with real names/roles; no fake urgency. Warm, calm tone.

---

## 4. Component patterns

### Hero

- **Layout:** Full-width; content in `.container` (max-width + padding). Optional split: headline + CTA left, image or illustration right.
- **Headline:** One clear value proposition (e.g. “Care that fits your life”). Poppins, `--text-3xl` to `--text-4xl`, `--color-primary-dark`.
- **Subhead:** One to two lines, Open Sans, `--text-md` or `--text-lg`, `--color-text` or `--color-text-light`.
- **CTA:** Primary button “Book a free consultation”; optional secondary “Call us” (phone link). Min height 44px.
- **Background:** `--color-background-grey` or soft gradient (cream → light teal); avoid harsh purple/pink gradients.
- **Image:** Prefer warm, real-life (carer and client, home setting). No stock “hospital” or cold clinical imagery.

### Testimonials

- **Container:** Section background `--color-background-grey` or white; padding `--space-xl` / `--space-2xl`.
- **Card:** Soft shadow `--shadow-soft`, `--radius-md`, padding `--space-md`/`--space-lg`. Quote in Open Sans italic; name + role in Poppins or Open Sans.
- **Avatar:** Optional; 48–64px, rounded. Prefer real photos with consent.
- **Carousel:** If used, ensure keyboard and swipe accessible; no auto-play that can’t be paused; `prefers-reduced-motion` respected.

### Service cards

- **Layout:** Grid, 1 col mobile → 2–3 cols tablet/desktop. Gap `--space-md` or `--space-lg`.
- **Card:** Bg white or cream; `--shadow-soft`, `--radius-md`; padding `--space-lg`. Heading (Poppins) + short body (Open Sans).
- **Icon/illustration:** Optional; simple, warm (e.g. house, clock, heart). Accent colour `--color-primary` or `--color-secondary`.
- **Link:** “Learn more” or “Get in touch” with clear focus state.

### Consultation form

- **Purpose:** Capture name, phone, email, brief message; submit = “Request a call-back” or “Book consultation”.
- **Layout:** Minimal fields (3–5); single column; labels above inputs. Max-width ~480px for form block.
- **Inputs:** Height ≥44px; border 1px solid `--color-border`; focus ring `--focus-ring`. Error state: `--color-urgent` + message below field.
- **Button:** Primary “Send” or “Request call-back”; loading state (disabled + spinner); success message after submit.
- **Trust:** Short line above submit: “We’ll call within 24 hours” or “No obligation.” No fake scarcity.

---

## 5. Typography

### Validated pairing

- **Headings:** **Poppins** (300, 400, 500, 600, 700, 800). Use for H1–H6 and nav.
- **Body:** **Open Sans** (300, 400, 600, 700). Use for paragraphs, lists, forms, captions.

**Why it works:** Poppins = friendly, modern; good for trust and approachability. Open Sans = highly readable at small sizes; strong for long-form and forms. Contrast between the two keeps hierarchy clear.

### Scale (modular, ~1.25)

| Token      | Size  | Use |
|------------|-------|-----|
| `text-xs`  | 14px  | Captions, labels |
| `text-sm`  | 15px  | Secondary copy |
| `text-base`| 16px  | Body default |
| `text-md`  | 18px  | Lead, intro |
| `text-lg`  | 22px  | H4/H5 |
| `text-xl`  | 28px  | H3 |
| `text-2xl` | 35px  | H2 |
| `text-3xl` | 44px  | H1 |
| `text-4xl` | 48px  | Hero |

### Hierarchy

- **H1:** One per page (e.g. hero). Poppins 700, `--text-3xl` or `--text-4xl`, `--color-primary-dark`, line-height ~1.2.
- **H2:** Section titles. Poppins 700, `--text-2xl`, `--color-primary-dark`.
- **H3:** Subsections. Poppins 600, `--text-xl`.
- **H4–H6:** Poppins 600/500, `--text-lg` / `--text-md`.
- **Body:** Open Sans 400, `--text-base`, line-height ≥1.5 (1.6 for long copy).
- **Lead/intro:** Open Sans 400 or 600, `--text-md`.

### Weights

- Body: 400 (normal), 600 (emphasis).
- Headings: 600 (H2–H4), 700 (H1, key CTAs). Lighter weights (300, 500) for variety only where contrast allows.

---

## 6. Spacing system and grid

### Base unit: 8px

| Token   | Value | Use |
|---------|-------|-----|
| `xs`    | 8px   | Inline gaps, icon padding |
| `sm`    | 16px  | Compact blocks, list spacing |
| `md`    | 24px  | Card padding, section gaps |
| `lg`    | 32px  | Section padding, component spacing |
| `xl`    | 48px  | Section vertical rhythm |
| `2xl`   | 64px  | Major section separation |
| `2.5xl` | 80px  | Large section padding (e.g. between trust and testimonials) |
| `3xl`   | 96px  | Hero / full-section padding |

### Grid

- **Container:** Max-widths as in `design-system.css` (e.g. `--bp-lg` 1024px, `--bp-xl` 1440px). Padding inline: `--space-md` mobile, `--space-lg` tablet, `--space-xl` desktop.
- **Columns:** 12-column grid for complex layouts; simple sections can use flex or CSS Grid with `gap: var(--space-md)` / `var(--space-lg)`.
- **Content width:** Long-form text max-width 65–75ch (e.g. `max-width: 65ch`).

### Touch targets

- Minimum **44×44px** for all interactive elements (buttons, links, form controls). Use `min-height: 2.75rem` (44px) and adequate padding.

---

## 7. Animation and transition guidelines

- **Duration:** Micro 150ms; standard 250–300ms; complex (e.g. scroll reveal) up to 350–600ms. Use `--duration-fast` (150ms), `--duration-normal` (250ms), `--duration-slow` (350ms). For any animation over 400ms, ensure it can be disabled via `prefers-reduced-motion` without losing meaning.
- **Easing:** `--ease-out` or `--ease-in-out` (e.g. `cubic-bezier(0.25, 0.46, 0.45, 0.94)`). Avoid linear for UI.
- **Properties:** Prefer `transform` and `opacity` for animations (performance). Avoid animating `width`/`height`/`top`/`left` for large elements.
- **Reduced motion:** Always respect `prefers-reduced-motion: reduce`. Set `--duration-*` to `0ms` in that media query (already in `design-system.css`). No essential information conveyed only by animation.
- **Do:** Subtle hover (lift, slight scale), focus transitions, button press feedback, gentle fade-in on scroll if not disabled by reduced motion.
- **Don’t:** Autoplay looping animations, parallax that can’t be disabled, more than 1–2 animated elements per view, harsh or fast motion.

---

## 8. Accessibility checklist (WCAG 2.1 AA)

### Contrast

- **Normal text:** ≥4.5:1 on background.  
  **Valid pairs:** `#2e2e2e` on `#ffffff` or `#f6f5ef`; `#564298` on `#ffffff` or `#f6f5ef` for large text or UI.
- **Large text (18px+ or 14px bold+):** ≥3:1.
- **UI components:** ≥3:1 against adjacent background.
- **Avoid:** Body-sized text in `#9b8fb5` or `#a8ddd4` on cream/white. Use these for backgrounds, borders, or large decorative text only.

### Focus

- All interactive elements (links, buttons, form controls) must have a **visible focus indicator**.
- Use `:focus-visible` with 2px solid ring in `#564298` and 2px offset (in `design-system.css`). Do not remove outline without replacing with a ring.

### Motion

- Respect `prefers-reduced-motion: reduce`: no essential information only by animation; durations 0 when preference set.

### Text and layout

- Body font size ≥16px; line height ≥1.5. Support zoom to 200% without loss of content or functionality.
- Heading hierarchy: one H1 per page; sequential H2 → H3 (no skips).
- Form inputs: visible labels (not placeholder-only); errors associated and announced (e.g. `aria-describedby`, `role="alert"`).

### Keyboard and screen readers

- All functionality available via keyboard; tab order logical.
- Icon-only buttons have `aria-label`. Images have descriptive `alt` text (or `alt=""` if decorative).
- **Skip link:** “Skip to content” or “Skip to main content” must be the first focusable element on every page (e.g. `<a href="#main" class="skip-link">`). Visible on focus only; target `id="main"` on main content. Required for nav-heavy pages and WCAG 2.4.1 (Bypass Blocks).

### Touch

- Touch targets ≥44×44px; adequate spacing between targets (≥8px).

---

## 9. Anti-patterns to avoid (healthcare / care sector)

### Visual and tone

- **Avoid:** Clinical or hospital-like imagery (white coats, sterile rooms, generic “medical” blue).
- **Avoid:** AI-style purple/pink gradients or neon accents; stick to brand purple/teal/cream.
- **Avoid:** Harsh or fast animations; autoplay carousels without pause; parallax that can’t be turned off.
- **Avoid:** Overly playful or childish (e.g. cartoon characters) unless audience is clearly children/families in that context.
- **Do:** Warm, calm, human imagery; real people (with consent); home and community settings; clear, reassuring copy.

### Trust and compliance

- **Avoid:** Fake urgency (“Only 2 spots left”) or pressure tactics.
- **Avoid:** Burying CQC or regulatory info; keep “Rated Good” and trust signals visible.
- **Avoid:** Vague or unverifiable claims; prefer concrete outcomes and real testimonials.
- **Do:** Clear contact info; visible CQC badge; real names/roles in testimonials; “No obligation” where appropriate.

### Forms and conversion

- **Avoid:** Long forms above the fold; keep consultation form to 3–5 fields.
- **Avoid:** Submit with no feedback; always show loading then success/error.
- **Avoid:** Placeholder-only labels; always use visible labels for accessibility.

### Technical

- **Avoid:** Conveying information by colour alone (e.g. red/green only); add icons or text.
- **Avoid:** Removing focus outline without a visible replacement.
- **Avoid:** Tiny touch targets or cramped tap areas on mobile.

---

## 10. File and token reference

- **Design tokens and utilities:** `assets/css/design-system.css`
- **Components (buttons, cards, etc.):** `assets/css/components.css`
- **This document:** `design-system/MASTER.md`

Enqueue **Poppins** and **Open Sans** (e.g. Google Fonts) in the theme; weights 300, 400, 500, 600, 700 (Poppins) and 300, 400, 600, 700 (Open Sans). Use `--font-family-heading` and `--font-family-body` from the CSS for consistency.

---

## Quick reference: do’s and don’ts

| Do | Don’t |
|----|--------|
| Soft UI Evolution: soft shadows, 8–12px radius | Clinical look; harsh edges; AI purple/pink gradients |
| Hero + Testimonials + Trust + CTA structure | Burying CQC or trust signals |
| Poppins headings, Open Sans body | Body text in accent/teal on light backgrounds |
| 44×44px min touch targets; visible focus | Placeholder-only labels; outline: none without replacement |
| 150–250ms transitions; prefers-reduced-motion | Autoplay carousels; essential info only in motion |
| Warm, real imagery; “No obligation” where relevant | Fake urgency; vague claims; long forms above fold |
