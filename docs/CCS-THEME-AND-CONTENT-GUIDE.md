# CCS Theme & Content Guide

**One reference for theme status, content to add, and implementation.**

Theme status, content to add, WordPress setup, and implementation checklist. Everything you need is in this document.

**Tagline (use everywhere):** **Your Team, Your Time, Your Life**

**Use CTA files; tailor content to CCS:** Migrated pages and sections use the same files/structure as CTA, but content is CCS-only. Examples: **Resources** = **Home care guides** (care guides, FAQs, referral info); **CQC Hub** page = **CQC and our care** (reassurance, not training hub); **Group Training** page slot = **Book a care consultation** / **Arrange a visit** or **Getting started with care**. No course catalogues, training bookings, or employer training copy.

---

## 1. Current status

### Done in theme code

- **functions.php** – Phone `01622 689 047`, email `office@continuitycareservices.co.uk`, social links (Facebook Messenger, Instagram, LinkedIn, Threads), CQC link constant.
- **template-homepage.php** – Hero with “Your Team, Your Time, Your Life”, CTAs (Explore Our Services / Explore Career Paths), Why Choose Us, CQC section, services intro, Claire Pitchford testimonial, partnerships, info cards (About, FAQs, Careers).
- **footer.php** – Company description, CQC registration number and rating badge.
- **Theme** – Structure, design system, WCAG 2.1 AA accessibility, real contact info, homepage and footer content.

### Still to do in WordPress admin

- Create 3 care service posts and all listed pages.
- Set menus, Reading/General settings, widgets.
- Install form plugin, add consultation form, upload images, optional plugins (SEO, cache, security, backups).

---

## 2. Quick reference

**Live site (canonical):** **https://www.continuitycareservices.co.uk**

### Contact

| Item | Value |
|------|--------|
| **Phone** | 01622 689 047 |
| **Email** | office@continuitycareservices.co.uk |
| **Address** | The Maidstone Studios, New Cut Road, Maidstone, Kent, ME14 5NZ |
| **CQC** | 1-2624556588 (Rated: Good) |

### Social

- **Facebook Messenger:** https://m.me/821174384562849  
- **Instagram:** http://instagram.com/continuityofcareservices/  
- **LinkedIn:** http://linkedin.com/company/continuitycareservices  
- **Threads:** https://www.threads.com/@continuityofcareservices  

### Messaging (use consistently)

- **“Your Team, Your Time, Your Life”** ← main tagline
- “It’s not just *what* we do, it’s *how* we do it”
- “Home care Maidstone families trust”
- “Compassionately supporting children and adults”
- “Trusted Home Care Services in Maidstone & Kent”
- “Day or night, 24/7”
- “Friendly, familiar, and person-centred”
- “We’re here to make life feel a little lighter”

### Brand (in theme CSS)

| Role | Value |
|------|--------|
| Primary | `#564298` (deep purple) |
| Secondary | `#a8ddd4` (light teal) |
| Accent | `#9b8fb5` (muted purple) |
| Background | `#f6f5ef` (cream) |
| Text | `#2e2e2e` (charcoal) |

**Fonts:** Poppins (headings, 300–800), Open Sans (body, 300–700).

---

## 2b. Homepage & key copy

Use this exact copy on the homepage template. **Tagline = Your Team, Your Time, Your Life.**

### Hero
- **H1:** “Home Care in Maidstone & Kent — **Your Team, Your Time, Your Life**”
- **H2:** “Trusted Home Care Services in Maidstone & Kent”
- **Description:** “Compassionately supporting children and adults with domiciliary, disability, respite, complex, and palliative care, day or night, 24/7.”
- **CTAs:** “Explore Our Services” | “Explore Career Paths”

### Why Choose Us
- **Heading:** “Why Choose Us for Home Care in Maidstone?”
- **Subheading:** “It’s not just *what* we do, it’s *how* we do it.”
- **Body:** “Reliably supporting adults and children across Maidstone and Kent, we’re here to provide personalised care, day or night, tailored to you. **Our caring, local team is dedicated to supporting families across Kent.** We don’t rush or rotate staff every other week. Instead, we take the time to get to know each person, not just their care plan. Our staff commit to discovering the quirks of every client, from how they like their toast to what puts them at ease on a tough day. We believe that the best care doesn’t stop when the to-do list is ticked; it continues through our staff showing up in a way that feels friendly, familiar, and person-centred. **Learn more about the home care services we offer in Maidstone & Kent.**”

### CQC section
- **Heading:** “Regulated, rated, and reliable home care across Maidstone & Kent”
- **Subheading:** “Proud to be rated ‘Good’ by the CQC”

### Care options (intro above service cards)
- **Small heading:** “Beginning your home care journey”
- **Main heading:** “Explore Your Care Options”
- **Body:** “Whether you need a little help dressing in the mornings, round-the-clock complex care, or just someone to pop in for a cuppa and a catch-up, we’re here to make life feel a little lighter. For expert home care Maidstone families trust, get in touch today, and we’ll create a plan tailored to your needs.”

### Information cards (three cards)
1. **Our Care Approach** (icon: hand holding heart) — Subheading: “About Us”. “Compassionate care, tailored to you. We’re dedicated to supporting your independence, dignity, and wellbeing, by delivering trusted care services with a personal touch. Discover how our team makes a difference every day.” **CTA:** Learn More → `/home/about-home-care-maidstone/`
2. **Home Care FAQs** (icon: info circle) — Subheading: “FAQs”. “Have questions about our home care in Maidstone & Kent? Find answers about our services, care plans, and what to expect. If you’re finding the care search overwhelming, or need more information, our team is just a call away.” **CTA:** Get Answers → `/home/home-care-services-kent/`
3. **Care Careers in Kent** (icon: users/team) — Subheading: “Careers”. “Make a real impact by joining our team. Offering rewarding roles, flexible hours, and ongoing training, we’d love to hear from you. If you’re passionate about helping others, explore how you can grow your career with us.” **CTA:** Explore Roles → `/home/care-careers-maidstone-kent/`

### Partnerships section
- **Heading:** “Our Local & National Partnerships”
- **Subheading:** “We’re proud to collaborate with local, regional, and national organisations to enhance care and opportunities for our clients and staff.”
- **Partner logos (11):** National Care Association; Disability Confident Committed; CV Minder; MidKent College; Kent Integrated Care Alliance (KiCA); Care Quality Commission (CQC); iTrust; NHS; Homecare Association; Brain Injury Group; Continuity Training Academy.

### Consultation form (Contact page)
- **Heading:** “Book Your Free Care Consultation”
- **Description:** “Please let us know what you’d like to discuss below, whether you’re seeking home care in Maidstone or across Kent, and we’ll get in touch to arrange a call or visit. We can’t promise we’ll be able to make your exact date, but we’ll use this as a rough guideline on when to get in touch!”
- **Submit button:** “Send Request”

---

## 2c. Tone, SEO & strategy

**Tone:** Warm & compassionate (not clinical); personal & approachable (“from how they like their toast”); professional yet friendly; reassuring; honest (“We can’t promise we’ll be able to make your exact date”).

**SEO – primary keywords:** Home Care Maidstone; Home Care Kent; Domiciliary Care Maidstone; Complex Care Kent; Respite Care Maidstone; Care Services Kent.  
**Long-tail:** “expert home care Maidstone families trust”; “home care services in Maidstone & Kent”; “care careers Maidstone Kent”; “about home care Maidstone”.

**Page title (home):** “Home Care Maidstone | Personalised Home Care Service in Kent”

**What makes CCS different:** Consistency (we don’t rotate staff); personal touch (staff learn quirks & preferences); comprehensive (children and adults); 24/7; local (Maidstone & Kent); person-centred (care beyond the to-do list); CQC Good.

**Target audiences:** (1) Families seeking home care in Maidstone/Kent, (2) Individuals seeking care for themselves, (3) Professionals making referrals, (4) Care workers seeking jobs.

**Conversion points:** Free consultation form; service exploration; career paths; phone/email in header; social links.

**Image alt text:** Descriptive, specific, include context (e.g. “at Lainey’s Care Farm”), focus on people and activities.

**Social ARIA labels:** “Message Continuity’s Facebook”; “Find Continuity on Instagram”; “Find Continuity on Linkedin”; “Find Continuity on Threads”.

---

## 3. Service posts (Custom Post Type: care_service)

Create three posts in WordPress; use the content below. Set the suggested **Featured Image** for each.

---

### 3.1 Domiciliary Care

**Title:** Domiciliary Care  
**Featured Image:** Client enjoying time outdoors with walking frame in garden

**Excerpt:**  
Getting dressed. Making breakfast. Remembering the right meds at the right time. Our carers provide gentle assistance with everyday tasks, ensuring care calls are always scheduled to fit your daily routine.

**Full content:**

At Continuity Care Services, we understand that sometimes you just need a helping hand with daily tasks. Our domiciliary care services provide compassionate support in the comfort of your own home.

**What We Help With:**
- Personal care (washing, dressing, grooming)
- Medication reminders and administration
- Meal preparation and assistance with eating
- Light housekeeping and laundry
- Shopping and errands
- Companionship and social interaction

**Your Schedule, Your Way**  
We don’t believe in rigid routines that don’t fit your life. Whether you need a morning visit to help you get ready for the day, or evening support to help you wind down, we’ll create a care plan that works for you.

**Consistency You Can Count On**  
We don’t rotate staff every week. You’ll work with the same familiar faces who take the time to learn how you like your tea, what makes you laugh, and what puts you at ease.

---

### 3.2 Respite Care

**Title:** Respite Care  
**Featured Image:** Client with glasses and ear defenders playing pink ukulele during music session

**Excerpt:**  
Whether it’s for a few hours or a few days, our team are here to step in and provide a client’s family and friends with gentle, reliable respite support. Take some time to rest, you can’t pour from an empty cup.

**Full content:**

Caring for a loved one is rewarding, but it’s also exhausting. Everyone needs a break. Our respite care services give family carers the time they need to rest, recharge, or simply take care of themselves.

**Flexible Respite Options:**
- Short breaks (a few hours to a day)
- Extended respite (overnight or multiple days)
- Regular scheduled breaks (weekly, fortnightly)
- Emergency respite care
- Holiday cover for regular carers

**Seamless Continuity of Care**  
We take time to understand your loved one’s routines, preferences, and needs so the transition is smooth. Your family member will receive the same high standard of care they’re used to.

**Give Yourself Permission to Rest**  
There’s no guilt in needing a break. Whether you need to attend an appointment, catch up on sleep, visit friends, or just have some time to yourself, we’re here to make that possible.

**What Our Respite Care Includes:**
- All aspects of personal care
- Medication administration
- Meal preparation
- Engaging activities and companionship
- Continuation of care routines
- Regular updates to family members

---

### 3.3 Complex Care

**Title:** Complex Care  
**Featured Image:** Two smiling women in wheelchairs enjoying time outdoors

**Excerpt:**  
From epilepsy care to PEG and mobility support, we provide complex care in the comfort of your own home. We work closely with families, nurses and healthcare teams to ensure we get it right, every time.

**Full content:**

Complex care requires specialist knowledge, clinical skills, and unwavering attention to detail. At Continuity Care Services, our trained care team provides expert support for individuals with complex health needs.

**Conditions We Support:**
- Epilepsy and seizure management
- Spinal injuries and paralysis
- Tracheostomy care
- PEG feeding and nutrition support
- Ventilation support
- Cerebral palsy
- Brain injuries
- Multiple sclerosis
- Motor neurone disease
- Acquired brain injury

**Clinical Care at Home**  
Our carers receive specialist training in clinical procedures and work closely with healthcare professionals to deliver:
- Medication management and administration
- Clinical observations and monitoring
- Moving and handling with specialist equipment
- Personal care with dignity
- Catheter and stoma care
- Wound care
- Night sits and waking night care

**Working as Part of Your Team**  
We don’t work in isolation. We collaborate with occupational therapists, physiotherapists, speech and language therapists, community nurses, GPs and consultants, social workers, and family members.

**24/7 Support When You Need It**  
Complex care needs don’t follow a 9–5 schedule. Our team is available day and night to provide the consistent, skilled care required.

**Person-Centred, Not Just Medical**  
Yes, we’re experts in clinical care. But we’re also committed to ensuring our clients live full, happy lives. We support hobbies, outings, social connections, and everything that makes life worth living.

---

## 4. Pages to create

| Page | Suggested URL | Content source |
|------|----------------|----------------|
| **About Home Care Maidstone** | `/home/about-home-care-maidstone/` | Expand “Why Choose Us” body (Section 2b) into full About page |
| **Home Care Services Kent** | `/home/home-care-services-kent/` | Overview of all services + links to each service post |
| **Who You’ll Meet** | `/home/who-youll-meet/` | Keelie Varney & Nikki Mackay bios, team info, values & training |
| **Care Careers Maidstone Kent** | `/home/care-careers-maidstone-kent/` | Intro and benefits listed in this section below |
| **Contact Us** | `/home/contact-us/` | Consultation form: heading, description and all fields in Section 10 |
| **Resources** (parent) | `/home/resources/` | Use CTA resources/downloads structure; label as **Home care guides** or **Care guides**. Parent for children below. |
| **Care Guides** | `/home/resources/care-guides/` | Home care guides (educational PDFs, how-to, info for families) |
| **FAQs** | `/home/resources/faqs/` | Frequently asked questions |
| **Referral Information** | `/home/resources/referral-information/` | For professionals making referrals |
| **News & Updates** | e.g. `/home/news-and-updates/` | Assign as Posts page in Reading |

**Careers page intro:**  
“Make a real impact by joining our team. Offering rewarding roles, flexible hours, and ongoing training, we’d love to hear from you. If you’re passionate about helping others, explore how you can grow your career with us.”

**Careers benefits:** Flexible working hours; competitive pay rates; ongoing training and development; supportive team environment; make a real difference in people’s lives.

---

## 5. Testimonial

**Claire Pitchford**  
“I am delighted to express my gratitude for the outstanding care CCS delivered to my paraplegic father, requiring complex care. The skilled and compassionate team went above and beyond, addressing his unique needs with unwavering dedication. Their expertise and empathy transformed what could have been a challenging situation into a positive and reassuring experience for him and his family.”

---

## 6. Images needed

Use descriptive, specific alt text; include context (e.g. “at Lainey’s Care Farm”); focus on people and activities.

1. **Hero** – Home care worker with clients (responsive: desktop/tablet/mobile)
2. **Why Choose Us** – Client at Lainey’s Care Farm (e.g. “Young woman in outdoor gear standing in front of a farm enclosure”)
3. **CQC section** – Caregiver supporting patient, holding hands; compassionate care scene
4. **Domiciliary card** – Client enjoying time outdoors with walking frame in garden
5. **Respite card** – Client with glasses and ear defenders playing pink ukulele during music session
6. **Complex care card** – Two smiling women in wheelchairs enjoying time outdoors
7. **Testimonial** – Autumn leaves painted and glued onto newspaper during craft activity
8. **About** – Team photos, office location  

Plus: CQC badge/widget; partner logos — National Care Association, Disability Confident Committed, CV Minder, MidKent College, Kent Integrated Care Alliance (KiCA), Care Quality Commission (CQC), iTrust, NHS, Homecare Association, Brain Injury Group, Continuity Training Academy. Set featured images on the three service posts.

---

## 7. Menus

### Primary (header)

```
Home
About Us          → /home/about-home-care-maidstone/
Our Services      → /home/home-care-services-kent/
Who You'll Meet   → /home/who-youll-meet/
Careers           → /home/care-careers-maidstone-kent/
Resources         (dropdown)
  ↳ Care Guides   → /home/resources/care-guides/
  ↳ FAQs          → /home/resources/faqs/
  ↳ Referral Information → /home/resources/referral-information/
News & Updates    → /home/news-and-updates/
Contact Us        → /home/contact-us/
```

### Footer

About Us | Services | Careers | FAQs | Contact Us | Privacy Policy | Terms & Conditions | Accessibility Statement

---

## 8. WordPress settings

**General:**  
Site Title: **Continuity Care Services**  
Tagline: **Home Care in Maidstone & Kent - Your Team, Your Time, Your Life**  
Email: **office@continuitycareservices.co.uk**

**Reading:**  
- Homepage displays: **A static page**  
- Homepage: select the **Homepage** page (uses template-homepage.php)  
- Posts page: **News & Updates**

**Permalinks:** Post name `/%postname%/`

---

## 9. Widgets

**Sidebar (if used):** Contact info, Recent posts, Search.  
**Footer:** About + social links; contact info; quick links menu.

---

## 10. Forms & plugins

**Consultation form (Contact Form 7 or WPForms)**

- **Heading:** “Book Your Free Care Consultation”
- **Description (place above form):** “Please let us know what you’d like to discuss below, whether you’re seeking home care in Maidstone or across Kent, and we’ll get in touch to arrange a call or visit. We can’t promise we’ll be able to make your exact date, but we’ll use this as a rough guideline on when to get in touch!”

| Field | Type | Required | Notes |
|-------|------|----------|--------|
| Your Name | text | Yes | |
| Your Phone Number | tel | Yes | |
| Your Email | email | Yes | |
| Select Service | multi-select | No | Options: Domiciliary Care, Respite Care, Complex Care, I'm Not Sure |
| With Whom? | select | No | Options: Anyone, Keelie Varney, Nikki Mackay |
| Preferred Date | date | No | Date picker |
| Preferred Time | time | No | Time picker |
| Any Information You'd Like Us To Know? | textarea | No | |
| Consent to contact | checkbox | Yes | “I consent to Continuity of Care Services storing my details so they can respond to this enquiry.” |
| Subscribe to updates | checkbox | No, default on | “I agree to Continuity of Care Services storing my details so they can send me newsletters and updates.” |
| reCAPTCHA | — | Yes | |
| **Submit** | button | — | Label: “Send Request” |

**Optional:** Yoast SEO or Rank Math; caching (e.g. WP Rocket, W3 Total Cache); Wordfence; UpdraftPlus; Google Analytics.

---

## 11. Implementation checklist

Use this order when setting up the site.

| # | Task | Status |
|---|------|--------|
| 1 | Update functions.php with contact details | ✅ |
| 2 | Update template-homepage.php with real content | ✅ |
| 3 | Update footer.php with CQC info | ✅ |
| 4 | Create 3 care service posts (Domiciliary, Respite, Complex) | ⏳ |
| 5 | Create pages (About, Services, Who You’ll Meet, Careers, Contact, Resources + children) | ⏳ |
| 6 | Assign Homepage as front page, News & Updates as posts page | ⏳ |
| 7 | Create Primary and Footer menus | ⏳ |
| 8 | Upload images; set service featured images | ⏳ |
| 9 | Install form plugin; create consultation form | ⏳ |
| 10 | Configure General & Reading settings | ⏳ |
| 11 | Upload logo; configure Customizer/widgets | ⏳ |
| 12 | Test links, forms, mobile, accessibility | ⏳ |
| 13 | Optional: SEO plugin, analytics, cache, security, backups | ⏳ |

---

## 12. Theme installation

1. Zip theme (exclude docs):  
   `zip -r ccs-theme.zip *.php inc/ assets/ style.css screenshot.png page-templates/ template-parts/ -x "*.md"`
2. **WordPress:** Appearance → Themes → Add New → Upload Theme → Activate.
3. Work through **Section 11** in WordPress admin.
4. Test: navigation, forms, mobile, screen reader, speed.

---

## 13. Design & tone

- **Care path:** Warm, reassuring; **“Your Team, Your Time, Your Life”**; CTAs: “Book a free consultation”, “Speak to our team in Maidstone”; value props: local expertise, CQC Good, personalised care, family-run.
- **Career path:** Belonging, purpose; CTAs: “View current openings”, “Join our Maidstone team”; value props: close-knit team, training, work locally, supportive environment.
- **Page layout order:** Hero → trust (CQC/partners) → 4-column value props → services/roles → testimonials → about → CTA banner → footer.
- **Care hero (optional alternate):** Headline “It’s good to be at home”; subheading “Exceptional, heartfelt care in Maidstone and surrounding areas. Your team, your time, your life.”; CTAs: Call 01622 689 047, Book Free Consultation.
- **Career hero (optional alternate):** Headline “Careers in care where you belong”; subheading “Join our Maidstone team and make a real difference in your local community.”; CTAs: View Open Positions, Call 01622 689 047.
