</main>
<footer class="site-footer">
  <div class="container">
    <div class="footer-cols">
      <div class="footer-about">
        <a class="logo" href="<?= e(url('index.php')) ?>">
          <img src="<?= e(url('assets/img/logo.png')) ?>" alt="<?= e(FIRM['name']) ?>" width="205" height="44">
        </a>
        <p>Legal practitioners, notaries, conveyancers, executors and commercial lawyers serving clients across Zimbabwe.</p>
      </div>
      <div>
        <h3>Firm</h3>
        <ul>
          <li><a href="<?= e(url('about.php')) ?>">About Us</a></li>
          <li><a href="<?= e(url('team.php')) ?>">Our Team</a></li>
          <li><a href="<?= e(url('blog.php')) ?>">Blog</a></li>
          <li><a href="<?= e(url('contact.php')) ?>">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3>Practice</h3>
        <ul>
          <li><a href="<?= e(url('practice-areas.php')) ?>">Conveyancing</a></li>
          <li><a href="<?= e(url('practice-areas.php')) ?>">Litigation</a></li>
          <li><a href="<?= e(url('practice-areas.php')) ?>">Estates</a></li>
          <li><a href="<?= e(url('practice-areas.php')) ?>">Commercial Law</a></li>
        </ul>
      </div>
      <div>
        <h3>Contact</h3>
        <ul class="footer-contact">
          <li><?= e(implode(', ', FIRM['address'])) ?></li>
          <li><a href="tel:+263<?= e(substr(preg_replace('/\D/', '', FIRM['phones'][0]), 1)) ?>"><?= e(FIRM['phones'][0]) ?></a> / <?= e(FIRM['phones'][1]) ?></li>
          <li><?= e(FIRM['phones'][2]) ?> / <?= e(FIRM['phones'][3]) ?></li>
          <li><a href="mailto:<?= e(FIRM['email']) ?>"><?= e(FIRM['email']) ?></a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© <?= date('Y') ?> Makuwatsine Chirenje Attorneys (MC Attorneys). Registered with the Law Society of Zimbabwe.</p>
    </div>
  </div>
</footer>
<script src="<?= e(url('assets/js/main.js')) ?>" defer></script>
</body>
</html>
