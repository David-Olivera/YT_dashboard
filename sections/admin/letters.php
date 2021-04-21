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
    <title>YameviTravel - Proveedores</title>
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
    
            <?php 
                include('../../model/cartas.php');
                $letter = new Cartas;
                if (isset($_POST['esLetterID']) && isset($_POST['esytLetterTy'])) {
                    $Letter = $_POST['esLetter'];
                    $id = $_POST['esLetterID'];
                    $table = $_POST['taLetterID'];
                    $letter->updateLetter($Letter, $id, $table);
                }
                if (isset($_POST['enytLetterID']) && isset($_POST['enytLetterTy'])) {
                    $Letter = $_POST['enytLetter'];
                    $id = $_POST['enytLetterID'];
                    $table = $_POST['enytLetterTa'];
                    $letter->updateLetter($Letter, $id, $table);
                }
                if (isset($_POST['poytLetterID']) && isset($_POST['poytLetterTy'])) {
                    $Letter = $_POST['poytLetter'];
                    $id = $_POST['poytLetterID'];
                    $table = $_POST['poytLetterTa'];
                    $letter->updateLetter($Letter, $id, $table);
                }
                if (isset($_POST['estuLetterID']) && isset($_POST['estuLetterTy'])) {
                    $Letter = $_POST['estuLetter'];
                    $id = $_POST['estuLetterID'];
                    $table = $_POST['estuLetterTa'];
                    $letter->updateLetter($Letter, $id, $table);
                }
                if (isset($_POST['entuLetterID']) && isset($_POST['entuLetterTy'])) {
                    $Letter = $_POST['entuLetter'];
                    $id = $_POST['entuLetterID'];
                    $table = $_POST['entuLetterTa'];
                    $letter->updateLetter($Letter, $id, $table);
                }
                if (isset($_POST['potuLetterID']) && isset($_POST['potuLetterTy'])) {
                    $Letter = $_POST['potuLetter'];
                    $id = $_POST['potuLetterID'];
                    $table = $_POST['potuLetterTa'];
                    $letter->updateLetter($Letter, $id, $table);
                }
            ?>

        <h3 class="pb-2">Cartas</h3>
        <!-- <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="check_terminos">
            <label class="form-check-label" for="defaultCheck1">
                <small>Acepto <a href="">Términos y Condiciones</a></small>
            </label>
        </div> -->
        
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-yt-tab" data-toggle="pill" href="#pills-yt" role="tab" aria-controls="pills-yt" aria-selected="true">YameviTravel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-tu-tab" data-toggle="pill" href="#pills-tu" role="tab" aria-controls="pills-tu" aria-selected="false">Tureando</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-yt" role="tabpanel" aria-labelledby="pills-yt-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-es-tab" data-toggle="tab" href="#nav-es" role="tab" aria-controls="nav-es" aria-selected="true">Español</a>
                                <a class="nav-item nav-link" id="nav-en-tab" data-toggle="tab" href="#nav-en" role="tab" aria-controls="nav-en" aria-selected="false">Ingles</a>
                                <a class="nav-item nav-link" id="nav-po-tab" data-toggle="tab" href="#nav-po" role="tab" aria-controls="nav-po" aria-selected="false">Portugues</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active p-2" id="nav-es" role="tabpanel" aria-labelledby="nav-es-tab">
                                
                            </div>
                            <div class="tab-pane fade p-2" id="nav-en" role="tabpanel" aria-labelledby="nav-en-tab">
                                
                            </div>
                            <div class="tab-pane fade p-2" id="nav-po" role="tabpanel" aria-labelledby="nav-po-tab">
                                
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-tu" role="tabpanel" aria-labelledby="pills-tu-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-es-tu-tab" data-toggle="tab" href="#nav-es-tu" role="tab" aria-controls="nav-es-tu" aria-selected="true">Español</a>
                                <a class="nav-item nav-link" id="nav-en-tu-tab" data-toggle="tab" href="#nav-en-tu" role="tab" aria-controls="nav-en" aria-selected="false">Ingles</a>
                                <a class="nav-item nav-link" id="nav-po-tu-tab" data-toggle="tab" href="#nav-po-tu" role="tab" aria-controls="nav-po" aria-selected="false">Portugues</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active p-2" id="nav-es-tu" role="tabpanel" aria-labelledby="nav-es-tu-tab">
                                
                            </div>
                            <div class="tab-pane fade p-2" id="nav-en-tu" role="tabpanel" aria-labelledby="nav-en-tu-tab">
                                
                            </div>
                            <div class="tab-pane fade p-2" id="nav-po-tu" role="tabpanel" aria-labelledby="nav-po-tu-tab">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/ckeditor/ckeditor.js"></script>
    <script>
    
    </script>
    <?php
    include('../include/scrips.php');
    ?>
    <script src="../../assets/js/navigation.js"></script>
    
    <script src="../../assets/js/letters.js"></script>
</body>
</html>