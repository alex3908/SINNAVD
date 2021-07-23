<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idU = $_GET['id'];
	
	
$histo="SELECT visitas_unidad.id, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, visitas_unidad.responsable as res, visitas_unidad.fecha, cat_asuntos_unidad.asunto,  visitas_unidad.tipo,  visitas_unidad.nuc, departamentos.responsable FROM visitas_unidad, benef_unidad, departamentos, cat_asuntos_unidad WHERE visitas_unidad.id_benef='$idU' and visitas_unidad.id_benef=benef_unidad.id and visitas_unidad.asunto=cat_asuntos_unidad.id and departamentos.id=visitas_unidad.respo_reg";
	$rUsuarios=$mysqli->query($histo);
$nom="SELECT nombre, apellido_p, apellido_m FROM benef_unidad WHERE id='$idU'";
$nomr=$mysqli->query($nom);

$cuenta="SELECT count(id) as cuenta FROM visitas_unidad where id_benef='$idU'";
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
												<th>RESPONSABLE</th>
												<th>ASUNTO</th>
												<th>NUC</th>
												<th>FECHA</th>
												<th>TIPO</th>
												<th>REGISTRO HECHO POR</th>
											</tr>
										</thead>
										<tbody><?php while($row=$rUsuarios->fetch_assoc()){?>
											<tr>
												<td><?php echo $row['res'];?></td>
												<td><?php echo $row['asunto'];?></td>
												<td><?php echo $row['nuc'];?></td>
												<td><?php echo $row['fecha'];?></td>
												<td><?php echo $row['tipo'];?></td>
												<td><?php echo $row['responsable'];?></td>
											</tr>
							<?PHP } ?>
										</tbody>
									</table>
								</div></div>
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