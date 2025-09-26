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
              <td><input class="form-control form-control-sm" value="${row.cat_name}" data-id="${row.cat_id}" /></td>
              <td>
                <button class="btn btn-sm btn-success me-2" data-action="save" data-id="${row.cat_id}">Save</button>
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
  
    // SAVE / DELETE
    const table = document.getElementById('catTable');
    if (table) {
      table.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
  
        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');
  
        if (action === 'save') {
          const input = btn.closest('tr').querySelector('input');
          const fd = new FormData();
          fd.append('cat_id', id);
          fd.append('category_name', input.value);
  
          fetch('../actions/update_category_action.php', { method: 'POST', body: fd })
            .then(resp => safeJson(resp))
            .then(res => {
              if (!res.success) alert(res.message || 'Update failed');
              fetchList();
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
    }
  
    // Basic proof JS is loaded
    console.log('[category.js] loaded');
    fetchList();
  })();
  