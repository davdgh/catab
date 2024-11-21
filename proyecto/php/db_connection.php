<?php
$servername = "localhost"; // Cambia si tu servidor es diferente
$username = "root"; // Coloca tu usuario de base de datos
$password = ""; // Coloca tu contraseña de base de datos
$dbname = "catab1"; // Coloca el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
