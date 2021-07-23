<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}	
	$idDEPTO = $_SESSION['id'];
	$estRep= $_GET['estRep'];
	$total="SELECT id from reportes_vd";
	$etotal=$mysqli->query($total);
	$rows3=$etotal->num_rows;
	$valida="SELECT id from departamentos where (id_depto='9' and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	$evalida=$mysqli->query($valida);
	$rows2=$evalida->num_rows;
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
						<div class="inner"><br><br>
			<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px"   /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
		</div>
		<table class="alt">
			<thead>
				<tr>
					<td><h2>Reportes de posible vulneración de derechos a NNA</h2></td>
					<td><input type="button" value="alta" onclick="location='reg_reporte.php'" class="button special"></td>
				</tr>
				<tr>
					<td colspan="2"><form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />
						<h4 onclick="location='lista_reporte.php?estRep=0'">Total de reportes: <?php echo $rows3; ?></h4></form></td>
				</tr>
			</thead>
		</table>
								
			
			<table class="alt">									
							<thead>
								<tr>	
									<th onclick="location='lista_reporte.php?estRep=5'">Reportes no asignados: 
						<?php $ra="SELECT count(id) as total from reportes_vd where atendido=1 and asignado=0";
					 		  $era=$mysqli->query($ra);
					  			while ($row=$era->fetch_assoc()) {
					   			echo $row['total'];
					   			} ?></th>								
									<th onclick="location='lista_reporte.php?estRep=1'">Reportes no atendidos: 
						<?php $ra="SELECT count(id) as total from reportes_vd where atendido=1 and asignado!=0";
					 		  $era=$mysqli->query($ra);
					  			while ($row=$era->fetch_assoc()) {
					   			echo $row['total'];
					   			} ?></th>
									<th onclick="location='lista_reporte.php?estRep=2'">Reportes en proceso: 
						<?php $ra="SELECT count(id) as total from reportes_vd where atendido=2";
					 		  $era=$mysqli->query($ra);
					  			while ($row=$era->fetch_assoc()) {
					   			echo $row['total'];
					   			} ?></th>
									<th onclick="location='lista_reporte.php?estRep=3'">Reportes atendidos negativos:
						<?php $ran="SELECT count(id) as total from reportes_vd where atendido=3";
					 		  $eran=$mysqli->query($ran);
					  			while ($row=$eran->fetch_assoc()) {
					   			echo $row['total'];
					   			} ?></th>
									<th onclick="location='lista_reporte.php?estRep=4'">Reportes atendidos positivos:
						<?php $rap="SELECT count(id) as total from reportes_vd where atendido=4";
					 		  $erap=$mysqli->query($rap);
					  			while ($row=$erap->fetch_assoc()) {
					   			echo $row['total'];
					   			} ?></th>																	
								</tr>
							</thead>
						</table>
			</div>
			<table >			
				<tr>
					<td><b>FOLIO</b></td>
					<td><b>FECHA</b></td>					
					<td><b>NNA</b></td>
					<td><b>UBICACIÓN</b></td>
					<td><b>PERSONA QUE REPORTO</b></td>
					<td><b>T.S.</b></td>
					<td><b>PSIC</b></td>
					<td><b>ESTATUS</b></td>
					
				</tr>
				<tbody>
				<?php
	@$buscar = $_POST["palabra"];
	
	if ($estRep==0) {
	if (empty($buscar)) {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, reportes_vd.recepcion, 
		reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable as d2res, reportes_vd.atendido, reportes_vd.ubicacion 
		from reportes_vd INNER JOIN  departamentos d1 ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 
		ON reportes_vd.asignado_psic=d2.id  order by reportes_vd.id desc limit 20";
	} else {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, reportes_vd.recepcion, 
		reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable as d2res, reportes_vd.atendido, reportes_vd.ubicacion 
		from reportes_vd INNER JOIN  departamentos d1 ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 
		ON reportes_vd.asignado_psic=d2.id where reportes_vd.folio like '%$buscar%' OR reportes_vd.fecha like '%$buscar%' 
		OR reportes_vd.recepcion like '%$buscar%' OR reportes_vd.persona_reporte like '%$buscar%' OR d1.responsable 
		like '%$buscar%'or d2.responsable like '%$buscar%' or reportes_vd.nom_nna like '%$buscar%' or reportes_vd.ubicacion 
		like '%$buscar%' order by reportes_vd.id desc";
	}
	}else if ($estRep==5) {
	if (empty($buscar)) {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, reportes_vd.recepcion, 
		reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable as d2res, reportes_vd.atendido, reportes_vd.ubicacion 
		from reportes_vd INNER JOIN  departamentos d1 ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 
		ON reportes_vd.asignado_psic=d2.id where reportes_vd.asignado=0 order by reportes_vd.id desc limit 20 ";
	} else {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, reportes_vd.recepcion, reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable as d2res, reportes_vd.atendido, reportes_vd.ubicacion from reportes_vd INNER JOIN  departamentos d1 ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 ON reportes_vd.asignado_psic=d2.id where reportes_vd.asignado=0 and (reportes_vd.folio like '%$buscar%' OR reportes_vd.fecha like '%$buscar%' OR reportes_vd.recepcion like '%$buscar%' OR reportes_vd.persona_reporte like '%$buscar%' OR d1.responsable like '%$buscar%'or d2.responsable like '%$buscar%' or reportes_vd.nom_nna like '%$buscar%' or reportes_vd.ubicacion like '%$buscar%') order by reportes_vd.id desc";
	}
	}else {
	if (empty($buscar)) {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, 
		reportes_vd.recepcion, reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable 
		as d2res, reportes_vd.atendido, reportes_vd.ubicacion from reportes_vd INNER JOIN  departamentos d1 
		ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 ON reportes_vd.asignado_psic=d2.id 
		where reportes_vd.atendido='$estRep' and reportes_vd.asignado!=0 order by reportes_vd.id desc limit 20";
	} else {
		$query="SELECT reportes_vd.id, reportes_vd.folio, reportes_vd.fecha, reportes_vd.nom_nna, reportes_vd.recepcion, 
		reportes_vd.persona_reporte, d1.responsable as d1res, d2.responsable as d2res, reportes_vd.atendido, reportes_vd.ubicacion 
		from reportes_vd INNER JOIN  departamentos d1 ON reportes_vd.asignado=d1.id INNER JOIN departamentos d2 
		ON reportes_vd.asignado_psic=d2.id where reportes_vd.atendido='$estRep' and reportes_vd.asignado!=0 
		and (reportes_vd.folio like '%$buscar%' OR reportes_vd.fecha like '%$buscar%' OR reportes_vd.recepcion 
		like '%$buscar%' OR reportes_vd.persona_reporte like '%$buscar%' OR d1.responsable like '%$buscar%' 
		or d2.responsable like '%$buscar%' or reportes_vd.nom_nna like '%$buscar%' or reportes_vd.ubicacion 
		like '%$buscar%') order by reportes_vd.id desc";
	}
	}
		
	$resultado=$mysqli->query($query);
	
	$rows=$resultado->num_rows;
	echo "Resultados: ".$rows;

 while($row=$resultado->fetch_assoc()){ ?>
					
						<tr>
							<td><a href="perfil_posible_caso.php?idPosibleCaso=<?php echo $row['id'];?>"><?php echo $row['folio'];?></a></td>
							<td|


							><?php echo $row['fecha'];?></td>
							<td><?php echo $row['nom_nna'];?></td>							
							<td><?php echo $row['ubicacion'];?></td>
							<td><?php  echo $row['persona_reporte']; ?></td>
							<td>
								<?php $id_asi=$row['d1res']; 
								
								if (is_null($id_asi)) { ?>
										<?php if ($rows2=='0') { ?>
												<?php if ($reva='1') { ?>
													<input type="button" class="special button fit small" name="No asignado" value="Asignar" >
												<?php }else{ ?>
													<input type="button" class="special button fit small"  value="No asignado" >
										<?php } }else{ ?>
										<input type="button" class="special button fit small" name="Asignar" value="No asignado" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $row['id'];?>'">
										
								<?php }}else { ?>  <?php  echo $row['d1res']; } ?>
							</td>
							<td><?php $id_asip=$row['d2res']; 
								
								if (is_null($id_asip)) { ?>
										<?php if ($rows2=='0') { ?>
												<?php if ($reva='1') { ?>
													<input type="button" class="special button fit small" name="No asignado" value="Asignar" >
												<?php }else{ ?>
													<input type="button" class="special button fit small"  value="No asignado" >
										<?php } }else{ ?>
										<input type="button" class="special button fit small" name="Asignar" value="No asignado" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $row['id'];?>'">
										
								<?php }}else { ?>  <?php  echo $row['d2res']; } ?></td>
							<td align="center" valign="middle">
								<?php $atend=$row['atendido'];
									  
									if ($atend=='1') { ?>
									   	<img src="images/advertencia.png" width="50px" height="50px">
									<?php }else if ($atend=='2') { ?>
										<img src="images/proceso.png" width="65px" height="65px">
									<?php }else if ($atend=='3') { ?>
										<img src="images/Anegativo.png" width="65px" height="65px">
									<?php }else if ($atend=='4') { ?>
									  	<img src="images/Apositivo.png" width="65px" height="65px"	>
									 <?php } ?>
							</td>							
						</tr>
								<?php } ?>
				</tbody>
			</table>	
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