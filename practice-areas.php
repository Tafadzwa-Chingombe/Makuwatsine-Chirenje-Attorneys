<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$active = 'practice';
$page_title = 'Practice Areas';
$meta_desc = 'Litigation, conveyancing, notarial services, estates, labour, corporate, commercial and mining law, debt collection and patents & trademarks in Harare.';
require __DIR__ . '/includes/header.php';
page_hero('Practice areas', 'Comprehensive legal services under one roof.', 'From litigation and conveyancing to estates, labour, mining and commercial law — practical advice for individuals and businesses.');
?>
<section class="section alt">
  <div class="container">
    <div class="grid grid-3">
      <?php foreach (practice_areas() as [$t, $d]) area_card($t, $d); ?>
    </div>
  </div>
</section>
<?php
cta_banner('Not sure where to start?', 'Tell us about your matter and we will guide you.');
require __DIR__ . '/includes/footer.php';
