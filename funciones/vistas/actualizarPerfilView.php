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
$menu->menuHeader('inicio', 0);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="col-md-12 text-center">
    <h1 class="h3 text-center mb-0 text-info text-black-800">Actualiza tus datos</h1>
    <!--             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div><br>
  <input type="text" name="idAuditor" id="idAuditor" value="<?php echo $auditor['id_auditor']; ?>"  style=" display: none;" />

  <div class="text-center form-group row mx-auto">
    <div class="col-sm-12 mb-3 mb-sm-0">
      <img width="180px" height="180px" class="img-thumbnail rounded img-responsive" src="../../img/images/<?php echo $auditor['imagen'];?>" />
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-12 mb-3 mb-sm-0">
      <div class="input-group">
        <div class="input-group-prepend">
          <button class="btn btn-warning" style="z-index: 0;" id="btnSubirImagen" name="btnSubirImagen">Subir</button>
        </div>
        <div class="custom-file">
          <input lang="es" type="file" class="custom-file-input" id="imagenAuditor"
          aria-describedby="imagenAuditor">
          <label class="custom-file-label" for="imagenAuditor" style="z-index: 0;">Selecciona imagen</label>
        </div>
      </div>
    </div>
  </div>

  <form class="user" method="post" id="formAuditor" name="formAuditor">
    <div class="form-group row">
      <div class="col-sm-12 mb-3 mb-sm-0">
        <input type="text" class="form-control form-control-user" value="<?php echo $auditor['username']; ?>" id="username" name="username" placeholder="Nombre de usuario">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-4 mb-3 mb-sm-0">
        <input type="text" class="form-control form-control-user" value="<?php echo $auditor['nombre']?>" id="nombre" name="nombre" placeholder="Nombre">
      </div>
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-user" value="<?php echo utf8_encode($auditor['ap_pat']); ?>" id="apPat" name="apPat" placeholder="Apellido Paterno">
      </div>
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-user"  value="<?php echo $auditor['ap_mat'];?>" id="apMat" name="apMat" placeholder="Apellido Materno">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="email" class="form-control form-control-user" value="<?php echo $auditor['email']?>"  id="email" name="email" placeholder="Ingresa Email">
      </div>
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="number" maxlength="10" size="10" value="<?php echo $auditor['telefono']?>" class="form-control form-control-user" name="telefono" id="telefono" placeholder="Teléfono">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="password" class="form-control form-control-user" value="<?php echo $auditor['password'];?>" name="password" id="password" placeholder="Contraseña">
      </div>
      <div class="col-sm-6">
        <input type="password" class="form-control form-control-user" value="<?php echo $auditor['password'];?>" name="password2" id="password2" placeholder="Repite Contraseña">
      </div>
    </div>
    <button class="btn btn-primary btn-user btn-block">
      Actualizar Datos
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
                $("#imagenAuditor").each(function() {
                  $($(this)).val('');
                });
              }
              $(document).ready(function(){
      //PARA SUBIR SOLAMENTE LA IMAGEN
      $('#btnSubirImagen').on("click", subirImagen);
      function subirImagen() {
        var imagenAuditor = document.getElementById('imagenAuditor');
        if(imagenAuditor.value == "") {
          Swal.fire(
            "¡Cuidado!",
            "Debes seleccionar una imagen para actualizar",
            "warning"
            ); 
        } else {
          var form_data = new FormData();
          var idAuditor = document.getElementById('idAuditor');
          var imagen = $('#imagenAuditor').prop('files')[0];
          form_data.append('idAuditor', idAuditor.value);
          form_data.append('imagen', imagen);
          $.ajax({
            type: "POST",
            url: "../procesos/actualizarImagenAuditor.php",
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
                  ).then(function() {
                    window.location = "actualizarPerfilView.php";  
                  });
                } else {
                  Swal.fire(
                    "¡Error!",
                    "Ha ocurrido un error al actualizar la imagen: " + data,
                    "success"
                    )
                }
              }
            });
        }
      }
      //ENVIAR FORMULARIO CON AJAX
      $("#formAuditor").validate({
        rules: {
            // imagenAuditor: {
            //   required: true
            // },
            username: {
              required: true,
              // remote: "../procesos/validarUserNameAuditor.php"
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
            // imagenAuditor: {
            //   required: "<p align='center' class='text-danger'> Debes ingresar seleccionar una imagen</p>"
            // },
            username: {
              required: "<p align='center' class='text-danger'> Debes ingresar un nombre de usuario</p>",
              // remote: "<div class='mx-auto'><font color='red' align='center'> <img width='32px' height = '32px' src='../../img/icon/error.png'/> No disponible</font></div>"
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
        //var imagen = $('#imagenAuditor').prop('files')[0];   
        var nombre = document.getElementById('nombre');     
        var apPat = document.getElementById('apPat');
        var apMat = document.getElementById('apMat');     
        var email = document.getElementById('email');
        var username = document.getElementById('username');     
        var telefono = document.getElementById('telefono');
        var password = document.getElementById('password');     
        var idAuditor = document.getElementById('idAuditor');     

        //form_data.append('imagen', imagen);
        form_data.append('username', username.value);
        form_data.append('nombre', nombre.value);
        form_data.append('apPat', apPat.value);
        form_data.append('apMat', apMat.value);
        form_data.append('email', email.value);
        form_data.append('telefono', telefono.value);
        form_data.append('password', password.value);
        form_data.append('idAuditor', idAuditor.value);
        $.ajax({
          type: "POST",
          url: "../procesos/actualizarPerfil.php",
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
                  window.location = "inicioView.php";  
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
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  </script>