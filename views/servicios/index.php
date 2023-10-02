<h1 class="nombre-pagina">Servicios</h1>
<h2 class="descripcion-pagina">Administración de Servicios</h2>

<?php
include_once __DIR__ . '/../templates/barra.php'
?>

<ul class="servicios">
    <?php foreach ($servicios as $servicio) : ?>
        <li>
            <p>Nombre: <Span><?php echo $servicio->nombre; ?></Span></p>
            <p>Precio: <Span><?php echo $servicio->precio; ?></Span></p>

            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar Servicio</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <input type="submit" value="Borrar" class="boton-eliminar">
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>