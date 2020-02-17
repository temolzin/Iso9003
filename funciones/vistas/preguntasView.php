<?php 
  session_start();
  $objEmpresa = $_SESSION['empresa'];
	require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
  $idSubCategoria = $_GET['idsubcategoria'];
  $idCategoria = $_GET['idcategoria'];
	$menu = new Menu($conex);
	$menu->menuHeader('auditoria', $idCategoria);
  
  $query = "select * from subcategoriapregunta where id_subcategoria = " . $idSubCategoria;
  foreach ($conex->consultar($query) as $key => $value) {
    $nombreCategoria = $value['nombre'];
  }
  echo '    
  <h3 class="text-center text-info">'. utf8_encode($nombreCategoria) .'</h3><br>';

  $query = "select * from pregunta where id_subcategoria = " . $idSubCategoria;
    echo '    
    <form id="formPreguntas" name="formPreguntas">
      <div class="form-row">
        ';
  foreach ($conex->consultar($query) as $key => $value) {
    $queryCalificacion = "select * from calificacion ca inner join opcionPregunta op on 
    ca.id_opcion_pregunta = op.id_opcion_pregunta
    where id_pregunta = " . $value['id_pregunta'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $idPreguntaRespondida = 0;
    $idOpcionPreguntaRespondida = 0;
    $checkCriticoSi = "";
    $checkCriticoNo = "";
    if($conex->consultar($queryCalificacion) != null) {
      foreach ($conex->consultar($queryCalificacion) as $key => $value2) {
        $idPreguntaRespondida = $value2['id_pregunta'];
        $idOpcionPreguntaRespondida = $value2['id_opcion_pregunta'];
        if($value2['id_opcion_pregunta'] == 6) {
          $checkCriticoSi = 'checked="checked"';
        } else if($value2['id_opcion_pregunta'] == 5) {
          $checkCriticoNo = 'checked="checked"';
        }
      }
    }

    // echo '<input id="idPregunta'.$value['id_pregunta'].'" hidden name="idPregunta'.$value['id_pregunta'].'" value="'.$value['id_pregunta'].'"/>';
    if($value['critico'] == true) {
      echo '<label for="inputPregunta" class="text-danger col-sm-8 col-form-label">'.utf8_encode($value['pregunta']).'</label>';
      echo '
      <div class="col-sm-4">
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" ';
        if($value['id_pregunta'] == $idPreguntaRespondida) {
          echo $checkCriticoSi;
        } 
        echo ' name="radioCritico-'.$value['id_pregunta'].'" id="radioCriticoSi" value="1">
        <label class="form-check-label" for="radioCriticoSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" ';
        if($value['id_pregunta'] == $idPreguntaRespondida) {
          echo $checkCriticoNo;
        } 
        echo 'name="radioCritico-'.$value['id_pregunta'].'" id="radioCriticoNo" value="0">
        <label class="form-check-label" for="radioCriticoNo">No</label>
        </div>
      </div><br><br><br>';
    } else {
        echo '<label for="calificacion-'.$value['id_pregunta'].'" class="text-dark col-sm-8 col-form-label">'.utf8_encode($value['pregunta']).'</label>';
        echo ' 
        <div class="col-sm-4">
          <select name="calificacion-'.$value['id_pregunta'].'" id="calificacion-'.$value['id_pregunta'].'" class="form-control">
            <option>Selecciona</option>';
              $query = "select * from opcionPregunta where id_opcion_pregunta NOT IN(5,6)";
              foreach ($conex->consultar($query) as $key => $valueOpcion) {
                echo '<option ';
                if($value['id_pregunta'] == $idPreguntaRespondida && $idOpcionPreguntaRespondida == $valueOpcion['id_opcion_pregunta']) {
                  echo 'selected';
                }
                echo' value=' . $valueOpcion['id_opcion_pregunta'] . '>'.$valueOpcion['nombre']. '</option>';
              }
          echo '</select>
        </div><br><br><br>';
    }
  }
      echo '
          </div>
          </form>
          <div class="col-sm-12 text-center">
            <a href="subCategoriaView.php?id='.$idCategoria.'"><button id="btnRegresar" class="btn btn-danger">Regresar</button></a>
          </div><br>
          <div class="col-sm-12 text-center">
            <button id="btnRegistrar" class="btn btn-success">Registrar Calificación</button>
          </div>
      ';
?>
<?php 
	$menu->menuFooter();
?>

<script type="text/javascript">
$(document).ready(function(){
  $("#btnRegistrar").on("click",registrarPregunta);
  function registrarPregunta() {
      $.ajax({
        type: "POST",
        url: "../procesos/registrarCalificacionPregunta.php",
        data: $('#formPreguntas').serialize(),
        success: function(data) {
          if(data == 'ok') {
            Swal.fire(
              "¡Éxito!",
              "Las preguntas han sido registradas exitosamente",
              "success").then(function() {
                  window.location = "subCategoriaView.php?id=<?php echo $idCategoria; ?>";
              });
          } else if(data == 'NA') {
            Swal.fire(
              "¡Advertencia!",
              "Debes contestar al menos una pregunta para poder registrarla",
              "warning");
          } else {
            Swal.fire(
              "¡Error!",
              "Ha ocurrido un error al registrar la pregunta: " + data,
              "error");
          }
        }
      });
    }
  });
</script>