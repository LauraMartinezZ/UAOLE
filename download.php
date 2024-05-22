<?php
include('credentials.php');

$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_GET['file']) && isset($_GET['id'])) {
    $fileName = $_GET['file'];
    $id_proyecto = intval($_GET['id']);
    
    $sql = "SELECT nombre, tipo, contenido FROM ficheros WHERE nombre = ? AND id_proyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $fileName, $id_proyecto);
    $stmt->execute();
    $stmt->bind_result($nombre, $tipo, $contenido);
    $stmt->fetch();

    if ($nombre) {
        header("Content-Type: $tipo");
        header("Content-Disposition: attachment; filename=\"$nombre\"");
        echo $contenido;
    } else {
        echo "Archivo no encontrado.";
    }

    $stmt->close();
} else {
    echo "Datos de descarga no válidos.";
}

$conexion->close();
?>
