<?php
require_once '../../config/conexion.php';
session_start();
$id_res = $_GET['reservation'];
$coinv = $_GET['coinv'];
$reedit = 0;
$id_user = "";
if($_GET['reedit']) {
  $reedit =  $_GET['reedit'];
}
if (isset($_SESSION['id_user'])) {
  $id_user = $_SESSION['id_user'];
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
    <link rel="icon" href="../../assets/img/icon/yamevIcon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if($reedit == 0 || $reedit == '' || $reedit != 1){?>
    <title>Detalles - <?php echo $coinv  ?> </title>
    <?php } ?>    
    <?php if($reedit == 1){?>
    <title>Editar - <?php echo $coinv  ?> </title>
    <?php } ?>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
      include('../include/estilos.php');
      include('../../model/reservaciones.php');
      $reserva_model = new Reservacion();
      if (isset($id_res) AND $id_res != null) {
        echo $id_res;
        $reservation = json_decode($reserva_model->getDetailsReservation($id_res));
        $provider_rep = json_decode($reserva_model->getProviderAndRepReservation($id_res));
        $expenses = json_decode($reserva_model->getExpensesReservation($id_res));
        $deposits = json_decode($reserva_model->getDepositsReservation($id_res));
        $actividad = json_decode($reserva_model->getActivitiesReservation($id_res));
        $trasnfer = '';
        $newnamepay = '';
        $labelChange = '';
        $time_ser = "00:00:00";
        if ($reservation->time_service != null || $reservation->time_service != "") {
          $time_ser = substr($reservation->time_service,0,-3);
        }
        $currency = $reservation->type_currency == 'mx' ? 'MXN' : 'USD';
        $newemail = substr($reservation->email_client,0,22)."...";
        switch ($reservation->type_transfer) {
          case 'RED':
            $trasnfer = 'Redondo';
            break;
          case 'SEN/AH':
            $trasnfer = 'Sencillo Aeropuerto > Hotel';
            break;
          case 'SEN/HA':
            $trasnfer = 'Sencillo Hotel > Aeropuerto';
            break;
          case 'REDHH':
            $trasnfer = 'Redondo - Hotel > Hotel';
            break;
          case 'SEN/HH':
            $trasnfer = 'Sencillo - Hotel > Hotel';
            break;
        }
        switch ($reservation->method_payment) {
          case 'oxxo':
            $newnamepay = "OXXO";
            break;
          case 'transfer':
            $newnamepay = "TRANSFERENCIA";
            break;
          case 'airport':
            $newnamepay = "AEROPUERTO";
            break;
          case 'paypal':
            $newnamepay = "PAYPAL";
            break;
          case 'card':
            $newnamepay = "TARJETA";
            break;
          case 'deposit':
            $newnamepay = "DEPOSITO";
            break;
        }
        $labelChange ='';
        // PICKUP INTERHOTEL
        $arrivalTimeArgs = explode(' ', $reservation->time_arrival);
        $arrivalTimeArgs = explode(':', $arrivalTimeArgs[0]);

        $arrivalTimeArgs_ex = explode(' ', $reservation->time_exit);
        $arrivalTimeArgs_ex = explode(':', $arrivalTimeArgs_ex[0]);
        $new_pickup = "00:00:00";
        if ($reservation->pickup_entry) {
          $new_pickup = $reservation->pickup_entry;
        }
        $arrivalTimeArgs_pickup = explode(' ', $new_pickup);
        $arrivalTimeArgs_pickup = explode(':', $arrivalTimeArgs_pickup[0]);

        $arrivalTimeArgs_pickup_ex = explode(' ', $reservation->pickup);
        $arrivalTimeArgs_pickup_ex = explode(':', $arrivalTimeArgs_pickup_ex[0]);
      }
    ?>
</head>
<body>
    <input type="hidden" name="" id="inp_code_invoice" value="<?php echo $coinv  ?>">
    <input type="text" name="" id="inp_id_reservation" value="<?php echo $reservation->id_reservation ?>">
    <input type="hidden" name="" id="inp_id_user" value="<?php echo $_SESSION['id_user']?>">
    <?php if($reedit == 0 || $reedit == '' || $reedit != 1) { ?>
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
          <h4 class="">Reservación - <?php echo $coinv ?></h4>
          <?php
            if (isset($id_res) AND $id_res != null) {
          ?>
            <div class="row">
              <div class="col-lg-9 col-md-8 col-sm-8" >
                <div class=" pt-3 separador">
                  <h6>Información de la reserva interna</h6>
                </div>
                <div class=" pt-1" >
                    <div class="row pb-1">
                      <div class="col-sm-4">
                        <span>Localizador</span><br>
                        <small><strong><?php echo $reservation->code_client ?></strong></small>
                      </div>
                      <div class="col-sm-4">
                        <span>Asesor</span><br>
                        <small>
                          <strong><?php echo $reservation->name_advisor ?></strong>
                        </small>
                      </div>
                      <div class="col-sm-4">
                        <span>De la Agencia</span><br>
                        <?php if($reservation->id_agency == $reservation->of_the_agency || $reservation->of_the_agency == $id_user){ ?>
                          <small>
                          <strong></strong>
                          </small>
                        <?php }else{ ?>
                          <small>
                          <strong><?php echo $reservation->of_the_agency ?></strong>
                          </small>
                        <?php } ?>
                      </div>
                    </div>   
                </div>
                <div class=" pt-3 separador">
                  <h6>Información de la reservación</h6>
                </div>
                <div class=" pt-1" >
                    <div class="row pb-1">
                      <div class="col-sm-4">
                        <span>Hotel</span><br>
                        <small><strong><?php echo $reservation->transfer_destiny ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Traslado</span><br>
                        <small>
                          <strong><?php echo $trasnfer ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Servicio</span><br>
                        <small>
                          <strong><?php echo $reservation->type_service ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Pasajeros</span><br>
                        <small>
                          <strong><?php echo $reservation->number_adults ?></strong>
                        </small>
                      </div>
                    </div>   
                  <?php if ($reservation->type_transfer == 'SEN/HH' || $reservation->type_transfer == 'REDHH') { ?>
                    <div class="row pb-1">
                      <div class="col-sm-4">
                        <span>Hotel Origen</span><br>
                        <small><strong><?php echo $reservation->transfer_destiny ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Hotel Destino</span><br>
                        <small>
                          <strong><?php echo $reservation->destiny_interhotel ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Servicio Fecha/Hora</span><br>
                        <small>
                          <strong><?php echo $reservation->date_arrival.' / '.$reservation->time_arrival ?> Hrs</strong>
                        </small>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if($reservation->type_transfer == 'REDHH') { ?>
                    <div class="row pb-1">
                      <div class="col-sm-4">
                        <span>Hotel Origen</span><br>
                        <small><strong><?php echo $reservation->destiny_interhotel ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Hotel Destino</span><br>
                        <small>
                          <strong><?php echo $reservation->transfer_destiny ?> </strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Servicio Fecha/Hora</span><br>
                        <small>
                          <strong><?php echo $reservation->date_exit.' '.$reservation->pickup ?> Hrs</strong>
                        </small>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if($reservation->type_transfer == 'RED' || $reservation->type_transfer == 'SEN/AH') { ?>
                    <div class="row pt-1 pb-1">
                      <div class="col-sm-4">
                        <span>Llegada</span><br>
                        <small><strong><?php echo $reservation->date_arrival ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Hora de llegada</span><br>
                        <small>
                          <strong><?php echo $reservation->time_arrival ?> Hrs</strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Aerolinia</span><br>
                        <small>
                          <strong><?php echo $reservation->airline_in ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>No. de Vuelo</span><br>
                        <small>
                          <strong><?php echo $reservation->no_fly ?></strong>
                        </small>
                      </div>
                    </div> 
                  <?php }?>
                  <?php if ($reservation->type_transfer == 'RED' || $reservation->type_transfer == 'SEN/HA') { ?>   
                    <div class="row pt-1 pb-1">
                      <div class="col-sm-4">
                        <span>Salida</span><br>
                        <small><strong><?php echo $reservation->date_exit ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Hora de salida</span><br>
                        <small>
                          <strong><?php echo $reservation->time_exit ?> Hrs</strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Aerolinia</span><br>
                        <small>
                          <strong><?php echo $reservation->airline_out ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>No. de Vuelo</span><br>
                        <small>
                          <strong><?php echo $reservation->no_flyout ?></strong>
                        </small>
                      </div>
                    </div>
                    <div class="row pt-1 pb-1">
                      <div class="col-sm-4">
                        <span>Hora de Pickup</span><br>
                        <small><strong><?php echo $reservation->pickup_entry ?> Hrs</strong></small>
                      </div>
                    </div>
                  <?php } ?>
                    <div class="row pt-1 pb-1">
                      <div class="col-sm-4">
                        <span>Reservación</span><br>
                        <small><strong><?php echo $reservation->date_register_reservation ?></strong></small>
                      </div>
                      <div class="col-sm-3">
                        <span>Estado de reservación</span><br>
                        <small>
                          <strong><?php echo $reservation->status_reservation ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Método de pago</span><br>
                        <small>
                          <strong><?php echo $newnamepay ?></strong>
                        </small>
                      </div>
                      <div class="col-sm">
                        <span>Total</span><br>
                        <small>
                          <strong><?php echo $reservation->total_cost.' '.$currency ?></strong>
                        </small>
                      </div>
                    </div>
                    <?php 
                      if($reservation->type_service == 'compartido'){
                        $new_time_service = 'N/A';
                        if ($reservation->time_service) {
                          $new_time_service = $reservation->time_service.' Hrs';
                        }
                    ?>
                    <div class="row pt-1 pb-1">
                      <div class="col-sm-4">
                        <span>Hora de Servicio</span><br>
                        <small><strong><?php echo $new_time_service ?> </strong></small>
                      </div>
                    </div>
                    <?php } ?>
                </div>
                <div class=" pt-3 separador">
                  <h6>Información del cliente</h6>
                </div>
                <div class=" pt-2">
                  <div class="row pb-1">
                    <div class="col-sm-4">
                      <span>Nombre</span><br>
                      <small><strong><?php echo $reservation->name_client ?></strong></small>
                    </div>
                    <div class="col-sm">
                      <span>Correo Electrónico</span><br>
                      <small>
                        <strong><a href='#' class='copy_email' title='<?php echo $reservation->email_client ?>'><?php echo $newemail ?></a></strong>
                      </small>
                    </div>
                    <div class="col-sm">
                      <span>Teléfono</span><br>
                      <small>
                        <strong><?php echo $reservation->phone_client ?></strong>
                      </small>
                    </div>
                    <div class="col-sm">
                      <span>País</span><br>
                      <small>
                        <strong><?php echo $reservation->country_client ?></strong>
                      </small>
                    </div>
                  </div>
                  <?php if($reservation->comments_client){?>
                    <div class="row pb-1">
                      <div class="col-sm">
                        <span>Comentarios</span><br>
                        <small><strong><?php echo $reservation->comments_client ?></strong></small>
                      </div>
                    </div>
                  <?php } ?>
                </div>
                <div class=" pt-3 separador">
                  <h6>Información del proveedor y REP</h6>
                </div>
                <div class=" pt-2">
                  <div class="row pb-3">
                    <div class="col-sm-4">
                      <span>Llegada <small>(Proveedor)</small></span><br>
                      <small><strong><?php echo $provider_rep->{'name_provider_e'} ?></strong></small>
                    </div>
                    <div class="col-sm-3">
                      <span>Salida <small>(Proveedor)</small></span><br>
                      <small>
                        <strong><?php echo $provider_rep->{'name_provider_s'}?></strong>
                      </small>
                    </div>
                    <div class="col-sm">
                      <span>Llegada <small>(REP)</small></span><br>
                      <small>
                        <strong><?php echo $provider_rep->{'name_rep_e'}?></strong>
                      </small>
                    </div>
                    <div class="col-sm">
                      <span>Salida <small>(REP)</small></span><br>
                      <small>
                        <strong><?php echo $provider_rep->{'name_rep_s'}?></strong>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-4">
                <div class="accordion" id="accordionExample">
                  <div class="card">
                    <button class="btn btn-block btn-black card-header text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
                      <div class="" id="headingOne"> 
                          <h5>
                            Chat
                          </h5>
                      </div>
                    </button>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                      <div class="card-body content-card" id="content-messages">
                        
                      </div>
                      <div class="card-header">
                        <div class="row">
                          <div class="col-lg-9 col-md-9 col-sm-9 p-0">
                            <textarea name="" id="input_msj" class="form-control form-control-sm " rows="1"></textarea>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-3 pl-1">
                            <a href="#" class="btn btn-block btn-sm btn-black" id="btn_send_msj"><i class="far fa-paper-plane"></i></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <button class="btn btn-block btn-black card-header text-left" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne"> 
                      <div class="" id="headingTwo"> 
                          <h5>
                            Historial de Actividad
                          </h5>
                      </div>
                    </button>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                      <div class="card-body content-card">
                          <?php if($actividad != null) { ?>
                            <?php foreach($actividad as $act) { 
                              switch ($act->{'activity_type'}) {
                                case 'STATE':
                                    $labelChange = 'Cambio de estado';
                                    break;
                                case 'EDITSALE':
                                    $labelChange = 'Edición de reserva';
                                    break;
                                case 'SETPROVIDER':
                                    $labelChange = 'Asignación de Proveedor';
                                    break;
                                case 'UPDATEPROVIDER':
                                    $labelChange = 'Cambio de Proveedor';
                                    break;
                                case 'METHODPAYMENT':
                                    $labelChange = 'Cambio de Método de Pago';
                                    break;
                                case 'SETREP':
                                    $labelChange = 'Asignación de REP';
                                    break;
                                case 'UPDATEREP':
                                    $labelChange = 'Cambio de REP';
                                    break;
                              }
                            ?>
                            <div class="separador">
                              <div class="text-left">
                                <small>@<?php echo $act->{'username'} ?></small><br>
                                <small><strong><?php echo $labelChange; ?></strong> </small><br>
                                <small><?php echo $act->{'change_date'};?></small>
                              </div>
                              <div class="text-right">
                                <small><strong><?php echo $act->{'activity_status'} ?></strong></small>
                              </div>
                            </div>
                            <?php } ?>
                          <?php } else { ?>
                            <div class="text-center">
                              <small><p>No se encontró ningún registro de actividad.</p></small>
                            </div>
                          <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <button class="btn btn-block btn-black card-header text-left" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseOne"> 
                      <div class="" id="headingThree"> 
                          <h5 >
                              Historial de Gastos
                          </h5>
                      </div>
                    </button>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                      <div class="card-body content-card">
                        <?php if($expenses != null) { ?>
                          <?php
                            $total_expenses = 0;
                            foreach($expenses as $exp) {
                          ?>
                            <div class="separador" data-expenseid="<?php echo $exp->{'id_expenses'}; ?>">
                              <div class="text-left">
                                <small>@<?php echo $exp->{'username'}; ?></small><br>
                                <small><strong><?php echo $exp->{'concept'}; ?></strong> </small><br>
                                <small><?php echo $exp->{'date_expense'}; ?></small>
                              </div>
                              <div class="text-right">
                                <?php if($exp->{'name_provider'}) { ?>
                                  <small><?php echo $exp->{'name_provider'}; ?></small><br>

                                <?php }else{ ?>
                                  <small>---</small><br>

                                <?php } ?>
                                <small><strong><?php echo $exp->{'expense_amount'}.' '.$exp->{'type_currency'}; ?></strong></small>
                              </div>
                              <?php $total_expenses = $total_expenses + $exp->{'expense_amount'} ?>
                            </div>
                          <?php } ?>
                        <?php } else { ?>
                          <div class="text-center">
                            <small><p>No se encontró ningún registro de gasto.</p></small>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <button class="btn btn-block btn-black card-header text-left" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"> 
                      <div class="" id="headingFour"> 
                          <h5>
                              Historial de depositos
                          </h5>
                      </div>
                    </button>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                      <div class="card-body content-card">
                        <?php if($deposits != null) { ?>
                          <?php
                            $total_deposits = 0;
                            foreach($deposits as $dep) {
                          ?>
                          <div class="separador" data-depositid="<?php echo $exp->{'id_expenses'}; ?>">
                            <div class="text-left">
                              <small>@<?php echo $dep->{'username'}; ?></small><br>
                              <small><strong><?php echo $dep->{'concept'}; ?></strong> </small><br>
                              <small><?php echo $dep->{'date_expense'}; ?></small>
                            </div>
                            <div class="text-right">
                              <small><strong><?php echo $dep->{'expense_amount'}.' '.$dep->{'type_currency'}; ?></strong></small>
                            </div>
                          </div>

                          <?php } ?>
                        <?php } else { ?>
                          <div class="text-center">
                            <small><p>No se encontró ningún registro de deposito.</p></small>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="card">
                    <button class="btn btn-block btn-black card-header text-left" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"> 
                      <div class="" id="headingFive"> 
                          <h5 >
                              Conciliación
                          </h5>
                      </div>
                    </button>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                      <div class="card-body content-card">
                        <div class="text-left pb-2">
                            <small><strong>Comprovante de Conciliación</strong> </small><br>
                        </div>
                        <div class="text-left separador">
                              <input id="" type ="file" name="imagen" class=" p-0 form-control-sm" placeholder="Titulo" required>
                        </div>
                        <div class="separador">
                          <div class="text-left">
                              <small>@Mormex</small><br>
                              <small>
                                <a href="/agencias/vendor/src/conciliaciones/YhfgKX39342T-tras priv oscar.jpg" target="_blank">YhfgKX39342T-tras priv oscar.jpg</a>
                              </small><br>
                              <small><strong>Requiere Factura</strong></small><br>
                              <small class="font-italic">2021-02-19 10:49:12</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          <?php
            }else{
          ?>
            <div class="row">
                <div class="col-lg-12">
                    <h5>Hubo un error al traer los datos de la reservación</h5>
                </div>
            </div>
          <?php
            }
          ?>
      </div>
    <?php } ?>
    <?php if($reedit == 1) { ?>
      <div class="wrapper">
          <?PHP
              include('../include/navigation.php');
          ?>
          
          
          <h4 class="">Editar reservación - <?php echo $coinv ?></h4>
          <div class=" d-flex justify-content-center">
              <div class="alert alert-dismissible w-100" id="alert-msg">
                      <p style="margin-bottom: 0;">
                          <input id="text-msg" type="text" class="sinbordefond w-100" value="">
                      </p>   
                      <button type="button" class="close" id="alert-close">&times;</button>  
              </div>
          </div>
          <?php
            if (isset($id_res) AND $id_res != null) {
          ?>
            <div class="row ">
                <div class="col-lg-10" id="content_edit_reserva">
                  <div class=" mb-3">
                      <?php 
                        $today = date('Y-m-d');
                          $date_today = date_create($today);
                          $date_exit = date_create($reservation->date_exit);
                          $date_differences = date_diff($date_today, $date_exit);
                          $date_arrival = date_create($reservation->date_arrival);
                          $date_differences_arrival = date_diff($date_today, $date_arrival);
                          if ($reservation->method_payment == 'card' || $reservation->method_payment == 'paypal') {
                            $cargo = 0.95;

                            $new_total_cost_commision = round(($reservation->total_cost / $cargo) + $reservation->agency_commision,0);
                          }else{
                              $new_total_cost_commision= $reservation->total_cost;
                          }
                          if ($date_differences_arrival->days >= 2) {
                              // echo 'LA FECHA ENTRADA SI ES MAYOR A 2 DIAS <br>';
                          }else { 
                            // echo 'LA FECHA ENTRADA NO ES MAYOR A 2 DIAS <br> ';
                          }
                          if ($date_differences->days >= 2) {
                              // echo 'LA FECHA SALIDA SI ES MAYOR A 2 DIAS';
                          }else {
                              // echo 'LA FECHA SALIDA NO ES MAYOR A 2 DIAS';
                          }
                      ?>
                      <div id="code_booking">
                        <div class="d-flex justify-content-center row" >
                        
                          <div class="col-xl-12 col-md-12 pt-1 content_type_info">
                              <div class=" pt-3 separador">
                                <h6>Datos de codigo de reserva externa / (Solo Yamevi)</h6>
                              </div>
                          </div>
                          <div class="col-xl-12 pt-3">
                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="">Localizador</label>
                                <input type="text" class="form-control form-control-sm" id="inp_code_client_edit" placeholder="ID de reserva externa" value='<?php echo $reservation->code_client?>'>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="">Asesor</label>
                                <input type="text" class="form-control form-control-sm" id="inp_asesor_edit" placeholder="Nombre de Asesor de Venta" value='<?php echo $reservation->name_advisor ?>'>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="">De la Agencia</label>
                                <!-- ESTE ES EL ID DE LA AGENICA QUE TOMARA EN CASO DE QUE NO HAYA OTRA AGENCIA DIFERENTE -->
                                <input type="hidden" class="form-control form-control-sm" id="inp_agency_edit" placeholder="ID de reserva externa" value='<?php echo $reservation->of_the_agency?>'>
                                <?php if($reservation->id_agency == $reservation->of_the_agency || $reservation->of_the_agency == $id_user){ ?>
                                  <input list="agencies" name="agencies" id="inp_ofagency_edit" type="text" class="form-control form-control-sm w-100" placeholder="Selaña la agencia" value="">
                                <?php }else{ ?>
                                  <input list="agencies" name="agencies" id="inp_ofagency_edit" type="text" class="form-control form-control-sm w-100" placeholder="Selaña la agencia" value='<?php echo $reservation->of_the_agency ?>'>
                                <?php } ?>
                                <datalist id="agencies">
                                    <?php
                                    $query = "SELECT * FROM agencies";
                                    $result = mysqli_query($con,$query);
                                    if ($result) {
                                        while($row = mysqli_fetch_array($result)){
                                            echo '<option value = "'.$row['id_agency'].'"> '.$row['name_agency'].'</option>';
                                        }
                                        
                                    }else{
                                        echo '<option value="">No hay agencias registradas</option>';
                                    }
                                    ?>
                                </datalist>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center row">
                        <div class="col-xl-12 col-md-12 content_type_info">
                            <div class=" pt-1 separador">
                              <h6>Datos de traslado</h6>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 pt-2">
                            <div class="form_details">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="">Hotel</label>
                                        <input list="encodings" id="inp_hotel_edit" name="inp_hotel_edit" placeholder="Ingresa el hotel" class="form-control form-control-sm" value='<?php echo $reservation->transfer_destiny ?>'>
                                        <datalist id="encodings">
                                            <?php
                                                $query = "SELECT * FROM hotels";
                                                $result = mysqli_query($con,$query);
                                                if ($result) {
                                                    while($row = mysqli_fetch_array($result)){
                                                        echo '<option  value = "'.$row['name_hotel'].'"> </option>';
                                                    }
                                                    
                                                }else{
                                                    echo '<option value="">No hay zonas registradas</option>';
                                                }
                                            ?>
                                        </datalist>   
                                    </div>
                                    <div class="form-group col-md-3" id="content_inp_interhotel">
                                        <label for="">Hotel Interhotel</label>
                                        <input list="encodings" id="inp_hotel_interhotel_edit" name="inp_hotel_interhotel_edit" placeholder="Ingresa el hotel" class="form-control form-control-sm" value='<?php echo $reservation->destiny_interhotel ?>'>
                                        <datalist id="encodings">
                                            <?php
                                                $query = "SELECT * FROM hotels";
                                                $result = mysqli_query($con,$query);
                                                if ($result) {
                                                    while($row = mysqli_fetch_array($result)){
                                                        echo '<option  value = "'.$row['name_hotel'].'"> </option>';
                                                    }
                                                    
                                                }else{
                                                    echo '<option value="">No hay zonas registradas</option>';
                                                }
                                            ?>
                                        </datalist>   
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="">Traslado</label>
                                        <select class="custom-select custom-select-sm " id="inp_traslado_up" name="inp_traslado_edit" >
                                            <option value="">Seleccione tipo de traslado</option>
                                            <?php if($reservation->type_transfer == 'RED') { ?> <option value="RED" selected="selected">Redondo</option> <?php }else{ ?> <option value="RED">Redondo</option> <?php }?>
                                            <?php if($reservation->type_transfer == 'SEN/AH') { ?> <option value="SEN/AH" selected="selected">Aeropuerto - Hotel</option> <?php }else{ ?> <option value="SEN/AH">Aeropuerto - Hotel</option> <?php }?>
                                            <?php if($reservation->type_transfer == 'SEN/HA') { ?> <option value="SEN/HA" selected="selected">Hotel - Aeropuerto</option> <?php }else{ ?> <option value="SEN/HA">Hotel - Aeropuerto</option> <?php }?>
                                            <?php if($reservation->type_transfer == 'REDHH') { ?> <option value="REDHH" selected="selected">Redondo / Hotel - Hotel</option> <?php }else{ ?> <option value="REDHH">Redondo / Hotel - Hotel</option> <?php }?>
                                            <?php if($reservation->type_transfer == 'SEN/HH') { ?> <option value="SEN/HH" selected="selected">Sencillo / Hotel - Hotel</option> <?php }else{ ?> <option value="SEN/HH">Sencillo / Hotel - Hotel</option> <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">Servicio</label>
                                        <select class="custom-select custom-select-sm " id="inp_servicio_edit" name="inp_servicio_edit">
                                            <option value="">Seleccione tipo de servicio</option>
                                            <?php if($reservation->type_service == 'compartido') { ?> <option id="compartido_ts" value="compartido" selected="selected">Compartido</option> <?php }else{ ?> <option id="compartido_ts" value="compartido">Compartido</option> <?php }?>
                                            <?php if($reservation->type_service == 'privado') { ?> <option value="privado" selected="selected">Privado</option> <?php }else{ ?> <option value="privado">Privado</option> <?php }?>
                                            <?php if($reservation->type_service == 'lujo') { ?> <option value="lujo" selected="selected">Lujo</option> <?php }else{ ?> <option value="lujo">Lujo</option> <?php }?>  
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label for="">Pasajeros</label>
                                        <select class="custom-select custom-select-sm" id="inp_pasajeros_edit" name="inp_pasajeros_edit" placeholder="Seleccione núm. de pasajeros">
                                            <?php
                                                for($valor = 1; $valor <= 16; $valor++) {
                                                    echo "<option value='$valor'";
                                                    if ($reservation->number_adults == $valor) { echo ' selected="selected"';}
                                                    echo ">$valor</option>";
                                            }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-12 col-md-12 content_type_info">
                            <div class=" pt-1 separador">
                              <h6>Datos de vuelo y/o pickup</h6>
                            </div>
                        </div>
                        <!-- EL NUEVO -->
                        <div class="col-xl-12 pt-2">
                              <div class="form_details">
                          
                                  <div class="form-row" id="inps_entrada_edit">
                                      <div class="form-group mb-0 col-md-3">
                                          <label id="label_date_star" for="datepicker_star">Llegada</label>
                                          <div class="input-group">
                                              <input type="text" id="datepicker_arrival_edit" name="datepicker_arrival_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm"  aria-describedby="date" value='<?= $reservation->date_arrival; ?>' >
                                              <div class="input-group-append mr-2">
                                                  <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-0 col-md-3">
                                          <label for="">Aerolina</label>
                                          <input type="text" class="form-control form-control-sm" id="inp_airline_entry_edit" placeholder="Nombre de aerolina"  value='<?php echo $reservation->airline_in ?>' >
                                      </div>
                                      <div class="form-group mb-0 col-md-3">
                                          <label for="">Número de Vuelo</label>
                                          <input type="text" class="form-control form-control-sm" id="inp_nofly_entry_edit" placeholder="Número de vuelo" value='<?php echo $reservation->no_fly ?>'>
                                      </div>
                                      <div class="form-group mb-0 col-md-3">
                                          <div class="row">
                                              <div class="col-md-12">                                            
                                                  <label for="">Hora</label>
                                              </div>
                                              <div class="form-group col-xl-4 col-md-5 pr-1">
                                                  <select class="form-control form-control-sm" id="inp_hour_entry_edit">
                                                    <?php for($i = 1; $i < 24; $i++) { ?>
                                                    <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs[0]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                    <?php } ?>
                                                  </select>
                                              </div>
                                              <div class="col-md-1 p-1 text-center">
                                                  <span>:</span>
                                              </div>
                                              <div class="form-group col-xl-4 col-md-5 pl-1">
                                                  <select class="form-control form-control-sm" id="inp_minute_entry_edit">
                                                    <?php for($i = 0; $i < 60; $i++) { ?>
                                                        <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs[1]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                    <?php } ?>
                                                  </select>
                                              </div>
                                              <div class="col-md-1 p-1 text-center">
                                                  <span>Hrs</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-row" id="inps_salida_edit">
                                      <div class="form-group mb-0 col-md-3">
                                          <div class="form-group pb-2" id="content_date_end">
                                              <label for="datepicker_end">Salida</label>
                                              <div class="input-group">
                                                  <input type="text" id="datepicker_exit_edit" name="datepicker_exit_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date"  value='<?= $reservation->date_exit; ?>'>
                                                  <div class="input-group-append mr-2">
                                                      <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-0 col-md-3">
                                          <label for="">Aerolina</label>
                                          <input type="text" class="form-control form-control-sm" id="inp_airline_exit_edit" placeholder="Nombre de aerolina" value='<?php echo $reservation->airline_out ?>'>
                                      </div>
                                      <div class="form-group mb-0 col-md-3">
                                          <label for="">Número de Vuelo</label>
                                          <input type="text" class="form-control form-control-sm" id="inp_nofly_exit_edit" placeholder="Número de vuelo" value='<?php echo $reservation->no_flyout ?>'>
                                      </div>
                                      <div class="form-group  mb-0 col-md-3">
                                          <div class="row">
                                              <div class="col-md-12">                                            
                                                  <label for="exampleFormControlSelect1">Hora</label>
                                              </div>
                                              <div class="form-group col-xl-4 col-md-5 pr-1">
                                                  <select class="form-control form-control-sm" id="inp_hour_exit_edit">
                                                    <?php for($i = 1; $i < 24; $i++) { ?>
                                                    <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_ex[0]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                    <?php } ?>
                                                  </select>
                                              </div>
                                              <div class="col-md-1 p-1 text-center">
                                                  <span>:</span>
                                              </div>
                                              <div class="form-group col-xl-4 col-md-5 pl-1">
                                                  <select class="form-control form-control-sm" id="inp_minute_exit_edit">  
                                                    <?php for($i = 0; $i < 60; $i++) { ?>
                                                        <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_ex[1]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                    <?php } ?>
                                                  </select>
                                              </div>
                                              <div class="col-md-1 p-1 text-center">
                                                  <span>Hrs</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-row" id="inp_pickup_edit">
                                      <div id="pick_up_arrival" class="col-md-6">
                                          <div class="form-row">
                                              <div class="form-group mb-0 col-md-6">
                                                  <label id="label_date_star" for="datepicker_star">Fecha de Servicio</label>
                                                  <div class="input-group">
                                                      <input type="text" id="datepicker_pickup_arrival_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm"  aria-describedby="date" value='<?= $reservation->date_arrival; ?>' >
                                                      <div class="input-group-append mr-2">
                                                          <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="form-group mb-0 col-md-6" id="inp_pickup_enter_edit">
                                                  <div class="row">
                                                      <div class="col-md-12">                                            
                                                          <label for="exampleFormControlSelect1">Hora de Pickup <small>(Ida)</small></label>
                                                      </div>
                                                      <div class="form-group mb-0 col-xl-4 col-md-4 pr-1">
                                                          <select class="form-control form-control-sm" id="inp_hour_pick_edit">
                                                            <?php for($i = 1; $i < 24; $i++) { ?>
                                                              <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs[0]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                            <?php } ?>
                                                          </select>
                                                      </div>
                                                      <div class="col-md-1 p-1 text-center">
                                                          <span>:</span>
                                                      </div>
                                                      <div class="form-group mb-0 col-xl-4 col-md-4 pl-1">
                                                          <select class="form-control form-control-sm" id="inp_minute_pick_edit">
                                                            <?php for($i = 0; $i < 60; $i++) { ?>
                                                              <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs[1]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                            <?php } ?>
                                                          </select>
                                                      </div>
                                                      <div class="col-md-1 p-1 text-center">
                                                          <span>Hrs</span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div id="pick_up_exit" class="col-md-6">
                                          <div class="form-row">
                                              <div class="form-group mb-0 col-md-6">
                                                  <div class="form-group mb-0 pb-2" id="content_date_end">
                                                      <label for="datepicker_end">Salida</label>
                                                      <div class="input-group">
                                                          <input type="text" id="datepicker_pickup_exit_edit" name="datepicker_end_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date" value='<?= $reservation->date_exit; ?>'>
                                                          <div class="input-group-append mr-2">
                                                              <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="form-group mb-0 col-md-6" id="inp_pickup_exit_edit">
                                                  <div class="row">
                                                      <div class="col-md-12">                                            
                                                          <label for="exampleFormControlSelect1">Hora de Pickup <small>(Regreso)</small></label>
                                                      </div>
                                                      <div class="form-group mb-0 col-xl-4 col-md-4 pr-1">
                                                          <select class="form-control form-control-sm" id="inp_hour_pick_inter_edit">
                                                            <?php for($i = 1; $i < 24; $i++) { ?>
                                                              <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_ex[0]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                            <?php } ?>
                                                          </select>
                                                      </div>
                                                      <div class="col-md-1 p-1 text-center">
                                                          <span>:</span>
                                                      </div>
                                                      <div class="form-group mb-0 col-xl-4 col-md-4 pl-1">
                                                          <select class="form-control form-control-sm" id="inp_minute_pick_inter_edit">
                                                            <?php for($i = 0; $i < 60; $i++) { ?>
                                                              <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_ex[1]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                            <?php } ?>
                                                          </select>
                                                      </div>
                                                      <div class="col-md-1 p-1 text-center">
                                                          <span>Hrs</span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-row pb-2" id="">
                                      <div class="form-group mb-0 col-md-3" id="inp_time_service_edit">
                                          <label id="" for="">Hora de abordaje</label>
                                          <div class="form-group col-xl-12 col-md-12 p-0">
                                            
                                              <select class="form-control" id="inp_time_service">
                                                  <option value="<?php echo $reservation->time_service ?>"><?php echo $time_ser ?></option>
                                                  <option value="08:00:00">08:00</option>
                                                  <option value="08:40:00">08:40</option>
                                                  <option value="09:20:00">09:20</option>
                                                  <option value="10:00:00">10:00</option>
                                                  <option value="10:40:00">10:40</option>
                                                  <option value="11:20:00">11:20</option>
                                                  <option value="12:00:00">12:00</option>
                                                  <option value="12:40:00">12:40</option>
                                                  <option value="13:20:00">13:20</option>
                                                  <option value="14:00:00">14:00</option>
                                                  <option value="14:40:00">14:40</option>
                                                  <option value="15:20:00">15:20</option>
                                                  <option value="16:00:00">16:00</option>
                                                  <option value="16:40:00">16:40</option>
                                                  <option value="17:20:00">17:20</option>
                                                  <option value="18:00:00">18:00</option>
                                                  <option value="18:40:00">18:40</option>
                                                  <option value="19:20:00">19:20</option>
                                                  <option value="20:00:00">20:00</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group mb-0 col-md-4" id="inp_pickup">
                                        <div class="row">
                                            <div class="col-md-12">                                            
                                                <label for="exampleFormControlSelect1">Hora de Pickup</label>
                                            </div>
                                            <div class="form-group mb-0 col-xl-4 col-md-4 pr-1">
                                                <select class="form-control form-control-sm" id="inp_hour_pick">
                                                  <?php for($i = 1; $i < 24; $i++) { ?>
                                                    <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_pickup[0]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                  <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1 p-1 text-center">
                                                <span>:</span>
                                            </div>
                                            <div class="form-group mb-0 col-xl-4 col-md-4 pl-1">
                                                <select class="form-control form-control-sm" id="inp_minute_pick">
                                                  <?php for($i = 0; $i < 60; $i++) { ?>
                                                    <option value="<?= $i < 10 ? '0'.$i : $i; ?>" <?php if($i == $arrivalTimeArgs_pickup[1]){ ?> selected="selected" <?php } ?> ><?= $i < 10 ? '0'.$i : $i; ?></option>
                                                  <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1 p-1 text-center">
                                                <span>Hrs</span>
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm" id="inp_before_pickup" placeholder="Número de vuelo" value='<?php echo $reservation->pickup_entry ?>'>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              
                              
                              
                        </div>
                        <br>
                        <div class="col-xl-12 col-md-12 content_type_info">
                            <div class=" pt-1 separador">
                              <h6>Datos de pago y estado</h6>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 pt-2">
                            <div class="form_details">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="">Fecha de Reserva</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_date_register_res_edit" placeholder="Fecha de Registro de Reservación" value='<?php echo $reservation->date_register_reservation ?>' disabled>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">Estado</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_status_reserva_edit" placeholder="Estado de la Reservación" value='<?php echo $reservation->status_reservation ?>' disabled>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="inputEmail4">Método Pago</label>
                                        <select class="custom-select custom-select-sm " id="inp_method_payment_edit" name="inp_method_payment_edit" >
                                            <option id="transfer" value="transfer">TRANSFERENCIA</option>
                                            <?php if($reservation->method_payment == 'card') { ?> <option id="card" value="card" selected="selected">TARJETA</option> <?php }else{ ?> <option id="card" value="card">TARJETA</option> <?php }?>
                                            <?php if($reservation->method_payment == 'oxxo') { ?> <option id="oxxo" value="oxxo" selected="selected">OXXO</option> <?php }else{ ?> <option id="oxxo" value="oxxo">OXXO</option> <?php }?>
                                            <?php if($reservation->method_payment == 'paypal') { ?> <option id="paypal" value="paypal" selected="selected">PAYPAL</option> <?php }else{ ?> <option id="paypal" value="paypal">PAYPAL</option> <?php }?> 
                                            <?php if($reservation->method_payment == 'airport') { ?> <option id="airport" value="airport" selected="selected">PAGO AL ABORDAR</option> <?php }else{ ?> <option id="airport" value="airport">PAGO AL ABORDAR</option> <?php }?> 
                                            <?php if($reservation->method_payment == 'deposit') { ?> <option id="deposit" value="deposit" selected="selected">DEPOSITO</option> <?php }else{ ?> <option id="deposit" value="deposit">DEPOSITO</option> <?php }?> 
                                            <?php if($reservation->method_payment == 'a_pa') { ?><option id="a_pa" value="a_pa" selected="selected">AGENCIA - PAGO AL ABORDAR</option><?php }else{?><option id="a_pa" value="a_pa" >AGENCIA - PAGO AL ABORDAR</option><?php }?> 
                                            <?php if($reservation->method_payment == 'a_transfer') { ?><option id="a_transfer" value="a_transfer" selected="selected">AGENCIA - TRANSFERENCIA</option><?php }else{?><option id="a_transfer" value="a_transfer" >AGENCIA - TRANSFERENCIA</option><?php }?> 
                                            <?php if($reservation->method_payment == 'a_paypal') { ?><option id="a_paypal" value="a_paypal" selected="selected">AGENCIA - PAYPAL</option><?php }else{ ?><option id="a_paypal" value="a_paypal" >AGENCIA - PAYPAL</option><?php }?> 
                                            <?php if($reservation->method_payment == 'a_card') { ?><option id="a_card" value="a_card" selected="selected">AGENCIA - TARJETA</option><?php }else {?><option id="a_card" value="a_card">AGENCIA - TARJETA</option><?php }?> 
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-0 col-md-2 pl-1" id="content_subtotal">
                                        <label for="">Subtotal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" id="inp_total_cost_edit" placeholder="Subtotal" value='<?php echo $reservation->total_cost ?>' disabled >
                                            <div class="input-group-append mr-2">
                                                <span class="input-group-text" id="currency"><small><?php echo $reservation->type_currency ?></small></span>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control form-control-sm" id="inp_total_cost_b" placeholder="Subtota" value='<?php echo $reservation->total_cost ?>' disabled ><br>
                                    </div>
                                    <div class="form-group mb-0 col-md-2" id="content_comission_agency">
                                        <label for="">Comisión</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_agency_commision_edit" placeholder="Comisión" value='<?php echo $reservation->agency_commision ?>' >
                                    </div>
                                    <div class="form-group mb-0 col-md-2 pl-1" id="content_total_commision">
                                        <label for="">Total</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" id="inp_total_cost_commesion_edit" placeholder="Costo Total" value='<?php echo $new_total_cost_commision ?>' disabled >
                                            <div class="input-group-append mr-2">
                                                <span class="input-group-text" id="currency"><small><?php echo $reservation->type_currency ?></small></span>
                                            </div>
                                        </div>
                                            <input type="hidden" class="form-control form-control-sm" id="inp_total_cost_commesion_b" placeholder="Costo Total" value='<?php echo $reservation->total_cost_commision ?>' disabled ><br>
                                            <?php if($reservation->method_payment == 'paypal' || $reservation->method_payment == 'card'){ ?>
                                              <input type="hidden" id="inp_total_cost_before" class="form-control form-control-sm" value='<?php echo $reservation->total_cost_commision ?>'>
                                            <?php }else{ ?>
                                              <input type="hidden" id="inp_total_cost_before" class="form-control form-control-sm" value='<?php echo $reservation->total_cost ?>'>
                                            <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-xl-12 col-md-12 content_type_info">
                            <div class=" pt-1 separador">
                              <h6>Datos de cliente</h6> 
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 pt-2">
                            
                            <div class="form_details">
                                <div class="form-row">
                                    <div class="form-group mb-0 col-md-2">
                                        <label for="">Nombre (s)</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_name_client_edit" placeholder="Nombre del Cliente" value='<?php echo $reservation->name_client ?>'  >
                                    </div>
                                    <div class="form-group mb-0 col-md-2">
                                        <label for="">Apellido Paterno</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_lastname_client_edit" placeholder="Apellido del Cliente" value='<?php echo $reservation->last_name ?>'   >
                                    </div>
                                    <div class="form-group mb-0 col-md-2">
                                        <label for="">Apellido Materno</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_mother_lastname_edit" placeholder="Apellido del Cliente"  value='<?php echo $reservation->mother_lastname ?>'  >
                                    </div>
                                    <div class="form-group mb-0 col-md-2">
                                        <label for="inputEmail4">Correo</label>
                                        <input type="email" class="form-control form-control-sm" id="inp_email_client_edit" placeholder="Email del Cliente"  value='<?php echo $reservation->email_client ?>' >
                                    </div>
                                    <div class="form-group mb-0 col-md-2">
                                        <label for="">Teléfono Celular</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_phone_client_edit" placeholder="Teléfono del Cliente"  value='<?php echo $reservation->phone_client ?>'  >
                                    </div>
                                    <div class="form-group mb-0 col-md-2 pl-1">
                                        <label for="">País</label>
                                        <input type="text" class="form-control form-control-sm" id="inp_country_client_edit" placeholder="País del Cliente" disabled  value='<?php echo $reservation->country_client ?>'>
                                    </div>
                                    <div class="form-group mb-0 col-md-12">
                                        <label for="">Peticiones Especiales</label>
                                        <textarea name="" class="form-control form-control-sm" id="inp_special_requests_edit" rows="3" ><?php echo $reservation->comments_client ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                      </div>
                  </div>
                </div>
                <div class="col-lg-10">
                    <a href="#" class="btn btn-black btn-block" id="update_details_reservation">G U A R D A R</a>
                </div>
                <br>
            </div>
          <?php
            }else{
          ?>
            <div class="row">
                <div class="col-lg-12">
                    <h5>Hubo un error al traer los datos de la reservación</h5>
                </div>
            </div>
          <?php
            }
          ?>
      </div>
    <?php } ?>
    
    <!-- Modal Success -->
    <div id="update"  class="modal fade"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <div class="icon-box">
                        <i class="material-icons">&#xE876;</i>
                    </div>
                    <button type="button" class="close" id="close_alert_edit" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="code_invoice_edit_alert">
                    <h4>Excelente!</h4>	
                        <div id="msj_success"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- Modal Error -->
    <div id="myModalerror" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="header_error">
                    <div class="icon-box">
                        <i class="material-icons">&#xE5CD;</i>
                    </div>
                    <button type="button" class="close" id="close_alert_edit" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4>Ooops!</h4>	
                    <p>Hubo un error al actualizar su reservacion. Intente más tarde.</p>
                    <button class="btn btn-success" data-dismiss="modal" onclick="window.location.href='reservations.php'"><span>Mis Reservaciones</span> <i class="material-icons">&#xE5C8;</i></button>
                </div>
            </div>
        </div>
    </div> 
    <?php
    include('../include/scrips.php');
    ?>
    <script>
        
    </script>
    <script src="../../assets/js/reservations.js"></script>
    <script src="../../assets/js/servicies.js"></script>
    <script src="../../assets/js/navigation.js"></script>
</body>
</html>