<?php 
    
    include('../model/tipo_cambio.php');
    $exchange = new Exchange();


    //get exchange
    if (isset($_POST['action']) && $_POST['action'] == 'get_exchange_rate') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $exchange->getExchangeRate($req);
    }
    //get exchange
    if (isset($_POST['action']) && $_POST['action'] == 'update_exchange') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $exchange->updateExchangeRate($req);
    }
?>