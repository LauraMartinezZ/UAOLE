<?php
session_start();
include('credentials.php');

$conn = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    header("location: index.php");
    exit;
}

if (isset($_GET['id_proyecto'])) {
    $id_proyecto = $_GET['id_proyecto'];
    $sql = "SELECT COUNT(*) AS num_likes FROM favoritos WHERE usuid = ? AND proyectoid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['id_usu'], $id_proyecto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num_likes = $row['num_likes'];

    if ($num_likes > 0) { // Ya hay like. Se elimina
        $sql_delete = "DELETE FROM favoritos WHERE usuid = ? AND proyectoid = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("ii", $_SESSION['id_usu'], $id_proyecto);

        if ($stmt->execute()) {
            // Éxito al eliminar el "like"
            echo "Dislike";
        } else {
            // Error al eliminar el "like"
            echo "Error";
        }
    } else { // No hay like. Se inserta
        $sql_insert = "INSERT INTO favoritos (usuid, proyectoid) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ii", $_SESSION['id_usu'], $id_proyecto);
        if ($stmt->execute()) {
            // Éxito al crear el "like"
            echo "Like";
        } else {
            // Error al crear el "like"
            echo "Error";
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
