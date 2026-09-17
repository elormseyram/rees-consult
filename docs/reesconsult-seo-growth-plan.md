# Rees Consult, SEO & First-100-Visitors Growth Plan
*Prepared: August 2026 | reesconsult.com*

---

## Executive Summary

Rees Consult is a well-built Laravel site with real credibility (Global Trade & Business Award, British Council affiliate, strong testimonials, GTV appearance) but almost zero organic discoverability. The site has multiple indexed pages, one blog post, a broken sitemap, no schema markup, and thin page-level SEO. The good news: the keyword opportunity is massive and under-served in the Ghana/West Africa market. Fixing the technical foundation + publishing 6–8 targeted blog posts could realistically deliver 100+ monthly organic visitors within 90 days.

---

## 1. Critical Technical Issues (Fix First)

### BROKEN SITEMAP, Priority 1

**Problem:** `/sitemap.xml` is rendering raw Laravel Blade template syntax, `{{ url('/') }}`, `{{ date('Y-m-d') }}`, instead of compiled URLs. Google is receiving an invalid XML file and may not be discovering all pages.

**Fix:** In your Laravel route or controller that serves the sitemap, ensure the view is compiled and returned as `Content-Type: application/xml`. Use `spatie/laravel-sitemap` package or verify your sitemap route is not bypassing Blade rendering.

```php
// In routes/web.php
Route::get('/sitemap.xml', function () {
    $content = view('sitemap')->render();
    return response($content, 200)->header('Content-Type', 'application/xml');
});
```

**Also add individual service URLs to the sitemap:**
- `/services/ielts-preparation`
- `/services/toefl-preparation`
- `/services/gre-intensive`
- `/services/sat-college-prep`
- `/services/oet-medical-prep`
- `/services/gmat-preparation`
- `/services/tesol-certification`
- `/services/nclex-review`
- Each `/blog/{slug}` post

### robots.txt, OK
Current: `User-agent: * / Disallow:`, crawling is fully open. Good.

### Submit to Google Search Console
If not done: verify ownership, submit the fixed sitemap, and request indexing for all key pages manually.

---

## 2. On-Page SEO Gaps

### Homepage

| Element | Current State | What It Should Be |
|---|---|---|
| Title tag | "Rees Consult - Standardised Tests Preparation And Job & Study Abroad" | `IELTS Preparation & Study Abroad Consultancy in Ghana, Rees Consult` |
| Meta description | Not set | `Ghana's award-winning IELTS, GRE & SAT prep centre. Expert study abroad & job placement consultancy in Accra. British Council affiliate. Book a free session.` |
| H1 | "Travel Abroad With Ease" | Too vague, no keyword. Change to: `Expert IELTS Preparation & Study Abroad Consultancy in Ghana` |
| Image alt text | Filename-only (e.g. `rees-core-team.jpg`) | Add descriptive alt: `Rees Consult team in Accra, Ghana` |

### Services Page
Each service has a dedicated detail page (e.g. `/services/ielts-preparation`). Each needs:
- Unique title tag with keyword (e.g. `IELTS Preparation Course in Ghana, Rees Consult`)
- 300–500 words of unique body content per page (not just the enrollment form)
- Internal links from the homepage and blog posts

### Blog
Only **1 post** published (Dec 2025). This is your biggest organic traffic lever. See Section 5 for the content plan.

---

## 3. Missing Schema Markup

None detected on any page. Add these immediately, they cost nothing and can win rich results in Google.

### LocalBusiness / EducationalOrganization Schema (add to every page head)

```json
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "Rees Consult",
  "url": "https://reesconsult.com",
  "logo": "https://reesconsult.com/reesconsult-logo.png",
  "description": "Ghana's award-winning IELTS, GRE, SAT, and TOEFL preparation centre and study/work abroad consultancy.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "UQ79, Nii Kwaofio St",
    "addressLocality": "Accra",
    "addressCountry": "GH"
  },
  "telephone": "+233256212634",
  "email": "info@reesconsult.com",
  "sameAs": [
    "https://www.instagram.com/reesconsult1/",
    "https://gh.linkedin.com/company/rees-consult"
  ]
}
```

### Additional Schema to Add
- **Review schema** on the Testimonials page, can generate star ratings in Google results
- **Course schema** on each service detail page, can surface in Google's course carousels

---

## 4. High-Intent Keywords to Target

Focus on long-tail, Ghana-specific keywords. Lower competition, highly buyable intent.

### Tier 1, Core (Target Now)

| Keyword | Intent | Page to Target |
|---|---|---|
| IELTS preparation Ghana | Transactional | `/services/ielts-preparation` |
| IELTS coaching Accra | Transactional | `/services/ielts-preparation` |
| study abroad consultancy Ghana | Transactional | `/services` |
| work abroad from Ghana | Transactional | `/services` (Job Placements section) |
| GRE prep Ghana | Transactional | `/services/gre-intensive` |
| OET preparation Ghana nurses | Transactional | `/services/oet-medical-prep` |
| NCLEX review Ghana | Transactional | `/services/nclex-review` |

### Tier 2, Content (Blog)

| Keyword | Blog Post Title |
|---|---|
| how to study abroad from Ghana | Already published, needs optimization |
| IELTS band score requirements UK visa | "What IELTS Score Do You Need for a UK Visa in 2026?" |
| how to get a job in Canada from Ghana | "How to Get a Sponsored Job in Canada from Ghana (2026 Guide)" |
| scholarship for Ghanaian students | "10 Fully Funded Scholarships Open to Ghanaian Students Right Now" |
| IELTS academic vs general training | "IELTS Academic vs General Training: Which One Do You Need?" |
| how to apply to UK universities from Ghana | "A Ghanaian Student's Guide to Applying to UK Universities" |

### Tier 3, Brand & Local

| Keyword | Status |
|---|---|
| Rees Consult | Likely ranking, protect with GMB |
| IELTS centre East Legon | Not targeted yet, add to homepage copy |
| study abroad agency Accra | Good low-competition opportunity |

---

## 5. Content Plan, 8 Posts in 60 Days

Publish 2 posts per week for 4 weeks. Each post targets one Tier 2 keyword and links internally to the relevant service page.

| Week | Post Title | Target Keyword | Links To |
|---|---|---|---|
| 1 | "IELTS Academic vs General Training: Which One Do You Need?" | ielts academic vs general | `/services/ielts-preparation` |
| 1 | "What IELTS Band Score Do You Need for a UK Visa in 2026?" | IELTS band score UK visa | `/services/ielts-preparation` |
| 2 | "How to Get a Sponsored Job in Canada from Ghana (2026 Guide)" | job in Canada from Ghana | `/services` Job Placements |
| 2 | "10 Fully Funded Scholarships Open to Ghanaian Students Right Now" | scholarships Ghanaian students | `/scholarships` |
| 3 | "A Ghanaian Student's Guide to Applying to UK Universities" | apply to UK universities Ghana | `/services` School Applications |
| 3 | "NCLEX for Ghanaian Nurses: Everything You Need to Know" | NCLEX Ghana nurses | `/services/nclex-review` |
| 4 | "How Long Does It Take to Prepare for IELTS? (Honest Answer)" | how long IELTS preparation | `/services/ielts-preparation` |
| 4 | "OET vs IELTS for Healthcare Workers: Which Test Is Right for You?" | OET vs IELTS healthcare | `/services/oet-medical-prep` |

Each post: 800–1,200 words, one CTA to book a consultation, links to 2–3 internal pages.

---

## 6. Internal Linking Rules to Implement

1. Every blog post links to at least one service page with keyword-rich anchor text (e.g. "our IELTS preparation course in Accra").
2. The homepage links to each individual service detail page, not just `/services`.
3. The Scholarships page links to the Study Abroad service.
4. Add a "Related Posts" section to every blog post.
5. Each service page links to 1–2 relevant blog posts.

---

## 7. Google Business Profile (Fastest Local Win)

If Rees Consult doesn't have a verified Google Business Profile, this is your single highest-ROI action this week.

1. Go to business.google.com and claim/create the listing at UQ79, Nii Kwaofio St, Accra.
2. Primary category: **Educational Consultant**. Secondary: **Test Preparation Center**.
3. Add all services listed on the website.
4. Upload 10+ photos, team, office, award ceremony, student sessions.
5. Set operating hours and add the website URL.
6. Ask every existing client to leave a Google review, even 10 reviews puts you ahead of most local competitors.

With a verified GMB, searches like "IELTS coaching Accra" and "study abroad consultant near me" can surface you in the local 3-pack, the map results that appear above organic listings.

---

## 8. Referral & Off-Page Channels

| Source | Action |
|---|---|
| LinkedIn (44 followers) | Post 2x/week: success stories, scholarship alerts, IELTS tips. Link each post to a blog post or service page. |
| Instagram (@reesconsult1) | Already active, rotate blog post links in bio. Add link stickers in Stories. |
| YouTube | Add cards and end screens to all existing videos linking to reesconsult.com |
| Reddit / Quora | Answer "How do I study abroad from Ghana?" and "Best IELTS prep in Accra", link back naturally |
| Ghanaian university Facebook groups | Share scholarship posts and study abroad guides |
| British Council | Ask to be listed on their affiliate/partner directory, a backlink from britishcouncil.org.gh is extremely valuable |

### Backlink Opportunities
- Get listed on IDP Ghana and NAFSA member directories (you're already a partner, claim those directory entries)
- Submit to Ghana education directories (GhanaWeb Education, Ghana Yellow Pages)
- Reach out to Ghana-based education bloggers and YouTubers for features
- Send a press release to Joy FM / GTV about the Global Trade Award, turn that GTV appearance into a web mention with a link

---

## 9. Realistic 90-Day Traffic Projection

| Month | Primary Actions | Expected Monthly Visitors |
|---|---|---|
| Month 1 | Fix sitemap, submit to GSC, set up GMB, optimize title tags + H1, publish 4 blog posts | 20–40 |
| Month 2 | Publish 4 more posts, add schema markup, build 5+ backlinks, GMB reviews begin | 50–80 |
| Month 3 | Blog posts gaining traction in SERPs, GMB local rankings improve, referral traffic from social | 100–150 |

Low competition in the Ghana IELTS/study abroad niche makes this achievable. If the sitemap fix resolves indexing gaps, the jump in Month 1–2 could be sharper.

---

## 10. Quick Wins Checklist

- [ ] Fix the sitemap.xml Blade rendering bug
- [ ] Submit fixed sitemap in Google Search Console
- [ ] Update homepage H1 to include "IELTS Preparation" and "Ghana"
- [ ] Write/confirm meta titles and descriptions for all 7 nav pages
- [ ] Add LocalBusiness + EducationalOrganization schema to site-wide head
- [ ] Claim and fully complete Google Business Profile
- [ ] Ask 10 past clients to leave a Google review this week
- [ ] Publish first 2 blog posts from the content plan above
- [ ] Add internal links from existing blog post to IELTS and Study Abroad service pages
- [ ] Add descriptive alt text to all images site-wide
- [ ] Update LinkedIn and Instagram bios to link directly to reesconsult.com
