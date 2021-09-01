<?php
    class Hotel{

        //Search agency
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM hotels  WHERE STATUS = 1 AND (name_hotel LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_assoc($result)){
                    
                    $query2 = "SELECT * FROM rates_public WHERE id_zone = {$row['id_zone']}";
                    $result2 = mysqli_query($con,$query2);
                    if (mysqli_num_rows($result2)>0) {
                        while($row2 = mysqli_fetch_assoc($result2)){
                            $json[] = array(
                                'id_hotel' => $row['id_hotel'],
                                'name_hotel' => $row['name_hotel'],
                                'name_zone' => $row2['name_zone']
                            ); 
                        }
                    }else{$json[] = array(
                        'id_hotel' => $row['id_hotel'],
                        'name_hotel' => $row['name_hotel'],
                        'name_zone' => ""
                    );

                    }
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }


        //Add hotel
        public function insert($obj){
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'name_hotel'};
            $query = "SELECT * FROM hotels WHERE STATUS = 1 AND (name_hotel = '$nom');";
            $result = mysqli_query($con, $query);
            $codestate= 0;
            if (isset($result)) {
                if(mysqli_num_rows($result) > 0) {
                    $message = 'El hotel '.$ins->{'name_hotel'}.' ya se encuentra registrada.';
                }else{
                    $nombre_hotel = $ins->{'name_hotel'};
                    $nombre_zona = $ins->{'name_zone'};
                    $query2 =  "INSERT INTO hotels(name_hotel,id_zone,status)VALUES('$nombre_hotel',$nombre_zona,$status);";
                    $result2 = mysqli_query($con,$query2);
                    if (!$result2) {
                        $message = "Error al registrar la agencia";
                    }else{
                        $message = 'El hotel '.$ins->{'name_hotel'}.', ha sido registrada con exito.';
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
            $query = "SELECT * FROM hotels  WHERE id_hotel = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                $message = "Error al traer los datos del hotel"; 
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_hotel' => $row['id_hotel'],
                    'name_hotel' => $row['name_hotel'],
                    'name_zone' => $row['id_zone']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }

        //Edit hotel
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_hotel = $ins->{'id'};
            $nombre_hotel = $ins->{'name_hotel'};
            $nombre_zona = $ins->{'name_zone'};

            $query ="UPDATE hotels SET name_hotel = '$nombre_hotel', id_zone = $nombre_zona WHERE id_hotel = $id_hotel";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar el hotel";    
            }
            $message = 'El hotel a sido editada correctamente';
            return $message;

        }

        //Delete hotel
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query = "UPDATE hotels SET status = $status WHERE id_hotel = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    $message = "Error al eliminar el hotel";
                }
                $message = 'El hotel a sido eliminado correctamente';
                return $message;
            }
        }
    }

?>