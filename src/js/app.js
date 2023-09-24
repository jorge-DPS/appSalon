let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: "",
    fecha: "",
    hora: "",
    servicios: [],
};

document.addEventListener("DOMContentLoaded", function () {
    iniciarApp();
});

function iniciarApp() {
    // Mostrando la seccion por defecto
    mostrarSeccion();
    tabs(); // cambia la seccion cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();

    // API
    consultarAPI(); //Consulta la API en el backen de php
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
}

function tabs() {
    const botones = document.querySelectorAll(".tabs button");

    botones.forEach((boton) => {
        boton.addEventListener("click", function (e) {
            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
        });
    });
}

// mostrando la seccion clikeada
function mostrarSeccion() {
    //ocultar la sección que tenga la clase mosrtar
    const seccionAnterior = document.querySelector(".mostrar");
    if (seccionAnterior) {
        seccionAnterior.classList.remove("mostrar");
    }

    // Seleccionar la seccion con el paso.... clickeado
    const seccion = document.querySelector(`#paso-${paso}`);
    // console.log(seccion);
    seccion.classList.add("mostrar");

    // Remueve la clase "acutal" del anterior tab
    const tabAnterior = document.querySelector(".actual");
    if (tabAnterior) {
        tabAnterior.classList.remove("actual");
    }

    // Resalta el tab acutal
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add("actual");
}

function botonesPaginador() {
    const botonAnterior = document.querySelector("#anterior");
    const botonSiguiente = document.querySelector("#siguiente");

    if (paso === 1) {
        botonAnterior.classList.add("ocultar");
        botonSiguiente.classList.remove("ocultar");
    } else if (paso === 3) {
        botonAnterior.classList.remove("ocultar");
        botonSiguiente.classList.add("ocultar");
    } else {
        botonAnterior.classList.remove("ocultar");
        botonSiguiente.classList.remove("ocultar");
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector("#anterior");
    paginaAnterior.addEventListener("click", function () {
        if (paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector("#siguiente");
    paginaSiguiente.addEventListener("click", function () {
        if (paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    });
}

async function consultarAPI() {
    try {
        const url = "http://localhost:8000/api/servicios";
        const resultado = await fetch(url);
        const servicios = await resultado.json();

        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach((servicio) => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.idServicio = id;

        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        // console.log(servicioDiv);
        document.querySelector("#servicios").appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si un servicio ya fue agregado
    if (servicios.some((agregado) => agregado.id === servicio.id)) {
        // console.log("ya esta agregado");
        // Eliminarlo
        cita.servicios = servicios.filter((agregado) => agregado.id !== id); // -> retorna true cuando no coincide con ningun elemtno del arreglo; false si coincide con algun elemento y lo saca del arreglo, lo retorna
        divServicio.classList.remove("seleccionado");
    } else {
        // console.log("no estaba agregado");
        // Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add("seleccionado");
    }
    // console.log(cita.servicios);

    console.log(cita);
}

function nombreCliente() {
    cita.nombre = document.querySelector("#nombre").value;
    // cita.nombre = nombre;
    // console.log(nombre);
}

function seleccionarFecha() {
    const inputFecha = document.querySelector("#fecha");
    inputFecha.addEventListener("input", function (e) {
        const dia = new Date(e.target.value).getUTCDay(); // -> retorna un valor, sabado = 6; domingo = 0
        if ([6, 0].includes(dia)) {
            console.log("sab y dom no abrimos");
        } else {
            // console.log("corecto");
            cita.fecha = e.target.value;
        }
    });
}
