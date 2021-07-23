<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}
	$idDEPTO = $_SESSION['id'];
	$idSup=$_GET['id'];	
	$fecha= date ("j/n/Y");

	$xcas="SELECT supervisiones.folio, centros.nombre, motivoscas.motivo, supervisiones.fecha_sup from centros, motivoscas, supervisiones where supervisiones.id='$idSup' and centros.id=supervisiones.id_centro and supervisiones.id_motivo=motivoscas.id";
	$excas=$mysqli->query($xcas);
	while ($row=$excas->fetch_assoc()) {
		$folio=$row['folio'];
		$nomC=$row['nombre'];
		$motivo=$row['motivo'];
		$fechaS=$row['fecha_sup'];
	}


	$lautoridad="SELECT autoridadesCAS.id, autoridadesCAS.autoridad from autoridadesCAS, autoridad_sup where autoridad_sup.id_supervision='$idSup' and autoridad_sup.id_autoridad=autoridadesCAS.id";
	$elautoridad=$mysqli->query($lautoridad);


	if(!empty($_POST))
	{
		$autoridad = $_POST['autoridad'];
		$observacion = mysqli_real_escape_string($mysqli,$_POST['observacion']);
		$recomendacion = mysqli_real_escape_string($mysqli,$_POST['recomendacion']);
		$temporalidad = mysqli_real_escape_string($mysqli,$_POST['temporalidad']);
		$tipo = $_POST['tipo'];
		
		$obser="INSERT INTO observaciones_sup (id_auto, observacion, recomendacion, tipo, temporalidad, fecha, respo_reg) VALUES ('$autoridad','$observacion','$recomendacion','$tipo','$temporalidad','´$fecha','$idDEPTO')";
		
	}			
	
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Supervisión</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
		
	</head>
	<body>
		
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
								<h2>Supervisión</h2>
								<h3>Observaciones y recomendaciones</h3>
								<div class="row uniform">
									<div class="2u 12u$(xsmall)">Folio: <strong><?php echo $folio; ?></strong>
									</div>
									<div class="5u 12u$(xsmall)">Centro: <strong><?php echo $nomC; ?></strong>
									</div>
									<div class="2u 12u$(xsmall)">Fecha: <strong><?php echo $fechaS; ?></strong>
									</div>
									<div class="3u 12u$(xsmall)">Motivo: <strong><?php echo $motivo; ?></strong>
									</div>
								</div>
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								<div class="box">
			 					<div class="row uniform">
									<div class="12u">
										<div class="row uniform">
											<div class="3u">AUTORIDAD
											<div class="select-wrapper">
											<select id="autoridad" name="autoridad" required>
												<?php while ($row=$elautoridad->fetch_assoc()) { ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['autoridad']; ?></option>
												<?php } ?>
												
											</select>
											</div>
											</div>
											<div class="3u">OBSERVACION
												<textarea name="observacion" required></textarea>
											</div>
											<div class="3u">RECOMENDACION
												<textarea name="recomendacion" required></textarea>
											</div>
											<div class="3u">TIPO DE RECOMENDACION
											<div class="select-wrapper">
											<select id="tipo" name="tipo" required>
												<option value="URGENTE">URGENTE</option>
												<option value="ATENCION INMEDIATA">ATENCION INMEDIATA</option>
											</select>												
											</div>
											</div>											
											<div class="3u">TEMPORALIDAD
												<input type="text" name="temporalidad" required>
											</div>
											<div class="6u">.
												<input class="button special fit" name="registar" type="submit" value="Registrar" >
											</div>
										</div>
									</div>									
								</div>								
							</div>
				<div class="12u 12u$(small)">
										<table>
											
										</table>																	
									</div>
						<div class="12u$">
							<ul class="actions">
								
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_nna.php'" >
							</ul>
						</div>
					</form>
				</div>
			</div>

				<!-- Sidebar -->
				
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>