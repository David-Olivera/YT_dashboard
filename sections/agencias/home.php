
<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_agency'])) {
}else{
 header('location: index.php');
}
$todaysale = 0;
if (isset($_SESSION['todaysale'])) {
    $todaysale = $_SESSION['todaysale'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Traslados</title>
    <?php include('../include/estilos_agencies.php')?>
</head>
<body>
    
<button  id="btnToTop" title="Go to top"><i class="fas fa-angle-up"></i></button> 
    <div class="backgound_img" data-background="../../assets/img/hero/h1_hero.jpg">
        <?php include('../include/navigation_Agencies.php');?>
        <div class="content_home_1">
            <div class="row">
                <div class="col-lg-6 col-md-12 text_home_0">
                    <div class="text_home_1 ">
                        <p data-animation="fadeInLeft" data-delay=".2s">Es bueno tenerte de vuelta. Bienvenido</p>
                        <h1 data-animation="fadeInLeft" data-delay=".5s"><?php echo $_SESSION['name_agency']?></h1><br>
                        <!-- Hero Btn -->
                        <a href="transfers.php" class="btn btn-yamevi" data-animation="fadeInLeft" data-delay=".8s">Reservar Ahora</a><br>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <!-- about-img -->
                    <div class="content_img_1">
                            <img src="../../assets/img/hero/back_1.png"   alt="">
                    </div>
                </div>
            </div>
        </div>
        <?php include('../include/footer_agencies.php')?>
    </div>
    <?php include('../include/scrips_agencies.php')?>
    
</body>
</html>