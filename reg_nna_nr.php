<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$sqlres="SELECT responsable from departamentos where id='$idDEPTO'";
	$esqlres=$mysqli->query($sqlres);
	while ($row=$esqlres->fetch_assoc()) {
		$respo=$row['responsable'];
	}
	$fechaa= date ("j/n/Y");

	$bandera = false;
	if(!empty($_POST))
	{
		$sexo = $_POST['sexo'];
		$municipio = $_POST['municipio'];
		$deteccion = mysqli_real_escape_string($mysqli,$_POST['deteccion']);
		if ($sexo=='HOMBRE') {
			$lsex='H';
		}else if ($sexo='MUJER') {
			$lsex='M';
		}

		
		$error = '';
		
		
		
		$snum='SELECT terminacion from nfolio where id=2';
		$esnum=$mysqli->query($snum);
		while ($row=$esnum->fetch_assoc()) {
			$ter=$row['terminacion'];
		}
		$ter2=$ter+1;
		$folio="NR".$lsex.$idDEPTO.$ter2;
		$sqlUser = "SELECT id FROM nna WHERE folio = '$folio'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;
		


		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Ya existe');</script>
			
			<?php } else {
			
			$sqlNino = "INSERT INTO nna_exposito (folio, respo_reg, sexo, municipio_deteccion, fecha_reg, situacion, nna_n) VALUES ('$folio', '$idDEPTO', '$sexo', '$municipio', '$fechaa', '$deteccion', '0')";
			$resultNino = $mysqli->query($sqlNino);
			$contador="UPDATE nfolio set terminacion='$ter2' where id=2";
			$econt=$mysqli->query($contador);
			echo $sqlNino;
			if($resultNino>0)
			header("Location: welcome.php");
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
								<h1>NNA Exposito</h1>
								
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="6u 12u$(xsmall)">
										<input id="nombre" name="nombre" type="text" placeholder="Nombre temporal"  style="text-transform:uppercase;" value="<?php echo $respo; ?>" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									
									<div class="6u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="sexo" name="sexo" required>
												<option value="">SEXO...</option>
												<option value="HOMBRE">HOMBRE</option>
												<option value="MUJER">MUJER</option>
											</select>
										</div>
									</div>
									<div class="6u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="municipio" name="municipio"  required>
												<option value="0">MUNICIPIO DE DETECCION</option>
												<?php 
												
												$mun="SELECT id, municipio from municipios";
												$emun=$mysqli->query($mun);
											
												while ($row=$emun->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
												<?php } ?>
											</select>
										</div>	
									</div>
									
									<div class="6u 12u$(xsmall)">
										<input id="fecha_nac" name="fecha_nac" type="text" placeholder="Fecha de nacimiento   dd/mm/aaaa" value="<?php echo $fechaa; ?>"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>Fecha de registro	
									</div>
									<div class="12u$">Situación de detección:
										<textarea id="deteccion" name="deteccion" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
									</div>
									
						</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
		</ul>
	</div>
</form>


		<?php if($bandera) { 
			header("Location: welcome.php");

			?>
						
			<?php }else{ ?>
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