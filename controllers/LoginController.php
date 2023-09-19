<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $router->render('auth/login', []);
    }
    public static function logout()
    {
        echo 'desde el logout';
    }

    public static function olvide(Router $router)
    {
        $router->render('auth/olvide-password', []);
    }
    public static function crear(Router $router)
    {

        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // echo 'Enviaste el formulario';
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alerta este vacio
            if (empty($alertas)) {
                // echo 'pasaste la validacion';
                // Verficar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // no esta registrado
                    // encriptar password
                    $usuario->encriptarPassword();

                    // Generar Token unico
                    $usuario->generarToken();

                    //Enviar el email (depues de generar el token)
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
                    debuguear($usuario);
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}
