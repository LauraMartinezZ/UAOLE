<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="css/cuenta.css" />
    <link rel="stylesheet" href="css/header.css" />

</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <div class="perfil">
            <img src="resources/user_icon.png" alt="Foto de perfil">
            
        </div>
        <div class="detalles">
            <h1>Bienvenido Usuario</h1>
            <p><strong>Nombre:</strong> <?php echo $_SESSION["username"]; ?></p>
            <p><strong>Email:</strong> <?php echo $_SESSION["mail"]; ?></p>
            <p><strong>Grado:</strong> <?php echo $_SESSION["titulacion"]; ?></p>
            <button><a href="editar_perfil.php">Editar perfil</a></button>
            <button><a href="mis_proyectos.php">Mis proyectos</a></button>
        </div>
    </div>
</body>
</html>
