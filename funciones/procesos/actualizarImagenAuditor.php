<?php
	session_start();
	require 'Conexion.class.php';
	$conex = Conexion::getInstance();

	$imagen = $_FILES["imagen"];
    $nombreImagen = $imagen["name"];
    $tipoImagen = $imagen["type"];
    $carpetaImagen = "../../img/images/";
    $ruta_provisional = $imagen["tmp_name"];

    if ($tipoImagen != 'image/jpg' && $tipoImagen != 'image/jpeg' && $tipoImagen != 'image/png' && $tipoImagen != 'image/gif')
    {
      echo 'El archivo no es una imagen';
    }
    else
    {
        copy($ruta_provisional, $carpetaImagen.$nombreImagen);
    }

	$idAuditor = $_POST['idAuditor'];

	$valoresActualizar = array(
		':imagen' => $nombreImagen,
		':idAuditor' => $idAuditor
	);

	$sentencia = $conex->ejecutarAccion("UPDATE auditor SET imagen = :imagen WHERE id_auditor = :idAuditor", $valoresActualizar);
	//Codigo para cambiar los datos de la sesión y se muestren los datos actualizados sin cerrar sesión.
	$query = "SELECT * FROM auditor WHERE id_auditor = ".$idAuditor;
	foreach ($conex->consultar($query) as $key => $value) {
		$_SESSION['auditor']['imagen'] = $value['imagen'];
	}
	
	
	echo 'ok';
?>