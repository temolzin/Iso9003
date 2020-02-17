<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Calidad de Software">
  <meta name="author" content="TemolzinItzae">

  <title>Calidad de Software Registrate</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="shortcut icon" href="../../favicon.png" type="image/x-icon">
  <link rel="icon" href="../../favicon.png" type="image/x-icon">
  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Crea una cuenta</h1>
              </div>
              <form class="user" method="post" id="formAuditor" name="formAuditor">
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <div class="custom-file">
                      <input type="file" accept="image/*" class="custom-file-input" name="imagenAuditor" id="imagenAuditor" lang="es">
                      <label class="custom-file-label" for="imagenAuditor">Selecciona Imagen</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Nombre de usuario">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="nombre" name="nombre" placeholder="Nombre">
                  </div>
                  <div class="col-sm-4">
                    <input type="text" class="form-control form-control-user" id="apPat" name="apPat" placeholder="Apellido Paterno">
                  </div>
                  <div class="col-sm-4">
                    <input type="text" class="form-control form-control-user" id="apMat" name="apMat" placeholder="Apellido Materno">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Ingresa Email">
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="number" maxlength="10" size="10" class="form-control form-control-user" name="telefono" id="telefono" placeholder="Teléfono">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Contraseña">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="password2" id="password2" placeholder="Repite Contraseña">
                  </div>
                </div>
                <button class="btn btn-primary btn-user btn-block">
                  Crear cuenta
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
              <div class="text-center">
                <a class="small" href="loginView.php">¿Ya tienes una cuenta?, ¡Inicia Sesión!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/jquery.validate.js"></script>

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
      $("#imagenAuditor").each(function() {
        $($(this)).val('');
      });
    }
    $(document).ready(function(){
    $("#formAuditor").validate({
        rules: {
            imagenAuditor: {
              required: true
            },
            username: {
              required: true,
              remote: "../procesos/validarUserNameAuditor.php"
            },
            nombre: {
              required: true
            },
            apPat: {
              required: true
            },
            apMat: {
              required: true
            },
            email: {
              required: true,
              email: true
            },
            telefono: {
              required: true
            },
            password: {
              required: true,
            },
            password2: {
              required: true,
              equalTo: password
            },
            comboStatus: {
              validarComboVacio: "default"
            }
        },
        messages: {
            imagenAuditor: {
              required: "<p align='center' class='text-danger'> Debes seleccionar una imagen</p>"
            },
            username: {
              required: "<p align='center' class='text-danger'> Debes ingresar un nombre de usuario</p>",
              remote: "<div class='mx-auto'><font color='red' align='center'> <img width='32px' height = '32px' src='../../img/icon/error.png'/> No disponible</font></div>"
            }, 
            imagenAuditor: {
              required: "<p align='center' class='text-danger'> Debes seleccionar una imagen</p>"
            },
            nombre: {
              required: "<p align='center' class='text-danger'> Debes ingresar el nombre de usuario</p>"
            },
            apPat: {
              required: "<p align='center' class='text-danger'> Debes ingresar un Apellido Paterno</p>"
            },
            apMat: {
              required: "<p align='center' class='text-danger'> Debes ingresar un Apellido Materno</p>"
            },
            email: {
              required: "<p align='center' class='text-danger'> Debes ingresar un email</p>",
              email: "<p align='center' class='text-danger'> Ingresa un Email correcto </p>"
            },
            telefono: {
              required: "<p align='center' class='text-danger'> Ingresa un número de teléfono </p>"
            },
            password: {
              required: "<p align='center' class='text-danger'> Debes ingresar una Contraseña</p>"
            },
            password2: {
              required: "<p align='center' class='text-danger'> Confirma la contraseña</p>",
              equalTo: "<p align='center' class='text-danger'> Las Contraseñas no coinciden</p>"
            }
        },
        submitHandler: function(){
        var form_data = new FormData();
        var imagen = $('#imagenAuditor').prop('files')[0];   
        var nombre = document.getElementById('nombre');     
        var apPat = document.getElementById('apPat');
        var apMat = document.getElementById('apMat');     
        var email = document.getElementById('email');
        var username = document.getElementById('username');     
        var telefono = document.getElementById('telefono');
        var password = document.getElementById('password');     

        form_data.append('imagen', imagen);
        form_data.append('username', username.value);
        form_data.append('nombre', nombre.value);
        form_data.append('apPat', apPat.value);
        form_data.append('apMat', apMat.value);
        form_data.append('email', email.value);
        form_data.append('telefono', telefono.value);
        form_data.append('password', password.value);
        $.ajax({
          type: "POST",
          url: "../procesos/registrarAuditor.php",
          dataType: 'text',  // what to expect back from the PHP script, if anything
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          success: function(data){ 
            if(data == 'ok') {
              Swal.fire(
                "¡Éxito!",
                "Tus datos han sido registrados de manera correcta",
                "success"
              ).then(function() {
                window.location = "loginView.php";  
                limpiarCajas();
              })
             } else {
              Swal.fire(
                "¡Error!",
                "Ha ocurrido un error al registrarte. " + data,
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
</body>

</html>
