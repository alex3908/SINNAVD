
<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idUsuario=$_GET['id'];
	$val=$_GET['val'];

	$valida="SELECT responsable from departamentos where id='$idUsuario'";
	$evalida=$mysqli->query($valida);
	while ($row=$evalida->fetch_assoc()) {
		$resp=$row['responsable'];
	}

	$med="SELECT id from cuadro_guia where id_sp_registro='$idUsuario'";
		$emed=$mysqli->query($med);
		$rowsmed=$emed->num_rows;

	$mede="SELECT id from cuadro_guia where id_sp_registro='$idUsuario' and estado=1";
		$emede=$mysqli->query($mede);
		$rowsmede=$emede->num_rows; 
	
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
						<div class="inner">
						

		<section id="search" class="alt">

			<div class="box alt" align="center">
							<div class="row 10% uniform">
								<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
								<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
								<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
							</div>							
						</div> 
			<div class="box alt">
							<div class="row uniform">
								<div class="4u"><input type="button" onclick="location='perfil_personal.php?id=<?php echo $idUsuario; ?>'" value="Regresar" class="button special small"></div>
								<div class="6u"><h2>Medidas decretadas por <?php echo $resp; ?> </h2></div>								
							</div>							
						</div> 
			
			
				
					
						<div class="box alt" align="center">
					<div class="row 10% uniform">
				<div class="4u"><input type="button" onclick="location='medidas_personal.php?id=<?php echo $idUsuario; ?>&val=2'" value="Todas las medidas"></div>
				<div class="4u"><input type="button" onclick="location='medidas_personal.php?id=<?php echo $idUsuario; ?>&val=0'" value="Medidas no ejecutadas"></div>
				<div class="4u"><input type="button" onclick="location='medidas_personal.php?id=<?php echo $idUsuario; ?>&val=1'" value="Medidas ejecutadas"></div>
			</div>		</div>
				
			</section>
			
					
						
<table  >
			
				<tr>
					<td><b>Derecho vulnerado</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>Institucion responsable</b></td>					
					<td><b>Periodicidad</b></td>
					<td><b>Fecha</b></td>
					<td><b>Ejecucion</b></td>
					<td><b>Caso</b></td>
				</tr>
				<tbody>
				<?php

	
	if ($val=='2') {

		$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable, casos.folio_c from cuadro_guia, derechos_nna, departamentos, medidas, casos where cuadro_guia.id_sp_registro='$idUsuario' AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id and casos.id=cuadro_guia.id_caso";
	}elseif ($val=='1') {
		$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable, casos.folio_c from cuadro_guia, derechos_nna, departamentos, medidas, casos where cuadro_guia.id_sp_registro='$idUsuario' AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id and casos.id=cuadro_guia.id_caso and cuadro_guia.estado='1'";
	}elseif ($val=='0') {
		$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable, casos.folio_c from cuadro_guia, derechos_nna, departamentos, medidas, casos where cuadro_guia.id_sp_registro='$idUsuario' AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id and casos.id=cuadro_guia.id_caso and cuadro_guia.estado='0'";
	}
	

	
	$ecuadro=$mysqli->query($cuadro);
	$rowss=$ecuadro->num_rows;
	echo "Resultados: ".$rowss;
	
 while($row=$ecuadro->fetch_assoc()){ ?>
					
						<tr>
							<td><?php echo $row['derecho'];?>
							</td>
							<td>
								<?php echo $row['med_prot'];?>
							</td>
							<td>
								<?php echo $row['responsable_med'];?>
							</td>
							<td>
								<?php echo $row['periodicidad'];?>
							</td>
							<td>
								<?php echo $row['fecha'];?>
							</td>
							<td>
								<?php  //siendo tu medida 
								$es=$row['estado'];
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" >
														</div>
													<?php }else if($es==1 ){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" >
														</div>
													<?php } //cierre siendo tu medida ?>
							</td>
							<td>
								<?php echo $row['folio_c'];?>
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