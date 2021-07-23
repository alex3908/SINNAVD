<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idCaso = $_GET['id'];
	
	$dis="SELECT id, distrits from distritos";
	$edis=$mysqli->query($dis);	
	$del="SELECT id, delito from delitos";
	$edel=$mysqli->query($del);
	
	$query="SELECT casos.id, casos.folio_c, casos.nombre, casos.descripcion, departamentos.responsable, date_format(casos.fecha_registro, '%d/%m/%Y') as fecha FROM departamentos, casos WHERE casos.funcionario_reg=departamentos.id and casos.id='$idCaso'";
	
	$resultado=$mysqli->query($query);
	if(!empty($_POST['guardar']))
	{
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);		
		$descripcion = mysqli_real_escape_string($mysqli,$_POST['descripcion']);			
		$sqlNino = "UPDATE casos set nombre='$nombre', descripcion='$descripcion' WHERE id='$idCaso'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: perfil_caso.php?id=$idCaso");		
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	</head>
	<body>
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	
		<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<?php while($row=$resultado->fetch_assoc()){ ?>
						<h2>Folio: <?php echo $row['folio_c'];  ?></h4></h2>
					
						<ul class="alt">
								<li><h4>Nombre: </h4><input name='nombre' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['nombre']; ?>" required></li>
								<li><h4>Detección: </h4><input name='descripcion' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['descripcion']; ?>" required></li>
								
								<li><h4>Responsable del registro: </h4><?php echo $row['responsable'];  ?> </li>
								<li><h4>Fecha: </h4><?php echo $row['fecha'];  ?> </li>
						</ul>
						<?php } ?>
						<input type="submit" name="guardar" class="fit" value="guardar">
						<input type="button" class="special fit" value="cancelar" onclick="location='perfil_caso.php?id=<?php echo $idCaso; ?>'">
						</form>
							
						
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