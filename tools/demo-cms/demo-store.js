/* MC Attorneys CMS demo — shared browser-side store (localStorage).
   Demo only: data lives in the visitor's own browser and is never sent anywhere. */
(function () {
  var KEY = 'mcDemo.v1';
  var meta = document.querySelector('meta[name="mc-base"]');
  var base = meta ? meta.content : '/';
  var state = null;

  function clone(o) { return JSON.parse(JSON.stringify(o)); }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function resolve(p) {
    if (!p) return '';
    if (/^(data:|https?:|\/)/.test(p)) return p;
    return base + p.replace(/^\/+/, '');
  }
  function persist() {
    try { localStorage.setItem(KEY, JSON.stringify(state)); return true; } catch (e) { return false; }
  }
  function load() {
    if (state) return state;
    var raw = null;
    try { raw = localStorage.getItem(KEY); } catch (e) {}
    if (raw) {
      try { var d = JSON.parse(raw); if (d && d.posts) { state = d; return state; } } catch (e) {}
    }
    state = clone(window.MC_DEMO_SEED);
    state.session = false;
    persist();
    return state;
  }
  function reload() { state = null; return load(); }
  function reset() {
    state = clone(window.MC_DEMO_SEED);
    state.session = false;
    persist();
    return state;
  }
  function parseDate(s) { return s ? new Date(String(s).replace(' ', 'T')) : null; }
  function pad(n) { return (n < 10 ? '0' : '') + n; }
  function stamp(d) {
    return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':00';
  }
  function formatDate(s) {
    var d = parseDate(s);
    return d ? d.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : '';
  }
  function slugify(s) {
    return String(s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 150);
  }
  function textOf(html) {
    var d = document.createElement('div'); d.innerHTML = html || ''; return (d.textContent || '').replace(/\s+/g, ' ').trim();
  }
  function readMinutes(html) {
    var w = textOf(html).split(' ').filter(Boolean).length;
    return Math.max(1, Math.ceil(w / 200));
  }
  function isLive(p) {
    if (p.status !== 'published') return false;
    var d = parseDate(p.published_at);
    return !d || d <= new Date();
  }
  function sortKey(p) { var d = parseDate(p.published_at || p.updated_at); return d ? d.getTime() : 0; }

  var ALLOWED = { P: 1, H2: 1, H3: 1, STRONG: 1, EM: 1, U: 1, B: 1, I: 1, UL: 1, OL: 1, LI: 1, BLOCKQUOTE: 1, A: 1, IMG: 1, BR: 1 };
  function clean(node) {
    Array.prototype.slice.call(node.childNodes).forEach(function (ch) {
      if (ch.nodeType === 3) return;
      if (ch.nodeType !== 1) { node.removeChild(ch); return; }
      if (/^(SCRIPT|STYLE|IFRAME|OBJECT|EMBED|LINK|META|FORM|INPUT|BUTTON|SVG)$/.test(ch.tagName)) { node.removeChild(ch); return; }
      clean(ch);
      if (!ALLOWED[ch.tagName]) {
        while (ch.firstChild) node.insertBefore(ch.firstChild, ch);
        node.removeChild(ch);
        return;
      }
      Array.prototype.slice.call(ch.attributes).forEach(function (a) {
        var n = a.name.toLowerCase(), ok = false;
        if (ch.tagName === 'A' && n === 'href' && /^(https?:|mailto:|\/|#)/i.test(a.value)) ok = true;
        if (ch.tagName === 'IMG' && n === 'src' && /^(data:image\/(png|jpe?g|gif|webp);|https?:|\/|uploads\/)/i.test(a.value)) ok = true;
        if (ch.tagName === 'IMG' && n === 'alt') ok = true;
        if (!ok) ch.removeAttribute(a.name);
      });
      if (ch.tagName === 'A') ch.setAttribute('rel', 'noopener');
    });
  }
  function sanitize(html) {
    var doc = new DOMParser().parseFromString('<body>' + (html || '') + '</body>', 'text/html');
    clean(doc.body);
    return doc.body.innerHTML;
  }

  window.MCStore = {
    base: base, load: load, reload: reload, persist: persist, reset: reset, resolve: resolve, esc: esc, clone: clone,
    parseDate: parseDate, stamp: stamp, formatDate: formatDate, slugify: slugify, textOf: textOf,
    readMinutes: readMinutes, isLive: isLive, sortKey: sortKey, sanitize: sanitize
  };
})();
