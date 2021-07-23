
<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idUsusario=$_GET['id'];

	$valida="SELECT responsable from departamentos where id='$idUsusario'";
	$evalida=$mysqli->query($valida);
	while ($row=$evalida->fetch_assoc()) {
		$resp=$row['responsable'];
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
						<div class="inner"><br> <br> 
						<div class="box alt" align="center">
							<div class="row 10% uniform">
								<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
								<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
								<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
							</div>							
						</div> 

		<section id="search" class="alt">
			<input type="button" onclick="location='perfil_personal.php?id=<?php echo $idUsusario; ?>'" value="Regresar" class="button special small">
			<h2>Carpetas asignadas a <?php echo $resp; ?> </h2>
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />
						
				</form>
				
			</section>
						
<table  >
			
				<tr>
					<td><b>NUC</b></td>
					<td><b>Fecha de inicio</b></td>
					<td><b>Folio del caso</b></td>					
					<td><b>Estatus</b></td>
					<td></td>
				</tr>
				<tbody>
				<?php
	

	@$buscar = $_POST["palabra"];
	
	$query="SELECT carpeta_inv.id, carpeta_inv.nuc, carpeta_inv.fecha_ini, carpeta_inv.id_caso, casos.folio_c, carpeta_inv.estado, carpeta_inv.tipo_pross, departamentos.responsable from carpeta_inv, casos, departamentos where carpeta_inv.asignado='$idUsusario' and casos.id=carpeta_inv.id_caso and departamentos.id=carpeta_inv.asignado and (carpeta_inv.nuc like '%$buscar%' OR carpeta_inv.fecha_ini like '%$buscar%' OR departamentos.responsable like '%$buscar%' OR casos.folio_c like '%$buscar%') limit 20";
	
	$resultado=$mysqli->query($query);
	
 while($row=$resultado->fetch_assoc()){ ?>
					
						<tr>
							<td><?php echo $row['nuc'];?>
							</td>
							<td>
								<?php echo $row['fecha_ini'];?>
							</td>
							<td>
								<a href="perfil_caso.php?id=<?php echo $row['id_caso'];?>"><?php echo $row['folio_c'];?></a>
							</td>
							<td>
								<?php $est=$row['estado'];$tip=$row['tipo_pross'];
								if ($tip==0) {								
											if ($est==20) { ?>
								 	<img src="images/G20.png" width="80">
								 <?php }else if ($est==40) { ?>
								 	<img src="images/G40.png" width="80">
								 <?php }else if ($est==60) { ?>
								 	<img src="images/G60.png" width="80">
								 <?php }else if ($est==80) { ?>
								 	<img src="images/G80.png" width="80">
								 <?php }else if($est==100){ ?>
								 	<img src="images/G100.png" width="80">
								 <?php 	}
								 }else if ($tip>=1 or $tip<=4) { ?>
								 	INVESTIGACION TERMINADA
								 <?php }else if ($tip==5 or $tip==6) { ?>
								 	SOLUCION ALTERNA
								 <?php }else if ($tip==7) { ?>
								 	TERMINACION ANTICIPADA
								 <?php } ?>
							</td>
							<td>
								<input type="button" name="Ver" value="Ver" onclick="location='perfil_carpeta.php?id=<?php echo $row['id'];?>'">
							</td>
							
						</tr>
					<?php } ?>
				</tbody>
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