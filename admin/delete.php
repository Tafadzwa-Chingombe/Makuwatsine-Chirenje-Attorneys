<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect(url('admin/index.php')); }
csrf_check();
$pdo = db();
$id = (int) ($_POST['id'] ?? 0);
if ($pdo && $id) {
    $st = $pdo->prepare('SELECT cover_image FROM posts WHERE id = ?');
    $st->execute([$id]);
    if ($row = $st->fetch()) {
        $pdo->prepare('DELETE FROM posts WHERE id = ?')->execute([$id]);
        if ($row['cover_image']) {
            $f = ROOT_DIR . '/uploads/' . basename($row['cover_image']);
            if (is_file($f)) { @unlink($f); }
        }
        flash('Article deleted.');
    }
}
redirect(url('admin/index.php'));
