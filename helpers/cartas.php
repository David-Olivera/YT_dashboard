<?php
    include('../model/cartas.php');
    $hotel = new Cartas();

    //Load
    if (isset($_POST['nav']) && $_POST['nav'] != '') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $hotel->getLetters($req);
    }

    //Update Letter
    if (isset($_POST['action']) && $_POST['action'] == 'update_letter') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $hotel->updateLetter($req);
    }
?>