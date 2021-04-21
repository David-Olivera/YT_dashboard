
<?php  
 session_start();  
 if (isset($_SESSION['id_agency'])) {
      header("location: home.php");
}else {
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../../assets/img/icon/yamevIcon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Inicio de sesión</title>
	<!-- GOOGLE FONTS -->
    <link rel="stylesheet" href="../../assets/css/style2.css">
	<!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>	
	<div class="sidenav_agencies">
        <div class="login-main-text">
            <h4>YameviTravel</h4>
            <h1>Agencias Afiliadas</h1>
            <small>Al ser miembro de nuestro programa tienes acceso al sistema y al amplio catalogo de servicios para optimizar y aumentar tus ventas.</small>
        </div>
    </div>
    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
              <form method="POST" >
                 <div class="form-group title-login-agen">
                    <h2>Iniciar Sesión</h2>
                 </div>
                 <div class="form-group">
                    <label>Email / Usuario</label>
                    <input type="text" class="form-control" name="email" value=""  placeholder="Email" >
                 </div>
                 <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" value="" placeholder="Password" >
                 </div>
                 <div class="form-group">
                   <a href="#"><i>Olvidaste tu contraseña?</i></a>
                 </div>
                 <div class="form-group">
                  <div class="row">
                       <div class="col-lg-6">
			  	        	      <button type="submit" class="btn btn-login-agencies btn-block">Ingresar</button>
                       </div>
                        <div class="col-lg-6">
                           <a href="../../login.php" class="btn btn-block btn-outline-primary">Soy Yamevi</a>
                        </div>
                    </div>
                 </div>
                 <div class="form-group">
                    <h4><?php $msg ?> </h4>
                 </div>
				<!--<div class="d-flex">
					<a href="#">Forgot your password?</a>
				</div>-->
              </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
   include('../../config/conexion.php');

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myemail = mysqli_real_escape_string($con,$_POST['email']);
      $mypassword = mysqli_real_escape_string($con,$_POST['password']); 
      $newpassword = MD5($mypassword);
      $sql = "SELECT * FROM agencies AS A
      INNER JOIN agency_payment AS AP ON AP.id_agency = A.id_agency WHERE (A.email_agency = '$myemail' or A.username = '$myemail') and A.password = '$newpassword';";
      $result = mysqli_query($con,$sql);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
      if ($result) {     
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
         $count = mysqli_num_rows($result);
         if (($myemail != '') && ($newpassword !='')) {
               if ($count == 1) {
                  if ($row['password'] == $newpassword) {
                     $_SESSION['id_agency'] = $row['id_agency'];
                     $_SESSION['name_agency'] = $row['name_agency'];
                     $_SESSION['username'] =  $row['username'];
                     $_SESSION['todaysale'] = $row['todaysale'];
                     $agency = $row['name_agency'];
                     if ($_SESSION['id_agency']) {
                        header("location: home.php");
                     }
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

      echo $message;
   }
?>