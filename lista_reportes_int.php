
<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$buscaf="SELECT id, responsable, id_depto from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);
	
	while ($row=$ebf->fetch_assoc()) {
		$id_d=$row['id_depto'];
	}
	$tota="SELECT count(id) as t from reportes_int";
	$etota=$mysqli->query($tota);
	$rpendi="SELECT count(id) as rp from reportes_int where estado='0'";
	$erpendi=$mysqli->query($rpendi);
	$rter="SELECT count(id) as rt from reportes_int where estado='1'";
	$erter=$mysqli->query($rter);
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
		<section id="search" class="alt">
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />
						<br>
					<input type="button" class="button fit small" name="" value="Reportar" onclick="location='reporteinterno.php'">	
				</form>
			</section>
						
				<div class="row uniform">
				<div class="4u 12u$(xsmall)"><center>
				<strong>Reportes totales:</strong>
				<?php while ($row=$etota->fetch_assoc()) {  ?>
					<h4><?php echo $row['t']; ?></h4> 
					<?php }   ?>
				</center>
				</div>
				<div class="4u 12u$(xsmall)"><center>
				<strong>Reportes terminados:</strong>
				<?php while ($row=$erter->fetch_assoc()) {  ?>
					<h4><?php echo $row['rt']; ?></h4> 
				</center>
				</div><?php }   ?>
				<div class="4u 12u$(xsmall)"><center>
				<strong>Reportes pendientes:</strong>
				<?php while ($row=$erpendi->fetch_assoc()) {  ?>
					<h4> <?php  echo $row['rp']; ?></h4> 
				<?php }   ?></center>
				</div>
				</div>
<table  >
			
				<tr>
					<td><b>ID</b></td>
					<td><b>SOLICITANTE</b></td>
					<td><b>DEPARTAMENTO</b></td>
					<td><b>FECHA DE REGISTRO</b></td>
					<td><b>TIPO</b></td>
					<td></td>
				</tr>
				<tbody>
				<?php
	

	@$buscar = $_POST["palabra"];
	if ($id_d==16) {
		if (empty($buscar)) {
			$query="SELECT reportes_int.id, departamentos.responsable, depto.departamento, reportes_int.fecha_ini, reportes_int.tipo, reportes_int.estado from reportes_int, departamentos, depto where reportes_int.id_solicitante=departamentos.id and reportes_int.id_depto=depto.id and reportes_int.estado='0' and (departamentos.responsable like '%$buscar%' or depto.departamento like '%$buscar%' or reportes_int.tipo like '%$buscar%') limit 25";
		}else {
	$query="SELECT reportes_int.id, departamentos.responsable, depto.departamento, reportes_int.fecha_ini, reportes_int.tipo, reportes_int.estado from reportes_int, departamentos, depto where reportes_int.id_solicitante=departamentos.id and reportes_int.id_depto=depto.id and (departamentos.responsable like '%$buscar%' or depto.departamento like '%$buscar%' or reportes_int.tipo like '%$buscar%')";
}
	}else{
		$query="SELECT reportes_int.id, departamentos.responsable, depto.departamento, reportes_int.fecha_ini, reportes_int.tipo, reportes_int.estado from reportes_int, departamentos, depto where reportes_int.id_solicitante=departamentos.id and reportes_int.id_depto=depto.id and reportes_int.id_depto=$id_d and (departamentos.responsable like '%$buscar%' or depto.departamento like '%$buscar%' or reportes_int.tipo like '%$buscar%') limit 25";
	}
	
	
	$resultado=$mysqli->query($query);
	
 while($row=$resultado->fetch_assoc()){ ?>
					
						<tr>
							<td><?php echo $row['id'];?>
							</td>
							<td>
								<?php echo $row['responsable'];?>
							</td>
							<td>
								<?php echo $row['departamento'];?>
							</td>

							<td>
								<?php echo $row['fecha_ini'];?>
							</td>

							<td>
								<?php echo $row['tipo']; ?>
							</td>
							<td>
								<?php   
									$esta=$row['estado'];
									if ($id_d==16) {
									if ($esta==0) { ?>
										<input type="image" src="images/no_ejecutada.png" height="40" width="40" onclick="location='reporte_atendido.php?id=<?php echo $row['id'];?>'">
									<?php } else if ($esta==1) { ?>
										<input type="image" src="images/ejecutada.png" height="40" width="40" onclick="location='reporte_natendido.php?id=<?php echo $row['id'];?>'">
									<?php } }else {
										if ($esta==0) { ?>
										<input type="image" src="images/no_ejecutada.png" height="40" width="40" >
									<?php } else if ($esta==1) { ?>
										<input type="image" src="images/ejecutada.png" height="40" width="40" >
									<?php }
									}
								?>
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