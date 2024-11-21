<?php
session_start(); // Iniciar la sesión

header('Content-Type: application/json');

// Configura la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catab1"; // Asegúrate de que el nombre de la base de datos es correcto

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
if(!$email = $_POST['email']) {
    echo json_encode(['success' => false, 'message' => 'Agregue su correo electrónico']);
    exit();
}
if(!$password = $_POST['password']) {
    echo json_encode(['success' => false, 'message' => 'Agregue su contraseña']);
    exit();
}

// Prepara y ejecuta la consulta
$sql = $conn->prepare("SELECT id, nombre, contraseña, tipo_cliente FROM usuarios WHERE correo = ?");
$sql->bind_param("s", $email);
$sql->execute();
$result = $sql->get_result();

$response = [];

if($result->num_rows <= 0) {
    $response['success'] = false;
    $response['message'] = 'Correo y/o contraseña incorrectas';
    echo json_encode($response);
    exit();
}

// Usuario encontrado
$user = $result->fetch_assoc();

if(!password_verify($password, $user['contraseña'])) {
    $response['success'] = false;
    $response['message'] = 'Correo y/o contraseña incorrectas';
    echo json_encode($response);
    exit();
}

// Establecer sesión
$_SESSION['nombre'] = $user['nombre'];
$_SESSION['loggedin'] = true;
$_SESSION['email'] = $email;
$_SESSION['tipo_cliente'] = $user['tipo_cliente'];
$_SESSION['user_id'] = $user['id'];

// Verificar tipo de cliente y redirigir
if ($user['tipo_cliente'] === 'administrador') {
    $_SESSION['admin'] = true;
    $response['redirect'] = 'admin_view.php'; // Indicar que se debe redirigir a vista_admin.html
} else {
    $response['redirect'] = 'index.html'; // Indicar que se debe redirigir a una página de inicio normal
}

$response['success'] = true;
$response['nombre'] = $user['nombre'];

// Cierra la conexión
$sql->close();
$conn->close();

// Retorna respuesta en formato JSON
echo json_encode($response);
exit();
