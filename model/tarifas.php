<?php
    class Zone{

        //Search zona
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM rates_public  WHERE STATUS = 1 AND (name_zone LIKE '%$search%' OR id_zone LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_zone' => $row['id_zone'],
                        'name_zone' => $row['name_zone'],
                        'privado_ow_1' => $row['privado_ow_1'],
                        'privado_ow_2' => $row['privado_ow_2'],
                        'privado_ow_3' => $row['privado_ow_3'],
                        'privado_ow_4' => $row['privado_ow_4'],
                        'privado_ow_5' => $row['privado_ow_5'],
                        'privado_ow_6' => $row['privado_ow_6'],
                        'privado_rt_1' => $row['privado_rt_1'],
                        'privado_rt_2' => $row['privado_rt_2'],
                        'privado_rt_3' => $row['privado_rt_3'],
                        'privado_rt_4' => $row['privado_rt_4'],
                        'privado_rt_5' => $row['privado_rt_5'],
                        'privado_rt_6' => $row['privado_rt_6'],
                        'compartido_ow' => $row['compartido_ow'],
                        'compartido_rt' => $row['compartido_rt'],
                        'compartido_ow_premium' => $row['compartido_ow_premium'],
                        'compartido_rt_premium' => $row['compartido_rt_premium'],
                        'lujo_ow_1' => $row['lujo_ow_1'],
                        'lujo_ow_2' => $row['lujo_ow_2'],
                        'lujo_rt_1' => $row['lujo_rt_1'],
                        'lujo_rt_2' => $row['lujo_rt_2']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Search zona agencies
        public function searchAgencie($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT RT.*, RP.name_zone FROM rates_agencies AS RT
				INNER JOIN rates_public AS RP ON RT.id_zone = RP.id_zone
				WHERE RT.status = 1 AND (RP.name_zone LIKE '%$search%' OR RT.id_zone LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_zone' => $row['id_zone'],
                        'name_zone' => $row['name_zone'],
                        'privado_ow_1' => $row['privado_ow_1'],
                        'privado_ow_2' => $row['privado_ow_2'],
                        'privado_ow_3' => $row['privado_ow_3'],
                        'privado_rt_4' => $row['privado_ow_4'],
                        'privado_rt_5' => $row['privado_ow_5'],
                        'privado_rt_6' => $row['privado_ow_6'],
                        'privado_rt_1' => $row['privado_rt_1'],
                        'privado_rt_2' => $row['privado_rt_2'],
                        'privado_rt_3' => $row['privado_rt_3'],
                        'privado_rt_4' => $row['privado_rt_4'],
                        'privado_rt_5' => $row['privado_rt_5'],
                        'privado_rt_6' => $row['privado_rt_6'],
                        'compartido_ow' => $row['compartido_ow'],
                        'compartido_rt' => $row['compartido_rt'],
                        'compartido_ow_premium' => $row['compartido_ow_premium'],
                        'compartido_rt_premium' => $row['compartido_rt_premium'],
                        'lujo_ow_1' => $row['lujo_ow_1'],
                        'lujo_ow_2' => $row['lujo_ow_2'],
                        'lujo_rt_1' => $row['lujo_rt_1'],
                        'lujo_rt_2' => $row['lujo_rt_2']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Search zona tureando
        public function searchTureando($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT RA.*, RP.name_zone FROM rates_tureando AS RA
                INNER JOIN rates_public AS RP ON RA.id_zone = RP.id_zone
                WHERE RA.status = 1 AND (RP.name_zone LIKE '%$search%' OR RA.id_zone LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_zone' => $row['id_zone'],
                        'name_zone' => $row['name_zone'],
                        'privado_ow_1' => $row['privado_ow_1'],
                        'privado_ow_2' => $row['privado_ow_2'],
                        'privado_ow_3' => $row['privado_ow_3'],
                        'privado_rt_4' => $row['privado_ow_4'],
                        'privado_rt_5' => $row['privado_ow_5'],
                        'privado_rt_6' => $row['privado_ow_6'],
                        'privado_rt_1' => $row['privado_rt_1'],
                        'privado_rt_2' => $row['privado_rt_2'],
                        'privado_rt_3' => $row['privado_rt_3'],
                        'privado_rt_4' => $row['privado_rt_4'],
                        'privado_rt_5' => $row['privado_rt_5'],
                        'privado_rt_6' => $row['privado_rt_6'],
                        'compartido_ow' => $row['compartido_ow'],
                        'compartido_rt' => $row['compartido_rt'],
                        'compartido_ow_premium' => $row['compartido_ow_premium'],
                        'compartido_rt_premium' => $row['compartido_rt_premium'],
                        'lujo_ow_1' => $row['lujo_ow_1'],
                        'lujo_ow_2' => $row['lujo_ow_2'],
                        'lujo_rt_1' => $row['lujo_rt_1'],
                        'lujo_rt_2' => $row['lujo_rt_2']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Add zone
        public function insert($obj){
            $ins = json_decode($obj);
            $status = 1;
            include('../config/conexion.php');
            $nom = $ins->{'name_zone'};
            $query = "SELECT * FROM rates_public WHERE status = 1 AND (name_zone = '$nom')";
            $result = mysqli_query($con, $query);
            $codestate = 0;

            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $message = 'La zona '.$ins->{'name_zone'}.' ya se encuentran registrados.';
                }else{
                    $nombre_zona = $ins->{'name_zone'};
                    $privado_ow_1 = $ins->{'privado_ow_1'};
                    $privado_ow_2 = $ins->{'privado_ow_2'};
                    $privado_ow_3 = $ins->{'privado_ow_3'};
                    $privado_ow_4 = $ins->{'privado_ow_4'};
                    $privado_ow_5 = $ins->{'privado_ow_5'};
                    $privado_ow_6 = $ins->{'privado_ow_6'};
                    $privado_rt_1 = $ins->{'privado_rt_1'};
                    $privado_rt_2 = $ins->{'privado_rt_2'};
                    $privado_rt_3 = $ins->{'privado_rt_3'};
                    $privado_rt_4 = $ins->{'privado_rt_4'};
                    $privado_rt_5 = $ins->{'privado_rt_5'};
                    $privado_rt_6 = $ins->{'privado_rt_6'};
                    $compartido_ow = $ins->{'compartido_ow'};
                    $compartido_rt = $ins->{'compartido_rt'};
                    $compartido_ow_premium = $ins->{'compartido_ow_premium'};
                    $compartido_rt_premium = $ins->{'compartido_rt_premium'};
                    $lujo_ow_1 = $ins->{'lujo_ow_1'};
                    // $lujo_ow_2 = $ins->{'lujo_ow_2'};
                    $lujo_rt_1 = $ins->{'lujo_rt_1'};
                    // $lujo_rt_2 = $ins->{'lujo_rt_2'};
                    $query2 = "INSERT INTO rates_public(name_zone, privado_ow_1, privado_ow_2, privado_ow_3, privado_ow_4, privado_ow_5, privado_ow_6, privado_rt_1, privado_rt_2, privado_rt_3, privado_rt_4, privado_rt_5, privado_rt_6, compartido_ow, compartido_rt, compartido_ow_premium, compartido_rt_premium, lujo_ow_1, lujo_rt_1, status) VALUES('$nombre_zona','$privado_ow_1','$privado_ow_2','$privado_ow_3','$privado_ow_4','$privado_ow_5','$privado_ow_6','$privado_rt_1','$privado_rt_2','$privado_rt_3','$privado_rt_4','$privado_rt_5','$privado_rt_6','$compartido_ow','$compartido_rt','$compartido_ow_premium','$compartido_rt_premium','$lujo_ow_1','$lujo_rt_1',$status)";
                    $result2 = mysqli_query($con,$query2);
                    if (!$result2) {
                        $message = "Error al registrar la zona, intentelo de nuevo";
                    }else{
                        $idn = mysqli_insert_id($con);
                        //Add rate agencies
                        $query_a = "INSERT INTO rates_agencies(id_zone, privado_ow_1, privado_ow_2, privado_ow_3, privado_ow_4, privado_ow_5, privado_ow_6, privado_rt_1, privado_rt_2, privado_rt_3, privado_rt_4, privado_rt_5, privado_rt_6, compartido_ow, compartido_rt, compartido_ow_premium, compartido_rt_premium, lujo_ow_1, lujo_rt_1, status) VALUES('$idn','$privado_ow_1','$privado_ow_2','$privado_ow_3','$privado_ow_4','$privado_ow_5','$privado_ow_6','$privado_rt_1','$privado_rt_2','$privado_rt_3','$privado_rt_4','$privado_rt_5','$privado_rt_6','$compartido_ow','$compartido_rt','$compartido_ow_premium','$compartido_rt_premium','$lujo_ow_1','$lujo_rt_1',$status)";
                        $result_a = mysqli_query($con,$query_a);
                        //add rate tureando
                        $query_t = "INSERT INTO rates_tureando(id_zone, privado_ow_1, privado_ow_2, privado_ow_3, privado_ow_4, privado_ow_5, privado_ow_6, privado_rt_1, privado_rt_2, privado_rt_3, privado_rt_4, privado_rt_5, privado_rt_6, compartido_ow, compartido_rt, compartido_ow_premium, compartido_rt_premium, lujo_ow_1, lujo_rt_1, status) VALUES('$idn','$privado_ow_1','$privado_ow_2','$privado_ow_3','$privado_ow_4','$privado_ow_5','$privado_ow_6','$privado_rt_1','$privado_rt_2','$privado_rt_3','$privado_rt_4','$privado_rt_5','$privado_rt_6','$compartido_ow','$compartido_rt','$compartido_ow_premium','$compartido_rt_premium','$lujo_ow_1','$lujo_rt_1',$status)";
                        $result_t = mysqli_query($con,$query_t);

                        $message = 'La Zona '.$ins->{'name_zone'}.', ha sido registrada con exito. ';
                        $codestate = 1;
                    }
                }
            }
            return json_encode(array('code' => $codestate, 'message' => $message));
        }

        //Get datas
        public function getData($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id'};
            $rate_type = $ins->{'single'};
            $query = "";
            if ($rate_type == 'rate_public') {
                $query = "SELECT * FROM rates_public WHERE id_zone = $id";
            }
            if ($rate_type == 'rate_agencie') {
                $query = "SELECT RT.*, RP.name_zone FROM rates_agencies AS RT
				INNER JOIN rates_public AS RP ON RT.id_zone = RP.id_zone
				WHERE RT.id_zone = $id";
            }
            if ($rate_type == 'rate_tureando') {
                $query = "SELECT RA.*, RP.name_zone FROM rates_tureando AS RA
                INNER JOIN rates_public AS RP ON RA.id_zone = RP.id_zone
                WHERE RA.id_zone = $id";
            }
            $result = mysqli_query($con,$query);
            if (!$result) {
                die('error al editar el registro');
            }
            $json = array();
            while($row = mysqli_fetch_array($result)){
                $json[] = array(
                    'id_zone' => $row['id_zone'],
                    'name_zone' => $row['name_zone'],
                    'privado_ow_1' => $row['privado_ow_1'],
                    'privado_ow_2' => $row['privado_ow_2'],
                    'privado_ow_3' => $row['privado_ow_3'],
                    'privado_ow_4' => $row['privado_ow_4'],
                    'privado_ow_5' => $row['privado_ow_5'],
                    'privado_ow_6' => $row['privado_ow_6'],
                    'privado_rt_1' => $row['privado_rt_1'],
                    'privado_rt_2' => $row['privado_rt_2'],
                    'privado_rt_3' => $row['privado_rt_3'],
                    'privado_rt_4' => $row['privado_rt_4'],
                    'privado_rt_5' => $row['privado_rt_5'],
                    'privado_rt_6' => $row['privado_rt_6'],
                    'compartido_ow' => $row['compartido_ow'],
                    'compartido_rt' => $row['compartido_rt'],
                    'compartido_ow_premium' => $row['compartido_ow_premium'],
                    'compartido_rt_premium' => $row['compartido_rt_premium'],
                    'lujo_ow_1' => $row['lujo_ow_1'],
                    'lujo_ow_2' => $row['lujo_ow_2'],
                    'lujo_rt_1' => $row['lujo_rt_1'],
                    'lujo_rt_2' => $row['lujo_rt_2'],
                );
            }
            $jsonString = json_encode($json[0]);
            echo $jsonString;
        }

        //Edit zona
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $lujo_ow_2 = 0;
            $id_zona = $ins->{'id'};
            $type_rate = $ins->{'type_rate'};
            $nombre_zona = $ins->{'name_zone'};
            $privado_ow_1 = $ins->{'privado_ow_1'};
            $privado_ow_2 = $ins->{'privado_ow_2'};
            $privado_ow_3 = $ins->{'privado_ow_3'};
            $privado_ow_4 = $ins->{'privado_ow_4'};
            $privado_ow_5 = $ins->{'privado_ow_5'};
            $privado_ow_6 = $ins->{'privado_ow_6'};
            $privado_rt_1 = $ins->{'privado_rt_1'};
            $privado_rt_2 = $ins->{'privado_rt_2'};
            $privado_rt_3 = $ins->{'privado_rt_3'};
            $privado_rt_4 = $ins->{'privado_rt_4'};
            $privado_rt_5 = $ins->{'privado_rt_5'};
            $privado_rt_6 = $ins->{'privado_rt_6'};
            $compartido_ow = $ins->{'compartido_ow'};
            $compartido_rt = $ins->{'compartido_rt'};
            $compartido_ow_premium = $ins->{'compartido_ow_premium'};
            $compartido_rt_premium = $ins->{'compartido_rt_premium'};
            $lujo_ow_1 = $ins->{'lujo_ow_1'};
            // $lujo_ow_2 = $ins->{'lujo_ow_2'};
            $lujo_rt_1 = $ins->{'lujo_rt_1'};
            // $lujo_rt_2 = $ins->{'lujo_rt_2'};

            $query ="";
            if ($type_rate == 'rate_public') {
                $query = "UPDATE rates_public SET name_zone = '$nombre_zona', privado_ow_1 = '$privado_ow_1', privado_ow_2 = '$privado_ow_2', privado_ow_3 = '$privado_ow_3', privado_ow_4 = '$privado_ow_4', privado_ow_5 = '$privado_ow_5', privado_ow_6 = '$privado_ow_6', privado_rt_1 = '$privado_rt_1', privado_rt_2 = '$privado_rt_2', privado_rt_3 = '$privado_rt_3', privado_rt_4 = '$privado_rt_4', privado_rt_5 = '$privado_rt_5', privado_rt_6 = '$privado_rt_6', compartido_ow = '$compartido_ow', compartido_rt = '$compartido_rt', compartido_ow_premium = '$compartido_ow_premium', compartido_rt_premium = '$compartido_rt_premium', lujo_ow_1 = '$lujo_ow_1', lujo_rt_1 = '$lujo_rt_1'  WHERE id_zone = '$id_zona'";
            }
            if ($type_rate == 'rate_agencie') {
                $query = "UPDATE rates_agencies SET privado_ow_1 = '$privado_ow_1', privado_ow_2 = '$privado_ow_2', privado_ow_3 = '$privado_ow_3', privado_ow_4 = '$privado_ow_4', privado_ow_5 = '$privado_ow_5', privado_ow_6 = '$privado_ow_6', privado_rt_1 = '$privado_rt_1', privado_rt_2 = '$privado_rt_2', privado_rt_3 = '$privado_rt_3', privado_rt_4 = '$privado_rt_4', privado_rt_5 = '$privado_rt_5', privado_rt_6 = '$privado_rt_6', compartido_ow = '$compartido_ow', compartido_rt = '$compartido_rt', compartido_ow_premium = '$compartido_ow_premium', compartido_rt_premium = '$compartido_rt_premium', lujo_ow_1 = '$lujo_ow_1', lujo_rt_1 = '$lujo_rt_1'  WHERE id_zone = '$id_zona'";
            }
            if ($type_rate == 'rate_tureando') {
                $query = "UPDATE rates_tureando SET privado_ow_1 = '$privado_ow_1', privado_ow_2 = '$privado_ow_2', privado_ow_3 = '$privado_ow_3', privado_ow_4 = '$privado_ow_4', privado_ow_5 = '$privado_ow_5', privado_ow_6 = '$privado_ow_6', privado_rt_1 = '$privado_rt_1', privado_rt_2 = '$privado_rt_2', privado_rt_3 = '$privado_rt_3', privado_rt_4 = '$privado_rt_4', privado_rt_5 = '$privado_rt_5', privado_rt_6 = '$privado_rt_6', compartido_ow = '$compartido_ow', compartido_rt = '$compartido_rt', compartido_ow_premium = '$compartido_ow_premium', compartido_rt_premium = '$compartido_rt_premium', lujo_ow_1 = '$lujo_ow_1', lujo_rt_1 = '$lujo_rt_1'  WHERE id_zone = '$id_zona'";
            }
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar la zona";
            }
            $message = "La Zona $nombre_zona a sido editada correctamente";
            return $message;
        }

        //Delete zona
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query_2 ="";
                $query_3 ="";
                $query = "DELETE FROM rates_tureando  WHERE id_zone = $id";
                $result = mysqli_query($con, $query);
                if ($result) {
                    $query_2 = "DELETE FROM rates_agencies WHERE id_zone = $id";
                    $result_2 = mysqli_query($con, $query_2);
                    if ($result_2) {
                        $query_3 = "DELETE FROM rates_public WHERE id_zone = $id";
                        $result_3 = mysqli_query($con, $query_3);
                        if ($result_3) {
                            $message = 'La Zona a sido eliminado correctamente';
                            return $message;
                        }
                        $message = "Error al eliminar la zona";
                    }
                    $message = "Error al eliminar la zona";
                }
                $message = "Error al eliminar la zona";            
            }
            return $query.' - '.$query_2.' - '.$query_3;
        }
    }

?>