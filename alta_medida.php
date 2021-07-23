<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$total="SELECT id from catalogo_medidas";
	$etotal=$mysqli->query($total);	
	$rows2=$etotal->num_rows;

	if(!empty($_POST['regMed'])){
		$medida = mysqli_real_escape_string($mysqli,$_POST['medida']);

		$varipfolio="SELECT max(id) from catalogo_medidas";
		$evaripfolio=$mysqli->query($varipfolio);
			if ($row=$evaripfolio->fetch_assoc()) {
				$ffolio=$row['max(id)'];
			}
			$ffolio=$ffolio+1;
		$folioMed='M'.$ffolio;
		$reg="INSERT into catalogo_medidas (folio, medidaC) value ('$folioMed', '$medida')";
		$ereg=$mysqli->query($reg);

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
						<div class="inner"><br><br><h2>Medidas de Protección</h2>
			<div class="box">	
			<?php if ($_SESSION['departamento']==16) { ?>
				<form id="registro" name="registro" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
					<div class="row uniform">
						<div class="10u 12u$(xsmall)">
							<input id="medida" name="medida" type="text" placeholder="Medida"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
						</div>
						<div class="2u 12u$(xsmall)">
							<input type="submit" class="button special fit" name="regMed" value="Registrar">
						</div>
					</div>
				</form>
			<?php }else {} ?>	<br>	
			<section id="search" class="alt">
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
						<h3>Medidas registradas: <?php echo $rows2; ?></h3>
						
				</form>
			</section>
						
				
				<table>			
				<tr>
					<td><b>Numero</b></td>
					<td><b>Medida</b></td>
					
				</tr>
				<tbody>
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$query="SELECT id, folio, medidaC from catalogo_medidas where folio like '%$buscar%' OR medidaC like '%$buscar%' limit 20";
					}else {
						$query="SELECT id, folio, medidaC from catalogo_medidas where folio like '%$buscar%' OR medidaC like '%$buscar%'";
					}
					
						$resultado=$mysqli->query($query);
						$rows=$resultado->num_rows;
						echo "Resultados: ".$rows;
 					while($row=$resultado->fetch_assoc()){ ?>
					
				<tr>
					<td><?php echo $row['folio'];?></td>
					<td><?php echo $row['medidaC']; ?></td>
					
				</tr>
					<?php } ?>
				</tbody>
			</table>	

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