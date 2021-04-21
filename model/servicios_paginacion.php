<?php

	// Connect database 

	require_once('../config/conexion.php');

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
    if ($navs == 'LLEGADA') {
        $query = "SELECT * FROM (clients AS C INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation) WHERE D.date_arrival >= '$today' AND D.date_arrival <= '$today' ORDER BY D.date_arrival ASC, D.time_arrival ASC ;";
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
                                
                                    <th>ID</th>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Destino</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Total</th>
                                    <th>Metodo pago</th>
                                    <th>Estado</th>
                                    <th>Proveedor</th>
                                    <th>REP</th>
                                    <th></th>
                                    <th></th>
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
                                $newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                    <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                    <option value='COMPLETED'>COMPLETED</option>
                                    <option value='NO SHOW'>NO SHOW</option>
                                    <option value='CANCELLED'>CANCELLED</option>
                                    <option value='REFUNDED'>REFUNDED</option>
                                </select>";
                                break;
                            case 'COMPLETED':
                                $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
                                    <option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
                                    <option value='RESERVED'>RESERVED</option>
                                    <option value='NO SHOW'>NO SHOW</option>
                                    <option value='CANCELLED'>CANCELLED</option>
                                    <option value='REFUNDED'>REFUNDED</option>
                                </select>";
                                break;
                            case 'NO SHOW':
                                $newstatus .= "<select class='form-control-sm ' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
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
                                $newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
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
                        $provider = getProvideBySaleId($row['id_reservation'], 'A', $con);
                        $newprovider = $provider == null ? 'S/A' : $provider['name_provider'];
                        //REP
                        $rep = getRepBySaleId($row['id_reservation'], 'A', $con);
                        $newrep = $rep == null ? 'S/A' : $rep['name_receptionist'];

                        $newidreserva = MD5($row['id_reservation']);

                        $newtypeaction = $provider == null ? 'insert_provider' : 'update_provider';
                        
                        $newtypeactionrep = $rep == null ? 'insert_rep' : 'update_rep';
                        
                        $newtime = substr($row['time_arrival'], 0, 5);
                        $output.="<tr reserva-id='{$row['id_reservation']}'>
                                <td>{$row['id_reservation']}</td>
                                <td>{$row['code_invoice']}</td>
                                <td>{$row['name_client']}</td>
                                <td>{$row['transfer_destiny']}</td>
                                <td>{$row['type_service']}</td>
                                <td>{$newtype}</td>
                                <td>{$row['date_arrival']}</td>
                                <td>{$newtime} Hrs</td>
                                <td>$ $total $currency</td>
                                <td>{$newpayment}</td>
                                <td>{$newstatus}</td>
                                <td><a href='#' id='select_provider' datare='{$row['id_reservation']}'  data='{$newprovider}' datatag='entrada' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeaction}' dataservice='A' data-toggle='modal' data-target='#providerModal'> {$newprovider}</a></td>
                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='{$newrep}' datatag='entrada' datare='{$row['id_reservation']}' dataservice='A' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeactionrep}' data-target='#repModal'> {$newrep}</a></td>
                                <td class='text-center'>
                                    <a   href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}' target='_blank' title='ver detalles' id='view_details'><i class='far fa-eye'></i></a>
                                </td>
                                <td class='text-center'>
                                    <a href='#' id='amenity-delete' title='Conciliar' data-toggle='modal' data-target='#exampleModal' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                </td>
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
    }else if($navs == 'SALIDA'){
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
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Destino</th>
                                    <th>Servicio</th>
                                    <th>Traslado</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Total</th>
                                    <th>Metodo pago</th>
                                    <th>Estado</th>
                                    <th>Proveedor</th>
                                    <th>REP</th>
                                    <th></th>
                                    <th></th>
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
                                <td>{$row['id_reservation']}</td>
                                <td>{$row['code_invoice']}</td>
                                <td>{$row['name_client']}</td>
                                <td>{$row['transfer_destiny']}</td>
                                <td>{$row['type_service']}</td>
                                <td>{$newtype}</td>
                                <td>{$row['date_exit']}</td>
                                <td>{$newtime} Hrs</td>
                                <td>$ $total $currency</td>
                                <td>{$newpayment}</td>
                                <td>{$newstatus}</td>
                                <td><a href='#' id='select_provider' datare='{$row['id_reservation']}'  data='{$newprovider}' datatag='salida' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeaction}' dataservice='D' data-toggle='modal' data-target='#providerModal'> {$newprovider}</a></td>
                                <td><a href='#' id='select_rep' data-toggle='modal'  datarep='{$newrep}' datare='{$row['id_reservation']}' datatag='salida' dataservice='D' datainvoice='{$row['code_invoice']}' dataaction='{$newtypeactionrep}' data-target='#repModal'> {$newrep}</a></td>
                                <td class='text-center'>
                                    <a id='amenity-delete'  href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' class='amenity- '><i class='far fa-eye'></i></a>
                                </td>
                                <td class='text-center'>
                                    <a href='#' id='amenity-delete' title='Conciliar' data-toggle='modal' data-target='#exampleModal' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
                                </td>
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