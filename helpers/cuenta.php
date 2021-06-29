<?php
    include('../model/cuenta.php');
    $account = new Account();


    //GET DATA
    if (isset($_POST['action']) && $_POST['action'] == 'get_data_account') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $account->getDataAccount($req);
    }
    
    //UPDATE DATA
    if (isset($_POST['action']) && $_POST['action'] == 'update_data') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $account->updateDatas($req);
    }


    //UPDATE CREDENTIALS
    if (isset($_POST['action']) && $_POST['action'] == 'update_credentials') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $account->updateCredentials($req);
    }


?>