<?php
    include('../model/reservaciones.php');
    $reservas = new Reservacion();


    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $reservas->search($req);
    }

    //Search date
    if (isset($_POST['search_date']) && $_POST['search_date'] == 'search_date') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $reservas->search_date($req);
    }
    
    //Set Method Payment
    if (isset($_POST['setmethodpay']) && $_POST['setmethodpay'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $reservas->setmethodpay($req);
    }
    //Set status reservation
    if (isset($_POST['setstatusmet']) && $_POST['setstatusmet'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $reservas->setstatusres($req);
    }


?>