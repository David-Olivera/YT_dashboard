
<?php  
 session_start();  
 if (isset($_SESSION['id_user'])) {
   if($_SESSION["id_role"]) //Condicion admin
     {
      header("location: sections/admin/index.php");
     }
}else {
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/icon/yamevIcon.png">
    <title>YameviTravel - Administrativo</title>
	<!-- GOOGLE FONTS -->
    <link rel="stylesheet" href="assets/css/style2.css">
	<!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>	
	<div class="sidenav">
        <div class="login-main-text">
            <h4>YameviTravel</h4>
            <h1>Colaboradores</h1>
            <small>Cancún tours and fun.</small>
        </div>
    </div>
    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
              <form method="POST" >
                 <div class="form-group title-login-col">
                    <h2>Iniciar Sesión</h2>
                 </div>
                 <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" id="email" value=""  placeholder="Email" >
                 </div>
                 <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" >
                 </div>
                 <div class="form-group">
                   <a href="#"><i>Olvidaste tu contraseña?</i></a>
                 </div>
                 <div class="form-group">
                    <div class="row">
                       <div class="col-lg-6">
                          <button type="submit" class="btn btn-login-colaborador btn-block" id="btn_login">Ingresar</button>
                       </div>
                        <div class="col-lg-6">
                           <a href="../es/index.php" class="btn btn-block btn-outline-primary">Soy una agencia</a>
                        </div>
                    </div>
                 </div>
                 
                 <div class="form-group" id="error_msg">
                 </div>
				<!--<div class="d-flex">
					<a href="#">Forgot your password?</a>
				</div>-->
              </form>
            </div>
        </div>
    </div>
</body>
    <script src="assets/js/login.js"></script>
</html>
