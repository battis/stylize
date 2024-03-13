document.getElementById('url').addEventListener('input', (evt) => {
  if (
    evt.target.value.startsWith('webcal://') ||
    evt.target.value.endsWith('.ics')
  ) {
    bootstrap.Tab.getOrCreateInstance(
      document.querySelector('#ics-tab')
    ).show();
  } else {
    bootstrap.Tab.getOrCreateInstance(
      document.querySelector('#html-tab')
    ).show();
  }
});

function copyUrl(withEdit) {
  const search = new URLSearchParams();
  Array.from(document.getElementById('form').elements).forEach((elt) => {
    if (elt.value.length > 0) {
      if (elt.name !== 'edit' || (elt.name === 'edit' && withEdit)) {
        search.append(elt.name, elt.value);
      }
    }
  });
  const url = `${window.location.protocol}//${window.location.host}${
    window.location.pathname
  }?${search.toString()}`;
  navigator.clipboard.writeText(url);
  const div = document.createElement('div');
  div.innerHTML = `<div class="alert alert-warning alert-dismissible fade show m-3" role="alert" data-timeout="5000">
                        <strong>Copied to clipboard</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
        `;
  document.querySelector('#messages').appendChild(div.firstElementChild);

  // https://stackoverflow.com/a/74919974/294171
  let alert_list = document.querySelectorAll('.alert');
  alert_list.forEach(function (alert) {
    new bootstrap.Alert(alert);
    let alert_timeout = alert.getAttribute('data-timeout');
    setTimeout(() => {
      bootstrap.Alert.getInstance(alert).close();
    }, +alert_timeout);
  });
}

document
  .getElementById('copy-button')
  .addEventListener('click', () => copyUrl(false));
document
  .getElementById('edit-button')
  .addEventListener('click', () => copyUrl(true));

// https://stackoverflow.com/a/37630464/294171
function adjustTextarea(elt) {
  elt.style.overflow = 'hidden';
  elt.style.height = 0;
  elt.style.height = elt.scrollHeight + 'px';
}

Array.from(document.querySelectorAll('textarea')).forEach((textarea) => {
  adjustTextarea(textarea);
  textarea.addEventListener('input', () => adjustTextarea(textarea));
});
