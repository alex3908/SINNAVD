<?php	
	session_start();
	require 'conexion.php';	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	
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
					<div class="inner">			
						<br> <br> 	
			<div class="uniform row">
				
			<div class="12u 12u$(xsmall)">
				<div class="box">
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<h2>Registro de actividad</h2>
					<table class="alt">
						
							<tbody>
								<tr>
									<td colspan="5">Nombre del actividad<input type="text" name="nombre" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></td>
									<td>Fecha<input type="text" name="fecha" placeholder="DD/MM/AAAA"></td>
								</tr>
							</tbody>				
							<tbody>
								<tr>
									<td colspan="6">Numero de asistentes</td>
								</tr>
								<tr>
									<td colspan="2">de 0 a 11 años</td>
									<td colspan="2">de 12 a 17 años</td>
									<td colspan="2">de 18 en adelante</td>
									
								</tr>
								<tr>
									<td>Niñas<input type="text" name="ni"></td>
									<td>Niños<input type="text" name="na"></td>
									<td>Adolescentes mujeres<input type="text" name="adm"></td>
									<td>Adolescentes hombres<input type="text" name="adh"></td>
									<td>Mujeres<input type="text" name="am"></td>
									<td>Hombres<input type="text" name="ah"></td>
								</tr>
								<tr>
									<td colspan="6">
									<input class="button special fit" name="registar" type="submit" value="Guardar" ></td>
								</tr>
								
							</tbody>
						</table>
					
				</form>
			</div>
			</div>
			
			<div class="12u 12u$(xsmall)">				
					<div class="table-wrapper">
						<?php $mos="SELECT actividad, fecha, na, ni, adm, adh, am, ah, na+ni+adm+adh+am+ah as total from ccpi";
								$emos=$mysqli->query($mos);
								$totalact=$emos->num_rows; ?>
						<table class="alt">
						<thead>
							<tr>
								<td colspan="6"><h3>Historial de actividades: <?php echo $totalact; ?></h3></td>							
							</tr>
						</thead>
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Actividad</th>
									<th>Niñas</th>
									<th>Niños</th>
									<th>Adolescentes mujeres</th>
									<th>Adolescentes hombres</th>
									<th>Mujeres</th>
									<th>Hombres</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row=$emos->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $row['fecha']; ?></td>
									<td><?php echo $row['actividad']; ?></td>
									<td><?php echo $row['na']; ?></td>
									<td><?php echo $row['ni']; ?></td>
									<td><?php echo $row['adm']; ?></td>
									<td><?php echo $row['adh']; ?></td>
									<td><?php echo $row['am']; ?></td>
									<td><?php echo $row['ah']; ?></td>
									<td><?php echo $row['total'] ?></td>
								</tr>
								<?php } ?>
							</tbody>
						
						</table>
					</div>
			</div>
		</div>
						</div>

					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>	
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