<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';

$active = 'contact';
$page_title = 'Contact Us';
$meta_desc = 'Contact Makuwatsine Chirenje Attorneys at 6 Londonderry Avenue, Eastlea, Harare. Call, email or send us a message.';

$areas = array_column(practice_areas(), 0);
$old = ['name' => '', 'phone' => '', 'email' => '', 'area' => '', 'message' => ''];
$errors = [];
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($old as $k => $_) {
        $old[$k] = trim((string) ($_POST[$k] ?? ''));
    }
    $honeypot = trim((string) ($_POST['website'] ?? ''));

    if ($honeypot !== '') {
        $sent = true; // silently drop bots
    } else {
        if ($old['name'] === '' || mb_strlen($old['name']) > 120) { $errors[] = 'Please enter your full name.'; }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Please enter a valid email address.'; }
        if (mb_strlen($old['phone']) > 60) { $errors[] = 'Phone number is too long.'; }
        if ($old['area'] !== '' && !in_array($old['area'], $areas, true)) { $old['area'] = ''; }
        if (mb_strlen($old['message']) < 10) { $errors[] = 'Please tell us a little about your matter (at least 10 characters).'; }
        if (mb_strlen($old['message']) > 5000) { $errors[] = 'Your message is too long (maximum 5,000 characters).'; }

        // Throttle: max 5 enquiries per hour per session.
        start_session();
        $_SESSION['enq'] = array_filter($_SESSION['enq'] ?? [], fn($t) => $t > time() - 3600);
        if (count($_SESSION['enq']) >= 5) { $errors[] = 'Too many messages sent. Please try again later or call us.'; }

        if (!$errors) {
            $ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
            if ($pdo = db()) {
                try {
                    $pdo->prepare('INSERT INTO enquiries (name, phone, email, practice_area, message, ip) VALUES (?,?,?,?,?,?)')
                        ->execute([$old['name'], $old['phone'], $old['email'], $old['area'], $old['message'], $ip]);
                } catch (Throwable $ex) {
                    error_log('Enquiry save failed: ' . $ex->getMessage());
                }
            }
            // Email via the cPanel mail server (Exim). Header fields are stripped of line breaks to prevent injection.
            $clean = fn($s) => str_replace(["\r", "\n"], ' ', $s);
            $body = "New website enquiry\n\nName: {$old['name']}\nPhone: {$old['phone']}\nEmail: {$old['email']}\nPractice area: {$old['area']}\n\nMessage:\n{$old['message']}\n";
            $headers = 'From: ' . $clean(FIRM['short']) . ' Website <' . $clean((string) config('mail_from')) . ">\r\n"
                . 'Reply-To: ' . $clean($old['email']) . "\r\n"
                . "Content-Type: text/plain; charset=UTF-8\r\n";
            @mail((string) config('mail_to'), 'Website enquiry from ' . $clean($old['name']), $body, $headers);

            $_SESSION['enq'][] = time();
            $sent = true;
            $old = array_fill_keys(array_keys($old), '');
        }
    }
}

require __DIR__ . '/includes/header.php';
page_hero('Contact us', 'Let’s talk about your legal matter.', 'Call, email or visit our offices in Eastlea, Harare — or send a message and we will get back to you.', 'assets/img/about-handshake.jpg');
?>
<section class="section">
  <div class="container contact-grid">
    <div>
      <p class="eyebrow">Our details</p>
      <h2>Get in touch with MC Attorneys.</h2>
      <dl>
        <div class="detail"><span class="icon-dot" aria-hidden="true">◆</span><div><dt>Address</dt><dd><?= e(implode(', ', FIRM['address'])) ?></dd></div></div>
        <div class="detail"><span class="icon-dot" aria-hidden="true">◆</span><div><dt>Phone</dt><dd><?php foreach (FIRM['phones'] as $ph): ?><a href="tel:+263<?= e(substr(preg_replace('/\D/', '', $ph), 1)) ?>"><?= e($ph) ?></a><br><?php endforeach; ?></dd></div></div>
        <div class="detail"><span class="icon-dot" aria-hidden="true">◆</span><div><dt>Email</dt><dd><a href="mailto:<?= e(FIRM['email']) ?>"><?= e(FIRM['email']) ?></a></dd></div></div>
        <div class="detail"><span class="icon-dot" aria-hidden="true">◆</span><div><dt>Bankers</dt><dd><?= e(FIRM['banker']) ?></dd></div></div>
      </dl>
      <!-- MAP PLACEHOLDER: replace with a Google Maps embed <iframe> for 6 Londonderry Avenue, Eastlea, Harare -->
      <div class="img-ph wide" role="img" aria-label="Map placeholder"><span>MAP PLACEHOLDER — GOOGLE MAPS EMBED</span></div>
    </div>

    <form class="contact-form" method="post" action="<?= e(url('contact.php')) ?>#form" id="form" novalidate>
      <h2>Send us a message</h2>
      <?php if ($sent): ?>
        <div class="alert alert-success" role="status">Thank you — your message has been sent. We will get back to you shortly.</div>
      <?php endif; ?>
      <?php if ($errors): ?>
        <div class="alert alert-error" role="alert"><?php foreach ($errors as $er): ?><?= e($er) ?><br><?php endforeach; ?></div>
      <?php endif; ?>
      <?= csrf_field() ?>
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="field-row">
        <div class="field"><label for="name">Full name</label><input type="text" id="name" name="name" value="<?= e($old['name']) ?>" required maxlength="120" autocomplete="name"></div>
        <div class="field"><label for="phone">Phone number</label><input type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" maxlength="60" autocomplete="tel"></div>
      </div>
      <div class="field"><label for="email">Email address</label><input type="email" id="email" name="email" value="<?= e($old['email']) ?>" required maxlength="190" autocomplete="email"></div>
      <div class="field"><label for="area">Practice area</label>
        <select id="area" name="area"><option value="">Select a practice area</option>
          <?php foreach ($areas as $a): ?><option <?= $old['area'] === $a ? 'selected' : '' ?>><?= e($a) ?></option><?php endforeach; ?>
        </select></div>
      <div class="field"><label for="message">Your message</label><textarea id="message" name="message" required maxlength="5000"><?= e($old['message']) ?></textarea></div>
      <button class="btn btn-primary" type="submit">Book a Consultation</button>
    </form>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php';
