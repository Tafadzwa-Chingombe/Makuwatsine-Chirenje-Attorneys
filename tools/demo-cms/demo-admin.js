/* MC Attorneys CMS demo — browser-only admin that mirrors the real PHP/MySQL CMS. */
(function () {
  var S = window.MCStore, st = S.load(), esc = S.esc, base = S.base;
  var app = document.getElementById('app');
  var flashMsg = null, filter = '', query = '', quill = null, coverData = null, coverRemoved = false;

  function flash(msg, type) { flashMsg = { msg: msg, type: type || 'success' }; }
  function persist() {
    if (!S.persist()) { alert('Your browser blocked or filled its storage, so this change could not be kept. The demo only works with browser storage enabled.'); return false; }
    return true;
  }
  function go(h) { if (location.hash === '#' + h) route(); else location.hash = h; }
  function toLocalInput(s) { var d = S.parseDate(s); return d ? d.getFullYear() + '-' + p2(d.getMonth() + 1) + '-' + p2(d.getDate()) + 'T' + p2(d.getHours()) + ':' + p2(d.getMinutes()) : ''; }
  function p2(n) { return (n < 10 ? '0' : '') + n; }
  function shortDate(s) { var d = S.parseDate(s); return d ? d.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : ''; }
  function when(s) { var d = S.parseDate(s); return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) + ', ' + p2(d.getHours()) + ':' + p2(d.getMinutes()); }
  function excerptOf(html) { var t = S.textOf(html); return t.length > 160 ? t.slice(0, 157).replace(/\s+\S*$/, '') + '…' : t; }
  function articleUrl(p, preview) { return base + 'blog/article.html?s=' + encodeURIComponent(p.slug) + (preview ? '&preview=1' : ''); }
  function nextId(list) { return list.reduce(function (m, x) { return Math.max(m, x.id); }, 0) + 1; }

  function resizeImage(file, maxW, cb) {
    if (!/^image\/(jpeg|png|webp|gif)$/.test(file.type)) { alert('Please choose a JPG, PNG, WebP or GIF image.'); return; }
    if (file.size > 8 * 1024 * 1024) { alert('That image is larger than 8 MB.'); return; }
    var r = new FileReader();
    r.onload = function () {
      var im = new Image();
      im.onload = function () {
        var w = Math.min(maxW, im.width), h = Math.round(im.height * (w / im.width));
        var c = document.createElement('canvas'); c.width = w; c.height = h;
        c.getContext('2d').drawImage(im, 0, 0, w, h);
        cb(c.toDataURL('image/jpeg', 0.82));
      };
      im.onerror = function () { alert('That image could not be read.'); };
      im.src = r.result;
    };
    r.readAsDataURL(file);
  }

  /* ---------- shell ---------- */
  var BANNER = '<div class="demo-banner">CMS demo — everything you do here is saved only in this browser (no server). <a href="' + esc(base) + '">Back to the website</a></div>';
  function shell(nav, inner) {
    var unread = st.enquiries.filter(function (e) { return !e.is_read; }).length;
    var f = flashMsg ? '<div class="notice notice-' + esc(flashMsg.type) + '" role="status">' + esc(flashMsg.msg) + '</div>' : '';
    flashMsg = null;
    app.innerHTML = BANNER +
      '<header class="admin-bar"><a class="admin-brand" href="#/"><span class="logo-mark">MC</span><span>Content Manager</span></a>' +
      '<nav><a href="#/"' + (nav === 'posts' ? ' class="on"' : '') + '>Articles</a>' +
      '<a href="#/edit"' + (nav === 'new' ? ' class="on"' : '') + '>New article</a>' +
      '<a href="#/enquiries"' + (nav === 'enq' ? ' class="on"' : '') + '>Enquiries' + (unread ? ' (' + unread + ')' : '') + '</a>' +
      '<a href="#/password"' + (nav === 'pw' ? ' class="on"' : '') + '>Password</a>' +
      '<a href="' + esc(base + 'blog.html') + '" target="_blank" rel="noopener">View blog ↗</a></nav>' +
      '<div class="bar-actions"><button class="reset" id="reset" type="button">Reset demo data</button>' +
      '<form class="logout" id="logout"><span>' + esc(st.user.name) + '</span><button type="submit">Log out</button></form></div></header>' +
      '<main class="admin-main">' + f + inner + '</main>';
    document.getElementById('logout').addEventListener('submit', function (e) { e.preventDefault(); st.session = false; persist(); go('/'); });
    document.getElementById('reset').addEventListener('click', function () {
      if (confirm('Reset the demo? This restores the sample articles and clears anything you added.')) { st = S.reset(); st.session = true; persist(); flash('Demo data reset.'); go('/'); }
    });
    window.scrollTo(0, 0);
  }

  /* ---------- login ---------- */
  function viewLogin() {
    app.innerHTML = BANNER + '<div class="login-wrap demo-login"><form class="login-card" id="login" autocomplete="on">' +
      '<div class="login-logo"><span class="logo-mark">MC</span></div><h1>Content Manager</h1><p class="sub">Makuwatsine Chirenje Attorneys</p>' +
      (flashMsg ? '<div class="notice notice-' + esc(flashMsg.type) + '" role="alert">' + esc(flashMsg.msg) + '</div>' : '') +
      '<label for="email">Email</label><input type="email" id="email" required autocomplete="username" value="' + esc(st.user.email) + '">' +
      '<label for="password">Password</label><input type="password" id="password" required autocomplete="current-password" value="' + esc(st.user.password) + '">' +
      '<button class="btn-primary" type="submit">Log in</button>' +
      '<div class="demo-hint"><strong>Demo login</strong> is pre-filled.<br>Email <code>' + esc(st.user.email) + '</code><br>Password <code>' + esc(st.user.password) + '</code></div>' +
      '</form></div>';
    flashMsg = null;
    document.getElementById('login').addEventListener('submit', function (e) {
      e.preventDefault();
      var em = document.getElementById('email').value.trim().toLowerCase(), pw = document.getElementById('password').value;
      if (em === st.user.email.toLowerCase() && pw === st.user.password) { st.session = true; persist(); flash('Signed in to the demo.'); go('/'); }
      else { flash('Incorrect email or password.', 'error'); viewLogin(); }
    });
  }

  /* ---------- articles list ---------- */
  function viewPosts() {
    var unread = st.enquiries.filter(function (e) { return !e.is_read; }).length;
    var counts = { published: 0, draft: 0 };
    st.posts.forEach(function (p) { counts[p.status] = (counts[p.status] || 0) + 1; });
    var list = st.posts.filter(function (p) { return (!filter || p.status === filter) && (!query || p.title.toLowerCase().indexOf(query.toLowerCase()) > -1); })
      .sort(function (a, b) { return S.sortKey(b) - S.sortKey(a); });
    var rows = list.map(function (p) {
      var sched = p.status === 'published' && S.parseDate(p.published_at) > new Date();
      return '<tr><td><a class="title" href="#/edit/' + p.id + '">' + esc(p.title) + '</a><div class="slug">/blog/' + esc(p.slug) + '</div></td>' +
        '<td>' + esc(p.category) + '</td><td><span class="badge ' + (sched ? 'badge-sched' : 'badge-' + p.status) + '">' + (sched ? 'Scheduled' : (p.status === 'draft' ? 'Draft' : 'Published')) + '</span></td>' +
        '<td>' + esc(p.published_at ? shortDate(p.published_at) : 'Updated ' + shortDate(p.updated_at)) + '</td>' +
        '<td class="actions"><a href="#/edit/' + p.id + '">Edit</a><a href="' + esc(articleUrl(p, true)) + '" target="_blank" rel="noopener">View</a>' +
        '<form data-del="' + p.id + '"><button class="link-danger">Delete</button></form></td></tr>';
    }).join('');
    shell('posts',
      '<div class="page-head"><h1>Articles</h1><a class="btn-primary" href="#/edit">+ New article</a></div>' +
      (unread ? '<div class="notice notice-info">You have <strong>' + unread + '</strong> unread website enquir' + (unread === 1 ? 'y' : 'ies') + '. <a href="#/enquiries">View enquiries</a></div>' : '') +
      '<div class="toolbar"><nav class="tabs"><a href="#" data-f="" class="' + (filter === '' ? 'on' : '') + '">All (' + st.posts.length + ')</a>' +
      '<a href="#" data-f="published" class="' + (filter === 'published' ? 'on' : '') + '">Published (' + counts.published + ')</a>' +
      '<a href="#" data-f="draft" class="' + (filter === 'draft' ? 'on' : '') + '">Drafts (' + counts.draft + ')</a></nav>' +
      '<form class="search" id="search"><input type="search" id="q" value="' + esc(query) + '" placeholder="Search titles…"><button>Search</button></form></div>' +
      (list.length ? '<div class="table-wrap"><table><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>' + rows + '</tbody></table></div>'
        : '<div class="empty"><p>No articles found.</p><a class="btn-primary" href="#/edit">Write your first article</a></div>'));
    Array.prototype.forEach.call(app.querySelectorAll('.tabs a'), function (a) { a.addEventListener('click', function (e) { e.preventDefault(); filter = a.getAttribute('data-f'); viewPosts(); }); });
    document.getElementById('search').addEventListener('submit', function (e) { e.preventDefault(); query = document.getElementById('q').value.trim(); viewPosts(); });
    Array.prototype.forEach.call(app.querySelectorAll('form[data-del]'), function (f) {
      f.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!confirm('Delete this article permanently?')) return;
        var id = +f.getAttribute('data-del');
        st.posts = st.posts.filter(function (p) { return p.id !== id; });
        if (persist()) { flash('Article deleted.'); viewPosts(); }
      });
    });
  }

  /* ---------- editor ---------- */
  function viewEdit(id) {
    var post = id ? st.posts.filter(function (p) { return p.id === id; })[0] : null;
    if (id && !post) { flash('Article not found.', 'error'); go('/'); return; }
    post = post ? S.clone(post) : { id: 0, title: '', slug: '', excerpt: '', content: '', cover: null, category: st.categories[0], author: st.user.name, status: 'draft', published_at: null };
    coverData = post.cover; coverRemoved = false;
    var live = post.id && post.status === 'published';
    shell(post.id ? 'posts' : 'new',
      '<div class="page-head"><h1>' + (post.id ? 'Edit article' : 'New article') + '</h1>' +
      (post.id ? '<a class="btn-ghost" href="' + esc(articleUrl(post, !live)) + '" target="_blank" rel="noopener">' + (live ? 'View live ↗' : 'Preview ↗') + '</a>' : '') + '</div>' +
      '<div id="errs"></div>' +
      '<form id="post-form" class="editor-layout"><div class="editor-main">' +
      '<label for="title">Title</label><input type="text" id="title" value="' + esc(post.title) + '" maxlength="200" class="title-input" placeholder="Article title">' +
      '<label for="slug">URL slug <span class="hint">/blog/<strong id="slug-preview">' + esc(post.slug || '…') + '</strong></span></label>' +
      '<input type="text" id="slug" value="' + esc(post.slug) + '" maxlength="150" placeholder="auto-generated from the title" data-manual="' + (post.id ? '1' : '0') + '">' +
      '<label>Content</label><div id="editor">' + S.sanitize(post.content) + '</div>' +
      '<label for="excerpt">Excerpt <span class="hint">Short summary shown on the blog list. Leave empty to create it automatically.</span></label>' +
      '<textarea id="excerpt" rows="3" maxlength="400">' + esc(post.excerpt) + '</textarea></div>' +
      '<aside class="editor-side"><div class="panel"><h2>Publish</h2>' +
      '<p class="status-line">Status: <span class="badge badge-' + post.status + '">' + (post.status === 'draft' ? 'Draft' : 'Published') + '</span></p>' +
      '<label for="published_at">Publish date <span class="hint">Set a future date to schedule.</span></label>' +
      '<input type="datetime-local" id="published_at" value="' + esc(toLocalInput(post.published_at)) + '">' +
      '<div class="btn-row"><button type="button" class="btn-ghost" data-act="draft">Save draft</button>' +
      '<button type="button" class="btn-primary" data-act="publish">' + (live ? 'Update' : 'Publish') + '</button></div></div>' +
      '<div class="panel"><h2>Details</h2><label for="category">Category</label><select id="category">' +
      st.categories.map(function (c) { return '<option' + (c === post.category ? ' selected' : '') + '>' + esc(c) + '</option>'; }).join('') + '</select>' +
      '<label for="author">Author</label><input type="text" id="author" value="' + esc(post.author) + '" maxlength="120"></div>' +
      '<div class="panel"><h2>Cover image</h2><div id="cover-box"></div>' +
      '<input type="file" id="cover" accept="image/jpeg,image/png,image/webp,image/gif">' +
      '<p class="hint">JPG, PNG, WebP or GIF. The demo shrinks images to fit browser storage. Recommended 1600×900.</p></div></aside></form>');
    drawCover();
    initEditor();

    var title = document.getElementById('title'), slug = document.getElementById('slug'), prev = document.getElementById('slug-preview');
    title.addEventListener('input', function () { if (slug.dataset.manual !== '1') { slug.value = S.slugify(title.value); prev.textContent = slug.value || '…'; } });
    slug.addEventListener('input', function () { slug.dataset.manual = '1'; prev.textContent = S.slugify(slug.value) || '…'; });
    document.getElementById('cover').addEventListener('change', function (e) {
      var f = e.target.files[0]; if (!f) return;
      resizeImage(f, 1400, function (d) { coverData = d; coverRemoved = false; drawCover(); });
    });
    Array.prototype.forEach.call(app.querySelectorAll('[data-act]'), function (b) {
      b.addEventListener('click', function () { save(post, b.getAttribute('data-act')); });
    });
  }

  function drawCover() {
    var box = document.getElementById('cover-box');
    box.innerHTML = coverData ? '<img class="cover-preview" src="' + esc(S.resolve(coverData)) + '" alt="Current cover image"><label class="check"><input type="checkbox" id="rm-cover"> Remove cover image</label>' : '';
    var rm = document.getElementById('rm-cover');
    if (rm) rm.addEventListener('change', function () { coverRemoved = rm.checked; });
  }

  function initEditor() {
    var el = document.getElementById('editor');
    if (typeof Quill === 'undefined') {
      var ta = document.createElement('textarea'); ta.id = 'content-fallback'; ta.rows = 16; ta.value = el.innerHTML.trim();
      el.parentNode.replaceChild(ta, el); quill = null; return;
    }
    quill = new Quill('#editor', {
      theme: 'snow', placeholder: 'Write your article here…',
      modules: { toolbar: { container: [[{ header: [2, 3, false] }], ['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['blockquote', 'link', 'image'], ['clean']],
        handlers: { image: function () {
          var input = document.createElement('input'); input.type = 'file'; input.accept = 'image/jpeg,image/png,image/webp,image/gif';
          input.onchange = function () {
            if (!input.files[0]) return;
            resizeImage(input.files[0], 1000, function (d) { var r = quill.getSelection(true); quill.insertEmbed(r.index, 'image', d, 'user'); quill.setSelection(r.index + 1); });
          };
          input.click();
        } } } }
    });
  }

  function save(post, action) {
    var errs = [], g = function (i) { return document.getElementById(i).value; };
    var html = quill ? quill.root.innerHTML : g('content-fallback');
    html = S.sanitize(html === '<p><br></p>' ? '' : html);
    var title = g('title').trim(), slug = S.slugify(g('slug') || title);
    if (!title) errs.push('Please enter a title.');
    if (action === 'publish' && !S.textOf(html) && html.indexOf('<img') < 0) errs.push('An article needs some content before it can be published.');
    if (!slug) slug = 'article';
    var base0 = slug, n = 1;
    while (st.posts.some(function (p) { return p.slug === slug && p.id !== post.id; })) slug = base0 + '-' + (++n);
    if (errs.length) { document.getElementById('errs').innerHTML = '<div class="notice notice-error" role="alert">' + errs.map(esc).join('<br>') + '</div>'; window.scrollTo(0, 0); return; }

    var dt = g('published_at'), ts = dt ? S.stamp(new Date(dt)) : null, nowS = S.stamp(new Date());
    var rec = {
      id: post.id || nextId(st.posts), slug: slug, title: title, category: g('category'), author: g('author').trim(),
      excerpt: g('excerpt').trim() || excerptOf(html), content: html,
      cover: coverRemoved ? null : coverData, status: action === 'publish' ? 'published' : 'draft',
      published_at: action === 'publish' ? (ts || post.published_at || nowS) : (ts || post.published_at), updated_at: nowS
    };
    var before = S.clone(st.posts);
    if (post.id) st.posts = st.posts.map(function (p) { return p.id === rec.id ? rec : p; }); else st.posts.push(rec);
    if (!persist()) { st.posts = before; return; }
    flash(action === 'publish' ? 'Article published — it is now live on the blog.' : 'Draft saved.');
    go('/edit/' + rec.id);
  }

  /* ---------- enquiries ---------- */
  function viewEnquiries() {
    var rows = st.enquiries.map(function (r) {
      return '<article class="enq ' + (r.is_read ? '' : 'unread') + '"><header><strong>' + esc(r.name) + '</strong><span>' + esc(when(r.created_at)) + '</span>' + (r.is_read ? '' : '<span class="badge badge-published">New</span>') + '</header>' +
        '<p class="enq-meta"><a href="mailto:' + esc(r.email) + '">' + esc(r.email) + '</a>' + (r.phone ? ' · ' + esc(r.phone) : '') + (r.practice_area ? ' · ' + esc(r.practice_area) : '') + '</p>' +
        '<p>' + esc(r.message).replace(/\n/g, '<br>') + '</p><div class="enq-actions">' +
        (r.is_read ? '' : '<button class="btn-ghost" data-read="' + r.id + '">Mark as read</button>') + '<button class="link-danger" data-del="' + r.id + '">Delete</button></div></article>';
    }).join('');
    shell('enq', '<div class="page-head"><h1>Website enquiries</h1></div>' +
      (rows || '<div class="empty"><p>No enquiries yet. Messages sent from the contact form will appear here (and are emailed to the firm).</p></div>') +
      '<p class="hint">Demo tip: send a message from the website\'s Contact page and it will show up here.</p>');
    Array.prototype.forEach.call(app.querySelectorAll('[data-read]'), function (b) { b.addEventListener('click', function () {
      var id = +b.getAttribute('data-read'); st.enquiries.forEach(function (e) { if (e.id === id) e.is_read = true; }); persist(); viewEnquiries(); }); });
    Array.prototype.forEach.call(app.querySelectorAll('[data-del]'), function (b) { b.addEventListener('click', function () {
      if (!confirm('Delete this enquiry?')) return;
      var id = +b.getAttribute('data-del'); st.enquiries = st.enquiries.filter(function (e) { return e.id !== id; }); persist(); flash('Enquiry deleted.'); viewEnquiries(); }); });
  }

  /* ---------- password ---------- */
  function viewPassword() {
    shell('pw', '<div class="page-head"><h1>Change password</h1></div><div id="errs"></div>' +
      '<form id="pw" class="panel narrow"><div class="notice notice-info">Demo only — the new password is kept in this browser, and "Reset demo data" restores the original.</div>' +
      '<label for="current">Current password</label><input type="password" id="current" autocomplete="current-password">' +
      '<label for="new">New password <span class="hint">At least 10 characters.</span></label><input type="password" id="new" autocomplete="new-password">' +
      '<label for="confirm">Confirm new password</label><input type="password" id="confirm" autocomplete="new-password">' +
      '<button class="btn-primary" type="submit">Update password</button></form>');
    document.getElementById('pw').addEventListener('submit', function (e) {
      e.preventDefault();
      var errs = [], cur = document.getElementById('current').value, nw = document.getElementById('new').value;
      if (cur !== st.user.password) errs.push('Your current password is incorrect.');
      if (nw.length < 10) errs.push('The new password must be at least 10 characters.');
      if (nw !== document.getElementById('confirm').value) errs.push('The new passwords do not match.');
      if (errs.length) { document.getElementById('errs').innerHTML = '<div class="notice notice-error" role="alert">' + errs.map(esc).join('<br>') + '</div>'; return; }
      st.user.password = nw; if (persist()) { flash('Password updated.'); viewPassword(); }
    });
  }

  function route() {
    if (quill) quill = null;
    st = S.reload();
    if (!st.session) { viewLogin(); return; }
    var parts = (location.hash.replace(/^#/, '') || '/').split('/');
    if (parts[1] === 'edit') viewEdit(parts[2] ? +parts[2] : 0);
    else if (parts[1] === 'enquiries') viewEnquiries();
    else if (parts[1] === 'password') viewPassword();
    else viewPosts();
  }
  window.addEventListener('hashchange', route);
  route();
})();
