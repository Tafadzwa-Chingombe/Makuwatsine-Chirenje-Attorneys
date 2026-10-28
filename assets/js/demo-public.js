/* MC Attorneys CMS demo — makes the public Blog, article and Contact pages read from the demo store. */
(function () {
  var S = window.MCStore;
  if (!S) return;
  var st = S.load(), esc = S.esc, base = S.base;

  function postUrl(slug) { return base + 'blog/article.html?s=' + encodeURIComponent(slug); }
  function img(p, label) {
    return p.cover
      ? '<img src="' + esc(S.resolve(p.cover)) + '" alt="">'
      : '<div class="img-ph " role="img" aria-label="' + esc(label) + '"><span>IMAGE PLACEHOLDER</span></div>';
  }
  function livePosts() {
    return st.posts.filter(S.isLive).sort(function (a, b) { return S.sortKey(b) - S.sortKey(a); });
  }

  /* ---------- Blog list ---------- */
  function renderBlog(chipsEl) {
    var box = chipsEl.parentNode, cat = '';
    function draw() {
      var posts = livePosts().filter(function (p) { return cat === '' || p.category === cat; });
      var h = '<nav class="chips" aria-label="Filter by category"><a class="chip' + (cat === '' ? ' is-active' : '') + '" data-cat="" href="#">All</a>' +
        st.categories.map(function (c) {
          return '<a class="chip' + (cat === c ? ' is-active' : '') + '" data-cat="' + esc(c) + '" href="#">' + esc(c) + '</a>';
        }).join('') + '</nav>';
      if (!posts.length) {
        h += '<div class="empty-state"><h3>No articles in this category yet</h3><p>New articles will appear here once published.</p></div>';
      } else {
        var f = posts[0], rest = posts.slice(1);
        h += '<article class="featured"><a href="' + esc(postUrl(f.slug)) + '" tabindex="-1" aria-hidden="true">' + img(f, 'Featured article image') + '</a>' +
          '<div class="featured-body"><p class="eyebrow">Featured · ' + esc(f.category) + '</p>' +
          '<h2><a href="' + esc(postUrl(f.slug)) + '">' + esc(f.title) + '</a></h2><p>' + esc(f.excerpt) + '</p>' +
          '<p class="meta">' + esc(S.formatDate(f.published_at)) + ' · ' + S.readMinutes(f.content) + ' min read · ' + esc(f.author) + '</p>' +
          '<p><a class="read-more" href="' + esc(postUrl(f.slug)) + '">Read article →</a></p></div></article>';
        if (rest.length) {
          h += '<div class="posts">' + rest.map(function (p) {
            return '<article class="post-card"><a class="post-img" href="' + esc(postUrl(p.slug)) + '" tabindex="-1" aria-hidden="true">' + img(p, 'Article image') + '</a>' +
              '<p class="eyebrow">' + esc(p.category) + '</p><h3><a href="' + esc(postUrl(p.slug)) + '">' + esc(p.title) + '</a></h3>' +
              '<p>' + esc(p.excerpt) + '</p><p class="meta">' + esc(S.formatDate(p.published_at)) + ' · ' + S.readMinutes(p.content) + ' min read</p></article>';
          }).join('') + '</div>';
        }
      }
      box.innerHTML = h;
      Array.prototype.forEach.call(box.querySelectorAll('.chip'), function (c) {
        c.addEventListener('click', function (e) { e.preventDefault(); cat = c.getAttribute('data-cat'); draw(); });
      });
    }
    draw();
  }

  /* ---------- Article ---------- */
  function renderArticle(el) {
    var q = new URLSearchParams(location.search);
    var slug = q.get('s') || decodeURIComponent((location.pathname.split('/').pop() || '').replace(/\.html$/, ''));
    var preview = q.get('preview') === '1';
    var p = st.posts.filter(function (x) { return x.slug === slug; })[0];
    if (!p || (!S.isLive(p) && !(preview && st.session))) {
      el.innerHTML = '<a class="back-link" href="' + esc(base + 'blog.html') + '">← All articles</a><header class="article-head"><h1>Article not found</h1><p class="meta">It may have been unpublished or deleted in the CMS demo.</p></header>';
      return;
    }
    document.title = p.title + ' — Makuwatsine Chirenje Attorneys';
    el.innerHTML = '<a class="back-link" href="' + esc(base + 'blog.html') + '">← All articles</a>' +
      '<header class="article-head"><p class="eyebrow">' + esc(p.category) + (S.isLive(p) ? '' : ' · DRAFT PREVIEW') + '</p><h1>' + esc(p.title) + '</h1>' +
      '<p class="meta">' + esc(S.formatDate(p.published_at || p.updated_at)) + ' · ' + S.readMinutes(p.content) + ' min read' + (p.author ? ' · By ' + esc(p.author) : '') + '</p></header>' +
      (p.cover ? '<figure class="article-cover"><img src="' + esc(S.resolve(p.cover)) + '" alt=""></figure>' : '') +
      '<div class="prose">' + S.sanitize(p.content) + '</div>';
  }

  /* ---------- Contact form -> demo inbox ---------- */
  function hookContact(form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      function v(id) { var x = document.getElementById(id); return x ? x.value.trim() : ''; }
      var old = form.querySelector('.alert'); if (old) old.remove();
      var n = document.createElement('div');
      n.setAttribute('role', 'status');
      if (!v('name') || !v('email') || !v('message') || v('email').indexOf('@') < 1) {
        n.className = 'alert alert-error';
        n.textContent = 'Please enter your name, a valid email address and a message.';
      } else {
        var next = st.enquiries.reduce(function (m, x) { return Math.max(m, x.id); }, 0) + 1;
        st.enquiries.unshift({ id: next, name: v('name'), email: v('email'), phone: v('phone'), practice_area: v('area'), message: v('message'), created_at: S.stamp(new Date()), is_read: false });
        S.persist();
        n.className = 'alert alert-success';
        n.textContent = 'Thank you — your message has been sent. We will get back to you shortly. (Demo: it now appears in the CMS Enquiries inbox.)';
        form.reset();
      }
      form.insertBefore(n, form.children[1]);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var chips = document.querySelector('.chips');
    if (chips && document.querySelector('.posts, .featured, .empty-state')) renderBlog(chips);
    var art = document.querySelector('article.article');
    if (art) renderArticle(art);
    var form = document.querySelector('form.contact-form');
    if (form) hookContact(form);
  });
})();
