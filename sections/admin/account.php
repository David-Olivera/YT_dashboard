<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_user'])) {
  if ($_SESSION["id_role"] && $_SESSION['id_user']) //Condicion admin
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
    <title>YameviTravel - Cuenta</title>
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
           <div class="alert  alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h3 class="pb-2">Cuenta</h3>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                    <h5 class="pb-2">Mis Datos</h5>
                    <form id="agencyDataForm">
                        <input type="hidden" class="form-control form-control-sm" id="inp_id_user">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Nombre de Usuario</label>
                                    <input type="text" class="form-control form-control-sm" id="inp_name_user" placeholder="Ingrese el Nombre del Usuario">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Apellido Paterno</label>
                                    <input type="text" class="form-control form-control-sm" id="inp_last_user" placeholder="Ingrese el Apellido Materno" >
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="inp_email_user" placeholder="Ingrese el Email de Usuario" >
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Teléfono</label>
                                    <input type="text" class="form-control form-control-sm" id="inp_phone_user" placeholder="Ingrese el Email de Pagos">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" id="saveButtonData" class="btn btn-black btn-sm text-center p-2">Actualizar Datos</button>
                        </div>
                    </form>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                    <h5 class="pb-2">Credenciales</h5>
                    <form id="agencyCredentialsForm">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Nombre de Usuario</label>
                            <input type="text" class="form-control form-control-sm" id="inp_username_user" placeholder="Ingrese el Nuevo Usuario" >
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Contraseña <small>(Actualizar)</small></label>
                            <input type="password" class="form-control form-control-sm" autocomplete="off" id="inp_password_user" placeholder="Ingrese la Nueva Contraseña">
                            <div class="invalid-feedback">
                                La contraseña debe ser superior a 6 caracteres
                            </div>
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="checked_pass_user">
                                <label class="custom-control-label" for="checked_pass_user"><small>Deseo actualizar mi contraseña</small></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" id="saveButtonCreden" class="btn btn-black btn-sm text-center p-2">Actualizar Credenciales</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    <?php
    include('../include/scrips.php');
    ?>
    
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/account.js"></script>
</body>
</html>