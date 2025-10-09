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
