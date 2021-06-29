<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_user'])) {
  if ($_SESSION["id_role"]) //Condicion admin
  {
  } else {
    header('location: ../../login.php');
  }
} else {
  header('location: ../../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Proveedores</title>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    ?>
</head>
<body>
    <div class="wrapper">
        <?PHP
            include('../include/navigation.php');
        ?>
    
        <div class=" d-flex justify-content-center" id="content-alert-msg">
           <div class="alert alert-info alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h3 class="pb-2">Reportes</h3>
        <hr>
        <div class="row">
          <a href="#" class="btn btn-black ml-3">
            <div class="box-icon" data-report="operation">
                  <h3><i class="fas fa-bus"></i></h3>
                  <span>OPERACIÓN</span>
            </div>
          </a>
        </div>
    </div>

    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/providers.js"></script>
</body>
</html>