<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del contenido</title>
    <link rel="stylesheet" href="css/fav.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="https://kit.fontawesome.com/c978cb0a63.js" crossorigin="anonymous"></script>

     <!-- Enlaces iconos -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
   
    <?php include 'header.php'; ?>
    
    <main>
        <section>
            <div id="datos">
                <div id="imagen">
                    <img src="resources/ejemplo1.webp" alt="foto de usuario">
                    <span class="material-symbols-outlined favorito">favorite</span>
                </div>
                <div id="textos">
                    <h2>Mis favoritos</h2>
                    <p>TODOS TUS TFG FAVORITOS GUARDADOS</p>
                </div>
            </div>

            <div id="filtros">
                <div class="secciones">
                    <span class="material-symbols-outlined">description</span>    
                    <h3>TFG Escrito</h3>
                </div>

                <div class="secciones">
                    <span class="material-symbols-outlined">mic</span>
                    <h3>TFG Audio</h3>
                </div>

                <div class="secciones">
                    <span class="material-symbols-outlined">movie</span>   
                    <h3>TFG Video</h3>
                </div>
            </div>
        </section>

        <section>
            <h2>Todos tus favoritos</h2>
            <div id="todos">
                <div class="item">
                    <a class="recuadro" href="item.php">
                        <img src="./resources/ejemplo1.webp" alt="Portada Primeros pasos en Blender">
                        <span class="material-symbols-outlined estilo">description</span>
                        <h3>Título 1</h3>
                    </a>
                </div>

                <div class="item">
                    <a class="recuadro" href="item.php">
                        <img src="./resources/ejemplo1.webp" alt="Portada Primeros pasos en Blender">
                        <span class="material-symbols-outlined estilo">description</span>
                        <h3>Título 1</h3>
                    </a>
                </div>

                <div class="item">
                    <a class="recuadro" href="item.php">
                        <img src="./resources/ejemplo1.webp" alt="Portada Primeros pasos en Blender">
                        <span class="material-symbols-outlined estilo">description</span>
                        <h3>Título 1</h3>
                    </a>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <!-- Coloca aquí tu pie de página -->
    </footer>
</body>
</html>
