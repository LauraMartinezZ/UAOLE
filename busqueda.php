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

$busqueda = "";

function truncateString($string, $length = 10) {
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}

if (isset($_POST['busqueda'])) {
    $busqueda = $conexion->real_escape_string($_POST['busqueda']);

    $sql = "SELECT proyectos.*, usuarios.nombre AS autor_nombre, titulacion.nombre AS titulacion_nombre
            FROM proyectos 
            LEFT JOIN usuarios ON proyectos.autor = usuarios.id_usu
            LEFT JOIN titulacion ON proyectos.titulacion = titulacion.id_titulacion
            WHERE proyectos.titulo LIKE '%$busqueda%'
               OR usuarios.nombre LIKE '%$busqueda%'
               OR titulacion.nombre LIKE '%$busqueda%'";

    $result = $conexion->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conexion->error);
    }
}

$usuarioId = $_SESSION["id_usu"];
$sql_proyectos = "SELECT id_proyecto, titulo, portada FROM proyectos WHERE autor = ?";
$stmt = $conexion->prepare($sql_proyectos);

if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado_proyectos = $stmt->get_result();

$proyectos = [];
if ($resultado_proyectos->num_rows > 0) {
    while ($fila = $resultado_proyectos->fetch_assoc()) {
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
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/busqueda.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="perfil">
            <form action="" method="post">
                <div class="barrita">
                    <input type="text" name="busqueda" placeholder="Filtra por título, facultad, autor.." value="<?php echo htmlspecialchars($busqueda); ?>">
                    <button type="submit">Buscar</button>
                </div>
                <div class="user">
                    <h3>Resultados de Búsqueda</h3>
                </div>
            </form>
            <div class="detalles">
                <div class="items">
                    <?php if (isset($result) && $result->num_rows > 0) : ?>
                        <?php while($row = $result->fetch_assoc()) : ?>
                            <div class="item">
                                <a class="recuadro" href="item.php?id=<?php echo htmlspecialchars($row['id_proyecto']); ?>">
                                    <img src="mostrar_imagen.php?id=<?php echo htmlspecialchars($row['id_proyecto']); ?>" alt="Portada del proyecto" title="<?php echo htmlspecialchars($row['titulo']); ?>">
                                    <h3><?php echo htmlspecialchars(truncateString($row['titulo'])); ?></h3>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <h2>No se encontraron resultados para '<?php echo htmlspecialchars($busqueda); ?>'.</h2>
                    <?php endif; ?>
                </div>              
            </div>
        </div>
    </div>
</body>
</html>
