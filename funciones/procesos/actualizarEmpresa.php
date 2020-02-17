<?php
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();

	$nombre = $_POST['nombre'];
	$codigopostal = $_POST['codigopostal'];
	$rfc = $_POST['rfc'];
	$email = $_POST['email'];
	$telefono = $_POST['telefono'];
	$idEmpresa = $_POST['idEmpresa'];

	$valoresActualizar = array(
		':nombre' => $nombre,
		':rfc' => $rfc,
		':codigopostal' => $codigopostal,
		':email' => $email,
		':telefono' => $telefono,
		':idEmpresa' => $idEmpresa,
	);

	$sentencia = $conex->ejecutarAccion("UPDATE empresa SET nombre = :nombre, rfc = :rfc, codigopostal=:codigopostal, email = :email, telefono = :telefono WHERE id_empresa = :idEmpresa", $valoresActualizar);
	
	
	echo 'ok';
?>