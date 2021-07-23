<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idReporte=$_SESSION['idRep'];
	$bandera = false;

	$fecha= date ("j/n/Y");
	$buscaf="SELECT responsable  from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);

	$folio_r="SELECT folio from reportes_vd where id='$idReporte'";
	$efo=$mysqli->query($folio_r);
	while ($row=$efo->fetch_assoc()) {
		$folio_rep=$row['folio'];
	}

	$report="SELECT reportes_vd.id, reportes_vd.folio from reportes_vd, reporte_caso where reporte_caso.id_reporte!=reportes_vd.id";
	$ereport=$mysqli->query($report);

	if(!empty($_POST))
	{
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$descripcion = mysqli_real_escape_string($mysqli,$_POST['descripcion']);		
		$error = '';		
		$sqlUser = "SELECT id FROM casos WHERE nombre = '$nombre'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;		
		$foli="SELECT terminacion from nfolio where id=3";
		$efoli=$mysqli->query($foli);
		while ($row=$efoli->fetch_assoc()) {
			$ter=$row['terminacion'];
		}
		$ter2=$ter+1;
		$folio='CAP0'.$ter2.'P';
		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Ya existe un caso con ese nombre');</script>
			
			<?php } else {
			
			$sqlNino = "INSERT INTO casos(folio_c,nombre, descripcion, id_reporte, funcionario_reg, fecha) VALUES ('$folio','$nombre', '$descripcion', '$idReporte', '$idDEPTO', '$fecha')";
			$resultNino = $mysqli->query($sqlNino);
			$upd="UPDATE nfolio set terminacion=$ter2 where id=3";
			$eudp=$mysqli->query($upd);
			if($resultNino>0)
			header("Location: lista_casos.php");
			else
			$error = "Error al Registrar";
			
		}
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		
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
								<h1>Registro de caso</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="12u$">Nombre del caso:
										<input id="nombre" name="nombre" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<?php  while ($row=$ebf->fetch_assoc()) {
										
									  ?>
									<div class="4u 12u$(xsmall)">Servidor publico:
										<input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado" type="text"  disabled value="<?php echo $row['responsable']; }?>">
									</div>
									
									<div class="4u 12u$(xsmall)">Fecha de registro:
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha; ?>" disabled>	
									</div>

									<div class="4u 12u$(xsmall)">Reporte:
										<input id="rep" name="rep" type="text" value="<?php echo $folio_rep; ?>" disabled>	
									</div>
									
									<div class="12u$">Detección:
										<textarea name="descripcion" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
									
								</div>
								
						</div>
			
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_casos.php'" >
		</ul>
	</div>
</form>
		<?php if($bandera) { header("Location: lista_casos.php");
			 }else{ ?>
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>
		
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>	
								<section>
									<header class="major">
										<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
									</header>
									<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-home">Plaza Juarez #118<br />
										Col. Centro <br> Pachuca Hidalgo</li>
									</ul>
								</section>
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Sistema DIF Hidalgo </p>
								</footer>

						</div>
					</div>
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