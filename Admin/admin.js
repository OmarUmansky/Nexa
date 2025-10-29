// Función para mostrar la ventana seleccionada
function mostrarVentana(id) {
  const ventanas = document.querySelectorAll('.ventana');
  ventanas.forEach(v => v.classList.remove('ventana-activa'));
  const ventanaSeleccionada = document.getElementById(id);
  if (ventanaSeleccionada) ventanaSeleccionada.classList.add('ventana-activa');
}

// Buscador en sección Usuarios
window.addEventListener("load", function() {
  const buscador = document.getElementById("buscadorUsuarios");
  if(!buscador) return;

  buscador.addEventListener("keyup", function() {
      mostrarVentana('usuarios');
      const texto = buscador.value.toLowerCase();
      const filas = document.querySelectorAll("#usuarios table tbody tr");
      filas.forEach(fila => {
          const nombre = fila.cells[1].textContent.toLowerCase();
          const rol = fila.cells[3] ? fila.cells[3].textContent.toLowerCase() : '';
          fila.style.display = (nombre.includes(texto) || rol.includes(texto)) ? "" : "none";
      });
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('userFuncsBtn');
  const menu = document.getElementById('userFuncsMenu');
  if (!btn || !menu) return;
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    menu.classList.toggle('show');
    btn.classList.toggle('open');
  });
});
