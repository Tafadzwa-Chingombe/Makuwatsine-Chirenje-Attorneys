# Getting Makuwatsine Chirenje Attorneys found on Google and AI search

**Honest summary:** the website is now technically ready to be found. But *being listed* and *ranking high* are two different things. Google must first discover the site (days), and ranking then depends on content, trust and time (usually 2–6 months for a new firm website). Nobody can honestly promise a position. What this guide gives you is everything within reach, in the right order.

---

## Part 1 — What I built into the website

| What | Where | Why it helps |
|---|---|---|
| One **main address** (canonical): `https://makuwatsinechirenjeattorneys.co.zw` | in every page's `<head>` | Stops Google splitting your ranking between two addresses |
| Keyword-aware **page titles and descriptions** | each page | What people see in Google results. Includes Harare, Zimbabwe, Eastlea and your services |
| **Sitemap** `/sitemap.xml` | updates itself when you publish a blog article | Tells Google every page that exists |
| **robots.txt** `/robots.txt` | automatic | Lets Google and AI crawlers (GPTBot, ClaudeBot, PerplexityBot, Google-Extended…) read the site; keeps `/admin/` and `/install/` private |
| **Structured data** (a machine-readable "business card") | every page | Tells Google and AI who you are: a law firm in Harare, address, phone, email, founders, team, practice areas. Also breadcrumbs, FAQ and article data |
| **FAQ sections** with matching data | Home and Practice Areas | Answers the questions people type into Google and ask AI assistants. Every answer is taken from facts already on your site |
| **`/llms.txt`** | automatic | A short plain-language summary of the firm written for AI assistants (ChatGPT, Claude, Perplexity, Google AI) |
| **Social sharing image + cards** | `assets/img/og-image.jpg` | A proper picture when the site is shared on WhatsApp, Facebook or LinkedIn |
| Faster pages | `.htaccess` compression and caching | Speed is a ranking factor |
| **Demo site hidden from Google** | GitHub demo | So the demo never competes with your real site |

Everything updates by itself: add a practice area or a team member and the structured data, sitemap and `llms.txt` change with it.

---

## Part 2 — What YOU need to do (in order)

### Step 1 — Make `makuwatsinechirenjeattorneys.co.zw` the main address (most important)
Your pages now tell Google that this domain is the main one. So it **must** work:
1. Add it in cPanel → **Domains → Create A New Domain** (document root `public_html`).
2. Point its DNS or nameservers to your hosting (server IP `156.38.135.148`).
3. cPanel → **SSL/TLS Status → Run AutoSSL**, so it shows the padlock (`https://`).
4. Open `.htaccess` (File Manager → show hidden files → Edit). Remove the `# ` from the 3 lines under **"ONE main web address"**, and from the 2 lines under **"Force HTTPS"**. Save.
   This sends `mcattorneys.co.zw` and `www.` visitors to the main address, which protects your ranking.
5. Test: type `http://mcattorneys.co.zw` — you should land on `https://makuwatsinechirenjeattorneys.co.zw`.

> **Do not skip step 1.** Until the main domain works, Google is told one address and shown another. Keep the old address working in the meantime. If you'd rather keep `mcattorneys.co.zw` as your main address, tell me and I'll switch it in one line.

### Step 2 — Check the three files open
In your browser, open each, replacing the address with yours:
- `https://makuwatsinechirenjeattorneys.co.zw/sitemap.xml` shows a list of pages
- `https://makuwatsinechirenjeattorneys.co.zw/robots.txt` shows text starting "# Makuwatsine Chirenje Attorneys…"
- `https://makuwatsinechirenjeattorneys.co.zw/llms.txt` shows the firm summary

### Step 3 — Google Search Console (free; this is how you get listed)
1. Go to **search.google.com/search-console** and sign in with a Google account the firm will keep.
2. Click **Add property → Domain**, type `makuwatsinechirenjeattorneys.co.zw`.
3. Google gives you a **TXT record**. Add it where the domain's DNS is managed (cPanel → **Zone Editor**, or your registrar). Click **Verify** (it can take a few minutes).
4. Left menu → **Sitemaps** → type `sitemap.xml` → **Submit**.
5. Left menu → **URL Inspection** → paste your home page address → **Request indexing**. Repeat for About, Practice Areas, Team and Contact.
6. Check back in a few days: **Pages** shows what Google has indexed.

### Step 4 — Bing (it also feeds ChatGPT search and others)
Go to **bing.com/webmasters**, sign in, and **Import from Google Search Console**. Then submit the same sitemap.

### Step 5 — Google Business Profile (the biggest win for "lawyers in Harare")
You said a profile exists. Make sure it is claimed and verified, then:
- **Website:** `https://makuwatsinechirenjeattorneys.co.zw`
- **Category:** *Law firm* (add "Attorney", "Notary public", "Conveyancer" as extra categories if offered)
- **Name, address and phone must match the website exactly:** Makuwatsine Chirenje Attorneys, 6 Londonderry Avenue, Eastlea, Harare, **+263 24 2720094**
- Add opening hours, the logo, photos of the office and team, and a short description using your services.
- Ask satisfied clients for a Google review. Reviews strongly influence local ranking.
- Then paste the profile link and your hours into `includes/seo-settings.php` (see Part 3).

### Step 6 — Replace the sample blog articles
The 7 sample articles installed with the CMS say things like "this is sample content for the design preview". **Search engines penalise placeholder content, and it makes the firm look unfinished.** Before you announce the site:
- Open **/admin/** and delete the seven samples (or edit them into real articles), then publish real ones.

### Step 7 — Publish real content regularly (this is what builds rankings)
Aim for **2–4 articles a month**, 500–1,000 words, each answering one question a client really asks. Ideas that match your services:
- "How much does it cost to transfer property in Zimbabwe, and how long does it take?"
- "What happens to a deceased estate in Zimbabwe? A step-by-step guide"
- "Unfair dismissal in Zimbabwe: employer and employee rights"
- "How to register a mortgage bond in Zimbabwe"
- "Mining claims in Zimbabwe: what investors must check"
- "What to do if you are arrested in Zimbabwe"
Use the **excerpt** box for a clear 1–2 sentence answer (it becomes the search description), add a cover picture, and link to the relevant practice area.

### Step 8 — Get listed elsewhere (trust signals)
Make your name, address and phone **identical** everywhere: Law Society of Zimbabwe directory, LinkedIn company page, Facebook page, local business directories, legal directories. Each link back to your site helps. Add the pages' links to `includes/seo-settings.php` as well.

---

## Part 3 — Details only you can supply: `includes/seo-settings.php`
In cPanel → File Manager → `public_html/includes/` → right-click **seo-settings.php** → **Edit**. It contains instructions and examples for:
- **Opening hours** (I did not invent any)
- **Google Business Profile link**
- **Social media and directory links**

Leave anything empty that you don't have. Save, and the structured data and `llms.txt` update immediately.

---

## Part 4 — About AI search ("AIEO")
Assistants like ChatGPT, Claude, Perplexity and Google's AI answers pick sources that are **clear, factual, consistent and easy to read by machine**. This site now does that through:
- the `llms.txt` summary and structured data, which state your name, address, services and people;
- FAQ answers written as plain, direct sentences;
- AI crawlers explicitly allowed in `robots.txt`;
- every fact on the site matching everywhere else.

To strengthen it, **keep facts identical across your website, Google Business Profile, LinkedIn and directories**, publish expert articles with the author's name, and earn mentions from other reputable sites. No one can guarantee an AI assistant will name the firm, but these are the signals they use.

---

## Part 5 — What to expect

| When | What you should see |
|---|---|
| Days 1–7 | Google finds the sitemap; home and key pages are indexed |
| Weeks 2–6 | Searching the firm's exact name shows the website |
| Months 2–6 | "Lawyers in Harare", "conveyancing Harare" and similar start to appear and move up, helped by your Business Profile, reviews and articles |
| Ongoing | Check **Search Console → Performance** monthly to see what people searched to find you |

## Quick checklist
- [ ] Main domain connected, HTTPS on, redirects enabled
- [ ] sitemap.xml, robots.txt and llms.txt open correctly
- [ ] Search Console verified, sitemap submitted, key pages "Request indexing"
- [ ] Bing Webmaster Tools set up
- [ ] Google Business Profile claimed, details match the website, website link added
- [ ] Sample articles deleted and real ones published
- [ ] Hours, profile link and social links added in `seo-settings.php`
- [ ] 2–4 new articles a month
