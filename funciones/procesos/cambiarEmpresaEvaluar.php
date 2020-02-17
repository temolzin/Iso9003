<?php
	session_start();
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();
	$id_empresa = $_POST['comboEmpresa'];

	$query = "SELECT * FROM empresa WHERE id_empresa = " . $id_empresa;

	foreach ($conex->consultar($query) as $key => $value) {
		$_SESSION['empresa'] = $value;
	}

	echo 'ok';

?>