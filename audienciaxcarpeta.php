
<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idCarpeta=$_GET['id'];
	$buscaf="SELECT id, responsable, id_depto from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);
	
	while ($row=$ebf->fetch_assoc()) {
		$id_d=$row['id_depto'];
	}
	$audi="SELECT audiencias.id, audiencias.folio, audiencias.nombre, audiencias.fecha_aud, audiencias.fecha_reg, audiencias.lugar, audiencias.duracion, audiencias.id_carpeta, departamentos.responsable from audiencias, departamentos where departamentos.id=audiencias.respo_reg and audiencias.id_carpeta='$idCarpeta'";
	$eaudi=$mysqli->query($audi);
	$eaudi1=$mysqli->query($audi);

	$audr="SELECT id, respo_reg from audiencias where id_carpeta='$idCarpeta'";
	$eaudr=$mysqli->query($audr);
	$rows2=$eaudr->num_rows;
	if ($rows2==0) {
		
	}else {

	while ($row=$eaudi1->fetch_assoc()) {
		@$id_A=$row['id'];
		
	}
	while ($row=$eaudr->fetch_assoc()) {
		$respo_reg=$row['respo_reg'];
	}
	
	$xrepre="SELECT departamentos.responsable from personas_aud, departamentos where personas_aud.id_aud='$id_A' and departamentos.id=personas_aud.id_respo";
	$exrepre=$mysqli->query($xrepre);
	$rows=$exrepre->num_rows;

	}
?>



<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
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
						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> 
		
						
				<br>
				<div class="row uniform">
				<?php if ($rows2==0) { 
					echo "No hay audiencias registradas en esta carpeta";
				}else { ?>
				<?php while ($row=$eaudi->fetch_assoc()) { ?>
					
				<div class="6u 12u$(xsmall)">	
				<div class="box">
					<h2><?php echo $row['nombre']; ?></h2>
					<u>Folio:</u> <?php echo $row['folio']; ?><br>
					<u>Fecha de audiencia:</u> <?php echo $row['fecha_aud']; ?><br>
					<u>Lugar:</u> <?php echo $row['lugar']; ?><br>
					<u>Fecha de registro:</u> <?php echo $row['fecha_reg']; ?><br>
					<u>Responsable de registro:</u> <?php echo $row['responsable']; ?><br>
					<u>Duración:</u> <?php $dura=$row['duracion'];
											if (empty($dura)) { ?>
												<?php if ($respo_reg==$idDEPTO) { ?>
													<input type="button" name="" value="Registrar" class="button small" onclick="location='duracion.php?id=<?php echo $row['id']; ?>'">
												<?php 							} ?>
											<?php 			  }else { echo $row['duracion']." horas"; }?><br>
					<u>Representantes:</u> <?php if ($rows==0) { ?><?php if ($respo_reg==$idDEPTO) { ?>
						<input type="button" name="" class="button small" value="Registrar" onclick="location='representantes_aud.php?id=<?php echo $id_A; ?>'"><?php }  ?>
					<?php }else {
					 while ($row=$exrepre->fetch_assoc()) {
					 	echo $row['responsable'].", "; 
					}
					
					} ?>
				</div>
				</div>
				<?php } } ?>
				</div>
			<?php if(@$bandera) { 
			header("Location: welcome.php");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

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
									<ul><li><a href="welcome.php">Inicio</a></li>
										
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
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
												
												
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
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										
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