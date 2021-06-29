<?php
    include('../model/conciliaciones.php');
    $conciliation = new Conciliation();
    include('../model/reservaciones.php');
    $reservas = new Reservacion();

    //ADD PAY 
    if (isset($_POST['action']) && $_POST['action'] == 'addPay') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $conciliation->registerDeposit($req);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'load_docs') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $conciliation->load_documents($req);
    }
    //Set status reservation
    if (isset($_POST['setstatusmet']) && $_POST['setstatusmet'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $reservas->setstatusres($req);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'upload_docs') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $conciliation->upload_documents($req);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'delete_doc') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $conciliation->delete_documents($req);
    }

?>