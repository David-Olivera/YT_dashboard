<?php
session_start();
if (isset($_SESSION['id_user'])) {
  if ($_SESSION["id_role"] == 1) //Condicion admin
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
    <title>YameviTravel - Usuarios</title>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    ?>
</head>
<body >
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
        <h3 class="pb-2">Usuarios</h3>
        <div class="row">
            <div class="col-lg-6 col-md-4 col-sm-4">
                <div>
                    <button id="formButton" type="button" class="btn btn-success" data-toggle="modal"><i class="fas fa-plus-square"></i> Nuevo usuario</button>
                </div>
            </div>
            <div class="col-lg-6 col-md-8 col-sm-8">
                <div class="form-group"> 
                    <form class="form-inline" id="form-search"  accept-charset="UTF-8" method="get">
                        <div class="flex-fill mr-2">
                            <input type="search" name="search" id="search" placeholder="ID, Username, Nombre, Apellido, Email" class="form-control w-100" label="Search this site">
                        </div>
                        <button class="btn btn-black my-2 my-sm-0" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4" id="crud-form">
                <div class="card">
                    <div class="card-body">
                        <h2>Nuevo</h2>
                        <form role="form" id="userForm">
                            <input type="hidden" id="user-id">
                            <div class="form-group">
                                <input type="text" id="nombre_usuario" placeholder="Primer nombre (Opcional)" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="text" id="apellido_paterno" placeholder="Apellido paterno (Opcional)" class="form-control" >
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="role" >
                                    <option value="">Asigna un rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Operador</option>
                                    <option value="3">Driver</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="email" id="email_usuario" placeholder="Email usuario" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="text" id="username" placeholder="Username" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" autocomplete="off" placeholder="Password" class="form-control" aria-describedby="passwordHelpBlock" >
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                Por motivos de <i class="fas fa-lock"></i> el password se cifrara al guardarlo en la base de datos</small>
                            </div>
                            <div class="row mx-auto">
                                <div class="w-50 p-1">
                                    <button type="submit" id="saveButton" class="roomupdate btn btn-primary btn-block text-center">Guardar</button>
                                </div>
                                <div class="w-50 p-1">
                                    <button type="button" id="cancelButton" class=" btn btn-light btn-block  text-center ">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4" id="crud-form-edit">
                <div class="card">
                    <div class="card-body">
                        <h2>Editar</h2>
                        <form role="form" id="userFormEdit">
                            <input type="hidden" id="user-id-edit">
                            <div class="form-group">
                                <input type="text" id="nombre_usuario_edit" placeholder="Nombre usuario (Opcional)" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="apellido_paterno_edit" placeholder="Apellido paterno (Opcional)" class="form-control" >
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="role_edit" required>
                                    <option value="">-- Asigna un rol --</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Operador</option>
                                    <option value="3">Driver</option>
                                    <option value="5">Agencia</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="email" id="email_usuario_edit" placeholder="Email usuario" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="text" id="username_edit" placeholder="Username" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Contraseña <small>(Actualizar)</small></label>
                                <input type="password" class="form-control form-control-sm" autocomplete="off" id="password_edit" placeholder="Ingrese la Nueva Contraseña">
                                <div class="invalid-feedback">
                                    La contraseña debe ser superior a 6 caracteres
                                </div>
                                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checked_pass_user_edit">
                                    <label class="custom-control-label" for="checked_pass_user_edit"><small>Deseo actualizar mi contraseña</small></label>
                                </div>
                            </div>
                            <div class="row mx-auto">
                                <div class="w-50 p-1">
                                    <button type="submit" id="saveButtonEdit" class="roomupdate btn btn-primary btn-block text-center">Guardar</button>
                                </div>
                                <div class="w-50 p-1">
                                    <button type="button" id="cancelButtonEdit" class=" btn btn-light btn-block  text-center ">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12" id="resultSearch">
                <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                <div class="card my-3" id="result-search">
                    <div class="card-body">
                        <div class="row">
                                <ul id="container" class="row w-100" ></ul>
                        </div>
                    </div>
                </div>
                <div id="table-data">
                    
                </div>
            </div>
        </div>
    </div>
    <?php
    include('../include/scrips.php');
    ?>
    
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/users.js"></script>
</body>
</html>