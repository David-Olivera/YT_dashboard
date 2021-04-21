<?php
    include('../model/amenidades.php');
    $amenity = new Amenity();


    //Delete
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $amenity->search($req);
    }
    
    // Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $amenity->insert($req);
    }

    //Get datas
    if (isset($_POST['single']) && $_POST['single'] == 'single') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $amenity->getData($req);
    }
    
    //Get datas
    if (isset($_POST['single_img']) && $_POST['single_img'] == 'single_img') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $amenity->getData($req);
    }

    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $amenity->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $amenity->delete($req);
    }
    //Delete img
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete_img') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $amenity->deleteImg($req);
    }


?>