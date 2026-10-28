<?php
/**
 * Builds a STATIC design preview of the site (for GitHub Pages or any static host).
 * It renders the real PHP pages to .html and fills the blog with sample articles.
 * The CMS and contact form are NOT part of the preview — they need PHP + MySQL (cPanel).
 *
 * Usage:  php tools/build-static.php <output-dir> [base-path]
 * e.g.    php tools/build-static.php /tmp/site /Makuwatsine-Chirenje-Attorneys
 */
if (PHP_SAPI !== 'cli') { exit('CLI only.'); }

$out  = rtrim($argv[1] ?? '', '/');
$base = rtrim($argv[2] ?? '', '/');
if ($out === '') { fwrite(STDERR, "Usage: php tools/build-static.php <output-dir> [base-path]\n"); exit(1); }

define('STATIC_CONFIG', ['base_path' => $base, 'site_url' => 'https://tafadzwa-chingombe.github.io' . $base]);
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$root = dirname(__DIR__);
chdir($root);
require_once $root . '/includes/functions.php';
require_once $root . '/includes/data.php';
require_once $root . '/includes/partials.php';

/* ---------- Sample articles (design preview only) ---------- */
$samples = [
    ['what-to-know-before-transferring-property', 'Conveyancing', 'What to know before transferring property in Zimbabwe', 'Tendai Makuwatsine', '2025-10-12',
     'A plain-English overview of the steps involved when property changes hands, and why professional conveyancing support matters.',
     '<p>Buying or selling property is one of the biggest decisions most people make. This sample article shows how a published post looks on the MC Attorneys blog.</p><h2>Why a conveyancer matters</h2><p>A conveyancer checks title, prepares the transfer documents and makes sure every step is completed in the right order.</p><ul><li>Verify ownership and title conditions</li><li>Prepare and review the agreement of sale</li><li>Lodge and follow up the transfer</li></ul><blockquote>Always seek professional advice before you sign.</blockquote><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['administering-a-deceased-estate', 'Estates', 'Administering a deceased estate: first steps for families', 'Liberty Chirenje', '2025-10-05',
     'An introduction to what families usually need to do first when a loved one passes away.',
     '<p>Losing a loved one is difficult, and the administrative steps that follow can feel overwhelming. This is sample content showing the article layout.</p><h2>Where to start</h2><p>Gather the important documents, locate any will, and speak to an executor or legal practitioner about next steps.</p><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['employer-duties-when-ending-employment', 'Labour Law', 'Employer duties when terminating employment', 'Taurayi Cyril Chirinda', '2025-09-28',
     'A short guide to fair process for employers considering termination.',
     '<p>Fair process protects both employers and employees. This placeholder article demonstrates headings, lists and links.</p><h2>Good practice</h2><ul><li>Follow the applicable code or contract</li><li>Document each step</li><li>Take advice early</li></ul><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['choosing-a-business-structure', 'Commercial', 'Choosing the right business structure for your venture', 'Tendai Makuwatsine', '2025-09-20',
     'Sole trader, partnership or private company? A starting point for new business owners.',
     '<p>The structure you choose affects liability, tax and how you raise money. This is sample copy for the preview.</p><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['your-rights-when-arrested', 'Litigation', 'Your rights when arrested: a short guide', 'Liberty Chirenje', '2025-09-12',
     'What to do, and what to ask for, if you or someone you know is arrested.',
     '<p>Knowing your rights helps you stay calm and protected. This is sample copy for the preview.</p><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['understanding-mining-title', 'Mining Law', 'Understanding mining title and claim registration', 'Manasa Masiiwa', '2025-09-03',
     'An overview of why clear mining title matters before investing in a claim.',
     '<p>Mining ventures depend on secure title. This is sample copy for the preview.</p><p><em>Sample content for the design preview — not legal advice.</em></p>'],
    ['mc-attorneys-opens-in-eastlea', 'News', 'MC Attorneys opens its doors in Eastlea, Harare', 'MC Attorneys', '2025-04-01',
     'Makuwatsine Chirenje Attorneys begins practice at 6 Londonderry Avenue, Eastlea.',
     '<p>Makuwatsine Chirenje Attorneys was established on 1 April 2025 by Tendai Makuwatsine and Liberty Chirenje, joined by Taurayi Cyril Chirinda and Manasa Masiiwa.</p><p>The firm offers litigation, conveyancing, estates, labour, commercial and mining law services from its offices in Eastlea, Harare.</p>'],
];
$posts = array_map(fn($s) => [
    'slug' => $s[0], 'category' => $s[1], 'title' => $s[2], 'author' => $s[3], 'published_at' => $s[4] . ' 09:00:00', 'created_at' => $s[4] . ' 09:00:00',
    'excerpt' => $s[5], 'content' => $s[6], 'cover_image' => null, 'status' => 'published',
], $samples);

/* ---------- Rendering helpers ---------- */
function render_file(string $file): string
{
    ob_start();
    include $file;
    return ob_get_clean();
}

function finish(string $html, string $base, string $depthPrefix = ''): string
{
    // PHP page links -> .html (drop query strings)
    $html = preg_replace('#(href=")([^"]*?)\b(index|about|practice-areas|team|blog|contact)\.php(?:\?[^"]*)?"#', '$1$2$3.html"', $html);
    // Article links /blog/<slug> -> /blog/<slug>.html
    $html = preg_replace('#(href="[^"]*/blog/[a-z0-9-]+)"#', '$1.html"', $html);
    // Contact form posts nowhere in the static preview (handled by JS)
    $html = preg_replace('~action="[^"]*contact\.php#form"~', 'action="#form"', $html);
    // Remove CSRF tokens (no server in the preview)
    $html = preg_replace('#<input type="hidden" name="csrf"[^>]*>#', '', $html);
    // Preview ribbon
    $ribbon = '<div class="preview-ribbon">Design preview — static demo. The blog CMS, admin and contact form run on the cPanel (PHP + MySQL) build.</div>';
    $html = preg_replace('#(<body[^>]*>)#', '$1' . $ribbon, $html, 1);
    $html = str_replace('</head>', '<style>.preview-ribbon{background:#C99A2E;color:#07152A;text-align:center;font:600 13px/1.4 Inter,system-ui,sans-serif;padding:8px 12px}</style></head>', $html);
    return $html;
}

function write_page(string $out, string $rel, string $html): void
{
    $path = $out . '/' . $rel;
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0755, true); }
    file_put_contents($path, $html);
}

/* ---------- Clean + copy assets ---------- */
if (is_dir($out)) { exec('rm -rf ' . escapeshellarg($out)); }
mkdir($out . '/assets', 0755, true);
exec('cp -r ' . escapeshellarg($root . '/assets/css') . ' ' . escapeshellarg($root . '/assets/js') . ' ' . escapeshellarg($out . '/assets/'));
if (is_dir($root . '/assets/img')) { exec('cp -r ' . escapeshellarg($root . '/assets/img') . ' ' . escapeshellarg($out . '/assets/')); }
@unlink($out . '/assets/css/admin.css');
@unlink($out . '/assets/js/admin.js');
touch($out . '/.nojekyll');

/* ---------- Static pages (rendered from the real PHP files) ---------- */
$contactNote = <<<'JS'
<script>
document.addEventListener('DOMContentLoaded', function () {
  var f = document.getElementById('form');
  if (!f) return;
  f.addEventListener('submit', function (e) {
    e.preventDefault();
    var n = document.createElement('div');
    n.className = 'alert alert-success';
    n.setAttribute('role', 'status');
    n.textContent = 'Preview only — on the live site this message is emailed to the firm and saved in the CMS inbox.';
    var old = f.querySelector('.alert'); if (old) old.remove();
    f.insertBefore(n, f.children[1]);
  });
});
</script>
JS;

foreach (['index', 'about', 'practice-areas', 'team', 'contact'] as $p) {
    $html = finish(render_file($root . '/' . $p . '.php'), $base);
    if ($p === 'contact') { $html = str_replace('</body>', $contactNote . '</body>', $html); }
    write_page($out, $p . '.html', $html);
}

/* ---------- Blog list (sample posts, client-side category filter) ---------- */
$filterJs = <<<'JS'
<script>
document.addEventListener('DOMContentLoaded', function () {
  var chips = document.querySelectorAll('.chip[data-cat]');
  var items = document.querySelectorAll('[data-post-cat]');
  chips.forEach(function (c) {
    c.addEventListener('click', function (e) {
      e.preventDefault();
      chips.forEach(function (x) { x.classList.toggle('is-active', x === c); });
      var cat = c.getAttribute('data-cat');
      var shown = 0;
      items.forEach(function (it) {
        var ok = cat === '' || it.getAttribute('data-post-cat') === cat;
        it.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });
      document.getElementById('no-posts').style.display = shown ? 'none' : '';
    });
  });
});
</script>
JS;

ob_start();
$active = 'blog';
$page_title = 'Blog';
$meta_desc = 'Legal insights and news from Makuwatsine Chirenje Attorneys (sample articles in this preview).';
require $root . '/includes/header.php';
page_hero('Legal insights', 'News and articles from MC Attorneys.', 'Practical guidance on property, estates, labour and commercial law.', 'assets/img/about-story.jpg');
$featured = $posts[0];
$rest = array_slice($posts, 1);
?>
<section class="section">
  <div class="container">
    <nav class="chips" aria-label="Filter by category">
      <a class="chip is-active" data-cat="" href="#">All</a>
      <?php foreach (config('categories') as $c): ?>
        <a class="chip" data-cat="<?= e($c) ?>" href="#"><?= e($c) ?></a>
      <?php endforeach; ?>
    </nav>
    <article class="featured" data-post-cat="<?= e($featured['category']) ?>">
      <a href="<?= e(post_url($featured['slug'])) ?>" tabindex="-1" aria-hidden="true"><?php img_placeholder('Featured article image'); ?></a>
      <div class="featured-body">
        <p class="eyebrow">Featured · <?= e($featured['category']) ?></p>
        <h2><a href="<?= e(post_url($featured['slug'])) ?>"><?= e($featured['title']) ?></a></h2>
        <p><?= e($featured['excerpt']) ?></p>
        <p class="meta"><?= e(format_date($featured['published_at'])) ?> · <?= reading_minutes($featured['content']) ?> min read · <?= e($featured['author']) ?></p>
        <p><a class="read-more" href="<?= e(post_url($featured['slug'])) ?>">Read article →</a></p>
      </div>
    </article>
    <div class="posts">
      <?php foreach ($rest as $p): ?>
        <div data-post-cat="<?= e($p['category']) ?>"><?php post_card($p); ?></div>
      <?php endforeach; ?>
    </div>
    <div class="empty-state" id="no-posts" style="display:none"><h3>No articles in this category yet</h3><p>New articles will appear here once published.</p></div>
  </div>
</section>
<?php
require $root . '/includes/footer.php';
$html = finish(ob_get_clean(), $base);
write_page($out, 'blog.html', str_replace('</body>', $filterJs . '</body>', $html));

/* ---------- Article pages ---------- */
foreach ($posts as $post) {
    ob_start();
    $active = 'blog';
    $page_title = $post['title'];
    $meta_desc = $post['excerpt'];
    require $root . '/includes/header.php';
    ?>
<section class="section">
  <div class="container">
    <article class="article">
      <a class="back-link" href="<?= e(url('blog.php')) ?>">← All articles</a>
      <header class="article-head">
        <p class="eyebrow"><?= e($post['category']) ?></p>
        <h1><?= e($post['title']) ?></h1>
        <p class="meta"><?= e(format_date($post['published_at'])) ?> · <?= reading_minutes($post['content']) ?> min read · By <?= e($post['author']) ?></p>
      </header>
      <div class="article-cover"><?php img_placeholder('Cover image', 'wide'); ?></div>
      <div class="prose"><?= $post['content'] ?></div>
    </article>
  </div>
</section>
    <?php
    cta_banner('Need advice?', 'Speak to MC Attorneys about your matter.');
    require $root . '/includes/footer.php';
    write_page($out, 'blog/' . $post['slug'] . '.html', finish(ob_get_clean(), $base));
}

/* ---------- 404 ---------- */
ob_start();
$active = '';
$page_title = 'Page not found';
require $root . '/includes/header.php';
echo '<section class="section"><div class="container article"><h1>Page not found</h1><p><a class="btn btn-dark" href="' . e(url('index.php')) . '">Back to home</a></p></div></section>';
require $root . '/includes/footer.php';
write_page($out, '404.html', finish(ob_get_clean(), $base));

echo "Built " . count(glob($out . '/*.html')) . " pages + " . count($posts) . " articles into $out (base: '" . $base . "')\n";
