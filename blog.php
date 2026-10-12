<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';

$active = 'blog';
$page_title = 'Blog';
$meta_desc = 'Legal insights and news from Makuwatsine Chirenje Attorneys: property, estates, labour, commercial and mining law in Zimbabwe.';

$cats = config('categories');
$cat = isset($_GET['category']) && in_array($_GET['category'], $cats, true) ? $_GET['category'] : '';
$perPage = max(1, (int) config('posts_per_page', 6));
$pageNo = max(1, (int) ($_GET['page'] ?? 1));
$posts = [];
$total = 0;
$dbDown = false;
$featured = null;

$pdo = db();
if (!$pdo) {
    $dbDown = true;
} else {
    try {
        $where = "status = 'published' AND published_at <= NOW()";
        $params = [];
        if ($cat !== '') {
            $where .= ' AND category = ?';
            $params[] = $cat;
        }
        $st = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE $where");
        $st->execute($params);
        $total = (int) $st->fetchColumn();

        // The newest article is featured on page 1; the grid lists everything after it.
        if ($pageNo === 1 && $total > 0) {
            $st = $pdo->prepare("SELECT * FROM posts WHERE $where ORDER BY published_at DESC, id DESC LIMIT 1");
            $st->execute($params);
            $featured = $st->fetch();
        }
        $offset = 1 + ($pageNo - 1) * $perPage;
        $st = $pdo->prepare("SELECT * FROM posts WHERE $where ORDER BY published_at DESC, id DESC LIMIT $perPage OFFSET $offset");
        $st->execute($params);
        $posts = $st->fetchAll();
    } catch (Throwable $ex) {
        error_log('Blog query failed: ' . $ex->getMessage());
        $dbDown = true;
    }
}
$pages = max(1, (int) ceil(max(0, $total - 1) / $perPage));

function blog_link(int $p, string $cat): string
{
    $q = [];
    if ($cat !== '') { $q['category'] = $cat; }
    if ($p > 1) { $q['page'] = $p; }
    return url('blog.php') . ($q ? '?' . http_build_query($q) : '');
}

require __DIR__ . '/includes/header.php';
page_hero('Legal insights', 'News and articles from MC Attorneys.', 'Practical guidance on property, estates, labour and commercial law.');
?>
<section class="section">
  <div class="container">
    <nav class="chips" aria-label="Filter by category">
      <a class="chip <?= $cat === '' ? 'is-active' : '' ?>" href="<?= e(url('blog.php')) ?>">All</a>
      <?php foreach ($cats as $c): ?>
        <a class="chip <?= $cat === $c ? 'is-active' : '' ?>" href="<?= e(url('blog.php') . '?category=' . rawurlencode($c)) ?>"><?= e($c) ?></a>
      <?php endforeach; ?>
    </nav>

    <?php if ($dbDown): ?>
      <div class="empty-state"><h3>Articles are coming soon</h3><p>Please check back shortly.</p></div>
    <?php elseif (!$featured && !$posts): ?>
      <div class="empty-state"><h3>No articles yet</h3><p><?= $cat ? 'There are no articles in this category yet.' : 'New articles will appear here once published.' ?></p></div>
    <?php else: ?>
      <?php if ($featured): ?>
        <article class="featured">
          <a href="<?= e(post_url($featured['slug'])) ?>" tabindex="-1" aria-hidden="true">
            <?php if ($featured['cover_image']): ?><img src="<?= e(upload_url($featured['cover_image'])) ?>" alt=""><?php else: img_placeholder('Featured article image'); endif; ?>
          </a>
          <div class="featured-body">
            <p class="eyebrow">Featured · <?= e($featured['category']) ?></p>
            <h2><a href="<?= e(post_url($featured['slug'])) ?>"><?= e($featured['title']) ?></a></h2>
            <p><?= e($featured['excerpt']) ?></p>
            <p class="meta"><?= e(format_date($featured['published_at'])) ?> · <?= (int) reading_minutes($featured['content']) ?> min read<?= $featured['author'] ? ' · ' . e($featured['author']) : '' ?></p>
            <p><a class="read-more" href="<?= e(post_url($featured['slug'])) ?>">Read article →</a></p>
          </div>
        </article>
      <?php endif; ?>

      <?php if ($posts): ?>
        <div class="posts"><?php foreach ($posts as $p) post_card($p); ?></div>
      <?php endif; ?>

      <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Pagination">
          <?php if ($pageNo > 1): ?><a href="<?= e(blog_link($pageNo - 1, $cat)) ?>" aria-label="Previous page">‹</a><?php endif; ?>
          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $pageNo): ?><span class="is-current" aria-current="page"><?= $i ?></span>
            <?php else: ?><a href="<?= e(blog_link($i, $cat)) ?>"><?= $i ?></a><?php endif; ?>
          <?php endfor; ?>
          <?php if ($pageNo < $pages): ?><a href="<?= e(blog_link($pageNo + 1, $cat)) ?>" aria-label="Next page">›</a><?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php';
