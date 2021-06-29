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
    <title>YameviTravel - Hoteles</title>
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
        <h3 class="pb-1">Conciliaciones</h3>
		<input type="hidden" class="form-control" name="inp_user" id="inp_user" value="<?php echo $_SESSION['id_user'] ?>">
        
          <div class=" pb-3">
                <a href="#" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#downloadConciModal">Generar Reporte</a>
          </div>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active" id="noconciliation-tab" data-toggle="tab" href="#noconciliation" role="tab" aria-controls="noconciliation" aria-selected="false">No Conciliados</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link " id="conciliation-tab" data-toggle="tab" href="#conciliation" role="tab" aria-controls="conciliation" aria-selected="true">Conciliados</a>
              </li>
          </ul>
          <div class="tab-content pl-4" id="myTabContent">
                    <div class="tab-pane fade show active pt-3" id="noconciliation" role="tabpanel" aria-labelledby="noconciliation-tab">
                        <div class='row' id="sec-search">
                            <div class='col-lg-3 col-md-6'>
                                <label for=''><small>Busqueda por ID de reservación</small></label>
                                <div class='form-inline'>
                                    <div class="flex-fill mr-1">
                                            <input type='text' class='form-control form-control-sm w-100 mr-1'  id='inp_code_invoice' autocomplete="off" placeholder='Escribe el ID de reservación'>
                                    </div>
                                    <div >
                                        <a href='#' id="search_code_invoice" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                    </div> 
                                </div>
                                <div class="pt-1 pb-1 form-check form-check">
                                    <input class="form-check-input" type="checkbox" id="code_multiple" value="0">
                                    <small id="date" class="form-text text-muted">Búsqueda por CODE multiple.</small> 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for=''><small>Busqueda por Nombre de Cliente</small></label>
                                <div class='form-inline'>
                                    <div class="flex-fill mr-2">
                                        <input type='text' class='form-control form-control-sm w-100 mr-1' autocomplete="off" id='inp_name_client' placeholder='Escribe el Nombre del Cliente'>
                                    </div>
                                    <div class='form-group'>
                                        <a href='#' id="search_name_client" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                    </div> 
                                </div>
                            </div>
                            <div class='col-lg-6 col-md-12  '>
                                <div class="content-h">
                                    <label><small>Búsqueda por Fecha y/o Agencia</small></label>
                                    <div class="form-row">
                                            <div class="form-group w-25" id="content_date_star">
                                                <div class="input-group pr-1">
                                                    <input type="text" id="datepicker_star_ser" name="datepicker_star_ser" autocomplete="off" placeholder="Fecha inicio" class="form-control form-control-sm" aria-describedby="date">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group w-25" id="content_date_end">
                                                <div class="input-group pr-1">
                                                    <input type="text" id="datepicker_end_ser" name="datepicker_end_ser" autocomplete="off" placeholder="Fecha final" class="form-control form-control-sm" aria-describedby="date">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="flex-fill mr-2">
                                                    <input list="agencies" name="agencies" id="name_agency" type="text" autocomplete="off" class="form-control form-control-sm w-100" placeholder="Elige una agencia">
                                                    <datalist id="agencies">
                                                        <option value = "Todos">Todas las Agencias</option>
                                                        <?php
                                                        $query = "SELECT * FROM agencies";
                                                        $result = mysqli_query($con,$query);
                                                        if ($result) {
                                                            while($row = mysqli_fetch_array($result)){
                                                                echo '<option value = "'.$row['id_agency'].'">'.$row['name_agency'].'</option>';
                                                            }
                                                            
                                                        }else{
                                                            echo '<option value="">No hay agencias registradas</option>';
                                                        }
                                                        ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class='form-group'>
                                                <a href='#' id="search_date_agency" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                            </div> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div id="no_conciliations">
                        </div>
                    </div>
                    <div class="tab-pane fade  pt-3" id="conciliation" role="tabpanel" aria-labelledby="conciliation-tab">
                        <div class='row' id="sec-search-con">
                            <div class='col-lg-3 col-md-6'>
                                <label for=''><small>Busqueda por ID de reservación</small></label>
                                <div class='form-inline'>
                                    <div class="flex-fill mr-2">
                                        <input type='text' class='form-control form-control-sm w-100 mr-1' autocomplete="off"  id='inp_code_invoice_con' placeholder='Escribe el ID de reservación'>
                                    </div>
                                    <div class=''>
                                        <a href='#' id="search_code_invoice_con" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                    </div> 
                                </div>
                                <div class="pt-1 pb-1 form-check form-check">
                                    <input class="form-check-input" type="checkbox" id="code_multiple_con" value="0">
                                    <small id="date" class="form-text text-muted">Búsqueda por CODE multiple.</small> 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for=''><small>Busqueda por Nombre de Cliente</small></label>
                                <div class='form-inline'>
                                    <div class="flex-fill mr-2">
                                        <input type='text' class='form-control form-control-sm w-100 mr-1' autocomplete="off" id='inp_name_client_con' placeholder='Escribe el Nombre del Cliente'>
                                    </div>
                                    <div class='form-group'>
                                        <a href='#' id="search_name_client_con" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                    </div> 
                                </div>
                            </div>
                            <div class='col-lg-6 col-md-12 '>
                                <div class="content-h">
                                    <label><small>Búsqueda por Fecha y/o Agencia</small></label>
                                    <div class="form-row">
                                            <div class="form-group w-25" id="content_date_star">
                                                <div class="input-group pr-1">
                                                    <input type="text" id="datepicker_star_ser_con" name="datepicker_star_ser" autocomplete="off" placeholder="Fecha inicio" class="form-control form-control-sm" aria-describedby="date">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group w-25" id="content_date_end">
                                                <div class="input-group pr-1">
                                                    <input type="text" id="datepicker_end_ser_con" name="datepicker_end_ser" autocomplete="off" placeholder="Fecha final" class="form-control form-control-sm" aria-describedby="date">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="flex-fill mr-2">
                                                    <input list="agencies" name="agencies" autocomplete="off" id="name_agency_con" type="text" class="form-control form-control-sm w-100" placeholder="Elige una agencia">
                                                    <datalist id="agencies">
                                                        <option value = "Todos">Todas las Agencias</option>
                                                        <?php
                                                        $query = "SELECT * FROM agencies";
                                                        $result = mysqli_query($con,$query);
                                                        if ($result) {
                                                            while($row = mysqli_fetch_array($result)){
                                                                echo '<option value = "'.$row['id_agency'].'">'.$row['name_agency'].'</option>';
                                                            }
                                                            
                                                        }else{
                                                            echo '<option value="">No hay agencias registradas</option>';
                                                        }
                                                        ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class='form-group'>
                                                <a href='#' id="search_date_agency_con" class='btn btn-black btn-sm'><i class="fas fa-search" aria-hidden="true"></i></a>
                                            </div> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div id="yes_conciliations">
                        </div>
                    </div>
          </div>
    </div>

	<!--PAGO RESERVACION -->
	<div class="modal fade" id="addPayModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Registrar Pago</h4>
                    <button type="button" class="close" data-dismiss="modal" id="close_modal_d" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form name="frmSetCharge" id="frmSetCharge" method="post" action="">
                        <div class="form-row">
                                <input type="hidden" class="form-control" name="inp_charge" id="inp_reserva" >
                                <input type="hidden" class="form-control" name="inp_charge" id="inp_total_cost" >
                            <div class="col">
                                <label>Pago</label>
                                <input type="text" class="form-control " name="inp_charge" id="inp_charge" placeholder="$0.00">
                            </div>
                            <div class="col">
                                <label>Moneda</label>
                                <input type="text" class="form-control" name="inp_currency" id="inp_currency" readonly="readonly" >
                            </div>
                        </div>
                        <div class="form-group pt-2">
							<label>Fecha de Pago</label>
							<div class="input-group pr-1">
                                <input type="text" id="datepicker_fp" style="z-index:1151 !important;" name="datepicker_fp" autocomplete="off" placeholder="Seleccione Fecha inicio" class="form-control " aria-describedby="date">
                                <div class="input-group-append ">
                                    <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group pt-2">
							<label>Concepto</label>
							<input type="text" class="form-control" name="inp_concept" id="inp_concept">
                        </div>
                        <div class="form-group">
							<input type="button" class="btn btn-black" id="btnSetCharge" value="Registrar" />
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>

    <!-- MODAL FILES -->
    <!-- <div class="modal fade " tabindex="-1" role="dialog" id="modal_files" data-backdrop="static" data-keyboard="false"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archivo de Conciliación</h5>
                    <button type="button" class="close btn_close_conci" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="form-group">
                        <label id="label_conci_code"></label><br>
                        
                    </div>
                    <div class="">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_id_reservation"  placeholder="ID">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_id_conciliation"  placeholder="CONCILIATION">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_code_conciliation"  placeholder="CODE">
                    </div>
                    <div id="loadedfiles" class="">
                          
                    </div>
                    
                    <div class="row ml-1 mr-1 mb-3" id="storaged_documents">
                         
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade " tabindex="-1" role="dialog" id="modal_files" data-backdrop="static" data-keyboard="false"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archivo de Conciliación</h5>
                    <button type="button" class="close btn_close_conci" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="form-group">
                        <label id="label_conci_code"></label><br>
                        <p><small>Solo permite archivos en formato PDF, JPG, JPEG y PNG</small></p>
                        <div id="upload" class="form-control-sm">
                            <div class="fileContainer">
                                <input id="files_conciliation" type="file" name="myfiles[]" multiple="multiple" accept="application/pdf, image/jpeg, image/jpg, image/png" required />
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox mr-sm-2 pt-3">
                            <input type="checkbox" class="custom-control-input" id="check_facture">
                            <label class="custom-control-label" for="check_facture"><small>¿Requiere Factura?</small></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_id_reservation"  placeholder="ID">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_id_conciliation"  placeholder="CONCILIATION">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_code_conciliation"  placeholder="CODE">
                        <input type="hidden" class="form-control form-control-sm" autocomplete="off" id="inp_agency"  placeholder="AGENCY">
                    </div>
                    <div class=" text-right">
                        <button type="button" disabled="disabled" id="btn_add_file" class="btn btn-primary">Agregar archivo</button>
                        <button type="button" class="btn btn-secondary btn_close_conci" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div id="loadedfiles" class="pt-2">
                          
                    </div>
                    
                    <hr>
                    <div class="row ml-1 mr-1 mb-3" id="storaged_documents">
                         
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal DESCARGAR -->
    <div class="modal fade" id="downloadConciModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generar Reporte</h5>
                    <button type="button" class="close btn_close_dowload_c" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class=" d-flex justify-content-center">
                        <div class="alert alert-dismissible w-100" id="alert-msg-c">
                            <div class="row">      
                                <div class="pl-3 pt-2">
                                    <i class="far fa-fw fa-bell"></i>
                                </div> 
                                <div class="p-1 w-90">
                                    <p style="margin-bottom: 0;">
                                        <input id="text-msg-c" type="text" class="sinbordefond w-100 form-control-plaintext" value="">
                                    </p>      
                                </div>        
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="col-lg-12 ">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkConciliations" checked>
                                    <label class="custom-control-label " for="checkConciliations"><p class="mb-0"><small>Búsqueda por Conciliados y No Conciliados</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_conciliations">
                                <form class="formDownloadReport_c">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <select class="custom-select custom-select-sm " id="inp_type_conciliation" name="inp_type_conciliation">
                                                <option value="1">Conciliados</option>
                                                <option value="0">No Conciliados</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 content_check">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="checkFechaServicio_c" >
                                    <label class="custom-control-label " for="checkFechaServicio_c"><p class="mb-0"><small>Búsqueda por Fecha de Servicio</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_date_c">
                                <form class="formDownloadReport_c">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_star_download_c" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_star" aria-describedby="date">
                                                <div class="input-group-append mr-2">
                                                    <span class="input-group-text" id="date"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>                                    
                                        <br>
                                        <div class="col-auto my-1">
                                            <div class="input-group">
                                                <input type="text" id="datepicker_end_download_c" autocomplete="off" style="z-index:1151 !important;" placeholder="Selecciona una fecha" class="form-control form-control-sm datepicker_end" aria-describedby="date">
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
                                    <input type="checkbox" class="custom-control-input" id="checkAgencia_c">
                                    <label class="custom-control-label " for="checkAgencia_c"><p class="mb-0"><small>Búsqueda por Agencia</small></p></label>
                                </div>
                                <hr class="my-2"/>
                            </div>
                            <div class="col-lg-12 pb-3" id="content_filter_agency_c">
                                <form class="formDownloadReport_c">
                                    <div class="form-row align-items-center">
                                        <div class="flex-fill pl-2 pr-2 pt-1">
                                            <input list="agencies_c" name="agencies_c" id="inp_acency_c" type="text" class="form-control form-control-sm w-100" placeholder="Seleeciona el nombre de la agencia">
                                            <datalist id="agencies_c">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn_close_dowload_c" data-dismiss="modal" >Cancelar</button>
                    <button type="button" class="btn btn-primary btn_dowload_report_c" id="btn_dowload_report_c"><span>Descargar</span></button>
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
                            <input type="hidden" name="inp_reservation" id="inp_reservation">
                            <input type="hidden" name="inp_transfer" id="inp_transfer">
                            <input type="hidden" name="inp_code" id="inp_code">
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
    <script src="../../assets/js/conciliation.js"></script>
</body>
</html>