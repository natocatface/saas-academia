// Sidebar toggle (móvil)
document.addEventListener('DOMContentLoaded', function () {
  const sb = document.getElementById('sidebar');
  const ov = document.getElementById('overlay');
  const hb = document.getElementById('hamb');
  if (hb) hb.addEventListener('click', () => { sb.classList.toggle('open'); ov.classList.toggle('show'); });
  if (ov) ov.addEventListener('click', () => { sb.classList.remove('open'); ov.classList.remove('show'); });

  // Dropdowns
  document.querySelectorAll('[data-dropdown]').forEach(d => {
    d.addEventListener('click', e => { e.stopPropagation(); d.classList.toggle('open'); });
  });
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
  });

  // Confirmación de borrado
  document.querySelectorAll('form[data-confirm]').forEach(f => {
    f.addEventListener('submit', e => {
      if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  // Animar barras de progreso y anillos
  document.querySelectorAll('.prog__fill[data-w]').forEach(el => {
    setTimeout(() => el.style.width = el.getAttribute('data-w') + '%', 100);
  });
});
