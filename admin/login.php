<?php
require_once __DIR__ . '/../includes/admin_layout.php';
start_session();
if (current_user()) {
    redirect(url('admin/index.php'));
}

$error = '';
$pdo = db();
$ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!$pdo) {
        $error = 'The database is not configured. Run /install/install.php first.';
    } else {
        $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)')->execute();
        $st = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > (NOW() - INTERVAL 15 MINUTE)');
        $st->execute([$ip]);
        if ((int) $st->fetchColumn() >= 5) {
            $error = 'Too many failed attempts. Please wait 15 minutes and try again.';
        } else {
            $email = strtolower(trim((string) ($_POST['email'] ?? '')));
            $st = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $st->execute([$email]);
            $u = $st->fetch();
            // Always run a hash check to keep timing similar for unknown emails.
            $hash = $u['password_hash'] ?? '$2y$10$abcdefghijklmnopqrstuuJ1Y8x8x8x8x8x8x8x8x8x8x8x8x8x8';
            $ok = password_verify((string) ($_POST['password'] ?? ''), $hash);
            if ($u && $ok) {
                session_regenerate_id(true);
                $_SESSION['user'] = ['id' => (int) $u['id'], 'name' => $u['name'], 'email' => $u['email']];
                $pdo->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
                redirect(url('admin/index.php'));
            }
            $pdo->prepare('INSERT INTO login_attempts (ip) VALUES (?)')->execute([$ip]);
            $error = 'Incorrect email or password.';
        }
    }
}

admin_header('Log in');
?>
<div class="login-wrap">
  <form class="login-card" method="post" autocomplete="on">
    <div class="login-logo"><img src="<?= e(url('assets/img/logo-large.png')) ?>" alt="Makuwatsine Chirenje Attorneys"></div>
    <h1>Content Manager</h1>
    <p class="sub">Makuwatsine Chirenje Attorneys</p>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required autofocus autocomplete="username">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">
    <button class="btn-primary" type="submit">Log in</button>
  </form>
</div>
<?php admin_footer();
