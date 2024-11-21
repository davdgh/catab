<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    // Simulación de datos del usuario
    $user_id = $_SESSION['user_id'];
    
    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'catab1');
    
    // Verificar conexión
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }
    
    // Consultar datos del usuario
    $sql = "SELECT nombre, correo, tipo_cliente FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Cerrar conexión
    $stmt->close();
    $conn->close();
    
    echo json_encode($user);
} else {
    echo json_encode([
        'nombre' => 'Invitado',
        'correo' => '',
        'tipo_cliente' => 'completo'
    ]);
}
?>
