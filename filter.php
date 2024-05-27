<?php
include('credentials.php');

$conn = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los valores de las fechas
$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

// Obtener el valor seleccionado del segundo select
$theme = $_GET['theme'] ?? '';

// Obtener la fecha actual para establecer un límite máximo si la fecha "hasta" está vacía
$fecha_actual = date('Y-m-d');

$sql = "SELECT * FROM proyectos";

if (!empty($desde) && !empty($hasta) && !empty($theme)) {
    $sql .= " WHERE proyectos.fecha BETWEEN '$desde' AND '$hasta' AND proyectos.titulacion = '$theme'";
} elseif (!empty($desde) && empty($hasta)) {
    $sql .= " WHERE proyectos.fecha BETWEEN '$desde' AND '$fecha_actual'";
} elseif (!empty($desde) && !empty($theme)) {
    $sql .= " WHERE proyectos.fecha >= '$desde' AND proyectos.titulacion = '$theme'";
} elseif (!empty($hasta) && !empty($theme)) {
    $sql .= " WHERE proyectos.fecha <= '$hasta' AND proyectos.titulacion = '$theme'";
} elseif (!empty($theme)) {
    $sql .= " WHERE proyectos.titulacion = '$theme'";
}

$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    $contador = 0;
    while ($contador < 10 && $row = $result->fetch_assoc()) {
        echo '
        <div class="item">
          <a class="recuadro" href="item.php">
              <img src="mostrar_imagen.php?id='.$row['id_proyecto'].'" alt="Portada ' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '">
          </a>
            <h3 class="titulo">' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '</h3>
        </div>
        ';
        $contador++;
    }
} else {
    echo "<h2>No se encontraron proyectos.</h2>";
}

$conn->close();

