<?php

session_start();


include('credentials.php');


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}


$message = "";


$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$titulaciones = [];
$sql = "SELECT id_titulacion, nombre FROM titulacion";
$resultado = $conexion->query($sql);
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $titulaciones[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $titulacion = $_POST["titulacion"];
    $fecha = $_POST["fecha"];
    $autor = $_SESSION["id_usu"];  
    
    
    if (isset($_FILES["portada"]) && $_FILES["portada"]["error"] == 0) {
        $portada = file_get_contents($_FILES["portada"]["tmp_name"]);
    } else {
        $message = "Error uploading file.";
    }

    if (empty($message)) {
        
        $sql = "INSERT INTO proyectos (titulo, descripcion, titulacion, fecha, autor, portada) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conexion->error);
        }

        $stmt->bind_param("ssisib", $titulo, $descripcion, $titulacion, $fecha, $autor, $portada);

        
        if ($stmt->execute()) {
            $message = "New project added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }

       
        $stmt->close();
    }

  
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir nuevo proyecto</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="css/nuevoproyecto.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <h2>Añadir nuevo proyecto</h2>
            <div class="input-container">
                <input type="text" name="titulo" placeholder="Título del proyecto" required>
            </div>
            <div class="input-container">
                <textarea name="descripcion" placeholder="Descripción del proyecto" required></textarea>
            </div>
            <div class="input-container">
                <select name="titulacion" required>
                    <option value="">Selecciona titulación</option>
                    <?php foreach ($titulaciones as $titulacion) : ?>
                        <option value="<?= $titulacion['id_titulacion'] ?>"><?= $titulacion['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-container">
                <input type="date" name="fecha" required>
            </div>
            <div class="input-container">
                <input type="file" name="portada" required>
            </div>
            <button type="submit">Add Project</button>
            <?php if (!empty($message)) : ?>
                <p><?= $message ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
