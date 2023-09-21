<?php

namespace Model;

class Usuario extends ActiveRecord
{
    //Base de datos 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validacion para la crecion de uuna cuenta

    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del cliente es obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido del cliente es obligatorio';
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = 'El Telefono del cliente es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El Email del cliente es obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener almenos 6 caracteres';
        }

        return self::$alertas;
    }

    // validar el formulario de incio de sesion
    public function validarLogin(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }

        return self::$alertas;
    }

    // Validar el email
    public function validarEmail(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        return self::$alertas;
    }

    // Validar Password
    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'el password es obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener almenos 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario()
    {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya existe';
        }
        return $resultado;
    }

    public function encriptarPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        // Verifica si el password es exactamente mismo cuando ingresa en el fomulario y de la base de datos
        $resultado = password_verify($password, $this->password);
        if (!$resultado || !$this->confirmado) {
            //debuguear('el usuario no esta confirmado');
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmado';
        }else {
            //debuguear('El usuario si esta confirmado');
            return true;
        }
    }
}
