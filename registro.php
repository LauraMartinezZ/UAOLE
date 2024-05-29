<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('credentials.php');
    
    $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
    
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $titulacion = $_POST["titulacion"];
    
    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        exit;
    }
    
    
    // Preparar y ejecutar la consulta para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email, clave, titulacion) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $password, $titulacion);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        echo "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
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
<title>Registro</title>
<link rel="stylesheet" href="css/registro.css" />
<link rel="stylesheet" href="css/header.css" />
</head>
<body>
<?php include 'header2.php'; ?>

<div class="login-container">
  <img src="resources/user_icon.png" alt="User Icon">
  <form action="registro.php" method="POST">
    <input type="text" name="username" placeholder="Enter your username" required>
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
    <div class="select-container">
    <select name="titulacion" required>
        <option value="">Selecciona tu titulación</option>
        <?php
        include('credentials.php');
        $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        $sql = "SELECT id_titulacion, nombre FROM titulacion";
        $resultado = $conexion->query($sql);
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila["id_titulacion"] . "'>" . $fila["nombre"] . "</option>";
            }
        }
        $conexion->close();
        ?>
    </select>
</div>
    <button type="reset"> Borrar</button>
    <button type="submit">Registrarse</button>
    <p>¿Ya eres usuario? <a href="login.php">Inicia sesión aquí</a></p>
  </form>
</div>

</body>
</html>
