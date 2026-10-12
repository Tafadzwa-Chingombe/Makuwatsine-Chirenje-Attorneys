<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';

$slug = (string) ($_GET['slug'] ?? '');
$post = null;
$pdo = db();
if ($pdo && $slug !== '') {
    try {
        // Logged-in admins may preview drafts via ?preview=1.
        $preview = !empty($_GET['preview']) && current_user();
        $sql = 'SELECT * FROM posts WHERE slug = ?' . ($preview ? '' : " AND status = 'published' AND published_at <= NOW()");
        $st = $pdo->prepare($sql);
        $st->execute([$slug]);
        $post = $st->fetch() ?: null;
    } catch (Throwable $ex) {
        error_log('Post query failed: ' . $ex->getMessage());
    }
}

if (!$post) {
    http_response_code(404);
    $active = 'blog';
    $page_title = 'Article not found';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="section"><div class="container article">
      <h1>Article not found</h1>
      <p>The article you are looking for does not exist or is no longer available.</p>
      <p><a class="btn btn-dark" href="<?= e(url('blog.php')) ?>">Back to the blog</a></p>
    </div></section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$active = 'blog';
$page_title = $post['title'];
$meta_desc = $post['excerpt'] !== '' ? $post['excerpt'] : make_excerpt($post['content'], 160);
if ($post['cover_image']) {
    $og_image = rtrim((string) config('site_url'), '/') . upload_url($post['cover_image']);
}
require __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="container">
    <article class="article">
      <a class="back-link" href="<?= e(url('blog.php')) ?>">← All articles</a>
      <header class="article-head">
        <p class="eyebrow"><?= e($post['category']) ?><?= $post['status'] === 'draft' ? ' · DRAFT PREVIEW' : '' ?></p>
        <h1><?= e($post['title']) ?></h1>
        <p class="meta"><?= e(format_date($post['published_at'] ?: $post['created_at'])) ?> · <?= (int) reading_minutes($post['content']) ?> min read<?= $post['author'] ? ' · By ' . e($post['author']) : '' ?></p>
      </header>
      <?php if ($post['cover_image']): ?>
        <figure class="article-cover"><img src="<?= e(upload_url($post['cover_image'])) ?>" alt=""></figure>
      <?php endif; ?>
      <div class="prose"><?= $post['content'] /* sanitised on save by sanitize_html() */ ?></div>
    </article>
  </div>
</section>
<?php
cta_banner('Need advice?', 'Speak to MC Attorneys about your matter.');
require __DIR__ . '/includes/footer.php';
