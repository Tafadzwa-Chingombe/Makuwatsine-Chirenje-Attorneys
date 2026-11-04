<?php
/** XML sitemap for Google and other search engines. Served as /sitemap.xml (see .htaccess). */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600');

$pages = [
    ['index.php', 1.0, 'weekly'], ['about.php', 0.8, 'monthly'], ['practice-areas.php', 0.9, 'monthly'],
    ['team.php', 0.8, 'monthly'], ['blog.php', 0.7, 'daily'], ['contact.php', 0.8, 'yearly'],
];
$urls = [];
foreach ($pages as [$file, $prio, $freq]) {
    $urls[] = [canonical_for($file), date('c', (int) @filemtime(__DIR__ . '/' . $file)), $freq, $prio];
}
if ($pdo = db()) {
    try {
        $rows = $pdo->query("SELECT slug, COALESCE(updated_at, published_at) AS m FROM posts WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC")->fetchAll();
        foreach ($rows as $r) {
            $urls[] = [canonical_for('blog/' . $r['slug']), date('c', strtotime($r['m'])), 'monthly', 0.6];
        }
    } catch (Throwable $ex) { /* pages only */ }
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$loc, $mod, $freq, $prio]) {
    echo '  <url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $mod . '</lastmod><changefreq>' . $freq . '</changefreq><priority>' . number_format($prio, 1) . "</priority></url>\n";
}
echo "</urlset>\n";
