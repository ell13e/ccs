# Launch checklist

Pre-launch audit, 25 November 2026. Checked against the launch checklist and the Gemini pre-launch checklist.

## Done and checked

- **Crawl:** all 66 sitemap URLs return 200. None is noindex, canonicals are correct, titles are unique, and each page has one h1.
- **Meta descriptions:** the 5 pages that were missing one now get one, taken from the page intro, the News page or the category.
- **robots.txt:** blocks /wp-admin/ only and lists the sitemap. The users sitemap is off.
- **Old-site redirects:** the old site's addresses reach their new page in one hop. Two with no successor, plus unknown `/home/...` addresses, answer 410 (gone) rather than sending people to the homepage. All of them are listed and editable under SEO → Redirects.
- **Structured data:** the JSON-LD is valid. The service and area schema now point at one shared organisation (@id), so Google no longer sees duplicates.
- **Rendering:** all content is in the HTML the server sends. Nothing depends on JavaScript.
- **QA:** PHP syntax, design audit at 4 widths and internal links all pass.
- **Analytics:** the site uses GA4 (G-9HNPQDC6QG) and nothing loads until the visitor accepts cookies. Form sends are recorded as generate_lead and thank-you visits as thank_you_page, each counted once.
- **Google Tag Manager:** the theme supports a container ID, set in Customizer → Analytics. It is consent-gated, and while it is on the theme does not load GA4 itself, so nothing is counted twice.

## GA4 settings (checked, not changed)

| Setting | Now | Recommended |
|---|---|---|
| Event data retention | 14 months (changed 27 Nov 2026) | Done |
| Internal traffic filter | Exists, but in **Testing** | Set it to **Active** once the office IP is defined in the data stream |
| Search Console link | None | Link it after Search Console is verified |

## Added before launch (now in the CCS SEO plugin)

- **AI search crawlers in robots.txt:** OAI-SearchBot, Claude-SearchBot and PerplexityBot are named and allowed, so AI answers can cite the site and link to it. The training bots (GPTBot, ClaudeBot, CCBot, Google-Extended) are left alone because blocking them is a business decision. To block any of them, tick them under SEO → Settings → AI crawlers.
- **IndexNow:** when a page or article is published, updated or taken down, Bing, Yandex, Seznam, Naver and Yep are told straight away. Google doesn't take part. It runs only on the live domain, never locally, on staging or when an SEO plugin is on. The key file is served at `/{key}.txt`.
- **Not added:** llms.txt. Google says it doesn't use it, and Lighthouse lists it only as an optional, experimental check.

## Launch day (your jobs)

0. Upload and activate the **CCS SEO** plugin alongside the theme (without it: no descriptions, schema or old-site redirects).
1. In the Customizer, enter **GTM-TGWL555X**. Publish the GTM container first.
2. Send one test enquiry, then mark **generate_lead** as a key event in GA4.
3. Verify Search Console as a Domain property (a DNS TXT record), submit `/wp-sitemap.xml`, then link it to GA4.
4. Add the site to Bing Webmaster Tools (it can import from Search Console). After the first page update, check its IndexNow report to confirm pings are arriving.
5. Event data retention is set to 14 months (done 27 Nov 2026). From the office: search "what is my IP", add that address under the data stream's internal traffic rules, then set the Internal Traffic filter to Active. Check whether the office broadband keeps a fixed IP address.
6. Server: turn WP_DEBUG off, force HTTPS, and check HSTS.
7. Run Lighthouse on the live homepage, an area page and the contact page. Do a quick VoiceOver pass.
8. **Care guides** launches as "coming soon". The page shows a short "being written" note with links to FAQs, Costs and funding and the care finder, and is hidden from Google. When the guides are approved, untick "Coming soon" on the page (Pages → Care Guides).
9. The site now uses "Continuity of Care Services" everywhere (25 Nov 2026). Make the Google Business Profile, social profiles and directory listings match. Web and email addresses stay as they are.
