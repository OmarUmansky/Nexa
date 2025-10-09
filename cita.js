const select = document.getElementById("servicio");
const botones = document.querySelectorAll(".btn-servicio");
const btnBarberia = botones[0];
const btnPeluqueria = botones[1];

const barberia = [
  { value: "1", text: "Corte de cabello" },
  { value: "5", text: "Arreglo de barba" },
  { value: "17", text: "Arreglo de bigote" }
];

const peluqueria = [
  { value: "2", text: "Tintado" },
  { value: "3", text: "Tratamiento capilar" },
  { value: "4", text: "Lavado de cabello" },
  { value: "6", text: "Brushing" },
  { value: "7", text: "Coloración" },
  { value: "8", text: "Claritos" },
  { value: "9", text: "Servicio maquillaje" },
  { value: "10", text: "Mantenimiento de cabello" },
  { value: "11", text: "Botox" },
  { value: "12", text: "Pelo dañado" },
  { value: "13", text: "Keratina" },
  { value: "14", text: "Baño de crema" },
  { value: "15", text: "Celulas madre" },
  { value: "16", text: "Tratamiento de ampollas" }
];

function cargarOpciones(lista) {
  select.innerHTML = "";
  lista.forEach(op => {
    const option = document.createElement("option");
    option.value = op.value;
    option.textContent = op.text;
    select.appendChild(option);
  });
}

function activarBoton(boton) {
  botones.forEach(b => b.classList.remove("activo"))
  boton.classList.add("activo");
}

btnBarberia.addEventListener("click", () => {
  cargarOpciones(barberia);
  activarBoton(btnBarberia);
});

btnPeluqueria.addEventListener("click", () => {
  cargarOpciones(peluqueria);
  activarBoton(btnPeluqueria);
});