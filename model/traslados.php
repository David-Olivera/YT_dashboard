<?php
    class Transfer{
        public function search_traslado($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $hotel = $ins->{'hotel'};
            $pasajeros = $ins->{'pasajeros'};
            $traslados = $ins->{'traslado'};
            $f_llegada = $ins->{'date_star'};
            $f_salida = $ins->{'date_end'};
            $rate_service_shared = '';
            $rate_service_private = '';
            $rate_service_luxury = '';

            //Validamos si es Inter Hotel
            if ($traslados == 'REDHH' || $traslados == 'SEN/HH') {
                return $this->getServiceListHotelHotel($ins, $con);
                exit;
            }
            //Verificamos existencia de hotel
            if ($this->verifyDestionation($ins->{'hotel'}, $con) == false) {
                return NULL;
                exit;
            }
            //Obetnemos el tipo de moneda
            $moneda = $this->getDivisa('mxn', $con);

            switch ($ins->{'traslado'}) {
                case 'RED':
                    $name_traslado = 'Redondo';
                    break;
        
                case 'SEN/AH':
                    $name_traslado = 'Aeropuerto - Hotel';
                    break;
         
                case 'SEN/HA':
                    $name_traslado = 'Hotel - Aeropuerto';
                    break;
             
                case 'REDHH':
                    $name_traslado = 'Redondo / Hotel - Hotel';
                    break;
            
                case 'SEN/HH':
                    $name_traslado = 'Sencillo / Hotel - Hotel';
                    break;
           }

           //Obtenemos zona de destino
           $zona = json_decode($this->getAreaDestination($ins->{'hotel'}, $con));
            
           //Obtenemos la tarifa de la zona
           $rates = json_decode($this->getRateArea($zona->{'id_zone'}, $con));  

           //Verificacion de tipo de servicio de traslado

           //Compartido
           if (intval($rates[0]->{'shared'}->{'oneway'} > 0 && $rates[0]->{'shared'}->{'oneway'} != NULL )) {
               
               $rates_shared_rt =  "";
               $rates_shared_ow =  "";
               $div_prices_shared = "";
               if ($traslados == 'RED') {
                    $rates_shared_ow = $rates[0]->{'shared'}->{'oneway'};
                    $rates_shared_rt = $rates[0]->{'shared'}->{'roundtrip'};
                    $rate_service_rt = $rates_shared_rt * $pasajeros;
                    $rate_service_ow = $rates_shared_ow * $pasajeros;
                    $div_prices_shared = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1"><strong>$'.round($rate_service_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1" data-rate="'.round($rate_service_rt,2).'"><strong>$'.round($rate_service_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }else{
                    $rates_shared_ow = $rates[0]->{'shared'}->{'oneway'};
                    $rates_shared_rt = $rates[0]->{'shared'}->{'roundtrip'};
                    $rate_service_rt = $rates_shared_rt * $pasajeros;
                    $rate_service_ow = $rates_shared_ow * $pasajeros;
                    $div_prices_shared = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1" data-rate="'.round($rate_service_ow,2).'" ><strong>$'.round($rate_service_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1"><strong>$'.round($rate_service_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }
               $rate_service_shared = '               
                    <div class="row mb-3 mt-3 p-2 bg-white border rounded">
                        <div class="col-md-3 mt-1 text-center content_card_result_center" >
                            <div>
                                <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/priv_com.png">
                                <br><br>
                                <h5 style="text-transform: uppercase;">SERVICIO <span>COMPARTIDO</span></h5>
                            </div>
                        </div>
                        <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                            <p><small>El servicio compartido es por pasajero.</small></p>
                            <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Incluye todos los impuestos y tasas aeroportuarias.</span></div>
                            <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Seguro de viajero.</span></div>
                            <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Recepción por uno de nuestros representantes.</span></div>
                            <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> Servicio a la mayoría de los hoteles.</span></div>
                            <div class="mt-1 mb-1 spec-1"><i class="fas fa-check"></i> <span> El servicio compartido sale de forma continua desde el aeropuerto. </span></div>
                        </div>
                        <div class="col-xl-3 col-md-3 border-left mt-1 ">
                            <div class="w-100 text-center">
                                <div class=" text-center align-items-center">
                                    <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                    <small class="name_traslado">'.$name_traslado.'</small><br>
                                    <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
                                </div>
                                '.$div_prices_shared.'
                                <div class="d-flex flex-column mt-4 mb-3">
                                    <button type="submit" class="btn_animation_2 btn btn-block btn-yamevi"  ><span>Reservar </span></button>
                                </div>
                            </div>
                        </div>
                    </div>
               ';
           }else{
            $rate_service_shared = '               
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
                                    <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                    <small class="name_traslado">'.$name_traslado.'</small><br>
                                    <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
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
                                    <p  class="text_not_available">NO DISPONIBLE</p>
                                </div>
                            </div>
                        </div>
                    </div>              
            ';
           }

           //Privado
           if (intval($rates[0]->{'private'}->{'privado_ow_1'} > 0 && $rates[0]->{'private'}->{'privado_ow_1'} != NULL)) {
               $rates_private_ow = "";
               $rates_private_rt = "";
               $div_prices_private = "";
               if ($pasajeros >=1 && $pasajeros <=4) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_1'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_1'};
               }
               if ($pasajeros >=5 && $pasajeros <=6) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_2'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_2'};
               }
               if ($pasajeros >=7 && $pasajeros <=8) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_3'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_3'};
               }
               if ($pasajeros >=9 && $pasajeros <=10) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_4'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_4'};
               }
               if ($pasajeros >10 && $pasajeros <=11) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_5'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_5'};
               }
               if ($pasajeros >=12 && $pasajeros <=16) {
                    $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_6'} ;
                    $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_6'};
               }
               if ($traslados == 'RED') {
                    $div_prices_private = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1"><strong>$'.round($rates_private_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1" data-rate="'.round($rates_private_rt,2).'"><strong>$'.round($rates_private_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }else{
                    $div_prices_private = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1" data-rate="'.round($rates_private_ow,2).'" ><strong>$'.round($rates_private_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1"><strong>$'.round($rates_private_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }
               $rate_service_private = '               
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
                                    <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                    <small class="name_traslado">'.$name_traslado.'</small><br>
                                    <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
                                </div>
                                '.$div_prices_private.'
                                <div class="d-flex flex-column mt-4">
                                    <button type="submit" class="btn_animation_2 btn btn-block btn-yamevi"  ><span>Reservar </span></button>
                                </div>
                            </div>
                        </div>
                    </div>
               ';
           }else{
                $rate_service_private = '               
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
                                        <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                        <small class="name_traslado">'.$name_traslado.'</small><br>
                                        <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
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
                                            <h5 class="mt-1" ><strong>---</strong></h5>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column mt-4">
                                        <p  class="text_not_available">NO DISPONIBLE</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                ';
           }

           //Lujo
           if (intval($rates[0]->{'luxury'}->{'lujo_ow_1'} > 0 && $rates[0]->{'luxury'}->{'lujo_ow_1'} != NULL)) {
                $rates_luxury_ow = "";
                $rates_luxury_rt = "";
                $div_prices_luxury = "";
                if ($pasajeros >=1 && $pasajeros <=6) {
                    $rates_luxury_ow = $rates[0]->{'luxury'}->{'lujo_ow_1'} ;
                    $rates_luxury_rt = $rates[0]->{'luxury'}->{'lujo_rt_1'};
                }
                if ($traslados == 'RED') {
                    $div_prices_luxury = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1"><strong>$'.round($rates_luxury_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1" data-rate="'.round($rates_luxury_rt,2).'"><strong>$'.round($rates_luxury_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }else{
                    $div_prices_luxury = '
                        <div class="row mt-2 content_prices_results">
                            <div class="col-xl-6 col-md-12">
                                <i class="fas fa-check-square active"></i>
                                <h5>SENCILLO</h5>
                                <h5 class="mt-1" data-rate="'.round($rates_luxury_ow,2).'" ><strong>$'.round($rates_luxury_ow, 2).' MXN</strong></h5>
                            </div>
                            <div class="col-xl-6 col-md-12 ">
                                <i class="fal fa-circle"></i>
                                <h5>REDONDO</h5>
                                <h5 class="mt-1"><strong>$'.round($rates_luxury_rt, 2).' MXN</strong></h5>
                            </div>
                        </div>
                    ';
               }
               $rate_service_luxury = '               
                    <div class="row mb-3 mt-3 p-2 bg-white border rounded">
                        <div class="col-md-3 mt-1 text-center content_card_result_center" >
                            <div>
                                <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/lujo.png">
                                <br><br>
                                <h5 style="text-transform: uppercase;">SERVICIO <span>LUJO</span></h5>
                            </div>
                        </div>
                        <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                            <p><small>El servicio de lujo es por van, NO por pasajero. Servicio disponible 24/7</small></p>
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
                                    <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                    <small class="name_traslado">'.$name_traslado.'</small><br>
                                    <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
                                </div>
                                '.$div_prices_luxury.'
                                <div class="d-flex flex-column mt-4">
                                    <button type="submit" class="btn_animation_2 btn btn-block btn-yamevi"  ><span>Reservar </span></button>
                                </div>
                            </div>
                        </div>
                    </div>
               ';
           }else{
            $rate_service_luxury = '               
                 <div class="row mb-3 mt-3 p-2 bg-white border rounded">
                     <div class="col-md-3 mt-1 text-center content_card_result_center" >
                         <div>
                             <img class="img-fluid img-responsive rounded product-image" src="../../assets/img/traslados/lujo.png">
                             <br><br>
                             <h5 style="text-transform: uppercase;">SERVICIO <span>LUJO</span></h5>
                         </div>
                     </div>
                     <div class="xol-xl-6 col-md-6 mt-1 mb-1 content_rules_card_result">
                         <p><small>El servicio de lujo es por van, NO por pasajero. Servicio disponible 24/7</small></p>
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
                                 <small class="name_hotel"><strong>'.$hotel.'</strong></small><br>
                                 <small class="name_traslado">'.$name_traslado.'</small><br>
                                 <small class="name_pasajeros">Pasajeros: '.$pasajeros.'</small><br>
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
                                <p class="text_not_available">NO DISPONIBLE</p>
                             </div>
                         </div>
                     </div>
                 </div>
            ';

           }

           $lists_services = "";
           $lists_services = $rate_service_private.$rate_service_luxury.$rate_service_shared;
           echo $lists_services;


        }

        function getServiceListHotelHotel($ins, $con){

        }
        function verifyDestionation($hotel, $con){
            $newhotel = mysqli_real_escape_string($con, $hotel);
            $query = "SELECT * FROM hotels AS H INNER JOIN rates_agencies AS R ON H.id_zone = R.id_zone WHERE H.name_hotel = '$newhotel';";
            $result = mysqli_query($con, $query);
            $res = false;
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $res = true;
                }
            }
            return $res;
        }

        function getDivisa($divisa, $con){
            $query = "SELECT amount_change FROM exchange_rate WHERE STATUS = 1 AND divisa = '$divisa' ORDER BY date_modify DESC LIMIT 0,1;";
            $result = mysqli_query($con,$query);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $divisa = $row['amount_change'];
                    }
                }
            }
            return $divisa;
        }
        function getAreaDestination($hotel, $con){
            $newhotel = mysqli_real_escape_string($con, $hotel);
            $query= "SELECT R.id_zone, R.name_zone FROM hotels AS H INNER JOIN rates_public AS R ON H.id_zone = R.id_zone WHERE H.name_hotel = '$newhotel';";
            $result = mysqli_query($con, $query);
            $json = array();
            if ($result) {                
                while ($row = mysqli_fetch_object($result)) {
                   $json = array(
                        'id_zone' => $row->id_zone,
                        'name_zone' => $row->name_zone
                   );
                }
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }
        function getRateArea($id_zone,$con){
            $query= "SELECT * FROM rates_agencies WHERE id_zone = $id_zone;";
            $result = mysqli_query($con, $query);
            $json = array();
            $shared = array();
            $private = array();
            $luxury = array();
            if ($result) {                
                while ($row = mysqli_fetch_object($result)) {
                        $json[]=array(
                            'shared' =>array(
                                'oneway' => $row->compartido_ow, 
                                'roundtrip' => $row->compartido_rt, 
                                'oneway_premium' => $row->compartido_ow_premium, 
                                'roundtrip_premium' => $row->compartido_rt_premium
                            ),
                            'private' =>array(
                                'privado_ow_1' => $row->privado_ow_1,
                                'privado_rt_1' => $row->privado_rt_1,
                                'privado_ow_2' => $row->privado_ow_2,
                                'privado_rt_2' => $row->privado_rt_2,
                                'privado_ow_3' => $row->privado_ow_3,
                                'privado_rt_3' => $row->privado_rt_3,
                                'privado_ow_4' => $row->privado_ow_4,
                                'privado_rt_4' => $row->privado_rt_4,
                                'privado_ow_5' => $row->privado_ow_5,
                                'privado_rt_5' => $row->privado_rt_5,
                                'privado_ow_6' => $row->privado_ow_6,
                                'privado_rt_6' => $row->privado_rt_6
                            ),
                            'luxury' =>array(
                                'lujo_ow_1' => $row->lujo_ow_1 ,
                                'lujo_rt_1' => $row->lujo_rt_1 
                            ),
                        );
                }
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }
    }
?>