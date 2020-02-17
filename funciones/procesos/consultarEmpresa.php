<?php
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();

	$idEmpresa = $_POST['idEmpresa'];

	$query = "SELECT * FROM empresa WHERE id_empresa = " . $idEmpresa;

	$objEmpresa = null;
	foreach ($conex->consultar($query) as $key => $value) {
		$objEmpresa = $value;
		echo json_encode($objEmpresa, JSON_UNESCAPED_UNICODE);
	}


?>