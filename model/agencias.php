<?php
    class Agency{

        //Search agency
        public function search($obj){
            $ins = json_decode($obj);
            session_start();
            $id_role = $_SESSION['id_role'];
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM agencies WHERE (name_agency like '$search%' OR username like '$search%') AND `status` = 1;";
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
                                $query_electronic_purse = "SELECT SUM(amount_electronic) as total, status as sta FROM electronic_purse WHERE id_agency = {$row['id_agency']};";
                                $result_electronic_purse = mysqli_query($con, $query_electronic_purse);
                                $class_ep ="btn btn-outline-success btn-sm";
                                if ($result_electronic_purse){
                                    $fila = mysqli_fetch_assoc($result_electronic_purse);
                                    if (isset($fila['total'])) {
                                        $class_ep = "btn btn-sm btn-success";
                                    }
                                }
								$checkedCash = $row2['cash'] == 1 ? 'checked="checked"' : NULL;
								$checkedToday = $row2['todaysale'] == 1 ? 'checked="checked"' : NULL;
								$checkedPaypal = $row2['paypal'] == 1 ? 'checked="checked"' : NULL;
								$checkedCard = $row2['card'] == 1 ? 'checked="checked"' : NULL;
								$checkedYT = $row2['internal_yt'] == 1 ? 'checked="checked"' : NULL;
								$checkedOPR = $row2['operadora'] == 1 ? 'checked="checked"' : NULL;
                                $json[] = array(
                                    'id_agency' => $row['id_agency'],
                                    'name_agency' => $row['name_agency'],
                                    'email_agency' => $row['email_agency'],
                                    'email_agency_pay' => $row['email_pay_agency'],
                                    'phone_agency' => $row['phone_agency'],
                                    'username' => $row['username'],
                                    'password' => $row['password'],
                                    'register_date' => $row['register_date'],
                                    'id_role' => $id_role,
                                    'class_saldo' => $class_ep,
                                    'checkedCash' => $checkedCash,
                                    'checkedToday' => $checkedToday,
                                    'checkedPaypal' => $checkedPaypal,
                                    'checkedCard' => $checkedCard,
                                    'checkedYT' => $checkedYT,
                                    'checkedOPR' => $checkedOPR
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
            $codestate= 0;
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
        

        //Change Operadora agency
        public function setOPConf($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $today = $arg->{'value'};
            $id = $arg->{'id'};
            $sql = "SELECT * FROM agency_payment WHERE id_agency = $id";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    $sql2 = "UPDATE agency_payment SET operadora = $today WHERE id_agency = $id";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = 'Se han guardado los cambios de configuración para la agencia seleccionada';
                    }
                }else{
                    $sql2 = "INSERT INTO agency_payment(id_agency, operadora) VALUES($id ,$today)";
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
                    $new_name_doc = substr($row['name_doc'],0,12);
                    $json[] = array('id_doc' => $row['id_agencies_docs'], 'name_doc' => $new_name_doc, 'name_doc_complete' => $row['name_doc'], 'date_register' => $row['date_register_doc'], 'id_agency' => $row['id_agency']);
                }
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        // Get Electronic purse Data
        public function getElectronicPurse($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id_agency'};
            $query = "SELECT * FROM electronic_purse WHERE id_agency = $id;";
            $json = array();
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) > 0) {
                $fila = "0.00";
                $agencia = "";
                $reserva = "";
                $user ="";
                $query_sum = "SELECT SUM(amount_electronic) as total FROM electronic_purse WHERE id_agency = $id;";
                $result_sum = mysqli_query($con, $query_sum);
                $sum_amount_electronic = 0.00;
                if ($result_sum){
                    $fila = mysqli_fetch_assoc($result_sum);
                    if($fila['total'] != null || $fila['total'] != ''){
                        $sum_amount_electronic = $fila['total'];
                    }
                }
                while($row = mysqli_fetch_array($result)){
                    $id_agen = $row['id_agency'];
                    $id_user = $row['id_user'];
                    $id_reservation = "";
                    $code_invoice = "";
                    $query_agencia = "SELECT * FROM agencies WHERE id_agency = $id_agen;";
                    $result_agencia = mysqli_query($con, $query_agencia);
                    if ($result_agencia) {
                        $agencia = mysqli_fetch_assoc($result_agencia);
                    }
                    if ($row['id_reservation'] != 0 && $row['id_reservation'] != "") {
                        $id_res = $row['id_reservation'];
                        $query_reservation = "SELECT * FROM reservations WHERE id_reservation = $id_res; ";
                        $result_reservation = mysqli_query($con, $query_reservation);
                        if ($result_reservation) {
                            $reserva = mysqli_fetch_assoc($result_reservation);
                        }
                        $id_reservation = $row['id_reservation'];
                        $code_invoice = $reserva['code_invoice'];
                    }
                    $query_user = "SELECT * FROM users WHERE id_user = $id_user;";
                    $result_user = mysqli_query($con, $query_user);
                    if ($result_user) {
                        $user = mysqli_fetch_assoc($result_user);
                    }
                    $json[] = array(
                        'folio' => $row['folio'],
                        'id_electronic' => $row['id_electronic'],
                        'id_agency' => $row['id_agency'],
                        'name_agency' => $agencia['name_agency'],
                        'id_reservation'=> $id_reservation,
                        'code_invoice' => $code_invoice,
                        'id_user'=> $row['id_user'],
                        'username' => $user['username'],
                        'descripcion_electronic'=> $row['descripcion_electronic'],
                        'amount_electronic' => $row['amount_electronic'],
                        'sum_amount_electronic' => $sum_amount_electronic,
                        'status' => $row['status'],
                        'date_register_electronic'=> $row['date_register_electronic']
                    );
                }
            }else{
                $json="";
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

        // Load Discount
        public function loadDiscount($obj){
            include('../config/conexion.php');
            $query = "SELECT * FROM discounts WHERE status = 1;";
            $result = mysqli_query($con, $query);
            $new_value = "0.00";
            if (isset($result)) {
                $ins = mysqli_fetch_object($result);
                if (isset($ins->amount_discounts)) {
                    $new_value = $ins->amount_discounts;
                }
            }
            return $new_value;
        }

        //Add Discount
        public function addDiscount($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $status = 0;
            $value = mysqli_real_escape_string($con, $ins->{'value'});
            $query_status = "SELECT * FROM discounts WHERE status = 1;";
            $result_status = mysqli_query($con, $query_status);
            $query = "";
            $row_cnt = mysqli_num_rows($result_status);
            if ($row_cnt > 0) {
                $query = "UPDATE discounts SET type_discounts = 'OPERATOR AGENCIES DISCOUNT', amount_discounts = $value, agency_operator = 1 WHERE status = 1;";
            }else{
                $query = "INSERT INTO discounts(type_discounts, amount_discounts, agency_operator, status)VALUES('OPERATOR AGENCIES DISCOUNT', $value, 1, 1);";
            }
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;

        }

        //Get users agency
        public function getUsersAgency($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $limit = 12;
            $id = $_POST['id'];
            if (isset($id)) {
                $id_agency =  $id;
            }
            if (isset($_POST['page_no'])) {
                $page_no = $_POST['page_no'];
            }else{
                $page_no = 1;
            }
            $offset = ($page_no-1) * $limit;
            $query = "SELECT * FROM users  WHERE id_agency = $id and status = 1 ORDER BY id_user DESC LIMIT $offset, $limit";
            $result = mysqli_query($con, $query);
            $output = "";
            $newrole ='';
            $newoutput = '';
            

            if ($result) {	
                if (mysqli_num_rows($result) > 0) {
                    $output.="
                    
                            <table class='table table-hover table-striped table-bordered table-sm' cellspacing='0' id='tablaUsuarios'>
                                <thead class='m-3'>
                                    <tr >
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th style='width:250px;'>Email</th>
                                        <th>Teléfono</th>
                                        <th>Username</th>
                                        <th class='update_users'></th>
                                        <th></th>
                                        <th></th>
                                        </tr>
                                </thead>
                                <tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                            
                            $output.="
                            <tr user-us='{$row['id_user']}' id='data_users_agency'>
                                    <td><input type='text' readonly class='form-control-plaintext pl-2' id='inp_user_agency_name' value='{$row['first_name']}'></td>
                                    <td><input type='text' readonly class='form-control-plaintext pl-2' id='inp_user_agency_last' value='{$row['last_name']}'></td>
                                    <td style='width:30px;'><input type='text' readonly class='form-control-plaintext pl-2' id='inp_user_agency_email' value='{$row['email_user']}'></td>
                                    <td><input type='text' readonly class='form-control-plaintext pl-2' id='inp_user_agency_phone' value='{$row['phone_user']}'></td>
                                    <td><input type='text' readonly class='form-control-plaintext pl-2' id='inp_user_agency_username' value='{$row['username']}'></td>
                                    <td class='text-center update_users'>
                                        <a href='#' class=' btn btn-yamevi_2 btn-sm' id='btn_update_user'><i class='fas fa-save' aria-hidden='true'></i></a>
                                    </td>
                                    <td class='text-center '>
                                        <a href='#' class=' btn btn-yamevi_2 btn-sm' id='btn_edit_user' ><i class='fas fa-edit'></i></a>
                                        <a href='#' class=' btn btn-yamevi_2 btn-sm' id='btn_cancel_edit_user'><i class='fas fa-times text-danger'></i></a>
                                    </td>
                                    <td class='text-center '>
                                        <a href='#' class=' btn btn-yamevi btn-sm' id='btn_delete_user'  user-name='{$row['first_name']}' user-last='{$row['last_name']}' ><i class='fas fa-trash-alt'></i></a>
                                    </td>
                                    
                            </tr>";
                    
                    } 
                    $output.="</tbody>
                        </table>";

                    $sql = "SELECT * FROM users  WHERE id_agency = $id and status = 1 ORDER BY id_user desc";
                    $records = mysqli_query($con, $sql);
                    $totalRecords = mysqli_num_rows($records);
                    $totalPage = ceil($totalRecords/$limit);
                    $output.="<ul class='pagination' style='margin:20px 0'>";
                    for ($i=1; $i <= $totalPage ; $i++) { 
                    if ($i == $page_no) {
                        $active = "active";
                        $btn = "btn-yamevi_2";
                    }else{
                        $active = "";
                        $btn = "";
                    }
                        $output.="<li class='page-item $active'><a class='page-link $btn' id='$i' href=''>$i</a></li>";
                    }
                    $output .= "</ul>";

                    return $output;

                }else{
                    $output.="
                    <div class='w-100 h-100'>
                        <p>No se encontro ningún usuario registrado</p>
                    </div>";
                    return $output;
                }
            }else{
                $output.="
                <div class='w-100 h-100'>
                    <p>No se encontro ningún usuario registrado</p>
                </div>";
                return $output;
            }

        }

        //Update user agency
        public function updateUsersAgency($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = mysqli_real_escape_string($con, $ins->{'id'});
            $val_name = mysqli_real_escape_string($con, $ins->{'val_name'});
            $val_last = mysqli_real_escape_string($con, $ins->{'val_last'});
            $val_email = mysqli_real_escape_string($con, $ins->{'val_email'});
            $val_phone = mysqli_real_escape_string($con, $ins->{'val_phone'});
            $val_username = mysqli_real_escape_string($con, $ins->{'val_username'});
            $stauts = 0;
            $query = "UPDATE users SET first_name = '$val_name', last_name = '$val_last', email_user = '$val_email', phone_user = '$val_phone', username = '$val_username' WHERE id_user like $id;";
            $result = mysqli_query($con, $query);
            if($result){
                $status = 1;
            }
            return $status;

        }

        public function deleteUserAgency($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = mysqli_real_escape_string($con, $ins->{'id'});
            $status =0;
            //$query = "UPDATE users SET status = 0 WHERE id_user like $id;";
            $query = "DELETE FROM users WHERE id_user like $id;";
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }

        public function addBalanceAgency($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $today = date('Y-m-d H:i:s');
            $status = 0;
            $caracteres = "0123456789";
            srand((double)microtime()*1000000);
            $rand = '';
            for($i = 0; $i < 10; $i++) {
                $rand .= $caracteres[rand()%strlen($caracteres)];
            }
            $folio = '000'.$rand;
            $id_user =  mysqli_real_escape_string($con, $ins->{'id_user'});
            $id_agency =  mysqli_real_escape_string($con, $ins->{'id_agency'});
            $motivo =  mysqli_real_escape_string($con, $ins->{'motivo'});
            $monto =  mysqli_real_escape_string($con, $ins->{'monto'});
            $query = "INSERT INTO electronic_purse(id_agency,id_user,folio,descripcion_electronic,amount_electronic,date_register_electronic) VALUES($id_agency, $id_user, '$folio' ,'$motivo', $monto, '$today');";
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }

        public function deleteBalanceAgency($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_electronic =  mysqli_real_escape_string($con, $ins->{'id_electronic'});
            $status = 0;
            $query = "DELETE FROM electronic_purse WHERE id_electronic = $id_electronic;";
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }
    }
?>