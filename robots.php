<?php
/** robots.txt: tells search engines and AI crawlers what they may read. Served as /robots.txt (see .htaccess). */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
$base = rtrim((string) config('base_path'), '/');
?>
# Makuwatsine Chirenje Attorneys — everyone is welcome to read the public website, including AI search assistants.
User-agent: *
User-agent: Googlebot
User-agent: Bingbot
User-agent: GPTBot
User-agent: OAI-SearchBot
User-agent: ChatGPT-User
User-agent: ClaudeBot
User-agent: Claude-User
User-agent: Claude-SearchBot
User-agent: PerplexityBot
User-agent: Perplexity-User
User-agent: Google-Extended
User-agent: Applebot
User-agent: Applebot-Extended
User-agent: Bytespider
User-agent: CCBot
Allow: /
Disallow: <?= $base ?>/admin/
Disallow: <?= $base ?>/install/
Disallow: <?= $base ?>/includes/
Disallow: <?= $base ?>/sql/

Sitemap: <?= canonical_for('sitemap.xml') ?>

# A plain-language summary of the firm for AI assistants:
# <?= canonical_for('llms.txt') ?>
