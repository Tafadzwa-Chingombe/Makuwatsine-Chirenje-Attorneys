<?php
require_once __DIR__ . '/../includes/admin_layout.php';
$user = require_login();
$pdo = db();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    csrf_check();
    $st = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
    $st->execute([$user['id']]);
    $hash = (string) $st->fetchColumn();
    $cur = (string) ($_POST['current'] ?? '');
    $new = (string) ($_POST['new'] ?? '');
    if (!password_verify($cur, $hash)) { $errors[] = 'Your current password is incorrect.'; }
    if (strlen($new) < 10) { $errors[] = 'The new password must be at least 10 characters.'; }
    if ($new !== (string) ($_POST['confirm'] ?? '')) { $errors[] = 'The new passwords do not match.'; }
    if (!$errors) {
        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
        session_regenerate_id(true);
        flash('Password updated.');
        redirect(url('admin/password.php'));
    }
}
admin_header('Change password', 'pw');
?>
<div class="page-head"><h1>Change password</h1></div>
<?php if ($errors): ?><div class="notice notice-error" role="alert"><?php foreach ($errors as $er): ?><?= e($er) ?><br><?php endforeach; ?></div><?php endif; ?>
<form method="post" class="panel narrow"><?= csrf_field() ?>
  <label for="current">Current password</label><input type="password" id="current" name="current" required autocomplete="current-password">
  <label for="new">New password <span class="hint">At least 10 characters.</span></label><input type="password" id="new" name="new" required minlength="10" autocomplete="new-password">
  <label for="confirm">Confirm new password</label><input type="password" id="confirm" name="confirm" required minlength="10" autocomplete="new-password">
  <button class="btn-primary" type="submit">Update password</button>
</form>
<?php admin_footer();
