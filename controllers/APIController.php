<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all(); // cuando es static no se nesecita instanciar el objeto solo se llama lka funcion statica
        echo json_encode($servicios);
    }

    public static function guardar()
    {

        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $idCita = $resultado['id'];

        // Almacena la Citas con su id y los Servicios de esa cita
        $idServicios = explode(",", $_POST['servicios']);
        // debuguear($_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => (int)$idCita,
                'servicioId' => (int)$idServicio
            ];


            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        };

        // retornamos la respuesta
        echo json_encode(['resultado' => $resultado]);
    }
}
