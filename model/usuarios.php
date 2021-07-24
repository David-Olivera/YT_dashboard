<?php

    class User{

        //Search user
        public function search($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $search = $ins->{'data'};
            if (!empty($search)) {
                $query = "SELECT * FROM users  WHERE STATUS = 1 AND (username LIKE '$search%' OR id_user LIKE '$search%' OR email_user LIKE '$search%')";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error de consulta'. mysqli_error($con));
                }
                $json = array();
                while($row = mysqli_fetch_array($result)){
                    $json[] = array(
                        'id_user' => $row['id_user'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'username' => $row['username'],
                        'email_user' => $row['email_user'],
                        'role' => $row['id_role'],
                        'agencia' => $row['id_agency']
                    ); 
                }
                $jsonString = json_encode($json);
                echo $jsonString;
            }
        }

        //Add user
        public function insert($obj){
            $today = date('Y-m-d H:i:s');
            $ins = json_decode($obj);
            $status = 1;
            require_once '../config/conexion.php';
            $nom = $ins->{'first_name'};
            $ema = $ins->{'email_user'};
            $codestate= 0;
            $query2 ="";
            $nombre_usuario = $ins->{'first_name'};
            $apellido_paterno = $ins->{'last_name'};
            $email_usuario = $ins->{'email_user'};
            $username = $ins->{'username'};
            $password = $ins->{'password'};
            $newpassword = MD5($password);
            $role = $ins->{'role'};
            $query2 =  "INSERT INTO users(first_name,last_name,email_user,username,password,id_role,status)VALUES('$nombre_usuario','$apellido_paterno','$email_usuario','$username','$newpassword',$role,$status);";
            $result2 = mysqli_query($con, $query2);
            if (!$result2) {
                $message = "Error al registrar al usuario";
            }
            $message = 'El usuario '.$ins->{'first_name'}.', ha sido registrado con exito.';
            $codestate = 1;
            return json_encode(array('code' => $codestate, 'message' => $message, 'sql' => $query2));

        }

        //Get datas
        public function getData($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id = $ins->{'id'};
            $query = "SELECT * FROM users WHERE id_user = $id";
            $result = mysqli_query($con,$query);
            if(!$result){
                $message = "Error al traer al usuario";
            }
            $json=array();
            while($row=mysqli_fetch_array($result)){
                $json[]= array(
                    'id_user' => $row['id_user'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'email_user' => $row['email_user'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                    'role' => $row['id_role']
                );
            }
            $jsonStrig = json_encode($json[0]);
            echo $jsonStrig; 
        }

        //Edit user
        public function edit($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            $id_usuario = $ins->{'id'};
            $nombre_usuario = $ins->{'first_name'};
            $apellido_paterno = $ins->{'last_name'};
            $email_usuario = $ins->{'email_user'};
            $username = $ins->{'username'};
            $password = $ins->{'password'};
            $role = $ins->{'role'};
            $cheked = $ins->{'status'};
            $status = 0;
            $query = "";
            $update_password ="";
            if($password != '' && $cheked == 'true'){
                $md5_pass = md5($password);
                $update_password = ",password = '$md5_pass'";
            }
            $query ="UPDATE users SET first_name = '$nombre_usuario', last_name = '$apellido_paterno', email_user = '$email_usuario' $update_password , username = '$username', id_role = '$role' WHERE id_user = '$id_usuario'";
            $result = mysqli_query($con, $query);
            if (!$result) {
                die('Error al registrar el agencia');
                $message = "Error al editar la usuario";    
            }
            $message = 'El usuario a sido editada correctamente';
            return $message;

        }

        //Delete user
        public function delete($obj){
            $ins = json_decode($obj);
            include('../config/conexion.php');
            if (isset( $ins->{'id'})) {
                $id = $ins->{'id'};
                $status = 0;
                $query = "UPDATE users SET status = $status WHERE id_user = $id";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Error al eliminar al usuario');
                    $message = "Error al eliminar al usuario";
                }
                $message = 'El usuario a sido eliminado correctamente';
                return $message;
            }
        }
    }

?>