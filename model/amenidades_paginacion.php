<?php

	// Connect database 

	require_once('../config/conexion.php');

	$limit = 20;

	if (isset($_POST['page_no'])) {
		$page_no = $_POST['page_no'];
	}else{
	    $page_no = 1;
	}
	$offset = ($page_no-1) * $limit;
	$query = "SELECT * FROM amenities WHERE status = 1 ORDER BY id_amenity DESC LIMIT $offset, $limit";
	$result = mysqli_query($con, $query);
	$output = "";
	$newrole ='';
	$newoutput = '';
	if ($result) {	
		if (mysqli_num_rows($result) > 0) {
			$output.="<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaAmenidades'>
						<thead class='m-3'>
							<tr >
								<th>Id</th>
								<th>Amenidad</th>
								<th>Tipo de amenidad</th>
								<th>Descripción</th>
								<th>Precio MX</th>
								<th>Precio US</th>
								<th>Imagen</th>
								<th></th>
								<th></th>
								</tr>
						</thead>
						<tbody>";
			while ($row = mysqli_fetch_assoc($result)) {
					$output.="<tr amenity-id='{$row['id_amenity']}'>
							<td>{$row['id_amenity']}</td>
							<td>{$row['name_amenity']}</td>
							<td>{$row['type_amenity']}</td>
							<td>{$row['description']}</td>
							<td>$ {$row['price_mx']}</td>
							<td>$ {$row['price_us']}</td>
							<td class='text-center'>
								".(($row['img'] != NULL)? "<a href='#' title='Presiona para editar' data='{$row['name_amenity']}' class='edit_img' id='add_img' data-toggle='modal' data-target='#exampleModal'><img src='../../assets/img/amenidades/{$row['img']}' class='img-thumbnail '></a>" : "<a href='#' data='{$row['name_amenity']}' class='btn btn-sm btn-primary' title='Presiona para subir imagen'  class='add_img' id='add_img' data-toggle='modal' data-target='#exampleModal'>Subir <i class='fas fa-upload'></i></a>")."
							</td>
							<td class='text-center'>
								<a href='#' id='amenity-edit' class='amenity-edit btn btn-primary btn-sm' ><i class='fas fa-edit' ></i></a>
							</td>
							<td class='text-center'>
								<a href='#' id='amenity-delete' class='amenity-delete btn btn-danger btn-sm '><i class='fas fa-trash-alt'></i></a>
							</td>
					</tr>";
			
			} 
			$output.="</tbody>
				</table>";

			$sql = "SELECT * FROM amenities WHERE status = 1 ORDER BY id_amenity desc";

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
			$output.="<p>No se encontro ninguna amenidad registrada</p>";
			echo $output;
		}
	}else{
		$output.="<p>No se encontro ninguna amenidad registrada</p>";
		echo $output;
	}

?>