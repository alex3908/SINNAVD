<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idU = $_GET['id'];
	
	
$histo="SELECT depto.departamento, departamentos.responsable, casos.folio_c, brindadas.brindada, atenciones_nna.observacion, atenciones_nna.fecha from depto, casos, departamentos, atenciones_nna, brindadas where atenciones_nna.id_depto=depto.id and atenciones_nna.id_caso=casos.id and atenciones_nna.respo_reg=departamentos.id and brindadas.id=atenciones_nna.tipo_ate and atenciones_nna.id_nna='$idU'";
	$rUsuarios=$mysqli->query($histo);
$nom="SELECT nombre, apellido_p, apellido_m FROM nna WHERE id='$idU'";
$nomr=$mysqli->query($nom);

$cuenta="SELECT count(id) as cuenta FROM atenciones_nna where id_nna='$idU'";
$ejecuenta=$mysqli->query($cuenta);
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Historial</title>
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


		<h2 align="center">Historial</h2>
							<?php while($row=$nomr->fetch_assoc()){?>
							<h3 align="center"><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></h3><?php }?><div class="row uniform">
				<div class="12u$"><center>
				
				<?php while ($row=$ejecuenta->fetch_assoc()) {  ?>
				<strong>Visitas totales: <?php echo $row['cuenta']; }?></strong>
					
				</center>
				</div>
				</div>
								<div class="box">
								<div class="table-wrapper">
									<table class="alt">
									
										<thead>
											<tr>
												
												
												<th>DEPARTAMENTO</th>
												<th>RESPONSABLE</th>									
												<th>ATENCION</th>
												<th>OBSERVACION</th>
												<th>FECHA</th>
												<th>PERTENECE AL CASO</th>
											</tr>
										</thead>
										<tbody><?php while($row=$rUsuarios->fetch_assoc()){?>
											<tr>
												
												<td><?php echo $row['departamento'];?></td>
												<td><?php echo $row['responsable'];?></td>
												
												<td><?php echo $row['brindada'];?></td>
												<td><?php echo $row['observacion'];?></td>
												<td><?php echo $row['fecha'];?>
												<td><?php $caso=$row['folio_c'];}
														if ($caso==0) {
															echo "NINGUNO";
														}else{
															
																echo $row['folio_c'];
															} ?>
													</td>
											</tr>
								
										</tbody>
									</table>
								</div></div>
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