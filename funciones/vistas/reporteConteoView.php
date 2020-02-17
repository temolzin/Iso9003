<?php 
  session_start();
  $objEmpresa = $_SESSION['empresa'];
  require 'Menu.class.php';
  require '../procesos/Conexion.class.php';
  $conex = Conexion::getInstance();
  $menu = new Menu($conex);
  $menu->menuHeader('reportes', 101);
  echo '<h3 class="text-center text-info">Reporte Conteo</h3><br>';
  
  $query = "select count(*) from pregunta";
  $totalPreguntas = 0;
  foreach ($conex->consultar($query) as $key => $value) {
    $totalPreguntas = $value[0];
  }

  $queryCalificacion = "select count(*) from calificacion where id_empresa = " . $objEmpresa['id_empresa'];
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
          <th scope="col">Criticas Superadas</th>';
          $query = "select * from opcionpregunta where id_opcion_pregunta NOT IN(5,6)";
          foreach ($conex->consultar($query) as $key => $value) {
            echo '<th scope="col">'.$value['nombre'].'</th>';
          }
      echo'
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
  $sumaApartadoOk=0;
  $sumaApartadoNcmm=0;
  $sumaApartadoNCM=0;
  $sumaApartadoNA=0;
  $msgCritico = "NA";

  //*********************************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE LA CATEGORIA SISTEMA DE GESTIÓN DE CALIDAD (ID_CATEGORIA 1)
  //*********************************************************************************************
  $query = "select * from subcategoriapregunta sc inner join categoriapregunta cp 
  on sc.id_categoria = cp.id_categoria where sc.id_categoria=1";
  foreach ($conex->consultar($query) as $key => $value) {
    //Se toma el id_subcategoria 1 para poner el titulo
    if($value['id_subcategoria'] == 1) {
          echo'
          <tr>
            <td class="table-primary text-center" colspan=7>'.utf8_encode($value['nombre']).'
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
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }

    //Si hay alguna pregunta critica no cumplida se manda error
    if($totalCritico != $totalCalificacionCritico) {
      $msgCritico = "error";
    }

    //Llenar columna conteos calificacion
    $sumaTotal=0;
    $sumaOk = 0;
    $sumaNCM = 0;
    $sumaNcmm = 0;
    $sumaNA = 0;
    $query = "select * from calificacion ca
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta
    WHERE id_opcion_pregunta NOT IN(5,6) 
    AND id_subcategoria=". $value['id_subcategoria'] . ' 
    and id_empresa = ' . $objEmpresa['id_empresa'];
    if($conex->consultar($query) != null) {
      foreach ($conex->consultar($query) as $key => $valueCalificacion) {
        if($valueCalificacion['id_opcion_pregunta'] == 1) {
          $sumaOk += 1; 
        } else if($valueCalificacion['id_opcion_pregunta'] == 2) {
          $sumaNcmm += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 3) {
          $sumaNCM += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 4) {
          $sumaNA += 1;
        }
      }
    }

    $query = "select count(*) from pregunta where id_subcategoria=".$value['id_subcategoria'];
    $numPreguntas = 0;
    foreach ($conex->consultar($query) as $key => $value2) {
      $numPreguntas = $value2[0];
    }

    //VARIABLES PARA LA SUMA DE TOTALES
    $sumaApartadoOk += $sumaOk;
    $sumaApartadoNcmm += $sumaNcmm;
    $sumaApartadoNCM += $sumaNCM;
    $sumaApartadoNA += $sumaNA;

    $query = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
    foreach ($conex->consultar($query) as $key => $value2) {
      if($value2[0] != $numPreguntas) {
        echo 
        '
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center ">'.$sumaOk.'</td>  
                <td class="text-center ">'.$sumaNcmm.'</td>          
                <td class="text-center ">'.$sumaNCM.' </td> 
                <td class="text-center ">'.$sumaNA.' </td> 
        ';
        echo'</tr>';
        break;
      } else {
        echo'
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center table-secondary" colspan=4>No Aplica</td>  
        ';
        echo'</tr>';
        break;
      }
    }

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
            <td class="table-primary text-center" colspan=7>'.utf8_encode($value['nombre']).'
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
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }

    //Si hay alguna pregunta critica no cumplida se manda error
    if($totalCritico != $totalCalificacionCritico) {
      $msgCritico = "error";
    }

    //Llenar columna conteos calificacion
    $sumaTotal=0;
    $sumaOk = 0;
    $sumaNCM = 0;
    $sumaNcmm = 0;
    $sumaNA = 0;
    $query = "select * from calificacion ca
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta
    WHERE id_opcion_pregunta NOT IN(5,6) 
    AND id_subcategoria=". $value['id_subcategoria'] . 
    ' and id_empresa = ' . $objEmpresa['id_empresa'];
    if($conex->consultar($query) != null) {
      foreach ($conex->consultar($query) as $key => $valueCalificacion) {
        if($valueCalificacion['id_opcion_pregunta'] == 1) {
          $sumaOk += 1; 
        } else if($valueCalificacion['id_opcion_pregunta'] == 2) {
          $sumaNcmm += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 3) {
          $sumaNCM += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 4) {
          $sumaNA += 1;
        }
      }
    }

    $query = "select count(*) from pregunta where id_subcategoria=".$value['id_subcategoria'];
    $numPreguntas = 0;
    foreach ($conex->consultar($query) as $key => $value2) {
      $numPreguntas = $value2[0];
    }

    //VARIABLES PARA LA SUMA DE TOTALES
    $sumaApartadoOk += $sumaOk;
    $sumaApartadoNcmm += $sumaNcmm;
    $sumaApartadoNCM += $sumaNCM;
    $sumaApartadoNA += $sumaNA;

    $query = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
    foreach ($conex->consultar($query) as $key => $value2) {
      if($value2[0] != $numPreguntas) {
        echo 
        '
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center ">'.$sumaOk.'</td>  
                <td class="text-center ">'.$sumaNcmm.'</td>          
                <td class="text-center ">'.$sumaNCM.' </td> 
                <td class="text-center ">'.$sumaNA.' </td> 
        ';
        echo'</tr>';
        break;
      } else {
        echo'
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center table-secondary" colspan=4>No Aplica</td>  
        ';
        echo'</tr>';
        break;
      }
    }
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
            <td class="table-primary text-center" colspan=7>'.utf8_encode($value['nombre']).'
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
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }

    //Si hay alguna pregunta critica no cumplida se manda error
    if($totalCritico != $totalCalificacionCritico) {
      $msgCritico = "error";
    }

    //Llenar columna conteos calificacion
    $sumaTotal=0;
    $sumaOk = 0;
    $sumaNCM = 0;
    $sumaNcmm = 0;
    $sumaNA = 0;
    $query = "select * from calificacion ca
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta
    WHERE id_opcion_pregunta NOT IN(5,6) 
    AND id_subcategoria=". $value['id_subcategoria'] . ' 
    and id_empresa = ' . $objEmpresa['id_empresa'];
    if($conex->consultar($query) != null) {
      foreach ($conex->consultar($query) as $key => $valueCalificacion) {
        if($valueCalificacion['id_opcion_pregunta'] == 1) {
          $sumaOk += 1; 
        } else if($valueCalificacion['id_opcion_pregunta'] == 2) {
          $sumaNcmm += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 3) {
          $sumaNCM += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 4) {
          $sumaNA += 1;
        }
      }
    }

    $query = "select count(*) from pregunta where id_subcategoria=".$value['id_subcategoria'];
    $numPreguntas = 0;
    foreach ($conex->consultar($query) as $key => $value2) {
      $numPreguntas = $value2[0];
    }

    //VARIABLES PARA LA SUMA DE TOTALES
    $sumaApartadoOk += $sumaOk;
    $sumaApartadoNcmm += $sumaNcmm;
    $sumaApartadoNCM += $sumaNCM;
    $sumaApartadoNA += $sumaNA;

    $query = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
    foreach ($conex->consultar($query) as $key => $value2) {
      if($value2[0] != $numPreguntas) {
        echo 
        '
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center ">'.$sumaOk.'</td>  
                <td class="text-center ">'.$sumaNcmm.'</td>          
                <td class="text-center ">'.$sumaNCM.' </td> 
                <td class="text-center ">'.$sumaNA.' </td> 
        ';
        echo'</tr>';
        break;
      } else {
        echo'
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center table-secondary" colspan=4>No Aplica</td>  
        ';
        echo'</tr>';
        break;
      }
    }
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
            <td class="table-primary text-center" colspan=7>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
        //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
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
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }

    //Si hay alguna pregunta critica no cumplida se manda error
    if($totalCritico != $totalCalificacionCritico) {
      $msgCritico = "error";
    }

    //Llenar columna conteos calificacion
    $sumaTotal=0;
    $sumaOk = 0;
    $sumaNCM = 0;
    $sumaNcmm = 0;
    $sumaNA = 0;
    $query = "select * from calificacion ca
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta
    WHERE id_opcion_pregunta NOT IN(5,6) 
    AND id_subcategoria=". $value['id_subcategoria'] . ' 
    and id_empresa = ' . $objEmpresa['id_empresa'];
    if($conex->consultar($query) != null) {
      foreach ($conex->consultar($query) as $key => $valueCalificacion) {
        if($valueCalificacion['id_opcion_pregunta'] == 1) {
          $sumaOk += 1; 
        } else if($valueCalificacion['id_opcion_pregunta'] == 2) {
          $sumaNcmm += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 3) {
          $sumaNCM += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 4) {
          $sumaNA += 1;
        }
      }
    }

    $query = "select count(*) from pregunta where id_subcategoria=".$value['id_subcategoria'];
    $numPreguntas = 0;
    foreach ($conex->consultar($query) as $key => $value2) {
      $numPreguntas = $value2[0];
    }

    //VARIABLES PARA LA SUMA DE TOTALES
    $sumaApartadoOk += $sumaOk;
    $sumaApartadoNcmm += $sumaNcmm;
    $sumaApartadoNCM += $sumaNCM;
    $sumaApartadoNA += $sumaNA;

    $query = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
    foreach ($conex->consultar($query) as $key => $value2) {
      if($value2[0] != $numPreguntas) {
        echo 
        '
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center ">'.$sumaOk.'</td>  
                <td class="text-center ">'.$sumaNcmm.'</td>          
                <td class="text-center ">'.$sumaNCM.' </td> 
                <td class="text-center ">'.$sumaNA.' </td> 
        ';
        echo'</tr>';
        break;
      } else {
        echo'
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center table-secondary" colspan=4>No Aplica</td>  
        ';
        echo'</tr>';
        break;
      }
    }
  }
  //******************************************************************************
  //CONSULTA PARA AÑADIR LA TABLA DE MEDICIÓN ANALISIS Y MEJORA (ID_CATEGORIA 5)
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
            <td class="table-primary text-center" colspan=7>'.utf8_encode($value['nombre']).'
            </td>
          </tr>';
    }
    //Llenar columna TotalCriticas
    $queryTotalPreguntasCriticas = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
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
    $queryTotalCalificacionPreguntasCriticas = "select count(*) from calificacion ca inner join pregunta p on ca.id_pregunta = p.id_pregunta where id_opcion_pregunta = 6 and id_subcategoria=".$value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    $totalCalificacionCritico = 0;
    $criticoCalificacionNoAplica = "";
    foreach ($conex->consultar($queryTotalCalificacionPreguntasCriticas) as $keyPreguntaCritico => $valorPreguntaCritico) {
      $totalCalificacionCritico = $valorPreguntaCritico[0];
    }
    if($totalCalificacionCritico == 0) {
      $criticoCalificacionNoAplica = 'table-secondary';
      $totalCalificacionCritico = "No Aplica";
    }

    //Si hay alguna pregunta critica no cumplida se manda error
    if($totalCritico != $totalCalificacionCritico) {
      $msgCritico = "error";
    }

    //Llenar columna conteos calificacion
    $sumaTotal=0;
    $sumaOk = 0;
    $sumaNCM = 0;
    $sumaNcmm = 0;
    $sumaNA = 0;
    $query = "select * from calificacion ca
    inner join pregunta pr on ca.id_pregunta = pr.id_pregunta
    WHERE id_opcion_pregunta NOT IN(5,6) AND id_subcategoria=". $value['id_subcategoria'] . ' and id_empresa = ' . $objEmpresa['id_empresa'];
    if($conex->consultar($query) != null) {
      foreach ($conex->consultar($query) as $key => $valueCalificacion) {
        if($valueCalificacion['id_opcion_pregunta'] == 1) {
          $sumaOk += 1; 
        } else if($valueCalificacion['id_opcion_pregunta'] == 2) {
          $sumaNcmm += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 3) {
          $sumaNCM += 1;
        } else if($valueCalificacion['id_opcion_pregunta'] == 4) {
          $sumaNA += 1;
        }
      }
    }

    $query = "select count(*) from pregunta where id_subcategoria=".$value['id_subcategoria'];
    $numPreguntas = 0;
    foreach ($conex->consultar($query) as $key => $value2) {
      $numPreguntas = $value2[0];
    }

    //VARIABLES PARA LA SUMA DE TOTALES
    $sumaApartadoOk += $sumaOk;
    $sumaApartadoNcmm += $sumaNcmm;
    $sumaApartadoNCM += $sumaNCM;
    $sumaApartadoNA += $sumaNA;

    $query = "select count(*) from pregunta where critico = true and id_subcategoria=".$value['id_subcategoria'];
    foreach ($conex->consultar($query) as $key => $value2) {
      if($value2[0] != $numPreguntas) {
        echo 
        '
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center ">'.$sumaOk.'</td>  
                <td class="text-center ">'.$sumaNcmm.'</td>          
                <td class="text-center ">'.$sumaNCM.' </td> 
                <td class="text-center ">'.$sumaNA.' </td> 
        ';
        echo'</tr>';
        break;
      } else {
        echo'
              <tr>
                <td>'.utf8_encode($value[2]).'</td>
                <td class="text-center '.$criticoNoAplica.'">'.$totalCritico.'</td>    
                <td class="text-center '.$criticoCalificacionNoAplica.'">'.$totalCalificacionCritico.'</td>            
                <td class="text-center table-secondary" colspan=4>No Aplica</td>  
        ';
        echo'</tr>';
        break;
      }
    }
  }

  //************************************************************************
  //*********************TOTALES********************************************
  //************************************************************************
  
  echo '
    <tr class="table-warning"> 
      <td class="text-center" colspan=3>TOTAL</td>
      <td class="text-center">'.$sumaApartadoOk.'</td> 
      <td class="text-center">'.$sumaApartadoNcmm.'</td> 
      <td class="text-center">'.$sumaApartadoNCM.'</td> 
      <td class="text-center">'.$sumaApartadoNA.'</td> 
    </tr>
  ';
  $conversionNCM = $sumaApartadoNcmm/4;
  echo '
    <tr class="table-info"> 
      <td class="text-center" colspan=3>NCM por conversión (4 Ncm = 1 NCM)</td>
      <td class="text-center" colspan=4>'.$conversionNCM.'</td>  
    </tr>';

  echo '
        </tbody>
      </table>
      <form action="reporteCertamenView.php" method="POST">
      <input style="display:none" id="apOk" name="apOk" value="'.$sumaApartadoOk.'" />
      <input style="display:none" id="apNcmm" name="apNcmm" value="'.$sumaApartadoNcmm.'" />
      <input style="display:none" id="apNCM" name="apNCM" value="'.$sumaApartadoNCM.'" />
      <input style="display:none" id="apNA" name="apNA" value="'.$sumaApartadoNA.'" />
      <input style="display:none" id="critico" name="critico" value="'.$msgCritico.'" />
      <div class="text-center col-md-12">
        <button class="btn btn-success"><i class="fa fa-certificate" aria-hidden="true"></i> Generar Certamen
        </button>
      </div>
      </form>
    </div>
  ';
?>

<?php 
  $menu->menuFooter();
?>