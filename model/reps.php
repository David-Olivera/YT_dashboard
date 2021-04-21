<?php
    class Rep{
        
        //Search rep
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM receptionists  WHERE STATUS = 1 AND (name_receptionist LIKE '%$search%' OR last_name LIKE '%$search%' OR email_receptionist LIKE '%$search%' OR id_receptionist LIKE '%$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_rep' => $row['id_receptionist'],
                        'name_rep' => $row['name_receptionist'],
                        'last_name' => $row['last_name'],
                        'email_rep' => $row['email_receptionist'],
                        'phone_rep' => $row['phone_receptionist'],
                        'notes_rep' => $row['notes_receptionist'],
                        'date_register' => $row['date_register']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Add REP
        public function insert($obj){
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'name_rep'};
            $las = $ins->{'last_name'};
            $query = "SELECT * FROM receptionists WHERE STATUS = 1 AND (name_receptionist = '$nom' AND last_name ='$las');";
            $result = mysqli_query($con, $query);
            $codestate= 0;

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $message = 'El REP '.$ins->{'name_rep'}.' ya se encuentran registrado.';
                }else{
                    $name_receptionist = $ins->{'name_rep'};
                    $last_name = $ins->{'last_name'};
                    $email_rep = $ins->{'email_rep'};
                    $phone_rep = $ins->{'phone_rep'};
                    $notes_rep = $ins->{'notes_rep'};
                    $today = date('Y-m-d H:i:s');
                    $status = 1;
                    $query2 =  "INSERT INTO receptionists(name_receptionist,last_name,email_receptionist,phone_receptionist,notes_receptionist,date_register,status)VALUES('$name_receptionist','$last_name','$email_rep','$phone_rep','$notes_rep','$today',$status);";
                    $result2 = mysqli_query($con, $query2);
                    if (!$result2) {
                        $message = "Error al registrar al REP";
                    }
                    $message = 'El REP '.$ins->{'name_rep'}.', ha sido registrado con exito.';
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
            $query = "SELECT * FROM receptionists WHERE id_receptionist = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                die('Error al editar el registro');
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_rep' => $row['id_receptionist'],
                    'name_rep' => $row['name_receptionist'],
                    'last_name' => $row['last_name'],
                    'email_rep' => $row['email_receptionist'],
                    'phone_rep' => $row['phone_receptionist'],
                    'notes_rep' => $row['notes_receptionist']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }

        //Edit REP
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_rep = $ins->{'id'};
            $name_rep = $ins->{'name_rep'};
            $last_name = $ins->{'last_name'};
            $email_rep = $ins->{'email_rep'};
            $phone_rep = $ins->{'phone_rep'};
            $notes_rep = $ins->{'notes_rep'};
            $newpassword = $password;
            $query ="UPDATE receptionists SET name_receptionist = '$name_rep', last_name = '$last_name', email_receptionist = '$email_rep', phone_receptionist = '$phone_rep', notes_receptionist = '$notes_rep' WHERE id_receptionist = '$id_rep';";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $message = "Error al editar el REP";
            }
            $message = 'El REP a sido editado correctamente';
            return $message;
        }

        
        //Delete agency
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id =  $ins->{'id'};
                $status = 0;
                $query = "UPDATE receptionists SET status = $status WHERE id_receptionist = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error al eliminar la agencia');
                    $message = "Error al eliminar al REP";
                }
                $message = 'El REP a sido eliminado correctamente';
                return $message;
            }
        }
    }
?>