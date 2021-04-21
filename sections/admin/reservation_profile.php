<?php
require_once '../../config/conexion.php';
session_start();
$id_res = $_GET['reservation'];
$coinv = $_GET['coinv'];
$reedit = 0;
if($_GET['reedit']) {
  $reedit =  $_GET['reedit'];
}
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
    <link rel="icon" href="../../assets/img/yamevIcon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if($reedit == 0 || $reedit == '' || $reedit != 1){?>
    <title>Detalles - <?php echo $coinv  ?></title>
    <?php } ?>    
    <?php if($reedit == 1){?>
    <title>Editar - <?php echo $coinv  ?></title>
    <?php } ?>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    include('../../model/reservaciones.php');
    $reserva_model = new Reservacion();
    ?>
</head>
<body>
    <?php if($reedit == 0 || $reedit == '' || $reedit != 1) { ?>
    <div class="wrapper">
        <?PHP
            include('../include/navigation.php');
        ?>
        
        <div class=" d-flex justify-content-center">
           <div class="alert alert-success alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h4 class="">Reservación - <?php echo $coinv ?></h4>
        <?php
          if (isset($id_res) AND $id_res != null) {
              $reservation = json_decode($reserva_model->getDetailsReservation($id_res));
              $provider_rep = json_decode($reserva_model->getProviderAndRepReservation($id_res));
              $expenses = json_decode($reserva_model->getExpensesReservation($id_res));
              $deposits = json_decode($reserva_model->getDepositsReservation($id_res));
              $actividad = json_decode($reserva_model->getActivitiesReservation($id_res));
              $trasnfer = '';
              $newnamepay = '';
              $labelChange = '';
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
        ?>
          <div class="row">
            <div class="col-lg-9" >
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
                      <small><strong><?php echo $reservation->pickup ?> Hrs</strong></small>
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
                  <div class="col-sm-3">
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
            <div class="col-lg-3">
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
                        <div class="col-sm-10 p-0">
                          <textarea name="" id="input_msj" class="form-control form-control-sm " rows="1"></textarea>
                        </div>
                        <div class="col-sm-2 pl-1">
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
                            <small><strong><?php echo $exp->{'expense_amount'}.' '.$exp->{'type_currency'}; ?></strong></small>
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
                <div class="card">
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
                </div>
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
        
        <div class=" d-flex justify-content-center">
           <div class="alert alert-success alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h4 class="">Editar reservación - <?php echo $coinv ?></h4>
        <?php
          if (isset($id_res) AND $id_res != null) {
              $reservation = json_decode($reserva_model->getDetailsReservation($id_res));
              $provider_rep = json_decode($reserva_model->getProviderAndRepReservation($id_res));
              $expenses = json_decode($reserva_model->getExpensesReservation($id_res));
              $deposits = json_decode($reserva_model->getDepositsReservation($id_res));
              $actividad = json_decode($reserva_model->getActivitiesReservation($id_res));
              $trasnfer = '';
              $newnamepay = '';
              $labelChange = '';
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
        ?>
          <div class="row">
            <div class="col-lg-9" >
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
                        <strong><?php echo $reservation->name_airline ?></strong>
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
                      <small><strong><?php echo $reservation->pickup ?> Hrs</strong></small>
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
                  <div class="col-sm-3">
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
            <div class="col-lg-3">
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
                        <div class="col-sm-10 p-0">
                          <textarea name="" id="input_msj" class="form-control form-control-sm " rows="1"></textarea>
                        </div>
                        <div class="col-sm-2 pl-1">
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
                            <small><strong><?php echo $exp->{'expense_amount'}.' '.$exp->{'type_currency'}; ?></strong></small>
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
                <div class="card">
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
                </div>
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
    <?php
    include('../include/scrips.php');
    ?>
    <script>
        
    </script>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/servicies.js"></script>
</body>
</html>