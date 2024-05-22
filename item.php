<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('credentials.php');

// Conectar a la base de datos
$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID del proyecto desde la URL, si no se proporciona usar un valor predeterminado (23 en este caso)
$id_proyecto = isset($_GET['id']) ? intval($_GET['id']) : 23;

// Consultar los detalles del proyecto y el nombre del usuario
$sql_proyecto = "SELECT p.titulo, p.descripcion, p.fecha, p.autor, p.portada, t.nombre AS titulacion, u.nombre AS autor_nombre
                 FROM proyectos p
                 JOIN titulacion t ON p.titulacion = t.id_titulacion
                 JOIN usuarios u ON p.autor = u.id_usu
                 WHERE p.id_proyecto = ?";
$stmt_proyecto = $conexion->prepare($sql_proyecto);
$stmt_proyecto->bind_param("i", $id_proyecto);
$stmt_proyecto->execute();
$result_proyecto = $stmt_proyecto->get_result();
$proyecto = $result_proyecto->fetch_assoc();

// Consultar los archivos relacionados
$sql_ficheros = "SELECT nombre, extension, tipo, contenido 
                 FROM ficheros 
                 WHERE id_proyecto = ?";
$stmt_ficheros = $conexion->prepare($sql_ficheros);
$stmt_ficheros->bind_param("i", $id_proyecto);
$stmt_ficheros->execute();
$result_ficheros = $stmt_ficheros->get_result();

$archivos = [];
while ($row = $result_ficheros->fetch_assoc()) {
    $archivos[] = $row;
}

// Cerrar las declaraciones y la conexión
$stmt_proyecto->close();
$stmt_ficheros->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del contenido</title>
    <link rel="stylesheet" href="css/item.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        body {
            background-image: url('data:image/jpeg;base64,<?php echo base64_encode($proyecto['portada']); ?>');
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="overlay"></div>
<main>
<div class="container">
    <h1><?php echo htmlspecialchars($proyecto['titulo']); ?></h1>
    <h2>Proyecto de <?php echo htmlspecialchars($proyecto['titulacion']); ?></h2>
    <div class="project-details">
        <p><?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?></p>
        <br>
        <p>Fecha: <?php echo htmlspecialchars($proyecto['fecha']); ?></p>
        <p>Autor: <?php echo htmlspecialchars($proyecto['autor_nombre']); ?></p>
    </div>
    <h2>Archivos enlazados</h2>
    <div class="carrusel">
        <?php foreach ($archivos as $archivo): ?>
            <div class="item">
                <a class="recuadro" href="download.php?file=<?php echo urlencode($archivo['nombre']); ?>&id=<?php echo $id_proyecto; ?>">
                    <img src="./resources/download.png" alt="Descarga">
                    <p><?php echo htmlspecialchars($archivo['nombre']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

