<?php
    class Amenity{
        
        //Search amenidad
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM amenities  WHERE STATUS = 1 AND (name_amenity LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_amenity' => $row['id_amenity'],
                        'name_amenity' => $row['name_amenity'],
                        'type_amenity' => $row['type_amenity'],
                        'description' => $row['description'],
                        'price_mx' => $row['price_mx'],
                        'price_us' => $row['price_us'],
                        'img' => $row['img']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Add amenidad
        public function insert($obj){ 
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'name_amenity'};
            $query = "SELECT * FROM amenities WHERE STATUS = 1 AND (name_amenity = '$nom');";
            $result = mysqli_query($con, $query);
            $codestate= 0;

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $message = 'La amenidad '.$ins->{'name_amenity'}.' ya se encuentran registrados.';
                }else{
                    $nombre_amenidad = $ins->{'name_amenity'};
                    $tipo_amenidad = $ins->{'type_amenity'};
                    $descipcion = $ins->{'description'};
                    $precio_mx = $ins->{'price_mx'};
                    $precio_us = $ins->{'price_us'};
                    $query2 =  "INSERT INTO amenities(name_amenity,type_amenity,price_mx,price_us,description,status)VALUES('$nombre_amenidad','$tipo_amenidad','$precio_mx','$precio_us','$descipcion',$status);";
                    $result2 = mysqli_query($con, $query2);
                    if (!$result2) {
                        $message = "Error al registrar la amenidad";
                    }
                    $message = 'La amenidad '.$ins->{'name_amenity'}.', ha sido registrado con exito.';
                    $codestate = 1;
                }
            }
            return json_encode(array('code' => $codestate, 'message' => $message));

        }
        
        //Get datas
        public function getData($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id'};
            $query = "SELECT * FROM amenities WHERE id_amenity = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                $message = "Error al traer los datos de la amenidad"; 
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_amenity' => $row['id_amenity'],
                    'name_amenity' => $row['name_amenity'],
                    'type_amenity' => $row['type_amenity'],
                    'description' => $row['description'],
                    'price_mx' => $row['price_mx'],
                    'price_us' => $row['price_us'],
                    'img' => $row['img']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }

        //Edit amenidad
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_amenidad = $ins->{'id'};
            $nombre_amenidad = $ins->{'name_amenity'};
            $tipo_amenidad = $ins->{'type_amenity'};
            $precio_mx = $ins->{'price_mx'};
            $precio_us = $ins->{'price_us'};
            $descipcion = $ins->{'description'};

            $query ="UPDATE amenities SET name_amenity = '$nombre_amenidad', type_amenity = '$tipo_amenidad', price_mx = '$precio_mx', price_us = '$precio_us', description = '$descipcion' WHERE id_amenity = '$id_amenidad'";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar la ameindad";    
            }
            $message = 'La amenidad a sido editada correctamente';
            return $message;

        }

        //Delete amenidad
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query = "UPDATE amenities SET status = $status WHERE id_amenity = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    $message = "Error al eliminar la amenidad";
                }
                $message = 'La amenidad a sido eliminado correctamente';
                return $message;
            }
        }

        //Delete amenidad img
        public function deleteImg($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $name =  $ins->{'name'};
                $status = 0;
                $directorio = "../assets/img/amenidades/$name";
                $query = "UPDATE amenities SET img = '' WHERE id_amenity = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    $message = "Error al eliminar la amenidad";
                }
                unlink($directorio);
                $message = 'La imagen de la amenidad a sido eliminado correctamente';
                return $message;
            }
        }
    }
?>