<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_user'])) {
  if ($_SESSION["id_role"] == 3 || $_SESSION["id_role"] == 2 || $_SESSION["id_role"] == 1) //Condicion admin
  {
  } else {
    header('location: ../../login.php');
  }
} else {
  header('location: ../../login.php');
}
require_once('../../model/proveedores.php');
$provider = new Provider;
if($_SESSION['id_role'] == 3) {
    $providerGroup = $provider->groupForDriver(MD5($_SESSION['id_user']));
    if($providerGroup != null) {
        $providerGroup = explode(',', $providerGroup);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Servicios</title>
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
        <div class=" d-flex justify-content-center">
           <div class="alert alert-info alert-msg alert-outline-coloured alert-dismissible w-100">
                <div class="row">      
                    <div class="pl-3 pt-2">
                        <i class="far fa-fw fa-bell"></i>
                    </div> 
                    <div class="p-1 w-75">
                        <p style="margin-bottom: 0;">
                            <input id="text-msg" type="text" class="sinbordefondo form-control-sm" value="" disabled>
                        </p>   
                    </div>        
                    <button type="button" class="close" id="alert-close">&times;</button>  
                </div>
           </div>
        </div>
        <h3 class="pb-2">Servicios</h3>
        <input type="hidden" name="inp_reservation" id="inp_reservation">
        <div class="row">
        
            <div class="col-lg-3 col-md-6 col-sm-6">
                <form class="form-inline" id="form-search" role="form">
                    <div class="flex-fill mr-2">
                        <input type="search" name="search" id="search" placeholder="Escribe ID o Cliente" class="form-control form-control-sm w-100" label="Search this site">
                    </div>
                    <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button><br>  
                </form>
                <small id="search" class="form-text text-muted">Búsqueda por ID o Cliente.</small>
            </div> 
            <?php if($_SESSION['id_role'] == 1) { ?>   
            <div class="col-lg-3 col-md-6 col-sm-6">
                <form class="form-inline" id="form-search-agency" role="form">
                    <div class="flex-fill mr-2">
                        <input list="agencies" name="agencies" id="name_agency" type="text" class="form-control form-control-sm w-100" placeholder="Elige una agencia">
                        <datalist id="agencies">
                            <?php
                            $query = "SELECT * FROM agencies";
                            $result = mysqli_query($con,$query);
                            if ($result) {
                                while($row = mysqli_fetch_array($result)){
                                    echo '<option value = "'.$row['name_agency'].'"> '.$row['id_agency'].'</option>';
                                }
                                
                            }else{
                                echo '<option value="">No hay agencias registradas</option>';
                            }
                            ?>
                        </datalist>
                    </div>
                    <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button><br>  
                </form>
                <small id="search" class="form-text text-muted">Búsqueda por Agencia.</small>
            </div>
            <?php } ?>  
            <div class="col-lg-5">
                <form id="form-date" class="form-inline" role="form">
                    <div class="input-group">
                        <input type="text" id="datepicker_star" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                        <div class="input-group-append mr-2">
                            <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                        </div>
                        <br/>
                    </div>
                    <br>
                    <div id="fecha_end">
                        <div class="input-group">
                            <input type="text" id="datepicker_end" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                            <div class="input-group-append mr-2">
                                <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-black my-2 btn-sm my-sm-0" type="submit"><i class="fas fa-search"></i></button>
                    
                    <div class="ml-2 form-check form-check">
                        <input class="form-check-input" type="checkbox" id="radiob" value="0">
                        <small id="date" class="form-text text-muted">Búsqueda por rango de fechas.</small> 
                    </div>
                </form>
            </div>  
            <?php if($_SESSION['id_role'] == 1) { ?>
            <div class="col-lg-2 col-md-3 col-sm-3 p-2">
                <a href="#" class="btn btn-success btn-block btn-sm"  data-toggle="modal" data-target="#downloadServiceModal">Generar Reporte</a>
            </div>
            <?php } ?>
            <?php if($_SESSION['id_role'] == 1 || $_SESSION['id_role'] == 2 || $_SESSION['id_role'] == 3) { ?>
            <div class="col-lg-2 col-md-3 col-sm-3 p-2">
                <a href="#" class="btn btn-success btn-block btn-sm"  data-toggle="modal" data-target="#downloadOperationModal">Generar Operación</a>
            </div>
            <?php } ?>
            <div class="col-lg-12" id="resultSearch">
                <div class="card my-3 p-2" id="result-search">
                    <!-- <div class="row">
                            <ul id="container" class="row w-100" ></ul>
                    </div> -->
                </div>
                <div class="navs pt-3" id="content_services">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="entry-tab" data-toggle="tab" value="hola" href="#reserved" role="tab" aria-controls="reserved" aria-selected="true">LLEGADAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="exit-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">SALIDAS</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="reserved" role="tabpanel" aria-labelledby="reserved-tab">
                            
                            <div id="table-data-re">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <div id="table-data-co">
                                
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
    </div>
	<!--PAGO PROVIDER -->
	<div class="modal fade" id="providerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Asignación de Proveedor</h4>
					<button type="button" class="close" id="cancelBtnProvider"  aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form role="form" id="providerForm">
                        <div class="form-group">
                            <p id="proveedor_asignado"></p>
                            <select class="form-control" name="nombre_proveedor" id="nombre_proveedor">
                                <option value="">Seleccione un proveedor</option>
                                <?php
                                $query = "SELECT * FROM providers";
                                $result = mysqli_query($con,$query);
                                if ($result) {
                                    while($row = mysqli_fetch_array($result)){
                                        echo '<option data="'.$row['name_provider'].'" value = "'.$row['id_provider'].'"> '.$row['name_provider'].' </option>';
                                    }
                                    
                                }else{
                                    echo '<option value="">No hay Proveedores registrados</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                                <textarea name="note_proveedor" id="note_proveedor" class="form-control" rows="3" placeholder="Agregue una nota"></textarea>
                        </div>
                        <input type="hidden" class="form-control" id="id_servicee">
                        <input type="hidden" id="code_invoice" class="form-control">
                        <input type="hidden" id="type_action" class="form-control">
                        <input type="hidden" id="type_service" class="form-control">
                        <input type="hidden" id="inp_user" class="form-control" value="<?php echo $_SESSION['id_user']; ?>">
                        <input type="hidden" id="type_tag" class="form-control">
                        <div class="form-group">
                                <button type="submit" id="btnProvider" class="btn btn-black btn-block text-center">Actualizar</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>
	<!--PAGO REP -->
	<div class="modal fade" id="repModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Asignación de REP</h4>
					<button type="button" class="close" id="cancelBtnRep"  aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form role="form" id="repForm">
                        <div class="form-group">
                            <p id="rep_asignado"></p>
                            <select class="form-control" name="nombre_rep" id="nombre_rep">
                                <option value="">Seleccione un REP</option>
                                <?php
                                $query = "SELECT * FROM receptionists";
                                $result = mysqli_query($con,$query);
                                if ($result) {
                                    while($row = mysqli_fetch_array($result)){
                                        echo '<option value = "'.$row['id_receptionist'].'"> '.$row['name_receptionist'].' </option>';
                                    }
                                    
                                }else{
                                    echo '<option value="">No hay REPS registrados</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                                <textarea name="note_rep" id="note_rep" class="form-control" rows="3" placeholder="Agregue una nota"></textarea>
                        </div>
                        
                        <input type="hidden" class="form-control" id="id_servicee_rep">
                        <input type="hidden" id="code_invoice_rep" class="form-control">
                        <input type="hidden" id="type_action_rep" class="form-control">
                        <input type="hidden" id="inp_user_rep" class="form-control" value="<?php echo $_SESSION['id_user']; ?>">
                        <input type="hidden" id="type_service_rep" class="form-control">
                        <input type="hidden" id="type_tag_rep" class="form-control">

                        <div class="form-group">
                                <button type="submit" id="btnRep" class="btn btn-black btn-block text-center">Actualizar</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>
    

	<!--PAGO RESERVACION -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Registrar Pago</h4>
					<button type="button" class="close" id="cancelButtonrg"  aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form name="frmSetCharge" id="frmSetCharge" method="post" action="">
                        <div class="form-row form-group">
                            <div class="col">
                                <label>Gasto</label>
                                <input type="text" class="form-control" name="inp_charge" id="inp_charge" placeholder="$0.00">
                            </div>
                            <div class="col">
                                <label>Moneda</label>
                                <input type="text" class="form-control" name="inp_currency" id="inp_currency" readonly="readonly" value="MXN">
                            </div>
                        </div>
                        <div class="form-group">
							<label>Concepto</label>
							<input type="text" class="form-control" name="inp_concept" id="inp_concept">
                        </div>
                        <div class="form-group" id="content_taxipayment">
							<label>Pago</label>
							<select class="form-control form-control-sm" name="inp_taxipayment" id="inp_taxipayment">
								<option value="1">Yamevi Travel</option>
								<option value="0">Proveedor</option>
							</select>
                        </div>
                        <div class="form-row pl-3">
                            <div class="form-group pr-3">
                                <input type="checkbox" name="inp_paleteo" id="inp_paleteo" >
                                <span>Paleteo</span>
                            </div>
                            <div class="form-group pr-2" id="content_taxi_pay">
                                <input type="checkbox" name="inp_taxi" id="inp_taxi" >
                                <span>Taxi</span>
                            </div>
                        </div>
                        <div class="form-group">
							<input type="button" class="btn btn-info" id="btnSetCharge" value="REGISTRAR" />
							<!-- <input type="hidden" name="inp_reservation" id="inp_reservation"> -->
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
    
    <!-- Modal DESCARGAR -->
    <div class="modal fade" id="downloadServiceModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generar Reporte</h5>
                    <button type="button" class="close btn_close_dowload_s" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class=" d-flex justify-content-center">
                        <div class="alert alert-dismissible w-100" id="alert-msg-s">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-90">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg-s" type="text" class="sinbordefond w-100 form-control-plaintext" value="">
                                    </p>      
                                </div>        
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="col-lg-12 ">
                                <div class=" mr-sm-2">
                                    <label  class="mb-0"><p class="mb-0"><small>Servicio de:</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="">
                                <form class="formDownloadReport_s">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <select class="custom-select custom-select-sm " id="inp_type_translate" name="inp_type_translate">
                                                <option value="todos">Llegadas y Salidas</option>
                                                <option value="llegadas">Llegadas</option>
                                                <option value="salidas">Salidas</option> 
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkFechaServicio_s" >
                                    <label class="custom-control-label " for="checkFechaServicio_s"><p class="mb-0"><small>Búsqueda por Fecha de Servicio</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_date_s">
                                <form class="formDownloadReport_s">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_star_download_s" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_star" aria-describedby="date">
                                                <div class="input-group-append mr-2">
                                                    <span class="input-group-text" id="date"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>                                    
                                        <br>
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_end_download_s" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_end" aria-describedby="date">
                                                <div class="input-group-append mr-2">
                                                    <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkAgencia_s">
                                    <label class="custom-control-label " for="checkAgencia_s"><p class="mb-0"><small>Búsqueda por Agencia</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_agency_s">
                                <form class="formDownloadReport_s">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <input list="agencies" name="agencies" id="inp_acency_s" type="text" class="form-control form-control-sm w-100" placeholder="Seleeciona el nombre de la agencia">
                                            <datalist id="agencies">
                                                <?php
                                                $query = "SELECT * FROM agencies";
                                                $result = mysqli_query($con,$query);
                                                if ($result) {
                                                    while($row = mysqli_fetch_array($result)){
                                                        echo '<option value = "'.$row['name_agency'].'"> </option>';
                                                    }
                                                    
                                                }else{
                                                    echo '<option value="">No hay zonas registradas</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkZona_s">
                                    <label class="custom-control-label " for="checkZona_s"><p class="mb-0"><small>Búsqueda por Zona</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_zone_s">
                                <form class="formDownloadReport_s">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <input list="zones" name="zones" id="inp_zone_s" type="text" class="form-control form-control-sm w-100" placeholder="Seleeciona el nombre de la zona">
                                            <datalist id="zones">
                                                <?php
                                                $query = "SELECT * FROM rates_agencies AS RA INNER JOIN rates_public AS RP ON RA.id_zone = RP.id_zone;";
                                                $result = mysqli_query($con,$query);
                                                if ($result) {
                                                    while($row = mysqli_fetch_array($result)){
                                                        echo '<option value = "'.$row['name_zone'].'"> </option>';
                                                    }
                                                    
                                                }else{
                                                    echo '<option value="">No hay zonas registradas</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkTypeServicie_s">
                                    <label class="custom-control-label " for="checkTypeServicie_s"><p class="mb-0"><small>Búsqueda por Tipo de Servicio</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_type_service_s">
                                <form class="formDownloadReport_s">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <select class="custom-select custom-select-sm " id="inp_service_s" name="inp_service">
                                                <option value="">Seleccione un Tipo de Servicio</option>
                                                <option value="compartido">Compartido</option>
                                                <option value="privado">Privado</option> 
                                                <option value="lujo">Lujo</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn_close_dowload_s" data-dismiss="modal" >Cancelar</button>
                    <button type="button" class="btn btn-primary btn_dowload_report_s" id="btn_dowload_report_s"><span>Descargar</span></button>
                </div>
            </div>
        </div>
    </div>
    <!--CANCELACIÓN RESERVACION -->
	<div class="modal fade" id="cancelationModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Cancelación de Reservación</h5>
					<button type="button" class="close" id="cancelButtonCancelation"  aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form name="formCancelation" id="formCancelation" method="post" action="">
                        <div class="form-group">
                            <label>Tipo de Cancelación</label>
                            <select class="custom-select custom-select-sm " id="type_cancelation">
                                <option value="full">Completa</option>
                                <option value="partial">Parcial</option>
                            </select>
                        </div>
                        <div class="form-group" id="content_type_cs">
                            <label>Tipo de Cancelación de Servicio</label>
							<select  class="custom-select custom-select-sm " id="type_cancelation_service">
                                <option value="c_arrival">Llegada</option>
                                <option value="c_exit">Salida</option>
                            </select>
                        </div>
                        <div class="form-group">
							<input type="button" class="btn btn-info" id="btn_form_cancelation" value="CAMBIAR ESTADO" />
                            <br>
							<input type="hidden" name="inp_selected" id="inp_selected">
                            <!-- <input type="hidden" name="inp_reservation" id="inp_reservation"> -->
                            <input type="hidden" name="inp_transfer" id="inp_transfer">
                            <input type="hidden" name="inp_code" id="inp_code">
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
    <!-- Modal DESCARGAR OPERACION -->
    <div class="modal fade" id="downloadOperationModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generar Reporte</h5>
                    <button type="button" class="close btn_close_dowload_s" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class=" d-flex justify-content-center">
                        <div class="alert alert-dismissible w-100" id="alert-msg-o">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-90">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg-o" type="text" class="sinbordefond w-100 form-control-plaintext" value="">
                                    </p>      
                                </div>        
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                            
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkFechaServicio_o" checked>
                                    <label class="custom-control-label " for="checkFechaServicio_o"><p class="mb-0"><small>Búsqueda por Fecha de Servicio</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_date_o">
                                <form class="formDownloadReport_o">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_star_download_o" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_star" aria-describedby="date">
                                                <div class="input-group-append mr-2">
                                                    <span class="input-group-text" id="date"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div> 
                                        
                                        <?php if($_SESSION['id_role'] == 1) { ?>
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_end_download_o" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_end" aria-describedby="date">
                                                <div class="input-group-append mr-2">
                                                    <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                                                </div>
                                            </div>
                                        </div> 
                                        <?php } ?>                                  
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkProvider_o">
                                    <label class="custom-control-label " for="checkProvider_o"><p class="mb-0"><small>Búsqueda por Proveedor</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_provider_o">
                                <form class="formDownloadReport_o">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <select name="inp_provider" id="inp_provider" class="form-control form-control-sm w-100">
                                                <option value="">Todos los proveedores</option>
                                                 <?php
                                                    $query = "SELECT * FROM providers where name_provider = 'MORMEX'";
                                                    $result = mysqli_query($con,$query);
                                                    if ($result) {
                                                        while($row = mysqli_fetch_array($result)){
                                                            echo '<option value = "'.$row['id_provider'].'">'.$row['name_provider'].' </option>';
                                                        }
                                                        
                                                    }else{
                                                        echo '<option value="">No hay zonas registradas</option>';
                                                    }
                                                    ?> 
                                                <?php
                                                    if($_SESSION['id_role'] == 1){
                                                ?>
                                                   
                                                 <?php
                                                    $query = "SELECT * FROM providers";
                                                    $result = mysqli_query($con,$query);
                                                    if ($result) {
                                                        while($row = mysqli_fetch_array($result)){
                                                            echo '<option value = "'.$row['id_provider'].'">'.$row['name_provider'].' </option>';
                                                        }
                                                        
                                                    }else{
                                                        echo '<option value="">No hay zonas registradas</option>';
                                                    }
                                                ?>  
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn_close_dowload_s" data-dismiss="modal" >Cancelar</button>
                    <button type="button" class="btn btn-primary btn_dowload_report_o" id="btn_dowload_report_o"><span>Descargar</span></button>
                </div>
            </div>
        </div>
    </div>
    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/servicies.js"></script>

</body>
</html>