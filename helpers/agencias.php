<?php
    include('../model/agencias.php');
    $agency = new Agency();


    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->search($req);
    }
    
    // Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->insert($req);
    }

    //Get datas
    if (isset($_POST['single']) && $_POST['single'] == 'single') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->getData($req);
    }

    //Set Cash agency
    if (isset($_POST['setcashconf']) && $_POST['setcashconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setCashConf($req);
    }
    //Set Card agency
    if (isset($_POST['setcardconf']) && $_POST['setcardconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setCardConf($req);
    }
    //Set Paypal agency
    if (isset($_POST['setpaypalconf']) && $_POST['setpaypalconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setPaypalConf($req);
    }
    //Set Today agency
    if (isset($_POST['settodayconf']) && $_POST['settodayconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setTodayConf($req);
    }

    //Set YT agency
    if (isset($_POST['setytconf']) && $_POST['setytconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setYTConf($req);
    }

    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->delete($req);
    }

    //Docs
    if (isset($_POST['add_docs']) && $_POST['add_docs'] == 'add_docs') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->getDocs($req);
    }

    //Delete Docs
    if (isset($_POST['delete_doc']) && $_POST['delete_doc'] == 'delete_doc') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->deleteDocs($req);
    }

?>