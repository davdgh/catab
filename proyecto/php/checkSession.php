<?php
session_start();

$response = [
    'loggedin' => false,
    'nombre' => '',
    'correo' => '',
    'tipo_cliente' => ''
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $response['loggedin'] = true;
    $response['nombre'] = $_SESSION['nombre'];
    $response['correo'] = $_SESSION['email'];
    $response['tipo_cliente'] = $_SESSION['tipo_cliente'];
}

echo json_encode($response);
