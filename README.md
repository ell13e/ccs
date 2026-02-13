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
CCS-WP-THEME/
├── style.css              # Theme header + base styles
├── functions.php           # Loader only; includes inc/setup.php
├── index.php               # Main template
├── header.php              # Document head + masthead
├── footer.php              # Footer + wp_footer()
├── inc/
│   └── setup.php           # Theme support, menus, enqueue
├── template-parts/
│   ├── content.php         # Default post/content loop
│   └── content-none.php    # No results
├── assets/
│   ├── css/                # Stylesheets
│   ├── js/                 # Scripts
│   ├── images/             # Theme images
│   └── fonts/              # Web fonts (if any)
└── README.md
```

- **Root PHP:** `index.php`, `header.php`, `footer.php` are the core templates; `functions.php` only loads `inc/setup.php`.
- **inc/** All theme logic (setup, enqueue, custom functionality) lives here.
- **assets/** Static assets; organised by type (css, js, images, fonts).

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
