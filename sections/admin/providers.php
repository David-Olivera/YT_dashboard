<?php
require_once '../../config/conexion.php';
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
        <h3 class="pb-2">Proveedores</h3>
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <button id="formButton" type="button" class="btn btn-success" data-toggle="modal"><i class="fas fa-plus-square"></i> Nuevo Proveedor</button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group"> 
                    <form class="form-inline" id="form-search"  accept-charset="UTF-8" method="get">
                        <div class="flex-fill mr-2">
                            <input type="search" name="search" id="search" placeholder="ID, Nombre proveedor, Nombre contacto" class="form-control w-100" label="Search this site">
                        </div>
                        <button class="btn btn-black my-2 my-sm-0" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-3" id="crud-form">
                <div class="card">
                    <div class="card-body">
                        <h2>Nuevo</h2>
                        <form role="form" id="providerForm">
                            <input type="hidden" id="provider-id">
                            <div class="form-group">
                                <input type="text" id="nombre_prov" placeholder="Nombre de proveedor" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="name_cont" placeholder="Nombre contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="apellido_cont" placeholder="Apellido parterno" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="email_prov" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="telefono_prov" placeholder="Tel??fono" class="form-control">
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
            <div class="col-lg-3" id="crud-form-edit">
                <div class="card">
                    <div class="card-body">
                        <h2>Editar</h2>
                        <form role="form" id="providerFormEdit">
                            <input type="hidden" id="provider-id-edit">
                            <div class="form-group">
                                <input type="text" id="nombre_prov_edit" placeholder="Nombre de proveedor" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="name_cont_edit" placeholder="Nombre contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="apellido_cont_edit" placeholder="Apellido parterno" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="email_prov_edit" placeholder="Email de REP" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" id="telefono_prov_edit" placeholder="Tel??fono de REP" class="form-control">
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
    <script src="../../assets/js/providers.js"></script>
</body>
</html>