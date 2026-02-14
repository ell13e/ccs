# CCS Theme — Performance Notes

**Skills applied:** `/web-performance-optimization`, `/wp-performance`, `/ui-ux-pro-max`  
**Scope:** Asset loading, JS/CSS patterns, Core Web Vitals targets.

---

## 1. Asset strategy (inc/theme-setup.php)

- **Critical CSS:** Inlined via `inc/performance/class-critical-css.php`; fallback `assets/css/critical.css`.
- **Non-critical CSS:** Deferred (media=print + onload="this.media='all'").
- **Scripts:** Enqueued in footer; defer applied via `ccs_defer_scripts()` to navigation, form-handler, consultation-form, site-interactions, scroll-animations, careers-interactions.
- **Conditional loading:** Homepage-only and careers-only CSS/JS enqueued only on those templates.

---

## 2. JavaScript patterns (Prompt 26)

- **Scroll / sticky CTA:** Throttled with requestAnimationFrame (careers-interactions.js).
- **Scroll animations:** Intersection Observer; run once per element; no continuous scroll listeners.
- **Parallax:** Disabled on mobile and when `prefers-reduced-motion: reduce`.
- **Confetti:** Canvas-based; runs ~2.8s then removes node; now skips when `prefers-reduced-motion: reduce`.
- **Smooth scroll:** Focus moved to target; URL updated; no layout thrashing.

---

## 3. CSS patterns

- **Transforms:** Card hover uses translateY (GPU-friendly); transitions 150–300ms.
- **No layout shift:** Hero and CQC reserve space; images should use dimensions (width/height or aspect-ratio) where applicable.

---

## 4. Targets (to verify with Lighthouse)

| Metric | Target |
|--------|--------|
| **LCP** | < 2.5s |
| **INP / FID** | < 100ms |
| **CLS** | < 0.1 |
| **Lighthouse Performance** | 90+ |
| **JS bundle (per page)** | Keep enhancement scripts small; aim <50KB total for theme JS where feasible |

---

## 5. Checklist (post-audit)

- [ ] Run Lighthouse on homepage (mobile + desktop).
- [ ] Run Lighthouse on contact page (form + confetti).
- [ ] Run Lighthouse on careers page (sticky CTA).
- [ ] Confirm no console errors on key flows.
- [ ] Confirm hero image (if used) has dimensions and modern format (WebP) where possible.

## 6. Final verification (skills + deliverables)

- [ ] **Skills syntax:** All references use `/skill` (e.g. `/ui-ux-pro-max`, `/architecture`), not `@skill`.
- [ ] **Design system:** MASTER.md has spacing token `2.5xl`, skip-link a11y, animation/reduced-motion guidance.
- [ ] **Architecture:** ARCHITECTURE-REVIEW.md covers structure, performance, security, admin UX.
- [ ] **Hero:** Docblock states schema from `inc/schema-markup.php`; CTAs have visible focus ring (`.hero-homepage__cta:focus-visible`).
- [ ] **Copy:** Why Choose Us CTA is “Explore our home care services in Maidstone & Kent”.
- [ ] **CQC:** Subheading prominent; registration number visible (“Registration no. 1-2624556588”); schema on front page only.
- [ ] **Animations:** Parallax and confetti respect `prefers-reduced-motion`.
- [ ] **Assets:** Enqueue strategy in theme-setup.php; no separate inc/enqueue.php.
- [ ] **Lighthouse:** Performance 90+; no critical console errors.
- [ ] **WCAG 2.1 AA:** ACCESSIBILITY-COMPLIANCE-REPORT.md; contact form success message has `role="status"` or `aria-live="polite"` for screen readers.

---

## 7. Summary

- Enqueue strategy is documented in theme-setup.php; conditional and deferred loading are in place.
- Enhancement scripts use rAF, Intersection Observer, and reduced-motion checks.
- Final Lighthouse scores and bundle sizes should be filled in after a live run (e.g. in CI or manual test).

**Document version:** 1.0  
**Skills:** /web-performance-optimization, /wp-performance
