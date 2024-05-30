<?php
session_start();


if (!isset($_SESSION["username"])) {
    
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nuevoNombre = $_POST["nombre"];
    $nuevoEmail = $_POST["mail"];
    $usuarioNombre = $_SESSION["username"];

    
    include 'credentials.php';

   
    $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    
    $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE nombre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nuevoNombre, $nuevoEmail, $usuarioNombre);
    $stmt->execute();

    
    if ($stmt->affected_rows > 0) {
        
        $_SESSION["username"] = $nuevoNombre;
        $_SESSION["mail"] = $nuevoEmail;

       
        header("Location: cuenta.php");
    } else {
        
        header("Location: cuenta.php");
    }


    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="css/editar_perfil.css">
    <link rel="stylesheet" href="css/header.css" />
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <form class="formulario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h2>Editar perfil</h2>
            <div class="anterior-info">
                <p><strong>Nombre anterior:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
                <p><strong>Email anterior:</strong> <?php echo htmlspecialchars($_SESSION["mail"]); ?></p>
            </div>
            <label for="nombre">Nuevo nombre:</label>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <label for="email">Nuevo email:</label>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="submit" value="Guardar cambios">
        </form>
    </div>
</body>
</html>

