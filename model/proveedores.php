<?php
    class Provider{
        //Search rep
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM providers  WHERE STATUS = 1 AND (name_provider LIKE '%$search%' OR name_contact LIKE '%$search%' OR email_provider LIKE '%$search%' OR id_provider LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();

                while($row = mysqli_fetch_array($result)){
                    $md5_id_provider =  MD5($row['id_provider']);
                    $json[] = array(
                        'id_provider' => $row['id_provider'],
                        'md5_id_provider' => $md5_id_provider,
                        'name_provider' => $row['name_provider'],
                        'name_contact' => $row['name_contact'],
                        'email_provider' => $row['email_provider'],
                        'phone_provider' => $row['phone_provider'],
                        'register_date' => $row['register_date']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }  
        // Add provider
        public function insert($obj){
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'name_provider'};
            $las = $ins->{'email_provider'};
            $query = "SELECT * FROM providers WHERE STATUS = 1 AND (name_provider = '$nom' AND email_provider ='$las');";
            $result = mysqli_query($con, $query);
            $codestate= 0;

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $message = 'El Proveedor '.$ins->{'name_provider'}.' ya se encuentran registrado.';
                }else{
                    $name_provider = $ins->{'name_provider'};
                    $name_contact = $ins->{'name_contact'};
                    $last_contact = $ins->{'last_contact'};
                    $email_provider = $ins->{'email_provider'};
                    $phone_provider = $ins->{'phone_provider'};
                    $today = date('Y-m-d H:i:s');
                    $status = 1;
                    $query2 =  "INSERT INTO providers(name_provider,name_contact,last_contact,email_provider,phone_provider,register_date,status)VALUES('$name_provider','$name_contact','$last_contact','$email_provider','$phone_provider','$today',$status);";
                    $result2 = mysqli_query($con, $query2);
                    $new_id_provider = mysqli_insert_id($con);
                    $this->createRateForProvider($new_id_provider, $con);
                    if (!$result2) {
                        $message = "Error al registrar al Proveedor";
                    }
                    $message = 'El Proveedor '.$ins->{'name_provider'}.', ha sido registrado con exito.';
                    $codestate = 1;
                }
            }
            return json_encode(array('code' => $codestate, 'message' => $message));

        }

        
    	public function createRateForProvider($id_provider, $con) {
            $query = "SELECT * FROM rates_public ORDER BY id_zone;";
            $result = mysqli_query($con, $query);
            if ($result) {
                while($row=mysqli_fetch_array($result)){
                    $id_zone = $row['id_zone'];
                    //COMPARTIDOS
                    $this->insertRateProvider($id_zone, $id_provider, 'Shared', 1, $con);
                    //PRIVADOS
                    $this->insertRateProvider($id_zone, $id_provider, 'Private', 4, $con);
                    $this->insertRateProvider($id_zone, $id_provider, 'Private', 6, $con);
                    $this->insertRateProvider($id_zone, $id_provider, 'Private', 8, $con);
                    $this->insertRateProvider($id_zone, $id_provider, 'Private', 10, $con);
            
                    //LUJO
                    $this->insertRateProvider($id_zone, $id_provider, 'Luxury', 6, $con);

                }
            }
        }

        public function insertRateProvider($id_zone, $id_provider, $type_service, $num_pax ,$con){
            $query ="INSERT INTO rates_providers(id_zone,id_provider,type_service,capacity_number) VALUES($id_zone, $id_provider, '$type_service', $num_pax);";
            $result = mysqli_query($con, $query);
        }

        //Get datas
        public function getData($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id'};
            $query = "SELECT * FROM providers WHERE id_provider = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                die('Error al editar el registro');
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_provider' => $row['id_provider'],
                    'name_provider' => $row['name_provider'],
                    'name_contact' => $row['name_contact'],
                    'last_contact' => $row['last_contact'],
                    'email_provider' => $row['email_provider'],
                    'phone_provider' => $row['phone_provider']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }
         //Edit Proveedor
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_provider = $ins->{'id'};
            $name_provider = $ins->{'name_provider'};
            $name_contact = $ins->{'name_contact'};
            $last_contact = $ins->{'last_contact'};
            $email_provider = $ins->{'email_provider'};
            $phone_provider = $ins->{'phone_provider'};
            $query ="UPDATE providers SET name_provider = '$name_provider', name_contact = '$name_contact', last_contact = '$last_contact', email_provider = '$email_provider', phone_provider = '$phone_provider' WHERE id_provider = '$id_provider';";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar el Proveedor";
            }
            $message = 'El Proveedor a sido editado correctamente';
            return $message;
        }
         
        //Delete proveedor
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query = "DELETE FROM providers WHERE id_provider = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error al eliminar al proveedor');
                    $message = "Error al eliminar el proveedor";
                }
                $message = 'El Proveedor a sido eliminado correctamente';
                return $message;
            }
        }

        //Obtenemos derecho de piso y nombre de proveedores
        public function getFloorRightById($id){
            include('../../config/conexion.php');
            $newid = mysqli_real_escape_string($con, $id);
            $query = "SELECT name_provider, cost_floorright FROM providers WHERE MD5(id_provider) = '$newid';";
            $result = mysqli_query($con, $query);
            if (!$result) {
                die('Error de consulta'. mysqli_error($con));
            }
            $json ="";
            while($row = mysqli_fetch_array($result)){
                $json = array(
                    'cost_floorright' => $row['cost_floorright'],
                    'name_provider' => $row['name_provider']
                );
            }
            mysqli_close($con);
            $jsonString = json_encode($json);
            return $jsonString;
        }

        //Obetenermos el listado de areas
        public function getListArea(){
            include('../../config/conexion.php');
            $query = "SELECT id_zone, name_zone from rates_public ORDER BY name_zone;";
            $result = mysqli_query($con, $query);
            if (!$result) {
                die('Error de consulta'. mysqli_error($con));
            }
            $json = array();
            while($row = mysqli_fetch_array($result)){
                $json[] = array(
                    'id_zone' => $row['id_zone'],
                    'name_zone' => $row['name_zone']
                );
            }
            mysqli_close($con);
            $jsonString = json_encode($json);
            return $jsonString;
        }

        //Obtenemos las tarifas de un proveedor
        public function getRateProvider($id_zone, $id_provider, $type_service, $cap){
            include('../../config/conexion.php');
            $newid_zone = mysqli_real_escape_string($con, $id_zone);
            $newid_provider = mysqli_real_escape_string($con, $id_provider);
            $newtype_service = mysqli_real_escape_string($con, $type_service);
            $new_cap = mysqli_real_escape_string($con, $cap);
            $query = "SELECT id_rate_provider, cost_service from rates_providers WHERE id_zone = $newid_zone AND MD5(id_provider) = '$newid_provider' AND type_service = '$newtype_service' AND capacity_number = $new_cap;";
            $result = mysqli_query($con, $query);
            $json = "";
            $cost_service = 0.00;
            $json = array('id_rate_provider' => null, 'cost_service'=> '0.00');
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)){
                        if (isset($row['cost_service'])) {
                            $cost_service = $row['cost_service'];
                        }
                        $json= array(
                            'cost_service' => $cost_service,
                            'id_rate_provider' => $row['id_rate_provider']
                        );
                    }
                }
            }
            mysqli_close($con);
            $jsonString = json_encode($json);
            return $jsonString;
        }

        //Update floorright
        public function updateFloorRight($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $new_floor_right = 0.00;
            $new_provider = "";
            if ($ins->{'new_value'} != $ins->{'current_value'}) {
                if ($ins->{'new_value'}) {
                    $value = $ins->{'new_value'};
                    $provider = $ins->{'id_provider'};
                    $new_floor_right = mysqli_real_escape_string($con, $value);
                    $new_provider = mysqli_real_escape_string($con, $provider);
                }
                require_once '../config/conexion.php';
                $query = "UPDATE providers SET cost_floorright = $new_floor_right WHERE MD5(id_provider) = '$new_provider';";
                $result = mysqli_query($con, $query);
                $message = "Error al editar el derecho de piso del Proveedor.";
                if ($result) {    
                        $message = "El derecho de piso del proveedor a sido actualizado correctamente.";
                }
                
            }else{
                $message = "El valor sigue siendo el mismo.";
            }
            return $message;
        }

        //Update Rates
        public function updateRates($obj){
            include('../config/conexion.php');
            $new_value_rate = "";
            $new_id = "";
            if ($obj['id']) {
                $rate = $obj['rate_value'];
                $id = $obj['id'];
                $new_value_rate = mysqli_real_escape_string($con, $rate);
                $new_id = mysqli_real_escape_string($con, $id);
                $query = "UPDATE rates_providers SET cost_service = $new_value_rate WHERE id_rate_provider = $new_id;";
                $result = mysqli_query($con, $query);
            }

        }

        public function groupForDriver($obj){
            include('../../config/conexion.php');
            $query = "SELECT provs FROM users WHERE MD5(id_user) = '$obj';";
            $result = mysqli_query($con, $query); 
            $provs = "";
            if ($result) {
				$ins_t = mysqli_fetch_object($result);
                $provs = $ins_t->provs;
            }
            return $provs;
        }
    }

?>