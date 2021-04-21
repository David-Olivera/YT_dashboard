<?php
require_once '../../config/conexion.php';
session_start();
if (isset($_SESSION['id_user'])) {
  if ($_SESSION["id_role"] == 1) //Condicion admin
  {
  } else {
    header('location: ../../login.php');
  }
} else {
  header('location: ../../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YameviTravel - Reservaciones</title>
    <!-- FontAwesome Styles-->
    <script src="https://kit.fontawesome.com/48e49b4629.js" crossorigin="anonymous"></script>
    <?php
    include('../include/estilos.php');
    ?>
</head>
<body>
    <div class="wrapper">
        <?PHP
            include('../include/navigation.php');
        ?>
    
        <div class=" d-flex justify-content-center">
           <div class="alert alert-success alert-msg alert-dismissible w-100">
                <div class="row">      
                    <div class="pl-3 pt-2">
                        <i class="far fa-fw fa-bell"></i>
                    </div> 
                    <div class="p-1 w-75">
                        <p style="margin-bottom: 0;">
                            <input id="text-msg" type="text" class="sinbordefondo form-control-sm" value="" disabled>
                        </p>   
                    </div>        
                    <button type="button" class="close" id="alert-close">&times;</button>  
                </div>
           </div>
        </div>
        <h3 class="pb-2">Reservaciones</h3>
        <div class="row">
            <!-- <div class="col-lg-12">
                <p><small>Debido a la cantidad de registros existentes, el sistema pueda demorar unos segundos la busqueda, favor de espere.</small></p>
            </div> -->
            
            <div class="col-lg-3">
                <form class="form-inline" id="form-search" role="form">
                    <div class="flex-fill mr-2">
                        <input type="search" name="search" id="search" placeholder="Escribe ID o Cliente" class="form-control form-control-sm w-100" label="Search this site">
                    </div>
                    <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button><br>  
                </form>
                <small id="search" class="form-text text-muted">Búsqueda por ID o Cliente.</small>
            </div>      
            <div class="col-lg-3">
                <form class="form-inline" id="form-search-agency" role="form">
                    <div class="flex-fill mr-2">
                        <input list="agencies" name="agencies" id="name_agency" type="text" class="form-control form-control-sm w-100" placeholder="Elige una agencia">
                        <datalist id="agencies">
                            <?php
                            $query = "SELECT * FROM agencies";
                            $result = mysqli_query($con,$query);
                            if ($result) {
                                while($row = mysqli_fetch_array($result)){
                                    echo '<option value = "'.$row['name_agency'].'"> </option>';
                                }
                                
                            }else{
                                echo '<option value="">No hay zonas registradas</option>';
                            }
                            ?>
                        </datalist>
                    </div>
                    <button class="btn btn-black btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button><br>  
                </form>
                <small id="search" class="form-text text-muted">Búsqueda por Agencia.</small>
            </div>
            <div class="col-lg-5">
                <form id="form-date" class="form-inline" role="form">
                    <div class="input-group">
                        <input type="text" id="datepicker_star" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                        <div class="input-group-append mr-2">
                            <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                        </div>
                        <br/>
                    </div>
                    <br>
                    <div id="fecha_end">
                        <div class="input-group">
                            <input type="text" id="datepicker_end" autocomplete="off" placeholder="Selecciona una fecha" class="form-control form-control-sm" aria-describedby="date">
                            <div class="input-group-append mr-2">
                                <span class="input-group-text" id="date"><i class="fas fa-calendar-minus"></i></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-black my-2 btn-sm my-sm-0" type="submit"><i class="fas fa-search"></i></button>
                    
                    <div class="ml-2 form-check form-check">
                        <input class="form-check-input" type="checkbox" id="radiob" value="0">
                        <small id="date" class="form-text text-muted">Búsqueda por rango de fechas.</small> 
                    </div>
                </form>
            </div>   
            <div class="col-lg-12" id="resultSearch">
                <div class="card my-3" id="result-search">
                        <div class="row">
                                <ul id="container" class="row w-100" ></ul>
                        </div>
                </div>
                <div class="navs pt-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="reserved-tab" data-toggle="tab" value="hola" href="#reserved" role="tab" aria-controls="reserved" aria-selected="true">RESERVED</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">COMPLETED</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="noshow-tab" data-toggle="tab" href="#noshow" role="tab" aria-controls="noshow" aria-selected="false">NO SHOW</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">CANCELLED</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="refunded-tab" data-toggle="tab" href="#refunded" role="tab" aria-controls="refunded" aria-selected="false">REFUNDED</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="reserved" role="tabpanel" aria-labelledby="reserved-tab">
                            <br/>
                            
                            <div id="table-data-re">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <br/>
                            <div id="table-data-co">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="noshow" role="tabpanel" aria-labelledby="noshow-tab">
                            <br/>
                            
                            <div id="table-data-no">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            <br/>
                            
                            <div id="table-data-ca">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="refunded" role="tabpanel" aria-labelledby="refunded-tab">
                            <br/>
                            
                            <div id="table-data-ref">
                                
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
    </div>
	<!--PAGO RESERVACION -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Registrar Pago</h4>
					<button type="button" class="close" id="cancelButton"  aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <form name="frmSetCharge" id="frmSetCharge" method="post" action="">
                        <div class="form-row">
                            <div class="col">
                                <label>Gasto</label>
                                <input type="text" class="form-control" name="inp_charge" id="inp_charge" placeholder="$0.00">
                            </div>
                            <div class="col">
                                <label>Moneda</label>
                                <input type="text" class="form-control" name="inp_currency" id="inp_currency" readonly="readonly" value="MXN">
                            </div>
                        </div>
                        <div class="form-group">
							<label>Concepto</label>
							<input type="text" class="form-control" name="inp_concept" id="inp_concept">
                        </div>
                        <div class="form-group">
							<input type="checkbox" name="inp_paleteo" id="inp_paleteo" >
							<span>Paleteo</span>
                        </div>
                        <div class="form-group">
							<input type="submit" class="btn btn-info" id="btnSetCharge" value="REGISTRAR" />
							<input type="hidden" name="inp_sale" id="inp_sale">
							<input type="hidden" name="inp_user" id="inp_user" value="<?php echo $_SESSION['IDsesion']; ?>">
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    <script src="../../assets/js/reservations.js"></script>
</body>
</html>