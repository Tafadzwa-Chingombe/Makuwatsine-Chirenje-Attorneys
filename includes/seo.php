<?php
/**
 * Search-engine (SEO) and AI-search (AIEO) helpers: canonical URLs, social cards and structured data (JSON-LD).
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/data.php';

function seo_settings(): array
{
    static $s = null;
    if ($s === null) {
        $f = __DIR__ . '/seo-settings.php';
        $s = is_file($f) ? (array) (require $f) : [];
        $s += ['opening_hours' => [], 'google_business_url' => '', 'same_as' => []];
    }
    return $s;
}

/** https://makuwatsinechirenjeattorneys.co.zw (no trailing slash) */
function canonical_base(): string
{
    return rtrim((string) config('canonical_url', config('site_url')), '/');
}

/** Full canonical address for a site path such as 'about.php' or 'blog/my-article'. */
function canonical_for(string $path = ''): string
{
    $path = ltrim($path, '/');
    if ($path === 'index.php') { $path = ''; }
    return canonical_base() . url($path);
}

/** Canonical address of the page being viewed (query strings dropped). */
function canonical_current(): string
{
    $p = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $base = rtrim((string) config('base_path'), '/');
    if ($base !== '' && strpos($p, $base) === 0) { $p = substr($p, strlen($base)); }
    $p = ltrim($p, '/');
    if ($p === 'index.php') { $p = ''; }
    return canonical_for($p);
}

function jsonld(array $data): string
{
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) . "</script>\n";
}

function seo_phone_e164(string $phone): string
{
    return '+' . preg_replace('/\D/', '', $phone);
}

/** The law firm as a machine-readable "business card" for Google and AI assistants. */
function seo_org_schema(): array
{
    $s = seo_settings();
    $base = canonical_base();
    $people = team();
    $org = [
        '@context' => 'https://schema.org',
        '@type' => ['LegalService', 'Attorney'],
        '@id' => $base . '/#organization',
        'name' => FIRM['name'],
        'alternateName' => FIRM['short'],
        'url' => $base . '/',
        'description' => 'Makuwatsine Chirenje Attorneys (MC Attorneys) is a modern Harare law firm, registered with the Law Society of Zimbabwe and formed in April 2025, serving individuals, businesses and institutions across Zimbabwe.',
        'logo' => ['@type' => 'ImageObject', 'url' => canonical_for('assets/img/favicon-512.png'), 'width' => 512, 'height' => 512],
        'image' => canonical_for('assets/img/og-image.jpg'),
        'telephone' => seo_phone_e164(FIRM['phones'][0]),
        'email' => FIRM['email'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => FIRM['address'][0] . ', ' . FIRM['address'][1],
            'addressLocality' => FIRM['address'][2],
            'addressCountry' => 'ZW',
        ],
        'areaServed' => ['@type' => 'Country', 'name' => 'Zimbabwe'],
        'foundingDate' => '2025-04',
        'knowsAbout' => array_map(fn($a) => $a['title'], service_areas()),
        'hasMap' => $s['google_business_url'] !== '' ? $s['google_business_url'] : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('6 Londonderry Avenue, Eastlea, Harare, Zimbabwe'),
        'contactPoint' => [['@type' => 'ContactPoint', 'contactType' => 'customer service', 'telephone' => seo_phone_e164(FIRM['phones'][0]), 'email' => FIRM['email'], 'areaServed' => 'ZW', 'availableLanguage' => 'English']],
        'founder' => array_map(fn($p) => ['@type' => 'Person', 'name' => $p['name'], 'jobTitle' => $p['role']], array_slice($people, 0, 2)),
        'employee' => array_map(fn($p) => ['@type' => 'Person', 'name' => $p['name'], 'jobTitle' => $p['role']], array_slice($people, 2)),
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Legal services',
            'itemListElement' => array_map(fn($a) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $a['title'], 'provider' => ['@id' => $base . '/#organization']]], service_areas()),
        ],
    ];
    $same = array_values(array_filter(array_merge([$s['google_business_url']], (array) $s['same_as'])));
    if ($same) { $org['sameAs'] = $same; }
    $hours = [];
    foreach ((array) $s['opening_hours'] as $h) {
        if (!empty($h['days']) && !empty($h['opens']) && !empty($h['closes'])) {
            $hours[] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $h['days'], 'opens' => $h['opens'], 'closes' => $h['closes']];
        }
    }
    if ($hours) { $org['openingHoursSpecification'] = $hours; }
    return $org;
}

function seo_website_schema(): array
{
    $base = canonical_base();
    return [
        '@context' => 'https://schema.org', '@type' => 'WebSite', '@id' => $base . '/#website',
        'url' => $base . '/', 'name' => FIRM['name'], 'inLanguage' => 'en',
        'publisher' => ['@id' => $base . '/#organization'],
    ];
}

/** Breadcrumbs: list of [name, absolute url]. */
function seo_breadcrumb_schema(array $crumbs): array
{
    $i = 0;
    return [
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(function ($c) use (&$i) {
            return ['@type' => 'ListItem', 'position' => ++$i, 'name' => $c[0], 'item' => $c[1]];
        }, $crumbs),
    ];
}

/** Visible FAQ block plus matching FAQPage data. $items = [[question, answer], ...] (answers are plain text). */
function faq_section(array $items, string $heading = 'Frequently asked questions', string $eyebrow = 'FAQ'): void
{
    if (!$items) { return; }
    ?>
    <section class="section faq-section">
      <div class="container">
        <div class="section-head">
          <p class="eyebrow"><?= e($eyebrow) ?></p>
          <h2><?= e($heading) ?></h2>
        </div>
        <div class="faq-list">
          <?php foreach ($items as [$q, $a]): ?>
            <details class="faq-item">
              <summary><?= e($q) ?></summary>
              <p><?= e($a) ?></p>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?= jsonld([
        '@context' => 'https://schema.org', '@type' => 'FAQPage',
        'mainEntity' => array_map(fn($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], $items),
    ]) ?>
    <?php
}

/** Questions people (and AI assistants) ask about the firm; every answer comes from facts already on this site. */
function firm_faqs(string $set = 'firm'): array
{
    $areas = implode(', ', array_map(fn($a) => $a['title'], service_areas()));
    $addr = implode(', ', FIRM['address']);
    $firm = [
        ['Who is Makuwatsine Chirenje Attorneys?', 'Makuwatsine Chirenje Attorneys (MC Attorneys) is a modern law firm in Harare, Zimbabwe, registered with the Law Society of Zimbabwe and formed in April 2025 by Tendai Makuwatsine and Liberty Chirenje. The firm serves individuals, businesses and institutions.'],
        ['Where is the firm located?', 'The office is at ' . $addr . ', Zimbabwe. The landline is ' . FIRM['phones'][0] . ' and the email address is ' . FIRM['email'] . '.'],
        ['What areas of law does the firm practise?', 'The firm practises ' . $areas . '.'],
        ['How do I book a consultation?', 'Use the contact form on the Contact page, call ' . FIRM['phones'][0] . ', or email ' . FIRM['email'] . '. Tell us briefly about your matter and the right lawyer will respond.'],
    ];
    if ($set === 'firm') { return $firm; }
    return array_merge($firm, [
        ['Is the firm registered to practise in Zimbabwe?', 'Yes. Makuwatsine Chirenje Attorneys is a registered law firm in terms of the Legal Practitioners Act, and its principal members hold valid practising certificates issued by the Law Society of Zimbabwe.'],
        ['Does the firm handle property transfers and conveyancing?', 'Yes. Conveyancing is a core specialty: registration and transfers of fixed property, registration of mortgage bonds and notarial bonds, security registration, and advice on sectional title and share block schemes.'],
        ['Do you act for businesses as well as individuals?', 'Yes. The firm acts for individuals, private companies and institutions, including on commercial and corporate law, labour law, insolvency, intellectual property and energy and mining matters.'],
        ['Can the firm help with wills and deceased estates?', 'Yes. The firm advises on estate planning and drafts wills and trusts, and assists with the administration and winding up of deceased estates.'],
    ]);
}
