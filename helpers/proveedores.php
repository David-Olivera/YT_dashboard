<?php
    include('../model/proveedores.php');
    $provider = new Provider();


    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $provider->search($req);
    }
    
    // Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $provider->insert($req);
    }

    //Get datas
    if (isset($_POST['single']) && $_POST['single'] == 'single') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $provider->getData($req);
    }

    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $provider->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $provider->delete($req);
    }

    //Update flooright
    if (isset($_POST['action']) && $_POST['action'] == 'update_flooright') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $provider->updateFloorRight($req);
    }

    //Update rates
    if (isset($_POST['action']) && $_POST['action'] == 'update_rates') {
        $request = (object) $_POST;
		foreach($request->rates as $rate) {
			$provider->updateRates($rate);
		}
        $msg = "Las tarifas del proveedor han sido actualizadas correctamente";
        echo $msg;
    }
    

?>