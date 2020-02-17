<?php
	//Código para verificar al momento de registrar al usuario,
	//si ese nombre de usuario, existe en la base de datos.
	require('Conexion.class.php');
	$conex = Conexion::getInstance();

	$nombreUsuario = $_REQUEST['username'];
	$consulta = $conex->consultar("select * from auditor where username = '". $nombreUsuario."'");

	if($consulta == null) {
		echo 'true';
	} else {
		echo 'false';
	}
	$conex->cerrarConex();
?>