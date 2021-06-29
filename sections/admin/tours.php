<?php
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
    <title>YameviTravel - Tours</title>
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
        <div class=" d-flex justify-content-center" id="content-alert-msg">
           <div class="alert alert-info alert-msg alert-dismissible w-100">
                <p style="margin-bottom: 0;">
                    <input id="text-msg" type="text" class="sinbordefondo" value="">
                </p>   
                <button type="button" class="close" id="alert-close">&times;</button>  
           </div>
        </div>
        <h2 class="pb-2">Tours</h2>
        <!-- Button trigger modal -->
        <div class="mb-2">
            <button type="button" id="buttonadd" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
            <i class="fas fa-plus-square"></i> Nuevo tour
            </button>
        </div>
        <div class="row">
            <div class="col-lg-12" id="zoneTable">
                    <p class="text-table">Puedes dar clic sobre cualquier columna para ordenar de manera ascendente o descendente.</p>
                    <div class="card my-3" id="tour-result">
                        <div class="card-body">
                            <div class="row">
                                    <ul id="container" class="row w-100" ></ul>
                            </div>
                        </div>
                    </div>
                    <div id="table-data">
                        
                    </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Nuevo tour</h3>
                    <button type="button" class="close" id="cancelButton"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="tour-form">
                    ...
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalEditLabel">Editar tour</h3>
                    <button type="button" class="close" id="cancelButtonEdit"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="tour-form-edit">
                    ...
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    <?php
    include('../include/scrips.php');
    ?>
    <script>
        
        //No permite a los inputs ingrese letras solo numeros
        function filterFloat(evt,input){
        // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
        var key = window.Event ? evt.which : evt.keyCode;    
        var chark = String.fromCharCode(key);
        var tempValue = input.value+chark;
        if(key >= 48 && key <= 57){
            if(filter(tempValue)=== false){
                return false;
            }else{       
                return true;
            }
        }else{
            if(key == 8 || key == 13 || key == 0) {     
                return true;              
            }else if(key == 46){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        return true;
                    }
            }else{
                return false;
            }
        }
    }
    function filter(__val__){
        var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
        if(preg.test(__val__) === true){
            return true;
        }else{
        return false;
        }
        
    }
    </script>
    <script src="../../js/navigation.js"></script>
    <script src="../../js/tours.js"></script>
</body>
</html>