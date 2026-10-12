<?php
require_once __DIR__ . '/../includes/admin_layout.php';
$user = require_login();
$pdo = db();
if (!$pdo) { exit('Database not available.'); }

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$post = [
    'id' => 0, 'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'cover_image' => null,
    'category' => config('categories')[0] ?? 'News', 'author' => $user['name'], 'status' => 'draft', 'published_at' => null,
];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $st->execute([$id]);
    $found = $st->fetch();
    if (!$found) { flash('Article not found.', 'error'); redirect(url('admin/index.php')); }
    $post = $found;
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $cats = config('categories');
    $action = ($_POST['action'] ?? 'draft') === 'publish' ? 'publish' : 'draft';

    $post['title'] = trim((string) ($_POST['title'] ?? ''));
    $post['slug'] = slugify((string) (($_POST['slug'] ?? '') !== '' ? $_POST['slug'] : $post['title']));
    $post['excerpt'] = mb_substr(trim((string) ($_POST['excerpt'] ?? '')), 0, 400);
    $post['category'] = in_array($_POST['category'] ?? '', $cats, true) ? $_POST['category'] : $cats[0];
    $post['author'] = mb_substr(trim((string) ($_POST['author'] ?? '')), 0, 120);
    $post['content'] = sanitize_html((string) ($_POST['content'] ?? ''));

    if ($post['title'] === '') { $errors[] = 'Please enter a title.'; }
    if (mb_strlen($post['title']) > 200) { $errors[] = 'The title must be 200 characters or fewer.'; }
    if ($action === 'publish' && trim(strip_tags($post['content'], '<img>')) === '') { $errors[] = 'An article needs some content before it can be published.'; }

    // Unique slug
    if (!$errors) {
        $base = $post['slug'];
        $n = 1;
        while (true) {
            $st = $pdo->prepare('SELECT id FROM posts WHERE slug = ? AND id <> ?');
            $st->execute([$post['slug'], $post['id']]);
            if (!$st->fetch()) { break; }
            $post['slug'] = $base . '-' . (++$n);
        }
    }

    // Cover image
    $oldCover = $post['cover_image'];
    $newCover = $oldCover;
    if (!$errors && !empty($_POST['remove_cover'])) { $newCover = null; }
    if (!$errors && !empty($_FILES['cover']['name'])) {
        try { $newCover = store_image($_FILES['cover']); } catch (RuntimeException $ex) { $errors[] = $ex->getMessage(); }
    }

    if (!$errors) {
        $dt = trim((string) ($_POST['published_at'] ?? ''));
        $ts = $dt !== '' ? strtotime($dt) : false;
        if ($action === 'publish') {
            $post['status'] = 'published';
            $publishedAt = $ts ? date('Y-m-d H:i:s', $ts) : ($post['published_at'] ?: date('Y-m-d H:i:s'));
        } else {
            $post['status'] = 'draft';
            $publishedAt = $ts ? date('Y-m-d H:i:s', $ts) : $post['published_at'];
        }
        $excerpt = $post['excerpt'] !== '' ? $post['excerpt'] : make_excerpt($post['content']);
        try {
            if ($post['id']) {
                $pdo->prepare('UPDATE posts SET title=?, slug=?, excerpt=?, content=?, cover_image=?, category=?, author=?, status=?, published_at=? WHERE id=?')
                    ->execute([$post['title'], $post['slug'], $excerpt, $post['content'], $newCover, $post['category'], $post['author'], $post['status'], $publishedAt, $post['id']]);
            } else {
                $pdo->prepare('INSERT INTO posts (title, slug, excerpt, content, cover_image, category, author, status, published_at) VALUES (?,?,?,?,?,?,?,?,?)')
                    ->execute([$post['title'], $post['slug'], $excerpt, $post['content'], $newCover, $post['category'], $post['author'], $post['status'], $publishedAt]);
                $post['id'] = (int) $pdo->lastInsertId();
            }
            if ($oldCover && $oldCover !== $newCover) {
                $f = ROOT_DIR . '/uploads/' . basename($oldCover);
                if (is_file($f)) { @unlink($f); }
            }
            flash($action === 'publish' ? 'Article published — it is now live on the blog.' : 'Draft saved.');
            redirect(url('admin/edit.php?id=' . $post['id']));
        } catch (Throwable $ex) {
            error_log('Save post failed: ' . $ex->getMessage());
            $errors[] = 'The article could not be saved. Please try again.';
        }
    }
}

$local = $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '';
admin_header($post['id'] ? 'Edit article' : 'New article', $post['id'] ? 'posts' : 'new');
?>
<div class="page-head">
  <h1><?= $post['id'] ? 'Edit article' : 'New article' ?></h1>
  <?php if ($post['id'] && $post['status'] === 'published'): ?><a class="btn-ghost" href="<?= e(post_url($post['slug'])) ?>" target="_blank" rel="noopener">View live ↗</a>
  <?php elseif ($post['id']): ?><a class="btn-ghost" href="<?= e(url('post.php?slug=' . rawurlencode($post['slug']) . '&preview=1')) ?>" target="_blank" rel="noopener">Preview ↗</a><?php endif; ?>
</div>
<?php if ($errors): ?><div class="notice notice-error" role="alert"><?php foreach ($errors as $er): ?><?= e($er) ?><br><?php endforeach; ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" id="post-form" class="editor-layout">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
  <div class="editor-main">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?= e($post['title']) ?>" maxlength="200" required class="title-input" placeholder="Article title">

    <label for="slug">URL slug <span class="hint">/blog/<strong id="slug-preview"><?= e($post['slug'] ?: '…') ?></strong></span></label>
    <input type="text" id="slug" name="slug" value="<?= e($post['slug']) ?>" maxlength="150" placeholder="auto-generated from the title" data-manual="<?= $post['id'] ? '1' : '0' ?>">

    <label>Content</label>
    <div id="editor"><?= $post['content'] /* already sanitised */ ?></div>
    <textarea name="content" id="content" hidden></textarea>

    <label for="excerpt">Excerpt <span class="hint">Short summary shown on the blog list. Leave empty to create it automatically.</span></label>
    <textarea id="excerpt" name="excerpt" rows="3" maxlength="400"><?= e($post['excerpt']) ?></textarea>
  </div>

  <aside class="editor-side">
    <div class="panel">
      <h2>Publish</h2>
      <p class="status-line">Status: <span class="badge badge-<?= e($post['status']) ?>"><?= e(ucfirst($post['status'])) ?></span></p>
      <label for="published_at">Publish date <span class="hint">Set a future date to schedule.</span></label>
      <input type="datetime-local" id="published_at" name="published_at" value="<?= e($local) ?>">
      <div class="btn-row">
        <button type="submit" name="action" value="draft" class="btn-ghost">Save draft</button>
        <button type="submit" name="action" value="publish" class="btn-primary"><?= $post['status'] === 'published' ? 'Update' : 'Publish' ?></button>
      </div>
    </div>
    <div class="panel">
      <h2>Details</h2>
      <label for="category">Category</label>
      <select id="category" name="category"><?php foreach (config('categories') as $c): ?><option <?= $post['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select>
      <label for="author">Author</label>
      <input type="text" id="author" name="author" value="<?= e($post['author']) ?>" maxlength="120">
    </div>
    <div class="panel">
      <h2>Cover image</h2>
      <?php if ($post['cover_image']): ?>
        <img class="cover-preview" src="<?= e(upload_url($post['cover_image'])) ?>" alt="Current cover image">
        <label class="check"><input type="checkbox" name="remove_cover" value="1"> Remove cover image</label>
      <?php endif; ?>
      <input type="file" name="cover" accept="image/jpeg,image/png,image/webp,image/gif">
      <p class="hint">JPG, PNG, WebP or GIF, up to 4 MB. Recommended 1600×900.</p>
    </div>
  </aside>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>window.MC = { csrf: <?= json_encode(csrf_token()) ?>, uploadUrl: <?= json_encode(url('admin/upload-image.php')) ?> };</script>
<script src="<?= e(url('assets/js/admin.js')) ?>"></script>
<?php admin_footer();
