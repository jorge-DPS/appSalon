<?php

namespace Model;

class Servicio extends ActiveRecord
{
    //Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        // aqui se define el constructor para traer los datos de la DB tienen que ser los mismos campos de la DB 
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $argc['precio'] ?? '';
    }
}
