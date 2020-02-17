<?php
	session_start();
	$auditor = $_SESSION['auditor'];
	$objEmpresa = $_SESSION['empresa'];
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();
	$veredicto = $_POST['resultado'];
	$observacion = $_POST['observacion'];
	$ncmEncontradas = $_POST['apNCM'];
	$cumpleCritico = $_POST['cumpleCritico'];
	foreach ($_POST as $key => $value) {
		//Si ya tienen un registro esas preguntas se eliminan para despues ser registradas otra vez.
		$valoresDelete = array(":id_empresa" => $objEmpresa['id_empresa']);
		//Esta sentencia es para eleiminar los certamen registrados con diferentes fechas.
		// $sentenciaDelete = $conex->ejecutarAccion("DELETE FROM resultadoCertamen where id_empresa = :id_empresa and fecha_certamen = CURDATE()", $valoresDelete);	
		$sentenciaDelete = $conex->ejecutarAccion("DELETE FROM resultadoCertamen where id_empresa = :id_empresa", $valoresDelete);

		$valoresInsertar = array(
			':id_auditor' => $auditor['id_auditor'],
			':id_empresa' => $objEmpresa['id_empresa'],
			':resultado' => $veredicto,
			':ncmEncontradas' => $ncmEncontradas,
			':cumpleCritico' => $cumpleCritico,
			':observacion' => $observacion
		);
		$sentencia = $conex->ejecutarAccion("INSERT INTO resultadoCertamen VALUES(null, :id_auditor, :id_empresa, :resultado, :observacion,
			:ncmEncontradas, :cumpleCritico, 
			CURDATE())", $valoresInsertar);
	}
	
	echo 'ok';
?>