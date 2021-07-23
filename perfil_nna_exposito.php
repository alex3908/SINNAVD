<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idNNA = $_GET['id'];


$sqlnna="SELECT nna_exposito.folio, departamentos.responsable, nna_exposito.sexo, municipios.municipio, nna_exposito.fecha_reg, nna_exposito.situacion, nna_exposito.nna_n from nna_exposito, municipios, departamentos where nna_exposito.id='$idNNA' AND nna_exposito.municipio_deteccion=municipios.id and departamentos.id=nna_exposito.respo_reg";
	$esqlnna=$mysqli->query($sqlnna);
?>



<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
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
		</div></div> <br>	
								<?php while($row=$esqlnna->fetch_assoc()){ $nna_N=$row['nna_n'];?>
						<h2>NNA exposito</h2>
					<div class="row uniform">
						
							<div class="6u 12u$(xsmall)">
							<ul class="alt">
								<div class="box">
								<li><h4>Folio: </h4><?php echo $row['folio'];  ?> </li>
								<li><h4>Sexo: </h4><?php echo $row['sexo'];  ?> </li>
								<li><h4>Municipio de detección: </h4><?php echo $row['municipio'];  ?> </li>		
								</div>
							</ul>
							</div>
							<div class="6u 12u$(xsmall)">
							<ul class="alt">
								<div class="box">
								<li><h4>Fecha de registro: </h4><?php echo $row['fecha_reg'];  ?> </li>
								<li><h4>Situación: </h4><?php echo $row['situacion'];  ?> </li>
								<li><h4>Responsable del registro: </h4><?php echo $row['responsable'];  ?> </li>
								</div>
							</ul>
							</div>
						
					</div>
						<?php 
							} 
  						   $consulta="SELECT id_caso from nna_caso where id_nna='$idNNA'";
						   $econsulta=$mysqli->query($consulta);

						   while ($row=$econsulta->fetch_assoc()) {
						   	 $idCas=$row['id_caso'];
						   }
						   

						   if (empty($idCas)) {
						   	
						   }else{ $nomcaso="SELECT casos.id, casos.folio_c, casos.nombre, casos.fecha from casos, nna_caso where nna_caso.id_nna='$idNNA' and casos.id=nna_caso.id_caso";
						   $enomcaso=$mysqli->query($nomcaso);?>
						   <?php while ($row=$enomcaso->fetch_assoc()) { ?>
						   <div class="box">						  
								<ul class="alt">																
								<li><h4>Folio del caso: <?php echo $row['folio_c'];  ?></h4> </li>
								<li><h4>Nombre: <?php echo $row['nombre'];  ?></h4> </li>
								<li><h4>Fecha de registro: <?php echo $row['fecha'];  ?></h4> </li>
								<button onclick="location='perfil_caso.php?id=<?php echo $row['id'];?>'">ver caso</button>
													
								</ul>
						   </div>
						   <?php } ?>	
						<?php } ?>	
						<?php if ($nna_N==0) { ?>
						
						
							<input type="button" name="asignar_curso" class="button fit" value="completar registro" onclick="location='completar_nna.php<?php $_SESSION['idNNA']=$idNNA;?>'">
							<?php }else {} ?>	
							<input type="button" name="asignar_curso" class="button special fit" value="cancelar" onclick="location='lista_nna_expositos.php'">
						

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