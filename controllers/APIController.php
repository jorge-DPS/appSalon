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

    public static function eliminar()
    {
        // echo 'hola desde eliminar cira';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si se recibieron los datos del formulario
            if (isset($_POST['id'], $_POST['token'])) {
                // Verificar si el token es válido
                if ($_POST['token'] === $_SESSION['token']) {

                    // Eliminar la cita
                    // debuguear($_POST);
                    $id = $_POST['id'];
                    $cita = Cita::find($id);
                    $cita->eliminar();
                    header('Location:' . $_SERVER['HTTP_REFERER']); // HTTP_REFERER -> es la infomracion, de donde esta vieniendo para volver

                } else {

                    // El token no es válido
                    // Manejar el error o redirigir a una página de error
                    echo "Error: token no válido";
                }
            }
            // // debuguear($_SESSION);
            // $id = $_POST['id'];
            // $cita = Cita::find($id);
            // $cita->eliminar();
            // header('Location:' . $_SERVER['HTTP_REFERER']); // HTTP_REFERER -> es la infomracion, de donde esta vieniendo para volver
        }
    }
}