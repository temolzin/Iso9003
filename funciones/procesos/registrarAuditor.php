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

	$username = $_POST['username'];
	$nombre = $_POST['nombre'];
	$apPat = $_POST['apPat'];
	$apMat = $_POST['apMat'];
	$email = $_POST['email'];
	$telefono = $_POST['telefono'];
	$password = $_POST['password'];

	$valoresInsertar = array(
		':nombre' => $nombre,
		':apPat' => $apPat,
		':apMat' => $apMat,
		':email' => $email,
		':telefono' => $telefono,
		':imagen' => $nombreImagen,
		':username' => $username,
		':password' => $password
	);

	$sentencia = $conex->ejecutarAccion("INSERT INTO auditor VALUES(null, :nombre, :apPat, :apMat, :email, :telefono, :imagen, :username, :password)", $valoresInsertar);
	
	
	echo 'ok';
?>