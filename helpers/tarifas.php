<?php
    include('../model/tarifas.php');
    include('../model/tarifas_paginacion.php');
    $zones = new Zone();
    $paginacion = new Pagination();

    //Search
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $zones->search($req);
    }

    //Search agencias
    if (isset($_POST['search']) && $_POST['search'] == 'search_ag') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $zones->searchAgencie($req);
    }
    
    //Search agencias
    if (isset($_POST['search']) && $_POST['search'] == 'search_tu') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $zones->searchTureando($req);
    }
    

    //Add
    if (isset($_POST['edit']) && $_POST['edit'] == 'false') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $zones->insert($req);
    }
    
    //Get datas publico
    if (isset($_POST['single']) && $_POST['single'] == 'rate_public') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $zones->getData($req);
    }
    
    //Get datas agencias
    if (isset($_POST['single']) && $_POST['single'] == 'rate_agencie') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $zones->getData($req);
    }
    
    //Get datas tureando
    if (isset($_POST['single']) && $_POST['single'] == 'rate_tureando') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $zones->getData($req);
    }
    
    //Edit
    if (isset($_POST['edit']) && $_POST['edit'] == 'true') {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $zones->edit($req);
    }

    //Delete
    if (isset($_POST['delete']) && $_POST['delete'] == 'delete') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $zones->delete($req);
    }
    //Get rate public
    if (isset($_POST['navs']) && $_POST['navs'] == 'PUBLIC') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $paginacion->getDataPublic($req);
    }
    //Get rate agencies
    if (isset($_POST['navs']) && $_POST['navs'] == 'AGENCIES') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $paginacion->getDataAgencies($req);
    }
    //Get rate tureando
    if (isset($_POST['navs']) && $_POST['navs'] == 'TUREANDO') {
        $request = (object) $_POST;
         $req = json_encode($request);
         echo $paginacion->getDataTureando($req);
    }
?>