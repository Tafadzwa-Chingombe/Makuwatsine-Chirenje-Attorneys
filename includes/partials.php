<?php
/** Small reusable view fragments. */

function img_placeholder(string $label = 'Image placeholder', string $class = ''): void
{
    echo '<div class="img-ph ' . e($class) . '" role="img" aria-label="' . e($label) . '"><span>IMAGE PLACEHOLDER</span></div>';
}

function page_hero(string $eyebrow, string $title, string $sub = '', string $bg = ''): void
{
    ?>
    <section class="page-hero<?= $bg ? ' has-bg' : '' ?>"<?= $bg ? ' style="background-image:url(' . e(url($bg)) . ')"' : '' ?>>
      <div class="container">
        <p class="eyebrow"><?= e($eyebrow) ?></p>
        <h1><?= e($title) ?></h1>
        <?php if ($sub): ?><p class="lead"><?= e($sub) ?></p><?php endif; ?>
      </div>
    </section>
    <?php
}

function cta_banner(string $eyebrow, string $title, string $extra = '', string $bg = 'assets/img/cta-boardroom.jpg'): void
{
    ?>
    <section class="cta-banner<?= $bg ? ' has-bg' : '' ?>"<?= $bg ? ' style="background-image:url(' . e(url($bg)) . ')"' : '' ?>>
      <div class="container">
        <p class="eyebrow"><?= e($eyebrow) ?></p>
        <h2><?= e($title) ?></h2>
        <?php if ($extra): ?><p><?= e($extra) ?></p><?php endif; ?>
        <a class="btn btn-primary" href="<?= e(url('contact.php')) ?>">Book a Consultation</a>
      </div>
    </section>
    <?php
}

function area_card(string $title, string $desc, bool $more = false): void
{
    ?>
    <article class="card area-card">
      <span class="icon-dot" aria-hidden="true">◆</span>
      <h3><?= e($title) ?></h3>
      <p><?= e($desc) ?></p>
      <?php if ($more): ?><a class="more" href="<?= e(url('practice-areas.php')) ?>">Learn more →</a><?php endif; ?>
    </article>
    <?php
}

function post_card(array $p): void
{
    ?>
    <article class="post-card">
      <a class="post-img" href="<?= e(post_url($p['slug'])) ?>" tabindex="-1" aria-hidden="true">
        <?php if (!empty($p['cover_image'])): ?>
          <img src="<?= e(upload_url($p['cover_image'])) ?>" alt="" loading="lazy">
        <?php else: img_placeholder('Article image'); endif; ?>
      </a>
      <p class="eyebrow"><?= e($p['category']) ?></p>
      <h3><a href="<?= e(post_url($p['slug'])) ?>"><?= e($p['title']) ?></a></h3>
      <p><?= e($p['excerpt']) ?></p>
      <p class="meta"><?= e(format_date($p['published_at'])) ?> · <?= (int) reading_minutes($p['content']) ?> min read</p>
    </article>
    <?php
}

/** Portrait for a team member: uses assets/img/team/<slug>.jpg when present, otherwise a placeholder. */
function team_photo(array $p, string $class = 'tall'): void
{
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $p['name']), '-'));
    $rel = 'assets/img/team/' . $slug . '.jpg';
    if (is_file(__DIR__ . '/../' . $rel)) {
        echo '<img class="team-photo ' . e($class) . '" src="' . e(url($rel)) . '" alt="Portrait of ' . e($p['name']) . '" loading="lazy">';
        return;
    }
    img_placeholder('Portrait of ' . $p['name'], $class);
}
