
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

	$tot="SELECT id from carpeta_inv where asignado='0'";
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
		<h2>Carpetas</h2>
				<?php 
				echo "Total: ".$erow;
				$carsa="SELECT departamentos.responsable, count(carpeta_inv.asignado) from carpeta_inv, departamentos where carpeta_inv.asignado='0' and departamentos.id=carpeta_inv.respo_reg group by departamentos.responsable having count(carpeta_inv.asignado)";
				$ecarsa=$mysqli->query($carsa);

					if ($rev=='1') { ?>
					<table class="alt" >
						<tr>
							<td><b>Responsable</b></td>
							<td><b>Faltas</b></td>
						</tr>
					<tbody>
					<?php while($row=$ecarsa->fetch_assoc()){ ?>
						<tr>
							<td><?php echo $row['responsable']; ?></td>
							<td><?php echo $row['count(carpeta_inv.asignado)']; ?></td>
						</tr>
					<?php } ?>
					</tbody>
					</table>
						
					<?php } else { } ?>
				
						
<table  >
			
				<tr>
					<td><b>NUC</b></td>
					<td><b>Fecha de inicio</b></td>
					<td><b>Folio del casos</b></td>
					<td><b>Representante coadyuvante</b></td>
					<td><b>Estatus</b></td>
					<td></td>
				</tr>
				<tbody>
				<?php
	

	
	
	$query="SELECT carpeta_inv.id, carpeta_inv.nuc, carpeta_inv.fecha_ini, casos.folio_c, carpeta_inv.estado, carpeta_inv.tipo_pross, carpeta_inv.asignado from casos, carpeta_inv where casos.id=carpeta_inv.id_caso and respo_reg='$idDEPTO' and asignado='0' ";
	
	$resultado=$mysqli->query($query);
	
 while($row=$resultado->fetch_assoc()){ ?>
					
						<tr>
							<td><?php echo $row['nuc'];?>
							</td>
							<td>
								<?php echo $row['fecha_ini'];?>
							</td>
							<td>
								<?php echo $row['folio_c'];?>
							</td>

							<td>
								<input type="button" class="special button fit small" name="Asignar" value="Asignar" onclick="location='asignar_carpeta.php?id=<?php echo $row['id'];?>'">
										
							</td>

							<td>
								<?php $est=$row['estado'];$tip=$row['tipo_pross'];
								if ($tip==0) {								
											if ($est==20) { ?>
								 	<img src="images/G20.png" width="80">
								 <?php }else if ($est==40) { ?>
								 	<img src="images/G40.png" width="80">
								 <?php }else if ($est==60) { ?>
								 	<img src="images/G60.png" width="80">
								 <?php }else if ($est==80) { ?>
								 	<img src="images/G80.png" width="80">
								 <?php }else if($est==100){ ?>
								 	<img src="images/G100.png" width="80">
								 <?php 	}
								 }else if ($tip>=1 or $tip<=4) { ?>
								 	INVESTIGACION TERMINADA
								 <?php }else if ($tip==5 or $tip==6) { ?>
								 	SOLUCION ALTERNA
								 <?php }else if ($tip==7) { ?>
								 	TERMINACION ANTICIPADA
								 <?php } ?>
							</td>
							<td>
								<input type="button" name="Ver" value="Ver" onclick="location='perfil_carpeta.php?id=<?php echo $row['id'];?>'">
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