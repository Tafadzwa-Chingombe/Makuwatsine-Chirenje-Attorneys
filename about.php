<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/partials.php';
$active = 'about';
$page_title = 'About Us';
$meta_desc = 'Makuwatsine Chirenje Attorneys was founded in April 2025 in Harare by Tendai Makuwatsine and Liberty Chirenje. Learn about our story, mission, vision and values.';
require __DIR__ . '/includes/header.php';
page_hero('About us', 'A new firm with decades of experience.', 'Makuwatsine Chirenje Attorneys (MC Attorneys) is a registered law firm in terms of the Legal Practitioners Act, formed in April 2025.', 'assets/img/about-hero.jpg');
?>
<section class="section">
  <div class="container split story-split">
    <div>
      <p class="eyebrow">Our story</p>
      <h2>Formed to deliver excellent legal services.</h2>
      <p><?= e('Makuwatsine Chirenje Attorneys is a modern law firm registered with the Law society of Zimbabwe formed in April 2025. We believe that exceptional legal service begins with understanding people, protecting their interests and finding practical solutions to complex challenges.') ?></p>
      <p><?= e('Built on a foundation of integrity, professionalism and collaboration, our firm brings together legal expertise and a forward-thinking approach to serving individuals, businesses and institutions. We understand that every legal matter carries its own challenges and opportunities, which is why we approach each instruction with care, precision and a clear focus on our clients\' objectives.') ?></p>
      <p><?= e('Our practice is guided by a commitment to excellence, strategic thinking and the highest standards of professional conduct. We combine a deep respect for the law with a modern, solutions-driven mindset, enabling us to navigate complex legal issues while building lasting relationships founded on trust.') ?></p>
      <p><?= e('At the heart of our firm is the belief that the law should be more than a profession; it should be a means of protecting rights, creating opportunities and making a meaningful difference.') ?></p>
      <p><?= e('As we continue to grow, our commitment remains unchanged: to deliver thoughtful legal solutions, uphold the principles of justice and provide representation our clients can trust.') ?></p>
    </div>
    <img class="about-photo story-photo" src="<?= e(url('assets/img/about-story.jpg')) ?>" alt="A dimly lit boardroom with a long conference table" width="487" height="488" loading="lazy">
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
