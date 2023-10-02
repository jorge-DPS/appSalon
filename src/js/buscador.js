document.addEventListener("DOMContentLoaded", function () {
    iniciarApp();
});

function iniciarApp() {
    buscarPorfecha();
}

function buscarPorfecha() {
    const fechaInput = document.querySelector("#fecha");
    // cada vez que elija una fecha en el input date lo redirecciona; y asi obtiene las fechas
    fechaInput.addEventListener("input", function (e) { 
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}
