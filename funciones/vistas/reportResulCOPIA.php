<?php 
	require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
	$menu = new Menu($conex);
	$menu->menuHeader('reportes', 100);
  echo '<h3 class="text-center text-info">Reporte de Resultados</h3><br>';
  
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
    <div class="col-md-12 table-responsive-sm">
    <table class="table table-bordered text-dark">
      <thead>
        <tr>
          <th scope="col">Apartado</th>
          <th scope="col">Total Criticas</th>
          <th scope="col">Criticas Superadas</th>
          <th scope="col">Puntuación</th>
          <th scope="col">Puntuación Máxima</th>
          <th scope="col">% de Acierto</th>
          <th scope="col">% por Subapartado</th>
          <th scope="col">% por Apartado</th>
          <th scope="col">Total</th>
        </tr>
      </thead>
      <tbody>
        ';

  //CONSULTA PARA SABER CUANTAS SUBCATEGORIAS EXISTEN PARA EL ROWSPAN DE TOTAL
  $query = "select count(*) from subcategoriapregunta";
  $numSubCategorias = 0;
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategorias = $value[0];
  }
  //Se le aumentan los titulos de las 5 categorias
  $numSubCategorias += 5;

  //Variables para la suma de los apartados y el total del reporte de resultados
  $sumaApartado1=0;
  $sumaApartado2=0;
  $sumaApartado3=0;
  $sumaApartado4=0;
  $sumaApartado5=0;
  $sumaTotal=0;
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
            <td class="table-primary text-center" colspan=8>'.utf8_encode($value['nombre']).'
            </td>
            <td id="totalResultado" name="totalResultado" style="vertical-align : middle;text-align:center;" class="table-success text-center" rowspan = '.$numSubCategorias.'></td>
          </tr>';
    }
    //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta  where critico = true and id_subcategoria=".$value['id_subcategoria'];
    $totalCritico = 0;
    $criticoNoAplica = "";
    foreach ($conex->consultar($queryTotalPreguntasCriticas) as $keyCritico => $valorCritico) {
      $totalCritico = $valorCritico[0];
    }
    if($totalCritico == 0) {
      $criticoNoAplica = 'table-secondary';
      $totalCritico = "No Aplica";
    }
    //Llenar columna CriticasSuperadas
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on
    ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
          //Variables para ver si todas las preguntas fueron NA 
    //para no quitar el valor de esa subcategoria en el reporte
    $totalNA = 0;
    $totalPreguntas = 0;
    if($conex->consultar($queryPuntuacionMax) != null) {

      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
              //Variables para ver si todas las preguntas fueron NA 
    //para no quitar el valor de esa subcategoria en el reporte
    $queryTotalPreguntasNoCritico = 'SELECT count(*) FROM pregunta where id_subcategoria =' . $value['id_subcategoria'] . ' and critico = false';
    echo $queryTotalPreguntasNoCritico;
    $queryNA = 'SELECT count(*) from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.nombre IN ("NA") and id_subcategoria='.$value['id_subcategoria'];
    echo $queryNA;
    foreach ($conex->consultar($queryTotalPreguntasNoCritico) as $keyNoCritico => $valorNoCritico) {
      $totalPreguntas = $valorNoCritico[0];
      break;
    }
    foreach ($conex->consultar($queryNA) as $keyNA => $valorNA) {
      $totalNA = $valorNA[0];
      break;
    }
    //Termina variablesNA
    if($totalNA == $totalPreguntas) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica (N/A)";
    }
    else if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }
    //Llenar columna % DE ACIERTO
    $porcentajeAcierto = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica";
    } elseif ($totalPuntuacionMax == "No Aplica (N/A)") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica (N/A)";
    } else {
      $porcentajeAcierto = ($totalPuntuacion / $totalPuntuacionMax) * 100;
      $porcentajeAcierto = round($porcentajeAcierto) . "%";
      $criticoPorcentajeAcierto = "";
    }
    //Llenar columna % DE SUBAPARTADO
    $porcentajeSubApartado = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeSubApartado = "table-secondary";
      $porcentajeSubApartado = "No Aplica";
    } else if($totalPuntuacionMax == "No Aplica (N/A)") {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    } else {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = ($totalPuntuacion / $totalPuntuacionMax) * $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    }
    //LLENAR COLUMNA % POR APARTADO
    if(is_numeric($porcentajeSubApartado)) {
      $sumaApartado1 += $porcentajeSubApartado;
    }

    echo 
    '
          <tr>
            <td>'.utf8_encode($value[2]).'</td>
            <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
            <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
            <td class="text-center '.$criticoPorcentajeAcierto.'">'.$porcentajeAcierto.' </td> 
            <td class="text-center '.$criticoPorcentajeSubApartado.'">'.$porcentajeSubApartado.' </td> 
    ';
            if($value['id_subcategoria'] == 1) {
              echo '
              <td id="apartado1" name="apartado1" style="vertical-align : middle;text-align:center;" class="table-warning text-center" rowspan = '.$numSubCategoriasInd.'>'.$sumaApartado1.'</td>';
            }
          echo'</tr>';
  }

  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA RESPONSABILIDAD DE LA DIRECCIÓN (ID_CATEGORIA 2)
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
    //Se toma el id_subcategoria 6 para poner el titulo de la segunda categoria
    if($value['id_subcategoria'] == 6) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=8>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
   //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta  where critico = true and id_subcategoria=".$value['id_subcategoria'];
    $totalCritico = 0;
    $criticoNoAplica = "";
    foreach ($conex->consultar($queryTotalPreguntasCriticas) as $keyCritico => $valorCritico) {
      $totalCritico = $valorCritico[0];
    }
    if($totalCritico == 0) {
      $criticoNoAplica = 'table-secondary';
      $totalCritico = "No Aplica";
    }
    //Llenar columna CriticasSuperadas
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on
    ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }
    //Llenar columna % DE ACIERTO
    $porcentajeAcierto = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica";
    } elseif ($totalPuntuacionMax == "No Aplica (N/A)") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica (N/A)";
    } else {
      $porcentajeAcierto = ($totalPuntuacion / $totalPuntuacionMax) * 100;
      $porcentajeAcierto = round($porcentajeAcierto) . "%";
      $criticoPorcentajeAcierto = "";
    }
    //Llenar columna % DE SUBAPARTADO
    $porcentajeSubApartado = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeSubApartado = "table-secondary";
      $porcentajeSubApartado = "No Aplica";
    } else if($totalPuntuacionMax == "No Aplica (N/A)") {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    } else {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = ($totalPuntuacion / $totalPuntuacionMax) * $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    }
    //LLENAR COLUMNA % POR APARTADO
    if(is_numeric($porcentajeSubApartado)) {
      $sumaApartado2 += $porcentajeSubApartado;
    }

    echo 
    '
          <tr>
            <td>'.utf8_encode($value[2]).'</td>
            <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
            <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
            <td class="text-center '.$criticoPorcentajeAcierto.'">'.$porcentajeAcierto.' </td> 
            <td class="text-center '.$criticoPorcentajeSubApartado.'">'.$porcentajeSubApartado.' </td> 
    ';
            if($value['id_subcategoria'] == 6) {
              echo '<td class="table-warning" id="apartado2" name="apartado2" style="vertical-align : middle;text-align:center;" rowspan = '.$numSubCategoriasInd.'>'.$sumaApartado2.'</td>';
            }
          echo'</tr>';
  }
  //******************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE GESTIÓN DE RECURSOS (ID_CATEGORIA 3)
  //******************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=3";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=3";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 10 para poner el titulo de la tercera categoria
    if($value['id_subcategoria'] == 10) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=8>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta  where critico = true and id_subcategoria=".$value['id_subcategoria'];
    $totalCritico = 0;
    $criticoNoAplica = "";
    foreach ($conex->consultar($queryTotalPreguntasCriticas) as $keyCritico => $valorCritico) {
      $totalCritico = $valorCritico[0];
    }
    if($totalCritico == 0) {
      $criticoNoAplica = 'table-secondary';
      $totalCritico = "No Aplica";
    }
    //Llenar columna CriticasSuperadas
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on
    ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }
    //Llenar columna % DE ACIERTO
    $porcentajeAcierto = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica";
    } elseif ($totalPuntuacionMax == "No Aplica (N/A)") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica (N/A)";
    } else {
      $porcentajeAcierto = ($totalPuntuacion / $totalPuntuacionMax) * 100;
      $porcentajeAcierto = round($porcentajeAcierto) . "%";
      $criticoPorcentajeAcierto = "";
    }
    //Llenar columna % DE SUBAPARTADO
    $porcentajeSubApartado = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeSubApartado = "table-secondary";
      $porcentajeSubApartado = "No Aplica";
    } else if($totalPuntuacionMax == "No Aplica (N/A)") {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    } else {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = ($totalPuntuacion / $totalPuntuacionMax) * $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    }
    //LLENAR COLUMNA % POR APARTADO
    if(is_numeric($porcentajeSubApartado)) {
      $sumaApartado3 += $porcentajeSubApartado;
    }

    echo 
    '
          <tr>
            <td>'.utf8_encode($value[2]).'</td>
            <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
            <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
            <td class="text-center '.$criticoPorcentajeAcierto.'">'.$porcentajeAcierto.' </td> 
            <td class="text-center '.$criticoPorcentajeSubApartado.'">'.$porcentajeSubApartado.' </td> 
    ';
            if($value['id_subcategoria'] == 10) {
              echo '<td class="table-warning" id="apartado3" name="apartado3" style="vertical-align : middle;text-align:center;" rowspan = '.$numSubCategoriasInd.'></td>';
            }
          echo'</tr>';
  }
  //******************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE REALIZACIÓN DEL PRODUCTO (ID_CATEGORIA 4)
  //******************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=4";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=4";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 10 para poner el titulo de la tercera categoria
    if($value['id_subcategoria'] == 11) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=8>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta  where critico = true and id_subcategoria=".$value['id_subcategoria'];
    $totalCritico = 0;
    $criticoNoAplica = "";
    foreach ($conex->consultar($queryTotalPreguntasCriticas) as $keyCritico => $valorCritico) {
      $totalCritico = $valorCritico[0];
    }
    if($totalCritico == 0) {
      $criticoNoAplica = 'table-secondary';
      $totalCritico = "No Aplica";
    }
    //Llenar columna CriticasSuperadas
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on
    ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica (N/A)";
    }
    //Llenar columna % DE ACIERTO
    $porcentajeAcierto = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica";
    } elseif ($totalPuntuacionMax == "No Aplica (N/A)") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica (N/A)";
    } else {
      $porcentajeAcierto = ($totalPuntuacion / $totalPuntuacionMax) * 100;
      $porcentajeAcierto = round($porcentajeAcierto) . "%";
      $criticoPorcentajeAcierto = "";
    }
    //Llenar columna % DE SUBAPARTADO
    $porcentajeSubApartado = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeSubApartado = "table-secondary";
      $porcentajeSubApartado = "No Aplica";
    } else if($totalPuntuacionMax == "No Aplica (N/A)") {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    } else {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = ($totalPuntuacion / $totalPuntuacionMax) * $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    }
    //LLENAR COLUMNA % POR APARTADO
    if(is_numeric($porcentajeSubApartado) or $totalPuntuacionMax == "No Aplica (N/A)") {
      $sumaApartado4 += $porcentajeSubApartado;
    }

    echo 
    '
          <tr>
            <td>'.utf8_encode($value[2]).'</td>
            <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
            <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
            <td class="text-center '.$criticoPorcentajeAcierto.'">'.$porcentajeAcierto.' </td> 
            <td class="text-center '.$criticoPorcentajeSubApartado.'">'.$porcentajeSubApartado.' </td> 
    ';
            if($value['id_subcategoria'] == 11) {
              echo '<td class="table-warning" id="apartado4" name="apartado4" style="vertical-align : middle;text-align:center;" rowspan = '.$numSubCategoriasInd.'></td>';
            }
          echo'</tr>';
  }
  //******************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE MEDICIÓN ANPALISIS Y MEJORA (ID_CATEGORIA 5)
  ////******************************************************************************
  $numSubCategoriasInd = 0;
  $query = "select count(*) from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=5";
  foreach ($conex->consultar($query) as $key => $value) {
    $numSubCategoriasInd = $value[0];
  }
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=5";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 10 para poner el titulo de la tercera categoria
    if($value['id_subcategoria'] == 17) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=8>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
//Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta  where critico = true and id_subcategoria=".$value['id_subcategoria'];
    $totalCritico = 0;
    $criticoNoAplica = "";
    foreach ($conex->consultar($queryTotalPreguntasCriticas) as $keyCritico => $valorCritico) {
      $totalCritico = $valorCritico[0];
    }
    if($totalCritico == 0) {
      $criticoNoAplica = 'table-secondary';
      $totalCritico = "No Aplica";
    }
    //Llenar columna CriticasSuperadas
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on
    ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }
    //Llenar columna PUNTUACIÓN
    $queryPuntuacion = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacion = 0;
    $criticoPuntuacionNoAplica = "";
    if($conex->consultar($queryPuntuacion)!= null) {
      foreach ($conex->consultar($queryPuntuacion) as $keyPuntuacion => $valorPuntuacion) {
        $totalPuntuacion += $valorPuntuacion['valor'];
      }
    }
    if($totalPuntuacion == 0) {
      $criticoPuntuacionNoAplica = 'table-secondary';
      $totalPuntuacion = "No Aplica";
    }
    //Llenar columna PUNTUACIÓNMÁXIMA
    $queryPuntuacionMax = "select * from calificacion ca 
    inner join opcionpregunta op on ca.id_opcion_pregunta = op.id_opcion_pregunta 
    inner join pregunta pe on ca.id_pregunta = pe.id_pregunta
    where op.id_opcion_pregunta NOT IN (5,6) and op.nombre NOT IN ('NA') and id_subcategoria=".$value['id_subcategoria'];
    $totalPuntuacionMax = 0;
    $criticoPuntuacionMaxNoAplica = "";
    if($conex->consultar($queryPuntuacionMax)!= null) {
      foreach ($conex->consultar($queryPuntuacionMax) as $keyPuntuacionMax => $valorPuntuacionMax) {
        $totalPuntuacionMax += 10;
      }
    }
    if($totalPuntuacionMax == 0) {
      $criticoPuntuacionMaxNoAplica = 'table-secondary';
      $totalPuntuacionMax = "No Aplica";
    }
    //Llenar columna % DE ACIERTO
    $porcentajeAcierto = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica";
    } elseif ($totalPuntuacionMax == "No Aplica (N/A)") {
      $criticoPorcentajeAcierto = "table-secondary";
      $porcentajeAcierto = "No Aplica (N/A)";
    } else {
      $porcentajeAcierto = ($totalPuntuacion / $totalPuntuacionMax) * 100;
      $porcentajeAcierto = round($porcentajeAcierto) . "%";
      $criticoPorcentajeAcierto = "";
    }
    //Llenar columna % DE SUBAPARTADO
    $porcentajeSubApartado = 0;
    if($totalPuntuacionMax == "No Aplica") {
      $criticoPorcentajeSubApartado = "table-secondary";
      $porcentajeSubApartado = "No Aplica";
    } else if($totalPuntuacionMax == "No Aplica (N/A)") {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    } else {
      $querySubApartado = "select * from subcategoriapregunta WHERE id_subcategoria=".$value['id_subcategoria'];
      $porcentajeSubApartadoBD = 0;
      $criticoPorcentajeSubApartado = "";
      if($conex->consultar($querySubApartado)!= null) {
        foreach ($conex->consultar($querySubApartado) as $keySubApartad => $valorSubApartado) {
          $porcentajeSubApartadoBD = $valorSubApartado['valor'];
        }
      }
      $porcentajeSubApartado = ($totalPuntuacion / $totalPuntuacionMax) * $porcentajeSubApartadoBD;
      $porcentajeSubApartado = round($porcentajeSubApartado,2);
      $criticoPorcentajeSubApartado = "";
    }
    //LLENAR COLUMNA % POR APARTADO
    if(is_numeric($porcentajeSubApartado)) {
      $sumaApartado5 += $porcentajeSubApartado;
    }

    echo 
    '
          <tr>
            <td>'.utf8_encode($value[2]).'</td>
            <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
            <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
            <td class="text-center '.$criticoPuntuacionNoAplica.'">'.$totalPuntuacion.'</td>  
            <td class="text-center '.$criticoPuntuacionMaxNoAplica.'">'.$totalPuntuacionMax.'</td>          
            <td class="text-center '.$criticoPorcentajeAcierto.'">'.$porcentajeAcierto.' </td> 
            <td class="text-center '.$criticoPorcentajeSubApartado.'">'.$porcentajeSubApartado.' </td> 
    ';
            if($value['id_subcategoria'] == 17) {
              echo '<td class="table-warning" id="apartado5" name="apartado5" style="vertical-align : middle;text-align:center;" rowspan = '.$numSubCategoriasInd.'></td>';
            }
          echo'</tr>';
  }

  //************************************************************************
  //*********************CALCULO DEL TOTAL**********************************
  //************************************************************************
  $queryCategoria = "select * from categoriapregunta";
  $total1 = 0;
  $total2 = 0;
  $total3 = 0;
  $total4 = 0;
  $total5 = 0;
  foreach ($conex->consultar($queryCategoria) as $key => $value) {
    if($value['id_categoria'] == 1) { 
      $total1 = $sumaApartado1 * $value['valor'];
    } else if($value['id_categoria'] == 2) {
      $total2 = $sumaApartado2 * $value['valor'];
    } else if($value['id_categoria'] == 3) {
      $total3 = $sumaApartado3 * $value['valor'];
    } else if($value['id_categoria'] == 4) {
      $total4 = $sumaApartado4 * $value['valor'];
    } else if($value['id_categoria'] == 5) {
      $total5 = $sumaApartado5 * $value['valor'];
    }
  }
  $sumaTotal = $total1 + $total2 + $total3 + $total4 + $total5;
  echo '
        </tbody>
      </table>
    </div>
    <div class="text-center">
      <a href="../procesos/exportarExcel/exportarReporteResultado.php"><button id="btnExcel" name="btnExcel" class="btn btn-success"><img src="../../img/excel.png" width="32px" height="32px"> Exportar a excel</button></a>
    </div>
  ';

	$menu->menuFooter();

  echo '    
  <script>
      $(document).ready(cambiarValoresTabla);
      function cambiarValoresTabla() {
        $("#apartado1").html('.$sumaApartado1.');
        $("#apartado2").html('.$sumaApartado2.');
        $("#apartado3").html('.$sumaApartado3.');
        $("#apartado4").html('.$sumaApartado4.');
        $("#apartado5").html('.$sumaApartado5.');
        $("#totalResultado").html('.$sumaTotal.');
      }
  </script>';
?>