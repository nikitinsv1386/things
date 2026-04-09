// Form validation and AJAX helpers
(function () {
  const forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });

  function openModal(url) {
    fetch(url + (url.includes('?') ? '&modal=1' : '?modal=1'))
      .then(r => r.text())
      .then(html => {
        const modalEl = document.getElementById('formModal');
        modalEl.querySelector('.modal-content').innerHTML = html;
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      });
  }

  function resetUsersForm() {
    const form = document.getElementById('usersForm');
    if (!form) return;

    form.reset();
    form.querySelector('#user_id').value = '';
    form.querySelector('#user_role').value = 'Пользователь';
    form.querySelector('#user_submit').textContent = 'Сохранить';
    form.querySelector('#user_cancel').hidden = true;
  }

  document.addEventListener('click', function (e) {
    const modalTrigger = e.target.closest('[data-modal]');
    if (modalTrigger) {
      e.preventDefault();
      openModal(modalTrigger.href);
      return;
    }

    const deleteLink = e.target.closest('.delete-link');
    if (deleteLink) {
      e.preventDefault();
      if (!confirm('Удалить?')) return;
      fetch(deleteLink.href + '&ajax=1')
        .then(r => r.json())
        .then(() => {
          const row = deleteLink.closest('tr') || deleteLink.closest('.col-md-4');
          if (row) row.remove();
        });
      return;
    }

    const editCategoryBtn = e.target.closest('.edit-category');
    if (editCategoryBtn) {
      const tr = editCategoryBtn.closest('tr');
      document.querySelector('input[name=id]').value = tr.dataset.id;
      document.querySelector('input[name=name]').value = tr.dataset.name;
      document.querySelector('input[name=location]').value = tr.dataset.location;
      return;
    }

    const editUserBtn = e.target.closest('.edit-user');
    if (editUserBtn) {
      const form = document.getElementById('usersForm');
      if (!form) return;

      form.querySelector('#user_id').value = editUserBtn.dataset.id;
      form.querySelector('#user_name').value = editUserBtn.dataset.name;
      form.querySelector('#user_email').value = editUserBtn.dataset.email;
      form.querySelector('#user_role').value = editUserBtn.dataset.role;
      form.querySelector('#user_submit').textContent = 'Обновить';
      form.querySelector('#user_cancel').hidden = false;
      return;
    }

    const deleteUserLink = e.target.closest('.delete-user');
    if (deleteUserLink) {
      e.preventDefault();
      if (!confirm('Удалить пользователя?')) return;

      fetch(deleteUserLink.href + '&ajax=1')
        .then(r => r.json())
        .then(resp => {
          if (!resp.success) return;
          const row = deleteUserLink.closest('tr');
          if (row) row.remove();
          resetUsersForm();
        });
      return;
    }

    if (e.target.id === 'user_cancel') {
      resetUsersForm();
    }
  });

  document.addEventListener('submit', function (e) {
    if (e.target.classList.contains('ajax-form')) {
      e.preventDefault();
      const form = e.target;
      const data = new FormData(form);
      data.append('ajax', 1);
      fetch(form.action, { method: form.method, body: data })
        .then(r => r.json())
        .then(resp => {
          const modalEl = document.getElementById('formModal');
          if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
          if (resp.id) {
            fetch('index.php?card=1&id=' + resp.id)
              .then(r => r.text())
              .then(html => {
                const list = document.querySelector('.row');
                if (list) list.insertAdjacentHTML('afterbegin', html);
              });
          }
        });
      return;
    }

    if (e.target.classList.contains('users-form')) {
      e.preventDefault();
      const form = e.target;
      const data = new FormData(form);
      data.append('ajax', '1');

      fetch(form.action, { method: 'POST', body: data })
        .then(r => r.json())
        .then(resp => {
          if (!resp.success) {
            alert(resp.message || 'Не удалось сохранить пользователя');
            return;
          }

          const tableBody = document.getElementById('usersBody');
          const existingRow = tableBody.querySelector(`tr[data-id="${resp.id}"]`);
          if (existingRow) {
            existingRow.outerHTML = resp.rowHtml;
          } else {
            tableBody.insertAdjacentHTML('afterbegin', resp.rowHtml);
          }
          resetUsersForm();
        });
    }
  });
})();
