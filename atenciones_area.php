<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$deptos="SELECT id, departamento FROM depto where id!=16 AND id!=7";
	$edeptos=$mysqli->query($deptos);
	$edeptos2=$mysqli->query($deptos);
	
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
	
		
						<br><br><h2>Departamentos</h2>											
									<div class="row uniform">
						<?php 
							while ($row=$edeptos->fetch_assoc()) {
								$idD=$row['id'];
							}
								
								$cue2="SELECT count(id) as cuenta FROM historial WHERE id_departamento='$idD' and fecha_salida is null";
								$ecue2=$mysqli->query($cue2);
									while ($row=$ecue2->fetch_assoc()) {
						 			$cc2=$row['cuenta'];
						 			}
								$cue3="SELECT count(id) as cuenta FROM historial WHERE id_departamento='$idD' and fecha_salida is not null";
								$ecue3=$mysqli->query($cue3);
									while ($row=$ecue3->fetch_assoc()) {
						 			$cc3=$row['cuenta'];
						 			}
						?>
		<?php while ($row=$edeptos2->fetch_assoc()) { $idd=$row['id']; ?>
			<div class="4u 12u$(xsmall)">	
				<div class="box" >
					<center>
					
					<h4><?php echo $row['departamento']; ?></h4>
					
					<div class="row uniform">
						<ul>
						<?php
						$cue="SELECT count(id) as cuenta FROM historial WHERE id_departamento='$idd'";
						$ecue=$mysqli->query($cue);
							while ($row=$ecue->fetch_assoc()) {
						 		$cc=$row['cuenta'];
						 		}
						?>
							<li>Visitas de usuarios: <a href="atenciones_pendientes.php?id=<?php echo $idd;?>"><?php echo $cc; ?></a></li>
						<?php
						$cue="SELECT count(id) as cuenta FROM atenciones_nna WHERE id_depto='$idd'";
						$ecue=$mysqli->query($cue);
							while ($row=$ecue->fetch_assoc()) {
						 		$cc=$row['cuenta'];
						 		}
						?>
							<li>Atenciones a nna: <a href=""><?php echo $cc; ?></a></li>
						<?php
						$cue="SELECT count(departamentos.id_depto) as cuenta from departamentos, carpeta_inv where carpeta_inv.asignado=departamentos.id and departamentos.id_depto='$idd'";
						$ecue=$mysqli->query($cue);
							while ($row=$ecue->fetch_assoc()) {
						 		$cc=$row['cuenta'];
						 		}
						?>
							<li>Carpetas asignadas: <a href=""><?php echo $cc; ?></a></li>
						<?php
						$cue="SELECT count(departamentos.id_depto) as cuenta from departamentos, cuadro_guia where cuadro_guia.id_sp_registro=departamentos.id and departamentos.id_depto='$idd'";
						$ecue=$mysqli->query($cue);
							while ($row=$ecue->fetch_assoc()) {
						 		$cc=$row['cuenta'];
						 		}
						?>
							<li>Medidas: <a href=""><?php echo $cc; ?></a></li>
						<?php
						$cue="SELECT count(departamentos.id_depto) as cuenta from departamentos, audiencias where audiencias.respo_reg=departamentos.id and departamentos.id_depto='$idd'";
						$ecue=$mysqli->query($cue);
							while ($row=$ecue->fetch_assoc()) {
						 		$cc=$row['cuenta'];
						 		}
						?>
							<li>Audiencias: <a href=""><?php echo $cc; ?></a></li>

							
						</ul>
					</div>
					</center>	
				</div>
				
			</div>
		<?php } ?>
							</div> 
						<br>
					
					<div class="12u$(xsmall)">
					<input type="button" name="" value="cancelar" class="button special fit" onclick="location='welcome.php'"></div>
					
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