<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$total="SELECT id from benef_unidad";
	$etotal=$mysqli->query($total);
	$rows=$etotal->num_rows;

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Beneficiarios de la UIENNAVD</title>
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
						<div class="inner"><br>
		 <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
			</div>
		</div> 
		<table class="alt">
			<thead>
				<tr>
					<td><h1>UIENNAVD</h1></td>
					
					<?php if ($_SESSION['spcargo']==3 or $_SESSION['spcargo']==1 or $_SESSION['spcargo']==2) { ?>
					<td><input type="button" value="ALTA" class="button special" onclick="location='reg_beneficiarios.php'"></td>
				<?php } ?>
				</tr>
				
				<tr>
					<td colspan="2"><form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
						<h3>Beneficiarios registrados: <?php echo $rows; ?></h3></form></td>
				</tr>
			</thead>
		</table>
						
				
				<table>			
				<tr>
					<td><b>Folio</b></td>
					<td><b>Nombre</b></td>
					<td><b>Edad </b></td>
					<td><b>Teléfono</b></td>
					
				</tr>
				<tbody>
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$query="SELECT id, folio, nombre, apellido_p, apellido_m, concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, telefono, curp from benef_unidad having (nombre like '%$buscar%' OR apellido_p like '%$buscar%' OR apellido_m like '%$buscar%' OR folio like '%$buscar%' OR nom like '%$buscar%' OR fecha_nac like '%$buscar%' or curp like '%$buscar%') order by id desc limit 20";
					}else {
						$query="SELECT id, folio, nombre, apellido_p, apellido_m, concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, telefono, curp from benef_unidad having (nombre like '%$buscar%' OR apellido_p like '%$buscar%' OR apellido_m like '%$buscar%' OR folio like '%$buscar%' OR nom like '%$buscar%' OR fecha_nac like '%$buscar%' or curp like '%$buscar%')";
					}
					
						$resultado=$mysqli->query($query);
						$rows2=$resultado->num_rows;
						echo "Resultados: ".$rows2;
 					while($row=$resultado->fetch_assoc()){ ?>
					
				<tr>
					<td><a href="perfil_beneficiarios.php?id=<?php echo $row['id'];?>"><?php echo $row['folio'];?></a></td>
					<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
					<td style="text-transform:uppercase;"><?php $fec=$row['fecha_nac'];
														@list($dia, $mes, $ano)=explode('/', $fec);
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
															
														echo $edad.' años '.$meses.' meses';?></td>
					<td><?php echo $row['telefono'];?></td>
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
										<li><a href="logout.php">Cerrar sesión</a></li>
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