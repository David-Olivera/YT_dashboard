<?php
   include('../config/conexion.php');

      // username and password sent from form 
      
      $myemail = mysqli_real_escape_string($con,$_POST['email']);
      $mypassword = mysqli_real_escape_string($con,$_POST['password']); 
      $newpassword = MD5($mypassword);
      $sql = "SELECT * FROM users WHERE email_user = '$myemail' and password = '$newpassword';";
      $result = mysqli_query($con,$sql);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
      if ($result) {     
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $count = mysqli_num_rows($result);
         $session = false;
         if (($myemail != '') && ($newpassword !='')) {
            if (filter_var($myemail,FILTER_VALIDATE_EMAIL)) {
               if ($count == 1) {
                  if ($row['password'] == $newpassword) {
                     $_SESSION['id_user'] = $row['id_user'];
                     $_SESSION['id_role'] = $row['id_role'];
                     $_SESSION['username'] =  $row['username'];
                     if($_SESSION["id_role"]==1) //Condicion admin
                     {
                     header("location:../sections/admin/index.php");
                     $session = true;
                     }
                     if($_SESSION["id_role"]==2) //Condicion personal
                     {
                     header("location:../sections/agencias/index.php");
                     }
                     if($_SESSION["id_role"]==3) //Condicion Usuarios
                     {
                     header("location:../sections/vendedor/index.php");
                     }
                     $message = 1;
                  }else{
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
               $message = '
               <div class=" d-flex justify-content-center">
                  <div class="alert alert-danger alert-error alert-dismissible">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <strong>Ups!</strong> Email inválido.
                  </div>
               </div>
            ' ;
            }
         }else{
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
         $message = '
         <div class=" d-flex justify-content-center">
            <div class="alert alert-danger alert-error alert-dismissible">
               <button type="button" class="close" data-dismiss="alert">&times;</button>
               <strong>Ups!</strong> El email o contraseña es incorrecto.
            </div>
         </div>
      ' ;
      }

      echo $json = json_encode(array('session' => $session, 'msg' => $message));
?>