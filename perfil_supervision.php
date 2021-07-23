<?php	
	session_start();
	require 'conexion.php';	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idS=$_GET['id'];
	$fecha= date ("j/n/Y");

	$pc="SELECT id_centro from supervisiones where id='$idS'";
	$epc=$mysqli->query($pc);
	while ($row=$epc->fetch_assoc()) {
		$idC=$row['id_centro'];
	}
	$super="SELECT centros.nombre as nomC, supervisiones.folio, supervisiones.fecha_sup, motivoscas.motivo, supervisiones.cargo, supervisiones.nombre, supervisiones.ap_paterno, supervisiones.ap_materno, supervisiones.t_identificacion, supervisiones.material, supervisiones.cantObs, supervisiones.notas FROM supervisiones, centros, motivoscas where supervisiones.id='$idS' and supervisiones.id_centro=centros.id and supervisiones.id_motivo=motivoscas.id";
	$esuper=$mysqli->query($super);
	
	$obs="SELECT observaciones_sup.id, autoridadescas.autoridad, observaciones_sup.observacion, observaciones_sup.recomendacion, observaciones_sup.tipo, observaciones_sup.temporalidad, observaciones_sup.atendida from observaciones_sup, autoridadescas where observaciones_sup.id_sup='$idS' and observaciones_sup.id_auto=autoridadescas.id";
	$eobs=$mysqli->query($obs);
	$can=$eobs->num_rows;

	$lautoridad="SELECT autoridadescas.id, autoridadescas.autoridad from autoridadescas, autoridad_sup where autoridad_sup.id_supervision='$idS' and autoridad_sup.id_autoridad=autoridadescas.id";
	$elautoridad=$mysqli->query($lautoridad);

	if(!empty($_POST['agregar']))
	{
		$autoridad = $_POST['autoridad'];
		$observacion = mysqli_real_escape_string($mysqli,$_POST['observacion']);
		$recomendacion = mysqli_real_escape_string($mysqli,$_POST['recomendacion']);
		$temporalidad = mysqli_real_escape_string($mysqli,$_POST['temporalidad']);
		$tipo = $_POST['tipo'];
		
		$obser="INSERT INTO observaciones_sup (id_sup, id_auto, observacion, recomendacion, tipo, temporalidad, fecha_reg, respo_reg, atendida) VALUES ('$idS','$autoridad','$observacion','$recomendacion','$tipo','$temporalidad','$fecha','$idDEPTO','0')";
		$eobser=$mysqli->query($obser);
		
		header("Location: perfil_supervision.php?id=$idS");
	}

	if(!empty($_POST['cerrar']))
	{
		$actuO="UPDATE supervisiones set cantObs='$can' where id='$idS'";
		$eactuO=$mysqli->query($actuO);
		header("Location: perfil_supervision.php?id=$idS");

	}

	if (!empty($_POST['g_notas'])) 
	{	
		$notas = $_POST['notas'];
		$actuN="UPDATE supervisiones set notas='$notas', fecha_regn='$fecha', respo_regn='$idDEPTO' where id='$idS'";
		$eactuN=$mysqli->query($actuN);
		header("Location: perfil_supervision.php?id=$idS");		
	}
	
	$valS="SELECT id, tipo, sancion, fecha_reg from sanciones_sup where id_sup='$idS'";
	$evalS=$mysqli->query($valS);
	$numS=$evalS->num_rows;

	if (!empty($_POST['g_sancion'])) {
		
		$tipo=$_POST['tipo'];
		$sancion=$_POST['sancion'];

		$agS="INSERT INTO sanciones_sup (id_sup, tipo, sancion, respo_reg, fecha_reg) VALUES ('$idS','$tipo','$sancion','$idDEPTO','$fecha')";
		$eagS=$mysqli->query($agS);
		header("Location: perfil_supervision.php?id=$idS");	
	}

	if (!empty($_POST['no'])) {
		$idO= mysqli_real_escape_string($mysqli,$_POST['idO']);
		$acO="UPDATE observaciones_sup set atendida='1' where id='$idO'";
		$eacO=$mysqli->query($acO);
		header("Location: perfil_supervision.php?id=$idS");		
	}

	if (!empty($_POST['si'])) {
		$idO= mysqli_real_escape_string($mysqli,$_POST['idO']);
		$acO="UPDATE observaciones_sup set atendida='0' where id='$idO'";
		$eacO=$mysqli->query($acO);
		header("Location: perfil_supervision.php?id=$idS");		
	}

	$vald="SELECT id from departamentos where id='$idDEPTO' and (id_depto='13' or id_depto='16')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Perfil Supervision</title>
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
							<div class="box alt" align="center">
								<div class="row 10% uniform">
									<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
									<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
									<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
								</div>
							</div>								
							<input type="button" value="atras" name="" class="button special small" onclick="location='lista_supervisionCAS.php?id=<?php echo $idC; ?>'">
							<div class="uniform row">
								<?php while ($row=$esuper->fetch_assoc()) { ?>								
								<div class="5u 12u$(xsmall)">
									Supervision realizada en fecha: <strong><?php echo $row['fecha_sup']; ?></strong>
									<br>
									Folio: <strong><?php echo $row['folio']; ?></strong>
									<br>
									Centro: <strong><?php echo $row['nomC']; ?></strong>
									<br>
									Motivo: <strong><?php echo $row['motivo']; ?></strong>
									<br><br>
									<h4>Personal del CAS que atendio la visita</h4>
								
									Cargo: <strong><?php echo $row['cargo']; ?></strong>
									<br>
									Nombre: <strong><?php echo $row['nombre'].' '.$row['ap_paterno'].' '.$row['ap_materno']; ?></strong>
									<br>
									Identificación: <strong><?php echo $row['t_identificacion']; ?></strong>
									<br>
									¿Cuenta con material soporte de las visitas? <strong><?php echo $row['material']; ?></strong>
								</div>
								<?php $valBtn=$row['cantObs'];
										$nots=$row['notas']; } ?>
								<?php if (empty($valBtn)) { ?>
								<div class="7u">
									<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
										<div class="box">Agregar Observaciones y Recomendaciones
			 								<div class="row uniform">
												<div class="12u">
													<div class="row uniform">
														<div class="12u">AUTORIDAD
															<div class="select-wrapper">
																<select id="autoridad" name="autoridad" required>
																<?php while ($row=$elautoridad->fetch_assoc()) { ?>
																<option value="<?php echo $row['id']; ?>"><?php echo $row['autoridad']; ?></option>
																<?php } ?>
																</select>
															</div>
														</div>
														<div class="4u">OBSERVACION
															<textarea name="observacion" rows="1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
														</div>
														<div class="4u">RECOMENDACION
															<textarea name="recomendacion" rows="1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
														</div>
														<div class="4u">TIPO DE RECOMENDACION
															<div class="select-wrapper">
																<select id="tipo" name="tipo" required>
																	<option value="URGENTE">URGENTE</option>
																	<option value="ATENCION INMEDIATA">ATENCION INMEDIATA</option>
																</select>												
															</div>
														</div>											
														<div class="4u">TEMPORALIDAD
															<input type="text" name="temporalidad" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
														</div>	
														<?php if ($rows2>0) { ?>
															<div class="3u">.
															<input class="button special fit small" name="agregar" type="submit" value="agregar" >
															</div>
														<?php }else {} ?>													
														
									</form>
														
									<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
														<?php if ($rows2>0) { ?>
														<div class="12u">.
															<input type="submit" class="button fit small" name="cerrar" value="no hay mas observaciones">
														</div>
														<?php }else {} ?>													
									</form>													
													</div>												
												</div>									
											</div>								
										</div>
									
								</div>
								<?php }else { ?>
									<div class="7u">
										<div class="box">
											<?php if (empty($nots)) { ?>
											<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
											<strong>Notas adicionales</strong>
											<textarea rows="4" name="notas" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
											<br>
											<?php if ($rows2>0) { ?>
											<input type="submit" class="fit small" name="g_notas" value="Guardar">
											<?php }else {} ?>													
											</form>
											<?php }else{ ?>
											<strong>Notas adicionales</strong>
											<textarea rows="4" name="notas" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $nots ?></textarea>	
											<?php } ?>
											
										</div>
									</div>									
								<?php } ?>
								<div class="12u" align="center"><h4>Observaciones y recomendaciones</h4>
								<?php if (empty($can)) { 
									echo 'No hay observaciones registradas';
								}else { ?>									
									<table class="alt">									
										<thead>
											<tr>
												<th>AUTORIDAD</th>
												<th>OBSERVACION</th>
												<th>RECOMENDACION</th>
												<th>TIPO</th>
												<th>TEMPORALIDAD</th>
												<th>ACATADA</th>									
											</tr>
										</thead>
										<tbody>
											<?php while($row=$eobs->fetch_assoc()){ ?>
											<tr>
												<td><?php echo $row['autoridad'];?></td>
												<td><?php echo $row['observacion'];?></td>
												<td><?php echo $row['recomendacion'];?></td>
												<td><?php echo $row['tipo']; ?></td>										
												<td><?php echo $row['temporalidad'];?></td>
												<td><form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
													<input type="hidden" name="idO" value="<?php echo $row['id']; ?>">
													<?php $at=$row['atendida'];
													if ($rows2>0) { 
													if ($at=='0') { ?>														
													 	<input type="submit" class="special" name="no" value="NO">
													<?php }else if($at=='1'){ ?>
														<input type="submit" class="special" name="si" value="SI">
													<?php } 
													}else { 
														if ($at=='0') { ?>														
													 	<input type="submit" class="special" value="NO" disabled>
													<?php }else if($at=='1'){ ?>
														<input type="submit" class="special" value="SI" disabled>
													<?php } } ?></form>	</td>										
											</tr>
											<?php } ?>
										</tbody>
									</table>								
								<?php } ?>
								</div>
								
								
							</div>

								<?php if (empty($nots)) { }else { ?>
									<div class="row uniform">
										<div class="6u">
											<div class="box">
												<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
												<strong>Registrar sanciones</strong>
												<br>
												<div class="select-wrapper">
													<select id="tipo" name="tipo" required>
														<option value="">TIPO</option>
														<option value="VERBAL">VERBAL</option>
														<option value="AMONESTACION">AMONESTACION</option>
														<option value="MULTA">MULTA</option>							
													</select>
												</div>
												<br>
												<textarea name="sancion" placeholder="SANCION" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
												<br>
												<?php if ($rows2>0) { ?>	
												<input type="submit" name="g_sancion" value="guardar" class="fit small">
												<?php }else {} ?>
												</form>
											</div><br>		
										</div>
										<?php if ($numS>0) { ?>
											<div class="6u">
											<table class="alt">		
												<caption>SANCIONES</caption>							
												<thead>
													<tr>
														<th>TIPO</th>
														<th>SANCION</th>
														<th>FECHA</th>														
													</tr>
												</thead>
												<tbody>
													<?php while($row=$evalS->fetch_assoc()){ ?>
													<tr>
														<td><?php echo $row['tipo'];?></td>
														<td><?php echo $row['sancion'];?></td>
														<td><?php echo $row['fecha_reg'];?></td>
													</tr>
													<?php } ?>
												</tbody>												
											</table>	
											
											</div>
										<?php }else{ echo "NO HAY SANCIONES REGISTRADAS"; } ?>
										
										<br>
									</div>
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