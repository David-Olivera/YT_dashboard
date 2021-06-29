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
    <title>YameviTravel - Amenidades</title>
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
        <h3 class="pb-2">Amenidades</h3>
        
        <div class="row">

            <div class="col-lg-6">
                <div>
                    <button id="formButton" type="button" class="btn btn-success" data-toggle="modal"><i class="fas fa-plus-square"></i> Nueva amenidad</button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group"> 
                    <form class="form-inline" id="form-search"  accept-charset="UTF-8" method="get">
                        <div class="flex-fill mr-2">
                            <input type="search" name="search" id="search" placeholder="ID, Nombre de amenidad" class="form-control w-100" label="Search this site">
                        </div>
                        <button class="btn btn-black my-2 my-sm-0" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-3" id="crud-form">
                <div class="card">
                    <div class="card-body">
                    <h2>Nuevo</h2>
                    <form role="form" id="amenityForm">
                        <input type="hidden" id="amenity-id">
                        <div class="form-group">
                            <input type="text" id="nombre_amenidad" placeholder="Nombre de amenidad" class="form-control" >
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="amenidad" required>
                                <option value="">-- Define el tipo de amenidad --</option>
                                <option value="Hotel">Hotel</option>
                                <option value="Tour">Tour</option>
                                <option value="Traslado">Traslado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" id="price_mx" placeholder="Precio en MXN" class="form-control" >
                        </div>
                        <div class="form-group">
                            <input type="text" id="price_us" placeholder="Precio en US" class="form-control" >
                        </div>
                        <div class="form-group">
                            <textarea name="" id="descripcion" class="form-control" cols="30" rows="4" placeholder="Descripcion de amenidad"></textarea>
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
                        <form role="form" id="amenityFormEdit">
                            <input type="hidden" id="amenity-id-edit">
                            <div class="form-group">
                                <input type="text" id="nombre_amenidad_edit" placeholder="Nombre usuario" class="form-control" >
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="amenidad_edit">
                                    <option value="">-- Define el tipo de amenidad --</option>
                                    <option value="Hotel">Hotel</option>
                                    <option value="Tour">Tour</option>
                                    <option value="Traslado">Traslado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" id="price_mx_edit" placeholder="Precio en MXN" class="form-control" >
                            </div>
                            <div class="form-group">
                                <input type="text" id="price_us_edit" placeholder="Precio en US" class="form-control" >
                            </div>
                            <div class="form-group">
                                <textarea name="" id="descripcion_edit" class="form-control" cols="30" rows="4" placeholder="Descripcion de amenidad"></textarea>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Imagen de articulo</h5>
                    <button type="button" class="close" id="cancelButtonModal"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="amenityFormModal">
                    
                        <div class="form-group">
                            <input type="hidden" id="id_amenity_img" name="id_amenity_img" placeholder="ID" class="form-control" >
                        </div>
                        <div class="form-group pb-2 elimined border-bottom">
                            <div class="row">
                                <div class="col-lg-10 col-md-8">
                                    <input type="text" class="form-control" id="name_img" disabled>
                                    <small id="name_img" class="form-text text-muted">El nombre de la imagen actual de la amenidad.</small>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <a href="#" id="deleteImg" class="btn btn-danger btn-block"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                            <div class="w-100 text-center">
                                <img src="" class="w-50 p-3" alt="" id="img_ame">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="name_ame_img">
                        </div>
                        <div class="form-group mb-4">
                        <input id="" type ="file" name="imagen" class="form-control-sm" placeholder="Titulo" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="saveButtonImg" class="btn btn-success btn-block text-center">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/amenities.js"></script>
</body>
</html>