<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['selectedSeats']) && !empty($data['selectedSeats'])) {
    $selectedSeats = $data['selectedSeats'];
    $user_id = $_SESSION['user_id'];
    $ruta_id = 2; // Debes determinar cómo obtener la ruta seleccionada
    $autobus_id = 2; // Debes determinar cómo obtener el autobús seleccionado

    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'catab1');

    // Verificar conexión
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]));
    }

    // Obtener el tipo de cliente desde la sesión
    $tipo_cliente = $_SESSION['tipo_cliente'];

    // Verificar el valor de tipo_cliente
    if (!$tipo_cliente) {
        echo json_encode(['success' => false, 'message' => 'Tipo de cliente no está definido en la sesión']);
        $conn->close();
        exit;
    }

    // Obtener el precio del tipo de cliente
    $stmt_precio = $conn->prepare("SELECT precio FROM tipo_cliente WHERE nombre = ?");
    $stmt_precio->bind_param("s", $tipo_cliente);
    if (!$stmt_precio->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener el precio']);
        $stmt_precio->close();
        $conn->close();
        exit;
    }

    $result_prices = $stmt_precio->get_result();
    $prices = $result_prices->fetch_assoc();
    $stmt_precio->close();

    if (!$prices) {
        echo json_encode(['success' => false, 'message' => 'Tipo de cliente no encontrado en la base de datos']);
        $conn->close();
        exit;
    }

    $precio = $prices['precio'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        foreach ($selectedSeats as $seat) {
            $stmt = $conn->prepare("INSERT INTO reservas (usuario_id, ruta_id, autobus_id, numero_asiento, fecha_compra, precio) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?)");
            if (!$stmt) {
                throw new Exception('Error en la preparación de la declaración SQL');
            }
            // Insertar la fecha y el precio correctamente
            $stmt->bind_param('iiisi', $user_id, $ruta_id, $autobus_id, $seat, $precio);
            if (!$stmt->execute()) {
                throw new Exception('Error al procesar la reserva: ' . $stmt->error);
            }
            $stmt->close();
        }

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    // Cerrar la conexión
    $conn->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
