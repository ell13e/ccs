# Your own SEO plugin: plan

27 November 2026. The SEO code that was in the theme's `inc/seo/` has moved into a plugin of its own, **CCS SEO**, in `../ccs-seo/` beside the theme. Rank Math Pro and Yoast SEO Premium are the benchmarks: the plan matches what a care provider's site needs, not every feature they sell.

Working name: **CCS SEO** (rename any time).

## What the theme already does (about 2,400 lines in `inc/seo/`)

| Area | Now |
|---|---|
| Titles and descriptions | Per-page fields, automatic fallbacks, length limits |
| Social sharing | Open Graph and X cards, with a default image |
| Canonical and robots | Canonical on every page, noindex/nofollow per page |
| Sitemap | WordPress's own, tidied: no users, no thank-you page, no noindex pages |
| Schema | Organisation (HomeHealthCareService), website, web page, article, service, area, contact, FAQ, breadcrumbs, as one linked graph |
| Redirects | The old site's 20 addresses, written in code |
| robots.txt | AI search bots named and allowed |
| IndexNow | Pings Bing and the others on publish, update and unpublish |
| Search Console | Verification tag |

**Where it's tied to the theme:** company details (phone, email, address, hours, CQC rating and report, prices, social links, logo) are read from the theme's Customizer. The SEO box exists only on pages. Redirects are a list in code, so changing one means a developer.

## How Rank Math Pro and Yoast Premium compare

Read in the browser on 27 Nov 2026 from their own pages: Rank Math's free-vs-pro table and its guides to redirections and the 404 monitor; Yoast's Premium page and its WordPress.org listing.

| Feature | Rank Math | Yoast | Yours now | Plan |
|---|---|---|---|---|
| Title, description, robots per page, all post types | Free | Free | Pages only | **Stage 1** |
| Google preview (desktop and mobile) | Free | Free | No | **Stage 2** |
| Social preview (Facebook, X) | Free | Premium | No | **Stage 2** |
| Bulk editor for titles and descriptions | Pro (advanced) | Free (AI drafts are Premium) | No | **Stage 2**, in the needs-attention list |
| "What to work on next" list | No | Premium (task list) | No | **Stage 2**, as the needs-attention list |
| Redirect manager: exact, contains, starts with, ends with, regex; 301, 302, 307, 410, 451; hit counts | Free | Premium | Code only | **Stage 2** |
| Redirect prompt when a page moves or is deleted | Free (automatic) | Premium | No | **Stage 2**, automatic |
| Redirects CSV import and export | Pro | Premium | No | **Stage 2** |
| 404 log: address, hits, last seen; referrer and browser in advanced mode | Free (export is Pro) | No | No | **Stage 2**, with a one-click redirect from each row |
| Orphaned pages (nothing links to them) | Pro | Premium | No | **Stage 2**, in the needs-attention list |
| Image alt text: fill in automatically, find and replace | Free and Pro | No | From the photo sheet | **Stage 2**: a report of missing alt text |
| AI training bot blocker (GPTBot, CCBot, Google-Extended) | No | Premium | Code only | **Stage 2**: switches |
| IndexNow | Separate free plugin | Premium | Yes | Done; move into the plugin |
| llms.txt | Free | Free | No | Stage 3 |
| Schema | Builder with 18 types, 840+ in Pro | Automatic graph, more types in Premium | Automatic graph, care-specific | Stage 1: settings screen; care details become options |
| Local business | Pro (multiple locations) | Premium (Local SEO) | Organisation plus area pages | Covered |
| Keyphrase, readability and inclusive language analysis | Free (30+ tests) | Free (5 keyphrases in Premium) | No | Stage 3: a short checklist |
| Internal link suggestions | Pro with Content AI | Premium | No | Stage 3 |
| Front-end SEO inspector | No | Premium | No | Stage 3 |
| Search Console and GA4 data in WordPress | Free (more in Pro) | Through Site Kit | No | Stage 3 |
| Rank tracking | Pro (paid data) | Through Semrush/Wincher | No | Skip: needs a paid data source |
| AI writing (titles, descriptions, content ideas) | Content AI subscription | Premium | No | Skip for now: needs an AI account and costs per use |
| Import from Yoast, Rank Math, AIOSEO | Free | Free | No | Stage 3 (it's going on other sites) |
| News and video sitemaps, WooCommerce, Elementor, Divi, podcasts, AdSense | Pro | Premium add-ons | n/a | Skip |

## What the Semrush guide adds

From Semrush's "What Is Google SEO?" guide (Aug 2025), read 27 Nov 2026. Most of it is about content and links rather than plugin work. Points that shape the plugin:

- **Title length:** 50 to 60 characters. The plugin already trims to 60; the preview will show the count.
- **Description length:** Semrush suggests 105 characters or fewer, so nothing important is cut off. Google shows about 155 to 160 on desktop and less on mobile. The plugin keeps 155 as the limit, and the preview will show where mobile cuts off.
- **Leftovers from development block new sites.** The needs-attention list will warn loudly when "Discourage search engines" is on, when robots.txt blocks a page, or when a page in the menu is noindex.
- **404s** come from typos and old links. The 404 log covers this.
- **Search Console's Page indexing report** is the most reliable check that Google can crawl and index the site. The plugin can link straight to it; showing its data inside WordPress is Stage 3.
- **Page speed and the Google Business Profile** matter, but they belong to the theme and to Google, not to the plugin.

## What Google's own documentation says (Search Central)

Read 27 Nov 2026: the SEO Starter Guide in full, plus title links, snippets, robots meta tags, canonicals, redirects, sitemaps, general structured data rules, Organization, LocalBusiness, site names, image SEO and "AI features and your website".

**Confirms what the plugin already does**
- Unique title per page, with the site name short at the end after a separator. No limit on length, but Google cuts titles to fit the screen.
- A canonical on every page, pointing to itself, with the full address.
- Sitemaps list only pages you want found: no noindex pages, no duplicates.
- Structured data in JSON-LD, linked by `@id` into one graph.
- AI Overviews and AI Mode have no special requirements: "You don't need to create new machine readable files, AI text files, or markup." This is why llms.txt stays low priority.

**Changes for the plugin**
1. **Google ignores sitemap `priority` and `changefreq`.** It only uses `lastmod`, and only when it's accurate. The plugin will stop setting priority and keep lastmod honest.
2. **Unknown old addresses shouldn't all go to the homepage.** Google treats a redirect to an unrelated page as a "soft 404". Old addresses with no matching page should return 410 (gone) instead. Today, `/home/anything/` and `/sitemap/` redirect to the homepage. The redirect manager will offer 410 for these.
3. **Area pages are marked up as separate local businesses**, each with its own address. Google's rules say structured data must describe what's really there. There's one office, so area pages should describe the service and the area it covers (`Service` with `areaServed`) and link to the one business. This needs your say before it changes.
4. **Organisation details:** Google recommends adding as many real details as apply: alternate name, founding date, contact point, company number. The settings page will have fields for these.
5. **Site name:** Google reads the site name from WebSite structured data on the homepage, with an optional `alternateName`. That's a new setting.
6. **Robots controls:** the SEO box will offer noindex, nofollow, nosnippet and max-snippet. `nosnippet` also keeps a page's text out of AI Overviews and AI Mode.
7. **The sitelinks search box is retired** (Google, November 2024), so the WebSite `SearchAction` does nothing for Google. It's harmless and stays for other search engines.
8. **Images:** descriptive file names and alt text, and the main image declared in metadata. An image sitemap is a Stage 3 option.

## Structured data research (27 Nov 2026)

Read in full in the browser:
- **schema.org:** the schemas overview, and the complete vocabulary file (826 types; every type's parent, and whether it is pending, retired or deprecated).
- **Google:** the structured data intro, the list of supported features (search gallery), the general guidelines, and the Organization, Local business, Profile page, Article, Breadcrumb, Review snippet, Job posting and Vacation rental pages, plus the Search Central changelog.
- **getmerank:** "What is schema in SEO".

**Rules that decide what the plugin outputs**
1. **Google's documentation is the authority for Google**, not schema.org. Unrecognised types are ignored.
2. **Only mark up what visitors can see**, and make it match the page exactly: author, breadcrumbs, prices, phone, address.
3. **Fewer, complete and accurate properties beat many thin ones.**
4. **Use the most specific true type.** Never use a "pending" or "attic" (retired) schema.org type.
5. **Reviews of your own business on your own site** (including embedded Google or Facebook review widgets) never get star ratings for a LocalBusiness or Organization. The plugin must not add AggregateRating to the organisation.
6. **FAQ rich results ended for all sites on 7 May 2026**, and HowTo results in 2023. FAQPage markup is harmless and can stay, but it earns nothing in Google.
7. **Job posting** data must sit on each job's own page, next to the description visitors read. The CCS vacancies come through the job board's frame, so the job board provides it.
8. **Vacation rental** rich results are only for sites working with a Google account manager through Hotel Center. A holiday home still uses `VacationRental` so search engines understand it. For accessibility, Google's amenity list includes `wheelchairAccessible` and `elevator`.

**Mistakes found and fixed**
- `HomeHealthCareService` isn't a schema.org type: replaced with `LocalBusiness`.
- The plugin's dropdown offered `ProfessionalService` (deprecated by schema.org) and `Physiotherapy` (a medical specialty, not a business type): both removed. Every remaining option is checked against the vocabulary file.
- **Area pages** described each town as a separate local business with its own address and map position. There is one office. They now describe the home care service and the town it covers (`Service` + `areaServed`), provided by the one business. Each page also had a second copy of this data from its template; that has been removed.
- **Service pages:** a second copy in the template (Service and FAQPage) has been removed. The single graph now adds `serviceType` and `areaServed` (Kent).
- **Breadcrumb data** didn't match the breadcrumbs visitors see on any inner page (missing "Our care services", "Careers", "Resources" and "Who You'll Meet"; "Locations" instead of "Areas we cover"). It is now built from the same trail the page shows.
- **Article author** was a *Person* called "Continuity of Care Services". The byline shows the business, so the author is now the organisation itself.
- **Kent** was typed as a `State`. It's now an `AdministrativeArea`.

**Added (Google recommends them, and they're visible on the site or checked against Companies House)**
- `legalName` (Continuity of Care Services Limited), the company number, and the founding year.
- **Page types:** the contact page is a `ContactPage`, the About page an `AboutPage`, News and topic pages `CollectionPage`s.
- **Team pages** are `ProfilePage`s about the person: name, role, photo, and who they work for. Google lists "an employee page on a company website" as a valid use.

## Build order

**Stage 1: the move (same site, nothing visible changes)** (built 27 Nov 2026)
1. Create a plugin folder with its own settings page: company details, logo, social profiles, verification codes, default share image, title format.
2. The plugin reads the theme's Customizer values until its own settings are filled, so nothing is lost on the switch.
3. The SEO box works on every post type: pages, posts, services and areas.
4. Things in the theme that need SEO details ask the plugin through filters. Things in the plugin that need theme details (FAQs, page intros) ask the theme the same way.
5. If the plugin is switched off, the theme still works, with only its basic titles.
6. Check: every page's head output (titles, descriptions, schema, canonicals, robots) is identical before and after.

Stage 1 as built:
- **Plugin:** settings page (SEO in the admin menu); SEO box on pages, posts and areas (services keep their own box); title, description, sharing tags, canonical, Google and Bing verification, schema, sitemap rules, redirects, robots.txt with switches for AI training bots, IndexNow.
- **Theme:** `inc/seo-bridge.php` hands over this site's details through filters. `inc/old-site-redirects.php` holds the old website's addresses. Theme setup switches the plugin on.
- **Check:** the head of all 68 pages compared before and after the move. Titles, descriptions, sharing tags, canonicals and schema are identical (publish and update dates aside, as the preview is rebuilt on restart). Sitemaps, robots.txt and the old-site redirects are identical too.
- **Fixed:** the business was typed `LocalBusiness` + `HomeHealthCareService`, but `HomeHealthCareService` isn't a schema.org type (schema.org returns 404), so search engines ignored it. It's now `LocalBusiness`, and the settings page offers only real types.
- **Made easy (client, 27 Nov 2026: "as easy as possible"):** settings use choices, not codes. Organisation type is a plain-English dropdown, country a dropdown, office hours day and time pickers, founded a year box, the separator radio buttons with a preview, one box per social network, and verification accepts the whole pasted tag.
- **New, but nothing on the site uses them yet:** nosnippet in the SEO box, Bing verification, alternate site name, company number, founding date, training-bot switches.

**Stage 2: the things you'd use weekly**
- Redirect manager, with the old site's 20 addresses imported.
- Automatic redirect when a page's address changes.
- 404 log.
- Google and social previews in the SEO box.
- A "needs attention" list: missing titles or descriptions, noindex pages, missing alt text, orphaned pages, with quick editing.
- AI bot toggles.

Stage 2 progress:
- **Redirect manager: built 27 Nov 2026 (plugin 0.2.0).** Designed from Google's status code and soft 404 guidance, Rank Math's redirections guide and Yoast's redirect manager.
  - **Choices:** "moved for good" (301), "for now" (302, or 307 under More options), "gone" (410), legal (451).
  - **Matching:** this address only, everything under it, or a pattern.
  - **Inputs:** a pasted full link is cleaned up; the new-address box suggests the site's pages.
  - **List:** hit counts and last used, "Added by" (you, automatic, Old website), switch off, bulk actions, search, CSV import and export.
  - **Automatic:** when a published page's address changes, a redirect is added (child pages too), and any redirect that would hide the new address is removed.
  - **Safety:** chains are flattened, and the homepage, loops and bad patterns are refused.
  - **Old website:** its addresses were copied in once. Two with no successor (`/home/under-construction/`, `/sitemap/`) and unknown `/home/...` addresses now answer 410 instead of redirecting to the homepage (Google's soft 404 guidance).
- Next: 404 log, previews, needs-attention list.

**Stage 3: nice to have**
- A writing checklist (keyphrase in title, description length, headings, internal links).
- Internal link suggestions.
- llms.txt.
- Search Console data in WordPress.
- Importing settings from Yoast or Rank Math (for use on other sites).

## Decisions (27 Nov 2026)

1. **Other sites too.** Nothing care-specific is built in: the organisation type, CQC details and prices become settings, or come from the theme through filters.
2. **Stage 2:** redirect manager, 404 log, previews and the needs-attention list.
3. **AI bot switches:** yes. Search bots are allowed by default; each training bot gets its own switch.
4. **Name:** "CCS SEO" for now. Easy to change until it goes on a second site.
