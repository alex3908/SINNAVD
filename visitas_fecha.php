<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	@$buscar = $_POST['palabra'];
	if (empty($buscar)) {
		$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.atencion_brindada, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable, usuarios.municipio, usuarios.localidad, usuarios.fecha_nac FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && departamentos.id=historial.responsable AND (usuarios.nombre like '%$buscar%' OR usuarios.apellido_p like '%$buscar%' OR usuarios.apellido_m like '%$buscar%' OR departamentos.responsable like '%$buscar%' OR historial.fecha_ingreso like '%$buscar%') ORDER BY historial.fecha_ingreso DESC LIMIT 20 ";
	$rUsuarios=$mysqli->query($listaUsuarios);
	} else  {
		$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.atencion_brindada, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable, usuarios.municipio, usuarios.localidad, usuarios.fecha_nac 	FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && departamentos.id=historial.responsable AND (usuarios.nombre like '%$buscar%' OR usuarios.apellido_p like '%$buscar%' OR usuarios.apellido_m like '%$buscar%' OR departamentos.responsable like '%$buscar%' OR historial.fecha_ingreso like '%$buscar%') ORDER BY historial.fecha_ingreso DESC ";
	$rUsuarios=$mysqli->query($listaUsuarios);
	}
	
	$rows=$rUsuarios->num_rows;
	
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
							
							
									<div class="box">
								<div class="table-wrapper">
								<section id="search" class="alt">
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />
						
				</form>
			</section>
									<table class="alt">
									<h5>Resultados: <?php echo $rows; ?></h5>
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>DIRECCIÓN</th>
												<th>EDAD</th>
												<th>DEPARTAMENTO</th>
												<th>RESPONSABLE</th>
												<th>ASUNTO</th>
												<th>ATENCIÓN BRINDADA</th>
												<th>FECHA DE INGRESO</th>
												<th>FECHA DE SALIDA</th>
												<th></th>
											</tr>
										</thead>
										<tbody><?php while($row=$rUsuarios->fetch_assoc()){?>
											<tr>
												<td><?php echo $row['id_usuario'];?></td>
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
												<td><?php echo $row['municipio']." ".$row['localidad']; ?></td>
												<td><?php $fec=$row['fecha_nac'];
														@list($dia, $mes, $ano)=split('[/.-]', $fec);
														@$diah=date(j);
														@$mesh=date(n);
														@$anoh=date(Y);
														if (($mes == $mesh) && ($dia > $diah)) {
															$anoh=($anoh-1);
														}
														if ($mes > $mesh) {
															$anoh=($anoh-1);
														}
														$edad=($anoh-$ano);
														$meses=($mesh-$mes);
															
														echo $edad.' años '.$meses.' meses';
														?></td>
												<td><?php echo $row['departamento'];?></td>
												<td><?php echo $row['responsable'];?></td>
												<td><?php echo $row['asunto'];?></td>
												<td><?php echo $row['atencion_brindada'];?></td>
												<td><?php echo $row['fecha_ingreso'];?></td>
												<?php if ( empty($row['fecha_salida'])) { ?>
												<td><input type="button" value="Pendiente" =""></td>
												<?php } else{ ?>
												<td><?php echo $row['fecha_salida'];?></td>
												<?php }?>
															 
														
											</tr>
							<?PHP } ?>
										</tbody>
									</table>
								</div></div>
							<br>
								
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
									<ul>
										<li><a href="welcome.php">inicio</a></li>
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
									<ul>
										
										<li><a href="welcome.php" ">Inicio</a></li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
									<li><a href="welcome.php">inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
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