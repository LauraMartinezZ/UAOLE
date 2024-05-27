<?php
function formatFecha($fecha) {
    // Convertir la fecha a objeto DateTime
    $date = new DateTime($fecha);
    // Formatear la fecha en el formato deseado
    return $date->format('d \d\e F \d\e Y');
}

include('credentials.php');
        
$conn = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" href="css/header.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
<?php include 'header.php'; ?>
<div class="overlay"></div>
<main>

<section class="carrousel">
  <?php
   $sql = "SELECT proyectos.*, 
   usuarios.nombre AS autor_nombre, 
   titulacion.nombre AS titulacion_nombre,
   (SELECT COUNT(*) FROM favoritos WHERE favoritos.proyectoid = proyectos.id_proyecto) AS likes
   FROM proyectos 
   INNER JOIN usuarios ON proyectos.autor = usuarios.id_usu
   INNER JOIN titulacion ON proyectos.titulacion = titulacion.id_titulacion";

    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $contador = 0;
        while ($contador < 3 && $row = $result->fetch_assoc()) {
          if(isset($_SESSION['id_usu'])) {
            $sql1 = "SELECT 1 FROM favoritos WHERE usuid = '{$_SESSION['id_usu']}' AND proyectoid = '{$row['id_proyecto']}' LIMIT 1";
            $result1 = $conn->query($sql1);
            if ($result1 === false) {
                die("Error en la consulta: " . $conn->error);
            }
            $liked = $result1->num_rows > 0 ? "liked" : "";
          } else {
            $liked = "";
          }

          echo '
            <div class="slide-content" id='.$row['id_proyecto'].' >
              <img style="display:none" src="mostrar_imagen.php?id='.$row['id_proyecto'].'" alt="Portada de ' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '">
              <h3 class="categoria-muestra">' . htmlspecialchars($row['titulacion_nombre'], ENT_QUOTES, 'UTF-8') . '</h3>
              <h2 class="titulo-muestra">' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '</h2>
              <h3 class="fecha-muestra">' . formatFecha($row['fecha']) . '</h3>
              <p class="creador-muestra">' . htmlspecialchars($row['autor_nombre'], ENT_QUOTES, 'UTF-8') . '</p>
              <div class="interacciones">
                <div class="acciones">
                  <span class="material-symbols-outlined marcado">notifications_active</span>
                  <span class="material-symbols-outlined marcado">bookmark</span>
                  <div id="like-'.$row['id_proyecto'].'" onclick="darLike('.$row['id_proyecto'].')" class="likes ' . $liked . '">
                    <span class="material-symbols-outlined">favorite</span>
                    <p id="likes-count-'.$row['id_proyecto'].'" class"likes-count">'. htmlspecialchars($row['likes'], ENT_QUOTES, 'UTF-8') .'</p>
                  </div>
                </div>
                <h3 class="play">PLAY</h3>
              </div>
            </div>';
            $contador++;
        }
    } else {
        echo "No se encontraron proyectos.";
    }
  ?>

  <div class="centrar">
    <div class="w3-center w3-section w3-large w3-text-white w3-display-bottommiddle botones">
      <span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(1)"></span>
      <span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(2)"></span>
      <span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(3)"></span>
    </div>
  </div>
</section>

<div class="category">
    <h2>Más populares</h2>
    <div class="objetos">
        <?php
        $sql = "SELECT * FROM proyectos";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Error en la consulta: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $contador = 0;
            while ($contador < 5 && $row = $result->fetch_assoc()) {
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
          echo "No se encontraron proyectos.";
        }
        ?>
    </div>
</div>

<div class="category">
  <h2>Más Recientes</h2>
  <div class="objetos">
    <?php
    $sql = "SELECT * FROM proyectos ORDER BY fecha DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $contador = 0;
        while ($contador < 5 && $row = $result->fetch_assoc()) {
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
      echo "No se encontraron proyectos.";
    }
    ?>
  </div>
</div>

<div class="category">
  <h2>Para ti</h2>
  <div class="objetos">
    <?php
    $sql = "SELECT * FROM proyectos ORDER BY RAND() LIMIT 5";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="item">
              <a class="recuadro" href="item.php">
                  <img src="mostrar_imagen.php?id='.$row['id_proyecto'].'" alt="Portada ' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '">
              </a>
                <h3 class="titulo">' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '</h3>
            </div>
            ';
        }
    } else {
        echo "No se encontraron proyectos.";
    }
    ?>
  </div>
</div>
</main>

<script src="script.js"></script>
<script src="js/index.js"></script>

</body>
</html>

<?php
$conexion->close();
?>
