<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        estaAutenticado();
        esAdmin();
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);
        if (!checkdate($fechas[1], $fechas[2], $fechas[0])) { // -> checkdate retorna un true o un false
            header('Location: /404');
        }

        // Consultar a la base de datos
        // debuguear($_SESSION);

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre,' '
        ,usuarios.apellido) as cliente, usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio
        FROM citas 
        LEFT OUTER JOIN usuarios 
        ON citas.usuarioId=usuarios.id 
        LEFT OUTER JOIN citasServicios 
        ON citasServicios.citaId=citas.id
        LEFT OUTER JOIN servicios
        ON servicios.id=citasServicios.servicioId
        WHERE fecha='$fecha'";

        $citas = AdminCita::SQL($consulta);
        $token = uniqid();
        // debuguear($token); // -> siempre que da clicck a la barra ver citas cambia el toñekn
        $_SESSION['token'] = $token;

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha,
        ]);
    }
}