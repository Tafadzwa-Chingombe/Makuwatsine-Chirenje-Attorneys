<?php
require_once __DIR__ . '/functions.php';

function admin_header(string $title, string $nav = ''): void
{
    $user = current_user();
    $flash = flash();
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> — MC Attorneys CMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">
</head>
<body class="admin">
<?php if ($user): ?>
<header class="admin-bar">
  <a class="admin-brand" href="<?= e(url('admin/index.php')) ?>"><img src="<?= e(url('assets/img/logo-large.png')) ?>" alt="Makuwatsine Chirenje Attorneys"><span class="brand-sub">Content Manager</span></a>
  <nav>
    <a href="<?= e(url('admin/index.php')) ?>" <?= $nav === 'posts' ? 'class="on"' : '' ?>>Articles</a>
    <a href="<?= e(url('admin/edit.php')) ?>" <?= $nav === 'new' ? 'class="on"' : '' ?>>New article</a>
    <a href="<?= e(url('admin/enquiries.php')) ?>" <?= $nav === 'enq' ? 'class="on"' : '' ?>>Enquiries</a>
    <a href="<?= e(url('admin/password.php')) ?>" <?= $nav === 'pw' ? 'class="on"' : '' ?>>Password</a>
    <a href="<?= e(url('blog.php')) ?>" target="_blank" rel="noopener">View blog ↗</a>
  </nav>
  <form method="post" action="<?= e(url('admin/logout.php')) ?>" class="logout"><?= csrf_field() ?><span><?= e($user['name']) ?></span><button type="submit">Log out</button></form>
</header>
<?php endif; ?>
<main class="admin-main">
<?php if ($flash): ?><div class="notice notice-<?= e($flash['type']) ?>" role="status"><?= e($flash['msg']) ?></div><?php endif; ?>
<?php
}

function admin_footer(): void
{
    echo "</main>\n</body>\n</html>\n";
}
