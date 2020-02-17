<?php
  session_start();
  $objEmpresa = $_SESSION['empresa'];
  require_once '../../vendor/autoload.php';
  require '../procesos/Conexion.class.php';
  $nombreEmpresa = "";
  $rfcEmpresa = "";
  $conex = Conexion::getInstance();
  $queryCertamen = "SELECT distinct *, em.nombre as nombreEmpresa, au.imagen as imagenAuditor, em.imagen as imagenEmpresa, au.nombre as nombreAuditor, em.email as emailEmpresa, au.email as emailAuditor 
  FROM resultadoCertamen rc INNER JOIN empresa em on rc.id_empresa = em.id_empresa 
  INNER JOIN auditor au on rc.id_auditor = au.id_auditor 
  where rc.id_empresa = " . $objEmpresa['id_empresa'];
  $queryResultado = "SELECT * FROM resultadoCertamen where id_empresa = " . $objEmpresa['id_empresa'];
  $html = '
  <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Certamen</title>
    <link rel="stylesheet" href="../../css/styleReporteResultadoPDF.css" media="all" />
    <link rel="icon" href="../../favicon.png" type="image/x-icon">
    </head>
    <body>';
  foreach ($conex->consultar($queryResultado) as $key => $value) {
    $cumplePreguntaCritica = null;
    $claseCritico = "";
    if($value['cumpleCritico'] == false) {
      $cumplePreguntaCritica = "No";
    } else {
      $cumplePreguntaCritica = "Si";
      $claseCritico = "background-color: green;";
    }
    $fecha = $value['fecha_certamen'];
    $totalNCM = $value['ncmEncontradas'];
    $resultadoCertamen = $value['resultado'];
    $observacion = $value['observacion'];
    if($resultadoCertamen == "NO CERTIFICABLE") {
      foreach ($conex->consultar($queryCertamen) as $key => $value) {
        $imagenAuditor = $value['imagenAuditor'];
        $imagenEmpresa = $value['imagenEmpresa'];

        setlocale(LC_TIME, 'es_MX.UTF-8');
        $fecha_format = date('m/d/Y', strtotime($value['fecha_certamen']));
        $html .= '
        <header class="clearfix">
        <div class="logo" id="logo"  style="background-image: url(../../img/images/'.$imagenAuditor.')">

        </div>
        <div id="company">
        <h2 class="name">'.utf8_encode($value['nombreAuditor']). ' '. '</h2>
        <h2 class="name">'.utf8_encode($value['ap_pat']).' '. utf8_encode($value['ap_mat']).'</h2>
        <div>'.$value['telefono'].'</div>
        <div><a href="mailto:'.$value['emailAuditor'].'">'.$value['emailAuditor'].'</a></div>
        </div>
        </div>
        </header>
        <main>
        <div id="details" class="clearfix">
        <div id="client">
        <div class="to">CERTAMEN PARA:</div> 
        <h2 class="name"> 
        <div class="logoNoCert" id="logo" style="background-image: url(../../img/images/'.$value['imagenEmpresa'].')">

        </div> '.$value['nombreEmpresa'].'</h2>
        <div class="address">RFC: '.$value['RFC'].' <br>CP: '.$value['codigopostal'].'</div>
        <div class="email"><a href="mailto:'.$value['emailEmpresa'].'">'.$value['emailEmpresa'].'</a></div>
        </div>
        <div id="invoice">
        <h1 class="nocertificado">NO CERTIFICABLE</h1>
        <div class="date">Fecha de la evaluación: '.strftime("%A, %d de %B de %Y", strtotime($fecha_format)).'
        </div>
        </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
        <th scope="col" colspan=2>RESULTADOS</th>
        </tr>
        </thead>
        <tbody>
        <tr>  
        <td>Cumple todas las preguntas críticas</td>
        <td style="'.$claseCritico.'">'.$cumplePreguntaCritica.'</td>
        </tr>
        <tr>
        <td>Número de NCM encontradas</td>
        <td>'.$totalNCM.'</td>
        </tr>
        </table>
        <div id="notices">
        <div>Observaciones hechas por el auditor:</div>
        <div class="notice">'.$observacion.'</div>
        </div><br><br><br>
        <div id="thanks">______________________<br>
          Auditor<br>
          '. utf8_encode($value['nombreAuditor']) . ' ' . utf8_encode($value['ap_pat']) . ' ' . utf8_encode($value['ap_mat']) .'
        </div>
        </main>
          <br><br><br><br><br><br><br><div class="logoAuditor" id="logo" style="background-image: url(../../img/logo.png)">
          </div>
        <footer>
          Copyright &copy; TemolzinItzae 2020
        </footer>';
      }
    } else {
      setlocale(LC_TIME, 'es_MX.UTF-8');
      $fecha_format = date('m/d/Y', strtotime($value['fecha_certamen']));
      $html .= '<div style="width:800px; height:600px; padding:20px; text-align:center; border: 10px solid #787878">
      <div style="width:750px; height:550px; padding:20px; text-align:center; border: 5px solid #787878">
      <span style="font-size:40px; font-weight:bold">Certificado <br>ISO 9000-3</span>
      <br>
      <img src="../../img/certificacion.png" style="align="center"" width = "150px" height="130px" /><br>
      <span style="font-size:25px"><i>Temolzin Itzae Systems certifica a:</i></span>
      <br><br>
        <div class="logoEmpresa" id="logo" style="background-image: url(../../img/images/'.$objEmpresa['imagen'].')">
        </div><br>
      <span style="font-size:30px"><b>'.$objEmpresa['nombre'].'</b></span><br/><br>
      <span style="font-size:25px"><i>Por haber concluido satisfactoriamente la auditoria en ISO 9000-3</i></span> <br/>
      <br/>
      <span style="font-size:25px"><i>Fecha de emisión: </i></span><br>
      ';
      $html .= strftime("%A, %d de %B de %Y", strtotime($fecha_format));
      foreach ($conex->consultar($queryCertamen) as $key => $value) {
        $html .='<br><br><br>
        <span style="font-size:25px align="center""><i>_________________________________________</i></span><br>
        <span style="font-size:18px"><i>Auditor<br> </span>
        <div class="logoAuditor" id="logo"  style="background-image: url(../../img/images/'.$value['imagenAuditor'].')">
        </div>
        <br>
        <span style="font-size:18px">
        '.utf8_encode($value['nombreAuditor']). ' ' . utf8_encode($value['ap_pat']) . ' ' . utf8_encode($value['ap_mat']) .'<br>'.$value['telefono'].'<br> '. $value['emailAuditor'] .'</i></span><br>

        </div>
        </div>';
      }
    }
  }

  $html .= ' 
       </body>
        </html>';
  $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
  $nombrePDF = 'Certificado'.$objEmpresa['nombre'].'.pdf';
  $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
  $mpdf->WriteHTML($html);
  //Letra I para cargar PDF en la misma página, letra D para descargar PDF
  $mpdf->Output($nombrePDF,"D");
?>
