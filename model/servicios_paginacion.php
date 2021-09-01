<?php

	// Connect database 

    session_start();
	require_once('../config/conexion.php');
    $id_role = $_SESSION['id_role'];

	$limit = 1;

	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	if (isset($_POST['navs'])) {
		$navs = $_POST['navs'];
	}else{
		$navs = "LLEGADA";
	}	
    $offset = ($page_no-1) * $limit;

    $today = date('Y-m-d');
	$complete_query_search ="";
	$type_search = $_POST['type_search'];
    //$new_date = date("Y-m-d",strtotime($today."- 3 day"));
    $search_today = date("Y");
    $new_date = $search_today.'-01-01';
    $class ="";

    if ($_POST['navs'] == '') {
		$search = $_POST['data_search'];
        $class ='class="font-weight-bold"';
        if ($_POST['type_search'] == 1) {
            $query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$complete_query_search = "((R.code_invoice = '$search') OR (CONCAT_WS(' ', C.name_client, C.last_name, C.mother_lastname) LIKE  '$search%' OR
            CONCAT_WS(' ', C.last_name, C.mother_lastname, C.name_client) LIKE  '$search%')) AND ((D.date_arrival >= '$new_date') OR (D.date_exit >= '$new_date'))";
        }
        if ($_POST['type_search'] == 2) {
			$query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$complete_query_search = " A.name_agency LIKE '$search%' AND ((D.date_arrival >= '$new_date') OR (D.date_exit >= '$new_date'))";
        }
        if ($_POST['type_search'] == 3) {
			$query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$f_llegada = $_POST['f_llegada'];
			$complete_query_search = " ((D.date_arrival BETWEEN '$f_llegada' AND '$f_llegada') or (D.date_exit BETWEEN '$f_llegada' AND '$f_llegada')) ";
        }
        if ($_POST['type_search'] == 4) {
			$query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$f_llegada = $_POST['f_llegada'];
			$f_salida = $_POST['f_salida'];
			$complete_query_search = "((D.date_arrival BETWEEN '$f_llegada' AND '$f_salida') or (D.date_exit BETWEEN '$f_llegada' AND '$f_salida')) ";
        }
		$query = "SELECT * FROM reservations AS R INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
		INNER JOIN clients AS C ON C.id_client = R.id_client  WHERE $complete_query_search ;";
		$query_count = "SELECT count(*) as total FROM reservations AS R INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
		INNER JOIN clients AS C ON C.id_client = R.id_client  WHERE $complete_query_search ;";
            $result = mysqli_query($con, $query);
            $output = "";
            $output2 = "";
            $output3 = "";
            $newrole ='';
            $newoutput = '';
            $result_count = mysqli_query($con, $query_count);
            $fila = mysqli_fetch_assoc($result_count);
            if ($type_search != 0 || $type_search != '') {
                $output.="
                <div class='w-100 pb-2'>
                    <div class='row'>
                        <div class='col-lg-12 text-right'>
                            <a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_services' data-delay='.8s'><i class='fas fa-times'></i></a><br>
                        </div>
                    </div>
                </div>
                ";
                $output.="<h5>N.º de reservaciones de la busqueda: <strong>{$fila['total']}</strong></h5>";
            }
            if ($result) {	
                if (mysqli_num_rows($result) > 0) {
                    $output.="
                    <p class='text-table'>Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                <thead>
                                    <tr>
                                        <th class='hidden-sm'>ID</th>
                                        <th>Cliente</th>
                                        <th class='hidden-sm'>Destino</th>
                                        <th>Servicio</th>
                                        <th>Traslado</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.= "
                                        <th>Total</th>";
                    }
                    if ($id_role == 1) {
                        $output.="
                                        <th>Metodo pago</th>
                                        <th>Estado</th>";
                    }
                        $output.="
                                        <th>Proveedor</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.="
                                        <th>REP</th>
                                        <th></th>
                                        <th></th>";
                    }
                    if ($id_role == 1) {
                        $output.= "
                                        <th></th>";
                    }
                    $output.="
                                        </tr>
                                </thead>
                                <tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                            $newstatus = '';
                            $newnamepay = '';
                            $newpayment = '';
                            $total = '';
                            $currency = '';
                            $newtype = '';
                            $set_provider = "";
                            $arrival_or_exit = "";
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
                            switch ($row['status_reservation']) {
                                case 'RESERVED':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'COMPLETED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'NO SHOW':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'CANCELLED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'REFUNDED':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                    </select>";
                                    break;
                                default:
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                            }
                            switch ($row['method_payment']) {
                                case 'oxxo':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'transfer':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'airport':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'paypal':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'card':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'deposit':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                    </select>";
                                    break;
                            }
                            $total = $row['total_cost'] - $row['agency_commision'];
                            if ($row['type_transfer'] == 'RED') {
                                $total = ($row['total_cost'] - $row['agency_commision']) / 2;
                            }
                            if ($row['type_currency'] == 'mx') {
                                $currency = 'MXN';
                            }
                            if ($row['type_currency'] == 'us') {
                                $currency = 'USD';
                            }
                            if ($row['type_currency'] == 'pt') {
                                $currency = 'USD';
                            }
                            //Provider
                            $provider = getProvideBySaleId($row['id_reservation'], 'A', $con);
                            $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                            //REP
                            $rep = getRepBySaleId($row['id_reservation'], 'A', $con);
                            $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
    
                            $newidreserva = MD5($row['id_reservation']);
    
                            $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                            
                            $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';
                            
                            if ($row['status_reservation'] == 'CANCELLED') {
                                $set_provider = "CANCELADO";
                            }else{
                                $set_provider = "<a href='#' id='select_provider' datare='{$row['id_reservation']}'  data='{$newprovider}' datatag='entrada' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> {$newprovider}</a>";
                            }
                            $newtime = "";
                            if ($row['date_arrival'] == $today || ($row['date_arrival'] >= $today && $today < $row['date_exit'])) {
                                $output.='<tr> 
                                            <td colspan = "17" style="background: #3F80EA; color: #fff; font-size 12px !important">L L E G A D A</td>
                                        </tr>';
                                $arrival_or_exit = $row['date_arrival'];
                                $newtime = substr($row['time_arrival'], 0, 5);
                            }
                            if ($row['date_exit'] == $today || ($row['date_exit'] >= $today && $today > $row['date_arrival'])) {
                                $output.='<tr> 
                                            <td colspan = "17" style="background: #495057; color: #fff;">S A L I D A </td>
                                        </tr>';
                                        $arrival_or_exit = $row['date_exit'];
                                        $newtime = substr($row['time_exit'], 0, 5);
                            }
                            if ($row['date_arrival'] < $today && $row['date_exit'] < $today) {
                                $output.='<tr> 
                                            <td colspan = "17" style="background: #A4A4A4; color: #fff;">P A S A D O </td>
                                        </tr>';
                                        $arrival_or_exit = $row['date_arrival'];
                                        $newtime = substr($row['time_arrival'], 0, 5);
                            }
                            $output.="<tr reserva-id='{$row['id_reservation']}'>
                                    <td class='font-weight-bold' class='hidden-sm'>{$row['code_invoice']}</td>
                                    <td class='font-weight-bold'>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
                                    <td class='hidden-sm'>{$row['transfer_destiny']}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$newtype}</td>
                                    <td class='font-weight-bold'>{$arrival_or_exit}</td>
                                    <td class='font-weight-bold'>{$newtime} Hrs</td>
                                    ";
                            if ($id_role == 1 || $id_role == 2) {
                            $output.="<td>$ $total $currency</td>";
                            }
                            if ($id_role == 1) {
                            $output.="
                                    <td>{$newpayment}</td>
                                    <td>{$newstatus}</td>";
                            }
                            $output.="
                                    <td>{$set_provider}</td>
                                    ";
                            if ($id_role == 1 || $id_role == 2) {
                                $output.="
                                
                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='{$newrep}' datatag='entrada' datare='{$row['id_reservation']}' dataservice='A' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeactionrep}' data-target='#repModal'> {$newrep}</a></td>
                                <td class='text-center'>
                                    <a   href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                </td>
                                <td class='text-center'>
                                    <a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=1' target='_blank' id='reservation-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
                                </td>";
                            }
                            if ($id_role == 1) {
                                $output.="
                                        <td class='text-center'>
                                            <a href='#' id='btn_register_pay' title='Conciliar' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-dollar-sign'></i></a>
                                        </td>
                                ";
                            }
                            $output.="
                            </tr>";
                    } 
                    $output.="</tbody>
                        </table>";
        
                    echo $output;
        
                }else{
                    $output.="<div class='pt-3'><p>No se encontro ninguna reservación registrada</p><div>";
                    echo $output;
                }
            }else{
                $output.="<div class='pt-3'><p>No se encontro ninguna reservación registrada</p></div>";
                echo $output;
            }
    }else{
        if ($navs == 'LLEGADA') {
            $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation) WHERE D.date_arrival >= '$today' AND D.date_arrival <= '$today' ORDER BY D.date_arrival ASC, D.time_arrival ASC LIMIT 2;";
            $result = mysqli_query($con, $query);
            $output = "";
            $output2 = "";
            $output3 = "";
            $newrole ='';
            $newoutput = '';
            if ($result) {	
                if (mysqli_num_rows($result) > 0) {
                    $output.="
                    <br/>
                    <h6>L L E G A D A S</h6>
                    <p class='text-table'>Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                    <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                <thead>
                                    <tr>
                                        <th class='hidden-sm'>ID</th>
                                        <th>Cliente</th>
                                        <th class='hidden-sm'>Destino</th>
                                        <th>Servicio</th>
                                        <th>Traslado</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.="
                                        <th>Total</th>";
                    }
                    if ($id_role == 1) {
                        $output.="
                                        <th>Metodo pago</th>
                                        <th>Estado</th>";
                    }
                        $output.="
                                        <th>Proveedor</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.="
                                        <th>REP</th>
                                        <th></th>
                                        <th></th>";
                    }
                    if ($id_role == 1) {
                        $output.= "
                                        <th></th>";
                    }
                    $output.="
                                        </tr>
                                </thead>
                                <tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                            $newstatus = '';
                            $newnamepay = '';
                            $newpayment = '';
                            $total = '';
                            $currency = '';
                            $newtype = '';
                            $set_provider = "";
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
                            switch ($row['status_reservation']) {
                                case 'RESERVED':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'COMPLETED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'NO SHOW':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'CANCELLED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'REFUNDED':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                    </select>";
                                    break;
                                default:
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                            }
                            switch ($row['method_payment']) {
                                case 'oxxo':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'transfer':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'airport':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'paypal':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'card':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'deposit':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                    </select>";
                                    break;
                            }
                            $total = $row['total_cost'] - $row['agency_commision'];
                            if ($row['type_transfer'] == 'RED') {
                                $total = ($row['total_cost'] - $row['agency_commision']) / 2;
                            }
                            if ($row['type_currency'] == 'mx') {
                                $currency = 'MXN';
                            }
                            if ($row['type_currency'] == 'us') {
                                $currency = 'USD';
                            }
                            if ($row['type_currency'] == 'pt') {
                                $currency = 'USD';
                            }
                            //Provider
                            $provider = getProvideBySaleId($row['id_reservation'], 'A', $con);
                            $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                            //REP
                            $rep = getRepBySaleId($row['id_reservation'], 'A', $con);
                            $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
    
                            $newidreserva = MD5($row['id_reservation']);
    
                            $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                            
                            $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';
                            
                            if ($row['status_reservation'] == 'CANCELLED') {
                                $set_provider = "CANCELADO";
                            }else{
                                $set_provider = "<a href='#' id='select_provider' datare='{$row['id_reservation']}'  data='{$newprovider}' datatag='entrada' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> {$newprovider}</a>";
                            }
                            $newtime = substr($row['time_arrival'], 0, 5);
                            $output.="<tr reserva-id='{$row['id_reservation']}'>
                                    <td class='hidden-sm'>{$row['code_invoice']}</td>
                                    <td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
                                    <td class='hidden-sm'>{$row['transfer_destiny']}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['date_arrival']}</td>
                                    <td>{$newtime} Hrs</td>";    
                            if ($id_role == 1 || $id_role == 2) {
                            $output.="
                                    <td>$ $total $currency</td>";
                            }
                            if ($id_role == 1) {
                            $output.="
                                    <td>{$newpayment}</td>
                                    <td>{$newstatus}</td>";
                            }
                            $output.="
                                    <td>{$set_provider}</td>
                                    ";
                                    
                            if ($id_role == 1 || $id_role == 2) {
                                $output.="
                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='{$newrep}' datatag='entrada' datare='{$row['id_reservation']}' dataservice='A' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeactionrep}' data-target='#repModal'> {$newrep}</a></td>
                                <td class='text-center'>
                                    <a   href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                </td>
                                <td class='text-center'>
                                    <a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=1' target='_blank' id='reservation-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
                                </td>";
                            }
                            if ($id_role == 1) {
                                $output.="
                                        <td class='text-center'>
                                            <a href='#' id='btn_register_pay' title='Conciliar' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-dollar-sign'></i></a>
                                        </td>
                                ";
                            }
                            $output.="
                            </tr>";
                    } 
                    $output.="</tbody>
                        </table>";
        
                    echo $output;
        
                }else{
                    $output.="<div class='pt-3'><p>No se encontro ninguna reservación registrada</p><div>";
                    echo $output;
                }
            }else{
                $output.="<div class='pt-3'><p>No se encontro ninguna reservación registrada</p></div>";
                echo $output;
            }
        }
        if($navs == 'SALIDA'){
            $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation) WHERE D.date_exit >= '$today' AND D.date_exit <= '$today' ORDER BY D.date_exit ASC, D.time_exit ASC;";
            $result = mysqli_query($con, $query);
            $output = "";
            $output2 = "";
            $output3 = "";
            $newrole ='';
            $newoutput = '';
            if ($result) {	
                if (mysqli_num_rows($result) > 0) {
                    $output.="
                    <br/>
                    <h6>S A L I D A S</h6>
                    <p class='text-table'>Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                    <table class='table table-hover table-striped  table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Destino</th>
                                        <th>Servicio</th>
                                        <th>Traslado</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.="
                                        <th>Total</th>";
                    }
                    if ($id_role == 1) {
                        $output.="
                                        <th>Metodo pago</th>
                                        <th>Estado</th>";
                    }
                        $output.="
                                        <th>Proveedor</th>";
                    if ($id_role == 1 || $id_role == 2) {
                        $output.=" 
                                        <th>REP</th>
                                        <th></th>
                                        <th></th>
                                        ";
                    }
                    if ($id_role == 1 ) {
                        $output.=" 
                                        <th></th>";
                    }
                    $output.="
                                        </tr>
                                </thead>
                                <tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                            $newstatus = '';
                            $newnamepay = '';
                            $newpayment = '';
                            $total = '';
                            $currency = '';
                            $newtype = '';
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
                            switch ($row['status_reservation']) {
                                case 'RESERVED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'COMPLETED':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'NO SHOW':
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'CANCELLED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                                case 'REFUNDED':
                                    $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                    </select>";
                                    break;
                                default:
                                    $newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                        <option value='RESERVED'>RESERVED</option>
                                        <option value='COMPLETED'>COMPLETED</option>
                                        <option value='NO SHOW'>NO SHOW</option>
                                        <option value='CANCELLED'>CANCELLED</option>
                                        <option value='REFUNDED'>REFUNDED</option>
                                    </select>";
                                    break;
                            }
                            switch ($row['method_payment']) {
                                case 'oxxo':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'transfer':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'airport':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'paypal':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='card'>TARJETA</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'card':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='deposit'>DEPOSITO</option>
                                    </select>";
                                    break;
                                case 'deposit':
                                    $newpayment .= "<select class='form-control-sm' name='new_method_payment' data='{$row['id_reservation']}' id='new_method_payment'>
                                        <option value='{$row['method_payment']}'>{$newnamepay}</option>
                                        <option value='oxxo'>OXXO</option>
                                        <option value='transfer'>TRANSFERENCIA</option>
                                        <option value='airport'>AEROPUERTO</option>
                                        <option value='paypal'>PAYPAL</option>
                                        <option value='card'>TARJETA</option>
                                    </select>";
                                    break;
                            }
                            $total = $row['total_cost'] - $row['agency_commision'];
                            if ($row['type_transfer'] == 'RED') {
                                $total = ($row['total_cost'] - $row['agency_commision']) / 2;
                            }
                            if ($row['type_currency'] == 'mx') {
                                $currency = 'MXN';
                            }
                            if ($row['type_currency'] == 'us') {
                                $currency = 'USD';
                            }
                            if ($row['type_currency'] == 'pt') {
                                $currency = 'USD';
                            }
                            //Provider
                            $provider = getProvideBySaleId($row['id_reservation'], 'D', $con);
                            $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                            //REP
                            $rep = getRepBySaleId($row['id_reservation'], 'D', $con);
                            $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];
                            $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                            $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';
                            $newidreserva = MD5($row['id_reservation']);
                            $newtime = substr($row['time_exit'], 0, 5);
                            $output.="<tr reserva-id='{$row['id_reservation']}'>
                                    <td>{$row['code_invoice']}</td>
                                    <td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
                                    <td>{$row['transfer_destiny']}</td>
                                    <td>{$row['type_service']}</td>
                                    <td>{$newtype}</td>
                                    <td>{$row['date_exit']}</td>
                                    <td>{$newtime} Hrs</td>";
                            if ($id_role == 1 || $id_role == 2) {
                                $output.="
                                    <td>$ $total $currency</td>";
                            }
                            if ($id_role == 1) {
                                $output.="
                                    <td>{$newpayment}</td>
                                    <td>{$newstatus}</td>
                                ";
                            }
                                $output.="
                                    <td><a href='#' id='select_provider' datare='{$row['id_reservation']}'  data='{$newprovider}' datatag='salida' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> {$newprovider}</a></td>
                                    ";
                                    
                            if ($id_role == 1 || $id_role == 2) {
                                $output.="
                                        <td><a href='#' id='select_rep' data-toggle='modal'  datarep='{$newrep}' datare='{$row['id_reservation']}' datatag='salida' dataservice='D' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeactionrep}' data-target='#repModal'> {$newrep}</a></td>
                                        <td class='text-center'>
                                            <a id='amenity-delete'  href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' class='amenity- '><i class='far fa-eye'></i></a>
                                        </td> 
                                        <td class='text-center'>
                                            <a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=1' target='_blank' id='reservation-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
                                        </td>";
                            }
                            if ($id_role == 1) {
                                $output.="
                                        <td class='text-center'>
                                            <a href='#' id='amenity-delete' title='Conciliar' data-toggle='modal' data-target='#exampleModal' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                        </td>";
                            }
                            $output.="
                            </tr>";
                    } 
                    $output.="</tbody>
                        </table>";
        
                    echo $output;
        
                }else{
                    $output.="<br><p>No se encontro ninguna reservación registrada</p>";
                    echo $output;
                }
            }else{
                $output.="<br><p>No se encontro ninguna reservación registrada</p>";
                echo $output;
            }
        }
    }

    
    function getProvideBySaleId($sale, $service, $con){
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

     function getRepBySaleId($sale, $service, $con){
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

?>