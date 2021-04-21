<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_agency']) and $_POST['inp_hotel']) {
}else{
 header('location: transfers.php');
}
   $hotel = mysqli_real_escape_string($con,$_POST['inp_hotel']);
   $pasajeros = mysqli_real_escape_string($con,$_POST['inp_pasajeros']);
   $traslado = '';
   $traslado = mysqli_real_escape_string($con,$_POST['inp_traslado']);
   $name_traslado = '';
   switch ($traslado) {
        case 'RED':
            $name_traslado = 'Redondo';
            break;

        case 'SEN/AH':
         $name_traslado = 'Aeropuerto - Hotel';
         break;
 
        case 'SEN/HA':
            $name_traslado = 'Hotel - Aeropuerto';
            break;
     
        case 'REDHH':
            $name_traslado = 'Redondo / Hotel - Hotel';
            break;
    
        case 'SEN/HH':
            $name_traslado = 'Sencillo / Hotel - Hotel';
            break;

   }
   $f_llegada = '';
   if ($_POST['datepicker_star']) { 
       $f_llegada = mysqli_real_escape_string($con,$_POST['datepicker_star']);
   }
   $f_salida = '';
   if ($_POST['datepicker_end']) { 
    $f_salida = mysqli_real_escape_string($con,$_POST['datepicker_end']);
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../../assets/img/yamevIcon.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Tipo de servicio</title>
    <?php include('../include/estilos_agencies.php')?>
    <script>
        document.ready = document.getElementById("inp_traslado").value = $traslado;
    </script>
</head>
<body>    <button  id="btnToTop" title="Go to top"><i class="fas fa-angle-up"></i></button>   
    <div class="backgound_img" data-background="../../assets/img/hero/h1_hero.jpg">
        <?php include('../include/navigation_Agencies.php');?>

        <div class="row pr-3 pl-3">
            <div class="col-lg-12 pb-2">
                <button id="btn_form_search" type="button" class="btn btn-success btn-sm" data-toggle="modal">Editar Búsqueda</button>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6" id="form_search">
                <div class="card">
                    <div class="card_header text-center">
                        <h5><i class="fas fa-calendar-check pr-2"></i>R e s e r v a c i ó n</h5>
                    </div>
                    <div class="card-body">
                            <form id="form_reserva" action="transfers_results.php" method="post" > 
                                <input type="hidden" class="" value="<?php echo $_SESSION['todaysale']?>" id="inp_todaysale" >
                                <div class="form-group">
                                    <label for="inp_hotel">Hotel</label>
                                    <input list="encodings" id="inp_hotel" name="inp_hotel" placeholder="Ingresa el hotel" class="form-control form-control-sm " value="<?php echo $hotel ?>">
                                    <datalist id="encodings">
                                        <?php
                                            $query = "SELECT * FROM hotels";
                                            $result = mysqli_query($con,$query);
                                            if ($result) {
                                                while($row = mysqli_fetch_array($result)){
                                                    echo '<option  value = "'.$row['name_hotel'].'"> </option>';
                                                }
                                                
                                            }else{
                                                echo '<option value="">No hay zonas registradas</option>';
                                            }
                                        ?>
                                    </datalist>      
                                </div>
                                <div class="form-group">
                                    <label for="inp_pasajeros">Pasajeros</label>
                                    <select class="custom-select custom-select-sm" id="inp_pasajeros" name="inp_pasajeros" placeholder="Seleccione núm. de pasajeros" >
                                    <?php
                                    for($valor = 0; $valor <= 16; $valor++) {
                                        echo "<option value='$valor'";
                                        if ($pasajeros == $valor) { echo ' selected="selected"'; }
                                        echo ">$valor</option>"; ?>
                                    <?php } ?>   
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inp_traslado">Traslado</label>
                                    <select class="custom-select custom-select-sm " id="inp_traslado" name="inp_traslado">
                                        <option value="">Seleccione tipo de traslado</option>
                                        <option value="RED">Redondo</option>
                                        <option value="SEN/AH">Aeropuerto - Hotel</option>
                                        <option value="SEN/HA">Hotel - Aeropuerto</option>
                                        <option value="REDHH">Redondo / Hotel - Hotel</option>
                                        <option value="SEN/HH">Sencillo / Hotel - Hotel</option>
                                    </select>
                                </div>
                                <div class="form-group" id="content_date_star">
                                    <label id="label_date_star" for="datepicker_star">Llegada</label>
                                    <div class="input-group">
                                        <input type="text" id="datepicker_star" name="datepicker_star" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date" value="<?php echo $f_llegada ?>">
                                        <div class="input-group-append mr-2">
                                            <span class="input-group-text" id="date"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group pb-2" id="content_date_end">
                                    <label for="datepicker_end">Salida</label>
                                    <div class="input-group">
                                        <input type="text" id="datepicker_end" name="datepicker_end" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date" value="<?php echo $f_salida ?>">
                                        <div class="input-group-append mr-2">
                                            <span class="input-group-text" id="date"><i class="far fa-calendar-minus"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn_animation btn btn-block btn-yamevi_2"  id="btn_search_reserva"><span>B u s c a r </span></button>
                                <button type="submit" class="btn btn-block btn-outline-yamevi"  id="btn_cancel_search"><span>C a n c e l a r </span></button>
                            </form>

                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-6" id="results_search">
                <div>
                    <h1><?php echo $hotel ?></h1>
                </div>
                <div>
                    <h1><?php echo $pasajeros ?></h1>
                </div>
                <div>
                    <h1><?php echo $traslado ?></h1>
                </div>
                <div>
                    <h1><?php echo $f_llegada ?></h1>
                </div>
                <div>
                    <h1><?php echo $f_salida ?></h1>
                </div>
            </div>
        </div>
    </div>
</body>
    <?php include('../include/footer_agencies.php')?>
    <?php include('../include/scrips_agencies.php')?>

</html>