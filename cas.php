<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$buscaf="SELECT c.id, c.nombre, c.titular, c.tipo, c.calle, c.cp, e.estado, m.municipio, l.localidad, c.completo from centros c, estados e, municipios m, localidades l WHERE c.id_estado=e.id and c.id_mun=m.id and c.id_loc=l.id";
	$ecentros=$mysqli->query($buscaf);
	
	$vald="SELECT id from departamentos where id='$idDEPTO' and casp='1' and (id_depto='13' OR id_depto='16' OR id_depto='10')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
	
	$ing="SELECT nna_centros.id from nna_centros, centros where nna_centros.id_centro=centros.id";
	$eing=$mysqli->query($ing);
	$rowing=$eing->num_rows;

	$egr="SELECT egreso_cas.id from egreso_cas, centros where egreso_cas.id_centro=centros.id";
	$eegr=$mysqli->query($egr);
	$rowegr=$eegr->num_rows;
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
					<?php if ($rows2>0) { ?>
						<input type="button" class="button special fit small" value="registrar cas" onclick="location='reg_cas.php'">
					<?php }else {} ?>
							
				<br>
				<table>						
						<tbody>
							<tr>
								<td><strong>INGRESOS</strong></td>
								<td><?php echo $rowing; ?></td>
								<td><a href="lista_ingresos.php">VER</a></td>								
							</tr>	
							<tr>
								<td><strong>EGRESOS</strong></td>
								<td><?php echo $rowegr; ?></td>
								<td><a href="lista_egresos.php">VER</a></td>								
							</tr>														
						</tbody>
					</table>
				<br>
				<div class="table-wrapper">
					<table class="alt">
						<thead>
							<tr>
								<th>N.</th>
								<th>NOMBRE</th>
								<th>TITULAR</th>								
								<th>TIPO</th>								
								<th>DIRECCIÓN</th>
								<th>NNA ALBERGADOS</th>
								<th>ESTATUS</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row=$ecentros->fetch_assoc()) { 
									$idCas=$row['id']; 
									$nnaC="SELECT id from nna_centros where id_centro='$idCas' and activo='1'";
									$enna=$mysqli->query($nnaC);
									$rows=$enna->num_rows;?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><a href="listaxcentro.php?id=<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></a></td>
								<td><?php echo $row['titular']; ?></td>
								<td><?php echo $row['tipo']; ?></td>
								<td><?php echo $row['calle'].', '.$row['cp'].', '.$row['localidad'].', '.$row['municipio'].', '.$row['estado'].'.';?></td>
								<td><?php echo $rows; ?></td>
								<?php $com=$row['completo']; ?>
								<td><a class="button special small"><?php if ($com=='1') { ?>completo <?php }else{ ?>incompleto <?php } ?></a></td>
							</tr>	
							<?php } ?>															
						</tbody>
					</table>
				</div>
				<div class="row uniform">
				
				<?php while ($row=$ecentros->fetch_assoc()) { 
					$idCas=$row['id']; 
				$nnaC="SELECT id from nna_centros where id_centro='$idCas'";
	$enna=$mysqli->query($nnaC);
	$rows=$enna->num_rows;?>
					
				<div class="6u 12u$(xsmall)">	
				<div class="box" onclick="location='listaxcentro.php?id=<?php echo $row['id']; ?>'">
					<div align="right"><a class="button special small">incompleto</a></div>
					<h2><?php echo $row['nombre']; ?></h2>					
					<u>Titular: </u> <?php echo $row['titular']; ?><br>
					<u>RFC: </u> <?php echo $row['rfc']; ?><br>
					<u>Telefono fijo: </u> <?php echo $row['telefono']; ?><br>
					<u>Telefono celular: </u> <?php echo $row['celular']; ?><br>
					<u>Estado: </u> <?php echo $row['estado']; ?><br>
					<u>Municipio: </u> <?php echo $row['municipio']; ?><br>
					NNA albergados en el centro: <?php echo $rows; ?>
				</div>
				</div>
				<?php } ?>
				</div>
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