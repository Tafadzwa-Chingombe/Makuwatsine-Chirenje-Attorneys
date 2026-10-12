<?php
require_once __DIR__ . '/../includes/admin_layout.php';
require_login();
$pdo = db();
if (!$pdo) {
    admin_header('Articles', 'posts');
    echo '<div class="notice notice-error">Cannot connect to the database. Check includes/config.php.</div>';
    admin_footer();
    exit;
}

$filter = in_array($_GET['status'] ?? '', ['draft', 'published'], true) ? $_GET['status'] : '';
$q = trim((string) ($_GET['q'] ?? ''));
$sql = 'SELECT id, title, slug, category, status, published_at, updated_at FROM posts WHERE 1=1';
$params = [];
if ($filter) { $sql .= ' AND status = ?'; $params[] = $filter; }
if ($q !== '') { $sql .= ' AND title LIKE ?'; $params[] = '%' . $q . '%'; }
$sql .= ' ORDER BY COALESCE(published_at, updated_at) DESC, id DESC LIMIT 200';
$st = $pdo->prepare($sql);
$st->execute($params);
$posts = $st->fetchAll();
$counts = $pdo->query("SELECT status, COUNT(*) c FROM posts GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$unread = (int) $pdo->query('SELECT COUNT(*) FROM enquiries WHERE is_read = 0')->fetchColumn();

admin_header('Articles', 'posts');
?>
<div class="page-head">
  <h1>Articles</h1>
  <a class="btn-primary" href="<?= e(url('admin/edit.php')) ?>">+ New article</a>
</div>
<?php if ($unread): ?><div class="notice notice-info">You have <strong><?= $unread ?></strong> unread website enquir<?= $unread === 1 ? 'y' : 'ies' ?>. <a href="<?= e(url('admin/enquiries.php')) ?>">View enquiries</a></div><?php endif; ?>

<div class="toolbar">
  <nav class="tabs">
    <a href="?" class="<?= $filter === '' ? 'on' : '' ?>">All (<?= (int) (($counts['draft'] ?? 0) + ($counts['published'] ?? 0)) ?>)</a>
    <a href="?status=published" class="<?= $filter === 'published' ? 'on' : '' ?>">Published (<?= (int) ($counts['published'] ?? 0) ?>)</a>
    <a href="?status=draft" class="<?= $filter === 'draft' ? 'on' : '' ?>">Drafts (<?= (int) ($counts['draft'] ?? 0) ?>)</a>
  </nav>
  <form method="get" class="search"><?php if ($filter): ?><input type="hidden" name="status" value="<?= e($filter) ?>"><?php endif; ?><input type="search" name="q" value="<?= e($q) ?>" placeholder="Search titles…"><button>Search</button></form>
</div>

<?php if (!$posts): ?>
  <div class="empty"><p>No articles found.</p><a class="btn-primary" href="<?= e(url('admin/edit.php')) ?>">Write your first article</a></div>
<?php else: ?>
<div class="table-wrap">
<table>
  <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($posts as $p):
      $scheduled = $p['status'] === 'published' && $p['published_at'] && strtotime($p['published_at']) > time(); ?>
    <tr>
      <td><a class="title" href="<?= e(url('admin/edit.php?id=' . (int) $p['id'])) ?>"><?= e($p['title']) ?></a><div class="slug">/blog/<?= e($p['slug']) ?></div></td>
      <td><?= e($p['category']) ?></td>
      <td><span class="badge <?= $scheduled ? 'badge-sched' : 'badge-' . e($p['status']) ?>"><?= $scheduled ? 'Scheduled' : e(ucfirst($p['status'])) ?></span></td>
      <td><?= e($p['published_at'] ? format_date($p['published_at']) : 'Updated ' . format_date($p['updated_at'])) ?></td>
      <td class="actions">
        <a href="<?= e(url('admin/edit.php?id=' . (int) $p['id'])) ?>">Edit</a>
        <a href="<?= e(url('post.php?slug=' . rawurlencode($p['slug']) . '&preview=1')) ?>" target="_blank" rel="noopener">View</a>
        <form method="post" action="<?= e(url('admin/delete.php')) ?>" onsubmit="return confirm('Delete this article permanently?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><button class="link-danger">Delete</button></form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php endif;
admin_footer();
