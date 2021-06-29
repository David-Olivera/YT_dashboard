<?php

	// Connect database 

	require_once('../config/conexion.php');

	$limit = 100;
	$type = $_POST['type'];
	$code = "";
    $today = date('Y-m-d');
    date_default_timezone_set('America/Cancun');
	$search = 0;
	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	$query = "";
	$offset = ($page_no-1) * $limit;
	$complete_query_search = "";
	$tota_agency= "";
	$query_total = "";
	if (($_POST['search'] && $_POST['type_search']) || ($_POST['type_search'] && $_POST['search'] && $_POST['f_llegada'] && $_POST['f_salida'])) {
		$text_search = $_POST['search'];
		if ($_POST['type_search'] == 1) {
			$complete_query_search = "WHERE code_invoice = '$text_search' AND";
		}
		if ($_POST['type_search'] == 2) {
			$complete_query_search = "WHERE CONCAT_WS(' ', C.name_client, C.last_name, C.mother_lastname) LIKE '$text_search%' OR
            CONCAT_WS(' ', C.last_name, C.mother_lastname, C.name_client   ) LIKE  '$text_search%' AND";
		}
		if ($_POST['type_search'] == 3) {
			$date_en = $_POST['f_llegada'];
			$date_ex = $_POST['f_salida'];
			if ($text_search == 'Todos') {
				$complete_query_search = " WHERE ((RD.date_arrival >= '$date_en' AND RD.date_arrival <= '$date_ex') OR (RD.date_exit >= '$date_en' AND RD.date_exit <= '$date_ex')) AND ";
			}else{
				$complete_query_search = " WHERE (((RD.date_arrival >= '$date_en' AND RD.date_arrival <= '$date_ex') OR (RD.date_exit >= '$date_en' AND RD.date_exit <= '$date_ex')) and (R.of_the_agency = $text_search)) AND";
				$query_total =" SELECT SUM(total_cost) as total FROM reservation_details as RD INNER JOIN reservations as R on RD.id_reservation = R.id_reservation   INNER JOIN conciliation AS CN ON R.id_reservation = CN.id_reservation  WHERE R.id_agency = $text_search AND CN.`status` = $type;";
				$result_total = mysqli_query($con, $query_total);
				$ins_total = mysqli_fetch_object($result_total);
				$tota_agency = $ins_total->total;
			}
		}
		if ($_POST['type_search'] == 4) {
			$complete_query_search = "INNER JOIN conciliation_docs AS CD on CN.id_conciliation = CD.id_conciliation WHERE CD.conciliation_multiple = '$text_search' AND";
		}
		$search = 1;
		$query = "SELECT * FROM clients AS C INNER JOIN reservations AS R on C.id_client = R.id_client INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation 
        INNER JOIN conciliation AS CN ON R.id_reservation = CN.id_reservation
        $complete_query_search  R.status_reservation NOT IN('CANCELLED') AND CN.`status` = $type  ORDER BY R.id_agency  DESC LIMIT $offset, $limit";
	
	}else{
        $query = "SELECT * FROM clients AS C INNER JOIN reservations AS R on C.id_client = R.id_client INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation 
        INNER JOIN conciliation AS CN ON R.id_reservation = CN.id_reservation
        WHERE ((RD.date_arrival BETWEEN '$today' AND '$today') or (RD.date_exit BETWEEN '$today' AND '$today')) AND R.status_reservation NOT IN('CANCELLED') AND CN.`status` = $type ORDER BY R.id_agency  DESC LIMIT $offset, $limit";
	
    }
	$result = mysqli_query($con, $query);
	$output = "";
	$newrole ='';
	$newoutput = '';
	$text_msg ="";

	if ($result) {	
		if (mysqli_num_rows($result) > 0) {
			
			if ($search == 1 ) {
				if($_POST['type_search'] == 1){
					$text_msg = "<p class='mb-0'><small>Resultados encontrados con el ID <strong>$text_search</strong></small> </p>";
				}
				if($_POST['type_search'] == 2){
					$text_msg = "<p class='mb-0'><small>Resultados encontrados con el Nombre <strong>$text_search</strong> </small></p>";
				}
				if($_POST['type_search'] == 3){
					if ($text_search == 'Todos') {
						$text_msg = "<p class='mb-0'><small>Resultados encontrados entre las fechas $date_en al $date_ex<small></p>";
					}else{
						$text_msg = "<p class='mb-0'><small>Resultados encontrados con la Agencia <strong>$text_search</strong> entre las fechas $date_en al $date_ex</p><p class='mb-0'>Saldo a deber <strong>$ $tota_agency</strong><small></p>";
					}
				}
				if($_POST['type_search'] == 4){
					$text_msg = "<p class='mb-0'><small>Resultados encontrados con el CODE de la CM <strong>$text_search</strong> </small></p>";
				}
				if ($type == 0) {
					$output.="
					<div class='w-100 pb-2'>
						<div class='row'>
							<div class='col-lg-11'>
								$text_msg
							</div>
							<div class='col-lg-1 text-right'>
								<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
							</div>
						</div>
					</div>
					";
	
				}else{
					
					$output.="
					<div class='w-100 pb-2'>
						<div class='row'>
							<div class='col-lg-11'>
								$text_msg
							</div>
							<div class='col-lg-1 text-right'>
								<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations_con' data-delay='.8s'><i class='fas fa-times'></i></a><br>
							</div>
						</div>
					</div>
					";
				}

			}
			$output.="
					
					<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaConciliaciones'>
						<thead class='m-3'>
							<tr >
								<th>ID</th>
								<th style='width:11%'  scope='col' class='hidden-sm'>Agencia</th>
								<th style='width:11%'  scope='col'>Cliente</th>
								<th>Traslado</th>
								<th class='hidden-sm'>Zona</th>
								<th>Servicio</th>
								<th class='hidden-sm'>Pax</th>
								<th>Método de Pago</th>
								<th>Tarifa Neta</th>
								<th class='hidden-sm'>Total</th>
								<th>CXC</th>
								<th>TM</th>
								<th>Estado</th>
								<th></th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
					$methodpayment = "";
					$new_amount_total ="";
					$class_conciliation = 'btn-outline-dark';
					$id_conci =  $row['id_conciliation'] ;
					$id_r = $row['id_reservation'];
					
					switch ($row['type_currency']) {
						case 'mx':
							$currency ="MXN";
							break;
						
						case 'us':
							$currency ="USD";
							break;
					}
					$query_total = "SELECT SUM(expense_amount) as total FROM expenses WHERE id_reservation = $id_r;";
					$result_t = mysqli_query($con, $query_total);
					if ($result_t) {
						$ins_t = mysqli_fetch_object($result_t);
						$new_amount_total = $row['total_cost'] - $ins_t->total;
					}
					$query_docs = "SELECT * FROM reservations AS R inner join conciliation AS C ON R.id_reservation = C.id_reservation INNER JOIN conciliation_docs as CD on C.id_conciliation = CD.id_conciliation WHERE CD.id_conciliation = $id_conci AND C.`status` = $type;";
                    $result_c =  mysqli_query($con, $query_docs);
					if ($result_c) {
						$ins = mysqli_fetch_object($result_c);
						$id_c = "";
						if (isset($ins->id_conciliation)) {
							$id_c = $ins->id_conciliation;
							if ($row['id_conciliation'] == $id_c) {
								$class_conciliation = "btn-outline-primary";
							}
						}
					}
					$query_depo = "SELECT SUM(expense_amount) as total FROM expenses WHERE id_reservation like $id_r and charge_type = 'D'; ";
					$result_de = mysqli_query($con, $query_depo);
					$new_total =0;
					$btn_deposit ="<button type='button' disabled='disabled' class='btn btn-outline-dark btn-sm'><i class='fas fa-dollar-sign'></i> </button>     
					";
					if ($result_de) {
						$ins_t = mysqli_fetch_object($result_de);
						if($ins_t->total < $row['total_cost']){
							$btn_deposit = "<a href='#' id='btn_add_deposit' reserva='{$row['id_reservation']}' total-cost='{$row['total_cost']}' currency='{$currency}'  conciliation='{$row['id_conciliation']}' code='{$row['code_invoice']}' class=' btn btn-outline-dark btn-sm' data-toggle='modal' data-target='#addPayModal'><i class='fas fa-dollar-sign'></i></a>";
						}
					}
                    //Transfer
                    switch ($row['type_transfer']) {
                        case 'RED':
                            $transfer = 'Redondo';
                            break;
                        case 'SEN/AH':
                            $transfer = 'Sencillo, Aeropuerto a Hotel';
                            break;
                        case 'SEN/HA';
                            $transfer = 'Sencillo, Hotel a Aeropuerto';
                            break;
                        case 'REDHH':
                            $transfer = 'Redondo, Hotel a Hotel';
                            break;
                        case 'SEN/HH':
                            $transfer = 'Sencillo, Hotel a Hotel';
                            break;
                    }
                    
					$newstatus = '';
					$newidreserva = MD5($row['id_reservation']);
					switch ($row['status_reservation']) {
						case 'RESERVED':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' transfer={$row['type_transfer']} total-cost='{$row['total_cost']}' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'COMPLETED':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation'  transfer={$row['type_transfer']} total-cost='{$row['total_cost']}' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'NO SHOW':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' transfer={$row['type_transfer']} total-cost='{$row['total_cost']}'  currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'CANCELLED':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' transfer={$row['type_transfer']} total-cost='{$row['total_cost']}' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						case 'REFUNDED':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' total-cost='{$row['total_cost']}' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='CANCELLED'>CANCELLED</option>
							</select>";
							break;
						case 'COURTESY':
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' transfer={$row['type_transfer']} total-cost='{$row['total_cost']}' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='{$row['status_reservation']}'>{$row['status_reservation']}</option>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
						default:
							$newstatus .= "<select class='form-control p-1' name='new_status_reservation' transfer={$row['type_transfer']} total-cost='{$row['total_cost']} ' currency='{$currency}' code='{$row['code_invoice']}' data='{$row['id_reservation']}' id='new_status_reservation'>
								<option value='RESERVED'>RESERVED</option>
								<option value='COMPLETED'>COMPLETED</option>
								<option value='NO SHOW'>NO SHOW</option>
								<option value='COURTESY'>COURTESY</option>
								<option value='CANCELLED'>CANCELLED</option>
								<option value='REFUNDED'>REFUNDED</option>
							</select>";
							break;
					}
                    switch ($row['method_payment']) {
                        case 'card':
                            $methodpayment = 'Tarjeta';
                            break;
                        case 'transfer':
                            $methodpayment = 'Transferencia';
                            break;
                        case 'paypal';
                            $methodpayment = 'Paypal';
                            break;
                        case 'airport':
                            $methodpayment = 'Pago al Abordar';
                            break;
                    }
                    $new_nameagency = "";
                    if($row['id_agency'] || $row['of_the_agency']){
                        $id_ag = "";
                        if ($row['id_agency'] == $row['of_the_agency']) {
                            $id_ag = $row['id_agency'];
                        }else{
                            $id_ag = $row['of_the_agency'];
                        }
                        $query_agency = "SELECT * FROM agencies WHERE id_agency like $id_ag;";
                        $result_a =  mysqli_query($con, $query_agency);
						$new_nameagency = "";
                        if ($result_a) {
                            $ins = mysqli_fetch_object($result_a);
							if (isset($ins->name_agency)) {
								$new_nameagency = $ins->name_agency;
							}
                        }
                    }
                    if ($row['transfer_destiny']) {
                        $name_zona = "";
                        $query_zon = "SELECT * FROM hotels as H inner join rates_public as R on H.id_zone = R.id_zone WHERE H.name_hotel like '{$row['transfer_destiny']}';";
                        $result_z = mysqli_query($con, $query_zon);
                        if ($result_z) {
                            $ins = mysqli_fetch_object($result_z);
                            $name_zona = "";
							if (isset($ins->name_zone)) {
								$name_zona = $ins->name_zone;
							}
                        }
                    }
					$output.="
					<tr reserva-re='{$row['id_reservation']}' agency='{$row['id_agency']}' code-invoice={$row['code_invoice']} reserva-con={$row['id_conciliation']}>
							<td>{$row['code_invoice']}</td>
                            <td class='hidden-sm'>{$new_nameagency}</td>
							<td>{$row['name_client']} {$row['last_name']} {$row['mother_lastname']}</td>
							<td>{$row['type_transfer']}</td>
							<td class='hidden-sm'>{$name_zona}</td>
							<td>{$row['type_service']}</td>
							<td class='hidden-sm'>{$row['number_adults']}</td>
							<td>{$methodpayment}</td>
							<td> {$row['total_cost']}</td>
							<td class='hidden-sm'> {$row['total_cost_commision']}</td>
							<td> {$new_amount_total} </td>
							<td>{$currency}</td>
							<td>{$newstatus}</td>
							<td class='text-center column_only'>
								<a href='#' id='btn_upload_file' reserva='{$row['id_reservation']}' agency='{$row["id_agency"]}'  conciliation='{$row['id_conciliation']}' code='{$row['code_invoice']}' class=' btn $class_conciliation btn-sm' data-toggle='modal' data-target='#modal_files'><i class='fas fa-file-upload'></i></a>
							</td>
							<td class='text-center column_only'>
								<a href='reservation_profile.php?reservation={$newidreserva}&coinv={$row['code_invoice']}&reedit=0' target='_blank' class=' btn btn-outline-dark btn-sm' ><i class='fas fa-eye'></i></a>
							</td>
							<td class='text-center column_only'>
								$btn_deposit
							</td>
							
					</tr>";
			
			} 
			$output.="</tbody>
				</table>";
			if ($search == 1) {
				$sql = "SELECT * FROM clients AS C INNER JOIN reservations AS R on C.id_client = R.id_client INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation 
				INNER JOIN conciliation AS CN ON R.id_reservation = CN.id_reservation
				$complete_query_search ((RD.date_arrival BETWEEN '$today' AND '$today') or (RD.date_exit BETWEEN '$today' AND '$today')) AND R.status_reservation NOT IN('CANCELLED') AND CN.`status` = $type  ORDER BY R.id_agency desc";
			}else {
				$sql = "SELECT * FROM clients AS C INNER JOIN reservations AS R on C.id_client = R.id_client INNER JOIN reservation_details AS RD ON R.id_reservation = RD.id_reservation 
                INNER JOIN conciliation AS CN ON R.id_reservation = CN.id_reservation
                WHERE ((RD.date_arrival BETWEEN '$today' AND '$today') or (RD.date_exit BETWEEN '$today' AND '$today')) AND R.status_reservation NOT IN('CANCELLED') AND CN.`status` = $type ORDER BY R.id_agency desc";
			}
			$records = mysqli_query($con, $sql);
			$totalRecords = mysqli_num_rows($records);
			$totalPage = ceil($totalRecords/$limit);
			$output.="<ul class='pagination' style='margin:20px 0'>";
			for ($i=1; $i <= $totalPage ; $i++) { 
			if ($i == $page_no) {
				$active = "active";
				$btn = "btn-black";
			}else{
				$active = "";
				$btn = "";
			}
				$output.="<li class='page-item $active'><a class='page-link $btn' id='$i' href=''>$i</a></li>";
			}
			$output .= "</ul>";

			echo $output;

		}else{
			if (isset($text_search)) {
				if ($type == 0) {
					$output.= "<div class='w-100 pb-2'>
					<div class='row'>
							<div class='col-lg-10'>
								<p>No se encontro ninguna conciliación realizada</p>
							</div>
							<div class='col-lg-2 text-right'>
								<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
							</div>
						</div>
					</div>";
				}else{
					$output.= "<div class='w-100 pb-2'>
					<div class='row'>
							<div class='col-lg-10'>
								<p>No se encontro ninguna conciliación realizada</p>
							</div>
							<div class='col-lg-2 text-right'>
								<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations_con' data-delay='.8s'><i class='fas fa-times'></i></a><br>
							</div>
						</div>
					</div>";
				}

			}else{
				
				$output.="
				<div class='w-100 h-100'>
					<p>No se encontro ninguna conciliación realizada</p>
				</div>";
			}
			echo $output;
		}
	}else{
		if (isset($text_search)) {
			if ($type == 0) {
				$output.= "<div class='w-100 pb-2'>
				<div class='row'>
						<div class='col-lg-10'>
							<p>No se encontro ninguna conciliación realizada</p>
						</div>
						<div class='col-lg-2 text-right'>
							<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations' data-delay='.8s'><i class='fas fa-times'></i></a><br>
						</div>
					</div>
				</div>";
			}else{
				$output.= "<div class='w-100 pb-2'>
				<div class='row'>
						<div class='col-lg-10'>
							<p>No se encontro ninguna conciliación realizada</p>
						</div>
						<div class='col-lg-2 text-right'>
							<a href='#' class='btn  btn btn-outline-dark btn-sm' data-animation='fadeInLeft' id='view_all_conciliations_con' data-delay='.8s'><i class='fas fa-times'></i></a><br>
						</div>
					</div>
				</div>";
			}
		}else{
			
			$output.="
			<div class='w-100 h-100'>
				<p>No se encontro ninguna conciliación realizada</p>
			</div>";
		}
		echo $output;
	}

?>