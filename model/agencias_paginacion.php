<?php

	// Connect database 

	require_once('../config/conexion.php');

	$limit = 60;

	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	$offset = ($page_no-1) * $limit;
	$query = "SELECT * FROM agencies WHERE status = 1 ORDER BY id_agency DESC LIMIT $offset, $limit";
	$result = mysqli_query($con, $query);
	$output = "";
	$newrole ='';
	$newoutput = '';
	if ($result) {	
		if (mysqli_num_rows($result) > 0) {
			$querycount= "SELECT COUNT(*) as total FROM agencies WHERE status = 1";
			$resultcount = mysqli_query($con,$querycount);
			$fila = mysqli_fetch_assoc($resultcount);

			$output.="
					<h5>Agencias registradas: <strong>{$fila['total']}</strong></h5>
					<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
						<thead>
							<tr>
								<th>ID</th>
								<th>Agencia</th>
								<th>Email Contacto</th>
								<th>Email Pagos</th>
								<th>Usuario</th>
								<th>Teléfono</th>
								<th>Registro</th>
								<th>Docs</th>
								<th>CASH</th>
								<th>CARD</th>
								<th>PAYPAL</th>
								<th>TODAY</th>
								<th>Yamevi</th>
								<th></th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
				$query2 = "SELECT * FROM agency_payment WHERE id_agency = {$row['id_agency']}";
				$result2 =  mysqli_query($con,$query2);
				if ($result2) {
					if (mysqli_num_rows($result2) > 0) {
						while($row2 = mysqli_fetch_assoc($result2)){
								$checkedCash = $row2['cash'] == 1 ? 'checked="checked"' : NULL;
								$checkedToday = $row2['todaysale'] == 1 ? 'checked="checked"' : NULL;
								$checkedPaypal = $row2['paypal'] == 1 ? 'checked="checked"' : NULL;
								$checkedCard = $row2['card'] == 1 ? 'checked="checked"' : NULL;
								$checkedYT = $row2['internal_yt'] == 1 ? 'checked="checked"' : NULL;
								$newemail = "";						
								$newemailpay = "";
								if ($row['email_pay_agency']) {
									$newemailpay = substr($row['email_pay_agency'],0,16)."...";
								}
								if ($row['email_agency']) {
									$newemail = substr($row['email_agency'],0,16)."...";	
								}
								$output.="<tr agency-id='{$row['id_agency']}'>
											<td>{$row['id_agency']}</td>
											<td>{$row['name_agency']}</td>
											<td><a href='#' class='copy_email' title='{$row['email_agency']}'>{$newemail}</a></td>
											<td><a href='#' class='copy_email' title='{$row['email_pay_agency']}'>{$newemailpay}</a></td>
											<td id='usuario'>{$row['username']}</td>
											<td>{$row['phone_agency']}</td>
											<td>{$row['register_date']}</td>
											<td class='text-center'><a href='#' id='load_docs' class='btn btn-sm btn-black' datagen='{$row['id_agency']}' data-toggle='modal' data-target='#exampleModal' title='Subir documentos'><i class='fas fa-file-upload'></i></a></td>
											<td>
												<div class='form-check '>
													<input type='checkbox' class='settingCash' data-cash='{$row['id_agency']}' $checkedCash ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check'>
												<input type='checkbox' class='settingCard' data-card='{$row['id_agency']}' $checkedCard ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
												<input type='checkbox' class='settingPaypal' data-paypal='{$row['id_agency']}' $checkedPaypal ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
												<input type='checkbox' class='settingToday' data-today='{$row['id_agency']}' $checkedToday ><br /> 
												</div>
											</td>
											<td>
												<div class='form-check '>
												<input type='checkbox' class='settingYT' data-yt='{$row['id_agency']}' $checkedYT ><br /> 
												</div>
											</td>
											<td class='text-center text-center'>
												<a href='#' id='agency-edit' title='Editar Agencia' class='agency-edit btn btn-primary btn-sm ' ><i class='fas fa-edit' ></i></a>
											</td>
											<td class='text-center text-center'>
												<a href='#' id='agency-delete' title='Eliminar Agencia' class='agency-delete btn btn-danger btn-sm ' ><i class='fas fa-trash-alt'></i></a>
											</td>
											<td class='text-center text-center'>
												<a href='#' id='agency-users' title='Administrar Usuarios' class='agency-users btn btn-dark btn-sm ' ><i class='fas fa-users'></i></a>
											</td>
											
									</tr>";
						}
					}else{
						$checkedCash = '' == 1 ? 'checked="checked"' : NULL;
						$checkedToday = '' == 1 ? 'checked="checked"' : NULL;
						$checkedPaypal = '' == 1 ? 'checked="checked"' : NULL;
						$checkedCard = '' == 1 ? 'checked="checked"' : NULL;
						$checkedYT = '' == 1 ? 'checked="checked"' : NULL;
						
						$newemail = "";						
						$newemailpay = "";
						if ($row['email_pay_agency']) {
							$newemailpay = substr($row['email_pay_agency'],0,16)."...";
						}
						if ($row['email_agency']) {
							$newemail = substr($row['email_agency'],0,16)."...";	
						}
							$output.="<tr agency-id='{$row['id_agency']}'>
									<td>{$row['id_agency']}</td>
									<td>{$row['name_agency']}</td>
									<td><a href='#' class='copy_email' title='{$row['email_agency']}'>{$newemail}</a></td>
									<td><a href='#' class='copy_email' title='{$row['email_pay_agency']}'>{$newemailpay}</a></td>
									<td id='usuario'>{$row['username']}</td>
									<td>{$row['phone_agency']}</td>
									<td>{$row['register_date']}</td>
									<td>
										<div class='form-check '>
											<input type='checkbox' class='settingCash' data-cash='{$row['id_agency']}' $checkedCash ><br /> 
										</div>
									</td>
									<td>
										<div class='form-check'>
										<input type='checkbox' class='settingCard' data-card='{$row['id_agency']}' $checkedCard ><br /> 
										</div>
									</td>
									<td>
										<div class='form-check '>
										<input type='checkbox' class='settingPaypal' data-paypal='{$row['id_agency']}' $checkedPaypal ><br /> 
										</div>
									</td>
									<td>
										<div class='form-check '>
										<input type='checkbox' class='settingToday' data-today='{$row['id_agency']}' $checkedToday ><br /> 
										</div>
									</td>
									<td>
										<div class='form-check '>
										<input type='checkbox' class='settingYT' data-yt='{$row['id_agency']}' $checkedYT ><br /> 
										</div>
									</td>
									<td class='text-center text-center'>
										<a href='#' id='agency-edit' class='agency-edit' ><i class='fas fa-edit btn btn-primary btn-sm' ></i></a>
									</td>
									<td class='text-center text-center'>
										<a href='#' id='agency-delete' class='agency-delete btn btn-danger btn-sm '><i class='fas fa-trash-alt'></i></a>
									</td>
									<td class='text-center text-center'>
										<a href='#' id='agency-users' title='Administrar Usuarios' class='agency-users btn btn-dark btn-sm ' ><i class='fas fa-users'></i></a>
									</td>
							</tr>";
					}
				}else{

				}

			
			} 
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM agencies WHERE status = 1 ORDER BY id_agency DESC";

			$records = mysqli_query($con, $sql);
			$totalRecords = mysqli_num_rows($records);
			$totalPage = ceil($totalRecords/$limit);
			$output.="<ul class='pagination' style='margin:20px 0'>";
			for ($i=1; $i <= $totalPage ; $i++) { 
			if ($i == $page_no) {
				$active = "active";
			}else{
				$active = "";
			}
				$output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
			}
			$output .= "</ul>";

			echo $output;

		}else{
			$output.="<p>No se encontro ninguna agenica registrada</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ninguna agenica registrada</p>";
		echo $output;
	}


?>