<?php
session_start();

if (!isset($_SESSION["id_usu"])) {
    header("Location: login.php");
    exit();
}

include('credentials.php');

// Conectar a la base de datos
$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_proyecto"])) {
    $id_proyecto = intval($_POST["id_proyecto"]);
    
    // Verificar si el usuario logueado es el autor del proyecto
    $sql_autor = "SELECT autor FROM proyectos WHERE id_proyecto = ?";
    $stmt_autor = $conexion->prepare($sql_autor);
    $stmt_autor->bind_param("i", $id_proyecto);
    $stmt_autor->execute();
    $result_autor = $stmt_autor->get_result();
    $proyecto = $result_autor->fetch_assoc();

    if ($proyecto && $proyecto["autor"] == $_SESSION["id_usu"]) {
        // Iniciar una transacción
        $conexion->begin_transaction();

        try {
            // Eliminar archivos relacionados
            $sql_delete_files = "DELETE FROM ficheros WHERE id_proyecto = ?";
            $stmt_delete_files = $conexion->prepare($sql_delete_files);
            $stmt_delete_files->bind_param("i", $id_proyecto);
            $stmt_delete_files->execute();

            // Eliminar el proyecto
            $sql_delete = "DELETE FROM proyectos WHERE id_proyecto = ?";
            $stmt_delete = $conexion->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id_proyecto);
            $stmt_delete->execute();

            // Confirmar la transacción
            $conexion->commit();

            // Redirigir a una página de confirmación o a la lista de proyectos
            header("Location: mis_proyectos.php");
            exit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            echo "Error al eliminar el proyecto: " . $e->getMessage();
        }

        $stmt_delete_files->close();
        $stmt_delete->close();
    } else {
        echo "No tienes permisos para eliminar este proyecto.";
    }

    $stmt_autor->close();
}

$conexion->close();
?>
