
<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];

	$total="SELECT id from reporte_guardia";
	$etotal=$mysqli->query($total);
	$rows3=$etotal->num_rows;

	$valida="SELECT id from departamentos where (id_depto='9' and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	$evalida=$mysqli->query($valida);
	$rows2=$evalida->num_rows;


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
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px"   /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
		</div></div> 
		<section id="search" class="alt"><h2>Reportes de guardias</h2>
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />
						<h4>Total de reportes: <?php echo $rows3; ?></h4>
				</form>				
					
		
			</section>
			<table >			
				<tr>
					<td><b>FOLIO</b></td>
					<td><b>FECHA INICIO</b></td>
					<td><b>FECHA FIN</b></td>
					<td><b>RESPONSABLE DEL REPORTE</b></td>
					<td><b>ESTATUS</b></td>
				</tr>
				<tbody>
				<?php
	@$buscar = $_POST["palabra"];
	
	if (empty($buscar)) {
		$query="SELECT reporte_guardia.id, reporte_guardia.folio, reporte_guardia.fecha_ini, reporte_guardia.fecha_fin, departamentos.responsable, reporte_guardia.envio from reporte_guardia, departamentos where reporte_guardia.respo_reg=departamentos.id and (reporte_guardia.folio like '%$buscar%' OR reporte_guardia.fecha_ini like '%$buscar%' OR reporte_guardia.fecha_fin like '%$buscar%' OR departamentos.responsable like '%$buscar%') limit 20";
	} else {
		$query="SELECT reporte_guardia.id, reporte_guardia.folio, reporte_guardia.fecha_ini, reporte_guardia.fecha_fin, departamentos.responsable, reporte_guardia.envio from reporte_guardia, departamentos where reporte_guardia.respo_reg=departamentos.id and (reporte_guardia.folio like '%$buscar%' OR reporte_guardia.fecha_ini like '%$buscar%' OR reporte_guardia.fecha_fin like '%$buscar%' OR departamentos.responsable like '%$buscar%') ";
	}	
	$resultado=$mysqli->query($query);
	
	$rows=$resultado->num_rows;
	echo "Resultados: ".$rows;
	
 while($row=$resultado->fetch_assoc()){ $estado=$row['envio'];  $idRG=$row['id'];  ?>
					
						<tr>
							<td><a href="perfil_repG.php?id=<?php echo $idRG; ?>"><?php echo $row['folio'];?></a></td>
							<td><?php echo $row['fecha_ini'];?></td>
							<td><?php echo $row['fecha_fin'];?></td>
							<td><?php echo $row['responsable']; ?></td>
							<?php 
								$pceg="SELECT count(carpetas_guardia.id_carpeta) as tanto from carpeta_inv, carpetas_guardia, reporte_guardia where carpeta_inv.asignado=0 and carpetas_guardia.id_guardia='$idRG' and carpetas_guardia.id_carpeta=carpeta_inv.id and reporte_guardia.id=carpetas_guardia.id_guardia";
								$epceg=$mysqli->query($pceg);
								while ($row=$epceg->fetch_assoc()) {
								 	$pcheck=$row['tanto'];
								 	
								 } ?>
								
							<?php if ($estado=='0') { ?>	
								<td><img src="images/abierto.png" width="80px" height="70px" onclick="location='perfil_repG.php?id=<?php echo $idRG; ?>'" /></td>		
							<?php }else if ($estado=='1') { ?>
									<?php if($pcheck=='0'){ ?>	
								<td><img src="images/atendido.png" width="80px" height="60px" onclick="location='perfil_repG.php?id=<?php echo $idRG; ?>'" /></td>
									<?php }else if($pcheck>'0'){ ?>
								<td><img src="images/enviado.png" width="80px" height="60px" onclick="location='perfil_repG.php?id=<?php echo $idRG; ?>'" /></td>
									<?php } ?>
							<?php } ?>		
						</tr>
								<?php } ?>
				</tbody>
			</table>	


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