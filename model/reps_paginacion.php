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
	$query = "SELECT * FROM receptionists WHERE status = 1 ORDER BY id_receptionist ASC LIMIT $offset, $limit";
	$result = mysqli_query($con, $query);
	$output = "";
	$newrole ='';
	$newoutput = '';
	if ($result) {	
		if (mysqli_num_rows($result) > 0) {

			$output.="
					<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
						<thead>
							<tr>
								<th>ID</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Email</th>
								<th>Teléfono</th>
								<th>Notas</th>
								<th>Registrado</th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
				$output.="<tr rep-id='{$row['id_receptionist']}'>
						<td>{$row['id_receptionist']}</td>
						<td>{$row['name_receptionist']}</td>
						<td>{$row['last_name']}</td>
						<td>{$row['email_receptionist']}</td>
						<td>{$row['phone_receptionist']}</td>
						<td>{$row['notes_receptionist']}</td>
						<td>{$row['date_register']}</td>
						<td class='text-center text-center'>
							<a href='#' id='rep-edit' class='rep-edit btn btn-primary btn-sm' ><i class='fas fa-edit' ></i></a>
						</td>
						<td class='text-center text-center'>
							<a href='#' id='rep-delete' class='rep-delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i></a>
						</td>
				</tr>";

			} 
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM receptionists WHERE status = 1 ORDER BY id_receptionist DESC";

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
			$output.="<p>No se encontro ningun REP registrado</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ningun REP registrado</p>";
		echo $output;
	}

?>