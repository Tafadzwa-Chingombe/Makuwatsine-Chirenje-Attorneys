<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$active = 'practice';
$page_title = 'Practice Areas';
$meta_desc = 'Conveyancing, notarial practice, litigation, arbitration, estates, labour, commercial and corporate, energy and mining, insurance, intellectual property, insolvency and tax law in Harare, Zimbabwe.';
require __DIR__ . '/includes/header.php';
?>
<section class="services-intro">
  <div class="container">
    <h1>Practice Areas</h1>
    <p>Makuwatsine Chirenje Attorneys offers a comprehensive range of legal services across Zimbabwe, combining deep local expertise with international standards of excellence.</p>
  </div>
</section>
<section class="section alt services-grid-section">
  <div class="container">
    <div class="services-grid">
      <?php
      // Grouped by content length so each row of cards is a similar height.
      $order = ['Energy and Mining Law', 'Business Rescue and Insolvency', 'Tax Advice and Litigation',
                'General Civil Litigation', 'Intellectual Property', 'Arbitration',
                'Conveyancing and Notarial Practice', 'Commercial and Corporate Law', 'Labour Law',
                'Criminal Law', 'Estate Planning, Wills and Successions', 'Insurance Law'];
      $areas = service_areas();
      usort($areas, fn($a, $b) => array_search($a['title'], $order) <=> array_search($b['title'], $order));
      foreach ($areas as $area): ?>
        <article class="card service-card">
          <div class="service-head">
            <span class="icon-dot" aria-hidden="true">◆</span>
            <h3><?= e($area['title']) ?></h3>
          </div>
          <?php if (!empty($area['intro'])): ?><p class="service-intro"><?= e($area['intro']) ?></p><?php endif; ?>
          <?php if (!empty($area['items'])): ?>
            <ul class="service-list">
              <?php foreach ($area['items'] as $item): ?>
                <?php if (is_array($item)): ?>
                  <li><?= e($item[0]) ?>
                    <ul class="service-list sub">
                      <?php foreach ($item[1] as $sub): ?><li><?= e($sub) ?></li><?php endforeach; ?>
                    </ul>
                  </li>
                <?php else: ?>
                  <li><?= e($item) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
cta_banner('Not sure where to start?', 'Tell us about your matter and we will guide you.');
require __DIR__ . '/includes/footer.php';
