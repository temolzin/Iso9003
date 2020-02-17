<?php 
  session_start();
  $objEmpresa = $_SESSION['empresa'];
  require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
  //Cadena para poner los ID en las gráficas
  $cadenaID = "";
  //Cadena para poner los valores de puntuación obtenida
  $puntuacionObtenida = 0;
  $cadenaPuntuacionObtenida = "";  
  //Cadena para poner los valores de puntuación máxima
  $puntuacionMaxima = 0;
  $cadenaPuntuacionMaxima = "";

  $queryCertamen = "SELECT * FROM resultadoCertamen where id_empresa = " . $objEmpresa['id_empresa'];
  $observacion = "";

  $menu = new Menu($conex);
  $menu->menuHeader('reportes', 103);
  $clasePreguntasCritica = '<td class="text-light bg-success">Si</td>';
  $cumplePreguntaCritica = "";

  $query = "select count(*) from pregunta";
  $totalPreguntas = 0;
  foreach ($conex->consultar($query) as $key => $value) {
    $totalPreguntas = $value[0];
  }

  $queryCalificacion = "select count(*) from calificacion";
  $totalPreguntasContestadas = 0;
  foreach ($conex->consultar($queryCalificacion) as $key => $value) {
    $totalPreguntasContestadas = $value[0];
  }

  if($totalPreguntasContestadas != $totalPreguntas) {
    //echo '<div class="col-md-12"><h4 class="text-center text-danger">Para poder ver el reporte necesitas completar todos los cuestionarios<h4></div>';
  }

  echo '
  <h1 class="h3 text-center mb-0 text-info text-black-800">Gráficas</h1><br>
    <div class="col-md-8 mx-auto table-responsive-sm">
    <table class="table table-bordered text-dark">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Apartado</th>
          <th scope="col">Puntuación Obtenida</th>
          <th scope="col">Puntuación Máxima</th>
        </tr>
      </thead>
      <tbody>
        ';


  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 1)
  //*********************************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=1";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=1";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 1 para poner el titulo
    if($value['id_subcategoria'] == 1) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=4>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    $puntuacionObtenida = $totalPuntuacion;
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    $puntuacionMaxima = $totalPuntuacionMax;
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }
    //Se asignan variables para la creación de las gráficas
    $cadenaID .= "'".$value['id_subcategoria']."',";
    $cadenaPuntuacionObtenida .= "'".$puntuacionObtenida."',";
    $cadenaPuntuacionMaxima .= "'".$puntuacionMaxima."',";

    echo 
    '
          <tr>
            <td>'.$value['id_subcategoria'].'</td>       
            <td class="text-center">'.utf8_encode($value[2]).'</td>  
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
    ';
          echo'</tr>';
  }  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 2)
  //*********************************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=2";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=2";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 6 para poner el titulo
    if($value['id_subcategoria'] == 6) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=4>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    $puntuacionObtenida = $totalPuntuacion;
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    $puntuacionMaxima = $totalPuntuacionMax;
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }

    //Se asignan variables para la creación de las gráficas
    $cadenaID .= "'".$value['id_subcategoria']."',";
    $cadenaPuntuacionObtenida .= "'".$puntuacionObtenida."',";
    $cadenaPuntuacionMaxima .= "'".$puntuacionMaxima."',";

    echo 
    '
          <tr>
            <td>'.$value['id_subcategoria'].'</td>       
            <td class="text-center">'.utf8_encode($value[2]).'</td>  
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
    ';
          echo'</tr>';
  }  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 3)
  //*********************************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=3";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=3";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 10 para poner el titulo
    if($value['id_subcategoria'] == 10) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=4>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    } 
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    $puntuacionObtenida = $totalPuntuacion;
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    $puntuacionMaxima = $totalPuntuacionMax;
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }

    //Se asignan variables para la creación de las gráficas
    $cadenaID .= "'".$value['id_subcategoria']."',";
    $cadenaPuntuacionObtenida .= "'".$puntuacionObtenida."',";
    $cadenaPuntuacionMaxima .= "'".$puntuacionMaxima."',";

    echo 
    '
          <tr>
            <td>'.$value['id_subcategoria'].'</td>       
            <td class="text-center">'.utf8_encode($value[2]).'</td>  
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
    ';
          echo'</tr>';
  }  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 4)
  //*********************************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=4";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=4";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 11 para poner el titulo
    if($value['id_subcategoria'] == 11) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=4>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    $puntuacionObtenida = $totalPuntuacion;
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    $puntuacionMaxima = $totalPuntuacionMax;
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }

    //Se asignan variables para la creación de las gráficas
    $cadenaID .= "'".$value['id_subcategoria']."',";
    $cadenaPuntuacionObtenida .= "'".$puntuacionObtenida."',";
    $cadenaPuntuacionMaxima .= "'".$puntuacionMaxima."',";

    echo 
    '
          <tr>
            <td>'.$value['id_subcategoria'].'</td>       
            <td class="text-center">'.utf8_encode($value[2]).'</td>  
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
    ';
          echo'</tr>';
  }  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 5)
  //*********************************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=5";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=5";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 17 para poner el titulo
    if($value['id_subcategoria'] == 17) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=4>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    $puntuacionObtenida = $totalPuntuacion;
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    $puntuacionMaxima = $totalPuntuacionMax;
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }

    //Se asignan variables para la creación de las gráficas
    $cadenaID .= "'".$value['id_subcategoria']."',";
    $cadenaPuntuacionObtenida .= "'".$puntuacionObtenida."',";
    $cadenaPuntuacionMaxima .= "'".$puntuacionMaxima."',";

    echo 
    '
          <tr>
            <td>'.$value['id_subcategoria'].'</td>       
            <td class="text-center">'.utf8_encode($value[2]).'</td>  
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
    ';
          echo'</tr>';
  }
    echo '      
    </tbody>
    </table>
    </div>
  ';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

<!--           <p class="mb-4">Chart.js is a third party plugin that is used to generate the charts in this theme. The charts below have been customized - for further customization options, please visit the <a target="_blank" href="https://www.chartjs.org/docs/latest/">official Chart.js documentation</a>.</p> -->

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-8 mx-auto col-lg-7">

              <!-- Area Chart -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Gráfica de puntos</h6>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="graficaPuntos"></canvas>
                  </div>
                  <hr>
                  Se representa en gráfica de puntos la puntuación obtenida y la puntuación máxima de la evaluación para la norma <code>ISO 9000-3</code> 
                </div>
              </div>

              <!-- Bar Chart -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Gráfica de barras</h6>
                </div>
                <div class="card-body">
                  <div class="chart-bar">
                    <canvas id="graficaBarras"></canvas>
                  </div>
                  <hr>
                  Se representa en gráfica de barras la puntuación obtenida y la puntuación máxima de la evaluación para la norma <code>ISO 9000-3</code> 
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<?php
  $menu->menuFooter();
?>

  <!-- Page level plugins -->
  <script src="../../vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <!-- <script src="../../js/graficaBarras.js"></script> -->
  <script type="text/javascript">
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}


// Bar Chart Example
var ctx = document.getElementById("graficaBarras");
var graficaBarras = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php 
      $cadenaID = trim($cadenaID, ',');
      $cadenaPuntuacionObtenida = trim($cadenaPuntuacionObtenida, ',');
      $cadenaPuntuacionMaxima = trim($cadenaPuntuacionMaxima, ',');
      echo $cadenaID;
     ?>],
    datasets: [{
      label: "Puntuación Obtenida",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#29F4CF",
      borderColor: "#4e73df",
      data: [<?php echo $cadenaPuntuacionObtenida;?>],
    },{label: "Puntuación Máxima",
      backgroundColor: "#7CF94D",
      hoverBackgroundColor: "#F4B329",
      borderColor: "#60F429",
      data: [<?php echo $cadenaPuntuacionMaxima;?>],
    }
    ],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 25
        },
        maxBarThickness: 25,
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 120,
          maxTicksLimit: 8,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return '' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ':' + number_format(tooltipItem.yLabel);
        }
      }
    },
  }
});

  </script>
    <script type="text/javascript">
      // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("graficaPuntos");
var graficaPuntos = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [<?php echo $cadenaID;?>],
    datasets: [
      {
        label: "Puntuación Obtenida",
        lineTension: 0.3,
        backgroundColor: "rgba(120, 98, 110, 0.05)",
        borderColor: "rgba(180, 98, 110, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(180, 98, 110, 1)",
        pointBorderColor: "rgba(180, 98, 110, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(180, 98, 110, 1)",
        pointHoverBorderColor: "rgba(180, 98, 110, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: [<?php echo $cadenaPuntuacionObtenida;?>],
      },
      {
        label: "Puntuación Máxima",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: [<?php echo $cadenaPuntuacionMaxima;?>],
      }
    ],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 25
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 120,
          maxTicksLimit: 8,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});

    </script>
  <!-- <script src="../../js/demo/chart-bar-demo.js"></script> -->

