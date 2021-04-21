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
	$query = "SELECT * FROM hotels WHERE status = 1 ORDER BY id_hotel DESC LIMIT $offset, $limit";
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
								<th>Hotel</th>
								<th>Zona</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
				$id_zona = $row['id_zone'];
				$query2 = "SELECT * FROM rates_public WHERE id_zone = $id_zona";
				$result2 = mysqli_query($con,$query2);
				if (mysqli_num_rows($result2)>0) {
					while($row2 = mysqli_fetch_assoc($result2)){
						$output.="<tr hotel-id='{$row['id_hotel']}'>
								<td>{$row['id_hotel']}</td>
								<td>{$row['name_hotel']}</td>
								<td>{$row2['name_zone']}</td>
								<td class='text-center text-center'>
									<a href='#' id='hotel-edit' class='hotel-edit btn btn-primary btn-sm ' ><i class='fas fa-edit' ></i></a>
								</td>
								<td class='text-center text-center'>
									<a href='#' id='hotel-delete' class='hotel-delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i></a>
								</td>
						</tr>";
					}
				}

			} 
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM hotels WHERE status = 1 ORDER BY id_hotel DESC";

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
			$output.="<p>No se encontro ningun hotel registrado</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ningun hotel registrado</p>";
		echo $output;
	}

?>