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
$sql_proyecto = "SELECT p.titulo, 
                        p.descripcion, 
                        p.fecha, 
                        p.autor, 
                        p.portada, 
                        t.nombre AS titulacion, 
                        u.nombre AS autor_nombre, 
                        (SELECT COUNT(*) 
                         FROM favoritos 
                         WHERE favoritos.proyectoid = p.id_proyecto) AS likes
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

if(isset($_SESSION['id_usu'])) {
    $sql1 = "SELECT 1 FROM favoritos WHERE usuid = '{$_SESSION['id_usu']}' AND proyectoid = '{$id_proyecto}' LIMIT 1";
    $result1 = $conexion->query($sql1);
    if ($result1 === false) {
        die("Error en la consulta: " . $conexion->error);
    }
    $liked = $result1->num_rows > 0 ? "liked" : "";
} else {
    $liked = "";
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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
        <?php
        if(isset($_SESSION['id_usu'])) {
            echo '
            <div id="like-'.$id_proyecto.'" onclick="darLike('.$id_proyecto.')" class="likes ' . $liked . '">
                <span class="material-symbols-outlined">favorite</span>
                <p id="likes-count-'.$id_proyecto.'" class"likes-count">'. htmlspecialchars($proyecto['likes'], ENT_QUOTES, 'UTF-8') .'</p>
            </div>';
        }
        ?>
    </div>
    <h2>Archivos enlazados</h2>
    <br>
    <br>
    <?php if(!isset($_SESSION['id_usu'])): ?>
        <p class="login-message">Por favor <a href="login.php">inicia sesión</a> para descargar archivos.</p>
    <?php endif; ?>
    <div class="carrusel">
        <?php foreach ($archivos as $archivo): ?>
            <div class="item">
                <?php if(isset($_SESSION['id_usu'])): ?>
                    <a class="recuadro" href="download.php?file=<?php echo urlencode($archivo['nombre']); ?>&id=<?php echo $id_proyecto; ?>">
                        <img src="./resources/download.png" alt="Descarga">
                        <p><?php echo htmlspecialchars($archivo['nombre']); ?></p>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php 
        if (isset($_SESSION['id_usu']) && $_SESSION['id_usu'] == $proyecto['autor']) {
            echo '
            <form method="POST" action="eliminar.php" onsubmit="return confirm(\'¿Estás seguro de que deseas eliminar este proyecto?\');">
                <input type="hidden" name="id_proyecto" value="' . htmlspecialchars($id_proyecto) . '">
                <button type="submit" class="btn-delete">Eliminar Proyecto</button>
            </form>';
        }
    ?>
</div>

<script src="js/index.js"></script>

</body>
</html>



