<?php
// Iniciar sesión solo si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="navbar">
  <a href="index.php"><i class="fas fa-home"></i><span class="hide-on-mobile"> Inicio</span></a>
  <a href="explorar.php"><i class="fas fa-compass"></i><span class="hide-on-mobile"> Explorar</span></a>
  <a href="busqueda.php"><i class="fas fa-search"></i><span class="hide-on-mobile"> Búsqueda</span></a>
  
  <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
    <a href="favoritos.php"><i class="fas fa-heart"></i><span class="hide-on-mobile"> Favoritos</span></a>
 
    <a href="cuenta.php" class="right"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
    <a href="logout.php" class="right"><i class="fas fa-sign-out-alt"></i> Logout</a>
 
  <?php else : ?>
    <a href="login.php"><i class="fas fa-sign-in-alt"></i><span class="hide-on-mobile"> Login</span></a>
  <?php endif; ?>
</div>


