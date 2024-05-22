<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Proyecto</title>
    <link rel="stylesheet" href="css/nuevo.css" />
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php include 'header.php'; 
    include('credentials.php');
        
    $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
    ?>
    <div class="container">
        <div class="header-content">
            <div class="perfil">
                <img src="resources/user_icon.png" alt="Foto de perfil">
            </div>
            <div class="detalles">
                <h1>Bienvenido <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
            </div>
        </div>
        
        <form action="subida.php" method="post" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-left">
                    <div class="form-row">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-row">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="form-row">
                        <label for="categoria">Titulación:</label>
                        <select id="categoria" name="categoria" required>
                        <?php
                            // Consulta SQL para obtener los nombres de la tabla titulacion
                            $sql = "SELECT nombre FROM titulacion";
                            $result = $conexion->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data de cada fila
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["nombre"] . "'>" . $row["nombre"] . "</option>";
                                }
                            } else {
                                echo "0 resultados";
                            }

                            // Cerrar conexión
                            $conexion->close();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-right">
                    <div class="form-row">
                        <label for="imagen">Portada:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="form-row">
                        <label for="archivos">Archivos:</label>
                        <input type="file" id="archivos" name="archivos[]" accept="audio/*,video/*,image/*,.pdf" multiple>
                    </div>
                    <div class="form-row">
                        <input type="submit" value="Subir Proyecto">
                    </div>
                </div>
            </div>
        </form>
        
    </div>
</body>
</html>
