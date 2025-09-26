(function () {
    function flash(el, text, ok=false) {
      el.textContent = text || '';
      el.className = ok ? 'text-success small' : 'text-danger small';
      if (text) setTimeout(() => { el.textContent = ''; }, 3000);
    }
  
    function safeJson(resp) {
      // Try to parse JSON; if it fails, throw with response text
      return resp.text().then(txt => {
        try { return JSON.parse(txt); }
        catch (e) { throw new Error('Non-JSON response: ' + txt); }
      });
    }
  
    function fetchList() {
      fetch('../actions/fetch_category_action.php', { method: 'GET' })
        .then(resp => safeJson(resp))
        .then(res => {
          const tbody = document.querySelector('#catTable tbody');
          tbody.innerHTML = '';
          if (!res.success) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-danger">Failed to load categories</td></tr>';
            return;
          }
          (res.data || []).forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>
                <span class="category-name" data-id="${row.cat_id}">${escapeHtml(row.cat_name)}</span>
                <input class="form-control form-control-sm category-input" value="${escapeHtml(row.cat_name)}" data-id="${row.cat_id}" style="display: none;" />
              </td>
              <td>
                <button class="btn btn-sm btn-success me-2" data-action="edit" data-id="${row.cat_id}">Edit</button>
                <button class="btn btn-sm btn-primary me-2" data-action="save" data-id="${row.cat_id}" style="display: none;">Save</button>
                <button class="btn btn-sm btn-secondary me-2" data-action="cancel" data-id="${row.cat_id}" style="display: none;">Cancel</button>
                <button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${row.cat_id}">Delete</button>
              </td>`;
            tbody.appendChild(tr);
          });
        })
        .catch(err => {
          console.error('fetchList error:', err);
          const tbody = document.querySelector('#catTable tbody');
          tbody.innerHTML = '<tr><td colspan="2" class="text-danger">Error loading categories</td></tr>';
        });
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function enableEdit(id) {
      const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
      const nameSpan = row.querySelector('.category-name');
      const nameInput = row.querySelector('.category-input');
      const editBtn = row.querySelector('[data-action="edit"]');
      const saveBtn = row.querySelector('[data-action="save"]');
      const cancelBtn = row.querySelector('[data-action="cancel"]');

      // Hide read-only elements
      nameSpan.style.display = 'none';
      editBtn.style.display = 'none';

      // Show edit elements
      nameInput.style.display = 'block';
      nameInput.focus();
      nameInput.select();
      saveBtn.style.display = 'inline-block';
      cancelBtn.style.display = 'inline-block';
    }

    function cancelEdit(id) {
      const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
      const nameSpan = row.querySelector('.category-name');
      const nameInput = row.querySelector('.category-input');
      const editBtn = row.querySelector('[data-action="edit"]');
      const saveBtn = row.querySelector('[data-action="save"]');
      const cancelBtn = row.querySelector('[data-action="cancel"]');

      // Reset input to original value
      nameInput.value = nameSpan.textContent;

      // Show read-only elements
      nameSpan.style.display = 'block';
      editBtn.style.display = 'inline-block';

      // Hide edit elements
      nameInput.style.display = 'none';
      saveBtn.style.display = 'none';
      cancelBtn.style.display = 'none';
    }
  
    // ADD
    const addForm = document.getElementById('addForm');
    if (addForm) {
      addForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = document.getElementById('addMsg');
        const fd = new FormData(addForm);
  
        fetch('../actions/add_category_action.php', { method: 'POST', body: fd })
          .then(resp => safeJson(resp))
          .then(res => {
            if (res.success) {
              addForm.reset();
              flash(msg, 'Added!', true);
              fetchList();
            } else {
              flash(msg, res.message || 'Could not add category.');
            }
          })
          .catch(err => {
            console.error('add error:', err);
            flash(msg, String(err));
          });
      });
    }
  
    // EDIT / SAVE / CANCEL / DELETE
    const table = document.getElementById('catTable');
    if (table) {
      table.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
  
        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');

        if (action === 'edit') {
          enableEdit(id);

        } else if (action === 'cancel') {
          cancelEdit(id);

        } else if (action === 'save') {
          const input = btn.closest('tr').querySelector('.category-input');
          const nameSpan = btn.closest('tr').querySelector('.category-name');
          const newName = input.value.trim();

          if (newName === '') {
            alert('Category name cannot be empty');
            input.focus();
            return;
          }

          const fd = new FormData();
          fd.append('cat_id', id);
          fd.append('category_name', newName);
  
          fetch('../actions/update_category_action.php', { method: 'POST', body: fd })
            .then(resp => safeJson(resp))
            .then(res => {
              if (res.success) {
                // Update the display text
                nameSpan.textContent = newName;
                cancelEdit(id);
              } else {
                alert(res.message || 'Update failed');
              }
            })
            .catch(err => {
              console.error('update error:', err);
              alert('Update failed: ' + err.message);
            });
  
        } else if (action === 'delete') {
          if (!confirm('Delete this category?')) return;
          const fd = new FormData();
          fd.append('cat_id', id);
  
          fetch('../actions/delete_category_action.php', { method: 'POST', body: fd })
            .then(resp => safeJson(resp))
            .then(res => {
              if (!res.success) alert(res.message || 'Delete failed');
              fetchList();
            })
            .catch(err => {
              console.error('delete error:', err);
              alert('Delete failed: ' + err.message);
            });
        }
      });

      // Handle Enter key to save, Escape to cancel
      table.addEventListener('keydown', (e) => {
        if (e.target.classList.contains('category-input')) {
          if (e.key === 'Enter') {
            e.preventDefault();
            const saveBtn = e.target.closest('tr').querySelector('[data-action="save"]');
            saveBtn.click();
          } else if (e.key === 'Escape') {
            const cancelBtn = e.target.closest('tr').querySelector('[data-action="cancel"]');
            cancelBtn.click();
          }
        }
      });
    }
  
    // Basic proof JS is loaded
    console.log('[category.js] loaded');
    fetchList();
  })();