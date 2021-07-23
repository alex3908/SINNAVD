<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idNNA=$_GET['id'];

	$sqlnna="SELECT nna.folio, nna.nombre, nna.apellido_p, nna.curp, nna.apellido_m, nna.fecha_reg, nna.respo_reg from nna where nna.id='$idNNA'";
	$esqlnna=$mysqli->query($sqlnna);
	$fecha= date ("j/n/Y");
	$bandera = false;
	
	if(!empty($_POST))
	{
		$curp = mysqli_real_escape_string($mysqli,$_POST['curp']);
		$fecha_reg=$_POST['fecha'];
		$error = '';
			@$sqlNino = "UPDATE nna SET curp='$curp', fecha_reg='$fecha', fecha_reg2='$fecha_reg', respo_regc='$idDEPTO' WHERE id='$idNNA'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			$bandera = true;
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
		<script type="text/javascript" src="jquery.min.js"></script>
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
		
		<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 	
		<div class="box" >
			<h2>Actualizar CURP</h2>			
				<?php while ($row=$esqlnna->fetch_assoc()) { ?>				
				<h3>Folio: <?php echo $row['folio'].' - '.$row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></h3>
				<div class="row uniform">
					<div class="4u 12u$(xsmall)"><input type="hidden" name="fecha" value="<?php echo $row['fecha_reg']; ?>"></div>								
					<div class="4u 12u$(xsmall)">CURP
						<input id="curp" name="curp" type="text" value="<?php echo $row['curp'];  ?>" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
					</div>	
					<div class="4u 12u$(xsmall)"></div>
				<?php } ?>
					<div class="6u 12u$(xsmall)">						
						<input class="button special fit" name="registar" type="submit" value="Actualizar" >	
					</div>
					<div class="6u 12u$(xsmall)">						
						<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?php echo $idNNA; ?>'" >						
					</div>
				</div>
		</div>
		</form>	
			
		
						
	<?php if($bandera) { 
			header("Location: perfil_nna.php?id=$idNNA");
			}else{ ?>
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
												<li><a href="registro_usuarios.php">Alta</a></li>
												
												
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