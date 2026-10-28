<?php
$active = 'home';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$page_title = FIRM['name'];
require __DIR__ . '/includes/header.php';
?>
<section class="hero" style="background-image:url(<?= e(url('assets/img/hero-courtroom.jpg')) ?>)">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Harare · Established 2025</p>
      <h1>Trusted legal counsel, built on integrity.</h1>
      <p class="lead">Makuwatsine Chirenje Attorneys provides diligent, transparent legal services in litigation, conveyancing, estates, labour and commercial law.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= e(url('contact.php')) ?>">Book a Consultation</a>
        <a class="btn btn-outline" href="<?= e(url('practice-areas.php')) ?>">Our Practice Areas</a>
      </div>
    </div>
    <img class="hero-photo" src="<?= e(url('assets/img/hero-justice.jpg')) ?>" alt="Statue of Lady Justice holding the scales" width="736" height="385">
  </div>
</section>

<section class="stats" aria-label="Firm at a glance">
  <div class="container stats-grid">
    <div class="stat"><strong>7</strong><span>Practising lawyers</span></div>
    <div class="stat"><strong>12</strong><span>Areas of legal practice</span></div>
    <div class="stat"><strong>16+</strong><span>Years of legal experience</span></div>
    <div class="stat"><strong>LSZ</strong><span>Registered with the Law Society</span></div>
  </div>
</section>

<section class="section">
  <div class="container split about-split">
    <img class="about-photo" src="<?= e(url('assets/img/about-handshake.jpg')) ?>" alt="A handshake across a desk over a signed document" width="417" height="626" loading="lazy">
    <div>
      <p class="eyebrow">About the firm</p>
      <h2>Excellence in service, with clients at the centre.</h2>
      <p>Founded in April 2025 by Tendai Makuwatsine and Liberty Chirenje, MC Attorneys brings together lawyers from established Harare practices to offer reliable, comprehensive legal services to individuals and corporate organisations.</p>
      <div class="pills"><span class="pill">Diligence</span><span class="pill">Integrity</span><span class="pill">Accountability</span><span class="pill">Transparency</span></div>
      <a class="btn btn-outline-dark" href="<?= e(url('about.php')) ?>">Read More</a>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Practice areas</p>
      <h2>Comprehensive legal services under one roof.</h2>
    </div>
    <div class="grid grid-3">
      <?php foreach (home_areas() as [$t, $d]) area_card($t, $d, true); ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Our team</p>
      <h2>Experienced advocates you can rely on.</h2>
    </div>
    <div class="grid grid-4 team-grid">
      <?php foreach (array_slice(team(), 0, 4) as $m): ?>
        <div class="member">
          <?php team_photo($m, 'member-photo'); ?>
          <h3><?= e($m['name']) ?></h3>
          <p><?= e($m['role']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
cta_banner('Get in touch', 'Need legal advice? Speak to MC Attorneys today.', implode(', ', FIRM['address']) . ' · ' . FIRM['phones'][0]);
require __DIR__ . '/includes/footer.php';
