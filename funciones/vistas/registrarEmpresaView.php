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
  $menu->menuHeader('empresa', 10);
?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
  <!-- Page Heading -->
  <div class="col-md-12 text-center">
    <h1 class="h3 text-center mb-0 text-info text-black-800">Registrar Empresa</h1>
    <!--             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div><br>
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
<!--           <div class="col-lg-5 d-none d-lg-block bg-register-image"></div> -->
          <div class="col-lg-10 mx-auto">
              <form class="user" method="post" id="formEmpresa" name="formEmpresa">
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <div class="custom-file">
                      <input type="file" accept="image/*" class="custom-file-input" name="imagenEmpresa" id="imagenEmpresa" lang="es">
                      <label class="custom-file-label" for="imagenEmpresa" style="z-index: 0;">Selecciona imagen</label>
                    </div>
                  </div>
                </div>
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
                  Registrar Empresa
                </button>
<!--                 <hr>
                <a href="index.html" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Register with Google
                </a>
                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                </a> -->
              </form>
              <hr>
<!--               <div class="text-center">
                <a class="small" href="forgot-password.html">Forgot Password?</a>
              </div> -->
            </div>
          </div>
        </div>
      </div>

<?php 
  $menu->menuFooter();
?>
  <script type="text/javascript">
    function limpiarCajas() {
      $(":text").each(function() {
        $($(this)).val('');
      });
      $(":password").each(function() {
        $($(this)).val('');
      });
      $(":number").each(function() {
        $($(this)).val('');
      });
      $("#imagenEmpresa").each(function() {
        $($(this)).val('');
      });
    }
    $(document).ready(function(){
    $("#formEmpresa").validate({
        rules: {
            imagenEmpresa: {
              required: true
            },
            rfc: {
              required: true,
            },
            nombre: {
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
            imagenEmpresa: {
              required: "<p align='center' class='text-danger'> Debes seleccionar una imagen</p>"
            },
            rfc: {
              required: "<p align='center' class='text-danger'> Debes ingresar un RFC</p>",
            }, 
            nombre: {
              required: "<p align='center' class='text-danger'> Debes ingresar el nombre de usuario</p>"
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
        var imagen = $('#imagenEmpresa').prop('files')[0];   
        var nombre = document.getElementById('nombre');     
        var rfc = document.getElementById('rfc');
        var codigopostal = document.getElementById('codigopostal');     
        var email = document.getElementById('email');   
        var telefono = document.getElementById('telefono');       

        form_data.append('imagen', imagen);
        form_data.append('rfc', rfc.value);
        form_data.append('nombre', nombre.value);
        form_data.append('email', email.value);
        form_data.append('telefono', telefono.value);
        form_data.append('codigopostal', codigopostal.value);
        $.ajax({
          type: "POST",
          url: "../procesos/registrarEmpresa.php",
          dataType: 'text',  // what to expect back from the PHP script, if anything
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          success: function(data){ 
            if(data == 'ok') {
              Swal.fire(
                "¡Éxito!",
                "La empresa ha sido registrada de manera correcta.",
                "success"
              ).then(function() {
                //window.location = "inicioView.php";  
                limpiarCajas();
              })
             } else {
              Swal.fire(
                "¡Error!",
                "Ha ocurrido un error al registrar la empresa: " + data,
                "error"
              ); 
            }
            },  
          });
        }
    });
    });
  </script>
  <script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  </script>