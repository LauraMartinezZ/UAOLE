<?php
// Obtener el ID del proyecto desde la URL
$idProyecto = isset($_GET['id']) ? intval($_GET['id']) : 0;

include('credentials.php');

// Conexión a la base de datos
$conn = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los datos de la imagen desde la base de datos
$sql = "SELECT * FROM proyectos WHERE id_proyecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idProyecto);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Obtener los datos de la imagen almacenados en la base de datos
    $row = $result->fetch_assoc();
    if($row["portada"]){
        $imagen = $row["portada"];
        echo $imagen;

    }else{
    mostrarImagenDeReemplazo();
        
    }

    // Establecer el tipo de contenido para la imagen
} else {
    // Mostrar imagen de reemplazo si no se encuentra la imagen
}

// Función para mostrar imagen de reemplazo
function mostrarImagenDeReemplazo() {
    $rutaImagenReemplazo = 'resources/ejemplo.jpg';
    if (file_exists($rutaImagenReemplazo)) {
        header("Content-Type: image/jpeg");
        readfile($rutaImagenReemplazo);
    } else {
        die("No se encuentra la imagen de reemplazo.");
    }
}

// Cierra la conexión
$conn->close();
?>
