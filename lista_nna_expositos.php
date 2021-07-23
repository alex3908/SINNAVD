
<?php
ob_start();
?>

<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$total="SELECT id from nna_exposito where nna_n=0";
	$etotal=$mysqli->query($total);
	$rows=$etotal->num_rows;
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
		<section id="search" class="alt"><h1>NNA expositos</h1>
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
						<h3>NNA expositos registrados: <?php echo $rows; ?></h3>
						<input type="button" class="button special fit small" name="expositos" value="NO EXPOSITOS" onclick="location='lista_nna.php'">
				</form>
			</section>
						
				
				<table>			
				
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$query="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable from departamentos, nna_exposito, municipios where nna_exposito.respo_reg=departamentos.id and nna_exposito.nna_n=0 and municipios.id=nna_exposito.municipio_deteccion having departamentos.responsable like '%$buscar%' OR municipios.municipio like '%$buscar%' OR nna_exposito.folio like '%$buscar%' OR nna_exposito.fecha_reg like '%$buscar%' limit 20";
					}else {
						$query="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable from departamentos, nna_exposito, municipios where nna_exposito.respo_reg=departamentos.id and nna_exposito.nna_n=0 and municipios.id=nna_exposito.municipio_deteccion having departamentos.responsable like '%$buscar%' OR municipios.municipio like '%$buscar%' OR nna_exposito.folio like '%$buscar%' OR nna_exposito.fecha_reg like '%$buscar%'";
					}
						$resultado=$mysqli->query($query);
						$rows2=$resultado->num_rows;
						
					if ($rows2>0) { ?>
						
					<tr>
					<td><b>Folio</b></td>
					<td><b>Fecha de registro</b></td>
					<td><b>Municipio de detección</b></td>
					<td><b>Servidor que registro</b></td>
					<td></td>
				</tr>
				<tbody>
				<?php echo "Resultados: ".$rows2;
					while($row=$resultado->fetch_assoc()){ 
							?>
				<tr>
					<td><?php echo $row['folio'];?></td>
					<td><?php echo $row['fecha_reg'];?></td>
					<td><?php echo $row['municipio'];?></td>
					<td><?php echo $row['responsable'];?></td>
					<td><input type="button" name="Ver" value="Ver" onclick="location='perfil_nna_exposito.php?id=<?php echo $row['id'];?>'"></td>
				</tr></tbody>
					<?php } 
					}else{ ?><div class="box"> NO HAY NNA EXPOSITOS REGISTRADOS</div> <?php } ?>
 					
				
			</table>	




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