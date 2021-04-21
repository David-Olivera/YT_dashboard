<?php
    include('../model/traslados.php');
    $traslado = new Transfer();

    if (isset($_POST['search_traslado']) && $_POST['search_traslado'] == true) {
        $request = (object) $_POST;
        $req = json_encode($request);
        echo $traslado->search_traslado($req);
    }
?>