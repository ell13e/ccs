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
| Keyphrase, readability and inclusive language analysis | Free (30+ tests) | Free (5 keyphrases in Premium) | Search phrase checklist (0.8.0) | Done: Google-backed checks only |
| Internal link suggestions | Pro with Content AI | Premium | No | Stage 3 |
| Front-end SEO inspector | No | Premium | No | Stage 3 |
| Site-wide SEO analysis with a score | SEO Analyzer (runs on rankmath.com; competitor compare in Pro) | No | Yes (0.7.0): SEO check, local, with compare | Done |
| Search Console and GA4 data in WordPress | Free (more in Pro) | Through Site Kit | No | Stage 3 |
| Rank tracking | Pro (paid data) | Through Semrush/Wincher | No | Skip: needs a paid data source |
| AI writing (titles, descriptions, content ideas) | Content AI subscription | Premium | No | Skip for now: needs an AI account and costs per use |
| Import from Yoast, Rank Math, AIOSEO | Free | Free | Yes (0.6.0) | Done |
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
- **Fixed:** the business was typed `LocalBusiness` + `HomeHealthCareService`, but `HomeHealthCareService` isn't a schema.org type (schema.org returns 404), so search engines ignored it. It's now `MedicalBusiness` ("Health or care provider"), the client's choice on 27 Nov 2026. That's a real schema.org type and a kind of LocalBusiness, so Google's local business features still apply. The settings page offers only real types.
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
- **404 log: built 27 Nov 2026 (plugin 0.3.0).** Designed from Rank Math's 404 monitor guide (address, hits and last seen; the advanced mode adds referrer and browser; log limit; exclusions) and the ICO's definition of personal data, where online identifiers such as IP addresses count.
  - **Collects only:** address, hits, visits by people, first and last seen, the linking page (no query string), and person / search engine / bot. Never an IP address or browser string.
  - **Clean-up:** entries clear after 30, 90 (default), 180 or 365 days unseen, with a cap of 2,000.
  - **Not logged:** hacking probes, and addresses that already answer "gone".
  - **Screen:** "Visited by people" by default, with everything (bots included) one click away. Shows hits, who, last seen, and "linked from", where a broken link on the site itself is flagged and kept over outside sources.
  - **Actions:** Add redirect (opens the form filled in; the entry leaves the log once redirected), Ignore, Remove, Empty the log.
  - **Menu:** a badge counts new addresses people hit this week. Settings are on/off, days, and addresses never to log.
- **Previews: built 27 Nov 2026 (plugin 0.4.0).** Sources were read on 27 Nov 2026; each is listed with how current it is:
  - Google Search Central on title links and snippets (each page dated Dec 2025 to Jul 2026): shortened "to fit the device width", and Google may rewrite them. No fixed lengths are published, so the previews are labelled approximate.
  - Meta, "Images in link shares" (updated 30 Jun 2026): 1200 × 630 best, 600 × 315 for the large card, 200 × 200 minimum, about 1.91:1. Images are cached by address; refresh with the Sharing Debugger.
  - LinkedIn Help, "Make your website shareable" (the page says it was last updated about three years ago, so around 2023; it's LinkedIn's current official page): 1200 × 627 minimum, 1.91:1, under 5 MB; images under 401 px wide show as thumbnails.
  - The Open Graph protocol (ogp.me): og:image:alt is "a description of what is in the image (not a caption)".
  - **In the SEO box:** tabs for Google on a computer, Google on a phone, and When shared. They update as the title and description are typed (and with the post title and featured image in the block editor), using the same fallbacks as the live page. The share card flags images that are too small or the wrong shape, or have no alt text. Links to the Facebook Sharing Debugger and LinkedIn Post Inspector.
  - **Fixed on the live site:** og:image:alt was the page title; it's now the image's own description (media library alt text, or the theme's photo sheet). The share image is now the smallest copy at least 1200 px wide, not WordPress's 1024 px "large", and its width and height are stated.
- **Needs attention: built 27 Nov 2026 (plugin 0.5.0).** Designed from Yoast's orphaned content filter (pages with no links to them) and Google's guidance, read 27 Nov 2026: unique titles (title links), unique descriptions (snippets), alt text (image SEO), and "Link best practices" (updated 10 Dec 2025: "Every page you care about should have a link from at least one other page on your site"; only real `<a href>` links count).
  - **Fix first:** the site hidden from search engines; duplicate titles; missing or duplicate descriptions; images in use (featured or in the text) without alt text.
  - **Worth a look:** titles cut short (over 60 characters), pages set to hidden (to confirm), share images under LinkedIn's 1200 × 627, and pages nothing links to.
  - **Link check:** reads every page as a visitor sees it, menus and templates included, in batches. Yoast counts only links in the text, which would wrongly flag most pages here, because their links come from menus and cards.
  - **On the preview:** the 3 team pages awaiting bios have no description, and 6 share images are small. There are no duplicates and no images without alt text. The link check read all pages and found no orphans.
  - Each item has a Fix button, and the menu shows a badge counting the "fix first" items.
- **Per-page sharing image (0.5.1, 27 Nov 2026):** a "Sharing image" picker in the SEO box, used before the featured image, as Yoast and Rank Math do. The five pages the list flagged now share 1200 × 630 crops of real CCS photos (theme `assets/images/share/`), and their banner photos are unchanged.
- **Sharing image on services (0.5.2, 27 Nov 2026):** post types without the full SEO box (on CCS, services) get the same picker as a small "Sharing image" box in the editor sidebar, so a service can share its own picture. Which types get it: `ccs_seo_share_box_post_types`.
- **Importer (0.6.0, 27 Nov 2026):** SEO > Import copies from Yoast SEO, Rank Math or All in One SEO. Storage read from each plugin's own source code on GitHub (all updated 25–27 Nov 2026): Yoast `inc/class-wpseo-meta.php` (`_yoast_wpseo_*` meta; noindex '1' hidden, '2' shown, '0' type default; redirects in `wpseo-premium-redirects-base`), Rank Math `includes/class-installer.php` and its redirections module (`rank_math_*` meta, `rank_math_robots` array, `rank_math_redirections` table), AIOSEO `app/Common/Models/Post.php` (`aioseo_posts` table; robots flags count only when `robots_default` is off) and Rank Math's own AIOSEO importer for the `aioseo_redirects` columns (that table is in AIOSEO Pro, not the public repo). Decisions: fill only empty fields, so it can run again; titles keep only the part before the site name ("%%title%% %%sep%% %%sitename%%" means the default, so it stays blank); variables that change over time (current year) aren't frozen, the page is listed to check; whole-post-type settings aren't copied; a canonical pointing elsewhere is listed, since CCS SEO always self-canonicalises; other plugins' regexes match without the leading slash, so an anchored one gets it back. Tested on the preview with sample data for all three, then removed. Found and fixed on the way: a pattern redirect filling "$1" doubled the closing slash.
- **SEO check (0.7.0, 27 Nov 2026):** SEO > SEO check replaces Needs attention, modelled on Rank Math's SEO Analyzer layout (client's screenshots; Rank Math 1.0.283 tried on the preview: it needs a Rank Math account and runs its tests on rankmath.com's servers, so it can't check a local site). Ours runs on the site's own server. Rank Math tests left out because current guidance doesn't support them: 0.2 s response, under 20 files, under 50 KB of HTML, 10+ internal links, minification as pass/fail, keywords in title and description. Limits used, read 27 Nov 2026: TTFB good ≤0.8 s, poor >1.8 s (web.dev, updated 18 Nov 2025); payload aim <1,600 KiB, flagged >5,000 KiB (Lighthouse); heading order and count don't matter to Google (SEO Starter Guide); supported image formats (Google Image SEO); WebP 26% smaller than PNG, 25–34% smaller than JPEG (Google WebP), so older formats only warn when switching would save 100 KiB or more; width and height on images (web.dev CLS); don't lazy-load the first image (web.dev); cache policy (Lighthouse); HTTPS and Core Web Vitals (Google page experience). Speed, compression, caching, www and HTTPS show as "check after launch" on a local copy rather than failing. The comparison scores only tests that gave an answer on both sites. Found on the way: switching Rank Math on and off left /wp-sitemap.xml answering 404 (its rules outlived it); the plugin now rebuilds WordPress's address rules on the next request after any known SEO plugin is switched off, tested by switching Rank Math on and off.
- **Search phrase checklist (0.8.0, 27 Nov 2026):** a "Search phrase" field in the SEO box (meta `_ccs_seo_phrase`) and a checklist under it. Structure from Yoast's assessments (read from its source: keyphrase in title, description, introduction, subheadings, density, internal links, previously used keyphrase) and Rank Math's; kept only what Google's SEO Starter Guide supports ("think about the words that a user might search for"; stuffing is "against Google's spam policies"; links). Dropped: word count (Google: "no magical word count target"), keyword in the URL ("hardly any effect beyond appearing in breadcrumbs"), minimum density. Over 3% of the words is flagged, Yoast's upper limit. Heading, opening and links are read from the published page because CCS pages get much of their text from the theme, not the editor. A phrase counts when all its words appear in another order ("Home care in Maidstone" for "home care Maidstone"). The importer now copies focus keyphrases too: Yoast `_yoast_wpseo_focuskw`, Rank Math `rank_math_focus_keyword` (first of the list), AIOSEO `focus_keyword` column or the older `keyphrases` JSON (read from AIOSEO's Post model). Theme: the two hero photos and the video poster now carry width and height (the SEO check's last warning); their frames still set the size, checked unchanged at 316 × 316 and 920 × 518.
- **Stage 2 is complete.**

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
