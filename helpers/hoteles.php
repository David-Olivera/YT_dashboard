<?php
    include('../model/hoteles.php');
    $hotel = new Hotel();


    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $hotel->search($req);
    }
    
    // Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->insert($req);
    }

    //Get datas
    if (isset($_POST['single']) && $_POST['single'] == 'single') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->getData($req);
    }

    //Set Cash agency
    if (isset($_POST['setcashconf']) && $_POST['setcashconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->setCashConf($req);
    }
    //Set Card agency
    if (isset($_POST['setcardconf']) && $_POST['setcardconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->setCardConf($req);
    }
    //Set Paypal agency
    if (isset($_POST['setpaypalconf']) && $_POST['setpaypalconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->setPaypalConf($req);
    }
    //Set Today agency
    if (isset($_POST['settodayconf']) && $_POST['settodayconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->setTodayConf($req);
    }

    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $hotel->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $hotel->delete($req);
    }


?>