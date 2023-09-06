<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>UAEM Sistema de Tickets::Perfil</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

	<div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">

			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Perfil</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="..\Home\">Inicio</a></li>
								<li class="active">Cambiar Contraseña</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<h5 class="m-t-lg with-border">Cambiar contraseña de usuario:</h5>

				<div class="row">

						<div class="col-lg-6"> <!-- Campo para ingresar nueva contraseña -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="exampleInput">Nueva Contraseña</label>
								<input type="password" class="form-control" id="txtpass" name="txtpass">
							</fieldset>
						</div>

						<div class="col-lg-6"> <!-- Campo para confirmar nueva contraseña -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="exampleInput">Confirmar Contraseña</label>
								<input type="password" class="form-control" id="txtpassnew" name="txtpassnew">
							</fieldset>
						</div>
						
						<div class="col-lg-12"> <!-- #Boton para actualizar contraseña -->
							<button type="button" id="btnactualizar" class="btn btn-rounded btn-inline btn-primary">Actualizar</button>
						</div>
				</div>

			</div>
		</div>
	</div>
	<!-- Cotenido -->
	
    <?php require_once("../MainJs/js.php");?>
	
	<script type="text/javascript" src="mntperfil.js"></script>
</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>