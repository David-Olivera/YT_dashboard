<?php 
    class Conciliation{
        function registerDeposit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d H:i:s');
            $id = mysqli_real_escape_string($con,$ins->id);
            $total_cost = mysqli_real_escape_string($con,$ins->total_cost);
            $charge = mysqli_real_escape_string($con,$ins->charge);
            $f_pago = mysqli_real_escape_string($con,$ins->f_pago);
            $concepto = mysqli_real_escape_string($con,$ins->concepto);
            $currency = mysqli_real_escape_string($con,$ins->currency);
            $id_user = mysqli_real_escape_string($con,$ins->id_user);
            $totalDeposits = $this->totalDeposits($id,$con);
            $msg ="";
            $status = 0;
            if ($totalDeposits < $total_cost) {
                $query_me = "INSERT INTO expenses(expense_amount, type_currency, concept, id_reservation, id_user, date_expense, charge_type, inaccount) VALUES('$charge', '$currency','$concepto',$id, $id_user,'$today','D','$f_pago');";
                $result_me = mysqli_query($con, $query_me);
                if ($result_me) {
                    $status = 1;
                    $sql = "SELECT * FROM change_type WHERE status = 1 ORDER BY id_change DESC LIMIT 0,1;";
                    $result_sql = mysqli_query($con, $sql);
                    if ($result_sql) {
                        $ins_sql = mysqli_fetch_object($result_sql);
                        $sql_detalles = "UPDATE reservation_details SET change_type = '$ins_sql->type_change' WHERE id_reservation like $id;";
                        $result_detalles = mysqli_query($con, $sql_detalles);
                        $totalDeposits_again = $this->totalDeposits($id,$con);
                        if ($totalDeposits_again >= $total_cost) {
                            $status = 1;
                            $query_con = "UPDATE conciliation SET status = 1 where id_reservation = $id;";
                            $result_con = mysqli_query($con, $query_con);
                            if ($result_con) {
                                $query_res = "UPDATE reservations SET status_reservation = 'COMPLETED' WHERE id_reservation like $id;";
                                $result_res = mysqli_query($con, $query_res);
                                $status = 1;
                            }
                        }
                    }
                }
            }
            return $status;
        }
        //Change status reservation
        public function setstatusres($obj){
            $arg = json_decode($obj);
            include('../config/conexion.php');
            $statusr = $arg->{'value'};
            $id = $arg->{'id'};
            $text = $arg->{'text'};
            $total_cost = $arg->{'total_cost'};
            $currency = $arg->{'currency'};
            $id_user = $arg->{'id_user'};
            $code = $arg->{'code'};
            $status = 0;
            $message ="";
            date_default_timezone_set('America/Cancun');
            $today = date('Y-m-d H:i:s');
            date_default_timezone_set('America/Cancun');
            $register = date('Y-m-d');
            $sql = "SELECT * FROM reservations WHERE id_reservation = $id;";
            $result= mysqli_query($con, $sql);
            if (isset($result)) {
                if (mysqli_num_rows($result) > 0) {
                    
                    $sql2 = "UPDATE reservations SET status_reservation = '$statusr' WHERE id_reservation = $id;";
                    $result2 = mysqli_query($con, $sql2);  
                    if ($result2) {   
                        $query_ac = "INSERT INTO activities(activity_type, activity_status, id_user, id_reservation, change_date) VALUES('STATE', '$statusr', $id_user, $id, '$today');";
                        $result_ac = mysqli_query($con, $query_ac);
                        $status = 1;
                        if ($query_ac) {
                            if ($text == 'COURTESY') {
                                $query_me = "INSERT INTO expenses(expense_amount, type_currency, concept, id_reservation, id_user, date_expense, charge_type, inaccount) VALUES('$total_cost', '$currency','CORTESIA',$id, $id_user,'$today','D','$register');";
                                $result_me = mysqli_query($con, $query_me);
                                if ($result_me) {
                                    $status = 1;
                                    $sql = "SELECT * FROM change_type WHERE status = 1 ORDER BY id_change DESC LIMIT 0,1;";
                                    $result_sql = mysqli_query($con, $sql);
                                    if ($result_sql) {
                                        $ins_sql = mysqli_fetch_object($result_sql);
                                        $sql_detalles = "UPDATE reservation_details SET change_type = '$ins_sql->type_change' WHERE id_reservation like $id;";
                                        $result_detalles = mysqli_query($con, $sql_detalles);
                                        $totalDeposits_again = $this->totalDeposits($id,$con);
                                        if ($totalDeposits_again >= $total_cost) {
                                            $status = 1;
                                            $query_con = "UPDATE conciliation SET status = 1 where id_reservation = $id;";
                                            $result_con = mysqli_query($con, $query_con);
                                            if ($result_con) {
                                                $query_res = "UPDATE reservations SET status_reservation = 'COURTESY' WHERE id_reservation like $id;";
                                                $result_res = mysqli_query($con, $query_res);
                                            }
                                        }
                                    }
                                }
                            }      
                            if ($status == 1) {
                                $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $text.";
                            }else{
                                $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                            }
                        }
                    }
                }else{
                    $sql2 = "INSERT INTO reservations(id_reservation, status_reservation) VALUES($id ,$statusr);";
                    $result2 = mysqli_query($con, $sql2);        
                    if (!$result) {
                        $message = 'Lo sentimos, ocurrio un problema en la actualización del valor seleccionado';
                    }else{
                        $message = "Se a cambiado correctamente el estado de la reservacion con ID $code a $text.";
                    }
                }
            }

            return $message;
            
        }
        
        public function totalDeposits($id, $con){
            $query = "SELECT SUM(expense_amount) as total FROM expenses WHERE id_reservation like $id and charge_type = 'D'; ";
            $result_t = mysqli_query($con, $query);
            $new_total =0;
			if ($result_t) {
				$ins_t = mysqli_fetch_object($result_t);
                if($ins_t->total){
                    $new_total = $ins_t->total;
                    return $new_total;
                }
			}else{
                return $new_total;
            }
        }
        
        function load_documents($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_conciliation = $ins->{'id_conciliation'};
            $query = "SELECT * FROM conciliation AS C INNER JOIN conciliation_docs AS CD ON C.id_conciliation = CD.id_conciliation WHERE CD.id_conciliation = $id_conciliation;";
            $json = array();
            $result = mysqli_query($con, $query);
            if (!$result) {
                $json="No tiene documentos registrados";
            }
            if (mysqli_num_rows($result)> 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $new_name_doc = substr($row['file_document'],0,43);
                    $json[] = array('id_concidocs' => $row['id_concidocs'], 'file_document' => $new_name_doc, 'file_document_completed' => $row['file_document'], 'register_date' => $row['register_date'], 'id_agency' => $row['id_agency'], 'facture' => $row['facture'], 'conci_mul' => $row['conciliation_multiple']);
                }
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }
        function upload_documents($obj){
            include('../config/conexion.php');
            $path="../../es/assets/docs/conciliaciones/";//server path
            $ins = json_decode($obj);
            $code_invoice = mysqli_real_escape_string($con,$ins->code_invoice);
            $id_reservation = mysqli_real_escape_string($con,$ins->id_reservation);
            $id_conciliation = mysqli_real_escape_string($con,$ins->id_conciliation);
            $id_agency = mysqli_real_escape_string($con,$ins->id_agency);
            $facture = mysqli_real_escape_string($con,$ins->facture);
            $today = date('Y-m-d H:i:s');
            date_default_timezone_set('America/Cancun');
            $status = 1;
            foreach($_FILES as $key){
                if($key['error'] == UPLOAD_ERR_OK ){                    
                    $name = $key['name'];
                    $new_name = $code_invoice.'_'.$name;
                    $temp = $key['tmp_name'];
                    $size= ($key['size'] / 1000)."Kb";
                    $ext = $key['type'];
                    if ($ext == "application/pdf" || $ext == "image/jpeg" || $ext == "image/jpg" || $ext == "image/png") {
                        $query_select= "SELECT * FROM conciliation_docs WHERE file_document like '$new_name' and id_conciliation like $id_conciliation;";
                        $result_select = mysqli_query($con, $query_select);
                        if ($result_select) {
                            if (mysqli_num_rows($result_select) > 0) {
                                echo "
                                    <div>
                                    <h12><strong>Archivo: $new_name</strong></h2><br />
                                    <h12><strong>Tamaño: $size</strong></h2><br />
                                    <hr>
                                    </div>
                                    <div class='text-center'>
                                        <p><small>Ya existe un archivo con el mismo nombre, favor de intentarlo más tarde</small> <i class='denied fas fa-times-circle text-danger'></i></p>
                                    </div>
            
                                ";
                                
                            }else{
                                $query = "INSERT INTO conciliation_docs(id_conciliation,file_document,register_date,facture)VALUES($id_conciliation, '$new_name', '$today', $facture);";
                                $result = mysqli_query($con, $query);
                                if ($result) {
                                    if (move_uploaded_file($temp, $path . $new_name)) {
                                    echo "
                                        <div>
                                            <h12><strong>Archivo: $new_name</strong></h2><br />
                                            <h12><strong>Tamaño: $size</strong></h2><br />
                                            <hr>
                                        </div>
                                        <div class='form-group text-center'>
                                                <p><small>El archivo a sido agregado correctamente </small> <i class='approved fas fa-check-circle text-success'></i></p>
                                        </div>
                                        ";
                                    }
                                }else{
                                    echo "
                                        <div>
                                            <h12><strong>Archivo: $new_name</strong></h2><br />
                                            <h12><strong>Tamaño: $size</strong></h2><br />
                                            <hr>
                                        </div>
                                        <div class='text-center'>
                                            <p><small>Error al registrar el archivo </small> <i class='denied fas fa-times-circle text-danger'></i></p>
                                        </div>
                
                                    ";
                                }  
                            }
                        }
                    }else{
                        echo "
                        <div>
                            <h12><strong>Archivo: $new_name</strong></h2><br />
                            <h12><strong>Tamaño: $size</strong></h2><br />
                            <hr>
                        </div>
                        <div class='text-center'>
                            <p><small>El archivo tiene una extension que no es permitida</small> <i class='denied fas fa-times-circle'></i></p>
                        </div>
        
                        ";
                        
                    }
                }else{
                    echo $key['error'];
                }
            }
        }
        function delete_documents($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_conciliation = mysqli_real_escape_string($con,$ins->{'id'});
            $id_agency = mysqli_real_escape_string($con,$ins->{'id_agency'});
            $name_doc = mysqli_real_escape_string($con,$ins->{'name_doc'});
            $directorio = "../../es/assets/docs/conciliaciones/$name_doc";
            $query = "DELETE FROM conciliation_docs WHERE id_concidocs = $id_conciliation";
            $result = mysqli_query($con, $query);
            if (!$result) {
                die('Error al eliminar la agencia');
                $message = "
                <div class='text-center'>
                    <p><small>Error al eliminar el documento</small> <i class='denied fas fa-times-circle text-danger'></i></p>
                </div>";
            }

            unlink($directorio);
            $message = "<div class='text-center'>
                    <p><small>El archivo $name_doc a sido eliminado correctamente</small> <i class='approved fas fa-check-circle text-success'></i></p>
                </div>";
            return $message;
        }
    }
?>