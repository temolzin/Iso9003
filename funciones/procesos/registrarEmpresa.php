<?php
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

	$rfc = $_POST['rfc'];
	$nombre = $_POST['nombre'];
	$codigoPostal = $_POST['codigopostal'];
	$email = $_POST['email'];
	$telefono = $_POST['telefono'];

	$valoresInsertar = array(
		':nombre' => $nombre,
		':rfc' => $rfc,
		':codigoPostal' => $codigoPostal,
		':email' => $email,
		':telefono' => $telefono,
		':imagen' => $nombreImagen
	);

	$sentencia = $conex->ejecutarAccion("INSERT INTO empresa VALUES(null, :nombre, :rfc, :codigoPostal, :email, :telefono, :imagen)", $valoresInsertar);
	
	
	echo 'ok';
?>