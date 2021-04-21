<?php
    class Reservacion{
        
        //Search reserva
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM clients AS C 
                INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE A.name_agency LIKE '%$search%' OR R.code_invoice LIKE '%$search%' OR C.name_client LIKE '%$search%' OR R.status_reservation LIKE '%$search%' OR D.method_payment LIKE '%$search%' OR D.type_service LIKE '%$search%'";
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
                    INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE R.date_register_reservation >= '$search' AND R.date_register_reservation <= '$search2' ORDER BY R.date_register_reservation asc;";
                    
                   
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
                }else{
                    
                    $query = "SELECT * FROM clients AS C 
                    INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
                    INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE R.date_register_reservation like '$search' ORDER BY R.date_register_reservation asc;";
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
            $payment = $arg->{'value'};
            $id = $arg->{'id'};
            $text = $arg->{'text'};
            $sql = "SELECT * FROM reservations WHERE id_reservation = $id;";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE reservation_details SET method_payment = '$payment' WHERE id_reservation = $id;";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a guardado correctamente a $text como metodo de pago";
                    }
                }else{
                    $sql2 = "INSERT INTO reservation_details(id_reservation, method_payment) VALUES($id ,$payment);";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a guardado correctamente a $text como metodo de pago";
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
            $id = $arg->{'id'};
            $text = $arg->{'text'};
            $sql = "SELECT * FROM reservations WHERE id_reservation = $id;";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE reservations SET status_reservation = '$statusr' WHERE id_reservation = $id;";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a cambiado correctamente el estado de la reservacion a $text.";
                    }
                }else{
                    $sql2 = "INSERT INTO reservations(id_reservation, status_reservation) VALUES($id ,$statusr);";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a cambiado correctamente el estado de la reservacion a $text.";
                    }
                }
            }

            return json_encode(array('message' => $message));
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
                INNER JOIN sale_providers AS S ON E.id_reservation = S.id_reservation
                INNER JOIN providers AS P ON S.id_provider = P.id_provider
                INNER JOIN users AS U ON E.id_user = U.id_user 
                WHERE E.id_reservation = '$obj' AND E.charge_type = 'D';";
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
    }
?>