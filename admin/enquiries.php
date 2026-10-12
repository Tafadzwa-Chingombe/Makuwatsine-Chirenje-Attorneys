<?php
require_once __DIR__ . '/../includes/admin_layout.php';
require_login();
$pdo = db();
if (!$pdo) { exit('Database not available.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) ($_POST['id'] ?? 0);
    $act = $_POST['do'] ?? '';
    if ($id && $act === 'read') { $pdo->prepare('UPDATE enquiries SET is_read = 1 WHERE id = ?')->execute([$id]); }
    if ($id && $act === 'delete') { $pdo->prepare('DELETE FROM enquiries WHERE id = ?')->execute([$id]); flash('Enquiry deleted.'); }
    redirect(url('admin/enquiries.php'));
}
$rows = $pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 200')->fetchAll();
admin_header('Enquiries', 'enq');
?>
<div class="page-head"><h1>Website enquiries</h1></div>
<?php if (!$rows): ?><div class="empty"><p>No enquiries yet. Messages sent from the contact form will appear here (and are emailed to the firm).</p></div><?php endif; ?>
<?php foreach ($rows as $r): ?>
  <article class="enq <?= $r['is_read'] ? '' : 'unread' ?>">
    <header>
      <strong><?= e($r['name']) ?></strong>
      <span><?= e(date('j M Y, H:i', strtotime($r['created_at']))) ?></span>
      <?php if (!$r['is_read']): ?><span class="badge badge-published">New</span><?php endif; ?>
    </header>
    <p class="enq-meta"><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a><?= $r['phone'] ? ' · ' . e($r['phone']) : '' ?><?= $r['practice_area'] ? ' · ' . e($r['practice_area']) : '' ?></p>
    <p><?= nl2br(e($r['message'])) ?></p>
    <form method="post" class="enq-actions"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
      <?php if (!$r['is_read']): ?><button name="do" value="read" class="btn-ghost">Mark as read</button><?php endif; ?>
      <button name="do" value="delete" class="link-danger" onclick="return confirm('Delete this enquiry?');">Delete</button>
    </form>
  </article>
<?php endforeach;
admin_footer();
