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
		<h1 class="h3 text-center mb-0 text-info text-black-800">Bienvenido</h1>
		<!--             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
	</div><br><br>

	<!-- Content Row -->
	<div class="row">
		<div class="col-md-3"></div>
		<div class="text-primary text-center col-md-6">
			<h4>Auditor: 
			<?php  
				echo utf8_encode($auditor['nombre']) . " " . utf8_encode($auditor['ap_pat']) . " " . utf8_encode($auditor['ap_mat']); 
				// $query = 'select * from auditor where id_auditor = 1';
				// foreach ($conex->consultar($query) as $key => $value) {
				// 	echo $value['nombre'] . " " . $value['ap_pat'] ." ". $value['ap_mat'];
				// }
			?>	
			</h4><br><br>
			<h4>Empresa a evaluar: 
			</h4>
			<form id="formCambiarEmpresa" method="post" name="formCambiarEmpresa">
				<select id="comboEmpresa" name="comboEmpresa" class="col-md-5 form-control mx-auto">
					<?php  
						$query = 'select * from empresa';
						$selected = "";
						echo '<option value="default">Selecciona </option>';
						foreach ($conex->consultar($query) as $key => $value) {
							if(isset($_SESSION['empresa']) != false) {
								if($_SESSION['empresa']['id_empresa'] == $value['id_empresa']) {
 									$selected = "selected";
								} else {
									$selected = "";
								}	
							}
							echo '<option ' .$selected.' value='.$value['id_empresa'].'>'.$value['nombre']."</option>";
						}
					?>						
				</select>
				<br>
				<button id="cambiarEmpresaEvaluar" name="cambiarEmpresaEvaluar" class="btn btn-success">Aceptar</button>
			</form>
		</div> 
		</div>
		<br><br>
		<div class="col-md-12 text-center">
			<img class="img-responsive" src="../../img/iso.png">
		</div>
		
	</div>
	<!-- /.container-fluid -->
<?php
  $menu->menuFooter();
?>
<script type="text/javascript">
$.validator.addMethod("validarComboVacio", 
function(value, element, arg){ 
return arg != value; }, "<p class='text-danger'>Selecciona una empresa</p>"); 
	$(document).ready(function(){
		$("#formCambiarEmpresa").validate({
			rules: {
				comboEmpresa: {
					validarComboVacio: "default"
				}
			}, 
			submitHandler: function(){
				$.ajax({
					type: "POST",
					url: "../procesos/cambiarEmpresaEvaluar.php",
					data: $('#formCambiarEmpresa').serialize(),
					success: function(data) {
						if(data == 'ok') {
							Swal.fire(
								"¡Éxito!",
								"La empresa ha sido seleccionada para su evaluación.",
								"success").then(function() {
									window.location = "inicioView.php";
								});
							} else {
								Swal.fire(
									"¡Error!",
									"Ha ocurrido un error al seleccionar la empresa: " + data,
									"error");
							}
						}
					});
			}
		});
	});
</script>