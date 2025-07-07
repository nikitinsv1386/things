// Form validation and AJAX helpers
(function(){
  // validation
  const forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function(form){
    form.addEventListener('submit', function(event){
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

  document.addEventListener('click', function(e){
    if (e.target.dataset.modal !== undefined) {
      e.preventDefault();
      openModal(e.target.href);
      return;
    }
    if (e.target.classList.contains('delete-link')) {
      e.preventDefault();
      if (!confirm('Удалить?')) return;
      fetch(e.target.href + '&ajax=1')
        .then(r => r.json())
        .then(() => {
          const row = e.target.closest('tr') || e.target.closest('.col-md-4');
          if (row) row.remove();
        });
    }
    if (e.target.classList.contains('edit-category')) {
      const tr = e.target.closest('tr');
      document.querySelector('input[name=id]').value = tr.dataset.id;
      document.querySelector('input[name=name]').value = tr.dataset.name;
      document.querySelector('input[name=location]').value = tr.dataset.location;
    }
  });

  document.addEventListener('submit', function(e){
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
    }
  });
})();
