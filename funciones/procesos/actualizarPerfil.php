<?php
	session_start();
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();

	$username = $_POST['username'];
	$nombre = $_POST['nombre'];
	$apPat = $_POST['apPat'];
	$apMat = $_POST['apMat'];
	$email = $_POST['email'];
	$telefono = $_POST['telefono'];
	$password = $_POST['password'];
	$idAuditor = $_POST['idAuditor'];

	$valoresInsertar = array(
		':nombre' => $nombre,
		':apPat' => utf8_decode($apPat),
		':apMat' => utf8_decode($apMat),
		':email' => $email,
		':telefono' => $telefono,
		':username' => $username,
		':password' => $password,
		':idAuditor' => $idAuditor
	);

	$sentencia = $conex->ejecutarAccion("UPDATE auditor SET nombre = :nombre, ap_pat = :apPat, ap_mat=:apMat, email = :email, telefono = :telefono, username = :username, password = :password WHERE id_auditor = :idAuditor", $valoresInsertar);
	//Codigo para cambiar los datos de la sesión y se muestren los datos actualizados sin cerrar sesión.
	$query = "SELECT * FROM auditor WHERE id_auditor = ".$idAuditor;
	foreach ($conex->consultar($query) as $key => $value) {
		$_SESSION['auditor'] = $value;
	}
	
	
	echo 'ok';
?>