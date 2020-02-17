<?php
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();
	$idEmpresa = $_POST['idEmpresa'];
	$valoresDelete = array(":id_empresa" => $idEmpresa);
	//Esta sentencia es para eleiminar los certamen registrados con diferentes fechas.
	$sentenciaDelete = $conex->ejecutarAccion("DELETE FROM empresa where id_empresa = :id_empresa", $valoresDelete);
	echo 'ok';
?>