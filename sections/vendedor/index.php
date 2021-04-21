<?php
    session_start();
    if (isset($_SESSION['id_usuario'])) {
        if($_SESSION["role"]==3) //Condicion admin
          {
          }else{ 
            header('location: ../../index.php');
          }
    }else {
        header('location: ../../index.php');
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendedor</title>
    <?php 
      include('../include/estilos.php');
    ?>
</head>
<body>
  <div id="wrapper">
      <?php 
        include('../include/navigation.php');
      ?>
  </div>
  <div class="p-4">
    <h2> Usuario <?php echo $_SESSION["nombre_usuario"]; ?> </h2>
    <h4> ID: <?php echo $_SESSION["id_usuario"]; ?> - ROLE <?php echo $_SESSION["role"]; ?>  </h4>
    <a href="../../controllers/logout.php">Cerrar Sesión</a>
  </div>

   <?php
    include('../include/scrips.php');
   ?>    
</html>