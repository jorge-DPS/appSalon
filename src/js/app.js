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
    seleccionarHora(); // Añada la hora de la cita en el objeto
    mostrarResumen(); // Muestra el resumen de la cita
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

        mostrarResumen();
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
            cita.fecha = e.target.value = "";
            mostrarAlerta(
                "Fines de semana no permitidos",
                "error",
                "#alertas-formulario"
            );
        } else {
            // console.log("corecto");
            cita.fecha = e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // validar que solo se muestre una sola vez la laerta
    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece) {
        // Elminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
        // console.log(alerta);
    }
}

function seleccionarHora() {
    const inputHora = document.querySelector("#hora");

    inputHora.addEventListener("input", function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 18) {
            // console.log("Horas no validas");
            cita.hora = e.target.value = "";
            mostrarAlerta("hora No Válida", "error", "#alertas-formulario");
        } else {
            // console.log("hora valida");
            cita.hora = e.target.value;
            console.log(cita);
        }
    });
}

function mostrarResumen() {
    const resumen = document.querySelector(".contenido-resumen");
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        // console.log("hacen falta datos o servicios");
        mostrarAlerta(
            "Faltan datos de Servicios, Fecha u Hora",
            "error",
            ".contenido-resumen",
            false
        );
        return;
    }
    const existeAlerta = document.querySelector(".alerta");
    if (existeAlerta) {
        existeAlerta.remove();
        // console.log("todo bien");
    }

    const { nombre, fecha, hora, servicios } = cita;

    // Heading para Cita en Resumen
    const headingCita = document.createElement("H3");
    headingCita.textContent = "Resumen de Cita";

    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement("P");
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre} `;

    // formatear la fecha

    const fechaObj = new Date(fecha + " 00:00");
    const opciones = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    const fechaFormateada = fechaObj.toLocaleDateString("es-MX", opciones);
    console.log(fechaFormateada);

    const fehcaCita = document.createElement("P");
    fehcaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada} `;

    const horaCita = document.createElement("P");
    horaCita.innerHTML = `<span>Hora:</span> ${hora} `;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fehcaCita);
    resumen.appendChild(horaCita);

    // Heading para Servicios en Resumen
    const headingServicios = document.createElement("H3");
    headingServicios.classList.add("head-servicio");
    headingServicios.textContent = "Resumen de Servicios";

    resumen.appendChild(headingServicios);
    // mostrarndo los servicios
    servicios.forEach((servicio) => {
        const { id, precio, nombre } = servicio;
        const contenedorServicios = document.createElement("DIV");
        contenedorServicios.classList.add("contenedor-servicio");

        const nameServicio = document.createElement("P");
        nameServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio:</span> ${precio} `;

        contenedorServicios.appendChild(nameServicio);
        contenedorServicios.appendChild(precioServicio);

        resumen.appendChild(contenedorServicios);
    });

    console.log(nombreCliente);
}
