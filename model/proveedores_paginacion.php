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
	$query = "SELECT * FROM providers WHERE status = 1 ORDER BY id_provider ASC LIMIT $offset, $limit";
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
								<th>Contacto</th>
								<th>Email</th>
								<th>Teléfono</th>
								<th>Registrado</th>
								<th></th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
				$newidprovider = MD5($row['id_provider']);
				$output.="<tr provider-id='{$row['id_provider']}'>
						<td>{$row['id_provider']}</td>
						<td>{$row['name_provider']}</td>
						<td>{$row['name_contact']}</td>
						<td>{$row['email_provider']}</td>
						<td>{$row['phone_provider']}</td>
						<td>{$row['register_date']}</td>
						<td class='text-center text-center'>
							<a href='#' id='provider-edit' class='provider-edit btn btn-primary btn-sm' ><i class='fas fa-edit' ></i></a>
						</td>
						<td class='text-center text-center'>
							<a href='#' id='provider-delete' class='provider-delete btn btn-danger btn-sm '><i class='fas fa-trash-alt'></i></a>
						</td>
						<td class='text-center text-center'>
							<a  href='providers_rates.php?id_provider={$newidprovider}&provider={$row['name_provider']}' target='_blank' id='provider-tarifa' title='Tarifas de proveedor' class='btn btn-black btn-sm '><i class='fas fa-calculator'></i></a>
						</td>
				</tr>";

			} 
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM providers WHERE status = 1 ORDER BY id_provider DESC";

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
			$output.="<p>No se encontro ningun Proveedor registrado</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ningun Proveedor registrado</p>";
		echo $output;
	}

?>