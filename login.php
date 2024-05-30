<?php
// Iniciar sesión
session_start();

$mensaje_error = '';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(isset($_POST["username"]) && isset($_POST["password"])){
        
        include('credentials.php');
        
        
        $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
        
        
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        
        $user = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT u.id_usu, u.nombre, u.email, t.nombre AS nombre_titulacion 
        FROM usuarios u 
        INNER JOIN titulacion t ON u.titulacion = t.id_titulacion 
        WHERE u.nombre = ? AND u.clave = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $user, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();


        
        if ($resultado && $resultado->num_rows > 0) {
          
            $fila = $resultado->fetch_assoc();
            $idUsuario = $fila["id_usu"];
            $nombreUsuario = $fila["nombre"];
            $mail = $fila["email"];
            $titulacion = $fila["nombre_titulacion"];

           
            $_SESSION["loggedin"] = true;
            $_SESSION["id_usu"] = $idUsuario;
            $_SESSION["username"] = $nombreUsuario;
            $_SESSION["mail"] = $mail;
            $_SESSION["titulacion"] = $titulacion;
            header("location: index.php");
            exit;
        } else {
            
            $mensaje_error = "Credenciales incorrectas. Inténtalo de nuevo.";
        }

        
        $stmt->close();
        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
<link rel="stylesheet" href="css/registro.css" />
<link rel="stylesheet" href="css/header.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Fc5kbb8uOYGtEzvONuOJz6vcW5REHdRJ+t1LjuR8piCLqL2/zD05FQl3AmmFQICqyN5iY4yWvcgf5ZkkEokjEg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
.error-message {
  color: red;
  font-size: 14px;
}
</style>
</head>
<body>
<?php include 'header2.php'; ?>

<div class="login-container">
  <img src="resources/user_icon.png" alt="User Icon">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php 
        if(!empty($mensaje_error)){
            echo '<p class="error-message">'.$mensaje_error.'</p>';
        }
    ?>
    <input type="text" name="username" placeholder="Enter your username">
    <input type="password" name="password" placeholder="Enter your password">
    <button type="reset">Borrar</button>
    <button type="submit">Iniciar Sesión</button>
    <p>¿Nuevo por aquí? <a href="registro.php">Regístrate</a></p>
  </form>
</div>

</body>
</html>
