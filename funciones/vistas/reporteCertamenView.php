<?php 
  session_start();
  $objEmpresa = $_SESSION['empresa'];
  require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
  $queryCertamen = "SELECT * FROM resultadoCertamen where id_empresa = " . $objEmpresa['id_empresa'];
  $observacion = "";

  $menu = new Menu($conex);
  $menu->menuHeader('reportes', 102);
  $clasePreguntasCritica = '<td class="text-light bg-success">Si</td>';
  $cumplePreguntaCritica = "";
  $totalNCM = 0;
  $cantidadNCM = 0;
  $cantidadNcmm = 0;

  if(empty($_POST) == false){
    foreach ($_POST as $key => $value) {
      if($key == 'apNCM') {
        $cantidadNCM = $value;
      }
      if($key == 'apNcmm') {
        $cantidadNcmm = $value;
      }
      if($value == "error") {
        $clasePreguntasCritica = '<td class="text-light bg-danger">No</td>';
        $cumplePreguntaCritica = false;
      } else {
        $cumplePreguntaCritica = true;
      }
    }

    $totalNCM = $cantidadNCM + ($cantidadNcmm / 4);
    $veredicto = "";
    $claseResultado = "";
    $claseVeredicto = "";
    if($totalNCM > 1) {
      $veredicto = "NO CERTIFICABLE";
      $claseVeredicto = "text-danger";
      $claseResultado = 'class="text-light bg-danger"';
    } else if ($cumplePreguntaCritica == false) {
      $veredicto = "NO CERTIFICABLE";
      $claseVeredicto = "text-danger";
      $claseResultado = 'class="text-light bg-danger"';
    } else {
      $claseResultado = 'class="text-light bg-success"';
      $claseVeredicto = "text-success";
      $veredicto = "CERTIFICABLE";
    }
    echo '<h3 class="text-center text-info">Certamen</h3><br>
    <div class="container">
    <div class="row justify-content-center">';
    echo '
    <div class="col-md-10 text-center">
    <table class="table">
    <thead class="thead-dark">
    <tr class="text-center">
    <th scope="col" colspan=2>RESULTADO</th>
    </tr>
    </thead>
    <tbody>
    <tr>  
    <td clas="col-md-8">Cumple todas las preguntas críticas</td>
    '.$clasePreguntasCritica.'
    </tr>
    <tr>
    <td>Número de NCM encontradas</td>
    <td '.$claseResultado.'>'.$totalNCM.'</td>
    </tr>
    </tbody>
    </table>
    <div class="text-center col-md-12 form-group green-border-focus">
    <h2 class="'.$claseVeredicto. '">VEREDICTO: '.$veredicto.'</h2>
    </div>
    <form id="formCertamen" name ="formCertamen">
    <input type="hidden" id="apNCM" name="apNCM" value="'.$totalNCM.'"/>
    <input type="hidden" id="cumpleCritico" name="cumpleCritico" value="'.$cumplePreguntaCritica.'"/>
    <input type="text" style="display: none" id="resultado" name="resultado" value="'.$veredicto.'" />
    <div class="text-center col-md-12 form-group green-border-focus">
    <label for="taObservacion">Observación</label>
    <textarea id="observacion" name="observacion" class="form-control" id="taObservacion" rows="3">'.$observacion.'</textarea>
    </div>
    </form>

    <div class="text-center">
    <button id="btnRegistrar" name="btnRegistrar" class="btn btn-primary">Registrar Certamen</button>
    </div><br>
    <div class="text-center">
    <a href="certamenPDFView.php"><button id="btnPDF" name="btnPDF" class="btn btn-danger"><i class="far fa-file-pdf"></i> Generar PDF</button></a>
    </div>
    </div>
    </div>
    </div>
    ';
} else if($conex->consultar($queryCertamen) != null) {
    foreach ($conex->consultar($queryCertamen) as $key => $value) {
      $fecha = $value['fecha_certamen'];
      $totalNCM = $value['ncmEncontradas'];
      $resultadoCertamen = $value['resultado'];
      $observacion = $value['observacion'];
      $cumplePreguntaCritica = $value['cumpleCritico'];
    }
    $veredicto = "";
    $claseResultado = "";
    $claseVeredicto = "";
    if($totalNCM > 1) {
      $veredicto = "NO CERTIFICABLE";
      $claseVeredicto = "text-danger";
      $claseResultado = 'class="text-light bg-danger"';
    } else if ($cumplePreguntaCritica == false) {
      $veredicto = "NO CERTIFICABLE";
      $clasePreguntasCritica = '<td class="text-light bg-danger">No</td>';
      $claseVeredicto = "text-danger";
      $claseResultado = 'class="text-light bg-danger"';
    } else {
      $claseResultado = 'class="text-light bg-success"';
      $claseVeredicto = "text-success";
      $veredicto = "CERTIFICABLE";
    }
    echo '<h3 class="text-center text-info">Certamen Registrado</h3><br>
    <div class="container">
    <div class="row justify-content-center">';
    echo '
    <div class="col-md-10 text-center">
    <table class="table">
    <thead class="thead-dark">
    <tr class="text-center">
    <th scope="col" colspan=2>RESULTADO</th>
    </tr>
    </thead>
    <tbody>
    <tr>  
    <td clas="col-md-8">Cumple todas las preguntas críticas</td>
    '.$clasePreguntasCritica.'
    </tr>
    <tr>
    <td>Número de NCM encontradas</td>
    <td '.$claseResultado.'>'.$totalNCM.'</td>
    </tr>
    </tbody>
    </table>
    <div class="text-center col-md-12 form-group green-border-focus">
    <h2 class="'.$claseVeredicto. '">VEREDICTO: '.$veredicto.'</h2>
    </div>
    <form id="formCertamen" name ="formCertamen">
    <input type="hidden" id="apNCM" name="apNCM" value="'.$cantidadNCM.'"/>
        <input type="hidden" id="cumpleCritico" name="cumpleCritico" value="'.$cumplePreguntaCritica.'"/>
    <input type="text" style="display: none" id="resultado" name="resultado" value="'.$veredicto.'" />
    <div class="text-center col-md-12 form-group green-border-focus">
    <label for="taObservacion">Observación</label>
    <textarea id="observacion" name="observacion" class="form-control" id="taObservacion" rows="3">'.$observacion.'</textarea>
    </div>
    </form>

    <div class="text-center">
    <button id="btnRegistrar" name="btnRegistrar" class="btn btn-warning">Editar Certamen</button>
    </div><br>
    <div class="text-center">
    <a href="certamenPDFView.php"><button id="btnPDF2" name="btnPDF2" class="btn btn-danger"><i class="far fa-file-pdf"></i> Generar PDF</button></a>
    </div>
    </div>
    </div>
    </div>
    ';
} else {
    echo '<h3 class="text-center text-danger">Para ver el certamen necesitas generarlo a partir del reporte de conteo</h3><br>
    <div class="container">
    <div class="row justify-content-center">';
}


  $menu->menuFooter();

?>
<script type="text/javascript">
$(document).ready(function(){
  $('#btnPDF').hide();
  $("#btnRegistrar").on("click",registrarCertamen);
  function registrarCertamen() {
      $.ajax({
        type: "POST",
        url: "../procesos/registrarCertamen.php",
        data: $('#formCertamen').serialize(),
        success: function(data) {
          if(data == 'ok') {
            Swal.fire(
              "¡Éxito!",
              "El certamen ha sido registrado exitosamente, ahora puedes generar el PDF",
              "success"
          	);
            $('#btnPDF').show();
          } else {
            Swal.fire(
              "¡Error!",
              "Ha ocurrido un error al registrar el certamen: " + data,
              "error");
          }
        }
      });
    }
  });
</script>