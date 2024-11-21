<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultas
$usuarios_query = "SELECT * FROM usuarios";
$terminales_query = "SELECT * FROM terminales";
$autobuses_query = "SELECT * FROM autobuses";
$rutas_query = "SELECT * FROM rutas";
$reservas_query = 
"SELECT r.id as ID, u.nombre as Usuario, u.tipo_cliente as 'Tipo cliente', r.numero_asiento as 'Número de asiento', ru.origen as Origen, ru.destino as Destino, r.fecha_compra, r.precio
FROM reservas as r
INNER JOIN usuarios as u on u.id=r.usuario_id
INNER JOIN rutas as ru on ru.id=r.ruta_id
order by r.numero_asiento asc";

$usuarios_result = $conn->query($usuarios_query);
$terminales_result = $conn->query($terminales_query);
$autobuses_result = $conn->query($autobuses_query);
$rutas_result = $conn->query($rutas_query);
$reservas_result = $conn->query($reservas_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Administrador</title>
    <link rel="stylesheet" href="vista_admi.css"> <!-- Agrega tu CSS aquí -->
</head>
<body>
    <div class="flex flex-col w-full min-h-screen">
        <header class="header">
            <nav class="nav">
                <a href="index.html">
                    <img class="logo" src="public/logo.png" alt="Logo"/>
                </a>
                <li class="anchors">
                    <a href="terminales.html">Terminales</a>
                    |
                    <a href="rutas.html">Rutas y Horarios</a>
                    |
                    <a href="promociones.html">Promociones</a>
                    |
                    <a href="inicio_sesion.html"><img src="public/icons8-user-24.png" alt="Inicio de sesión"></a>
                    
                    <div id="user-info"></div>
                </li>
            </nav>
        </header>

        <main>
            <?php if($_SESSION['admin'] ?? true): ?>
            <section>
                <h2>Usuarios</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Tipo de Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $usuarios_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['correo']; ?></td>
                                <td><?php echo $row['telefono']; ?></td>
                                <td><?php echo $row['tipo_cliente']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section>
                <h2>Terminales</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ubicación</th>
                            <th>Ciudad</th>
                            <th>Código Postal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $terminales_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['ubicacion']; ?></td>
                                <td><?php echo $row['ciudad']; ?></td>
                                <td><?php echo $row['codigo_postal']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section>
                <h2>Autobuses</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Placa</th>
                            <th>Número de Asientos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $autobuses_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['placa']; ?></td>
                                <td><?php echo $row['numero_asientos']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section>
                <h2>Rutas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Origen</th>
                            <th>Destino</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $rutas_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['origen']; ?></td>
                                <td><?php echo $row['destino']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section>
                <h2>Reservas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Número de Asiento</th>
                            <th>Fecha de Compra</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $reservas_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['ID']; ?></td>
                                <td><?php echo $row['Usuario']; ?></td>
                                <td><?php echo $row['Origen']; ?></td>
                                <td><?php echo $row['Destino']; ?></td>
                                <td><?php echo $row['Número de asiento']; ?></td>
                                <td><?php echo $row['fecha_compra']; ?></td>
                                <td>$<?php echo $row['precio']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <?php else: ?>
            <h1>
                ¿Entraste a no debías?.
                <a href="index.html">Regresar al apartado principal</a>
            </h1>
            <?php endif; ?>
        </main>
    </div>
    <script src="checkSession.js"></script> <!-- Script para manejar la sesión -->
</body>
</html>
