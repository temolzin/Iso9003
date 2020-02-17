<?php 
	require 'funciones/procesos/Conexion.class.php';
	$conex = Conexion::getInstance();
?>
			<!DOCTYPE html>
			<html lang="en">

			<head>
			  <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <meta name="description" content="">
			  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			  <meta name="author" content="">

			  <title>Calidad de Software</title>

			  <!-- Custom fonts for this template-->
			  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
			  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
			  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
			  <link rel="icon" href="favicon.png" type="image/x-icon">
			  <!-- Custom styles for this template-->
			  <link href="css/sb-admin-2.min.css" rel="stylesheet">

			</head>
			<body id="page-top">

			  <!-- Page Wrapper -->
			  <div id="wrapper">

			    <!-- Sidebar -->
			    <ul class="navbar-nav bg-gradient-primary sidebar toggled sidebar-dark accordion" id="accordionSidebar">

			      <!-- Sidebar - Brand -->
			      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
			        <div class="sidebar-brand-icon rotate-n-15">
			          <i class="fas fa-asterisk"></i>
			        </div>
			        <div class="sidebar-brand-text mx-3">ISO 9000-3</div>
			      </a>

			      <!-- Divider -->
			      <hr class="sidebar-divider my-0">

			      <!-- Nav Item - Dashboard -->
			      <li class="nav-item active">
			        <a class="nav-link" href="index.php">
			          <i class="fas fa-fw fa-tachometer-alt"></i>
			          <span>Inicio</span></a>
			      </li>

			      <!-- Divider -->
			      <hr class="sidebar-divider my-0">

			      <!-- Heading -->
			<!--       <div class="sidebar-heading">
			        Interface
			      </div> -->

			      <!-- Nav Item - Pages Collapse Menu -->
 			      <li class="nav-item ' .$activeEmpresa . '">
			        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
			          <i class="fas fa-fw fa-building"></i>
			          <span>Empresa</span>
			        </a>
			        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			          <div class="bg-white py-2 collapse-inner rounded">
	<!-- 		             <h6 class="collapse-header">Custom Components:</h6> -->
 			            <a class="collapse-item" href="buttons.html">Registrar</a>
			            <a class="collapse-item" href="cards.html">Ver Empresas</a>
			          </div>
			        </div>
			      </li>
			        <!-- Divider -->
			      <hr class="sidebar-divider my-0">

			      <!-- Nav Item - Utilities Collapse Menu -->
			      <li class="nav-item ' .$activeAuditoria. '">
			        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
			          <i class="fas fa-fw fa-atlas"></i>
			          <span>Auditoria</span>
			        </a>
			        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			          <div class="bg-white py-2 collapse-inner rounded">
			          	<?php
			          	$query = "select * from categoriapregunta";
			          	$consulta = $conex->consultar($query);
			          	foreach ($consulta as $key => $value) {
			          		echo '<a class="collapse-item" href="funciones/vistas/subCategoriaView.php?id='.$value['id_categoria'].'">'.utf8_encode($value['nombre']).'</a>';
			          	}
			          	?>
			          </div>
			        </div>
			      </li>

			      <!-- Divider -->
			      <hr class="sidebar-divider my-0">

			      <!-- Heading -->
			<!--       <div class="sidebar-heading">
			        Reporte
			      </div> -->

			      <!-- Nav Item - Pages Collapse Menu -->
			      <li class="nav-item ' .$activeReporte. '">
			        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
			          <i class="fas fa-fw fa-clipboard"></i>
			          <span>Reportes</span>
			        </a>
			        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
			          <div class="bg-white py-2 collapse-inner rounded">
			            <h6 class="collapse-header">Reporte</h6>
			            <a class="collapse-item" href="funciones/vistas/reporteResultadoView.php">Resultados</a>
			            <a class="collapse-item" href="funciones/vistas/reporteConteoView.php">Conteos</a>
			            <a class="collapse-item" href="funciones/vistas/reporteCertamenView.php">Certamen</a>
			          </div>
			        </div>
			      </li>

			      <!-- Divider -->
			<!--       <hr class="sidebar-divider d-none d-md-block"> -->

			      <!-- Sidebar Toggler (Sidebar) -->
			<!--       <div class="text-center d-none d-md-inline">
			        <button class="rounded-circle border-0" id="sidebarToggle"></button>
			      </div> -->

			    </ul>
			    <!-- End of Sidebar -->

			    <!-- Content Wrapper -->
			    <div id="content-wrapper" class="d-flex flex-column">

			      <!-- Main Content -->
			      <div id="content">

			        <!-- Topbar -->
			        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

			          <!-- Sidebar Toggle (Topbar) -->
			          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
			            <i class="fa fa-bars"></i>
			          </button>

			          <!-- Topbar Search -->
			<!--           <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
			            <div class="input-group">
			              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
			              <div class="input-group-append">
			                <button class="btn btn-primary" type="button">
			                  <i class="fas fa-search fa-sm"></i>
			                </button>
			              </div>
			            </div>
			          </form>
			 -->
			          <!-- Topbar Navbar -->
			          <ul class="navbar-nav ml-auto">

			            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
			<!--             <li class="nav-item dropdown no-arrow d-sm-none">
			              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                <i class="fas fa-search fa-fw"></i>
			              </a> -->
			              <!-- Dropdown - Messages -->
			<!--               <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
			                <form class="form-inline mr-auto w-100 navbar-search">
			                  <div class="input-group">
			                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
			                    <div class="input-group-append">
			                      <button class="btn btn-primary" type="button">
			                        <i class="fas fa-search fa-sm"></i>
			                      </button>
			                    </div>
			                  </div>
			                </form>
			              </div>
			            </li> -->
			            <li class="nav-item dropdown no-arrow mx-1">
			                        <p class="text-primary text-right nav-link dropdown-toggle"> Calidad de Software </p>
			            </li>

			            <div class="topbar-divider d-none d-sm-block"></div>

			            <!-- Nav Item - User Information -->
			            <li class="nav-item dropdown no-arrow">
			              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Auditor</span>
			                <img class="img-profile rounded-circle" src="img/perfil.png">
			              </a>
			              <!-- Dropdown - User Information -->
			              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
			                <a class="dropdown-item" href="#">
			                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
			                  Perfil
			                </a>
			                <div class="dropdown-divider"></div>
			                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
			                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			                  Cerrar Sesión
			                </a>
			              </div>
			            </li>

			          </ul>

			        </nav>
			        <!-- End of Topbar -->

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
				$query = 'select * from auditor where id_auditor = 1';
				foreach ($conex->consultar($query) as $key => $value) {
					echo $value['nombre'] . " " . $value['ap_pat'] ." ". $value['ap_mat'];
				}
			?>	
			</h4><br><br>
			<h4>Empresa a evaluar: 
			</h4>
			<select class="col-md-5 form-control mx-auto">
				<?php  
					$query = 'select * from empresa';
					foreach ($conex->consultar($query) as $key => $value) {
						echo '<option value='.$value['id_empresa'].'>'.$value['nombre']."</option>";
					}
				?>						
			</select>
			<br>
			<button class="btn btn-success">Aceptar</button>
		</div> 
		</div>
		<br><br>
		<div class="col-md-12 text-center">
			<img class="img-responsive" src="img/iso.png">
		</div>
		
	</div>
	<!-- /.container-fluid -->
					      </div>
		      <!-- End of Main Content -->

		      <!-- Footer -->
		      <footer class="sticky-footer bg-white">
		        <div class="container my-auto">
		          <div class="copyright text-center my-auto">
		            <span>Copyright &copy; TemolzinItzae 2019</span>
		          </div>
		        </div>
		      </footer>
		      <!-- End of Footer -->

		    </div>
		    <!-- End of Content Wrapper -->

		  </div>
		  <!-- End of Page Wrapper -->

		  <!-- Scroll to Top Button-->
		  <a class="scroll-to-top rounded" href="#page-top">
		    <i class="fas fa-angle-up"></i>
		  </a>

		  <!-- Logout Modal-->
		  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		    <div class="modal-dialog" role="document">
		      <div class="modal-content">
		        <div class="modal-header">
		          <h5 class="modal-title" id="exampleModalLabel">¿Quieres cerrar sesión?</h5>
		          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
		            <span aria-hidden="true">×</span>
		          </button>
		        </div>
		        <div class="modal-body">Selecciona cancelar para regresar al menú o selecciona cerrar sesión para abandonar el sistema</div>
		        <div class="modal-footer">
		          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
		          <a class="btn btn-primary" href="login.html">Cerrar Sesión</a>
		        </div>
		      </div>
		    </div>
		  </div>

		  <!-- Bootstrap core JavaScript-->
		  <script src="vendor/jquery/jquery.min.js"></script>
		  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

		  <!-- Core plugin JavaScript-->
		  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

		  <!-- Custom scripts for all pages-->
		  <script src="js/sb-admin-2.min.js"></script>

		  <!-- Page level plugins -->
<!-- 		  <script src="vendor/chart.js/Chart.min.js"></script> -->

		  <!-- Page level custom scripts -->
<!-- 		  <script src="js/demo/chart-area-demo.js"></script>
		  <script src="js/demo/chart-pie-demo.js"></script> -->

		</body>

		</html>