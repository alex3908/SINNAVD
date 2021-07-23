<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idCentro=$_GET['id'];

	$cen="SELECT nombre from centros where id='$idCentro'";
	$ecen=$mysqli->query($cen);
	while ($row=$ecen->fetch_assoc()) { $centro=$row['nombre'];	}

	$ing="SELECT supervisiones.folio ,supervisiones.id, supervisiones.fecha_sup, motivoscas.motivo, centros.nombre from supervisiones, motivoscas, centros where supervisiones.id_motivo=motivoscas.id and supervisiones.id_centro=centros.id and supervisiones.id_centro='$idCentro'";
	$eing=$mysqli->query($ing);
	$rowing=$eing->num_rows;

	$vald="SELECT id from departamentos where id='$idDEPTO' and (id_depto='13' or id_depto='16')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
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
						<div class="inner"><br>  <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> 
				<input type="button" class="small" value="atras" onclick="location='listaxcentro.php?id=<?php echo $idCentro; ?>'">

				<div class="row uniform">
					<div class="9u">
						<h2>Lista de supervisiones: <?php echo $centro; ?></h2>
					</div>
					<?php if ($rows2>0) { ?>
					<div class="3u">
						<input type="button" value="registrar supervision" class="small" onclick="location='reg_supervisionCAS.php?id=<?php echo $idCentro; ?>'">
					</div>
					<?php }else {} ?>
					
				</div>
				<br>
				
				<?php if ($rowing=='0') {
					echo "SIN REGISTROS";
				} else { ?>
				<div class="table-wrapper">
					<table class="alt">
						<caption>Total: <?php echo $rowing; ?></caption>
						<thead>
							<tr>
								<th>FOLIO</th>
								<th>MOTIVO</th>
								<th>FECHA</th>								
								<th>ESTATUS</th>									
							</tr>
						</thead>
						<tbody>
						<?php while ($row=$eing->fetch_assoc()) { ?>									
							<tr>
						<td><a href="perfil_supervision.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio']; ?></a></td>
								<td><?php echo $row['motivo']; ?></td>
								<td><?php echo $row['fecha_sup']; ?></td>
								<td><?php echo $row['motivo']; ?></td>								
							</tr>	
						<?php } ?>														
						</tbody>
					</table>
				</div>
				
			<?php } if(@$bandera) { 
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
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" >Cerrar sesión</a></li>
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