<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$active = 'team';
$page_title = 'Our Team';
$meta_desc = 'Meet the partners and senior associates of Makuwatsine Chirenje Attorneys, Harare.';
require __DIR__ . '/includes/header.php';
page_hero('Our team', 'Lawyers from diverse backgrounds, one shared standard.', 'A managing partner, a founding partner and two senior associates, supported by professional assistants and dedicated staff.', 'assets/img/team-hero.jpg');
$people = team();
?>
<section class="section partners">
  <div class="container">
    <?php foreach (array_slice($people, 0, 2) as $p): ?>
      <article class="partner">
        <div class="partner-img"><?php team_photo($p); ?></div>
        <div>
          <p class="eyebrow"><?= e($p['role']) ?></p>
          <h2><?= e($p['name']) ?></h2>
          <p class="degree"><?= e($p['degree']) ?></p>
          <?php foreach (explode("\n", $p['bio']) as $para): ?><p><?= e($para) ?></p><?php endforeach; ?>
          <?php team_phones($p); ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Senior associates</p>
      <h2>Committed practitioners serving justice.</h2>
    </div>
    <div class="grid grid-2">
      <?php
      // Senior associates: longest profiles first, shortest last.
      $associates = array_slice($people, 2);
      usort($associates, fn($a, $b) => strlen($b['bio']) <=> strlen($a['bio']));
      foreach ($associates as $p): ?>
        <article class="card assoc<?= strlen($p['bio']) > 500 ? ' assoc-wide' : '' ?>">
          <?php team_photo($p); ?>
          <div>
            <h3><?= e($p['name']) ?></h3>
            <p class="role"><?= e($p['degree']) ?> — <?= e($p['role']) ?></p>
            <?php foreach (explode("\n", $p['bio']) as $para): ?><p><?= e($para) ?></p><?php endforeach; ?>
          <?php team_phones($p); ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
cta_banner('Meet us', 'Speak directly with the right lawyer for your matter.');
require __DIR__ . '/includes/footer.php';
