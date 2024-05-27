<?php
  include('credentials.php');
        
  $conn = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
  
  if ($conn->connect_error) {
      die("Conexión fallida: " . $conn->connect_error);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/explorar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<main>
<section class="explore">
    <h2>Explorar</h2>
    <div class="filter">
        <div class="date-filter">
            <h3>Filtrar por fecha:</h3>
            <div class="date-inputs">
                <div class="input-container">
                    <label for="fecha-desde">Desde:</label>
                    <input type="date" id="fecha-desde" onchange="filtrar()">
                </div>
                <div class="input-container">
                    <label for="fecha-hasta">Hasta:</label>
                    <input type="date" id="fecha-hasta" onchange="filtrar()">
                </div>
            </div>
        </div>
        <div class="theme-filter">
            <h3>Filtrar por facultad:</h3>
            <select name="theme" id="theme" onchange="filtrar()">
                <option value="">Seleccionar</option>
                <?php
                    $sql = "SELECT id_titulacion, nombre FROM titulacion";
                    $result = $conn->query($sql);
                    if ($result === false) {
                        die("Error en la consulta: " . $conn->error);
                    }
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id_titulacion'] . '">' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>
</section>
  <section class="objetos">
    <!-- Aquí se insertarán los resultados filtrados -->
  </section>
</main>
   
<script>
  filtrar();

  function filtrar() {
    var theme = document.getElementById("theme").value;
    var desde = document.getElementById("fecha-desde").value;
    var hasta = document.getElementById("fecha-hasta").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".objetos").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "filter.php?desde=" + desde + "&hasta=" + hasta + "&theme=" + theme, true);
    xhttp.send();
  }
</script>
</body>
</html>
