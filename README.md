# Makuwatsine Chirenje Attorneys — Website & CMS

Website for **Makuwatsine Chirenje Attorneys (MC Attorneys)**, Harare, Zimbabwe, with a custom blog CMS built for cPanel hosting (PHP 8 + MySQL, no framework or build step).

Design: Figma file *"Makuwatsine Chirenje Attorneys"* (desktop 1440 / tablet 768 / mobile 390). Brand colours: dark navy `#0B1F3A` and gold `#C99A2E` (from the logo).

## Pages
| Page | File |
|---|---|
| Home | `index.php` |
| About | `about.php` |
| Practice Areas | `practice-areas.php` |
| Team | `team.php` |
| Blog (+ article) | `blog.php`, `post.php` (`/blog/<slug>`) |
| Contact (form → email + CMS inbox) | `contact.php` |

## CMS (`/admin/`)
* Secure login, rich-text editor (Quill), cover & inline image upload
* Draft / publish / schedule, categories, auto slug & excerpt, delete
* Contact-form enquiries inbox, change password
* Published articles appear on the Blog page immediately

## Quick start (cPanel)
1. Create a MySQL database + user in cPanel → *MySQL® Databases*.
2. Upload the repository to `public_html` (or use cPanel *Git™ Version Control*; see `.cpanel.yml`).
3. Open `/install/install.php`, complete the form, then **delete `/install`**.
4. Log in at `/admin/`.

Full guide: [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md)

## Local development
```bash
cp includes/config.sample.php includes/config.php   # set DB credentials
mysql -u root -e "CREATE DATABASE mcattorneys CHARACTER SET utf8mb4"
mysql -u root mcattorneys < sql/schema.sql
php -S 127.0.0.1:8000
```
(The built-in PHP server ignores `.htaccess`, so use `post.php?slug=…` instead of `/blog/…` locally.)

## Static design preview (GitHub Pages)
GitHub Pages cannot run PHP, so a static preview of the design (sample blog posts, no CMS or working contact form) is built from the real pages:
```bash
php tools/build-static.php ./site /Makuwatsine-Chirenje-Attorneys
```
The output is published on the `gh-pages` branch → https://tafadzwa-chingombe.github.io/Makuwatsine-Chirenje-Attorneys/ (Settings → Pages → Branch: `gh-pages` / root).

## Structure
```
index.php about.php practice-areas.php team.php blog.php post.php contact.php
includes/   functions, data (firm content), header/footer, partials, config
admin/      login, articles list, editor, upload, enquiries, password
assets/     css, js, img
install/    one-time installer (delete after use)
sql/        schema.sql
uploads/    article images (git-ignored)
```
