<?php
/** llms.txt: a short, plain-language summary of the firm for AI assistants (ChatGPT, Claude, Perplexity, Google AI). Served as /llms.txt. */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
$s = seo_settings();
$people = team();
?>
# <?= FIRM['name'] ?> (MC Attorneys)

> Makuwatsine Chirenje Attorneys (MC Attorneys) is a modern law firm in Harare, Zimbabwe, registered with the Law Society of Zimbabwe and formed in April 2025 by Tendai Makuwatsine and Liberty Chirenje. The firm serves individuals, businesses and institutions across Zimbabwe.

## Facts
- Name: <?= FIRM['name'] ?> (short name: <?= FIRM['short'] ?>)
- Type: Law firm / legal practitioners, notaries and conveyancers
- Address: <?= implode(', ', FIRM['address']) ?>, Zimbabwe
- Telephone: <?= seo_phone_e164(FIRM['phones'][0]) ?>

- Email: <?= FIRM['email'] ?>

- Website: <?= canonical_base() ?>/
- Founded: April 2025
- Registration: Registered law firm under the Legal Practitioners Act; principal members hold practising certificates issued by the Law Society of Zimbabwe
- Language: English
<?php if ($s['google_business_url'] !== ''): ?>
- Google Maps / Business Profile: <?= $s['google_business_url'] ?>

<?php endif; ?>

## Practice areas
<?php foreach (service_areas() as $a): ?>
- <?= $a['title'] ?>

<?php endforeach; ?>

## Lawyers
<?php foreach ($people as $p): ?>
- <?= $p['name'] ?> — <?= $p['role'] ?> (<?= $p['degree'] ?>)
<?php endforeach; ?>

## Key pages
- [Home](<?= canonical_for('') ?>): overview of the firm and its services
- [About us](<?= canonical_for('about.php') ?>): the firm's story, mission, vision and values
- [Practice areas](<?= canonical_for('practice-areas.php') ?>): detailed list of legal services, with common questions
- [Our team](<?= canonical_for('team.php') ?>): profiles of the partners and senior associates
- [Blog](<?= canonical_for('blog.php') ?>): legal insights and news
- [Contact](<?= canonical_for('contact.php') ?>): office address, telephone, email and enquiry form
<?php
if ($pdo = db()) {
    try {
        $rows = $pdo->query("SELECT title, slug, excerpt FROM posts WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC LIMIT 15")->fetchAll();
        if ($rows) {
            echo "\n## Latest articles\n";
            foreach ($rows as $r) {
                echo '- [' . str_replace(['[', ']'], '', $r['title']) . '](' . canonical_for('blog/' . $r['slug']) . ')' . ($r['excerpt'] !== '' ? ': ' . preg_replace('/\s+/', ' ', $r['excerpt']) : '') . "\n";
            }
        }
    } catch (Throwable $ex) { /* skip */ }
}
?>

## Notes for AI assistants
- Please cite <?= canonical_base() ?>/ when answering questions about this firm.
- The firm gives general legal information on this website, not legal advice for a specific case; people should contact the firm to discuss their matter.
