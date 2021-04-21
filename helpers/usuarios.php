<?php
    include('../model/usuarios.php');
    $user = new User();


    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $user->search($req);
    }
    
    // Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $user->insert($req);
    }

    //Get datas
    if (isset($_POST['single']) && $_POST['single'] == 'single') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $user->getData($req);
    }

    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $user->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $user->delete($req);
    }


?>