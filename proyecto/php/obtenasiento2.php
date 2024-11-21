<?php
session_start();
header('Content-Type: application/json');

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'catab1');

// Verificar conexión
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$ruta_id = 2; // Aquí debes definir cómo obtener la ruta
$autobus_id = 2; // Aquí debes definir cómo obtener el autobús

$sql = "SELECT numero_asiento FROM reservas WHERE ruta_id = ? AND autobus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $ruta_id, $autobus_id);
$stmt->execute();
$result = $stmt->get_result();

$reservedSeats = [];
while ($row = $result->fetch_assoc()) {
    $reservedSeats[] = $row['numero_asiento'];
}

// Cerrar conexión
$stmt->close();
$conn->close();

echo json_encode(['reservedSeats' => $reservedSeats]);
