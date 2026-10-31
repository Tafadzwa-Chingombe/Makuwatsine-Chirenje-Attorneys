// New-category box: shown when "+ Add a new category…" is chosen (works even if the editor library is blocked).
(function () {
  var sel = document.getElementById('category'), box = document.getElementById('new-category'), hint = document.getElementById('new-category-hint');
  if (!sel || !box) return;
  function sync() { var on = sel.value === '__new__'; box.hidden = !on; if (hint) hint.hidden = !on; if (on && document.activeElement === sel) box.focus(); }
  sel.addEventListener('change', sync); sync();
})();

// Article editor: Quill rich-text editor, slug helper and image upload.
(function () {
  var form = document.getElementById('post-form');
  var editorEl = document.getElementById('editor');
  if (!form || !editorEl) return;

  if (typeof Quill === 'undefined') {
    // CDN blocked: fall back to a plain textarea so content can still be edited.
    var ta = document.getElementById('content');
    ta.hidden = false;
    ta.rows = 18;
    ta.value = editorEl.innerHTML.trim();
    editorEl.style.display = 'none';
    return;
  }

  var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Write your article here…',
    modules: {
      toolbar: {
        container: [
          [{ header: [2, 3, false] }],
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['blockquote', 'link', 'image'],
          ['clean']
        ],
        handlers: { image: imageHandler }
      }
    }
  });

  function imageHandler() {
    var input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/jpeg,image/png,image/webp,image/gif';
    input.onchange = function () {
      var file = input.files[0];
      if (!file) return;
      var fd = new FormData();
      fd.append('image', file);
      fd.append('csrf', window.MC.csrf);
      fetch(window.MC.uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
        .then(function (res) {
          if (!res.ok) throw new Error(res.j.error || 'Upload failed');
          var range = quill.getSelection(true);
          quill.insertEmbed(range.index, 'image', res.j.url, 'user');
          quill.setSelection(range.index + 1);
        })
        .catch(function (err) { alert(err.message); });
    };
    input.click();
  }

  // Keep hidden textarea in sync on submit
  form.addEventListener('submit', function () {
    var html = quill.root.innerHTML;
    document.getElementById('content').value = (html === '<p><br></p>') ? '' : html;
  });

  // Slug helper: follow the title until the user edits the slug manually
  var title = document.getElementById('title');
  var slug = document.getElementById('slug');
  var preview = document.getElementById('slug-preview');
  function slugify(s) {
    return s.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 150);
  }
  title.addEventListener('input', function () {
    if (slug.dataset.manual !== '1') {
      slug.value = slugify(title.value);
      preview.textContent = slug.value || '…';
    }
  });
  slug.addEventListener('input', function () {
    slug.dataset.manual = '1';
    preview.textContent = slugify(slug.value) || '…';
  });
})();
