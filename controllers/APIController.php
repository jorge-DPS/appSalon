<?php

namespace Controllers;

use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all(); // cuando es static no se nesecita instanciar el objeto solo se llama lka funcion statica
        echo json_encode($servicios);
    }
}
