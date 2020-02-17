<?php 
	session_start();
	$auditor = $_SESSION['auditor'];
	if(isset($auditor)==false) {
		header("Location: loginView.php");
	}
	require 'Menu.class.php';
	require '../procesos/Conexion.class.php';
	$conex = Conexion::getInstance();
	$menu = new Menu($conex);
	$menu->menuHeader('empresa', 11);
?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
	<!-- Page Heading -->
	<div class="col-md-12 text-center">
		<h1 class="h3 text-center mb-0 text-info text-black-800">Empresas Registradas</h1>
		<!--             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
	</div><br><br>

	<!-- Content Row -->
	<div class="row">
		<div class="col-md-12 table-responsive-sm">
		<table id="tablaEmpresa" class="table table-bordered text-dark table-responsive-sm">
	        <thead>
	            <tr>
      					<th>ID</th>
      					<th>Logo</th>
      					<th>Nombre</th>
      					<th>RFC</th>
      					<th>Código Postal</th>
      					<th>Email</th>
      					<th>Teléfono</th>
      					<th>Editar</th>
      					<th>Eliminar</th>
	            </tr>
	        </thead>
	        <tbody>
            	<?php
            		$query = "SELECT * FROM empresa";
            		foreach ($conex->consultar($query) as $key => $value) {
            			echo '<tr>';
            			echo '<th>' . $value['id_empresa'] . '</th>';
            			echo '<th class="text-center"><img width="40px" height="40px" src="../../img/images/' . $value['imagen'] . '"</th>';
            			echo '<th>' . $value['nombre'] . '</th>';
            			echo '<th>' . $value['RFC'] . '</th>';
            			echo '<th>' . $value['codigopostal'] . '</th>';
            			echo '<th>' . $value['email'] . '</th>';
            			echo '<th>' . $value['telefono'] . '</th>';
            			//echo '<th>' . $value[7] . '</th>';
            			//echo "<input value=".$value['id_empresa']." type='hidden' id='idEmpresa' name='idEmpresa'/>";
            			echo '<th align="center" class="text-center"><a href="" data-toggle="modal" data-target="#modalEditEmpresa" data-whatever='.$value['id_empresa'].'><img width="40px" height="40px" src="../../img/icon/edit.png" class="img-responsive"></a></th>';
            			echo '<th class="text-center" align="center"><a href="" data-toggle="modal" data-target="#modalEliminarEmpresa" data-nombreEmpresa='.$value['nombre'].'  data-whatever='.$value['id_empresa'].'><img width="40px" height="40px" src="../../img/icon/error.png" class="img-responsive"></a></th>';
            			echo '</tr>';
            		}
            	?>
	        </tbody>
	    </table>
		</div> 
	</div>
	</div>
	<!-- /.container-fluid -->
<?php
  $menu->menuFooter();
?>

<!--Modal: Login with Avatar Form-->
<div class="modal fade" id="modalEditEmpresa" name="modalEditEmpresa" tabindex="-1" role="dialog" aria-labelledby="modalEditEmpresa"
  aria-hidden="true">
  <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
    <!--Content-->
    <div class="modal-content">

      <!--Header-->
      <div class="modal-header">
        <img id="imgEmpresa" name="imgEmpresa" width="120px" height="120px" alt="avatar" class="mx-auto rounded-circle img-responsive">
      </div>
      <!--Body-->
      <div class="modal-body text-center mb-1">
              <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button class="btn btn-warning" id="btnSubirImagen" name="btnSubirImagen">Subir</button>
                      </div>
                      <div class="custom-file">
                        <input lang="es" type="file" class="custom-file-input" id="imagenEmpresa"
                          aria-describedby="imagenEmpresa">
                        <label class="custom-file-label" for="imagenEmpresa">Selecciona imagen</label>
                      </div>
                    </div>
                  </div>
              </div>
            <form class="user" method="post" id="formActEmpresa" name="formActEmpresa">
                <input value="<?php echo $value['id_empresa']; ?>" type='hidden' id='idEmpresa' name='idEmpresa'/>
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="rfc" name="rfc" placeholder="RFC de la empresa">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="nombre" name="nombre" placeholder="Nombre">
                  </div>
              	</div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="number" maxlength="10" size="10" class="form-control form-control-user" name="telefono" id="telefono" placeholder="Teléfono">
                  </div>
                  <div class="col-sm-6">
                    <input type="number" maxlength="5" size="5" class="form-control form-control-user" id="codigopostal" name="codigopostal" placeholder="Código Postal">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Ingresa Email">
                  </div>                 
                </div>
              
                <button class="btn btn-primary btn-user btn-block">
                  Actualizar
                </button>
              </form>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: Login with Avatar Form-->

<div class="modal fade" id="modalEliminarEmpresa" tabindexa="-1" role="dialog" aria-labelledby="modalEliminarEmpresa" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="exampleModalLabel">Eliminar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="textoAlertaEliminar" name="textAlertaEliminar" class="modal-body text-danger">
      </div>
      <input type="hidden" name="eliminarIdEmpresa" id = "eliminarIdEmpresa">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnEliminarModal" name="btnEliminarModal" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- <div class="text-center">
  <a href="" class="btn btn-default btn-rounded" >Launch
    Modal Login with Avatar</a>
</div> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script> 

<script type="text/javascript">
  $(document).ready(function() {
      $('#tablaEmpresa').DataTable();
      $('#modalEditEmpresa').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var idEmpresa = button.data('whatever'); // Extract info from data-* attributes
      $.ajax({
        type: "POST",
          url: "../procesos/consultarEmpresa.php",
          data: {idEmpresa: idEmpresa},
            success: function(data){
              var datos = JSON.parse(data);
              $('#imgEmpresa').attr("src", "../../img/images/"+datos.imagen);
              $('#rfc').val(datos.RFC);
              $('#nombre').val(datos.nombre);
              $('#codigopostal').val(datos.codigopostal);
              $('#email').val(datos.email);
              $('#telefono').val(datos.telefono);
            }, 
      });
    });
      $('#btnSubirImagen').on("click", subirImagen);
      function subirImagen() {
        var imagenEmpresa = document.getElementById('imagenEmpresa');
        if(imagenEmpresa.value == "") {
          Swal.fire(
            "¡Cuidado!",
            "Debes seleccionar una imagen para actualizar",
            "warning"
          ); 
        } else {
          var form_data = new FormData();
          var idEmpresa = document.getElementById('idEmpresa');
          var imagen = $('#imagenEmpresa').prop('files')[0];
          form_data.append('idEmpresa', idEmpresa.value);
          form_data.append('imagen', imagen);
          $.ajax({
            type: "POST",
            url: "../procesos/actualizarImagenEmpresa.php",
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
              if(data == 'ok') {
                Swal.fire(
                  "¡Éxito!",
                  "La imagen fue actualizada correctamente",
                  "success"
                ).then(function () {
                  window.location = "consultarEmpresaView.php";
                });
              } else {
                Swal.fire(
                  "¡Error!",
                  "Ha ocurrido un error al actualizar la imagen: " + data,
                  "success"
                );
              }
            }
          });
        }
      }
      //ENVIAR FORMULARIO CON AJAX
    $("#formActEmpresa").validate({
        rules: {
            nombre: {
              required: true
            },
            rfc: {
              required: true
            },
            codigopostal: {
              required: true
            },
            email: {
              required: true,
              email: true
            },
            telefono: {
              required: true
            }
        },
        messages: {
            nombre: {
              required: "<p align='center' class='text-danger'> Debes ingresar el nombre de la empresa</p>"
            },
            rfc: {
              required: "<p align='center' class='text-danger'> Debes ingresar un RFC</p>"
            },
            codigopostal: {
              required: "<p align='center' class='text-danger'> Debes ingresar un Código Postal</p>"
            },
            email: {
              required: "<p align='center' class='text-danger'> Debes ingresar un email</p>",
              email: "<p align='center' class='text-danger'> Ingresa un Email correcto </p>"
            },
            telefono: {
              required: "<p align='center' class='text-danger'> Ingresa un número de teléfono </p>"
            }
        },
        submitHandler: function(){
        var form_data = new FormData();
        //var imagen = $('#imagenAuditor').prop('files')[0];   
        var nombre = document.getElementById('nombre');     
        var rfc = document.getElementById('rfc');
        var codigopostal = document.getElementById('codigopostal');     
        var email = document.getElementById('email');    
        var telefono = document.getElementById('telefono');
        var idEmpresa = document.getElementById('idEmpresa');     
 
        form_data.append('nombre', nombre.value);
        form_data.append('rfc', rfc.value);
        form_data.append('codigopostal', codigopostal.value);
        form_data.append('email', email.value);
        form_data.append('telefono', telefono.value);
        form_data.append('idEmpresa', idEmpresa.value);
        $.ajax({
          type: "POST",
          url: "../procesos/actualizarEmpresa.php",
          dataType: 'text',  // what to expect back from the PHP script, if anything
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          success: function(data){ 
            if(data == 'ok') {
              Swal.fire(
                "¡Éxito!",
                "Tus datos han sido actualizados de manera correcta.",
                "success"
              ).then(function() {
                window.location = "consultarEmpresaView.php";  
                limpiarCajas();
              })
             } else {
              Swal.fire(
                "¡Error!",
                "Ha ocurrido un error al actualizar los datos. " + data,
                "error"
              ); 
            }
          },  
        });
      }
    });
  });
</script>

<script type="text/javascript">
  $('#modalEliminarEmpresa').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var nombreEmpresa = button.data('nombreempresa');
    $('#textoAlertaEliminar').html('¿Realmente quieres eliminar la empresa ' + nombreEmpresa + "? <br>Recuerda que las empresas que ya tienen calificaciones no se pueden eliminar");
    var idEmpresaModal = button.data('whatever'); // Extract info from data-* attributes
    $('#eliminarIdEmpresa').val(idEmpresa);
    $('#btnEliminarModal').on("click", eliminarEmpresa);
    function eliminarEmpresa() {
      $.ajax({
          type: "POST",
          url: "../procesos/eliminarEmpresa.php",
          dataType: 'text',  // what to expect back from the PHP script, if anything
          data: {idEmpresa: idEmpresaModal},
          success: function(data) {
            if(data == 'ok') {
              Swal.fire(
                "¡Éxito!",
                "La empresa: " + nombreEmpresa + " ha sido eliminada exitosamente",
                "success"
              ).then(function(){
                window.location = "consultarEmpresaView.php";
              });
            } else {
              Swal.fire(
                "¡Error!",
                "No se ha podido eliminar la empresa: " + nombreEmpresa + " debido ha que ya tiene calificaciones emitidas",
                "error"
              ).then(function(){
                window.location = "consultarEmpresaView.php";
              });
            }
          }
      });
    }
  });
</script>