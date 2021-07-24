<?php
    class Reservacion{
        //Search reserva
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            $today = date('Y-m-d');
            if (!empty($search)) {
                $query_p = "SET SESSION SQL_BIG_SELECTS = 1";
                $result_p = mysqli_query($con, $query_p);
                $query = "SELECT * FROM clients AS C 
                INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE A.name_agency LIKE '%$search%' OR R.code_invoice LIKE '%$search%' OR (CONCAT_WS(' ', C.name_client, C.last_name, C.mother_lastname) LIKE  '%$search%' OR
                CONCAT_WS(' ', C.last_name, C.mother_lastname, C.name_client) LIKE  '%$search%') ;";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
					$newidreserva = MD5($row['id_reservation']);
                    $json[] = array(
                        'newidreserva' => $newidreserva,
                        'id_reservation' => $row['id_reservation'],
                        'code' => $row['code_invoice'],
                        'name_agency' => $row['name_agency'],
                        'type_service' => $row['type_service'],            
                        'name_client' => $row['name_client'],
                        'last_name' => $row['last_name'],
                        'mother_lastname' => $row['mother_lastname'],
                        'number_adults' => $row['number_adults'],
                        'number_children' => $row['number_children'],
                        'total_cost' => $row['total_cost'],
                        'status_reservation' => $row['status_reservation'],
                        'method_payment' => $row['method_payment'],
                        'date_register_reservation' => $row['date_register_reservation'],
                        'type_transfer' => $row['type_transfer']
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
                    $query = "SELECT * FROM clients AS C 
                    INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                    INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE ((D.date_arrival BETWEEN '$search' AND '$search2') or (D.date_exit BETWEEN '$search' AND '$search2'))  ORDER BY R.date_register_reservation asc;";
                    
                   
                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        die('Error de consulta'. mysqli_error($con));
                    }
                    $json = array();
                    while($row = mysqli_fetch_array($result)){
                        $newidreserva = MD5($row['id_reservation']);
                        $json[] = array(
                        'newidreserva' => $newidreserva,
                        'id_reservation' => $row['id_reservation'],
                        'code' => $row['code_invoice'],
                        'name_agency' => $row['name_agency'],
                        'type_service' => $row['type_service'],            
                        'name_client' => $row['name_client'],
                        'last_name' => $row['last_name'],
                        'mother_lastname' => $row['mother_lastname'],
                        'number_adults' => $row['number_adults'],
                        'number_children' => $row['number_children'],
                        'total_cost' => $row['total_cost'],
                        'status_reservation' => $row['status_reservation'],
                        'method_payment' => $row['method_payment'],
                        'date_register_reservation' => $row['date_register_reservation'],
                        'type_transfer' => $row['type_transfer']
                        ); 
                    }
                    $jsonString = json_encode($json);
                    echo $jsonString;
                }else{
                    
                    $query = "SELECT * FROM clients AS C 
                    INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                    INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE ((D.date_arrival BETWEEN '$search' AND '$search') or (D.date_exit BETWEEN '$search' AND '$search')) ORDER BY R.date_register_reservation asc;";
                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        die('Error de consulta'. mysqli_error($con));
                    }
                    $json = array();
                    while($row = mysqli_fetch_array($result)){
                        $json[] = array(
                            'id_reservation' => $row['id_reservation'],
                            'code' => $row['code_invoice'],
                            'name_agency' => $row['name_agency'],
                            'type_service' => $row['type_service'],            
                            'name_client' => $row['name_client'],
                            'number_adults' => $row['number_adults'],
                            'number_children' => $row['number_children'],
                            'total_cost' => $row['total_cost'],
                            'status_reservation' => $row['status_reservation'],
                            'method_payment' => $row['method_payment'],
                            'date_register_reservation' => $row['date_register_reservation'],
                            'type_transfer' => $row['type_transfer']
                        ); 
                    }
                    $jsonString = json_encode($json);
                    echo $jsonString;
                }
            }
        }

        //Change methodpayment
        public function setmethodpay($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $payment = mysqli_real_escape_string($con,$arg->{'value'});
            $id = mysqli_real_escape_string($con,$arg->{'id'});
            $text = mysqli_real_escape_string($con,$arg->{'text'});
            $code = $arg->{'code'};
            $sql = "SELECT * FROM reservations WHERE id_reservation = $id;";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE reservation_details SET method_payment = '$payment' WHERE id_reservation = $id;";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a guardado correctamente a $text como metodo de pago en la reserva $code";
                    }
                }else{
                    $sql2 = "INSERT INTO reservation_details(id_reservation, method_payment) VALUES($id ,$payment);";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a guardado correctamente a $text como metodo de pago en la reserva $code";
                    }
                }
            }

            return json_encode(array('message' => $message));
        }

        //Change status reservation
        public function setstatusres($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $statusr = $arg->{'value'};
            $id = mysqli_real_escape_string($con,$arg->{'id'});
            $new_id = md5($id);
            $text = mysqli_real_escape_string($con,$arg->{'text'});
            $code = mysqli_real_escape_string($con,$arg->{'code'});
            $user = mysqli_real_escape_string($con,$arg->{'user'});
            $transfer = $arg->{'transfer'};
            $message = "";
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d H:i:s');
            $sql = "SELECT * FROM reservations AS R INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation WHERE R.id_reservation = $id;";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $ins = mysqli_fetch_object($result);
                    if ($statusr == 'CANCELLED') {
                        if ($transfer == 'REDHH' || $transfer == 'RED') {
                            if ($text == 'partial') {
                                $new_typetransfer = "";
                                $new_details_reservation = "";
                                $new_total_cost = "";
                                $new_total_cost_neto = "";
                                $new_commision = "0";
                                $column_cost = "";
                                $ty_cs = $arg->{'ty_cs'};
                                if ($ty_cs == 'c_arrival') {
                                    $message = "La Llegada de la reservación $code a sido cancelada, El servicio a sido actualizado a Traslado Sencillo Hotel - Aeropuerto";
                                    $new_typetransfer = "SEN/HA";
                                    $new_details_reservation = "date_arrival = '', time_arrival = '' ";
                                    //$message = "La Reservación fue CANCELLED la Entrada  $id - $text - $code - $user - $transfer - $ty_cs";
                                }
                                if ($ty_cs == 'c_exit') {
                                    $message = "La Salida de la reservación $code a sido cancelada, El servicio a sido actualizado a Traslado Sencillo Aeropuerto - Hotel";
                                    $new_typetransfer = "SEN/AH";
                                    $new_details_reservation = "date_exit = '', time_exit = '' ";
                                    //$message = "La Reservación fue CANCELLED la Salida  $id - $text - $code - $user - $transfer - $ty_cs";
                                }
                                $query_status = "UPDATE reservations SET status_reservation = 'RESERVED' WHERE id_reservation = $id;";
                                $result_status = mysqli_query($con, $query_status);
                                
                                $query_bitacora = "INSERT INTO bitacora(comments,id_user,id_reservation,register_date)VALUES('$message',$user,'$new_id','$today');";
                                $result_bitacora = mysqli_query($con, $query_bitacora);
    
                                $query_actividad = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date)VALUES('STATE', '$statusr', $user,$id, '$today');";
                                $result_actividad = mysqli_query($con, $query_actividad);
                                
                                if ($ins->method_payment == 'card' || $ins->method_payment == 'paypal') {
                                    $new_total_cost = $ins->total_cost_commision / 2;
                                    $new_commision = $ins->agency_commision / 2;
                                    $new_total_cost_neto = $ins->total_cost / 2;
                                }else{
                                    $new_total_cost = $ins->total_cost_commision / 2;
                                    $new_total_cost_neto = $ins->total_cost / 2;
                                }
                                
                                $query_reservation = "UPDATE reservations SET type_transfer = '$new_typetransfer' WHERE id_reservation = $id;";
                                $result_reservation = mysqli_query($con, $query_reservation);
    
                                $query_cost_total = "UPDATE reservation_details SET $new_details_reservation , agency_commision = '$new_commision', total_cost_commision = '$new_total_cost', total_cost = '$new_total_cost_neto' WHERE id_reservation = $id;";
                                $result_cost_total = mysqli_query($con, $query_cost_total);
    
                                if ($result_status && $result_bitacora && $result_actividad && $result_reservation && $result_cost_total) {
                                    //COMPROBAMOS SI YA ESTA CONCILIADO
                                    $id_agency_elec = "";
                                    $amount_electronic = "";
                                    if ($ins->of_the_agency && $ins->id_agency != $ins->of_the_agency) {
                                        $id_agency_elec = $ins->of_the_agency;
                                    }else{
                                        $id_agency_elec = $ins->id_agency;
                                    }
                                    $query_com_ope = "SELECT * FROM conciliation WHERE id_reservation = $id;";
                                    $reseult_com_ope = mysqli_query($con, $query_com_ope);
                                    $row_com_ope = mysqli_fetch_assoc($reseult_com_ope);
                                    if ($row_com_ope['status'] == 1) {
                                        date_default_timezone_set('America/Cancun');
                                        $today = date('Y-m-d H:i:s');
                                        if ($ins->method_payment == 'card' || $ins->method_payment == 'paypal') {
                                            $price_total_commision = $ins->total_cost_commision + $ins->agency_commision;
                                            $cargo_total_commision = $price_total_commision * 0.05;
                                            $amount_electronic = round(($price_total_commision - $cargo_total_commision) / 2);
                                        }else{
                                            $amount_electronic = round($ins->total_cost / 2);
                                        }
                                        //ASIGNAMOS EL MONEDERO ELECTRONICO
                                        $sql_electronic_purse ="";
                                        $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id;";
                                        $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                                        if ($result_electronic_purse) {
                                            if (mysqli_num_rows($result_electronic_purse) > 0) {
                                                $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $id_agency_elec, id_reservation = $id, id_user = $user, descripcion_electronic = 'CANCELACIÓN PARCIAL DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                            }else{
                                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $id, $user, 'CANCELACIÓN PARCIAL DE RESERVA', $amount_electronic, '$today');";
                                            }
                                        }else{
                                            $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $ud, $user, 'CANCELACIÓN PARCIAL DE RESERVA', $amount_electronic, '$today');";
                                        }
                                        $result_ep = mysqli_query($con, $sql_electronic_purse);
                                    }
                                    return $message;
                                    
                                }else{
                                    return $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                                }
                            }
                            if ($text == 'full') {
                                $sql2 = "UPDATE reservations SET status_reservation = '$statusr' WHERE id_reservation = $id;";
                                $result2 = mysqli_query($con, $sql2);        
                                if ($result2) {
                                    $query_ac = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('STATE', '$statusr', $user, $id, '$today');";
                                    $result_ac = mysqli_query($con, $query_ac);
                                    if ($result_ac) {
                                        //COMPROBAMOS SI YA ESTA CONCILIADO
                                        $id_agency_elec = "";
                                        $amount_electronic = "";
                                        if ($ins->of_the_agency && $ins->id_agency != $ins->of_the_agency) {
                                            $id_agency_elec = $ins->of_the_agency;
                                        }else{
                                            $id_agency_elec = $ins->id_agency;
                                        }
                                        $query_com_ope = "SELECT * FROM conciliation WHERE id_reservation = $id;";
                                        $reseult_com_ope = mysqli_query($con, $query_com_ope);
                                        $row_com_ope = mysqli_fetch_assoc($reseult_com_ope);
                                        if ($row_com_ope['status'] == 1) {
                                            date_default_timezone_set('America/Cancun');
                                            $today = date('Y-m-d H:i:s');
                                            if ($ins->method_payment == 'card' || $ins->method_payment == 'paypal') {
                                                $price_total_commision = $ins->total_cost_commision + $ins->agency_commision;
                                                $cargo_total_commision = $price_total_commision * 0.05;
                                                $amount_electronic = round($price_total_commision - $cargo_total_commision);
                                            }else{
                                                $amount_electronic = round($ins->total_cost);
                                            }
                                            //ASIGNAMOS EL MONEDERO ELECTRONICO
                                            $sql_electronic_purse ="";
                                            $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id;";
                                            $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                                            if ($result_electronic_purse) {
                                                if (mysqli_num_rows($result_electronic_purse) > 0) {
                                                    $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $id_agency_elec, id_reservation = $id, id_user = $user, descripcion_electronic = 'CANCELACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                                }else{
                                                    $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $id, $user, 'CANCELACIÓN DE RESERVA', $amount_electronic, '$today');";
                                                }
                                            }else{
                                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $ud, $user, 'CANCELACIÓN DE RESERVA', $amount_electronic, '$today');";
                                            }
                                            $result_ep = mysqli_query($con, $sql_electronic_purse);
                                        }
                                        return $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $statusr.";
                                    }else{
                                        return $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                                    }
                                }else{
                                    return $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                                }
                            }
                        }else{
                            $sql2 = "UPDATE reservations SET status_reservation = '$statusr' WHERE id_reservation = $id;";
                            $result2 = mysqli_query($con, $sql2);        
                            if ($result2) {
                                $query_ac = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('STATE', '$statusr', $user, $id, '$today');";
                                $result_ac = mysqli_query($con, $query_ac);
                                if ($result_ac) {
                                    //COMPROBAMOS SI YA ESTA CONCILIADO
                                    $id_agency_elec = "";
                                    $amount_electronic = "";
                                    if ($ins->of_the_agency && $ins->id_agency != $ins->of_the_agency) {
                                        $id_agency_elec = $ins->of_the_agency;
                                    }else{
                                        $id_agency_elec = $ins->id_agency;
                                    }
                                    $query_com_ope = "SELECT * FROM conciliation WHERE id_reservation = $id;";
                                    $reseult_com_ope = mysqli_query($con, $query_com_ope);
                                    $row_com_ope = mysqli_fetch_assoc($reseult_com_ope);
                                    if ($row_com_ope['status'] == 1) {
                                        date_default_timezone_set('America/Cancun');
                                        $today = date('Y-m-d H:i:s');
                                        if ($ins->method_payment == 'card' || $ins->method_payment == 'paypal') {
                                            $price_total_commision = $ins->total_cost_commision + $ins->agency_commision;
                                            $cargo_total_commision = $price_total_commision * 0.05;
                                            $amount_electronic = round($price_total_commision - $cargo_total_commision);
                                        }else{
                                            $amount_electronic = round($ins->total_cost);
                                        }
                                        //ASIGNAMOS EL MONEDERO ELECTRONICO
                                        $sql_electronic_purse ="";
                                        $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id;";
                                        $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                                        if ($result_electronic_purse) {
                                            if (mysqli_num_rows($result_electronic_purse) > 0) {
                                                $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $id_agency_elec, id_reservation = $id, id_user = $user, descripcion_electronic = 'CANCELACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                            }else{
                                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $id, $user, 'CANCELACIÓN DE RESERVA', $amount_electronic, '$today');";
                                            }
                                        }else{
                                            $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($id_agency_elec, $ud, $user, 'CANCELACIÓN DE RESERVA', $amount_electronic, '$today');";
                                        }
                                        $result_ep = mysqli_query($con, $sql_electronic_purse);
                                    }
                                    $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $statusr.";
                                }else{
                                    $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                                }
                            }else{
                                $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                            }
                        }    
                    }else{
                        $sql2 = "UPDATE reservations SET status_reservation = '$statusr' WHERE id_reservation = $id;";
                        $result2 = mysqli_query($con, $sql2);        
                        if ($result2) {
                            $query_ac = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('STATE', '$statusr', $user, $id, '$today');";
                            $result_ac = mysqli_query($con, $query_ac);
                            if ($result_ac) {
                                $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $statusr.";
                            }else{
                                $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                            }
                        }else{
                            $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                        }
                    }
                }else{
                    $sql2 = "INSERT INTO reservations(id_reservation, status_reservation) VALUES($id ,$statusr);";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $text.";
                    }
                }
            }

            return $message;
        }

        /* FUNCIONES DE DETALLES DE RESERVACION */
        
        //Get datas reservation
        public function getDetailsReservation($obj){
            include('../../config/conexion.php');
            if ($obj) {
                $query = "SELECT * FROM clients as C
                INNER JOIN reservations AS R ON C.id_client = R.id_client
                INNER JOIN reservation_details AS D ON D.id_reservation = R.id_reservation
                WHERE md5(R.id_reservation) = '$obj';";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $jsonString = json_encode($row);
                mysqli_close($con);

                return $jsonString;
            }
        }

        //Get provider and REP
        public function getProviderAndRepReservation($obj){
            include('../../config/conexion.php');
            if ($obj) {
                $new_name_provider_e = 'S/A';
                $type_service_provider_e = 'Llegada';
                $new_name_rep_e = 'S/A';
                $type_service_rep_e = 'Llegada';
                
                $new_name_provider_s = 'S/A';
                $type_service_provider_s = 'Salida';
                $new_name_rep_s = 'S/A';
                $type_service_rep_s = 'Salida';

                //Provider
                $sql_provider ="SELECT name_provider, type_service FROM providers AS P INNER JOIN sale_providers AS S ON P.id_provider = S.id_provider WHERE md5(S.id_reservation) = '$obj';";
                $result_provider = mysqli_query($con, $sql_provider);
                if (!$result_provider) {
                    die('Error de consulta'. mysqli_error($con));
                }
                while($row = mysqli_fetch_array($result_provider)){
                    if ($row['type_service'] == 'A') {
                        $type_service_provider_e = 'Llegada';
                        $new_name_provider_e = $row['name_provider'];
                    }
                    if ($row['type_service'] == 'D') {
                        $type_service_provider_s = 'Salida';
                        $new_name_provider_s = $row['name_provider'];
                    }
                }

                //REP
                $sql_rep = "SELECT name_receptionist, type_service FROM receptionists AS R INNER JOIN sales_receptionists AS S ON R.id_receptionist = S.id_receptionist WHERE MD5(S.id_reservation) = '$obj';";
                $result_rep = mysqli_query($con, $sql_rep);
                if (!$result_rep) {
                    die('Error de consulta'. mysqli_error($con));
                }
                while($row = mysqli_fetch_array($result_rep)){
                    if ($row['type_service'] == 'A') {
                        $type_service_rep_e = 'Llegada';
                        $new_name_rep_e = $row['name_receptionist'];
                    }
                    if ($row['type_service'] == 'D') {
                        $type_service_rep_s = 'Salida';
                        $new_name_rep_s = $row['name_receptionist'];
                    }
                }

                $response = array('name_provider_e' => $new_name_provider_e, 'type_service_provider_e' => $type_service_provider_e, 'name_rep_e' => $new_name_rep_e, 'type_service_rep_e' => $type_service_rep_e,'name_provider_s' => $new_name_provider_s, 'type_service_provider_s' => $type_service_provider_s, 'name_rep_s' => $new_name_rep_s, 'type_service_rep_s' => $type_service_rep_s);
                mysqli_close($con);
                
                return json_encode($response);
            }
        }

        //Get expenses of reservation
        public function getExpensesReservation($obj){
            include('../../config/conexion.php');
            if ($obj) {
                $query_expense = "SELECT * FROM expenses AS E 
                INNER JOIN users AS U ON E.id_user = U.id_user 
                WHERE MD5(E.id_reservation) = '$obj' AND E.charge_type = 'G' ORDER BY E.id_expenses desc;";
                $result_expense = mysqli_query($con, $query_expense);
                if (!$result_expense) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result_expense)){
                    $json[] = array(
                        'id_expenses' => $row['id_expenses'],
                        'username' => $row['username'],
                        'concept' => $row['concept'],
                        'date_expense' => $row['date_expense'],
                        'name_provider' => $row['name_provider'],
                        'expense_amount' => $row['expense_amount'],
                        'type_currency' => $row['type_currency']
                    );
                }
                mysqli_close($con);
                $jsonString = json_encode($json);
                return $jsonString;
            }

        }

        //Get deposists of reservation
        public function getDepositsReservation($obj){
            include('../../config/conexion.php');
            if ($obj) {
                $query_deposit = "SELECT * FROM expenses AS E 
                INNER JOIN users AS U ON E.id_user = U.id_user 
                WHERE md5(E.id_reservation) = '$obj' AND E.charge_type = 'D';";
                $result_deposit = mysqli_query($con, $query_deposit);
                if (!$result_deposit) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result_deposit)){
                    $json[] = array(
                        'id_expenses' => $row['id_expenses'],
                        'username' => $row['username'],
                        'concept' => $row['concept'],
                        'date_expense' => $row['date_expense'],
                        'expense_amount' => $row['expense_amount'],
                        'type_currency' => $row['type_currency']
                    );
                }
                mysqli_close($con);
                $jsonString = json_encode($json);
                return $jsonString;
            }
        }

        //Get activity  of reservation
        public function getActivitiesReservation($obj){
            include('../../config/conexion.php');
            if ($obj) {
                $query_activity = "SELECT * FROM activities AS A INNER JOIN users AS U ON A.id_user = U.id_user 
                WHERE MD5(A.id_reservation) = '$obj' ORDER BY A.change_date DESC;";
                $result_activity = mysqli_query($con, $query_activity);
                if (!$result_activity) {
                    die('Error de consulta'. mysqli_error($con));
                }                
                $json = array();
                while ($row = mysqli_fetch_array($result_activity)) {
                    $json[] = array(
                        'activity_status' => $row['activity_status'],
                        'activity_type' => $row['activity_type'],
                        'username' => $row['username'],
                        'change_date' => $row['change_date']
                    );
                }
                mysqli_close($con);
                $jsonString = json_encode($json);
                return $jsonString;
            }
        }

        public function saveExpenseForSale($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d H:i:s');
            $taxipayment = '';
            $service = '';
            $charge = $ins->{'charge'};
            $currency = $ins->{'currency'};
            $concept = $ins->{'concept'};
            $check_taxipay = $ins->{'check_taxipay'};
            $taxipayment = $ins->{'taxipayment'};
            $check_paleteo = $ins->{'check_paleteo'};
            $id_reservation = $ins->{'id_reservation'};
            $id_user = $ins->{'id_user'};
            $new_concept = "";
            $STATUS = 0;
            if ($ins->{'check_taxipay'} == 1) {
                $taxipayment =  1;
            }else{
                $taxipayment = 0;
            }
            if ($ins->{'taxipayment'} == 1) {
                $service = 'D';
                $new_concept = $concept.' - YameviTravel';
            }else{
                $service = 'A';
                $new_concept = $concept.' - Proveedor';
            }
            $query ="INSERT INTO expenses(expense_amount, type_currency, concept, id_reservation, id_user, date_expense, type_expense_service, taxi_payment)VALUES($charge, '$currency', '$new_concept',$id_reservation,$id_user,'$today','$service', '$taxipayment');";
            $result = mysqli_query($con, $query);
            if ($result) {
                $STATUS = 1;
            }
            return $STATUS;
        }
        // //Get messages of reservation
        // public function getMessagesReservation($obj){
        //     include('../../config/conexion.php');
        //     if ($obj) {
        //         $query_messages = "SELECT * FROM bitacora AS B INNER JOIN users AS U ON B.id_user = U.id_user WHERE B.id_reservation = '$obj' ORDER BY B.register_date DESC;";
        //         $result_message = mysqli_query($con, $query_messages);
        //         if (!$result_message) {
        //             die('Error de consulta'. mysqli_error($con));
        //         }
        //         $json = array();
        //         while ($row = mysqli_fetch_array($result_message)) {
        //             $json[]=array(
        //                 'id_bitacora' => $row['id_bitacora'],
        //                 'comments' => $row['comments'],
        //                 'id_user' => $row['id_user'],
        //                 'id_reservation' => $row['id_reservation'],
        //                 'register_date' => $row['register_date']
        //             );
        //         }
        //         mysqli_close($con);
        //         $jsonString = json_encode($json);
        //         return $jsonString;
        //     }
        // }
        
        //Get datas reservation
        public function getDetailsReservationEdit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $newid= $ins->id;
            if ($obj) {
                $query = "SELECT * FROM clients as C
                INNER JOIN reservations AS R ON C.id_client = R.id_client
                INNER JOIN reservation_details AS D ON D.id_reservation = R.id_reservation
                WHERE R.id_reservation = $newid;";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json=array();
                while($row=mysqli_fetch_array($result)){
                    
                    // PICKUP INTERHOTEL
                    $arrivalTimeArgs = explode(' ', $row['time_arrival']);
                    $arrivalTimeArgs = explode(':', $arrivalTimeArgs[0]);
    
                    $arrivalTimeArgs_ex = explode(' ', $row['time_exit']);
                    $arrivalTimeArgs_ex = explode(':', $arrivalTimeArgs_ex[0]);
    
 
                    $json[]= array(
                        'code_invoice' => $row['code_invoice'],
                        'type_currency' => $row['type_currency'],
                        'method_payment' => $row['method_payment'],
                        'code_client' => $row['code_client'],
                        'name_advisor' => $row['name_advisor'],
                        'id_agency' => $row['id_agency'],
                        'of_the_agency' => $row['of_the_agency'],
                        'transfer_destiny' => $row['transfer_destiny'],
                        'destiny_interhotel' => $row['destiny_interhotel'],
                        'type_transfer' => $row['type_transfer'],
                        'type_service' => $row['type_service'],
                        'number_adults' => $row['number_adults'],
                        'airline_in' => $row['airline_in'],
                        'no_fly' => $row['no_fly'],
                        'airline_out' => $row['airline_out'],
                        'no_flyout' => $row['no_flyout'],
                        'date_arrival' => $row['date_arrival'],
                        'date_exit' => $row['date_exit'],
                        'time_hour_arrival' => $arrivalTimeArgs[0],
                        'time_min_arrival' =>$arrivalTimeArgs[1],
                        'time_hour_exit' =>$arrivalTimeArgs_ex[0],
                        'time_min_exit' => $arrivalTimeArgs_ex[1],
                        'time_service' => $row['time_service'],
                        'date_register_reservation' => $row['date_register_reservation'],
                        'status_reservation' => $row['status_reservation'],
                        'agency_commision' => $row['agency_commision'],
                        'total_cost_commision' => $row['total_cost_commision'],
                        'total_cost' => $row['total_cost'],
                        'name_client' => $row['name_client'],
                        'last_name' => $row['last_name'],
                        'mother_lastname' => $row['mother_lastname'],
                        'phone_client' => $row['phone_client'],
                        'email_client' => $row['email_client'],
                        'country_client' => $row['country_client'],
                        'comments_client' => $row['comments_client']
                    );
                }
                $jsonStrig = json_encode($json[0]);
                echo $jsonStrig; 
            }
        }
        public function update_traslado($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            session_start();
            $id_user = $_SESSION['id_user'];
            $id_reservation = $ins->{'id_reservation'};
            $code_invoice = $ins->{'code_invoice'};
            $code_client= $ins->{'code_client'}; 
            $name_asesor = $ins->{'name_asesor'};  
            $of_the_agency = $ins->{'of_the_agency'};
            $name_hotel = mysqli_real_escape_string($con,$ins->{'name_hotel'});
            $name_hotel_interhotel = mysqli_real_escape_string($con,$ins->{'name_hotel_interhotel'});
            $type_traslado = $ins->{'type_traslado'};
            $type_service = $ins->{'type_service'};
            $num_pasajeros = $ins->{'num_pasajeros'};
            $date_arrival = $ins->{'date_arrival'};
            $airline_arrival = $ins->{'airline_arrival'};
            $no_fly_arrival = $ins->{'no_fly_arrival'};
            $time_entry = mysqli_real_escape_string($con,$ins->{'time'});
            $time_exit = mysqli_real_escape_string($con,$ins->{'time_exit'});
            $time_hour_arrival = $ins->{'time_hour_arrival'};
            $time_minute_arrival = $ins->{'time_minute_arrival'};
            $date_exit = $ins->{'date_exit'};
            $airline_exit =  $ins->{'airline_exit'};
            $time_service = $ins->{'time_service'};
            $no_fly_exit = $ins->{'no_fly_exit'};
            $time_hour_exit =  $ins->{'time_hour_exit'};
            $time_minute_exit = $ins->{'time_minute_exit'}; 
            $time_pickup = $ins->{'time_pickup'};
            $time_pickup_inter = $ins->{'time_pickup_inter'};
            $method_payment = $ins->{'method_payment'};
            $sub_total = $ins->{'sub_total'};
            $commission = $ins->{'commission'};
            $total_cost_comision = $ins->{'total_cost_comision'};
            $currency = $ins->{'currency'};
            $name_client = mysqli_real_escape_string($con,$ins->{'name_client'});
            $last_name = mysqli_real_escape_string($con,$ins->{'last_name'});
            $mother_lastname = mysqli_real_escape_string($con,$ins->{'mother_lastname'});
            $email_client = mysqli_real_escape_string($con,$ins->{'email_client'}); 
            $phone_client = mysqli_real_escape_string($con,$ins->{'phone_client'});
            $special_request = mysqli_real_escape_string($con,$ins->{'special_request'});
            // Con comision
            $newvalue = "";
            // Sin comision neta
            $new_cost ="";
            $error = 0;
            $pickup = "";
            $before_pickup = "";
            //Validamos si es Inter Hotel
            if ($type_traslado == 'REDHH' || $type_traslado == 'SEN/HH') {
                return $this->getServiceListHotelHotel($ins, $con);
                exit;
            }
            //Verificamos existencia de hotel
            if ($this->verifyDestionation($name_hotel, $con) == false) {
                return NULL;
                exit;
            }
            if ($type_traslado == 'SEN/HA' || $type_traslado == 'RED') {
                $pickup = $ins->{'pickup'};
                $before_pickup = $ins->{'before_pickup'};
            }
            //Obetnemos el tipo de moneda
            $moneda = $this->getDivisa('mxn', $con);
            //Verificacion de tipo de servicio de traslado
            switch ($ins->{'type_traslado'}) {
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
            $new_time_en = "";
            $new_time_ex = "";
            $new_date_en = "";
            $new_date_ex = "";
            if ($type_traslado == 'SEN/HA' ) {
                $new_time_en = $time_entry;
                $new_time_ex = $time_exit;
                $new_date_ex = $date_exit;
                $new_date_en = "";

            }
            if ($type_traslado == 'RED') {
                $new_date_en = $date_arrival;
                $new_date_ex = $date_exit;
                $new_time_en = $time_entry;
                $new_time_ex = $time_exit;

            }
            if ($type_traslado == 'SEN/AH' ) {
                $new_time_en = $time_entry;
                $new_time_ex = $time_exit;
                $new_date_ex = "";
                $new_date_en = $date_arrival;
            }
            if ($type_traslado == 'SEN/HH' ) {
                $new_time_en = $time_pickup;
                $new_time_ex = $time_pickup_inter;
                $new_date_ex = "";
                $new_date_en = $date_arrival;
            }
            if ($type_traslado == 'REDHH') {
                $new_time_en = $time_pickup;
                $new_time_ex = $time_pickup_inter;
                $new_date_ex = $date_exit;
                $new_date_en = $date_arrival;

            }
            //Obtenemos zona de destino
            $zona = json_decode($this->getAreaDestination($name_hotel, $con));
            //Obtenemos la tarifa de la zona
            $rates = json_decode($this->getRateArea($zona->{'id_zone'}, $con));  
            $div_price = "";
            //Compartido
            if ($type_service == 'compartido') {
                if(intval($rates[0]->{'shared'}->{'oneway'}) > 0 && $rates[0]->{'shared'}->{'oneway'} != NULL ) {    
                    $rates_shared_rt =  "";
                    $rates_shared_ow =  "";
                    $div_prices_shared = "";
                    if ($type_traslado == 'RED') {
                        $rates_shared_ow = $rates[0]->{'shared'}->{'oneway'};
                        $rates_shared_rt = $rates[0]->{'shared'}->{'roundtrip'};
                        $rate_service_rt = $rates_shared_rt * $num_pasajeros;
                        $rate_service_ow = $rates_shared_ow * $num_pasajeros;
                        if ($currency == 'mx') {
                            $div_price = round($rate_service_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rate_service_rt / $moneda,0);
                        }
                    }else{
                        $rates_shared_ow = $rates[0]->{'shared'}->{'oneway'};
                        $rates_shared_rt = $rates[0]->{'shared'}->{'roundtrip'};
                        $rate_service_rt = $rates_shared_rt * $num_pasajeros;
                        $rate_service_ow = $rates_shared_ow * $num_pasajeros;
                        if ($currency == 'mx') {
                            $div_price = round($rate_service_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rate_service_ow / $moneda,0);
                        }
                    }
                }
            }
            //Privado
            if ($type_service == 'privado') {
                if (intval($rates[0]->{'private'}->{'privado_ow_1'}) > 0 && $rates[0]->{'private'}->{'privado_ow_1'} != NULL) {
                    $rates_private_ow = "";
                    $rates_private_rt = "";
                    $div_prices_private = "";
                    if ($num_pasajeros >=1 && $num_pasajeros <=4) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_1'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_1'};
                    }
                    if ($num_pasajeros >=5 && $num_pasajeros <=6) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_2'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_2'};
                    }
                    if ($num_pasajeros >=7 && $num_pasajeros <=8) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_3'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_3'};
                    }
                    if ($num_pasajeros >=9 && $num_pasajeros <=10) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_4'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_4'};
                    }
                    if ($num_pasajeros >10 && $num_pasajeros <=11) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_5'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_5'};
                    }
                    if ($num_pasajeros >=12 && $num_pasajeros <=16) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_6'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_6'};
                    }
                    if ($type_traslado == 'RED') {
                        if ($currency == 'mx') {
                            $div_price = round($rates_private_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_private_rt / $moneda,0);
                        }
                    }else{
                        if ($currency == 'mx') {
                            $div_price = round($rates_private_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_private_ow / $moneda,0);
                        }
                    }
                }
            }
            //Lujo
            if ($type_service == 'lujo') {
                if (intval($rates[0]->{'luxury'}->{'lujo_ow_1'}) > 0 && $rates[0]->{'luxury'}->{'lujo_ow_1'} != NULL && $num_pasajeros <=6) {
                    $rates_luxury_ow = "";
                    $rates_luxury_rt = "";
                    $div_prices_luxury = "";
                    if ($num_pasajeros >=1 && $num_pasajeros <=6) {
                        $rates_luxury_ow = $rates[0]->{'luxury'}->{'lujo_ow_1'} ;
                        $rates_luxury_rt = $rates[0]->{'luxury'}->{'lujo_rt_1'};
                    }
                    if ($type_traslado == 'RED') {
                        
                        if ($currency == 'mx') {
                            $div_price = round($rates_luxury_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_luxury_rt / $moneda,0);
                        }
                    }else{
                        if ($currency == 'mx') {
                            $div_price = round($rates_luxury_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_luxury_ow / $moneda,0);
                        }
                    }
                }
            }
            if ($method_payment == 'card' || $method_payment == 'paypal') {
                $cargo = 0.95;
                $add_cargo = $div_price / $cargo;
                $sum = $commission + $add_cargo;
                $new_cost = $div_price;
                $newvalue = number_format($sum, 0, '.', '');
            }
            if($method_payment == 'oxxo' || $method_payment == 'airport' || $method_payment == 'deposit' || $method_payment == 'transfer'){            
                $new_cost = number_format($div_price, 0, '.', '');
                $newvalue = number_format($div_price, 0, '.', '');
            }
            if ($method_payment == 'a_pa' || $method_payment == 'a_transfer' || $method_payment == 'a_paypal' || $method_payment == 'a_card') {
                $new_cost = number_format($total_cost_comision, 0, '.', '');
                $newvalue = number_format($total_cost_comision, 0, '.', '');
            }
            $query = "SELECT * FROM reservations AS R INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation
            INNER JOIN clients AS C ON R.id_client = C.id_client WHERE R.id_reservation = $id_reservation;";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $id_client_e = $row['id_client'];
            $comment_bef = $row['comments_client'];
            $json = array();
            if ($newvalue != "" || $new_cost != "") {
                if ($comment_bef != $special_request) {
                    if ($special_request != "") {
                        date_default_timezone_set('America/Cancun');
                        $today = date('Y-m-d H:i:s');
                        $query_comment = "INSERT INTO bitacora(comments,id_user,id_reservation,register_date,status)VALUES('$special_request', $id_user, $id_reservation, '$today',0);";
                        $result_comment = mysqli_query($con,$query_comment);
                    }
                }
                //Insertamos datos Clientes
                $query_client = "UPDATE clients SET code_client= '$code_client', name_advisor = '$name_asesor',name_client = '$name_client',last_name = '$last_name',mother_lastname = '$mother_lastname',email_client = '$email_client',phone_client = '$phone_client',comments_client = '$special_request' WHERE id_client = $id_client_e;";
                $result_client = mysqli_query($con, $query_client);
                //Insertamos datos Reserva
                $query_reserva = "UPDATE reservations SET type_transfer = '$type_traslado', airline_in = '$airline_arrival', no_fly = '$no_fly_arrival', airline_out = '$airline_exit', no_flyout = '$no_fly_exit', transfer_destiny = '$name_hotel',destiny_interhotel = '$name_hotel_interhotel',of_the_agency = $of_the_agency where id_reservation = $id_reservation;";
                $result_reserv = mysqli_query($con, $query_reserva);

                //Insertamos datos Detalles Reserva
                $query_detalles = "UPDATE reservation_details SET date_arrival = '$new_date_en',date_exit = '$new_date_ex', time_arrival = '$new_time_en', time_exit = '$new_time_ex', time_service = '$time_service', number_adults = $num_pasajeros, agency_commision = '$commission', total_cost_commision = $newvalue, total_cost = $new_cost, type_service = '$type_service' , method_payment = '$method_payment' , pickup_entry = '$pickup' WHERE id_reservation = $id_reservation;";
                $result_detalles = mysqli_query($con, $query_detalles);
                //Insertamos datos Actividad de pickup.
                if ($pickup != '' && $pickup != '01:00 Hrs' && $pickup != $before_pickup) {
                    date_default_timezone_set('America/Cancun');
                    $today = date('Y-m-d H:i:s');
                    $query_actividad = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date)VALUES('PICKUP', '$pickup', $id_user ,$id_reservation, '$today');";
                    $result_actividad = mysqli_query($con, $query_actividad);
                }
                $query = "";
                $sql_uconci = "";
                $query_res = "";
                $status_reserva = "";
                //COMPROBAMOS SI YA ESTA CONCILIADO
                $query_com_ope = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                $reseult_com_ope = mysqli_query($con, $query_com_ope);
                $row_com_ope = mysqli_fetch_assoc($reseult_com_ope);
                if ($row_com_ope['status'] == 1) {
                    $status_c = "";
                    if ($method_payment =='card' || $method_payment == 'paypal') {
                        if ($newvalue > $total_cost_comision || $new_cost > $total_cost_comision) {
                            $status_c = 0;
                            $status_reserva = "RESERVED";
                        }
                        if ($newvalue == $total_cost_comision || $new_cost == $total_cost_comision) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                        }
                        if ($newvalue < $total_cost_comision || $new_cost < $total_cost_comision) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                        
                            date_default_timezone_set('America/Cancun');
                            $today = date('Y-m-d H:i:s');
                            $amount_electronic = round($total_cost_comision - $newvalue);
                            $sql_electronic_purse ="";
                            $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id_reservation;";
                            $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                            if ($result_electronic_purse) {
                                if (mysqli_num_rows($result_electronic_purse) > 0) {
                                    $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $of_the_agency, id_reservation = $id_reservation, id_user = $id_user, descripcion_electronic = 'MODIFICACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                }else{
                                    $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                                }
                            }else{
                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                            }
                            $result_ep = mysqli_query($con, $sql_electronic_purse);
                        }
                        $query_conci = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                        $result_conci = mysqli_query($con, $query_conci);
                        if ($result_conci) {
                            $ins_sql = mysqli_fetch_object($result_conci);
                            $sql_uconci = "UPDATE conciliation SET status = $status_c WHERE id_reservation = $id_reservation;";
                            $result_uconci = mysqli_query($con, $sql_uconci);
                            $query_res = "UPDATE reservations SET status_reservation = '$status_reserva' WHERE id_reservation like $id_reservation;";
                            $result_res = mysqli_query($con, $query_res);
                                
                        }
                    }else{
                        if ($newvalue > $sub_total || $new_cost > $sub_total) {
                            $status_c = 0;
                            $status_reserva = "RESERVED";
                        }
                        if ($newvalue == $sub_total || $new_cost == $sub_total) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                        }
                        if ($newvalue < $sub_total || $new_cost < $sub_total) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                               
                            date_default_timezone_set('America/Cancun');
                            $today = date('Y-m-d H:i:s');
                            $amount_electronic = round($total_cost_comision - $newvalue);
                            $sql_electronic_purse ="";
                            $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation = $id_reservation;";
                            $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                            if ($result_electronic_purse) {
                                if (mysqli_num_rows($result_electronic_purse) > 0) {
                                    $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $of_the_agency, id_reservation = $id_reservation, id_user = $id_user, descripcion_electronic = 'MODIFICACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                }else{
                                    $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                                }
                            }else{
                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                            }
                            mysqli_query($con, $sql_electronic_purse);
                        }
                        $query_conci = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                        $result_conci = mysqli_query($con, $query_conci);
                        if ($result_conci) {
                            $ins_sql = mysqli_fetch_object($result_conci);
                            $sql_uconci = "UPDATE conciliation SET status = $status_c WHERE id_reservation = $id_reservation;";
                            $result_uconci = mysqli_query($con, $sql_uconci);
                            $query_res = "UPDATE reservations SET status_reservation = '$status_reserva' WHERE id_reservation like $id_reservation;";
                            $result_res = mysqli_query($con, $query_res);
                        }
    
                    }
                }

                $error = 1;
                $json = array(
                    'total_cost_commision' => $newvalue,
                    'total_cost' => $new_cost,
                    'error' => $error
                );
                $jsonString = json_encode($json);
                echo $jsonString;
            }else{               
                $json = array(
                    'total_cost_commision' => '0',
                    'total_cost' => '0',
                    'error' => $error
                );
                $jsonString = json_encode($json);
                echo $jsonString;
            }
            /* Cierra la conexión */
            mysqli_close($con);
        }
        function getServiceListHotelHotel($ins, $con){
            include('../config/conexion.php');
            $id_reservation = $ins->{'id_reservation'};
            $id_user = $ins->{'id_user'};
            $code_invoice = $ins->{'code_invoice'};
            $code_client= $ins->{'code_client'}; 
            $name_asesor = $ins->{'name_asesor'};  
            $of_the_agency = $ins->{'of_the_agency'};
            $name_hotel = mysqli_real_escape_string($con,$ins->{'name_hotel'});
            $name_hotel_interhotel = mysqli_real_escape_string($con,$ins->{'name_hotel_interhotel'});
            $type_traslado = $ins->{'type_traslado'};
            $type_service = $ins->{'type_service'};
            $num_pasajeros = $ins->{'num_pasajeros'};
            $date_arrival = $ins->{'date_arrival'};
            $airline_arrival = $ins->{'airline_arrival'};
            $no_fly_arrival = $ins->{'no_fly_arrival'};
            $time_entry = mysqli_real_escape_string($con,$ins->{'time'});
            $time_exit = mysqli_real_escape_string($con,$ins->{'time_exit'});
            $time_hour_arrival = $ins->{'time_hour_arrival'};
            $time_minute_arrival = $ins->{'time_minute_arrival'};
            $date_exit = $ins->{'date_exit'};
            $airline_exit =  $ins->{'airline_exit'};
            $no_fly_exit = $ins->{'no_fly_exit'};
            $time_hour_exit =  $ins->{'time_hour_exit'};
            $time_minute_exit = $ins->{'time_minute_exit'}; 
            $time_pickup = $ins->{'time_pickup'};
            $time_pickup_inter = $ins->{'time_pickup_inter'};
            $time_service = $ins->{'time_service'};
            $method_payment = $ins->{'method_payment'};
            $sub_total = $ins->{'sub_total'};
            $commission = $ins->{'commission'};
            $total_cost_comision = $ins->{'total_cost_comision'};
            $currency = $ins->{'currency'};
            $name_client = mysqli_real_escape_string($con,$ins->{'name_client'});
            $last_name = mysqli_real_escape_string($con,$ins->{'last_name'});
            $mother_lastname = mysqli_real_escape_string($con,$ins->{'mother_lastname'});
            $email_client = mysqli_real_escape_string($con,$ins->{'email_client'}); 
            $phone_client = mysqli_real_escape_string($con,$ins->{'phone_client'});
            $special_request = mysqli_real_escape_string($con,$ins->{'special_request'});
            $newvalue = 0;
            $error = 0;
            $new_cost =0;
            $pickup ="";
            $div_price ="";
            //Obetnemos el tipo de moneda
            $moneda = $this->getDivisa('mxn', $con);
             
            //Verificacion de tipo de servicio de traslado
            switch ($ins->{'type_traslado'}) {
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

           if ($type_traslado == 'SEN/HA' || $type_traslado == 'RED') {
            $pickup = $ins->{'pickup'};
            }
           $new_time_en = "";
           $new_time_ex = "";
           $new_date_en = "";
           $new_date_ex = "";
           if ($type_traslado == 'SEN/HA' ) {
               $new_time_en = $time_entry;
               $new_time_ex = $time_exit;
               $new_date_ex = $date_exit;
               $new_date_en = "";

           }
           if ($type_traslado == 'RED') {
               $new_date_en = $date_arrival;
               $new_date_ex = $date_exit;
               $new_time_en = $time_entry;
               $new_time_ex = $time_exit;

           }
           if ($type_traslado == 'SEN/AH' ) {
               $new_time_en = $time_entry;
               $new_time_ex = $time_exit;
               $new_date_ex = "";
               $new_date_en = $date_arrival;
           }
           if ($type_traslado == 'SEN/HH' ) {
               $new_time_en = $time_pickup;
               $new_time_ex = $time_pickup_inter;
               $new_date_ex = "";
               $new_date_en = $date_arrival;
           }
           if ($type_traslado == 'REDHH') {
               $new_time_en = $time_pickup;
               $new_time_ex = $time_pickup_inter;
               $new_date_ex = $date_exit;
               $new_date_en = $date_arrival;

           }
            //Obtenemos zona de origen
            $zona = json_decode($this->getAreaDestination($ins->{'name_hotel'}, $con));
            //Obtenemos la tarifa de la zona
            $rates = json_decode($this->getRateArea($zona->{'id_zone'}, $con));  
             
            $zona_interhotel = '';
            $rates_interhotel = '';
            if ($ins->{'name_hotel_interhotel'}) {
                //Obtenemos zona de destino
                $zona_interhotel = json_decode($this->getAreaDestination($ins->{'name_hotel_interhotel'}, $con));
                //Obtenemos la tarifa de la zona
                $rates_interhotel = json_decode($this->getRateArea($zona_interhotel->{'id_zone'}, $con));  
                
            }
            //Cargo adicional al encontrarse a una distancia muy larga de 20%
            $additional_charge = 0;
            if ($zona->{'additional_charge'} != $zona_interhotel->{'additional_charge'}) {
                $additional_charge = 0.80;
            }

            //Privado
            if ($type_service == 'privado') {
                if (intval($rates[0]->{'private'}->{'privado_ow_1'}) > 0 && $rates[0]->{'private'}->{'privado_ow_1'} != NULL) {
                    $rates_private_rt =  "";
                    $rates_private_ow =  "";
                    $rates_private_rt_2 =  "";
                    $rates_private_ow_2 =  "";
                    $div_prices_private = "";
                    $new_rate_rt = '';
                    $new_rate_ow = '';
                    if ($num_pasajeros >=1 && $num_pasajeros <=4) {
                        //Hotel 1
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_1'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_1'};
                        //Hotel 2
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_1'};
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_1'};
    
                    }
                    if ($num_pasajeros >=5 && $num_pasajeros <=6) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_2'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_2'};
                        
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_2'} ;
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_2'};
                    }
                    if ($num_pasajeros >=7 && $num_pasajeros <=8) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_3'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_3'};
                        
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_3'} ;
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_3'};
                    }
                    if ($num_pasajeros >=9 && $num_pasajeros <=10) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_4'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_4'};
                        
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_4'} ;
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_4'};
                    }
                    if ($num_pasajeros >10 && $num_pasajeros <=11) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_5'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_5'};
                        
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_5'} ;
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_5'};
                    }
                    if ($num_pasajeros >=12 && $num_pasajeros <=16) {
                        $rates_private_ow = $rates[0]->{'private'}->{'privado_ow_6'} ;
                        $rates_private_rt = $rates[0]->{'private'}->{'privado_rt_6'};
                        
                        $rates_private_ow_2 = $rates_interhotel[0]->{'private'}->{'privado_ow_6'} ;
                        $rates_private_rt_2 = $rates_interhotel[0]->{'private'}->{'privado_rt_6'};
                    }
                    if ($type_traslado == 'REDHH') {
                        if ($currency == 'mx') {
                            $div_price = round($rates_private_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_private_rt / $moneda,0);
                        }
                    }else{
                        if ($currency == 'mx') {
                            $div_price = round($rates_private_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($rates_private_ow / $moneda,0);
                        }
                    }
                    if ($type_traslado == 'REDHH') {
                        if ($rates_private_rt > $rates_private_rt_2) {
                            $new_rate_rt = $rates_private_rt;
                            $new_rate_ow = $rates_private_ow;
                        }else{
                            $new_rate_rt = $rates_private_rt_2;
                            $new_rate_ow = $rates_private_ow_2;
                        }
                        $new_rate_additional_charge_rt = '';
                        $new_rate_additional_charge_rt = $new_rate_rt;
                        $new_rate_additional_charge_ow = '';
                        $new_rate_additional_charge_ow = $new_rate_ow;
                        if ($additional_charge != 0) {
                            $new_rate_additional_charge_rt = $new_rate_rt / $additional_charge;  
                            $new_rate_additional_charge_ow = $new_rate_ow / $additional_charge;  
                        }
                        if ($currency == 'mx') {
                            $div_price = round($new_rate_additional_charge_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($new_rate_additional_charge_rt / $moneda,0);
                        }
                        
                    }else{
                        if ($rates_private_ow > $rates_private_ow_2) {
                            $new_rate_rt = $rates_private_rt;
                            $new_rate_ow = $rates_private_ow;
                        }else{
                            $new_rate_rt = $rates_private_rt_2;
                            $new_rate_ow = $rates_private_ow_2;
                        }
                        $new_rate_additional_charge_rt = '';
                        $new_rate_additional_charge_rt = $new_rate_rt;
                        $new_rate_additional_charge_ow = '';
                        $new_rate_additional_charge_ow = $new_rate_ow;
                        if ($additional_charge != 0) {
                            $new_rate_additional_charge_rt = $new_rate_rt / $additional_charge;  
                            $new_rate_additional_charge_ow = $new_rate_ow / $additional_charge;  
                        }
                        if ($currency == 'mx') {
                            $div_price = round($new_rate_additional_charge_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($new_rate_additional_charge_ow / $moneda,0);
                        }
                    }
                }
            }

            //Lujo
            if ($type_service == 'lujo') {
                if (intval($rates[0]->{'luxury'}->{'lujo_ow_1'}) > 0 && $rates[0]->{'luxury'}->{'lujo_ow_1'} != NULL && $num_pasajeros <=6) {
                    $rates_luxury_ow = "";
                    $rates_luxury_rt = "";
                    $rates_luxury_ow_2 = "";
                    $rates_luxury_rt_2 = "";
                    $div_prices_luxury = "";
                    if ($num_pasajeros >=1 && $num_pasajeros <=6) {
                        $rates_luxury_ow = $rates[0]->{'luxury'}->{'lujo_ow_1'} ;
                        $rates_luxury_rt = $rates[0]->{'luxury'}->{'lujo_rt_1'};
    
                        $rates_luxury_ow_2 = $rates_interhotel[0]->{'luxury'}->{'lujo_ow_1'} ;
                        $rates_luxury_rt_2 = $rates_interhotel[0]->{'luxury'}->{'lujo_rt_1'};
                    }
                    if ($type_traslado == 'REDHH') {
                        if ($rates_luxury_rt > $rates_luxury_rt_2) {
                            $new_rate_rt = $rates_luxury_rt;
                            $new_rate_ow = $rates_luxury_ow;
                        }else{
                            $new_rate_rt = $rates_luxury_rt_2;
                            $new_rate_ow = $rates_luxury_ow_2;
                        }
                        $new_rate_additional_charge_rt = '';
                        $new_rate_additional_charge_rt = $new_rate_rt;
                        $new_rate_additional_charge_ow = '';
                        $new_rate_additional_charge_ow = $new_rate_ow;
                        if ($additional_charge != 0) {
                            $new_rate_additional_charge_rt = $new_rate_rt / $additional_charge;  
                            $new_rate_additional_charge_ow = $new_rate_ow / $additional_charge;  
                        }
                        
                        if ($currency == 'mx') {
                            $div_price = round($new_rate_additional_charge_rt, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($new_rate_additional_charge_rt / $moneda,0);
                        }
                    }else{
                        if ($rates_luxury_ow > $rates_luxury_ow_2) {
                            $new_rate_rt = $rates_luxury_rt;
                            $new_rate_ow = $rates_luxury_ow;
                        }else{
                            $new_rate_rt = $rates_luxury_rt_2;
                            $new_rate_ow = $rates_luxury_ow_2;
                        }
                        $new_rate_additional_charge_rt = '';
                        $new_rate_additional_charge_rt = $new_rate_rt;
                        $new_rate_additional_charge_ow = '';
                        $new_rate_additional_charge_ow = $new_rate_ow;
                        if ($additional_charge != 0) {
                            $new_rate_additional_charge_rt = $new_rate_rt / $additional_charge;  
                            $new_rate_additional_charge_ow = $new_rate_ow / $additional_charge;  
                        }
                        if ($currency == 'mx') {
                            $div_price = round($new_rate_additional_charge_ow, 0);
                        }
                        if ($currency == 'us') {
                            $div_price = round($new_rate_additional_charge_ow / $moneda,0);
                        }
                    }
                }
            }
            
            if ($method_payment == 'card' || $method_payment == 'paypal') {
                $cargo = 0.95;
                $add_cargo = $div_price / $cargo;
                $sum = $commission + $add_cargo;
                $new_cost = $div_price;
                $newvalue = number_format($sum, 2, '.', '');
            }
            if($method_payment == 'oxxo' || $method_payment == 'airport' || $method_payment == 'deposit' || $method_payment == 'transfer'){    
                $new_cost = number_format($div_price, 2, '.', '');
                $newvalue = number_format($div_price, 2, '.', '');
            }
            if ($method_payment == 'a_pa' || $method_payment == 'a_transfer' || $method_payment == 'a_paypal' || $method_payment == 'a_card') {
                $new_cost = number_format($total_cost_comision, 0, '.', '');
                $newvalue = number_format($total_cost_comision, 0, '.', '');
            }
            
            $query = "SELECT * FROM reservations AS R INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation
            INNER JOIN clients AS C ON R.id_client = C.id_client WHERE R.id_reservation = $id_reservation;";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $id_client_e = $row['id_client'];
            $json = array();
            if ($newvalue != 0 || $new_cost != 0) {
                if ($comment_bef != $special_request) {
                    date_default_timezone_set('America/Cancun');
                    $today = date('Y-m-d H:i:s');
                    $query_comment = "INSERT INTO bitacora(comments,id_user,id_reservation,register_date,status)VALUES('$special_request', $id_user, $id_reservation, '$today',0);";
                    $result_comment = mysqli_query($con,$query_comment);
                }
                //Insertamos datos Clientes
                $query_client = "UPDATE clients SET code_client= '$code_client', name_advisor = '$name_asesor',name_client = '$name_client',last_name = '$last_name',mother_lastname = '$mother_lastname',email_client = '$email_client',phone_client = '$phone_client',comments_client = '$special_request' WHERE id_client = $id_client_e;";
                $result_client = mysqli_query($con, $query_client);
                //Insertamos datos Reserva
                $query_reserva = "UPDATE reservations SET type_transfer = '$type_traslado', airline_in = '$airline_arrival', no_fly = '$no_fly_arrival', airline_out = '$airline_exit', no_flyout = '$no_fly_exit', transfer_destiny = '$name_hotel',destiny_interhotel = '$name_hotel_interhotel',of_the_agency = $of_the_agency where id_reservation = $id_reservation;";
                $result_reserv = mysqli_query($con, $query_reserva);
                //Insertamos datos Detalles Reserva
                $query_detalles = "UPDATE reservation_details SET date_arrival = '$new_date_en',date_exit = '$new_date_ex', time_arrival = '$new_time_en', time_exit = '$new_time_ex', time_service = '$time_service', number_adults = $num_pasajeros, agency_commision = '$commission', total_cost_commision = $newvalue, total_cost = $new_cost, type_service = '$type_service', method_payment = '$method_payment' ,pickup_entry = '$pickup' WHERE id_reservation = $id_reservation;";
                $result_detalles = mysqli_query($con, $query_detalles);
                
                //Insertamos datos Conciliacion
                $status_c = 0;
                $query = "";
                $sql_uconci = "";
                $query_res = "";
                $status_reserva = "";
                //COMPROBAMOS SI YA ESTA CONCILIADO
                $query_com_ope = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                $reseult_com_ope = mysqli_query($con, $query_com_ope);
                $row_com_ope = mysqli_fetch_assoc($reseult_com_ope);
                if ($row_com_ope['status'] == 1) {
                    if ($method_payment =='card' || $method_payment == 'paypal') {
                        if ($newvalue > $total_cost_comision || $new_cost > $total_cost_comision) {
                            $status_c = 0;
                            $status_reserva = "RESERVED";
                        }
                        if ($newvalue == $total_cost_comision || $new_cost == $total_cost_comision) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                        }
                        if ($newvalue < $total_cost_comision || $new_cost < $total_cost_comision) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                            
                            date_default_timezone_set('America/Cancun');
                            $today = date('Y-m-d H:i:s');
                            $cargo_new_price = $newvalue * 0.05;
                            $cargo_before_price = $total_cost_comision * 0.05;
                            $amount_electronic = round(($total_cost_comision - $cargo_before_price) - ($newvalue - $cargo_new_price));

                            $sql_electronic_purse ="";
                            $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id_reservation;";
                            $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                            if ($result_electronic_purse) {
                                if (mysqli_num_rows($result_electronic_purse) > 0) {
                                    $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $of_the_agency, id_reservation = $id_reservation, id_user = $id_user, descripcion_electronic = 'MODIFICACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                }else{
                                    $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                                }
                            }else{
                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                            }
                            mysqli_query($con, $sql_electronic_purse);
                        }
                        $query_conci = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                        $result_conci = mysqli_query($con, $query_conci);
                        if ($result_conci) {
                            $sql_uconci = "UPDATE conciliation SET status = $status_c WHERE id_reservation = $id_reservation;";
                            $result_uconci = mysqli_query($con, $sql_uconci);
                            $query_res = "UPDATE reservations SET status_reservation = '$status_reserva' WHERE id_reservation like $id_reservation;";
                            $result_res = mysqli_query($con, $query_res);
                        }
                    }else{
                        if ($newvalue > $sub_total || $new_cost > $sub_total) {
                            $status_c = 0;
                            $status_reserva = "RESERVED";
                        }
                        if ($newvalue == $sub_total || $new_cost == $sub_total) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                        }
                        if ($newvalue < $sub_total || $new_cost < $sub_total) {
                            $status_c = 1;
                            $status_reserva = "COMPLETED";
                            
                            date_default_timezone_set('America/Cancun');
                            $today = date('Y-m-d H:i:s');
                            $amount_electronic = round($sub_total - $newvalue);
                            $sql_electronic_purse ="";
                            $queryelectronic_purse = "SELECT * FROM electronic_purse WHERE id_reservation like $id_reservation;";
                            $result_electronic_purse = mysqli_query($con, $queryelectronic_purse);
                            if ($result_electronic_purse) {
                                if (mysqli_num_rows($result_electronic_purse) > 0) {
                                    $sql_electronic_purse = "UPDATE electronic_purse SET id_agency = $of_the_agency, id_reservation = $id_reservation, id_user = $id_user, descripcion_electronic = 'MODIFICACIÓN DE RESERVA', amount_electronic= $amount_electronic,  date_register_electronic = '$today';";
                                }else{
                                    $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                                }
                            }else{
                                $sql_electronic_purse = "INSERT INTO electronic_purse(id_agency, id_reservation,id_user, descripcion_electronic, amount_electronic, date_register_electronic) VALUES($of_the_agency, $id_reservation, $id_user, 'MODIFICACIÓN DE RESERVA', $amount_electronic, '$today');";
                            }
                            mysqli_query($con, $sql_electronic_purse);
                            
                        }
                        $query_conci = "SELECT * FROM conciliation WHERE id_reservation = $id_reservation;";
                        $result_conci = mysqli_query($con, $query_conci);
                        if ($result_conci) {
                            $sql_uconci = "UPDATE conciliation SET status = $status_c WHERE id_reservation = $id_reservation;";
                            $result_uconci = mysqli_query($con, $sql_uconci);
                            $query_res = "UPDATE reservations SET status_reservation = '$status_reserva' WHERE id_reservation like $id_reservation;";
                            $result_res = mysqli_query($con, $query_res);
                                
                        }
    
                    }
                }


                $error = 1;
                $json = array(
                    'total_cost_commision' => $newvalue,
                    'total_cost' => $new_cost,
                    'error' => $error,
                );
                $jsonString = json_encode($json);
                echo $jsonString;
            }else{               
                $json = array(
                    'total_cost_commision' => '0',
                    'total_cost' => '0',
                    'error' => $error
                );
                $jsonString = json_encode($json);
                echo $jsonString;
            }
            /* Cierra la conexión */
            mysqli_close($con);
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
            $query= "SELECT R.id_zone, R.name_zone, R.additional_charge FROM hotels AS H INNER JOIN rates_public AS R ON H.id_zone = R.id_zone WHERE H.name_hotel = '$newhotel';";
            $result = mysqli_query($con, $query);
            $json = array();
            if ($result) {                
                while ($row = mysqli_fetch_object($result)) {
                   $json = array(
                        'id_zone' => $row->id_zone,
                        'name_zone' => $row->name_zone,
                        'additional_charge' => $row->additional_charge
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

        function count_msj($obj){
            include('../config/conexion.php');
            $fecha_actual = date("Y-m-d");
            $date = date("Y-m-d",strtotime($fecha_actual."- 3 day"));
            $query_count = "SELECT COUNT(*) as total FROM bitacora AS B INNER JOIN users AS U ON B.id_user = U.id_user INNER JOIN reservations as R on B.id_reservation = R.id_reservation WHERE B.status = 0 and B.register_date >= '$date'; ";
            $result_count = mysqli_query($con, $query_count);
            if ($result_count) {
                $fila = mysqli_fetch_assoc($result_count);
                return $fila['total'];
            }
        }
        function count_acts($obj){
            include('../config/conexion.php');
            $fecha_actual = date("Y-m-d");
            $date = date("Y-m-d",strtotime($fecha_actual."- 3 day"));
            $query_count = "SELECT COUNT(*) as total FROM activities AS A INNER JOIN users AS U ON A.id_user = U.id_user INNER JOIN reservations as R on A.id_reservation = R.id_reservation WHERE  A.change_date >= '$date' AND  A.status_activity = 0;";
            $result_count = mysqli_query($con, $query_count);
            if ($result_count) {
                $fila = mysqli_fetch_assoc($result_count);
                return $fila['total'];
            }
        }
    }
?>