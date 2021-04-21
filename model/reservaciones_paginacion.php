<?php

	// Connect database 

	require_once('../config/conexion.php');

	$limit = 500;

	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	if (isset($_POST['navs'])) {
		$navs = $_POST['navs'];
	}else{
		$navs = "RESERVED";
	}
	
	$today = date('Y');
	$new_date = $today.'-01-01';
	$offset = ($page_no-1) * $limit;
	$query = "SELECT * FROM (clients AS C 
	INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
	INNER JOIN agencies AS A ON R.id_agency = A.id_agency) WHERE (R.status_reservation LIKE '$navs') AND (R.date_register_reservation >= '$new_date') ORDER BY R.id_reservation  DESC ";
	
	$query_count = "SELECT count(*) as total FROM (clients AS C 
	INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
	INNER JOIN agencies AS A ON R.id_agency = A.id_agency) WHERE (R.status_reservation LIKE '$navs') AND (R.date_register_reservation >= '$new_date') ORDER BY R.id_reservation  DESC ";
	
	$result = mysqli_query($con, $query);
	$output = "";
	$output2 = "";
	$output3 = "";
	$newrole ='';
	$newoutput = '';
	if ($result) {	
		if (mysqli_num_rows($result) > 0) {
			$result_count = mysqli_query($con, $query_count);
			$fila = mysqli_fetch_assoc($result_count);
			$output.="
				<h5>N.º de reservaciones: <strong>{$fila['total']}</strong></h5>
				<p class='text-table'>Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
				<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
						<thead>
							<tr>
								<th>ID</th>
								<th>Cliente</th>
								<th>Servicio</th>
								<th>Traslado</th>
								<th>Adultos</th>
								<th>Niños</th>
								<th>Total pagado</th>
								<th>Metodo pago</th>
								<th>Estado</th>
								<th>Fecha de Registro</th>
								<th>Agencia</th>
								<th></th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
					$newtype = '';
					$newstatus = '';
					$newnamepay = '';
					$newpayment = '';
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
							$newstatus .= "<select class='form-control-sm' name='new_status_reservation' data='{$row['id_reservation']}' id='new_status_reservation'>
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
					$newidreserva = MD5($row['id_reservation']);
					$output.="<tr reserva-id='{$row['id_reservation']}'>
							<td>{$row['code_invoice']}</td>
							<td>{$row['name_client']}</td>
							<td>{$row['type_service']}</td>
							<td>$newtype</td>
							<td>{$row['number_adults']}</td>
							<td>{$row['number_children']}</td>
							<td>$ {$row['total_cost']}</td>
							<td>{$newpayment}</td>
							<td>{$newstatus}</td>
							<td>{$row['date_register_reservation']}</td>
							<td>{$row['name_agency']}</td>
							<td class='text-center'>
								<a id=''  href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' class='amenity- '><i class='far fa-eye'></i></a>
							</td>
							<td class='text-center'>
								<a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=1' target='_blank' id='reservation-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
							</td>
							<td class='text-center'>
								<a href='#' id='amenity-delete' title='Conciliar' data-toggle='modal' data-target='#exampleModal' class=' amenity-delete '><i class='fas fa-dollar-sign'></i></a>
							</td>
					</tr>";
			} 
			$output.="</tbody>
				</table>";

			// $sql = "SELECT * FROM (clients AS C 
			// INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
			// INNER JOIN agencies AS A ON R.id_agency = A.id_agency)WHERE R.status_reservation LIKE '$navs' order BY R.id_reservation desc;";

			// $records = mysqli_query($con, $sql);
			// $totalRecords = mysqli_num_rows($records);
			// $totalPage = ceil($totalRecords/$limit);
			// $output.="<ul class='pagination justify-content' style='margin:20px 0'>";
			// for ($i=1; $i <= $totalPage ; $i++) { 
			// if ($i == $page_no) {
			// 	$active = "active";
			// }else{
			// 	$active = "";
			// }
			// 	$output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
			// }
			// $output .= "</ul>";

			echo $output;

		}else{
			$output.="<p>No se encontro ninguna reservación registrada</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ninguna reservación registrada</p>";
		echo $output;
	}

?>