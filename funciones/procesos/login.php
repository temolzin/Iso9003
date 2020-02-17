<?php 	
	session_start();
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();
	$_SESSION['auditor'] = null;

	$username = $_POST['username'];
	$password = $_POST['password'];
	$queryLogin = "SELECT * FROM auditor WHERE username='".$username. "' and password = '".$password."'";
	if($conex->consultar($queryLogin) != null) {
		echo 'ok';
		foreach ($conex->consultar($queryLogin) as $key => $value) {
			$_SESSION['auditor'] = $value;
		}
	} else {
		echo 'error';
	}

?>