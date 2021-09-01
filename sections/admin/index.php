<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_user'])) {
  if (isset($_SESSION['id_role']) && $_SESSION["id_role"] == 1) //Condicion admin
  {
  }
  if(isset($_SESSION['id_role']) && $_SESSION['id_role'] == 3){
    header('location: ../../sections/admin/servicies.php');
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
  <title>YameviTravel- Administrador</title>
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
            <h2 class="pb-2">Dashboard</h2>
            
            <br>
            <div class="row">
            <div id="user-form" class="col-lg-3 pb-3">
                <form  role="form">
                        <input type="hidden" id="user-id">
                        <div class="form-group">
                            <input type="text" id="nombre_usuario" placeholder="Nombre usuario" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" id="apellido_paterno" placeholder="Apellido paterno" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" id="apellido_materno" placeholder="Apellido materno" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="email" id="email_usuario" placeholder="Email usuario" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" placeholder="Password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" id="telefono_usuario" placeholder="Teléfono usuario" class="form-control" >
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="role" >
                                <option value="">-- Asigna un rol --</option>
                                <option value="1">Administrador</option>
                                <option value="2">Operador</option>
                                <option value="3">Vendedor</option>
                            </select>
                        </div> 
                        <div class="row mx-auto">
                            <div class="w-50 p-1">
                                <button type="button" class=" btn btn-success btn-block text-center">Guardar</button>
                            </div>
                            <div class="w-50 p-1">
                                <button type="button" id="cancelButton" class=" btn btn-danger btn-block  text-center ">Cancelar</button>
                            </div>
                        </div>
                </form>
            </div>
            <div class="col-lg-12" id="userTable">
                <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
  <?php
  include('../include/scrips.php');
  ?>
  <script>
      $('li').on('click',function(e){ 
        $(this).parent().find('li.active').removeClass('active'); 
        $(this).addClass('active');  
        });
  </script>
    <script src="../../assets/js/navigation.js"></script>
</body>

</html>