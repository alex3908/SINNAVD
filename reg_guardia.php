<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$fecha= date ("j/n/Y");
	$sql = "SELECT id, responsable FROM departamentos WHERE id= '$idDEPTO'";	
	$result=$mysqli->query($sql);
	if ($row=$result->fetch_assoc()) {
		$respo=$row['responsable'];
	}
	
	$sexo="SELECT id, sexo FROM sexo";
	$resu=$mysqli->query($sexo);

	$bandera = false;
	
	if(!empty($_POST))
	{
		$fecha_ini=mysqli_real_escape_string($mysqli,$_POST['fecha_ini']);
		$fecha_fin=mysqli_real_escape_string($mysqli,$_POST['fecha_fin']);

		$error = '';
		if (empty($fecha_ini) and empty($fecha_fin)) {
			$error="Llenar campos";
		}else {
			$varipfolio="SELECT max(id) from reporte_guardia";
			$evaripfolio=$mysqli->query($varipfolio);
			if ($row=$evaripfolio->fetch_assoc()) {
				$ffolio=$row['max(id)'];
			}
			$ffolio=$ffolio+1;
			$ff="RG0".$ffolio;
			$sq="INSERT INTO reporte_guardia (folio, fecha_ini, fecha_fin, respo_reg, fecha_reg, envio, atendido) values ('$ff','$fecha_ini','$fecha_fin','$idDEPTO', '$fecha', '0', '0')";
			$esq=$mysqli->query($sq);
			echo $sq;
			if($esq>0)
			header("Location:welcome.php");
			else
			$error = "Error al Registrar";
			}
		}
	
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>MiGuardia</title>
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
		</div></div> <br>	
						
		
			<div class="box" >
			<h1>Registro de Guardia</h1>
			
			<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">
			<input id="fecha_ini" name="fecha_ini" type="text" class="fecha_ini" placeholder="Fecha de inicio" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required="required">
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="fecha_fin" name="fecha_fin" type="text" class="fecha_fin" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="Fecha de fin" required="required">
			</div>
			<div class="12u$">
			<input id="respo_reg" name="respo_reg" type="text" class="respo_reg" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled value="<?php echo $respo; ?>">
			</div>
			
										
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
			</ul></div>
			</div>
			</form>	
		</div>
	
						
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
									<ul><li><a href="welcome.php">Inicio</a></li>
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
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												<li><a href="atenciones_area.php">Atenciones</a></li>
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