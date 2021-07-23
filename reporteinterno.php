<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	 //Establecemos zona horaria por defecto
    date_default_timezone_set('America/Mexico_City');
    //preguntamos la zona horaria
    $zonahoraria = date_default_timezone_get();
	$horaa= date ("h:i");
$fechaa= date ("j/n/Y");
$fechahora=$fechaa." ".$horaa;

	$buscaf="SELECT id, responsable, id_depto from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);
	$ebf2=$mysqli->query($buscaf);

	while ($row=$ebf->fetch_assoc()) {
		$id_d=$row['id_depto'];
	}

	if(!empty($_POST))
	{
		$tipo = $_POST['tipo'];
		
		
		$error = '';
		
			
			$sqlNino = "INSERT INTO reportes_int (id_solicitante,id_depto,tipo,fecha_ini,estado) VALUES ('$idDEPTO','$id_d','$tipo','$fechahora','0')";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: welcome.php");
			else
			$error = "Error al Registrar";
			
		
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
							<br> <br> 
							<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div>
		</div>
								<h1>Reporte</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="12u$">Tipo:
										<div class="select-wrapper">
											<select id="tipo" name="tipo" >
												<option value="ASESORIA EN SISTEMA">ASESORIA EN SISTEMA</option>
												<option value="FALLA TECNICA">FALLA TECNICA</option>
												<option value="MODIFICACION SISTEMA">MODIFICACION SISTEMA</option>
											</select>
										</div>
									</div>
									<?php  while ($row=$ebf2->fetch_assoc()) {
										
									  ?>
									<div class="4u 12u$(xsmall)">Servidor publico:
										<input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado" type="text"  disabled value="<?php echo $row['responsable']; }?>">
									</div>
									
									<div class="4u 12u$(xsmall)">Fecha:
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fechaa; ?>" disabled>	
									</div>
									<div class="4u 12u$(xsmall)">Hora:
										<input id="hora_reg" name="hora_reg" type="text" value="<?php echo $horaa; ?>" disabled>	
									</div>
									
									
									
								</div>
								
						<br>
			
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="enviar" type="submit" value="enviar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
		</ul>
	</div>
</form>
</div>
</div>
		
						</div>
					

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							
						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>

									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										
										<li><a href="welcome.php" ">Inicio</a></li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>		
							
								<?php }
	
								?>
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