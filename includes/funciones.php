<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// verificcar si eta autenticado

// function estaAutenticado(): void
// {
//     if (!isset($_SESSION['login'])) {
//         // no esta autenticado
//         header('Location: /');
//     }
// }

function esUltimo(string $actual, string $proximo): bool
{
    if ($actual !== $proximo) {
        return true;
    }

    return false;
}

// funcion que revisa que el usuario este auntenticado
function estaAutenticado(): void
{
    if (!isset($_SESSION)) {
        session_start();
    } elseif (!isset($_SESSION['login'])) {
        // no esta autenticado; lo redirige al login
        header('Location: /');
    }
}

function esAdmin(): void
{
    if (!isset($_SESSION['admin'])) {
        header('Location:/');
    }
}