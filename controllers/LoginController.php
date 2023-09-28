<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        $auth = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                //echo 'usuario inicio sesion';
                // Comprobar que exista el usuario
                $usuario = Usuario::donde('email', $auth->email);
                if ($usuario) {
                    //Verificar el password

                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // redireccionamiento 
                        if ($usuario->admin === '1') {
                            //debuguear('es admin');
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }

                        debuguear($_SESSION);
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth,
        ]);
    }
    public static function logout()
    {
        // echo 'desde el logout';
        estaAutenticado();
        $_SESSION = []; // -> aqui borramos la session
        header('Location: /');
    }

    // olvide mi contraseña
    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::donde('email', $auth->email);
                if ($usuario && $usuario->confirmado === '1') {
                    //debuguear('si esiste y esta confirmado');
                    // Generar un token 
                    $usuario->generarToken();
                    $usuario->guardar();

                    // Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    //$alertas = Usuario::getAlertas();
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta Confirmado');
                    //$alertas = Usuario::getAlertas();
                }
            }
        }
        $alertas = Usuario::getAlertas();


        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::donde('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo ṕassword
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->encriptarPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar(); // -> devuelve un true
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router)
    {

        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // echo 'Enviaste el formulario';
            $usuario->sincronizar($_POST);
            // debuguear($_POST);
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
                    // debuguear($usuario->token);

                    //Enviar el email (depues de generar el token)
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    // debuguear($resultado);
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {

        $alertas = [];
        $token = s($_GET['token']);

        $usuario = Usuario::donde('token', $token);

        if (empty($usuario)) {
            //echo 'token no valido';
            // mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //echo 'token valido, confirmando usuario';
            //Modificar a usuario confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();

        // Renderizar vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
