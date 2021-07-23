<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	
	$idxdep=$_GET['id'];
	$deptos="SELECT departamento FROM depto where id='$idxdep'";
	$edeptos=$mysqli->query($deptos);
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
						
		
						<br><br><h2> <?php 
						
							while ($row=$edeptos->fetch_assoc()) { echo $row['departamento']; }?></h2>		
				<h3>
				<?php   
					$consultana="SELECT id from historial where responsable=0 and id_departamento='$idxdep'";
					$ena=$mysqli->query($consultana); 

					$rows2 = $ena->num_rows;
			
				?>			
				<a href="visitaPRespoSD.php?id=<?php echo $idxdep;?>"><strong>Visitas no asignadas: <?php echo $rows2;?></strong></a>				
				</h3>
						<table  >
			
				<tr>
				
					<td><b>Nombre</b></td>
					<td><b>Totales</b></td>
					<td><b>Pendientes</b></td>
					<td><b>Terminadas</b></td>
					
				</tr>
				<tbody>
						<?php 
							$repon="SELECT id, responsable from departamentos where id_depto='$idxdep' and id_personal!='3'";
							$eje=$mysqli->query($repon);
							$rows = $eje->num_rows;
							$i=0;
							while ($row=$eje->fetch_assoc()) {
															
								$respons[$i] = $row['id'];
								$nomres[$i] = $row['responsable'];
								$i++;

							}
							

							$longitud=count($respons);
						
							for ($a=0; $a <$longitud ; $a++) { 
								$re=$respons[$a];
								$consulta="SELECT count(id) as cuenta1 from historial where responsable='$re'";
								$e=$mysqli->query($consulta);
								
								$consulta2="SELECT count(id) as cuenta2 from historial where responsable='$re' and fecha_salida is null";
								$e2=$mysqli->query($consulta2);

								$consulta3="SELECT count(id) as cuenta3 from historial where responsable='$re' and fecha_salida is not null";
								$e3=$mysqli->query($consulta3);
								 ?>

<?php 
									$r=$respons[$a]; ?>	
								<tr>
									<td><a href="visitaPRespo.php?id=<?php echo $r;?>"><strong><?php echo $nomres[$a];?></strong></a>
																		 
									</td>
									<td>
									<?php while ($row=$e->fetch_assoc()) {
									echo $row['cuenta1'];} ?>
									</td>
									<td>
									<?php while ($row=$e2->fetch_assoc()) {
									echo $row['cuenta2'];} ?>
									</td>
									<td>
									<?php while ($row=$e3->fetch_assoc()) {
									echo $row['cuenta3'];} ?>
									</td>
									
								</tr>
								<?php
							}
						?>								
					
							
							
					
				</tbody>
			</table>	
					
					<div class="12u$(xsmall)">
					<input type="button" name="" value="cancelar" class="button special fit" onclick="location='atenciones_area.php'"></div>
					
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