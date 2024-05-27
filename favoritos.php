<?php
session_start();

if (!isset($_SESSION["id_usu"])) {
    header("Location: login.php");
    exit();
}

include 'credentials.php';

$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$usuarioId = $_SESSION["id_usu"];
$sql = "SELECT id_proyecto, titulo, portada FROM proyectos
        INNER JOIN favoritos ON proyectos.id_proyecto = favoritos.proyectoid
        WHERE favoritos.usuid = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

$proyectos = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $portada = $fila['portada']; 
        $portadaURL = 'data:image/jpeg;base64,' . base64_encode($portada);
        $fila['portada'] = $portadaURL;
        $proyectos[] = $fila;
    }
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis proyectos</title>
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/mis_proyectos.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="perfil">
            <div class="user">
                <img src="resources/user_icon.png" alt="Foto de perfil">
                <h3>Mis favoritos</h3>
            </div>
            <div class="detalles">
                <div class="items">
                    <?php foreach ($proyectos as $proyecto) : ?>
                        <div class="item">
                            <a class="recuadro" href="item.php?id=<?php echo htmlspecialchars($proyecto['id_proyecto']); ?>">
                                <img src="<?php echo htmlspecialchars($proyecto['portada']); ?>" alt="Portada del proyecto" title="<?php echo htmlspecialchars($proyecto['titulo']); ?>">
                                <h3><?php echo htmlspecialchars($proyecto['titulo']); ?></h3>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>              
            </div>
        </div>
    </div>
</body>
</html>
