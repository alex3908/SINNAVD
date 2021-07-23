<?php
	ob_start();
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
		<title>Oficios 2019</title>
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
		<table class="alt">
			<thead>
				<tr>
					<td><h1>Oficios 2019</h1></td>
					
				</tr>
				<tr>
					<td colspan="2"><form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" /></form></td>
				</tr>
			</thead>
		</table>

		<table class="alt">
						
							<thead>
								<tr>
									<th>Folio</th>
									<th>Responsable</th>
									<th>Destinatario</th>
									<th>Asunto</th>
									<th>Fecha</th>
									
								</tr>
							</thead>
							<tbody>
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$querytabla="SELECT oficios_2019.id, departamentos.responsable, oficios_2019.destinatario, oficios_2019.asunto, oficios_2019.fecha from oficios_2019, departamentos where  oficios_2019.respo=departamentos.id and oficios_2019.act='1' order by oficios_2019.id asc limit 20";
	
					}else {
						$querytabla="SELECT oficios_2019.id, departamentos.responsable, oficios_2019.destinatario, oficios_2019.asunto, oficios_2019.fecha from oficios_2019, departamentos where  oficios_2019.respo=departamentos.id and oficios_2019.act='1' and (oficios_2019.id like '%$buscar%' or departamentos.responsable like '%$buscar%' or oficios_2019.destinatario like '%$buscar%' or oficios_2019.asunto like '%$buscar%' or oficios_2019.fecha like '%$buscar%') order by oficios_2019.id asc";
					}
					$ellenar=$mysqli->query($querytabla);
					 while ($row=$ellenar->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $row['id']; ?></td>	
									<td><?php echo $row['responsable']; ?></td>	
									<td><?php echo $row['destinatario']; ?></td>	
									<td><?php echo $row['asunto']; ?></td>	
									<td><?php echo $row['fecha']; ?></td>									
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