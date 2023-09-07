<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>UAEM Sistema de Tickets::Nuevo Ticket</title>
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
							<h3>Nuevo Ticket</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="..\Home\">Inicio</a></li>
								<li class="active">Nuevo Ticket</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<p>
					Desde esta ventana podrás generar nuevos tickets de ayuda:
				</p>

				<h5 class="m-t-lg with-border">Ingresar Información</h5>

				<div class="row">
					<form method="post" id="ticket_form">

						<input type="hidden" id="usu_id" name="usu_id" value="<?php echo $_SESSION["usu_id"] ?>">

						<div class="col-lg-12"> <!-- #TextArea para titulo del ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="tick_titulo">Título</label>
								<input type="text" class="form-control" id="tick_titulo" name="tick_titulo" placeholder="Ingrese título">
							</fieldset>
						</div>

						<div class="col-lg-6"> <!-- selector de categoria para el ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="cat_id">Categoría</label>
								<select id="cat_id" name="cat_id" class="form-control" data-placeholder="Seleccionar">
									
								</select>
							</fieldset>
						</div>

						<div class="col-lg-6"> <!-- selector de subcategoria para el ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="cats_id">SubCategoría</label>
								<select id="cats_id" name="cats_id" class="form-control" data-placeholder="Seleccionar">
									<option label="Seleccionar"></option>
								</select>
							</fieldset>
						</div>

						<div class="col-lg-6"> <!-- selector de prioridad para el ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="prio_id">Prioridad</label>
								<select id="prio_id" name="prio_id" class="form-control" data-placeholder="Seleccionar">
									<option label="Seleccionar"></option>
								</select>
							</fieldset>
						</div>

						<div class="col-lg-6"> <!-- Boton para subir documentos al ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="fileElem">Documentos Adicionales</label>
								<input type="file" name="fileElem" id="fileElem" class="form-control" multiple>
							</fieldset>
						</div>
						
						<div class="col-lg-12"> <!-- #Summernote para escribir la descipcion del ticket -->
							<fieldset class="form-group">
								<label class="form-label semibold" for="tick_descrip">Descripción</label>
								<div class="summernote-theme-3">
									<textarea id="tick_descrip" name="tick_descrip" class="summernote" name="name"></textarea>
								</div>
							</fieldset>
						</div>
						<div class="col-lg-12"> <!-- #Boton para subir el ticket -->
							<button type="submit" name="action" value="add" class="btn btn-rounded btn-inline btn-primary">Guardar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<!-- Cotenido -->
	
    <?php require_once("../MainJs/js.php");?>
	
	<script type="text/javascript" src="nuevoticket.js"></script>
</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>