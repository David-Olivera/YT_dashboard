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
    <title>YameviTravel - Tarifas Publicas</title>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    ?>
    <!-- IMPORTANTE !!! -->
    <!-- TODOS LOS ARCHIVOS RELACIONADOS A ESTA VISTA SE LLAMAN ZONAS  -->
</head>
<body>
    <div class="wrapper">
        <?PHP
            include('../include/navigation.php');
        ?>
        
        <h3 class="pb-2">Tarifas</h3>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="public-tab" data-toggle="tab" href="#public" role="tab" aria-controls="public" aria-selected="true"><small>Publicas</small></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="agencie-tab" data-toggle="tab" href="#agencie" role="tab" aria-controls="agencie" aria-selected="false"><small>Agencias</small></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tureando-tab" data-toggle="tab" href="#tureando" role="tab" aria-controls="tureando" aria-selected="false"><small>Tureando</small></a>
            </li>
            
        </ul>
        <div class="tab-content" id="myTabContent">           
            <div class="tab-pane fade show active pt-3" id="public" role="tabpanel" aria-labelledby="public-tab">                  
                <div class=" d-flex justify-content-center">
                    <div class="alert alert-success alert-msg alert-outline-coloured alert-dismissible w-100">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-75">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg" type="text" class="sinbordefondo form-control-sm" value="" disabled>
                                    </p>   
                                </div>        
                                <button type="button" class="close alert-close" id="alert-close">&times;</button>  
                            </div>
                    </div>
                </div>
                <!-- Button trigger modal -->
                <div class="row">
                    <div class="col-lg-6">
                        <div>
                            <button type="button" id="buttonadd" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-plus-square"></i> Nueva Zona
                            </button> 
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <form class="form-inline" id="form-search"  accept-charset="UTF-8" method="get">
                                <div class="flex-fill mr-2">
                                    <input type="search" name="search" id="search" placeholder="ID, Nombre de zona" class="form-control form-control-sm w-100" label="Search this site">
                                </div>
                                <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit">Buscar</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12" id="zoneTable">
                            <small>OW - One-way (De ida, sencillo) / RT - Round trip (Ida y vuelta, redondo)</small>
                            <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                            <div class="card my-3" id="result-search">
                                <div class="card-body">
                                    <div class="row">
                                            <ul id="container" class="row w-100" ></ul>
                                    </div>
                                </div>
                            </div>
                            <div id="table-data-pu">
                                
                            </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade pt-3" id="agencie" role="tabpanel" aria-labelledby="agencia-tab">
                <div class=" d-flex justify-content-center">
                    <div class="alert alert-success alert-msg-ag alert-outline-coloured alert-dismissible w-100">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-75">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg-ag" type="text" class="sinbordefondo form-control-sm" value="" disabled>
                                    </p>   
                                </div>        
                                <button type="button" class="close alert-close" id="alert-close">&times;</button>  
                            </div>
                    </div>
                </div>
                <!-- Button trigger modal -->
                <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <form class="form-inline" id="form-search-ag"  accept-charset="UTF-8" method="get">
                                <div class="flex-fill mr-2">
                                    <input type="search" name="search-ag" id="search-ag" placeholder="ID, Nombre de zona" class="form-control form-control-sm w-100" label="Search this site">
                                </div>
                                <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit">Buscar</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12" id="zoneTable">
                            <small>OW - One-way (De ida, sencillo) / RT - Round trip (Ida y vuelta, redondo)</small>
                            <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                            <div class="card my-3" id="result-search-ag">
                                <div class="card-body">
                                    <div class="row">
                                            <ul id="container-ag" class="row w-100" ></ul>
                                    </div>
                                </div>
                            </div>
                            <div id="table-data-ag">
                                
                            </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade pt-3" id="tureando" role="tabpanel" aria-labelledby="tureando-tab">
                <div class=" d-flex justify-content-center">
                    <div class="alert alert-success alert-msg-tu alert-outline-coloured alert-dismissible w-100">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-75">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg-tu" type="text" class="sinbordefondo form-control-sm" value="" disabled>
                                    </p>   
                                </div>        
                                <button type="button" class="close alert-close" id="alert-close">&times;</button>  
                            </div>
                    </div>
                </div>
                <!-- Button trigger modal -->
                <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <form class="form-inline" id="form-search-tu"  accept-charset="UTF-8" method="get">
                                <div class="flex-fill mr-2">
                                    <input type="search" name="search-tu" id="search-tu" placeholder="ID, Nombre de zona" class="form-control form-control-sm w-100" label="Search this site">
                                </div>
                                <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit">Buscar</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12" id="zoneTable">
                            <small>OW - One-way (De ida, sencillo) / RT - Round trip (Ida y vuelta, redondo)</small>
                            <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                            <div class="card my-3" id="result-search-tu">
                                <div class="card-body">
                                    <div class="row">
                                            <ul id="container-tu" class="row w-100" ></ul>
                                    </div>
                                </div>
                            </div>
                            <div id="table-data-tu">
                                
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva Zona</h5>
                        <button type="button" class="close" id="cancelButton"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="zone-form">
                            <form role="form" id="zoneForm">
                                <input type="hidden" id="zone-id">
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <label for="nombre_zona">Nombre de zona</label>
                                        <input type="text" id="nombre_zona" placeholder="Zona" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group border border-top-0 border-left-0 border-bottom-0 col-lg-4">
                                        <div class=" text-center">
                                            <h6>Servicio privado<br><small>Precios</small></h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 4 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_1" class="form-control form-control-sm" placeholder="Sencillo">                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_1" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 6 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_2" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_2"class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 8 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_3" class="form-control form-control-sm" placeholder="Sencillo"  >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_3" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 10 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_4" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_4" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 11 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_5" class="form-control form-control-sm" placeholder="Sencillo">                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_5" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 16 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_6" class="form-control form-control-sm" placeholder="Sencillo">                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_6" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group border border-top-0 border-left-0 border-bottom-0 col-lg-4">
                                        <div class="text-center">
                                            <h6>Servicio compartido<br><small>Precios</small> </h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>Minibus</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_ow" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_rt" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>

                                        <div class="text-center pt-2">
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>Premium</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_ow_premium" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_rt_premium" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  col-lg-4">
                                        <div class="text-center">
                                            <h6>Servicio lujo<br><small>Precios</small> </h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 6 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="lujo_ow_1" class="form-control form-control-sm" placeholder="Sencillo"  >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="lujo_rt_1" class="form-control form-control-sm" placeholder="Redondo"  >                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="saveButton" class="btn btn-success btn-block text-center">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="exampleModalEdit" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalEditLabel">Editar zona</h4>
                        <button type="button" class="close" id="cancelButtonEdit"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="zone-form-edit">
                            <form role="form" id="zoneFormEdit">
                                <input type="hidden" id="zone-id-edit">
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <label for="nombre_zona">Nombre de zona</label>
                                        <input type="text" id="nombre_zona_edit" placeholder="Zona" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <input type="hidden" name="rate_type" class="form-control form-control-sm" id="rate_type">
                                    </div>
                                    <div class="form-group border border-top-0 border-left-0 border-bottom-0 col-lg-4">
                                        <div class="text-center">
                                            <h6><strong>Servicio privado</strong><br><small>Precios</small></h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 4 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_1_edit" class="form-control form-control-sm" placeholder="Sencillo"  >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_1_edit" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 6 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_2_edit" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_2_edit"class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 8 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_3_edit" class="form-control form-control-sm" placeholder="Sencillo"  >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_3_edit" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 10 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_4_edit" class="form-control form-control-sm" placeholder="Sencillo">                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_4_edit" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 11 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_5_edit" class="form-control form-control-sm" placeholder="Sencillo"  >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_5_edit" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 16 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_ow_6_edit" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="privado_rt_6_edit" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group border border-top-0 border-left-0 border-bottom-0 col-lg-4">
                                        <div class="text-center">
                                            <h6><strong>Servicio compartido</strong><br><small>Precios</small> </h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>Minibus</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_ow_edit" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_rt_edit" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                            
                                            <div class="col-lg-12 pt-2 text-center">
                                                <small>Premium</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_ow_premium_edit" class="form-control form-control-sm" placeholder="Sencillo" >                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="compartido_rt_premium_edit" class="form-control form-control-sm" placeholder="Redondo" >                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  col-lg-4">
                                        <div class="text-center">
                                            <h6><strong>Servicio lujo</strong><br><small>Precios</small> </h6>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12 text-center">
                                                <small>1 - 6 Pasajeros</small>
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="lujo_ow_1_edit" class="form-control form-control-sm" placeholder="Sencillo">                             
                                            </div>
                                            <div class="form-group col-lg-6"> 
                                                <input type="text" id="lujo_rt_1_edit" class="form-control form-control-sm" placeholder="Redondo">                             
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="saveButtonEdit" class="btn btn-success btn-block text-center">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    include('../include/scrips.php');
    ?>
    
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/rates.js"></script>
</body>
</html>