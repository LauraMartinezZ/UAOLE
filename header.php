<?php
// Iniciar sesión solo si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
  <a href="index.php">Inicio</a>
  <a href="explorar.php">Explorar</a>
  <a href="busqueda.php">Búsqueda</a>
  <a href="favoritos.php">Favoritos</a>
  <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
 
    <a href="cuenta.php" class="right"><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
    <a href="logout.php" class="right">Logout</a>
 
  <?php else : ?>
    <a href="login.php">Login</a>
  <?php endif; ?>
</div>
