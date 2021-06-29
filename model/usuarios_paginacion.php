<?php

	// Connect database 

	require_once('../config/conexion.php');

	$limit = 30;
	$value = $_POST['value'];

	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	$offset = ($page_no-1) * $limit;
	$query = "SELECT * FROM users WHERE status = 1 ORDER BY id_user DESC LIMIT $offset, $limit";
	$result = mysqli_query($con, $query);
	$output = "";
	$newrole ='';
	$newoutput = '';
	if ($result) {	
		if (mysqli_num_rows($result) > 0) {

			$output.="<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Email</th>
								<th>Agencia</th>
								<th>Role</th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
				$name_agency ="N/A";
				if ($row['id_role'] == 1) {
					$newrole = 'Administrador';
				}
				if ($row['id_role'] == 2) {
					$newrole = 'Operador';
				}
				if ($row['id_role'] == 3) {
					$newrole = 'Proveedor';
				}
				if ($row['id_role'] == 5) {
					$newrole = 'Usuario de Agencia';
				}
					$query2 = "SELECT * FROM agencies WHERE id_agency like {$row['id_agency']}";
					$result2 = mysqli_query($con, $query2);
					if ($result2) {
						$ins_t = mysqli_fetch_object($result2);
						if ($row['id_agency'] && ($row['id_agency'] != 0 || $row['id_agency'] != "")) {
							$name_agency = $ins_t->name_agency;
						}
					}
					if ($value == $row['id_user']) {
						$output.="<tr user-id='{$row['id_user']}'>
								<td>{$row['id_user']}</td>
								<td>{$row['username']}</td>
								<td>{$row['email_user']}</td>
								<td>{$name_agency}</td>
								<td>$newrole</td>
								<td class='text-center'>
									<a href='#' id='user-edit' class='user-edit btn btn-primary btn-sm ' ><i class='fas fa-edit' ></i></a>
								</td>
								<td></td>
						</tr>";
					}else{
							$output.="<tr user-id='{$row['id_user']}'>
									<td>{$row['id_user']}</td>
									<td>{$row['username']}</td>
									<td>{$row['email_user']}</td>
									<td>{$name_agency}</td>
									<td>$newrole</td>
									<td class='text-center'>
										<a href='#' id='user-edit' class='user-edit btn btn-primary btn-sm ' ><i class='fas fa-edit' ></i></a>
									</td>
									<td class='text-center'>
										<a href='#' id='user-delete' class='user-delete btn btn-danger btn-sm '><i class='fas fa-trash-alt'></i></a>
									</td>
							</tr>";
					}
			}
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM users WHERE status = 1 ORDER BY id_user desc";

			$records = mysqli_query($con, $sql);
			$totalRecords = mysqli_num_rows($records);
			$totalPage = ceil($totalRecords/$limit);
			$output.="<ul class='pagination justify-content' style='margin:20px 0'>";
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
			$output.="<p>No se encontro ningunan usuario registrado</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ninguna usuario registrado</p>";
		echo $output;
	}

?>