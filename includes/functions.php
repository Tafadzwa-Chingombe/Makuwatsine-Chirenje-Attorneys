<?php
/**
 * Shared helpers: config, database, sessions, CSRF, sanitising, URLs.
 */

define('ROOT_DIR', dirname(__DIR__));
date_default_timezone_set('Africa/Harare'); // CAT, UTC+2, no DST

function config(?string $key = null, $default = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $defaults = (require __DIR__ . '/config.sample.php');
        $file = __DIR__ . '/config.php';
        $cfg = is_file($file) ? array_merge($defaults, (array) require $file) : $defaults;
        $cfg['_installed'] = is_file($file);
        if (defined('STATIC_CONFIG')) { // set by tools/build-static.php only
            $cfg = array_merge($cfg, STATIC_CONFIG);
        }
    }
    return $key === null ? $cfg : ($cfg[$key] ?? $default);
}

/** Blog categories: the preset list plus any custom category already used by an article (draft or published). */
function all_categories(?PDO $pdo = null): array
{
    $cats = (array) config('categories');
    $pdo = $pdo ?: db();
    if ($pdo) {
        try {
            foreach ($pdo->query("SELECT DISTINCT category FROM posts WHERE category <> '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN) as $c) {
                $exists = false;
                foreach ($cats as $k) { if (mb_strtolower($k) === mb_strtolower($c)) { $exists = true; break; } }
                if (!$exists) { $cats[] = $c; }
            }
        } catch (Throwable $ex) { /* fall back to presets */ }
    }
    return $cats;
}

/** tel: link for a phone number written as +263 …, 263 … or local 0… */
function tel_href(string $phone): string
{
    $d = preg_replace('/\D/', '', $phone);
    if (strpos($d, '2630') === 0) { $d = '263' . substr($d, 4); }
    elseif (strpos($d, '263') !== 0 && strpos($d, '0') === 0) { $d = '263' . substr($d, 1); }
    return 'tel:+' . $d;
}

function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* ---------- URLs ---------- */

function url(string $path = ''): string
{
    return rtrim((string) config('base_path'), '/') . '/' . ltrim($path, '/');
}

function post_url(string $slug): string
{
    return url('blog/' . rawurlencode($slug));
}

function upload_url(?string $file): string
{
    return $file ? url('uploads/' . rawurlencode($file)) : '';
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

/* ---------- Database ---------- */

function db(): ?PDO
{
    static $pdo = false;
    if ($pdo === false) {
        $pdo = null;
        if (config('_installed')) {
            try {
                $dsn = 'mysql:host=' . config('db_host') . ';dbname=' . config('db_name') . ';charset=utf8mb4';
                $pdo = new PDO($dsn, config('db_user'), config('db_pass'), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                // Keep MySQL NOW() aligned with PHP's Africa/Harare time (scheduled posts).
                $pdo->exec("SET time_zone = '+02:00'");
            } catch (Throwable $ex) {
                error_log('DB connection failed: ' . $ex->getMessage());
                $pdo = null;
            }
        }
    }
    return $pdo;
}

/* ---------- Sessions, flash, CSRF ---------- */

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('mc_session');
    session_start();
}

function flash(?string $msg = null, string $type = 'success')
{
    start_session();
    if ($msg !== null) {
        $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
        return null;
    }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    start_session();
    $sent = $_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(419);
        exit('Security token expired. Please go back, refresh the page and try again.');
    }
}

/* ---------- Text helpers ---------- */

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    if (function_exists('iconv')) {
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($t !== false) {
            $text = $t;
        }
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim((string) $text, '-');
    return $text !== '' ? substr($text, 0, 150) : 'post';
}

function reading_minutes(string $html): int
{
    $words = str_word_count(strip_tags($html));
    return max(1, (int) ceil($words / 200));
}

function make_excerpt(string $html, int $len = 170): string
{
    $spaced = preg_replace('#</(p|h[1-6]|li|blockquote|div|figcaption)>|<br\s*/?>#i', ' ', $html);
    $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($spaced), ENT_QUOTES, 'UTF-8')));
    return mb_strlen($text) > $len ? rtrim(mb_substr($text, 0, $len - 1)) . '…' : $text;
}

function format_date(?string $d): string
{
    return $d ? date('j F Y', strtotime($d)) : '';
}

/**
 * Allow-list HTML sanitiser for blog content produced by the rich-text editor.
 * Strips scripts, event handlers, inline styles and unknown tags/attributes.
 */
function sanitize_html(string $html): string
{
    $allowedTags = [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'h2' => [], 'h3' => [], 'h4' => [], 'blockquote' => [], 'ul' => [], 'ol' => [], 'li' => [],
        'a' => ['href', 'title', 'target', 'rel'], 'img' => ['src', 'alt'], 'hr' => [], 'pre' => [], 'code' => [],
        'figure' => [], 'figcaption' => [],
    ];
    if (trim($html) === '') {
        return '';
    }
    $doc = new DOMDocument();
    $prev = libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?><div id="mc-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    $root = $doc->getElementById('mc-root');
    if (!$root) {
        return '';
    }

    $clean = function (DOMNode $node) use (&$clean, $allowedTags, $doc) {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);
                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'svg', 'math', 'link', 'meta'], true)) {
                    $node->removeChild($child);
                    continue;
                }
                if (!isset($allowedTags[$tag])) {
                    // Unwrap unknown tag but keep (cleaned) children.
                    $clean($child);
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }
                foreach (iterator_to_array($child->attributes) as $attr) {
                    $name = strtolower($attr->name);
                    if (!in_array($name, $allowedTags[$tag], true)) {
                        $child->removeAttribute($attr->name);
                        continue;
                    }
                    $val = trim($attr->value);
                    if ($name === 'href' && !preg_match('#^(https?://|mailto:|tel:|/|\#)#i', $val)) {
                        $child->removeAttribute('href');
                    }
                    if ($name === 'src' && !preg_match('#^(https://|/)#i', $val)) {
                        $child->removeAttribute('src');
                    }
                }
                if ($tag === 'a') {
                    $href = $child->getAttribute('href');
                    if (preg_match('#^https?://#i', $href)) {
                        $child->setAttribute('target', '_blank');
                        $child->setAttribute('rel', 'noopener noreferrer');
                    } else {
                        $child->removeAttribute('target');
                    }
                }
                if ($tag === 'img' && !$child->getAttribute('src')) {
                    $node->removeChild($child);
                    continue;
                }
                $clean($child);
            } elseif ($child instanceof DOMComment) {
                $node->removeChild($child);
            }
        }
    };
    $clean($root);

    $out = '';
    foreach ($root->childNodes as $c) {
        $out .= $doc->saveHTML($c);
    }
    return trim($out);
}

/* ---------- Images ---------- */

/**
 * Validate and store an uploaded image in /uploads. Returns the stored file name.
 * @throws RuntimeException with a user-friendly message
 */
function store_image(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The image could not be uploaded (error ' . (int) ($file['error'] ?? -1) . ').');
    }
    if ($file['size'] > 4 * 1024 * 1024) {
        throw new RuntimeException('Images must be 4 MB or smaller.');
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Only JPG, PNG, WebP or GIF images are allowed.');
    }
    $dir = ROOT_DIR . '/uploads';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('The uploads folder is missing and could not be created.');
    }
    $name = date('Ymd') . '-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
        throw new RuntimeException('The uploads folder is not writable. Set its permissions to 755 in cPanel File Manager.');
    }
    return $name;
}

/* ---------- Auth ---------- */

function current_user(): ?array
{
    start_session();
    return $_SESSION['user'] ?? null;
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        redirect(url('admin/login.php'));
    }
    return $u;
}
