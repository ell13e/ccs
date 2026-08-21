# Accessibility & build checklist

Merged resource checklist for the CCS theme build. Tick items as you review them.

## 📜 Official standard

- [ ] [WCAG 2.2 (W3C Recommendation) (⭐ Core reference)](https://www.w3.org/TR/WCAG22/) — The conformance target — the normative rulebook behind every accessibility decision on the build
- [ ] [Understanding WCAG 2.2 (🔁 Regular dip-in)](https://www.w3.org/WAI/WCAG22/Understanding/) — Per-criterion intent, who benefits, and worked examples — open when a rule feels ambiguous for your case
- [ ] [How to Meet WCAG — Quick Reference (2.2, all levels) (⭐ Core reference)](https://www.w3.org/WAI/WCAG22/quickref/?versions=2.2&currentsidebar=%23col_customize&levels=aaa) — The whole standard as a filterable checklist — the daily build reference, with sufficient techniques + documented failures per criterion
- [ ] [How to Meet WCAG 2.1 — Quick Reference (📌 Useful one-off)](https://www.w3.org/WAI/WCAG21/quickref/) — The older baseline — superseded by 2.2 (which is purely additive); kept for citing version differences
- [ ] [Understanding Conformance (levels A / AA / AAA) (🔁 Regular dip-in)](https://www.w3.org/WAI/WCAG21/Understanding/conformance#levels) — Why AA is the floor, blanket AAA isn't recommended, and conformance is full-page only — including every responsive variation and the CQC widget
- [ ] [Understanding Techniques for WCAG Success Criteria (🔁 Regular dip-in)](https://www.w3.org/WAI/WCAG21/Understanding/understanding-techniques) — Sufficient vs advisory vs failures — techniques are informative, only the success criteria are required; failures prove non-conformance
- [ ] [ATAG 2.0 (W3C Recommendation) (📌 Useful one-off)](https://www.w3.org/TR/ATAG20/) — The standard that judges authoring tools (WordPress), not the site — Part A: accessible tool UI; Part B: helping authors produce accessible content
- [ ] [ATAG Overview (W3C WAI) (📌 Useful one-off)](https://www.w3.org/WAI/standards-guidelines/atag/) — Plain-English intro to ATAG — CMSs are explicitly in scope; a tidy citation for uni work on tooling choices

## 🦮 ARIA & semantics

- [ ] [WAI-ARIA 1.2 specification (🔁 Regular dip-in)](https://www.w3.org/TR/wai-aria/) — The vocabulary of roles, states & properties that feeds assistive tech — semantics only, zero behaviour (you script the keyboard support)
- [ ] [ARIA Authoring Practices Guide (APG) (⭐ Core reference)](https://www.w3.org/WAI/ARIA/apg/) — The living pattern library — the disclosure pattern for the mobile menu & FAQ accordions, landmarks, accessible names and full keyboard specs
- [ ] [Using ARIA (the rules of ARIA use) (🔁 Regular dip-in)](https://www.w3.org/TR/using-aria/) — Discontinued draft, but home of the rules of ARIA use — native HTML first, and no ARIA is better than bad ARIA
- [ ] [ARIA Landmarks Example — General Principles (📌 Useful one-off)](https://www.w3.org/TR/wai-aria-practices/examples/landmarks/) — Worked examples of landmark regions — the page-skeleton pattern for every CCS template
- [ ] [ARIA Landmarks Example — HTML Sectioning Elements (📌 Useful one-off)](https://www.w3.org/TR/wai-aria-practices/examples/landmarks/HTML5.html) — How header / nav / main / footer map to landmarks automatically — semantic HTML does the ARIA for you

## 🧑‍💻 WordPress standards

- [ ] [WordPress CSS Coding Standards (⭐ Core reference)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/) — The CSS house style for the hand-coded theme — distilled in the Coding Standards section of Build HQ
- [ ] [WordPress PHP Coding Standards (⭐ Core reference)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) — The full PHP house rules — naming, Yoda conditions, braces, long arrays, $wpdb->prepare and the never-list (eval, extract, goto)
- [ ] [WordPress Accessibility Coding Standards (🔁 Regular dip-in)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/) — WordPress's own accessibility standard (WCAG-based) — and the source of this whole resource list
- [ ] [WordPress PHP Documentation Standards (⭐ Core reference)](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/) — The DocBlock rulebook — third-person summaries, @since changelogs and @param format for every theme function, hook and file header
- [ ] [WordPress JavaScript Documentation Standards (🔁 Regular dip-in)](https://developer.wordpress.org/coding-standards/inline-documentation-standards/javascript/) — JSDoc rules for the theme's JavaScript — mirrors the PHPDoc format with @since, @param and @return
- [ ] [WordPress JavaScript Coding Standards (⭐ Core reference)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/) — House style for the theme's JS (menu, accordions) — strict equality, const/let, spacing, naming and semicolons
- [ ] [WordPress Markdown Style Guide (📌 Useful one-off)](https://developer.wordpress.org/coding-standards/styleguide/) — Formatting conventions for repo docs and READMEs — italic, bold, fenced code blocks with language tags
- [ ] [WordPress HTML Coding Standards (🔁 Regular dip-in)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/) — Markup rules for every template — always quote attributes (security), W3C validation, lowercase tags, tab indentation

## 🎨 CSS craft

- [ ] [Unitless line-heights (Eric Meyer) (📌 Useful one-off)](https://meyerweb.com/eric/thoughts/2006/02/08/unitless-line-heights/) — Why body line-height is 1.5, never 1.5em — unitless values inherit as a scaling factor, not frozen pixels
- [ ] [idiomatic-css (Nicolas Gallagher) (🔁 Regular dip-in)](https://github.com/necolas/idiomatic-css) — Code like one person typed it — the consistency philosophy under the WP standard, plus the max-1-level nesting rule

## 🧰 Tooling

- [ ] [css-audit (WordPress) (🔁 Regular dip-in)](https://github.com/WordPress/css-audit) — Pre-launch audit CLI — catches palette drift beyond the 6 brand tokens, !important creep and specificity spikes
- [ ] [Accessibility Support (http://a11ysupport.io) (🔁 Regular dip-in)](https://a11ysupport.io/) — Will this code actually work with assistive tech? Check here before relying on any ARIA feature
- [ ] [GitHub Actions Workflow Standards (📌 Useful one-off)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/github-actions/) — Workflow security if the theme repo ever gets CI — permissions: {}, SHA-pinned actions, no template injection
- [ ] [WordPress Development in 2026 (DeployHQ) (📌 Useful one-off)](https://www.deployhq.com/blog/wordpress-development-in-2025-from-full-site-editing-to-flawless-deployments) — Git-based WordPress deployment workflows — the article body wouldn't load for AI, so worth opening directly; the deploy-via-Git principle is captured in Build HQ

## 🇬🇧 Gov & legal

- [ ] [UK Government Digital Service blog (🔁 Regular dip-in)](https://gds.blog.gov.uk/) — GDS sets the bar for plain-English, accessible UK services — the right tone benchmark for a regulated care brand
- [ ] [Section 508 — Create Accessible Software & Websites (US) (📌 Useful one-off)](https://www.section508.gov/develop/software-websites/) — US federal accessibility how-tos — good practical checklists even outside the US
- [ ] [Accessibility in Government Blog (UK) (🔁 Regular dip-in)](https://accessibility.blog.gov.uk/) — UK practitioners on real accessibility decisions — closest match to CCS's older, UK audience

## ✍️ Expert blogs

- [ ] [Deque — Web Accessibility Blog (📌 Useful one-off)](https://www.deque.com/blog/) — From the makers of axe — practical testing and remediation articles
- [ ] [WebAIM — Web Accessibility In Mind (⭐ Core reference)](https://webaim.org/) — The most practical single a11y resource — plain-English articles, plus the contrast checker for the 7:1 brand palette checks
- [ ] [Adrian Roselli (📌 Useful one-off)](https://adrianroselli.com/) — Brutally thorough component-level accessibility testing write-ups — check before building anything fancy
- [ ] [Tink — Léonie Watson (📌 Useful one-off)](https://tink.uk/) — Engineer and screen reader user — how the web actually sounds to the people CCS serves
- [ ] [TPGi Blog (🔁 Regular dip-in)](https://www.tpgi.com/blog/) — Deep technical accessibility consultancy writing — home of Steve Faulkner's work
- [ ] [Scott O’Hara (📌 Useful one-off)](https://www.scottohara.me/) — Accessible HTML patterns from a co-editor of ARIA in HTML — great for markup-level questions
- [ ] [Joe Dolson (📌 Useful one-off)](https://www.joedolson.com/blog) — WordPress accessibility from a WP core contributor — directly relevant to the custom theme
- [ ] [Sarah Higley (📌 Useful one-off)](https://sarahmhigley.com/) — Deep dives on complex widgets (tooltips, drag & drop) — for the rare tricky cases
- [ ] [Marco’s Accessibility Blog (Marco Zehe) (📌 Useful one-off)](https://www.marcozehe.de/) — How screen readers and browsers actually interoperate, from a former Mozilla accessibility engineer
- [ ] [Karl Groves (📌 Useful one-off)](https://karlgroves.com/) — Accessibility testing methodology and the business case for a11y
- [ ] [Inclusive Components (Heydon Pickering) (⭐ Core reference)](https://inclusive-components.design/) — A recipe book of accessible components — menus, cards, toggles — exactly what the theme will hand-code
- [ ] [Mozilla Accessibility Blog (📌 Useful one-off)](https://blog.mozilla.org/accessibility/) — Browser-side accessibility developments — users first, no matter their abilities
- [ ] [Equalize Digital — Blog & Resources (🔁 Regular dip-in)](https://equalizedigital.com/resources/) — WordPress-specific accessibility resources — the team behind the Accessibility Checker plugin and WP Accessibility Meetup
- [ ] [Inline Documentation (John James Jacoby) (📌 Useful one-off)](https://jjj.blog/2012/06/inline-documentation/) — The classic 'why bother commenting' argument from a bbPress/BuddyPress lead — the philosophy behind the WP documentation standards
- [ ] [How To Become a WordPress Developer in 2026 (Fizz Designs) (📌 Useful one-off)](https://fizzdesigns.co.uk/how-to-become-a-wordpress-developer/) — The 2026 WP developer roadmap — child themes, lean plugin stacks, Gutenberg/FSE, security cadence and Git deployments; a map of the skills the rebuild is teaching

## 🎪 Community & events

- [ ] [Accessibility London Meetup (📌 Useful one-off)](https://www.meetup.com/London-Accessibility-Meetup/) — London meetup, livestreamed on YouTube — free ongoing CPD close to home
- [ ] [24 Accessibility (📌 Useful one-off)](https://www.24a11y.com/) — Advent-calendar essay series — broad, high-quality one-off reads
- [ ] [WordPress Accessibility Meetup (🔁 Regular dip-in)](https://www.meetup.com/wordpress-accessibility-meetup-group/) — Free online meetup focused purely on WordPress accessibility — recordings available afterwards
- [ ] [WordPress Accessibility Day Conference (📌 Useful one-off)](https://wpaccessibility.day/) — Annual 24-hour conference dedicated to WordPress accessibility

## 🧪 Technical deep-dives

- [ ] [Accessibility APIs: A Key To Web Accessibility (Léonie Watson, Smashing) (📌 Useful one-off)](https://www.smashingmagazine.com/2015/03/web-accessibility-with-accessibility-api/) — How browsers expose your markup to assistive tech via platform APIs — the why behind semantic HTML
- [ ] [How accessibility trees inform assistive tech (Hidde de Vries) (📌 Useful one-off)](https://hacks.mozilla.org/2019/06/how-accessibility-trees-inform-assistive-tech/) — The accessibility tree explained — what screen readers actually receive from your HTML
- [ ] [What is this thing and what does it do? (Karl Groves, video) (📌 Useful one-off)](https://www.youtube.com/watch?v=YLihNhn_MO4) — Talk on how every interface element must communicate its name, role and value
- [ ] [The Browser Accessibility Tree (Steve Faulkner) (📌 Useful one-off)](https://www.tpgi.com/the-browser-accessibility-tree/) — How to inspect the accessibility tree in dev tools — debugging the theme's semantics directly
- [ ] [Brief history of browser accessibility support (Steve Faulkner) (📌 Useful one-off)](https://www.tpgi.com/brief-history-of-browser-accessibility-support/) — Why assistive-tech support varies by browser — useful context for http://a11ysupport.io checks
- [ ] [MDN Web Docs — Accessibility (⭐ Core reference)](https://developer.mozilla.org/en-US/docs/Web/Accessibility) — The day-to-day reference for accessible HTML/CSS/JS — first stop for “how do I mark this up properly”

## 🔍 SEO & search

- [ ] [Internal Links: Ultimate Guide (Semrush) (⭐ Core reference)](https://www.semrush.com/blog/internal-links/) — The linking rulebook — anchor text, crawl depth, orphan pages and the 9-point audit to run before launch
- [ ] [Google PageRank: What the Search Leak Reveals (Semrush) (📌 Useful one-off)](https://www.semrush.com/blog/pagerank/) — Why internal links pass authority — PageRank history, the reasonable-surfer principle and the 2024 leak
- [ ] [Building High-Performing Content Pillars (Semrush) (🔁 Regular dip-in)](https://www.semrush.com/blog/building-high-performing-content-pillars/) — The full pillar workflow — Guide vs What-Is vs How-To formats, content audits and interlinking, with worked examples
- [ ] [Topic Clusters for SEO (Semrush) (⭐ Core reference)](https://www.semrush.com/blog/topic-clusters/) — How clusters build topical authority and E-E-A-T — the strategy behind the Advice & Resources hub
- [ ] [What Is a Pillar Page & How to Create One (Semrush) (⭐ Core reference)](https://www.semrush.com/blog/pillar-page/) — The pillar-page playbook — how the Care Services, Areas and Advice hubs should each anchor a cluster of deeper pages
- [ ] [Google Search Appearance Overview (🔁 Regular dip-in)](https://developers.google.com/search/docs/appearance) — Index of everything that shapes how CCS looks in results — title links, snippets, favicons, sitelinks and structured-data features (LocalBusiness, FAQ, Breadcrumb)
- [ ] [Google SEO Starter Guide (⭐ Core reference)](https://developers.google.com/search/docs/fundamentals/seo-starter-guide) — Google's official basics — descriptive URLs, titles and snippets, alt text, canonicals, plus the myth-busting list (no E-E-A-T ranking factor, no word-count magic)
- [ ] [A Guide to Google Search Ranking Systems (⭐ Core reference)](https://developers.google.com/search/docs/appearance/ranking-systems-guide) — Google's own list of ranking systems — helpful content (now core), site diversity, deduplication, freshness, reliable information
- [ ] [30 WordPress SEO Best Practices (Hostinger) (🔁 Regular dip-in)](https://www.hostinger.com/uk/tutorials/wordpress-seo-tips) — The hands-on WordPress SEO checklist — sitemaps, permalinks, redirects, schema, local SEO and Core Web Vitals; the practical companion to Google's starter guide
