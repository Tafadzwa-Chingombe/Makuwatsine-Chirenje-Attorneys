# Putting the Makuwatsine Chirenje Attorneys website and CMS on cPanel

**You will upload one file:** `mc-attorneys-cpanel.zip` (about 1.4 MB). It contains the whole website, all the pictures from the demo site, and the CMS (Content Manager). Allow about 30 minutes.

**What you need before you start**
- Your cPanel login (usually `https://yourdomain.co.zw/cpanel` or the link your hosting company emailed you).
- Your domain pointing to the hosting (for example `mcattorneys.co.zw`).
- A password you will use for the CMS admin login (at least 10 characters).

> **Two sites, two jobs.** The GitHub demo (`github.io`) stays as your showcase for the client. The cPanel site is the real one: its CMS saves to a real database, so articles and enquiries are kept for everyone and are not tied to one browser.

---

## Step 1 — Check the PHP version
1. In cPanel, open **Select PHP Version** (or **MultiPHP Manager**).
2. Choose **PHP 8.1 or newer** for your domain.
3. In **Select PHP Version → Extensions**, make sure these are ticked: `pdo_mysql` (or `mysqlnd`), `mbstring`, `dom` (sometimes called `xml`), `fileinfo`, `iconv`. Click **Save**.

## Step 2 — Create the database
1. In cPanel, open **MySQL® Databases**.
2. Under **Create New Database**, type `mcattorneys` and click **Create Database**. cPanel adds a prefix, so the real name looks like `cpaneluser_mcattorneys`. **Write down the full name.**
3. Under **MySQL Users → Add New User**, type a username such as `mcadmin` and click **Password Generator** (or type a strong password). Click **Create User**. **Write down the full username (also with a prefix) and the password.**
4. Under **Add User To Database**, pick the user and the database, click **Add**, tick **ALL PRIVILEGES**, and click **Make Changes**.

You now have three things written down: **database name, database user, database password**.

## Step 3 — Upload and unzip the website
1. In cPanel, open **File Manager** and go into **`public_html`** (this is the folder your website is served from).
2. If there is a default `index.html`, a `default.html` or a `cgi-bin` placeholder page inside, delete the page file (leave `cgi-bin` alone).
3. Click **Settings** (top right of File Manager) and tick **Show Hidden Files (dotfiles)**, then **Save**. This lets you see `.htaccess`, which the site needs.
4. Click **Upload**, drag in **`mc-attorneys-cpanel.zip`**, and wait for the bar to reach 100%. Click **Go Back**.
5. Click the zip in the list and choose **Extract** (top menu). Extract into `/public_html` and click **Extract File(s)**. Close the results box.
6. Check that `public_html` now shows: `.htaccess`, `about.php`, `admin`, `assets`, `blog.php`, `contact.php`, `includes`, `index.php`, `install`, `post.php`, `practice-areas.php`, `sql`, `team.php` and `uploads`.
7. **Delete the zip file** from `public_html` (select it and click **Delete**).

*Want the site in a sub-folder, like `yourdomain.co.zw/mc`?* Extract into `public_html/mc` instead. In the installer (Step 5), the "Install folder" box fills itself in as `/mc`.

## Step 4 — Turn on HTTPS (the padlock)
1. In cPanel, open **SSL/TLS Status**.
2. Click **Run AutoSSL**. After a few minutes your domain shows a green padlock.
3. If it is not ready yet, you can carry on and come back to this in Step 7.

## Step 5 — Run the installer (once)
1. In your browser, go to `https://yourdomain.co.zw/install/install.php`.
2. Fill in the form:

| Box | What to type |
|---|---|
| Host | `localhost` |
| Database name | the full name from Step 2 (for example `cpaneluser_mcattorneys`) |
| Database user | the full username from Step 2 |
| Database password | the password from Step 2 |
| Site URL | already filled in; check it matches your address, with no slash at the end |
| Install folder | leave blank if the site is in `public_html` itself |
| Send contact enquiries to | `admin@mcattorneys.co.zw` (already filled in) |
| Send enquiries from | an address on your domain, for example `no-reply@mcattorneys.co.zw` (see Step 8) |
| Name / Email / Password | your CMS admin login. Keep these safe |
| Add the 7 sample blog articles | leave ticked if you want the Blog page filled with the demo articles and pictures; untick for an empty blog |

3. Click **Install**. You should see **"Installation complete."**

If you see an error about writing `includes/config.php`, go to File Manager, right-click the `includes` folder, choose **Change Permissions**, set it to **775**, run the installer again, then set it back to **755**.

## Step 6 — Delete the installer (important for security)
1. In File Manager, go to `public_html`, select the **`install`** folder and click **Delete**. Confirm.
2. Do not skip this step. The installer locks itself, but the folder should not stay on the server.

## Step 7 — Force HTTPS
1. In File Manager, right-click **`.htaccess`** and choose **Edit**.
2. Near the bottom, remove the `# ` (hash and space) at the start of these two lines:
   ```
   RewriteCond %{HTTPS} off
   RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```
   (Leave the line above them that starts with `# Force HTTPS` as it is.)
3. Click **Save Changes**. Only do this once the padlock from Step 4 is working.

## Step 8 — Email (so enquiries arrive)
1. In cPanel, open **Email Accounts** and make sure **`admin@mcattorneys.co.zw`** exists. This is where the Contact form sends messages. If it does not, click **Create** and set a password.
2. Create a second address such as **`no-reply@mcattorneys.co.zw`**. This is the "from" address the website uses. If you used a different address in Step 5, create that one instead.
3. Open **Email Deliverability** and click **Manage** next to your domain. If it says there are problems with SPF or DKIM, click **Repair**. This keeps messages out of spam.

Every message from the Contact page is also saved in the CMS under **Enquiries**, so nothing is lost even if an email is delayed.

## Step 9 — Test everything
Open each of these and check that the pictures show:
- [ ] `https://yourdomain.co.zw/` (home page, with the hero pictures and the four team photos)
- [ ] **About**, **Practice Areas**, **Team** (all seven portraits), **Blog** (the featured article and the six cards have pictures), **Contact** (the map shows)
- [ ] Open one blog article. Its cover picture shows at the top.
- [ ] Go to `https://yourdomain.co.zw/admin/` and log in with the admin email and password from Step 5.
- [ ] Click **New article**, write a short test article, add a cover picture and click **Publish**. Open the Blog page and check it appears. Then delete the test article.
- [ ] Send a test message from the **Contact** page. Check it appears in the CMS under **Enquiries**, and that an email arrives at `admin@mcattorneys.co.zw`.

## Using the CMS day to day
- **Write an article:** Admin → **New article** → add title, text, category, cover picture → **Publish** (or **Save draft** to keep it private).
- **Schedule an article:** set a publish date in the future. It appears by itself at that time.
- **Enquiries:** Admin → **Enquiries**.
- **Change your password:** Admin → **Password**.
- **Categories:** the blog categories are Conveyancing, Litigation, Estates, Labour Law, Commercial, Mining Law and News. To add or rename them, ask me to update the list. (The "type your own category" feature exists in the GitHub demo only so far, and I can add it to this real CMS.)

## Keeping your site safe
- Back up regularly: cPanel → **Backup** (download a full backup), or **JetBackup** if your host has it. Include the database **and** the `uploads` folder (article pictures).
- Never delete `includes/config.php` (it holds your database login) or the `uploads` folder.
- To change a page later, upload the changed file over the old one in File Manager. Do **not** re-extract the whole zip over a live site, because it would put the sample files back. The installer is gone, so your database and settings stay safe.

## If something goes wrong
| What you see | What to do |
|---|---|
| **500 error** on every page | cPanel → **Select PHP Version** → make sure it is 8.1 or newer. Then check `.htaccess` was extracted fully. |
| **Pictures missing** (grey boxes or broken images) | In File Manager, right-click the `assets` folder → **Change Permissions** → 755 → tick "Recurse into subdirectories" → Save. Files should be 644. |
| **Blog article gives "Not Found"** | `.htaccess` is missing or not read. Turn on **Show Hidden Files** and check it is in `public_html`. |
| Blog says **"Articles are coming soon"** | The database login is wrong. Open `includes/config.php` in File Manager and check the name, user and password. |
| **Cannot log in** to the CMS | Use the email and password you typed in the installer. After 5 wrong tries, wait 15 minutes. |
| **Enquiry emails don't arrive** | Check spam, then Step 8. The message is still saved under Enquiries in the CMS. |
| **Installer says "Already installed"** | It worked the first time. Delete the `install` folder and log in at `/admin/`. |
| **Map doesn't show** | Check the Contact page loads over `https`, and that your browser isn't blocking Google Maps. |

---
*Files included in the zip: the website pages, `admin/` (the CMS), `assets/` (styles, scripts and every picture used on the demo site), `includes/`, `install/` (delete after use), `sql/` and `uploads/`. The seven sample blog pictures sit in `install/sample-covers` and are copied to `uploads` by the installer.*
