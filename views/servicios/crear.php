<h1 class="nombre-pagina">Nuevo Servicios</h1>
<h2 class="descripcion-pagina">llena todos los campos para añadir un nuevo servicio</h2>

<?php
include_once __DIR__ . '/../templates/barra.php';
include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" class="boton" value="Guardar Servicio">
</form>