<?php 
  session_start();
  $objEmpresa = $_SESSION['empresa'];
	require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
  $idCategoria = $_GET['id'];
	$menu = new Menu($conex);
	$menu->menuHeader('auditoria', $idCategoria);
  $query = "select * from categoriapregunta where id_categoria = " . $idCategoria;
  foreach ($conex->consultar($query) as $key => $value) {
    $nombreCategoria = $value['nombre'];
  }

  $query = "select * from subcategoriapregunta where id_categoria = " . $idCategoria;
  echo '    
  <h3 class="text-center text-info">'. utf8_encode($nombreCategoria) .'</h3><br>
    <div class="col-md-12">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Subcategoria</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
        ';
  foreach ($conex->consultar($query) as $key => $value) {
    $num = $key + 1;
    $queryPreguntasRegistradas = "select count(*) from pregunta where id_subcategoria = " . $value['id_subcategoria'];
    $queryPreguntasCalificacion = "select count(*) from calificacion ca 
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta 
    inner join subcategoriapregunta sc on pr.id_subcategoria = sc.id_subcategoria 
    where pr.id_subcategoria = " . $value['id_subcategoria'] . ' and id_empresa = '.$objEmpresa['id_empresa'];
    $numPreguntas = 0;
    $numPreguntasCalificacion = 0;
    foreach ($conex->consultar($queryPreguntasRegistradas) as $key2 => $valor) {
      $numPreguntas = $valor[0];
    }
    foreach ($conex->consultar($queryPreguntasCalificacion) as $key3 => $valor2) {
      $numPreguntasCalificacion = $valor2[0];
    }

    $urlImagen = "";
    if($numPreguntasCalificacion == 0) {
      $urlImagen = "error.png";
    } else if($numPreguntas == $numPreguntasCalificacion) {
      $urlImagen = "ok.png";
    } else if($numPreguntas != $numPreguntasCalificacion) {
      $urlImagen = "warning.png";
    } 

    echo 
    '
          <tr>
            <th scope="row">'.$num.'</th>
            <td><a href="preguntasView.php?idsubcategoria='.$value['id_subcategoria'].'&idcategoria='.$idCategoria.'"><button type="button" class="btn btn-primary">'.utf8_encode($value['nombre']).'</button></a></td>
            <td><img width="46px" heigth="46px" src="../../img/icon/'.$urlImagen.'" class="img-responsive"></td>
          </tr>';
  }
  echo '
        </tbody>
      </table>
    </div>
  ';
?>

<?php 
	$menu->menuFooter();
?>