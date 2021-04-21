<?php
    include('../model/servicios.php');
    $servicio = new Servicio();
    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $servicio->search($req);
    }

    //Search date
    if (isset($_POST['search_date']) && $_POST['search_date'] == 'search_date') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $servicio->search_date($req);
    }

    //Change provider
    if (isset($_POST['change_provider']) && $_POST['change_provider'] == 'true') {
        $request = (object)$_POST;
        $req = json_encode($request);
        echo $servicio->change_provider($req);
    }
    //Change REP
    if (isset($_POST['change_rep']) && $_POST['change_rep'] == 'true') {
        $request = (object)$_POST;
        $req = json_encode($request);
        echo $servicio->change_rep($req);
    }
    //Add Message
    if (isset($_POST['send']) && $_POST['send'] == 'true') {
        $request = (object)$_POST;
        $req = json_encode($request);
        echo $servicio->addMessage($req);
    }
?>