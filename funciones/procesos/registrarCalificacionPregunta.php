<?php
	session_start();
	$auditor = $_SESSION['auditor'];
	$objEmpresa = $_SESSION['empresa'];
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();

	$contador = 0;

	foreach ($_POST as $key => $value) {
		if($value != "Selecciona") {
			$contador += 1;
			$idPregunta = explode("-", $key);
			$critico = explode("radio", $key);

			//Si ya tienen un registro esas preguntas se eliminan para despues ser registradas otra vez.
			$valoresDelete = array
			(":id_pregunta"=>$idPregunta[1],
			 ":id_empresa"=>$objEmpresa['id_empresa']
			);
			$sentenciaDelete = $conex->ejecutarAccion("DELETE FROM calificacion where id_pregunta = :id_pregunta and id_empresa = :id_empresa", $valoresDelete);	

			if($idPregunta[0] == 'radioCritico') {
				if($value == 0) {
					//id_opcion_pregunta = 5 CriticoNo
					$value = 5;
				} else {
					//id_opcion_pregunta = 6 CriticoSi
					$value = 6;
				}
			}
			$valoresInsertar = array(
				':id_auditor' => $auditor['id_auditor'],
				':id_empresa' => $objEmpresa['id_empresa'],
				':id_pregunta' => $idPregunta[1],
				':id_opcion_pregunta' => $value
			);
			$sentencia = $conex->ejecutarAccion("INSERT INTO calificacion VALUES(null, :id_auditor, :id_empresa, :id_pregunta, :id_opcion_pregunta, CURDATE())", $valoresInsertar);
		}
	}
	if($contador == 0) {
		echo 'NA';
	} else {
		echo 'ok';
	}
?>