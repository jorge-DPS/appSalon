<?php
foreach ($alertas as $alerta => $mensajes) :
    foreach ($mensajes as $mensaje) :
?>

        <div class="alerta <?php echo $alerta; ?>">
            <?php echo $mensaje;?>
        </div>

<?php
    endforeach;

endforeach;

?>
