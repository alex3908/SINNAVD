<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$v="SELECT id from departamentos where id='$idDEPTO' and id_personal='1'";
	$ev=$mysqli->query($v);
	$rev=$ev->num_rows;

	$valida="SELECT id from departamentos where (id_depto='10' and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO')";
	$evalida=$mysqli->query($valida);
	$rows=$evalida->num_rows;

	$tot="SELECT id from nna where curp='0'";
	$etot=$mysqli->query($tot);
	$erow=$etot->num_rows;
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
						<div class="inner"><br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> 	 
		<h2>NNA sin curp</h2>
				<?php 
				echo "Total: ".$erow;
				$carsa="SELECT departamentos.id, departamentos.responsable, count(nna.curp) from nna, departamentos where nna.curp='0' and departamentos.id=nna.respo_reg group by departamentos.id having count(nna.curp)";
				$ecarsa=$mysqli->query($carsa);

				?>					
				<table class="alt">
						<tr>
							<td><b>Responsable</b></td>
							<td><b>Faltas</b></td>
						</tr>
					<tbody>
					<?php while($row=$ecarsa->fetch_assoc()){ ?>
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<tr>
							<td><input type="hidden" name="paraid" value="<?php echo $row['id']; ?>">
								
								<input type="submit" name="res" value="<?php echo $row['responsable']; ?>"></td>
							<td><?php echo $row['count(nna.curp)']; ?></td>
						</tr>
					</form>
					<?php } ?>
					</tbody>
				</table>
			

		<?php if (isset($_POST['res'])) {
				$idd = mysqli_real_escape_string($mysqli,$_POST['paraid']);
				$query="SELECT nna.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.curp, nna.fecha_nac, departamentos.responsable from nna, departamentos where departamentos.id=nna.respo_reg and curp='0' and nna.respo_reg='$idd'";
				$resultado=$mysqli->query($query); 
				$resultado2=$mysqli->query($query); 
				while ($row=$resultado2->fetch_assoc()) {
					$respon=$row['responsable'];
				}
				?>
		
								
		<table>
			<caption><?php echo $respon; ?></caption>
			<tr>
				<td><b>Folio</b></td>
				<td><b>Nombre</b></td>
				<td><b>Curp</b></td>
				<td><b>Fecha de nacimiento</b></td>					
			</tr>
			<tbody><?php while($row=$resultado->fetch_assoc()){ ?>
		
				<tr>
					<td><a href="perfil_nna.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio'];?></a></td>
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m'];?></td>
					<td><?php echo $row['curp']; ?></td>
					<td><?php echo $row['fecha_nac'];?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>	
		<?php }  ?>
</form>



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