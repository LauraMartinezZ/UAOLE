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

// Verificar si se ha enviado un término de búsqueda
if (isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];


    $sql_titulacion = "SELECT id_titulacion FROM titulacion WHERE nombre LIKE '%$busqueda%'";
    $resultado_titulacion = $conexion->query($sql_titulacion);

 
    $sql_autor = "SELECT id_usu FROM usuarios WHERE nombre LIKE '%$busqueda%'";
    $resultado_autor = $conexion->query($sql_autor);

    // Verificar si se encontró la titulación o el autor
    if ($resultado_titulacion->num_rows > 0 || $resultado_autor->num_rows > 0) {
        $titulacion_id = null;
        $autor_id = null;

        
        if ($resultado_titulacion->num_rows > 0) {
            $fila_titulacion = $resultado_titulacion->fetch_assoc();
            $titulacion_id = $fila_titulacion['id_titulacion'];
        }

      
        if ($resultado_autor->num_rows > 0) {
            $fila_autor = $resultado_autor->fetch_assoc();
            $autor_id = $fila_autor['id_usu'];
        }

     
        $sql = "SELECT * FROM proyectos WHERE titulo LIKE '%$busqueda%'";
        if ($titulacion_id) {
            $sql .= " OR titulacion = $titulacion_id";
        }
        if ($autor_id) {
            $sql .= " OR autor = $autor_id";
        }

        $result = $conexion->query($sql);

   
        if ($result === false) {
            die("Error en la consulta: " . $conexion->error);
        }
    } else {
   
        $sql = "SELECT * FROM proyectos WHERE titulo LIKE '%$busqueda%'";
        $result = $conexion->query($sql);

 
        if ($result === false) {
            die("Error en la consulta: " . $conexion->error);
        }
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="perfil">
            <form action="" method="post">
                <div class="barrita">
                    <input type="text" name="busqueda" placeholder="Filtra por título, facultad, autor.." value="<?php echo $busqueda; ?>">
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
                                    <img src="<?php echo htmlspecialchars($row['portada']); ?>" alt="Portada del proyecto">
                                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
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