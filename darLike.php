<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$previous_page = $_SERVER['HTTP_REFERER'];

include('credentials.php');

// Conectar a la base de datos
$bd = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

// Verificar la conexión
if ($bd->connect_error) {
    die("Error de conexión: " . $bd->connect_error);
}

// Obtener el ID del proyecto desde la URL, si no se proporciona usar un valor predeterminado (23 en este caso)
$idProyecto = isset($_GET['id']) ? intval($_GET['id']) : 23;
$idUsuario = $_SESSION["id_usu"];

$sql = "SELECT * 
FROM favoritos 
WHERE usuid = '$idUsuario' AND proyectoid = '$idProyecto'";

$result = $bd->query($sql);

if($result->num_rows != 0){
    //Significa que el usuario ya ha dado me gusta a esta publicacion
    // Preparar la consulta
    $sql = "DELETE FROM favoritos WHERE usuid = ? AND proyectoid = ?";
    $stmt = $bd->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("ii", $idUsuario, $idProyecto);

    // Ejecutar la consulta
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo"Eliminado de favoritos";
        header("Location: $previous_page");
        exit;
    }

}else{
    //Significa que estamos dando me gusta al proyecto
    // Preparar la consulta
    $sql = "INSERT INTO favoritos (usuid, proyectoid) VALUES (?, ?)";
    $stmt = $bd->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("ii", $idUsuario, $idProyecto);
    // Ejecutar la consulta
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo "Añadido a fav";
        header("Location: $previous_page");
        exit;
    }
}