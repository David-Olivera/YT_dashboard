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

    //Set Operadores agency
    if (isset($_POST['setopconf']) && $_POST['setopconf'] != NULL) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $agency->setOPConf($req);
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
    //Load Electronic Purse
    if (isset($_POST['action']) && $_POST['action'] == 'load_electronic_purse') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->getElectronicPurse($req);
    }

    //Delete Docs
    if (isset($_POST['delete_doc']) && $_POST['delete_doc'] == 'delete_doc') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->deleteDocs($req);
    }

    // Load Discount
    if (isset($_POST['action']) && $_POST['action'] == 'get_discount') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->loadDiscount($req);
    }


    //Add Discount Operator Agencies
    if (isset($_POST['action']) && $_POST['action'] == 'discount_ao') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->addDiscount($req);
    }
    //Get users agency
    if (isset($_POST['action']) && $_POST['action'] == 'get_users_agency') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->getUsersAgency($req);
    }
    //Update users agency
    if (isset($_POST['action']) && $_POST['action'] == 'update_data_user_agency') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->updateUsersAgency($req);
    }
    //Delete users agency
    if (isset($_POST['action']) && $_POST['action'] == 'delete_data_user_agency') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->deleteUserAgency($req);
    }
    //Add balance agency
    if (isset($_POST['action']) && $_POST['action'] == 'add_balance') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->addBalanceAgency($req);
    }
    //Delete balance agency
    if (isset($_POST['action']) && $_POST['action'] == 'delete_balance') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $agency->deleteBalanceAgency($req);
    }
?>