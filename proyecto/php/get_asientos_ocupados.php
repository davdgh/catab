<?php
// get_asientos_ocupados.php

header('Content-Type: application/json');

$mysqli = new mysqli('localhost', 'root', '', 'catab1');

if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión']);
    exit;
}

$result = $mysqli->query("SELECT numero_asiento FROM reservas WHERE ruta_id = 1"); // Usa el ID de ruta correspondiente
$ocupados = [];

while ($row = $result->fetch_assoc()) {
    $ocupados[] = $row['numero_asiento'];
}

$mysqli->close();

echo json_encode(['ocupados' => $ocupados]);
