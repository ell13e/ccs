# Kent Care Provider – WordPress Theme

Custom WordPress theme for a Kent-based home care provider. Built for speed, conversions, and SEO, with all copy in English (UK).

**Theme name:** Kent Care Provider  
**Text domain:** `ccs-wp-theme`  
**Version:** 1.0.0

---

## Installation

1. **From ZIP (production)**
   - Zip the theme folder (ensure `style.css` and `functions.php` are at the root of the ZIP).
   - In WordPress: **Appearance → Themes → Add New → Upload Theme**, then upload the ZIP and activate.

2. **From project folder (development)**
   - Copy or symlink this folder into `wp-content/themes/` of your WordPress install.
   - In **Appearance → Themes**, find “Kent Care Provider” and click **Activate**.

---

## Development setup

- **Requirements:** WordPress 5.9+, PHP 7.4+
- **Local WP:** Use Local by Flywheel, MAMP, or similar; point the site’s theme directory to this project (or a copy/symlink under `wp-content/themes/`).
- **Text domain:** Use `ccs-wp-theme` for all `__()`, `_e()`, `esc_html_e()`, etc., and for translations.
- **Language:** Keep all theme strings and default copy in British English.

---

## File structure

```
CCS/
├── style.css                # Theme header only; real styles in assets/css
├── functions.php             # Loader: constants, autoloader, CPTs, taxonomies
├── theme.json                 # Block-editor palette, typography, spacing
├── header.php / footer.php / page.php / search.php / index.php
├── single-service.php / single-location.php
├── inc/                       # All theme logic — see docs/CODEBASE-PARSE.md §5
├── page-templates/             # PHP page templates — see docs/CODEBASE-PARSE.md §6
├── template-parts/             # Reusable parts (home/*, breadcrumb, modals)
├── assets/
│   ├── css/                  # design-system.css, components, header, footer, per-page CSS
│   ├── js/                   # navigation, resource-download, consultation-form
│   ├── images/, fonts/
├── design-system/
│   └── MASTER.md              # Canonical, live design system (colours, type, spacing, WCAG)
├── docs/                      # Technical/architecture docs + audits (see below)
│   └── research/               # Market, brand and UX research
├── reference/                  # Copy, strategy, sitemap, reviews, rebuild-direction design docs
└── BUILD-LOG.md                # Historical session log
```

- **Root PHP:** `header.php`, `footer.php`, `page.php`, `search.php`, `index.php` are the core templates; `functions.php` is the bootstrap (constants, autoloader, CPT/taxonomy registration).
- **inc/** All theme logic (setup, enqueue, forms, admin, SEO, performance, accessibility, blocks) lives here, grouped by area.
- **assets/** Static assets; organised by type (css, js, images, fonts).

---

## Documentation map

Docs are `.md`-only and split by purpose:

| Looking for… | Go to |
|---|---|
| **The live design system** (colours, type, spacing, shadows, WCAG — matches `theme.json` / `design-system.css` today) | [`design-system/MASTER.md`](design-system/MASTER.md) |
| **The rebuild's proposed design direction** (different "Mint Purple" palette, not yet live) | [`reference/design-tokens.md`](reference/design-tokens.md) + [`reference/design-language.md`](reference/design-language.md) |
| **The current, authoritative site strategy & build plan** (IA, page templates, wireframes, SEO, accessibility standards, coding standards) | [`reference/strategy.md`](reference/strategy.md) |
| **Redirect map / URL build tracker** | [`reference/sitemap-urls.md`](reference/sitemap-urls.md) |
| **Real reviews, competitor teardowns, homepage copy, coverage data** | [`reference/reviews.md`](reference/reviews.md), [`reference/inspo-teardowns.md`](reference/inspo-teardowns.md), [`reference/copy/homepage.md`](reference/copy/homepage.md), [`reference/schema-data.md`](reference/schema-data.md) |
| **Codebase architecture / where things live** | [`docs/CODEBASE-PARSE.md`](docs/CODEBASE-PARSE.md) |
| **Site copy, page inventory, forms, menus** (content-and-implementation reference) | [`docs/CCS-THEME-AND-CONTENT-GUIDE.md`](docs/CCS-THEME-AND-CONTENT-GUIDE.md) |
| **Critical CSS strategy** | [`docs/DESIGN-SYSTEM-CRITICAL-CSS-STRATEGY.md`](docs/DESIGN-SYSTEM-CRITICAL-CSS-STRATEGY.md) |
| **Accessibility audit of the current implementation** | [`docs/ACCESSIBILITY-AUDIT.md`](docs/ACCESSIBILITY-AUDIT.md) |
| **WCAG standards checklist to build against** | [`reference/a11y-checklist.md`](reference/a11y-checklist.md) |
| **Security audit** | [`docs/SECURITY-AUDIT.md`](docs/SECURITY-AUDIT.md) |
| **Market/brand/UX research** (comprehensive research, brand voice, design fundamentals, competitive landscape, UI polish backlog) | [`docs/research/`](docs/research/) |
| **History of the CTA → CCS content migration** | [`docs/FINALCTAIHOPE-inventory.md`](docs/FINALCTAIHOPE-inventory.md), [`docs/FINALCTAIHOPE-to-CCS-workflow.md`](docs/FINALCTAIHOPE-to-CCS-workflow.md) |
| **Build session history / decisions log** | [`BUILD-LOG.md`](BUILD-LOG.md) |

---

## Repository

Initialised as a Git repository. For a remote named **CCS-WP-THEME**:

```bash
git remote add origin <your-repo-url>
git branch -M main
git add .
git commit -m "Initial theme structure"
git push -u origin main
```

---

## License

GNU General Public License v2 or later.
