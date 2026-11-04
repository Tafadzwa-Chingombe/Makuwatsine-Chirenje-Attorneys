<?php
/**
 * Expects: $page_title (string), $active (nav key), optional $meta_desc, $og_image
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/seo.php';

$page_title = $page_title ?? FIRM['name'];
$meta_desc  = $meta_desc ?? 'Makuwatsine Chirenje Attorneys (MC Attorneys) — legal practitioners, notaries, conveyancers, executors, labour law consultants and commercial lawyers in Harare, Zimbabwe.';
$active     = $active ?? '';
$nav = [
    'home'     => ['Home', 'index.php'],
    'about'    => ['About', 'about.php'],
    'practice' => ['Practice Areas', 'practice-areas.php'],
    'team'     => ['Team', 'team.php'],
    'blog'     => ['Blog', 'blog.php'],
    'contact'  => ['Contact', 'contact.php'],
];
$full_title = $seo_title ?? ($page_title === FIRM['name'] ? $page_title : $page_title . ' | ' . FIRM['short']);
$noindex   = !empty($noindex) || defined('MC_NOINDEX');   // MC_NOINDEX is set only for the GitHub demo build
$canonical = $canonical ?? canonical_current();
$og_image  = $og_image ?? canonical_for('assets/img/og-image.jpg');
$og_type   = $og_type ?? 'website';
if (!isset($crumbs)) {
    $crumbs = ($active === 'home' || $active === '') ? [] : [['Home', canonical_base() . url('')], [$page_title, $canonical]];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($full_title) ?></title>
<link rel="icon" href="<?= e(url('favicon.ico')) ?>" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(url('assets/img/favicon-32.png')) ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?= e(url('assets/img/favicon-192.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(url('assets/img/apple-touch-icon.png')) ?>">
<meta name="description" content="<?= e($meta_desc) ?>">
<meta name="robots" content="<?= $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1' ?>">
<?php if (!$noindex): ?><link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<meta name="theme-color" content="#0B1F3A">
<meta property="og:site_name" content="<?= e(FIRM['name']) ?>">
<meta property="og:locale" content="en_GB">
<meta property="og:title" content="<?= e($full_title) ?>">
<meta property="og:description" content="<?= e($meta_desc) ?>">
<meta property="og:type" content="<?= e($og_type) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($og_image) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($full_title) ?>">
<meta name="twitter:description" content="<?= e($meta_desc) ?>">
<meta name="twitter:image" content="<?= e($og_image) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>?v=<?= (int) @filemtime(__DIR__ . '/../assets/css/style.css') ?>">
<?= jsonld(seo_org_schema()) ?><?= jsonld(seo_website_schema()) ?><?php if ($crumbs): ?><?= jsonld(seo_breadcrumb_schema($crumbs)) ?><?php endif; ?></head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header" id="top">
  <div class="container nav-row">
    <a class="logo" href="<?= e(url('index.php')) ?>" aria-label="<?= e(FIRM['name']) ?> — home">
      <img src="<?= e(url('assets/img/logo-large.png')) ?>" alt="" width="205" height="44">
    </a>
    <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="site-nav"><span></span><span></span><span></span></button>
    <nav id="site-nav" class="site-nav" aria-label="Main">
      <ul>
        <?php foreach ($nav as $key => [$label, $href]): ?>
          <li><a href="<?= e(url($href)) ?>" <?= $key === $active ? 'class="is-active" aria-current="page"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="nav-menu-foot">
        <a class="btn btn-primary" href="<?= e(url('contact.php')) ?>">Book a Consultation</a>
        <p><?= e(implode(', ', FIRM['address'])) ?><br><?= e(FIRM['email']) ?></p>
      </div>
    </nav>
  </div>
</header>
<main id="main">
