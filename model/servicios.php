<?php
    class Servicio{
        //Search reserva
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            $today = date('Y');
            $new_date = $today.'-01-01';
            if (!empty($search)) {
                $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client 
                INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                INNER JOIN agencies AS A ON R.id_agency = A.id_agency) 
                WHERE (A.name_agency like '$search' OR R.code_invoice LIKE '$search' OR C.name_client like '%$search%') AND (R.date_register_reservation >= '$new_date');";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    //Provider
                    $provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                    $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                    //REP
                    $rep = $this->getRepBySaleId($row['id_reservation'], 'A', $con);
                    $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
                    //Provider salid
                    $provider_salida = $this->getProvideBySaleId($row['id_reservation'], 'D', $con);
                    $newprovider_salida = $provider_salida== null ? 'S/A' : $provider_salida['name_provider'];
                    //REP salida
                    $rep_salida = $this->getRepBySaleId($row['id_reservation'], 'D', $con);
                    $newrep_salida = $rep_salida == null ? 'S/A' : $rep_salida['name_receptionist'];

                    $newidreserva = MD5($row['code_invoice']);

                    $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                   
                    $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';

                    $today = date('Y-m-d');
                    $newidreservation = md5($row['id_reservation']);

                     $json[] = array(
                         'id_reservation' => $row['id_reservation'],
                         'new_id_reservation' => $newidreservation,
                         'code_invoice' => $row['code_invoice'],
                         'name_client' => $row['name_client'],
                         'transfer_destiny' => $row['transfer_destiny'],
                         'type_transfer' => $row['type_transfer'],
                         'type_service' => $row['type_service'], 
                         'date_arrival' => $row['date_arrival'],
                         'time_arrival' => $row['time_arrival'],
                         'date_exit' => $row['date_exit'],
                         'time_exit' => $row['time_exit'],
                         'total_cost' => $row['total_cost'],
                         'type_currency' => $row['type_currency'],
                         'method_payment' => $row['method_payment'],
                         'status_reservation' => $row['status_reservation'],
                         'agency_commision' => $row['agency_commision'],
                         'provider' => $newprovider,
                         'rep' => $newrep,
                         'provider_salida' => $newprovider_salida,
                         'rep_salida' => $newrep_salida,
                         'new_idreserva' => $newidreserva,
                         'new_typeaction' => $newtypeaction,
                         'new_trpeactionrep' => $newtypeactionrep,
                         'today' => $today
                     );  
                }
                
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }
        //Search reserva date
        public function search_date($obj){
            $ins = json_decode($obj);

            include('../config/conexion.php');
            $search = $ins->{'star'};
            if (!empty($search)) {
                $search2 = $ins->{'end'};
                if (!empty($search2)) {
                    $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client 
                    INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation) 
                    WHERE (D.date_arrival >= '$search' AND D.date_arrival <= '$search2') or (D.date_exit >= '$search' AND D.date_exit <= '$search2')  
                    ;";
                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        die('Error de consulta'. mysqli_error($con));
                    }
                    $json = array();
                    while($row = mysqli_fetch_array($result)){
                       //Provider
                       $provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                       $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                       //REP
                       $rep = $this->getRepBySaleId($row['id_reservation'], 'A', $con);
                       $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
                       //Provider salid
                       $provider_salida = $this->getProvideBySaleId($row['id_reservation'], 'D', $con);
                       $newprovider_salida = $provider_salida== null ? 'S/A' : $provider_salida['name_provider'];
                       //REP salida
                       $rep_salida = $this->getRepBySaleId($row['id_reservation'], 'D', $con);
                       $newrep_salida = $rep_salida == null ? 'S/A' : $rep_salida['name_receptionist'];

                       $newidreserva = MD5($row['code_invoice']);

                       $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                      
                       $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';

                        $json[] = array(
                            'id_reservation' => $row['id_reservation'],
                            'code_invoice' => $row['code_invoice'],
                            'name_client' => $row['name_client'],
                            'transfer_destiny' => $row['transfer_destiny'],
                            'type_transfer' => $row['type_transfer'],
                            'type_service' => $row['type_service'], 
                            'date_arrival' => $row['date_arrival'],
                            'time_arrival' => $row['time_arrival'],
                            'date_exit' => $row['date_exit'],
                            'time_exit' => $row['time_exit'],
                            'total_cost' => $row['total_cost'],
                            'type_currency' => $row['type_currency'],
                            'method_payment' => $row['method_payment'],
                            'status_reservation' => $row['status_reservation'],
                            'agency_commision' => $row['agency_commision'],
                            'provider' => $newprovider,
                            'rep' => $newrep,
                            'provider_salida' => $newprovider_salida,
                            'rep_salida' => $newrep_salida,
                            'new_idreserva' => $newidreserva,
                            'new_typeaction' => $newtypeaction,
                            'new_trpeactionrep' => $newtypeactionrep
                        ); 
                    }
                    $jsonString = json_encode($json);
                    echo $jsonString;
                }else{
                    $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client 
                    INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation) 
                    WHERE (D.date_arrival like '$search') or (D.date_exit like '$search')   ;";
                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        die('Error de consulta'. mysqli_error($con));
                    }
                    $json = array();
                    while($row = mysqli_fetch_array($result)){
                       //Provider
                       $provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                       $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                       //REP
                       $rep = $this->getRepBySaleId($row['id_reservation'], 'A', $con);
                       $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
                       //Provider salida
                       $provider_salida = $this->getProvideBySaleId($row['id_reservation'], 'D', $con);
                       $newprovider_salida = $provider_salida == null ? 'S/A' : $provider_salida['name_provider'];
                       //REP salida
                       $rep_salida = $this->getRepBySaleId($row['id_reservation'], 'D', $con);
                       $newrep_salida = $rep_salida == null ? 'S/A' : $rep_salida['name_receptionist'];

                       $newidreserva = MD5($row['code_invoice']);

                       $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                      
                       $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';
                        $json[] = array(
                            'id_reservation' => $row['id_reservation'],
                            'code_invoice' => $row['code_invoice'],
                            'name_client' => $row['name_client'],
                            'transfer_destiny' => $row['transfer_destiny'],
                            'type_service' => $row['type_service'], 
                            'type_transfer' => $row['type_transfer'],
                            'date_arrival' => $row['date_arrival'],
                            'time_arrival' => $row['time_arrival'],
                            'date_exit' => $row['date_exit'],
                            'time_exit' => $row['time_exit'],
                            'total_cost' => $row['total_cost'],
                            'type_currency' => $row['type_currency'],
                            'method_payment' => $row['method_payment'],
                            'status_reservation' => $row['status_reservation'],
                            'agency_commision' => $row['agency_commision'],
                            'provider' => $newprovider,
                            'rep' => $newrep,
                            'provider_salida' => $newprovider_salida,
                            'rep_salida' => $newrep_salida,
                            'new_idreserva' => $newidreserva,
                            'new_typeaction' => $newtypeaction,
                            'new_trpeactionrep' => $newtypeactionrep
                        ); 
                    }
                    $jsonString = json_encode($json);
                    echo $jsonString;
                }
            }
        }
        // Obtener details reservation
        public function load_details($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'data'};
            if ($id) {
                $query = "SELECT * FROM clients as C
                INNER JOIN reservations AS R ON C.id_client = R.id_client
                INNER JOIN reservation_details AS D ON D.id_reservation = R.id_reservation
                WHERE md5(R.id_reservation) = '$id';";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $jsonString = json_encode($row);
                echo $jsonString;
            }
        }
        // Obtener el proveedor
        public function getProvideBySaleId($sale, $service, $con){
            $sql = "SELECT * FROM (providers AS P INNER JOIN sale_providers as S on P.id_provider = S.id_provider) WHERE S.id_reservation = '$sale' AND S.type_service ='$service';";
            
            $resul = mysqli_query($con, $sql);
            $response = null;
            if ($resul) {
                if (mysqli_num_rows($resul) > 0) {
                    while ($row = mysqli_fetch_assoc($resul)) {
                        $response = array('id_provider' => $row['id_provider'], 'name_provider' => $row['name_provider']);
                    }
                }
            }
            return $response;
        }
        // Obtener el REP
        public function getRepBySaleId($sale, $service, $con){
            $sql = "SELECT * FROM receptionists AS R 
            INNER JOIN sales_receptionists AS S ON R.id_receptionist = S.id_receptionist WHERE S.id_reservation = $sale AND type_service = '$service';";
            $resul = mysqli_query($con, $sql);
            $response = null;
            if ($resul) {
                if (mysqli_num_rows($resul) > 0) {
                    while ($row = mysqli_fetch_assoc($resul)) {
                        $response = array('id_receptionist' => $row['id_receptionist'], 'name_receptionist' => $row['name_receptionist']);
                    }
                }
            }
            return $response;
        }
        //iNSERTA O ACTUALIZA EL PROVEEDOR
        public function change_provider($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_reservation = $ins->{'id_reservation'};
            $id_provider = $ins->{'id_provider'};
            $name_provider = $ins->{'name_provider'};
            $note_provider = $ins->{'note_provider'};
            $code_invoice = $ins->{'code_invoice'};
            $type_action = $ins->{'type_action'};
            $type_service = $ins->{'type_service'};
            $inp_user = $ins->{'inp_user'};
            $statusquery = 0;
            date_default_timezone_set('America/Cancun');

            //Obtenemos el tipo de paquete de la tabla reservations_details
            $package_type = $this->getReservationDetails($id_reservation, $con);
            //Obtenemos la zona de servicio
            $zona = 0;
            if ($package_type == 'classic') {
                $zona = $this->getAreaDestinationService($id_reservation, $con);
            }
            //Obtenemos el costo de proveedor y costo de zona
            if ($zona > 0) {
                //En caso de que $area = 0, el servico es hotel a hotel, se debe ingresar el precio de otro modo
                $providerCosts = $this->getCostProvider($id_reservation, $id_provider, $zona, $con);
            }
            //Insert
            if ($type_action == 'insert_provider') {
                $query = "INSERT INTO sale_providers(type_service, id_reservation, id_provider) VALUES('$type_service', $id_reservation, $id_provider);";
                $result = mysqli_query($con, $query);
                if ($zona > 0) {
                    if ($type_service == 'A') {
                        $expenses = $this->insertExpensesFor('DERECHO DE PISO', $providerCosts['cost_floorright'], $name_provider, $ins, $con);
                    }
                        $expenses = $this->insertExpensesFor('COSTO PROVEEDOR', $providerCosts['cost_transfer'],$name_provider, $ins, $con);
                }
                $statusquery = 1;
            }
            //Update
            if ($type_action == 'update_provider') {
                $query = "UPDATE sale_providers SET id_provider = $id_provider WHERE id_reservation = $id_reservation AND type_service = '$type_service';";
                $result = mysqli_query($con, $query);
                if ($zona > 0) {
                    if ($type_service == 'A') {
                        $expenses = $this->insertExpensesFor('DERECHO DE PISO', $providerCosts['cost_floorright'],$name_provider, $ins, $con);
                    }
                        $expenses = $this->insertExpensesFor('COSTO PROVEEDOR', $providerCosts['cost_transfer'],$name_provider, $ins, $con);
                }
                $statusquery = 1;
            }
            
            //Agregar en la tabla Actividad
            $param = array('SETPROVIDER', date('Y-m-d H:i:s'));
            if ($type_action == 'update_provider') {
                $param[0] = 'UPDATEPROVIDER';
            }
            $activity_type = $param[0];
            $change_date = $param[1];
            $query = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('$activity_type', '$name_provider',$inp_user, $id_reservation,'$change_date');";
            $result = mysqli_query($con, $query);

            $id_reservation_md5 = MD5($id_reservation);

            //Agregar a la tabla bitacora
            if ($type_action == 'update_provider') {
                $newid_reservation = $id_reservation_md5;
                $register_date = $param[1];
                $query = "INSERT INTO bitacora(comments, id_user, id_reservation, register_date) VALUES('$note_provider', $inp_user, '$newid_reservation', '$register_date');";
                $result = mysqli_query($con, $query);
                
                $statusquery = 1;
            }
            if ($statusquery != 0) {
                    $statusquery = 0;
                    $message = "Ocurrio un problema al intentar asignar el Proveedor, por favor intente más tarde.";
            }
            $message = "El Proveedor $name_provider ha sido asignado correctamente a la reservación con ID $code_invoice";
            return json_encode(array('code' => $statusquery, 'message' => $message));
           
        }
        // Inserta o Actualiza el REP
        public function change_rep($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_reservation = $ins->{'id_reservation'};
            $id_rep = $ins->{'id_rep'};
            $name_rep = $ins->{'name_rep'};
            $code_invoice = $ins->{'code_invoice'};
            $type_action = $ins->{'type_action'};
            $type_service = $ins->{'type_service'};
            $inp_user = $ins->{'inp_user'};
            $statusquery = 0;
            date_default_timezone_set('America/Cancun');
            //INSERT REP
            if ($type_action == 'insert_rep') {
                $query = "INSERT INTO sales_receptionists(type_service, id_reservation, id_receptionist) VALUES('$type_service', $id_reservation, $id_rep);";
                $result = mysqli_query($con, $query);
                $statusquery = 1;
            }
            //UPDATE REP
            if ($type_action == 'update_rep') {
                $query = "UPDATE sales_receptionists SET id_receptionist = $id_rep WHERE id_reservation = $id_reservation AND type_service = '$type_service';";
                $result = mysqli_query($con, $query);
                $statusquery = 1;
            }
            //Agregar en la tabla Actividad
            $param = array('SETREP', date('Y-m-d H:i:s'));
            if ($type_action == 'update_rep') {
                $param[0] = 'UPDATEREP';
            }
            $activity_type = $param[0];
            $change_date = $param[1];
            $query = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('$activity_type', '$name_rep',$inp_user, $id_reservation,'$change_date');";
            $result = mysqli_query($con, $query);

            $id_reservation_md5 = MD5($id_reservation);

            //Agregar a la tabla bitacora
            if ($type_action == 'update_provider') {
                $newid_reservation = $id_reservation_md5;
                $register_date = $param[1];
                $query = "INSERT INTO bitacora(comments, id_user, id_reservation, register_date) VALUES('$note_provider', $inp_user, '$newid_reservation', '$register_date');";
                $result = mysqli_query($con, $query);
                
                $statusquery = 1;
            }
            if ($statusquery != 0) {
                    $statusquery = 0;
                    $message = 'Ocurrio un problema al intentar asignar el Proveedor, por favor intente más tarde.';
            }
            $message = "El REP $name_rep ha sido asignado correctamente a la reservación con ID $code_invoice";
            return json_encode(array('code' => $statusquery, 'message' => $message));

        }
        //Funcion para traer el tipo de paquete de la reservacion
        public function getReservationDetails($id_reservation, $con){
            $query="SELECT * FROM reservation_details WHERE id_reservation = $id_reservation;";
            $result = mysqli_query($con, $query);
            $package_type = 'classic';
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
			        while ($row = mysqli_fetch_assoc($result)) {
                        $package_type = $row['package_type'];
                    }
                }
            }
            return $package_type;
        }

        //Funcion para traer el id de la zona del servicio
        public function getAreaDestinationService($id_reservation, $con){
            $query="SELECT * FROM (reservations AS R INNER JOIN hotels AS H ON R.transfer_destiny = H.name_hotel
            INNER JOIN rates_agencies AS Z ON H.id_zone = Z.id_zone) WHERE R.id_reservation = $id_reservation;";
            $result = mysqli_query($con, $query);
            $zona = 0;
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $zona = $row['id_zone'];
                    }
                }
            }
            return $zona;
        }

        //Funcion que trae el costo de proveedor y derecho de piso por venta
        public function getCostProvider($id_reservation, $id_provider, $zona, $con){
            //Antes traemos el tipo de servicio y numero de pasajeros
            $serv_pasj = $this->getServicePasj($id_reservation, $con);
            $no_pasj = $serv_pasj['no_pasj'];
            if ($serv_pasj['type_service'] == 'Shared') {
                $no_pasj = 1;
            }
            $type_service = $serv_pasj['type_service'];
            $query = "SELECT * FROM rates_providers WHERE id_zone = $zona AND type_service = '$type_service' AND capacity_number >= $no_pasj AND id_provider = $id_provider ORDER BY capacity_number LIMIT 0,1;  ";
            $result = mysqli_query($con, $query);
            $costs_service = array('cost_transfer' => 0, 'cost_floorright' => 0);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $costs_service['cost_transfer'] = $row['cost_service'];
                        if ($serv_pasj['type_service'] == 'Shared') {
                            // En compartido el costo de operacion es (*) No.pasajeros
                            $costs_service['cost_transfer'] = $costs_service['cost_transfer'] * $serv_pasj['no_pasj'];
                        }
                    }
                }
                //Obtenermos el costo de derecho de piso
                $query_2 = "SELECT cost_floorright FROM providers WHERE id_provider = $id_provider;";
                $result_2 = mysqli_query($con, $query_2);
                if ($result_2) {
                    if (mysqli_num_rows($result_2) > 0) {
                        while ($row = mysqli_fetch_assoc($result_2)) {
                            $costs_service['cost_floorright'] = $row['cost_floorright'];
                        }
                    }
                }
                return $costs_service;
            }
        }

        //Funcion para obtener el numero de pasajeros y tipo de servicio
        public function getServicePasj($id_reservation, $con){
            $query = "SELECT * FROM reservation_details WHERE id_reservation = $id_reservation;";
            $result = mysqli_query($con, $query);

            $res = array('no_pasj' => 0, 'type_service' => null);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $res['no_pasj'] = $row['number_adults'];
                        $res['type_service'] = $row['type_service'];
                    }
                }
                //Canviar texto de tipo de servicio
                if ($res['type_service'] != null) {
                    switch ($res['type_service']) {
                        case 'compartido':
                            $res['type_service'] = 'Shared';
                            break;
                        
                        case 'privado':
                            $res['type_service'] = 'Private';
                            break;
                                
                        case 'lujo':
                            $res['type_service'] = 'Luxury';
                            break;
                    }
                }
            }
            return $res;
        }

        public function insertExpensesFor($concepto_cost, $cost_amount,$name_provider, $obj, $con){
            $today = date('Y-m-d H:i:s');
            $type_currency = "MXN";
            $id_reservation = $obj->{'id_reservation'};
            $id_user = $obj->{'inp_user'};
            $type_service = $obj->{'type_service'};
            $query = "INSERT INTO expenses(expense_amount, type_currency,concept,id_reservation, id_user, name_provider, date_expense, type_expense_service) VALUES($cost_amount, '$type_currency','$concepto_cost',$id_reservation,$id_user,'$name_provider','$today','$type_service');";
            
            mysqli_query($con, $query);
        }

        public function addMessage($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d H:i:s');

            $statusquery = 0;
            $id_reservation = $ins->{'id_reservation'};
            $comment = $ins->{'comment'};
            $id_user = $ins->{'id_user'};
            $query = "INSERT INTO bitacora(comments, id_user, id_reservation, register_date) VALUES('$comment', $id_user, '$id_reservation', '$today');";
            $result = mysqli_query($con, $query);
            if ($result) {
                $statusquery = 1;
            }

            return $statusquery;
            
        }
    }
?>