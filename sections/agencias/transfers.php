
<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_agency'])) {
}else{
 header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Traslados</title>
    <?php include('../include/estilos_agencies.php')?>
</head>
<body id="body"> 
    <button  id="btnToTop" title="Go to top"><i class="fas fa-angle-up"></i></button>   
    <div class="backgound_img" data-background="../../assets/img/hero/h1_hero.jpg">
        <?php include('../include/navigation_Agencies.php');?>


        <div id="content_results" class="pr-3 pl-3">
            <div class="row pr-3 pl-3">
                <div class="col-lg-12 col-md-12 col-sm-6 ">
                    <div class="card p-1">
                        <div class="card-body">
                            <form id="form_reserva_edit" > 
                                <input type="hidden" class="" value="<?php echo $_SESSION['todaysale']?>" id="inp_todaysale_edit">    
                                <div class="form-row">
                                    <div class="form-inline col-md-3">
                                        <label for="inp_hotel" class="pr-3"><i class="fas fa-hotel"></i></label>
                                        <input list="encodings" id="inp_hotel_edit" name="inp_hotel_edit" placeholder="Ingresa el hotel" class="form-control form-control-plaintext form-control-sm ">
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
                                    <div class="form-inline col-md-2">
                                        <label for="inp_pasajeros" class="pr-2"><i class="fas fa-user-friends"></i></label>
                                        <select class="custom-select custom-select-sm" id="inp_pasajeros_edit" name="inp_pasajeros_edit" placeholder="Seleccione núm. de pasajeros">
                                            <option value="">Seleccione núm. de pasajeros</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                        </select>
                                    </div>
                                    <div class="form-inline col-md-2">
                                        <label for="inp_traslado_edit" class="pr-2"><i class="fas fa-bus"></i></label>
                                        <select class="custom-select custom-select-sm " id="inp_traslado_edit" name="inp_traslado_edit">
                                            <option value="">Seleccione tipo de traslado</option>
                                            <option value="RED">Redondo</option>
                                            <option value="SEN/AH">Aeropuerto - Hotel</option>
                                            <option value="SEN/HA">Hotel - Aeropuerto</option>
                                            <option value="REDHH">Redondo / Hotel - Hotel</option>
                                            <option value="SEN/HH">Sencillo / Hotel - Hotel</option>
                                        </select>
                                    </div> 
                                    <div class="form-inline col-md-2" id="content_date_star_edit">
                                        <label id="label_date_star_edit" class=" pr-3" for="datepicker_star"><i id="icon_date_s" class="fas fa-calendar-alt"></i></label>
                                        <input type="text" id="datepicker_star_edit" name="datepicker_star_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-plaintext form-control-sm" aria-describedby="date">
                                    </div>
                                    <div class="form-inline col-md-2" id="content_date_end_edit">
                                        <label for="datepicker_end" class="pr-3"><i id="icon_date_e" class="fas fa-calendar-minus"></i></label>
                                        <input type="text" id="datepicker_end_edit" name="datepicker_end_edit" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-plaintext form-control-sm" aria-describedby="date">
                                    </div> 
                                    <div class="form-inline col-md-1" id="content_btn_search">
                                        <button type="submit" class="btn_animation btn  btn-sm btn-block btn-yamevi_2"  id="btn_search_reserva_edit"><span>Buscar </span></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class=" mt-4 mb-5">
                        <div class="d-flex justify-content-center row">
                            <div class="col-xl-12 col-md-12 content_steps">
                                <h3>PASO 2</h3>
                                <p>Elija el servicio de su preferencia y llene los detalles de su vuelo...</p>
                            </div>
                            <div class="col-xl-12 col-md-12 content_card_results pt-2" >
                                <div class="row mb-3 mt-3 p-2 bg-white border rounded">
                                    <div class="col-md-3 mt-1 text-center content_card_result_center" >
                                        <div>
                                            <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/priv_com.png">
                                            <br><br>
                                            <h5 style="text-transform: uppercase;">SERVICIO <span>PRIVADO</span></h5>
                                        </div>
                                    </div>
                                    <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                                        <p><small>El servicio privado es por van, NO por pasajero. Servicio disponible 24/7</small></p>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Precio por Vehículo</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Incluye todos los impuestos y tasas aeroportuarias.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Seguro de viajero.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Recepción por uno de nuestros representantes.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> 24 Horas de servicio.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Servicio puerta a puerta en Cancun & Riviera Maya.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Vehículo privado – NO COMPARTIDO.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Vehículo espacioso para los pasajeros y el equipaje.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Un asiento de seguridad para niños incluido - Cuando lo solicite.</span></div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 border-left mt-1 ">
                                        <div class="w-100 text-center">
                                            <div class=" text-center align-items-center">
                                                <small><strong>Riu Dunamar</strong></small><br>
                                                <small>Sencillo - Aeropuerto a Hotel</small><br>
                                                <small>Pasajeros: 2</small><br>
                                            </div>
                                            <div class="row mt-2 content_prices_results">
                                                <div class="col-xl-6 col-md-12 ">
                                                    <i class="fal fa-circle"></i>
                                                    <h5>SENCILLO</h5>
                                                    <h5 class="mt-1"><strong>---</strong></h5>
                                                </div>
                                                <div class="col-xl-6 col-md-12">
                                                    <i class="fal fa-circle"></i>
                                                    <h5>REDONDO</h5>
                                                    <h5 class="mt-1"><strong>---</strong></h5>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column mt-4">
                                                <p>NO DISPONIBLE</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-3 p-2 bg-white border rounded mt-2">
                                    <div class="col-md-3 mt-1 text-center content_card_result_center">
                                        <div>
                                            <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/lujo.png">
                                            <br><br>
                                            <h5 style="text-transform: uppercase;">SERVICIO <span style="color: #E1423B;">LUJO</span></h5>
                                        </div>
                                    </div>
                                    <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                                        <p><small>El servicio privado es por van, NO por pasajero. Servicio disponible 24/7</small></p>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Precio por Vehículo</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Incluye todos los impuestos y tasas aeroportuarias.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Seguro de viajero.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Recepción por uno de nuestros representantes.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> 24 Horas de servicio.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Servicio puerta a puerta en Cancun & Riviera Maya.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Vehículo privado – NO COMPARTIDO.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Vehículo espacioso para los pasajeros y el equipaje.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Un asiento de seguridad para niños incluido - Cuando lo solicite.</span></div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 border-left mt-1 content_prices_card_resilt">
                                        <div class="w-100 text-center">
                                            <div class=" text-center align-items-center">
                                                <small><strong>Riu Dunamar</strong></small><br>
                                                <small>Sencillo - Aeropuerto a Hotel</small><br>
                                                <small>Pasajeros: 2</small><br>
                                                <h4 class="mt-1"><strong>$1900 MXN</strong></h4>
                                            </div>
                                            <div class="d-flex flex-column mt-4">
                                                <button type="submit" class="btn_animation_2 btn btn-block btn-yamevi"  ><span>Reservar </span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-3 p-2 bg-white border rounded mt-2">
                                    <div class="col-md-3 mt-1 text-center content_card_result_center">
                                        <div>
                                            <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/priv_com.png">
                                            
                                            <br><br>
                                            <h5 style="text-transform: uppercase;">SERVICIO <span style="color: #E1423B;">COMPARTIDO</span></h5>
                                        </div>
                                    </div>
                                    <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Incluye todos los impuestos y tasas aeroportuarias.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Seguro de viajero.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Recepción por uno de nuestros representantes.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Servicio a la mayoría de los hoteles.</span></div>
                                        <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> El servicio compartido sale de forma continua desde el aeropuerto. </span></div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 border-left mt-1 content_prices_card_resilt">
                                        <div class="w-100 text-center">
                                            <div class=" text-center align-items-center">
                                                <small><strong>Riu Dunamar</strong></small><br>
                                                <small>Sencillo - Aeropuerto a Hotel</small><br>
                                                <small>Pasajeros: 2</small><br>
                                                <h4 class="mt-1"><strong>$360 MXN</strong></h4>
                                            </div>
                                            <div class="d-flex flex-column mt-4">
                                                <button class="btn btn-yamevi btn-sm" type="button">Reservar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="content_search">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-6 p-3 content_traslados_1">
                    <div class="card">
                        <div class="card_header text-center">
                            <h4><i class="fas fa-calendar-check pr-4"></i>R e s e r v a c i ó n</h4>
                        </div>
                        <div class="card-body">
                            <form id="form_reserva" > 
                                <input type="hidden" class="" value="<?php echo $_SESSION['todaysale']?>" id="inp_todaysale">
                                <div class="form-group">
                                    <label for="inp_hotel">Hotel</label>
                                    <input list="encodings" value="" id="inp_hotel" name="inp_hotel" placeholder="Ingresa el hotel" class="form-control form-control-sm ">
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
                                <div class="form-group">
                                    <label for="inp_pasajeros">Pasajeros</label>
                                    <select class="custom-select custom-select-sm" id="inp_pasajeros" name="inp_pasajeros" placeholder="Seleccione núm. de pasajeros">
                                        <option value="">Seleccione núm. de pasajeros</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inp_traslado">Traslado</label>
                                    <select class="custom-select custom-select-sm " id="inp_traslado" name="inp_traslado">
                                        <option value="">Seleccione tipo de traslado</option>
                                        <option value="RED">Redondo</option>
                                        <option value="SEN/AH">Aeropuerto - Hotel</option>
                                        <option value="SEN/HA">Hotel - Aeropuerto</option>
                                        <option value="REDHH">Redondo / Hotel - Hotel</option>
                                        <option value="SEN/HH">Sencillo / Hotel - Hotel</option>
                                    </select>
                                </div>
                                <div class="form-group" id="content_date_star">
                                    <label id="label_date_star" for="datepicker_star">Llegada</label>
                                    <div class="input-group">
                                        <input type="text" id="datepicker_star" name="datepicker_star" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                                        <div class="input-group-append mr-2">
                                            <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group pb-2" id="content_date_end">
                                    <label for="datepicker_end">Salida</label>
                                    <div class="input-group">
                                        <input type="text" id="datepicker_end" name="datepicker_end" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                                        <div class="input-group-append mr-2">
                                            <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn_animation btn btn-block btn-yamevi_2"  id="btn_search_reserva"><span>B u s c a r </span></button>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 col-sm-6 p-3 content_traslados_2" >
                    <ul class="fast_access" data-animation="to-top">
                        <li>
                            <a href="users.php" title=" Ver Usuarios">
                            <span><p>Usuarios</p><h5>7</h5></span>
                            <span>
                            <i class="fas fa-user"  aria-hidden="true"></i>
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="reservations.php" title="Ver Reservaciones">
                            <span><p>Reservaciones</p><h5>20</h5></span>
                            <span>
                                <i class="fas fa-calendar-check" aria-hidden="true"></i>    
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="conciliations.php" title="Ver Pendientes de conciliar">
                            <span><p>Pte. Conciliar</p><h5>8</h5></span>
                            <span>
                                <i class="fas fa-gavel" aria-hidden="true"></i>      
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="conciliations.php" title="Ver Conciliados">
                            <span><p>Conciliados</p><h5>12</h5></span>
                            <span>
                                <i class="fas fa-handshake" aria-hidden="true"></i>    
                            </span>
                            </a>
                        </li>
                    </ul>
                    <br>
                    <div class="content_btn_cuentas">
                        <div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-yamevi" title="Ver cuentas" data-toggle="modal" data-target="#exampleModal">
                            Cuentas para pagos
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <!-- Modal -->
        <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cuentas para pagos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <p>Cuentas en pesos MXN</p>
                                <h5>Banco Santender</h5>
                                <small>Cuenta: 65506503248</small><br>
                                <small>SANTANDER SUC 0268 AV TULUM NO. 173
                                    LOTES 14-15 MZ 3 SMZ 20
                                    VIAJES Y ACTIVIDADES EL COLETO SA DE CV
                                </small><br>
                                <small>RFC: VAC160624716</small>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <p>Transferencia o Pago</p>
                                <h5>OXXO</h5>
                                <small>Tarjeta de credito (Santander)</small><br>
                                <small>4913 2700 0074 0396</small>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <p>Cuenta en dolares USD</p>
                                <h5>Banco Santander</h5>
                                <small>Cuenta: 82500836211</small><br>
                                <small>Clabe: 014691825008362113</small><br>
                                <small>SANTANDER SUC 0268 AV TULUM NO. 173
                                    LOTES 14-15 MZ 3 SMZ 20
                                    VIAJES Y ACTIVIDADES EL COLETO SA DE CV
                                </small><br>
                                <small>RFC: VAC160624716</small>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <p>Cuenta colombia</p>
                                <h5>Banco Davivienda</h5>
                                <small>Cuenta: 476100093733</small><br>
                                <small>CHAPINORTE, BOGOTA – COLOMBIA
                                CALLE 67 No. 12 – 57
                                YAMEVI TRAVEL SAS</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <?php include('../include/footer_agencies.php')?>
    <?php include('../include/scrips_agencies.php')?>
    
</html>