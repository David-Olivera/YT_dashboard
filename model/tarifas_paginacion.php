<?php
	class Pagination{
		public function getDataPublic($obj){
            $ins = json_decode($obj);
			// Connect database 
		
			require_once('../config/conexion.php');
		
			$limit = 10;
		
			if (isset($ins->{'page_no'})) {
				$page_no = $_POST['page_no'];
			}else{
				$page_no = 1;
			}
			if (isset($ins->{'navs'})) {
				$navs = $ins->{'navs'};
			}else{
				$navs = "PUBLIC";
			}
			$offset = ($page_no-1) * $limit;
			
			
			$query = "SELECT * FROM rates_public WHERE status = 1 ORDER BY name_zone ASC";
			$result = mysqli_query($con, $query);
			$output = "";
			$newrole ='';
			$newoutput = '';
			if ($result) {	
				if (mysqli_num_rows($result) > 0) {
					$output.="
							<h5>PUBLICOS</h5>
							<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaZonas'>
								<thead >
									<tr class='text-center'>
										<th>Zona</th>
										<th>Privado (OW)</th>
										<th>Privado (RT)</th>
										<th>Compartido  <br/> Minibus (OW)</th>
										<th>Compartido  <br/> Minibus (RT)</th>
										<th>Compartido  <br/> Premium (OW)</th>
										<th>Compartido  <br/> Premium (RT)</th>
										<th>Lujo (OW)</th>
										<th>Lujo (RT)</th>
										<th></th>
										<th></th>
										</tr>
								</thead>
								<tbody>";
					while ($row = mysqli_fetch_assoc($result)) {
							$output.="<tr class='text-center ' id='info_zonas' zone-id='{$row['id_zone']}'>
										<td>{$row['name_zone']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_ow_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_ow_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_ow_3']} <br/> <small><strong>1-10 </strong></small>$ {$row['privado_ow_4']} <br/> <small><strong>1-11 </strong></small>$ {$row['privado_ow_5']} <br/> <small><strong>1-16 </strong></small>$ {$row['privado_ow_6']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_rt_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_rt_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_rt_3']} <br/> <small><strong>1-10</strong> </small>$ {$row['privado_rt_4']} <br/> <small><strong>1-11</strong> </small>$ {$row['privado_rt_5']} <br/> <small><strong>1-16</strong> </small>$ {$row['privado_rt_6']}</td>
										<td>$ {$row['compartido_ow']}</td>
										<td>$ {$row['compartido_rt']}</td>
										<td>$ {$row['compartido_ow_premium']}</td>
										<td>$ {$row['compartido_rt_premium']}</td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_ow_1']} </td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_rt_1']} </td>
										<td>
											<a href='#' id='zone-edit-pu' class='zone-edit-pu' ><i class='fas fa-edit' ></i></a>
										</td>
										<td class='align-middle'>
											<a href='#' id='zone-delete' class='zone-delete'><i class='fas fa-trash-alt'></i></a>
										</td>
								</tr>";
							}
					$output.="</tbody>
						</table>";
		
					// $sql = "SELECT * FROM rates_public WHERE status = 1 ORDER BY id_zone DESC";
					// $records = mysqli_query($con, $sql);
					// if ($records) {
						// $totalRecords = mysqli_num_rows($records);
						// $totalPage = ceil($totalRecords/$limit);
						// $output.="<ul class='pagination justify-content' style='margin:20px 0'>";
						// for ($i=1; $i <= $totalPage ; $i++) { 
						// if ($i == $page_no) {
							// $active = "active";
						// }else{
							// $active = "";
						// }
							// $output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
						// }
						// $output .= "</ul>";
					// }
		
					echo $output;
		
				}else{
					$output.="<p>No se encontro ninguna zona registrada</p>";
					echo $output;
				}
			}else{
				$output.="<p>No se encontro ninguna zona registrada</p>";
				echo $output;
			}

		}
		public function getDataAgencies($obj){
            $ins = json_decode($obj);
			// Connect database 
		
			require_once('../config/conexion.php');
		
			$limit = 10;
		
			if (isset($ins->{'page_no'})) {
				$page_no = $_POST['page_no'];
			}else{
				$page_no = 1;
			}
			if (isset($ins->{'navs'})) {
				$navs = $ins->{'navs'};
			}else{
				$navs = "PUBLIC";
			}
			$offset = ($page_no-1) * $limit;
			$query = "SELECT RT.*, RP.name_zone FROM rates_agencies AS RT
				INNER JOIN rates_public AS RP ON RT.id_zone = RP.id_zone
				WHERE RT.status = 1 ORDER BY name_zone ASC ";
			$result = mysqli_query($con, $query);
			$output = "";
			$newrole ='';
			$newoutput = '';
			if ($result) {	
				if (mysqli_num_rows($result) > 0) {
					$output.="
					<h5>AGENCIAS</h5>
							<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaZonas'>
								<thead >
									<tr class='text-center'>
										<th>Zona</th>
										<th>Privado (OW)</th>
										<th>Privado (RT)</th>
										<th>Compartido  <br/> Minibus (OW)</th>
										<th>Compartido  <br/> Minibus (RT)</th>
										<th>Compartido  <br/> Premium (OW)</th>
										<th>Compartido  <br/> Premium (RT)</th>
										<th>Lujo (OW)</th>
										<th>Lujo (RT)</th>
										<th></th>
										</tr>
								</thead>
								<tbody>";
					while ($row = mysqli_fetch_assoc($result)) {
							$output.="<tr class='text-center ' id='info_zonas' zone-id='{$row['id_zone']}'>
										<td>{$row['name_zone']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_ow_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_ow_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_ow_3']} <br/> <small><strong>1-10 </strong></small>$ {$row['privado_ow_4']} <br/> <small><strong>1-11 </strong></small>$ {$row['privado_ow_5']} <br/> <small><strong>1-16 </strong></small>$ {$row['privado_ow_6']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_rt_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_rt_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_rt_3']} <br/> <small><strong>1-10</strong> </small>$ {$row['privado_rt_4']} <br/> <small><strong>1-11</strong> </small>$ {$row['privado_rt_5']} <br/> <small><strong>1-16</strong> </small>$ {$row['privado_rt_6']}</td>
										<td>$ {$row['compartido_ow']}</td>
										<td>$ {$row['compartido_rt']}</td>
										<td>$ {$row['compartido_ow_premium']}</td>
										<td>$ {$row['compartido_rt_premium']}</td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_ow_1']} </td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_rt_1']} </td>
										<td>
											<a href='#' id='zone-edit-ag' class='zone-edit-ag' ><i class='fas fa-edit' ></i></a>
										</td>
								</tr>";
							}
					$output.="</tbody>
						</table>";
		
					// $sql = "SELECT RT.*, RP.name_zone FROM rates_agencies AS RT	INNER JOIN rates_public AS RP ON RT.id_zone = RP.id_zone WHERE RT.status = 1 ORDER BY name_zone DESC";
					// $records = mysqli_query($con, $sql);
					// if ($records) {
						// $totalRecords = mysqli_num_rows($records);
						// $totalPage = ceil($totalRecords/$limit);
						// $output.="<ul class='pagination justify-content' style='margin:20px 0'>";
						// for ($i=1; $i <= $totalPage ; $i++) { 
						// if ($i == $page_no) {
							// $active = "active";
						// }else{
							// $active = "";
						// }
							// $output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
						// }
						// $output .= "</ul>";
					// }
		
					echo $output;
		
				}else{
					$output.="<p>No se encontro ninguna zona registrada</p>";
					echo $output;
				}
			}else{
				$output.="<p>No se encontro ninguna zona registrada</p>";
				echo $output;
			}

		}
		public function getDataTureando($obj){
            $ins = json_decode($obj);
			// Connect database 
		
			require_once('../config/conexion.php');
		
			$limit = 10;
		
			if (isset($ins->{'page_no'})) {
				$page_no = $_POST['page_no'];
			}else{
				$page_no = 1;
			}
			if (isset($ins->{'navs'})) {
				$navs = $ins->{'navs'};
			}else{
				$navs = "PUBLIC";
			}
			$offset = ($page_no-1) * $limit;
			
			$query = "SELECT RA.*, RP.name_zone FROM rates_tureando AS RA
			INNER JOIN rates_public AS RP ON RA.id_zone = RP.id_zone
			WHERE RA.status = 1 ORDER BY name_zone ASC ";
			
			$result = mysqli_query($con, $query);
			$output = "";
			$newrole ='';
			$newoutput = '';
			if ($result) {	
				if (mysqli_num_rows($result) > 0) {
					$output.="
					<h5>TUREANDO</h5>
							<table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaZonas'>
								<thead >
									<tr class='text-center'>
										<th>Zona</th>
										<th>Privado (OW)</th>
										<th>Privado (RT)</th>
										<th>Compartido  <br/> Minibus (OW)</th>
										<th>Compartido  <br/> Minibus (RT)</th>
										<th>Compartido  <br/> Premium (OW)</th>
										<th>Compartido  <br/> Premium (RT)</th>
										<th>Lujo (OW)</th>
										<th>Lujo (RT)</th>
										<th></th>
										</tr>
								</thead>
								<tbody>";
					while ($row = mysqli_fetch_assoc($result)) {
							$output.="<tr class='text-center ' id='info_zonas' zone-id='{$row['id_zone']}'>
										<td>{$row['name_zone']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_ow_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_ow_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_ow_3']} <br/> <small><strong>1-10 </strong></small>$ {$row['privado_ow_4']} <br/> <small><strong>1-11 </strong></small>$ {$row['privado_ow_5']} <br/> <small><strong>1-16 </strong></small>$ {$row['privado_ow_6']}</td>
										<td><small><strong>1-4</strong> </small>$ {$row['privado_rt_1']} <br/> <small><strong>1-6</strong> </small>$ {$row['privado_rt_2']} <br/> <small><strong>1-8</strong> </small>$ {$row['privado_rt_3']} <br/> <small><strong>1-10</strong> </small>$ {$row['privado_rt_4']} <br/> <small><strong>1-11</strong> </small>$ {$row['privado_rt_5']} <br/> <small><strong>1-16</strong> </small>$ {$row['privado_rt_6']}</td>
										<td>$ {$row['compartido_ow']}</td>
										<td>$ {$row['compartido_rt']}</td>
										<td>$ {$row['compartido_ow_premium']}</td>
										<td>$ {$row['compartido_rt_premium']}</td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_ow_1']} </td>
										<td><small><strong>1-6</strong> </small>$ {$row['lujo_rt_1']} </td>
										<td>
											<a href='#' id='zone-edit-tu' class='zone-edit-tu' ><i class='fas fa-edit' ></i></a>
										</td>
								</tr>";
							}
					$output.="</tbody>
						</table>";
		
				// $sql = "SELECT RA.*, RP.name_zone FROM rates_tureando AS RA	INNER JOIN rates_public AS RP ON RA.id_zone = RP.id_zone WHERE RA.status = 1 ORDER BY name_zone DESC";
					// $records = mysqli_query($con, $sql);
					// if ($records) {
						// $totalRecords = mysqli_num_rows($records);
						// $totalPage = ceil($totalRecords/$limit);
						// $output.="<ul class='pagination justify-content' style='margin:20px 0'>";
						// for ($i=1; $i <= $totalPage ; $i++) { 
						// if ($i == $page_no) {
							// $active = "active";
						// }else{
							// $active = "";
						// }
							// $output.="<li class='page-item $active'><a class='page-link' id='$i' href=''>$i</a></li>";
						// }
						// $output .= "</ul>";
					// }
		
					echo $output;
		
				}else{
					$output.="<p>No se encontro ninguna zona registrada</p>";
					echo $output;
				}
			}else{
				$output.="<p>No se encontro ninguna zona registrada</p>";
				echo $output;
			}

		}
	}

?>