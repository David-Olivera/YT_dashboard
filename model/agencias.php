<?php
    class Agency{

        //Search agency
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM agencies  WHERE STATUS = 1 AND (name_agency LIKE '$search%' OR id_agency LIKE '$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_assoc($result)){
                    $query2 = "SELECT * FROM agency_payment WHERE id_agency = {$row['id_agency']}";
                    $result2 =  mysqli_query($con,$query2);
                    if ($result2) {
                        if (mysqli_num_rows($result2) > 0) {
                            while($row2 = mysqli_fetch_assoc($result2)){
								$checkedCash = $row2['cash'] == 1 ? 'checked="checked"' : NULL;
								$checkedToday = $row2['todaysale'] == 1 ? 'checked="checked"' : NULL;
								$checkedPaypal = $row2['paypal'] == 1 ? 'checked="checked"' : NULL;
								$checkedCard = $row2['card'] == 1 ? 'checked="checked"' : NULL;
								$checkedYT = $row2['internal_yt'] == 1 ? 'checked="checked"' : NULL;
                                $json[] = array(
                                    'id_agency' => $row['id_agency'],
                                    'name_agency' => $row['name_agency'],
                                    'email_agency' => $row['email_agency'],
                                    'email_agency_pay' => $row['email_pay_agency'],
                                    'phone_agency' => $row['phone_agency'],
                                    'username' => $row['username'],
                                    'password' => $row['password'],
                                    'register_date' => $row['register_date'],
                                    'checkedCash' => $checkedCash,
                                    'checkedToday' => $checkedToday,
                                    'checkedPaypal' => $checkedPaypal,
                                    'checkedCard' => $checkedCard,
                                    'checkedYT' => $checkedYT
                                ); 

                            }
                        }
                    }
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Add agency
        public function insert($obj){
            $today = date('Y-m-d H:i:s');
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'name_agency'};
            $newnom = mysqli_real_escape_string($con,$nom);
            $ema = $ins->{'email_agency'};
            $newema = mysqli_real_escape_string($con,$ema);
            $query = "SELECT * FROM agencies WHERE STATUS = 1 AND (name_agency = '$nom' OR email_agency = '$ema')";
            $result = mysqli_query($con, $query);
            $codestate= 0;

            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $message = 'La agencia '.$ins->{'name_agency'}.' o el email de agencia '.$ins->{'email_agency'}.' ya se encuentran registrados.';
                }else{
                    $nombre_agencia = $ins->{'name_agency'};
                    $new_nombre_agencia = mysqli_real_escape_string($con,$nombre_agencia);
                    
                    $email_agencia = $ins->{'email_agency'};
                    $new_email_agencia = mysqli_real_escape_string($con,$email_agencia);

                    $email_agencia_pagos = $ins->{'email_agency_pay'};
                    $new_email_agencia_pagos = mysqli_real_escape_string($con,$email_agencia_pagos);

                    $telefono_agencia = $ins->{'phone_agency'};
                    $new_telefono_agencia = mysqli_real_escape_string($con,$telefono_agencia);

                    $usuario_agencia = $ins->{'username'};
                    $new_usuario_agencia = mysqli_real_escape_string($con,$usuario_agencia);

                    $password = MD5($ins->{'password'});
                    $newpass = mysqli_real_escape_string($con,$password);
                    $fecha_registro = $today;
                    $query2 =  "INSERT INTO agencies(name_agency,email_agency,email_pay_agency,phone_agency,username,register_date,password,status)VALUES('$new_nombre_agencia','$new_email_agencia','$new_email_agencia_pagos','$new_telefono_agencia','$new_usuario_agencia','$fecha_registro','$newpass',$status)";
                    $result2 = mysqli_query($con,$query2);
                    if ($result2) {
                        $idagency =  mysqli_insert_id($con);
                        $query3 = "INSERT INTO agency_payment(id_agency) VALUES('$idagency')";
                        $result3 = mysqli_query($con,$query3);
                        if (!$result3) {
                            die('Error al registrar el agencia');
                            $message = "Error al registrar la agencia";
                        }
                        $message = 'La Agencia '.$ins->{'name_agency'}.', ha sido registrada con exito.';
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
            $query = "SELECT * FROM agencies WHERE id_agency = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                die('Error al editar el registro');
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_agency' => $row['id_agency'],
                    'name_agency' => $row['name_agency'],
                    'email_agency' => $row['email_agency'],
                    'email_agency_pay' => $row['email_pay_agency'],
                    'phone_agency' => $row['phone_agency'],
                    'username' => $row['username'],
                    'password' => $row['password']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }

        //Change cash agency
        public function setCashConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $cash = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET cash = $cash WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, cash) VALUES($id ,$cash)";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }
            }

            return json_encode(array('message' => $message));
        }

        //Change card agency
        public function setCardConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $card = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET card = $card WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, card) VALUES($id ,$card)";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }
            }

            return json_encode(array('message' => $message));
        }

        //Change paypal agency
        public function setPaypalConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $paypal = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET paypal = $paypal WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, paypal) VALUES($id ,$paypal)";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }
            }

            return json_encode(array('message' => $message));
        }

        //Change today agency
        public function setTodayConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $today = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET todaysale = $today WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, todaysale) VALUES($id ,$today)";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }
            }

            return json_encode(array('message' => $message));
        }

        //Change YAMEVI agency
        public function setYTConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $today = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET internal_yt = $today WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, internal_yt) VALUES($id ,$today)";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }
            }

            return json_encode(array('message' => $message));
        }
        //Edit agnecy
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_agencia = $ins->{'id'};

            $nombre_agencia = $ins->{'name_agency'};
            $new_nombre_agencia = mysqli_real_escape_string($con,$nombre_agencia);
            
            $email_agencia = $ins->{'email_agency'};
            $new_email_agencia = mysqli_real_escape_string($con,$email_agencia);

            $email_agencia_pagos = $ins->{'email_agency_pay'};
            $new_email_agencia_pagos = mysqli_real_escape_string($con,$email_agencia_pagos);

            $telefono_agencia = $ins->{'phone_agency'};
            $new_telefono_agencia = mysqli_real_escape_string($con,$telefono_agencia);

            $usuario_agencia = $ins->{'username'};
            $new_usuario_agencia = mysqli_real_escape_string($con,$usuario_agencia);
            
            $pass = $ins->{'password'};
            $password = MD5($pass);
            $newpass = mysqli_real_escape_string($con,$password);
            
            $query = "";
            if ($pass == '') {
                $query ="UPDATE agencies SET name_agency = '$new_nombre_agencia', email_agency = '$new_email_agencia', email_pay_agency = '$new_email_agencia_pagos' ,phone_agency = '$new_telefono_agencia', username = '$new_usuario_agencia' WHERE id_agency = '$id_agencia'"; 
            }else{
                $query ="UPDATE agencies SET name_agency = '$new_nombre_agencia', email_agency = '$new_email_agencia', email_pay_agency = '$new_email_agencia_pagos' ,phone_agency = '$new_telefono_agencia', username = '$new_usuario_agencia', password = '$newpass' WHERE id_agency = '$id_agencia'";
            }
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar la agencia";
            }
            $message = 'La agencia a sido editada correctamente';
            return $message;
        }

        //Delete agency
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query = "UPDATE agencies SET status = $status WHERE id_agency = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error al eliminar la agencia');
                    $message = "Error al eliminar la agencia";
                }
                $message = 'La agencia a sido eliminado correctamente';
                return $message;
            }
        }

        //Get Datas Docs
        public function getDocs($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id_agency'};
            $query = "SELECT * FROM agencies_docs WHERE id_agency = $id;";
            $json = array();
            $result = mysqli_query($con, $query);
            if (!$result) {
                $json="No tiene documentos registrados";
            }
            if (mysqli_num_rows($result)> 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $new_name_doc = substr($row['name_doc'],0,17);
                    $json[] = array('id_doc' => $row['id_agencies_docs'], 'name_doc' => $new_name_doc, 'name_doc_complete' => $row['name_doc'], 'date_register' => $row['date_register_doc'], 'id_agency' => $row['id_agency']);
                }
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        //Delete docs
        public function deleteDocs($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $name = $ins->{'name_doc'};
                $directorio = "../docs/$name";
                $query = "DELETE FROM agencies_docs WHERE id_agencies_docs = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error al eliminar la agencia');
                    $message = "
                    <div class='text-center'>
                        <p><small>Error al eliminar el documento</small> <i class='denied fas fa-times-circle'></i></p>
                    </div>";
                }

                unlink($directorio);
                $message = "<div class='text-center'>
                        <p><small>El archivo $name a sido eliminado correctamente</small> <i class='approved fas fa-check-circle'></i></p>
                    </div>";
                return $message;
            }
        }
    }
?>