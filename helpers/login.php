<?php 
    include('../model/login.php');
    $login = new Login();

    	//Login
	if(isset($_POST['username']) && $_POST['username'] != NULL) {
		$request = (object) $_POST;
		$req = json_encode($request);
		echo $login->login_access($req);
	}
?>