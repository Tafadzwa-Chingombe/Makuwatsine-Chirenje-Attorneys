# Deploying to cPanel

This site is plain **PHP 8 + MySQL** — the stack every cPanel host provides. Nothing needs Composer, Node or SSH.
The CMS "talks to cPanel" through the standard cPanel services below, so no API tokens are required.

| What the site needs | cPanel tool that provides it |
|---|---|
| Database for articles, users, enquiries | **MySQL® Databases** (create DB + user) / **phpMyAdmin** |
| Getting the code onto the server | **Git™ Version Control** (recommended) or **File Manager** upload |
| Contact-form email | cPanel mail server (PHP `mail()`), optional **Email Accounts** for a `no-reply@` sender |
| HTTPS | **SSL/TLS Status** (AutoSSL) |
| PHP version & extensions | **Select PHP Version** / **MultiPHP Manager** |
| Backups | **Backup** / **JetBackup** (include the database **and** the `uploads/` folder) |

## 1. Prepare PHP
cPanel → **Select PHP Version** → choose **8.1 or newer** and make sure these extensions are ticked:
`pdo_mysql`, `mbstring`, `dom` (xml), `fileinfo`, `iconv`, `gd` (optional).

## 2. Create the database
cPanel → **MySQL® Databases**
1. *Create New Database* → e.g. `mcattorneys` (cPanel prefixes it: `cpaneluser_mcattorneys`).
2. *MySQL Users* → add a user (e.g. `mcadmin`) with a strong password.
3. *Add User To Database* → select both → tick **ALL PRIVILEGES**.

Keep the three values (database name, user, password) — the installer asks for them.

## 3. Upload the site
**Option A — Git Version Control (auto-deploy)**
1. cPanel → **Git™ Version Control → Create** → clone URL of this repository, repository path e.g. `/home/cpaneluser/repositories/mc-attorneys`.
2. Edit `.cpanel.yml` and replace `CPANELUSER` with your cPanel username (and the target folder if the site is not in `public_html`).
3. **Manage → Deploy HEAD Commit**. Every later deploy (after you pull) copies the site but **never overwrites** `includes/config.php` or `uploads/`.

**Option B — File Manager**
Zip the repository, upload it to `public_html` (or a subfolder), and *Extract*.

Folder permissions: directories `755`, files `644`, and `uploads/` must be writable by PHP (`755` normally works; use `775` if your host runs PHP under a different group).

## 4. Run the installer (once)
Visit `https://your-domain/install/install.php`, fill in the database details, site URL, the address contact enquiries go to, and the first administrator's name, email and password (min. 10 characters).

The installer creates the tables, the admin user and `includes/config.php`, then locks itself.
**Afterwards delete the `/install` folder** (File Manager → select `install` → Delete).

> Prefer manual setup? Import `sql/schema.sql` in phpMyAdmin, copy `includes/config.sample.php` to `includes/config.php`, fill it in, and create the first user with a bcrypt hash:
> `INSERT INTO users (name,email,password_hash) VALUES ('Name','you@example.com','<output of php -r "echo password_hash(\'YourPassword\', PASSWORD_DEFAULT);">');`

## 5. HTTPS
cPanel → **SSL/TLS Status → Run AutoSSL**. Then open `.htaccess` and uncomment the *Force HTTPS* lines.

## 6. Contact-form email
Enquiries are (a) saved in the CMS under **Enquiries** and (b) emailed to the address you set (`mail_to`).
For reliable delivery set `mail_from` to a real address on your own domain (create one in **Email Accounts**, e.g. `no-reply@yourdomain.co.zw`). Replies go to the visitor because `Reply-To` is set to their address.
If emails land in spam, add the SPF/DKIM records shown in cPanel → **Email Deliverability**.

## 7. Using the Content Manager
Go to `https://your-domain/admin/` → log in → **New article**.

* Write with the rich-text editor (headings, bold/italic, lists, quotes, links, inline images).
* Add a **cover image**, choose a **category**, optionally write an **excerpt** (otherwise created automatically).
* **Save draft** keeps it private. **Publish** makes it appear immediately on **/blog.php** and as `/blog/your-article-title`.
* Set a **future publish date** to schedule an article — it appears automatically at that time (Harare time, CAT).
* Contact-form messages appear under **Enquiries**; change your password under **Password**.

## 8. Replacing the placeholders
* **Logo** — in `includes/header.php` and `includes/footer.php`, replace the `<span class="logo-mark">` with `<img src="assets/img/logo.png" alt="Makuwatsine Chirenje Attorneys" height="48">`.
* **Photos** — every grey "IMAGE PLACEHOLDER" is produced by `img_placeholder()` in `includes/partials.php` (and the `.img-ph` markup in the pages). Replace with an `<img>` in the page, e.g. `team.php`, `index.php`.
* **Map** — `contact.php` has a comment marking where to paste the Google Maps `<iframe>`.

## Security notes
* Passwords are stored with bcrypt; all database access uses prepared statements; forms use CSRF tokens.
* Article HTML is passed through an allow-list sanitiser, so pasted scripts/iframes/inline event handlers are removed.
* Uploads are validated by real file type and renamed; `uploads/.htaccess` blocks script execution.
* `includes/`, `sql/` and `.git*` are blocked from direct web access by `.htaccess`.
* Login is limited to 5 failed attempts per 15 minutes per IP.
* Back up the database **and** `uploads/` regularly.

## Troubleshooting
| Symptom | Fix |
|---|---|
| Blog says "Articles are coming soon" | Database not reachable — check `includes/config.php` values in File Manager. |
| `/blog/article-name` gives 404 | `.htaccess` was not uploaded (hidden file) or `mod_rewrite` is off. `post.php?slug=article-name` still works. |
| Image upload fails | `uploads/` not writable, or file > 4 MB, or PHP `upload_max_filesize` is lower (Select PHP Version → Options). |
| Editor toolbar missing | The Quill editor loads from cdn.jsdelivr.net — check the visitor's connection; a plain-text fallback is shown. |
| Contact emails not arriving | Set `mail_from` to an address on your domain; check Email Deliverability and spam folder. |
