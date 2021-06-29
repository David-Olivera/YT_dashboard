<?php
   class Login{
        public function login_access($obj){
            include('../config/conexion.php');
           $_SESSION = array();
            session_start();
           $req =json_decode($obj);
            $newemail =$req->{'username'};
            $newpassword = $req->{'password'};
            $myemail = mysqli_real_escape_string($con,$newemail);
            $mypassword = mysqli_real_escape_string($con,$newpassword); 
           $newpassword = MD5($mypassword);
           $sql = "SELECT * FROM users WHERE (email_user = '$myemail' or username = '$myemail') and password = '$newpassword';";
           $result = mysqli_query($con,$sql);
           $status = 0;
           $message = "";
           $json=array();
           // If result matched $myusername and $mypassword, table row must be 1 row
           if ($result) {     
              $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
              $count = mysqli_num_rows($result);
              $session = false;
              if ($row['id_role'] == 1 || $row['id_role'] == 2 || $row['id_role'] == 3) {
                 if (($myemail != '') && ($newpassword !='')) {
                     if ($count == 1) {
                          if ($row['password'] == $newpassword) {
                             $_SESSION['id_user'] = $row['id_user'];
                             $_SESSION['id_role'] = $row['id_role'];
                             $_SESSION['username'] =  $row['username'];
                             $status = 1;
                          }else{
                           $status = 0;
                             $message = '
                             <div class=" d-flex justify-content-center">
                                <div class="alert alert-danger alert-error alert-dismissible">
                                   <button type="button" class="close" data-dismiss="alert">&times;</button>
                                   <strong>Error!</strong> Verifique contraseña.
                                </div>
                             </div>
                          ' ;
                          }
                     }else{
                        $status = 0;
                          $message = '
                             <div class=" d-flex justify-content-center">
                                <div class="alert alert-danger alert-error alert-dismissible">
                                   <button type="button" class="close" data-dismiss="alert">&times;</button>
                                   <strong>Ups!</strong> El email o contraseña es incorrecto.
                                </div>
                             </div>
                          ' ;
                     }
                 }else{
                  $status = 0;
                    $message = '
                       <div class=" d-flex justify-content-center">
                          <div class="alert alert-danger alert-error alert-dismissible">
                             <button type="button" class="close" data-dismiss="alert">&times;</button>
                             <strong>Ups!</strong> Debe llenar todos los campos.
                          </div>
                       </div>
                    ' ;
                 }
              }else{
               $status =0;
               $message = '
                  <div class=" d-flex justify-content-center">
                     <div class="alert alert-danger alert-error alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Ups!</strong> Lo sentimos tus credenciales no son permitidas.
                     </div>
                  </div>
               ' ;
              }
           }else{
            $status = 0;
              $message = '
              <div class=" d-flex justify-content-center">
                 <div class="alert alert-danger alert-error alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Ups!</strong> El email o contraseña es incorrecto.
                 </div>
              </div>
           ' ;
           }
           $json[]=array(
            'msg' =>$message, 
            'status' => $status
            );
         mysqli_close($con);
         $jsonStrig = json_encode($json[0]);
         echo $jsonStrig; 
        }
        
   }
?>