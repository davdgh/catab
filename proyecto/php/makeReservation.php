<?php
session_start();
include 'db_connection.php'; // Incluye tu archivo de conexión a la base de datos

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
    exit;
}

$stmt_precio = $conn->prepare("SELECT precio FROM tipo_cliente WHERE nombre = ?");
$stmt_precio->bind_param("s", $_SESSION['tipo_cliente']);
if (!$stmt_precio->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener el precio']);
    exit;
}

$result_prices = $stmt_precio->get_result();
$prices = $result_prices->fetch_assoc();

$price = $prices['precio'];
$user_id = $_SESSION['user_id'];
$ruta_id = 1;
$autobus_id = 1;
$seats = explode(',', $_POST['seats']);

foreach ($seats as $seat) {
    $stmt = $conn->prepare("INSERT INTO reservas (usuario_id, ruta_id, autobus_id, numero_asiento, fecha_compra, precio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiisd", $user_id, $ruta_id, $autobus_id, $seat, 'NOW()', $price);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva.']);
        exit;
    }
}

echo json_encode(['success' => true]);