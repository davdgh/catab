<?php
session_start();

header('Content-Type: application/json');

$datos[
    'name' => $_SESSION['nombre'],
    'correo' => $_SESSION['email'],
    'tipo_cliente' => $_SESSION['tipo_cliente']
];

echo json_encode($datos);
exit;