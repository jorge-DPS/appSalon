<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        estaAutenticado();
        esAdmin();
        $servicios = Servicio::all();
        $router->render('/servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios,
        ]);
    }
    public static function crear(Router $router)
    {
        estaAutenticado();
        esAdmin();
        $servicio = new Servicio;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas,
        ]);
    }

    public static function actualizar(Router $router)
    {
        estaAutenticado();
        esAdmin();
        if (!is_numeric($_GET['id'])) return;
        $id = $_GET['id'];
        $servicio = Servicio::find($id);
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        $router->render('/servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas,
        ]);
    }

    public static function eliminar()
    {
        estaAutenticado();
        esAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            if (isset($_POST['id'], $_POST['token'])) {
                if ($_SESSION['token'] === $_POST['token']) {
                    // debuguear($_POST['token']);
                    $servicio = Servicio::find($id);
                    $servicio->eliminar();
                    header('Location: /servicios');
                }
            }
        }
    }
}
