<?php 
    class Account{
        public function getDataAccount($obj){
            include('../config/conexion.php');
            $ins = json_decode($obj);
            $id = $ins->id;
            $new_id_user = mysqli_real_escape_string($con,$id);
            $query = "SELECT * FROM users WHERE id_user = $new_id_user;";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $mej ="Error al traer los datos  de la cuenta";
                return $mej;
            }
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $jsonString = json_encode($row);
            mysqli_close($con);
            return $jsonString;
        }
        //Updata datas account
        public function updateDatas($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $new_id = mysqli_real_escape_string($con, $ins->id);
            $name_user = mysqli_real_escape_string($con, $ins->name_user);
            $last_user = mysqli_real_escape_string($con, $ins->last_user);
            $email_user = mysqli_real_escape_string($con, $ins->email_user);
            $phone_user = mysqli_real_escape_string($con, $ins->phone_user);
            
            $status = 0;
            $query = "UPDATE users SET first_name = '$name_user', last_name = '$last_user', email_user = '$email_user', phone_user = '$phone_user' WHERE id_user = $new_id;";
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }
        //Update credentials account
        public function updateCredentials($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $new_id = mysqli_real_escape_string($con, $ins->id);
            $new_username = mysqli_real_escape_string($con, $ins->username);
            $new_password = mysqli_real_escape_string($con, $ins->password);
            $cheked = $ins->status;
            $status = 0;
            $query = "";
            if($new_password != '' && $cheked == 'true'){
                $md5_pass = md5($new_password);
                $query = "UPDATE users SET username = '$new_username', password = '$md5_pass' WHERE id_user = $new_id;";
            }else{
                $query = "UPDATE users SET username = '$new_username' WHERE id_user = $new_id;";
            }
            $result = mysqli_query($con, $query);
            if ($result) {
                $status = 1;
            }
            return $status;
        }

    }
?>