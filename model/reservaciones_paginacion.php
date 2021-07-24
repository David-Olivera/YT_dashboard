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
	}
	$today = date('Y');
	$new_date = $today.'-01-01';
	$offset = ($page_no-1) * $limit;
	$query ="";
	$query_count = "";
	$complete_query_search ="";
	$type_search = $_POST['type_search'];
	if ($_POST['navs'] == '') {
		$search = $_POST['data_search'];
		if ($_POST['type_search'] == 1) {
			$query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$complete_query_search = " R.code_invoice LIKE '%$search%' OR (CONCAT_WS(' ', C.name_client, C.last_name, C.mother_lastname) LIKE  '%$search%' OR CONCAT_WS(' ', C.last_name, C.mother_lastname, C.name_client) LIKE  '%$search%')";
		}
		if ($_POST['type_search'] == 2) {
			$query_p = "SET SESSION SQL_BIG_SELECTS = 1";
			$result_p = mysqli_query($con, $query_p);
			$complete_query_search = " A.name_agency LIKE '$search%'";
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
		INNER JOIN clients AS C ON C.id_client = R.id_client INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE $complete_query_search ;";
		$query_count = "SELECT count(*) as total FROM reservations AS R INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation
		INNER JOIN clients AS C ON C.id_client = R.id_client INNER JOIN agencies AS A ON R.id_agency = A.id_agency WHERE $complete_query_search ;";
	}else{
		$query = "SELECT * FROM clients AS C 
		INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation WHERE (R.status_reservation LIKE '$navs') AND (R.date_register_reservation >= '$new_date') ORDER BY R.id_reservation  DESC ";
		$query_count = "SELECT count(*) as total FROM clients AS C 
		INNER JOIN reservations AS R ON C.id_client = R.id_client INNER JOIN reservation_details AS D ON R.id_reservation = D.id_reservation WHERE (R.status_reservation LIKE '$navs') AND (R.date_register_reservation >= '$new_date') ORDER BY R.id_reservation  DESC ";
	}
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
			if ($type_search != 0 || $type_search != '') {
				$output.="
				<div class='w-100 pb-2'>
					<div class='row'>
						<div class='col-lg-12 text-right'>
							<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_reservations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
						</div>
					</div>
				</div>
				";
				$output.="<h5>N.º de reservaciones de la busqueda: <strong>{$fila['total']}</strong></h5>";
			}else{
				$output.="<h5>N.º de reservaciones: <strong>{$fila['total']}</strong></h5>";
			}
			$output.="
				<p class='text-table'>Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
				<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
						<thead>
							<tr>
								<th class='hidden-sm'>ID</th>
								<th>Cliente</th>
								<th>Servicio</th>
								<th>Traslado</th>
								<th>Adultos</th>
								<th>Total pagado</th>
								<th>Metodo pago</th>
								<th>Estado</th>
								<th class='hidden-sm'>Fecha de Registro</th>
								<th class='hidden-sm'>Agencia</th>
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
							
						case 'a_pa':
							$newnamepay = "SITIO WEB - PAGO AL ABORDAR";
							break;
								
						case 'a_transfer':
							$newnamepay = "SITIO WEB - TRANSFERENCIA";
							break;
							
						case 'a_paypal':
							$newnamepay = "SITIO WEB - PAYPAL";
							break;
							
						case 'a_card':
							$newnamepay = "SITIO WEB - TARJETA";
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
							$newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'NO SHOW':
							$newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'CANCELLED':
							$newstatus .= "<select class='form-control-sm' name='new_status_reservation' transfer={$row['type_transfer']} data='{$row['id_reservation']}' code='{$row['code_invoice']}' id='new_status_reservation'>
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
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
								<option value='deposit'>DEPOSITO</option>
							</select>";
							break;
						case 'transfer':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
								<option value='deposit'>DEPOSITO</option>
							</select>";
							break;
						case 'airport':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
								<option value='deposit'>DEPOSITO</option>
							</select>";
							break;
						case 'paypal':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment'  code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='card'>TARJETA</option>
								<option value='deposit'>DEPOSITO</option>
							</select>";
							break;
						case 'card':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='deposit'>DEPOSITO</option>
							</select>";
							break;
						case 'deposit':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
							</select>";
							break;
							
						case 'a_pa':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
							</select>";
							break;
							
						case 'a_transfer':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
							</select>";
							break;
							
						case 'a_paypal':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
							</select>";
							break;
							
						case 'a_card':
							$newpayment .= "<select class='form-control-sm' name='new_method_payment' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_method_payment'>
								<option value='{$row['method_payment']}'>{$newnamepay}</option>
								<option value='oxxo'>OXXO</option>
								<option value='transfer'>TRANSFERENCIA</option>
								<option value='airport'>AEROPUERTO</option>
								<option value='paypal'>PAYPAL</option>
								<option value='card'>TARJETA</option>
							</select>";
							break;
					}
					$new_nameagency = "";
                    if($row['id_agency'] || $row['of_the_agency']){
                        $id_ag = "";
                        if ($row['id_agency'] == $row['of_the_agency'] || $row['of_the_agency'] == '') {
                            $id_ag = $row['id_agency'];
                        }else{
                            $id_ag = $row['of_the_agency'];
                        }
                        $query_agency = "SELECT * FROM agencies WHERE id_agency like $id_ag;";
                        $result_a =  mysqli_query($con, $query_agency);
                        if ($result_a) {
                            $ins = mysqli_fetch_object($result_a);
							if ($ins) {
								$new_nameagency = $ins->name_agency;
							}
                        }
                    }
					$newidreserva = MD5($row['id_reservation']);
					$output.="<tr reserva-id='{$row['id_reservation']}'>
							<td class='hidden-sm'>{$row['code_invoice']}</td>
							<td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
							<td>{$row['type_service']}</td>
							<td>$newtype</td>
							<td>{$row['number_adults']}</td>
							<td>$ {$row['total_cost']}</td>
							<td>{$newpayment}</td>
							<td>{$newstatus}</td>
							<td class='hidden-sm'>{$row['date_register_reservation']}</td>
							<td class='hidden-sm'>{$new_nameagency}</td>
							<td class='text-center'>
								<a id=''  href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' title='ver detalles' class='amenity- '><i class='far fa-eye'></i></a>
							</td>
							<td class='text-center'>
								<a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=1' target='_blank' id='reservation-edit' title='Editar' class=' amenity-edit ' ><i class='fas fa-edit' ></i></a>
							</td>
							<td class='text-center'>
								<a href='#' id='btn_register_pay' title='Conciliar' data-toggle='modal' data-target='#exampleModal' ><i class='fas fa-dollar-sign'></i></a>
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
			$output.="
			<div class='w-100 pb-2'>
				<div class='row'>
					<div class='col-lg-12 text-right'>
						<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_reservations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
					</div>
				</div>
			</div>
			";
			$output.="<p>No se encontro ninguna reservación registrada</p>";
			echo $output;
		}
	}else{
		$output.="
		<div class='w-100 pb-2'>
			<div class='row'>
				<div class='col-lg-12 text-right'>
					<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_reservations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
				</div>
			</div>
		</div>
		";
		$output.="<p>No se encontro ninguna reservación registrada</p>";
		echo $output;
	}

?>