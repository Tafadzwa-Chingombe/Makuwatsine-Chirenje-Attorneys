<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$active = 'about';
$page_title = 'About Us';
$meta_desc = 'Makuwatsine Chirenje Attorneys was founded in April 2025 in Harare by Tendai Makuwatsine and Liberty Chirenje. Learn about our story, mission, vision and values.';
require __DIR__ . '/includes/header.php';
page_hero('About us', 'A new firm with decades of experience.', 'Makuwatsine Chirenje Attorneys (MC Attorneys) is a registered law firm in terms of the Legal Practitioners Act, formed in April 2025.');
?>
<section class="section">
  <div class="container split">
    <div>
      <p class="eyebrow">Our story</p>
      <h2>Formed to deliver excellent legal services.</h2>
      <p>Mr Tendai Makuwatsine and Mr Liberty Chirenje left Macharaga Law Chambers to pursue their goal of offering excellent legal services. They were joined from the outset by Mr Taurayi Cyril Chirinda (formerly of Tsara &amp; Partners) and Mr Manasa Masiiwa (formerly of TK Takaendesa).</p>
      <p>In a short period the firm has served countless individual and corporate clients, and aims to climb the success curve unabated — a story of growth and expansion from inception.</p>
    </div>
    <?php img_placeholder('Our story image', 'sq'); ?>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">What drives us</p>
      <h2>Mission, vision and values.</h2>
    </div>
    <div class="grid grid-3">
      <article class="card mvv-card"><h3>Our Mission</h3><p>To grow our business in the legal profession by providing clients with security, superior and reliable service through highly motivated and dedicated employees.</p></article>
      <article class="card mvv-card"><h3>Our Vision</h3><p>To become the market leader in the provision of comprehensive consulting and litigation services in the legal profession.</p></article>
      <article class="card mvv-card"><h3>Core Values</h3><p>Diligence · Integrity · Accountability · Transparency</p></article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split top">
    <div>
      <p class="eyebrow">Registration</p>
      <h2>Authorised by the Law Society of Zimbabwe.</h2>
      <p>All principal members hold valid practising certificates issued by the Law Society of Zimbabwe, authorising the firm to offer the following services to the public.</p>
    </div>
    <ul class="check-list">
      <?php foreach (['Criminal and civil legal services', 'Notarial services', 'Conveyancing', 'Executors and administrators of deceased estates', 'Labour law', 'Commercial law', 'Mining law', 'Patent and trademark agents'] as $s): ?>
        <li><?= e($s) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php
cta_banner('Get in touch', 'Work with a team that puts clients first.');
require __DIR__ . '/includes/footer.php';
