<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('credentials.php');
        
$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recoger los datos del formulario
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categoria'];
$autor = $_SESSION['id_usu']; // Suponiendo que ya tienes el ID del usuario en la sesión

date_default_timezone_set('Europe/Madrid');
$fecha = date("Y-m-d");

// Verificar si se ha enviado un archivo de imagen
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $fileName = $_FILES['imagen']['name'];
    $fileSize = $_FILES['imagen']['size'];
    $fileType = $_FILES['imagen']['type'];
    
    // Leer el contenido del archivo como un string binario
    $imagenBinaria = file_get_contents($fileTmpPath);

    // Verificar el id_titulacion correspondiente al nombre de la categoría seleccionada
    $id_titulacion = null;
    $sql_titulacion = "SELECT id_titulacion FROM titulacion WHERE nombre = ?";
    if ($stmt_titulacion = $conexion->prepare($sql_titulacion)) {
        $stmt_titulacion->bind_param("s", $categoria);
        if ($stmt_titulacion->execute()) {
            $stmt_titulacion->bind_result($id_titulacion);
            $stmt_titulacion->fetch();
        } else {
            echo "Error al ejecutar la consulta de titulación: " . $stmt_titulacion->error;
        }
        $stmt_titulacion->close();
    } else {
        echo "Error al preparar la consulta de titulación: " . $conexion->error;
    }

    if ($id_titulacion !== null) {
        // Sentencia SQL para insertar el proyecto con la imagen en la tabla
        $sql = "INSERT INTO proyectos (titulo, descripcion, titulacion, fecha, autor, portada)
                VALUES (?, ?, ?, ?, ?, ?)";

        // Preparar la declaración
        $stmt = $conexion->prepare($sql);

        // Vincular los parámetros y sus tipos
        $stmt->bind_param("ssisss", $titulo, $descripcion, $id_titulacion, $fecha, $autor, $imagenBinaria);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            // Obtener el ID del proyecto recién insertado
            $id_proyecto = $stmt->insert_id;

            // Procesar los archivos adicionales
            if(isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                $archivos = $_FILES['archivos'];
                $totalArchivos = count($archivos['name']);

                for($i = 0; $i < $totalArchivos; $i++) {
                    $fileTmpPath = $archivos['tmp_name'][$i];
                    $fileName = $archivos['name'][$i];
                    $fileSize = $archivos['size'][$i];
                    $fileType = $archivos['type'][$i];
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                    // Leer el contenido del archivo como un string binario
                    $fileBinario = file_get_contents($fileTmpPath);

                    // Sentencia SQL para insertar el archivo en la tabla ficheros
                    $sql_fichero = "INSERT INTO ficheros (nombre, extension, tipo, contenido, id_proyecto)
                                    VALUES (?, ?, ?, ?, ?)";

                    // Preparar la declaración
                    $stmt_fichero = $conexion->prepare($sql_fichero);

                    // Vincular los parámetros y sus tipos
                    $stmt_fichero->bind_param("ssssi", $fileName, $fileExt, $fileType, $fileBinario, $id_proyecto);

                    // Ejecutar la declaración
                    if ($stmt_fichero->execute()) {
                        echo "Fichero subido correctamente: $fileName<br>";
                    } else {
                        echo "Error al subir el fichero: " . $stmt_fichero->error . "<br>";
                    }

                    // Cerrar la declaración
                    $stmt_fichero->close();
                }
            }

            echo "Proyecto subido correctamente";
        } else {
            echo "Error al subir el proyecto: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "No se encontró el id_titulacion correspondiente al nombre de la categoría seleccionada.";
    }
} else {
    echo "Error al subir la imagen del proyecto.";
}
header('Location: item.php?id='.$id_proyecto.'');



// Cerrar la conexión
$conexion->close();

