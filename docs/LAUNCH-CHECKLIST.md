# Launch checklist

Pre-launch audit, 25 November 2026. Checked against the launch checklist and the Gemini pre-launch checklist.

## Done and checked

- **Crawl:** all 66 sitemap URLs return 200. None is noindex, canonicals are correct, titles are unique, and each page has one h1.
- **Meta descriptions:** the 5 pages that were missing one now get one, taken from the page intro, the News page or the category.
- **robots.txt:** blocks /wp-admin/ only and lists the sitemap. The users sitemap is off.
- **Old-site redirects:** all 20 old URLs reach a live page in one hop.
- **Structured data:** the JSON-LD is valid. The service and area schema now point at one shared organisation (@id), so Google no longer sees duplicates.
- **Rendering:** all content is in the HTML the server sends. Nothing depends on JavaScript.
- **QA:** PHP syntax, design audit at 4 widths and internal links all pass.
- **Analytics:** the site uses GA4 (G-9HNPQDC6QG) and nothing loads until the visitor accepts cookies. Form sends are recorded as generate_lead and thank-you visits as thank_you_page, each counted once.
- **Google Tag Manager:** the theme supports a container ID, set in Customizer → Analytics. It is consent-gated, and while it is on the theme does not load GA4 itself, so nothing is counted twice.

## GA4 settings (checked, not changed)

| Setting | Now | Recommended |
|---|---|---|
| Event data retention | 2 months | 14 months, so year-on-year Explorations work |
| Internal traffic filter | Exists, but in **Testing** | Set it to **Active** once the office IP is defined in the data stream |
| Search Console link | None | Link it after Search Console is verified |

## Added before launch (in inc/seo/, the future plugin)

- **AI search crawlers in robots.txt** (`inc/seo/robots-ai.php`): OAI-SearchBot, Claude-SearchBot and PerplexityBot are named and allowed, so AI answers can cite the site and link to it. The training bots (GPTBot, ClaudeBot, CCBot, Google-Extended) are left alone because blocking them is a business decision. To block them, use the `ccs_seo_ai_blocked_bots` filter.
- **IndexNow** (`inc/seo/indexnow.php`): when a page or article is published, updated or taken down, Bing, Yandex, Seznam, Naver and Yep are told straight away. Google doesn't take part. It runs only on the live domain, never locally, on staging or when an SEO plugin is on. The key file is served at `/{key}.txt`.
- **Not added:** llms.txt. Google says it doesn't use it, and Lighthouse lists it only as an optional, experimental check.

## Launch day (your jobs)

1. In the Customizer, enter **GTM-TGWL555X**. Publish the GTM container first.
2. Send one test enquiry, then mark **generate_lead** as a key event in GA4.
3. Verify Search Console as a Domain property (a DNS TXT record), submit `/wp-sitemap.xml`, then link it to GA4.
4. Add the site to Bing Webmaster Tools (it can import from Search Console). After the first page update, check its IndexNow report to confirm pings are arriving.
5. Set event data retention to 14 months, then define the office IP and set the internal traffic filter to Active.
6. Server: turn WP_DEBUG off, force HTTPS, and check HSTS.
7. Run Lighthouse on the live homepage, an area page and the contact page. Do a quick VoiceOver pass.
8. Choose one business name and use it everywhere: on the site, the Google Business Profile and directories. The audit found both "Continuity Care Services" and "Continuity of Care Services (Limited)"; the logo uses the second.
