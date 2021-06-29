<?php
    class Reports{
        public function downloadReport($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
            $name_agency = mysqli_real_escape_string($con, $ins->{'name_agency'});
            $name_zone = mysqli_real_escape_string($con, $ins->{'name_zone'});
            $name_type_service = mysqli_real_escape_string($con, $ins->{'name_type_service'});
            $query ="";
            $condition_date ="";
            $condition_agency = "";
            $condition_zone ="";
            $condition_type_service ="";
            $inner_agency = "";
            $inner_zone ="";
            $and = "";
            $template = "";

            if ($f_date_a && $f_date_s) {
                $condition_date = "(RD.date_arrival >= '$f_date_a' AND RD.date_exit <= '$f_date_s')";
                $and = "AND "; 
            }
            if ($name_agency) {
                $condition_agency = "$and (A.name_agency = '$name_agency') ";
                $and = "AND "; 
            }
            if ($name_zone) {
                $inner_zone = "INNER JOIN hotels AS H ON R.transfer_destiny = H.name_hotel INNER JOIN rates_public as RP on H.id_zone = RP.id_zone";
                $condition_zone = "$and (RP.name_zone = '$name_zone') " ;
                $and = "AND "; 
            }
            if ($name_type_service) {
                $condition_type_service = "$and (RD.type_service = '$name_type_service') ";
            }

            $query ="SELECT * FROM reservations AS R INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation INNER JOIN clients AS C ON C.id_client = R.id_client INNER JOIN agencies AS A ON R.of_the_agency = A.id_agency OR R.id_agency = A.id_agency  $inner_zone WHERE $condition_date $condition_agency $condition_zone $condition_type_service ";
            $result = mysqli_query($con, $query);
            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                    <th>Hotel Destino</th>
                                    <th>Interhotel Destino</th>
                                    <th>Pasajeros</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Aerolina de Llegada</th>
                                    <th>No. vuelo de Llegada</th>
                                    <th>Fecha de Llegada</th>
                                    <th>Hora de Llegada</th>
                                    <th>Aerolina de Salida</th>
                                    <th>No. vuelo de Salida</th>
                                    <th>Fecha de Salida</th>
                                    <th>Hora de Salida</th>
                                    <th>Pickup</th>
                                    <th>Pickup Interhotel (Redondo)</th>
                                    <th>Hora de Servicio (Compartido)</th>
                                    <th>Total</th>
                                    <th>Moneda</th>
                                    <th>Metodo de Pago</th>
                                    <th>Fecha de Reservacion</th>
                                    <th>Agencia</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $newtype = "";
                        $newcurrency ="";
                        $newnamepay ="";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Sencillo Aeropuerto > Hotel';
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Sencillo Hotel > Aeropuerto';
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Redondo - Hotel > Hotel';
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Sencillo - Hotel > Hotel';
                        }
                        if ($row['type_currency'] == 'mx') {
                            $newcurrency = 'MXN';
                        }else {
                            $newcurrency = 'USD';
                        }
                        switch ($row['method_payment']) {
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
                        $template.="
                            <tr>
                                    <td>{$row['code_invoice']}</td>
                                    <td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
                                    <td>{$row['email_client']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['destiny_interhotel']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['airline_in']}</td>
                                    <td>{$row['no_fly']}</td>
                                    <td>{$row['date_arrival']}</td>
                                    <td>{$row['time_arrival']}</td>
                                    <td>{$row['airline_out']}</td>
                                    <td>{$row['no_flyout']}</td>
                                    <td>{$row['date_exit']}</td>
                                    <td>{$row['time_exit']}</td>
                                    <td>{$row['pickup_entry']}</td>
                                    <td>{$row['pickup']}</td>
                                    <td>{$row['time_service']}</td>
                                    <td>{$row['total_cost']}</td>
                                    <td>{$newcurrency}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$row['date_register_reservation']}</td>
                                    <td>{$row['name_agency']}</td>
                                    <td>{$row['comments_client']}</td>

                            </tr>";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                
                    $template = '<tr><td colspan="8" align="center"><strong style="font-size: 24px;">No se encontro ninguna reservación</strong></td></tr>';
                }
            }
            return utf8_decode($template);
        }
        public function downloadReportS($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');

            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
            $name_agency = mysqli_real_escape_string($con, $ins->{'name_agency'});
            $name_zone = mysqli_real_escape_string($con, $ins->{'name_zone'});
            $name_type_service = mysqli_real_escape_string($con, $ins->{'name_type_service'});
            $type_translate = mysqli_real_escape_string($con, $ins->{'type_translate'});
            $template = "";
            $title_tranlate ="";
            $query_p = "SET SESSION SQL_BIG_SELECTS = 1";
            $result_p = mysqli_query($con, $query_p);
            $template.="
            <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                <thead class='m-3'>
                    $title_tranlate
                    <tr>";
            if ($type_translate == 'todos') {
                $template.="
                            <th></th>
                ";
            }
            $template.="
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th>Zona</th>
                            <th>Hotel</th>
                            <th>Pasajeros</th>
                            <th>Servicio</th>
                            <th>Traslado</th>
                            <th>Aerolina de Llegada</th>
                            <th>No. vuelo de Llegada</th>
                            <th>Fecha de Llegada</th>
                            <th>Hora de Llegada</th>
                            <th>Aerolina de Salida</th>
                            <th>No. vuelo de Salida</th>
                            <th>Fecha de Salida</th>
                            <th>Hora de Salida</th>
                            <th>Pickup</th>
                            <th>Pickup Interhotel (Redondo)</th>
                            <th>Hora de Servicio (Compartido)</th>
                            <th>Total</th>
                            <th>Moneda</th>
                            <th>Metodo de Pago</th>
                            <th>Fecha de Reservacion</th>
                            <th>Agencia</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            $query = $this->servicesQueryString($obj, $con);          
            $result = mysqli_query($con, $query);

            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $newtype = "";
                        $newcurrency ="";
                        $newnamepay ="";
                        $arrvial_or_exit ="";
                        $newhotel = "";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Sencillo Aeropuerto > Hotel';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Sencillo Hotel > Aeropuerto';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Redondo - Hotel > Hotel';
                            $newhotel = $row['transfer_destiny'].' > '.$row['destiny_interhotel'];
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Sencillo - Hotel > Hotel';
                            $newhotel = $row['transfer_destiny'].' > '.$row['destiny_interhotel'];
                        }
                        if ($row['type_currency'] == 'mx') {
                            $newcurrency = 'MXN';
                        }else {
                            $newcurrency = 'USD';
                        }
                        switch ($row['method_payment']) {
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
                        if ($row['date_arrival'] >= $f_date_a && $row['date_arrival'] <= $f_date_s) {
                            $arrvial_or_exit = '<td style="color: #088A29; font-weight: 300;">Llegada</td>';
                        }
                        if ($row['date_exit'] >= $f_date_a && $row['date_exit'] <= $f_date_s) {
                            $arrvial_or_exit = '<td style="color: #B40404; font-weight: 400;">Salida</td>';
                        }
                        $template.="
                            <tr>";
                        if ($type_translate == 'todos') {
                            $template.="
                                    $arrvial_or_exit
                            ";
                        }
                        $template.="
                        $query
                                    <td>{$row['code_invoice']}</td>
                                    <td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
                                    <td>{$row['email_client']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['name_zone']}</td>
                                    <td>{$newhotel}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['airline_in']}</td>
                                    <td>{$row['no_fly']}</td>
                                    <td>{$row['date_arrival']}</td>
                                    <td>{$row['time_arrival']}</td>
                                    <td>{$row['airline_out']}</td>
                                    <td>{$row['no_flyout']}</td>
                                    <td>{$row['date_exit']}</td>
                                    <td>{$row['time_exit']}</td>
                                    <td>{$row['pickup_entry']}</td>
                                    <td>{$row['pickup']}</td>
                                    <td>{$row['time_service']}</td>
                                    <td>{$row['total_cost']}</td>
                                    <td>{$newcurrency}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$row['date_register_reservation']}</td>
                                    <td>{$row['name_agency']}</td>
                                    <td>{$row['comments_client']}</td>

                            </tr>";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                
                    $template = 'No se encontro ninguna reservacion'.$query;
                }
            }else{
                
                $template = 'No se encontro ninguna reservacion'.$query;
            }
            return $template;
        }
        public function downloadReportC($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
            $name_agency = mysqli_real_escape_string($con, $ins->{'name_agency'});
            $type_conciliation = mysqli_real_escape_string($con, $ins->{'type_conciliation'});
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d');
            $query ="";
            $condition_date ="";
            $condition_agency = "";
            $inner_conciliation = "";
            $condition_conciliation = "";
            $name_zona = "";
            $and = "";
            $template = "";
            $inner_agency ="";

            if ($f_date_a && $f_date_s) {
                $condition_date = "((RD.date_arrival BETWEEN '$f_date_a' AND '$f_date_s') or (RD.date_exit BETWEEN '$f_date_a' AND '$f_date_s'))";
                $and = "AND "; 
            }else{
                $f_date_a = $today;
                $f_date_s = $today;
                $condition_date = "((RD.date_arrival BETWEEN '$f_date_a' AND '$f_date_s') or (RD.date_exit BETWEEN '$f_date_a' AND '$f_date_s'))";
                $and = "AND "; 

            }
            if ($name_agency) {
                $inner_agency = "INNER JOIN agencies AS A ON R.of_the_agency = A.id_agency OR R.id_agency = A.id_agency ";
                $condition_agency = "$and (A.name_agency = '$name_agency') ";
                $and = "AND "; 
            }
            if ($type_conciliation != '') {
                $inner_conciliation = "INNER JOIN conciliation AS CO ON R.id_reservation = CO.id_reservation";
                $condition_conciliation = "$and (CO.status = $type_conciliation)";
                $and = "AND "; 
            }
            $query ="SELECT * FROM clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation $inner_agency $inner_conciliation  WHERE $condition_date $condition_agency $condition_conciliation ORDER BY R.id_agency  DESC";
            $result = mysqli_query($con, $query);
            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    $template.="
                    $query
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr >
                                    <th>ID</th>
                                    <th>Agencia</th>
                                    <th>Cliente</th>
                                    <th>Hotel</th>
                                    <th>Zona</th>
                                    <th>Traslado</th>
                                    <th>Servicio</th>
                                    <th>Pasajeros</th>
                                    <th>Metodo de Pago</th>
                                    <th>Tarifa Neta</th>
                                    <th>Total (Comision)</th>
                                    <th>CXC</th>
                                    <th>Moneda</th>
                                    <th>Estado</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $newtype = "";
                        $newcurrency ="";
                        $newnamepay ="";
                        $newhotel ="";
                        $new_amount_total="";
                        $newagency ="";
                        $newnameclient = "";
                        if ($row['name_client']) {
                            $newnameclient = utf8_decode($row['name_client'].' '.$row['last_name'].' '.$row['mother_lastname']);
                        }
                        $id_r = $row['id_reservation'];
                        
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Sencillo Aeropuerto > Hotel';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Sencillo Hotel > Aeropuerto';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                            $newhotel = $row['transfer_destiny'];
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Redondo - Hotel > Hotel';
                            $newhotel = $row['transfer_destiny'].' > '.$row['destiny_interhotel'];
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Sencillo - Hotel > Hotel';
                            $newhotel = $row['transfer_destiny'].' > '.$row['destiny_interhotel'];
                        }
                        if ($row['type_currency'] == 'mx') {
                            $newcurrency = 'MXN';
                        }else {
                            $newcurrency = 'USD';
                        }
                        switch ($row['method_payment']) {
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
                        $new_nameagency = "";
                        $new_emailagency = "";
                        $new_phoneagency = "";
                        if($row['id_agency'] || $row['of_the_agency']){
                            $id_ag = "";
                            if ($row['id_agency'] == $row['of_the_agency']) {
                                $id_ag = $row['id_agency'];
                            }else{
                                $id_ag = $row['of_the_agency'];
                            }
                            $query_agency = "SELECT * FROM agencies WHERE id_agency like $id_ag;";
                            $result_a =  mysqli_query($con, $query_agency);
                            if ($result_a) {
                                $ins = mysqli_fetch_object($result_a);
                                $new_nameagency = utf8_decode($ins->name_agency);
                                $new_emailagency = utf8_decode($ins->email_pay_agency);
                                $new_phoneagency = utf8_decode($ins->phone_agency);
                            }
                        }
                        
                        $query_total = "SELECT SUM(expense_amount) as total FROM expenses WHERE id_reservation = $id_r;";
                        $result_t = mysqli_query($con, $query_total);
                        if ($result_t) {
                            $ins_t = mysqli_fetch_object($result_t);
                            $new_amount_total = $row['total_cost'] - $ins_t->total;
                        }
                        if ($row['transfer_destiny']) {
                            $name_zona ="";
                            $query_zon = "SELECT * FROM hotels as H inner join rates_public as R on H.id_zone = R.id_zone WHERE H.name_hotel = '{$row['transfer_destiny']}';";
                            $result_z = mysqli_query($con, $query_zon);
                            if ($result_z) {
                                $ins_zone = mysqli_fetch_object($result_z);
                                if (!is_null($ins_zone->name_zone)) {
                                    $name_zona = $ins_zone->name_zone;
                                }
                            }
                        }
                        $template.="
                            <tr>
                                    <td>{$row['code_invoice']}</td>
                                    <td class='hidden-sm'>{$new_nameagency}</td>
                                    <td>{$newnameclient}</td>
                                    <td>{$newhotel}</td>
                                    <td>{$name_zona}</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$row['total_cost']}</td>
                                    <td>{$row['total_cost_commision']}</td>
                                    <td> {$new_amount_total} </td>
                                    <td>{$newcurrency}</td>
                                    <td>{$row['status_reservation']}</td>
                                    <td>{$new_emailagency}</td>
                                    <td>{$new_phoneagency}</td>

                            </tr>";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                
                    $template = "No se encontro ninguna reservacion $query";
                }
            }else{
                
                $template = "No se encontro ninguna reservacion $query";
            }
            return utf8_decode($template);
        }
        public function downloadReportA($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');

            $query ="SELECT * FROM agencies WHERE `status` = 1 ORDER BY id_agency DESC ";
            $result = mysqli_query($con, $query);
            $template ="";
            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de agencia</th>
                                    <th>Email de Contacto</th>
                                    <th>Email de Pagos</th>
                                    <th>Telefono</th>
                                    <th>Nombre de Contacto</th>
                                    <th>Usuario</th>
                                    <th>Fecha de Registro</th>
                                    <th>Logo</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $template.="
                            <tr>
                                    <td>{$row['id_agency']}</td>
                                    <td>{$row['name_agency']}</td>
                                    <td>{$row['email_agency']}</td>
                                    <td>{$row['email_pay_agency']}</td>
                                    <td>{$row['phone_agency']}</td>
                                    <td>{$row['name_contact']} {$row['last_name_contact']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['register_date']}</td>
                                    <td>{$row['icon_agency']}</td>

                            </tr>";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }
            }else{
                
                $template = '<tr><td colspan="8" align="center"><strong style="font-size: 24px;">No se encontro ninguna reservación</strong></td></tr>';
            }
            return utf8_decode($template);
        }
        public function downloadReportO($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = "";
            $separador ="";
            if ($ins->{'f_date_s'} != "") {
                $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
                $separador = " al ";
            }
            $provider = mysqli_real_escape_string($con, $ins->{'provider'});
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d');
            // $query ="";
            // $condition_date ="";
            // $condition_date_exit ="";
            // $condition_agency = "";
            // $condition_zone ="";
            // $condition_type_service ="";
            // $inner_agency = "";
            // $inner_zone ="";
            // $order_by ="";
            // $and = "";
            $template = "";
            $title_tranlate ="";
            
		    $data = array('arrival_obj' => null, 'departure_obj' => null, 'interhoteles_obj' => null);
            //LLEGADAS
            $query_l = $this->operationQueryString('A', $provider, 'classic', $obj, $con);
            $result_l = mysqli_query($con, $query_l);
            if ($result_l) {
		        if (mysqli_num_rows($result_l) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <td colspan='12' align='center'><strong style='font-size: 24px;'>LLEGADAS $f_date_a $separador $f_date_s</strong></td>
                                </tr>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Destino</th>
                                    <th>Pax</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Hora</th>
                                    <th>Aerolinea</th>
                                    <th>Vuelo</th>
                                    <th>Pago</th>
                                    <th>Proveedor</th>
                                    <th>Staff</th>
                                    <th>Estado Reserva</th>
                                    <th  style='width:1000px'>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result_l)) {
                        $newtype = '';
                        $newnamepay ="";
                        $newprovider ="";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Aeorpuerto - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Hotel - Aeorpuerto ';
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        switch ($row['method_payment']) {
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
                        $_provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                        $nameProvider = $_provider == '' ? 'S/A' : $_provider;
                        $_rep = $this->getRepBySaleId($row['id_reservation'], 'A', $con);
                        $nameRep = $_rep == '' ? 'S/A' : $_rep;
                        $_comments = $this->getCommentsSaleId($row['id_reservation'], $con);
                        $allComments = $_comments == '' ? 'S/A' : $_comments;
                        $template.="
                            <tr>
                                    <td>{$row['name_client']} {$row['last_name']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>".ucfirst($row['type_service'])."</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['time_arrival']}</td>
                                    <td>{$row['airline_in']}</td>
                                    <td>{$row['no_fly']}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$nameProvider}</td>
                                    <td>{$nameRep}</td>
                                    <td>{$row['status_reservation']}</td>
                                    <td  style='width:1000px'>{$allComments}</td>
                            </tr>
                            ";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                    $template.= "No se encontro ninguna reservacion Llegadas";
                }
            }else{
                $template.= "No se encontro ninguna reservacion Llegadas";
            }

            //SALIDAS
            $query_s = $this->operationQueryString('D', $provider, 'classic', $obj, $con);
            $result_s = mysqli_query($con, $query_s);
            if ($result_s) {
		        if (mysqli_num_rows($result_s) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <td colspan='12' align='center'><strong style='font-size: 24px;'>SALIDAS $f_date_a $separador $f_date_s</strong></td>
                                </tr>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Origen</th>
                                    <th>Pax</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Hora</th>
                                    <th>Pickup</th>
                                    <th>Aerolinea</th>
                                    <th>Vuelo</th>
                                    <th>Pago</th>
                                    <th>Proveedor</th>
                                    <th>Estado Reserva</th>
                                    <th style='width:1000px'>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result_s)) {
                        $newtype = '';
                        $newnamepay ="";
                        $newprovider ="";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Aeorpuerto - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Hotel - Aeorpuerto ';
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        switch ($row['method_payment']) {
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
                        $_provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                        $nameProvider = $_provider == '' ? 'S/A' : $_provider;
                        $_comments = $this->getCommentsSaleId($row['id_reservation'], $con);
                        $allComments = $_comments == '' ? 'S/A' : $_comments;
                        $template.="
                            <tr>
                                    <td>{$row['name_client']} {$row['last_name']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>".ucfirst($row['type_service'])."</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['time_exit']}</td>
                                    <td>{$row['pickup_entry']}</td>
                                    <td>{$row['airline_out']}</td>
                                    <td>{$row['no_flyout']}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$nameProvider}</td>
                                    <td>{$row['status_reservation']}</td>
                                    <td style='width:1000px'>{$allComments}</td>
                            </tr>
                            ";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                    $template.= "No se encontro ninguna reservacion Salidas";
                }
            }else{
                $template.= "No se encontro ninguna reservacion Salidas";
            }
            //INTERHOTEL LLEGADAS
            $query_hl = $this->operationQueryString('A', $provider, 'hotel', $obj, $con);
            $result_hl = mysqli_query($con, $query_hl);
            if ($result_hl) {
		        if (mysqli_num_rows($result_hl) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <td colspan='12' align='center'><strong style='font-size: 24px;'>INTERHOTEL IDA $f_date_a $separador $f_date_s</strong></td>
                                </tr>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Origen</th>
                                    <th>Pax</th>
                                    <th>Destino</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Hora Pickup</th>
                                    <th>Pago</th>
                                    <th>Proveedor</th>
                                    <th>Estado Reserva</th>
                                    <th style='width:1000px'>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result_hl)) {
                        $newtype = '';
                        $newnamepay ="";
                        $newprovider ="";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Aeorpuerto - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Hotel - Aeorpuerto ';
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        switch ($row['method_payment']) {
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
                        $_provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                        $nameProvider = $_provider == '' ? 'S/A' : $_provider;
                        $_comments = $this->getCommentsSaleId($row['id_reservation'], $con);
                        $allComments = $_comments == '' ? 'S/A' : $_comments;
                        $template.="
                            <tr>
                                    <td>{$row['name_client']} {$row['last_name']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>{$row['destiny_interhotel']}</td>
                                    <td>".ucfirst($row['type_service'])."</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['pickup_entry']}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$nameProvider}</td>
                                    <td>{$row['status_reservation']}</td>
                                    <td style='width:1000px'>{$allComments}</td>
                            </tr>
                            ";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                    $template.= "No se encontro ninguna reservacion Interhotel";
                }
            }else{
                $template.= "No se encontro ninguna reservacion Interhotel";
            }
            //INTERHOTEL SALIDAS
            $query_hs = $this->operationQueryString('D', $provider, 'hotel', $obj, $con);
            $result_hs = mysqli_query($con, $query_hs);
            if ($result_hs) {
		        if (mysqli_num_rows($result_hs) > 0) {
                    $template.="
					    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0'>
                            <thead class='m-3'>
                                <tr>
                                    <td colspan='12' align='center'><strong style='font-size: 24px;'>INTERHOTEL VUELTA $f_date_a $separador $f_date_s</strong></td>
                                </tr>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Origen</th>
                                    <th>Pax</th>
                                    <th>Destino</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Hora Pickup</th>
                                    <th>Pago</th>
                                    <th>Proveedor</th>
                                    <th>Estado Reserva</th>
                                    <th style='width:1000px'>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
                    while ($row = mysqli_fetch_assoc($result_hs)) {
                        $newtype = '';
                        $newnamepay ="";
                        $newprovider ="";
                        if ($row['type_transfer'] == 'SEN/AH' ) {
                            $newtype = 'Aeorpuerto - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HA' ) {
                            $newtype = 'Hotel - Aeorpuerto ';
                        }
                        if ($row['type_transfer'] == 'RED' ) {
                            $newtype = 'Redondo';
                        }
                        if ($row['type_transfer'] == 'REDHH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        if ($row['type_transfer'] == 'SEN/HH' ) {
                            $newtype = 'Hotel - Hotel ';
                        }
                        switch ($row['method_payment']) {
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
                        $_provider = $this->getProvideBySaleId($row['id_reservation'], 'A', $con);
                        $nameProvider = $_provider == '' ? 'S/A' : $_provider;
                        $_comments = $this->getCommentsSaleId($row['id_reservation'], $con);
                        $allComments = $_comments == '' ? 'S/A' : $_comments;
                        $template.="
                            <tr>
                                    <td>{$row['name_client']} {$row['last_name']}</td>
                                    <td>{$row['phone_client']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['number_adults']}</td>
                                    <td>{$row['destiny_interhotel']}</td>
                                    <td>".ucfirst($row['type_service'])."</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['pickup']}</td>
                                    <td>{$newnamepay}</td>
                                    <td>{$nameProvider}</td>
                                    <td>{$row['status_reservation']}</td>
                                    <td style='width:1000px'>{$allComments}</td>
                            </tr>
                            ";
                    }
                    $template.="
                            </tbody>
                        </table>
                    ";
                }else{
                    $template.= "No se encontro ninguna reservacion Interhotel";
                }
            }else{
                $template.= "No se encontro ninguna reservacion Interhotel";
            }

            return $template;
        }
        function servicesQueryString($obj, $con){
            $ins = json_decode($obj);
            
            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
            $name_agency = mysqli_real_escape_string($con, $ins->{'name_agency'});
            $name_zone = mysqli_real_escape_string($con, $ins->{'name_zone'});
            $name_type_service = mysqli_real_escape_string($con, $ins->{'name_type_service'});
            $type_translate = mysqli_real_escape_string($con, $ins->{'type_translate'});
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d');
            $condition_date ="";
            $condition_date_exit ="";
            $condition_agency = "";
            $condition_zone ="";
            $condition_type_service ="";
            $inner_agency = "";
            $inner_zone ="";
            $order_by ="";
            $and = "";
            $template = "";
            $title_tranlate ="";

            if ($type_translate) { 
                if ($f_date_a == '' && $f_date_s == '') {
                    $f_date_a = $today;
                    $f_date_s = $today;
                }       
                if ($type_translate == 'todos') {
                    $condition_date = "((RD.date_arrival BETWEEN '$f_date_a' AND '$f_date_s') or (RD.date_exit BETWEEN '$f_date_a' AND '$f_date_s'))";
                    $and = "AND "; 
                    $title_tranlate = '<tr><td colspan="8" align="center"><strong style="font-size: 24px;">LLEGADAS y SALIDAS</strong></td></tr>';
                    $order_by ="ORDER BY RD.date_arrival ASC, RD.time_arrival ASC, RD.date_exit ASC, RD.pickup_entry ASC";
                }
                if ($type_translate == 'llegadas') {
                    $condition_date = "(RD.date_arrival BETWEEN '$f_date_a' AND '$f_date_s')";
                    $and = "AND "; 
                    $title_tranlate = '<tr><td colspan="8" align="center"><strong style="font-size: 24px;">LLEGADAS</strong></td></tr>';
                    $order_by = "ORDER BY RD.date_arrival ASC, RD.time_arrival ASC ";
                }
                if ($type_translate == 'salidas') {
                    $condition_date = "(RD.date_exit BETWEEN '$f_date_a' AND '$f_date_s')";
                    $and = "AND "; 
                    $title_tranlate = '<tr><td colspan="8" align="center"><strong style="font-size: 24px;">SALIDAS</strong></td></tr>';
                    $order_by = "ORDER BY RD.date_exit ASC, RD.pickup_entry ASC";
                }
            }
            if ($name_agency) {
                $condition_agency = "$and (A.name_agency = '$name_agency') ";
                $and = "AND "; 
            }
            if ($name_zone) {
                $inner_zone = "INNER JOIN hotels AS H ON R.transfer_destiny = H.name_hotel INNER JOIN rates_public as RP on H.id_zone = RP.id_zone";
                $condition_zone = "$and (RP.name_zone = '$name_zone') " ;
                $and = "AND "; 
            }
            if ($name_type_service) {
                $condition_type_service = "$and (RD.type_service = '$name_type_service') ";
            }
            
            $query ="SELECT * FROM reservations AS R INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation INNER JOIN clients AS C ON C.id_client = R.id_client INNER JOIN agencies AS A ON R.of_the_agency = A.id_agency OR R.id_agency = A.id_agency $inner_zone WHERE $condition_date $condition_agency $condition_zone $condition_type_service $order_by";
            return $query;
        }
        function operationQueryString($service, $provider, $type_service, $obj, $con){
            $ins = json_decode($obj);
            $type_date = $service == 'A' ? 'date_arrival' : 'date_exit';
            $type_time = $service == 'A' ? 'time_arrival' : 'time_exit';
            $f_date_a = mysqli_real_escape_string($con, $ins->{'f_date_a'});
            $f_date_s = "";
            if ($ins->{'f_date_s'} != "") {
                $f_date_s = mysqli_real_escape_string($con, $ins->{'f_date_s'});
            }
            $condition_transfer ="";
            $condition_provider ="";
            $condition_date ="";
            $inner_provider ="";
            $and ="";
            if ($provider || $provider != '') {
                $inner_provider = "INNER JOIN sale_providers AS SP ON R.id_reservation = SP.id_reservation";
                $condition_provider = "  SP.type_service = '$service'";
                $and = "AND";
            }
            if ($ins->{'f_date_a'}) {
                $condition_date = " $and (D.$type_date >= '$f_date_a' AND D.$type_date <= '$f_date_a')";
                if ($f_date_a != '' && $f_date_s != '') {
                    $condition_date = " $and ((D.date_arrival >= '$f_date_a' AND D.date_arrival <= '$f_date_s') OR (D.date_exit>= '$f_date_a' AND D.date_exit <= '$f_date_s'))";
                }
                $and = "AND";
            }
            if ($type_service == 'classic') {
                $condition_transfer = "$and (R.type_transfer = 'SEN/HA' OR R.type_transfer = 'SEN/AH' OR R.type_transfer = 'RED')";
            }else{
                $condition_transfer = " $and (R.type_transfer = 'SEN/HH' OR R.type_transfer = 'REDHH')";
            }

            $query = "SELECT * FROM reservations AS R INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation INNER JOIN clients AS C ON R.id_client = C.id_client  $inner_provider WHERE $condition_provider $condition_date $condition_transfer;";
            return $query;
        }

        function getProvideBySaleId($id_reservation, $service, $con){
            $query ="SELECT * FROM providers AS P INNER JOIN sale_providers AS S ON P.id_provider = S.id_provider WHERE S.id_reservation = $id_reservation AND S.type_service = '$service';";
            $result = mysqli_query($con, $query);
            $res ="";
            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    $ins = mysqli_fetch_object($result);
                    $res = $ins->name_provider;
                }
            }
            return $res;
        }
        function getRepBySaleId($id_reservation, $service, $con){
            $query ="SELECT * FROM receptionists AS R INNER JOIN sales_receptionists AS S ON R.id_receptionist = S.id_receptionist WHERE S.id_reservation = $id_reservation AND S.type_service = '$service';";
            $result = mysqli_query($con, $query);
            $res ="";
            if ($result) {
		        if (mysqli_num_rows($result) > 0) {
                    $ins = mysqli_fetch_object($result);
                    $res = $ins->name_receptionist;
                }
            }
            return $res;
        }
        function getCommentsSaleId($id_reservation, $con){
            $query_messages = "SELECT * FROM bitacora AS B INNER JOIN users AS U ON B.id_user = U.id_user WHERE B.id_reservation = $id_reservation ORDER BY B.register_date asc   ;";
            $result_message = mysqli_query($con, $query_messages);
            $template = "";
            if ($result_message) {
                if (mysqli_num_rows($result_message) > 0) {
                    $message = "";
                    while ($row = mysqli_fetch_assoc($result_message)) {
                        $template.= ' *'.$row['comments'];
                    }
                }else{
                    $template= 'Sin comentarios';
                }
            }else{
                $template= 'Sin comentarios';
            }
            return $template;
        }
    }
?>