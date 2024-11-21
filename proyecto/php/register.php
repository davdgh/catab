<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catab1";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit();
}

// Obtener datos del POST
$nombre = $_POST['nombre'];
$correo = $_POST['email'];
$telefono = $_POST['telefono'];
$contraseña = $_POST['password'];
$tipo_cliente = $_POST['tipo_cliente'];

// Validar datos
if (empty($nombre) || empty($correo) || empty($telefono) || empty($contraseña) || empty($tipo_cliente)) {
    echo json_encode(['message' => 'Todos los campos son obligatorios.']);
    exit();
}

// Verificar tipo de cliente
$tipos_validos = ['estudiante', 'adulto_mayor', 'completo'];
if (!in_array($tipo_cliente, $tipos_validos)) {
    echo json_encode(['message' => 'Tipo de cliente inválido.']);
    exit();
}

// Encriptar contraseña
$contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT);

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, correo, telefono, contraseña, tipo_cliente) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['message' => 'Error al preparar la consulta: ' . $conn->error]);
    exit();
}

$stmt->bind_param("sssss", $nombre, $correo, $telefono, $contraseñaHash, $tipo_cliente);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Usuario registrado exitosamente']);
} else {
    echo json_encode(['message' => 'Error al registrar usuario: ' . $stmt->error]);
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
